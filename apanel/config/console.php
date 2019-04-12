<?php

$config = [
    'id' => 'Console App',

    'basePath'   => dirname(__DIR__),
    'bootstrap'  => ['log'],

    'aliases' => [
        // директория с общими файлами для админ и фронт частей
        '@shared'        => '@app' . DS . '..' . DS . SHARED_DIR,
    ],

    'controllerNamespace' => 'app\commands',

    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],

        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets'    => [
                [
                    'class'  => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],

        'db' => require __DIR__ . '/db.php',
    ],
];

return $config;

