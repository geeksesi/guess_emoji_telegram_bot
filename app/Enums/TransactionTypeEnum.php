<?php
namespace App\Enums;

enum TransactionTypeEnum: int
{
    // -
    case HINT_COST = 10;

    // +
    case BUY_CREDIT = 20;
    case SUGGEST_LEVEL = 22;
    case WIN_LEVEL = 23;
    case FRIEND_INVITE = 24;
    case FRIEND_GIFT_BACK = 25;
    case ADVERTISE_GIFT_CREDIT = 26;
}
