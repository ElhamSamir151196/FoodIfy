<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\PaymentMethodController;


/*
|--------------------------------------------------------------------------
| Client Routes (Orders, Checkout, Payments)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'client'])->group(function () {

   
    // ── Payments ──────────────────────────
    Route::post('orders/{order}/pay', [PaymentController::class, 'initiate']);

    // ── Payment Methods ───────────────────
    Route::get('payment-methods',                  [PaymentMethodController::class, 'index']);
    Route::post('payment-methods',                 [PaymentMethodController::class, 'store']);
    Route::patch('payment-methods/{id}/default',   [PaymentMethodController::class, 'setDefault']);
    Route::delete('payment-methods/{id}',          [PaymentMethodController::class, 'destroy']);
});

// Paymob webhook — no auth, Paymob calls this server-to-server
Route::post('payments/callback', [PaymentController::class, 'callback']);


