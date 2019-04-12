<?php

namespace shared\models\common\core;

use yii\helpers\ArrayHelper;


class Module extends BaseModel
{
    public static function tableName()
    {
        return 'module';
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
            'icon'      => 'Класс иконки',
        ], parent::attributeLabels());
    }

    public function rules()
    {
        return ArrayHelper::merge([
            // строковые значения
            [
                [
                    'name', 'str_id', 'icon',
                ], 'trim', 'skipOnArray' => true, 'skipOnEmpty' => true,
            ],

            [
                [
                    'name', 'str_id', 'icon',
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

    // шаблон с иконкой
    public function getIconTpl()
    {
        return $this->icon ? '<i class="' . $this->icon . '"></i>' : '';
    }
}

