<?php

namespace app\controllers\web;


class MenuController extends core\BaseBehaviorController
{
    public const MODULE_ID = 1;


    public function getWordforms()
    {
        return (object) [
            'singular' => 'Новая группа меню',
        ];
    }
}

