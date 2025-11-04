<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentExamController extends Controller
{
    /**
     * Display a listing of exams for the authenticated student
     */
    public function index()
    {
        $student = Auth::guard('student')->user();

        // Get student's current enrollment to find their modules
        $currentEnrollment = $student->currentProgramEnrollment();

        if (!$currentEnrollment) {
            return view('student.exams.index', [
                'upcomingExams' => collect([]),
                'pastExams' => collect([]),
            ]);
        }

        // Get module IDs for the student's filiere and year
        $moduleIds = Module::where('filiere_id', $currentEnrollment->filiere_id)
            ->where('year_in_program', $currentEnrollment->year_in_program)
            ->pluck('id');

        // Get upcoming exams
        $upcomingExams = Exam::published()
            ->upcoming()
            ->whereIn('module_id', $moduleIds)
            ->with(['module', 'academicYear'])
            ->get();

        // Get past exams
        $pastExams = Exam::published()
            ->past()
            ->whereIn('module_id', $moduleIds)
            ->with(['module', 'academicYear'])
            ->take(10)
            ->get();

        return view('student.exams.index', compact('upcomingExams', 'pastExams'));
    }

    /**
     * Display the specified exam
     */
    public function show(Exam $exam)
    {
        $student = Auth::guard('student')->user();

        // Verify student has access to this exam
        $currentEnrollment = $student->currentProgramEnrollment();

        if (!$currentEnrollment) {
            abort(403, 'Vous n\'êtes pas inscrit pour l\'année en cours.');
        }

        // Check if exam's module belongs to student's filiere and year
        $hasAccess = Module::where('id', $exam->module_id)
            ->where('filiere_id', $currentEnrollment->filiere_id)
            ->where('year_in_program', $currentEnrollment->year_in_program)
            ->exists();

        if (!$hasAccess || !$exam->is_published) {
            abort(403, 'Vous n\'avez pas accès à cet examen.');
        }

        $exam->load(['module', 'academicYear']);

        return view('student.exams.show', compact('exam'));
    }

    /**
     * Display exam convocation
     */
    public function convocation(Exam $exam)
    {
        $student = Auth::guard('student')->user();

        // Verify student has access to this exam
        $currentEnrollment = $student->currentProgramEnrollment();

        if (!$currentEnrollment) {
            abort(403, 'Vous n\'êtes pas inscrit pour l\'année en cours.');
        }

        // Check if exam's module belongs to student's filiere and year
        $hasAccess = Module::where('id', $exam->module_id)
            ->where('filiere_id', $currentEnrollment->filiere_id)
            ->where('year_in_program', $currentEnrollment->year_in_program)
            ->exists();

        if (!$hasAccess || !$exam->is_published) {
            abort(403, 'Vous n\'avez pas accès à cette convocation.');
        }

        $exam->load(['module', 'academicYear']);

        return view('student.exams.convocation-single', compact('exam', 'student'));
    }
}
