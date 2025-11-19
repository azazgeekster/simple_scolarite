<?php

namespace App\Console\Commands;

use App\Models\Exam;
use App\Models\ExamConvocation;
use App\Models\StudentModuleEnrollment;
use Illuminate\Console\Command;

class BackfillExamConvocations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exams:backfill-convocations {--exam-id= : Specific exam ID to backfill}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backfill exam convocations for existing exams that are missing them';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $examId = $this->option('exam-id');

        if ($examId) {
            // Backfill a specific exam
            $exam = Exam::with(['module', 'examPeriod'])->find($examId);
            if (!$exam) {
                $this->error("Exam with ID {$examId} not found!");
                return 1;
            }

            $this->backfillExamConvocations($exam);
        } else {
            // Backfill all exams
            $this->info('Backfilling convocations for all exams...');
            $exams = Exam::with(['module', 'examPeriod'])->get();

            $this->withProgressBar($exams, function ($exam) {
                $this->backfillExamConvocations($exam);
            });

            $this->newLine(2);
            $this->info('Backfill completed!');
        }

        return 0;
    }

    private function backfillExamConvocations(Exam $exam)
    {
        // Check if convocations already exist
        $existingCount = $exam->convocations()->count();

        if ($existingCount > 0) {
            $this->warn("Exam {$exam->id} ({$exam->module->label}) already has {$existingCount} convocations. Skipping...");
            return;
        }

        // Get all student module enrollments for this module in the exam's academic year
        $enrollments = StudentModuleEnrollment::where('module_id', $exam->module_id)
            ->where('semester', $exam->semester)
            ->whereHas('programEnrollment', function($query) use ($exam) {
                $query->where('academic_year', $exam->academic_year)
                      ->where('enrollment_status', 'active');
            })
            ->get();

        $convocationsCreated = 0;

        foreach ($enrollments as $enrollment) {
            ExamConvocation::create([
                'exam_id' => $exam->id,
                'student_module_enrollment_id' => $enrollment->id,
            ]);

            $convocationsCreated++;
        }

        if ($convocationsCreated > 0) {
            $this->info("Created {$convocationsCreated} convocations for Exam {$exam->id} ({$exam->module->label})");
        } else {
            $this->warn("No student enrollments found for Exam {$exam->id} ({$exam->module->label})");
        }
    }
}
