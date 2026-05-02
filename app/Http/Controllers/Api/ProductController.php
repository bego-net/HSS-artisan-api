<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\CloudinaryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class ProductController extends Controller
{
    public function index(): JsonResponse
    {
        $products = Product::orderBy('created_at', 'desc')->get();

        return response()->json($products);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'image'       => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            $cloudinary = new CloudinaryService();
            $uploaded   = $cloudinary->upload($request->file('image')->getRealPath(), 'hawi/products');

            $product = Product::create([
                'title'       => $request->title,
                'description' => $request->description,
                'image'       => $uploaded['url'],
                'public_id'   => $uploaded['public_id'],
            ]);

            return response()->json($product, 201);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Failed to upload image.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function show(string $id): JsonResponse
    {
        $product = Product::findOrFail($id);

        return response()->json($product);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'title'       => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'image'       => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            if ($request->has('title')) {
                $product->title = $request->title;
            }

            if ($request->has('description')) {
                $product->description = $request->description;
            }

            if ($request->hasFile('image')) {
                $cloudinary = new CloudinaryService();

                if ($product->public_id) {
                    $cloudinary->destroy($product->public_id);
                }

                $uploaded = $cloudinary->upload($request->file('image')->getRealPath(), 'hawi/products');
                $product->image     = $uploaded['url'];
                $product->public_id = $uploaded['public_id'];
            }

            $product->save();

            return response()->json($product);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Failed to update product.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        $product = Product::findOrFail($id);

        try {
            if ($product->public_id) {
                $cloudinary = new CloudinaryService();
                $cloudinary->destroy($product->public_id);
            }

            $product->delete();

            return response()->json(['message' => 'Product deleted successfully']);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Failed to delete product.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
