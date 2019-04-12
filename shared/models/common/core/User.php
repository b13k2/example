<?php

namespace shared\models\common\core;

use yii;
use yii\helpers\ArrayHelper;


class User extends BaseModel
{
    public const SCENARIO_CREATE = 'create';
    public const SCENARIO_UPDATE = 'update';


    public static function tableName()
    {
        return 'user';
    }

    // метод показывает какие поля и как мы отображаем в виджете hierarchy
    public static function hierarchyMap()
    {
        return [
            'login' => 'title',
        ];
    }

    public function init()
    {
        $class = static::class;

        // устанавливаем сценарий
        $this->on($class::EVENT_BEFORE_VALIDATE, function($e) {
            $this->isNewRecord
                ? $this->setScenario(self::SCENARIO_CREATE)
                : $this->setScenario(self::SCENARIO_UPDATE);

            $this->auth_key = yii::$app->getSecurity()->generateRandomString();

            // редактируем пользователя
            if (!$this->isNewRecord && !trim($this->passwd)) {
                // не перезаписываем пароль, если он не задан
                unset($this->passwd);
            }
        });

        // перед вставкой в базу хешируем пароль
        $this->on($class::EVENT_BEFORE_INSERT, function($e) {
            $this->passwd = yii::$app->security->generatePasswordHash($this->passwd);
        });

        // перед обновлением хешируем пароль
        $this->on($class::EVENT_BEFORE_UPDATE, function($e) {
            if (!empty($this->passwd)) {
                $this->passwd = yii::$app->security->generatePasswordHash($this->passwd);
            }
        });

        parent::init();
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge([
            'login'     => 'Логин',
            'passwd'    => 'Пароль',
            'auth_key'  => 'Ключ аутентификации',
        ], parent::attributeLabels());
    }

    public function rules()
    {
        return ArrayHelper::merge([
            // строковые значения
            [
                [
                    'login', 'passwd', 'auth_key',
                ], 'trim', 'skipOnArray' => true, 'skipOnEmpty' => true,
            ],

            [
                [
                    'login',
                ], 'string', 'max' => 30,
            ],

            [
                [
                    'passwd',
                ], 'string', 'max' => 60,
            ],

            [
                [
                    'auth_key',
                ], 'string', 'max' => 32,
            ],

            // уникальные значения
            [
                [
                    'login',
                ], 'unique',
            ],

            // обязательные к заполнению поля
            [
                [
                    'login', 'passwd',
                ], 'required', 'on' => self::SCENARIO_CREATE,
            ],

            [
                [
                    'login',
                ], 'required', 'on' => self::SCENARIO_UPDATE,
            ],
        ], parent::rules());
    }
}

