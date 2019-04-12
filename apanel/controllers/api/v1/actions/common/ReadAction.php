<?php

namespace app\controllers\api\v1\actions\common;

use shared\components\common\core\Helper;


class ReadAction extends BaseAction
{
    public function run($id=NULL)
    {
        $model = new $this->modelClass;

        // получаем данные модели по заданому ID
        if ($id) {
            $model = $model->findOne($id);

            if (is_null($model)) {
                throw new \yii\web\NotFoundHttpException('Запись с ID ' . $id . ' не найдена!');
            }
        }

        // получаяем поля модели (для формирования шаблона)
        $vList = [];
        $vName = '';

        $required = false;
        $formName = $model->formName();

        foreach ($model->activeValidators as $validator) {
            // e($validator);

            if (is_a($validator, 'yii\validators\StringValidator')) {
                $vName = 'string';
            }

            if (is_a($validator, 'shared\components\common\core\validators\ColorValidator')) {
                $vName = 'color';
            }

            foreach ($validator->attributes as $attrName) {
                // небезопасные атрибуты
                if ($attrName == 'auth_key') {
                    continue;
                }

                $attrFormName = $formName . '-' . $attrName;

                if ($vName) {
                    $vList[$attrFormName]['validator']['name'] = $vName;

                    if ($required) {
                        $vList[$attrFormName]['required'] = $required;
                    }

                    $vList[$attrFormName]['attrLabel'] = $model->getAttributeLabel($attrName);
                    $vList[$attrFormName]['value'] = $model->$attrName;

                    if ($id && $attrName == 'passwd') {
                        $vList[$attrFormName]['value'] = '';
                    }
                }
            }

            $vName = '';
            $required = false;
        }

        return $vList;
    }
}

