<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\Auth\AuthController;



/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->group(function () {
    Route::post('register',               [AuthController::class, 'register']);
    Route::post('register/verify',        [AuthController::class, 'verifyRegister']);
    Route::post('login',                  [AuthController::class, 'login']);
    Route::post('forget-password',        [AuthController::class, 'forgetPassword']);
    Route::post('forget-password/verify', [AuthController::class, 'verifyResetOtp']);
    Route::post('reset-password',         [AuthController::class, 'resetPassword']);
});

Route::middleware(['auth:sanctum', 'active'])->prefix('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me',      [AuthController::class, 'me']);
});
