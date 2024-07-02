<?php

namespace App\Http\Controllers;

use App\Http\Requests\TourPackageRequest;
use App\Models\TourPackage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class TourPackageController extends Controller
{
    public function index()
    {
        try {
            $tourPackages = TourPackage::all();
            return response()->json(['tourPackages' => $tourPackages], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while fetching tour packages', 'error' => $e->getMessage()], 500);
        }
    }

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

    public function show($slug)
    {
        try {
            $tourPackage = TourPackage::where('slug', $slug)->firstOrFail();
            return response()->json(['tourPackage' => $tourPackage], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while fetching the tour package', 'error' => $e->getMessage()], 500);
        }
    }

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
