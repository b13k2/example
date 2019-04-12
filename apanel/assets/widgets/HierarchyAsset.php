<?php

namespace app\assets\widgets;


class HierarchyAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@assets-source' . DS . 'widgets';

    public $css = [
    ];

    public $js = [
        'js/sortable-dnd-tree.js',
        'js/hierarchy.js'
    ];

    public $jsOptions = [
    ];

    public $depends = [
        'app\assets\widgets\NestableAsset',
    ];
}

