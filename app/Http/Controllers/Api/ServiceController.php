<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Services\CloudinaryService;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    private function isAdmin(Request $request): bool
    {
        $user = $request->user();
        return ($user?->role ?? null) === 'admin' || (bool) ($user?->is_admin ?? false);
    }

    /**
     * Public: List all services.
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Services retrieved successfully',
            'data'    => Service::all(),
        ], 200);
    }

    /**
     * Admin: Create a new service (supports image upload).
     */
    public function store(Request $request): JsonResponse
    {
        if (! $this->isAdmin($request)) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden. Admin access required.',
            ], 403);
        }

        $validated = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'content'     => ['nullable', 'string'],
            'icon'        => ['nullable', 'string'],
            'image'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
        ]);

        // Handle image upload via Cloudinary
        if ($request->hasFile('image')) {
            $cloudinary = new CloudinaryService();
            $uploaded   = $cloudinary->upload($request->file('image')->getRealPath(), 'hawi/services');

            $validated['image']     = $uploaded['url'];
            $validated['public_id'] = $uploaded['public_id'];
        }

        try {
            $service = Service::create($validated);
        } catch (UniqueConstraintViolationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'A service with this title (or slug) already exists. Please use a different title.',
                'errors'  => ['title' => ['A service with this title already exists.']],
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Service created successfully',
            'data'    => $service,
        ], 201);
    }

    /**
     * Public: Get a single service by ID.
     */
    public function show(string $id): JsonResponse
    {
        $service = Service::find($id);

        if (! $service) {
            return response()->json([
                'success' => false,
                'message' => 'Service not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Service retrieved successfully',
            'data'    => $service,
        ], 200);
    }

    /**
     * Public: Get a single service by slug.
     */
    public function showBySlug(string $slug): JsonResponse
    {
        $service = Service::where('slug', $slug)->first();

        if (! $service) {
            return response()->json([
                'success' => false,
                'message' => 'Service not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Service retrieved successfully',
            'data'    => $service,
        ], 200);
    }

    /**
     * Admin: Update a service (supports image upload).
     */
    public function update(Request $request, string $id): JsonResponse
    {
        if (! $this->isAdmin($request)) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden. Admin access required.',
            ], 403);
        }

        $service = Service::find($id);

        if (! $service) {
            return response()->json([
                'success' => false,
                'message' => 'Service not found',
            ], 404);
        }

        $validated = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'content'     => ['nullable', 'string'],
            'icon'        => ['nullable', 'string'],
            'image'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
        ]);

        // Handle image upload — delete old from Cloudinary, upload new
        if ($request->hasFile('image')) {
            $cloudinary = new CloudinaryService();
            $cloudinary->destroy($service->public_id);

            $uploaded = $cloudinary->upload($request->file('image')->getRealPath(), 'hawi/services');
            $validated['image']     = $uploaded['url'];
            $validated['public_id'] = $uploaded['public_id'];
        }

        try {
            $service->update($validated);
        } catch (UniqueConstraintViolationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'A service with this title (or slug) already exists. Please use a different title.',
                'errors'  => ['title' => ['A service with this title already exists.']],
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Service updated successfully',
            'data'    => $service->fresh(),
        ], 200);
    }

    /**
     * Admin: Delete a service (also removes its image from Cloudinary).
     */
    public function destroy(string $id): JsonResponse
    {
        if (! $this->isAdmin(request())) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden. Admin access required.',
            ], 403);
        }

        $service = Service::find($id);

        if (! $service) {
            return response()->json([
                'success' => false,
                'message' => 'Service not found',
            ], 404);
        }

        // Delete image from Cloudinary
        $cloudinary = new CloudinaryService();
        $cloudinary->destroy($service->public_id);

        $service->delete();

        return response()->json([
            'success' => true,
            'message' => 'Service deleted successfully',
            'data'    => null,
        ], 200);
    }
}
