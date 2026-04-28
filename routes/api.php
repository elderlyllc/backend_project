<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\CartController;


/* User Related Operation */
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/test', function () {
    return ['message' => 'JWT working'];
});
Route::put('/user/{id}', [AuthController::class, 'updateUserDetails']);

/* Subscription Related Operation */
Route::get('/subscriptions', [SubscriptionController::class, 'index']);

/* Cart Related Operation */
Route::get('/carts', [CartController::class, 'index']);
Route::post('/add-cart', [CartController::class, 'addCart']);
Route::get('/cart-details/{id}', [CartController::class, 'fetchCartDetails']);
Route::patch('/cart-status/{id}', [CartController::class, 'updateCartStatus']);
Route::post('/cart-details/save', [CartController::class, 'saveCartDetails']);