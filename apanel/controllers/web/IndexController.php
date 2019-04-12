<?php

namespace app\controllers\web;


class IndexController extends \app\controllers\web\core\MainController
{
    public function actionShowContent()
    {
        return $this->render('index-page');
    }

    public function getWordforms()
    {
        return (object) [
        ];
    }
}

