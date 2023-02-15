<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 2/13/2023
 * @time: 11:35 AM
 */

use yii\symfonymailer\Mailer;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'controllerNamespace' => 'app\modules\studentRegistration\commands',
    'bootstrap' => ['log'],
    'timeZone' => 'Africa/Nairobi',
    'components' => [
        'db' => $db['smis'],
        'db2' => $db['smisportal'],
        'mailer' => [
            /**
             * https://github.com/symfony/symfony-docs/issues/17115
             */
            'class' => Mailer::class,
            'viewPath' => '@app/modules/studentRegistration/mail',
            'useFileTransport' => false,
            'transport' => [
//                'dsn' => 'smtp://d38acd23973124:4badb45ed6fd76@smtp.mailtrap.io:2525?encryption=tls&auth_mode=login',
                'dsn' => 'gmail://smisadmin@uonbi.ac.ke:lziunystxuhwunjh@default'
            ]
        ],
        'log' => [
            'class' => 'yii\log\Target',
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\EmailTarget',
                    'logVars' => [],
                    'levels' => ['error', 'warning', 'info'],
                    'except' => [
                        'yii\db\Command:*',
                    ],
                    'message' => [
                        'from' => ['smisadmin@uonbi.ac.ke'],
                        'to' => ['examadmin@uonbi.ac.ke'],
                        'subject' => 'Student registration module logs on dev',
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
    'params' => $params
];
return $config;