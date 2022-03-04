<?php
error_reporting(-1);
ini_set('display_errors', 1);

include __DIR__ . '/vendor/autoload.php';
use App\Model\Level;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

var_dump(Level::create(['quest' => "test", 'answer' => 'test']));
var_dump($level = Level::get_first());
var_dump(Level::get_paginate());
var_dump(Level::delete($level->id));
