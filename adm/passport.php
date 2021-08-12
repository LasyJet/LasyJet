<?
header('Content-Type: text/html; charset=utf-8');
include_once("../config.php");
echo "\n<html>";
echo "\n<title>Паспорта</title>";
echo "\n<head>";
echo "\n<link href='/css/datatables.min.css' rel='stylesheet'>";
echo "\n<link href='/css/mdb.min.css' rel='stylesheet'>";
echo "\n<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'>";
echo "\n<script src='/js/jquery.min.js'></script>";
echo "\n<script src='/js/mdb.min.js'></script>";
echo "\n<script src='/js/datatables.min.js'></script>";

echo "\n<script>
$(document).ready(function() {
    $('#passport').DataTable({
        'language': {
          'url': '//cdn.datatables.net/plug-ins/1.10.21/i18n/Russian.json'
         // 'url': '/dataTables/Plugins/i18n/RU_ru.lang'
        }
      } );
    console.log('started');
} );
</script>";
echo "\n<style>th {font-weight:bold; background:lightgrey; margin:0; paddind:0; vertical-align:middle}</style>";
echo "\n</head>";
echo "\n<body>";
if(isset($_GET['quit'])) {
    session_unset($_SESSION['admin'], $_SESSION['login'], $_SESSION['adminName'], $_SESSION['loginError']);
    header('Location: '.SITE.'/adm');
}

include_once("../classes/adm.class.php");
$Passports=TU::getPassport();
$Online=TU::getOnlineParticipants();
$TotalRuPassport=TU::getRUPassport();
$noPassport=$TotalRuPassport-$Passports['count']-$Online;
echo "<p><b>Указали паспорта [".$Passports['count']."].  Участвуют онлайн [".$Online."]</b></p>";
echo "<p>Всего {$TotalRuPassport}. Безответных ".$noPassport."</hp>";

// `user_id`,
//  `Name`, 
// `substitute` 
// `passport` 

$content="<table id='passport'>";
$content.="<thead>";
$content.="\n<tr><th>id</th><th>user_id</th><th>Дата обновления</th><th>Имя</th><th>Почта</th><th>Замена</th><th>Паспорт</th><th>Оплата</th></tr>";
$content.="</thead>";
$content.="<tbody>";
foreach($Passports['data'] as $p)
{
    $content.="\n<tr><td>".$p['id']."</td><td>".$p['user_id']."</td><td>".$p['passport_update']."</td><td>".$p['Name']."</td><td>".$p['email'].
    "</td><td>".$p['substitute']."</td><td>".$p['passport']."</td><td>".
    (($p['fee']!='---')?"Да":"Нет")."</td></tr>";
}
$content.="<tbody></table>";


$list = $Passports['data'];
array_unshift($list ,['user_id','Имя','Замена','Паспорт']);

foreach ($list  as &$str)
{
    foreach ($str as $k => &$elm){
    $elm = iconv("UTF-8", "CP1251",$elm);
    }

}

$fp = fopen('passports.csv', 'w');

foreach ($list as $fields) {
    fputcsv($fp, $fields, ";");
}

fclose($fp);

echo "<p><a href='./passports.csv'>Паспорта.csv</a></p>";
echo $content;
// file_put_contents("posters".YEAR.".html", $content);
echo "</body>";
echo "</html>";
?>