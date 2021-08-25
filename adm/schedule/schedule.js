// let td_editable = "td:nth-child(2), td:nth-child(3), td:nth-child(4)";
let td_editable = "td:not(:first-child, :last-child)";

function renumerate() {
    $('#schedule tbody td:first-child').each(function(i) {
        let num = i + 1;
        $(this).html(num);
        // $(this).click(function() { console.log(num) });
        $(this).addClass('context-menu');
    });
}

function rowProperties(row) {
    row.children().html('&nbsp;');
    row.children(td_editable)
        .attr('contenteditable', 'true')
        .animate({ 'background-color': 'lightgreen' }, 1000)
        .animate({ 'background-color': '#fffa9c' }, 1000);
    renumerate();
}

function sumTime(time1, time2) {

    function toMin(time) { //covert time from "hh:mm" to mimutes
        var tmpTime = time.split(":");
        var min = parseInt(tmpTime[0]) * 60 + parseInt(tmpTime[1]);
        return min;
    }

    var sumTime = toMin(time1) + toMin(time2);
    var hours = (sumTime - sumTime % 60) / 60;
    var mins = sumTime - hours * 60;
    hours = hours.toString();
    mins = mins.toString();
    if (hours.length == 1) hours = "0" + hours;
    if (mins.length == 1) mins = "0" + mins;
    return hours + ":" + mins;
};

function saveStatus(status) {
    if (status == 'saved') {
        $('#event').css('color', 'green').text('сохранено');
    } else if (status == 'changed') {
        $('#event').css('color', 'red').text('иземено');
    }

}

var target, thisId; // parent;

var report_idx, duration_idx, time_idx;

let duration_length = {
    'plenary': 40,
    'invited': 20,
    'oral': 15,
};



$(document).ready(function() {

    //выясним индексы столбцов
    $("table#itemSchedule tr th").each(function() {
        let header = $(this).text();
        switch (header) {
            case 'Report':
                report_idx = $(this).index();
                break;
            case 'Duration':
                duration_idx = $(this).index();
                break;
            case 'Time':
                time_idx = $(this).index();
                break;
            default:
                break;
        }
    });

    //спрячем колонку с thesis_id
    $('.schedule td:last-child, .schedule th:last-child').hide();

    // сделаем редактируемымой таблицу расписания
    $("table#Schedule tr").each(function() {
        $(this).children(td_editable).attr('contenteditable', 'true');
    });

    // +++ заполнение столбца Duration значениями соответсвующими типу доклад
    $("table#itemSchedule tr").each(function() {
        let this_report = $(this).children('td:eq(' + report_idx + ')').text();
        let this_duration = duration_length[this_report];
        $(this).children('td:eq(' + duration_idx + ')').text(this_duration);
    });


    // пересчитать время
    function recalcTime() {
        let next_time = "0:0";
        $("table#Schedule tbody tr").each(function() {
            let obj_time = $(this).children('td:eq(' + time_idx + ')');
            let obj_duration = $(this).children('td:eq(' + duration_idx + ')');
            // let this_time = obj_time.text();
            let this_duration = obj_duration.text();

            if (this_duration == 0) {
                obj_time.text('');
                obj_duration.text('');
                return true;
            }
            // console.log(this_time, this_duration, "=", sumTime(this_time, "0:" + this_duration));

            if ($(this).index() != 0) {
                obj_time.text(next_time);
            } else {
                next_time = obj_time.text();
            }
            console.log(next_time);
            next_time = sumTime(next_time, "0:" + this_duration);

        });
    }
    recalcTime();

    //добавление сортировки перетаскиванием
    $("tbody.connectedSortable")
        .sortable({
            cancel: '[contenteditable]',
            connectWith: ".connectedSortable",
            items: "> tr",
            helper: "clone",
            zIndex: 999990,
            // placeholder: "ui-state-highlight",
            update: function() {
                renumerate();
                saveStatus('changed');

            }
        });
    // .disableSelection();
    // var $tab_items = $(".nav-tabs > li").droppable({
    //     accept: ".connectedSortable tr",
    //     hoverClass: "ui-state-hover",
    //     drop: function(event, ui) {
    //         return false;
    //     }
    // });

    renumerate();

    // контекстное меню
    let = contextMenu = $('.context-menu-open');
    $('.context-menu').on('contextmenu', function(e) {
        e.preventDefault();
        contextMenu.css({ top: e.clientY + 'px', left: e.clientX + 'px' });
        contextMenu.show();
        var target = $(e.target);
        parent = target.parent();

        $(document).on('click', '#addRowBelow', function() {
            var newRow = parent.clone(true).insertBefore(parent);
            rowProperties(newRow);
            saveStatus('changed');
        });

        $(document).on('click', '#deleteRow', function() {
            parent.remove();
            renumerate();
            saveStatus('changed');
        });

        $(document).on('click', '#addRowAfter', function() {
            var newRow = parent.clone(true).insertAfter(parent);
            rowProperties(newRow);
            saveStatus('changed');
        });
    });

    $(document).on('click', function() {
        contextMenu.hide();
        $(document).off('click', '#addRowBelow');
        $(document).off('click', '#addRowAfter');
        renumerate();
    });

    // сохранение изменений
    $("#save").click(function() {
        renumerate();
        recalcTime();

        var keys = [];
        $("#schedule thead th").each(function() {
            var k = $(this).text().trim();
            keys.push(k);
        });

        var data = [];
        $("#schedule tbody tr").each(function(i, elm) {
            let row = {};
            $.each(keys, function(n, val) {
                row[val] = $("td:eq(" + n + ")", elm).html().trim();
            });
            data.push(row);
        });

        var jsonData = JSON.stringify(data);
        var saveData = $.post("save.php", { data: jsonData });
        saveData.done(function(result) {
            result = result.trim()
            if (result == 'saved') {
                saveStatus('saved');
            }
        });
        // console.log(jsonData);
    });

    // console.log(sumTime("9:05", "10:5"));

});