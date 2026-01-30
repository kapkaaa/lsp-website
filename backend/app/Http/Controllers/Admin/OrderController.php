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
            'orderDetails.product_detail.product.brand',
            'orderDetails.product_detail.product.type',
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
                        return ($detail->product_detail->product->cost_price ?? 0) * $detail->quantity;
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
            'order_status' => 'required|in:pending,verified,shipped,completed,cancelled,refunded'
        ]);

        try {
            DB::beginTransaction();

            $order->load('orderDetails.product_detail');

            $oldStatus = $order->order_status;
            $order->order_status = $validated['order_status'];

            // If cancelled, restore stock
            if ($validated['order_status'] === 'cancelled' && $oldStatus !== 'cancelled') {
                foreach ($order->orderDetails as $detail) {
                    if ($detail->product_detail) {
                        $detail->product_detail->increaseStock($detail->quantity);
                    }
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

            $order->load('orderDetails.product_detail');

            $order->payment_status = 'rejected';
            $order->order_status = 'cancelled';
            $order->approved_by = auth()->id();
            // You may want to add rejection_reason field to orders table
            $order->save();

            // Restore stock
            foreach ($order->orderDetails as $detail) {
                if ($detail->product_detail) {
                    $detail->product_detail->increaseStock($detail->quantity);
                }
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

    public function refund(Request $request, Order $order)
    {
        if (!$order->canBeRefunded()) {
            return redirect()->back()
                ->with('error', 'Order cannot be refunded at this stage');
        }

        try {
            DB::beginTransaction();

            // Update order status
            $order->payment_status = 'refunded';
            $order->order_status = 'refunded';
            $order->save();

            // Restore stock
            foreach ($order->orderDetails as $detail) {
                if ($detail->product_detail) {
                    $detail->product_detail->increaseStock($detail->quantity);
                }
            }

            DB::commit();

            return redirect()->back()
                ->with('success', 'Order has been refunded and stock restored');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to refund order: ' . $e->getMessage());
        }
    }
    public function approveRefundRequest(Request $request, Order $order)
    {
        if (!$order->isRefundRequested()) {
            return redirect()->back()->with('error', 'No refund requested for this order');
        }

        try {
            DB::beginTransaction();

            // Use existing refund logic
            $order->payment_status = 'refunded';
            $order->order_status = 'refunded';
            $order->refund_request_status = 'approved';
            $order->approved_by = auth()->id();
            $order->save();

            // Restore stock
            foreach ($order->orderDetails as $detail) {
                if ($detail->product_detail) {
                    $detail->product_detail->increaseStock($detail->quantity);
                }
            }

            DB::commit();

            return redirect()->back()
                ->with('success', 'Refund request approved. Order refunded and stock restored.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to approve refund: ' . $e->getMessage());
        }
    }

    public function rejectRefundRequest(Request $request, Order $order)
    {
        if (!$order->isRefundRequested()) {
            return redirect()->back()->with('error', 'No refund requested for this order');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        try {
            $order->update([
                'refund_request_status' => 'rejected',
                'refund_rejection_reason' => $validated['rejection_reason'],
                'approved_by' => auth()->id()
            ]);

            return redirect()->back()
                ->with('success', 'Refund request rejected.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to reject refund: ' . $e->getMessage());
        }
    }
}