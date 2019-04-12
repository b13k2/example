<?php

namespace app\commands\core;

use shared\components\common\core\Helper;


abstract class JsonBehavior extends \yii\base\Behavior
{
    // флаг, определяющий признак использования curl для запросов
    public const USE_CURL = false;


    // линк на json данные, которые мы должны получить
    private $linkToJsonData;
    // название файла с кешем
    private $cacheFileName;


    // метод должен возвращать линк на json данные;
    // например, 'https://site.ru/api/v1/user'
    abstract public function getLinkToJsonData();

    // метод должен возвращать название файла с кешем;
    // например, 'user'
    abstract public function getCacheFileName();

    // метод описывавет массив для последующего маппинга ключей "объекта json -> объект model";
    // регистр не имеет значения;
    // например,
    // [
    //   'json-field-name' => 'your-model-field-name',
    //   'user_profile'    => 'user_profile_id',
    // ]
    abstract public function getKeysMap();

    // метод возвращает целочисленный список опциональных полей
    public function getOptionalKeys()
    {
        return [];
    }

    // метод описывает массив заголовков для CURL (опционально)
    public function getCurlHeaders()
    {
        return [];
    }


    // метод возвращает признак: нужно ли использовать CURL для запроса
    public function isUseCurl() : bool
    {
        if (static::USE_CURL && empty($this->curlAgent)) {
            // скрыто производим инициализацию curl агента
            $this->curlAgent = \curl_init();
        }

        return static::USE_CURL;
    }

    // метод фильтрует атрибуты и заменяет json ключи на model ключи,
    // которые соответствуют именам полей в БД
    public function normalizeAttrs($attrs) : array
    {
        // приводим ключи к нижнему регистру
        $attrs = \array_change_key_case($attrs);

        $filteredAttrs = [];
        foreach ($attrs as $fieldName => $fieldValue) {
            if (!\array_key_exists($fieldName, $this->keysMap)) {
                continue;
            }

            $filteredAttrs[$this->keysMap[$fieldName]] = $fieldValue;
        }

        // заданы опциональные поля
        if ($oKeys = $this->getOptionalKeys()) {
            foreach ($oKeys as $fieldName) {
                $filteredAttrs[$fieldName] = NULL;
            }
        }

        return $filteredAttrs;
    }

    // метод делает превалидацию значений полей: чистит, фильтрует, преобразует
    public function prevalidateAttrs($attrs)
    {
        foreach ($attrs as $attrName => & $attrValue) {
            $attrName = $this->resolveAttrName($attrName);
            $method   = 'prevalidate' . $attrName;

            // передаём управление
            if ($this->hasMethod($method)) {
                $attrValue = $this->$method($attrValue, $attrs);
            }
        }

        return $attrs;
    }

    // метод получает модель по заданному ID из БД и возвращает её
    public function findModelById($attrs)
    {
        if (!isset($attrs['id'])) {
            return NULL;
        }

        $modelClass = Helper::resolveModelClass($this->owner->modelClassName);
        if (!$modelClass) {
            return NULL;
        }

        // по умолчанию поиск ведём по ID
        return $modelClass::find()->where([
            'id' => $attrs['id'],
        ])->one();
    }

    // инициализация curl агента
    public function setCurlAgent($agent)
    {
        $this->curlAgent = $agent;

        // запрашиваемый url
        \curl_setopt($this->curlAgent, CURLOPT_URL, $this->getLinkToJsonData());
        // не выводим данные напрямую в браузер
        \curl_setopt($this->curlAgent, CURLOPT_RETURNTRANSFER, true);

        $headers = [];
        foreach ($this->getCurlHeaders() as $name => $value) {
            $headers[] = $name . ': ' . $value;
        }

        if ($headers) {
            // опциональные заголовки
            \curl_setopt($this->curlAgent, CURLOPT_HTTPHEADER, $headers);
        }
    }

    // метод получает json данные по средством curl агента
    public function getJsonDataByCurlAgent()
    {
        return \curl_exec($this->curlAgent);
    }


    // метод преобразует название атрибута по правилам Yii
    private function resolveAttrName($name)
    {
        $name = \array_map(function($name) {
            return \ucfirst($name);
        }, \explode('_', $name));

        return \implode('', $name);
    }
}

