<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id', 'user_id', 'payment_method',
        'midtrans_order_id', 'midtrans_transaction_id',
        'midtrans_transaction_status', 'midtrans_payment_type',
        'gross_amount', 'va_number', 'pdf_url',
        'income', 'profit'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
