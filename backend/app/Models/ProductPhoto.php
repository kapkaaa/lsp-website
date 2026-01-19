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

    public function productDetail()
    {
        return $this->belongsTo(ProductDetail::class);
    }

    public function getPhotoUrlAttribute($value)
    {
        if ($value && !str_starts_with($value, 'http')) {
            try {
                // Check if the file exists on the supabase disk and generate the appropriate URL
                if (\Illuminate\Support\Facades\Storage::disk('supabase')->exists($value)) {
                    return \Illuminate\Support\Facades\Storage::disk('supabase')->url($value);
                }
            } catch (\Exception $e) {
                \Log::error('Error getting URL from Supabase:', ['error' => $e->getMessage(), 'value' => $value]);
            }

            // Fallback to default disk
            return \Illuminate\Support\Facades\Storage::disk()->url($value);
        }
        return $value;
    }
}