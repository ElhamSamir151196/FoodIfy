<?php

namespace App\Http\Controllers\Api\Admin;

use App\Actions\Admin\GetAllOrdersAction;
use App\Actions\Order\AssignRiderAction;
use App\Actions\Order\UpdateOrderStatusAction;
use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\AssignRiderRequest;
use App\Http\Requests\Order\UpdateOrderStatusRequest;
use App\Http\Resources\OrderResource;
use App\Repositories\OrderRepository;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use ApiResponse;

    public function index(Request $request, GetAllOrdersAction $action): JsonResponse
    {
        $orders = $action->execute($request->only(['status', 'date_from', 'date_to', 'user_id']));

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

    public function show(int $id, OrderRepository $orders): JsonResponse
    {
        $order = $orders->findById($id);

        if (!$order) {
            return $this->error('Order not found.', 404);
        }

        return $this->success(data: ['order' => new OrderResource($order)]);
    }

    public function updateStatus(int $id, UpdateOrderStatusRequest $request, UpdateOrderStatusAction $action): JsonResponse
    {
        $order = $action->execute($id, OrderStatus::from($request->status));

        if (!$order) {
            return $this->error('Order not found.', 404);
        }

        return $this->success(
            data: ['order' => new OrderResource($order)],
            message: 'Order status updated.'
        );
    }

    public function assignRider(int $id, AssignRiderRequest $request, AssignRiderAction $action): JsonResponse
    {
        $order = $action->execute($id, $request->rider_id);

        if (!$order) {
            return $this->error('Order not found.', 404);
        }

        return $this->success(
            data: ['order' => new OrderResource($order)],
            message: 'Rider assigned successfully.'
        );
    }
}