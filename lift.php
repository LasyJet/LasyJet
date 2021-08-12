<?php
header('Content-Type: text/html; charset=utf-8');
// ini_set("display_errors","1");
// ini_set("display_startup_errors","1");
// ini_set('error_reporting', E_ALL^E_NOTICE);
// error_reporting(E_ALL ^ E_NOTICE); 
include "config.php";
include "classes/adm.class.php";
if (!empty($_GET['ses'])){
$session=$_GET['ses']; 
$content="";
ob_start();
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <title>PhysicA.SPb/<?=$YEAR?></title>
    <style>
    p {font-size:3rem}
    </style>
    </head>
    <body>
<?

}
else {
	echo "<p>Выберите номер сессии</p>";
	$path="lift.php";
	// echo "<h3>";
	for($n=1;$n<=6; $n++){
		$r=rand();
		echo "<h3>&nbsp;<a href='$path?ses=$n&$r'>&nbsp;&nbsp;$n&nbsp;&nbsp;</a></h3><br>";
	}
	// echo "</h3>";
	exit;
}

$pdfs=TU::getLifts($session);// выбрать из БД по сессии
// $pdfs=TU::getPDFs(1);// выбрать из БД по сессии
$names="";

foreach($pdfs as $p){
	// $p['lift_file']=ltrim($p['lift_file'],"/");
	$file="".$p['lift_file'];
	$names.="<p><a href=".SITE.$file.">[PDF]</a> ".$p['poster_session']."-".$p['poster_num']." ".$p['name']."</p>";

}
echo "<p><b>Всего докладов: ".count($pdfs)."</b></p>";
$html="lifts".$session.".html";
$all_in_one="merged_lift_session".$session.".pdf";
// $all_in_one="merged_lift_session4.pdf";
echo "<p><a href='".$all_in_one."'>Скачать все PDF вместе</a></p>";

echo $names;
$content = ob_get_contents();
 
ob_end_clean();
echo $content;
echo "<br><p><a href='".$html."'>HTML</a></p>";
file_put_contents($html, $content);
?>
</body>
</html>