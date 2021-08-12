<? 
header('Content-Type: text/html; charset=utf-8');
include_once("../config.php");
include_once("../classes/adm.class.php");


if(isset($_GET['quit'])) {
    session_unset($_SESSION['b_keeper'], $_SESSION['login'], $_SESSION['adminName'], $_SESSION['loginError']);
    header('Location: '.SITE.'/pay');
}

// var_dump($_SESSION);

if(TU::bkAuthorise() ){ //&& in_array($_SESSION['b_keeper'], array('root','bookkeeper','viewer'))
    $speakers=TU::bkSpeakers();
    
    $content=TU::bkData2Table($speakers);
    $money=TU::howMuchMoney(); //count, money

} 
else {
    $content="<div class='col-md-4 offset-md-4'>\n";
    $content.="<h3>Авторизация</h3>";
    $content.="</div>";
    $content.=TU::AuthForm();
}

// Форма для фиксации оплаты
//


?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Pay::PhysicA.SPB/<?echo YEAR?></title>
    
    <link rel="icon" href="data:;base64,iVBORw0KGgo=">
    <script src="https://use.fontawesome.com/7b07b4d79c.js"></script>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/bootstrap-modal-ios.css" rel="stylesheet">
    <!-- <link href="../css/mdb.min.css" rel="stylesheet"> -->
    <link href="../css/datatables.min.css" rel="stylesheet">

    <link href="../adm/adm.css" rel="stylesheet">

    <script src="../js/jquery.min.js"></script>
    <!-- <script src="../js/popper.min.js"></script> -->
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/bootbox.min.js"></script>
    <script src="../js/bootstrap-modal-ios.js"></script>
    <!-- <script src="../js/mdb.min.js"></script> -->
    <script src="../js/datatables.min.js"></script>
    <script src="../js/jquery.jeditable.min.js"></script>
    
    <script>
  
    $(document).ready(function() 
    {
        $('[title]').click(function(){
            var text=$(this).attr('title');
            // console.log(text);
            $('#tip .modal-body').text(text);
            $("#tip").modal();
        }).css({'cursor':'pointer','color':'blue'});

        //GET FIELD NAMES
        var fld=[];
        $("thead th").each(function(){ 
            fld.push($(this).attr('id'));
        })

        //set object with properties as filed=num_col
        var numCol={};
        $.each(fld, function(index, value){
            numCol[value]=$("#"+value).index();
        })
        // console.log(fld);
        // console.log(numCol);

        <? if($_SESSION['b_keeper']!=="viewer"):?>
        $("tbody tr").each(function() {
            thisCell=$(this).children("td:eq("+numCol.fee+")").children("div");
            // rowId=$(this).children("td:eq("+numCol.id+")").text();
            //  console.log(rowId);

            thisCell.editable("ajax_pay.php",{
                type:"select",
                tooltip:"выбрать сумму",
                data: <?=$fee?>,
                submit : "OK",
                cancel : "&times;",
                submitdata:function(){
                    var thisId=$(this).closest("tr").children("td:eq("+numCol.id+")").text();
                    console.log("selected id="+thisId);
                    return {id:thisId};
                },
                intercept : function(value) {
                    var json = $.parseJSON(value); 
                    $(this).closest("tr").children("td:eq("+numCol.fee_date+")").text(json.date);
                    console.log(json.date);
                    return json.fee;
                }
            })

        });
        <? endif; ?>
   

        $("table").addClass("table table-sm table-hover table-striped");
        $("table").DataTable({
            "order": [[ 6, "desc" ]],
            "displayLength": 10
            // "columnDefs": [ {
            //     "targets": [ 0 ],
            //     "orderData": [ 0, 1 ]
            // }, {
            //     "targets": [ 1 ],
            //     "orderData": [ 1, 0 ]
            // }, {
            //     "targets": [ 4 ],
            //     "orderData": [ 4, 0 ]
            // } ]
            });
        // $("tr:nth-child(even)").addClass("table-active");
        $('.dataTables_length').addClass('bs-select');

        
    });


    </script>

    <style>
    .modal-backdrop {
    display: none !important;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #eee;
    }

    .table-hover tbody tr:hover {background-color:#fcfdcc}
    .col1 {width:4em}
    .col2 {width:10em}
    .col3 {width:16em}
    .col4 {width:8em}
    
    </style>
    
    </head>
    <body>
    <div class="container">
    <?
    if($_SESSION['b_keeper']!=''):
        echo "<p class='text-right text-primary mr-4'>Вы вошли как: ".(!empty($_SESSION['adminName'])?$_SESSION['adminName']:$_SESSION['login'])."&nbsp;|&nbsp<a href='".SITE."/pay?quit'>Exit</a></p>";
    endif;
    ?>
    <div class="row">
        <div class="col-md-12 text-center">Олачено взносов <?=$money['count']." на сумму ".$money['money']?> руб.</div>
    </div>
    <div class="row">
		<div class="col-md-12 table-responsive" style="max-width: 100%; overflow: auto;">
        <?=$content?>
        </div>
    </div>

    <div class="modal" id="tip">
    <div class="modal-dialog modal-dialog-centered modal-sm">
      <div class="modal-content m-0 p-0 border-3 border-info bg-light">
        
          <button type="button" class="close text-right mr-2 p-0" data-dismiss="modal">×</button>
        
        <div class="modal-body text-center m-0"></div>
      </div>
    </div>
  </div>
    </body>
</html>