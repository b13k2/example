<?php

namespace shared\models\common\core\menu;

use shared\components\common\core\Helper;
use yii\helpers\ArrayHelper;


class MenuItem extends \shared\models\common\core\BaseModel
{
    public function attributeLabels()
    {
        return ArrayHelper::merge([
            'pid'           => 'Родитель',
            'menu_group_id' => 'Группа',
            'name'          => 'Название',
            'module_id'     => 'Модуль',
        ], parent::attributeLabels());
    }

    public function rules()
    {
        return ArrayHelper::merge([
            // строковые значения
            [
                [
                    'name',
                ], 'trim', 'skipOnArray' => true, 'skipOnEmpty' => true,
            ],

            [
                [
                    'name',
                ], 'string', 'max' => 45,
            ],

            // числа smallint
            [
                'pid',
                'integer', 'integerOnly' => true,  'min' => 0, 'max' => 65535,
            ],

            [
                'menu_group_id', 'module_id',
                'integer', 'integerOnly' => true,  'min' => 1, 'max' => 65535,
            ],

            // обязательные к заполнению поля
            [
                [
                    'name',
                ], 'required',
            ],
        ], parent::rules());
    }

    // связка с module
    public function getModule()
    {
        return $this->hasOne(Helper::resolveModelClass('module'), [
            'id' => 'module_id',
        ]);
    }
}

