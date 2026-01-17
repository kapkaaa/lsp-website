<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CustomerServiceChat;
use App\Models\User;
use App\Models\OperationalHour;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ChatController extends Controller
{
    public function index()
    {
        // Check operational hours
        $isOperational = OperationalHour::isOperational('online');
        $operationalMessage = OperationalHour::getOperationalMessage('online');

        // Get admin/kasir user for chat
        $csUser = User::whereHas('role', function($query) {
            $query->whereIn('name', ['Admin', 'Kasir']);
        })->first();

        if (!$csUser) {
            return view('customer.chat.index', [
                'messages' => collect([]),
                'isOperational' => false,
                'operationalMessage' => 'Customer service is not available',
                'csUserId' => null
            ]);
        }

        // Get conversation
        $messages = CustomerServiceChat::getConversation(auth()->id(), $csUser->id);

        // Mark messages as read
        CustomerServiceChat::where('sender_id', $csUser->id)
            ->where('receiver_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('customer.chat.index', compact('messages', 'isOperational', 'operationalMessage', 'csUser'));
    }

    public function send(Request $request)
    {
        // Check operational hours
        if (!OperationalHour::isOperational('online')) {
            return response()->json([
                'success' => false,
                'message' => 'Customer service is currently closed'
            ], 403);
        }

        $validated = $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        // Get admin/kasir user
        $csUser = User::whereHas('role', function($query) {
            $query->whereIn('name', ['Admin', 'Kasir']);
        })->first();

        if (!$csUser) {
            return response()->json([
                'success' => false,
                'message' => 'Customer service is not available'
            ], 404);
        }

        $chat = CustomerServiceChat::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $csUser->id,
            'message' => $validated['message'],
            'message_type' => 'text',
            'sent_at' => Carbon::now(),
            'is_read' => false
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully',
            'data' => [
                'id' => $chat->id,
                'message' => $chat->message,
                'sent_at' => $chat->sent_at->format('Y-m-d H:i:s'),
                'sender' => 'me'
            ]
        ]);
    }

    public function getMessages(Request $request)
    {
        $csUser = User::whereHas('role', function($query) {
            $query->whereIn('name', ['Admin', 'Kasir']);
        })->first();

        if (!$csUser) {
            return response()->json([
                'success' => true,
                'data' => []
            ]);
        }

        $messages = CustomerServiceChat::getConversation(auth()->id(), $csUser->id, 50);

        // Mark as read
        CustomerServiceChat::where('sender_id', $csUser->id)
            ->where('receiver_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $formattedMessages = $messages->map(function($msg) use ($csUser) {
            return [
                'id' => $msg->id,
                'message' => $msg->message,
                'sent_at' => $msg->sent_at->format('Y-m-d H:i:s'),
                'sender' => $msg->sender_id === auth()->id() ? 'me' : 'cs',
                'is_read' => $msg->is_read
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $formattedMessages
        ]);
    }
}