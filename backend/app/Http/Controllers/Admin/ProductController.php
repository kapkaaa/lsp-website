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
                    try {
                        // Store file on the supabase disk explicitly
                        $path = $photo->store('products/' . $productDetail->id, 'supabase');
                        
                        // Get full public URL
                        $fullUrl = \Illuminate\Support\Facades\Storage::disk('supabase')->url($path);

                        // Log the URL for debugging
                        \Log::info('File stored on Supabase in ProductController:', ['path' => $path, 'url' => $fullUrl]);

                        ProductPhoto::create([
                            'product_detail_id' => $productDetail->id,
                            'photo_url' => $fullUrl
                        ]);
                    } catch (\Exception $e) {
                        \Log::error('Failed to store photo on Supabase in ProductController:', ['error' => $e->getMessage()]);

                        throw $e; // Re-throw to prevent silent failure
                    }
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

        // Process deleted photos
        if ($request->has('delete_photo_ids')) {
            foreach ($request->delete_photo_ids as $photoId) {
                $photo = ProductPhoto::find($photoId);
                if ($photo) {
                    $path = ProductPhoto::extractPathFromUrl($photo->getRawOriginal('photo_url'));
                    if ($path) {
                        \Illuminate\Support\Facades\Storage::disk('supabase')->delete($path);
                    }
                    $photo->delete();
                }
            }
        }

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
                    $path = ProductPhoto::extractPathFromUrl($photo->getRawOriginal('photo_url'));
                    if ($path) {
                        \Illuminate\Support\Facades\Storage::disk('supabase')->delete($path);
                    }
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
                // Validasi agar tidak ada duplikasi kombinasi size dan color untuk produk ini
                $duplicateCheck = ProductDetail::where('product_id', $product->id)
                                              ->where('size_id', $variantData['size_id'])
                                              ->where('color_id', $variantData['color_id'])
                                              ->exists();

                if ($duplicateCheck) {
                    return redirect()->back()
                                     ->withErrors(['error' => 'Varian dengan ukuran dan warna ini sudah ada!'])
                                     ->withInput();
                }

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
                    try {
                        // Store file on the supabase disk explicitly
                        $path = $photo->store('products/' . $productDetail->id, 'supabase');
                        
                        // Get full public URL
                        $fullUrl = \Illuminate\Support\Facades\Storage::disk('supabase')->url($path);
                        
                        \Log::info('File stored on Supabase in ProductController update method:', ['path' => $path, 'url' => $fullUrl]);

                        ProductPhoto::create([
                            'product_detail_id' => $productDetail->id,
                            'photo_url' => $fullUrl
                        ]);
                    } catch (\Exception $e) {
                        \Log::error('Failed to store photo on Supabase in ProductController update method:', ['error' => $e->getMessage()]);

                        throw $e; // Re-throw to prevent silent failure
                    }
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
                $path = ProductPhoto::extractPathFromUrl($photo->getRawOriginal('photo_url'));
                if ($path) {
                    \Illuminate\Support\Facades\Storage::disk('supabase')->delete($path);
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

    public function liveSearch(Request $request)
    {
        $query = $request->get('q', '');

        $products = Product::with(['brand', 'type', 'productDetails.photos'])
            ->when($query, function ($builder, $search) {
                $builder->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                        ->orWhereHas('brand', fn($b) => $b->where('name', 'LIKE', "%{$search}%"))
                        ->orWhereHas('type', fn($t) => $t->where('name', 'LIKE', "%{$search}%"));
                });
            })
            ->paginate(15); // Sesuaikan jumlah per halaman

        return view('admin.products.partials.product_table', compact('products'));
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
