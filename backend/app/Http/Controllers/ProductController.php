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
        $products = Product::with(['brand', 'type', 'productDetails'])->get();
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'brand_id' => 'required|exists:brands,id',
            'type_id' => 'required|exists:types,id',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Product::create($request->all());

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        $product->load(['brand', 'type', 'productDetails.productPhotos']);
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $brands = Brand::all();
        $types = Type::all();
        return view('products.edit', compact('product', 'brands', 'types'));
    }

    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'brand_id' => 'required|exists:brands,id',
            'type_id' => 'required|exists:types,id',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $product->update($request->all());

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
