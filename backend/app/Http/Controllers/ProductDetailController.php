<?php

namespace App\Http\Controllers;

use App\Models\ProductDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductDetailController extends Controller
{
    public function index()
    {
        $productDetails = ProductDetail::with(['product', 'productPhotos'])->get();
        return view('productDetails.index', compact('productDetails'));
    }

    public function create()
    {
        $products = Product::all();
        return view('productDetails.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'size_id' => 'required|integer',
            'color_id' => 'required|integer',
            'stock' => 'required|integer|min:0',
            'product_id' => 'required|exists:products,id',
            'status' => 'required|boolean',
            'barcode' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        ProductDetail::create($request->all());

        return redirect()->route('productDetails.index')->with('success', 'Product detail created successfully.');
    }

    public function show(ProductDetail $productDetail)
    {
        $productDetail->load(['product', 'productPhotos']);
        return view('productDetails.show', compact('productDetail'));
    }

    public function edit(ProductDetail $productDetail)
    {
        $products = Product::all();
        return view('productDetails.edit', compact('productDetail', 'products'));
    }

    public function update(Request $request, ProductDetail $productDetail)
    {
        $validator = Validator::make($request->all(), [
            'size_id' => 'required|integer',
            'color_id' => 'required|integer',
            'stock' => 'required|integer|min:0',
            'product_id' => 'required|exists:products,id',
            'status' => 'required|boolean',
            'barcode' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $productDetail->update($request->all());

        return redirect()->route('productDetails.index')->with('success', 'Product detail updated successfully.');
    }

    public function destroy(ProductDetail $productDetail)
    {
        $productDetail->delete();

        return redirect()->route('productDetails.index')->with('success', 'Product detail deleted successfully.');
    }
}