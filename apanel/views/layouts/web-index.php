<?php $this->beginPage() ?>

<!DOCTYPE html>
<!--[if IE 9]><html class="no-js lt-ie10" lang="ru"><![endif]-->
<!--[if gt IE 9]><!--><html class="no-js" lang="ru"><!--<![endif]-->

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>APANEL</title>

        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">

        <?php $this->head() ?>
    </head>

    <body>
        <?php $this->beginBody() ?>

        <!-- Page Wrapper -->
        <!-- In the PHP version you can set the following options from inc/config file -->
        <!--
            Available classes:

            'page-loading'      enables page preloader
        -->
        <div id="page-wrapper">
            <!-- Preloader -->
            <!-- Preloader functionality (initialized in js/app.js) - pageLoading() -->
            <!-- Used only if page preloader is enabled from inc/config (PHP version) or the class 'page-loading' is added in #page-wrapper element (HTML version) -->
            <div class="preloader themed-background">
                <h1 class="push-top-bottom text-light text-center"><strong>Pro</strong>UI</h1>
                <div class="inner">
                    <h3 class="text-light visible-lt-ie10"><strong>Loading..</strong></h3>
                    <div class="preloader-spinner hidden-lt-ie10"></div>
                </div>
            </div>
            <!-- END Preloader -->

            <div id="page-container" class="sidebar-no-animations">
                <div id="sidebar">
                    <div id="sidebar-scroll">
                        <div class="sidebar-content">
                            <a href="index.html" class="sidebar-brand">
                                <span class="sidebar-nav-mini-hide"><strong>A</strong>PANEL</span>
                            </a>

                            <div class="sidebar-section sidebar-user clearfix sidebar-nav-mini-hide">
                                <div class="sidebar-user-avatar">
                                    <a href="page_ready_user_profile.html">
                                        <img src="/<?= APANEL_DEFAULT_IMG_DIR ?>/noname-avatar.jpg" alt="">
                                    </a>
                                </div>

                                <div class="sidebar-user-name">Админ</div>

                                <div class="sidebar-user-links">
                                    <a href="" data-toggle="tooltip" data-placement="bottom" title="Мой профиль">
                                        <i class="gi gi-user"></i>
                                    </a>

                                    <a
                                        href="javascript:void(0)"
                                        class="enable-tooltip"
                                        data-placement="bottom"
                                        title="Настройки" onclick="$('#modal-user-settings').modal('show');"
                                    >
                                        <i class="gi gi-cogwheel"></i>
                                    </a>

                                    <a
                                        href="/<?= APANEL_WEB_ALIAS ?>/logout"
                                        data-toggle="tooltip"
                                        data-placement="bottom"
                                        title="Выход из системы"
                                    >
                                        <i class="gi gi-exit"></i>
                                    </a>
                                </div>
                            </div>

                            <ul class="sidebar-nav">
                                <li>
                                    <a href="index.html">
                                        <i class="gi gi-stopwatch sidebar-nav-icon"></i>
                                        <span class="sidebar-nav-mini-hide">Dashboard</span>
                                    </a>
                                </li>

                                <li>
                                    <a href="#" class="sidebar-nav-menu">
                                        <i class="fa fa-angle-left sidebar-nav-indicator sidebar-nav-mini-hide"></i>
                                        <i class="gi gi-shopping_cart sidebar-nav-icon"></i>
                                        <span class="sidebar-nav-mini-hide">eCommerce</span>
                                    </a>

                                    <ul>
                                        <li>
                                            <a href="page_ecom_dashboard.html">Dashboard</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div id="main-container">
                    <header class="navbar navbar-default">
                        <div class="navbar-header">
                            <ul class="nav navbar-nav-custom pull-right visible-xs">
                                <li>
                                    <a href="javascript:void(0)" data-toggle="collapse" data-target="#horizontal-menu-collapse">Меню</a>
                                </li>
                            </ul>

                            <ul class="nav navbar-nav-custom">
                                <li>
                                    <a href="javascript:void(0)" onclick="App.sidebar('toggle-sidebar');this.blur();">
                                        <i class="fa fa-bars fa-fw"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div id="horizontal-menu-collapse" class="collapse navbar-collapse">
                            <ul class="nav navbar-nav">
                                <li>
                                    <a href="javascript:void(0)">Подписчики</a>
                                </li>

                                <li>
                                    <a href="javascript:void(0)">Рассылка</a>
                                </li>

                                <li class="dropdown">
                                    <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                                        Скрипты <i class="fa fa-angle-down"></i>
                                    </a>

                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="javascript:void(0)">
                                                Парсеры
                                            </a>
                                        </li>

                                        <li>
                                            <a href="javascript:void(0)">
                                                Подбор конкурентов
                                            </a>
                                        </li>

                                        <li>
                                            <a href="javascript:void(0)">
                                                Yandex OAuth
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <li>
                                    <a href="javascript:void(0)">Настройки</a>
                                </li>

                                <li>
                                    <a href="javascript:void(0)">Ry.ru</a>
                                </li>

                                <li>
                                    <a href="javascript:void(0)">Партнеры</a>
                                </li>
                            </ul>

                            <form action="page_ready_search_results.html" method="post" class="navbar-form-custom">
                                <div class="form-group">
                                    <input type="text" id="top-search" name="top-search" class="form-control" placeholder="Поиск...">
                                </div>
                            </form>
                        </div>
                    </header>

                    <div id="page-content">
                        <?= $content ?>
                    </div>

                    <footer class="clearfix">
                        <div class="pull-right">
                            Developed
                            <i class="fa fa-heart text-danger"></i>
                            by
                            <a href="javascript:{}" target="_blank">Кохан Александр (fatalproject@gmail.com)</a>
                        </div>

                        <div class="pull-left">
                            &copy; <a href="" target="_blank">ПАО "Газпром нефть"</a>
                        </div>
                    </footer>
                </div>
            </div>
        </div>

        <a href="javascript:{}" id="to-top"><i class="fa fa-angle-double-up"></i></a>

        <!-- User Settings, modal which opens from Settings link (found in top right user menu) and the Cog link (found in sidebar user info) -->
        <div id="modal-user-settings" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header text-center">
                        <h2 class="modal-title"><i class="fa fa-pencil"></i> Settings</h2>
                    </div>
                    <!-- END Modal Header -->

                    <!-- Modal Body -->
                    <div class="modal-body">
                        <form action="index.html" method="post" enctype="multipart/form-data" class="form-horizontal form-bordered" onsubmit="return false;">
                            <fieldset>
                                <legend>Vital Info</legend>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Username</label>
                                    <div class="col-md-8">
                                        <p class="form-control-static">Admin</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="user-settings-email">Email</label>
                                    <div class="col-md-8">
                                        <input type="email" id="user-settings-email" name="user-settings-email" class="form-control" value="admin@example.com">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="user-settings-notifications">Email Notifications</label>
                                    <div class="col-md-8">
                                        <label class="switch switch-primary">
                                            <input type="checkbox" id="user-settings-notifications" name="user-settings-notifications" value="1" checked>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend>Password Update</legend>
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="user-settings-password">New Password</label>
                                    <div class="col-md-8">
                                        <input type="password" id="user-settings-password" name="user-settings-password" class="form-control" placeholder="Please choose a complex one..">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="user-settings-repassword">Confirm New Password</label>
                                    <div class="col-md-8">
                                        <input type="password" id="user-settings-repassword" name="user-settings-repassword" class="form-control" placeholder="..and confirm it!">
                                    </div>
                                </div>
                            </fieldset>
                            <div class="form-group form-actions">
                                <div class="col-xs-12 text-right">
                                    <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-sm btn-primary">Save Changes</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- END Modal Body -->
                </div>
            </div>
        </div>

        <?php $this->endBody() ?>
    </body>

</html>

<?php $this->endPage() ?>

