<?php

namespace App\Observers;

use App\Models\ModuleGrade;

class ModuleGradeObserver
{
    /**
     * Handle the ModuleGrade "creating" event.
     * Ensures denormalized fields match the normalized relationship.
     */
    public function creating(ModuleGrade $grade): void
    {
        // Auto-populate student_id and module_id from moduleEnrollment if not set
        if ($grade->module_enrollment_id && !$grade->student_id) {
            $enrollment = $grade->moduleEnrollment;
            $grade->student_id = $enrollment->student_id;
            $grade->module_id = $enrollment->module_id;
        }
    }

    /**
     * Handle the ModuleGrade "saving" event.
     * Validates denormalized fields before saving.
     */
    public function saving(ModuleGrade $grade): void
    {
        // Validate that denormalized fields match the relationship chain
        if ($grade->module_enrollment_id) {
            $enrollment = $grade->moduleEnrollment;

            if ($grade->student_id && $grade->student_id !== $enrollment->student_id) {
                throw new \Exception(
                    "Data integrity error: grade.student_id ({$grade->student_id}) " .
                    "does not match moduleEnrollment.student_id ({$enrollment->student_id})"
                );
            }

            if ($grade->module_id && $grade->module_id !== $enrollment->module_id) {
                throw new \Exception(
                    "Data integrity error: grade.module_id ({$grade->module_id}) " .
                    "does not match moduleEnrollment.module_id ({$enrollment->module_id})"
                );
            }
        }
    }
}
