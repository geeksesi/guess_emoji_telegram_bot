<?php
define('TOKEN', '2105225490:AAHMduRnGXLoBVhFF6bxOnKX4lS9YkJ6deE');
define('UPDATE_ID_FILE', __DIR__ . '/update_id.txt');

$url = 'https://api.telegram.org/bot' . TOKEN . '/';

$admin_id = '950263421';

// $SayHello = $url . 'sendMessage?text=Hi&chat_id=' . $admin_id;

// var_dump($get_update);
$file = fopen(UPDATE_ID_FILE, 'r');

$last_message_id = fread($file, filesize(UPDATE_ID_FILE));
fclose($file);
$get_update = $url . 'getUpdates?offset=' . $last_message_id;

$curl = curl_init($get_update);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$update_result = curl_exec($curl);

$messages = json_decode($update_result, true);
$has_message = false;
foreach ($messages['result'] as $message) {
    $chat_id = $message['message']['chat']['id'];
    $text = 'ممنونم که به گفتی ' . $message['message']['text'];

    $say_thank = $url . 'sendMessage?text=' . $text . '&chat_id=' . $chat_id;

    $curl = curl_init($say_thank);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $update_result = curl_exec($curl);

    $last_message_id = $message['update_id'];
    $has_message = true;
}
$file = fopen(UPDATE_ID_FILE, 'w+');

fwrite($file, $last_message_id + intval($has_message));
fclose($file);
