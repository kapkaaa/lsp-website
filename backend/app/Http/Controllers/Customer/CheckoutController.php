<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\ProductDetail;
use App\Models\ShippingRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return redirect()->route('customer.cart.index')
                ->with('error', 'Your cart is empty');
        }

        $cartItems = [];
        $subtotal = 0;
        $totalItems = 0;

        foreach ($cart as $item) {
            $productDetail = ProductDetail::with(['product', 'size', 'color'])
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
                $totalItems += $item['quantity'];
            }
        }

        // Get shipping rates
        $shippingRates = ShippingRate::all();

        return view('customer.checkout.index', compact('cartItems', 'subtotal', 'totalItems', 'shippingRates'));
    }

    public function calculateShipping(Request $request)
    {
        $validated = $request->validate([
            'shipping_rate_id' => 'required|exists:shipping_rates,id',
            'total_items' => 'required|integer|min:1'
        ]);

        $shippingRate = ShippingRate::findOrFail($validated['shipping_rate_id']);
        $shippingCost = $shippingRate->calculateShippingCost($validated['total_items']);

        return response()->json([
            'success' => true,
            'shipping_cost' => $shippingCost,
            'region' => $shippingRate->region
        ]);
    }
}