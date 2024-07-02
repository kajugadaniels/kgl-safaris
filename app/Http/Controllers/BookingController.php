<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\TourPackage;
use Illuminate\Http\Request;
use App\Http\Requests\BookingRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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

    public function getBookings()
    {
        try {
            // Check if the user is authenticated via Sanctum
            if (!Auth::check()) {
                return response()->json(['message' => 'Login to access this page.'], 401);
            }
    
            // If authenticated, proceed to retrieve bookings
            $bookings = Booking::all();
    
            return response()->json(['bookings' => $bookings], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Resource not found.'], 404);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Database query error.'], 400);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Unexpected error occurred.'], 500);
        }
    }
}
