<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Exam;
use App\Models\ExamConvocation;
use App\Models\ExamPeriod;
use App\Models\Filiere;
use App\Models\GradeImportError;
use App\Models\Module;
use App\Models\ModuleGrade;
use App\Models\Reclamation;
use App\Models\ReclamationSetting;
use App\Models\Student;
use App\Models\StudentModuleEnrollment;
use App\Services\GradeBulkImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Barryvdh\DomPDF\Facade\Pdf;

class GradeManagementController extends Controller
{
    /**
     * Display grades publication dashboard.
     */
    public function index(Request $request)
    {
        $academicYears = AcademicYear::orderBy('start_year', 'desc')->get();
        $filieres = Filiere::orderBy('label_fr')->get();

        // Get selected filters
        $selectedYear = $request->get('academic_year', AcademicYear::where('is_current', true)->first()?->start_year ?? now()->year);
        $selectedFiliere = $request->get('filiere_id');
        $selectedSemester = $request->get('semester');
        $selectedSession = $request->get('session', 'normal');

        // Build query for grade statistics
        $query = ModuleGrade::query()
            ->join('student_module_enrollments', 'module_grades.module_enrollment_id', '=', 'student_module_enrollments.id')
            ->join('student_program_enrollments', 'student_module_enrollments.program_enrollment_id', '=', 'student_program_enrollments.id')
            ->join('modules', 'student_module_enrollments.module_id', '=', 'modules.id')
            ->where('student_program_enrollments.academic_year', $selectedYear)
            ->where('module_grades.session', $selectedSession);

        if ($selectedFiliere) {
            $query->where('student_program_enrollments.filiere_id', $selectedFiliere);
        }

        if ($selectedSemester) {
            $query->where('student_module_enrollments.semester', $selectedSemester);
        }

        // Get statistics
        $stats = [
            'total' => (clone $query)->count(),
            'published' => (clone $query)->where('module_grades.is_published', true)->count(),
            'unpublished' => (clone $query)->where('module_grades.is_published', false)->count(),
            'passed' => (clone $query)->where('module_grades.is_published', true)->where('module_grades.result', 'validé')->count(),
            'failed' => (clone $query)->where('module_grades.is_published', true)->where('module_grades.result', 'non validé')->count(),
        ];

        // Get modules with grade counts for the table
        $modules = Module::query()
            ->select([
                'modules.id',
                'modules.code',
                'modules.label',
                'modules.semester',
                'modules.filiere_id',
                'filieres.label_fr as filiere_name',
                DB::raw('COUNT(module_grades.id) as total_grades'),
                DB::raw('SUM(CASE WHEN module_grades.is_published = 1 THEN 1 ELSE 0 END) as published_grades'),
                DB::raw('SUM(CASE WHEN module_grades.is_published = 0 THEN 1 ELSE 0 END) as unpublished_grades'),
                DB::raw('AVG(CASE WHEN module_grades.is_published = 1 THEN module_grades.grade ELSE NULL END) as avg_grade'),
                DB::raw('SUM(CASE WHEN module_grades.result = "RATT" THEN 1 ELSE 0 END) as ratt_count'),
                DB::raw('SUM(CASE WHEN module_grades.result = "ABI" THEN 1 ELSE 0 END) as abi_count'),
                DB::raw('SUM(CASE WHEN module_grades.is_absence_justified = 1 THEN 1 ELSE 0 END) as justified_count'),
            ])
            ->join('filieres', 'modules.filiere_id', '=', 'filieres.id')
            ->join('student_module_enrollments', 'modules.id', '=', 'student_module_enrollments.module_id')
            ->join('student_program_enrollments', 'student_module_enrollments.program_enrollment_id', '=', 'student_program_enrollments.id')
            ->join('module_grades', function ($join) use ($selectedSession) {
                $join->on('student_module_enrollments.id', '=', 'module_grades.module_enrollment_id')
                    ->where('module_grades.session', '=', $selectedSession);
            })
            ->where('student_program_enrollments.academic_year', $selectedYear)
            ->when($selectedFiliere, fn($q) => $q->where('modules.filiere_id', $selectedFiliere))
            ->when($selectedSemester, fn($q) => $q->where('modules.semester', $selectedSemester))
            ->groupBy('modules.id', 'modules.code', 'modules.label', 'modules.semester', 'modules.filiere_id', 'filieres.label_fr')
            ->orderBy('modules.semester')
            ->orderBy('modules.code')
            ->paginate(20)
            ->withQueryString();

        // Get reclamation settings for these modules
        $moduleIds = $modules->pluck('id')->toArray();
        $reclamationSettings = ReclamationSetting::where('academic_year', $selectedYear)
            ->where('session', $selectedSession)
            ->whereIn('module_id', $moduleIds)
            ->get()
            ->keyBy('module_id');

        return view('admin.grades.index', compact(
            'academicYears',
            'filieres',
            'selectedYear',
            'selectedFiliere',
            'selectedSemester',
            'selectedSession',
            'stats',
            'modules',
            'reclamationSettings'
        ));
    }

    /**
     * Publish grades for a specific module.
     */
    public function publishModule(Request $request, $moduleId)
    {
        $validated = $request->validate([
            'session' => 'required|in:normal,rattrapage',
            'academic_year' => 'required|integer',
        ]);

        try {
            $count = ModuleGrade::query()
                ->join('student_module_enrollments', 'module_grades.module_enrollment_id', '=', 'student_module_enrollments.id')
                ->join('student_program_enrollments', 'student_module_enrollments.program_enrollment_id', '=', 'student_program_enrollments.id')
                ->where('student_module_enrollments.module_id', $moduleId)
                ->where('student_program_enrollments.academic_year', $validated['academic_year'])
                ->where('module_grades.session', $validated['session'])
                ->where('module_grades.is_published', false)
                ->update([
                    'module_grades.is_published' => true,
                    'module_grades.published_at' => now(),
                    'module_grades.published_by' => auth('admin')->id(),
                ]);

            return back()->with('success', "{$count} note(s) publiée(s) avec succès.");
        } catch (\Exception $e) {
            Log::error('Error publishing module grades: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la publication des notes.');
        }
    }

    /**
     * Unpublish grades for a specific module.
     */
    public function unpublishModule(Request $request, $moduleId)
    {
        $validated = $request->validate([
            'session' => 'required|in:normal,rattrapage',
            'academic_year' => 'required|integer',
        ]);

        try {
            $count = ModuleGrade::query()
                ->join('student_module_enrollments', 'module_grades.module_enrollment_id', '=', 'student_module_enrollments.id')
                ->join('student_program_enrollments', 'student_module_enrollments.program_enrollment_id', '=', 'student_program_enrollments.id')
                ->where('student_module_enrollments.module_id', $moduleId)
                ->where('student_program_enrollments.academic_year', $validated['academic_year'])
                ->where('module_grades.session', $validated['session'])
                ->where('module_grades.is_published', true)
                ->update([
                    'module_grades.is_published' => false,
                    'module_grades.published_at' => null,
                    'module_grades.published_by' => null,
                ]);

            return back()->with('success', "{$count} note(s) dépubliée(s) avec succès.");
        } catch (\Exception $e) {
            Log::error('Error unpublishing module grades: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la dépublication des notes.');
        }
    }

    /**
     * Bulk publish grades.
     */
    public function bulkPublish(Request $request)
    {
        $validated = $request->validate([
            'module_ids' => 'required|array|min:1',
            'module_ids.*' => 'exists:modules,id',
            'session' => 'required|in:normal,rattrapage',
            'academic_year' => 'required|integer',
        ]);

        try {
            $count = ModuleGrade::query()
                ->join('student_module_enrollments', 'module_grades.module_enrollment_id', '=', 'student_module_enrollments.id')
                ->join('student_program_enrollments', 'student_module_enrollments.program_enrollment_id', '=', 'student_program_enrollments.id')
                ->whereIn('student_module_enrollments.module_id', $validated['module_ids'])
                ->where('student_program_enrollments.academic_year', $validated['academic_year'])
                ->where('module_grades.session', $validated['session'])
                ->where('module_grades.is_published', false)
                ->update([
                    'module_grades.is_published' => true,
                    'module_grades.published_at' => now(),
                    'module_grades.published_by' => auth('admin')->id(),
                ]);

            return back()->with('success', "{$count} note(s) publiée(s) avec succès.");
        } catch (\Exception $e) {
            Log::error('Error bulk publishing grades: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la publication en masse.');
        }
    }

    /**
     * Display reclamations management.
     */
    public function reclamations(Request $request)
    {
        $query = Reclamation::with([
            'moduleGrade.moduleEnrollment.student',
            'moduleGrade.moduleEnrollment.module',
            'moduleGrade.moduleEnrollment.programEnrollment.filiere',
            'moduleGrade.moduleEnrollment.programEnrollment.academicYear',
        ]);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by academic year
        if ($request->filled('academic_year')) {
            $query->whereHas('moduleGrade.moduleEnrollment.programEnrollment', function ($q) use ($request) {
                $q->where('academic_year', $request->academic_year);
            });
        }

        // Filter by session
        if ($request->filled('session')) {
            $query->where('session', $request->session);
        }

        // Filter by reclamation type
        if ($request->filled('type')) {
            $query->where('reclamation_type', $request->type);
        }

        // Search by student name or CNE
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('moduleGrade.moduleEnrollment.student', function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                    ->orWhere('prenom', 'like', "%{$search}%")
                    ->orWhere('cne', 'like', "%{$search}%")
                    ->orWhere('apogee', 'like', "%{$search}%");
            });
        }

        // Get statistics
        $stats = [
            'total' => Reclamation::count(),
            'pending' => Reclamation::where('status', 'PENDING')->count(),
            'under_review' => Reclamation::where('status', 'UNDER_REVIEW')->count(),
            'resolved' => Reclamation::where('status', 'RESOLVED')->count(),
            'rejected' => Reclamation::where('status', 'REJECTED')->count(),
        ];

        $reclamations = $query->latest()->paginate(20)->withQueryString();

        $academicYears = AcademicYear::orderBy('start_year', 'desc')->get();

        $reclamationTypes = [
            'grade_calculation_error' => 'Erreur de calcul',
            'missing_grade' => 'Note manquante',
            'transcription_error' => 'Erreur de transcription',
            'exam_paper_review' => 'Révision de copie',
            'other' => 'Autre',
        ];

        return view('admin.grades.reclamations', compact(
            'reclamations',
            'stats',
            'academicYears',
            'reclamationTypes'
        ));
    }

    /**
     * Show reclamation details.
     */
    public function showReclamation($id)
    {
        $reclamation = Reclamation::with([
            'moduleGrade.moduleEnrollment.student',
            'moduleGrade.moduleEnrollment.module.professor',
            'moduleGrade.moduleEnrollment.programEnrollment.filiere',
            'moduleGrade.moduleEnrollment.programEnrollment.academicYear',
        ])->findOrFail($id);

        return view('admin.grades.reclamation-show', compact('reclamation'));
    }

    /**
     * Mark reclamation as under review.
     */
    public function reviewReclamation(Request $request, $id)
    {
        $reclamation = Reclamation::findOrFail($id);

        if ($reclamation->status !== 'PENDING') {
            return back()->with('error', 'Cette réclamation ne peut pas être mise en révision.');
        }

        $validated = $request->validate([
            'admin_response' => 'nullable|string|max:1000',
        ]);

        try {
            $reclamation->markAsUnderReview($validated['admin_response'] ?? 'Réclamation prise en charge.');

            return back()->with('success', 'Réclamation mise en révision.');
        } catch (\Exception $e) {
            Log::error('Error reviewing reclamation: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la mise en révision.');
        }
    }

    /**
     * Resolve reclamation.
     */
    public function resolveReclamation(Request $request, $id)
    {
        $reclamation = Reclamation::findOrFail($id);

        if (!in_array($reclamation->status, ['PENDING', 'UNDER_REVIEW'])) {
            return back()->with('error', 'Cette réclamation ne peut pas être résolue.');
        }

        $validated = $request->validate([
            'revised_grade' => 'required|numeric|min:0|max:20',
            'admin_response' => 'required|string|min:10|max:1000',
        ]);

        try {
            DB::transaction(function () use ($reclamation, $validated) {
                // Resolve the reclamation
                $reclamation->resolve($validated['revised_grade'], $validated['admin_response']);

                // Update the actual grade
                $moduleGrade = $reclamation->moduleGrade;
                $moduleGrade->grade = $validated['revised_grade'];

                // Recalculate result based on new grade
                if ($validated['revised_grade'] >= 10) {
                    $moduleGrade->result = $moduleGrade->session === 'rattrapage' ? 'validé après rattrapage' : 'validé';
                } else {
                    $moduleGrade->result = $moduleGrade->session === 'normal' ? 'en attente rattrapage' : 'non validé';
                }

                $moduleGrade->save();

                // Recalculate final grade on enrollment
                $moduleGrade->moduleEnrollment->calculateFinalGrade();
            });

            return back()->with('success', 'Réclamation résolue avec succès. Note mise à jour.');
        } catch (\Exception $e) {
            Log::error('Error resolving reclamation: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la résolution de la réclamation.');
        }
    }

    /**
     * Reject reclamation.
     */
    public function rejectReclamation(Request $request, $id)
    {
        $reclamation = Reclamation::findOrFail($id);

        if (!in_array($reclamation->status, ['PENDING', 'UNDER_REVIEW'])) {
            return back()->with('error', 'Cette réclamation ne peut pas être rejetée.');
        }

        $validated = $request->validate([
            'admin_response' => 'required|string|min:10|max:1000',
        ]);

        try {
            $reclamation->reject($validated['admin_response']);

            return back()->with('success', 'Réclamation rejetée.');
        } catch (\Exception $e) {
            Log::error('Error rejecting reclamation: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors du rejet de la réclamation.');
        }
    }

    /**
     * Bulk update reclamations status.
     */
    public function bulkUpdateReclamations(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:reclamations,id',
            'action' => 'required|in:review,reject',
            'admin_response' => 'required|string|min:5|max:1000',
        ]);

        try {
            $count = 0;
            foreach ($validated['ids'] as $id) {
                $reclamation = Reclamation::find($id);
                if (!$reclamation) continue;

                if ($validated['action'] === 'review' && $reclamation->status === 'PENDING') {
                    $reclamation->markAsUnderReview($validated['admin_response']);
                    $count++;
                } elseif ($validated['action'] === 'reject' && \in_array($reclamation->status, ['PENDING', 'UNDER_REVIEW'])) {
                    $reclamation->reject($validated['admin_response']);
                    $count++;
                }
            }

            $actionLabel = $validated['action'] === 'review' ? 'mise(s) en révision' : 'rejetée(s)';
            return back()->with('success', "{$count} réclamation(s) {$actionLabel}.");
        } catch (\Exception $e) {
            Log::error('Error bulk updating reclamations: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la mise à jour en masse.');
        }
    }

    /**
     * Export reclamations to Excel with filters.
     */
    public function exportReclamations(Request $request)
    {
        $validated = $request->validate([
            'academic_year' => 'nullable|integer',
            'session' => 'nullable|in:normal,rattrapage',
            'filiere_id' => 'nullable|exists:filieres,id',
            'semester' => 'nullable|string',
            'module_id' => 'nullable|exists:modules,id',
            'status' => 'nullable|in:PENDING,UNDER_REVIEW,RESOLVED,REJECTED',
        ]);

        try {
            $query = Reclamation::with([
                'moduleGrade.moduleEnrollment.student',
                'moduleGrade.moduleEnrollment.module.filiere',
                'moduleGrade.moduleEnrollment.programEnrollment',
                'corrector',
            ]);

            // Apply filters
            if ($request->filled('academic_year')) {
                $query->whereHas('moduleGrade.moduleEnrollment.programEnrollment', function ($q) use ($request) {
                    $q->where('academic_year', $request->academic_year);
                });
            }

            if ($request->filled('session')) {
                $query->where('session', $request->session);
            }

            if ($request->filled('filiere_id')) {
                $query->whereHas('moduleGrade.moduleEnrollment.programEnrollment', function ($q) use ($request) {
                    $q->where('filiere_id', $request->filiere_id);
                });
            }

            if ($request->filled('semester')) {
                $query->whereHas('moduleGrade.moduleEnrollment', function ($q) use ($request) {
                    $q->where('semester', $request->semester);
                });
            }

            if ($request->filled('module_id')) {
                $query->whereHas('moduleGrade.moduleEnrollment', function ($q) use ($request) {
                    $q->where('module_id', $request->module_id);
                });
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            $reclamations = $query->latest()->get();

            // Create spreadsheet
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Réclamations');

            // Headers
            $headers = [
                'Référence', 'Date Demande', 'CNE', 'Apogée', 'Nom', 'Prénom',
                'Filière', 'Semestre', 'Module Code', 'Module Libellé',
                'Session', 'Type Réclamation', 'Motif', 'Note Originale',
                'Note Corrigée', 'Statut', 'Réponse Admin', 'Corrigé Par',
                'Date Correction'
            ];

            $col = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue($col . '1', $header);
                $col++;
            }

            // Style header
            $headerStyle = $sheet->getStyle('A1:S1');
            $headerStyle->getFont()->setBold(true)->setSize(11);
            $headerStyle->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FF4F81BD');
            $headerStyle->getFont()->getColor()->setARGB('FFFFFFFF');
            $headerStyle->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $headerStyle->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

            // Data rows
            $row = 2;
            foreach ($reclamations as $reclamation) {
                $student = $reclamation->moduleGrade?->moduleEnrollment?->student;
                $module = $reclamation->moduleGrade?->moduleEnrollment?->module;
                $filiere = $module?->filiere;
                $semester = $reclamation->moduleGrade?->moduleEnrollment?->semester;

                $sheet->setCellValue('A' . $row, $reclamation->reference);
                $sheet->setCellValue('B' . $row, $reclamation->created_at?->format('Y-m-d H:i'));
                $sheet->setCellValue('C' . $row, $student?->cne);
                $sheet->setCellValue('D' . $row, $student?->apogee);
                $sheet->setCellValue('E' . $row, $student?->nom);
                $sheet->setCellValue('F' . $row, $student?->prenom);
                $sheet->setCellValue('G' . $row, $filiere?->label_fr);
                $sheet->setCellValue('H' . $row, $semester);
                $sheet->setCellValue('I' . $row, $module?->code);
                $sheet->setCellValue('J' . $row, $module?->label);
                $sheet->setCellValue('K' . $row, ucfirst($reclamation->session));
                $sheet->setCellValue('L' . $row, $reclamation->getReclamationTypeLabel());
                $sheet->setCellValue('M' . $row, $reclamation->reason);
                $sheet->setCellValue('N' . $row, $reclamation->original_grade);
                $sheet->setCellValue('O' . $row, $reclamation->revised_grade);
                $sheet->setCellValue('P' . $row, $reclamation->getStatusLabel());
                $sheet->setCellValue('Q' . $row, $reclamation->admin_response);
                $sheet->setCellValue('R' . $row, $reclamation->corrector?->name);
                $sheet->setCellValue('S' . $row, $reclamation->corrected_at?->format('Y-m-d H:i'));

                // Apply borders
                $sheet->getStyle('A' . $row . ':S' . $row)
                    ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

                $row++;
            }

            // Auto-size columns
            foreach (range('A', 'S') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            // Freeze first row
            $sheet->freezePane('A2');

            // Generate filename
            $filters = [];
            if ($request->filled('session')) $filters[] = $request->session;
            if ($request->filled('academic_year')) $filters[] = $request->academic_year;
            $filterStr = !empty($filters) ? '_' . implode('_', $filters) : '';
            $filename = 'reclamations' . $filterStr . '_' . date('Y-m-d_His') . '.xlsx';

            // Output
            $writer = new Xlsx($spreadsheet);
            $temp = tempnam(sys_get_temp_dir(), 'reclamations');
            $writer->save($temp);

            return response()->download($temp, $filename, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ])->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            Log::error('Error exporting reclamations: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de l\'exportation: ' . $e->getMessage());
        }
    }

    /**
     * Download reclamations template for bulk upload.
     */
    public function downloadReclamationsTemplate(Request $request)
    {
        $validated = $request->validate([
            'academic_year' => 'required|integer',
            'session' => 'required|in:normal,rattrapage',
            'filiere_id' => 'nullable|exists:filieres,id',
            'semester' => 'nullable|string',
            'module_id' => 'nullable|exists:modules,id',
        ]);

        try {
            $query = Reclamation::with([
                'moduleGrade.moduleEnrollment.student',
                'moduleGrade.moduleEnrollment.module.filiere',
                'moduleGrade.moduleEnrollment.programEnrollment',
            ]);

            // Apply filters
            $query->whereHas('moduleGrade.moduleEnrollment.programEnrollment', function ($q) use ($validated) {
                $q->where('academic_year', $validated['academic_year']);
            });

            $query->where('session', $validated['session']);

            if ($request->filled('filiere_id')) {
                $query->whereHas('moduleGrade.moduleEnrollment.programEnrollment', function ($q) use ($request) {
                    $q->where('filiere_id', $request->filiere_id);
                });
            }

            if ($request->filled('semester')) {
                $query->whereHas('moduleGrade.moduleEnrollment', function ($q) use ($request) {
                    $q->where('semester', $request->semester);
                });
            }

            if ($request->filled('module_id')) {
                $query->whereHas('moduleGrade.moduleEnrollment', function ($q) use ($request) {
                    $q->where('module_id', $request->module_id);
                });
            }

            $reclamations = $query->latest()->get();

            // Create spreadsheet
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Headers
            $headers = [
                'ID (NE PAS MODIFIER)', 'Référence', 'CNE', 'Nom', 'Prénom',
                'Module', 'Note Originale', 'Note Corrigée', 'Statut (RESOLVED/REJECTED)',
                'Réponse Admin'
            ];

            $col = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue($col . '1', $header);
                $col++;
            }

            // Style header
            $headerStyle = $sheet->getStyle('A1:J1');
            $headerStyle->getFont()->setBold(true);
            $headerStyle->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FFCCCCCC');

            // Data rows
            $row = 2;
            foreach ($reclamations as $reclamation) {
                $student = $reclamation->moduleGrade?->moduleEnrollment?->student;
                $module = $reclamation->moduleGrade?->moduleEnrollment?->module;

                $sheet->setCellValue('A' . $row, $reclamation->id);
                $sheet->setCellValue('B' . $row, $reclamation->reference);
                $sheet->setCellValue('C' . $row, $student?->cne);
                $sheet->setCellValue('D' . $row, $student?->nom);
                $sheet->setCellValue('E' . $row, $student?->prenom);
                $sheet->setCellValue('F' . $row, $module?->code . ' - ' . $module?->label);
                $sheet->setCellValue('G' . $row, $reclamation->original_grade);
                $sheet->setCellValue('H' . $row, $reclamation->revised_grade ?? '');
                $sheet->setCellValue('I' . $row, $reclamation->status);
                $sheet->setCellValue('J' . $row, $reclamation->admin_response ?? '');

                // Lock ID column
                $sheet->getStyle('A' . $row)->getProtection()->setLocked(true);

                $row++;
            }

            // Auto-size columns
            foreach (range('A', 'J') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            $filename = 'reclamations_template_' . $validated['session'] . '_' . $validated['academic_year'] . '.xlsx';

            $writer = new Xlsx($spreadsheet);
            $temp = tempnam(sys_get_temp_dir(), 'reclamations_template');
            $writer->save($temp);

            return response()->download($temp, $filename, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ])->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            Log::error('Error downloading template: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors du téléchargement du modèle: ' . $e->getMessage());
        }
    }

    /**
     * Import reclamations corrections from Excel.
     */
    public function importReclamations(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:10240',
        ]);

        try {
            $spreadsheet = IOFactory::load($request->file('file')->getPathname());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            // Skip header row
            array_shift($rows);

            $updated = 0;
            $errors = [];

            DB::beginTransaction();

            foreach ($rows as $index => $row) {
                $rowNum = $index + 2;

                if (empty($row[0])) {
                    continue;
                }

                $reclamationId = $row[0];
                $revisedGrade = $row[7];
                $status = trim(strtoupper($row[8] ?? ''));
                $adminResponse = $row[9] ?? '';

                // Validate status
                if (!in_array($status, ['RESOLVED', 'REJECTED', 'UNDER_REVIEW', 'PENDING'])) {
                    $errors[] = "Ligne {$rowNum}: Statut invalide '{$status}'";
                    continue;
                }

                $reclamation = Reclamation::find($reclamationId);
                if (!$reclamation) {
                    $errors[] = "Ligne {$rowNum}: Réclamation non trouvée (ID: {$reclamationId})";
                    continue;
                }

                // Update based on status
                if ($status === 'RESOLVED') {
                    if (empty($revisedGrade)) {
                        $errors[] = "Ligne {$rowNum}: Note corrigée requise pour RESOLVED";
                        continue;
                    }

                    $gradeValue = floatval($revisedGrade);
                    if ($gradeValue < 0 || $gradeValue > 20) {
                        $errors[] = "Ligne {$rowNum}: Note invalide ({$gradeValue})";
                        continue;
                    }

                    // Resolve the reclamation
                    $reclamation->update([
                        'status' => 'RESOLVED',
                        'revised_grade' => $gradeValue,
                        'admin_response' => $adminResponse,
                        'corrected_by' => auth('admin')->id(),
                        'corrected_at' => now(),
                    ]);

                    // Update the actual grade
                    $moduleGrade = $reclamation->moduleGrade;
                    if ($moduleGrade) {
                        $moduleGrade->grade = $gradeValue;
                        if ($gradeValue >= 10) {
                            $moduleGrade->result = $moduleGrade->session === 'rattrapage' ? 'validé après rattrapage' : 'validé';
                        } else {
                            $moduleGrade->result = $moduleGrade->session === 'normal' ? 'en attente rattrapage' : 'non validé';
                        }
                        $moduleGrade->save();
                        $moduleGrade->moduleEnrollment->calculateFinalGrade();
                    }

                    $updated++;

                } elseif ($status === 'REJECTED') {
                    if (empty($adminResponse)) {
                        $errors[] = "Ligne {$rowNum}: Réponse admin requise pour REJECTED";
                        continue;
                    }

                    $reclamation->update([
                        'status' => 'REJECTED',
                        'admin_response' => $adminResponse,
                        'corrected_by' => auth('admin')->id(),
                        'corrected_at' => now(),
                    ]);

                    $updated++;
                } else {
                    // Update other fields if changed
                    $reclamation->update([
                        'status' => $status,
                        'admin_response' => $adminResponse ?: $reclamation->admin_response,
                    ]);
                    $updated++;
                }
            }

            DB::commit();

            $message = "{$updated} réclamation(s) mise(s) à jour.";
            if (count($errors) > 0) {
                $message .= " " . count($errors) . " erreur(s).";
            }

            return back()->with('success', $message)->with('import_errors', $errors);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error importing reclamations: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de l\'importation: ' . $e->getMessage());
        }
    }

    /**
     * Download reclamations PV (Procès-Verbal).
     */
    public function downloadReclamationsPV(Request $request)
    {
        $validated = $request->validate([
            'academic_year' => 'required|integer',
            'session' => 'required|in:normal,rattrapage',
            'filiere_id' => 'nullable|exists:filieres,id',
            'semester' => 'nullable|string',
            'module_id' => 'nullable|exists:modules,id',
        ]);

        try {
            $query = Reclamation::with([
                'moduleGrade.moduleEnrollment.student',
                'moduleGrade.moduleEnrollment.module.filiere',
                'moduleGrade.moduleEnrollment.programEnrollment',
                'corrector',
            ]);

            $query->whereHas('moduleGrade.moduleEnrollment.programEnrollment', function ($q) use ($validated) {
                $q->where('academic_year', $validated['academic_year']);
            });

            $query->where('session', $validated['session']);

            if ($request->filled('filiere_id')) {
                $query->whereHas('moduleGrade.moduleEnrollment.programEnrollment', function ($q) use ($request) {
                    $q->where('filiere_id', $request->filiere_id);
                });
            }

            if ($request->filled('semester')) {
                $query->whereHas('moduleGrade.moduleEnrollment', function ($q) use ($request) {
                    $q->where('semester', $request->semester);
                });
            }

            if ($request->filled('module_id')) {
                $query->whereHas('moduleGrade.moduleEnrollment', function ($q) use ($request) {
                    $q->where('module_id', $request->module_id);
                });
            }

            $reclamations = $query->latest()->get();

            $academicYear = AcademicYear::where('start_year', $validated['academic_year'])->first();
            $filiere = $request->filled('filiere_id') ? Filiere::find($request->filiere_id) : null;
            $module = $request->filled('module_id') ? Module::find($request->module_id) : null;

            $stats = [
                'total' => $reclamations->count(),
                'resolved' => $reclamations->where('status', 'RESOLVED')->count(),
                'rejected' => $reclamations->where('status', 'REJECTED')->count(),
                'pending' => $reclamations->whereIn('status', ['PENDING', 'UNDER_REVIEW'])->count(),
                'grade_changed' => $reclamations->where('status', 'RESOLVED')->filter(fn($r) => $r->hasGradeChange())->count(),
            ];

            // Generate HTML from view
            $html = view('admin.grades.reclamations-pv', [
                'reclamations' => $reclamations,
                'academicYear' => $academicYear,
                'session' => $validated['session'],
                'filiere' => $filiere,
                'semester' => $request->semester,
                'module' => $module,
                'stats' => $stats,
                'generatedAt' => now(),
            ])->render();

            // Create mPDF instance with Brawler font configuration
            $defaultFontConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
            $defaultFontData = (new \Mpdf\Config\FontVariables())->getDefaults();

            $mpdf = new \Mpdf\Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4-L',
                'margin_left' => 15,
                'margin_right' => 15,
                'margin_top' => 20,
                'margin_bottom' => 20,
                'default_font' => 'brawler',
                'fontDir' => array_merge(
                    $defaultFontConfig['fontDir'],
                    [public_path('fonts')]
                ),
                'fontdata' => array_merge(
                    $defaultFontData['fontdata'],
                    [
                        'brawler' => [
                            'R' => 'brawler/brawler-regular.ttf',
                            'B' => 'brawler/brawler-bold.ttf',
                        ],
                    ]
                ),
            ]);

            // Write HTML to PDF
            $mpdf->WriteHTML($html);

            $filename = 'PV_reclamations_' . $validated['session'] . '_' . $validated['academic_year'];
            if ($module) {
                $filename .= '_' . $module->code;
            }
            $filename .= '_' . date('Y-m-d') . '.pdf';

            // Output PDF for download
            return response($mpdf->Output($filename, 'I'), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $filename . '"',
            ]);

        } catch (\Exception $e) {
            Log::error('Error downloading PV: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la génération du PV: ' . $e->getMessage());
        }
    }

    /**
     * Manage reclamation settings.
     */
    public function reclamationSettings(Request $request)
    {
        $query = ReclamationSetting::with(['filiere', 'module', 'creator', 'academicYearRelation']);

        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        if ($request->filled('session')) {
            $query->where('session', $request->session);
        }

        $settings = $query->latest()->paginate(20)->withQueryString();

        $academicYears = AcademicYear::orderBy('start_year', 'desc')->get();
        $filieres = Filiere::orderBy('label_fr')->get();

        return view('admin.grades.reclamation-settings', compact(
            'settings',
            'academicYears',
            'filieres'
        ));
    }

    /**
     * Store reclamation setting.
     */
    public function storeReclamationSetting(Request $request)
    {
        $validated = $request->validate([
            'academic_year' => 'required|integer',
            'session' => 'required|in:normal,rattrapage',
            'filiere_id' => 'nullable|exists:filieres,id',
            'semester' => 'nullable|string|max:10',
            'module_id' => 'nullable|exists:modules,id',
            'is_active' => 'required|boolean',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after:starts_at',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $validated['created_by'] = auth('admin')->id();

            ReclamationSetting::create($validated);

            return back()->with('success', 'Paramètre de réclamation créé avec succès.');
        } catch (\Exception $e) {
            Log::error('Error creating reclamation setting: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la création: ' . $e->getMessage());
        }
    }

    /**
     * Update reclamation setting.
     */
    public function updateReclamationSetting(Request $request, $id)
    {
        $setting = ReclamationSetting::findOrFail($id);

        $validated = $request->validate([
            'is_active' => 'required|boolean',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after:starts_at',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $setting->update($validated);

            return back()->with('success', 'Paramètre mis à jour avec succès.');
        } catch (\Exception $e) {
            Log::error('Error updating reclamation setting: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la mise à jour: ' . $e->getMessage());
        }
    }

    /**
     * Delete reclamation setting.
     */
    public function deleteReclamationSetting($id)
    {
        try {
            $setting = ReclamationSetting::findOrFail($id);
            $setting->delete();

            return back()->with('success', 'Paramètre supprimé avec succès.');
        } catch (\Exception $e) {
            Log::error('Error deleting reclamation setting: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la suppression.');
        }
    }

    /**
     * Get modules by filiere and semester for AJAX.
     */
    public function getModulesByFiliereSemester(Request $request)
    {
        $validated = $request->validate([
            'filiere_id' => 'required|exists:filieres,id',
            'semester' => 'nullable|string',
        ]);

        try {
            $query = Module::where('filiere_id', $validated['filiere_id']);

            if ($request->filled('semester')) {
                $query->where('semester', $validated['semester']);
            }

            $modules = $query->orderBy('code')->get(['id', 'code', 'label']);

            return response()->json([
                'success' => true,
                'modules' => $modules
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du chargement des modules'
            ], 500);
        }
    }

    /**
     * Show grade import form.
     */
    public function showImportForm()
    {
        $academicYears = AcademicYear::orderBy('start_year', 'desc')->get();
        $filieres = Filiere::orderBy('label_fr')->get();

        return view('admin.grades.import', compact('academicYears', 'filieres'));
    }

    /**
     * Preview import file - validates and returns data for review.
     */
    public function previewImport(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:10240',
            'module_id' => 'required|exists:modules,id',
            'academic_year' => 'required|integer',
            'session' => 'required|in:normal,rattrapage',
        ]);

        try {
            $spreadsheet = IOFactory::load($request->file('file')->getPathname());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            // Skip header row
            array_shift($rows);

            $module = Module::with('filiere')->findOrFail($validated['module_id']);

            $preview = [];
            $stats = [
                'total' => 0,
                'valid' => 0,
                'errors' => 0,
                'new' => 0,
                'updates' => 0,
            ];

            foreach ($rows as $index => $row) {
                $rowNum = $index + 2;

                // Skip empty rows
                if (empty($row[0]) && empty($row[1])) {
                    continue;
                }

                $stats['total']++;

                $cne = trim($row[0] ?? '');
                $apogee = trim($row[1] ?? '');
                $nom = trim($row[2] ?? '');
                $prenom = trim($row[3] ?? '');
                $grade = $row[4] ?? null;
                $absent = strtoupper(trim($row[5] ?? 'N')) === 'O';

                $rowData = [
                    'row' => $rowNum,
                    'cne' => $cne,
                    'apogee' => $apogee,
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'grade' => $grade,
                    'absent' => $absent,
                    'status' => 'valid',
                    'action' => 'new',
                    'error' => null,
                ];

                // Find student
                $student = Student::where('cne', $cne)
                    ->orWhere('apogee', $apogee)
                    ->first();

                if (!$student) {
                    $rowData['status'] = 'error';
                    $rowData['error'] = 'Étudiant non trouvé';
                    $stats['errors']++;
                    $preview[] = $rowData;
                    continue;
                }

                // Find enrollment
                $enrollment = StudentModuleEnrollment::where('module_id', $validated['module_id'])
                    ->whereHas('programEnrollment', function ($q) use ($validated, $student) {
                        $q->where('academic_year', $validated['academic_year'])
                            ->where('student_id', $student->id);
                    })
                    ->first();

                if (!$enrollment) {
                    $rowData['status'] = 'error';
                    $rowData['error'] = 'Non inscrit au module';
                    $stats['errors']++;
                    $preview[] = $rowData;
                    continue;
                }

                // Validate grade
                if (!$absent) {
                    if ($grade === null || $grade === '') {
                        $rowData['status'] = 'error';
                        $rowData['error'] = 'Note manquante';
                        $stats['errors']++;
                        $preview[] = $rowData;
                        continue;
                    }
                    $gradeVal = floatval($grade);
                    if ($gradeVal < 0 || $gradeVal > 20) {
                        $rowData['status'] = 'error';
                        $rowData['error'] = 'Note invalide (0-20)';
                        $stats['errors']++;
                        $preview[] = $rowData;
                        continue;
                    }
                }

                // Check if updating or creating
                $existingGrade = ModuleGrade::where('module_enrollment_id', $enrollment->id)
                    ->where('session', $validated['session'])
                    ->first();

                if ($existingGrade) {
                    $rowData['action'] = 'update';
                    $rowData['old_grade'] = $existingGrade->grade;
                    $stats['updates']++;
                } else {
                    $stats['new']++;
                }

                $stats['valid']++;
                $preview[] = $rowData;
            }

            // Store file temporarily for processing
            $tempPath = $request->file('file')->store('temp/grade-imports');

            return response()->json([
                'success' => true,
                'preview' => $preview,
                'stats' => $stats,
                'module' => [
                    'code' => $module->code,
                    'label' => $module->label,
                    'filiere' => $module->filiere->label_fr,
                ],
                'temp_file' => $tempPath,
            ]);

        } catch (\Exception $e) {
            Log::error('Error previewing import: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'analyse du fichier: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Process import in chunks (AJAX).
     */
    public function processImportChunk(Request $request)
    {
        $validated = $request->validate([
            'temp_file' => 'required|string',
            'module_id' => 'required|exists:modules,id',
            'academic_year' => 'required|integer',
            'session' => 'required|in:normal,rattrapage',
            'chunk_start' => 'required|integer|min:0',
            'chunk_size' => 'required|integer|min:1|max:100',
        ]);

        try {
            $filePath = storage_path('app/' . $validated['temp_file']);

            if (!file_exists($filePath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Fichier temporaire non trouvé. Veuillez réuploader.',
                ], 404);
            }

            $spreadsheet = IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            // Skip header row
            array_shift($rows);

            $totalRows = count($rows);
            $chunkStart = $validated['chunk_start'];
            $chunkSize = $validated['chunk_size'];

            // Get the chunk
            $chunk = array_slice($rows, $chunkStart, $chunkSize);

            $imported = 0;
            $updated = 0;
            $errors = [];

            DB::beginTransaction();

            foreach ($chunk as $index => $row) {
                $rowNum = $chunkStart + $index + 2;

                // Skip empty rows
                if (empty($row[0]) && empty($row[1])) {
                    continue;
                }

                $cne = trim($row[0] ?? '');
                $apogee = trim($row[1] ?? '');
                $grade = $row[4] ?? null;
                $absent = strtoupper(trim($row[5] ?? 'N')) === 'O';

                // Find student
                $student = Student::where('cne', $cne)
                    ->orWhere('apogee', $apogee)
                    ->first();

                if (!$student) {
                    $errors[] = "Ligne {$rowNum}: Étudiant non trouvé";
                    continue;
                }

                // Find enrollment
                $enrollment = StudentModuleEnrollment::where('module_id', $validated['module_id'])
                    ->whereHas('programEnrollment', function ($q) use ($validated, $student) {
                        $q->where('academic_year', $validated['academic_year'])
                            ->where('student_id', $student->id);
                    })
                    ->first();

                if (!$enrollment) {
                    $errors[] = "Ligne {$rowNum}: Inscription non trouvée";
                    continue;
                }

                // Validate grade
                if (!$absent) {
                    if ($grade === null || $grade === '') {
                        $errors[] = "Ligne {$rowNum}: Note manquante";
                        continue;
                    }
                    $grade = floatval($grade);
                    if ($grade < 0 || $grade > 20) {
                        $errors[] = "Ligne {$rowNum}: Note invalide";
                        continue;
                    }
                }

                // Determine result
                $result = $this->determineResult($grade, $absent, $validated['session']);

                // Create or update grade
                $existingGrade = ModuleGrade::where('module_enrollment_id', $enrollment->id)
                    ->where('session', $validated['session'])
                    ->first();

                if ($existingGrade) {
                    $existingGrade->update([
                        'grade' => $absent ? null : $grade,
                        'is_absent' => $absent,
                        'result' => $result,
                    ]);
                    $updated++;
                } else {
                    ModuleGrade::create([
                        'module_enrollment_id' => $enrollment->id,
                        'session' => $validated['session'],
                        'grade' => $absent ? null : $grade,
                        'is_absent' => $absent,
                        'result' => $result,
                        'is_published' => false,
                    ]);
                    $imported++;
                }
            }

            DB::commit();

            $processed = $chunkStart + count($chunk);
            $isComplete = $processed >= $totalRows;

            // Clean up temp file if complete
            if ($isComplete && file_exists($filePath)) {
                unlink($filePath);
            }

            return response()->json([
                'success' => true,
                'imported' => $imported,
                'updated' => $updated,
                'errors' => $errors,
                'processed' => $processed,
                'total' => $totalRows,
                'is_complete' => $isComplete,
                'progress' => round(($processed / $totalRows) * 100),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error processing import chunk: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du traitement: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get modules for a filiere (AJAX).
     */
    public function getModulesForFiliere(Request $request)
    {
        $modules = Module::where('filiere_id', $request->filiere_id)
            ->orderBy('semester')
            ->orderBy('code')
            ->get(['id', 'code', 'label', 'semester']);

        return response()->json($modules);
    }

    /**
     * Download grade import template.
     */
    public function downloadTemplate(Request $request)
    {
        $validated = $request->validate([
            'module_id' => 'required|exists:modules,id',
            'academic_year' => 'required|integer',
            'session' => 'required|in:normal,rattrapage',
        ]);

        $module = Module::with('filiere')->findOrFail($validated['module_id']);

        // Get enrolled students for this module
        $enrollments = StudentModuleEnrollment::with(['programEnrollment.student'])
            ->where('module_id', $module->id)
            ->whereHas('programEnrollment', function ($q) use ($validated) {
                $q->where('academic_year', $validated['academic_year']);
            })
            ->get();

        // Create spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Headers
        $sheet->setCellValue('A1', 'CNE');
        $sheet->setCellValue('B1', 'Apogée');
        $sheet->setCellValue('C1', 'Nom');
        $sheet->setCellValue('D1', 'Prénom');
        $sheet->setCellValue('E1', 'Note (0-20)');
        $sheet->setCellValue('F1', 'Absent (O/N)');

        // Style headers
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);
        $sheet->getStyle('A1:F1')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFE0E0E0');

        // Add student data
        $row = 2;
        foreach ($enrollments as $enrollment) {
            $student = $enrollment->programEnrollment->student;
            $sheet->setCellValue("A{$row}", $student->cne);
            $sheet->setCellValue("B{$row}", $student->apogee);
            $sheet->setCellValue("C{$row}", $student->nom);
            $sheet->setCellValue("D{$row}", $student->prenom);
            $sheet->setCellValue("E{$row}", '');
            $sheet->setCellValue("F{$row}", 'N');
            $row++;
        }

        // Auto-size columns
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Add metadata sheet
        $metaSheet = $spreadsheet->createSheet();
        $metaSheet->setTitle('Metadata');
        $metaSheet->setCellValue('A1', 'Module ID');
        $metaSheet->setCellValue('B1', $module->id);
        $metaSheet->setCellValue('A2', 'Module Code');
        $metaSheet->setCellValue('B2', $module->code);
        $metaSheet->setCellValue('A3', 'Academic Year');
        $metaSheet->setCellValue('B3', $validated['academic_year']);
        $metaSheet->setCellValue('A4', 'Session');
        $metaSheet->setCellValue('B4', $validated['session']);

        $spreadsheet->setActiveSheetIndex(0);

        // Generate filename
        $filename = "notes_{$module->code}_{$validated['academic_year']}_{$validated['session']}.xlsx";

        // Output
        $writer = new Xlsx($spreadsheet);
        $temp = tempnam(sys_get_temp_dir(), 'grades');
        $writer->save($temp);

        return response()->download($temp, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    /**
     * Import grades from Excel file.
     */
    public function import(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:10240',
            'module_id' => 'required|exists:modules,id',
            'academic_year' => 'required|integer',
            'session' => 'required|in:normal,rattrapage',
        ]);

        try {
            $spreadsheet = IOFactory::load($request->file('file')->getPathname());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            // Skip header row
            array_shift($rows);

            $imported = 0;
            $updated = 0;
            $errors = [];

            DB::beginTransaction();

            foreach ($rows as $index => $row) {
                $rowNum = $index + 2; // Account for header and 0-index

                // Skip empty rows
                if (empty($row[0]) && empty($row[1])) {
                    continue;
                }

                $cne = trim($row[0] ?? '');
                $apogee = trim($row[1] ?? '');
                $grade = $row[4] ?? null;
                $absent = strtoupper(trim($row[5] ?? 'N')) === 'O';

                // Find student
                $student = Student::where('cne', $cne)
                    ->orWhere('apogee', $apogee)
                    ->first();

                if (!$student) {
                    $errors[] = "Ligne {$rowNum}: Étudiant non trouvé (CNE: {$cne})";
                    continue;
                }

                // Find enrollment
                $enrollment = StudentModuleEnrollment::where('module_id', $validated['module_id'])
                    ->whereHas('programEnrollment', function ($q) use ($validated, $student) {
                        $q->where('academic_year', $validated['academic_year'])
                            ->where('student_id', $student->id);
                    })
                    ->first();

                if (!$enrollment) {
                    $errors[] = "Ligne {$rowNum}: Inscription non trouvée pour {$student->nom} {$student->prenom}";
                    continue;
                }

                // Validate grade
                if (!$absent) {
                    if ($grade === null || $grade === '') {
                        $errors[] = "Ligne {$rowNum}: Note manquante pour {$student->nom} {$student->prenom}";
                        continue;
                    }
                    $grade = floatval($grade);
                    if ($grade < 0 || $grade > 20) {
                        $errors[] = "Ligne {$rowNum}: Note invalide ({$grade}) pour {$student->nom} {$student->prenom}";
                        continue;
                    }
                }

                // Determine result
                $result = $this->determineResult($grade, $absent, $validated['session']);

                // Create or update grade
                $existingGrade = ModuleGrade::where('module_enrollment_id', $enrollment->id)
                    ->where('session', $validated['session'])
                    ->first();

                if ($existingGrade) {
                    $existingGrade->update([
                        'grade' => $absent ? null : $grade,
                        'is_absent' => $absent,
                        'result' => $result,
                    ]);
                    $updated++;
                } else {
                    ModuleGrade::create([
                        'module_enrollment_id' => $enrollment->id,
                        'session' => $validated['session'],
                        'grade' => $absent ? null : $grade,
                        'is_absent' => $absent,
                        'result' => $result,
                        'is_published' => false,
                    ]);
                    $imported++;
                }
            }

            DB::commit();

            $message = "{$imported} note(s) importée(s), {$updated} mise(s) à jour.";
            if (count($errors) > 0) {
                $message .= " " . count($errors) . " erreur(s).";
            }

            return back()->with('success', $message)->with('import_errors', $errors);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error importing grades: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de l\'importation: ' . $e->getMessage());
        }
    }

    /**
     * Determine grade result based on score and session.
     */
    private function determineResult($grade, $absent, $session)
    {
        if ($absent) {
            return 'absent';
        }

        if ($grade >= 10) {
            return $session === 'rattrapage' ? 'validé après rattrapage' : 'validé';
        }

        return $session === 'normal' ? 'en attente rattrapage' : 'non validé';
    }

    /**
     * Show bulk import form for all filieres/semesters at once
     */
    public function showBulkImportForm()
    {
        $academicYears = AcademicYear::orderBy('start_year', 'desc')->get();
        $examPeriods = ExamPeriod::orderBy('academic_year', 'desc')
            ->orderBy('start_date', 'desc')
            ->get();

        return view('admin.grades.bulk-import', compact('academicYears', 'examPeriods'));
    }

    /**
     * Download template for bulk session grades import
     */
    public function downloadBulkSessionGradesTemplate(Request $request)
    {
        $validated = $request->validate([
            'academic_year' => 'required|integer',
            'session' => 'required|in:normal,rattrapage',
            'exam_period_id' => 'nullable|exists:exam_periods,id',
        ]);

        try {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Notes Session');

            // Headers
            $headers = [
                'Apogee',
                'Code Module',
                'Note (0-20)',
                'Resultat (optionnel)',
            ];

            $col = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue($col . '1', $header);
                $col++;
            }

            // Style header
            $sheet->getStyle('A1:D1')->getFont()->setBold(true)->setSize(12);
            $sheet->getStyle('A1:D1')->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FF4472C4');
            $sheet->getStyle('A1:D1')->getFont()->getColor()->setARGB('FFFFFFFF');
            $sheet->getStyle('A1:D1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Add instructions sheet
            $instructionsSheet = $spreadsheet->createSheet();
            $instructionsSheet->setTitle('Instructions');
            $instructionsSheet->setCellValue('A1', 'Instructions pour l\'import des notes');
            $instructionsSheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

            $instructions = [
                '',
                'Format des colonnes:',
                '- Apogee: Code Apogee de l\'étudiant (obligatoire)',
                '- Code Module: Code du module (ex: M01, INF101) (obligatoire)',
                '- Note: Note sur 20 (obligatoire si présent)',
                '- Resultat: validé, non validé, en attente rattrapage (optionnel)',
                '',
                'Notes importantes:',
                '- Le système détecte automatiquement la période d\'examen active',
                '- Le résultat est calculé automatiquement si non fourni',
                '- Les étudiants doivent être inscrits au module',
                '- Les erreurs seront enregistrées et téléchargeables',
            ];

            $row = 2;
            foreach ($instructions as $instruction) {
                $instructionsSheet->setCellValue('A' . $row, $instruction);
                $row++;
            }

            $instructionsSheet->getColumnDimension('A')->setWidth(80);

            // Add metadata sheet
            $metaSheet = $spreadsheet->createSheet();
            $metaSheet->setTitle('Metadata');
            $metaSheet->setCellValue('A1', 'Academic Year');
            $metaSheet->setCellValue('B1', $validated['academic_year']);
            $metaSheet->setCellValue('A2', 'Session');
            $metaSheet->setCellValue('B2', $validated['session']);
            if (isset($validated['exam_period_id'])) {
                $metaSheet->setCellValue('A3', 'Exam Period ID');
                $metaSheet->setCellValue('B3', $validated['exam_period_id']);
            }

            $spreadsheet->setActiveSheetIndex(0);

            // Auto-size columns
            foreach (range('A', 'D') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            $filename = "bulk_notes_session_{$validated['session']}_{$validated['academic_year']}.xlsx";

            $writer = new Xlsx($spreadsheet);
            $temp = tempnam(sys_get_temp_dir(), 'bulk_grades_');
            $writer->save($temp);

            return response()->download($temp, $filename, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ])->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            Log::error('Error downloading bulk template: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors du téléchargement du modèle.');
        }
    }

    /**
     * Import bulk session grades (all filieres, all semesters)
     */
    public function importBulkSessionGrades(Request $request, GradeBulkImportService $importService)
    {
        $validated = $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:51200', // 50MB max
            'academic_year' => 'required|integer',
            'session' => 'required|in:normal,rattrapage',
            'exam_period_id' => 'nullable|exists:exam_periods,id',
        ]);

        try {
            set_time_limit(600); // 10 minutes for large files
            ini_set('memory_limit', '512M');

            $filePath = $request->file('file')->store('temp/bulk-imports');
            $fullPath = Storage::path($filePath);

            $stats = $importService->importSessionGrades(
                $fullPath,
                $validated['academic_year'],
                $validated['session'],
                $validated['exam_period_id'] ?? null,
                auth('admin')->id()
            );

            // Clean up temp file
            Storage::delete($filePath);

            $message = "Import terminé: {$stats['successful']} notes importées ({$stats['created']} créées, {$stats['updated']} mises à jour)";

            if ($stats['errors'] > 0) {
                $message .= ", {$stats['errors']} erreurs";
            }

            if ($stats['skipped'] > 0) {
                $message .= ", {$stats['skipped']} lignes ignorées";
            }

            return back()->with('success', $message)
                ->with('batch_id', $stats['batch_id'])
                ->with('import_stats', $stats);

        } catch (\Exception $e) {
            Log::error('Bulk import error: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de l\'import: ' . $e->getMessage());
        }
    }

    /**
     * Download template for final grades import
     */
    public function downloadFinalGradesTemplate(Request $request)
    {
        $validated = $request->validate([
            'academic_year' => 'required|integer',
        ]);

        try {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Notes Finales');

            // Headers
            $headers = [
                'Apogee',
                'Code Module',
                'Note Finale (0-20)',
                'Resultat Final',
                'Session Finale (normal/rattrapage)',
                'Ancienne Note (référence)',
            ];

            $col = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue($col . '1', $header);
                $col++;
            }

            // Style header
            $sheet->getStyle('A1:F1')->getFont()->setBold(true)->setSize(12);
            $sheet->getStyle('A1:F1')->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FF70AD47');
            $sheet->getStyle('A1:F1')->getFont()->getColor()->setARGB('FFFFFFFF');
            $sheet->getStyle('A1:F1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Add instructions
            $instructionsSheet = $spreadsheet->createSheet();
            $instructionsSheet->setTitle('Instructions');
            $instructionsSheet->setCellValue('A1', 'Instructions - Import Notes Finales');
            $instructionsSheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

            $instructions = [
                '',
                'Ce fichier est utilisé pour importer les notes finales après délibérations.',
                '',
                'Format des colonnes:',
                '- Apogee: Code Apogee de l\'étudiant (obligatoire)',
                '- Code Module: Code du module (obligatoire)',
                '- Note Finale: Note finale après délibération (obligatoire)',
                '- Resultat Final: validé, non validé, validé après rattrapage (obligatoire)',
                '- Session Finale: normal ou rattrapage (obligatoire)',
                '- Ancienne Note: Note avant délibération (optionnel, pour référence)',
                '',
                'Notes:',
                '- Utilisé généralement une fois par an après toutes les délibérations',
                '- Met à jour les notes finales dans student_module_enrollments',
                '- Met également à jour la note de la session correspondante',
            ];

            $row = 2;
            foreach ($instructions as $instruction) {
                $instructionsSheet->setCellValue('A' . $row, $instruction);
                $row++;
            }

            $instructionsSheet->getColumnDimension('A')->setWidth(80);

            // Metadata
            $metaSheet = $spreadsheet->createSheet();
            $metaSheet->setTitle('Metadata');
            $metaSheet->setCellValue('A1', 'Academic Year');
            $metaSheet->setCellValue('B1', $validated['academic_year']);

            $spreadsheet->setActiveSheetIndex(0);

            foreach (range('A', 'F') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            $filename = "notes_finales_{$validated['academic_year']}.xlsx";

            $writer = new Xlsx($spreadsheet);
            $temp = tempnam(sys_get_temp_dir(), 'final_grades_');
            $writer->save($temp);

            return response()->download($temp, $filename, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ])->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            Log::error('Error downloading final grades template: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors du téléchargement du modèle.');
        }
    }

    /**
     * Import final grades
     */
    public function importFinalGrades(Request $request, GradeBulkImportService $importService)
    {
        $validated = $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:51200',
            'academic_year' => 'required|integer',
        ]);

        try {
            set_time_limit(600);
            ini_set('memory_limit', '512M');

            $filePath = $request->file('file')->store('temp/bulk-imports');
            $fullPath = Storage::path($filePath);

            $stats = $importService->importFinalGrades(
                $fullPath,
                $validated['academic_year'],
                auth('admin')->id()
            );

            Storage::delete($filePath);

            $message = "Import notes finales terminé: {$stats['successful']} notes mises à jour";

            if ($stats['errors'] > 0) {
                $message .= ", {$stats['errors']} erreurs";
            }

            return back()->with('success', $message)
                ->with('batch_id', $stats['batch_id'])
                ->with('import_stats', $stats);

        } catch (\Exception $e) {
            Log::error('Final grades import error: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de l\'import: ' . $e->getMessage());
        }
    }

    /**
     * View import errors for a batch
     */
    public function viewImportErrors(Request $request, $batchId)
    {
        $errors = GradeImportError::forBatch($batchId)
            ->with(['admin', 'examPeriod'])
            ->orderBy('row_number')
            ->paginate(50);

        $summary = GradeImportError::createBatchSummary($batchId);

        return view('admin.grades.import-errors', compact('errors', 'summary', 'batchId'));
    }

    /**
     * Download import errors as Excel
     */
    public function downloadImportErrors(Request $request, GradeBulkImportService $importService, $batchId)
    {
        try {
            $tempFile = $importService->downloadErrorReport($batchId);

            $filename = "erreurs_import_{$batchId}_" . date('Y-m-d_His') . '.xlsx';

            return response()->download($tempFile, $filename, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ])->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            Log::error('Error downloading import errors: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors du téléchargement des erreurs.');
        }
    }

    /**
     * Download PV (Procès-Verbal) for a specific module with statistics
     */
    public function downloadModulePV(Request $request)
    {
        $validated = $request->validate([
            'module_id' => 'required|exists:modules,id',
            'session' => 'required|in:normal,rattrapage',
            'academic_year' => 'required|integer',
        ]);

        try {
            // Get module with grades
            $module = Module::with('filiere')->findOrFail($validated['module_id']);
            $academicYear = AcademicYear::where('start_year', $validated['academic_year'])->firstOrFail();

            // Get exam info first to use in grades query
            $examPeriod = ExamPeriod::where('academic_year', $validated['academic_year'])
                ->where('session_type', $validated['session'])
                ->first();

            $exam = null;
            if ($examPeriod) {
                $exam = Exam::where('exam_period_id', $examPeriod->id)
                    ->where('module_id', $validated['module_id'])
                    ->first();
            }

            // Get all grades for this module with student info (optimized single query)
            $gradesQuery = ModuleGrade::query()
                ->select([
                    'module_grades.id',
                    'module_grades.grade',
                    'module_grades.result',
                    'module_grades.is_published',
                    'module_grades.module_enrollment_id',
                    'students.apogee',
                    'students.prenom',
                    'students.nom',
                    'students.prenom_ar',
                    'students.nom_ar',
                    'exam_convocations.n_examen',
                ])
                ->join('student_module_enrollments', 'module_grades.module_enrollment_id', '=', 'student_module_enrollments.id')
                ->join('student_program_enrollments', 'student_module_enrollments.program_enrollment_id', '=', 'student_program_enrollments.id')
                ->join('students', 'student_program_enrollments.student_id', '=', 'students.id');

            // Join exam_convocations if exam exists
            if ($exam) {
                $gradesQuery->leftJoin('exam_convocations', function($join) use ($exam) {
                    $join->on('exam_convocations.student_module_enrollment_id', '=', 'student_module_enrollments.id')
                         ->where('exam_convocations.exam_id', '=', $exam->id);
                });
            }

            $grades = $gradesQuery
                ->where('student_module_enrollments.module_id', $validated['module_id'])
                ->where('student_program_enrollments.academic_year', $validated['academic_year'])
                ->where('module_grades.session', $validated['session'])
                ->orderBy('students.nom')
                ->orderBy('students.prenom')
                ->get();

            // Get exam locations from convocations (optimized)
            $examLocations = [];
            if ($exam) {
                $examLocations = ExamConvocation::where('exam_id', $exam->id)
                    ->join('locals', 'exam_convocations.local_id', '=', 'locals.id')
                    ->select('locals.name')
                    ->distinct()
                    ->pluck('name')
                    ->toArray();
            }

            // Statistics (database-level calculation for better performance with 500+ students)
            $statsQuery = ModuleGrade::query()
                ->selectRaw('
                    COUNT(*) as total,
                    SUM(CASE WHEN is_published = 1 THEN 1 ELSE 0 END) as published,
                    AVG(CASE WHEN is_published = 1 AND grade IS NOT NULL THEN grade END) as avg_grade,
                    SUM(CASE WHEN result = ? THEN 1 ELSE 0 END) as passed,
                    SUM(CASE WHEN result = ? THEN 1 ELSE 0 END) as failed
                ', ['validé', 'non validé'])
                ->join('student_module_enrollments', 'module_grades.module_enrollment_id', '=', 'student_module_enrollments.id')
                ->join('student_program_enrollments', 'student_module_enrollments.program_enrollment_id', '=', 'student_program_enrollments.id')
                ->where('student_module_enrollments.module_id', $validated['module_id'])
                ->where('student_program_enrollments.academic_year', $validated['academic_year'])
                ->where('module_grades.session', $validated['session'])
                ->first();

            $stats = [
                'total' => $statsQuery->total ?? 0,
                'published' => $statsQuery->published ?? 0,
                'avg_grade' => $statsQuery->avg_grade ?? 0,
                'passed' => $statsQuery->passed ?? 0,
                'failed' => $statsQuery->failed ?? 0,
            ];

            // Generate HTML from view
            $html = view('admin.grades.module-pv', [
                'module' => $module,
                'academicYear' => $academicYear,
                'session' => $validated['session'],
                'grades' => $grades,
                'exam' => $exam,
                'examPeriod' => $examPeriod,
                'examLocations' => $examLocations,
                'stats' => $stats,
                'generatedAt' => now(),
            ])->render();

            // Create mPDF instance with Brawler font
            $defaultFontConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
            $defaultFontData = (new \Mpdf\Config\FontVariables())->getDefaults();

            $mpdf = new \Mpdf\Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'margin_left' => 15,
                'margin_right' => 15,
                'margin_top' => 20,
                'margin_bottom' => 20,
                'default_font' => 'brawler',
                'fontDir' => array_merge(
                    $defaultFontConfig['fontDir'],
                    [public_path('fonts')]
                ),
                'fontdata' => array_merge(
                    $defaultFontData['fontdata'],
                    [
                        'brawler' => [
                            'R' => 'brawler/brawler-regular.ttf',
                            'B' => 'brawler/brawler-bold.ttf',
                        ],
                    ]
                ),
            ]);

            $mpdf->WriteHTML($html);

            $filename = 'PV_' . $module->code . '_' . $validated['session'] . '_' . $validated['academic_year'] . '_' . date('Y-m-d') . '.pdf';

            $mpdf->Output($filename, \Mpdf\Output\Destination::INLINE);
            // return response()->streamDownload(function() use ($mpdf) {
            //     echo $mpdf->Output('', 'S');
            // }, $filename, [
            //     'Content-Type' => 'application/pdf',
            // ]);

        } catch (\Exception $e) {
            Log::error('Error downloading module PV: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la génération du PV: ' . $e->getMessage());
        }
    }

    /**
     * Toggle reclamations for a specific module
     */
    public function toggleModuleReclamations(Request $request, $moduleId)
    {
        $validated = $request->validate([
            'session' => 'required|in:normal,rattrapage',
            'academic_year' => 'required|integer',
            'action' => 'required|in:enable,disable',
        ]);

        try {
            // Get module with filiere
            $module = Module::with('filiere')->findOrFail($moduleId);

            // Check if setting exists
            $setting = ReclamationSetting::where('academic_year', $validated['academic_year'])
                ->where('session', $validated['session'])
                ->where('module_id', $moduleId)
                ->first();

            if ($validated['action'] === 'enable') {
                if ($setting) {
                    $setting->update(['is_active' => true]);
                } else {
                    ReclamationSetting::create([
                        'academic_year' => $validated['academic_year'],
                        'session' => $validated['session'],
                        'filiere_id' => $module->filiere_id,
                        'semester' => $module->semester,
                        'module_id' => $moduleId,
                        'is_active' => true,
                        'created_by' => auth('admin')->id(),
                    ]);
                }
                $message = "Réclamations activées pour {$module->code}";
            } else {
                if ($setting) {
                    $setting->update(['is_active' => false]);
                    $message = "Réclamations désactivées pour {$module->code}";
                } else {
                    $message = "Aucune configuration de réclamation trouvée";
                }
            }

            return back()->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Error toggling reclamations: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la modification des réclamations.');
        }
    }

    /**
     * Bulk toggle reclamations for multiple modules
     */
    public function bulkToggleReclamations(Request $request)
    {
        $validated = $request->validate([
            'module_ids' => 'required|array',
            'module_ids.*' => 'exists:modules,id',
            'session' => 'required|in:normal,rattrapage',
            'academic_year' => 'required|integer',
            'action' => 'required|in:enable,disable',
        ]);

        try {
            $updated = 0;
            $created = 0;

            foreach ($validated['module_ids'] as $moduleId) {
                $module = Module::find($moduleId);
                if (!$module) continue;

                // Check if setting exists
                $setting = ReclamationSetting::where('academic_year', $validated['academic_year'])
                    ->where('session', $validated['session'])
                    ->where('module_id', $moduleId)
                    ->first();

                if ($validated['action'] === 'enable') {
                    if ($setting) {
                        $setting->update(['is_active' => true]);
                        $updated++;
                    } else {
                        ReclamationSetting::create([
                            'academic_year' => $validated['academic_year'],
                            'session' => $validated['session'],
                            'filiere_id' => $module->filiere_id,
                            'semester' => $module->semester,
                            'module_id' => $moduleId,
                            'is_active' => true,
                            'created_by' => auth('admin')->id(),
                        ]);
                        $created++;
                    }
                } else {
                    if ($setting) {
                        $setting->update(['is_active' => false]);
                        $updated++;
                    }
                }
            }

            $action = $validated['action'] === 'enable' ? 'activées' : 'désactivées';
            $message = "Réclamations {$action} pour " . count($validated['module_ids']) . " module(s)";
            if ($created > 0) {
                $message .= " ({$created} créées, {$updated} mises à jour)";
            }

            return back()->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Error bulk toggling reclamations: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la modification des réclamations.');
        }
    }
}
