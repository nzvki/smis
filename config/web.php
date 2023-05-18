<?php

use yii\caching\FileCache;
use app\models\User;
use yii\log\FileTarget;
use app\modules\studentRecords\Module;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$smDb = require __DIR__ . '/sm_db.php';
$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@photos'   => '@app/uploads/avatars',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'DoxD53x6jhMrWZDTyVIjIU2_UbkSyG-7',
        ],
        'cache' => [
            'class' => FileCache::class,
        ],
        'user' => [
            'identityClass' => User::class,
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'sm_db' => $smDb,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'login' => 'site/login',
                'logout' => 'site/logout',
                '<module>/<controller>/<action:(update|delete|view|report-lost-id|print-single)>/<id:\d+>' => '<module>/<controller>/<action>',
            ],
        ],
        'formatter' => [
            'defaultTimeZone' => 'Africa/Nairobi',
            'dateFormat' => 'd-M-Y',
            'datetimeFormat' => 'd-M-Y H:i:s'
        ],
    ],
    'params' => $params,
    'modules' => [
        'student-records' => [
            'class' => Module::class
        ],
        'setup' => [
            'class' => \app\modules\setup\Module::class,
        ],
        'functionalSetup' => [
            'class' => 'app\modules\functionalSetup\Module',
        ],
        'student-registration' => [
            'class' => 'app\modules\studentRegistration\Module',
        ],
        'studentid' => [
            'class' => 'app\modules\studentid\Module',
            'defaultRoute' => 'manage-student-id'
        ],
        // Other TP Modules
        'gridview' => [
            'class' => \kartik\grid\Module::class
        ]
    ],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => \yii\debug\Module::class,
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => \yii\gii\Module::class,
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
        'allowedIPs' => ['*'],
    ];
}

return $config;
