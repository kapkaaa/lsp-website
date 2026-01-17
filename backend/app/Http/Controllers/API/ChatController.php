<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Chat;
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
        $chats = Chat::where(function ($query) use ($user) {
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
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000'
        ]);

        $user = $request->user();
        
        $chat = Chat::create([
            'sender_id' => $user->id,
            'receiver_id' => $request->receiver_id,
            'message' => $request->message
        ]);

        return response()->json([
            'data' => $chat->load('sender', 'receiver'),
            'message' => 'Message sent successfully'
        ], 201);
    }
}