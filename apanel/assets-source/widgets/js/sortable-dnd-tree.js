(function($, undefined) {

    // плагин сортировки любого дерева;
    // плагин расширяет возможности плагина nestable и работает только тесно с ним
    jQuery.fn.sortableDndTree = function(options) {

        // ПЕРЕМЕННЫЕ
        //

        var
            // настройки
            settings = $.extend({
                'debug': false,
            }, options),

            // флаг инициализации контейнера
            initialized = this.data('sortableDndTree'),

            // контейнер со структурой
            container = this;



        // ФУНКЦИИ
        //        

        // просчитываем порядок следования всех элементов
        var setOrdinalsIds = function() {
            var liElems = $('.dd-item', container);

            if (settings.debug) {
                console.log('setOrdinalsIds function called');
                console.log('-- liCount: ' + liElems.length);
            }

            liElems.each(function(i) {
                $(this).data('ordinaly-id', i + 1);

                if (settings.debug) {
                    console.log('>> sorting: ' + $(this).data('sorting') + ' >> ordinal id: ' + $(this).data('ordinalyId'));
                }
            });
        };

        // функция пересортировки данных
        var reSortingData = function(liElems) {
            var liCnt = liElems.length;

            if (settings.debug) {
                console.log('reSortingData function called');
                console.log('-- liCount: ' + liCnt);
            }

            // ничего не делаем, если элементов меньше, чем 2
            if (liCnt <= 1) {
                return;
            }

            liElems.each(function(i) {
                // первый элемент
                var firstElem = liElems.eq(i);
                // элемент, следующий за первым
                var secondElem = liElems.eq(i + 1);

                if (settings.debug) {
                    console.log('---- process li in pos ' + i);
                    console.log('---- second elem exist flag: ' + secondElem.length);
                }

                // второй элемент существует
                if (secondElem.length) {
                    var sorting1 = firstElem.data('sorting');
                    var sorting2 = secondElem.data('sorting');

                    // выше стоит элемент старше, меняем их местами
                    if (sorting1 > sorting2) {
                        firstElem.data('sorting', sorting2);
                        secondElem.data('sorting', sorting1);
                    }
                }
            });
        };



        // РЕАЛИЗАЦИЯ
        //

        // проверка флага инициализации
        if (initialized) {
            if (settings.debug) {
                console.log('sortableDndTree already inited with status: ' + initialized + '; canceled.');
            }
            return false;

        } else {
            if (settings.debug) {
                console.log('sortableDndTree is initing...');
            }
            this.data('sortableDndTree', 'initialized');
        }

        // структура была изменена
        this.on('dndTreeChanged', function() {
            if (settings.debug) {
                console.log('dndTree was changed');
            }

            // просчитываем порядок следования всех элементов
            setOrdinalsIds();

            // все ol эементы
            $('ol', container).each(function() {
                // бежим по каждому уровню и просчитываем порядок li элементов
                var liElems = $('> li', $(this));
                reSortingData(liElems);
            });

            // просчитываем порядок следования всех элементов
            setOrdinalsIds();
        });
    };

})(jQuery);

