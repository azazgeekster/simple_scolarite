<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Module;
use Illuminate\Support\Facades\Auth;

class StudentSituationController extends Controller
{
    /**
     * Display the student's current academic situation
     * Shows: current filiere, academic year, and modules to study
     */
    public function mySituation()
    {
        $student = Auth::guard('student')->user();

        // Get current enrollment
        $currentEnrollment = $student->programEnrollments()
            ->whereHas('academicYear', fn($q) => $q->where('is_current', true))
            ->with([
                'filiere.department',
                'filiere.coordinator',
                'academicYear'
            ])
            ->first();

        // If no enrollment, show message
        if (!$currentEnrollment) {
            return view('student.mysituation.no-enrollment', [
                'student' => $student
            ]);
        }

        // Get semesters for this year (e.g., L1 = S1,S2  L2 = S3,S4  L3 = S5,S6)
        $semesters = $currentEnrollment->getSemestersForYear();

        // Get all modules for these semesters
        $modulesBySemester = [];

        foreach ($semesters as $semesterCode) {
            $modules = Module::where('filiere_id', $currentEnrollment->filiere_id)
                ->where('year_in_program', $currentEnrollment->year_in_program)
                ->where('semester', $semesterCode)
                // ->where('is_active', true)
                ->with('professor')
                ->orderBy('code')
                ->get();

            $modulesBySemester[$semesterCode] = $modules;
        }

        return view('student.mysituation', [
            'student' => $student,
            'enrollment' => $currentEnrollment,
            'modulesBySemester' => $modulesBySemester
        ]);
    }
}