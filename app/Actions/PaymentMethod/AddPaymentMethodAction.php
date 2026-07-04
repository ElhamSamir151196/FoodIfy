<?php

namespace App\Actions\PaymentMethod;

use App\Models\PaymentMethod;
use App\Repositories\PaymentMethodRepository;

class AddPaymentMethodAction
{
    public function __construct(private readonly PaymentMethodRepository $paymentMethods) {}

    public function execute(int $userId, array $data): PaymentMethod
    {
        $data['user_id'] = $userId;

        return $this->paymentMethods->create($data);
    }
}