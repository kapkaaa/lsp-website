<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Brand;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['brand', 'type', 'details'])
                          ->latest()
                          ->paginate(20);
        
        return view('products.index', compact('products'));
    }
    
    public function create()
    {
        $brands = Brand::all();
        $types = Type::all();
        
        return view('products.create', compact('brands', 'types'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'brand_id' => 'required|exists:brands,id',
            'type_id' => 'required|exists:types,id',
            'name' => 'required|string|max:255',
            'selling_price' => 'required|numeric|min:0',
            'cost_price' => 'required|numeric|min:0'
        ]);
        
        $product = Product::create($validated);
        
        return redirect()->route('products.variants.index', $product)
                        ->with('success', 'Produk berhasil dibuat. Silakan tambahkan varian.');
    }
    
    public function edit(Product $product)
    {
        $brands = Brand::all();
        $types = Type::all();
        
        return view('products.edit', compact('product', 'brands', 'types'));
    }
    
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'brand_id' => 'required|exists:brands,id',
            'type_id' => 'required|exists:types,id',
            'name' => 'required|string|max:255',
            'selling_price' => 'required|numeric|min:0',
            'cost_price' => 'required|numeric|min:0'
        ]);
        
        $product->update($validated);
        
        return redirect()->route('products.index')
                        ->with('success', 'Produk berhasil diupdate.');
    }
    
    public function destroy(Product $product)
    {
        // Delete all photos first
        foreach ($product->details as $detail) {
            foreach ($detail->photos as $photo) {
                $photo->delete();
            }
        }
        
        $product->delete();
        
        return redirect()->route('products.index')
                        ->with('success', 'Produk berhasil dihapus.');
    }
}