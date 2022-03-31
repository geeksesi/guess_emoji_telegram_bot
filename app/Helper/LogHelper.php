<?php

namespace App\Helper;

class LogHelper
{
    public static function update(string $update)
    {
        $before = microtime(true);
        $path = __DIR__ . "/../../storage/log";

        $file = $path . "/updates-" . date("W", time()) . ".txt";

        if (!file_exists("somefile.txt")) {
            touch($file);
        }
        $hanlde = fopen($file, "a+");
        $data = json_encode($update) . "\n";
        // file_put_contents($file, $data);

        fwrite($hanlde, $data);

        fclose($hanlde);
        $after = microtime(true);
        echo $after - $before . " sec\n";
    }
}
