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

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'day' => 'required|string|max:255',
            'opening_time' => 'required|date_format:H:i',
            'closing_time' => 'required|date_format:H:i|after:opening_time',
            'status' => 'required|in:open,closed'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        OperationalHour::create($request->all());

        return redirect()->route('operationalhours.index')->with('success', 'Operational hour created successfully.');
    }

    public function show(OperationalHour $operationalHour)
    {
        return view('operationalhours.show', compact('operationalHour'));
    }

    public function edit(OperationalHour $operationalHour)
    {
        return view('operationalhours.edit', compact('operationalHour'));
    }

    public function update(Request $request, OperationalHour $operationalHour)
    {
        $validator = Validator::make($request->all(), [
            'day' => 'required|string|max:255',
            'opening_time' => 'required|date_format:H:i',
            'closing_time' => 'required|date_format:H:i|after:opening_time',
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
}
