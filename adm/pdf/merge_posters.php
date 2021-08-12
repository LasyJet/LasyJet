<?php
header('Content-Type: text/html; charset=utf-8');
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
ini_set('error_reporting', E_ALL^E_NOTICE);
error_reporting(E_ALL ^ E_NOTICE); 


include "../config.php";
include "../classes/adm.class.php";
if (!empty($_GET['ses'])){
$session=$_GET['ses']; 
}
else {
	echo "<p>Выберите номер сессии</p>";
	$path="merge_posters.php";
	echo "<p>";
	for($n=1;$n<=6; $n++){
		$r=rand();
		echo "&nbsp;<a href='$path?ses=$n&$r'> $n </a>";
	}
	echo "</p>";
	exit;
}

$pdfs=TU::getPDFs($session);// выбрать из БД по сессии
// $pdfs=TU::getPDFs(1);// выбрать из БД по сессии
$names="";
// echo "<pre>";
// var_dump($pdfs);
// echo "</pre>";
foreach($pdfs as &$p){
	$p['poster_file']=ltrim($p['poster_file'],"/");
	$files.=" ".$p['poster_file'];
	$names.="<p>".$p['poster_session']."-".$p['poster_num']." ".$p['name']."</p>";

}
// echo $names;
// echo $files;
$outputFile="merged_session_".$session.".pdf";// добавить номер сессии
// $cmd = "gs -q -dNOPAUSE -dBATCH -sDEVICE=pdfwrite -sOutputFile=$outputFile ".implode(" ",$files);
$cmd = "gs -q -dNOPAUSE -dBATCH -sDEVICE=pdfwrite -sOutputFile=$outputFile ".$files;
$result = shell_exec($cmd);

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename=' . basename($outputFile));
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($outputFile));
header ("Content-Disposition: attachment; filename=" . $outputFile);
readfile($outputFile);

?>