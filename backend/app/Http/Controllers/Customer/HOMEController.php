<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\Brand;
use App\Models\Type;
use App\Models\OperationalHour;
use Illuminate\Http\Request;

// ============ HomeController ============
class HomeController extends Controller
{
    public function index()
    {
        // Featured products
        $featuredProducts = Product::with(['brand', 'type', 'productDetails.photos'])
            ->whereHas('productDetails', function($query) {
                $query->where('status', 'available')
                      ->where('stock', '>', 0);
            })
            ->latest()
            ->limit(8)
            ->get();

        // Operational hours
        $operationalStatus = OperationalHour::isOperational('online');
        $operationalMessage = OperationalHour::getOperationalMessage('online');

        // Brands
        $brands = Brand::withCount('products')->limit(6)->get();

        return view('customer.home', compact(
            'featuredProducts',
            'operationalStatus',
            'operationalMessage',
            'brands'
        ));
    }
}

// ============ ProductController ============
class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['brand', 'type', 'productDetails.photos'])
            ->whereHas('productDetails', function($q) {
                $q->where('status', 'available')
                  ->where('stock', '>', 0);
            });

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('brand', function($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by brand
        if ($request->has('brand_id') && $request->brand_id) {
            $query->where('brand_id', $request->brand_id);
        }

        // Filter by type
        if ($request->has('type_id') && $request->type_id) {
            $query->where('type_id', $request->type_id);
        }

        // Filter by price range
        if ($request->has('min_price') && $request->min_price) {
            $query->where('selling_price', '>=', $request->min_price);
        }
        if ($request->has('max_price') && $request->max_price) {
            $query->where('selling_price', '<=', $request->max_price);
        }

        // Sorting
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('selling_price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('selling_price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            default:
                $query->latest();
        }

        $products = $query->paginate(12);
        $brands = Brand::all();
        $types = Type::all();

        return view('customer.products.index', compact('products', 'brands', 'types'));
    }

    public function show($id)
    {
        $product = Product::with([
            'brand',
            'type',
            'productDetails.size',
            'productDetails.color',
            'productDetails.photos'
        ])
        ->findOrFail($id);

        // Get available variants
        $availableVariants = $product->productDetails()
            ->where('status', 'available')
            ->where('stock', '>', 0)
            ->with(['size', 'color', 'photos'])
            ->get();

        // Related products
        $relatedProducts = Product::with(['brand', 'type', 'productDetails.photos'])
            ->where('type_id', $product->type_id)
            ->where('id', '!=', $product->id)
            ->whereHas('productDetails', function($q) {
                $q->where('status', 'available')
                  ->where('stock', '>', 0);
            })
            ->limit(4)
            ->get();

        return view('customer.products.show', compact('product', 'availableVariants', 'relatedProducts'));
    }

    public function getVariantDetails(Request $request)
    {
        $productDetail = ProductDetail::with(['size', 'color', 'photos', 'product'])
            ->findOrFail($request->variant_id);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $productDetail->id,
                'size' => $productDetail->size->name,
                'color' => $productDetail->color->name,
                'stock' => $productDetail->stock,
                'price' => $productDetail->product->selling_price,
                'photos' => $productDetail->photos->map(function($photo) {
                    return $photo->photo_url;
                })
            ]
        ]);
    }
}