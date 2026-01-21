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
        if (empty($value)) {
            return null;
        }

        // If it's already a full URL, return as is
        if (str_starts_with($value, 'http')) {
            return $value;
        }

        try {
            // Check if it exists on supabase and return URL
            return \Illuminate\Support\Facades\Storage::disk('supabase')->url($value);
        } catch (\Exception $e) {
            \Log::error('Error getting URL from Supabase:', ['error' => $e->getMessage(), 'value' => $value]);
            // Fallback to default disk URL
            return \Illuminate\Support\Facades\Storage::disk()->url($value);
        }
    }

    /**
     * Extract the storage path from a full Supabase URL
     */
    public static function extractPathFromUrl($url)
    {
        if (empty($url)) return null;

        // If it's already a path (doesn't start with http), return it
        if (!str_starts_with($url, 'http')) {
            return $url;
        }

        // Get the base bucket URL from config
        $baseUrl = config('filesystems.disks.supabase.url');
        
        // Remove the base URL to get the path
        if (str_starts_with($url, $baseUrl)) {
            return ltrim(substr($url, strlen($baseUrl)), '/');
        }

        // Fallback: try to find 'products/' in the URL if it's a standard Supabase public storage URL
        // pattern: .../object/public/bucket-name/folder/file
        $search = '/object/public/' . config('filesystems.disks.supabase.bucket') . '/';
        $pos = strpos($url, $search);
        if ($pos !== false) {
            return substr($url, $pos + strlen($search));
        }

        return $url; // Return original if pattern not found
    }
}