<?php

use App\Http\Controllers\Api\DeliveryRiderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TransportationWayController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('delivery-riders/{deliveryRider}', [DeliveryRiderController::class, 'show']);
});

Route::middleware(['auth:sanctum', 'is_admin'])->group(function () {
    Route::get('transportation-ways', TransportationWayController::class); // view all transportation ways

    Route::get('delivery-riders/', [DeliveryRiderController::class, 'index']);
    Route::post('delivery-riders/', [DeliveryRiderController::class, 'store']);
    Route::post('delivery-riders/{deliveryRider}', [DeliveryRiderController::class, 'update']); // POST + _method=PUT عشان الـ avatar (multipart)
    Route::delete('delivery-riders/{deliveryRider}', [DeliveryRiderController::class, 'destroy']);



});


