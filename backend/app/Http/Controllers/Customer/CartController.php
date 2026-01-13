<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\ProductDetail;
use App\Models\ShippingRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

// ============ CartController ============
class CartController extends Controller
{
    public function index()
    {
        $cart = Session::get('cart', []);
        $cartItems = [];
        $subtotal = 0;

        foreach ($cart as $item) {
            $productDetail = ProductDetail::with(['product', 'size', 'color', 'photos'])
                ->find($item['product_detail_id']);

            if ($productDetail && $productDetail->isAvailable()) {
                $itemTotal = $productDetail->product->selling_price * $item['quantity'];
                $cartItems[] = [
                    'product_detail' => $productDetail,
                    'quantity' => $item['quantity'],
                    'price' => $productDetail->product->selling_price,
                    'total' => $itemTotal
                ];
                $subtotal += $itemTotal;
            }
        }

        return view('customer.cart.index', compact('cartItems', 'subtotal'));
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_detail_id' => 'required|exists:product_details,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $productDetail = ProductDetail::with('product')->findOrFail($validated['product_detail_id']);

        if (!$productDetail->isAvailable()) {
            return response()->json([
                'success' => false,
                'message' => 'Product is not available'
            ], 400);
        }

        if ($productDetail->stock < $validated['quantity']) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient stock'
            ], 400);
        }

        $cart = Session::get('cart', []);
        $found = false;

        foreach ($cart as &$item) {
            if ($item['product_detail_id'] == $validated['product_detail_id']) {
                $newQuantity = $item['quantity'] + $validated['quantity'];
                if ($newQuantity > $productDetail->stock) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Quantity exceeds available stock'
                    ], 400);
                }
                $item['quantity'] = $newQuantity;
                $found = true;
                break;
            }
        }

        if (!$found) {
            $cart[] = [
                'product_detail_id' => $validated['product_detail_id'],
                'quantity' => $validated['quantity']
            ];
        }

        Session::put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart',
            'cart_count' => count($cart)
        ]);
    }

    public function update(Request $request, $productDetailId)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $productDetail = ProductDetail::findOrFail($productDetailId);

        if ($productDetail->stock < $validated['quantity']) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient stock'
            ], 400);
        }

        $cart = Session::get('cart', []);

        foreach ($cart as &$item) {
            if ($item['product_detail_id'] == $productDetailId) {
                $item['quantity'] = $validated['quantity'];
                break;
            }
        }

        Session::put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Cart updated'
        ]);
    }

    public function remove($productDetailId)
    {
        $cart = Session::get('cart', []);
        $cart = array_filter($cart, function($item) use ($productDetailId) {
            return $item['product_detail_id'] != $productDetailId;
        });

        Session::put('cart', array_values($cart));

        return redirect()->back()->with('success', 'Item removed from cart');
    }

    public function clear()
    {
        Session::forget('cart');
        return redirect()->back()->with('success', 'Cart cleared');
    }

    public function count()
    {
        $cart = Session::get('cart', []);
        return response()->json([
            'success' => true,
            'count' => count($cart)
        ]);
    }
}