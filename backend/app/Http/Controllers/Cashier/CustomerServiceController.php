<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\CustomerServiceChat;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CustomerServiceController extends Controller
{
    public function index()
    {
        // Get list of customers who have chatted
        $customers = CustomerServiceChat::getCustomerList();

        return view('cashier.customer_service.index', compact('customers'));
    }

    public function getMessages(Request $request, $userId)
    {
        $messages = CustomerServiceChat::getConversation(auth()->id(), $userId);

        // Mark messages as read
        CustomerServiceChat::where('sender_id', $userId)
            ->where('receiver_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $messages
            ]);
        }

        return view('admin.customer-service.messages', compact('messages', 'userId'));
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000'
        ]);

        $chat = CustomerServiceChat::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $validated['receiver_id'],
            'message' => $validated['message'],
            'message_type' => 'text',
            'sent_at' => Carbon::now(),
            'is_read' => false
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Message sent successfully',
                'data' => $chat
            ]);
        }

        return redirect()->back()
            ->with('success', 'Message sent successfully');
    }

    public function getUnreadCount()
    {
        $count = CustomerServiceChat::getUnreadCount(auth()->id());

        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }
}