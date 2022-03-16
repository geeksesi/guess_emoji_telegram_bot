<?php
namespace App\Enums;

enum OutputMessageEnum: int
{
    case LEVEL_WIN = 1;
    case LEVEL_LOSE = 2;
    case LEVEL_HINT = 3;
}
