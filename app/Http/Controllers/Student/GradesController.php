<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ModuleGrade;
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

        // Get grades grouped by semester and session
        $gradesBySemester = [];

        foreach ($enrollment->getSemestersForYear() as $semester) {
            // Get all grades for this semester
            $allGrades = ModuleGrade::where('student_id', $student->id)
                ->whereHas('module', function($q) use ($enrollment, $semester) {
                    $q->where('filiere_id', $enrollment->filiere_id)
                      ->where('year_in_program', $enrollment->year_in_program)
                      ->where('semester', $semester);
                })
                ->with(['module.professor'])
                ->where('is_final', true)
                ->whereNotNull('final_grade') // Only grades that have been entered
                ->get();

            // Add validation status to each grade
            $allGrades->transform(function($grade) {
                $grade->validation_status = $this->getValidationStatus($grade);
                return $grade;
            });

            // Separate by session
            $normalGrades = $allGrades->where('exam_session', 'normal');
            $rattrapageGrades = $allGrades->where('exam_session', 'rattrapage');

            // Calculate overall average (taking best grade for each module)
            $bestGrades = $allGrades->groupBy('module_id')->map(function($grades) {
                return $grades->sortByDesc('final_grade')->first();
            });

            $gradesBySemester[$semester] = [
                'normal_grades' => $normalGrades,
                'rattrapage_grades' => $rattrapageGrades,
                'average' => $bestGrades->isNotEmpty() ? $bestGrades->avg('final_grade') : null,
                'passed_modules' => $bestGrades->where('final_grade', '>=', 10)->count(),
                'total_modules' => $bestGrades->count(),
            ];
        }

        return view('student.grades.index', compact(
            'student',
            'enrollment',
            'allEnrollments',
            'gradesBySemester'
        ));
    }

    /**
     * Determine validation status of a grade
     */
    private function getValidationStatus($grade): array
    {
        // Handle null final_grade
        if (is_null($grade->final_grade)) {
            return [
                'label' => 'Non noté',
                'color' => 'gray',
                'icon' => 'question',
                'passed' => false,
            ];
        }

        $finalGrade = $grade->final_grade;

        // Validé (normal or rattrapage)
        if ($finalGrade >= 10) {
            if ($grade->exam_session === 'rattrapage') {
                return [
                    'label' => 'Validé après rattrapage',
                    'color' => 'blue',
                    'icon' => 'check-circle',
                    'passed' => true,
                ];
            }
            return [
                'label' => 'Validé',
                'color' => 'green',
                'icon' => 'check-circle',
                'passed' => true,
            ];
        }

        // Validé par compensation (8 <= note < 10)
        if ($finalGrade >= 8 && $finalGrade < 10) {
            return [
                'label' => 'Validé par compensation',
                'color' => 'yellow',
                'icon' => 'exclamation-circle',
                'passed' => true,
            ];
        }

        // Ajourné (note < 8)
        return [
            'label' => 'Ajourné',
            'color' => 'red',
            'icon' => 'x-circle',
            'passed' => false,
        ];
    }
}