<?php

use App\Helper\InputHelper;
use App\Helper\TelegramHelper;

error_reporting(-1);
ini_set('display_errors', 1);

include __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$updates = TelegramHelper::get_update();

$last_message_id = 0;
foreach ($updates as $update) {
    InputHelper::handle($update);

    $last_message_id = $update['update_id'];
}
$updates = TelegramHelper::get_update($last_message_id + 1);
