<?php

use yii\web\JsonParser;

$params = require __DIR__ . '/params.php';
$paramsLocal = require __DIR__ . '/params-local.php';
$db = require __DIR__ . '/db.php';
$components = require __DIR__ . '/components.php';

$config = [
    'id' => 'http.auth',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'components' => array_merge($components, [
        'request' => [
            'cookieValidationKey' => 'wkT1A8CjthIF0vUvq4b0RbCMs6d2RrHM',
            'parsers' => ['application/json' => JsonParser::class]
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'class' => 'app\components\api\ErrorHandler',
            'errorAction' => 'site/error',
        ],
        'log' => $paramsLocal['log'] ?? [],
        'db' => $db,

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'auth/' => 'auth/auth',
                'auth/refresh' => 'auth/refresh',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ],
        ],
    ]),
    'params' => array_merge($params, $paramsLocal),
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
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1']
    ];
}

return $config;
