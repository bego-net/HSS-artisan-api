<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Services\CloudinaryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class PartnerController extends Controller
{
    public function index(): JsonResponse
    {
        $partners = Partner::orderBy('created_at', 'desc')->get();

        return response()->json($partners);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            $cloudinary = new CloudinaryService();
            $uploaded   = $cloudinary->upload($request->file('logo')->getRealPath(), 'hawi/partners');

            $partner = Partner::create([
                'name'      => $request->name,
                'logo'      => $uploaded['url'],
                'public_id' => $uploaded['public_id'],
            ]);

            return response()->json($partner, 201);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Failed to upload logo.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function show(string $id): JsonResponse
    {
        $partner = Partner::findOrFail($id);

        return response()->json($partner);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $partner = Partner::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'logo' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            if ($request->has('name')) {
                $partner->name = $request->name;
            }

            if ($request->hasFile('logo')) {
                $cloudinary = new CloudinaryService();

                if ($partner->public_id) {
                    $cloudinary->destroy($partner->public_id);
                }

                $uploaded = $cloudinary->upload($request->file('logo')->getRealPath(), 'hawi/partners');
                $partner->logo      = $uploaded['url'];
                $partner->public_id = $uploaded['public_id'];
            }

            $partner->save();

            return response()->json($partner);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Failed to update partner.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        $partner = Partner::findOrFail($id);

        try {
            if ($partner->public_id) {
                $cloudinary = new CloudinaryService();
                $cloudinary->destroy($partner->public_id);
            }

            $partner->delete();

            return response()->json(['message' => 'Partner deleted successfully']);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Failed to delete partner.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
