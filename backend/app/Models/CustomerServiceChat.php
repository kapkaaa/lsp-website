<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerServiceChat extends Model
{
    protected $fillable = [
        'sender_id', 'receiver_id', 'message',
        'message_type', 'sent_at', 'is_read'
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'is_read' => 'boolean'
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}