<?php

namespace shared\components\common\core;

use yii;


class Helper
{
    // директория с ресурсами
    public const ASSETS_DIR     = 'assets';
    // директория с моделями
    public const MODELS_DIR     = 'models';
    // директория с виджетами
    public const WIDGETS_DIR    = 'widgets';
    // директория с экшенами (для API)
    public const ACTIONS_DIR    = 'actions';
    // директория с компонентами
    public const COMPONENTS_DIR = 'components';

    // public static $data;


    // возвращаем класс с ресурсом по его названию
    public static function getAssetClass($name)
    {
        $name = self::resolveName($name) . 'Asset';
        return 'app\\' . self::ASSETS_DIR . '\\' . $name;
    }

    // любые названия классов формируем по стандартам Yii
    public static function resolveName($name)
    {
        $nameParts  = explode('\\', $name);
        $className  = array_pop($nameParts);

        // по правилам Yii: разделителем служит тире,
        // а каждое слово д.б. с заглавной буквы
        $parts = array_map(function($word) {
            return ucfirst($word);
        }, explode('-', $className));

        $nameParts[] = implode('', $parts);

        return implode('\\', $nameParts);
    }

    // получаем расположение класса модели по её названию
    public static function resolveModelClass($className) : string
    {
        return self::resolveClass($className, self::MODELS_DIR);
    }

    // получаем путь к нужному шаблону
    public static function resolveWidgetTpl($tplName) : string
    {
        return self::resolveTpl($tplName, self::WIDGETS_DIR);
    }

    // находим и возвращаем путь к экшену с учетом приоритета поиска
    public static function resolveApiAction($controller, $actionName)
    {
        $controllerClassName = self::firstToLower(\str_ireplace('controller', '', self::getControllerClassName($controller)));
        $apiAction = \ucfirst($actionName) . 'Action';

        // ищем частный экшен
        $path = yii::$app->controllerNamespace . DS . API . DS . 'v' . API_VERSION . DS . self::ACTIONS_DIR . DS . $controllerClassName;
        $path = \str_replace('\\', '/', $path);

        if (\file_exists(yii::getAlias('@' . $path . DS . $apiAction . '.php'))) {
            return \str_replace('/', '\\', $path . DS . $apiAction);
        }

        $nameParts = \explode('\\', $controllerClassName);
        $groupName = \array_shift($nameParts);

        // ищем общую группу
        $path = yii::$app->controllerNamespace . DS . API . DS . 'v' . API_VERSION . DS . self::ACTIONS_DIR . DS . $groupName . DS . 'common';
        $path = \str_replace('\\', '/', $path);
        if (\file_exists(yii::getAlias('@' . $path . DS . $apiAction . '.php'))) {
            return \str_replace('/', '\\', $path . DS . $apiAction);
        }

        // ищем дефолтный вариант
        $path = yii::$app->controllerNamespace . DS . API . DS . 'v' . API_VERSION . DS . self::ACTIONS_DIR . DS . 'common';
        $path = \str_replace('\\', '/', $path);

        if (\file_exists(yii::getAlias('@' . $path . DS . $apiAction . '.php'))) {
            return \str_replace('/', '\\', $path . DS . $apiAction);
        }

        // throw new \yii\web\ServerErrorHttpException('Не удалось найти экшен ' . $actionName . ' для контроллера ' . $controllerClassName);
    }

    // первую букву названия класса приводим к нижнему регистру
    public static function firstToLower($name)
    {
        $nameParts  = \explode('\\', $name);
        $className  = \lcfirst(\array_pop($nameParts));

        $nameParts[] = $className;

        return \implode('\\', $nameParts);
    }

    // получаем название класса контроллера
    public static function getControllerClassName($controller)
    {
        if (API) {
            return \str_replace(yii::$app->controllerNamespace . '\\' . API . '\\' . 'v' . API_VERSION . '\\', '', \get_class($controller));
        }

        if (WEB_APP) {
            return \str_replace(yii::$app->controllerNamespace . '\\', '', \get_class($controller));
        }
    }

    // получаем расположение класса компонента по его названию
    public static function resolveComponentClass($className) : string
    {
        return self::resolveClass($className, self::COMPONENTS_DIR);
    }

    // возвращаем csrf token для шаблона в формате input:hidden
    public static function getCsrfInput()
    {
        return '
            <input
                type    ="hidden"
                name    ="' . yii::$app->request->csrfParam . '"
                value   ="' . yii::$app->request->getCsrfToken() . '"
                >
        ';
    }

    /*
    * Расстояние между двумя точками
    * $φA, $λA - широта, долгота 1-й точки,
    * $φB, $λB - широта, долгота 2-й точки
    * Написано по мотивам http://gis-lab.info/qa/great-circles.html
    * Михаил Кобзарев &amp;lt;mikhail@kobzarev.com&amp;gt;
    *
    */
    /*public static function getDist($φA, $λA, $φB, $λB)
    {
        // радиус земли
        $earthRadius = 6372795;

        // переводим координаты в радианы
        $lat1   = $φA * M_PI / 180;
        $lat2   = $φB * M_PI / 180;
        $long1  = $λA * M_PI / 180;
        $long2  = $λB * M_PI / 180;

        // косинусы и синусы широт и разницы долгот
        $cl1    = \cos($lat1);
        $cl2    = \cos($lat2);
        $sl1    = \sin($lat1);
        $sl2    = \sin($lat2);
        $delta  = $long2 - $long1;
        $cdelta = \cos($delta);
        $sdelta = \sin($delta);
         
        // вычисляем длину большого круга
        $y = \sqrt(\pow($cl2 * $sdelta, 2) + \pow($cl1 * $sl2 - $sl1 * $cl2 * $cdelta, 2));
        $x = $sl1 * $sl2 + $cl1 * $cl2 * $cdelta;

        $ad     = \atan2($y, $x);
        $dist   = $ad * $earthRadius;
         
        return $dist;
    }*/

    // public static function setDataToCache($data)
    // {
        // self::$data = $data;
    // }


    // получаем расположение класса по его названию с учетом приоритета его размещения и заданной директории $dir
    private static function resolveClass($className, $dir)
    {
        $className = str_replace('\\', '/', self::resolveName($className));
        // будет пусто, если консольный запуск
        $currAppWithApiVersion = '';

        if (WEB_APP || API) {
            $currAppWithApiVersion = str_replace('\\', '/', yii::$app->params['currAppWithApiVersion']) . DS;
        }

        // сначала ищем в самом приложении, потом в общих файлах
        foreach (['app', 'shared'] as $path) {
            $modelClass = $path . DS . $dir . DS . $currAppWithApiVersion . $className;
            $path       = yii::getAlias('@' . $modelClass . '.php');

            if (file_exists($path)) {
                return str_replace('/', '\\', DS . $modelClass);
            }
        }

        // если ничего не нашли, ищем в файлах ядра
        $modelClass = 'shared' . DS . $dir . DS . 'common' . DS . 'core' . DS . $className;
        $path       = yii::getAlias('@' . $modelClass . '.php');

        if (file_exists($path)) {
            return str_replace('/', '\\', DS . $modelClass);
        }

        // проверяем, может быть класс лежит в поддиректории!
        $subDir = '';

        // выявляем поддиректорию - первое слово класса
        for ($i = 0; $i < \strlen($className); $i++) {
            $char = $className[$i];

            if (\ctype_upper($char) && $i > 0) {
                break;
            }

            $subDir .= $char;
        }

        $subDir = \strtolower($subDir);

        if ($subDir) {
            // ищем в поддиректории в файлах ядра
            $modelClass = 'shared' . DS . $dir . DS . 'common' . DS . 'core' . DS . $subDir . DS . $className;
            $path       = yii::getAlias('@' . $modelClass . '.php');

            if (\file_exists($path)) {
                return \str_replace('/', '\\', DS . $modelClass);
            }
        }

        // класс нигде не найден
        return '';
    }

    // получаем расположение нужного шаблона с учетом приоритета поиска
    private static function resolveTpl($tplName, $dir) : string
    {
        // ищем в базовой директории, потом в общей
        foreach (['app', 'shared'] as $path) {
            $tpl  = $path . DS . $dir . DS . 'views' . DS . $tplName;
            $path = yii::getAlias('@' . $tpl . '.php');

            if (file_exists($path)) {
                return '@' . $tpl;
            }
        }

        throw new \yii\web\ServerErrorHttpException('Шаблон ' . $tplName . ' в директории ' . $dir . '/views не найден');
    }
}

