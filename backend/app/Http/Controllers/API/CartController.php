<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ProductDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display the user's cart.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        $cartItems = $user->cartItems()->with([
            'productDetail.product',
            'productDetail.photos',
            'productDetail.size',
            'productDetail.color'
        ])->get();

        $totalPrice = $cartItems->sum(function ($item) {
            return $item->quantity * $item->productDetail->price;
        });

        return response()->json([
            'data' => [
                'items' => $cartItems,
                'total_price' => $totalPrice,
                'count' => $cartItems->count()
            ],
            'message' => 'Cart retrieved successfully'
        ]);
    }

    /**
     * Add an item to the cart.
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_detail_id' => 'required|exists:product_details,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $user = $request->user();
        $productDetail = ProductDetail::findOrFail($request->product_detail_id);

        // Check if item already exists in cart
        $existingCartItem = $user->cartItems()
            ->where('product_detail_id', $request->product_detail_id)
            ->first();

        if ($existingCartItem) {
            $existingCartItem->update([
                'quantity' => $existingCartItem->quantity + $request->quantity
            ]);
            $message = 'Cart updated successfully';
        } else {
            $user->cartItems()->create([
                'product_detail_id' => $request->product_detail_id,
                'quantity' => $request->quantity
            ]);
            $message = 'Item added to cart successfully';
        }

        return response()->json([
            'message' => $message
        ], 201);
    }

    /**
     * Update an item in the cart.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $user = $request->user();
        $cartItem = $user->cartItems()->findOrFail($id);

        $cartItem->update([
            'quantity' => $request->quantity
        ]);

        return response()->json([
            'message' => 'Cart item updated successfully'
        ]);
    }

    /**
     * Remove an item from the cart.
     */
    public function remove(Request $request, $id)
    {
        $user = $request->user();
        $cartItem = $user->cartItems()->findOrFail($id);

        $cartItem->delete();

        return response()->json([
            'message' => 'Item removed from cart successfully'
        ]);
    }
}