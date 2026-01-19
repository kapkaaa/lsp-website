<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index()
    {
        $products = Product::with([
            'brand',
            'type',
            'productDetails',
            'productDetails.photos',
            'productDetails.size',
            'productDetails.color'
        ])
        ->paginate(12); // Adjust pagination as needed

        return response()->json([
            'data' => $products,
            'message' => 'Products retrieved successfully'
        ]);
    }

    /**
     * Display the specified product.
     */
    public function show($id)
    {
        $product = Product::with([
            'productDetails',
            'productDetails.photos',
            'productDetails.size',
            'productDetails.color',
            'brand',
            'type'
        ])->findOrFail($id);

        return response()->json([
            'data' => $product,
            'message' => 'Product retrieved successfully'
        ]);
    }
}