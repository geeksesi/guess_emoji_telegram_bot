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
        self::by_type($_user->chat_id, OutputMessageEnum::LEVEL_WIN);
        self::level($_user);
    }

    public static function lose_level(User $_user)
    {
        self::by_type($_user->chat_id, OutputMessageEnum::LEVEL_LOSE);
        self::level($_user);
    }

    public static function by_type(string $_chat_id, OutputMessageEnum $_type)
    {
        $message = OutputMessage::by_type($_type);
        if (empty($message)) {
            return;
        }
        $keyboard = KeyboardMakerHepler::by_type($_type);
        TelegramHelper::send_message($message->text, $_chat_id, $keyboard);
    }

    public static function level(User $_user)
    {
        $keyboard = KeyboardMakerHepler::level($_user);
        TelegramHelper::send_message($_user->level()->quest, $_user->chat_id, $keyboard);
    }

    public static function leader_board(string $_chat_id)
    {
        $keyboard = KeyboardMakerHepler::leader_board();
        TelegramHelper::send_message("در دست احداث 👷 ", $_chat_id, $keyboard);
    }

    public static function free_credit(string $_chat_id)
    {
        $keyboard = KeyboardMakerHepler::free_credit();
        TelegramHelper::send_message("در دست احداث 👷 ", $_chat_id, $keyboard);
    }
}
