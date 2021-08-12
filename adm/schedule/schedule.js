function renumerate() {

    $('tbody td:first-child').each(function(i) {
        let num = i + 1;
        $(this).html(num);
        // $(this).click(function() { console.log(num) });
        $(this).addClass('context-menu');

    });
}

var target, thisId;

function rowA(e) {
    console.log("A", thisId);
    // alert(e.data.t);
}

function rowB(e) {
    console.log("B", thisId);
    // alert(e.data.t);
}

$(document).ready(function() {


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

    $(document).on('click', '#addRowBelow', { t: thisId }, rowA);
    $(document).on('click', '#addRowAfter', { t: thisId }, rowB);

    // $(document).on('click', '#addRowBelow', { t: thisId }, rowA);
    // $(document).on('click', '#addRowAfter', { t: thisId }, rowB);


    let = contextMenu = $('.context-menu-open');
    $('.context-menu').on('contextmenu', function(e) {
        e.preventDefault();
        contextMenu.css({ top: e.clientY + 'px', left: e.clientX + 'px' });
        contextMenu.show();
        var target = $(e.target);
        parent = target.parent();
        thisId = parent.find('td:eq(2)').attr('title');
        // console.log(thisId);
        // $(document).off('click', '#addRowBelow');
        // $(document).off('click', '#addRowAfter');

    });
    $(document).on('click', function() {
        contextMenu.hide();
    });




});