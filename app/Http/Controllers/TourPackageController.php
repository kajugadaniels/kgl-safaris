<?php

namespace App\Http\Controllers;

use App\Http\Requests\TourPackageRequest;
use App\Models\TourPackage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Tag(name="TourPackages", description="API Endpoints for Tour Packages")
 */
class TourPackageController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/tour-packages",
     *     summary="Get all tour packages",
     *     tags={"TourPackages"},
     *     @OA\Response(response=200, description="Successful retrieval of tour packages"),
     *     @OA\Response(response=500, description="An error occurred while fetching tour packages")
     * )
     */
    public function index()
    {
        try {
            $tourPackages = TourPackage::all();
            return response()->json(['tourPackages' => $tourPackages], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while fetching tour packages', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/tour-package",
     *     summary="Create a new tour package",
     *     tags={"TourPackages"},
     *     security={{ "bearer_token": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="title", type="string", example="Tour Package Title"),
     *             @OA\Property(property="number_of_people", type="integer", example=10),
     *             @OA\Property(property="price", type="number", format="float", example=100.50),
     *             @OA\Property(property="days", type="integer", example=5),
     *             @OA\Property(property="description", type="string", example="Description of the tour package"),
     *             @OA\Property(property="image", type="string", format="binary", example="base64-encoded-image-data"),
     *         )
     *     ),
     *     @OA\Response(response=201, description="Tour package created successfully"),
     *     @OA\Response(response=500, description="An error occurred while creating the tour package")
     * )
     */
    public function store(TourPackageRequest $request)
    {
        try {
            $tourPackage = new TourPackage();
            $tourPackage->title = $request->title;
            $tourPackage->slug = Str::slug($request->title);
            $tourPackage->number_of_people = $request->number_of_people;
            $tourPackage->price = $request->price;
            $tourPackage->days = $request->days;
            $tourPackage->description = $request->description;

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = Str::slug($request->title) . '-' . $request->price . '-' . now()->timestamp . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('tour-packages', $filename, 'public');
                $tourPackage->image = $path;
            }

            $tourPackage->save();

            return response()->json(['message' => 'Tour package created successfully', 'tourPackage' => $tourPackage], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while creating the tour package', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/tour-package/{slug}",
     *     summary="Get a tour package by slug",
     *     tags={"TourPackages"},
     *     @OA\Parameter(
     *         name="slug",
     *         in="path",
     *         required=true,
     *         description="Slug of the tour package",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Successful retrieval of tour package"),
     *     @OA\Response(response=500, description="An error occurred while fetching the tour package")
     * )
     */
    public function show($slug)
    {
        try {
            $tourPackage = TourPackage::where('slug', $slug)->firstOrFail();
            return response()->json(['tourPackage' => $tourPackage], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while fetching the tour package', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/tour-package/{slug}",
     *     summary="Update a tour package",
     *     tags={"TourPackages"},
     *     security={{ "bearer_token": {} }},
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
     *             @OA\Property(property="title", type="string", example="Updated Tour Package Title"),
     *             @OA\Property(property="number_of_people", type="integer", example=15),
     *             @OA\Property(property="price", type="number", format="float", example=120.75),
     *             @OA\Property(property="days", type="integer", example=6),
     *             @OA\Property(property="description", type="string", example="Updated description of the tour package"),
     *             @OA\Property(property="image", type="string", format="binary", example="base64-encoded-image-data"),
     *         )
     *     ),
     *     @OA\Response(response=200, description="Tour package updated successfully"),
     *     @OA\Response(response=500, description="An error occurred while updating the tour package")
     * )
     */
    public function update(TourPackageRequest $request, $slug)
    {
        try {
            $tourPackage = TourPackage::where('slug', $slug)->firstOrFail();
            $tourPackage->title = $request->title ?? $tourPackage->title;
            $tourPackage->slug = $request->title ? Str::slug($request->title) : $tourPackage->slug;
            $tourPackage->number_of_people = $request->number_of_people ?? $tourPackage->number_of_people;
            $tourPackage->price = $request->price ?? $tourPackage->price;
            $tourPackage->days = $request->days ?? $tourPackage->days;
            $tourPackage->description = $request->description ?? $tourPackage->description;

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = Str::slug($request->title) . '-' . $request->price . '-' . now()->timestamp . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('tour-packages', $filename, 'public');
                $tourPackage->image = $path;
            }

            $tourPackage->save();

            return response()->json(['message' => 'Tour package updated successfully', 'tourPackage' => $tourPackage], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while updating the tour package', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/tour-package/{slug}",
     *     summary="Delete a tour package",
     *     tags={"TourPackages"},
     *     security={{ "bearer_token": {} }},
     *     @OA\Parameter(
     *         name="slug",
     *         in="path",
     *         required=true,
     *         description="Slug of the tour package",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Tour package deleted successfully"),
     *     @OA\Response(response=500, description="An error occurred while deleting the tour package")
     * )
     */
    public function destroy($slug)
    {
        try {
            $tourPackage = TourPackage::where('slug', $slug)->firstOrFail();
            $tourPackage->delete();

            return response()->json(['message' => 'Tour package deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while deleting the tour package', 'error' => $e->getMessage()], 500);
        }
    }
}
