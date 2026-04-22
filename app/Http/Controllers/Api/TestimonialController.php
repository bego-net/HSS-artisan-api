<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::orderBy('created_at', 'desc')->get();

        $testimonials->transform(function ($testimonial) {
            $testimonial->image = asset('storage/' . $testimonial->image);
            return $testimonial;
        });

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

        $path = $request->file('image')->store('testimonials', 'public');

        $testimonial = Testimonial::create([
            'name'    => $request->name,
            'role'    => $request->role,
            'message' => $request->message,
            'image'   => $path,
        ]);

        $testimonial->image = asset('storage/' . $testimonial->image);

        return response()->json($testimonial, 201);
    }

    public function show(string $id)
    {
        $testimonial = Testimonial::findOrFail($id);
        $testimonial->image = asset('storage/' . $testimonial->image);

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
            if ($testimonial->image && Storage::disk('public')->exists($testimonial->image)) {
                Storage::disk('public')->delete($testimonial->image);
            }
            $testimonial->image = $request->file('image')->store('testimonials', 'public');
        }

        $testimonial->save();

        $testimonial->image = asset('storage/' . $testimonial->image);

        return response()->json($testimonial);
    }

    public function destroy(string $id)
    {
        $testimonial = Testimonial::findOrFail($id);

        if ($testimonial->image && Storage::disk('public')->exists($testimonial->image)) {
            Storage::disk('public')->delete($testimonial->image);
        }

        $testimonial->delete();

        return response()->json(['message' => 'Testimonial deleted successfully']);
    }
}
