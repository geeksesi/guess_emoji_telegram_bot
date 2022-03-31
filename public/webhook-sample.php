<?php
/**
 * PLEASE RENAME THIS FILE. BECAUSE SECURITY ISSUES
 */
die("YOU SHOULD NOT RUN THIS FILE. PLEASE AFTER COPY REMOVE THIS LINE");
use App\Helper\InputHelper;
use App\Helper\LogHelper;

error_reporting(-1);
ini_set("display_errors", 0);

include __DIR__ . "/../vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->safeLoad();

$input = file_get_contents("php://input");

try {
    LogHelper::update(json_encode($update));
} catch (\Throwable $th) {
    var_dump("CANT STORE LOG");
}
$update = json_decode($input, true);
(new InputHelper($update))();
