<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ProductDetail;
use App\Models\ShippingRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
    public function process(Request $request)
    {
        $validated = $request->validate([
            'shipping_rate_id' => 'required|exists:shipping_rates,id',
            'destination_city' => 'required|string|max:255',
            'payment_method' => 'required|in:transfer,midtrans'
        ]);

        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return redirect()->route('customer.cart.index')
                ->with('error', 'Your cart is empty');
        }

        try {
            DB::beginTransaction();

            // Calculate totals
            $subtotal = 0;
            $totalItems = 0;
            $orderItems = [];

            foreach ($cart as $item) {
                $productDetail = ProductDetail::with('product')
                    ->findOrFail($item['product_detail_id']);

                if (!$productDetail->isAvailable()) {
                    throw new \Exception("Product {$productDetail->getFullName()} is not available");
                }

                if ($productDetail->stock < $item['quantity']) {
                    throw new \Exception("Insufficient stock for {$productDetail->getFullName()}");
                }

                $itemTotal = $productDetail->product->selling_price * $item['quantity'];
                $subtotal += $itemTotal;
                $totalItems += $item['quantity'];

                $orderItems[] = [
                    'product_id' => $productDetail->product->id,
                    'product_detail_id' => $productDetail->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $productDetail->product->selling_price,
                    'total' => $itemTotal
                ];

                // Decrease stock
                $productDetail->decreaseStock($item['quantity']);
            }

            // Calculate shipping
            $shippingRate = ShippingRate::findOrFail($validated['shipping_rate_id']);
            $weight = ceil($totalItems / 3); // 3 items per kg
            $shippingCost = $weight * $shippingRate->price_per_kg;
            $totalPayment = $subtotal + $shippingCost;

            // Create order
            $order = Order::create([
                'buyer_id' => auth()->id(),
                'shipping_rate_id' => $validated['shipping_rate_id'],
                'order_code' => Order::generateOrderCode(),
                'subtotal' => $subtotal,
                'weight' => $weight,
                'shipping_cost' => $shippingCost,
                'total_payment' => $totalPayment,
                'destination_city' => $validated['destination_city'],
                'payment_status' => 'pending',
                'order_status' => 'pending',
                'payment_method' => $validated['payment_method']
            ]);

            // Create order details
            foreach ($orderItems as $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total' => $item['total']
                ]);
            }

            // Clear cart
            Session::forget('cart');

            DB::commit();

            // Redirect based on payment method
            if ($validated['payment_method'] === 'midtrans') {
                return redirect()->route('customer.payment.midtrans', $order->id);
            } else {
                return redirect()->route('customer.orders.show', $order->id)
                    ->with('success', 'Order created successfully. Please complete payment.');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to process order: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function index()
    {
        $orders = Order::with(['orderDetails.product', 'shippingRate'])
            ->where('buyer_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('customer.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with([
            'orderDetails.product.brand',
            'orderDetails.product.type',
            'shippingRate',
            'approver',
            'payment'
        ])
        ->where('buyer_id', auth()->id())
        ->findOrFail($id);

        return view('customer.orders.show', compact('order'));
    }

    public function uploadPayment(Request $request, $id)
    {
        $order = Order::where('buyer_id', auth()->id())
            ->findOrFail($id);

        if (!$order->canUploadPayment()) {
            return redirect()->back()
                ->with('error', 'Cannot upload payment for this order');
        }

        $validated = $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Delete old payment proof if exists
        if ($order->payment_proof) {
            \Storage::disk('public')->delete($order->payment_proof);
        }

        // Upload new payment proof
        $path = $request->file('payment_proof')->store('payments', 'public');

        $order->payment_proof = $path;
        $order->save();

        return redirect()->back()
            ->with('success', 'Payment proof uploaded successfully. Please wait for verification.');
    }

    public function cancel($id)
    {
        $order = Order::where('buyer_id', auth()->id())
            ->findOrFail($id);

        if (!$order->isPending()) {
            return redirect()->back()
                ->with('error', 'Cannot cancel this order');
        }

        try {
            DB::beginTransaction();

            // Restore stock
            foreach ($order->orderDetails as $detail) {
                $productDetail = $detail->product->productDetails()->first();
                if ($productDetail) {
                    $productDetail->increaseStock($detail->quantity);
                }
            }

            $order->order_status = 'cancelled';
            $order->payment_status = 'cancelled';
            $order->save();

            DB::commit();

            return redirect()->back()
                ->with('success', 'Order cancelled successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to cancel order: ' . $e->getMessage());
        }
    }
}