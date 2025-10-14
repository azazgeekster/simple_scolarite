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
    public function index()
    {
        $student = auth('student')->user();
        $demandes = $student->demandes()->with(['document', 'academicYear'])->latest()->get();
        $documents = Document::all();
        $years = AcademicYear::orderByDesc('start_year')->get();

        return view('student.demandes.index', compact('demandes', 'documents', 'years'));
    }
    public function store(Request $request)
    {
        // THIS SHOULD BE IN ADMIN PART

        // $demande->status = 'READY_FOR_PICKUP';
        // $demande->ready_at = now();
        // $demande->must_return_by = now()->addHours(48);
        // $demande->save();

        $request->validate([
            'document_id' => 'required|exists:documents,id',
            'retrait_type' => 'required|in:temporaire,permanent',
        ], [
            'retrait_type.required' => 'Veuillez sélectionner un type de retrait.',
            'retrait_type.in' => 'Le type de retrait sélectionné est invalide.',
        ]);

        $student = auth('student')->user();

        // Get current academic year by date
        $today = now()->year;
        $year = AcademicYear::where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->firstOrFail();
        // Limit BAC retrait to 2 times per academic year (custom logic for doc id = BAC, e.g. 1)
        $limitReached = $student->demandes()
            ->where('academic_year_id', $year->id)
            ->where('document_id', $request->document_id)
            ->count() >= 2;

        if ($limitReached && $request->document_id == 1) {
            return back()->with('error', 'Tu ne peux plus faire le retrait du BAC, tu as atteint le nombre maximal');
        }
        // Prevent duplicate request (same document + retrait type on same day)
        $alreadyToday = $student->demandes()
            ->where('document_id', $request->document_id)
            ->where('retrait_type', $request->retrait_type)
            ->whereDate('created_at', now()->toDateString())
            ->exists();
        if ($alreadyToday) {

            return back()->with('error', 'Vous avez déjà effectué cette même demande aujourd\'hui.');
        }

        // If temporaire, define return deadline (e.g. 7 days from now)
        $mustReturnBy = $request->retrait_type === 'temporaire'
            ? now()->addDays(2)
            : null;

        $student->demandes()->create([
            'document_id' => $request->document_id,
            'academic_year_id' => $year->id,
            'retrait_type' => $request->retrait_type,
            'must_return_by' => $mustReturnBy,
        ]);
        \Log::warning("Hello");
        return back()->with('success', 'Demande soumise avec succès.');
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

        if ($demande->student_id !== $student->id || $demande->status !== 'PENDING') {
            abort(403);
        }

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

         // Get student's filiere enrollments ordered by academic year
         $academicFilieres = $student->academicFilieres()
             ->with(['filiere', 'academicYear'])
             ->orderBy('academic_year_id')
             ->get();

         // Map to available releves (transcripts)
         $availableReleves = $academicFilieres->map(function ($entry, $index) {
             $yearInProgram = $index + 1;
             $startSemester = $yearInProgram * 2 - 1;
             $endSemester = $yearInProgram * 2;

             $now = now();
             $isPastYear = $entry->academicYear->end_date < $now->year;

             return [
                 'academic_year_id' => $entry->academicYear->id,
                 'academic_year_label' => $entry->academicYear->label,
                 'semesters' => "S{$startSemester}-S{$endSemester}",
                 'filiere_label' => $entry->filiere->label_fr,
                 'filiere_id' => $entry->filiere->id,
                 'year_label' => ordinal($yearInProgram) . ' année ' . ucfirst($entry->filiere->level),
                 'disponible' => $isPastYear,
             ];
         });

         // Get student's previous transcript demandes
         $studentDemandes = $student->demandes()->with(['academicYear'])->latest()->get();

         return view('student.demandes.releve', compact('student', 'availableReleves', 'studentDemandes'));
     }


     public function store_releve(Request $request)
     {

        \Log::info('Saving demande', $request->all());
         $request->validate([
             'academic_year_id' => ['required', 'exists:academic_years,id'],
            //  'filiere_id' => ['required', 'exists:filieres,id'],
             'retrait_type' => ['required', 'in:temporaire,definitif'],
             'semester_id' => ['nullable', 'integer', 'min:1', 'max:12'],
         ]);

         $student = auth('student')->user();
         $alreadyExists = $student->demandes()
             ->where('document_id', 1) // 1 = Relevé de notes
             ->where('academic_year_id', $request->academic_year_id)
            //  ->where('filiere_id', $request->filiere_id)
             ->where(function ($query) use ($request) {
                 if ($request->filled('semester_id')) {
                     $query->where('semester_id', $request->semester_id);
                 } else {
                     $query->whereNull('semester_id');
                 }
             })
             ->exists();

         if ($alreadyExists) {
             return back()->with('error', 'Une demande pour cette période existe déjà.');
         }

         $student->demandes()->create([
             'document_id' => 1,
             'academic_year_id' => $request->academic_year_id,
            //  'filiere_id' => $request->filiere_id,
             'semester_id' => $request->semester_id, // can be null
             'status' => 'PENDING',
             'retrait_type' => $request->retrait_type,
         ]);

         return back()->with('success', 'Votre demande a été soumise avec succès.');
     }

}

// Helper function (can go in helpers.php)
if (!function_exists('ordinal')) {
    function ordinal($number)
    {
        return match($number) {
            1 => '1ère',
            2 => '2ème',
            3 => '3ème',
            4 => '4ème',
            default => "$number ème",
        };
    }
}