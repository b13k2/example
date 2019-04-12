<?php

// фильтруем значения
$apiList = array_map(function($value) {
    return trim($value);
}, explode(',', SUPPORTED_API_VERSIONS));

$apiList = implode('|', $apiList);

// пустое значение не допускается
if ($apiList == '') {
    $apiList = 1;
}

// проверка на запрос к API
if (preg_match('/^\/(' . APANEL_WEB_ALIAS . '\/|)api\/v(' . $apiList . ')\/./', $_SERVER['REQUEST_URI'], $matches)) {

    define('API', 'api');
    define('API_VERSION', $matches[2]);

// web приложение
} else {
    define('WEB_APP', 'web');
}

defined('API') or define('API', false);
defined('API_VERSION') or define('API_VERSION', false);
defined('WEB_APP') or define('WEB_APP', false);

// вывод ошибок
defined('YII_DEBUG') or define('YII_DEBUG', false);
// режим работы
defined('YII_ENV') or define('YII_ENV', 'prod');

