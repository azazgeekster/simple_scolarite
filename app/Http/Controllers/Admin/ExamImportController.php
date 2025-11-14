<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamConvocation;
use App\Models\Module;
use App\Models\ModuleGrade;
use App\Models\Student;
use App\Models\StudentModuleEnrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ExamImportController extends Controller
{
    /**
     * Show the exam import form
     */
    public function showImportForm()
    {
        // Check if user is super admin
        if (!auth('admin')->user()->hasRole('Super Admin')) {
            abort(403, 'Only Super Admins can import exams');
        }

        // Get all academic years ordered by start year descending
        $academicYears = \App\Models\AcademicYear::orderBy('start_year', 'desc')->get();

        return view('admin.exams.import', compact('academicYears'));
    }

    /**
     * Download CSV template file
     */
    public function downloadTemplate()
    {
        // Check if user is super admin
        if (!auth('admin')->user()->hasRole('Super Admin')) {
            abort(403, 'Only Super Admins can download templates');
        }

        $csvData = [
            ['numero_examen', 'code_apogee', 'code_module', 'date_examen', 'heure_debut', 'heure_fin', 'salle', 'observations'],
            ['E001', '20210001', 'M101', '2025-01-15', '08:30', '10:30', 'Amphi A', ''],
            ['E002', '20210002', 'M101', '2025-01-15', '08:30', '10:30', 'Amphi A', ''],
            ['E003', '20210003', 'M101', '2025-01-15', '08:30', '10:30', 'Amphi B', 'Besoins spéciaux'],
        ];

        $filename = 'modele_convocations_' . now()->format('Ymd') . '.csv';

        $callback = function() use ($csvData) {
            $file = fopen('php://output', 'w');

            // Add UTF-8 BOM for proper Excel encoding
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            foreach ($csvData as $row) {
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Pragma' => 'public',
        ]);
    }

    /**
     * Process the uploaded exam file
     */
    public function import(Request $request)
    {
        // Check if user is super admin
        if (!auth('admin')->user()->hasRole('Super Admin')) {
            abort(403, 'Only Super Admins can import exams');
        }

        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx,xls|max:10240', // 10MB max
            'academic_year' => 'required|integer|min:2020|max:2100',
            'season' => 'required|in:autumn,spring',
            'session_type' => 'required|in:normal,rattrapage',
        ]);

        $file = $request->file('file');
        $academicYear = $request->input('academic_year');
        $season = $request->input('season');
        $sessionType = $request->input('session_type');

        try {
            // Parse CSV file
            $data = $this->parseFile($file);

            if (empty($data)) {
                return back()->with('error', 'Le fichier est vide ou invalide');
            }

            // Validate and import exams
            $result = $this->processExams($data, $academicYear, $season, $sessionType);

            if ($result['success'] > 0) {
                $message = "Importation réussie: {$result['success']} examen(s) importé(s)";
                if ($result['skipped'] > 0) {
                    $message .= ", {$result['skipped']} ignoré(s) (doublons)";
                }
                if ($result['errors'] > 0) {
                    $message .= ", {$result['errors']} erreur(s)";
                }

                return back()->with('success', $message)
                    ->with('details', $result['details']);
            } else {
                return back()->with('error', 'Aucun examen importé. Vérifiez le fichier.')
                    ->with('details', $result['details']);
            }
        } catch (\Exception $e) {
            Log::error('Exam import error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Erreur lors de l\'importation: ' . $e->getMessage());
        }
    }

    /**
     * Parse CSV/Excel file
     */
    private function parseFile($file)
    {
        $extension = $file->getClientOriginalExtension();
        $path = $file->getRealPath();

        if (in_array($extension, ['csv', 'txt'])) {
            return $this->parseCsv($path);
        } elseif (in_array($extension, ['xlsx', 'xls'])) {
            // For Excel files, you'd need PhpSpreadsheet
            // For now, we'll focus on CSV
            return back()->with('error', 'Les fichiers Excel nécessitent PhpSpreadsheet. Utilisez CSV pour l\'instant.');
        }

        return [];
    }

    /**
     * Parse CSV file
     */
    private function parseCsv($path)
    {
        $data = [];
        $handle = fopen($path, 'r');

        if ($handle === false) {
            return $data;
        }

        // Read header row
        $header = fgetcsv($handle, 0, ',');

        if (!$header) {
            fclose($handle);
            return $data;
        }

        // Normalize headers (trim whitespace, lowercase)
        $header = array_map(fn($h) => trim(strtolower($h)), $header);

        // Map French headers to English field names
        $header = array_map(fn($h) => $this->mapHeaderToField($h), $header);

        // Read data rows
        while (($row = fgetcsv($handle, 0, ',')) !== false) {
            if (count($row) === count($header)) {
                $data[] = array_combine($header, $row);
            }
        }

        fclose($handle);
        return $data;
    }

    /**
     * Map French/English CSV headers to internal field names
     */
    private function mapHeaderToField($header)
    {
        $mapping = [
            // French headers
            'numero_examen' => 'n_examen',
            'n_examen' => 'n_examen',
            'code_apogee' => 'apogee',
            'apogee' => 'apogee',
            'code_module' => 'module_code',
            'module_code' => 'module_code',
            'date_examen' => 'exam_date',
            'exam_date' => 'exam_date',
            'heure_debut' => 'start_time',
            'start_time' => 'start_time',
            'heure_fin' => 'end_time',
            'end_time' => 'end_time',
            'salle' => 'local',
            'local' => 'local',
            'observations' => 'observations',
        ];

        return $mapping[$header] ?? $header;
    }

    /**
     * Process and import exams with student convocations
     */
    private function processExams($data, $academicYear, $season, $sessionType)
    {
        $success = 0;
        $skipped = 0;
        $errors = 0;
        $details = [];
        $examsCache = []; // Cache exams to avoid duplicate queries

        // Determine semesters based on season
        $semesters = $season === 'autumn' ? ['S1', 'S3', 'S5'] : ['S2', 'S4', 'S6'];

        foreach ($data as $index => $row) {
            $lineNumber = $index + 2; // +2 because: 1 for header, 1 for 0-based index

            try {
                // Validate required fields
                $validator = Validator::make($row, [
                    'n_examen' => 'nullable|string',
                    'apogee' => 'required|string',
                    'module_code' => 'required|string',
                    'exam_date' => 'required|date_format:Y-m-d',
                    'start_time' => 'required|date_format:H:i',
                    'end_time' => 'required|date_format:H:i',
                    'local' => 'nullable|string',
                    'observations' => 'nullable|string',
                ]);

                if ($validator->fails()) {
                    $errors++;
                    $details[] = "Ligne {$lineNumber}: Erreur de validation - " . implode(', ', $validator->errors()->all());
                    continue;
                }

                // Find student by apogee
                $student = Student::where('apogee', trim($row['apogee']))->first();
                if (!$student) {
                    $errors++;
                    $details[] = "Ligne {$lineNumber}: Étudiant non trouvé (Apogée: {$row['apogee']})";
                    continue;
                }

                // Find module by code
                $module = Module::where('code', trim($row['module_code']))->first();
                if (!$module) {
                    $errors++;
                    $details[] = "Ligne {$lineNumber}: Module non trouvé (Code: {$row['module_code']})";
                    continue;
                }

                // Validate semester matches season
                if (!in_array($module->semester, $semesters)) {
                    $errors++;
                    $details[] = "Ligne {$lineNumber}: Le module {$module->code} ({$module->semester}) n'appartient pas à la saison {$season}";
                    continue;
                }

                // Find student's enrollment in this module for this academic year
                $enrollment = StudentModuleEnrollment::where('student_id', $student->id)
                    ->where('module_id', $module->id)
                    ->whereHas('programEnrollment', function($q) use ($academicYear) {
                        $q->where('academic_year', $academicYear);
                    })
                    ->first();

                if (!$enrollment) {
                    $errors++;
                    $details[] = "Ligne {$lineNumber}: Étudiant {$row['apogee']} non inscrit au module {$module->code} pour l'année {$academicYear}";
                    continue;
                }

                // Create unique key for exam
                $examKey = "{$module->id}_{$sessionType}_{$season}_{$academicYear}_{$row['exam_date']}_{$row['start_time']}_{$row['end_time']}";

                // Get or create exam
                if (!isset($examsCache[$examKey])) {
                    $exam = Exam::firstOrCreate(
                        [
                            'module_id' => $module->id,
                            'session_type' => $sessionType,
                            'season' => $season,
                            'academic_year' => $academicYear,
                            'exam_date' => $row['exam_date'],
                            'start_time' => $row['exam_date'] . ' ' . $row['start_time'],
                            'end_time' => $row['exam_date'] . ' ' . $row['end_time'],
                        ],
                        [
                            'semester' => $module->semester,
                            'local' => $row['local'] ?? 'À définir',
                            'is_published' => false,
                            'published_at' => null,
                        ]
                    );
                    $examsCache[$examKey] = $exam;
                } else {
                    $exam = $examsCache[$examKey];
                }

                // Check if convocation already exists
                $existingConvocation = ExamConvocation::where('exam_id', $exam->id)
                    ->where('student_module_enrollment_id', $enrollment->id)
                    ->first();

                if ($existingConvocation) {
                    $skipped++;
                    continue;
                }

                // Create convocation
                ExamConvocation::create([
                    'exam_id' => $exam->id,
                    'student_module_enrollment_id' => $enrollment->id,
                    'n_examen' => $row['n_examen'] ?? null,
                    'location' => $row['local'] ?? null,
                    'observations' => $row['observations'] ?? null,
                ]);

                $success++;
            } catch (\Exception $e) {
                $errors++;
                $details[] = "Ligne {$lineNumber}: Erreur - " . $e->getMessage();
                Log::error("Exam import line {$lineNumber} error", [
                    'row' => $row,
                    'error' => $e->getMessage()
                ]);
            }
        }

        return [
            'success' => $success,
            'skipped' => $skipped,
            'errors' => $errors,
            'details' => $details,
        ];
    }

    /**
     * Export list of students who should be convocated to rattrapage
     * Based on failed normal exams + treated réclamations
     */
    public function exportRattrapageCandidates(Request $request)
    {
        // Check if user is super admin
        if (!auth('admin')->user()->hasRole('Super Admin')) {
            abort(403, 'Only Super Admins can export rattrapage candidates');
        }

        $request->validate([
            'academic_year' => 'required|integer|min:2020|max:2100',
            'season' => 'required|in:autumn,spring',
        ]);

        $academicYear = $request->input('academic_year');
        $season = $request->input('season');

        try {
            // Determine semesters based on season
            $semesters = $season === 'autumn' ? ['S1', 'S3', 'S5'] : ['S2', 'S4', 'S6'];

            // Get all module grades for normal session with grade < 10 for this year/season
            // Using module_grades table which has session-specific grades
            $failedGrades = ModuleGrade::where('session', 'normal')
                ->whereNotNull('grade')
                ->where('grade', '<', 10)
                ->whereHas('moduleEnrollment.programEnrollment', function ($q) use ($academicYear) {
                    $q->where('academic_year', $academicYear);
                })
                ->whereHas('moduleEnrollment.module', function ($q) use ($semesters) {
                    $q->whereIn('semester', $semesters);
                })
                ->with([
                    'moduleEnrollment.module',
                    'moduleEnrollment.student',
                    'moduleEnrollment.programEnrollment.filiere'
                ])
                ->get();

            if ($failedGrades->isEmpty()) {
                return back()->with('error', 'Aucun étudiant en échec trouvé pour cette période (session normale).');
            }

            // Prepare CSV data matching import format (French headers)
            $csvData = [];
            $csvData[] = [
                'numero_examen',
                'code_apogee',
                'code_module',
                'date_examen',
                'heure_debut',
                'heure_fin',
                'salle',
                'observations'
            ];

            foreach ($failedGrades as $grade) {
                $student = $grade->moduleEnrollment->student;
                $module = $grade->moduleEnrollment->module;

                $csvData[] = [
                    '', // n_examen - to be filled manually
                    $student->apogee ?? '',
                    $module->code ?? '',
                    '', // exam_date - to be filled manually (format: YYYY-MM-DD)
                    '', // start_time - to be filled manually (format: HH:MM)
                    '', // end_time - to be filled manually (format: HH:MM)
                    '', // local - to be filled manually
                    'Échec session normale - Note: ' . ($grade->grade ?? 'N/A') . ' - Statut: ' . ($grade->exam_status ?? 'N/A')
                ];
            }

            // Generate CSV file
            $filename = sprintf(
                'rattrapage_candidates_%s_%s_%s.csv',
                $academicYear,
                $season,
                now()->format('Ymd_His')
            );

            $callback = function() use ($csvData) {
                $file = fopen('php://output', 'w');

                // Add UTF-8 BOM for proper Excel encoding
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

                foreach ($csvData as $row) {
                    fputcsv($file, $row);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Pragma' => 'public',
            ]);

        } catch (\Exception $e) {
            Log::error('Rattrapage candidates export error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Erreur lors de l\'export: ' . $e->getMessage());
        }
    }

    /**
     * Publish or unpublish exams by session type
     */
    public function toggleSessionPublication(Request $request)
    {
        // Check if user is super admin
        if (!auth('admin')->user()->hasRole('Super Admin')) {
            abort(403, 'Only Super Admins can manage exam publication');
        }

        $request->validate([
            'session_type' => 'required|in:normal,rattrapage',
            'action' => 'required|in:publish,unpublish',
            'academic_year' => 'nullable|integer',
            'season' => 'nullable|in:autumn,spring',
        ]);

        try {
            $query = Exam::where('session_type', $request->session_type);

            // Optional filters
            if ($request->academic_year) {
                $query->where('academic_year', $request->academic_year);
            }
            if ($request->season) {
                $query->where('season', $request->season);
            }

            $isPublish = $request->action === 'publish';
            $affected = $query->update([
                'is_published' => $isPublish,
                'published_at' => $isPublish ? now() : null,
            ]);

            $sessionLabel = $request->session_type === 'normal' ? 'normale' : 'rattrapage';
            $actionLabel = $isPublish ? 'publiés' : 'dépubliés';

            return back()->with('success', "{$affected} examen(s) de session {$sessionLabel} {$actionLabel}");
        } catch (\Exception $e) {
            Log::error('Session publication toggle error: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la modification: ' . $e->getMessage());
        }
    }
}
