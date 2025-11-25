<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Demande;
use App\Models\Document;
use App\Models\Filiere;
use App\Models\Student;
use App\Services\DechargeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DocumentRequestController extends Controller
{
    /**
     * Display a listing of document requests with filtering and search.
     */
    public function index(Request $request)
    {
        $query = Demande::with(['student', 'document', 'processedBy']);

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by document type
        if ($request->filled('document_id')) {
            $query->where('document_id', $request->document_id);
        }

        // Filter by academic year
        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        // Filter by retrait type
        if ($request->filled('retrait_type')) {
            $query->where('retrait_type', $request->retrait_type);
        }

        // Filter overdue
        if ($request->boolean('overdue')) {
            $query->overdue();
        }

        // Filter extension requested
        if ($request->boolean('extension_requested')) {
            $query->extensionRequested();
        }

        // Search by reference number, student name, CNE
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('reference_number', 'like', "%{$search}%")
                    ->orWhereHas('student', function ($sq) use ($search) {
                        $sq->where('nom', 'like', "%{$search}%")
                            ->orWhere('prenom', 'like', "%{$search}%")
                            ->orWhere('cne', 'like', "%{$search}%")
                            ->orWhere('apogee', 'like', "%{$search}%");
                    });
            });
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDir = $request->get('sort_dir', 'desc');
        $query->orderBy($sortBy, $sortDir);

        $requests = $query->paginate(20)->withQueryString();

        // Get filter options
        $documents = Document::orderBy('label_fr')->get();
        $filieres = Filiere::orderBy('label_fr')->get();
        $academicYears = Demande::select('academic_year')
            ->distinct()
            ->orderBy('academic_year', 'desc')
            ->pluck('academic_year');

        // Statistics
        $stats = [
            'total' => Demande::count(),
            'pending' => Demande::pending()->count(),
            'ready' => Demande::ready()->count(),
            'picked' => Demande::picked()->count(),
            'completed' => Demande::completed()->count(),
            'overdue' => Demande::overdue()->count(),
            'extension_requested' => Demande::extensionRequested()->count(),
        ];

        return view('admin.document-requests.index', compact(
            'requests',
            'documents',
            'filieres',
            'academicYears',
            'stats'
        ));
    }

    /**
     * Display the specified document request.
     */
    public function show($id)
    {
        $demande = Demande::with([
            'student.programEnrollments.filiere',
            'document',
            'processedBy'
        ])->findOrFail($id);

        // Get student's other requests for context
        $otherRequests = Demande::where('student_id', $demande->student_id)
            ->where('id', '!=', $id)
            ->with('document')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get student's document history statistics
        $studentHistory = [
            'total' => Demande::where('student_id', $demande->student_id)->count(),
            'completed' => Demande::where('student_id', $demande->student_id)->where('status', 'COMPLETED')->count(),
            'pending_returns' => Demande::where('student_id', $demande->student_id)
                ->where('status', 'PICKED')
                ->where('retrait_type', 'temporaire')
                ->whereNull('returned_at')
                ->count()
        ];

        return view('admin.document-requests.show', compact('demande', 'otherRequests', 'studentHistory'));
    }

    /**
     * Mark a request as ready for pickup.
     */
    public function markReady(Request $request, $id)
    {
        $demande = Demande::findOrFail($id);

        if (!$demande->isPending()) {
            return back()->with('error', 'Cette demande ne peut pas être marquée comme prête.');
        }

        try {
            DB::beginTransaction();

            $demande->markAsReady(auth('admin')->id());

            // Set return deadline for temporary documents
            if ($demande->isTemporaire() && $request->filled('return_days')) {
                $demande->setReturnDeadline($request->return_days);
            }

            DB::commit();

            return back()->with('success', "Demande {$demande->reference_number} marquée comme prête pour retrait.");

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error marking request as ready: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la mise à jour de la demande.');
        }
    }

    /**
     * Mark a request as picked up by student.
     */
    public function markPicked($id)
    {
        $demande = Demande::findOrFail($id);

        if (!$demande->isReady()) {
            return back()->with('error', 'Cette demande ne peut pas être marquée comme retirée.');
        }

        try {
            $demande->markAsPicked();

            return back()->with('success', "Document {$demande->reference_number} retiré par l'étudiant.");

        } catch (\Exception $e) {
            \Log::error('Error marking request as picked: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la mise à jour de la demande.');
        }
    }

    /**
     * Mark a request as completed (document returned).
     */
    public function markCompleted($id)
    {
        $demande = Demande::findOrFail($id);

        if (!$demande->isPicked()) {
            return back()->with('error', 'Cette demande ne peut pas être complétée.');
        }

        try {
            $demande->markAsCompleted();

            return back()->with('success', "Demande {$demande->reference_number} complétée avec succès.");

        } catch (\Exception $e) {
            \Log::error('Error marking request as completed: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la mise à jour de la demande.');
        }
    }

    /**
     * Approve an extension request.
     */
    public function approveExtension(Request $request, $id)
    {
        $demande = Demande::findOrFail($id);

        if (!$demande->extension_requested_at) {
            return back()->with('error', 'Aucune demande de prolongation en attente.');
        }

        $validated = $request->validate([
            'extension_days' => 'required|integer|min:1|max:30',
        ]);

        try {
            $demande->approveExtension($validated['extension_days']);

            return back()->with('success', "Prolongation de {$validated['extension_days']} jours accordée.");

        } catch (\Exception $e) {
            \Log::error('Error approving extension: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de l\'approbation de la prolongation.');
        }
    }

    /**
     * Reject an extension request.
     */
    public function rejectExtension($id)
    {
        $demande = Demande::findOrFail($id);

        if (!$demande->extension_requested_at) {
            return back()->with('error', 'Aucune demande de prolongation en attente.');
        }

        try {
            $demande->update([
                'extension_requested_at' => null,
            ]);

            return back()->with('success', 'Demande de prolongation refusée.');

        } catch (\Exception $e) {
            \Log::error('Error rejecting extension: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors du refus de la prolongation.');
        }
    }

    /**
     * Cancel/delete a request.
     */
    public function destroy($id)
    {
        $demande = Demande::findOrFail($id);

        if ($demande->isPicked() || $demande->isCompleted()) {
            return back()->with('error', 'Cette demande ne peut pas être supprimée.');
        }

        try {
            $reference = $demande->reference_number;
            $demande->delete();

            return redirect()
                ->route('admin.document-requests.index')
                ->with('success', "Demande {$reference} supprimée avec succès.");

        } catch (\Exception $e) {
            \Log::error('Error deleting request: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la suppression de la demande.');
        }
    }

    /**
     * Bulk mark requests as ready.
     */
    public function bulkMarkReady(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:demandes,id',
        ]);

        try {
            DB::beginTransaction();

            $count = 0;
            foreach ($validated['ids'] as $id) {
                $demande = Demande::find($id);
                if ($demande && $demande->isPending()) {
                    $demande->markAsReady(auth('admin')->id());
                    $count++;
                }
            }

            DB::commit();

            return back()->with('success', "{$count} demande(s) marquée(s) comme prête(s).");

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error in bulk mark ready: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la mise à jour en masse.');
        }
    }

    /**
     * Generate and download décharge PDF
     */
    public function generateDecharge($id, DechargeService $dechargeService)
    {
        $demande = Demande::with(['student.programEnrollments.filiere', 'document', 'dechargeSignedBy'])->findOrFail($id);

        if (!$demande->isReady() && !$demande->isPicked() && !$demande->isCompleted()) {
            return back()->with('error', 'La décharge ne peut être générée que pour les documents prêts ou retirés.');
        }

        try {
            // Generate décharge info if not already done
            if (!$demande->hasDecharge()) {
                $demande->generateDecharge(auth('admin')->id());
                $demande->refresh();
            }

            return $dechargeService->stream($demande);

        } catch (\Exception $e) {
            \Log::error('Error generating decharge: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la génération de la décharge.');
        }
    }

    /**
     * Export requests to CSV.
     */
    public function export(Request $request)
    {
        $query = Demande::with(['student', 'document', 'processedBy']);

        // Apply same filters as index
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('document_id')) {
            $query->where('document_id', $request->document_id);
        }

        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        $requests = $query->orderBy('created_at', 'desc')->get();

        $filename = 'document_requests_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($requests) {
            $file = fopen('php://output', 'w');

            // Header row
            fputcsv($file, [
                'Référence',
                'Étudiant',
                'CNE',
                'Document',
                'Statut',
                'Type Retrait',
                'Année Académique',
                'Date Demande',
                'Date Prêt',
                'Date Retrait',
                'Date Retour',
            ]);

            // Data rows
            foreach ($requests as $req) {
                fputcsv($file, [
                    $req->reference_number,
                    $req->student->full_name ?? 'N/A',
                    $req->student->cne ?? 'N/A',
                    $req->document->label_fr ?? 'N/A',
                    $req->status_label,
                    $req->retrait_type === 'temporaire' ? 'Temporaire' : 'Permanent',
                    $req->academic_year . '/' . ($req->academic_year + 1),
                    $req->created_at->format('d/m/Y H:i'),
                    $req->ready_at ? $req->ready_at->format('d/m/Y H:i') : '',
                    $req->collected_at ? $req->collected_at->format('d/m/Y H:i') : '',
                    $req->returned_at ? $req->returned_at->format('d/m/Y H:i') : '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Document types management - list all document types.
     */
    public function documentTypes()
    {
        $documents = Document::withCount('demandes')
            ->orderBy('label_fr')
            ->get();

        return view('admin.document-requests.document-types', compact('documents'));
    }

    /**
     * Store a new document type.
     */
    public function storeDocumentType(Request $request)
    {
        $validated = $request->validate([
            'slug' => 'required|string|max:50|unique:documents,slug',
            'label_fr' => 'required|string|max:255',
            'label_ar' => 'nullable|string|max:255',
            'label_en' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'requires_return' => 'boolean',
        ]);

        try {
            Document::create($validated);

            return redirect()
                ->route('admin.document-requests.document-types')
                ->with('success', 'Type de document créé avec succès.');

        } catch (\Exception $e) {
            \Log::error('Error creating document type: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la création du type de document.');
        }
    }

    /**
     * Update a document type.
     */
    public function updateDocumentType(Request $request, $id)
    {
        $document = Document::findOrFail($id);

        $validated = $request->validate([
            'slug' => 'required|string|max:50|unique:documents,slug,' . $id,
            'label_fr' => 'required|string|max:255',
            'label_ar' => 'nullable|string|max:255',
            'label_en' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'requires_return' => 'boolean',
        ]);

        try {
            $document->update($validated);

            return redirect()
                ->route('admin.document-requests.document-types')
                ->with('success', 'Type de document mis à jour avec succès.');

        } catch (\Exception $e) {
            \Log::error('Error updating document type: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la mise à jour du type de document.');
        }
    }

    /**
     * Delete a document type.
     */
    public function destroyDocumentType($id)
    {
        $document = Document::withCount('demandes')->findOrFail($id);

        if ($document->demandes_count > 0) {
            return back()->with('error', 'Ce type de document ne peut pas être supprimé car il a des demandes associées.');
        }

        try {
            $document->delete();

            return redirect()
                ->route('admin.document-requests.document-types')
                ->with('success', 'Type de document supprimé avec succès.');

        } catch (\Exception $e) {
            \Log::error('Error deleting document type: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la suppression du type de document.');
        }
    }
}
