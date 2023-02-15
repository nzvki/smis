<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 2/13/2023
 * @time: 11:35 AM
 */

use yii\console\controllers\MigrateController;
use yii\symfonymailer\Mailer;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$smDb = require __DIR__ . '/sm_db.php';

$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'timeZone' => 'Africa/Nairobi',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
        '@tests' => '@app/tests',
    ],
    'components' => [
        'db' => $db,
        'sm_db' => $smDb,
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'mailer' => [
            /**
             * https://github.com/symfony/symfony-docs/issues/17115
             */
            'class' => Mailer::class,
            'useFileTransport' => false,
            'transport' => [
                'dsn' => 'gmail://ndukenyadev@uonbi.ac.ke:jbycuzbmswtoahpg@default'
            ]
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
//                [
//                    'class' => 'yii\log\FileTarget',
//                    'levels' => ['error', 'warning'],
//                ],
                [
                    'class' => 'yii\log\EmailTarget',
                    'logVars' => [],
                    'levels' => ['error', 'warning', 'info'],
                    'except' => [
                        'yii\db\Command:*',
                    ],
                    'message' => [
                        'from' => ['ndukenyadev@uonbi.ac.ke'],
                        'to' => ['ndukenyadev@uonbi.ac.ke'],
                        'subject' => 'Logs on dev server',
                    ],
                ],
            ],
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'defaultTimeZone' => 'Africa/Nairobi',
            'dateFormat' => 'd-M-Y',
            'datetimeFormat' => 'd-M-Y H:i:s'
        ],
    ],
    'params' => $params,
    'modules' => [
        'student-registration' => [
            'class' => 'app\modules\studentRegistration\Module',
        ],
        'migrate' => [
            'class' => MigrateController::class,
            'migrationTable' => 'test_migration'
        ],
    ],
];

return $config;
