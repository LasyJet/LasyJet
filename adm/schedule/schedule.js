// let td_editable = "td:nth-child(2), td:nth-child(3), td:nth-child(4)";
let td_editable = "td:not(:first-child, :last-child)";

// Return today's date and time
var currentTime = new Date()
    // var month = currentTime.getMonth() + 1
    // var day = currentTime.getDate()
var currentYear = currentTime.getFullYear()

function renumerate() {
    $('#schedule tbody tr:not(:last-child) td:first-child').each(function(i) {
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
        $('#event').css('color', 'red').text('изменено');
    }

}



var report_idx, duration_idx, time_idx;

var duration_length = {
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
            case 'Date':
                date_idx = $(this).index();
                break;
            default:
                break;
        }
    });

    //спрячем колонку с thesis_id
    $('.schedule td:last-child, .schedule th:last-child').hide();

    // сделаем редактируемой таблицу расписания
    $("table tr").each(function() {
        $(this).children(td_editable).attr('contenteditable', 'true');
    });

    // +++ заполнение столбца Duration значениями соответсвующими типу доклада
    $("table#itemSchedule tr").each(function() {
        let this_report = $(this).children('td:eq(' + report_idx + ')').text();
        let this_duration = duration_length[this_report];
        $(this).children('td:eq(' + duration_idx + ')').text(this_duration);
    });

    // +++ очистим столбец Date от нулевых значений
    $("table#Schedule tr").each(function() {
        let this_date = $(this).children('td:eq(' + date_idx + ')').text();
        if (this_date == '00.0') {
            $(this).children('td:eq(' + date_idx + ')').text('');
        }
    });

    $("table#Schedule tr").each(function() { //сделаем дату пожирнее
        $(this).children("td:eq(" + date_idx + ")").css("font-weight", "bold");
    });


    $("#Schedule").on('keyup', '[contenteditable]', function() { //сигнал об изменении 
        saveStatus('changed');
    });

    $("#itemSchedule tbody td:first-child").css("text-align", "center");

    $("#helpme").click(function() {
        $("#help").dialog({
            width: "42em"
        });
    })

    // пересчитать время в таблице
    function recalcTime() {
        let next_time = "0:0";
        $("table#Schedule tbody tr").each(function() {
            let obj_date = $(this).children('td:eq(' + date_idx + ')');
            let obj_time = $(this).children('td:eq(' + time_idx + ')');
            let obj_duration = $(this).children('td:eq(' + duration_idx + ')');
            // let this_time = obj_time.text();
            let this_duration = obj_duration.text();
            let this_date = obj_date.text();

            if (this_duration == 0) {
                obj_time.text('');
                obj_duration.text('');
                return true;
            }

            // if (this_duration == '-') {

            // }

            // if ($(this).index() != 0) {
            if (this_date == '') {
                obj_time.text(next_time);
            } else {
                next_time = obj_time.text();
            }
            next_time = sumTime(next_time, "0:" + this_duration);
        });
    }
    recalcTime();

    //добавление сортировки перетаскиванием
    $("tbody.connectedSortable")
        .sortable({
            cancel: '[contenteditable], tr:last-child',
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

    renumerate();

    // контекстное меню
    var target;
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

        // $(document).on('click', '#duplicateRow', function() {
        //     var newRow = parent.clone(true).insertAfter(parent);
        //     rowProperties(newRow);
        //     renumerate();
        //     saveStatus('changed');
        // });

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

    $("#schedule tbody tr:last-child td").each(function() {
        $(this).attr('contenteditable', 'false');
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
        $("#schedule tbody tr:not(:last-child)").each(function(i, elm) {
            let row = {};
            $.each(keys, function(n, val) {
                row[val] = $("td:eq(" + n + ")", elm).html().trim();
                if (val == 'Date') { //  format date to YYYY-MM-DD
                    date_parts = row[val].split('.');
                    row[val] = currentYear + "-" + date_parts[1] + "-" + date_parts[0];
                }

            });
            data.push(row);
        });

        var jsonData = JSON.stringify(data);
        // console.log(jsonData);
        var saveData = $.post("save.php", { data: jsonData });
        saveData.done(function(result) {
            result = result.trim()
            if (result == 'saved') {
                saveStatus('saved');
            }
        });
        console.log(jsonData);
    });

});