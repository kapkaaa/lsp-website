<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CustomerServiceChat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Display a listing of the user's chat messages.
     */
    public function getMessages(Request $request)
    {
        $user = $request->user();
        
        // Get messages where user is either sender or receiver
        $chats = CustomerServiceChat::where(function ($query) use ($user) {
                $query->where('sender_id', $user->id)
                      ->orWhere('receiver_id', $user->id);
            })
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'data' => $chats,
            'message' => 'Messages retrieved successfully'
        ]);
    }

    /**
     * Send a new chat message.
     */
    public function send(Request $request)
    {
        $request->validate([
            'receiver_id' => 'nullable|exists:users,id',
            'message' => 'required|string|max:1000'
        ]);

        $user = $request->user();
        
        // If receiver_id is not provided, default to Admin (ID 1)
        // This is useful for customers starting a chat with customer service
        $receiverId = $request->receiver_id ?? 1;

        $chat = CustomerServiceChat::create([
            'sender_id' => $user->id,
            'receiver_id' => $receiverId,
            'message' => $request->message
        ]);

        return response()->json([
            'data' => $chat->load('sender', 'receiver'),
            'message' => 'Message sent successfully'
        ], 201);
    }
}