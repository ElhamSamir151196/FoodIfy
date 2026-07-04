<?php

namespace App\Http\Controllers\Api;

use App\Actions\Order\CheckoutAction;
use App\Actions\Order\GetOrderAction;
use App\Actions\Order\GetOrdersAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\CheckoutRequest;
use App\Http\Resources\OrderResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use ApiResponse;

    public function index(Request $request, GetOrdersAction $action): JsonResponse
    {
        $orders = $action->execute($request->user()->id, $request->query('status'));

        return $this->success(data: [
            'orders' => OrderResource::collection($orders),
            'meta' => [
                'current_page' => $orders->currentPage(),
                'last_page'    => $orders->lastPage(),
                'per_page'     => $orders->perPage(),
                'total'        => $orders->total(),
            ],
        ]);
    }

    public function show(int $id, Request $request, GetOrderAction $action): JsonResponse
    {
        $order = $action->execute($id, $request->user()->id);

        if (!$order) {
            return $this->error('Order not found.', 404);
        }

        return $this->success(data: ['order' => new OrderResource($order)]);
    }

    public function checkout(CheckoutRequest $request, CheckoutAction $action): JsonResponse
    {
        $order = $action->execute($request->user()->id, $request->validated());

        if (!$order) {
            return $this->error('Your cart is empty.', 422);
        }

        return $this->success(
            data: ['order' => new OrderResource($order)],
            message: 'Order placed successfully.',
            statusCode: 201
        );
    }
}