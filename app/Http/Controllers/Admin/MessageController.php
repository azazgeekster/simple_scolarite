<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Student;
use App\Models\Filiere;
use App\Notifications\NewMessageNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    /**
     * Display list of sent messages
     */
    public function index()
    {
        $messages = Message::where('sender_id', auth('admin')->id())
            ->with(['recipient', 'filiere'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.messages.index', compact('messages'));
    }

    /**
     * Show form to compose new message
     */
    public function create()
    {
        $filieres = Filiere::orderBy('label_fr')->get();
        $students = Student::select('id', 'prenom', 'nom', 'apogee')
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get();

        return view('admin.messages.create', compact('filieres', 'students'));
    }

    /**
     * Send message
     */
    public function store(Request $request)
    {
        $request->validate([
            'recipient_type' => 'required|in:individual,filiere,year,semester,filiere_year,filiere_semester,all',
            'recipient_id' => 'required_if:recipient_type,individual|nullable|exists:students,id',
            'filiere_id' => 'required_if:recipient_type,filiere,filiere_year,filiere_semester|nullable|exists:filieres,id',
            'year_in_program' => 'required_if:recipient_type,year,filiere_year|nullable|integer|min:1|max:3',
            'semester' => 'required_if:recipient_type,semester,filiere_semester|nullable|in:S1,S2,S3,S4,S5,S6',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
            'priority' => 'required|in:low,normal,high,urgent',
            'category' => 'required|in:general,exam,grade,administrative,important',
        ]);

        DB::beginTransaction();
        try {
            $recipientType = $request->recipient_type;

            // Determine recipients based on type
            $recipients = $this->getRecipients($request);

            if ($recipients->isEmpty()) {
                return back()->with('error', 'Aucun destinataire trouvé pour ce critère.');
            }

            // Create message for each recipient
            $messagesCreated = 0;
            foreach ($recipients as $student) {
                $message = Message::create([
                    'sender_id' => auth('admin')->id(),
                    'recipient_id' => $student->id,
                    'recipient_type' => $recipientType,
                    'filiere_id' => $request->filiere_id,
                    'year_in_program' => $request->year_in_program,
                    'semester' => $request->semester,
                    'subject' => $request->subject,
                    'message' => $request->message,
                    'priority' => $request->priority,
                    'category' => $request->category,
                ]);

                // Send notification to student
                $student->notify(new NewMessageNotification($message));

                $messagesCreated++;
            }

            DB::commit();

            return redirect()->route('admin.messages.index')
                ->with('success', "Message envoyé avec succès à {$messagesCreated} étudiant(s)");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de l\'envoi du message: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show message details
     */
    public function show(Message $message)
    {
        // Ensure admin can only view their own messages
        if ($message->sender_id !== auth('admin')->id()) {
            abort(403, 'Non autorisé');
        }

        $message->load(['recipient', 'filiere']);

        return view('admin.messages.show', compact('message'));
    }

    /**
     * Delete message
     */
    public function destroy(Message $message)
    {
        // Ensure admin can only delete their own messages
        if ($message->sender_id !== auth('admin')->id()) {
            abort(403, 'Non autorisé');
        }

        $message->delete();

        return redirect()->route('admin.messages.index')
            ->with('success', 'Message supprimé avec succès');
    }

    /**
     * Get recipients based on criteria
     */
    private function getRecipients(Request $request)
    {
        $query = Student::query();

        switch ($request->recipient_type) {
            case 'individual':
                $query->where('id', $request->recipient_id);
                break;

            case 'filiere':
                $query->whereHas('programEnrollments', function ($q) use ($request) {
                    $q->where('filiere_id', $request->filiere_id)
                      ->where('enrollment_status', 'active');
                });
                break;

            case 'year':
                $query->whereHas('programEnrollments', function ($q) use ($request) {
                    $q->where('year_in_program', $request->year_in_program)
                      ->where('enrollment_status', 'active');
                });
                break;

            case 'semester':
                $query->whereHas('programEnrollments', function ($q) use ($request) {
                    $q->where('current_semester', $request->semester)
                      ->where('enrollment_status', 'active');
                });
                break;

            case 'filiere_year':
                $query->whereHas('programEnrollments', function ($q) use ($request) {
                    $q->where('filiere_id', $request->filiere_id)
                      ->where('year_in_program', $request->year_in_program)
                      ->where('enrollment_status', 'active');
                });
                break;

            case 'filiere_semester':
                $query->whereHas('programEnrollments', function ($q) use ($request) {
                    $q->where('filiere_id', $request->filiere_id)
                      ->where('current_semester', $request->semester)
                      ->where('enrollment_status', 'active');
                });
                break;

            case 'all':
                // All active students
                $query->whereHas('programEnrollments', function ($q) {
                    $q->where('enrollment_status', 'active');
                });
                break;
        }

        return $query->distinct()->get();
    }
}
