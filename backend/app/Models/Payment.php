<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'user_id',
        'payment_method',
        'midtrans_order_id',
        'midtrans_transaction_id',
        'midtrans_transaction_status',
        'midtrans_payment_type',
        'gross_amount',
        'va_number',
        'pdf_url',
        'income',
        'profit'
    ];

    protected $casts = [
        'gross_amount' => 'integer',
        'income' => 'integer',
        'profit' => 'integer'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isPending()
    {
        return $this->midtrans_transaction_status === 'pending';
    }

    public function isSuccess()
    {
        return in_array($this->midtrans_transaction_status, ['capture', 'settlement']);
    }

    public function isFailed()
    {
        return in_array($this->midtrans_transaction_status, ['deny', 'expire', 'cancel']);
    }
}