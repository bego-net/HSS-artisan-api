<?php

use App\Http\Controllers\Api\AdminAuthController;
use App\Http\Controllers\Api\ServiceController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/services', [ServiceController::class, 'index']);
Route::get('/services/{id}', [ServiceController::class, 'show']);

// Admin auth — no 'web' middleware needed; Sanctum token auth is stateless
Route::post('/admin/login', [AdminAuthController::class, 'login']);
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->middleware(['auth:sanctum', 'admin']);

// Protected routes
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::post('/services', [ServiceController::class, 'store']);
    Route::put('/services/{id}', [ServiceController::class, 'update']);
    Route::delete('/services/{id}', [ServiceController::class, 'destroy']);
});

// No manual OPTIONS catch-all needed — HandleCors middleware handles preflight automatically.