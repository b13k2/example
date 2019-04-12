<?php

namespace shared\components\common\core\validators;

use yii;


class ColorValidator extends \yii\validators\Validator
{
    // цветовая модель
    public $model;

    // поддерживаемые цветовые модели
    private $supportedModels = ['hex'];


    public function init()
    {
        parent::init();

        if (!in_array($this->model, $this->supportedModels)) {
            throw new \yii\web\ServerErrorHttpException(
                'Задана цветовая модель ' . $this->model . ', которая не поддерживается валидатором ' . get_class($this));
        }
    }

    public function validateAttribute($model, $attr)
    {
        if ($this->model == 'hex' && !preg_match('/^\#([a-z0-9]{3}$|[a-z0-9]{6}$)/i', $model->$attr)) {
            $model->addError($attr, 'Цветовая модель не соответствует HEX');
        }
    }
}

