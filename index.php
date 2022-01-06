<?php
include __DIR__ . '/env.php';
include __DIR__ . '/TelegramLib.php';
include __DIR__ . '/Controller.php';

$updates = TelegraLib::get_update();

$last_message_id = 0;
foreach ($updates as $update) {
    $controller = new Controller();

    $controller->handle($update);

    $last_message_id = $update['update_id'];
}
$updates = TelegraLib::get_update($last_message_id + 1);
