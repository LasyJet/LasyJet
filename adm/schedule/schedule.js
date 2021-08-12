// let td_editable = "td:nth-child(2), td:nth-child(3), td:nth-child(4)";
let td_editable = "td:not(:first-child, :last-child)";

function renumerate() {
    $('tbody td:first-child').each(function(i) {
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
    return hours + ":" + mins;
};

var target, thisId; // parent;


$(document).ready(function() {

    $('.schedule td:last-child, .schedule th:last-child').hide();

    $("table#Schedule tr").each(function() {
        // $("tbody.connectedSortable tr").each(function() {
        $(this).children(td_editable)
            .attr('contenteditable', 'true');
    });


    // заполнение ячеек Duration значениями соответсвующими типу доклада
    var report_idx, duration_idx;
    let duration_length = {
        'plenary': 40,
        'invited': 20,
        'oral': 15,
    };

    $("table#itemSchedule tr th").each(function() {
        let header = $(this).text();
        switch (header) {
            case 'Report':
                report_idx = $(this).index();
                break;
            case 'Duration':
                duration_idx = $(this).index();
                break;
            default:
                break;
        }

    });


    $("table#itemSchedule tr").each(function() {
        let this_report = $(this).children('td:eq(' + report_idx + ')').text();
        let this_duration = duration_length[this_report];
        $(this).children('td:eq(' + duration_idx + ')').text(this_duration);

    });




    $("tbody.connectedSortable")
        // $("tbody")
        .sortable({
            cancel: '[contenteditable]',
            connectWith: ".connectedSortable",
            items: "> tr",
            helper: "clone",
            zIndex: 999990,
            // update: function() {
            //     renumerate();
            // }
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
            // console.log("B");
        });

        $(document).on('click', '#deleteRow', function() {
            parent.remove();
            // console.log("del");
            renumerate();
        });

        $(document).on('click', '#addRowAfter', function() {
            var newRow = parent.clone(true).insertAfter(parent);
            rowProperties(newRow);
        });

        // $(document).off('click', '#addRowBelow');
        // $(document).off('click', '#addRowAfter');
    });

    $(document).on('click', function() {
        contextMenu.hide();
        $(document).off('click', '#addRowBelow');
        $(document).off('click', '#addRowAfter');
    });


    $("#save").click(function() {

        var keys = [];
        $("#schedule thead th").each(function() {
            var k = $(this).text().trim();
            keys.push(k);
        });
        // console.log(keys);

        var data = [];
        $("#schedule tbody tr").each(function(i, elm) {
            // console.log(i+"="+elm);
            let row = {};
            $.each(keys, function(n, val) {
                row[val] = $("td:eq(" + n + ")", elm).text().trim();
            });
            data.push(row);
        });
        // console.log(data);
        var jsonData = JSON.stringify(data);
        var saveData = $.post("save.php", { data: jsonData });
        saveData.done(function(result) {
            console.log("RESULT IS: " + result);
        });
        // console.log(jsonData);
    });

    // console.log(sumTime("09:00", "00:15"));

});