<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentSituationController extends Controller
{
    /**
     * Display the student's academic situation with history support
     */
    public function mySituation(Request $request)
    {
        $student = Auth::guard('student')->user();

        // Get all enrollments (history) with relationships
        $allEnrollments = $student->programEnrollments()
            ->with([
                'filiere.department',
                'filiere.coordinator',
                'academicYear'
            ])
            ->orderBy('academic_year', 'desc')
            ->get();

        // Add computed properties to each enrollment
        $allEnrollments->transform(function ($enrollment) {
            // Add status badge info
            $enrollment->status_badge = $this->getStatusBadge($enrollment->enrollment_status);

            return $enrollment;
        });

        // If no enrollment at all
        if ($allEnrollments->isEmpty()) {
            return view('student.mysituation.no-enrollment', [
                'student' => $student
            ]);
        }

        // Get selected year from request, or default to current
        $selectedYear = $request->get('year');

        if ($selectedYear) {
            $currentEnrollment = $allEnrollments->firstWhere('academic_year', $selectedYear);
        } else {
            // Try to get current year enrollment first
            $currentEnrollment = $allEnrollments->firstWhere(function($enrollment) {
                return $enrollment->academicYear && $enrollment->academicYear->is_current;
            });

            // If no current year enrollment, get the most recent
            if (!$currentEnrollment) {
                $currentEnrollment = $allEnrollments->first();
            }
        }

        // If still no enrollment found
        if (!$currentEnrollment) {
            return view('student.mysituation.no-enrollment', [
                'student' => $student
            ]);
        }

        // Get semesters for this year
        $semesters = $currentEnrollment->getSemestersForYear();

        // Get all modules for these semesters with enhanced data
        $modulesBySemester = [];

        foreach ($semesters as $semesterCode) {
            $modules = Module::where('filiere_id', $currentEnrollment->filiere_id)
                ->where('year_in_program', $currentEnrollment->year_in_program)
                ->where('semester', $semesterCode)
                ->with(['professor', 'prerequisite'])
                ->orderBy('code')
                ->get();

            // Enrich each module with enrollment status
            $modules->each(function($module) use ($student, $currentEnrollment) {
                // Check if student has an enrollment for this module
                $moduleEnrollment = $student->moduleEnrollments()
                    ->where('module_id', $module->id)
                    ->where('program_enrollment_id', $currentEnrollment->id)
                    ->with('grade')
                    ->first();

                if ($moduleEnrollment) {
                    $module->enrollment_status = 'enrolled';
                    $module->attempt_number = $moduleEnrollment->attempt_number;

                    // Student Situation shows enrollment only, NOT grades
                    // Grades are shown separately in the Grades section when published
                    $module->validation_status = 'enrolled';
                } else {
                    $module->enrollment_status = 'not_enrolled';
                    $module->validation_status = 'not_started';
                }

                // Check if module is blocked by prerequisite
                // Note: Prerequisite validation uses published grades only
                if ($module->prerequisite_id && $module->prerequisite) {
                    // Validate that the prerequisite relationship is valid based on semester rules
                    if ($module->isValidPrerequisite($module->prerequisite)) {
                        // Check if prerequisite was validated (using published grades only)
                        $prerequisiteValidated = $student->grades()
                            ->where('module_id', $module->prerequisite_id)
                            ->where('is_final', true)
                            ->where('is_published', true) // Only check published grades
                            ->where('final_grade', '>=', 10)
                            ->exists();

                        $module->prerequisite_validated = $prerequisiteValidated;
                        if (!$prerequisiteValidated && $module->enrollment_status === 'not_enrolled') {
                            $module->validation_status = 'blocked';
                        }
                    } else {
                        // Invalid prerequisite relationship - ignore it
                        $module->prerequisite_validated = true;
                        $module->invalid_prerequisite = true;
                    }
                }
            });

            $modulesBySemester[$semesterCode] = $modules;
        }

        // Calculate summary statistics
        $totalModules = collect($modulesBySemester)->flatten()->count();
        $totalCredits = collect($modulesBySemester)->flatten()->sum('credits');
        $obligatoryModules = collect($modulesBySemester)->flatten()->where('is_optional', false)->count();
        $optionalModules = collect($modulesBySemester)->flatten()->where('is_optional', true)->count();

        return view('student.mysituation', [
            'student' => $student,
            'enrollment' => $currentEnrollment,
            'allEnrollments' => $allEnrollments,
            'modulesBySemester' => $modulesBySemester,
            'stats' => [
                'total_modules' => $totalModules,
                'total_credits' => $totalCredits,
                'obligatory_modules' => $obligatoryModules,
                'optional_modules' => $optionalModules,
            ]
        ]);
    }

    /**
     * Get status badge configuration
     */
    private function getStatusBadge(string $status): array
    {
        return match($status) {
            'active' => [
                'label' => 'Actif',
                'color' => 'green',
                'bg_class' => 'bg-green-100',
                'text_class' => 'text-green-800',
            ],
            'completed' => [
                'label' => 'Terminé',
                'color' => 'blue',
                'bg_class' => 'bg-blue-100',
                'text_class' => 'text-blue-800',
            ],
            'failed' => [
                'label' => 'Échoué',
                'color' => 'red',
                'bg_class' => 'bg-red-100',
                'text_class' => 'text-red-800',
            ],
            'withdrawn' => [
                'label' => 'Retiré',
                'color' => 'gray',
                'bg_class' => 'bg-gray-100',
                'text_class' => 'text-gray-800',
            ],
            'transferred_out' => [
                'label' => 'Transféré',
                'color' => 'yellow',
                'bg_class' => 'bg-yellow-100',
                'text_class' => 'text-yellow-800',
            ],
            'suspended' => [
                'label' => 'Suspendu',
                'color' => 'orange',
                'bg_class' => 'bg-orange-100',
                'text_class' => 'text-orange-800',
            ],
            default => [
                'label' => 'Inconnu',
                'color' => 'gray',
                'bg_class' => 'bg-gray-100',
                'text_class' => 'text-gray-800',
            ]
        };
    }
}