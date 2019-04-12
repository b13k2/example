<?php

namespace shared\components\common\core\filters;

use yii;


class Authentication extends \yii\base\ActionFilter
{
    // своя логика проверки клиента на аутентификацию
    public function beforeAction($action)
    {
        if (yii::$app->user->isGuest) {
            yii::$app->user->loginRequired();
            return false;
        } else {
            return parent::beforeAction($action);
        }
    }
}

