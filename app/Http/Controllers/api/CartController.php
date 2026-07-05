<?php

namespace App\Http\Controllers\Api;

use App\Actions\Cart\AddToCartAction;
use App\Actions\Cart\ClearCartAction;
use App\Actions\Cart\GetCartAction;
use App\Actions\Cart\RemoveFromCartAction;
use App\Actions\Cart\UpdateCartItemAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\AddToCartRequest;
use App\Http\Requests\Cart\UpdateCartItemRequest;
use App\Http\Resources\CartResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    use ApiResponse;

    public function index(Request $request, GetCartAction $action): JsonResponse
    {
        $cart = $action->execute($request->user()->id);

        return $this->success(data: [
            'items' => CartResource::collection($cart['items']),
            'total' => $cart['total'],
        ]);
    }

    public function store(AddToCartRequest $request, AddToCartAction $action, GetCartAction $getCart): JsonResponse
    {
        $action->execute($request->user()->id, $request->meal_id, $request->quantity);

        $cart = $getCart->execute($request->user()->id);

        return $this->success(
            data: ['items' => CartResource::collection($cart['items']), 'total' => $cart['total']],
            message: 'Item added to cart.',
            statusCode: 201
        );
    }

    public function update( UpdateCartItemRequest $request, int $mealId, UpdateCartItemAction $action, GetCartAction $getCart): JsonResponse 
    {
        $item = $action->execute($request->user()->id, $mealId, $request->quantity);

        if (!$item) {  return $this->error('Item not found in cart.', 404);}

        $cart = $getCart->execute($request->user()->id);

        return $this->success(
            data: ['items' => CartResource::collection($cart['items']), 'total' => $cart['total']],
            message: 'Cart item updated.'
        );
    }

    public function destroy(
        Request $request,
        int $mealId,
        RemoveFromCartAction $action,
        GetCartAction $getCart
    ): JsonResponse {
        $removed = $action->execute($request->user()->id, $mealId);

        if (!$removed) {
            return $this->error('Item not found in cart.', 404);
        }

        $cart = $getCart->execute($request->user()->id);

        return $this->success(
            data: ['items' => CartResource::collection($cart['items']), 'total' => $cart['total']],
            message: 'Item removed from cart.'
        );
    }

    public function clear(Request $request, ClearCartAction $action): JsonResponse
    {
        $action->execute($request->user()->id);

        return $this->success(message: 'Cart cleared.');
    }
}