<?php

namespace App\Actions\Payment;

use App\Enums\PaymentStatus;
use App\Models\PaymentTransaction;
use App\Notifications\OrderPaidNotification;
use App\Repositories\OrderRepository;
use App\Services\PaymobService;

class HandlePaymentCallbackAction
{
    public function __construct(
        private readonly PaymobService   $paymob,
        private readonly OrderRepository $orders,
    ) {}

    public function execute(array $data): ?PaymentTransaction
    {
        $transaction = $this->paymob->handleCallback($data);

        if ($transaction && $transaction->status === PaymentStatus::Paid) {
            $order = $this->orders->findById($transaction->order_id);
            $order?->user?->notify(new OrderPaidNotification($order));
        }

        return $transaction;
    }
}