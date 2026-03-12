<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;


/* User Related Operation */
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/test', function () {
    return ['message' => 'JWT working'];
});