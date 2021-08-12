<?
header('Content-Type: text/html; charset=utf-8');
include_once("../config.php");

if(isset($_GET['quit'])) {
    session_unset($_SESSION['admin'], $_SESSION['login'], $_SESSION['adminName'], $_SESSION['loginError']);
    header('Location: '.SITE.'/adm');
}
if(isset($_GET['clean'])) $clean=TRUE;
include_once("../classes/adm.class.php");
// echo "Постеры";
$Posters=TU::getPosters();
$section="";
$content="";
$session=0;
$total_posters=0; 
$no_money=0;
$header='
<html>
<head>
<title>ПОСТЕРЫ</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .speaker{font-weight:bold}
        .number{color:green}
        .company{font-style:italic}
        .section{font-weight:160%}
        .text-danger{color:red}
    </style> 
</head>
<body>';


// var_dump($Posters);

foreach($Posters as $p)
{
        if($session!=$p['poster_session']){
            $session=$p['poster_session'];
            $content.=($session>0)?"</tbody></table>":"";
            $content.="\n<p>&nbsp;</p><table>
            <colgroup>
                <col style='width:2rem'; text-align:center !important'/>
                <col style='width:2 rem' />
                <col />
            </colgroup>
            <tbody>
                <tr>
                    <th class='number'>N стенда</th>".
                    ((!$clean)?"<th>PDF</th>":"").
                    "<th>Доклад</th>
                </tr>
                <tr><td class='session' colspan='".((!$clean)?"3":"2")."'>Сессия ".$session."</td></tr>
        ";
        }

        if($section!=$p['section']){
            $section=$p['section'];
            $content.="\n<tr><td class='section' colspan='".((!$clean)?"3":"2")."'><h2>".$section."<h2></td></tr>";
        }
    
    $physica_sight="http://physica.spb.ru/program2020/program2020poster/";
    $no_fee=(isset($_GET['nofee']) && $p['fee']=="---")?"class='text-danger'":"";
    
    if($p['fee']=="---") $no_money++;
        //.$p['num_sect'].": "
    if(strlen($p['poster_file'])>1) $total_posters++;
    $link=(strlen($p['poster_file'])>1)?"<a  href='".SITE."".$p['poster_file']."' class='text-danger'>".$pdfIcon."</a>":"";
    $content.="\n<tr><td class='number'><a name='".$p['poster_num']."'>".$p['poster_num']."</a></td>".
    ((!$clean)?"<td>".$link."</td>":"").
    "<td  $no_fee><span class='speaker'>".$p['name']."</span>, <span class='company'>".$p['company']."</span><br>".$p['title']."</td></tr>";
}

$content.="<tbody></table>";
$str.=$header."<h1>Программа постерных сессий</h1>";
$str.="\n<p>Всего ".$total_posters." </p>";
$str.=(!$clean)?"\n<p><span class='text-danger'>Красные ($no_money) </span> на заплатили или не отмечены инно-миром</p>":"";
$content=$str.$content;
echo $content;

file_put_contents("../posters".YEAR.".html", $content);
echo "\n\n\n</body></html>";
?>