<?
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);

header('Content-Type: text/html; charset=utf-8');
include_once("../config.php");

if(isset($_GET['quit'])) {
    session_unset($_SESSION['admin'], $_SESSION['login'], $_SESSION['adminName'], $_SESSION['loginError']);
    header('Location: '.SITE.'/adm');
}

// echo  $LANG['language'];

########### FUNCTIONS/classes ###############
include_once("../classes/adm.class.php");


########### end of FUNCTIONS/classes ###############
if(TU::Authorise()){
    // "show user+thesises data or Eval.thesises (expert)";
    if($_SESSION['admin']=='expert'){
        header("Location: ".SITE."/adm/theses.php");
        exit();
    }
    if(empty($_GET))
    {
        $user_fld=array("id", "familyname", "givenname", "parentname", "substitute", "birthday", "email", "country", "city", "company", "position", "degree", "passport", "reg_datetime", "deleted");
        $users=TU::getAllUsers($user_fld);
        $content="<div class='col-12' id='users'>";
        $totalReg=TU::totalRegistered()[0]['count'];
        $todayReg=TU::todayRegistered()[0]['count'];
        echo "<p>Всего:$totalReg</p>";// Сегодня: $todayReg
        $content.=TU::Data2Table($users);
        $content.="</div>";
    }
    elseif(!empty($_GET['theses']) && $_GET['theses']==1) {
        // $theses_fields=array("thesis_id", "section", "title", "speaker", "coauthors", "affiliations", "rfbr", "rfbr_title", "report_type", "date");
        $theses_fields=array("thesis_id", "section", "title", "speaker", "coauthors", "affiliations", "report_type", "date");
        $theses=TU::getAllThesis($theses_fields);
        $content="<div class='col-12' id='theses'>";
        $content.=TU::Data2Table($theses);
        $content.="</div>";
    }
    elseif(!empty($_GET['theses']) && $_GET['theses']==2) {
        // $theses_fields=array("id", "user_id", "thesis_id", "section", "title", "speaker", "coauthors", "affiliations", "rfbr", "rfbr_title", "report_type", "date");
        $theses_fields=array("thesis_id", "section", "title", "speaker", "coauthors", "affiliations", "rfbr", "report_type");
        $theses=TU::getAllThesis($theses_fields);
        $content="<div class='col-12' id='theses'>";
        $content.=TU::Data2Table($theses);
        $content.="</div>";
    }
    elseif(!empty($_GET['stat'])){
        ob_start();
		include("stat.php");
		$content.=ob_get_contents();
        ob_end_clean();      
    } elseif(!empty($_GET['grades'])){
        ob_start();
		include("grades.php");
		$content.=ob_get_contents();
        ob_end_clean();      
    }elseif(!empty($_GET['pwd'])){
        ob_start();
		include("pwd.php");
		$content.=ob_get_contents();
        ob_end_clean();      
    }
}
else{
    // authorisation form
    $content=TU::authForm();
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Admin::PhysicA.SPB</title>
    
    <link rel="icon" href="data:;base64,iVBORw0KGgo=">
    <script src="https://use.fontawesome.com/7b07b4d79c.js"></script>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/mdb.min.css" rel="stylesheet">
    <link href="../css/datatables.min.css" rel="stylesheet">
    <link href="adm.css" rel="stylesheet">
    
    <style>
        /* div.row{border:1px solid red !important} */
    </style>

    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/bootbox.min.js"></script>
    <script src="../js/mdb.min.js"></script>
    <script src="../js/datatables.min.js"></script>
    <script src="../js/jquery.jeditable.min.js"></script>
    <script>
    var sections=[]; //list of sections
    <?
        foreach(TU::getSection() as $sect){
            echo "\tsections[".$sect['id']."]='{$sect['ru']}';\n";
        }
    ?>
    var report_types='<?=TU::getReports()?>'; //JSON list of report types
    </script>

    <script src='../js/adm.js'></script>

  </head>
  <body>
  <div class="container-fluid">
	<div class="row">
		<div class="col-md-12">

<?
    if($_SESSION['admin']!=''):
    echo "<p class='text-right text-primary mr-4'>Вы вошли как: ".(!empty($_SESSION['adminName'])?$_SESSION['adminName']:$_SESSION['login'])."&nbsp;|&nbsp<a href='".SITE."/adm?quit'>Exit</a></p>";
?>
			<ul class="nav nav-tabs">
				<li class="nav-item">
					<a class="nav-link <?=TU::setActiveLink('list')?>" href="<? echo SITE?>/adm">Участники</a>
				</li>
				<li class="nav-item">
					<a class="nav-link <?=TU::setActiveLink('theses')?>" href="<? echo SITE?>/adm?theses=1">Тезисы</a>
				</li>

				<li class="nav-item">
					<a class="nav-link"  <?=TU::setActiveLink('stat')?>" href="<? echo SITE?>/adm?stat=1">Статистика</a>
				</li>
                <li class="nav-item">
					<a class="nav-link <?=TU::setActiveLink('alltheses')?>" href="<? echo SITE?>/adm/theses.php">Просмотр и оценка тезисов</a>
				</li>
                <li class="nav-item">
					<a class="nav-link <?=TU::setActiveLink('grades')?>" href="<? echo SITE?>/adm/?grades=1">Оценки</a>
				</li>

				<!--<li class="nav-item">
					<a class="nav-link disabled <?=TU::setActiveLink('msg')?>" href="<? echo SITE?>/adm?msg=1">Messages</a>
				</li> -->
            </ul>
<?endif;?>
            <h3 class="text-center">
             <?=TU::setHeader()?>
            </h3>
        </div>
    </div>
    <div class="row">
    <?=$content?>
	</div>
</div>

  </body>
</html>