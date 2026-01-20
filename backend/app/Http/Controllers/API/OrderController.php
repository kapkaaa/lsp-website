<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
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
        
        $orders = Order::where('buyer_id', $user->id)
            ->with(['orderDetails.product_detail.product', 'payment'])
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
            'destination_city' => 'required|string',
            'shipping_address' => 'required|string',
            'payment_method' => 'required|string',
            'shipping_rate_id' => 'required|exists:shipping_rates,id',
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $user = $request->user();
        
        // Get selected item IDs from session/request if provided, else take all from cart
        $selectedIds = json_decode($request->input('selected_ids', '[]'), true);
        
        $query = $user->cartItems()->with('productDetail');
        if (!empty($selectedIds)) {
            $query->whereIn('id', $selectedIds);
        }
        
        $cartItems = $query->get();
        
        if ($cartItems->isEmpty()) {
            return response()->json([
                'message' => 'Cannot create order: No items selected or cart is empty'
            ], 400);
        }

        // Calculate totals
        $subtotal = $cartItems->sum(function ($item) {
            return $item->quantity * ($item->productDetail->product->selling_price ?? 0);
        });

        $shippingRate = \App\Models\ShippingRate::findOrFail($request->shipping_rate_id);
        $totalQuantity = $cartItems->sum('quantity');
        $weight = ceil($totalQuantity / 3);
        $shippingCost = $weight * $shippingRate->price_per_kg;
        $totalPayment = $subtotal + $shippingCost;

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            // Handle payment proof upload to Supabase
            $path = $request->file('payment_proof')->store('payments', 'supabase');

            // Create order with new model structure
            $order = Order::create([
                'buyer_id' => $user->id,
                'shipping_rate_id' => $request->shipping_rate_id,
                'order_code' => Order::generateOrderCode(),
                'subtotal' => $subtotal,
                'weight' => $weight,
                'shipping_cost' => $shippingCost,
                'total_payment' => $totalPayment,
                'destination_city' => $request->destination_city,
                'payment_proof' => $path,
                'payment_status' => 'pending',
                'order_status' => 'pending',
                'payment_method' => $request->payment_method
            ]);

            // Create order details and reduce stock
            foreach ($cartItems as $cartItem) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_detail_id' => $cartItem->product_detail_id,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $cartItem->productDetail->product->selling_price ?? 0,
                    'total' => $cartItem->quantity * ($cartItem->productDetail->product->selling_price ?? 0)
                ]);

                // Reduce stock
                $productDetail = $cartItem->productDetail;
                if ($productDetail) {
                    $productDetail->decrement('stock', $cartItem->quantity);
                }
            }

            // Clear ONLY selected cart items
            if (!empty($selectedIds)) {
                $user->cartItems()->whereIn('id', $selectedIds)->delete();
            } else {
                $user->cartItems()->delete();
            }

            \Illuminate\Support\Facades\DB::commit();

            return response()->json([
                'data' => $order->load('orderDetails'),
                'message' => 'Order created successfully'
            ], 201);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            \Log::error('Order creation failed:', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Failed to create order: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified order.
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();
        
        $order = Order::where('id', $id)
            ->where('buyer_id', $user->id)
            ->with(['orderDetails.product_detail.product', 'payment'])
            ->firstOrFail();

        return response()->json([
            'data' => $order,
            'message' => 'Order retrieved successfully'
        ]);
    }
}