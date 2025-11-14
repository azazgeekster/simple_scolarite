<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ModuleGrade;
use App\Models\StudentModuleEnrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GradesController extends Controller
{
    public function index(Request $request)
    {
        $student = Auth::guard('student')->user();

        // Get all enrollments with academic years
        $allEnrollments = $student->programEnrollments()
            ->with(['filiere', 'academicYear'])
            ->orderBy('academic_year', 'desc')
            ->get();

        if ($allEnrollments->isEmpty()) {
            return view('student.grades.no-enrollment', compact('student'));
        }

        // Get selected year or default to current
        $selectedYear = $request->get('year');

        if ($selectedYear) {
            $enrollment = $allEnrollments->firstWhere('academic_year', $selectedYear);
        } else {
            $enrollment = $allEnrollments->firstWhere(fn($e) => $e->academicYear?->is_current)
                       ?? $allEnrollments->first();
        }

        if (!$enrollment) {
            return view('student.grades.no-enrollment', compact('student'));
        }

        // Get module enrollments with grades (MUCH more efficient with final_grade in enrollments)
        $gradesBySemester = [];

        foreach ($enrollment->getSemestersForYear() as $semester) {
            // Get all module enrollments for this semester with published grades
            $moduleEnrollments = StudentModuleEnrollment::where('student_id', $student->id)
                ->where('program_enrollment_id', $enrollment->id)
                ->whereHas('module', function($q) use ($semester) {
                    $q->where('semester', $semester);
                })
                ->whereNotNull('final_grade') // Only show if there's a final grade
                ->with([
                    'module.professor',
                    'grades' => function($q) {
                        // Load session grades for detail view
                        $q->where('is_published', true)->whereNotNull('grade');
                    }
                ])
                ->get();

            // Separate grades by session for display
            $normalGrades = $moduleEnrollments->map(function($enrollment) {
                return $enrollment->normalGrade;
            })->filter()->filter(fn($g) => $g->is_published);

            $rattrapageGrades = $moduleEnrollments->map(function($enrollment) {
                return $enrollment->rattrapageGrade;
            })->filter()->filter(fn($g) => $g->is_published);

            // Calculate stats using final_grade (already calculated!)
            $gradesBySemester[$semester] = [
                'enrollments' => $moduleEnrollments, // Pass enrollments instead of grades
                'normal_grades' => $normalGrades,
                'rattrapage_grades' => $rattrapageGrades,
                'average' => $moduleEnrollments->avg('final_grade'),
                'passed_modules' => $moduleEnrollments->filter->isPassed()->count(),
                'total_modules' => $moduleEnrollments->count(),
            ];
        }

        return view('student.grades.index', compact(
            'student',
            'enrollment',
            'allEnrollments',
            'gradesBySemester'
        ));
    }

    // Removed getValidationStatus - now using $grade->result field from database
}