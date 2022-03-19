<?php

namespace App\Helper;

use App\Enums\OutputMessageEnum;
use App\Model\Level;
use App\Model\OutputMessage;
use App\Model\User;

class OutputHelper
{
    public static function win_level(User $_user)
    {
        $message = OutputMessage::random(OutputMessageEnum::LEVEL_WIN);
        TelegramHelper::send_message($message->text, $_user->chat_id);
        self::level($_user->level(), $_user->chat_id);
    }

    public static function lose_level(User $_user)
    {
        $message = OutputMessage::random(OutputMessageEnum::LEVEL_LOSE);
        TelegramHelper::send_message($message->text, $_user->chat_id);
        self::level($_user->level(), $_user->chat_id);
    }

    public static function by_type(string $_chat_id, OutputMessageEnum $_type)
    {
        $message = OutputMessage::by_type($_type);
        TelegramHelper::send_message($message->text, $_chat_id);
    }

    public static function level(Level $level, string $_chat_id)
    {
        TelegramHelper::send_message($level->quest, $_chat_id);
    }
}
