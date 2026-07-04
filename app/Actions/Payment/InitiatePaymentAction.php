<?php

namespace App\Actions\Payment;

use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Repositories\PaymentTransactionRepository;
use App\Services\PaymobService;

class InitiatePaymentAction
{
    public function __construct(
        private readonly PaymobService                $paymob,
        private readonly PaymentTransactionRepository  $transactions,
    ) {}

    public function execute(Order $order): array
    {
        $authToken     = $this->paymob->authenticate();
        $paymobOrderId = $this->paymob->createOrder($order);
        $paymentKey    = $this->paymob->createPaymentKey($authToken, $paymobOrderId, $order);

        $this->transactions->create([
            'order_id'        => $order->id,
            'paymob_order_id' => $paymobOrderId,
            'amount'          => $order->total,
            'currency'        => 'EGP',
            'status'          => PaymentStatus::Pending,
        ]);

        return [
            'iframe_url'   => $this->paymob->iframeUrl($paymentKey),
            'payment_key'  => $paymentKey,
        ];
    }
}