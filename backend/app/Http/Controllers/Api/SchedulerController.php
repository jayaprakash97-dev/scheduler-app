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
    $date = $request->date;

    $availability = Availability::where('date', $date)->first();

    if (!$availability) {
        return response()->json([
            'success' => false,
            'message' => 'No availability found for selected date',
            'slots' => []
        ], 404);
    }

    $start = \Carbon\Carbon::parse($availability->start_time);
    $end = \Carbon\Carbon::parse($availability->end_time);

    $slotDuration = 30;

    $bookedSlots = Booking::where('booking_date', $date)
        ->get(['start_time', 'end_time'])
        ->toArray();

    $slots = [];

    while ($start->copy()->addMinutes($slotDuration) <= $end) {

        $slotStart = $start->copy();
        $slotEnd = $start->copy()->addMinutes($slotDuration);

        $isBooked = collect($bookedSlots)->contains(function ($booking) use ($slotStart, $slotEnd) {
            return $booking['start_time'] === $slotStart->format('H:i:s') &&
                   $booking['end_time'] === $slotEnd->format('H:i:s');
        });

        $slots[] = [
            'start' => $slotStart->format('H:i'),
            'end' => $slotEnd->format('H:i'),
            'available' => !$isBooked
        ];

        $start->addMinutes($slotDuration);
    }

    return response()->json([
        'success' => true,
        'slots' => $slots
    ]);
}

public function bookSlot(Request $request)
{
    $request->validate([
        'booking_date' => 'required|date',
        'start_time'   => 'required',
        'end_time'     => 'required',
        'name'         => 'required|string|max:255',
        'email'        => 'required|email|max:255'
    ]);

    $date = $request->booking_date;
    $startTime = $request->start_time;
    $endTime = $request->end_time;

    $availability = Availability::where('date', $date)->first();

    if (!$availability) {
        return response()->json([
            'success' => false,
            'message' => 'No availability for selected date'
        ], 404);
    }

    $alreadyBooked = Booking::where('booking_date', $date)
        ->where('start_time', $startTime)
        ->where('end_time', $endTime)
        ->exists();

    if ($alreadyBooked) {
        return response()->json([
            'success' => false,
            'message' => 'Slot already booked'
        ], 400);
    }

    Booking::create([
        'availability_id' => $availability->id,
        'booking_date' => $date,
        'start_time' => $startTime,
        'end_time' => $endTime,
        'name' => $request->name,
        'email' => $request->email
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Booking confirmed successfully'
    ], 201);
}


}
