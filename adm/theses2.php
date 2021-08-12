<?
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);

include_once("../config.php");
// include_once("functions.php");
include_once("../classes/adm.class.php");

if(isset($_GET['quit'])) {
    session_unset($_SESSION['admin'], $_SESSION['login'], $_SESSION['adminName'], $_SESSION['loginError']);
    header('Location: '.SITE.'/adm');
}

$print=!empty($_GET['print']); //если печатаем

if(TU::Authorise()){

    if(!empty($_SESSION['expert_id'])){
		$js_expert_id="var expert_id=".$_SESSION['expert_id'].";\n";
		// $contenteditable="contenteditable='true'";
		$contenteditable="";
	}
	else{
		$js_expert_id="";
		$contenteditable="";
	}
	// get all thesises
	$content="";
	
	$sectArr=TU::countSection();

	$content.=($print)?"":"<div class=\"row one_thesis m-4 p-4 border border-dark\">";
	$content.="<h2 class='col-12'>Тезисы конференции</h2>";
	$content.="<div>";
	$content.="<h4 class='text-center'>Секции</h4>";
	$content.="\t<ul>\r";
	foreach($sectArr as $sct){
		$content.="\t\t<li><a href=\"#{$sct['title']}\">{$sct['title']}</a> [{$sct['cnt']}]</li>\r";
	}
	$content.="\t</ul>\n";
	$content.="\t</div>\n";
	$content.=($print)?"":"</div>\n";

	$theses=TU::getCompleteTheses();

	$this_sect=0;
	$count=0;
	foreach($theses as $TH){
			$count++;
			if($this_sect!=$TH['sect_id']){
				$content.="<div class='section_header row m-4 p-2 border border-primary bg-info'>";
				$content.="<div class='col-12 '>";
				$content.="<a name='{$TH['section_title']}'></a>";
				$content.="<h2 class='text-white'>{$TH['section_title']}</h2>";
				$content.="</div>";
				$content.="</div>";
				$this_sect=$TH['sect_id'];
			}
		ob_start();
		include("thesis.tpl.php");
		$content.=($print)?"<br style='page-break-after: always;'>":"";
		$content.=ob_get_contents();
		ob_end_clean();
	}
	$content="<div class='row'><div class='col-12'>Всего тезисов: $count </div></div>".$content;
}
else{
    // authorisation form
    $content=TU::authForm();
}

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang='en'> <!--manifest="thesis.cache"-->
<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Cache-Control" content="max-age=3">
<title><?=$LANG['conference']."/".YEAR ?></title>
<link rel="icon" href="data:;base64,iVBORw0KGgo=">
<script src="../js/jquery.min.js" type="text/javascript"></script>
<script src="../js/bootstrap.min.js" type="text/javascript"></script>
<? if(!$print):?>
<link rel="stylesheet" href="../css/bootstrap.min.css"> 
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="../css/thesiseditor.css"> 
<? endif; ?>
<style>
@media print { /* Стиль для печати */
	
	* {margin:0}

    body {
     font-family: Times, 'Times New Roman', serif; /* Шрифт с засечками */
	 font-sile:12pt !important;
	 }

	 h1, h2, h3, h4,  p {
     color: #000; /* Черный цвет текста */
	 line-height: 120%;
    }

	h1 {font-size:18pt !important}
	h2 {font-size:16pt !important}
	h3 {font-size:14pt !important}
	h4 {font-size:11pt !important}

	.print {display:none}
	.grade span {margin:0 1em}
	.grade {margin-top:2em}
	 .m-4 {margin:auto}
	 .p-4 {padding:auto}
	 .fa {display:none}
	 /* .border {border:none} */
	 .section_header {page-break-before: always; border:none}
	 .section_header div h2 {font-size:24pt !important; font-weight:bold;}
	 .title_border  {border:none!important; margin-top:4em }
	 h1.title {font-weight:bold; }
	 
}

.preloader {
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    z-index: 1000;
	background: rgba(255,255,255, 0.5);
}
.preloader__loader {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
	
}
</style>

<script>
	<?=$js_expert_id?>
</script>

<script type="text/javascript" charset="utf-8">

	function saveGrade(grade, expert_id,thesis_id){
			var result=$.post("../ajax/grade_update.php",{
				"grade":grade,
				"expert_id":expert_id,
				"thesis_id":thesis_id
			})
			.done(function(data){
				// console.log(data);
				if(data==1){
					// console.log(data);
					result=1
				}
			})
			.fail(function(){
				console.log("..ts happen");
			});
			return result;
		};

	$(document).ready(function() {

		$(".one_thesis .grade span").click(function(){
			var this_grade=$(this).text();
			var this_thesisid=$(this).parent().data('thesisid');
			if(saveGrade(this_grade, expert_id,this_thesisid)){
				$(this).siblings().toggle("grade"+this_grade);
			}

			// console.log("Expert: "+expert_id+"; Tid="+this_thesisid+"; GRADE="+this_grade);
		});
	});

</script>


</head>

<body style="background-color: silver">
<div class="preloader">
    <div class="preloader__loader">
        <img src="../img/ajax-loader.gif" alt="" />
    </div>
</div>
<?
    if($_SESSION['admin']!='' && !$print){
		echo "<p class='text-right text-primary mr-4'>Вы вошли как: "
		.(!empty($_SESSION['adminName'])?$_SESSION['adminName']:$_SESSION['login'])
		."&nbsp;|&nbsp<a href='".SITE."/adm?quit'>Exit</a></p>";
	}
	
	
?>
<div class="container">

<div class="row">
	<div class="col-12 p-0">
	<h1 class="text-right border-bottom border-dark"><?=$LANG['conference']."/".YEAR?></h1>
		<hr>
	</div>
</div>

<?=$content?>

<script>
// Preloader page
$(document).ready(function($) {
	$(window).on('load', function () {
		var $preloader = $('.preloader'),
		    $loader = $preloader.find('.preloader__loader');
		$loader.fadeOut();
		$preloader.delay(250).fadeOut(200);
	});
});
</script>
</body>
</html>
<?

/*

<div class="modal fade" id="info">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">×</button>
        </div>
	    <!-- Modal Body -->
		<div class="modal-body text-center">
			<!-- place for information -->
		</div>
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal"><?=$LANG['alertClose']?></button>
        </div>
      </div>
    </div>
</div>

*/

?>