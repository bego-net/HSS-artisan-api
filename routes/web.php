<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ServiceController;

Route::get('/api/services', [ServiceController::class, 'index']);
Route::post('/api/services', [ServiceController::class, 'store']);

Route::get('/', function () {
    return view('welcome');
});
