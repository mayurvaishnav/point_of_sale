<?php

namespace App\Enums;

enum PaymentMethods: string
{
    case CASH = 'CASH';
    case CREDIT_CARD = 'CREDIT_CARD';
    case CUSTOMER_ACCOUNT = 'CUSTOMER_ACCOUNT';
}