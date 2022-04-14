<?php

namespace App\Helper;

class Normalizer
{
    private static array $pairs = [
        "arabic" => ["ي", "ك", "ؤ", "ۀ"],
        "persian" => ["ی", "ک", "و", "ه"],
    ];

    public static function run(string $string): string
    {
        return self::whitespace(self::persianize($string));
    }

    public static function persianize(string $string): string
    {
        $arabic = self::$pairs["arabic"];
        $persian = self::$pairs["persian"];

        return str_replace($arabic, $persian, $string);
    }

    /**
     * Remove white space and half space (persian ‌)
     *
     * @param string $string
     * @return string
     */
    public static function whitespace(string $string): string
    {
        return preg_replace("/(\‌|\s)+/", "", $string);
    }
}
