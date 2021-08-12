<?
include_once("../config.php");
include_once("../classes/adm.class.php");
//SELECT count(city) cnt, city FROM `2020_speaker` group by city ORDER BY `2020_speaker`.`city` ASC

$content="";

$content.="<div class='col-12 p-1'>";
$content.="<div class='border border-info' id='grades'>";
// $content.="<h4 class='bg-info text-white p-2'>Оценки</h4>";
$content.=TU::statGrades();
$content.="</div>";
$content.="</div>";




?>