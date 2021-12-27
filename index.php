<?php
include __DIR__ . '/env.php';
include __DIR__ . '/TelegramLib.php';

$messages = TelegraLib::get_update();


$last_message_id = 0;
foreach ($messages as $message) {
    $chat_id = $message['message']['chat']['id'];
    $text = 'ممنونم که به گفتی ' . $message['message']['text'];

    TelegraLib::send_message($text, $chat_id);

    $last_message_id = $message['update_id'];
}
$messages = TelegraLib::get_update($last_message_id + 1);
