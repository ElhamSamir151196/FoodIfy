<?php

namespace App\Repositories;

use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\PaymentTransaction;

class PaymentTransactionRepository
{
    public function __construct(private readonly PaymentTransaction $model) {}

    public function create(array $data): PaymentTransaction
    {
        return $this->model->create($data);
    }

    public function findByPaymobOrderId(string $paymobOrderId): ?PaymentTransaction
    {
        return $this->model->where('paymob_order_id', $paymobOrderId)->first();
    }

    public function findByOrder(Order $order): ?PaymentTransaction
    {
        return $this->model->where('order_id', $order->id)->latest()->first();
    }

    public function updateStatus(
        PaymentTransaction $transaction,
        PaymentStatus $status,
        ?string $paymobTransactionId = null,
        ?array $paymobResponse = null
    ): PaymentTransaction {
        $transaction->update([
            'status'                 => $status,
            'paymob_transaction_id'  => $paymobTransactionId ?? $transaction->paymob_transaction_id,
            'paymob_response'        => $paymobResponse ?? $transaction->paymob_response,
        ]);

        return $transaction->fresh();
    }
}