<?php

require dirname(__DIR__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'constants' . DIRECTORY_SEPARATOR . 'common.php';
require dirname(__DIR__) . DS . '..' . DS . 'constants' . DS . 'web.php';

require dirname(__DIR__) . DS . '..' . DS . 'vendor' . DS . 'autoload.php';
require dirname(__DIR__) . DS . '..' . DS . 'vendor' . DS .'yiisoft' . DS . 'yii2' . DS . 'Yii.php';


$config = require dirname(__DIR__) . DS . '..' . DS . APANEL_DIR . DS . 'config' . DS . 'config.php';
(new yii\web\Application($config))->run();


function e($data='')
{
    echo '<pre>';
        var_dump($data);
    echo '</pre>';
}

