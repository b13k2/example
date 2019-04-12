<?php

namespace app\assets\basic;


class StylesheetAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@assets-source' . DS . 'basic';

    public $css = [
        'css/bootstrap.min.css',
        'css/plugins.css',
        'css/main.css',
    ];

    public $js = [
        'js/vendor/modernizr.min.js',
    ];

    public $jsOptions = [
        'position' => \yii\web\View::POS_HEAD,
    ];

    public $depends = [
    ];
}

