<?php

namespace App\Helper;

use App\Model\Level;
use LevelHintsTypeEnum;

class LevelHintsHelper
{
    public static function generate(Level $level)
    {
        $range = range(0, strlen($level->answer));
        $all_hidden = self::word_place($level->answer);
        shuffle($range);
        $phase_count = (int) (count($range) / 4);
        $phase_one = array_chunk($range, $phase_count);
        $phase_two = array_chunk($range, $phase_count * 2);
        $phase_three = array_chunk($range, $phase_count * 3);
        $output = [
            1 => [
                "level_id" => $level->id,
                "hint" => $all_hidden,
                "type" => LevelHintsTypeEnum::AUTO_GENERATE->value,
                "orders" => 1,
            ],
            2 => [
                "level_id" => $level->id,
                "hint" => self::word_phase($level->answer, $phase_one),
                "type" => LevelHintsTypeEnum::AUTO_GENERATE->value,
                "orders" => 2,
            ],
            3 => [
                "level_id" => $level->id,
                "hint" => self::word_phase($level->answer, $phase_two),
                "type" => LevelHintsTypeEnum::AUTO_GENERATE->value,
                "orders" => 3,
            ],
            4 => [
                "level_id" => $level->id,
                "hint" => self::word_phase($level->answer, $phase_three),
                "type" => LevelHintsTypeEnum::AUTO_GENERATE->value,
                "orders" => 4,
            ],
            5 => [
                "level_id" => $level->id,
                "hint" => $level->answer,
                "type" => LevelHintsTypeEnum::AUTO_GENERATE->value,
                "orders" => 5,
            ],
        ];
        return $output;
    }

    private static function word_place(string $_answer): string
    {
        $output = [];
        foreach (str_split($_answer) as $c) {
            if ($c == " ") {
                $output[] = " ";
                continue;
            }
            $output[] = "*";
        }
        return implode("", $output);
    }

    private static function word_phase(string $_answer, array $_keys): string
    {
        $output = [];
        foreach (str_split($_answer) as $i => $c) {
            if (in_array($i, $_keys)) {
                $output[] = $c;
            }
            if ($c == " ") {
                $output[] = " ";
                continue;
            }
            $output[] = "*";
        }
        return implode("", $output);
    }
}
