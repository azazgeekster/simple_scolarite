<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Display student's inbox
     */
    public function index()
    {
        $student = auth('student')->user();

        $messages = Message::forStudent($student->id)
            ->with(['sender', 'filiere'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Get unread count
        $unreadCount = Message::forStudent($student->id)
            ->unread()
            ->count();

        return view('student.messages.index', compact('messages', 'unreadCount'));
    }

    /**
     * Show message details
     */
    public function show(Message $message)
    {
        $student = auth('student')->user();

        // Ensure student can only view their own messages
        if ($message->recipient_id !== $student->id) {
            abort(403, 'Non autorisé');
        }

        // Mark as read if not already read
        if (!$message->is_read) {
            $message->markAsRead();
        }

        $message->load(['sender', 'filiere']);

        return view('student.messages.show', compact('message'));
    }

    /**
     * Mark message as read
     */
    public function markAsRead(Message $message)
    {
        $student = auth('student')->user();

        if ($message->recipient_id !== $student->id) {
            abort(403, 'Non autorisé');
        }

        $message->markAsRead();

        return back()->with('success', 'Message marqué comme lu');
    }

    /**
     * Mark message as unread
     */
    public function markAsUnread(Message $message)
    {
        $student = auth('student')->user();

        if ($message->recipient_id !== $student->id) {
            abort(403, 'Non autorisé');
        }

        $message->markAsUnread();

        return back()->with('success', 'Message marqué comme non lu');
    }

    /**
     * Get unread messages count (for AJAX requests)
     */
    public function unreadCount()
    {
        $student = auth('student')->user();

        $count = Message::forStudent($student->id)
            ->unread()
            ->count();

        return response()->json(['count' => $count]);
    }
}
