<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Availability;
use App\Models\Booking;
use Carbon\Carbon;

class SchedulerController extends Controller
{
    public function getSlots(Request $request)
    {
        $request->validate([
            'date' => 'required|date'
        ]);

        $date = $request->date;

        $availability = Availability::where('date', $date)->first();

        if (!$availability) {
            return response()->json(['slots' => []]);
        }

        $start = Carbon::parse($availability->start_time);
        $end = Carbon::parse($availability->end_time);

        $slots = [];

        while ($start < $end) {
            $slots[] = $start->format('H:i');
            $start->addHour();
        }

        $booked = Booking::where('booking_date', $date)
            ->pluck('booking_time')
            ->toArray();

        $availableSlots = array_values(array_diff($slots, $booked));

        return response()->json([
            'slots' => $availableSlots
        ]);
    }

    public function bookSlot(Request $request)
    {
        $request->validate([
            'booking_date' => 'required|date',
            'booking_time' => 'required',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255'
        ]);

        $date = $request->booking_date;
        $time = $request->booking_time;

        $availability = Availability::where('date', $date)->first();

        if (!$availability) {
            return response()->json([
                'message' => 'No availability for selected date'
            ], 400);
        }

        $start = \Carbon\Carbon::parse($availability->start_time);
        $end = \Carbon\Carbon::parse($availability->end_time);
        $selectedTime = \Carbon\Carbon::parse($time);

        if ($selectedTime->lt($start) || $selectedTime->gte($end)) {
            return response()->json([
                'message' => 'Selected time is outside availability'
            ], 400);
        }

        $alreadyBooked = Booking::where('booking_date', $date)
            ->where('booking_time', $time)
            ->exists();

        if ($alreadyBooked) {
            return response()->json([
                'message' => 'Slot already booked'
            ], 400);
        }

        Booking::create([
            'booking_date' => $date,
            'booking_time' => $time,
            'name' => $request->name,
            'email' => $request->email
        ]);

        return response()->json([
            'message' => 'Booking confirmed successfully'
        ], 201);
    }
}
