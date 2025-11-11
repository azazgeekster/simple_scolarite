<?php

namespace App\Console\Commands;

use App\Models\Exam;
use App\Models\ModuleGrade;
use Illuminate\Console\Command;

class GenerateRattrapageExams extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exams:generate-rattrapage
                            {academic_year : The academic year (e.g., 2024)}
                            {season : The season (autumn or spring)}
                            {--dry-run : Show what would be created without actually creating}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate rattrapage exam entries for students who failed (grade <10) in normal session';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $academicYear = $this->argument('academic_year');
        $season = $this->argument('season');
        $isDryRun = $this->option('dry-run');

        // Validate season
        if (!in_array($season, ['autumn', 'spring'])) {
            $this->error('Season must be either "autumn" or "spring"');
            return 1;
        }

        $this->info("Generating rattrapage exams for {$academicYear} - {$season} season");
        if ($isDryRun) {
            $this->warn('DRY RUN MODE - No exams will be created');
        }

        // Determine semesters based on season
        $semesters = $season === 'autumn' ? ['S1', 'S3', 'S5'] : ['S2', 'S4', 'S6'];

        // Get all published normal session grades with score <10 for this year/season
        $failedGrades = ModuleGrade::where('session_type', 'normal')
            ->where('is_published', true)
            ->where('grade', '<', 10)
            ->whereHas('moduleEnrollment.programEnrollment', function ($q) use ($academicYear) {
                $q->where('academic_year', $academicYear);
            })
            ->whereHas('moduleEnrollment.module', function ($q) use ($semesters) {
                $q->whereIn('semester', $semesters);
            })
            ->with(['moduleEnrollment.module', 'moduleEnrollment.programEnrollment'])
            ->get();

        if ($failedGrades->isEmpty()) {
            $this->info('No failed students found for this period.');
            return 0;
        }

        $this->info("Found {$failedGrades->count()} failed grades");

        // Group by module to avoid duplicates
        $moduleIds = $failedGrades->pluck('module_id')->unique();

        $created = 0;
        $skipped = 0;

        foreach ($moduleIds as $moduleId) {
            $module = \App\Models\Module::find($moduleId);

            if (!$module) {
                continue;
            }

            // Check if rattrapage exam already exists
            $existingExam = Exam::where('module_id', $moduleId)
                ->where('session_type', 'rattrapage')
                ->where('season', $season)
                ->where('academic_year', $academicYear)
                ->first();

            if ($existingExam) {
                $this->line("  ⏭ Skipping {$module->code} - Rattrapage exam already exists");
                $skipped++;
                continue;
            }

            // Get normal exam to copy details
            $normalExam = Exam::where('module_id', $moduleId)
                ->where('session_type', 'normal')
                ->where('season', $season)
                ->where('academic_year', $academicYear)
                ->first();

            if (!$isDryRun) {
                Exam::create([
                    'module_id' => $moduleId,
                    'session_type' => 'rattrapage',
                    'semester' => $module->semester,
                    'season' => $season,
                    'academic_year' => $academicYear,
                    'exam_date' => now()->addDays(14)->toDateString(), // Placeholder
                    'start_time' => '08:00:00', // Placeholder
                    'end_time' => '10:00:00', // Placeholder
                    'local' => $normalExam->local ?? 'À définir',
                    'is_published' => false,
                    'published_at' => null,
                ]);
            }

            $studentsCount = $failedGrades->where('module_id', $moduleId)->count();
            $this->line("  ✓ Created rattrapage exam for {$module->code} ({$studentsCount} student(s) failed)");
            $created++;
        }

        $this->newLine();
        $this->info("Summary:");
        $this->info("  Created: {$created}");
        $this->info("  Skipped: {$skipped}");

        if (!$isDryRun) {
            $this->newLine();
            $this->warn("⚠ Remember to:");
            $this->warn("  1. Set actual exam dates and times in the admin panel");
            $this->warn("  2. Publish the exams when ready");
        }

        return 0;
    }
}
