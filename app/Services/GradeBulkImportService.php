<?php

namespace App\Services;

use App\Models\AcademicYear;
use App\Models\ExamPeriod;
use App\Models\GradeImportError;
use App\Models\Module;
use App\Models\ModuleGrade;
use App\Models\Student;
use App\Models\StudentModuleEnrollment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;

class GradeBulkImportService
{
    /**
     * Import session grades from Excel file (all filieres, all semesters at once)
     *
     * Expected format:
     * Columns: Apogee | Code Module | Note | Result (optional)
     * System will auto-detect exam period based on active exam_periods
     */
    public function importSessionGrades(
        string $filePath,
        int $academicYear,
        string $session,
        ?int $examPeriodId = null,
        ?int $adminId = null,
        int $chunkSize = 100
    ): array {
        $batchId = Str::uuid()->toString();
        $adminId = $adminId ?? auth('admin')->id();

        try {
            // Load spreadsheet
            $spreadsheet = IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            // Remove header row
            $headers = array_shift($rows);

            // Detect exam period if not provided
            if (!$examPeriodId) {
                $examPeriod = ExamPeriod::where('academic_year', $academicYear)
                    ->where('session_type', $session)
                    ->where('is_active', true)
                    ->first();

                $examPeriodId = $examPeriod?->id;
            }

            $stats = [
                'batch_id' => $batchId,
                'total_rows' => count($rows),
                'processed' => 0,
                'successful' => 0,
                'errors' => 0,
                'skipped' => 0,
                'created' => 0,
                'updated' => 0,
            ];

            // Process in chunks for better performance
            $chunks = array_chunk($rows, $chunkSize);

            foreach ($chunks as $chunkIndex => $chunk) {
                DB::beginTransaction();

                try {
                    foreach ($chunk as $index => $row) {
                        $rowNum = ($chunkIndex * $chunkSize) + $index + 2; // +2 for header and 1-indexing

                        // Skip empty rows
                        if (empty($row[0]) && empty($row[1])) {
                            $stats['skipped']++;
                            continue;
                        }

                        $stats['processed']++;

                        $result = $this->processSessionGradeRow(
                            $row,
                            $rowNum,
                            $academicYear,
                            $session,
                            $examPeriodId,
                            $batchId,
                            $adminId
                        );

                        if ($result['success']) {
                            $stats['successful']++;
                            if ($result['action'] === 'created') {
                                $stats['created']++;
                            } else {
                                $stats['updated']++;
                            }
                        } else {
                            $stats['errors']++;
                        }
                    }

                    DB::commit();

                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error("Chunk import failed: " . $e->getMessage());
                    throw $e;
                }
            }

            return $stats;

        } catch (\Exception $e) {
            Log::error("Bulk import failed: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Process a single row for session grades
     */
    private function processSessionGradeRow(
        array $row,
        int $rowNum,
        int $academicYear,
        string $session,
        ?int $examPeriodId,
        string $batchId,
        int $adminId
    ): array {
        try {
            // Parse row data
            $apogee = trim($row[0] ?? '');
            $moduleCode = trim($row[1] ?? '');
            $grade = $row[2] ?? null;
            $result = $row[3] ?? null; // Optional, system can calculate

            // Validate required fields
            if (empty($apogee)) {
                $this->logError($batchId, $adminId, $rowNum, $row, 'missing_apogee',
                    'Apogee manquant', $academicYear, $session, $examPeriodId, $apogee, $moduleCode);
                return ['success' => false, 'error' => 'missing_apogee'];
            }

            if (empty($moduleCode)) {
                $this->logError($batchId, $adminId, $rowNum, $row, 'missing_module_code',
                    'Code module manquant', $academicYear, $session, $examPeriodId, $apogee, $moduleCode);
                return ['success' => false, 'error' => 'missing_module_code'];
            }

            // Find student
            $student = Student::where('apogee', $apogee)->first();
            if (!$student) {
                $this->logError($batchId, $adminId, $rowNum, $row, 'student_not_found',
                    "Étudiant non trouvé avec Apogee: {$apogee}", $academicYear, $session, $examPeriodId, $apogee, $moduleCode);
                return ['success' => false, 'error' => 'student_not_found'];
            }

            // Find module
            $module = Module::where('code', $moduleCode)->first();
            if (!$module) {
                $this->logError($batchId, $adminId, $rowNum, $row, 'module_not_found',
                    "Module non trouvé avec code: {$moduleCode}", $academicYear, $session, $examPeriodId, $apogee, $moduleCode);
                return ['success' => false, 'error' => 'module_not_found'];
            }

            // Find enrollment
            $enrollment = StudentModuleEnrollment::where('module_id', $module->id)
                ->whereHas('programEnrollment', function ($q) use ($academicYear, $student) {
                    $q->where('academic_year', $academicYear)
                      ->where('student_id', $student->id);
                })
                ->first();

            if (!$enrollment) {
                $this->logError($batchId, $adminId, $rowNum, $row, 'enrollment_not_found',
                    "Inscription non trouvée pour l'étudiant {$apogee} au module {$moduleCode}",
                    $academicYear, $session, $examPeriodId, $apogee, $moduleCode);
                return ['success' => false, 'error' => 'enrollment_not_found'];
            }

            // Validate grade
            if ($grade !== null && $grade !== '') {
                $grade = floatval($grade);
                if ($grade < 0 || $grade > 20) {
                    $this->logError($batchId, $adminId, $rowNum, $row, 'invalid_grade',
                        "Note invalide (doit être entre 0 et 20): {$grade}",
                        $academicYear, $session, $examPeriodId, $apogee, $moduleCode);
                    return ['success' => false, 'error' => 'invalid_grade'];
                }
            }

            // Calculate result if not provided
            if (empty($result)) {
                $result = $this->calculateResult($grade, $session);
            }

            // Determine exam status
            $examStatus = $this->determineExamStatus($grade);

            // Create or update grade
            $existingGrade = ModuleGrade::where('module_enrollment_id', $enrollment->id)
                ->where('session', $session)
                ->first();

            $gradeData = [
                'grade' => $grade,
                'result' => $result,
                'exam_status' => $examStatus,
            ];

            $action = 'updated';
            if ($existingGrade) {
                $existingGrade->update($gradeData);
            } else {
                ModuleGrade::create(array_merge($gradeData, [
                    'module_enrollment_id' => $enrollment->id,
                    'session' => $session,
                    'is_published' => false,
                ]));
                $action = 'created';
            }

            // Recalculate final grade
            $enrollment->calculateFinalGrade();

            return ['success' => true, 'action' => $action];

        } catch (\Exception $e) {
            $this->logError($batchId, $adminId, $rowNum, $row, 'processing_error',
                "Erreur de traitement: " . $e->getMessage(),
                $academicYear, $session, $examPeriodId, $apogee ?? null, $moduleCode ?? null);
            return ['success' => false, 'error' => 'processing_error'];
        }
    }

    /**
     * Import final grades (usually after deliberations)
     *
     * Expected format:
     * Columns: Apogee | Code Module | Note Finale | Resultat Final | Session Finale | Ancienne Note (optional)
     */
    public function importFinalGrades(
        string $filePath,
        int $academicYear,
        ?int $adminId = null,
        int $chunkSize = 100
    ): array {
        $batchId = Str::uuid()->toString();
        $adminId = $adminId ?? auth('admin')->id();

        try {
            $spreadsheet = IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            // Remove header row
            $headers = array_shift($rows);

            $stats = [
                'batch_id' => $batchId,
                'total_rows' => count($rows),
                'processed' => 0,
                'successful' => 0,
                'errors' => 0,
                'skipped' => 0,
                'updated' => 0,
            ];

            $chunks = array_chunk($rows, $chunkSize);

            foreach ($chunks as $chunkIndex => $chunk) {
                DB::beginTransaction();

                try {
                    foreach ($chunk as $index => $row) {
                        $rowNum = ($chunkIndex * $chunkSize) + $index + 2;

                        if (empty($row[0]) && empty($row[1])) {
                            $stats['skipped']++;
                            continue;
                        }

                        $stats['processed']++;

                        $result = $this->processFinalGradeRow(
                            $row,
                            $rowNum,
                            $academicYear,
                            $batchId,
                            $adminId
                        );

                        if ($result['success']) {
                            $stats['successful']++;
                            $stats['updated']++;
                        } else {
                            $stats['errors']++;
                        }
                    }

                    DB::commit();

                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error("Final grades chunk import failed: " . $e->getMessage());
                    throw $e;
                }
            }

            return $stats;

        } catch (\Exception $e) {
            Log::error("Final grades import failed: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Process a single row for final grades
     */
    private function processFinalGradeRow(
        array $row,
        int $rowNum,
        int $academicYear,
        string $batchId,
        int $adminId
    ): array {
        try {
            $apogee = trim($row[0] ?? '');
            $moduleCode = trim($row[1] ?? '');
            $finalGrade = $row[2] ?? null;
            $finalResult = trim($row[3] ?? '');
            $finalSession = trim($row[4] ?? ''); // 'normal' or 'rattrapage'
            $oldGrade = $row[5] ?? null; // For reference

            // Validate required fields
            if (empty($apogee) || empty($moduleCode)) {
                $this->logError($batchId, $adminId, $rowNum, $row, 'missing_fields',
                    'Champs requis manquants', $academicYear, null, null, $apogee, $moduleCode);
                return ['success' => false];
            }

            // Find student
            $student = Student::where('apogee', $apogee)->first();
            if (!$student) {
                $this->logError($batchId, $adminId, $rowNum, $row, 'student_not_found',
                    "Étudiant non trouvé: {$apogee}", $academicYear, null, null, $apogee, $moduleCode);
                return ['success' => false];
            }

            // Find module
            $module = Module::where('code', $moduleCode)->first();
            if (!$module) {
                $this->logError($batchId, $adminId, $rowNum, $row, 'module_not_found',
                    "Module non trouvé: {$moduleCode}", $academicYear, null, null, $apogee, $moduleCode);
                return ['success' => false];
            }

            // Find enrollment
            $enrollment = StudentModuleEnrollment::where('module_id', $module->id)
                ->whereHas('programEnrollment', function ($q) use ($academicYear, $student) {
                    $q->where('academic_year', $academicYear)
                      ->where('student_id', $student->id);
                })
                ->first();

            if (!$enrollment) {
                $this->logError($batchId, $adminId, $rowNum, $row, 'enrollment_not_found',
                    "Inscription non trouvée", $academicYear, null, null, $apogee, $moduleCode);
                return ['success' => false];
            }

            // Validate final grade
            if ($finalGrade !== null && $finalGrade !== '') {
                $finalGrade = floatval($finalGrade);
                if ($finalGrade < 0 || $finalGrade > 20) {
                    $this->logError($batchId, $adminId, $rowNum, $row, 'invalid_grade',
                        "Note finale invalide: {$finalGrade}", $academicYear, null, null, $apogee, $moduleCode);
                    return ['success' => false];
                }
            }

            // Update enrollment with final grade and result
            $enrollment->update([
                'final_grade' => $finalGrade,
                'final_result' => $finalResult,
            ]);

            // If finalSession is specified, update/create that session's grade
            if ($finalSession && in_array($finalSession, ['normal', 'rattrapage'])) {
                $sessionGrade = ModuleGrade::where('module_enrollment_id', $enrollment->id)
                    ->where('session', $finalSession)
                    ->first();

                if ($sessionGrade) {
                    $sessionGrade->update([
                        'grade' => $finalGrade,
                        'result' => $finalResult,
                    ]);
                } else {
                    ModuleGrade::create([
                        'module_enrollment_id' => $enrollment->id,
                        'session' => $finalSession,
                        'grade' => $finalGrade,
                        'result' => $finalResult,
                        'exam_status' => 'présent',
                        'is_published' => false,
                    ]);
                }
            }

            return ['success' => true];

        } catch (\Exception $e) {
            $this->logError($batchId, $adminId, $rowNum, $row, 'processing_error',
                "Erreur: " . $e->getMessage(), $academicYear, null, null, $apogee ?? null, $moduleCode ?? null);
            return ['success' => false];
        }
    }

    /**
     * Log an import error
     */
    private function logError(
        string $batchId,
        int $adminId,
        int $rowNum,
        array $rowData,
        string $errorType,
        string $errorMessage,
        ?int $academicYear = null,
        ?string $session = null,
        ?int $examPeriodId = null,
        ?string $apogee = null,
        ?string $moduleCode = null
    ): void {
        GradeImportError::create([
            'batch_id' => $batchId,
            'admin_id' => $adminId,
            'import_type' => 'session_grades',
            'row_number' => $rowNum,
            'apogee' => $apogee,
            'module_code' => $moduleCode,
            'error_type' => $errorType,
            'error_message' => $errorMessage,
            'row_data' => $rowData,
            'academic_year' => $academicYear,
            'session' => $session,
            'exam_period_id' => $examPeriodId,
            'status' => 'failed',
        ]);
    }

    /**
     * Calculate result based on grade and session
     */
    private function calculateResult(?float $grade, string $session): string
    {
        if ($grade === null) {
            return 'en attente';
        }

        if ($grade >= 10) {
            return $session === 'rattrapage' ? 'validé après rattrapage' : 'validé';
        }

        if ($session === 'normal' && $grade >= 6) {
            return 'en attente rattrapage';
        }

        return 'non validé';
    }

    /**
     * Determine exam status based on grade
     */
    private function determineExamStatus(?float $grade): string
    {
        if ($grade === null) {
            return 'absent';
        }

        return 'présent';
    }

    /**
     * Get errors for a batch - can be exported
     */
    public function getErrorsForBatch(string $batchId): \Illuminate\Database\Eloquent\Collection
    {
        return GradeImportError::forBatch($batchId)
            ->orderBy('row_number')
            ->get();
    }

    /**
     * Download error report as Excel
     */
    public function downloadErrorReport(string $batchId): string
    {
        $errors = $this->getErrorsForBatch($batchId);

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Erreurs Import');

        // Headers
        $headers = ['Ligne', 'Apogee', 'Code Module', 'Type Erreur', 'Message', 'Données'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }

        // Style header
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);
        $sheet->getStyle('A1:F1')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFE0E0E0');

        // Data
        $row = 2;
        foreach ($errors as $error) {
            $sheet->setCellValue('A' . $row, $error->row_number);
            $sheet->setCellValue('B' . $row, $error->apogee);
            $sheet->setCellValue('C' . $row, $error->module_code);
            $sheet->setCellValue('D' . $row, $error->error_type);
            $sheet->setCellValue('E' . $row, $error->error_message);
            $sheet->setCellValue('F' . $row, json_encode($error->row_data));
            $row++;
        }

        // Auto-size
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $temp = tempnam(sys_get_temp_dir(), 'errors_');
        $writer->save($temp);

        return $temp;
    }
}
