<?php

namespace shared\models\common\core;


class BaseModel extends \yii\db\ActiveRecord
{
    public function init()
    {
        $class = static::class;

        // после вставки в базу обновляем поле sorting
        $this->on($class::EVENT_AFTER_INSERT, function($e) {
            if ($this->sorting == 0) {
                $this->sorting = $this->id;
                $this->update(true, ['sorting']);
            }
        });

        parent::init();
    }

    public function attributeLabels()
    {
        return [
            'id'        => 'ID',
            'updated'   => 'Последнее обновление',
            'created'   => 'Дата создания',
            'sorting'   => 'Порядок сортировки',
        ];
    }

    public function rules()
    {
        return [
            // дата
            [
                [
                    'updated', 'created',
                ], 'datetime', 'format' => 'php:Y-m-d H:i:s', 'skipOnEmpty' => true, 'skipOnError' => true,
            ],

            // числа int
            [
                'sorting',
                'integer', 'integerOnly' => true,  'min' => 0, 'max' => 4294967295,
            ],

            // значения по умолчанию
            [
                'sorting',
                'default', 'value' => 0,
            ],
        ];
    }
}

