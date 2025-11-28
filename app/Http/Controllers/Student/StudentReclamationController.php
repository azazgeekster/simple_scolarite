<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ModuleGrade;
use App\Models\Reclamation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StudentReclamationController extends Controller
{
    /**
     * Display a listing of the student's reclamations
     */
    public function index()
    {
        $student = Auth::guard('student')->user();

        $reclamations = Reclamation::whereHas('moduleGrade.moduleEnrollment', function($q) use ($student) {
                $q->where('student_id', $student->id);
            })
            ->with(['moduleGrade.moduleEnrollment.module'])
            ->latest()
            ->get();

        // Group by status for better organization
        $groupedReclamations = [
            'active' => $reclamations->whereIn('status', ['PENDING', 'UNDER_REVIEW']),
            'closed' => $reclamations->whereIn('status', ['RESOLVED', 'REJECTED']),
        ];

        return view('student.reclamations.index', compact('reclamations', 'groupedReclamations'));
    }

    /**
     * Show the form for creating a new reclamation
     */
    public function create(ModuleGrade $moduleGrade)
    {
        $student = Auth::guard('student')->user();

        // Verify this grade belongs to the student
        if ($moduleGrade->student->id !== $student->id) {
            abort(403, 'Vous n\'avez pas accès à cette note.');
        }

        // Check if can submit reclamation
        if (!$moduleGrade->canSubmitReclamation()) {
            return redirect()
                ->route('student.grades')
                ->with('error', 'Vous ne pouvez pas soumettre de réclamation pour cette note.');
        }

        $moduleGrade->load('moduleEnrollment.module');

        $reclamationTypes = [
            'grade_calculation_error' => 'Erreur de calcul de note',
            'missing_grade' => 'Note manquante',
            'transcription_error' => 'Erreur de transcription',
            'exam_paper_review' => 'Révision de copie d\'examen',
            'other' => 'Autre',
        ];

        return view('student.reclamations.create', compact('moduleGrade', 'reclamationTypes'));
    }

    /**
     * Store a newly created reclamation
     */
    public function store(Request $request, ModuleGrade $moduleGrade)
    {
        $student = Auth::guard('student')->user();

        // Verify this grade belongs to the student
        if ($moduleGrade->student->id !== $student->id) {
            abort(403, 'Vous n\'avez pas accès à cette note.');
        }

        // Check if can submit reclamation
        if (!$moduleGrade->canSubmitReclamation()) {
            return redirect()
                ->route('student.grades')
                ->with('error', 'Vous ne pouvez pas soumettre de réclamation pour cette note.');
        }

        $validator = Validator::make($request->all(), [
            'reclamation_type' => 'required|in:grade_calculation_error,missing_grade,transcription_error,exam_paper_review,other',
            'reason' => 'required|string|min:10|max:1000',
        ], [
            'reclamation_type.required' => 'Veuillez sélectionner un type de réclamation.',
            'reclamation_type.in' => 'Type de réclamation invalide.',
            'reason.required' => 'Veuillez expliquer votre réclamation.',
            'reason.min' => 'Votre explication doit contenir au moins 10 caractères.',
            'reason.max' => 'Votre explication ne peut pas dépasser 1000 caractères.',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('reclamations.create', $moduleGrade)
                ->withErrors($validator)
                ->withInput();
        }

        $validated = $validator->validated();

        $reclamation = Reclamation::create([
            'module_grade_id' => $moduleGrade->id,
            'reclamation_type' => $validated['reclamation_type'],
            'reason' => $validated['reason'],
            'session' => $moduleGrade->session,
            'status' => 'PENDING',
            'original_grade' => $moduleGrade->grade,
        ]);

        return redirect()
            ->route('reclamations.show', $reclamation)
            ->with('success', 'Votre réclamation a été soumise avec succès. Elle sera examinée par l\'administration.');
    }

    /**
     * Display the specified reclamation
     */
    public function show(Reclamation $reclamation)
    {
        $student = Auth::guard('student')->user();

        // Verify this reclamation belongs to the student
        if ($reclamation->student->id !== $student->id) {
            abort(403, 'Vous n\'avez pas accès à cette réclamation.');
        }

        $reclamation->load(['moduleGrade.moduleEnrollment.module']);

        return view('student.reclamations.show', compact('reclamation'));
    }

    /**
     * Remove the specified reclamation (only if pending)
     */
    public function destroy(Reclamation $reclamation)
    {
        $student = Auth::guard('student')->user();

        // Verify this reclamation belongs to the student
        if ($reclamation->student->id !== $student->id) {
            abort(403, 'Vous n\'avez pas accès à cette réclamation.');
        }

        // Only allow deletion if status is PENDING
        if (!$reclamation->isPending()) {
            return redirect()
                ->route('reclamations.show', $reclamation)
                ->with('error', 'Vous ne pouvez pas annuler une réclamation qui est déjà en cours d\'examen ou traitée.');
        }

        $reclamation->delete();

        return redirect()
            ->route('reclamations.index')
            ->with('success', 'Votre réclamation a été annulée avec succès.');
    }
}
