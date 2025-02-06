<?php

namespace App\Enums;

enum OrderStatus: string
{
    case LAYAWAY = 'LAYAWAY';
    case PENDING = "PENDING";
    case PAID = 'PAID';
    case DUE = 'DUE';
    case PARTIAL = 'PARTIAL';
    case CANCELLED = 'CANCELLED';
}