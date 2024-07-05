<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\TourPackage;
use Illuminate\Http\Request;
use App\Http\Requests\BookingRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * @OA\Tag(name="Bookings", description="API Endpoints for Bookings")
 */
class BookingController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/tour-package/{slug}/booking",
     *     summary="Create a booking for a tour package",
     *     tags={"Bookings"},
     *     @OA\Parameter(
     *         name="slug",
     *         in="path",
     *         required=true,
     *         description="Slug of the tour package",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
     *             @OA\Property(property="phone_number", type="string", example="+1234567890"),
     *             @OA\Property(property="date", type="string", format="date", example="2024-07-04"),
     *         )
     *     ),
     *     @OA\Response(response=201, description="Booking created successfully"),
     *     @OA\Response(response=500, description="An error occurred while creating the booking")
     * )
     */
    public function store(BookingRequest $request, $tourPackageId)
    {
        try {
            $tourPackage = TourPackage::findOrFail($tourPackageId);

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

    /**
     * @OA\Get(
     *     path="/api/bookings",
     *     summary="Get all bookings",
     *     tags={"Bookings"},
     *     security={{ "sanctum": {} }},
     *     @OA\Response(response=200, description="Successful retrieval of bookings"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=500, description="Unexpected error occurred")
     * )
    */
    public function getBookings()
    {
        try {
            if (!Auth::check()) {
                return response()->json(['message' => 'Login to access this page.'], 401);
            }

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
