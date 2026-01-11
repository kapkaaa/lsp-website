<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load([
            'buyer', 
            'approver', 
            'shippingRate', 
            'orderDetails.product.brand',
            'orderDetails.product.type',
            'payment'
        ]);

        return view('admin.orders.show', compact('order'));
    }

    public function verifyPayment(Request $request, Order $order)
    {
        if (!$order->canBeVerified()) {
            return redirect()->back()
                ->with('error', 'Order cannot be verified');
        }

        try {
            DB::beginTransaction();

            // Update order status
            $order->payment_status = 'paid';
            $order->order_status = 'verified';
            $order->approved_by = auth()->id();
            $order->save();

            // Create or update payment record
            $payment = Payment::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'user_id' => $order->buyer_id,
                    'payment_method' => $order->payment_method ?? 'transfer',
                    'gross_amount' => $order->total_payment,
                    'income' => $order->total_payment,
                    'profit' => $order->subtotal - ($order->orderDetails->sum(function($detail) {
                        return $detail->product->cost_price * $detail->quantity;
                    }))
                ]
            );

            DB::commit();

            return redirect()->back()
                ->with('success', 'Payment verified successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to verify payment: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'order_status' => 'required|in:pending,verified,shipped,completed,cancelled'
        ]);

        try {
            DB::beginTransaction();

            $oldStatus = $order->order_status;
            $order->order_status = $validated['order_status'];

            // If cancelled, restore stock
            if ($validated['order_status'] === 'cancelled' && $oldStatus !== 'cancelled') {
                foreach ($order->orderDetails as $detail) {
                    // Assuming you store product_detail_id in order_details
                    // You may need to adjust this based on your actual implementation
                    $product = $detail->product;
                    $product->productDetails()->first()->increaseStock($detail->quantity);
                }
            }

            $order->save();

            DB::commit();

            return redirect()->back()
                ->with('success', 'Order status updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to update order status: ' . $e->getMessage());
        }
    }
    

    public function reject(Request $request, Order $order)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            $order->payment_status = 'rejected';
            $order->order_status = 'cancelled';
            $order->approved_by = auth()->id();
            // You may want to add rejection_reason field to orders table
            $order->save();

            // Restore stock
            foreach ($order->orderDetails as $detail) {
                $product = $detail->product;
                $product->productDetails()->first()->increaseStock($detail->quantity);
            }

            DB::commit();

            return redirect()->back()
                ->with('success', 'Payment rejected and order cancelled');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to reject payment: ' . $e->getMessage());
        }
    }
}