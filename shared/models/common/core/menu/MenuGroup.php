<?php

namespace shared\models\common\core\menu;

use shared\components\common\core\Helper;
use yii\helpers\ArrayHelper;


class MenuGroup extends \shared\models\common\core\BaseModel
{
    public static function tableName()
    {
        return 'menu_group';
    }

    // метод показывает какие поля и как мы отображаем в виджете hierarchy
    public static function hierarchyMap()
    {
        return [
            'name' => 'title',
        ];
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge([
            'name'      => 'Название',
            'str_id'    => 'Строковой ID',
        ], parent::attributeLabels());
    }

    public function rules()
    {
        return ArrayHelper::merge([
            // строковые значения
            [
                [
                    'name', 'str_id',
                ], 'trim', 'skipOnArray' => true, 'skipOnEmpty' => true,
            ],

            [
                [
                    'name', 'str_id',
                ], 'string', 'max' => 45,
            ],

            // уникальные значения
            [
                [
                    'str_id',
                ], 'unique',
            ],

            // обязательные к заполнению поля
            [
                [
                    'name', 'str_id',
                ], 'required',
            ],
        ], parent::rules());
    }

    // связка с menu-item
    public function getItems()
    {
        return $this->hasMany(Helper::resolveModelClass('menu\menu-item'), [
            'menu_group_id' => 'id',
        ]);
    }
}

