<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('created_at', 'desc')->get();

        $products->transform(function ($product) {
            $product->image = asset('storage/' . $product->image);
            return $product;
        });

        return response()->json($products);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'image'       => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $path = $request->file('image')->store('products', 'public');

        $product = Product::create([
            'title'       => $request->title,
            'description' => $request->description,
            'image'       => $path,
        ]);

        $product->image = asset('storage/' . $product->image);

        return response()->json($product, 201);
    }

    public function show(string $id)
    {
        $product = Product::findOrFail($id);
        $product->image = asset('storage/' . $product->image);

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
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $product->image = $request->file('image')->store('products', 'public');
        }

        $product->save();

        $product->image = asset('storage/' . $product->image);

        return response()->json($product);
    }

    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);

        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }
}
