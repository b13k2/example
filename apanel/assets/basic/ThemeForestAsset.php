<?php

namespace app\assets\basic;


class ThemeForestAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@assets-source' . DS . 'basic';

    public $css = [
        'css/themes/forest.css',
    ];

    public $js = [
    ];

    public $jsOptions = [
    ];

    public $depends = [
    ];
}

