var persdData; // array(object) for data from database email, name, etc
var user_id;

$(document).ready(function() {

    $("#recipients_list").text("Нет адресатов");

    $('#recipients').on("load change mouseover", function() { // получение группы адресатов по изменению выбора в списке 
        // $('#recipients').change(function() {// получение группы адресатов по изменению выбора в списке 

        var this_recipients_group = $(this).val();
        if (this_recipients_group != "-") {
            $.post("email_select_recp.php", { recipients: this_recipients_group })
                .done(function(result) {
                    if (Object.keys(result).length > 4) { // console.log("#"+Object.keys(result).length);
                        $("#send").prop("disabled", false);
                        $("#recipients_list").text(' ');
                        persData = JSON.parse(result);
                        persData.forEach(function(pers) {
                            var name = "<p>" + pers.familyname + " " + pers.givenname + " " + pers.parentname + "</p>";
                            var email = "<p>" + pers.email + "</p>";
                            var item = "<div class='speaker' id='" + pers.user_id + "'>" + name + email + "</div>";
                            $("#recipients_list").append(item);
                        });

                    } else {
                        $("#recipients_list").text("Нет адресатов");
                        $("#send").attr("disabled", "disabled");

                    }
                });
        } else {
            $("#recipients_list").text("Нет адресатов");
            $("#send").attr("disabled", "disabled");
            console.log("no selected item");
        }

    });


    // SUBMIT mail form
    var frm = $('#mailform');
    frm.submit(function(e) {

        e.preventDefault();

        var formData = $(this).serializeArray();

        // persData.forEach(function(pers){});
        for (var N in persData) {
            pers = persData[N];
            console.log(pers);
            user_id = pers["user_id"];
            current = formData; // всякий раз отпрвляется данные + user_id
            current.push({ name: 'user_id', value: user_id });

            // $("#" + user_id).addClass("border border-warning");

            send_mail(current, function(res_id) {
                console.log("id=" + res_id, user_id);
                $("#" + res_id).hide(1000);
                /* if (res_id == user_id) {
                    $("#" + res_id).hide().remove();
                }
                else {
                    console.log("не судьба");
                };
 */
            })
        };
    });



    $('#message').autoResize(); // resize height of textarea
});



function send_mail(user_data, callback) { //data contain user_id
    var res_id = 0;
    $.ajax({
        type: 'POST',
        url: "email_send.php",
        data: $.param(user_data),
        async: false,
        success: function(srvData) { // server returns user_id if success
            res_id = srvData;
            // console.log(JSON.parse(res_id));
        },
        error: function(srvData) {
            console.log('An error occurred:' + srvData);
        },
    });
    callback(res_id.trim());
}