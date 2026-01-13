<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// ============ Transaction Model (POS Offline) ============
class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'transaction_code',
        'total',
        'payment_method',
        'transaction_status',
        'cash_received',
        'change_given'
    ];

    protected $casts = [
        'total' => 'integer',
        'cash_received' => 'integer',
        'change_given' => 'integer'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    // Helper Methods
    public static function generateTransactionCode()
    {
        $date = date('Ymd');
        $lastTransaction = self::whereDate('created_at', today())
            ->orderBy('id', 'desc')
            ->first();
        
        $number = $lastTransaction ? intval(substr($lastTransaction->transaction_code, -4)) + 1 : 1;
        return 'TRX-' . $date . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function calculateChange()
    {
        if ($this->payment_method === 'cash' && $this->cash_received > 0) {
            $this->change_given = $this->cash_received - $this->total;
        } else {
            $this->change_given = 0;
        }
    }

    public function isCompleted()
    {
        return $this->transaction_status === 'completed';
    }

    public function isPending()
    {
        return $this->transaction_status === 'pending';
    }

    public function isCancelled()
    {
        return $this->transaction_status === 'cancelled';
    }
}