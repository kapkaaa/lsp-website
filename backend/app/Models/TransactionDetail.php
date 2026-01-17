<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// ============ TransactionDetail Model ============
class TransactionDetail extends Model
{
    protected $fillable = [
        'transaction_id',
        'product_detail_id',
        'quantity',
        'unit_price',
        'subtotal'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'integer',
        'subtotal' => 'integer'
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function productDetail()
    {
        return $this->belongsTo(ProductDetail::class);
    }
}