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
    public function index(Request $request)
    {
        $query = Product::with([
            'brand',
            'type',
            'productDetails',
            'productDetails.photos',
            'productDetails.size',
            'productDetails.color'
        ]);

        // Search by name
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where('name', 'LIKE', "%{$searchTerm}%");
        }

        // Filter by brand
        if ($request->has('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        // Filter by type
        if ($request->has('type_id')) {
            $query->where('type_id', $request->type_id);
        }

        $products = $query->latest()->paginate(12);

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