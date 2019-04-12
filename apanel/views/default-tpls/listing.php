<div class="content-header">
    <?= \widgets\MenuWidget::widget([
        'str_id' => 'quick',
    ]) ?>
</div>

<ul class="breadcrumb breadcrumb-top">
    <li>
        <a href="/<?= APANEL_WEB_ALIAS ?>" title="Главная страница">
            <i class="fa fa-home"></i>
        </a>
    </li>

    <li>
        <?= $module->name ?>
    </li>
</ul>

<div class="block">
    <div class="block-title">
        <h2>
            <?= $module->iconTpl ?>
            <strong>Модуль &laquo;<?= $module->name ?>&raquo;</strong>
        </h2>

        <div class="block-options pull-right">
            <a
                href="structure/<?= $module->str_id ?>"
                class="btn btn-xs btn-info query-structure"
                style='display:none;'
            >
                Обновить структуру
            </a>

            <a
                href="<?= $module->str_id ?>"
                class="btn btn-xs btn-primary query-add-new"
            >
                <i class="fa fa-plus"></i>
                <?= $wordforms->singular ?>
            </a>

            <a
                href=""
                class="btn btn-sm btn-primary" data-toggle="tooltip" data-original-title="Настройки"
            >
                <i class="fa fa-cogs"></i>
            </a>
        </div>
    </div>
</div>

<?= \widgets\HierarchyWidget::widget([
    'moduleStrId' => $module->str_id,
]) ?>

