<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Services\CloudinaryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class TestimonialController extends Controller
{
    public function index(): JsonResponse
    {
        $testimonials = Testimonial::orderBy('created_at', 'desc')->get();

        return response()->json($testimonials);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'role'    => 'required|string|max:255',
            'message' => 'required|string',
            'image'   => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            $cloudinary = new CloudinaryService();
            $uploaded   = $cloudinary->upload($request->file('image')->getRealPath(), 'hawi/testimonials');

            $testimonial = Testimonial::create([
                'name'      => $request->name,
                'role'      => $request->role,
                'message'   => $request->message,
                'image'     => $uploaded['url'],
                'public_id' => $uploaded['public_id'],
            ]);

            return response()->json($testimonial, 201);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Failed to upload image.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function show(string $id): JsonResponse
    {
        $testimonial = Testimonial::findOrFail($id);

        return response()->json($testimonial);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $testimonial = Testimonial::findOrFail($id);

        $request->validate([
            'name'    => 'sometimes|required|string|max:255',
            'role'    => 'sometimes|required|string|max:255',
            'message' => 'sometimes|required|string',
            'image'   => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            if ($request->has('name')) {
                $testimonial->name = $request->name;
            }

            if ($request->has('role')) {
                $testimonial->role = $request->role;
            }

            if ($request->has('message')) {
                $testimonial->message = $request->message;
            }

            if ($request->hasFile('image')) {
                $cloudinary = new CloudinaryService();

                if ($testimonial->public_id) {
                    $cloudinary->destroy($testimonial->public_id);
                }

                $uploaded = $cloudinary->upload($request->file('image')->getRealPath(), 'hawi/testimonials');
                $testimonial->image     = $uploaded['url'];
                $testimonial->public_id = $uploaded['public_id'];
            }

            $testimonial->save();

            return response()->json($testimonial);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Failed to update testimonial.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        $testimonial = Testimonial::findOrFail($id);

        try {
            if ($testimonial->public_id) {
                $cloudinary = new CloudinaryService();
                $cloudinary->destroy($testimonial->public_id);
            }

            $testimonial->delete();

            return response()->json(['message' => 'Testimonial deleted successfully']);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Failed to delete testimonial.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
