<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\TransactionController;
use App\Http\Middleware\EnsureRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;

Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:10,1');
Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::put('/update-profile/{id}', [AuthController::class, 'update']);
    Route::get('/products', [ProductController::class, 'index']);
    Route::post('/products', [ProductController::class, 'store'])->middleware([EnsureRole::class, 'throttle:10,1']);
    Route::put('/products/{id}', [ProductController::class, 'update'])->middleware(EnsureRole::class);
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->middleware(EnsureRole::class);
    Route::get('/products/search', [ProductController::class, 'search']);
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/order-history', [OrderController::class, 'orderHistory']);
    Route::get('/shipping/provinces', [ShippingController::class, 'getProvinces']);
    Route::get('/shipping/cities', [ShippingController::class, 'getCities']);
    Route::post('/shipping/cost', [ShippingController::class, 'calculateShipping']);
    Route::post('/shipping/domestic-cost', [ShippingController::class, 'calculateDomesticCost']);
    Route::post('/orders/{id}/upload-proof', [OrderController::class, 'uploadProof']);
});
