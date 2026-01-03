<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductDetail extends Model
{
    protected $fillable = [
        'size_id',
        'color_id',
        'stock',
        'product_id',
        'status',
        'barcode'
    ];

    protected $casts = [
        'stock' => 'integer',
        'status' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function productPhotos(): HasMany
    {
        return $this->hasMany(ProductPhoto::class, 'product_detail_id');
    }
}