<?php

namespace widgets;

use shared\components\common\core\Helper;
use yii;


class HierarchyWidget extends \yii\base\Widget
{
    public $moduleStrId;


    public function init()
    {
        $asset = Helper::getAssetClass('widgets\hierarchy');
        $asset::register(yii::$app->view);

        if (is_null($this->moduleStrId)) {
            throw new \yii\web\ServerErrorHttpException('Укажите строковой ID модуля в виджете ' . get_class($this));
        }
    }

    public function run()
    {
        return $this->render('hierarchy', [
            'linkToApi' => 'hierarchy/' . $this->moduleStrId,
        ]);
    }
}

