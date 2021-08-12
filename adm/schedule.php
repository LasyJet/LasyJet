
<?
// schedule.php
header('Content-Type: text/html; charset=utf-8');
include_once("../config.php");

if(isset($_GET['quit'])) {
    session_unset($_SESSION['admin'], $_SESSION['login'], $_SESSION['adminName'], $_SESSION['loginError']);
    header('Location: '.SITE.'/adm');
}

// echo  $LANG['language'];

########### FUNCTIONS/classes ###############
include_once("../classes/adm.class.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang='ru'>
<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?=$LANG['schedule']?> :: <?=$LANG['conference']."/".YEAR ?></title>

<link rel="icon" href="data:;base64,iVBORw0KGgo=">
<script src="../js/jquery.min.js" type="text/javascript"></script>
<script src="../js/bootstrap.min.js" type="text/javascript"></script>
<link rel="stylesheet" href="../css/bootstrap.min.css"> 
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<!-- <link rel="stylesheet" href="../css/thesiseditor.css">  -->

<style>
@media print { /* Стиль для печати */
	
}

.row {border:1px solid darkblue; margin:1em 0}

</style>

<script>
    <?
    // echo $js_expert_id
    ?>
</script>

<script type="text/javascript" charset="utf-8">

	$(document).ready(function() {

	});

</script>


</head>

<body style="">
<div class="container">

<div class="row" style="background-color:none !important">
    <div class="col-12">
        <h1><?=$LANG['schedule']?> :: <?=$LANG['conference']."/".YEAR ?></h1>
    </div>
</div>
&nbsp;
<div class="row ">
    <div class="col-12">
    &nbsp;
    </div>
</div>

<div class="row ">
    <div class="col-12">
    &nbsp;
    </div>
</div>


</div>

</body>
</html>