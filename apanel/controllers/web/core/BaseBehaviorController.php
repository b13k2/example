<?php

namespace app\controllers\web\core;


class BaseBehaviorController extends MainController
{
    public function actionShowContent()
    {
        return $this->render($this->resolveView('listing'), [
            'module'    => $this->controllerModule,
            'wordforms' => $this->wordforms,
        ]);
    }
}

