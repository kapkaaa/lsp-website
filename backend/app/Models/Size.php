<?php

namespace App\Models;

use App\Models\ProductDetail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class size extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'information'];

    public function productDetails()
    {
        return $this->hasMany(ProductDetail::class);
    }
}