<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;


Route::get('/generate-token', function () {
    $user = User::first();
    $token = $user->createToken('ApiToken')->plainTextToken;
    return response()->json(['token' => $token]);
});

Route::post('/register', [RegisteredUserController::class, 'store'])->name('api.register');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('api.login');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('api.logout');

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::apiResource('categories', CategoryController::class)->except(['index', 'show']);
    Route::apiResource('products', ProductController::class)->except(['index', 'show']);
    Route::apiResource('orders', OrderController::class);
    
    Route::put('/orders/{order}/cancel', [OrderController::class, 'cancel']);
});

Route::apiResource('products', ProductController::class)->only(['index', 'show']);
Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);
Route::get('/random-product', [ProductController::class, 'random']);


