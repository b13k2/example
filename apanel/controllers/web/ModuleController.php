<?php

namespace app\controllers\web;


class ModuleController extends core\BaseBehaviorController
{
    public const MODULE_ID = 3;


    public function getWordforms()
    {
        return (object) [
            'singular' => 'Новый модуль',
        ];
    }
}

