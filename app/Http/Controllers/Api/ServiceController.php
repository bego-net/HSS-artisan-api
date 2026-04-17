<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Service::all());
    }

   public function store(Request $request)
{
    $service = Service::create([
        'title' => $request->title,
        'description' => $request->description,
        'icon' => $request->icon
    ]);

    return response()->json($service, 201);
}
}
