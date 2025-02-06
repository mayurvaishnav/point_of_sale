<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case PAID = 'PAID';
    case DUE = 'DUE';
    case PARTIAL = 'PARTIAL';
}