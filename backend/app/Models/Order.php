<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// ============ Order Model ============
class Order extends Model
{
    protected $fillable = [
        'buyer_id',
        'approved_by',
        'shipping_rate_id',
        'order_code',
        'subtotal',
        'weight',
        'shipping_cost',
        'total_payment',
        'destination_city',
        'payment_proof',
        'payment_status',
        'order_status',
        'payment_method'
    ];

    protected $appends = ['payment_proof_url'];

    protected $casts = [
        'subtotal' => 'integer',
        'weight' => 'integer',
        'shipping_cost' => 'integer',
        'total_payment' => 'integer'
    ];

    // Relationships
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

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    // Helper Methods
    public static function generateOrderCode()
    {
        $date = date('Ymd');
        $lastOrder = self::whereDate('created_at', today())
            ->orderBy('id', 'desc')
            ->first();
        
        $number = $lastOrder ? intval(substr($lastOrder->order_code, -4)) + 1 : 1;
        return 'ORD-' . $date . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function canUploadPayment()
    {
        return $this->payment_status === 'pending' && $this->order_status === 'pending';
    }

    public function canBeVerified()
    {
        return $this->payment_status === 'pending' && !empty($this->payment_proof);
    }

    public function canBeRejected()
    {
        return $this->payment_status === 'pending';
    }

    public function canBeShipped()
    {
        return $this->payment_status === 'paid' && $this->order_status === 'verified';
    }

    public function isPending()
    {
        return $this->order_status === 'pending';
    }

    public function isVerified()
    {
        return $this->order_status === 'verified';
    }

    public function isShipped()
    {
        return $this->order_status === 'shipped';
    }

    public function isCompleted()
    {
        return $this->order_status === 'completed';
    }

    public function isCancelled()
    {
        return $this->order_status === 'cancelled';
    }

    public function getPaymentProofUrlAttribute()
    {
        if ($this->payment_proof && !str_starts_with($this->payment_proof, 'http')) {
            // Use the supabase disk to generate the URL directly
            // Removed exists() check as it causes slow network calls during JSON serialization
            return \Illuminate\Support\Facades\Storage::disk('supabase')->url($this->payment_proof);
        }
        return $this->payment_proof;
    }
}