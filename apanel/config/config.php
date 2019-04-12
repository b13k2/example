<?php

$params = require __DIR__ . DS . 'params.php';
$routes = require __DIR__ . DS . 'routes.php';

$config = [
    'id' => WEB_APP ? 'Web APP' : 'Api Query',

    'container' => [
        'definitions' => [
        ],
    ],

    'basePath'   => dirname(__DIR__),
    'vendorPath' => dirname(__DIR__) . DS . '..' . DS . 'vendor',
    'bootstrap'  => ['log'],

    'controllerNamespace' => WEB_APP ? 'app\controllers\\' . WEB_APP : 'app\controllers',

    'aliases' => [
        // директория с файлами для ресурсов
        '@assets-source' => '@app' . DS . 'assets-source',
        // директория с общими файлами для админ и фронт частей
        '@shared'        => '@app' . DS . '..' . DS . SHARED_DIR,
        // директория с виджетами
        '@widgets'       => '@app/widgets',
    ],

    // настройка компонентов
    'components' => [
        'request' => [
            'enableCookieValidation' => true,
            'cookieValidationKey'    => '0e6d9d3ac00a2ccf87edcaeddbf8c8c6',
            'enableCsrfValidation'   => true,
        ],

        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],

        'user' => [
            'identityClass'   => 'shared\components\common\core\UserIdentity',
            'loginUrl'        => '/' . APANEL_WEB_ALIAS . '/login',
            'enableSession'   => WEB_APP ? true : false,
            'enableAutoLogin' => true,
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

        'urlManager' => [
            'enablePrettyUrl'       => true,
            'showScriptName'        => false,
            'enableStrictParsing'   => true,
            'suffix'                => '',
            // дефолтные запросы к web/api
            'rules'                 => WEB_APP ? array_merge([

                // главная
                '' => 'index',

                // модуль "Меню"
                'GET menu-group'    => 'menu-group',
                // модуль "Пользователи"
                'GET user'          => 'user',
                // модуль "Модули"
                'GET module'        => 'module',
                // модуль "Регионы"
                'GET region'        => 'region',

                // форма входа/аутентификация
                'GET,POST login'    => 'login',
                // выход из системы
                'GET logout'        => 'logout',

            ], $routes) : array_merge([

                [
                    'class'         => 'yii\rest\UrlRule',
                    'pluralize'     => false,

                    // работа с иерархией (деревом)
                    'controller'    => [
                        'api/v1/hierarchy/menu-group',
                        'api/v1/hierarchy/user',
                        'api/v1/hierarchy/module',
                        'api/v1/hierarchy/region',
                    ],

                    'patterns' => [
                        // запрос на получение списка моделей
                        'GET'               => '',
                    ],
                ],

                [
                    'class'         => 'yii\rest\UrlRule',
                    'pluralize'     => false,

                    // работа с данными модулей
                    'controller'    => [
                        'api/v1/menu-group',
                        'api/v1/user',
                        'api/v1/module',
                        'api/v1/region',
                    ],

                    'patterns' => [
                        // запрос на получение списка полей модели
                        'GET'               => '',
                        // запрос на получение данных модели
                        'GET <id:\d+>'      => '',
                        // запрос на создание новой модели
                        'POST'              => 'create',
                        // запрос на полное/частичное обновление записи
                        'PUT,PATCH <id:\d+>'=> 'update',
                        // запрос на удаление
                        'DELETE <id:\d+>'   => 'delete',
                    ],
                ],

                [
                    'class'         => 'yii\rest\UrlRule',
                    'pluralize'     => false,

                    // работа со структурой
                    'controller'    => [
                        'api/v1/structure/module',
                    ],

                    'patterns' => [
                        // запрос на частичное обновление записи
                        'PATCH'             => 'update',
                    ],
                ],

            ], $routes),
        ],

        'db' => require __DIR__ . '/db.php',

        'assetManager' => [
            'appendTimestamp' => true,
            'linkAssets'      => true,
        ],
    ],

    'params' => $params,
];

return $config;

