<?php

namespace app\controllers\web;


class RegionController extends core\BaseBehaviorController
{
    public const MODULE_ID = 4;


    public function getWordforms()
    {
        return (object) [
            'singular' => 'Новый регион',
        ];
    }
}

