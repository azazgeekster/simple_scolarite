<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Demande;
use App\Models\Document;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DemandeController extends Controller
{

    protected $student;

    public function __construct()
    {
        $student = auth('student')->user();
    }
    // In the index method, add this:
    public function index()
    {
        $student = auth('student')->user();
        $demandes = $student->demandes()->with(['document', 'academicYear'])->latest()->get();

        // Add overdue check
        $overdueDemandes = $demandes->filter(fn ($d) => $d->isOverdue());

        $documents = Document::all();
        $years = AcademicYear::orderByDesc('start_year')->get();

        return view('student.demandes.index', compact('demandes', 'documents', 'years', 'overdueDemandes'));
    }
    public function store(Request $request)
    {
        // Validate request - support both single and multiple documents
        $rules = [
            'document_ids' => 'required|array|min:1',
            'document_ids.*' => 'exists:documents,id',
        ];

        // Get documents to check if any require return
        $documentIds = $request->document_ids ?? [];
        $documents = Document::whereIn('id', $documentIds)->get();
        $anyRequiresReturn = $documents->contains('requires_return', true);

        // Only require retrait_type if any document requires return
        if ($anyRequiresReturn) {
            $rules['retrait_type'] = 'required|in:temporaire,permanent';
        }

        $request->validate($rules, [
            'document_ids.required' => 'Veuillez sélectionner au moins un document.',
            'document_ids.min' => 'Veuillez sélectionner au moins un document.',
            'retrait_type.required' => 'Veuillez sélectionner un type de retrait.',
            'retrait_type.in' => 'Le type de retrait sélectionné est invalide.',
        ]);

        $student = auth('student')->user();

        // Check for overdue documents
        $hasOverdue = $student->demandes()
            ->where('retrait_type', 'temporaire')
            ->whereNotNull('must_return_by')
            ->where('must_return_by', '<', now())
            ->whereNull('returned_at')
            ->exists();

        if ($hasOverdue) {
            return back()->with('error', 'Vous avez des documents en retard. Veuillez les retourner avant de faire une nouvelle demande.');
        }

        // Get current academic year
        $currentYear = now()->year;
        $academicYear = AcademicYear::where('start_year', '<=', $currentYear)
            ->where('end_year', '>=', $currentYear)
            ->first();

        if (! $academicYear) {
            return back()->with('error', 'Aucune année académique active trouvée.');
        }

        // Check for duplicate requests and create demandes
        $created = 0;
        $duplicates = [];

        foreach ($documents as $document) {
            // Check for duplicate requests
            $alreadyToday = $student->demandes()
                ->where('document_id', $document->id)
                ->whereDate('created_at', now()->toDateString())
                ->exists();

            if ($alreadyToday) {
                $duplicates[] = $document->label_fr;
                continue;
            }

            // Create demande
            $demandeData = [
                'document_id' => $document->id,
                'academic_year' => $academicYear->start_year,
                'status' => 'PENDING',
            ];

            // Only set retrait_type if document requires return
            if ($document->requires_return) {
                $demandeData['retrait_type'] = $request->retrait_type;

                // Set return deadline if temporary
                if ($request->retrait_type === 'temporaire') {
                    $demandeData['must_return_by'] = now()->addDays(2);
                }
            } else {
                // For documents that don't require return, set as definitif by default
                $demandeData['retrait_type'] = 'permanent';
            }

            $student->demandes()->create($demandeData);
            $created++;
        }

        // Build response message
        if ($created === 0 && count($duplicates) > 0) {
            return back()->with('error', 'Vous avez déjà effectué ces demandes aujourd\'hui: ' . implode(', ', $duplicates));
        }

        $message = $created === 1
            ? 'Votre demande a été soumise avec succès.'
            : "{$created} demande(s) soumise(s) avec succès.";

        if (count($duplicates) > 0) {
            $message .= ' (Demandes ignorées car déjà effectuées: ' . implode(', ', $duplicates) . ')';
        }

        return back()->with('success', $message);
    }

    public function print(Demande $demande)
    {
        $student = auth('student')->user();

        if ($demande->student_id !== $student->id) {
            abort(403);
        }

        $pdf = Pdf::loadView('student.pdf.demande_retrait', compact('demande'));

        return $pdf->stream('demande_bac_'.$demande->id.'.pdf');
    }
    public function cancel(Demande $demande)
    {
        $student = auth('student')->user();

        // Check ownership and status
        if ($demande->student_id !== $student->id || $demande->status !== 'PENDING') {
            abort(403, 'Vous ne pouvez pas annuler cette demande.');
        }

        // Check if cancellation is still allowed (within 30 minutes of creation)
        if (! $demande->canBeCancelled())
            return back()->with('error', 'L\'annulation de cette demande n\'est pas permise');


        $demande->delete();

        return back()->with('success', 'Demande annulée avec succès.');
    }

    public function requestExtension(Request $request, Demande $demande)
    {
        $request->validate([
            'extra_days' => 'required|integer|min:1|max:30',
        ]);

        $student = auth('student')->user();

        // ✅ 1. Ensure the demande belongs to the current student
        if ($demande->student_id !== $student->id) {
            return response()->json(['error' => 'Accès non autorisé.'], 403);
        }
        // ✅ 2. Must be in READY status
        if ($demande->status !== 'PICKED') {
            return response()->json(['error' => 'Vous ne pouvez pas demander une extension à ce stade.'], 422);
        }

        // ✅ 3. Must have been picked up at least 48h ago
        $pickupDate = $demande->ready_at ?? $demande->processed_at;
        if (! $pickupDate) {
            return response()->json(['error' => 'La date de retrait est inconnue.'], 422);
        }
        $originalDeadline = Carbon::parse($pickupDate)->addHours(48);

        if (now()->lessThan($originalDeadline)) {
            return response()->json(['error' => 'Vous devez attendre 48h après le retrait pour demander une extension.'], 422);
        }


        // ✅ 4. Prevent requesting a new extension if one is still active
        if ($demande->extension_days) {
            $extendedDeadline = $originalDeadline->copy()->addDays($demande->extension_days);
            // \Log::info($extendedDeadline);
            if (now()->lt($extendedDeadline)) {
                return response()->json(['error' => 'L\'extension précédente est encore en cours.'], 422);
            }
        }

        // ✅ 5. Update with new extension
        $demande->extension_days += $request->extra_days;
        $demande->extension_requested_at = now();
        $demande->save();

        return response()->json(['success' => true]);
    }




    /**
     *
     * FOR RELVE DE NOTES
     *
     */
    public function index_releve()
    {
        $student = auth('student')->user();

        // Get student's program enrollments with academic years and filieres
        $programEnrollments = $student->programEnrollments()
            ->with(['filiere', 'academicYear'])
            ->orderBy('academic_year')
            ->get();

        // Map to available transcripts
        $availableReleves = $programEnrollments->map(function ($enrollment) {
            $yearInProgram = $enrollment->year_in_program ?? 1;
            $startSemester = $yearInProgram * 2 - 1;
            $endSemester = $yearInProgram * 2;

            $now = now();
            $isPastYear = $enrollment->academic_year < $now->year;

            return [
                'academic_year' => $enrollment->academic_year, // Use start_year directly
                'academic_year_label' => "{$enrollment->academic_year}-".($enrollment->academic_year + 1),
                'semesters' => "S{$startSemester}-S{$endSemester}",
                'filiere_label' => $enrollment->filiere->label_fr,
                'filiere_id' => $enrollment->filiere->id,
                'year_label' => $this->getOrdinal($yearInProgram).' année '.ucfirst($enrollment->filiere->level),
                'disponible' => $isPastYear,
                'enrollment_status' => $enrollment->enrollment_status,
            ];
        });

        // Get student's previous transcript requests
        $studentDemandes = $student->demandes()
            ->where('document_id', 7) // Relevé de notes
            ->with(['document', 'academicYear'])
            ->latest()
            ->get();
        // dd($student->demandes()->where('document_id',1)->get());
        return view('student.demandes.releve', compact('student', 'availableReleves', 'studentDemandes'));
    }

    /**
     * Store a new transcript request
     */
    public function store_releve(Request $request)
    {
        \Log::info('Saving transcript demande', $request->all());

        // Get the document
        $document = Document::where('slug', 'releve_notes')->first();

        if (! $document) {
            return back()->with('error', 'Document non trouvé.');
        }

        // Simple validation - no retrait_type needed
        $request->validate([
            'academic_year' => ['required', 'exists:academic_years,start_year'],
            'semester' => ['nullable', 'in:S1,S2,S3,S4,S5,S6'],
        ]);

        $student = auth('student')->user();

        // Check for duplicate
        $alreadyExists = $student->demandes()
            ->where('document_id', $document->id)
            ->where('academic_year', $request->academic_year)
            ->where(function ($query) use ($request) {
                if ($request->filled('semester')) {
                    $query->where('semester', $request->semester);
                } else {
                    $query->whereNull('semester');
                }
            })
            ->exists();

        if ($alreadyExists) {
            return back()->with('error', 'Une demande pour cette période existe déjà.');
        }

        // Create demande - always definitif for relevés
        $demande = $student->demandes()->create([
            'document_id' => $document->id,
            'academic_year' => $request->academic_year,
            'semester' => $request->semester,
            'status' => 'PENDING',
            'retrait_type' => 'permanent', // Always definitif, no choice needed
        ]);
        \Log::info("Demande: ".$demande);

        return back()->with('success', 'Votre demande a été soumise avec succès. ');
    }

    /**
     * Get ordinal number in French
     */
    private function getOrdinal($number)
    {
        return match ($number) {
            1 => '1ère',
            2 => '2ème',
            3 => '3ème',
            4 => '4ème',
            default => "{$number}ème",
        };
    }
}