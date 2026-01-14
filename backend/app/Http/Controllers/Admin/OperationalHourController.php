<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OperationalHour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class OperationalHourController extends Controller
{
    public function index()
    {
        $serviceType = request()->query('service_type');
        $query = OperationalHour::query();
        

        if ($serviceType) {
            $query->where('service_type', $serviceType);
        }

        $operationalHours = $query->get();
        $serviceTypes = OperationalHour::distinct('service_type')->pluck('service_type');

        return view('admin.operational-hours.index', compact('operationalHours', 'serviceTypes'));
    }

    public function create()
    {
        return view('admin.operational-hours.create');
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

        return redirect()->route('admin.operational-hours.index')->with('success', 'Operational hour created successfully.');
    }

    public function edit(OperationalHour $operationalHour)
    {
        return view('admin.operational-hours.edit', compact('operationalHour'));
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

        return redirect()->route('admin.operational-hours.index')->with('success', 'Operational hour updated successfully.');
    }

    public function destroy(OperationalHour $operationalHour)
    {
        $operationalHour->delete();

        return redirect()->route('admin.operational-hours.index')->with('success', 'Operational hour deleted successfully.');
    }

    public function bulkUpdate(Request $request)
    {
        // DB::listen(function ($query) {
        //     dd($query->sql, $query->bindings);
        // });

        $request->validate([
            'days' => 'required|array|min:1',
            'days.*' => 'string|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'open_time' => 'required|date_format:H:i',
            'close_time' => 'required|date_format:H:i|after:open_time',
            'status' => 'required|in:open,closed',
            'service_type' => 'required|string|in:Store,website'
        ]);

        $updatedCount = 0;

        foreach ($request->days as $day) {
            $affected = OperationalHour::where('day', $day)
                ->where('service_type', $request->service_type)
                ->update([
                    'open_time' => $request->open_time,
                    'close_time' => $request->close_time,
                    'status' => $request->status,
                    'updated_at' => now()
                ]);
            $updatedCount += $affected;
        }

        return redirect()->route('admin.operational-hours.index')
            ->with('success', "{$updatedCount} operational hours updated successfully.");
    }

    public function filter(Request $request)
    {
        $serviceType = $request->query('service_type');

        $query = OperationalHour::query();

        if ($serviceType) {
            $query->where('service_type', $serviceType);
        }

        $hours = $query->get()->map(function ($hour) {
            return [
                'id' => $hour->id,
                'day' => $hour->day ?? '-',
                'open_time' => $hour->open_time ?? '-',
                'close_time' => $hour->close_time ?? '-',
                'status' => $hour->status ?? 'closed',
                'service_type' => $hour->service_type ?? '-',
                'created_at' => $hour->created_at,
            ];
        });

        return response()->json($hours);
    }
}
