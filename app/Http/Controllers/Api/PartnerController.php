<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PartnerController extends Controller
{
    public function index()
    {
        $partners = Partner::orderBy('created_at', 'desc')->get();

        $partners->transform(function ($partner) {
            $partner->logo = asset('storage/' . $partner->logo);
            return $partner;
        });

        return response()->json($partners);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $path = $request->file('logo')->store('partners', 'public');

        $partner = Partner::create([
            'name' => $request->name,
            'logo' => $path,
        ]);

        $partner->logo = asset('storage/' . $partner->logo);

        return response()->json($partner, 201);
    }

    public function show(string $id)
    {
        $partner = Partner::findOrFail($id);
        $partner->logo = asset('storage/' . $partner->logo);

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
            if ($partner->logo && Storage::disk('public')->exists($partner->logo)) {
                Storage::disk('public')->delete($partner->logo);
            }
            $partner->logo = $request->file('logo')->store('partners', 'public');
        }

        $partner->save();

        $partner->logo = asset('storage/' . $partner->logo);

        return response()->json($partner);
    }

    public function destroy(string $id)
    {
        $partner = Partner::findOrFail($id);

        if ($partner->logo && Storage::disk('public')->exists($partner->logo)) {
            Storage::disk('public')->delete($partner->logo);
        }

        $partner->delete();

        return response()->json(['message' => 'Partner deleted successfully']);
    }
}
