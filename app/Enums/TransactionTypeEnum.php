<?php
namespace App\Enums;

enum TransactionTypeEnum: int
{
    // -
    case HINT_COST = 10;

    // +
    case BUY_CREDIT = 20;
    case FRIEND_INVITE = 21;
    case SUGGEST_LEVEL = 22;
    case WIN_LEVEL = 23;
}
