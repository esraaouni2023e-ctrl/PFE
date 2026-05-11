<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class UserMessageController extends Controller
{
    /**
     * Display the inbox for the current user (Student or Counselor).
     */
    public function index()
    {
        $user = auth()->user();
        $messages = Message::where('receiver_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('messages.index', compact('messages'));
    }

    /**
     * Show a specific message and mark it as read.
     */
    public function show(Message $message)
    {
        if ($message->receiver_id !== auth()->id()) {
            abort(403);
        }

        if (!$message->is_read) {
            $message->update(['is_read' => true]);
        }

        return view('messages.show', compact('message'));
    }

    /**
     * Show the form for creating a new message.
     */
    public function create()
    {
        return view('messages.create');
    }

    /**
     * Send a message to another user by email.
     */
    public function store(Request $request)
    {
        $request->validate([
            'receiver_email' => 'required|email',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $receiver = User::where('email', $request->receiver_email)->first();

        if (!$receiver) {
            return back()->with('error', 'Aucun utilisateur trouvé avec cet email.')->withInput();
        }

        // Optional: Check roles (e.g., student can only message counselor, and vice versa)
        // For now, let's allow any internal messaging between roles for flexibility.

        Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $receiver->id,
            'sender_email' => auth()->user()->email,
            'receiver_email' => $receiver->email,
            'subject' => $request->subject,
            'body' => $request->body,
        ]);

        return redirect()->route('messages.index')->with('status', 'Message envoyé avec succès.');
    }

    /**
     * Reply to a message.
     */
    public function reply(Request $request, Message $message)
    {
        $request->validate([
            'body' => 'required|string',
        ]);

        Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $message->sender_id,
            'sender_email' => auth()->user()->email,
            'receiver_email' => $message->sender_email,
            'subject' => 'Re: ' . $message->subject,
            'body' => $request->body,
        ]);

        return back()->with('status', 'Réponse envoyée avec succès.');
    }

    /**
     * Remove the specified message from storage.
     */
    public function destroy(Message $message)
    {
        if ($message->receiver_id !== auth()->id() && $message->sender_id !== auth()->id()) {
            abort(403);
        }

        $message->delete();

        return redirect()->route('messages.index')->with('status', 'Message supprimé.');
    }

    /**
     * Get the count of unread messages for the notification bell.
     */
    public function unreadCount()
    {
        return response()->json([
            'count' => Message::where('receiver_id', auth()->id())->unread()->count(),
            'latest' => Message::where('receiver_id', auth()->id())
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function ($msg) {
                    return [
                        'id' => $msg->id,
                        'sender_name' => $msg->sender->name,
                        'subject' => $msg->subject,
                        'is_read' => $msg->is_read,
                        'time' => $msg->created_at->timezone('Africa/Tunis')->diffForHumans(),
                    ];
                })
        ]);
    }
}
