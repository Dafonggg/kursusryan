<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    /**
     * Display a listing of conversations
     */
    public function index()
    {
        $conversations = Conversation::with(['participants', 'latestMessage.user'])
            ->whereHas('participants', function ($query) {
                $query->where('users.id', auth()->id());
            })
            ->withCount(['messages' => function ($query) {
                $query->where('user_id', '!=', auth()->id())
                    ->whereDoesntHave('conversation.participants', function ($q) {
                        $q->where('users.id', auth()->id())
                            ->whereNotNull('conversation_participants.read_at');
                    });
            }])
            ->orderByDesc(function ($query) {
                $query->select('created_at')
                    ->from('messages')
                    ->whereColumn('messages.conversation_id', 'conversations.id')
                    ->orderByDesc('created_at')
                    ->limit(1);
            })
            ->get();

        return view('admin.chat.index', compact('conversations'));
    }

    /**
     * Show a specific conversation
     */
    public function show(Conversation $conversation)
    {
        // Ensure admin is a participant
        if (!$conversation->participants->contains(auth()->id())) {
            abort(403);
        }

        // Mark messages as read
        $conversation->participants()->updateExistingPivot(auth()->id(), [
            'read_at' => now()
        ]);

        $messages = Message::where('conversation_id', $conversation->id)
            ->with('user.profile')
            ->orderBy('created_at')
            ->get();

        // Get all conversations for sidebar
        $conversations = Conversation::with(['participants', 'latestMessage.user'])
            ->whereHas('participants', function ($query) {
                $query->where('users.id', auth()->id());
            })
            ->orderByDesc(function ($query) {
                $query->select('created_at')
                    ->from('messages')
                    ->whereColumn('messages.conversation_id', 'conversations.id')
                    ->orderByDesc('created_at')
                    ->limit(1);
            })
            ->get();

        $otherParticipants = $conversation->participants()
            ->where('users.id', '!=', auth()->id())
            ->get();

        return view('admin.chat.show', compact('conversation', 'messages', 'otherParticipants', 'conversations'));
    }

    /**
     * Start a new conversation with a user
     */
    public function create()
    {
        $users = User::where('id', '!=', auth()->id())
            ->whereIn('role', ['student', 'user', 'instructor'])
            ->with('profile')
            ->latest()
            ->get();

        return view('admin.chat.create', compact('users'));
    }

    /**
     * Store a new conversation and send initial message
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'required|string|max:5000',
        ]);

        $otherUser = User::findOrFail($validated['user_id']);

        // Check if conversation already exists
        $conversation = Conversation::whereHas('participants', function ($query) use ($otherUser) {
            $query->where('users.id', auth()->id())
                ->orWhere('users.id', $otherUser->id);
        })->whereHas('participants', function ($query) use ($otherUser) {
            $query->where('users.id', $otherUser->id);
        })->whereHas('participants', function ($query) {
            $query->where('users.id', auth()->id());
        })->first();

        // If no conversation exists, create one
        if (!$conversation) {
            $conversation = Conversation::create([
                'title' => 'Chat dengan ' . $otherUser->name
            ]);

            $conversation->participants()->attach([auth()->id(), $otherUser->id]);
        }

        // Create message
        Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => auth()->id(),
            'body' => $validated['message'],
        ]);

        return redirect()->route('admin.chat.show', $conversation->id)
            ->with('success', 'Pesan berhasil dikirim!');
    }

    /**
     * Send a message in a conversation
     */
    public function sendMessage(Request $request, Conversation $conversation)
    {
        // Ensure admin is a participant
        if (!$conversation->participants->contains(auth()->id())) {
            abort(403);
        }

        $validated = $request->validate([
            'body' => 'required|string|max:5000',
        ]);

        Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => auth()->id(),
            'body' => $validated['body'],
        ]);

        return redirect()->route('admin.chat.show', $conversation->id)
            ->with('success', 'Pesan berhasil dikirim!');
    }

    /**
     * Get unread messages count
     */
    public function unreadCount()
    {
        $count = Conversation::whereHas('participants', function ($query) {
                $query->where('users.id', auth()->id());
            })
            ->whereHas('messages', function ($query) {
                $query->where('user_id', '!=', auth()->id())
                    ->whereDoesntHave('conversation.participants', function ($q) {
                        $q->where('users.id', auth()->id())
                            ->whereNotNull('conversation_participants.read_at');
                    });
            })
            ->count();

        return response()->json(['count' => $count]);
    }
}

