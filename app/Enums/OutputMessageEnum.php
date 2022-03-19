<?php
namespace App\Enums;

enum OutputMessageEnum: int
{
    case LEVEL_WIN = 1;
    case LEVEL_LOSE = 2;
    case LEVEL_HINT = 3;
    case START = 4;
    case FINISH_GAME = 5;

    case NO_MISSION = 404;
}
