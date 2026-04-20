<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    private function isAdmin(Request $request): bool
    {
        $user = $request->user();
        return ($user?->role ?? null) === 'admin' || (bool) ($user?->is_admin ?? false);
    }

    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Services retrieved successfully',
            'data' => Service::all(),
        ], 200);
    }

    public function store(Request $request): JsonResponse
    {
        if (! $this->isAdmin($request)) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden. Admin access required.',
            ], 403);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'icon' => ['nullable', 'string'],
        ]);

        $service = Service::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Service created successfully',
            'data' => $service,
        ], 201);
    }

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
            'data' => $service,
        ], 200);
    }

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
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'icon' => ['nullable', 'string'],
        ]);

        $service->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Service updated successfully',
            'data' => $service,
        ], 200);
    }

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

        $service->delete();

        return response()->json([
            'success' => true,
            'message' => 'Service deleted successfully',
            'data' => null,
        ], 200);
    }
}
