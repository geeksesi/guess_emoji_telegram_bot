<?php
namespace App\Enums;

enum PaymentStatusEnum: int
{
    case CREATED = 1;
    case PENDING = 2;
    case SUCCESS = 3;
    case FAIL = 4;
}
