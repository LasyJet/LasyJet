function clean_form() {
    $("#title, #coauthors, #text, #literature").text("");
}




$(document).ready(function() {
    // inline select
    // var thesis_id = '<?=$thesis_id?>';
    $(".editable-select").editable("ajax/thesis_sect_update.php", {
        id: "name~" + thesis_id,
        type: "select",
        // this data will be sorted by value
        loadurl: "asset/sections.php",
        submitdata: function() {}, // { return {id : 42, something: 'else'};},
        style: "inherit",
    });


    var init, data, field_name, msg;
    $("[contenteditable='true']").on({

        focus: function(event) {
            init = $(this).html();
            field_name = $(this).attr('id')
        },
        blur: function() {
            data = $(this).html();
            var data_text = $(this).text();
            console.log(data_text.length);
            if (data_text.length + maxOverflow > maxCharLimit) {
                var overChar = data_text.length - maxCharLimit;
                $("#tooMuchLetters .modal-body").append("<span class='text-danger'>" + overChar + " character overflow</span>");
                $("#tooMuchLetters").modal("show");
            } else {
                var obj = $(this);
                // console.log('data '+field_name+"\n "+data);

                if (init != data) {
                    obj.animate({ opacity: 0.1 }, 500, function() { $("#popup").fadeIn('fast') });

                    var request = jQuery.ajax({
                        url: "ajax/thesis_update.php",
                        type: "POST",
                        data: {
                            content: data,
                            field_name: field_name,
                            thesis_id: thesis_id
                        },
                        dataType: "html"
                    });

                    request.done(function(msg) {
                        if (msg == 1) {
                            obj.animate({ opacity: 1 }, 500, function() { $("#popup").fadeOut('fast') });
                        }
                        //  console.log(msg);
                    });
                    request.fail(function(msg) {
                        obj.css('color', 'red');
                    });
                }
                //else console.log('nothind to do');
                // console.log($(this).html());
            }

        }

    });

});



function isCapslock(str) {
    var count = 0,
        low, upp;
    var Letters = [];
    Letters = { 'upperCase': 0, 'totalChars': str.length };
    for (var i = 0; i < str.length; i++) {
        low = str[i];
        upp = low.toUpperCase();
        if (low === upp && low.toLowerCase() !== upp) Letters.upperCase++;
        if (low.toLowerCase() == upp) Letters.totalChars--;
    }

    // console.log(Letters.upperCase+" from "+Letters.totalChars+" in "+$(this).attr('name'));
    tooManyUpC = (Letters.totalChars > 20) && (Math.ceil(Letters.totalChars / 2) < Letters.upperCase);
    console.log(tooManyUpC);
    return tooManyUpC;
}

$(document).ready(function() {
    $("#title").blur(function() {
        var title = $(this).html();
        if (isCapslock(title)) {
            $("#alertTitle").removeClass('alert-info');
            $("#alertTitle").addClass('alert-danger');
        } else {
            $("#alertTitle").removeClass('alert-danger');
            $("#alertTitle").addClass('alert-info');
        }
    });
});


$(document).ready(function() {
    CKEDITOR.dtd.$editable.span = 1;
    CKEDITOR.dtd.$editable.strong = 1;
    CKEDITOR.disableAutoInline = true;
    // addEditor();

});


$(document).ready(function() {
    console.log(Cookies.get('alert_info'));

    if (Cookies.get('alert_info') === undefined) {

        var alertInfo = $("#howToInfo .text").html();
        $("#alertInfo div.modal-body").html(alertInfo);
        $("#alertInfo").modal("show");
        // var exp_time = new Date(new Date().getTime() + 10 * 1000); //ten second
        var exp_time = 1 / 12; //one hour
        Cookies.set('alert_info', '1', { expires: exp_time });
        // Cookies.remove('alert_info');
    }
});