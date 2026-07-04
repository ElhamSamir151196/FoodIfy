<?php

namespace App\Http\Controllers\Api;

use App\Actions\Favorite\GetFavoritesAction;
use App\Actions\Favorite\ToggleFavoriteAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Favorite\ToggleFavoriteRequest;
use App\Http\Resources\FavoriteResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    use ApiResponse;

    public function index(Request $request, GetFavoritesAction $action): JsonResponse
    {
        $favorites = $action->execute($request->user()->id);

        return $this->success(data: ['favorites' => FavoriteResource::collection($favorites)]);
    }

    public function toggle(ToggleFavoriteRequest $request, ToggleFavoriteAction $action): JsonResponse
    {
        $result = $action->execute($request->user()->id, $request->meal_id);

        $message = $result['status'] === 'added'
            ? 'Meal added to favorites.'
            : 'Meal removed from favorites.';

        return $this->success(data: $result, message: $message);
    }
}