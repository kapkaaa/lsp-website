<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductDetail extends Model
{
    protected $fillable = [
        'product_id',
        'size_id',
        'color_id',
        'stock',
        'status',
        'barcode'
    ];

    protected $casts = [
        'stock' => 'integer'
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // app/Models/ProductDetail.php
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'product_detail_id');
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function photos()
    {
        return $this->hasMany(ProductPhoto::class);
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function variantValues()
    {
        return $this->hasMany(ProductDetail::class, 'id', 'id')
            ->with(['size', 'color']);
    }

    // Helper Methods
    public function getFullName()
    {
        return $this->product->name . ' - ' . $this->size->name . ' - ' . $this->color->name;
    }

    public function isAvailable()
    {
        return $this->status === 'available' && $this->stock > 0;
    }

    public function decreaseStock($quantity)
    {
        if ($this->stock >= $quantity) {
            $this->stock -= $quantity;
            if ($this->stock == 0) {
                $this->status = 'out_of_stock';
            }
            $this->save();
            return true;
        }
        return false;
    }

    public function increaseStock($quantity)
    {
        $this->stock += $quantity;
        if ($this->stock > 0) {
            $this->status = 'available';
        }
        $this->save();
    }
}
