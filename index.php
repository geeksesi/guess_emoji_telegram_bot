<?php
error_reporting(-1);
ini_set('display_errors', 1);

include __DIR__ . '/vendor/autoload.php';
use App\Controller;
use App\TelegramLib;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$updates = TelegramLib::get_update();

$last_message_id = 0;
foreach ($updates as $update) {
    $controller = new Controller();

    $controller->handle($update);

    $last_message_id = $update['update_id'];
}
$updates = TelegramLib::get_update($last_message_id + 1);
