<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CityController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;

// Define a named route for the login
Route::post('login', [AuthController::class, 'auth']);

Route::middleware(['auth:sanctum'])->group(function () {
    // Use 'apiResource' instead of 'resource' for API routes
    Route::apiResource('brand', BrandController::class);
    Route::apiResource('product', ProductController::class);
    Route::apiResource('city', CityController::class);
});
