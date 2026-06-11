<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\SubscriptionPincodeController;

/* User Related Operation */
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/user/{id}', [AuthController::class, 'getProfile']);
Route::post('/send-otp', [AuthController::class, 'sendOtp']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::get('/test', function () {
    return ['message' => 'JWT working'];
});
Route::put('/user/{id}', [AuthController::class, 'updateUserDetails']);
Route::put('/user/{id}/details', [AuthController::class, 'updateUserDetails']);

/* Subscription Related Operation */
Route::get('/subscriptions', [SubscriptionController::class, 'index']);

/* Subscription-Pincode Mapping */
Route::get('/subscription-pincode-mappings', [SubscriptionPincodeController::class, 'index']);
Route::post('/subscription-pincode-mappings', [SubscriptionPincodeController::class, 'store']);
Route::get('/subscription-pincode-mappings/{id}', [SubscriptionPincodeController::class, 'show']);
Route::put('/subscription-pincode-mappings/{id}', [SubscriptionPincodeController::class, 'update']);
Route::delete('/subscription-pincode-mappings/{id}', [SubscriptionPincodeController::class, 'destroy']);
Route::get('/subscriptions/{subscriptionId}/pincodes', [SubscriptionPincodeController::class, 'bySubscription']);
Route::get('/pincodes/{pincodeId}/subscriptions', [SubscriptionPincodeController::class, 'byPincode']);
Route::get('/customers/{customerId}/subscription-pincodes', [SubscriptionPincodeController::class, 'byCustomer']);

/* Cart Related Operation */
Route::get('/carts', [CartController::class, 'index']);
Route::post('/add-cart', [CartController::class, 'addCart']);
Route::get('/cart-details/{id}', [CartController::class, 'fetchCartDetails']);
Route::patch('/cart-status/{id}', [CartController::class, 'updateCartStatus']);
Route::post('/cart-details/save', [CartController::class, 'saveCartDetails']);
Route::post('/cart-details/save', [CartController::class, 'saveCartDetails']);


/* Payment Related Operation */
Route::post('/create-payment-intent', [PaymentController::class, 'createPaymentIntent']);
Route::post('/payment/save-status', [PaymentController::class, 'savePaymentStatus']);


/* Notification Related Operation */


Route::get('/notifications/{userId}', [NotificationController::class, 'index']);
Route::post('/notifications', [NotificationController::class, 'store']);
Route::patch('/notifications/read/{id}', [NotificationController::class, 'markAsRead']);
Route::patch('/notifications/read-all/{userId}', [NotificationController::class, 'markAllAsRead']);
Route::delete('/notifications/{id}', [NotificationController::class, 'delete']);


/* Subscription-Pincode Mapping */
Route::get('/subscription-pincode-mappings', [SubscriptionPincodeController::class, 'index']);
Route::post('/subscription-pincode-mappings', [SubscriptionPincodeController::class, 'store']);
Route::get('/subscription-pincode-mappings/{id}', [SubscriptionPincodeController::class, 'show']);
Route::put('/subscription-pincode-mappings/{id}', [SubscriptionPincodeController::class, 'update']);
Route::delete('/subscription-pincode-mappings/{id}', [SubscriptionPincodeController::class, 'destroy']);
Route::get('/subscriptions/{subscriptionId}/pincodes', [SubscriptionPincodeController::class, 'bySubscription']);
Route::get('/pincodes/{pincodeId}/subscriptions', [SubscriptionPincodeController::class, 'byPincode']);
Route::get('/customers/{customerId}/subscription-pincodes', [SubscriptionPincodeController::class, 'byCustomer']);
