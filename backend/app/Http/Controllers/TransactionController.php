<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['user', 'details.productDetail']);
        
        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Filter by cashier
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }
        
        $transactions = $query->latest()->paginate(20);
        
        // Get cashiers for filter
        $cashiers = \App\Models\User::whereHas('role', function($q) {
            $q->where('name', 'cashier');
        })->get();
        
        return view('transactions.index', compact('transactions', 'cashiers'));
    }
    
    public function show(Transaction $transaction)
    {
        $transaction->load(['user', 'details.productDetail.product', 
                           'details.productDetail.color', 'details.productDetail.size']);
        
        return view('transactions.show', compact('transaction'));
    }
}