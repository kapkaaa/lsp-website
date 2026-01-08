<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\ProductPhoto;
use App\Models\Brand;
use App\Models\Type;
use App\Models\Color;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['brand', 'type', 'productDetails']);

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                ->orWhereHas('brand', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
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

        $products = $query->paginate(10);
        $brands = Brand::all();
        $types = Type::all();

        return view('admin.products.index', compact('products', 'brands', 'types'));
    }

    public function create()
    {
        $brands = Brand::all();
        $types = Type::all();
        $colors = Color::all();
        $sizes = Size::all();

        return view('admin.products.create', compact('brands', 'types', 'colors', 'sizes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'brand_id' => 'required|exists:brands,id',
            'type_id' => 'required|exists:types,id',
            'name' => 'required|string|max:255',
            'selling_price' => 'required|numeric|min:0',
            'cost_price' => 'required|numeric|min:0',
            'variants' => 'required|array',
            'variants.*.size_id' => 'required|exists:sizes,id',
            'variants.*.color_id' => 'required|exists:colors,id',
            'variants.*.stock' => 'required|integer|min:0',
            'variants.*.photos.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Create product
        $product = Product::create([
            'brand_id' => $validated['brand_id'],
            'type_id' => $validated['type_id'],
            'name' => $validated['name'],
            'selling_price' => $validated['selling_price'],
            'cost_price' => $validated['cost_price']
        ]);

        // Create product details (variants)
        foreach ($validated['variants'] as $index => $variant) {
            $barcode = $this->generateBarcode();

            $productDetail = ProductDetail::create([
                'product_id' => $product->id,
                'size_id' => $variant['size_id'],
                'color_id' => $variant['color_id'],
                'stock' => $variant['stock'],
                'status' => $variant['stock'] > 0 ? 'available' : 'out_of_stock',
                'barcode' => $barcode
            ]);

            // Upload photos
            if ($request->hasFile("variants.{$index}.photos")) {
                foreach ($request->file("variants.{$index}.photos") as $photo) {
                    $path = $photo->store('products', 'public');

                    ProductPhoto::create([
                        'product_detail_id' => $productDetail->id,
                        'photo_url' => $path
                    ]);
                }
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully');
    }

    public function show(Product $product)
    {
        $product->load(['brand', 'type', 'productDetails.size', 'productDetails.color', 'productDetails.photos']);
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $brands = Brand::all();
        $types = Type::all();
        $colors = Color::all();
        $sizes = Size::all();
        $product->load(['productDetails.size', 'productDetails.color', 'productDetails.photos']);

        return view('admin.products.edit', compact('product', 'brands', 'types', 'colors', 'sizes'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'brand_id' => 'required|exists:brands,id',
            'type_id' => 'required|exists:types,id',
            'name' => 'required|string|max:255',
            'selling_price' => 'required|numeric|min:0',
            'cost_price' => 'required|numeric|min:0',
            'variants' => 'required|array',
            'variants.*.id' => 'nullable|exists:product_details,id,product_id,' . $product->id, // validasi hanya variant milik produk ini
            'variants.*.size_id' => 'required|exists:sizes,id',
            'variants.*.color_id' => 'required|exists:colors,id',
            'variants.*.stock' => 'required|integer|min:0',
            'variants.*.photos.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Update product info
        $product->update($validated);

        // Ambil ID semua variant yang dikirim dari form
        $incomingVariantIds = collect($validated['variants'])
            ->pluck('id')
            ->filter()
            ->toArray();

        // Hapus variant lama yang TIDAK ADA di form (opsional: hanya jika belum pernah dipakai)
        $existingVariants = $product->productDetails;
        foreach ($existingVariants as $detail) {
            if (!in_array($detail->id, $incomingVariantIds)) {
                // Opsional: cegah hapus jika sudah ada order
                if ($detail->orderDetails()->exists()) {
                    // Abaikan penghapusan; atau kembalikan error
                    continue;
                }
                // Hapus foto lama
                foreach ($detail->photos as $photo) {
                    Storage::disk('public')->delete($photo->photo_url);
                    $photo->delete();
                }
                $detail->delete();
            }
        }

        // Proses setiap variant dari form
        foreach ($validated['variants'] as $index => $variantData) {
            if (isset($variantData['id'])) {
                // Update variant yang sudah ada
                $productDetail = ProductDetail::findOrFail($variantData['id']);
                $productDetail->update([
                    'stock' => $variantData['stock'],
                    'status' => $variantData['stock'] > 0 ? 'available' : 'out_of_stock'
                    // Jangan update size_id atau color_id di sini untuk keamanan
                ]);
            } else {
                // Buat variant baru
                $barcode = $this->generateBarcode();
                $productDetail = ProductDetail::create([
                    'product_id' => $product->id,
                    'size_id' => $variantData['size_id'],
                    'color_id' => $variantData['color_id'],
                    'stock' => $variantData['stock'],
                    'status' => $variantData['stock'] > 0 ? 'available' : 'out_of_stock',
                    'barcode' => $barcode
                ]);
            }

            // Upload foto baru (jika ada)
            if ($request->hasFile("variants.{$index}.photos")) {
                foreach ($request->file("variants.{$index}.photos") as $photo) {
                    $path = $photo->store('products', 'public');
                    ProductPhoto::create([
                        'product_detail_id' => $productDetail->id,
                        'photo_url' => $path
                    ]);
                }
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully');
    }

    public function destroy(Product $product)
    {
        // Check if product has orders
        if ($product->orderDetails()->count() > 0) {
            return redirect()->route('admin.products.index')
                ->with('error', 'Cannot delete product with existing orders');
        }

        // Delete photos
        foreach ($product->productDetails as $detail) {
            foreach ($detail->photos as $photo) {
                if ($photo->photo_url) {
                    Storage::disk('public')->delete($photo->photo_url);
                }
                $photo->delete();
            }
            $detail->delete();
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully');
    }

    // Helper method to generate unique barcode
    private function generateBarcode()
    {
        do {
            $barcode = mt_rand(1000000000000, 9999999999999); // 13 digits
        } while (ProductDetail::where('barcode', $barcode)->exists());

        return $barcode;
    }

    // API endpoints for POS
    public function searchByBarcode(Request $request)
    {
        $barcode = $request->barcode;

        $productDetail = ProductDetail::with(['product', 'size', 'color'])
            ->where('barcode', $barcode)
            ->first();

        if (!$productDetail) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        if (!$productDetail->isAvailable()) {
            return response()->json([
                'success' => false,
                'message' => 'Product out of stock'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $productDetail->id,
                'name' => $productDetail->getFullName(),
                'price' => $productDetail->product->selling_price,
                'stock' => $productDetail->stock,
                'barcode' => $productDetail->barcode
            ]
        ]);
    }
}
