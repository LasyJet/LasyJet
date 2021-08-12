<?
include_once("config.php");
include_once($lang_file);
include_once("functions.php");
$dbh = new PDO('mysql:host='.$dbhost.';dbname='.$physica_db, $mysqluser, $mysqlpass);
$dbh->query("SET NAMES utf8");

$_SESSION['showAlert']=true;
// user_id
// section
// thesis_id
// title
// speaker
// coauthors
// affiliations
// text
// literature
// frbr
// date
if(isset($_GET['newthesis']) && strlen($_SESSION['user_id'])>0){
	
	$thesis_id=new_thesis_id($_SESSION['user_id']); //function are below
	$data=get_user_data($_SESSION['user_id']);
	$data['thesis_id']=$thesis_id;
	$data['user_id']=$_SESSION['user_id'];

	insert_user_into_thesis($data);
}	

if(isset($_GET['id'])){ //thesis id
	$thesis_id=$_GET['id']; 

}

//if(isset($_SESSION['user_id']) && strlen($thesis_id)>0){
if(strlen($thesis_id)>0){

	$TH=get_thesis_data($thesis_id);
	// $TH['email']=$data['email'];
	
}
if(isset($_GET['newthesis']) ){ //заполнение полей тестовыми данными
	foreach($TH	as $key=>&$v){
		global $LANG;
		
		if(strlen($v)==0) $v=$LANG["thesis_".$key];
		// echo $v."br";
	}
}


// echo $_SESSION['user_id'];
// echo "<br>".$thesis_id;
define('isOwner', is_owner($thesis_id, $_SESSION['user_id']));

if(isset($_SESSION['user_id']) && isOwner==1 && allowThesisEdit){
	$contentediable="contenteditable='true'";
	$this_js='<script src="js/thesiseditor.js" type="text/javascript"></script>';
}
else {
	$contentediable="";
	$this_js='';
}


############### FUNCTIONS #######################


function new_thesis_id($user_id){
	global $dbh;
	$sql="SELECT id FROM `".YEAR."_speaker` WHERE `user_id`=:user_id";
    $stmt = $dbh->prepare($sql);
	$stmt->bindValue(":user_id", $user_id);
    $stmt->execute();

	$data=$stmt->fetch(PDO::FETCH_ASSOC);
	
	$prefix=substr(YEAR,0,2)."000";
    return $thesis_id=((int)$prefix+$data['id'])*10; // на 10 умножается на будущее, если тезисов одного человека будет больше одного
}

function get_user_data($user_id){
	global $dbh;
	$sql="SELECT `familyname`, `givenname`, `parentname`, `company`, `email` FROM `".YEAR."_speaker` WHERE user_id=:user_id";
	$stmt=$dbh->prepare($sql);
	$stmt->bindValue(":user_id", $user_id);
	$stmt->execute();
	return $stmt->fetch(PDO::FETCH_ASSOC);
}

function insert_user_into_thesis($data){
	global $dbh;
	
 	$speaker =$data['familyname']."&nbsp;";
	$speaker.=mb_substr($data['givenname'],0,1,'UTF-8').".&nbsp;";
	$speaker.=mb_substr($data['parentname'],0,1,'UTF-8').".<sup>1</sup>";//speaker=user+affiliation number
	
	$affiliations="<sup>1</sup>".$data['company'];
	$sql="INSERT INTO `".YEAR."_thesises` (`thesis_id`, `user_id`, `speaker`, `affiliations`)
		SELECT * FROM (SELECT :thesis_id, :user_id, :speaker, :affiliations) as tmp
		WHERE NOT EXISTS (SELECT `thesis_id` from `".YEAR."_thesises` WHERE `thesis_id`=:thesis_id)";
	
	$stmt=$dbh->prepare($sql);
	$stmt->bindValue(":user_id", $data['user_id']);
	$stmt->bindValue(":thesis_id", $data['thesis_id']);
	$stmt->bindValue(":speaker", $speaker);
	$stmt->bindValue(":affiliations", $affiliations);
	$stmt->execute();
	return $stmt;
}

function get_thesis_data($thesis_id){
	global $dbh;

	// $sql="SELECT * FROM `".YEAR."_thesises` WHERE `thesis_id`=:thesis_id";
	$sql="SELECT t1.*, t2.email FROM `".YEAR."_thesises` t1 LEFT JOIN `".YEAR."_speaker` t2 ON t1.user_id=t2.user_id WHERE t1.thesis_id=:thesis_id";
	$stmt=$dbh->prepare($sql);
	$stmt->bindValue(":thesis_id", $thesis_id);
	$stmt->execute();
	$data=$stmt->fetch(PDO::FETCH_ASSOC);
	$data['cnt']=$stmt->rowCount();

	return $data;
}

function is_owner($thesis_id, $user_id){
	global $dbh;
	$sql="SELECT count(*) as `cnt` FROM `".YEAR."_thesises` WHERE `thesis_id`='$thesis_id' AND `user_id`='$user_id'";
	$stmt=$dbh->prepare($sql);
	$stmt->execute();
	$count=$stmt->fetch(PDO::FETCH_ASSOC);
	
	return (int)$count['cnt'];
}

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang='en'>
<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?=$LANG['conference']."/".YEAR?></title>

<link rel="icon" href="data:;base64,iVBORw0KGgo=">
<script src="js/jquery.min.js" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/js-cookie@beta/dist/js.cookie.min.js"></script>
<script   type="module"  src="https://cdn.jsdelivr.net/npm/js-cookie@beta/dist/js.cookie.min.mjs"></script>
<!-- <script src="js/popper.min.js" type="text/javascript"></script> -->
<script src="js/bootstrap.min.js" type="text/javascript"></script>

<script src="js/ckeditor/ckeditor.js" type="text/javascript"></script>
<script src="js/jquery.jeditable.min.js" type="text/javascript"></script>
<script src="js/ckeditor/adapters/jquery.js" type="text/javascript"></script>

<link rel="stylesheet" href="css/bootstrap.min.css"> 
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="css/thesiseditor.css"> 
<script type="text/javascript">
var thesis_id = '<?=$thesis_id?>';
var maxCharLimit=<? echo ThesisMaxChar?>;
var maxOverflow=<? echo maxOverflow?>;
var tooMuchWords="<?=$LANG['tooMuchWords']?>";
</script>
<?=$this_js?>

<script type="text/javascript" charset="utf-8">

$(document).ready(function() 
{
 	$("#popup").css('display', 'block').hide();

  var wrapperWidth=$("#wrapper").css('width');
  $("#togglefields").click(function()
	{
		$("#wrapper").toggleClass('wrapper');
		if ($("#wrapper").hasClass('wrapper')){
			$("#wrapper").css('width',wrapperWidth);
			$(this).text('<?=$LANG['hideMargins']?>');
		}
		else{ 
			$("#wrapper").css('width','90%');
			$(this).text('<?=$LANG['showMargins']?>');
		}
	});
	
	if(window.innerWidth<1200) $("#togglefields").click();

	console.log(Cookies.get('alert_info'));

	if(Cookies.get('alert_info')==undefined){
	
		var alertInfo=$("#howToInfo .text").html();
		$("#alertInfo div.modal-body").html(alertInfo);
		$("#alertInfo").modal("show");
		// var exp_time = new Date(new Date().getTime() + 10 * 1000); //ten second
		var exp_time = 1/12; //one hour
		Cookies.set('alert_info', '1',{expires:exp_time});
		// Cookies.remove('alert_info');
	}
	

});
</script>	

</head>

<body>
<p class="text-right"><? include("asset/lang_swithcher.php");  ?> </p>
<div id="wrapper" class="wrapper container">
<div class="row">
<div class="col-12 p-0">
<p class="text-right border-bottom border-dark"><?=$LANG['conference']."/".YEAR?></p>
<? if(isOwner): ?>
<h2 class="p-0"><?=$LANG['submission_abstract']?></h2>	

<div class="modal fade" id="alertInfo">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">

      <div class="modal-header">
          <h4 class="modal-title"><?=$LANG['modalAlert']?></h4>
          <button type="button" class="close" data-dismiss="modal">×</button>
        </div>
        <!-- Modal body -->
        <div class="modal-body alert alert-info m-0">
          
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>

<div id="howToInfo" class="alert alert-info alert-dismissible ">
	<!-- <div class="modal-content"> -->
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		<div class="text"><?=$LANG['thesis_fill_info']?></div>
	<!-- </div> -->
</div>

<p class="text-primary">
	<span id='togglefields' class="text-primary"><?=$LANG['hideMargins']?></span>&nbsp;
	<!-- |&nbsp;<span id="clean_form" onclick="javascript:clean_form()"><?=$LANG['clear_form']?></span>  -->
</p>
<hr>
<? endif; ?>
</div>
</div>


<div class="row">	
	<h3 class="text-info"><?=$LANG['thesis_section']?></h3>

<div class='col-12 mb-2 editable-select' id='section'>
	<?
		echo get_session_title($TH['section'], $dbh)[$_SESSION['lang']];
	?>
	</div>
</div>


<div class="row">
	<h3 title='<?=$thesis_id?>' class="text-info"><?=$LANG['thesis_title']?></h3>
	<div class="col-12 p-0 m-0" >
		<h1 id="title" <?=$contentediable?> >
			<?=$TH['title']?>
		</h1>
	</div>
	<div class="col-12 alert alert-info" id="alertTitle"><i class="fa fa-info-circle fa-lg" ></i>&nbsp;<?=$LANG['thesis_CAPS']?></div>
</div>


<div class="row">
	<div class="col-3 p-0">
		<h3 class="text-info"><?=$LANG['authors']?></h3>
	</div>
	<div class="col-9 p-0">
		<h3 class="text-info"><?=$LANG['coauthors']?></h3>
	</div>
	<div class='speaker col-3' id='speaker' <?=$contentediable?> >
		<?=$TH['speaker']?>
	</div>
    <div class="col-9 border-left border-secondary" id='coauthors' <?=$contentediable?> >
	<?=$TH['coauthors']?>
	</div>
</div>

<div class="row">
	<h3 class="text-info"><?=$LANG['affiliations']?></h3>
<div class='affiliations col-12'id='affiliations' <?=$contentediable?> >
	<?=$TH['affiliations']?>
</div>

<div class="col-12">
	<p class='speaker_email'><?=$LANG['email']?>:<i> <?=$TH['email']?></i></p>
</div>


<div class="col-12 p-0">
	<h3 class="text-info"><?=$LANG['text_header']?></h3>
<? if(isOwner): ?>
	<div class="col-12 alert alert-info" ><i class="fa fa-info-circle fa-lg" ></i>&nbsp;<?=$LANG['thesis_text_info']?></div>
<? endif;?>
</div>


<div id='text' class='text col-12' style="min-height:10rem"  <?=$contentediable?> >
	<?=$TH['text']?>
</div>
<!-- <div class="col-12 alert alert-info text-right"   id='#textInfo'></div> -->

<h3 class="text-info"><?=$LANG['liter_header']?></h3>
<div id='literature' class='literature_list col-12' <?=$contentediable?> >
	
    <?=$TH['literature']?>

</div>

<?if(isOwner):?>
<div class="alert alert-info mt-3 mb-0  col-12">
<i class="fa fa-info-circle fa-lg" ></i>&nbsp;
		<?=$LANG['rfbr_info']?>
</div>
<?endif;?>

<h3 class="text-info m-0 p-1"><?=$LANG['rfbr_title']?></h3>
<p id='rfbr_title' class='text col-12'  <?=$contentediable?> >
	<?=$TH['rfbr_title']?>
</p>

<h3 class="text-info mt-0"><?=$LANG['rfbr_num']?></h3>
<p id='rfbr' class='text col-12'  <?=$contentediable?> >
	<?=$TH['rfbr']?>
</p>

<?if(allowThesisEdit && isOwner):?>

<div class="col-12 mt-2 text-center">
	<button id="finish" type="button" class="btn btn-primary" onclick="javascript:setTimeout(window.location.assign('./'), 300)"><?=$LANG['thesis_finish']?></button>
</div>

<?
endif;
?>



</div>
</div>

<div class="col-12 justify-content-center">
    <div id="popup" class="rounded" style="display: none">
        <?=$LANG['save']?>
    </div>
</div>

<div class="modal fade" id="tooMuchLetters">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
         
          <button type="button" class="close" data-dismiss="modal">×</button>
        </div>
		<div class="modal-body text-center">
		<?=$LANG['tooMuchWords']?>
		</div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal"><?=$LANG['alertClose']?></button>
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

