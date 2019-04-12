// функция отправки любых запросов
function query_send(url, initiator, userSettings) {
    var settings = {};

    settings.data     = {};
    settings.dataType = 'json';
    settings.type     = 'GET';
    settings.apiLink  = 'api/v1';

    // переопределяем настройки по умолчанию
    $.extend(settings, userSettings);

    // можно задать свою версию api или свой линк на апи
    if (settings.url === undefined) {
        settings.url = '/' + core_apanelAlias + '/' + settings.apiLink + '/' + url;
    }

    settings.beforeSend = function(jqXHR, settings) {
        if (core_isApi(settings.url)) {
            jqXHR.setRequestHeader('Authorization', 'Bearer ' + core_getAuthKey());
        }

        // инициатор инициирует триггер
        initiator.trigger('send' + settings.type + 'query.beforeSend');
    };

    settings.error = function(jqXHR, textStatus, errorThrown) {
        // конкретная ошибка
        initiator.trigger('send' + settings.type + 'query.error.' + jqXHR.status, [jqXHR]);
        // общий триггер
        initiator.trigger('send' + settings.type + 'query.error.unknown', [jqXHR]);
    };

    settings.success = function(data, textStatus, jqXHR) {
        initiator.trigger('send' + settings.type + 'query.success', [data]);
    };

    settings.complete = function(jqXHR, textStatus) {
        initiator.trigger('send' + settings.type + 'query.complete');
    };

    // отправка запроса
    $.ajax(settings);
}



// ЗАПРОСЫ


// отправляем GET запрос
function query_sendGet(url, initiator, userSettings) {
    query_send(url, initiator, userSettings);
}

// отправляем POST запрос
function query_sendPost(url, initiator, userSettings) {
    var settings = {
        'type': 'POST',
    };

    $.extend(settings, userSettings);
    query_send(url, initiator, settings);
}

// отправляем PUT запрос
function query_sendPut(url, initiator, userSettings) {
    var settings = {
        'type': 'PUT',
    };

    $.extend(settings, userSettings);
    query_send(url, initiator, settings);
}

// отправляем PATCH запрос
function query_sendPatch(url, initiator, userSettings) {
    var settings = {
        'type': 'PATCH',
    };

    $.extend(settings, userSettings);
    query_send(url, initiator, settings);
}

// отправляем DELETE запрос
function query_sendDelete(url, initiator, userSettings) {
    var settings = {
        'type': 'DELETE',
    };

    $.extend(settings, userSettings);
    query_send(url, initiator, settings);
}



// HELPERS


// проверка на запрос к API
function core_isApi(url) {
    var re = new RegExp('^/' + core_apanelAlias + '/api/v(\\d+)/.');
    return re.test(url);
}

// получение ключа аутентификации
function core_getAuthKey() {
    return Cookies.get(core_cookieAuthKeyName);
}

// вызов пользовательской функции
function core_callUserFunc(funcName, data) {
    if (core_isUserFuncExists(funcName)) {
        return window[funcName](data);
    }

    return false;
}

// проверка существования пользовательской функции
function core_isUserFuncExists(funcName) {
    return typeof window[funcName] == 'function' ? true : false;
}



// УВЕДОМЛЕНИЯ


// шаблон прелоадера
function getPreloader() {
    return $('<i/>', {
        'class': 'fa fa-spinner fa-spin',
    });
}

// шаблон для стандартных уведомлений
function getNoticeTpl(message='', caption='', type='') {
    var noticeClass = '';
    var noticeIcon  = '';

    switch (type) {
        case 'error':
            noticeClass = 'alert alert-danger listing-notice';
            noticeIcon  = 'fa fa-times-circle';
            break;

        case 'warning':
            noticeClass = 'alert alert-warning listing-notice';
            noticeIcon  = 'fa fa-exclamation-circle';
            break;

        case 'info':
        case 'loading':
            noticeClass = 'alert alert-info listing-notice';
            noticeIcon  = 'fa fa-info-circle';
            break;

        case 'success':
            noticeClass = 'alert alert-success listing-notice';
            noticeIcon  = 'fa fa-check-circle';
            break;

        default:
            return '';
    }

    var notice = $('<div/>', {
            'class': noticeClass,
        })
        .append(
            $('<h4/>')
                .append(
                    $('<i/>', {
                        'class': noticeIcon,
                    })
                )
                .append(' ' + caption)
        )
        .append(message + ' ');

    if (type == 'loading') {
        notice.append(getPreloader());
    }

    // удаляем все нотисы, что активны
    $('.listing-notice').remove();

    return notice;
}

// уведомление о загрузке данных
function showDataLoadingPreloader(message='Пожалуйста, ожидайте...', caption='Загрузка данных') {
    return getNoticeTpl(message, caption, 'loading');
}

// неизвестная ошибка
function errorUndefined(message='Не удалось обработать Ваш запрос. Попробуйте позже.', caption='Неизвестная ошибка') {
    return getNoticeTpl(message, caption, 'error');
}

// 404 ошибка
function error404(message='Запрашиваемый ресурс не найден.', caption='404 Not Found') {
    return getNoticeTpl(message, caption, 'error');
}

// 500 ошибка
function error500(message='Упс! На сервере пошло что-то не так! Обратитесь к Вашему администратору.', caption='500 Internal Server Error') {
    return getNoticeTpl(message, caption, 'error');
}

// вывод информации от системы
function showInfo(message, caption) {
    return getNoticeTpl(message, caption, 'info');
}

// вывод предупреждения
function showWarning(message='Во время обработки запроса возникла ошибка.', caption='Что-то пошло не так') {
    return getNoticeTpl(message, caption, 'warning');
}



// ОТРИСОВКА UI


function ui_toFormCreateUpdateElements(response) {
    if (typeof modalForm == 'undefined') {
        console.log('modalForm not found');
        return;
    }

    var
        modal       = modalForm,
        form        = ui_createForm(),
        colorPicker = false;

    // передан корректный ассоциативный массив
    if (typeof response == 'object' && response.length === undefined) {
        for (var key in response) {
            var
                data        = key.split('-'),
                moduleStrId = data.shift(),
                attrName;

            if (data.length > 1) {
                attrName = data.join('-');
            } else {
                attrName = data.shift();
            }

            data = {
                'moduleStrId' : moduleStrId,
                'attrName'    : attrName,
            };

            switch (response[key].validator.name) {
                case 'string':
                    form.append(ui_createStringElement(data, response[key].attrLabel, response[key].value));
                    break;

                case 'color':
                    form.append(ui_createColorElement(data, response[key].attrLabel, response[key].value));
                    colorPicker = true;
                    break;
            }
        }
    }

    $('.modal-body', modal).html('').append(
        $('<div/>', {
            'class': 'row',
        }).append($('<div/>', {'class': 'col-md-12'}).append(form))
    );

    // задаём метод для формы;
    // берем из data кнопки
    $('form', modal).attr('method', saveButton.data('method'));

    if (colorPicker) {
        $('.input-colorpicker').colorpicker({format: 'hex'});
    }
}

// создаём форму
function ui_createForm(method) {
    return $('<form/>', {
        'method' : '',
        'class'  : 'form-horizontal form-bordered',
    });
}

// обёртка для любых элементов
function ui_createElementWrapper(data, attrLabel, element) {
    var helpBlock = $('<span/>', {
        'class': 'help-block',
    });

    return $('<div/>', {
        'class': 'form-group',
    }).append(
        $('<label/>', {
            'class' : 'col-md-2 control-label',
            'for'   : data.moduleStrId + '-' + data.attrName,
        }).append(attrLabel)
    ).append(
        $('<div/>', {
            'class': 'col-md-9',
        }).append(element).append(helpBlock)
    );
}

// создаём строковой элемент
function ui_createStringElement(data, attrLabel, value) {
    var input = $('<input/>', {
        'type'          : 'text',
        'id'            : data.moduleStrId + '-' + data.attrName,
        'name'          : data.moduleStrId + '[' + data.attrName + ']',
        'class'         : 'form-control',
        'placeholder'   : attrLabel,
        'value'         : value,
    });

    return ui_createElementWrapper(data, attrLabel, input);
}

// создаём элемент цвета (выбор цвета)
function ui_createColorElement(data, attrLabel, value) {
    var input = $('<input/>', {
        'type'          : 'text',
        'id'            : data.moduleStrId + '-' + data.attrName,
        'name'          : data.moduleStrId + '[' + data.attrName + ']',
        'class'         : 'form-control',
        'placeholder'   : attrLabel,
        'value'         : value,
    });

    var group = ui_createElementWrapper(data, attrLabel, input);

    $('input', group).wrap(
        $('<div/>', {
            'class': 'input-group input-colorpicker',
        }))
    .after(
        $('<span/>', {
            'class': 'input-group-addon',
        }).append($('<i/>'))
    );

    return group;
}



// РАБОТА С ФОРМОЙ


// определяем как обрабатываем форму
function query_processModel() {
    if (typeof modalForm == 'undefined') {
        console.log('modalForm not found');
        return;
    }

    var form = $('form', modalForm);

    if (form.attr('method') == 'POST') {
        return query_createModel(form);
    }

    if (form.attr('method') == 'PUT') {
        return query_updateModel(form);
    }
}

// запрос на добавление новой модели
function query_createModel(form) {
    var url = saveButton.attr('href');

    query_sendPost(url, form, {
        'data': form.serializeArray(),
    });
}

// запрос на обновление модели
function query_updateModel(form) {
    var url = saveButton.attr('href');

    query_sendPut(url, form, {
        'data': form.serializeArray(),
    });
}

