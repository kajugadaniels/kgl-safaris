<?php

namespace App\Http\Controllers;

use App\Models\TourPackage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\TourPackageRequest;
use Illuminate\Support\Facades\Validator;

class TourPackageController extends Controller
{
    public function index()
    {
        $tourPackages = TourPackage::orderBy('id', 'desc')->get();

        return response()->json($tourPackages);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'number_of_people' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'days' => 'required|integer|min:1',
            'description' => 'required|string',
        ]);

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

                // Save in storage/app/public/tour-packages
                $path = $image->storeAs('public/tour-packages', $filename);

                // Save in public/storage/tour-packages
                $image->move(public_path('storage/tour-packages'), $filename);

                // Save the path to the image in the database
                $tourPackage->image = 'tour-packages/' . $filename;
            }

            $tourPackage->save();

            return response()->json(['message' => 'Tour package created successfully', 'tourPackage' => $tourPackage], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while creating the tour package', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'number_of_people' => 'sometimes|required|integer|min:1',
            'price' => 'sometimes|required|numeric|min:0',
            'days' => 'sometimes|required|integer|min:1',
            'description' => 'sometimes|required|string',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation Error', 'errors' => $validator->errors()], 400);
        }

        $tourPackage = TourPackage::find($id);

        if (is_null($tourPackage)) {
            return response()->json(['message' => 'Tour Package not found'], 404);
        }

        try {
            // Update tour package attributes
            $tourPackage->title = $request->input('title', $tourPackage->title);
            $tourPackage->slug = Str::slug($request->input('title', $tourPackage->title));
            $tourPackage->number_of_people = $request->input('number_of_people', $tourPackage->number_of_people);
            $tourPackage->price = $request->input('price', $tourPackage->price);
            $tourPackage->days = $request->input('days', $tourPackage->days);
            $tourPackage->description = $request->input('description', $tourPackage->description);

            // Handle image upload if provided
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = Str::slug($tourPackage->title) . '-' . $tourPackage->price . '-' . now()->timestamp . '.' . $image->getClientOriginalExtension();

                // Save in storage/app/public/tour-packages
                $path = $image->storeAs('public/tour-packages', $filename);

                // Save in public/storage/tour-packages
                $image->move(public_path('storage/tour-packages'), $filename);

                // Delete old image if exists
                if ($tourPackage->image) {
                    $oldImagePath = public_path('storage/' . $tourPackage->image);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                    Storage::delete('public/' . $tourPackage->image);
                }

                // Update image path in the database
                $tourPackage->image = 'tour-packages/' . $filename;
            }

            // Save tour package changes
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
