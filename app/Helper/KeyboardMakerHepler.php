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
        "youtube" => "آموزش ساخت بازی 🕹",
        "about" => "🖥 درباره ما",
        "contact" => "📞 تماس با ما",
        "hint" => "🪄 کمک می‌خوای ؟",
        "support" => "😍 حمایت از ما",
        "profile" => "🖼 پروفایل",
        "change" => "تغییر پروفایل 📝",
        "back" => "بازگشت 🔙",
    ];

    public static function by_type(OutputMessageEnum $type)
    {
        if (method_exists(self::class, strtolower($type->name))) {
            return self::{strtolower($type->name)}();
        }
        return [];
    }

    public static function start_command_guest(): array
    {
        return TelegramHelper::make_keyboard([[["text" => "شروع بازی"]]], false, true);
    }

    public static function start_command_user(): array
    {
        return TelegramHelper::make_keyboard([[["text" => "ادامه بازی"]]], false, true);
    }

    public static function new_level(): array
    {
        return self::start_command_user();
    }

    public static function default_keyboard()
    {
        return TelegramHelper::make_keyboard(
            [
                [
                    ["text" => self::$texts["continue"]],
                    ["text" => self::$texts["free_credit"]],
                    ["text" => self::$texts["support"]],
                ],
                [["text" => self::$texts["profile"]], ["text" => self::$texts["buy_credit"]]],
                [
                    ["text" => self::$texts["about"]],
                    ["text" => self::$texts["youtube"]],
                    ["text" => self::$texts["contact"]],
                ],
            ],
            true,
            false
        );
    }

    public static function get_score()
    {
        return self::default_keyboard();
    }

    public static function level(User $user)
    {
        $help_text = self::$texts["hint"] . " " . $user->hint_cost() . " سکه";
        $credit_text = self::$texts["your_credit"] . $user->credit;

        return TelegramHelper::make_keyboard(
            [
                [["text" => $credit_text], ["text" => self::$texts["youtube"]], self::$texts["free_credit"]],
                [["text" => self::$texts["buy_credit"]], ["text" => $help_text]],
            ],
            true,
            true
        );
    }

    public static function leader_board()
    {
        return self::default_keyboard();
    }

    public static function free_credit()
    {
        return self::default_keyboard();
    }

    public static function about()
    {
        return self::default_keyboard();
    }

    public static function friend_invite_gift_back()
    {
        return [];
    }

    public static function no_mission()
    {
        return TelegramHelper::make_keyboard(
            [
                [["text" => self::$texts["support"]]],
                [["text" => self::$texts["about"]], ["text" => self::$texts["contact"]]],
                [["text" => self::$texts["youtube"]]],
            ],
            true,
            true
        );
    }

    public static function FINISH_GAME()
    {
        return self::no_mission();
    }

    public static function profile(): array
    {
        return TelegramHelper::make_keyboard(
            [
                [["text" => self::$texts["change"]], ["text" => self::$texts["back"]]],
            ],
            true,
            true
        );
    }

    public static function back(): array
    {
        return self::default_keyboard();
    }
}
