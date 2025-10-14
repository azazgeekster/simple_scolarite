<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Module;
use App\Models\StudentEnrollment;
use App\Models\AcademicYear;
use Illuminate\Support\Collection;

class LMDEnrollmentService
{
    /**
     * Get all available modules for a student in current academic year
     */
    public function getAvailableModules(Student $student, int $academicYearId): Collection
    {
        // Get student's current enrollment
        $currentEnrollment = $student->academicFilieres()
            ->where('academic_year_id', $academicYearId)
            ->firstOrFail();

        $yearInProgram = $currentEnrollment->year_in_program; // e.g., 2 (L2)
        $filiereId = $currentEnrollment->filiere_id;
        // Get current year's semesters (L2 = S3, S4)
        $currentSemesters = $this->getSemestersForYear($yearInProgram);


        // Get all modules for current year/semesters
        $allModules = Module::where('filiere_id', $filiereId)
            ->whereIn('semester_id', $currentSemesters)
            ->with('prerequisite')
            ->get();
 

        // Get modules to retake from previous years
        $retakeModules = $this->getModulesToRetake($student, $yearInProgram, $academicYearId);

        // Filter available modules
        $availableModules = collect();

        foreach ($allModules as $module) {
            if ($this->canEnrollInModule($student, $module, $academicYearId)) {
                $availableModules->push($module);
            }
        }

        // Add retake modules
        $availableModules = $availableModules->merge($retakeModules);

        return $availableModules;
    }

    /**
     * Check if student can enroll in a specific module
     */
    public function canEnrollInModule(Student $student, Module $module, int $academicYearId): bool
    {
        // Rule 1: Check prerequisite (Math II requires Math I)
        if ($module->prerequisite_id) {
            $prerequisitePassed = StudentEnrollment::where('student_id', $student->id)
                ->where('module_id', $module->prerequisite_id)
                ->where('status', 'passed')
                ->exists();

            if (!$prerequisitePassed) {
                return false; // ❌ Prerequisite not met
            }
        }

        // Rule 2: Check if already passed this module
        $alreadyPassed = StudentEnrollment::where('student_id', $student->id)
            ->where('module_id', $module->id)
            ->where('status', 'passed')
            ->exists();

        if ($alreadyPassed) {
            return false; // ❌ Already passed, no need to retake
        }

        // Rule 3: Check semester correspondence blocking
        // This is automatically handled by getModulesToRetake()
        // If a module from paired semester was failed, it appears in retake list

        return true; // ✅ Module is available
    }

    /**
     * Get modules student must retake this year (failed from paired semesters)
     */
    public function getModulesToRetake(Student $student, int $currentYearInProgram, int $academicYearId): Collection
    {
        // Get current year's semesters (e.g., L2 = [3, 4] meaning S3, S4)
        $currentSemesters = $this->getSemestersForYear($currentYearInProgram);

        // Get paired semesters (S3 pairs with S1, S4 pairs with S2)
        $pairedSemesters = array_map(
            fn($sem) => $this->getPairedSemester($sem),
            $currentSemesters
        );

        // Get all failed modules from paired semesters in previous years
        $failedModules = StudentEnrollment::where('student_id', $student->id)
            ->whereIn('semester_id', $pairedSemesters)
            ->where('academic_year_id', '<', $academicYearId)
            ->where('status', 'failed')
            ->with('module')
            ->get();

        // Filter out modules that were later passed
        $modulesToRetake = $failedModules->filter(function($enrollment) use ($student, $academicYearId) {
            $laterPassed = StudentEnrollment::where('student_id', $student->id)
                ->where('module_id', $enrollment->module_id)
                ->where('academic_year_id', '<', $academicYearId)
                ->where('status', 'passed')
                ->exists();

            return !$laterPassed; // Only include if NOT passed later
        });

        return $modulesToRetake->pluck('module');
    }

    /**
     * Get semester IDs for a given year in program
     */
    private function getSemestersForYear(int $year): array
    {
        return match($year) {
            1 => [1, 2], // L1: S1, S2
            2 => [3, 4], // L2: S3, S4
            3 => [5, 6], // L3: S5, S6
            4 => [7, 8], // M1: S7, S8
            5 => [9, 10], // M2: S9, S10
            default => [],
        };
    }

    /**
     * Get paired semester for correspondence rule
     */
    private function getPairedSemester(int $semesterId): int
    {
        return match($semesterId) {
            1 => 3, 3 => 1,  // S1 ↔ S3
            2 => 4, 4 => 2,  // S2 ↔ S4
            5 => 7, 7 => 5,  // S5 ↔ S7
            6 => 8, 8 => 6,  // S6 ↔ S8
            default => $semesterId,
        };
    }

    /**
     * Auto-enroll student in appropriate modules for new academic year
     */
    public function autoEnrollStudent(Student $student, int $academicYearId): void
    {
        $availableModules = $this->getAvailableModules($student, $academicYearId);

        foreach ($availableModules as $module) {
            // Check if already enrolled
            $alreadyEnrolled = StudentEnrollment::where('student_id', $student->id)
                ->where('module_id', $module->id)
                ->where('academic_year_id', $academicYearId)
                ->exists();

            if (!$alreadyEnrolled) {
                StudentEnrollment::create([
                    'student_id' => $student->id,
                    'module_id' => $module->id,
                    'academic_year_id' => $academicYearId,
                    'semester_id' => $module->semester_id,
                    'status' => 'enrolled',
                ]);
            }
        }
    }

    /**
     * Get enrollment summary for student
     */
    public function getEnrollmentSummary( $student, int $academicYearId): array
    {
        $currentEnrollment = $student->academicFilieres()
            ->where('academic_year_id', $academicYearId)
            ->first();

        if (!$currentEnrollment) {
            return [];
        }

        $availableModules = $this->getAvailableModules($student, $academicYearId);
        $retakeModules = $this->getModulesToRetake($student, $currentEnrollment->year_in_program, $academicYearId);

        // Categorize modules
        $normalModules = $availableModules->reject(function($module) use ($retakeModules) {
            return $retakeModules->contains('id', $module->id);
        });

        $blockedModules = $this->getBlockedModules($student, $currentEnrollment, $academicYearId);

        return [
            'year_in_program' => $currentEnrollment->year_in_program,
            'retake_modules' => $retakeModules,
            'normal_modules' => $normalModules,
            'blocked_modules' => $blockedModules,
            'total_modules' => $availableModules->count(),
        ];
    }

    /**
     * Get modules that are blocked for the student
     */
    private function getBlockedModules(Student $student, $enrollment, int $academicYearId): Collection
    {
        $yearInProgram = $enrollment->year_in_program;
        $filiereId = $enrollment->filiere_id;
        $currentSemesters = $this->getSemestersForYear($yearInProgram);

        // Get all modules for current semesters
        $allModules = Module::where('filiere_id', $filiereId)
            ->whereIn('semester_id', $currentSemesters)
            ->with('prerequisite')
            ->get();

        // Filter to only blocked ones
        return $allModules->reject(function($module) use ($student, $academicYearId) {
            return $this->canEnrollInModule($student, $module, $academicYearId);
        });
    }
}