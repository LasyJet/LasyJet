<?
include_once("../config.php");
include_once("../classes/adm.class.php");
//SELECT count(city) cnt, city FROM `2020_speaker` group by city ORDER BY `2020_speaker`.`city` ASC

$content="";

$dataCountry=TU::statCountry();
$statCountry=TU::Data2Table($dataCountry);
$content.="<div class='col-lg-3 p-1'>";
$content.="<div class='stat border border-info' id='country'>";
$content.="<h4 class='bg-info text-white p-2'>Страны</h4>";
$content.=$statCountry;
$content.="</div>";
$content.="</div>";

$dataCity=TU::statCity();
$statCity=TU::Data2Table($dataCity);
$content.="<div class='col-lg-3 p-1'>";
$content.="<div class='stat border border-info' id='city'>";
$content.="<h4 class='bg-info text-white p-2'>Города</h4>";
$content.=$statCity;
$content.="</div>";
$content.="</div>";

$dataCompany=TU::statCompany();
$statCompany=TU::Data2Table($dataCompany);
$content.="<div class='col-lg-3 p-1'>";
$content.="<div class='stat border border-info' id='company'>";
$content.="<h4 class='bg-info text-white p-2'>Организации</h4>";
$content.=$statCompany;
$content.="</div>";
$content.="</div>";

$dataSection=TU::statSection();
$statSection=TU::Data2Table($dataSection);
$content.="<div class='col-lg-3 p-1'>";
$content.="<div class='stat border border-info' id='company'>";
$content.="<h4 class='bg-info text-white p-2'>Секции</h4>";
$content.=$statSection;
$content.="</div>";
$content.="</div>";

$data=TU::ExpertsRating();
$table=TU::Data2Table($data);
$content.="<div class='col-lg-3 p-1'>";
$content.="<div class='border border-info' id='experts_rating'>";
$content.="<h4 class='bg-info text-white p-2'>Статистика оценки тезисов</h4>";
$content.=$table;
$content.="</div>";
$content.="</div>";

$data=TU::gradeCount();
$table=TU::Data2Table($data);

$this_cnt=4;
$data2=TU::fewGradeCount($this_cnt);
$table2=TU::Data2Table($data2);

$content.="<div class='col-lg-3 p-1'>";
$content.="<div class='border border-info stat' id='count_theses_grades'>";
$content.="<h4 class='bg-info text-white p-2'>Число тезисов - число оценок</h4>";
$content.=$table;
$content.="</div>";
$content.="<div class='border border-info stat'  id='few_grades'>";
$content.="<h4 class='bg-info text-white p-2'>Тезисы с количеством оценок &lt; $this_cnt</h4>";
$content.=$table2;
$content.="</div>";
$content.="</div>";

$data=TU::reportTypeCount();
$table=TU::Data2Table($data);
$content.="<div class='col-lg-3 p-1'>";
$content.="<div class='border border-info' id='report_type_count'>";
$content.="<h4 class='bg-info text-white p-2'>Распределение докладов по типу</h4>";
$content.=$table;
$content.="</div>";
$content.="</div>";



$data=TU::howMuchMoney(); //return data(count=>##; money=>xxx)
$rfbrCnt=TU::statRFBR();
$content.="<div class='col-lg-3 p-1'>";
$content.="<div class='border border-info stat'  id='howMychMoney'>";
$content.="<h4 class='bg-info text-white p-2'>Взносы</h4>";
$content.="<p>Олачено взносов: ".$data['count']."</p>";
$content.="<p>На сумму =".$data['money']." руб. </p>";
// $content.="<div class='border border-info stat'  id='howMychRfbr'>";
// $content.="<h4 class='bg-info text-white p-2'>Участников с РФФИ</h4>";
// $content.="<p>".$rfbrCnt."</p>";
// $content.="</div>";
$content.="</div>";

?>