<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CartController;


/*
|--------------------------------------------------------------------------
| Authenticated Client Routes (Cart)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    Route::get('cart',                [CartController::class, 'index']);
    Route::post('cart',               [CartController::class, 'store']);
    Route::put('cart/{mealId}',       [CartController::class, 'update']);
    Route::delete('cart/{mealId}',    [CartController::class, 'destroy']);
    Route::delete('cart',             [CartController::class, 'clear']);

});