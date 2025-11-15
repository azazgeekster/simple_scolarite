<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExamLocal;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ExamLocalController extends Controller
{
    /**
     * Display a listing of exam locals
     */
    public function index(Request $request)
    {
        $query = ExamLocal::query();

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by bloc
        if ($request->filled('bloc')) {
            $query->where('bloc', $request->bloc);
        }

        // Filter by active status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Search by code or name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }

        // Order by code
        $locals = $query->orderBy('type')
                       ->orderBy('bloc')
                       ->orderBy('code')
                       ->paginate(20)
                       ->withQueryString();

        return view('admin.exam-locals.index', compact('locals'));
    }

    /**
     * Show the form for creating a new exam local
     */
    public function create()
    {
        return view('admin.exam-locals.create');
    }

    /**
     * Store a newly created exam local
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:exam_locals,code',
            'name' => 'required|string|max:255',
            'type' => 'required|in:salle,amphi',
            'bloc' => 'nullable|in:F,E,AMPHI',
            'capacity' => 'required|integer|min:1|max:500',
            'rows' => 'nullable|integer|min:1|max:50',
            'seats_per_row' => 'nullable|integer|min:1|max:50',
            'is_active' => 'boolean',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Ensure rows and seats_per_row match capacity if provided
        if ($validated['rows'] && $validated['seats_per_row']) {
            $calculatedCapacity = $validated['rows'] * $validated['seats_per_row'];
            if ($calculatedCapacity !== $validated['capacity']) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors(['capacity' => "La capacité doit correspondre à (Rangées × Places par rangée) = {$calculatedCapacity}"]);
            }
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        ExamLocal::create($validated);

        return redirect()
            ->route('admin.exam-locals.index')
            ->with('success', 'Local d\'examen créé avec succès');
    }

    /**
     * Display the specified exam local
     */
    public function show(ExamLocal $examLocal)
    {
        $examLocal->load(['exams' => function($query) {
            $query->with('module', 'examPeriod')
                  ->orderBy('exam_date', 'desc')
                  ->limit(10);
        }]);

        $stats = [
            'total_exams' => $examLocal->exams()->count(),
            'upcoming_exams' => $examLocal->exams()
                ->where('exam_date', '>=', now()->toDateString())
                ->count(),
            'total_students_allocated' => $examLocal->seatAssignments()->count(),
        ];

        return view('admin.exam-locals.show', compact('examLocal', 'stats'));
    }

    /**
     * Show the form for editing the specified exam local
     */
    public function edit(ExamLocal $examLocal)
    {
        return view('admin.exam-locals.edit', compact('examLocal'));
    }

    /**
     * Update the specified exam local
     */
    public function update(Request $request, ExamLocal $examLocal)
    {
        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('exam_locals')->ignore($examLocal->id)
            ],
            'name' => 'required|string|max:255',
            'type' => 'required|in:salle,amphi',
            'bloc' => 'nullable|in:F,E,AMPHI',
            'capacity' => 'required|integer|min:1|max:500',
            'rows' => 'nullable|integer|min:1|max:50',
            'seats_per_row' => 'nullable|integer|min:1|max:50',
            'is_active' => 'boolean',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Ensure rows and seats_per_row match capacity if provided
        if ($validated['rows'] && $validated['seats_per_row']) {
            $calculatedCapacity = $validated['rows'] * $validated['seats_per_row'];
            if ($calculatedCapacity !== $validated['capacity']) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors(['capacity' => "La capacité doit correspondre à (Rangées × Places par rangée) = {$calculatedCapacity}"]);
            }
        }

        $validated['is_active'] = $request->boolean('is_active');

        $examLocal->update($validated);

        return redirect()
            ->route('admin.exam-locals.index')
            ->with('success', 'Local d\'examen mis à jour avec succès');
    }

    /**
     * Remove the specified exam local
     */
    public function destroy(ExamLocal $examLocal)
    {
        // Check if local has any seat assignments
        if ($examLocal->seatAssignments()->exists()) {
            return redirect()
                ->route('admin.exam-locals.index')
                ->with('error', 'Impossible de supprimer un local qui a des allocations de sièges');
        }

        // Check if local is linked to any exams
        if ($examLocal->exams()->exists()) {
            return redirect()
                ->route('admin.exam-locals.index')
                ->with('error', 'Impossible de supprimer un local qui est lié à des examens');
        }

        $examLocal->delete();

        return redirect()
            ->route('admin.exam-locals.index')
            ->with('success', 'Local d\'examen supprimé avec succès');
    }

    /**
     * Toggle active status
     */
    public function toggleStatus(ExamLocal $examLocal)
    {
        $examLocal->update([
            'is_active' => !$examLocal->is_active
        ]);

        $status = $examLocal->is_active ? 'activé' : 'désactivé';

        return redirect()
            ->back()
            ->with('success', "Local {$status} avec succès");
    }
}
