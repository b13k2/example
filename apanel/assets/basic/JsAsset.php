<?php

namespace app\assets\basic;


class JsAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@assets-source' . DS . 'basic';

    public $css = [
    ];

    public $js = [
        'js/vendor/jquery.min.js',
        'js/vendor/bootstrap.min.js',
        'js/plugins.js',
        'js/app.js',
        'js/core.common.js',
    ];

    public $jsOptions = [
    ];

    public $depends = [
    ];
}

