<?php

namespace App\Http\Controllers;

use App\Models\ProductDetail;
use App\Models\ProductPhoto;
use Illuminate\Http\Request;

class ProductPhotoController extends Controller
{
    public function index(ProductDetail $variant)
    {
        $photos = $variant->photos;
        
        return view('products.photos.index', compact('variant', 'photos'));
    }
    
    public function store(Request $request, ProductDetail $variant)
    {
        $validated = $request->validate([
            'photos' => 'required',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $uploadedFiles = [];

        foreach ($request->file('photos') as $photo) {
            try {
                // Store file on the supabase disk explicitly with variant ID folder
                $path = $photo->store('products/' . $variant->id, 'supabase');
                
                // Get the full public URL
                $fullUrl = \Illuminate\Support\Facades\Storage::disk('supabase')->url($path);

                \Log::info('File stored on Supabase:', ['path' => $path, 'url' => $fullUrl]);

                ProductPhoto::create([
                    'product_detail_id' => $variant->id,
                    'photo_url' => $fullUrl
                ]);

                $uploadedFiles[] = $fullUrl;
            } catch (\Exception $e) {
                \Log::error('Failed to store photo on Supabase:', ['error' => $e->getMessage()]);

                // Optionally, you could try to store on local disk as fallback
                // $path = $photo->store('products', 'public');

                throw $e; // Re-throw the exception to prevent silent failure
            }
        }

        return back()->with('success', count($uploadedFiles) . ' foto berhasil diupload.');
    }
    
    public function destroy(ProductDetail $variant, ProductPhoto $photo)
    {
        try {
            // Extract the path from the full URL or relative path
            $path = ProductPhoto::extractPathFromUrl($photo->getRawOriginal('photo_url'));
            
            if ($path) {
                \Illuminate\Support\Facades\Storage::disk('supabase')->delete($path);
                \Log::info('Deleted photo from Supabase:', ['path' => $path]);
            }
        } catch (\Exception $e) {
            \Log::error('Failed to delete photo from Supabase:', ['error' => $e->getMessage()]);
        }
        
        $photo->delete();
        
        return back()->with('success', 'Foto berhasil dihapus.');
    }
}