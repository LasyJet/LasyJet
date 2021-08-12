<?
header('Content-Type: text/html; charset=utf-8');
include_once("config.php");

if(isset($_GET['quit'])) {
    session_unset($_SESSION['admin'], $_SESSION['login'], $_SESSION['adminName'], $_SESSION['loginError']);
    header('Location: '.SITE.'/adm');
}

include_once("classes/adm.class.php");
// echo "Постеры";
$Posters=TU::getPoster();
$section="";
$content="";
$session=0;
echo "
<html>
<head>
    <style>
    .speaker{font-weight:bold}
    .company{font-style:italic}
    .section{font-weight:160%}
    </style> 
</head>
<body>";
foreach($Posters as $p)
{
        if($session!=$p['poster_session']){
            echo $session=$p['poster_session'];
            $content.=($session>0)?"</tbody></table>":"";
            $content.="\n<p>&nbsp;</p><table>
            <colgroup>
                <col style='text-align:center !important'/>
                <col />
            </colgroup>
            <tbody>
                <tr>
                    <th>N стенда</th>
                    <th>Доклад</th>
                </tr>
                <tr><td class='session' colspan='2'>Сессия ".$session."</td></tr>
        ";
        }

        if($section!=$p['section']){
            $section=$p['section'];
            $content.="\n<tr><td class='section' colspan='2'><h2>".$section."<h2></td></tr>";
        }

        //.$p['num_sect'].": "
    $content.="\n<tr><td>".$p['poster_session']."-".$p['poster_num'].
    "<td><span class='speaker'>".$p['name']."</span>, <span class='company'>".$p['company']."</span><br>".$p['title']."</td></tr>";
}
$content.="<tbody></table>";
echo $content;

file_put_contents("posters".YEAR.".html", $content);
echo "\n\n\n</body></html>";
?>