<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'buyer_id', 'approved_by', 'shipping_rate_id',
        'order_code', 'subtotal', 'weight', 'shipping_cost',
        'total_payment', 'destination_city', 'payment_proof',
        'payment_status', 'order_status', 'payment_method'
    ];

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function shippingRate()
    {
        return $this->belongsTo(ShippingRate::class);
    }

    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }
    
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
    
    // Generate unique order code
    public static function generateOrderCode()
    {
        do {
            $code = 'ORD' . date('Ymd') . str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
        } while (self::where('order_code', $code)->exists());
        
        return $code;
    }
}