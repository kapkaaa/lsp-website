<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingRate;
use App\Models\OperationalHour;
use Illuminate\Http\Request;

// ============ ShippingRateController ============
class ShippingRateController extends Controller
{
    public function index()
    {
        $shippingRates = ShippingRate::paginate(10);
        return view('admin.shipping-rates.index', compact('shippingRates'));
    }

    public function create()
    {
        return view('admin.shipping-rates.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'region' => 'required|string|max:255',
            'price_per_kg' => 'required|numeric|min:0'
        ]);

        ShippingRate::create($validated);

        return redirect()->route('admin.shipping-rates.index')
            ->with('success', 'Shipping rate created successfully');
    }

    public function edit(ShippingRate $shippingRate)
    {
        return view('admin.shipping-rates.edit', compact('shippingRate'));
    }

    public function update(Request $request, ShippingRate $shippingRate)
    {
        $validated = $request->validate([
            'region' => 'required|string|max:255',
            'price_per_kg' => 'required|numeric|min:0'
        ]);

        $shippingRate->update($validated);

        return redirect()->route('admin.shipping-rates.index')
            ->with('success', 'Shipping rate updated successfully');
    }

    public function destroy(ShippingRate $shippingRate)
    {
        if ($shippingRate->orders()->count() > 0) {
            return redirect()->route('admin.shipping-rates.index')
                ->with('error', 'Cannot delete shipping rate with existing orders');
        }

        $shippingRate->delete();

        return redirect()->route('admin.shipping-rates.index')
            ->with('success', 'Shipping rate deleted successfully');
    }
}

// ============ OperationalHourController ============
class OperationalHourController extends Controller
{
    public function index()
    {
        $operationalHours = OperationalHour::orderByRaw("
            CASE day
                WHEN 'Monday' THEN 1
                WHEN 'Tuesday' THEN 2
                WHEN 'Wednesday' THEN 3
                WHEN 'Thursday' THEN 4
                WHEN 'Friday' THEN 5
                WHEN 'Saturday' THEN 6
                WHEN 'Sunday' THEN 7
            END
        ")->get();

        return view('admin.operational-hours.index', compact('operationalHours'));
    }

    public function edit(OperationalHour $operationalHour)
    {
        return view('admin.operational-hours.edit', compact('operationalHour'));
    }

    public function update(Request $request, OperationalHour $operationalHour)
    {
        $validated = $request->validate([
            'open_time' => 'required|date_format:H:i',
            'close_time' => 'required|date_format:H:i|after:open_time',
            'status' => 'required|in:open,closed'
        ]);

        $operationalHour->update([
            'open_time' => $validated['open_time'] . ':00',
            'close_time' => $validated['close_time'] . ':00',
            'status' => $validated['status']
        ]);

        return redirect()->route('admin.operational-hours.index')
            ->with('success', 'Operational hour updated successfully');
    }

    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'open_time' => 'required|date_format:H:i',
            'close_time' => 'required|date_format:H:i|after:open_time',
            'status' => 'required|in:open,closed',
            'days' => 'required|array'
        ]);

        OperationalHour::whereIn('day', $validated['days'])
            ->update([
                'open_time' => $validated['open_time'] . ':00',
                'close_time' => $validated['close_time'] . ':00',
                'status' => $validated['status']
            ]);

        return redirect()->route('admin.operational-hours.index')
            ->with('success', 'Operational hours updated successfully');
    }
}