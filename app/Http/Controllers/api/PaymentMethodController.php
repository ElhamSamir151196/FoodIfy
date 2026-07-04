<?php

namespace App\Http\Controllers\Api;

use App\Actions\PaymentMethod\AddPaymentMethodAction;
use App\Actions\PaymentMethod\DeletePaymentMethodAction;
use App\Actions\PaymentMethod\GetPaymentMethodsAction;
use App\Actions\PaymentMethod\SetDefaultPaymentMethodAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentMethod\StorePaymentMethodRequest;
use App\Http\Resources\PaymentMethodResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    use ApiResponse;

    public function index(Request $request, GetPaymentMethodsAction $action): JsonResponse
    {
        $methods = $action->execute($request->user()->id);

        return $this->success(data: ['payment_methods' => PaymentMethodResource::collection($methods)]);
    }

    public function store(StorePaymentMethodRequest $request, AddPaymentMethodAction $action): JsonResponse
    {
        $method = $action->execute($request->user()->id, $request->validated());

        return $this->success(
            data: ['payment_method' => new PaymentMethodResource($method)],
            message: 'Payment method added.',
            statusCode: 201
        );
    }

    public function setDefault(int $id, Request $request, SetDefaultPaymentMethodAction $action): JsonResponse
    {
        $updated = $action->execute($request->user()->id, $id);

        if (!$updated) {
            return $this->error('Payment method not found.', 404);
        }

        return $this->success(message: 'Default payment method updated.');
    }

    public function destroy(int $id, Request $request, DeletePaymentMethodAction $action): JsonResponse
    {
        $deleted = $action->execute($request->user()->id, $id);

        if (!$deleted) {
            return $this->error('Payment method not found.', 404);
        }

        return $this->success(message: 'Payment method deleted.');
    }
}