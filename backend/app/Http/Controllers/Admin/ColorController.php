<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;

// ============ ColorController ============
class ColorController extends Controller
{
    public function index()
    {
        $colors = Color::withCount('productDetails')->paginate(10);
        return view('admin.colors.index', compact('colors'));
    }

    public function create()
    {
        return view('admin.colors.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'information' => 'nullable|string|max:255'
        ]);

        Color::create($validated);
        return redirect()->route('admin.colors.index')->with('success', 'Color created successfully');
    }

    public function edit(Color $color)
    {
        return view('admin.colors.edit', compact('color'));
    }

    public function update(Request $request, Color $color)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'information' => 'nullable|string|max:255'
        ]);

        $color->update($validated);
        return redirect()->route('admin.colors.index')->with('success', 'Color updated successfully');
    }

    public function destroy(Color $color)
    {
        if ($color->productDetails()->count() > 0) {
            return redirect()->route('admin.colors.index')->with('error', 'Cannot delete color with existing product details');
        }
        $color->delete();
        return redirect()->route('admin.colors.index')->with('success', 'Color deleted successfully');
    }
}
