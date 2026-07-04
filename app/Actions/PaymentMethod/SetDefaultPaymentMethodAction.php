<?php

namespace App\Actions\PaymentMethod;

use App\Repositories\PaymentMethodRepository;

class SetDefaultPaymentMethodAction
{
    public function __construct(private readonly PaymentMethodRepository $paymentMethods) {}

    public function execute(int $userId, int $paymentMethodId): bool
    {
        return $this->paymentMethods->setDefault($userId, $paymentMethodId);
    }
}