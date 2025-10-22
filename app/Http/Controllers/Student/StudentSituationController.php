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

        // Get all enrollments (history)
        $allEnrollments = $student->programEnrollments()
            ->with([
                'filiere.department',
                'filiere.coordinator',
                'academicYear'
            ])
            ->orderBy('academic_year', 'desc')
            ->get();

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

        // Get all modules for these semesters
        $modulesBySemester = [];

        foreach ($semesters as $semesterCode) {
            $modules = Module::where('filiere_id', $currentEnrollment->filiere_id)
                ->where('year_in_program', $currentEnrollment->year_in_program)
                ->where('semester', $semesterCode)
                ->with('professor')
                ->orderBy('code')
                ->get();

            $modulesBySemester[$semesterCode] = $modules;
        }

        return view('student.mysituation', [
            'student' => $student,
            'enrollment' => $currentEnrollment,
            'allEnrollments' => $allEnrollments,
            'modulesBySemester' => $modulesBySemester
        ]);
    }
}