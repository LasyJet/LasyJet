// https: //www.pvsm.ru/javascript/32431
document.oncontextmenu = function() { return false; };

// $(document).ready(function() {

// Вешаем слушатель события нажатие кнопок мыши для всего документа:

$(document).mousedown(function(event) {

    // Убираем css класс selected-html-element у абсолютно всех элементов на странице с помощью селектора "*":
    // $('*').removeClass('selected-html-element');
    // Удаляем предыдущие вызванное контекстное меню:
    // $('div.context-menu').hide();
    // $('div.context-menu').css('display', 'none');
    // console.log(event.which);
    // Проверяем нажата ли именно правая кнопка мыши:   
    if (event.which === 3) {
        // Получаем элемент на котором был совершен клик:
        var target = $(event.target);


        // Добавляем класс selected-html-element что бы наглядно показать на чем именно мы кликнули (исключительно для тестирования):
        target.addClass('selected-html-element');

        $('div.context-menu')
            .css({
                left: event.pageX + 'px', // Задаем позицию меню на X
                top: event.pageY + 'px' // Задаем позицию меню по Y
            })
            .css('display', 'block');
        // .show();

    }


});