<?php

namespace app\assets\basic\page;


class LoginAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@assets-source' . DS . 'basic';

    public $css = [
    ];

    public $js = [
        'js/pages/login.js',
    ];

    public $jsOptions = [
    ];

    public $depends = [
    ];
}

