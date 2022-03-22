<?php

namespace App\Helper;

use App\Model\Level;
use App\Enums\LevelHintsTypeEnum;

class LevelHintsHelper
{
    private static function mbStringToArray($string)
    {
        $strlen = mb_strlen($string);
        while ($strlen) {
            $array[] = mb_substr($string, 0, 1, "UTF-8");
            $string = mb_substr($string, 1, $strlen, "UTF-8");
            $strlen = mb_strlen($string);
        }
        return $array;
    }

    public static function generate(Level $level)
    {
        $answer = self::mbStringToArray($level->answer);
        $range = range(0, count($answer));
        $all_hidden = self::word_place($answer);
        shuffle($range);
        $phase_count = ceil(count($range) / 3);
        $chunks = array_chunk($range, $phase_count);
        $phase_one = $chunks[0];
        $phase_two = array_merge($phase_one, $chunks[1]);
        $phase_three = array_merge($phase_two, $chunks[2]);

        TelegramHelper::send_message(json_encode($chunks), $_ENV["ADMIN"]);
        $text = "تعداد حروف با احتساب فاصله (اگه داشته باشه) : " . count($answer);
        $text .= "\n";

        $output = [
            1 => [
                "level_id" => $level->id,
                "hint" => self::text($text, $all_hidden),
                "type" => LevelHintsTypeEnum::AUTO_GENERATE->value,
                "orders" => 1,
            ],
            2 => [
                "level_id" => $level->id,
                "hint" => self::text($text, self::word_phase($answer, $phase_one)),
                "type" => LevelHintsTypeEnum::AUTO_GENERATE->value,
                "orders" => 2,
            ],
            3 => [
                "level_id" => $level->id,
                "hint" => self::text($text, self::word_phase($answer, $phase_two)),
                "type" => LevelHintsTypeEnum::AUTO_GENERATE->value,
                "orders" => 3,
            ],
            4 => [
                "level_id" => $level->id,
                "hint" => self::text($text, self::word_phase($answer, $phase_three)),
                "type" => LevelHintsTypeEnum::AUTO_GENERATE->value,
                "orders" => 4,
            ],
            // 5 => [
            //     "level_id" => $level->id,
            //     "hint" => $answer,
            //     "type" => LevelHintsTypeEnum::AUTO_GENERATE->value,
            //     "orders" => 5,
            // ],
        ];
        return $output;
    }

    private static function word_place(array $_answer): string
    {
        $output = [];
        foreach ($_answer as $c) {
            if ($c === " ") {
                $output[] = "▫️";
                continue;
            }
            $output[] = "➖";
        }
        return implode("", $output);
    }

    private static function word_phase(array $_answer, array $_keys): string
    {
        $output = [];
        foreach ($_answer as $i => $c) {
            if ($c === " ") {
                $output[] = "▫️";
                continue;
            }
            if (in_array($i, $_keys)) {
                $output[] = $c;
                continue;
            }
            $output[] = "➖";
        }
        return implode($output);
    }

    public static function text($text, $generated)
    {
        return $text . "\n" . $generated;
    }
}
