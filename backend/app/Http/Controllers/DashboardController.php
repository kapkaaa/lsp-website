<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Brand;
use App\Models\ProductDetail;
use App\Models\Type;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get sales data (for demonstration, we'll simulate data)
        $salesData = collect([
            ['month' => 'Jan', 'sales' => 120],
            ['month' => 'Feb', 'sales' => 190],
            ['month' => 'Mar', 'sales' => 150],
            ['month' => 'Apr', 'sales' => 200],
            ['month' => 'May', 'sales' => 180],
            ['month' => 'Jun', 'sales' => 220],
        ]);

        // $salesData = DB::table('sales')
        //     ->select(DB::raw("DATE_FORMAT(sale_date, '%Y-%m') as month"), DB::raw('SUM(quantity) as sales'))
        //     ->groupBy('month')
        //     ->get();

        // Get best selling products (for demonstration, we'll simulate data)
        $bestSellingProducts = [
            ['name' => 'T-Shirt Cool', 'sales' => 85],
            ['name' => 'Hoodie Premium', 'sales' => 72],
            ['name' => 'Tank Top', 'sales' => 65],
            ['name' => 'Polo-Shirt', 'sales' => 45],
            ['name' => 'Jacket', 'sales' => 38],
        ];

        // Get products with low stock
        $lowStockProducts = ProductDetail::where('stock', '<', 10)->where('stock', '>', 0)->get();

        // Get out of stock products
        $outOfStockProducts = ProductDetail::where('stock', 0)->get();

        // Get counts for dashboard
        $totalProducts = Product::count();
        $totalBrands = Brand::count();
        $totalTypes = Type::count();

        return view('dashboard', compact(
            'salesData',
            'bestSellingProducts',
            'lowStockProducts',
            'outOfStockProducts',
            'totalProducts',
            'totalBrands',
            'totalTypes'
        ));
    }
}
