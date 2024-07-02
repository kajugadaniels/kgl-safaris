<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingRequest;
use App\Models\Booking;
use App\Models\TourPackage;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function store(BookingRequest $request, $tourPackageSlug)
    {
        try {
            $tourPackage = TourPackage::where('slug', $tourPackageSlug)->firstOrFail();

            $booking = new Booking();
            $booking->tour_package_id = $tourPackage->id;
            $booking->name = $request->name;
            $booking->email = $request->email;
            $booking->phone_number = $request->phone_number;
            $booking->date = $request->date;
            $booking->save();

            return response()->json(['message' => 'Booking created successfully', 'booking' => $booking], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while creating the booking', 'error' => $e->getMessage()], 500);
        }
    }
}
