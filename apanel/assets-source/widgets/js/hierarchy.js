// контейнер с деревом элементов
var hierarchyContainer = $('.hierarchyContainer');
// флаг: обработана ли ошибка
var errorProcessed = false;


$(function() {
    // ссылка для api запроса
    var linkToApi = hierarchyContainer.data('linktoapi');

    // применяем плагин к списку
    hierarchyContainer.nestable({
        maxDepth: 5,
    });

    // теперь можно и структуру сортировать
    hierarchyContainer.sortableDndTree({
        'debug': false,
    });

    // отслеживаем изменения дерева элементов
    hierarchyContainer.change(function() {
        hierarchyContainer.trigger('dndTreeChanged');
        $('.query-structure').show(590);
    });

    // создаём скрытый элемент,
    // который будет отвечать за перчичную загрузку дерева
    var treeLoader = $('<div/>', {
        'class': 'hide tree-loader',
    });
    hierarchyContainer.after(treeLoader);

    // выводим заглушку перед отправкой запроса
    treeLoader.on('sendGETquery.beforeSend', function() {
        hierarchyContainer.before(showDataLoadingPreloader());
        errorProcessed = false;
    });

    // отлавливаем необработанную ошибку
    treeLoader.on('sendGETquery.error.unknown', function() {
        if (errorProcessed === false) {
            hierarchyContainer.before(errorUndefined());
        }
    });

    // отлавливаем 404 ошибку
    treeLoader.on('sendGETquery.error.404', function() {
        hierarchyContainer.before(error404());
        errorProcessed = true;
    });

    // отлавливаем 500 ошибку
    treeLoader.on('sendGETquery.error.500', function() {
        hierarchyContainer.before(error500());
        errorProcessed = true;
    });

    // обрабатываем успешно завершённый запрос
    treeLoader.on('sendGETquery.success', function(e, response) {
        // проверяем ответ на корректность: ожидаем объект - ассоциативный массив
        if (typeof response == 'object' && response.length === undefined) {
            // удаляем все нотисы, что активны
            $('.listing-notice').remove();

            // создаём корневой блок OL
            var ol = heirarchy_createOlBlock();
            hierarchyContainer.append(ol);

            // добавляем элементы в дерево
            heirarchy_appendElements(response, ol);

            return;
        }

        if (typeof response == 'object' && response.length == 0) {
            hierarchyContainer.before(showInfo('Похоже, Вы не добавили ни одной записи.', 'Ничего не найдено'));
        } else {
            hierarchyContainer.before(showWarning());
        }
    });

    /*
    // отслеживаем клики на "+" и разворачиваем структуру
    hierarchyContainer.on('click', '[data-action=expand]', function() {
        // console.log('expand clicked'); return;

        var
            li      = $(this).parent(),
            button  = $(this);

        // раздел уже открыт
        if ($('> ol', li).length) {
            return;
        }

        // заглушка (временный вариант)
        //button.on('sendGETquery.error.unknown', function() {
            //alert('Извините, произошла ошибка...');
        //});

        // отлавливаем успешно отработанный запрос
        button.on('sendGETquery.success', function(e, response) {
            // ответ корректен
            if (typeof response == 'object' && response.pages !== undefined) {
                // console.log(response.pages.length);

                // пришёл ассоциативный массив (страницы есть)
                if (response.pages.length === undefined) {
                    // создаём корневой блок OL
                    var ol = heirarchy_createOlBlock();
                    li.append(ol);

                    // добавляем элементы в дерево
                    heirarchy_appendElements(response, ol);

                    return;
                }
            }

            // alert('Некорректный ответ от сервера...');
        });

        // получаем список дочерних элементов
        core_data_sendGETquery(linkToApi + 'struct/' + li.data('id') + '/', button);
    });
    */

    // получаем данные
    query_sendGet(linkToApi, treeLoader);
});


// создаём ol блок
function heirarchy_createOlBlock() {
    return $('<ol/>', {
        'class': 'dd-list',
    });
}

// добавляем элементы в дерево
function heirarchy_appendElements(response, ol) {
    // перебираем все модели, что вернул запрос
    for (var key in response) {
        // создаём дерево: основная страница и доп. информация
        var tpl = heirarchy_createItem(response[key]);
        ol.append(tpl);
    }
}

// создаём конкретный элемент
function heirarchy_createItem(item) {
    var nestedCnt = item.nestedCnt;

    var
        // сортировка
        sorting       = item.sorting == undefined ? 0 : item.sorting,
        // основной шаблон
        itemTpl       = '',
        // доп. информация
        nestedTpl     = '',
        // кнопки управления деревом
        nestedCtrlTpl = '';

    // есть доп. информация; выводим ее
    if (nestedCnt) {
        nestedTpl     = '<span style="font-size:12px"> <sup>' + nestedCnt + '</sup></span>';
        nestedCtrlTpl = heirarchy_createSubCtrlButtons();
    }

    var collapsed = nestedCtrlTpl ? 'dd-collapsed' : '';

    itemTpl += '<li class="dd-item ' + collapsed + '" data-id="' + item.id + '" data-sorting="' + sorting + '">';
        itemTpl += nestedCtrlTpl;

        itemTpl += '<div class="dd-handle"></div>';
        itemTpl += '<div class="dd-content">';
            itemTpl += item.title + nestedTpl;
            itemTpl += heirarchy_createControlElements(item);
        itemTpl += '</div>';
    itemTpl += '</li>';

    return itemTpl;
}

function heirarchy_createControlElements(item) {
    var nestedCnt = item.nestedCnt;

    var itemTpl = '';
    var route   = item.route === undefined ? false : route;

    itemTpl += '<div class="block-options pull-right">';

        // есть вложенные элементы,
        // а значит и возможность добавлять их
        if (nestedCnt) {
            itemTpl += '<a href="' + item.module.str_id + '" class="btn btn-xs btn-default query-add-new" title=""><i class="fa fa-plus"></i> </a>';
            itemTpl += ' ';
        }

        // только у роута есть опция видимости
        if (route) {
            itemTpl += '<a href="' + item.module.str_id + '/visible" class="item-visible querey-visible" title="Показать/скрыть"><i class="fa fa-eye"></i></a>';
            itemTpl += ' ';

            // публичная страница может быть только у роута
            itemTpl += '<a href="' + item.module.str_id + '/view/' + item.id + '" class="btn btn-alt btn-sm btn-default" title="Посмотреть страницу на сайте"><i class="fa fa-search"></i></a>';
            itemTpl += ' ';
        }

        itemTpl += '<a href="' + item.module.str_id + '/' + item.id + '" class="btn btn-alt btn-sm btn-default query-update" title="Редактировать"><i class="fa fa-pencil"></i></a>';
        itemTpl += ' ';
        itemTpl += '<a href="' + item.module.str_id + '/' + item.id + '" class="btn btn-alt btn-sm btn-default query-delete" title="Удалить"><i class="fa fa-trash-o"></i></a>';
        itemTpl += ' ';

        itemTpl += '<div class="btn-group btn-group-sm">';
            itemTpl += '<a href="javascript:void(0)" class="btn btn-alt btn-sm btn-default dropdown-toggle enable-tooltip" data-toggle="dropdown" title="" data-original-title="Options" aria-expanded="false">';
                itemTpl += '<span class="caret"></span>';
            itemTpl += '</a>';

            itemTpl += '<ul class="dropdown-menu dropdown-custom dropdown-menu-right">';
                itemTpl += '<li>';
                    itemTpl += '<a href="#"><i class="fa fa-calendar pull-right"></i>Отложенная публикация</a>';
                itemTpl += '</li>';

                itemTpl += '<li class="divider"></li>';
                    itemTpl += '<li>';
                        itemTpl += '<a href="#"><i class="fa fa-cogs pull-right"></i>Настройки';
                    itemTpl += '</a>';
                itemTpl += '</li>';
            itemTpl += '</ul>';
        itemTpl += '</div>';
    itemTpl += '</div>';

    return itemTpl;
}

// создаём кнопки управления дочерними страницами (показать / скрыть)
function heirarchy_createSubCtrlButtons() {
    var tpl = '';

    tpl += '<button class="dd-collapse" data-action="collapse" type="button">Свернуть</button>';
    tpl += '<button class="dd-expand" data-action="expand" type="button">Развернуть</button>';

    return tpl;
}

