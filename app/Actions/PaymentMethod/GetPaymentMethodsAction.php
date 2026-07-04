<?php

namespace App\Actions\PaymentMethod;

use App\Repositories\PaymentMethodRepository;
use Illuminate\Database\Eloquent\Collection;

class GetPaymentMethodsAction
{
    public function __construct(private readonly PaymentMethodRepository $paymentMethods) {}

    public function execute(int $userId): Collection
    {
        return $this->paymentMethods->getByUser($userId);
    }
}