<?
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
header('Content-Type: text/html; charset=utf-8');

include_once("config.php");
include_once("classes/index.fn.php");
include_once($lang_file);


if(isset($_GET['quit'])) {

	unset($_SESSION['user_id'],$_SESSION['backdoor']);
}

$dbh = new PDO('mysql:host='.$dbhost.';dbname='.$physica_db, $mysqluser, $mysqlpass);
$dbh->query("SET NAMES utf8");


?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <title>PhysicA.SPb/<?=$YEAR?></title>
	<link rel="icon" href="data:;base64,iVBORw0KGgo=">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/shutter.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="./js/jquery.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>

	<style>
		.eye-info {cursor:pointer}
		#pwdButtonGrp * {display:none}
		hr {border:1px solid #17a2b8 !important}
	</style>

	<script>
		var SITE="<?echo SITE; ?>";
		var recall_password="<?=$LANG['recall_password']?>";
		var Submit_enter="<?=$LANG['Submit_enter']?>";
		var passwd_recall_info="<?=$LANG['passwd_recall_info']?>";
		var isMistake="<?=$LANG['isMistake']?>";
		var user_id="<?=$_SESSION['user_id']?>";
	</script>
    <script src="./js/index.js"> </script>
  </head>

  <body>
  <div  class="container">
  <p class="text-right"><? include("asset/lang_swithcher.php");  ?> </p>
  <h3 class="text-center"><?=$LANG['conference']?>/<?=YEAR?>. <?=$LANG['cabinet']?></h3>

	<?
	if(isset($_SESSION['user_id'])):
	?>
		<div class='row'>
            <? //left column ?>
			<div class="card border-primary col-12 col-md-3 p-0" >
            <?
                include_once("asset/index_user_info.php");
            ?>
			</div>
			
			<? // right column ?>
			<div class="card border-primary col-12 col-md-9 p-0" ><!--style="max-width: 18rem;" -->
				<div class="card-header">
					<?=$LANG['information']?>
				</div>
				<div class="card-body">
					<?=info_block($dbh)['info']?>
					<div class="mt-3">
					<?=$LANG['SgnInfo']?>

					<hr/>
					<?
						// $Accepted=array('accepted', 'poster','oral','invited'); // defined in config.php
						$this_report_type=info_block($dbh)['report_type'];
						$this_poster_file=info_block($dbh)['poster_file'];
                        $_SESSION['this_report_type']=$this_report_type;

						// вывод блока про оплату
						if(in_array($this_report_type, $Accepted)&&!empty($_GET['test'])){
							// $pay_link='http://inno-mir.ru/ru/payment?'
							// .'userid='.$_SESSION['id']
							// .'&name='.urlencode($_SESSION['fullname'])
							// .'&email='.urlencode($_SESSION['email'])
							// .'&MobilePhone='.$_SESSION['phone'];
							$pay_link="http://reg.physica.spb.ru/payurl.php?h=".$_SESSION['user_id'];
							
							echo "<h5 class='card-title text-info'>".$LANG['payment_header']."</h5>";
                            echo "<p>".$LANG['payment_info']."</p>";
                            // echo "<p><a target='innopay' href='$pay_link'>http://inno-mir.ru/ru/payment</a></p>";
                            echo "<p><a target='innopay' href='$pay_link'>$pay_link</a></p>";
							echo "<p>".$LANG['payment_other']."</p>";
                            echo "\n<hr/>";
						}

                        // вывод блока про экскурсию
                        $spb_city=strripos($_SESSION['city'], array('петерб','спб','petersb'));

                        if(in_array($this_report_type,  $Accepted) && $spb_city===false && Excursion==true){
                            echo "<h5 class='card-title text-info'>".$LANG['excursion_header']."</h5>";
                            echo "<p>".$LANG['excursion_info']."</p>";
                            echo "<p>"
                            ."<input type='checkbox' name='excursion' id='excursion' value='true' "
                            .(($_SESSION['excursion_status']==true)?"checked":"")
                            ." > "
                            ."<label for='excursion'>".$LANG['excursion_check']."</label></p>"
                            ."\n<hr/>";
                        }

						// вывод блока про расширенные тезисы 
						if(in_array($this_report_type, $Accepted)){
							echo $LANG['ExtThesisInfo'];
							echo "<hr/>";
						}

						// вывод блока про приглашения и сертификаты
						if(in_array($this_report_type, $Accepted)){
							$allow_if_poster_uploaded= ($this_report_type=='poster' && strlen($this_poster_file)>2 && $_SESSION['fee']!='---');
							$allow_sertificate=($this_report_type=='oral' || $this_report_type=='invited' || $allow_if_poster_uploaded);
							echo (accessInvitation)?$LANG['Invitation']:"";
							echo (accessSertificate && $allow_sertificate)?"<hr/>".$LANG['Sertificate']:"";
						}
					?>
					</div>

					<?
                    // загрузка презентации в лифте 
                    if(accessLift && in_array($_SESSION['this_report_type'], ['poster','review'])){
                        include_once("asset/index_upload_lift.php");
                    }
                     
                    // Загрузка постера
					if(in_array($_SESSION['this_report_type'], ['poster','review']) && AllowUploadPoster){
                        include_once("asset/index_upload_poster");
                    }
					
                    // необходимо сообщить паспорнные данные гражданам России
					if(NeedPassport==true){
                        // echo "Паспорт";
                        $passport=getPassport($dbh);
                        if($passport!="!Есть пропуск ФТИ")
                            include_once("asset/index_passport.php");
                    }


					?>
				</div><? //end card body ?>
			</div>

		</div>
		
		<?

		if(info_block($dbh)['count'] < allowedThesisNum){ //|| isset($_SESSION['backdoor'])
				
			include_once("asset/index_add_thesis.php");
		};

	else:
        include_once("asset/index_login.php");	
    endif; 
        ?>

		<?
        if(isset($_SESSION['user_id'])){
            include_once("asset/index_messages.php");
        }
        ?>
	<?# $payment_sight="http://inno-mir.ru/ru/payment/index.php"?>
	<!-- <p><a href="<?=$payment_sight?>?id=<?=$_SESSION['user_id']?>&name=<?=$_SESSION['fullname']?>">payment</a></p> -->
	</div><!-- container -->
	
<div id="shutter">
	<div id="popup"><img src="./img/ajax-loader.gif"  style="border:0px"/>
	<br>Please wait.<br><i>Alea jacta est</i></div>
</div>


<?# здесь модальное окно о презентацтт в лифте 

    include_once("asset/index_modal_windows.php");

?>


	<div id='test'></div>
  </body>
</html>
