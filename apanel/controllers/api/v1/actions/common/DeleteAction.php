<?php

namespace app\controllers\api\v1\actions\common;

use yii;


class DeleteAction extends BaseAction
{
    public function run($id)
    {
        if (!$model = $this->modelClass::findOne($id)) {
            throw new \yii\web\NotFoundHttpException('Запись с ID = ' . $id . ' не найдена!');
        }

        if ($model->delete() === false) {
            throw new \yii\web\ServerErrorHttpException('Возникла ошибка при попытке удалить модель!');
        }

        return $model;
    }
}

