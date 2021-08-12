<?
include_once("../config.php");
include_once("../classes/adm.class.php");
//SELECT count(city) cnt, city FROM `2020_speaker` group by city ORDER BY `2020_speaker`.`city` ASC

$content=""; 

$data=TU::getPWD();
$content.="<div class='col-lg-8 p-1'>";
$content.="<div class='stat border border-info' id='country'>";
$content.="<h4 class='bg-info text-white p-2'>Пароли</h4>";
$content.=$data;
$content.="</div>";
$content.="</div>";


?>