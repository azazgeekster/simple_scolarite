<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    /**
     * Display list of messages for the authenticated student
     */
    public function index(Request $request)
    {
        $student = auth('student')->user();

        // Debug: Log student info
        \Log::info('Student Messages Index', [
            'student_id' => $student ? $student->id : 'NULL',
            'student_name' => $student ? $student->prenom . ' ' . $student->nom : 'NULL'
        ]);

        // Get messages that apply to this student
        $query = Message::where(function ($q) use ($student) {
            $q->where('recipient_id', $student->id)
              ->orWhere('recipient_type', 'all');

            // Messages for student's filiere, year, semester, and combinations
            if ($student->programEnrollments()->where('enrollment_status', 'active')->exists()) {
                $activeEnrollment = $student->programEnrollments()
                    ->where('enrollment_status', 'active')
                    ->first();

                if ($activeEnrollment) {
                    // Filiere only
                    $q->orWhere(function ($sq) use ($activeEnrollment) {
                        $sq->where('recipient_type', 'filiere')
                           ->where('filiere_id', $activeEnrollment->filiere_id);
                    });

                    // Year only
                    $q->orWhere(function ($sq) use ($activeEnrollment) {
                        $sq->where('recipient_type', 'year')
                           ->where('year_in_program', $activeEnrollment->year_in_program);
                    });

                    // Semester only
                    $q->orWhere(function ($sq) use ($activeEnrollment) {
                        $sq->where('recipient_type', 'semester')
                           ->where('semester', $activeEnrollment->current_semester);
                    });

                    // Filiere + Year
                    $q->orWhere(function ($sq) use ($activeEnrollment) {
                        $sq->where('recipient_type', 'filiere_year')
                           ->where('filiere_id', $activeEnrollment->filiere_id)
                           ->where('year_in_program', $activeEnrollment->year_in_program);
                    });

                    // Filiere + Semester
                    $q->orWhere(function ($sq) use ($activeEnrollment) {
                        $sq->where('recipient_type', 'filiere_semester')
                           ->where('filiere_id', $activeEnrollment->filiere_id)
                           ->where('semester', $activeEnrollment->current_semester);
                    });
                }
            }
        })
        ->with(['sender', 'filiere']);

        // Filter by read status
        if ($request->has('status')) {
            if ($request->status === 'unread') {
                $query->where('is_read', false);
            } elseif ($request->status === 'read') {
                $query->where('is_read', true);
            }
        }

        // Filter by category
        if ($request->has('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        $messages = $query->orderBy('created_at', 'desc')->paginate(20);

        // Debug: Log query results
        \Log::info('Student Messages Query Result', [
            'total_messages' => $messages->total(),
            'current_page_count' => count($messages->items()),
            'message_ids' => collect($messages->items())->pluck('id')->toArray()
        ]);

        // Count unread messages
        $unreadCount = Message::where(function ($q) use ($student) {
            $q->where('recipient_id', $student->id)
              ->orWhere('recipient_type', 'all');

            if ($student->programEnrollments()->where('enrollment_status', 'active')->exists()) {
                $activeEnrollment = $student->programEnrollments()
                    ->where('enrollment_status', 'active')
                    ->first();

                if ($activeEnrollment) {
                    // Filiere only
                    $q->orWhere(function ($sq) use ($activeEnrollment) {
                        $sq->where('recipient_type', 'filiere')
                           ->where('filiere_id', $activeEnrollment->filiere_id);
                    });

                    // Year only
                    $q->orWhere(function ($sq) use ($activeEnrollment) {
                        $sq->where('recipient_type', 'year')
                           ->where('year_in_program', $activeEnrollment->year_in_program);
                    });

                    // Semester only
                    $q->orWhere(function ($sq) use ($activeEnrollment) {
                        $sq->where('recipient_type', 'semester')
                           ->where('semester', $activeEnrollment->current_semester);
                    });

                    // Filiere + Year
                    $q->orWhere(function ($sq) use ($activeEnrollment) {
                        $sq->where('recipient_type', 'filiere_year')
                           ->where('filiere_id', $activeEnrollment->filiere_id)
                           ->where('year_in_program', $activeEnrollment->year_in_program);
                    });

                    // Filiere + Semester
                    $q->orWhere(function ($sq) use ($activeEnrollment) {
                        $sq->where('recipient_type', 'filiere_semester')
                           ->where('filiere_id', $activeEnrollment->filiere_id)
                           ->where('semester', $activeEnrollment->current_semester);
                    });
                }
            }
        })->where('is_read', false)->count();

        return view('student.messages.index', compact('messages', 'unreadCount'));
    }

    /**
     * Show message details
     */
    public function show(Message $message)
    {
        $student = auth('student')->user();

        // Verify the student has access to this message
        $hasAccess = $this->studentHasAccessToMessage($student, $message);

        if (!$hasAccess) {
            abort(403, 'Vous n\'avez pas accès à ce message.');
        }

        $message->load(['sender', 'filiere']);

        // Mark as read if this is a direct message to the student
        if ($message->recipient_id === $student->id && !$message->is_read) {
            $message->markAsRead();
        }

        return view('student.messages.show', compact('message'));
    }

    /**
     * Mark message as read
     */
    public function markAsRead(Message $message)
    {
        $student = auth('student')->user();

        if ($message->recipient_id === $student->id) {
            $message->markAsRead();
        }

        return back()->with('success', 'Message marqué comme lu');
    }

    /**
     * Mark message as unread
     */
    public function markAsUnread(Message $message)
    {
        $student = auth('student')->user();

        if ($message->recipient_id === $student->id) {
            $message->markAsUnread();
        }

        return back()->with('success', 'Message marqué comme non lu');
    }

    /**
     * Check if student has access to a message
     */
    private function studentHasAccessToMessage($student, Message $message): bool
    {
        // Direct message to this student
        if ($message->recipient_id === $student->id) {
            return true;
        }

        // Message to all students
        if ($message->recipient_type === 'all') {
            return true;
        }

        // Get active enrollment
        $activeEnrollment = $student->programEnrollments()
            ->where('enrollment_status', 'active')
            ->first();

        if (!$activeEnrollment) {
            return false;
        }

        // Message to student's filiere
        if ($message->recipient_type === 'filiere' &&
            $message->filiere_id === $activeEnrollment->filiere_id) {
            return true;
        }

        // Message to student's year
        if ($message->recipient_type === 'year' &&
            $message->year_in_program === $activeEnrollment->year_in_program) {
            return true;
        }

        // Message to student's semester
        if ($message->recipient_type === 'semester' &&
            $message->semester === $activeEnrollment->current_semester) {
            return true;
        }

        // Message to student's filiere + year
        if ($message->recipient_type === 'filiere_year' &&
            $message->filiere_id === $activeEnrollment->filiere_id &&
            $message->year_in_program === $activeEnrollment->year_in_program) {
            return true;
        }

        // Message to student's filiere + semester
        if ($message->recipient_type === 'filiere_semester' &&
            $message->filiere_id === $activeEnrollment->filiere_id &&
            $message->semester === $activeEnrollment->current_semester) {
            return true;
        }

        return false;
    }
}
