<?php

namespace App\Observers;

use App\Models\StudentModuleEnrollment;

class StudentModuleEnrollmentObserver
{
    /**
     * Handle the StudentModuleEnrollment "creating" event.
     * Ensures student_id matches the program enrollment.
     */
    public function creating(StudentModuleEnrollment $enrollment): void
    {
        // Auto-populate student_id from programEnrollment if not set
        if ($enrollment->program_enrollment_id && !$enrollment->student_id) {
            $programEnrollment = $enrollment->programEnrollment;
            $enrollment->student_id = $programEnrollment->student_id;
        }
    }

    /**
     * Handle the StudentModuleEnrollment "saving" event.
     * Validates that student_id matches the program enrollment.
     */
    public function saving(StudentModuleEnrollment $enrollment): void
    {
        // Validate student_id matches program enrollment
        if ($enrollment->program_enrollment_id && $enrollment->student_id) {
            $programEnrollment = $enrollment->programEnrollment;

            if ($enrollment->student_id !== $programEnrollment->student_id) {
                throw new \Exception(
                    "Data integrity error: moduleEnrollment.student_id ({$enrollment->student_id}) " .
                    "does not match programEnrollment.student_id ({$programEnrollment->student_id})"
                );
            }
        }
    }
}
