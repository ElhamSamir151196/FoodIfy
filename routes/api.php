<?php

use App\Http\Controllers\api\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('register',              [AuthController::class, 'register']);
    Route::post('register/verify',       [AuthController::class, 'verifyRegister']);
    Route::post('login',                 [AuthController::class, 'login']);
    Route::post('forget-password',       [AuthController::class, 'forgetPassword']);
    Route::post('forget-password/verify',[AuthController::class, 'verifyResetOtp']);
    Route::post('reset-password',        [AuthController::class, 'resetPassword']);
});

Route::middleware(['auth:sanctum', 'active'])->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me',      [AuthController::class, 'me']);
    });
});

/*
// ─────────────────────────────────────────
// Public Routes
// ─────────────────────────────────────────
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login',    [AuthController::class, 'login']);
});

// ─────────────────────────────────────────
// Protected Routes (Sanctum)
// ─────────────────────────────────────────
Route::middleware(['auth:sanctum', 'active'])->group(function () {

    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me',      [AuthController::class, 'me']);
    });

    // ── Client Routes ──────────────────────
    Route::middleware('client')->group(function () {
        // Route::apiResource('orders', OrderController::class);
        // Route::apiResource('cart', CartController::class);
    });

    // ── Admin Routes ───────────────────────
    Route::middleware('admin')->prefix('admin')->group(function () {
        // Route::apiResource('users', AdminUserController::class);
        // Route::apiResource('categories', CategoryController::class);
    });

});

*/