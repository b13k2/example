<?php

namespace app\controllers\web\core;

use yii;


class LogoutController extends \yii\web\Controller
{
    public $defaultAction = 'logout';


    public function actionLogout()
    {
        if (yii::$app->user->isGuest) {
            return yii::$app->response->redirect('/' . APANEL_WEB_ALIAS);
        }

        $identity = yii::$app->user->identity;
        $identity->auth_key = yii::$app->getSecurity()->generateRandomString();

        if ($identity->update(['auth_key']) !== false) {
            // производим разлогирование
            yii::$app->user->logout();
            return yii::$app->response->redirect('/' . APANEL_WEB_ALIAS);
        }

        throw new \yii\web\ServerErrorHttpException(
            'Извините, возникла неизвестная ошибка. Свяжитесь с администратором.');
    }
}

