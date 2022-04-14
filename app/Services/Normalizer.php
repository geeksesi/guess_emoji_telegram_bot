<?php

namespace App\Services;

class Normalizer
{
    private static array $pairs = [
        'arabic'  => ['ي', 'ك', 'ؤ', 'ۀ'],
        'persian' => ['ی', 'ک', 'و', 'ه'],
    ];

    public static function run(string $string): string
    {
        return self::whitespace(self::persianize($string));
    }

    public static function persianize(string $string): string
    {
        $arabic = self::$pairs['arabic'];
        $persian = self::$pairs['persian'];

        return str_replace($arabic, $persian, $string);
    }

    public static function whitespace(string $string): string
    {
        return preg_replace('/\s+/', ' ', $string);
    }
}
