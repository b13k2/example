<?php

namespace app\controllers\web\core;

use yii;
use shared\components\common\core\Helper;


class LoginController extends \yii\web\Controller
{
    public $layout = 'web-login';
    public $defaultAction = 'show-form';


    public function init()
    {
        // стили
        $this->registerStylesheets();
        // тема
        $this->registerTheme();
        // базовые скрипты
        $this->registerJs();
        // частные скрипты
        $this->registerLogin();
    }

    public function actionShowForm()
    {
        if (!yii::$app->user->isGuest) {
            yii::$app->response->redirect(yii::$app->user->returnUrl);
        }

        $modelClassName = Helper::resolveModelClass('login');
        $model          = new $modelClassName;

        // попытка аутентификации
        if (yii::$app->request->isPost) {
            $model->load(yii::$app->request->post());

            // форма заполнена корректно
            if ($model->validate()) {
                $identity = yii::$app->user->identityClass;

                // пользователь найден
                if ($user = $identity::findIdentity($model->login)) {
                    // валидируем пароль
                    if (yii::$app->getSecurity()->validatePassword($model->passwd, $user->passwd)) {
                        // обновляем ключ
                        $user->auth_key = yii::$app->getSecurity()->generateRandomString();

                        if ($user->update(['auth_key']) !== false) {
                            // устанавливаем куку в обход yii, чтобы работать с ней из js
                            \setcookie(
                                COOKIE_AUTH_KEY_NAME,
                                $user->auth_key,
                                $model->remember_me ? \time() + COOKIE_AUTH_TIME_VALUE : 0,
                                '/'
                            );

                            // аутентификация пройдена
                            yii::$app->user->login($user, $model->remember_me ? COOKIE_AUTH_TIME_VALUE : 0);
                            // редирект
                            yii::$app->response->redirect(yii::$app->user->returnUrl);
                        } else {
                            $model->addError('passwd', 'Сервис временно недоступен, попробуйте позже');
                        }
                    } else {
                        $model->addError('passwd', 'Логин и/или пароль неверны');
                    }
                } else {
                    $model->addError('passwd', 'Логин и/или пароль неверны');
                }
            }
        }

        // выводим форму
        return $this->render('form', [
            'csrfInput' => Helper::getCsrfInput(),
            'model'     => $model,
        ]);
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

    // метод регистрирует частные скрипты для этого контроллера
    protected function registerLogin()
    {
        Helper::getAssetClass('basic\page\login')::register(yii::$app->view);
    }
}

