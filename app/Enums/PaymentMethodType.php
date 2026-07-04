<?php

namespace App\Enums;

enum PaymentMethodType: string
{
    case Card = 'card';
    case Wallet = 'wallet';
    case NetBanking = 'net_banking';
    case CashOnDelivery = 'cash_on_delivery';
}