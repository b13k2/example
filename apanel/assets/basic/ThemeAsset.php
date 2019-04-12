<?php

namespace app\assets\basic;


class ThemeAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@assets-source' . DS . 'basic';

    public $css = [
        'css/themes.css',
    ];

    public $js = [
    ];

    public $jsOptions = [
    ];

    public $depends = [
        'app\assets\basic\ThemeForestAsset',
    ];
}

