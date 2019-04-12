var
    // базовая модалка с основной формой на странице;
    // вся работа с данными ведётся через эту форму
    modalForm   = prepareModalForm('_modal-form'),
    // алиас для доступа к заголовку модалки формы
    formTitle   = $('.modal-title', modalForm),
    // алиас для доступа к кнопке сохранения данных
    saveButton  = $('.btn-primary', modalForm),
    // окно подтверждения удаления
    deleteForm  = prepareDeleteForm('_delete-form');

// флаг обработки ошибки запроса: да/нет
var queryErrorProcessed  = false;



$(function() {
    var
        // кнопка в шаблоне модуля;
        // по нажатию вызывается форма для добавления новых моделей
        queryAddNew = $('.query-add-new'),

        // кнопка в шаблоне модальной формы;
        // по нажатию отправляется запрос на сервер на создание/обновление модели
        querySave   = $('.query-save', modalForm);



    // HANDLERS


    // вызываем форму с пустыми полями (добавление новой модели)
    queryAddNew.on('click', function(e) {
        var obj = $(this);
        e.preventDefault();

        // кнопка уже нажата
        if (isButtonBlocked(obj)) {
            return;
        }

        // заголовок модального окна
        setFormTitle(obj.text());
        // конфигурируем кнопку сохранения
        configureSaveButton('Создать', obj.attr('href'), 'POST');

        // отправляем запрос
        query_sendGet(obj.attr('href'), modalForm);
    });

    // пытаемся сохранить новую модель или обновить старую
    querySave.on('click', function(e) {
        var obj = $(this);
        e.preventDefault();

        // кнопка уже нажата
        if (isButtonBlocked(obj)) {
            return;
        }

        // конфигурируем кнопку сохранения
        configureSaveButtonWithPreloader('Ждите... ');

        // запрос на обработку модели;
        // ответ ловит modalForm
        query_processModel();
    });

    // клик на кнопке удаления модели;
    // в ответ возвращается форма подтверждения
    $('.hierarchyContainer').on('click', '.query-delete', function(e) {
        var obj = $(this);
        e.preventDefault();

        // кнопка уже нажата
        if (isButtonBlocked(obj)) {
            return;
        }

        // заголовок модального окна
        $('.modal-title', deleteForm)
            .html('<span class="label label-danger"><i class="fa fa-exclamation-triangle"></i></span> Подтвердите удаление');

        // тело
        $('.modal-body', deleteForm)
            .html('Вы точно хотите удалить запись <b>' + obj.parents('.dd-content').contents().filter(function(i) {
                    return this.nodeType == 3;
                }).text() + '</b>?'
            );

        $('.query-delete-confirmed', deleteForm)
            .attr('href', obj.attr('href'))
            .text('Удалить');

        deleteForm.modal();
        unblockButton();
    });

    // удаляем запись (пользователь подтвердил)
    $('body').on('click', '.query-delete-confirmed', function(e) {
        var obj = $(this);
        e.preventDefault();

        // кнопка уже нажата
        if (isButtonBlocked(obj)) {
            return;
        }

        $('.query-delete-confirmed', deleteForm)
            .html('Ждите... ')
            .append(getPreloader());

        // отправляем запрос
        query_sendDelete(obj.attr('href'), deleteForm);
    });

    // клик на кнопке редактирования;
    // в ответ возвращается инициализированная форма
    $('body').on('click', '.query-update', function(e) {
        var obj = $(this);
        e.preventDefault();

        // кнопка уже нажата
        if (isButtonBlocked(obj)) {
            return;
        }

        // заголовок модального окна
        setFormTitle(
            obj.parents('.dd-content').contents().filter(function(i) {
                return this.nodeType == 3;
            }).text()
        );
        // конфигурируем кнопку сохранения
        configureSaveButton('Обновить', obj.attr('href'), 'PUT');

        // отправляем запрос
        query_sendGet(obj.attr('href'), modalForm);
    });

    // клик на кнопке обновления структуры
    $('.query-structure').on('click', function(e) {
        var obj = $(this);
        e.preventDefault();

        // кнопка уже нажата
        if (isButtonBlocked(obj)) {
            return;
        }

        obj.text('Обновляю... ').append(getPreloader());

        var data = hierarchyContainer.nestable('serialize');

        // отправляем запрос
        query_sendPatch(obj.attr('href'), obj, {
            'data': {'structure':data},
        });
    });



    // РАЗБОР ОТВЕТОВ


    // GET

    // отлавливаем необработанную ошибку
    modalForm.on('sendGETquery.error.unknown', function(e, jqXHR) {
        // запрос ещё не обработан
        if (queryErrorProcessed === false) {
            var answer = getAnswer(jqXHR)
            queryErrorUndefined(answer.name, answer.message);
        }

        queryErrorProcessed = false;
    });

    // совершаем какие-либо действия по факту завершения запроса целиком
    modalForm.on('sendGETquery.complete', function(e, jqXHR) {
        unblockButton();
    });

    // отлавливаем ошибку 404
    modalForm.on('sendGETquery.error.404', function(e, jqXHR) {
        queryErrorProcessed = true;
        var answer = getAnswer(jqXHR)
        queryError404(answer.name, answer.message);
    });

    // отлавливаем успешно завершенный запрос:
    // - далее или выводим форму с пустыми полями для добавления новой модели
    // - или выводим форму с инициализированными полями для обновления модели
    modalForm.on('sendGETquery.success', function(e, response) {
        var obj = $(this);

        // вызываем пользовательскую функцию по отрисовке UI
        if (core_callUserFunc('toFormCreateUpdateElements', response) === false) {
            // функция ядра
            core_callUserFunc('ui_toFormCreateUpdateElements', response);
        }

        // показываем модальную форму
        obj.modal();
    });


    // POST

    // отлавливаем необработанную ошибку
    modalForm.on('sendPOSTquery.error.unknown', 'form', function(e, jqXHR) {
        // запрос ещё не обработан
        if (queryErrorProcessed === false) {
            var answer = getAnswer(jqXHR)
            queryErrorUndefined(answer.name, answer.message);
            clearValidationErrors();
        }

        queryErrorProcessed = false;
    });

    // совершаем какие-либо действия по факту завершения запроса целиком
    modalForm.on('sendPOSTquery.complete', 'form', function(e, jqXHR) {
        unblockButton();
        saveButton.text('Создать');
    });

    // отлавливаем ошибку 404
    modalForm.on('sendPOSTquery.error.404', function(e, jqXHR) {
        queryErrorProcessed = true;
        var answer = getAnswer(jqXHR)
        queryError404(answer.name, answer.message);
    });

    // обработка кода 422: валидация полей формы
    modalForm.on('sendPOSTquery.error.422', 'form', function(e, jqXHR) {
        queryErrorProcessed = true;
        var obj = $(this);
        var response = jqXHR.responseJSON;

        clearValidationErrors();

        if (typeof response == 'object' && response.length === undefined) {
            for (var attrId in response) {
                $('#' + attrId)
                    .parents('.form-group')
                    .addClass('has-error')
                    .find('.help-block')
                    .html(response[attrId]);
            }
        }
    });

    // отлавливаем успешно завершенный запрос:
    // - событие, когда новая модель успешно создана
    modalForm.on('sendPOSTquery.success', 'form', function(e, response) {
        modalForm.modal('hide');
        queryCreated();
    });


    // DELETE

    // отлавливаем необработанную ошибку
    deleteForm.on('sendDELETEquery.error.unknown', function(e, jqXHR) {
        // запрос ещё не обработан
        if (queryErrorProcessed === false) {
            var answer = getAnswer(jqXHR)
            queryErrorUndefined(answer.name, answer.message);
        }

        queryErrorProcessed = false;
    });

    // совершаем какие-либо действия по факту завершения запроса целиком
    deleteForm.on('sendDELETEquery.complete', function(e, jqXHR) {
        unblockButton();
        $('.query-delete-confirmed', deleteForm).text('Удалить');
    });

    // отлавливаем ошибку 404
    deleteForm.on('sendDELETEquery.error.404', function(e, jqXHR) {
        queryErrorProcessed = true;
        var answer = getAnswer(jqXHR)
        queryError404(answer.name, answer.message);
    });

    // отлавливаем успешно завершенный запрос:
    // - модель успешно удалена
    deleteForm.on('sendDELETEquery.success', function(e, response) {
        deleteForm.modal('hide');
        queryDeleted();
    });


    // PUT

    // отлавливаем необработанную ошибку
    modalForm.on('sendPUTquery.error.unknown', function(e, jqXHR) {
        // запрос ещё не обработан
        if (queryErrorProcessed === false) {
            var answer = getAnswer(jqXHR)
            queryErrorUndefined(answer.name, answer.message);
        }

        queryErrorProcessed = false;
    });

    // совершаем какие-либо действия по факту завершения запроса целиком
    modalForm.on('sendPUTquery.complete', function(e, jqXHR) {
        unblockButton();
        saveButton.text('Обновить');
    });

    // отлавливаем ошибку 404
    modalForm.on('sendPUTquery.error.404', function(e, jqXHR) {
        queryErrorProcessed = true;
        var answer = getAnswer(jqXHR)
        queryError404(answer.name, answer.message);
    });

    // обработка кода 422: валидация полей формы
    modalForm.on('sendPUTquery.error.422', 'form', function(e, jqXHR) {
        queryErrorProcessed = true;
        var obj = $(this);
        var response = jqXHR.responseJSON;

        clearValidationErrors();

        if (typeof response == 'object' && response.length === undefined) {
            for (var attrId in response) {
                $('#' + attrId)
                    .parents('.form-group')
                    .addClass('has-error')
                    .find('.help-block')
                    .html(response[attrId]);
            }
        }
    });

    // отлавливаем успешно завершенный запрос:
    // - событие, когда модель успешно обновлена
    modalForm.on('sendPUTquery.success', 'form', function(e, response) {
        modalForm.modal('hide');
        queryUpdated();
    });


    // PATCH

    // отлавливаем необработанную ошибку
    $('.query-structure').on('sendPATCHquery.error.unknown', function(e, jqXHR) {
        // запрос ещё не обработан
        if (queryErrorProcessed === false) {
            var answer = getAnswer(jqXHR)
            queryErrorUndefined(answer.name, answer.message);
        }

        queryErrorProcessed = false;
    });

    // совершаем какие-либо действия по факту завершения запроса целиком
    $('.query-structure').on('sendPATCHquery.complete', function(e, jqXHR) {
        unblockButton();
        $(this).text('Обновить структуру');
    });

    // отлавливаем ошибку 404
    $('.query-structure').on('sendPATCHquery.error.404', function(e, jqXHR) {
        queryErrorProcessed = true;
        var answer = getAnswer(jqXHR)
        queryError404(answer.name, answer.message);
    });

    // отлавливаем успешно завершенный запрос
    $('.query-structure').on('sendPATCHquery.success', function(e, response) {
        $(this).hide(590);
        showQuerySuccess(undefined, 'Структура обновлена', undefined);
    });
});



// Функции работы с кнопками!


// проверяем, заблокирована ли кнопка;
// если нет, блокируем
function isButtonBlocked(obj) {
    // кнопка уже нажата
    if (obj.hasClass('_pressed')) {
        return true;
    }

    // вызова не было;
    // блокируем кнопку
    obj.addClass('_pressed');
    return false;
}

// разблокируем нажатую кнопку
function unblockButton() {
    // находим все кнопки и разблокируем
    $('._pressed').removeClass('_pressed');
}



// Функции работы с модальными окнами!


// устанавливаем заголовок
function setFormTitle(text) {
    formTitle.text(text);
}

// настриваем кнопку сохранения модели (создать/обновить)
function configureSaveButton(text, link='', method='') {
    saveButton.text(text);

    if (link) {
        saveButton.attr('href', link)
    }

    if (method) {
        saveButton.data('method', method);
    }

    return saveButton;
}

// кнопка configureSaveButton только с прелоадером
function configureSaveButtonWithPreloader(text, link='', method='') {
    configureSaveButton(text, link, method).append(getPreloader());
}

// чистим ошибки валидации полей
function clearValidationErrors() {
    modalForm.find('.form-group')
        .removeClass('has-error')
        .find('.help-block')
        .html('');
}



// Функции работы с ответами на запросы (ошибки)!

// разбираем ответ с ошибкой
function getAnswer(jqXHR) {
    var 
        response = jqXHR.responseJSON,
        name, message;

    if (typeof response == 'object') {
        name    = response.status === undefined ? response.name : response.status + ' ' + response.name;
        message = response.message;
    }

    return {
        'name' : name,
        'message' : message,
    };
}



// Функции формирования модальных окон!


// единоразовая подготовка модальной формы для работы с данными моделей
function prepareModalForm(id) {
    return prepareModal(id, 'model', 'max');
}

// единоразовая подготовка окна подтверждения при удалении модели
function prepareDeleteForm(id) {
    return prepareModal(id, 'delete');
}

// функция строит и возвращает модальное окно;
// formType - тип формы, size - размер
function prepareModal(id, formType, size='') {
    var header = $('<div/>', {
        'class': 'modal-header',
    }).append(
        $('<button/>', {
            'type': 'button',
            'class': 'close',
            'data-dismiss': 'modal',
            'aria-hidden': 'true',
        }).append('&times;')
    ).append(
        $('<h3/>', {
            'class': 'modal-title',
        })
    );

    var body = $('<div/>', {
        'class': 'modal-body',
    })

    var buttonsList = '';

    // форма для работы с данными модели
    if (formType == 'model') {
        buttonsList  = '<button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Отмена</button>';
        buttonsList += '<a href="" class="btn btn-sm btn-primary query-save"></a>';
    // форма подтверждения удаления данных
    } else if (formType == 'delete') {
        buttonsList  = '<button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Отмена</button>';
        buttonsList += '<a href="" class="btn btn-sm btn-danger query-delete-confirmed"></a>';

    }

    var buttons = $('<div/>', {
        'class': 'modal-footer',
    }).append(buttonsList);

    var modal = $('<div/>', {
        'id'          : id,
        'class'       : 'modal fade in',
        'tabindex'    : '-1',
        'role'        : 'dialog',
        'aria-hidden' : 'true',
    }).append(
        $('<div/>', {
            'class' : 'modal-dialog ' + (size ? (size == 'max' ? 'modal-max-width' : 'modal-' + size) : ''),
        }).append(
            $('<div/>', {
                'class' : 'modal-content',
            }).append(header).append(body).append(buttons)
        )
    );

    $('body').append(modal);

    return $('#' + id);
}



// Функции работы с уведомлениями!


// общий шаблон вывода ошибок
function showQueryNotice(type, caption, message) {
    switch (type) {
        case 'success':
            caption = '<i class="fa fa-check-circle"></i>' + ' ' + (caption === undefined ? 'Успех' : caption);
            message = message === undefined ? 'Данные успешно обработаны!' : message;
            break;

        case 'danger':
            caption = '<i class="fa fa-times-circle"></i>' + ' ' + (caption === undefined ? 'Ошибка' : caption);
            message = message === undefined ? 'Не удалось обработать данные!' : message;
            break;
    }

    $.bootstrapGrowl('<h4>' + caption + '</h4>' + message, {
        ele     : 'body',
        type    : type,

        offset  : {
            from    : 'top',
            amount  : 20
        },

        align   : 'right',
        width   : 350,
        delay   : 6000,

        allow_dismiss   : true,
        stackup_spacing : 10,
    });
}

// обёртка для вывода ошибок
function showQueryError(caption, message) {
    showQueryNotice('danger', caption, message);
}

// обертка для вывода успеха
function showQuerySuccess(caption, message) {
    showQueryNotice('success', caption, message);
}

// неизвестная ошибка
function queryErrorUndefined(caption, message) {
    if (caption === undefined) {
        caption = 'Неизвестная ошибка';
    }

    if (message === undefined) {
        message = 'Не удалось обработать Ваш запрос.<br>Попробуйте позже.';
    }

    showQueryError(caption, message);
}

// ошибка 404
function queryError404(caption, message) {
    if (caption === undefined) {
        caption = '404 Not Found';
    }

    if (message === undefined) {
        message = 'Запрашиваемый ресурс не найден.';
    }

    showQueryError(caption, message);
}

// успех POST запроса
function queryCreated() {
    showQuerySuccess(undefined, 'Новая запись успешно добавлена!');
}

// успех DELETE запроса
function queryDeleted() {
    showQuerySuccess(undefined, 'Запись удалена!');
}

// успех UPDATE запроса
function queryUpdated() {
    showQuerySuccess(undefined, 'Запись успешно обновлена!');
}

