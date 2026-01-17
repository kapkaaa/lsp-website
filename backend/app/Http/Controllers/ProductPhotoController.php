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
            $path = $photo->store('products', 'public');
            
            ProductPhoto::create([
                'product_detail_id' => $variant->id,
                'photo_url' => $path
            ]);
            
            $uploadedFiles[] = $path;
        }
        
        return back()->with('success', count($uploadedFiles) . ' foto berhasil diupload.');
    }
    
    public function destroy(ProductDetail $variant, ProductPhoto $photo)
    {
        
        $photo->delete();
        
        return back()->with('success', 'Foto berhasil dihapus.');
    }
}