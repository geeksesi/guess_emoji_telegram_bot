<?php
error_reporting(-1);
ini_set('display_errors', 0);

include __DIR__ . '/vendor/autoload.php';
use App\Controller;
use App\TelegramLib;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$input = file_get_contents('php://input');

$update = json_decode($input, true);

$controller = new Controller();

$controller->handle($update);
