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
			<div class="card border-primary col-12 col-md-3 p-0" >
				<div class='card-header'><?=$LANG['account']?></div>
				<div class='card-body'>
				<?=account_data($dbh)?>
					<div class="col p-0" >
						<div class="input-group" id="passwd_form">
						<input id="chPassword" type="password" class="col-11 border border-info " placeholder="click to change password"/>
							<span  class="eye-info text-dark"><i class="fa fa-eye-slash" aria-hidden="true"></i></span>
						</div>
						<div id="pwdButtonGrp" tabindex="1">
							<button type="button" id="changePwd" class="btn-sm btn-success">Change</button>
							<button type="button" id="cancelPwd" class="btn-sm btn-secondary">Cancel</button>
							<p class='saved text-info'>Password saved</p>
						</div>
					</div>
				</div>
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
						$accepted=array('accepted', 'poster','oral','invited');
						$this_report_type=info_block($dbh)['report_type'];
						$this_poster_file=info_block($dbh)['poster_file'];
                        $_SESSION['this_report_type']=$this_report_type;

						if(in_array($this_report_type, $accepted)){
							echo $LANG['ExtThesisInfo'];
							echo "<hr/>";
							$allow_if_poster_uploaded= ($this_report_type=='poster' && strlen($this_poster_file)>2 && $_SESSION['fee']!='---');
							$allow_sertificate=($this_report_type=='oral' || $this_report_type=='invited' || $allow_if_poster_uploaded);
							echo (accessInvitation)?$LANG['Invitation']:"";
							echo (accessSertificate && $allow_sertificate)?"<hr/>".$LANG['Sertificate']:"";
						}

					?>
					</div>

					<? if(accessLift && in_array($_SESSION['this_report_type'], ['poster','review'])): ?>
					<hr/>

					<div class="row">
						<div class="col-12">
						<h5 class='card-title text-info'><?=$LANG['lift_upload']?> <?=$pdfIcon?>
						<span data-toggle="modal" data-target="#lift_about" style="cursor:help"><?=$infoIcon?></i></span></h5>
						</div>
					</div>

					<div class="row">
						<div class="col-6 pr-1"><input id="lift" type="file" name="lift" /></div>
						<div class="col-6"><button id="liftupload" class="btn-sm btn-success">Upload</button></div>
					</div>

					<div class="row">
						<div class="col-12 text-success text-center" id="lift_info">
						<a href="<? echo SITE.getLift($dbh)?>">Your previous uploaded file <?=$imageIcon?></a>
						</div>
					</div>
					<?
						endif; // загрузка презентации в лифте
					?>


					<? if(in_array($_SESSION['this_report_type'], ['poster','review']) && AllowUploadPoster): ?>

						<hr/>
						<div class="row">
							<div class="col-12">
							<h5 class='card-title text-info'>Загрузить файл постера для публикации онлайн (только pdf) <?=$pdfIcon?></h5>
							</div>
						</div>

						<div class="row">
							<div class="col-6 pr-1"><input id="poster" type="file" name="poster" /></div>
							<div class="col-6"><button id="poster_upload" class="btn-sm btn-success">Upload</button></div>
						</div>

						<div class="row mt-2">
							<div class="col-12 text-success text-center " id="poster_info">
							<a href="<? echo SITE.getPoster($dbh)?>">Your previous uploaded file <?=$imageIcon?></i></a>
							</div>
						</div>

						<hr/>
					<?
						endif; // Загрузка постера

						// загрузка паспортных данных - в конце кола
					?>


				</div>
			</div>

		</div>
		<?
			if(info_block($dbh)['count'] < allowedThesisNum):
		?>

		<div class="d-flex justify-content-center  mt-4">
			<button id="addThesises" type="button" class="btn btn-info"><?=$LANG['addThesises']?></button>
		</div>

		<?
			endif;
		?>

		<?
	else:
		?>
			<div class='row justify-content-md-center'>
			<form enctype="multipart/form-data" method="post" name="login" id="login" ><!-- action="login.php" -->
			<?

			if(isset($_GET['success'])) {
				  echo "<p class='m-4'>{$LANG['you_registered']}</p>";
			  }


			$today = date('Y-m-d');

			if($today<=DEADLINE): //backdoor form.php?bd=element_from_config:$bd
			?>
				<div class="col-12 text-center">
					<button type="button" class="btn btn-info m-4 btn-lg" onclick="window.location.assign('<?echo SITE?>/form.php?')"><?=$LANG['to_register']?></button>
				</div>


				<p class="text-center"><?=$LANG['or']?></p>

			<?
			else:
			?>
				<div class="col-12 text-center">
					<p class="text-danger">
						<?
						echo $LANG['registeration_closed'];
						echo (regAnnouncedLater)?"<br>".$LANG['regAnnouncedLater']:"";

						?>

					</p>
				</div>

			<?
			endif; // today < deadline
			?>

			<h4 class="text-center"><?=$LANG['Login']?></h4>
			<div class="border border-muted p-4 m-1">
				<div class="form-group row mb-4">
					<label for="email">Email:</label>
					<input type="text" class="form-control" id="email" name="email" placeholder="Enter email" required >
				</div>
				<div class="form-group row bm-4" id="password-block">
					<input type='hidden' name='forgot' value=true disabled=disabled>
					<label for="pwd">Password:</label>
					<input type="password" class="form-control" id="pwd" name="password" placeholder="Enter password" autocomplete="new-password">
				</div>
			</div>
				<!--
				<div class="form-group form-check">
				<label class="form-check-label">
					<input class="form-check-input" type="checkbox" name="remember"> Remember me
				</label>
				</div>
				-->
				<div class="col-12 text-center">
					<button id="submit" name="submit" type="submit" class="btn btn-success"><?=$LANG['Submit_enter']?></button>
					<p><a id="i_foret_password" href="#"><?=$LANG['forgot_password']?></a></p>
					<!-- <p><?=$infoIcon?> Функция восстановления пароля временно не работает.</br>Если вы забыли пароль пишите <a href="mailto:mail@physica.spb.ru?subject=I forgot password&body=Я забыл пароль!">mail@physica.spb.ru</a></p> -->
					<div id="infoBlock" class="alert alert-primary" role="alert"></div>
				</div>


				</form>

		</div>

		<?endif; ?>

		<?
		if(isset($_SESSION['user_id'])):
		?>
		<div class="row">
			<div class="card border-primary col-12 p-0 mt-2" id="messages">
				<div class='card-header'> 
					<?="Сообщения" ?>
				</div>
				<div class="casr-body m-1">
				<?=messages($dbh) ?>
				</div>
			</div>
		</div>
		<? endif; ?>
	</div><!-- container -->
	
<div id="shutter">
	<div id="popup"><img src="./img/ajax-loader.gif"  style="border:0px"/>
	<br>Please wait.<br><i>Alea jacta est</i></div>
</div>


<?# здесь модальное окно о презентацтт в лифте ?>
<div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="lift_about"  id="lift_about"  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content p-2">
	<div class="modal-header">
        <h5 class="modal-title"><?=$LANG['lift_title']?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
	  <div class="modal-body">
      <?=$LANG['lift_description']?>
	  </div>
    </div>
  </div>
</div>

<div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="file_about"  id="file_uploaded"  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content p-2">
	<div class="modal-header">
        <h5 class="modal-title text-center">Файл загружен</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
    </div>
	<div class="modal-body text-center">
		<button type="button" class="btn btn-success" data-dismiss="modal">Close</button>
    </div>

    </div>
  </div>
</div>

<!-- Yandex.Metrika counter -->
<script src="js/yandex.metrika.js"type="text/javascript" ></script>
<noscript><div><img src="https://mc.yandex.ru/watch/30705528" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->

  </body>
</html>

<?
// $passport=getPassport($dbh);
#if($passport!="!Есть пропуск ФТИ"):?>
<!-- <h5  class="card-title">В ФТИ им. А.Ф. Иоффе действует пропускной режим.<br>
Для организации прохода, пожалуйста, укажите ниже следующие данные:</h5> -->

<!-- <p class="m-0 font-weight-light">Фамилия, имя, отчество на русском языке.</p>
<p class="m-0 font-weight-light">Дата рождения, место рождения</p>
<p class="m-0 font-weight-light">Серия и номер паспорта,</p>
<p class="m-0 font-weight-light">Когда и кем выдан паспорт</p>
<p class="m-0 font-weight-light">Адрес регистрации.</p>
<p class="text-danger mt-2">Если ваш доклад представляет другой человек впишите его паспортные данные!</p>
<p class="text-danger mt-2">Если у вас нет парспорта РФ оставтьте поле как есть и свяжитесь с Оргкомитетом.</p>
<p class="mt-2">Если вы не можете приехать и будете участвовать онлайн,<br> нажмите на кнопку &laquo;участвую&nbsp;онлайн&raquo;</p> -->

<!-- <div id="passport" tabindex="2" class="alert alert-warning mt-2	text-dark border border-danger" style="min-height:8rem"role="alert" contenteditable="true"> -->
<!-- <div id="passport" tabindex="2" class="alert alert-warning mt-2	text-dark border border-danger" style="min-height:8rem"role="alert" contenteditable="false"> -->

<!-- <p class='text-primary'><b>Приём паспортных данных для оформления пропуска завершен.</b> <br>Если вы не сообщили данные ранее, значит вы участвуете онлайн</p> -->
<?#=$passport?>
<!-- </div> -->

<!-- <div class="row mt-2">
	<div class="col-2 "><button type="button" id="savePassport" class="btn-sm btn-success">Save</button></div>
	<div class="col-7 text-left" id="passport_info"></div>
	<div class="col-3 text-right"><button type="button" id="online" class="btn-sm btn-info">Участвую онлайн</button></div>
</div> -->
<? #endif;?>