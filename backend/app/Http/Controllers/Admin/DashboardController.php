<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Total Products
        $totalProducts = Product::count();
        
        // Total Stock
        $totalStock = Product::with('productDetails')->get()->sum(function($product) {
            return $product->productDetails->sum('stock');
        });
        
        // Total Users (Customers)
        $totalCustomers = User::whereHas('role', function($query) {
            $query->where('name', 'Customer');
        })->count();
        
        // Pending Orders
        $pendingOrders = Order::where('order_status', 'pending')->count();
        
        // Today's Sales (Online)
        $todayOnlineSales = Order::whereDate('created_at', today())
            ->where('payment_status', 'paid')
            ->sum('total_payment');
        
        // Today's Sales (Offline)
        $todayOfflineSales = Transaction::whereDate('created_at', today())
            ->where('transaction_status', 'completed')
            ->sum('total');
        
        // This Month Sales
        $thisMonthSales = Order::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->where('payment_status', 'paid')
            ->sum('total_payment');
        
        $thisMonthOfflineSales = Transaction::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->where('transaction_status', 'completed')
            ->sum('total');
        
        $thisMonthTotal = $thisMonthSales + $thisMonthOfflineSales;
        
        // Recent Orders
        $recentOrders = Order::with(['buyer', 'orderDetails.product'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Low Stock Products
        $lowStockProducts = Product::with('productDetails')
            ->get()
            ->filter(function($product) {
                return $product->getAvailableStock() < 10;
            })
            ->take(10);
        
        // Chart Data - Last 7 Days Sales
        $last7Days = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $onlineSales = Order::whereDate('created_at', $date)
                ->where('payment_status', 'paid')
                ->sum('total_payment');
            $offlineSales = Transaction::whereDate('created_at', $date)
                ->where('transaction_status', 'completed')
                ->sum('total');
            
            $last7Days->push([
                'date' => $date->format('d M'),
                'online' => $onlineSales,
                'offline' => $offlineSales,
                'total' => $onlineSales + $offlineSales
            ]);
        }

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalStock',
            'totalCustomers',
            'pendingOrders',
            'todayOnlineSales',
            'todayOfflineSales',
            'thisMonthTotal',
            'recentOrders',
            'lowStockProducts',
            'last7Days'
        ));
    }
}