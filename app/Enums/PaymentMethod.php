<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case QRIS = 'qris';
    case TUNAI = 'tunai';
}
