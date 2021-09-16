<?
// schedule index.php
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);

header('Content-Type: text/html; charset=utf-8');
include_once("config.php");
include_once("classes/adm.class.php");

$ScheduleTitle=ConfTitle."/".YEAR.": Программа конференции";
// $ScheduleTitle="Программа конференции";

$ScheduleData=TU::ScheduleData();
$ScheduleTable=TU::ScheduleTableToSight($ScheduleData);

$tpl=file_get_contents("adm/schedule/program_tpl.html");
$content=str_ireplace("{%ScheduleTitle%}", $ScheduleTitle, $tpl);
$content=str_ireplace("{%ScheduleTable%}", $ScheduleTable, $content);

echo $content; 

?>

