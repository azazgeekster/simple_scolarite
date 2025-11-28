<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ModuleGrade;
use App\Models\JustifiedAbsence;
use App\Models\Student;
use App\Models\Module;
use App\Models\Exam;
use App\Models\ExamConvocation;
use App\Models\StudentModuleEnrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\JustificationTemplateExport;
use App\Imports\JustifiedAbsencesImport;

class RattrapageConvocationController extends Controller
{
    /**
     * Display students eligible for rattrapage or with unjustified absences
     */
    public function index(Request $request)
    {
        $academicYear = $request->get('academic_year', now()->year);
        $session = $request->get('session', 'normal');
        $filiereId = $request->get('filiere_id');
        $moduleId = $request->get('module_id');

        // Get students with RATT or ABI status
        $query = ModuleGrade::with([
            'moduleEnrollment.student',
            'moduleEnrollment.module',
            'moduleEnrollment.programEnrollment.filiere'
        ])
        ->whereIn('result', ['RATT', 'ABI'])
        ->where('session', $session)
        ->whereHas('moduleEnrollment.programEnrollment', function($q) use ($academicYear) {
            $q->where('academic_year', $academicYear);
        });

        if ($filiereId) {
            $query->whereHas('moduleEnrollment.programEnrollment', function($q) use ($filiereId) {
                $q->where('filiere_id', $filiereId);
            });
        }

        if ($moduleId) {
            $query->whereHas('moduleEnrollment', function($q) use ($moduleId) {
                $q->where('module_id', $moduleId);
            });
        }

        $grades = $query->orderBy('created_at', 'desc')->paginate(50);

        // Statistics
        $stats = [
            'total_ratt' => ModuleGrade::where('result', 'RATT')->where('session', $session)->count(),
            'total_abi' => ModuleGrade::where('result', 'ABI')->where('session', $session)->count(),
            'justified_today' => JustifiedAbsence::whereDate('justified_at', today())->count(),
        ];

        return view('admin.rattrapage.index', compact('grades', 'stats', 'academicYear', 'session'));
    }

    /**
     * Justify a single absence
     */
    public function justifyAbsence(Request $request, $gradeId)
    {
        $request->validate([
            'reason' => 'nullable|string|max:1000',
            'document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $grade = ModuleGrade::findOrFail($gradeId);

        if ($grade->result !== 'ABI') {
            return back()->with('error', 'Seuls les étudiants absents peuvent être justifiés.');
        }

        if ($grade->is_absence_justified) {
            return back()->with('error', 'Cette absence est déjà justifiée.');
        }

        // Handle document upload
        $documentPath = null;
        if ($request->hasFile('document')) {
            $documentPath = $request->file('document')->store('justifications', 'public');
        }

        // Justify the absence
        $success = $grade->justifyAbsence(
            $request->input('reason'),
            $documentPath,
            auth('admin')->id()
        );

        if ($success) {
            return back()->with('success', 'Absence justifiée avec succès. Le statut est maintenant RATT.');
        }

        return back()->with('error', 'Erreur lors de la justification de l\'absence.');
    }

    /**
     * Unjustify an absence
     */
    public function unjustifyAbsence($gradeId)
    {
        $grade = ModuleGrade::findOrFail($gradeId);

        if (!$grade->is_absence_justified) {
            return back()->with('error', 'Cette absence n\'est pas justifiée.');
        }

        $success = $grade->unjustifyAbsence();

        if ($success) {
            return back()->with('success', 'Justification annulée. Le statut est maintenant ABI.');
        }

        return back()->with('error', 'Erreur lors de l\'annulation de la justification.');
    }

    /**
     * Download justification template (Excel)
     */
    public function downloadJustificationTemplate()
    {
        return Excel::download(
            new JustificationTemplateExport(),
            'template_justifications_absences.xlsx'
        );
    }

    /**
     * Bulk justify absences via Excel upload
     */
    public function bulkJustifyAbsences(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
            'academic_year' => 'required|integer',
            'session' => 'required|in:normal,rattrapage',
        ]);

        try {
            $import = new JustifiedAbsencesImport(
                $request->input('academic_year'),
                $request->input('session'),
                auth('admin')->id()
            );

            Excel::import($import, $request->file('file'));

            $summary = $import->getSummary();

            return back()->with('success', sprintf(
                'Import terminé: %d justifiées, %d échouées, %d déjà justifiées.',
                $summary['success'],
                $summary['failed'],
                $summary['already_justified']
            ))->with('import_details', $summary['details']);

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'import: ' . $e->getMessage());
        }
    }

    /**
     * Show convocation page
     */
    public function convocations(Request $request)
    {
        $academicYear = $request->get('academic_year', now()->year);
        $session = 'rattrapage'; // Only rattrapage session

        // Get exams for rattrapage session
        $exams = Exam::with('module')
            ->whereHas('examPeriod', function($q) use ($academicYear, $session) {
                $q->where('academic_year', $academicYear)
                  ->where('session_type', $session);
            })
            ->get();

        return view('admin.rattrapage.convocations', compact('exams', 'academicYear'));
    }

    /**
     * Convocate students to rattrapage exam
     * Note: This is for manually adding students who became eligible after exam creation
     */
    public function convocateToRattrapage(Request $request, $examId)
    {
        $exam = Exam::with('module')->findOrFail($examId);

        // Get all students with RATT for this module
        $eligibleGrades = ModuleGrade::with('moduleEnrollment')
            ->where('result', 'RATT')
            ->where('session', 'normal') // Students from normal session
            ->whereHas('moduleEnrollment', function($q) use ($exam) {
                $q->where('module_id', $exam->module_id);
            })
            ->get();

        $convocated = 0;
        $alreadyConvocated = 0;

        foreach ($eligibleGrades as $grade) {
            $enrollment = $grade->moduleEnrollment;
            
            // Check if already convocated
            $exists = ExamConvocation::where('exam_id', $exam->id)
                ->where('student_module_enrollment_id', $enrollment->id)
                ->exists();

            if ($exists) {
                $alreadyConvocated++;
                continue;
            }

            // Create convocation
            ExamConvocation::create([
                'exam_id' => $exam->id,
                'student_module_enrollment_id' => $enrollment->id,
                'local_id' => null, // To be assigned later
                'n_examen' => null, // To be assigned later
            ]);

            $convocated++;
        }

        return back()->with('success', sprintf(
            '%d étudiants convoqués, %d déjà convoqués.',
            $convocated,
            $alreadyConvocated
        ));
    }

    /**
     * Bulk convocate by module
     */
    public function bulkConvocateByModule(Request $request)
    {
        $request->validate([
            'exam_ids' => 'required|array',
            'exam_ids.*' => 'exists:exams,id',
        ]);

        $totalConvocated = 0;
        $totalAlready = 0;

        foreach ($request->input('exam_ids') as $examId) {
            $exam = Exam::findOrFail($examId);
            
            $eligibleGrades = ModuleGrade::with('moduleEnrollment')
                ->where('result', 'RATT')
                ->where('session', 'normal')
                ->whereHas('moduleEnrollment', function($q) use ($exam) {
                    $q->where('module_id', $exam->module_id);
                })
                ->get();

            foreach ($eligibleGrades as $grade) {
                $enrollment = $grade->moduleEnrollment;
                
                $exists = ExamConvocation::where('exam_id', $exam->id)
                    ->where('module_enrollment_id', $enrollment->id)
                    ->exists();

                if ($exists) {
                    $totalAlready++;
                    continue;
                }

                ExamConvocation::create([
                    'exam_id' => $exam->id,
                    'module_enrollment_id' => $enrollment->id,
                    'student_id' => $enrollment->programEnrollment->student_id,
                    'local_id' => null,
                    'n_examen' => null,
                    'convocation_status' => 'pending',
                ]);

                $totalConvocated++;
            }
        }

        return back()->with('success', sprintf(
            'Convocation terminée: %d étudiants convoqués, %d déjà convoqués.',
            $totalConvocated,
            $totalAlready
        ));
    }

    /**
     * Get students with RATT/ABI status for a module (AJAX endpoint)
     */
    public function getStudents(Request $request)
    {
        $moduleId = $request->get('module_id');
        $academicYear = $request->get('academic_year');
        $session = $request->get('session', 'normal');
        $resultFilter = $request->get('result'); // Optional: 'ABI' or 'RATT'

        $query = ModuleGrade::with(['moduleEnrollment.student', 'moduleEnrollment.programEnrollment'])
            ->where('session', $session)
            ->whereHas('moduleEnrollment', function($q) use ($moduleId, $academicYear) {
                $q->where('module_id', $moduleId)
                  ->whereHas('programEnrollment', function($q2) use ($academicYear) {
                      $q2->where('academic_year', $academicYear);
                  });
            });

        // Apply result filter if specified
        if ($resultFilter) {
            $query->where('result', $resultFilter);
        } else {
            // Default: show both RATT and ABI
            $query->whereIn('result', ['RATT', 'ABI']);
        }

        $grades = $query->get();

        $students = $grades->map(function($grade) {
            $student = $grade->moduleEnrollment->student;
            return [
                'grade_id' => $grade->id,
                'apogee' => $student->apogee,
                'name' => $student->first_name . ' ' . $student->last_name,
                'grade' => $grade->grade,
                'result' => $grade->result,
                'is_justified' => $grade->is_absence_justified,
            ];
        });

        return response()->json(['students' => $students]);
    }
}
