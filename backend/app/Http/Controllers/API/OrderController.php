<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the user's orders.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        $orders = Order::where('user_id', $user->id)
            ->with(['orderItems', 'payment'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'data' => $orders,
            'message' => 'Orders retrieved successfully'
        ]);
    }

    /**
     * Store a newly created order.
     */
    public function store(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string',
            'shipping_method' => 'required|string',
            'payment_method' => 'required|string',
        ]);

        $user = $request->user();
        
        // Get user's cart items
        $cartItems = $user->cartItems()->with('productDetail')->get();
        
        if ($cartItems->isEmpty()) {
            return response()->json([
                'message' => 'Cannot create order: Cart is empty'
            ], 400);
        }

        // Calculate total amount
        $totalAmount = $cartItems->sum(function ($item) {
            return $item->quantity * $item->productDetail->price;
        });

        // Create order
        $order = Order::create([
            'user_id' => $user->id,
            'total_amount' => $totalAmount,
            'shipping_address' => $request->shipping_address,
            'shipping_method' => $request->shipping_method,
            'payment_method' => $request->payment_method,
            'status' => 'pending' // Default status
        ]);

        // Create order items
        foreach ($cartItems as $cartItem) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_detail_id' => $cartItem->product_detail_id,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->productDetail->price
            ]);

            // Reduce stock
            $productDetail = $cartItem->productDetail;
            $productDetail->update([
                'stock' => $productDetail->stock - $cartItem->quantity
            ]);
        }

        // Clear user's cart
        $user->cartItems()->delete();

        return response()->json([
            'data' => $order->load('orderItems'),
            'message' => 'Order created successfully'
        ], 201);
    }

    /**
     * Display the specified order.
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();
        
        $order = Order::where('id', $id)
            ->where('user_id', $user->id)
            ->with(['orderItems', 'payment'])
            ->firstOrFail();

        return response()->json([
            'data' => $order,
            'message' => 'Order retrieved successfully'
        ]);
    }
}