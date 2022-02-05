<?php
error_reporting(-1);
ini_set('display_errors', 0);

include __DIR__ . '/env.php';
include __DIR__ . '/TelegramLib.php';
include __DIR__ . '/Model.php';
include __DIR__ . '/Controller.php';

$input = file_get_contents('php://input');

$update = json_decode($input, true);

$controller = new Controller();

$controller->handle($update);
