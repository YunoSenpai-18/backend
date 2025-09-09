<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InstructorController;

// Login route (no middleware, anyone can hit this)
Route::post('/login', [AuthController::class, 'login']);

// Protected routes (require Sanctum token)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::apiResource('instructors', InstructorController::class);

    Route::post('/logout', [AuthController::class, 'logout']);
});
