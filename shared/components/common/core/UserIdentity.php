<?php

namespace shared\components\common\core;


class UserIdentity extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    public static function findIdentity($login)
    {
        return static::findOne([
            'login' => (string) $login,
        ]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne([
            'auth_key' => (string) $token,
        ]);
    }

    public function getId()
    {
        return $this->login;
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }


    public static function tableName()
    {
        return 'user';
    }

    public function rules()
    {
        return [
            // строковые значения
            [
                [
                    'auth_key',
                ], 'trim', 'skipOnArray' => true, 'skipOnEmpty' => true,
            ],

            [
                [
                    'auth_key',
                ], 'string', 'max' => 32,
            ],

            // обязательные к заполнению поля
            [
                [
                    'auth_key',
                ], 'required',
            ],
        ];
    }
}

