<?php

return [
    'bsVersion' => '5.x', // Kartik Global Bootstrap Config

    'sysName' => 'SMIS',
    'orgName' => 'NDU-K',
    'adminEmail' => 'admin@example.com',
    'senderEmail' => 'noreply@example.com',
    'supportEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    'sitename' => 'smis',
    'sitenameLong' => 'smis',

    // Sys Configs
    'gender' => ['M' => 'MALE', 'F' => 'FEMALE'],
    'bloodGrp' => ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-',],

    // image defaults - Photo uploads
    'image' => [
        'default' => ['width' => 150, 'height' => 180,]
    ],

    // password reset url
    'resetPasswordUrl' => 'http://usermgtdev.uonbi.ac.ke/site/reset-password',
];
