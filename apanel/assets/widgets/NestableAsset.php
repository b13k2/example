<?php

namespace app\assets\widgets;


class NestableAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@assets-source' . DS . 'widgets';

    public $css = [
        YII_DEBUG ? 'plugins/jquery.nestable.css' : 'plugins/jquery.nestable.min.css',
    ];

    public $js = [
        YII_DEBUG ? 'plugins/jquery.nestable.js' : 'plugins/jquery.nestable.min.js',
    ];

    public $jsOptions = [
    ];

    public $depends = [
    ];
}

