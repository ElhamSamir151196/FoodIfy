<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FavoriteController;

/*
|--------------------------------------------------------------------------
| Authenticated Client Routes (Favorites)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    // ── Favorites ─────────────────────────
    Route::get('favorites',           [FavoriteController::class, 'index']);
    Route::post('favorites/toggle',   [FavoriteController::class, 'toggle']);

});
