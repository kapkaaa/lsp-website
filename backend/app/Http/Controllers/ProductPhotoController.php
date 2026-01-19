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
                // Store file on the supabase disk explicitly
                $path = $photo->store('products', 'supabase');

                // Log the path for debugging
                \Log::info('File stored on Supabase:', ['path' => $path]);

                ProductPhoto::create([
                    'product_detail_id' => $variant->id,
                    'photo_url' => $path
                ]);

                $uploadedFiles[] = $path;
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
        
        $photo->delete();
        
        return back()->with('success', 'Foto berhasil dihapus.');
    }
}