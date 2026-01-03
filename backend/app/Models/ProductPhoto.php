<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductPhoto extends Model
{
    protected $fillable = [
        'product_detail_id',
        'photo_url'
    ];

    public function productDetail(): BelongsTo
    {
        return $this->belongsTo(ProductDetail::class, 'product_detail_id');
    }
}