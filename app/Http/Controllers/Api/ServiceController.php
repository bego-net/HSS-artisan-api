<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;

class ServiceController extends Controller
{
    // GET all services
    public function index()
    {
        return response()->json(Service::all());
    }

    // CREATE new service
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