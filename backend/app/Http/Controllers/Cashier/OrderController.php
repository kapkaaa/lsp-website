<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['buyer', 'shippingRate']);

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_code', 'like', "%{$search}%")
                  ->orWhereHas('buyer', function($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by payment status
        if ($request->has('payment_status') && $request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by order status
        if ($request->has('order_status') && $request->order_status) {
            $query->where('order_status', $request->order_status);
        }

        // Filter by date range
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('cashier.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load([
            'buyer',
            'approver',
            'shippingRate',
            'orderDetails.product.brand',
            'orderDetails.product.type',
            'orderDetails.product_detail.color',
            'orderDetails.product_detail.size',
            'payment'
        ]);

        return view('cashier.orders.show', compact('order'));
    }
}