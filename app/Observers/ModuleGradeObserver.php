<?php

namespace App\Observers;

use App\Models\ModuleGrade;

class ModuleGradeObserver
{
    /**
     * Handle the ModuleGrade "creating" event.
     */
    public function creating(ModuleGrade $grade): void
    {
        // Note: student_id and module_id columns have been removed from module_grades table
        // The relationship is now managed solely through module_enrollment_id
        // No denormalization needed
    }

    /**
     * Handle the ModuleGrade "saving" event.
     */
    public function saving(ModuleGrade $grade): void
    {
        // Note: Denormalized fields (student_id, module_id) have been removed
        // All data is accessed through the module_enrollment_id relationship
        // No validation needed here
    }
}
