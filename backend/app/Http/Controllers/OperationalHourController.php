<?php

namespace App\Http\Controllers;

use App\Models\OperationalHour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OperationalHourController extends Controller
{
    public function index()
    {
        $operationalHours = OperationalHour::all();
        return view('operationalhours.index', compact('operationalHours'));
    }

    public function create()
    {
        return view('operationalhours.create');
    }

    public function service(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'day' => 'required|string|max:255',
            'open_time' => 'required|date_format:H:i',
            'close_time' => 'required|date_format:H:i|after:opening_time',
            'service_type' => 'required|string|max:255',
            'status' => 'required|in:open,closed'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        OperationalHour::create($request->all());

        return redirect()->route('operationalhours.index')->with('success', 'Operational hour created successfully.');
    }

    public function edit(OperationalHour $operationalHour)
    {
        return view('operationalhours.edit', compact('operationalHour'));
    }

    public function update(Request $request, OperationalHour $operationalHour)
    {
        $validator = Validator::make($request->all(), [
            'day' => 'required|string|max:255',
            'open_time' => 'required|date_format:H:i',
            'close_time' => 'required|date_format:H:i|after:open_time',
            // 'service_type' => 'required|string|max:255',
            'status' => 'required|in:open,closed'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $operationalHour->update($request->all());

        return redirect()->route('operationalhours.index')->with('success', 'Operational hour updated successfully.');
    }

    public function destroy(OperationalHour $operationalHour)
    {
        $operationalHour->delete();

        return redirect()->route('operationalhours.index')->with('success', 'Operational hour deleted successfully.');
    }

    public function filter(Request $request)
    {
        $serviceType = $request->query('service_type');

        $query = OperationalHour::query();
        
        if (in_array($serviceType, ['Store', 'website'])) {
            $query->where('service_type', $serviceType);
        }

        if ($request->filled('service_type')) {
            $query->where('service_type', $request->service_type);
        }
        
        $hours = $query->get()->map(function ($hour) {
            // Pastikan field null ditampilkan sebagai '-'
            return [
                'id' => $hour->id,
                'day' => $hour->day ?? '-',
                'open_time' => $hour->open_time ?? '-',
                'close_time' => $hour->close_time ?? '-',
                'status' => $hour->status ?? 'closed',
                'created_at' => $hour->created_at,
                // Tambahkan 'service_type' jika perlu di respons
            ];
        });

        return response()->json($hours);
    }
}
