<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Student;
use App\Models\Filiere;
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
        $filieres = Filiere::orderBy('name')->get();
        $students = Student::select('id', 'first_name', 'last_name', 'apogee')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        return view('admin.messages.create', compact('filieres', 'students'));
    }

    /**
     * Send message
     */
    public function store(Request $request)
    {
        $request->validate([
            'recipient_type' => 'required|in:individual,filiere,year,all',
            'recipient_id' => 'required_if:recipient_type,individual|nullable|exists:students,id',
            'filiere_id' => 'required_if:recipient_type,filiere|nullable|exists:filieres,id',
            'year_in_program' => 'required_if:recipient_type,year|nullable|integer|min:1|max:3',
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
                Message::create([
                    'sender_id' => auth('admin')->id(),
                    'recipient_id' => $student->id,
                    'recipient_type' => $recipientType,
                    'filiere_id' => $request->filiere_id,
                    'year_in_program' => $request->year_in_program,
                    'subject' => $request->subject,
                    'message' => $request->message,
                    'priority' => $request->priority,
                    'category' => $request->category,
                ]);
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

        // Get recipient count for bulk messages
        $recipientCount = null;
        if ($message->recipient_type !== 'individual') {
            $recipientCount = Message::where('sender_id', $message->sender_id)
                ->where('recipient_type', $message->recipient_type)
                ->where('subject', $message->subject)
                ->where('created_at', $message->created_at)
                ->count();
        }

        return view('admin.messages.show', compact('message', 'recipientCount'));
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
