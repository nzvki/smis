<?php

use yii\db\pgsql\Schema;
use yii\db\Connection;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$db = [
    'class' => Connection::class,
    'dsn' => 'pgsql:host='.$_ENV['DB_HOST'].';dbname='.$_ENV['DB_NAME'],
    'username' => $_ENV['DB_USER'],
    'password' => $_ENV['DB_PASS'],
    'charset' => 'utf8',
    'schemaMap' => [
        'pgsql' => [
            'class' => Schema::class,
            'defaultSchema' => 'smis'
        ]
    ],
//    'attributes' => [
//        PDO::NULL_TO_STRING => false,
//    ],
];

$dbDev = [];
if (file_exists(__DIR__ . '/db.local.php')) {
    $dbDev = require_once(__DIR__ . '/db.local.php');
}

return yii\helpers\ArrayHelper::merge(
    $db,
    $dbDev
);
