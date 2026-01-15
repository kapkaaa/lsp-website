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
        return self::where(function($query) use ($userId1, $userId2) {
                $query->where('sender_id', $userId1)
                      ->where('receiver_id', $userId2);
            })
            ->orWhere(function($query) use ($userId1, $userId2) {
                $query->where('sender_id', $userId2)
                      ->where('receiver_id', $userId1);
            })
            ->orderBy('sent_at', 'desc')
            ->limit($limit)
            ->get()
            ->reverse()
            ->values();
    }

    public static function getCustomerList()
    {
        // Get list of customers who have sent messages
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