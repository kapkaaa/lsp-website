<?php

namespace App\Models;

use App\Models\ProductDetail;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $fillable = ['name', 'information'];
    
    public function productDetails()
    {
        return $this->hasMany(ProductDetail::class);
    }
}