<?php

namespace app\controllers\web;


class UserController extends core\BaseBehaviorController
{
    public const MODULE_ID = 2;


    public function getWordforms()
    {
        return (object) [
            'singular' => 'Новый пользователь',
        ];
    }
}

