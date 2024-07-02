<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TourPackageController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::get('/users', [AuthController::class, 'getUsers'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/tour-packages', [TourPackageController::class, 'index']);
    Route::post('/tour-package', [TourPackageController::class, 'store']);
    Route::get('/tour-package/{slug}', [TourPackageController::class, 'show']);
    Route::put('/tour-package/{slug}', [TourPackageController::class, 'update']);
    Route::delete('/tour-package/{slug}', [TourPackageController::class, 'destroy']);
});