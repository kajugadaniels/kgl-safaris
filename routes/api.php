<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/test', function () {
    return 'API is working';
});

Route::get('/users', [AuthController::class, 'getUsers']);

Route::post('/login', [AuthController::class, 'login']);
