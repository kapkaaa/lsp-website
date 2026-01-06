<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id', 'transaction_code', 'total',
        'payment_method', 'transaction_status',
        'cash_received', 'change_given'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }
    
    // Calculate profit
    public function getProfitAttribute()
    {
        $totalCost = 0;
        foreach ($this->details as $detail) {
            $costPrice = $detail->productDetail->product->cost_price;
            $totalCost += $costPrice * $detail->quantity;
        }
        return $this->total - $totalCost;
    }
    
    // Generate unique transaction code
    public static function generateTransactionCode()
    {
        do {
            $code = 'TRX' . date('YmdHis') . mt_rand(10, 99);
        } while (self::where('transaction_code', $code)->exists());
        
        return $code;
    }
}