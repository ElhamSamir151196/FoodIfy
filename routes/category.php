<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;

/*
|--------------------------------------------------------------------------
| Public Catalog Routes (Categories & it's Meals)
|--------------------------------------------------------------------------
*/

// all users
Route::get('categories',      [CategoryController::class, 'index']); // view all categories
Route::get('categories/{id}', [CategoryController::class, 'show']);// view category details along with its meals


// admin routes
Route::middleware(['auth:sanctum', 'is_admin'])->prefix('admin')->group(function () {

    Route::post('categories',                [CategoryController::class, 'store']); // create new category
    Route::put('categories/{category}',      [CategoryController::class, 'update']); // update existing category
    Route::delete('categories/{category}',   [CategoryController::class, 'destroy']); // delete category

});