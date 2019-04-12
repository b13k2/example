<?php

namespace app\models\tpk;

use yii\helpers\ArrayHelper;


class Region extends \shared\models\common\core\BaseModel
{
    public static function tableName()
    {
        return 'region';
    }

    // метод показывает какие поля и как мы отображаем в виджете hierarchy
    public static function hierarchyMap()
    {
        return [
            'public_name' => 'title',
        ];
    }

    public function rules()
    {
        return ArrayHelper::merge([
            // числа
            [
                [
                    'id',
                ], 'integer', 'integerOnly' => true,  'min' => 1, 'max' => 4294967295,
            ],

            // строковые значения
            [
                [
                    'name', 'public_name',
                ], 'trim', 'skipOnArray' => true, 'skipOnEmpty' => true,
            ],

            [
                [
                    'name', 'public_name',
                ], 'string', 'max' => 50,
            ],

            // значения по умолчанию
            [
                [
                    'latitude', 'longitude',
                ], 'default', 'value' => 0,
            ],

            // обязательные к заполнению поля
            [
                [
                    'id', 'name', 'public_name',
                ], 'required',
            ],
        ], parent::rules());
    }
}

