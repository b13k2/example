<div class="content-header">
    <?= \widgets\MenuWidget::widget([
        'str_id' => 'quick',
    ]) ?>
</div>

<div class="block block-alt-noborder">
    <div class="row">
        <div class="col-md-6 col-lg-3 col-lg-offset-1">
            <div class="block-section">
                <h3 class="sub-header text-center">
                    <strong>Не забудь сделать!</strong>
                </h3>

                <p class="clearfix">
                    <i class="fa fa-clock-o fa-5x text-danger pull-left animation-pulse"></i>

                    Сегодня надо сделать плановую <span class="text-success"><strong>выгрузку остатков</strong></span> в рамках GPN Market и передать программисту на обработку!
                </p>

                <p>
                    <a href="#" class="btn btn-lg btn-success btn-block">Приступить</a>
                </p>
            </div>
        </div>

        <div class="col-md-6 col-lg-6 col-lg-offset-1">
            <h3 class="sub-header">
                <strong>Добро пожаловать!</strong>
            </h3>

            <div id="faq1" class="panel-group">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <i class="fa fa-angle-right"></i>

                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#faq1" href="#faq1_q1">
                                Как пользоваться админ панелью
                            </a>
                        </h4>
                    </div>

                    <div id="faq1_q1" class="panel-collapse collapse in">
                        <div class="panel-body">
                            <p>
                                Здесь будет текст с кратким описанием системы.
                            </p>

                            <p class="remove-margin">
                                Здесь немного деталей как пользоваться всем этим.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <i class="fa fa-angle-right"></i>

                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#faq1" href="#faq1_q2">
                                Кому писать/звонить в случае проблем?
                            </a>
                        </h4>
                    </div>

                    <div id="faq1_q2" class="panel-collapse collapse">
                        <div class="panel-body">
                            Контакты админа, программиста. Описание типовых случаев.
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <i class="fa fa-angle-right"></i>

                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#faq1" href="#faq1_q3">
                                Ещё базовая заметка...
                            </a>
                        </h4>
                    </div>

                    <div id="faq1_q3" class="panel-collapse collapse">
                        <div class="panel-body">
                            Текст...
                        </div>
                    </div>
                </div>
            </div>

            <h3 class="sub-header"><strong>Модуль "Пользователи"</strong></h3>

            <div id="faq2" class="panel-group">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <i class="fa fa-angle-right"></i>

                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#faq2" href="#faq2_q1">
                                Вопрос?
                            </a>
                        </h4>
                    </div>

                    <div id="faq2_q1" class="panel-collapse collapse">
                        <div class="panel-body">
                            Ответ...
                        </div>
                    </div>
                </div>
            </div>

            <h3 class="sub-header"><strong>Модуль "Онлайн сервисы"</strong></h3>

            <div id="faq3" class="panel-group">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <i class="fa fa-angle-right"></i>
                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#faq3" href="#faq3_q1">
                                Вопрос... ???
                            </a>
                        </h4>
                    </div>

                    <div id="faq3_q1" class="panel-collapse collapse">
                        <div class="panel-body">
                            А здесь ответ...
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

