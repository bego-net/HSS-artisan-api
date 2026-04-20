<?php

use App\Http\Controllers\Api\AdminAuthController;
use App\Http\Controllers\Api\ServiceController;
use Illuminate\Support\Facades\Route;

// Public (read-only) service endpoints
Route::get('/services', [ServiceController::class, 'index']);
Route::get('/services/{id}', [ServiceController::class, 'show']);

// Admin authentication endpoints
// `web` middleware is applied to login so the session guard can be used with Auth::attempt().
Route::post('/admin/login', [AdminAuthController::class, 'login'])->middleware('web');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->middleware(['auth:sanctum', 'admin']);

// Admin-only service endpoints protected by Sanctum + admin middleware
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::post('/services', [ServiceController::class, 'store']);
    Route::put('/services/{id}', [ServiceController::class, 'update']);
    Route::delete('/services/{id}', [ServiceController::class, 'destroy']);
});
Route::options('/{any}', function () {
    return response()->json([], 200);
})->where('any', '.*');