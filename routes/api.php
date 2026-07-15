<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\WishlistController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('category', CategoryController::class);
    Route::apiResource('product', ProductController::class);
    Route::post('/product/update/{id}', [ProductController::class, 'update']);
    Route::apiResource('cart', CartController::class);
    Route::apiResource('orders', OrderController::class);
    Route::post('/buy-now', [OrderController::class, 'buyNow']);
    Route::apiResource('payments', PaymentController::class);
    Route::get('/my-orders', [OrderController::class, 'myOrders']);
    Route::get('/wishlist', [WishlistController::class, 'index']);
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle']);
    Route::delete('/wishlist/{wishlistId}', [WishlistController::class, 'destroy']);
}); 

Route::apiResource('users', UserController::class);