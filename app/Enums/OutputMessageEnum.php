<?php
namespace App\Enums;

enum OutputMessageEnum: int
{
    // Game
    case LEVEL_WIN = 1;
    case LEVEL_LOSE = 2;
    case LEVEL_HINT = 3;
    case LEVEL = 4;
    case FINISH_GAME = 5;

    // about keyboards
    case START_GAME = 20;
    case CONTINUE = 21;
    case ABOUT = 22;
    case CONTACT = 23;
    case SUPPORT = 25;
    case YOUTUBE = 26;

    // Commands
    case START_COMMAND_GUEST = 30;
    case START_COMMAND_USER = 31;

    // Notify
    case NEW_LEVEL = 50;
    case INVATION_SUCCESS = 51;
    case FRIEND_INVITE_GIFT_BACK = 52;
    case SUCCESS_BUY_CREDIT = 53;

    // Errors
    case LOW_CREDIT = 401;
    case NO_MISSION = 404;
    case NO_HINT = 405;
}
