<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\Auth\AuthController;



/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->group(function () {
    Route::post('register',               [AuthController::class, 'register']); // register new user using phone number
    Route::post('register/verify',        [AuthController::class, 'verifyRegister']); // verify registration OTP
    Route::post('login',                  [AuthController::class, 'login']); // login user using phone number and password
    Route::post('forget-password',        [AuthController::class, 'forgetPassword']); // request password reset OTP
    Route::post('forget-password/verify', [AuthController::class, 'verifyResetOtp']); // verify password reset OTP
    Route::post('reset-password',         [AuthController::class, 'resetPassword']); // reset password using verified OTP
});

Route::middleware(['auth:sanctum', 'active'])->prefix('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']); // logout user and revoke access token
    Route::get('me',      [AuthController::class, 'me']); // get authenticated user details
});
