$(document).ready(function() {
      
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
          hlp.text("<?=$LANG['user_exist']?>");
          $("#submit").attr("disabled","disabled");
        }
        else{
          hlp.text("");
          $("#submit").removeAttr('disabled');
        }
      });
    });
    
  //option for test
  $("input").removeAttr('required');

  // SUBMIT registration form
  var frm = $('#userdata');
  frm.submit(function (e) {
      e.preventDefault();
      // var pass=$("#password").val();
      // console.log("Password: "+pass);
      // $("#password").val("+++"+pass+"+++");
      $.ajax({
          type: frm.attr('method'),
          url: frm.attr('action'),
          data: frm.serialize(),
          success: function (data) {
              console.log('Submission was successful.');
              console.log(JSON.parse(data));
          },
          error: function (data) {
              console.log('An error occurred.');
              console.log(data);
          },
      });
  });
});