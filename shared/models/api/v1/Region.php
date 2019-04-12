<?php

namespace shared\models\api\v1;

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

    public function attributeLabels()
    {
        return ArrayHelper::merge([
            'name'          => 'Название региона',
            'public_name'   => 'Название для вывода в списке пользователя',
        ], parent::attributeLabels());
    }

    public function rules()
    {
        return ArrayHelper::merge([
            // числа с плавающей точкой
            [
                [
                    'latitude', 'longitude',
                ], 'double',
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

            // обязательные к заполнению поля
            [
                [
                    'name', 'public_name',
                ], 'required',
            ],
        ], parent::rules());
    }
}

