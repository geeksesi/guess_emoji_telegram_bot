<?php

namespace App\Helper;

use App\Enums\OutputMessageEnum;
use App\Model\User;

class KeyboardMakerHepler
{
    private static array $texts = [
        "leader_board" => "🥇 برترین ها",
        "continue" => "❣️ ادامه بازی",
        "free_credit" => "💸 سکه رایگان",
        "buy_credit" => "💳 خرید سکه",
        "your_credit" => "💵 سکه‌های شما‌ : ",
        "youtube" => "🎓 دوست داری یاد بگیری یه ربات مثل این بسازی ؟",
        "about" => "🖥 درباره ما",
        "contact" => "📞 تماس با ما",
        "hint" => "🪄 کمک می‌خوای ؟",
        "support" => "😍 حمایت از ما",
    ];

    public static function by_type(OutputMessageEnum $type)
    {
        if (method_exists(self::class, strtolower($type->name))) {
            return self::{strtolower($type->name)}();
        }
        return [];
    }

    public static function start_command(): array
    {
        return TelegramHelper::make_keyboard([[["text" => "شروع بازی"]]], false, true);
    }

    public static function default_keyboard()
    {
        return TelegramHelper::make_keyboard(
            [
                [["text" => self::$texts["continue"]], ["text" => self::$texts["about"]]],
                [["text" => self::$texts["free_credit"]], ["text" => self::$texts["youtube"]]],
            ],
            true,
            true
        );
    }

    public static function get_score()
    {
        return TelegramHelper::make_keyboard(
            [
                [["text" => self::$texts["continue"]], ["text" => self::$texts["about"]]],
                [["text" => self::$texts["free_credit"]], ["text" => self::$texts["buy_credit"]]],
            ],
            true,
            true
        );
    }

    public static function level(User $user)
    {
        $help_text = self::$texts["hint"] . " " . $user->hint_cost() . " سکه";
        $credit_text = self::$texts["your_credit"] . $user->credit;

        return TelegramHelper::make_keyboard(
            [
                [["text" => $credit_text], ["text" => self::$texts["leader_board"]], self::$texts["free_credit"]],
                [["text" => $help_text]],
            ],
            true,
            true
        );
    }

    public static function leader_board()
    {
        return TelegramHelper::make_keyboard(
            [
                [["text" => self::$texts["continue"]], ["text" => self::$texts["about"]]],
                [["text" => self::$texts["support"]]],
            ],
            true,
            true
        );
    }

    public static function free_credit()
    {
        return TelegramHelper::make_keyboard(
            [
                [["text" => self::$texts["continue"]], ["text" => self::$texts["about"]]],
                [["text" => self::$texts["support"]], ["text" => self::$texts["buy_credit"]]],
            ],
            true,
            true
        );
    }

    public static function about()
    {
        return TelegramHelper::make_keyboard(
            [
                [["text" => self::$texts["continue"]], ["text" => self::$texts["contact"]]],
                [["text" => self::$texts["youtube"]], ["text" => self::$texts["support"]]],
            ],
            true,
            true
        );
    }
}
