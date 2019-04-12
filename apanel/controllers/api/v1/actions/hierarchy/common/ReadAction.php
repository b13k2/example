<?php

namespace app\controllers\api\v1\actions\hierarchy\common;

use shared\components\common\core\Helper;
use yii;


class ReadAction extends BaseAction
{
    public function run($pid=0)
    {
        if (!$this->modelClass) {
            throw new \yii\web\ServerErrorHttpException('Рабочая модель класса ' . get_class($this->controller) . ' не найдена');
        }

        // каждый класс, поддерживающий вывод через виджет hierarchy,
        // должен описать статический метод hierarchyMap, чтобы можно было конвертировать поля по правилам виджета
        if (!method_exists($this->modelClass, 'hierarchyMap')) {
            throw new \yii\web\ServerErrorHttpException('Укажите hierarchyMap в классе ' . $this->modelClass);
        }

        // данные для возврата
        $models = [];
        // рабочий модуль модели
        $module['str_id'] = $this->controller->controllerModule->toArray()['str_id'];

        $schema  = $this->modelClass::getTableSchema();
        // строка запроса на получение данных
        $command = 'SELECT * FROM `' . ($this->modelClass::tableName()) . '`';

        // проверяем поддержку вложенных элементов
        if  ($schema->getColumn('pid') && $pid) {
            // добавляем родителя
            $command .= ' WHERE `pid` = ' . intval($pid);
        }

        $command .= ' ORDER BY `SORTING` ASC';

        $i = 1;
        // обрабатываем результирующий набор
        foreach (yii::$app->db->createCommand($command)->query() as $row) {
            foreach ($row as $oldKey => $value) {
                // общие небезопасные поля
                if ($oldKey == 'passwd' || $oldKey == 'auth_key') {
                    continue;
                }

                // конвертируем ключи полей
                $newKey = array_key_exists($oldKey, $this->modelClass::hierarchyMap())
                    ? ($this->modelClass::hierarchyMap()[$oldKey])
                    : $oldKey;

                $models[$i][$newKey]  = $row[$oldKey];
                $models[$i]['module'] = $module;

                $models[$i]['nestedCnt'] = 0;
            }

            ++$i;
        }

        return $models;
    }
}

