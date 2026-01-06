<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['buyer', 'details']);
        
        // Filter by status
        if ($request->filled('order_status')) {
            $query->where('order_status', $request->order_status);
        }
        
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        
        $orders = $query->latest()->paginate(20);
        
        return view('orders.index', compact('orders'));
    }
    
    public function show(Order $order)
    {
        $order->load(['buyer', 'approver', 'shippingRate', 'details.product', 'payment']);
        
        return view('orders.show', compact('order'));
    }
    
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'order_status' => 'required|in:pending,processing,shipped,completed,cancelled',
            'payment_status' => 'nullable|in:pending,paid,failed,refund'
        ]);
        
        $order->update($validated);
        
        if ($request->filled('approved_by')) {
            $order->update(['approved_by' => auth()->id()]);
        }
        
        return back()->with('success', 'Status order berhasil diupdate.');
    }
}
