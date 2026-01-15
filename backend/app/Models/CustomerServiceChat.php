<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// ============ CustomerServiceChat Model ============
class CustomerServiceChat extends Model
{
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'message_type',
        'sent_at',
        'is_read'
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'is_read' => 'boolean'
    ];

    // Relationships
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    // Helper Methods
    public function markAsRead()
    {
        $this->is_read = true;
        $this->save();
    }

    public static function getUnreadCount($userId)
    {
        return self::where('receiver_id', $userId)
            ->where('is_read', false)
            ->count();
    }

    public static function getConversation($userId1, $userId2, $limit = 50)
    {
        // Check if user is cashier
        $user = \App\Models\User::find($userId1);

        \Log::info('CustomerServiceChat getConversation called', [
            'user_id' => $userId1,
            'target_user_id' => $userId2,
            'is_cashier' => $user ? $user->isKasir() : false
        ]);

        // Both cashier and admin can see all conversations involving the customer
        if ($user && ($user->isKasir() || $user->isAdmin())) {
            // Cashier and admin can see all conversations involving the customer
            $query = self::where(function($query) use ($userId2) {
                    $query->where('sender_id', $userId2);
                })
                ->orWhere(function($query) use ($userId2) {
                    $query->where('receiver_id', $userId2);
                });

            \Log::info('Cashier/Admin query SQL', ['sql' => $query->toSql(), 'bindings' => $query->getBindings()]);

            $results = $query->orderBy('sent_at', 'desc')
                ->limit($limit)
                ->get()
                ->reverse()
                ->values();

            \Log::info('Cashier/Admin query results', ['count' => $results->count()]);

            return $results;
        } else {
            // Other roles see conversation between specific users only
            $query = self::where(function($query) use ($userId1, $userId2) {
                    $query->where('sender_id', $userId1)
                          ->where('receiver_id', $userId2);
                })
                ->orWhere(function($query) use ($userId1, $userId2) {
                    $query->where('sender_id', $userId2)
                          ->where('receiver_id', $userId1);
                });

            \Log::info('Other role query SQL', ['sql' => $query->toSql(), 'bindings' => $query->getBindings()]);

            $results = $query->orderBy('sent_at', 'desc')
                ->limit($limit)
                ->get()
                ->reverse()
                ->values();

            \Log::info('Other role query results', ['count' => $results->count()]);

            return $results;
        }
    }

    public static function getCustomerList()
    {
        // Get list of customers who have sent messages
        // For cashier and admin, show all customers who have chatted
        return self::select('sender_id')
            ->distinct()
            ->with('sender')
            ->whereHas('sender', function($query) {
                $query->whereHas('role', function($q) {
                    $q->where('name', 'Customer');
                });
            })
            ->get()
            ->pluck('sender')
            ->unique('id');
    }
}