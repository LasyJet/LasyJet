    $(document).ready(function() {
      
      $("#loader").hide();
      $("#show_hide_password a").on('click', function(event) {
          event.preventDefault();
          // console.log("Привет");
          if($('#show_hide_password input').attr("type") == "text"){
              $('#show_hide_password input').attr('type', 'password');
              $('#show_hide_password i').addClass( "fa-eye-slash" );
              $('#show_hide_password i').removeClass( "fa-eye" );
          }else if($('#show_hide_password input').attr("type") == "password"){
              $('#show_hide_password input').attr('type', 'text');
              $('#show_hide_password i').removeClass( "fa-eye-slash" );
              $('#show_hide_password i').addClass( "fa-eye" );
          }
      });

      $('#email').blur(function(){
        var this_email=$('#email').val();
        var hlp=$("#emailAlertBlock");
        $.post("check/checkmail.php",{ email: this_email })
        .done(function( result ) {
          if(result==1){
            hlp.text(userExist);
            $("#submit").attr("disabled","disabled");
          }
          else{
            hlp.text("");
            $("#submit").removeAttr("disabled");
          }
        });
      });
       
    //option for test
    // $("input").removeAttr('required');

    // SUBMIT registration form
    // var frm = $('#userdata');
    // frm.submit(function (e) {

    //   $("#shutter").css("display","block");

    //     e.preventDefault();
        
    //     $.ajax({
    //         type: frm.attr('method'),
    //         url: "reg.php",//frm.attr('action'),
    //         data: frm.serialize(),
    //         success: function (data) {
    //             var result=JSON.parse(data);
    //             if(result==1){
    //               console.log("Submission was successful.");
    //               $("#submitAlertBlock").text("");
    //               document.location.href =SITE+'/?success=1';
    //               }
    //             else{
    //               console.log("E-mail exist in DB!");
    //               $("#loader").hide();
    //               $("#submit").show();
    //               $("#submitAlert").text(userExist);

    //             } 
    //             // console.log(data);
    //             $("#shutter").css("display","none");
    //         },
    //         error: function (data) {
    //             console.log('An error occurred.');
    //             console.log(data);
    //             $("#shutter").css("display","none");
    //         },
    //     });
    // });
  });