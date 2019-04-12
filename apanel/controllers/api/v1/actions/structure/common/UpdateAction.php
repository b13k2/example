<?php

namespace app\controllers\api\v1\actions\structure\common;

use yii;


class UpdateAction extends BaseAction
{
    public function run()
    {
        $data = yii::$app->request->getBodyParam('structure', []);
        // e($data);exit;
        // e($this->modelClass);exit;

        if ($data) {
            if (!$this->modelClass) {
                throw new \yii\web\ServerErrorHttpException('Модель не найдена.');
            }

            $this->updateStructure($data);
            return [];
        }

        throw new \yii\web\ServerErrorHttpException('Данные для обработки не найдены.');
    }


    private function updateStructure($data)
    {
        $modelClass = $this->modelClass;
        // e($data);exit;

        foreach ($data as $item) {
            // для всех дочeрних элементов текущий элемент будет родителем
            // $pid = (!empty($item['children']) && is_array($item['children']))
                // ? (int) $item['id']
                // : 0;

            $pid = 0;

            // ищем заданный элемент
            if ($res = $modelClass::findOne((int) $item['id'])) {
                // корень
                // $res->pid = 0;
                $res->sorting = (int) $item['sorting'];

                //if ($res->update(true, ['pid', 'sorting']) !== false && $pid) {
                if ($res->update(true, ['sorting']) !== false && $pid) {
                    // ДОДЕЛАТЬ! есть дочерние элементы
                    // $this->updateSubStructure($pid, (array) $item['children']);
                }
            }
        }
    }

    // ДОДЕЛАТЬ! обновляем вложенные элементы
    /*
    protected function updateSubStructure($pid, $children)
    {
        $modelName = $this->modelName;

        foreach ($children as $child) {
            // обновляем родителя
            if ($res = $modelName::findOne((int) $child['id'])) {
                $res->pid = $pid;
                $res->sorting = (int) $child['sorting'];

                if ($res->update(true, ['pid', 'sorting']) !== false && isset($child['children']) && is_array($child['children']) && $child['children']) {
                    // есть дочерние элементы
                    $this->updateSubStructure((int) $child['id'], (array) $child['children']);
                }
            }
        }
    }
    */
}

