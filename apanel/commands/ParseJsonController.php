<?php

namespace app\commands;

use shared\components\common\core\Helper;
use yii;


class ParseJsonController extends core\Controller
{
    // экшен по умолчанию
    public $defaultAction = 'create';
    // название класса модели
    public $modelClassName;


    // время старта скрипта
    private $startTime;
    // json данные для анализа
    private $jsonData = [];


    // консольные опции
    public function options($actionId)
    {
        return array_merge(parent::options($actionId), [
            'modelClassName',
        ]);
    }

    // инициализация
    public function init()
    {
        $this->startTime = microtime(true);
        $date = (new \DateTime)->setTimeZone((new \DateTimeZone('+0300')))->format('H:i:s');
        $this->text('[ START @ ' . $date . ' ]');
    }

    public function __destruct()
    {
        $this->text('[ Скрипт работал ' . (microtime(true) - $this->startTime) . ' сек. ]');
        $this->stdout("\n");

        // завершаем работу агента
        if (!empty($this->curlAgent)) {
            \curl_close($this->curlAgent);
        }
    }

    public function actionCreate()
    {
        $this->stdout("\n");

        // подключаем поведение текущей модели
        $this->attachModelBehavior();

        // получаем json данные
        $this->getJson();

        // запускаем разрабор данных
        $this->runParsing();

        // показываем статистику работы скрипта
        $this->showStats();

        $this->stdout("\n");
    }


    // подключаем поведение текущей модели
    private function attachModelBehavior()
    {
        $behaviorClass = Helper::resolveModelClass($this->modelClassName . '-behavior');

        // ошибка
        if (!$behaviorClass) {
            $this->returnError('класс поведения ' . $this->modelClassName . ' не найден');
        }

        // аттачим поведение класса
        $this->attachBehavior($this->modelClassName, $behaviorClass);
    }

    // получаем json данные
    private function getJson()
    {
        $pathToFile = yii::getAlias('@app' . DS . IMPORTED_FILES_DIR);

        if (!is_dir($pathToFile)) {
            mkdir($pathToFile);
        }

        foreach (['data', 'parse-json'] as $dir) {
            $pathToFile .= DS . $dir;

            if (!is_dir($pathToFile)) {
                mkdir($pathToFile);
            }
        }

        $file = $pathToFile . DS . $this->cacheFileName;

        if (!file_exists($file)) {

            // или делаем выборку через CURL
            if ($this->isUseCurl()) {
                $jsonData = $this->getJsonDataByCurlAgent();

                // отлавливаем ошибку
                if ($jsonData === false) {
                    $this->error('не удалось получить данные через curl агента');
                }

                \file_put_contents($file, $jsonData);

            // или делаем простой запрос через file_get_contents
            } else {
                $jsonData = \file_get_contents($this->linkToJsonData);
                \file_put_contents($file, $jsonData);
            }

            $this->jsonData = \json_decode($jsonData);
        }

        // получаем данные из кеша
        if (!$this->jsonData) {
            $this->jsonData = \json_decode(\file_get_contents($file));
        }
    }

    // запускаем разрабор данных
    private function runParsing()
    {
        // класс текущей модели
        $modelClass = Helper::resolveModelClass($this->modelClassName);

        if (!$modelClass) {
            $this->returnError('класс модели ' . $this->modelClassName . ' не найден');
        }

        if (!is_array($this->jsonData) || !$this->jsonData) {
            $this->returnError('json данные не найдены');
        }

        // бежим по каждому набору атрибутов объекта
        foreach ($this->jsonData as $attrs) {
            ++$this->stats['rowsRead'];

            if (is_object($attrs)) {
                $attrs = (array) $attrs;
            }

            // attrs обязательно должен быть массивом
            if (!is_array($attrs)) {
                $this->error('attrs ожидался типа array; пропуск.');
                continue;
            }

            if ($this->stats['rowsRead'] % 250 == 0) {
                $this->stdout('- прочитано строк: ' . $this->stats['rowsRead'] . "\n");
            }

            // e($attrs['ICON']);
            // continue;

            // приводим атрибуты в нормальную форму
            $attrs = $this->normalizeAttrs($attrs);
            // делаем превалидацию
            $attrs = $this->prevalidateAttrs($attrs);

            // ищем запись в базе или создаём новую
            if (!$model = $this->findModelById($attrs)) {
                $model = new $modelClass;
            }

            // e($attrs);
            // continue;

            // инициализируем и валидируем модель
            $model->attributes = $attrs;
            $model->validate();

            // e($model->errors);exit;
            // e($model);exit;

            // обработка ошибок
            if ($model->hasErrors()) {
                ++$this->stats['validationErrors'];
                $this->printErrors($model, $this->stats['rowsRead'], $attrs);
                continue;
            }

            try {
                // если новая запись
                if ($model->isNewRecord) {
                    // сохраняем текущую запись в базу
                    if ($model->save(false) === false) {
                        ++$this->stats['saveErrors'];
                    } else {
                        ++$this->stats['inserted'];
                        ++$this->stats['rowsProcessed'];
                    }

                // обновляем текущую запись
                } else {
                    if (($cnt = $model->update(false)) === false) {
                        ++$this->stats['saveErrors'];
                    } else {
                        $this->stats['updated'] = $this->stats['updated'] + $cnt;

                        if ($cnt) {
                            ++$this->stats['rowsProcessed'];
                        }
                    }
                }
            } catch (\yii\db\IntegrityException $e) {
                $this->error($e->getMessage());
            }
        }

        $this->text('Проверка наличия отношений:');

        // по завершению работы с данными обрабатываем все мультисвязки
        $relationClass = Helper::resolveComponentClass('parseJson\relation');
        $result = $relationClass::apply();

        $this->text('- связок добавлено: '  . $result['inserted']);
        $this->text('- связок удалено: '    . $result['deleted']);
        $this->text('- ошибок валидации: '  . $result['validationErrors']);
        $this->text('- ошибок удаления: '   . $result['deleteErrors']);

        // ошибки
        foreach ($result['errorsDetails'] as $fieldName => $errorValue) {
            $text  = '';
            $text .= $this->ansiFormat(':: Ошибка: ', \yii\helpers\Console::FG_RED) . $errorValue[0] . '; ';
            $text .= 'Поле: ' . $fieldName . ';' . "\n";
            $this->stdout($text);
        }
    }
}

