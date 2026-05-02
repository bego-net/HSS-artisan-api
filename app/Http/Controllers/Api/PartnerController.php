<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public function index()
    {
        $partners = Partner::orderBy('created_at', 'desc')->get();

        return response()->json($partners);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $cloudinary = new CloudinaryService();
        $uploaded   = $cloudinary->upload($request->file('logo')->getRealPath(), 'hawi/partners');

        $partner = Partner::create([
            'name'      => $request->name,
            'logo'      => $uploaded['url'],
            'public_id' => $uploaded['public_id'],
        ]);

        return response()->json($partner, 201);
    }

    public function show(string $id)
    {
        $partner = Partner::findOrFail($id);

        return response()->json($partner);
    }

    public function update(Request $request, string $id)
    {
        $partner = Partner::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'logo' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->has('name')) {
            $partner->name = $request->name;
        }

        if ($request->hasFile('logo')) {
            // Delete old logo from Cloudinary
            $cloudinary = new CloudinaryService();
            $cloudinary->destroy($partner->public_id);

            // Upload new logo
            $uploaded = $cloudinary->upload($request->file('logo')->getRealPath(), 'hawi/partners');
            $partner->logo      = $uploaded['url'];
            $partner->public_id = $uploaded['public_id'];
        }

        $partner->save();

        return response()->json($partner);
    }

    public function destroy(string $id)
    {
        $partner = Partner::findOrFail($id);

        // Delete logo from Cloudinary
        $cloudinary = new CloudinaryService();
        $cloudinary->destroy($partner->public_id);

        $partner->delete();

        return response()->json(['message' => 'Partner deleted successfully']);
    }
}
