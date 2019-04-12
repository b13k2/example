<?php

namespace shared\models\common\core;


class Login extends \yii\base\Model
{
    public $login;
    public $passwd;
    public $remember_me;


    public function attributeLabels()
    {
        return [
            'login'         => 'Логин',
            'passwd'        => 'Пароль',
            'remember_me'   => 'Запомнить меня',
        ];
    }

    public function rules()
    {
        return [
            // булевы значения
            [
                [
                    'remember_me',
                ], 'boolean',
            ],

            // обязательные к заполнению поля
            [
                [
                    'login', 'passwd',
                ], 'required', 'message' => 'Поле не может быть пустым',
            ],
        ];
    }
}

