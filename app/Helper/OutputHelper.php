<?php

namespace App\Helper;

use App\Enums\OutputMessageEnum;
use App\Model\Level;
use App\Model\OutputMessage;
use App\Model\User;

class OutputHelper
{
    public static function by_type(string $_chat_id, OutputMessageEnum $_type, bool $_random = false)
    {
        if ($_random) {
            $message = OutputMessage::random($_type);
        } else {
            $message = OutputMessage::by_type($_type);
        }
        if (empty($message)) {
            return false;
        }
        $keyboard = KeyboardMakerHepler::by_type($_type);
        TelegramHelper::send_message($message->text, $_chat_id, $keyboard);
    }

    public static function win_level(User $_user)
    {
        self::by_type($_user->chat_id, OutputMessageEnum::LEVEL_WIN, true);
        self::level($_user);
    }

    public static function lose_level(User $_user)
    {
        self::by_type($_user->chat_id, OutputMessageEnum::LEVEL_LOSE, true);
        self::level($_user);
    }

    public static function new_level(User $_user)
    {
        self::by_type($_user->chat_id, OutputMessageEnum::NEW_LEVEL, true);
        self::level($_user);
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

    public static function free_credit(User $_user)
    {
        $keyboard = KeyboardMakerHepler::free_credit();
        $message = "";
        $message .= "این لینک رو برای دوستات بفرست، اونها رو به بازی دعوت کن و 80 تا سکه بگیر 🤩";
        $message .= "\n";
        $message .= "راستی دوستت هم 40 تا سکه بیشتر می گیره اول بازی. فقط به خاطر اینکه دوست شماست 😉";
        $message .= "\n";
        $message .= $_user->invite_link();

        TelegramHelper::send_message($message, $_user->chat_id, $keyboard);
    }

    public static function low_credit(string $_chat_id)
    {
        self::by_type($_chat_id, OutputMessageEnum::LOW_CREDIT);
    }
}
