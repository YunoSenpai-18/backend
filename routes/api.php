<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\UserController;

// Login route (no middleware, anyone can hit this)
Route::post('/login', [AuthController::class, 'login']);

// Protected routes (require Sanctum token)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('/checker/schedules', [ScheduleController::class, 'checkerSchedules']);
    Route::apiResource('instructors', InstructorController::class);
    Route::apiResource('schedules', ScheduleController::class);
    Route::apiResource('users', UserController::class)->only(['index', 'show']);

    Route::post('/logout', [AuthController::class, 'logout']);
});
