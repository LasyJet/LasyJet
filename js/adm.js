$(document).ready(function() {
    //GET FIELD NAMES
    var fld = [];
    $("thead th").each(function() {
        fld.push($(this).attr('id'));
    })



    //set object with properties as filed=num_col
    var numCol = {};
    $.each(fld, function(index, value) {
            numCol[value] = $("#" + value).index();
        })
        // console.log(numCol);

    // SET DECORATIO AND ACTION ON DELETE CELL
    var user_deleted = "<i class='fa fa-user-times text-danger'></i>";
    var user_exist = "<i class='fa fa-user text-success'></i>";
    $("tbody tr").each(function() {
        thisCell = $(this).children("td:eq(" + numCol.deleted + ")");

        if (thisCell.text() == "-") thisCell.html(user_exist);
        else {
            thisCell.html(user_deleted);
            thisCell.parent("tr").addClass("text-deleted");
        }

        thisCell.click(function() {
            var thisCell = $(this);
            id = $(this).parent("tr").children("td:eq(" + numCol.id + ")").text();
            name = $(this).parent("tr").children("td:eq(" + numCol.familyname + ")").text();
            console.log("This id: " + id);
            bootbox.confirm("Точно удалить " + name + ": id=" + id + "?", function(result) {
                if (result) {
                    $.post("../ajax/adm_del_speaker.php", { "id": id })
                        .done(function(result) {
                            if (result) {
                                console.log(id + " deleted");
                                console.log(thisCell.html(user_deleted));
                                thisCell.parent("tr").addClass("text-deleted");
                            } else {
                                console.log("Error happen");
                            }
                        });

                } else console.log(id + " canceled");
            });

        });

    });

    //SET Actions ON columns
    $("table tr").each(function() {
        numSect = $(this).children("td:eq(" + numCol.section + ")");
        num = numSect.text();
        numSect.text(sections[num]);
        numSect.data("sectNum", num);


        //add link to thesis
        thesCell = $(this).children("td:eq(" + numCol.thesis_id + ")");
        thesLink = thesCell.text();
        thesCell.html("<a title='Посмотреть' href='../thesis.php?id=" + thesLink + "' class='text-primary'>" + thesLink + "</a>");

        //CHANGE SECTION
        sectCell = $(this).children("td:eq(" + numCol.section + ")");
        sectCell.addClass('text-primary');
        sectCell.click(function() {
            $(this).editable("../ajax/adm_sect_update.php", {
                type: "select",
                tooltip: "выбрать секцию",
                data: JSON.stringify(sections),
                // submit: "OK",
                // cancel: "&times;",
                submitdata: function() {
                    var thisId = $(this).closest("tr").children("td:eq(" + numCol.thesis_id + ")").text();
                    console.log("selected id=" + thisId);
                    return { id: thisId };
                },
                intercept: function(result) {
                    var n = parseInt(result);
                    // console.log('n: ', n);
                    $(this).data("sectNum", parseInt(n));
                    return sections[parseInt(n)];
                }
            });

        });

        //CHANGE REPORT
        if($('#report_type').length){ // чтобы не влияло на колонки других страниц
        reportCell = $(this).children("td:eq(" + numCol.report_type + ")");
        reportCell.addClass('text-black').css({ "text-decoration": "underline", "cursor": "pointer" });
        reportCell.click(function() {
            $(this).editable("../ajax/adm_report_update.php", {
                type: "select",
                tooltip: "выбрать тип доклада",
                data: report_types,
                // submit: "OK",
                // cancel: "&times;",
                submitdata: function() {
                    var thisId = $(this).closest("tr").children("td:eq(" + numCol.thesis_id + ")").text();
                    console.log("selected id=" + thisId);
                    return { id: thisId };
                },
                intercept: function(result) {
                    console.log(result);
                    // var n = parseInt(result);
                    // console.log('n: ', n);
                    // $(this).data("sectNum", parseInt(n));
                    return result;
                }
            });

        });
        }

    });

});


$.extend(true, $.fn.dataTable.defaults, {
    // "searching": false,
    // "ordering": false,
    "language": {
        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Russian.json"
    }
});

$(document).ready(function() {
    fewGrades = $('div#few_grades table tr');
    fewGrades.each(function() {
        Cell = $(this).children("td:eq(0)");
        ID = Cell.text();
        console.log(ID);
        Cell.html("<a title='Оценить' href='../adm/theses.php#" + ID + "' class='text-primary'>" + ID + "</a>");

    });

});


//apply DataTable functions
$(document).ready(function() {

    $("table").addClass("table table-sm table-hover table-striped");

    $('div.stat table').addClass('p-1');

    $('div.stat table').DataTable({
        "scrollY": "50vh",
        "scrollCollapse": true,
        "paging": false,
        "order": [
            [1, "desc"]
        ],
        "searching": false,
        // "ordering": false,
        "paging": false,
        // "info":     false
    });

    // $('div#experts_rating table, div#count_theses_grades table').addClass("cell-border compact");
    $('div#experts_rating table').DataTable({
        "scrollY": "50vh",
        "scrollCollapse": true,
        "paging": false,
        "order": [
            [2, "desc"]
        ],
        // "ordering": false,
        "paging": false,
        // "info":     false
    });

    // $('div#count_theses_grades table').DataTable({
    //     "scrollY": "50vh",
    //     "scrollCollapse": true,
    //     "paging": false,
    //     "order": [
    //         [2, "desc"]
    //     ],
    //     // "ordering": false,
    //     "paging": false,
    //     // "info":     false
    // });


    $('div#grades table').DataTable({
        "scrollY": "50vh",
        "scrollCollapse": true,
        "paging": true,
        "order": [
            [0, "asc"]
        ],
        "columnDefs": [
            { "width": "8rem", "targets": 0 },
            { "width": "8rem", "targets": 1 },
            { "width": "8rem", "targets": 2 },
            { "width": "36rem", "targets": 3 },
            { "width": "36rem", "targets": 4 },
            { "width": "2rem", "targets": -2 },
            { "width": "2rem", "targets": -1 }
        ],
        "lengthMenu": [
                [10, 25, 50, 100, -1],
                ['10', '25', '50', '100', 'All']
            ]
            // "ordering": false,
            // "info":     false
    });

    var table = $('div#grades table').DataTable();
    $('#grades table tbody')
        .on('mouseenter', 'td', function() {
            var colIdx = table.cell(this).index().column;
            $(table.cells().nodes()).removeClass('highlight');
            $(table.column(colIdx).nodes()).addClass('highlight');
        });

    $("#users table").DataTable({
        "order": [
            [0, "desc"]
        ]
    });

    $("#theses table").DataTable({
        "order": [
            [0, "asc"]
        ]
    });

    $('.dataTables_length').addClass('bs-select');


});