 $(document).ready(function() {

     $("#infoBlock").hide();

     $(".eye-info").on('click', function(event) {
         // event.preventDefault();
         // console.log("Привет");
         if ($('#passwd_form input').attr("type") == "text") {
             $('#passwd_form input').attr('type', 'password');
             $('#passwd_form i').addClass("fa-eye-slash");
             $('#passwd_form i').removeClass("fa-eye");
         } else if ($('#passwd_form input').attr("type") == "password") {
             $('#passwd_form input').attr('type', 'text');
             $('#passwd_form i').removeClass("fa-eye-slash");
             $('#passwd_form i').addClass("fa-eye");
         }
     });


     // SUBMIT registration form
     (function($) {
         //function disable and hide password group
         $.fn.disable_pwd_group = function() {
             this.click(function() {
                 $("[name=forgot]").removeAttr("disabled");
                 $("#pwd").attr("disabled", "disabled");
                 $("#password-block").hide();
                 $("#submit").text(recall_password);
                 $("#i_foret_password").hide();
             })
             return true;
         };
     })(jQuery);

     (function($) {
         $.fn.restore_pwd_group = function() {
             $("[name=forgot]").attr("disabled", "disabled");
             $("#pwd").removeAttr("disabled");
             $("#password-block").show();
             $("#submit").text(Submit_enter);
         }
     })(jQuery);


     var frm = $('#login');
     frm.submit(function(e) {
         $("#shutter").css("display", "block");
         e.preventDefault();

         $.ajax({
             type: frm.attr('method'),
             url: "login.php", //frm.attr('action'),
             data: frm.serialize(),
             success: function(data) {
                 var result = JSON.parse(data);
                 console.log(result);
                 if (result['recall_pwd'] == 1) {
                     // document.location.href ='Я всё забыл';
                     console.log('Зри в почту');
                     $("#infoBlock").show().text(passwd_recall_info);
                     $().restore_pwd_group();
                 } else if (result['recall_pwd'] == 0) {
                     console.log("Нет такого!");
                     $("#infoBlock").show().text("Unknown E-mail!");
                 } else if (result['user_id'] != false && result['user_id'] != 'undefined') {
                     console.log("Submission was successful.");
                     // console.log(result);
                     document.location.href = SITE + '/?user_id=' + result['user_id'];
                 } else {
                     $("#infoBlock").show().html(isMistake);
                     console.log("E-mail exist in DB!");
                 }

                 // console.log(data);
                 $("#shutter").css("display", "none");
                 // document.location.reload(true);

             },
             error: function(data) {
                 console.log('An error occurred.');
                 console.log(data);
                 // $("#infoBlock").text('Unknown error!');
                 $("#shutter").css("display", "none");
             }
         });
     });

     $("#addThesises").click(function() {
         window.location.assign(SITE + "/thesis.php?newthesis=1");
     })


     $("#i_foret_password").disable_pwd_group();


 });



 $(document).ready(function() {

     $("#passwd_form").on({
         "click focus keydown": function() {
             $("#pwdButtonGrp button").show();
         },
         "blur": function() {
             $("#pwdButtonGrp button").hide();
         }
     });

     $("#cancelPwd").click(function() {
         $("#chPassword").val("");
         $("#pwdButtonGrp button").hide();
     });

     function savePwd() {
         $.post("ajax/password_update.php", {

                 "passwd": $("#chPassword").val()
             })
             .done(function(data) {
                 if (data == 1) {
                     console.log(data);
                     $("#pwdButtonGrp button").hide();
                     $("#chPassword").val("");
                     $("#pwdButtonGrp p.saved").show(500).delay(1000).hide(700);
                 }
             })
             .fail(function() {
                 console.log("..ts happen");
             });
     }

     $("#changePwd").click(function() {
         savePwd();

     });

     $("#chPassword").keydown(function(e) {
         if (e.keyCode == 13) {
             savePwd();
         }
     });

     $("#savePassport").click(function() {
         //passport_save.php
         $.post("ajax/passport_save.php", {
                 "passport": $("#passport").text()
             })
             .done(function(data) {
                 if (data == 1) {
                     console.log(data);
                     console.log("Данные сохранены");
                     $("#passport_info").fadeOut(400).html("<span class='text-success'>Данные сохранены</span>").fadeIn(400);
                     // $("#pwdButtonGrp button").hide();
                 }
             })
             .fail(function() {
                 console.log("..ts happen");
             });
     });

     $("#passport").click(function() {
         $("#passport_info").fadeOut(400).html("<span class='text-danger'>Не забудьте сохранить данные</span>").fadeIn(400);
     });

     $("#online").click(function() {
         $("#passport").text("Участвую онлайн");
         $("#savePassport").click();
     })

     $('#liftupload').on('click', function() {
         var file_data = $('#lift').prop('files')[0];
         var form_data = new FormData();
         form_data.append('file', file_data);
         //  alert(form_data);
         $.ajax({
             url: 'ajax/lift_upload.php',
             dataType: 'text',
             cache: false,
             contentType: false,
             processData: false,
             data: form_data,
             type: 'post',
             success: function(php_response) {
                 $("#lift_info").html(php_response);
                 $('#lift').val("");
                 $('#file_uploaded').modal();

                 //  alert(php_response);
             }
         });
     });

     $('#poster_upload').on('click', function() {
         var file_data = $('#poster').prop('files')[0];
         console.log(file_data.size, file_data.type);
         var form_data = new FormData();
         form_data.append('file', file_data);
         //  alert(form_data);
         $.ajax({
             url: 'ajax/poster_upload.php',
             dataType: 'text',
             cache: false,
             contentType: false,
             processData: false,
             data: form_data,
             type: 'post',
             success: function(php_response) {
                 $("#poster_info").html(php_response);
                 //  alert(php_response);
                 $('#poster').val("");
                 $('#file_uploaded').modal();
             }
         });
     });


     // $("input[name='excursion']").click(function(e){
     $(document).on('click', '#excursion', function() {
         //  $('#test').text('туц-туц');
         var excursion_status;
         if ($(this).is(':checked')) {
             excursion_status = true;
         } else excursion_status = false;

         console.log("Set Status as " + excursion_status);

         $.post("ajax/excursion_status_update.php", {

                 "excursion_status": excursion_status
             })
             .done(function(status) {
                 console.log("Status is " + status);
                 if (status == 1) {
                     console.log("Excursion is " + status);
                 }

             })
             .fail(function() {
                 console.log("..ts happen");
             });


     });

 });