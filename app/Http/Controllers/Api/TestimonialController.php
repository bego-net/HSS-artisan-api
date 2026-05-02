<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::orderBy('created_at', 'desc')->get();

        return response()->json($testimonials);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'role'    => 'required|string|max:255',
            'message' => 'required|string',
            'image'   => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

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
    }

    public function show(string $id)
    {
        $testimonial = Testimonial::findOrFail($id);

        return response()->json($testimonial);
    }

    public function update(Request $request, string $id)
    {
        $testimonial = Testimonial::findOrFail($id);

        $request->validate([
            'name'    => 'sometimes|required|string|max:255',
            'role'    => 'sometimes|required|string|max:255',
            'message' => 'sometimes|required|string',
            'image'   => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',
        ]);

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
            // Delete old image from Cloudinary
            $cloudinary = new CloudinaryService();
            $cloudinary->destroy($testimonial->public_id);

            // Upload new image
            $uploaded = $cloudinary->upload($request->file('image')->getRealPath(), 'hawi/testimonials');
            $testimonial->image     = $uploaded['url'];
            $testimonial->public_id = $uploaded['public_id'];
        }

        $testimonial->save();

        return response()->json($testimonial);
    }

    public function destroy(string $id)
    {
        $testimonial = Testimonial::findOrFail($id);

        // Delete image from Cloudinary
        $cloudinary = new CloudinaryService();
        $cloudinary->destroy($testimonial->public_id);

        $testimonial->delete();

        return response()->json(['message' => 'Testimonial deleted successfully']);
    }
}
