<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Size;
use Illuminate\Http\Request;

// ============ SizeController ============
class SizeController extends Controller
{
    public function index()
    {
        $sizes = Size::withCount('productDetails')->paginate(10);
        return view('admin.sizes.index', compact('sizes'));
    }

    public function create()
    {
        return view('admin.sizes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'information' => 'nullable|string|max:255'
        ]);

        Size::create($validated);
        return redirect()->route('admin.sizes.index')->with('success', 'Size created successfully');
    }

    public function edit(Size $size)
    {
        return view('admin.sizes.edit', compact('size'));
    }

    public function update(Request $request, Size $size)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'information' => 'nullable|string|max:255'
        ]);

        $size->update($validated);
        return redirect()->route('admin.sizes.index')->with('success', 'Size updated successfully');
    }

    public function destroy(Size $size)
    {
        if ($size->productDetails()->count() > 0) {
            return redirect()->route('admin.sizes.index')->with('error', 'Cannot delete size with existing product details');
        }
        $size->delete();
        return redirect()->route('admin.sizes.index')->with('success', 'Size deleted successfully');
    }
}
