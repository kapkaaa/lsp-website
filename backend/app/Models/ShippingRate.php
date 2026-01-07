<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingRate extends Model
{
    protected $fillable = ['region', 'price_per_kg'];
    
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}