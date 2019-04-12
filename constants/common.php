<?php

$localConstants = __DIR__ . DIRECTORY_SEPARATOR . 'local.php';

if (file_exists($localConstants) && is_readable($localConstants)) {
    // подключаем файл с локальными константами
    require $localConstants;
}


// БАЗОВЫЕ Константы


// алиас к а-панели для доступа из web (админка)
defined('APANEL_WEB_ALIAS') or define('APANEL_WEB_ALIAS', 'apanel');


// директория с файлами а-панели
defined('APANEL_DIR') or define('APANEL_DIR', 'apanel');

// директория с файлами фронт части приложения
defined('FRONT_DIR') or define('FRONT_DIR', 'front');

// директория с файлами бекенд части приложения
defined('BACKEND_DIR') or define('BACKEND_DIR', 'backend');

// директория с общими файлами для админ и фронт частей
defined('SHARED_DIR') or define('SHARED_DIR', 'shared');

// директория с изображениями
defined('IMAGES_DIR') or define('IMAGES_DIR', 'images');

// директория с дефолтными шаблонами (для view)
defined('DEFAULT_TPLS_DIR') or define('DEFAULT_TPLS_DIR', 'default-tpls');

// директория с импортируемыми файлами (для загрузки данных из файлов в БД)
defined('IMPORTED_FILES_DIR') or define('IMPORTED_FILES_DIR', 'imported-files');


// время хранения сессии аутентификации в куках (по умолчанию 30 дней)
defined('COOKIE_AUTH_TIME_VALUE') or define('COOKIE_AUTH_TIME_VALUE', 2592000);

// имя ключа аутентификации, хранящего токен в куках
defined('COOKIE_AUTH_KEY_NAME') or define('COOKIE_AUTH_KEY_NAME', 'auth_key');


// поддерживаемые версии API;
// значения указываются через запятую без пробелов, например: 1,3,5,11.1
defined('SUPPORTED_API_VERSIONS') or define('SUPPORTED_API_VERSIONS', '1');


// алиас для разделителя
defined('DS') or define('DS', DIRECTORY_SEPARATOR);

// алиас для директории с дефолтными картинками для apanel`и
defined('APANEL_DEFAULT_IMG_DIR') or define('APANEL_DEFAULT_IMG_DIR', APANEL_WEB_ALIAS . '/' . IMAGES_DIR . '/default');

