<?php
    use yii\helpers\Html;
?>

<img src="/<?= APANEL_DEFAULT_IMG_DIR ?>/login-bg.jpg" alt="" class="full-bg animation-pulseSlow">

<div id="login-container" class="animation-fadeIn">
    <div class="login-title text-center">
        <h1>
            <strong>ГАЗПРОМ</strong>НЕФТЬ
            <br>
            <small>Необходима <strong>аутентификация</strong></small>
        </h1>
    </div>

    <div class="block push-bit">
        <form action="" method="post" id="form-login" class="form-horizontal form-bordered form-control-borderless">
            <?php
                $err = $model->getFirstError('login');
            ?>

            <div class="form-group <?= $err ? 'has-error' : '' ?>">
                <div class="col-xs-12">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="gi gi-envelope"></i></span>

                        <input
                            type="text"
                            id="<?= Html::getInputId($model, 'login') ?>"
                            name="<?= Html::getInputName($model, 'login') ?>"
                            class="form-control input-lg"
                            placeholder="<?= $model->getAttributeLabel('login') ?>"
                            value="<?= $model->login ?>"
                        >
                    </div>

                    <div id="<?= Html::getInputId($model, 'login') ?>-error" class="help-block animation-slideDown">
                        <?= $model->getFirstError('login') ?>
                    </div>
                </div>
            </div>

            <?php
                $err = $model->getFirstError('passwd');
            ?>

            <div class="form-group <?= $err ? 'has-error' : '' ?>">
                <div class="col-xs-12">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="gi gi-asterisk"></i></span>

                        <input
                            type="password"
                            id="<?= Html::getInputId($model, 'passwd') ?>"
                            name="<?= Html::getInputName($model, 'passwd') ?>"
                            class="form-control input-lg"
                            placeholder="<?= $model->getAttributeLabel('passwd') ?>"
                            value="<?= $model->passwd ?>"
                        >
                    </div>

                    <div id="<?= Html::getInputId($model, 'passwd') ?>-error" class="help-block animation-slideDown">
                        <?= $err ?>
                    </div>
                </div>
            </div>

            <div class="form-group form-actions">
                <div class="col-xs-4">
                    <label class="switch switch-primary" data-toggle="tooltip" title="<?= $model->getAttributeLabel('remember_me') ?>">
                        <input
                            type="hidden"
                            name="<?= Html::getInputName($model, 'remember_me') ?>"
                            value="0"
                        >

                        <input
                            type="checkbox"
                            id="<?= Html::getInputId($model, 'remember_me') ?>"
                            name="<?= Html::getInputName($model, 'remember_me') ?>"
                            value='1'
                            <?= $model->remember_me || is_null($model->remember_me) ? "checked" : "" ?>
                        >

                        <span></span>
                    </label>
                </div>

                <div class="col-xs-8 text-right">
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="fa fa-angle-right"></i>
                        ВОЙТИ
                    </button>
                </div>
            </div>

            <?= $csrfInput ?>
        </form>        
    </div>
</div>

