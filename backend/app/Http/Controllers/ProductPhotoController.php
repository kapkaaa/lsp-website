<?php

namespace App\Http\Controllers;

use App\Models\ProductPhoto;
use App\Models\ProductDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductPhotoController extends Controller
{
    public function index()
    {
        $productPhotos = ProductPhoto::with('productDetail')->get();
        return view('productPhotos.index', compact('productPhotos'));
    }

    public function create()
    {
        $productDetails = ProductDetail::all();
        return view('productPhotos.create', compact('productDetails'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_detail_id' => 'required|exists:product_details,id',
            'photo_url' => 'required|url|max:255'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        ProductPhoto::create($request->all());

        return redirect()->route('productPhotos.index')->with('success', 'Product photo created successfully.');
    }

    public function show(ProductPhoto $productPhoto)
    {
        $productPhoto->load('productDetail');
        return view('productPhotos.show', compact('productPhoto'));
    }

    public function edit(ProductPhoto $productPhoto)
    {
        $productDetails = ProductDetail::all();
        return view('productPhotos.edit', compact('productPhoto', 'productDetails'));
    }

    public function update(Request $request, ProductPhoto $productPhoto)
    {
        $validator = Validator::make($request->all(), [
            'product_detail_id' => 'required|exists:product_details,id',
            'photo_url' => 'required|url|max:255'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $productPhoto->update($request->all());

        return redirect()->route('productPhotos.index')->with('success', 'Product photo updated successfully.');
    }

    public function destroy(ProductPhoto $productPhoto)
    {
        $productPhoto->delete();

        return redirect()->route('productPhotos.index')->with('success', 'Product photo deleted successfully.');
    }
}