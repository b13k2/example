<?php

namespace widgets;

use shared\components\common\core\Helper;
use yii;


class MenuWidget extends \yii\base\Widget
{
    // строковой ID группы меню
    public $str_id;
    private $menuGroupClass;


    public function init()
    {
        parent::init();

        if (!$this->str_id) {
            throw new \yii\web\ServerErrorHttpException('Укажите str_id класса ' . get_class($this));
        }

        $this->str_id = (string) $this->str_id;
        $this->menuGroupClass = Helper::resolveModelClass('menu\menu-group');

        if (!$this->menuGroupClass) {
            throw new \yii\web\ServerErrorHttpException('Модель menu-group класса ' . get_class($this) . ' не найдена');
        }
    }

    public function run()
    {
        $moduleFromUrl = \trim(\substr(yii::$app->request->url, \strrpos(yii::$app->request->url, '/')), '/');

        $menuGroup = $this->menuGroupClass::find()
            ->with('items')
            ->where(['str_id' => $this->str_id])
            ->one();

        // группа меню не найдена
        if (!$menuGroup || !$menuGroup->items) {
            return '';
        }

        $itemTpl    = Helper::resolveWidgetTpl('menu' . DS . $this->str_id . DS . 'item');
        $wrapperTpl = Helper::resolveWidgetTpl('menu' . DS . $this->str_id . DS . 'wrapper');

        $content = '';
        foreach ($menuGroup->items as $model) {
            $content .= $this->render($itemTpl, [
                'model'     => $model,
                'active'    => $moduleFromUrl == $model->module->str_id ? 'active' : '',
            ]);
        }

        return $this->render($wrapperTpl, [
            'content' => $content
        ]);
    }
}

