<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\TourPackageController;
use App\Http\Middleware\CorsMiddleware;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::middleware('auth:sanctum')->get('/check-auth', [AuthController::class, 'checkAuth']);

Route::get('/users', [AuthController::class, 'getUsers'])->middleware('auth:sanctum');

Route::get('/tour-packages', [TourPackageController::class, 'index']);
Route::get('/tour-package/{slug}', [TourPackageController::class, 'show']);

Route::post('/tour-package', [TourPackageController::class, 'store']);
Route::put('/tour-package/{slug}', [TourPackageController::class, 'update']);
Route::delete('/tour-package/{slug}', [TourPackageController::class, 'destroy']);

Route::post('/tour-package/{tourPackageId}/booking', [BookingController::class, 'store']);
Route::get('/bookings', [BookingController::class, 'getBookings'])->middleware('auth:sanctum');


Route::middleware([CorsMiddleware::class])->group(function () {
    Route::get('/example', [ExampleController::class, 'method']);
    // other API routes
});
