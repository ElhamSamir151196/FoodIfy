<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;

/*
|--------------------------------------------------------------------------
| Public Catalog Routes (Categories & Meals)
|--------------------------------------------------------------------------
*/

// all users
Route::get('categories',      [CategoryController::class, 'index']);
Route::get('categories/{id}', [CategoryController::class, 'show']);


// admin routes
Route::middleware(['auth:sanctum', 'is_admin'])->prefix('admin')->group(function () {

    Route::post('categories',                [CategoryController::class, 'store']);
    Route::put('categories/{category}',      [CategoryController::class, 'update']);
    Route::delete('categories/{category}',   [CategoryController::class, 'destroy']);

});