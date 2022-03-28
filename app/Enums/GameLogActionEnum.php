<?php
namespace App\Enums;

enum GameLogActionEnum: int
{
    case WIN = 1;
    case LOSE = 2;
    case HINT = 3;
    case START = 4;
}
