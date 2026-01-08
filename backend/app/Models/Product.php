<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// ============ Product Model ============
class Product extends Model
{
    protected $fillable = [
        'brand_id',
        'type_id',
        'name',
        'selling_price',
        'cost_price'
    ];

    protected $casts = [
        'selling_price' => 'integer',
        'cost_price' => 'integer'
    ];

    // Relationships
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function productDetails()
    {
        return $this->hasMany(ProductDetail::class, 'product_id');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    // Helper Methods
    public function getTotalStock()
    {
        return $this->productDetails()->sum('stock');
    }

    public function getAvailableStock()
    {
        return $this->productDetails()
            ->where('status', 'available')
            ->sum('stock');
    }
}