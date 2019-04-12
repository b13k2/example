<?php

namespace app\controllers\web\core;

use shared\components\common\core\Helper;
use yii;


class MainController extends \yii\web\Controller
{
    // ID модуля в системе
    public const MODULE_ID = 0;

    public $layout = 'web-index';
    public $defaultAction = 'show-content';

    // модуль, которому принадлежит контроллер
    private $controllerModule;


    public function init()
    {
        // ядро
        $this->registerCore();
        // стили
        $this->registerStylesheets();
        // тема
        $this->registerTheme();
        // базовые скрипты
        $this->registerJs();

        // проверка на наличие словоформ;
        // каждый контроллер должен описать словоформы названия своего модуля;
        // return (object) ['единственное_число' => '', ...];
        if (empty($this->wordforms)) {
            throw new \yii\web\ServerErrorHttpException(
                'Задайте словоформы для названия модуля контроллера ' . get_class($this));
        }
    }

    public function behaviors()
    {
        return [
            // аутентификация пользователя
            'authentication' => Helper::resolveComponentClass('filters\authentication'),
        ];
    }

    // информация о модуле, что связан с контроллером
    public function getControllerModule()
    {
        if (is_null($this->controllerModule)) {
            $moduleClassName = Helper::resolveModelClass('module');
            $this->controllerModule = $moduleClassName::findOne(static::MODULE_ID);
        }

        if (is_null($this->controllerModule)) {
            throw new \yii\web\ServerErrorHttpException('ID модуля контроллера задан неверно ' . get_class($this));
        }

        return $this->controllerModule;
    }


    // метод регистрирует файлы ядра
    protected function registerCore()
    {
        Helper::getAssetClass('core')::register(yii::$app->view);

        yii::$app->view->registerJs(
            "\nvar core_apanelAlias       = '" . APANEL_WEB_ALIAS     . "';\n", yii::$app->view::POS_HEAD);

        yii::$app->view->registerJs(
            "\nvar core_cookieAuthKeyName = '" . COOKIE_AUTH_KEY_NAME . "';\n", yii::$app->view::POS_HEAD);
    }

    // метод регистрирует базовые стили
    protected function registerStylesheets()
    {
        Helper::getAssetClass('basic\stylesheet')::register(yii::$app->view);
    }

    // метод регистрирует тему по умолчанию
    protected function registerTheme()
    {
        Helper::getAssetClass('basic\theme')::register(yii::$app->view);
    }

    // метод регистрирует базовые js скрипты
    protected function registerJs()
    {
        Helper::getAssetClass('basic\js')::register(yii::$app->view);
    }

    // ищем шаблон отображения контроллера
    protected function resolveView($tplName)
    {
        $path = $this->getViewPath();
        // e($path);exit;
        $file = $path . DS . $tplName . '.php';

        if (\file_exists($file)) {
            return $tplName;
        }

        return '@app/views/' . DEFAULT_TPLS_DIR . '/' . $tplName;
    }
}

