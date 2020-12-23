<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$baseUrl = str_replace('/web', '', (new \yii\web\Request)->getBaseUrl());
$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'en',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin\Module',
        ],
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'q3wagzvs43tfj234drsew3rtwhg3w2a3eh5432qtgesr',
            'baseUrl' => $baseUrl,
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'baseUrl' => $baseUrl,
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'post/<id:>' => 'post/view',
                'postu/<id:>' => 'post/update',
                'postd/<id:>' => 'post/delete',
                'postc/' => 'post/create',
                'posts/page/<page:\d+>' => 'post/index', 
                'posts' => 'post/index',
                'admin/page/<page:\d+>' => 'admin/default/index', 
                'admin' => 'admin/default/index',
                'admin/user/<id>' => 'admin/default/user',
            ],
        ],
        'socialShare' => [
            'class' => \ymaker\social\share\configurators\Configurator::class,
            'socialNetworks' => [
                'telegram' => [
                    'class' => \ymaker\social\share\drivers\Telegram::class,
                ],
                'facebook' => [
                    'class' => \ymaker\social\share\drivers\Facebook::class,
                ],
                'whatsapp' => [
                    'class' => \ymaker\social\share\drivers\WhatsApp::class,
                ],
                'viber' => [
                    'class' => \ymaker\social\share\drivers\Viber::class,
                ],
                'linkedin' => [
                    'class' => \ymaker\social\share\drivers\Linkedin::class,
                ],
                'twitter' => [
                    'class' => \ymaker\social\share\drivers\Twitter::class,
                ],
            ],
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '192.168.10.20'],
    ];
}

return $config;
