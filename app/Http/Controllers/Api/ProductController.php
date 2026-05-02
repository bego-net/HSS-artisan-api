<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('created_at', 'desc')->get();

        return response()->json($products);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'image'       => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $cloudinary = new CloudinaryService();
        $uploaded   = $cloudinary->upload($request->file('image')->getRealPath(), 'hawi/products');

        $product = Product::create([
            'title'       => $request->title,
            'description' => $request->description,
            'image'       => $uploaded['url'],
            'public_id'   => $uploaded['public_id'],
        ]);

        return response()->json($product, 201);
    }

    public function show(string $id)
    {
        $product = Product::findOrFail($id);

        return response()->json($product);
    }

    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'title'       => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'image'       => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->has('title')) {
            $product->title = $request->title;
        }

        if ($request->has('description')) {
            $product->description = $request->description;
        }

        if ($request->hasFile('image')) {
            // Delete old image from Cloudinary
            $cloudinary = new CloudinaryService();
            $cloudinary->destroy($product->public_id);

            // Upload new image
            $uploaded = $cloudinary->upload($request->file('image')->getRealPath(), 'hawi/products');
            $product->image     = $uploaded['url'];
            $product->public_id = $uploaded['public_id'];
        }

        $product->save();

        return response()->json($product);
    }

    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);

        // Delete image from Cloudinary
        $cloudinary = new CloudinaryService();
        $cloudinary->destroy($product->public_id);

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }
}
