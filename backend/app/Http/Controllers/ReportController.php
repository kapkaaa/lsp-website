<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function sales(Request $request)
    {
        $dateFrom = $request->input('date_from', Carbon::now()->startOfMonth());
        $dateTo = $request->input('date_to', Carbon::now());
        
        $transactions = Transaction::with(['user', 'details.productDetail'])
                                  ->whereBetween('created_at', [$dateFrom, $dateTo])
                                  ->where('transaction_status', 'completed')
                                  ->get();
        
        $totalRevenue = $transactions->sum('total');
        $totalTransactions = $transactions->count();
        $totalProfit = $transactions->sum(function($t) {
            return $t->profit;
        });
        
        return view('reports.sales', compact(
            'transactions', 'totalRevenue', 'totalTransactions', 
            'totalProfit', 'dateFrom', 'dateTo'
        ));
    }
    
    public function profit(Request $request)
    {
        $dateFrom = $request->input('date_from', Carbon::now()->startOfMonth());
        $dateTo = $request->input('date_to', Carbon::now());
        
        $details = \App\Models\TransactionDetail::with(['productDetail.product', 'transaction'])
                                                ->whereHas('transaction', function($q) use ($dateFrom, $dateTo) {
                                                    $q->whereBetween('created_at', [$dateFrom, $dateTo])
                                                      ->where('transaction_status', 'completed');
                                                })
                                                ->get();
        
        $profitByProduct = $details->groupBy('productDetail.product.id')->map(function($items) {
            $product = $items->first()->productDetail->product;
            $totalRevenue = $items->sum('subtotal');
            $totalCost = $items->sum(function($item) {
                return $item->quantity * $item->productDetail->product->cost_price;
            });
            $profit = $totalRevenue - $totalCost;
            
            return [
                'product' => $product,
                'quantity_sold' => $items->sum('quantity'),
                'revenue' => $totalRevenue,
                'cost' => $totalCost,
                'profit' => $profit,
                'margin' => $totalRevenue > 0 ? ($profit / $totalRevenue * 100) : 0
            ];
        });
        
        return view('reports.profit', compact('profitByProduct', 'dateFrom', 'dateTo'));
    }
}