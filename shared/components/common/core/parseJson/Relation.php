<?php

namespace shared\components\common\core\parseJson;

use shared\components\common\core\Helper;
use yii\db\Expression;


// класс помощник для json парсера: удаляет и добавляет данные в таблицу отношений;
// например, есть таблица А и Б, а множественные связи хранятся в С.
// данный хелпер работает с таблицей С: умеет добавлять новые связи и удалять уже устаревшие
class Relation
{
    // данные, с которыми связываем источник
    public static $relatedTo = [];

    // объект текущего класса
    private static $classObject;

    // класс, отвечающий за работу со связками
    private static $relationClass;
    // информация о маппинге полей [json -> таблица] источника
    private static $sourceFieldMap;
    // маппинг полей "с чем связываем источник" json -> table
    private static $relatedToFieldMap;

    // список сформированных связей для последующей обработки
    private static $relationList = [];


    private function __construct() {}
    private function __wakeup() {}
    private function __clone() {}


    // инициализация
    public static function init()
    {
        if (is_null(self::$classObject)) {
            self::$classObject = new self;
        }

        return self::$classObject;
    }

    // устанавливаем класс, отвечающий за связки
    public static function setRelationClass($relationClassName)
    {
        if (is_null(self::$relationClass)) {
            self::$relationClass = Helper::resolveModelClass($relationClassName);
        }
    }

    // маппинг полей источника json -> table
    public static function setSourceFieldMap($jsonFieldName, $tableFieldName)
    {
        if (is_null(self::$sourceFieldMap)) {
            self::$sourceFieldMap = [
                'json'  => $jsonFieldName,
                'table' => $tableFieldName,
            ];
        }
    }

    // маппинг полей "с чем связываем источник" json -> table
    public static function setRelatedToFieldMap($jsonFieldName, $tableFieldName)
    {
        if (is_null(self::$relatedToFieldMap)) {
            self::$relatedToFieldMap = [
                'json'  => $jsonFieldName,
                'table' => $tableFieldName,
            ];
        }
    }

    // сохраняем связки в кеш
    public static function toCache($attrs)
    {
        if (!is_array(self::$relatedTo)) {
            return;
        }

        foreach (self::$relatedTo as $id) {
            self::$relationList[] = $attrs[self::$sourceFieldMap['json']] . ' ' . $id;
        }
    }

    // применяем все связки (добавляем/удаляем)
    public static function apply()
    {
        $result = [
            // связок добавлено
            'inserted'          => 0,
            // связок удалено
            'deleted'           => 0,

            // ошибок валидации
            'validationErrors'  => 0,
            // ошибок удаления
            'deleteErrors'      => 0,

            // детали ошибок
            'errorsDetails'     => [],
        ];

        if (!self::$relationClass) {
            return $result;
        }

        $expr = new Expression(
            'CONCAT(' . self::$sourceFieldMap['table'] . ', " ", ' . self::$relatedToFieldMap['table'] . ') as hash');

        $tableData = self::$relationClass::find()
            ->select($expr)
            ->asArray()
            ->indexBy('hash')
            ->all();

        $tableData = array_keys($tableData);

        $toAdd    = array_diff(self::$relationList, $tableData);
        $toDelete = array_diff($tableData, self::$relationList);

        // добавляем
        foreach ($toAdd as $data) {            
            $data = explode(' ', $data);

            $relClassObj = new self::$relationClass;
            $relClassObj->attributes = [
                self::$sourceFieldMap['table'] => $data[0],
                self::$relatedToFieldMap['table'] => $data[1],
            ];

            $relClassObj->save();

            if ($relClassObj->hasErrors()) {
                $result['errorsDetails'] = array_merge($result['errorsDetails'], $relClassObj->errors);
            } else {
                ++$result['inserted'];
            }
        }

        // удаляем
        foreach ($toDelete as $data) {
            $data = explode(' ', $data);

            $data = (self::$relationClass::find())->where([
                self::$sourceFieldMap['table'] => $data[0],
                self::$relatedToFieldMap['table'] => $data[1],  
            ])->all();

            foreach ($data as $relClassObj) {
                if (($cnt = $relClassObj->delete()) === false) {
                    ++$result['deleteErrors'];
                } else {
                    $result['deleted'] += $cnt;
                }
            }
        }

        return $result;
    }
}

