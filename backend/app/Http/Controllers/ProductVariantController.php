<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\Size;
use App\Models\Color;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    public function index(Product $product)
    {
        $variants = $product->details()
                           ->with(['size', 'color', 'photos'])
                           ->get();
        
        return view('products.variants.index', compact('product', 'variants'));
    }
    
    public function create(Product $product)
    {
        $sizes = Size::all();
        $colors = Color::all();
        
        return view('products.variants.create', compact('product', 'sizes', 'colors'));
    }
    
    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'size_id' => 'required|exists:sizes,id',
            'color_id' => 'required|exists:colors,id',
            'stock' => 'required|integer|min:0',
            'status' => 'required|in:available,out_of_stock,discontinued'
        ]);
        
        // Check if variant already exists
        $exists = ProductDetail::where('product_id', $product->id)
                               ->where('size_id', $validated['size_id'])
                               ->where('color_id', $validated['color_id'])
                               ->exists();
        
        if ($exists) {
            return back()->withErrors(['error' => 'Varian dengan warna dan size ini sudah ada!']);
        }
        
        $validated['product_id'] = $product->id;
        $validated['barcode'] = ProductDetail::generateBarcode();
        
        ProductDetail::create($validated);
        
        return redirect()->route('products.variants.index', $product)
                        ->with('success', 'Varian berhasil ditambahkan.');
    }
    
    public function edit(Product $product, ProductDetail $variant)
    {
        $sizes = Size::all();
        $colors = Color::all();
        
        return view('products.variants.edit', compact('product', 'variant', 'sizes', 'colors'));
    }
    
    public function update(Request $request, Product $product, ProductDetail $variant)
    {
        $validated = $request->validate([
            'size_id' => 'required|exists:sizes,id',
            'color_id' => 'required|exists:colors,id',
            'stock' => 'required|integer|min:0',
            'status' => 'required|in:available,out_of_stock,discontinued'
        ]);
        
        $variant->update($validated);
        
        return redirect()->route('products.variants.index', $product)
                        ->with('success', 'Varian berhasil diupdate.');
    }
    
    public function destroy(Product $product, ProductDetail $variant)
    {
        // Delete photos
        foreach ($variant->photos as $photo) {
            $photo->delete();
        }
        
        $variant->delete();
        
        return redirect()->route('products.variants.index', $product)
                        ->with('success', 'Varian berhasil dihapus.');
    }
}