<?php

use App\Http\Controllers\Api\AdminAuthController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\TestimonialController;
use App\Http\Controllers\Api\PartnerController;
use Illuminate\Support\Facades\Route;

// ──────────────────────────────────────────────
// Public routes
// ──────────────────────────────────────────────

Route::get('/services', [ServiceController::class, 'index']);
Route::get('/services/{id}', [ServiceController::class, 'show']);
Route::get('/services/slug/{slug}', [ServiceController::class, 'showBySlug']);

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);

Route::get('/testimonials', [TestimonialController::class, 'index']);
Route::get('/testimonials/{id}', [TestimonialController::class, 'show']);

Route::get('/partners', [PartnerController::class, 'index']);
Route::get('/partners/{id}', [PartnerController::class, 'show']);

// Contact form (public — no auth required)
Route::post('/contact', [ContactController::class, 'store']);

// Debug: test Cloudinary config (remove after debugging)
Route::get('/debug/cloudinary', function () {
    try {
        $url = config('cloudinary.url');
        if (! $url) {
            return response()->json([
                'status'  => 'error',
                'message' => 'CLOUDINARY_URL not set in config',
                'env_direct' => env('CLOUDINARY_URL') ? 'env() has value' : 'env() is null too',
            ]);
        }

        $service = new \App\Services\CloudinaryService();
        $ping    = $service->ping();

        return response()->json([
            'status'         => 'ok',
            'cloudinary_url' => substr($url, 0, 30) . '...',
            'ping'           => $ping,
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'status'  => 'error',
            'message' => $e->getMessage(),
            'class'   => get_class($e),
        ]);
    }
});

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

    // Products CRUD
    Route::post('/admin/products', [ProductController::class, 'store']);
    Route::post('/admin/products/{id}', [ProductController::class, 'update']);   // multipart
    Route::put('/admin/products/{id}', [ProductController::class, 'update']);
    Route::delete('/admin/products/{id}', [ProductController::class, 'destroy']);

    // Testimonials CRUD
    Route::post('/admin/testimonials', [TestimonialController::class, 'store']);
    Route::post('/admin/testimonials/{id}', [TestimonialController::class, 'update']);   // multipart
    Route::put('/admin/testimonials/{id}', [TestimonialController::class, 'update']);
    Route::delete('/admin/testimonials/{id}', [TestimonialController::class, 'destroy']);

    // Partners CRUD
    Route::post('/admin/partners', [PartnerController::class, 'store']);
    Route::post('/admin/partners/{id}', [PartnerController::class, 'update']);   // multipart
    Route::put('/admin/partners/{id}', [PartnerController::class, 'update']);
    Route::delete('/admin/partners/{id}', [PartnerController::class, 'destroy']);
});