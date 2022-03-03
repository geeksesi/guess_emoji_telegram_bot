<?php

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

return [
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/db/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/db/seeds',
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'production',
        'production' => [
            'adapter' => 'mysql',
            'host' => $_ENV['MYSQL_HOST'],
            'name' => $_ENV['MYSQL_DB'],
            'user' => $_ENV['MYSQL_USERNAME'],
            'pass' => $_ENV['MYSQL_PASSWORD'],
            'port' => $_ENV['MYSQL_PORT'],
            'charset' => 'utf8',
        ],
    ],
    'version_order' => 'creation',
];
