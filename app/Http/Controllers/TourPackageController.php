<?php

namespace App\Http\Controllers;

use App\Models\TourPackage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class TourPackageController extends Controller
{
    public function index()
    {
        $tourPackages = TourPackage::all();
        return response()->json(['tourPackages' => $tourPackages], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'image' => 'required|string',
            'number_of_people' => 'required|integer',
            'price' => 'required|numeric',
            'days' => 'required|integer',
            'description' => 'required|string',
        ]);

        $tourPackage = new TourPackage();
        $tourPackage->title = $request->title;
        $tourPackage->slug = Str::slug($request->title);
        $tourPackage->image = $request->image;
        $tourPackage->number_of_people = $request->number_of_people;
        $tourPackage->price = $request->price;
        $tourPackage->days = $request->days;
        $tourPackage->description = $request->description;
        $tourPackage->save();

        return response()->json(['message' => 'Tour package created successfully', 'tourPackage' => $tourPackage], 201);
    }

    public function show($slug)
    {
        $tourPackage = TourPackage::where('slug', $slug)->firstOrFail();
        return response()->json(['tourPackage' => $tourPackage], 200);
    }

    public function update(Request $request, $slug)
    {
        $request->validate([
            'title' => 'string',
            'image' => 'string',
            'number_of_people' => 'integer',
            'price' => 'integer',
            'days' => 'integer',
            'description' => 'string',
        ]);

        $tourPackage = TourPackage::where('slug', $slug)->firstOrFail();
        $tourPackage->title = $request->title ?? $tourPackage->title;
        $tourPackage->slug = Str::slug($request->title) ?? $tourPackage->slug;
        $tourPackage->image = $request->image ?? $tourPackage->image;
        $tourPackage->number_of_people = $request->number_of_people ?? $tourPackage->number_of_people;
        $tourPackage->price = $request->price ?? $tourPackage->price;
        $tourPackage->days = $request->days ?? $tourPackage->days;
        $tourPackage->description = $request->description ?? $tourPackage->description;
        $tourPackage->save();

        return response()->json(['message' => 'Tour package updated successfully', 'tourPackage' => $tourPackage], 200);
    }

    public function destroy($slug)
    {
        $tourPackage = TourPackage::where('slug', $slug)->firstOrFail();
        $tourPackage->delete();

        return response()->json(['message' => 'Tour package deleted successfully'], 200);
    }
}
