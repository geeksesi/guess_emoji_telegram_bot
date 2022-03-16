<?php

namespace App\Helper;

use App\Model\Level;
use App\Enums\LevelHintsTypeEnum;

class LevelHintsHelper
{
    public static function generate(Level $level)
    {
        $range = range(0, strlen($level->answer));
        $all_hidden = self::word_place($level->answer);
        shuffle($range);
        $phase_count = (int) (count($range) / 4);
        $chunks = array_chunk($range, $phase_count);
        $phase_one = $chunks[0];
        $phase_two = array_merge($phase_one, $chunks[1]);
        $phase_three = array_merge($phase_two, $chunks[2]);

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
        $output = "";
        foreach (str_split($_answer) as $i => $c) {
            // var_dump($i . "::" . $_answer[$i]);
            if (in_array($i, $_keys)) {
                $output .= $_answer[$i];
                continue;
            }
            if ($c == " ") {
                $output .= " ";
                continue;
            }
            $output .= "*";
        }
        // var_dump(implode("", $output), count($_keys));
        return mb_convert_encoding($output, "UTF-8", "UTF-8");
    }
}
