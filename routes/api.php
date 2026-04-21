<?php

use App\Http\Controllers\Api\AdminAuthController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\ServiceController;
use Illuminate\Support\Facades\Route;

// ──────────────────────────────────────────────
// Public routes
// ──────────────────────────────────────────────

Route::get('/services', [ServiceController::class, 'index']);
Route::get('/services/{id}', [ServiceController::class, 'show']);
Route::get('/services/slug/{slug}', [ServiceController::class, 'showBySlug']);

// Contact form (public — no auth required)
Route::post('/contact', [ContactController::class, 'store']);

// ──────────────────────────────────────────────
// Admin auth
// ──────────────────────────────────────────────

Route::post('/admin/login', [AdminAuthController::class, 'login']);
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->middleware(['auth:sanctum', 'admin']);

// ──────────────────────────────────────────────
// Protected admin routes
// ──────────────────────────────────────────────

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    // Services CRUD
    Route::post('/services', [ServiceController::class, 'store']);
    Route::put('/services/{id}', [ServiceController::class, 'update']);
    Route::post('/services/{id}', [ServiceController::class, 'update']);  // for multipart file uploads
    Route::delete('/services/{id}', [ServiceController::class, 'destroy']);

    // Contacts management
    Route::get('/admin/contacts', [ContactController::class, 'index']);
    Route::get('/admin/contacts/{id}', [ContactController::class, 'show']);
    Route::post('/admin/contacts/{id}/reply', [ContactController::class, 'reply']);
});