<?php
define('TOKEN', '2105225490:AAFPOMU7VtNm77hRpwQcdKO5GlQXIhJpDTg');

$url = 'https://api.telegram.org/bot' . TOKEN . '/';

$admin_id = '950263421';

$get_me = $url . 'getMe';

$SayHello = $url . 'sendMessage?text=Hi&chat_id=' . $admin_id;

var_dump($SayHello);

$curl = curl_init($SayHello);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($curl);

var_dump($result);
