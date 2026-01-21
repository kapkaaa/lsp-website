<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Report index page
     */
    public function index()
    {
        return view('admin.reports.index');
    }

    /**
     * Sales report
     */
    public function sales(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));

        // Online Sales
        $onlineQuery = Order::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where('payment_status', 'paid')
            ->where('order_status', '!=', 'refunded');
        
        $onlineSales = [
            'count' => $onlineQuery->count(),
            'total' => $onlineQuery->sum('total_payment'),
            'orders' => $onlineQuery->with(['buyer', 'orderDetails.product'])->get()
        ];

        // Offline Sales
        $offlineQuery = Transaction::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where('transaction_status', 'completed');
        
        $offlineSales = [
            'count' => $offlineQuery->count(),
            'total' => $offlineQuery->sum('total'),
            'transactions' => $offlineQuery->with(['user', 'transactionDetails.productDetail.product'])->get()
        ];

        // Combined totals
        $totalSales = $onlineSales['total'] + $offlineSales['total'];
        $totalTransactions = $onlineSales['count'] + $offlineSales['count'];

        // Daily breakdown
        $dailyBreakdown = [];
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        for ($date = $start->copy(); $date <= $end; $date->addDay()) {
            $dateStr = $date->format('Y-m-d');
            
            $dailyOnline = Order::whereDate('created_at', $dateStr)
                ->where('payment_status', 'paid')
                ->where('order_status', '!=', 'refunded')
                ->sum('total_payment');
            
            $dailyOffline = Transaction::whereDate('created_at', $dateStr)
                ->where('transaction_status', 'completed')
                ->sum('total');

            $dailyBreakdown[] = [
                'date' => $date->format('d M'),
                'online' => $dailyOnline,
                'offline' => $dailyOffline,
                'total' => $dailyOnline + $dailyOffline
            ];
        }

        return view('admin.reports.sales', compact(
            'onlineSales',
            'offlineSales',
            'totalSales',
            'totalTransactions',
            'dailyBreakdown',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Stock report
     */
    public function stock()
    {
        $products = Product::with(['brand', 'type', 'productDetails.size', 'productDetails.color'])
            ->get()
            ->map(function($product) {
                $totalStock = $product->productDetails->sum('stock');
                $availableStock = $product->productDetails->where('status', 'available')->sum('stock');
                
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'brand' => $product->brand->name,
                    'type' => $product->type->name,
                    'total_stock' => $totalStock,
                    'available_stock' => $availableStock,
                    'selling_price' => $product->selling_price,
                    'cost_price' => $product->cost_price,
                    'stock_value' => $totalStock * $product->cost_price,
                    'variants' => $product->productDetails,
                    'status' => $totalStock == 0 ? 'Out of Stock' : ($totalStock < 10 ? 'Low Stock' : 'In Stock')
                ];
            });

        // Summary
        $totalProducts = $products->count();
        $totalStock = $products->sum('total_stock');
        $totalValue = $products->sum('stock_value');
        $lowStockProducts = $products->where('total_stock', '<', 10)->where('total_stock', '>', 0)->count();
        $outOfStockProducts = $products->where('total_stock', 0)->count();

        return view('admin.reports.stock', compact(
            'products',
            'totalProducts',
            'totalStock',
            'totalValue',
            'lowStockProducts',
            'outOfStockProducts'
        ));
    }

    /**
     * Profit report
     */
    public function profit(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));

        // Online profit
        $onlineOrders = Order::with('orderDetails.product')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where('payment_status', 'paid')
            ->where('order_status', '!=', 'refunded')
            ->get();

        $onlineRevenue = $onlineOrders->sum('subtotal'); // Only product revenue, excluding shipping
        $onlineCost = $onlineOrders->sum(function($order) {
            return $order->orderDetails->sum(function($detail) {
                return $detail->product->cost_price * $detail->quantity;
            });
        });
        $onlineProfit = $onlineRevenue - $onlineCost;

        // Offline profit
        $offlineTransactions = Transaction::with('transactionDetails.productDetail.product')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where('transaction_status', 'completed')
            ->get();

        $offlineRevenue = $offlineTransactions->sum('total');
        $offlineCost = $offlineTransactions->sum(function($transaction) {
            return $transaction->transactionDetails->sum(function($detail) {
                return $detail->productDetail->product->cost_price * $detail->quantity;
            });
        });
        $offlineProfit = $offlineRevenue - $offlineCost;

        // Combined
        $totalRevenue = $onlineRevenue + $offlineRevenue;
        $totalCost = $onlineCost + $offlineCost;
        $totalProfit = $onlineProfit + $offlineProfit;
        $profitMargin = $totalRevenue > 0 ? ($totalProfit / $totalRevenue) * 100 : 0;

        return view('admin.reports.profit', compact(
            'onlineRevenue',
            'onlineCost',
            'onlineProfit',
            'offlineRevenue',
            'offlineCost',
            'offlineProfit',
            'totalRevenue',
            'totalCost',
            'totalProfit',
            'profitMargin',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Export report to PDF
     */
    public function exportPDF(Request $request)
    {
        $type = $request->get('type', 'sales');
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));

        $data = [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'generatedAt' => Carbon::now()->format('d M Y H:i'),
            'type' => $type
        ];

        if ($type === 'sales') {
            // Online Sales
            $data['onlineOrders'] = Order::with('orderDetails.product')
                ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                ->where('payment_status', 'paid')
                ->where('order_status', '!=', 'refunded')
                ->get();
            
            // Offline Sales
            $data['offlineTransactions'] = Transaction::with('transactionDetails.productDetail.product')
                ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                ->where('transaction_status', 'completed')
                ->get();

            // Totals
            $data['onlineTotal'] = $data['onlineOrders']->sum('total_payment');
            $data['offlineTotal'] = $data['offlineTransactions']->sum('total');
            $data['grandTotal'] = $data['onlineTotal'] + $data['offlineTotal'];

            $pdf = Pdf::loadView('admin.reports.pdf.sales', $data);
            return $pdf->download('sales-report-' . $startDate . '-to-' . $endDate . '.pdf');
        } 
        elseif ($type === 'stock') {
            $data['products'] = Product::with(['brand', 'type', 'productDetails'])
                ->get()
                ->map(function($product) {
                    return [
                        'name' => $product->name,
                        'brand' => $product->brand->name,
                        'type' => $product->type->name,
                        'total_stock' => $product->productDetails->sum('stock'),
                        'cost_price' => $product->cost_price,
                        'selling_price' => $product->selling_price
                    ];
                });

            $data['totalStock'] = $data['products']->sum('total_stock');
            $data['totalValue'] = $data['products']->sum(function($p) {
                return $p['total_stock'] * $p['cost_price'];
            });

            $pdf = Pdf::loadView('admin.reports.pdf.stock', $data);
            return $pdf->download('stock-report-' . Carbon::now()->format('Y-m-d') . '.pdf');
        }
        elseif ($type === 'profit') {
            // Calculate profit data
            $onlineOrders = Order::with('orderDetails.product')
                ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                ->where('payment_status', 'paid')
                ->where('order_status', '!=', 'refunded')
                ->get();

            $data['onlineRevenue'] = $onlineOrders->sum('subtotal');
            $data['onlineCost'] = $onlineOrders->sum(function($order) {
                return $order->orderDetails->sum(function($detail) {
                    return $detail->product->cost_price * $detail->quantity;
                });
            });
            $data['onlineProfit'] = $data['onlineRevenue'] - $data['onlineCost'];

            $offlineTransactions = Transaction::with('transactionDetails.productDetail.product')
                ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                ->where('transaction_status', 'completed')
                ->get();

            $data['offlineRevenue'] = $offlineTransactions->sum('total');
            $data['offlineCost'] = $offlineTransactions->sum(function($transaction) {
                return $transaction->transactionDetails->sum(function($detail) {
                    return $detail->productDetail->product->cost_price * $detail->quantity;
                });
            });
            $data['offlineProfit'] = $data['offlineRevenue'] - $data['offlineCost'];

            $data['totalRevenue'] = $data['onlineRevenue'] + $data['offlineRevenue'];
            $data['totalCost'] = $data['onlineCost'] + $data['offlineCost'];
            $data['totalProfit'] = $data['onlineProfit'] + $data['offlineProfit'];
            $data['profitMargin'] = $data['totalRevenue'] > 0 ? ($data['totalProfit'] / $data['totalRevenue']) * 100 : 0;

            $pdf = Pdf::loadView('admin.reports.pdf.profit', $data);
            return $pdf->download('profit-report-' . $startDate . '-to-' . $endDate . '.pdf');
        }

        return redirect()->back()->with('error', 'Invalid report type');
    }
}