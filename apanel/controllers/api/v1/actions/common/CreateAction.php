<?php

namespace app\controllers\api\v1\actions\common;

use yii;


class CreateAction extends BaseAction
{
    public function run()
    {
        $model = new $this->modelClass;
        $model->load(yii::$app->request->post());

        if ($model->validate()) {
            if ($model->save(false) === false) {
                throw new \yii\web\ServerErrorHttpException('Возникла ошибка при попытке сохранить модель!');
            }

            return $model;
        }

        // список ошибок
        $errorsList = [];
        $formName   = $model->formName();

        foreach ($model->errors as $attrName => $error) {
            // берем только первую ошибку
            $errorsList[$formName . '-' . $attrName] = $error[0];
        }

        yii::$app->response->setStatusCode(422);
        return $errorsList;
    }
}

