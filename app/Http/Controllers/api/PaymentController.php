<?php

namespace App\Http\Controllers\Api;

use App\Actions\Payment\HandlePaymentCallbackAction;
use App\Actions\Payment\InitiatePaymentAction;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    use ApiResponse;

    public function initiate(Order $order, InitiatePaymentAction $action): JsonResponse
    {
        $result = $action->execute($order);

        return $this->success(
            data: $result,
            message: 'Payment initiated.'
        );
    }

    public function callback(Request $request, HandlePaymentCallbackAction $action): JsonResponse
    {
        $transaction = $action->execute($request->all());

        if (!$transaction) {
            return $this->error('Invalid callback signature or unknown transaction.', 400);
        }

        return $this->success(message: 'Callback processed.');
    }
}