<?php

namespace app\controllers\api\v1\core;

use shared\components\common\core\Helper;


class MainController extends \yii\rest\Controller
{
    // ID модуля в системе
    public const MODULE_ID = 0;

    public $defaultAction = 'read';

    // модуль, которому принадлежит контроллер
    private $controllerModule;


    public function behaviors()
    {
        $behaviors = parent::behaviors();
        // аутентификация пользователя
        $behaviors['authenticator'] = \yii\filters\auth\HttpBearerAuth::className();

        return $behaviors;
    }

    // информация о модуле, что связан с контроллером
    public function getControllerModule()
    {
        if (is_null($this->controllerModule)) {
            $moduleClassName = Helper::resolveModelClass('module');
            $this->controllerModule = $moduleClassName::findOne(static::MODULE_ID);
        }

        if (is_null($this->controllerModule)) {
            throw new \yii\web\ServerErrorHttpException('ID модуля контроллера задан неверно ' . get_class($this));
        }

        return $this->controllerModule;
    }

    public function actions()
    {
        $actions = [
            'read'      => Helper::resolveApiAction($this, 'read'),
            'create'    => Helper::resolveApiAction($this, 'create'),
            'update'    => Helper::resolveApiAction($this, 'update'),
            'delete'    => Helper::resolveApiAction($this, 'delete'),
        ];

        return $actions;
    }
}

