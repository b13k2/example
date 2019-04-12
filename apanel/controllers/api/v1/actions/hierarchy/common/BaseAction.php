<?php

namespace app\controllers\api\v1\actions\hierarchy\common;

use shared\components\common\core\Helper;


class BaseAction extends \yii\rest\Action
{
    public function init()
    {
        // получаем рабочую модель через контроллер
        $controllerClassName = str_replace('Controller', '', Helper::getControllerClassName($this->controller));
        // e($controllerClassName);exit;

        // префикс hierarchy удаляем
        $modeClassName = str_replace('hierarchy\\', '', $controllerClassName);
        // e($modeClassName);exit;

        $this->modelClass = Helper::resolveModelClass($modeClassName);
        // e($this->modelClass);exit;

        parent::init();
    }
}

