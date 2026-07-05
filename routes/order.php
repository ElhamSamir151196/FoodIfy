<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\Admin\OrderController as AdminOrderController;



/*
|--------------------------------------------------------------------------
| Client Routes (Orders, Checkout, Payments)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'client'])->group(function () {

    // ── Orders ────────────────────────────
    Route::get('orders',      [OrderController::class, 'index']);
    Route::get('orders/{id}', [OrderController::class, 'show']);
    Route::post('checkout',   [OrderController::class, 'checkout']);

   
});


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'is_admin'])->prefix('admin')->group(function () {

    
    // ── Orders ────────────────────────────
    Route::get('orders',                     [AdminOrderController::class, 'index']);
    Route::get('orders/{id}',                [AdminOrderController::class, 'show']);
    Route::patch('orders/{id}/status',       [AdminOrderController::class, 'updateStatus']);
    Route::patch('orders/{id}/assign-rider', [AdminOrderController::class, 'assignRider']);

});
