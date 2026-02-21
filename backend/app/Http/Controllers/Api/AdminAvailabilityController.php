<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Availability;
use Illuminate\Http\Request;

class AdminAvailabilityController extends Controller
{
    public function index()
    {
        $availabilities = Availability::orderBy('date')->orderBy('start_time')->get();
        return response()->json($availabilities);
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);
        $availability = Availability::create($request->only(['date', 'start_time', 'end_time']));

        return response()->json([
            'message' => 'Availability added successfully',
            'availability' => $availability
        ]);
    }

    public function update(Request $request, $id)
    {
        $availability = Availability::findOrFail($id);

        $request->validate([
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        $availability->update($request->only(['date', 'start_time', 'end_time']));

        return response()->json([
            'message' => 'Availability updated successfully',
            'availability' => $availability
        ]);
    }

    public function destroy($id)
    {
        $availability = Availability::findOrFail($id);
        $availability->delete();

        return response()->json(['message' => 'Availability deleted successfully']);
    }
}