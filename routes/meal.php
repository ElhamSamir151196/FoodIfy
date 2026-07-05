<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MealController;
use App\Http\Controllers\Api\Meal\ToggleAvailabilityController;
use App\Http\Controllers\Api\Meal\IngredientController;

// all users
Route::get('meals/ingredients', IngredientController::class); //  items added to meal ingredients list
Route::get('meals/{id}',      [MealController::class, 'show']);// view meal details


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'is_admin'])->prefix('admin')->group(function () {
  
    // ── Meals  Admin ─────────────────────────────
    Route::get('meals',           [MealController::class, 'index']); // view all meals
    Route::post('meals',                              [MealController::class, 'store']); // create new meal
    Route::put('meals/{meal}',                         [MealController::class, 'update']); // update existing meal
    Route::delete('meals/{meal}',                      [MealController::class, 'destroy']); // delete meal
    Route::patch('meals/toggle-availability/{meal}', ToggleAvailabilityController::class); // toggle meal availability

   
});