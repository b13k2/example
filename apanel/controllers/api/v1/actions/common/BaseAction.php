<?php

namespace app\controllers\api\v1\actions\common;

use shared\components\common\core\Helper;


class BaseAction extends \yii\rest\Action
{
    public function init()
    {
        // получаем рабочую модель через контроллер
        $controllerClassName = str_replace('Controller', '', Helper::getControllerClassName($this->controller));
        // e($controllerClassName);exit;

        $this->modelClass = Helper::resolveModelClass($controllerClassName);
        // e($this->modelClass);exit;

        parent::init();
    }
}

