<?php

namespace App\Helper;

use App\Enums\OutputMessageEnum;
use App\Model\OutputMessage;
use App\Model\User;

class OutputHelper
{
    public static function fill_data(string $text, array $data): string
    {
        foreach ($data as $key => $value) {
            $text = str_replace($key, $value, $text);
        }
        return $text;
    }

    public static function by_type(string $_chat_id, OutputMessageEnum $_type, bool $_random = false, array $data = [])
    {
        if ($_random) {
            $message = OutputMessage::random($_type);
        } else {
            $message = OutputMessage::by_type($_type);
        }
        if (empty($message)) {
            $text = $_type->name;
        } else {
            $text = $message->text;
        }

        if (!empty($data)) {
            $text = self::fill_data($message->text, $data);
        }
        $keyboard = KeyboardMakerHepler::by_type($_type);
        TelegramHelper::send_message($text, $_chat_id, $keyboard);
    }

    public static function win_level(User $_user, int $_prize)
    {
        self::by_type($_user->chat_id, OutputMessageEnum::LEVEL_WIN, true);
        TelegramHelper::send_message("🤩 تو " . $_prize . " سکه بابت حدس درستت گرفتی 🥳", $_user->chat_id);
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
        $level = $_user->level();
        if ($level) {
            TelegramHelper::send_message($level->quest, $_user->chat_id, $keyboard);
            return;
        }
        OutputHelper::by_type($_user->chat_id, OutputMessageEnum::NO_MISSION);
    }

    public static function leader_board(string $_chat_id)
    {
        $keyboard = KeyboardMakerHepler::leader_board();
        TelegramHelper::send_message("در دست احداث 👷 ", $_chat_id, $keyboard);
    }

    public static function free_credit(User $_user)
    {
        $invite_message = "";
        $invite_message .= "یه چالش جدید توی تلگرام پیدا کردم 😉 بیا ببینم می تونی حدس بزنی این شکلک یعنی چی ؟";
        $invite_message .= "\n";
        $invite_message .= "🥖👄";
        $invite_message .= "\n";
        $invite_message .= "🤖 رو این لینک کلیک کن: ";
        $invite_message .= $_user->invite_link();

        TelegramHelper::send_message($invite_message, $_user->chat_id);

        $keyboard = KeyboardMakerHepler::free_credit();
        $message = "";
        $message .= "پیام بالا رو برای دوستات بفرست، اونها رو به بازی دعوت کن و 80 تا سکه بگیر 🤩";
        $message .= "\n";
        $message .= "راستی دوستت هم 40 تا سکه بیشتر می گیره اول بازی. فقط به خاطر اینکه دوست شماست 😉";
        $message .= "\n";
        $message .= "🔗 اینم لینک اختصاصی شماست : ";
        $message .= $_user->invite_link();

        TelegramHelper::send_message($message, $_user->chat_id, $keyboard);
    }

    public static function low_credit(string $_chat_id)
    {
        self::by_type($_chat_id, OutputMessageEnum::LOW_CREDIT);
    }

    public static function profile(string $_chat_id, User $user)
    {
        $image = $user->image_id ?? TelegramHelper::get_user_profile_photo($_chat_id);
        $keyboard = KeyboardMakerHepler::by_type(OutputMessageEnum::PROFILE);
        $now = new \DateTime();
        $from = new \DateTime($user->created_at);
        $diff = $now->diff($from);

        $message = "شما {$user->name} هستی، فقط هم مال مایی 😌";
        $message .= "\n";
        $message .= "ماشالله {$user->credit} 💰 سکه داری 🤧";
        $message .= "\n";
        $message .= "اولین بار از  {$diff->days} روز پیش داری بازی می کنی 😍";
        $message .= "\n";
        $message .= "تو این چند وقت به {$user->level_count()} تا مرحله جواب دادی 😦";

        TelegramHelper::send_photo($image, $_chat_id, $message, $keyboard);
    }
}
