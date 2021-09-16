<?
// schedule index.php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

header('Content-Type: text/html; charset=utf-8');
include_once("../../config.php");
include_once("../../classes/adm.class.php");

$ScheduleTitle=ConfTitle."/".YEAR.": Программа устных докладов.";

$itemsdata=TU::item4ScheduleData();
$itemsTable=TU::item4ScheduleTable($itemsdata);
$ScheduleData=TU::ScheduleData();
$ScheduleTable=TU::ScheduleTable($ScheduleData);


$tpl=file_get_contents("template.html");
//  $str=str_ireplace("{%ScheduleTable%}", $data, $tpl);
$content=str_ireplace("{%ScheduleTitle%}", $ScheduleTitle, $tpl);
$content=str_ireplace("{%itemsScheduleTable%}", $itemsTable, $content);
$content=str_ireplace("{%ScheduleTable%}", $ScheduleTable, $content);
// $content=str_ireplace("{%testTable%}", testData(), $content);

echo $content; 


?>

