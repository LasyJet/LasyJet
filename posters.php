<?
header('Content-Type: text/html; charset=utf-8');
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);

include_once("config.php");
include_once("classes/adm.class.php");

if(isset($_GET['quit'])) {
    session_unset($_SESSION['admin'], $_SESSION['login'], $_SESSION['adminName'], $_SESSION['loginError']);
    header('Location: '.SITE.'/adm');
}
$clean=(isset($_GET['clean']))?TRUE:FALSE;
$fee=(isset($_GET['fee']))?TRUE:FALSE;
$info=(isset($_GET['info']))?TRUE:FALSE;

// echo "Постеры";
$Posters=TU::getPosters();
$section="";
$content="";
$session=0;
$total_posters=0; 
$no_money=0;

// $roman_digits=array(1=>"I", 2=>"II",3=>"III",4=>"IV",5=>"V",6=>"VI",7=>"VII",8=>"VIII",9=>"IX",10=>"X",11=>"XI",12=>"XII");
$roman_digits=array(1=>"I-1", 2=>"I-2",3=>"II-1",4=>"II-2",5=>"III-1",6=>"III-2",7=>"IV-1",8=>"IV-2",9=>"V-1",10=>"V-2",11=>"VI-1",12=>"VI-2");

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
                    <th class='number text-center'>N стенда</th>".
                    ((!$clean)?"<th>PDF</th>":"").
                    "<th>Доклад</th>
                </tr>
                <tr><td class='session' colspan='".((!$clean)?"3":"2")."'>
                <a name='session".$session."'></a>
                <h3>
                Сессия ".$roman_digits[$session]."
                </h3>
                </td></tr>
                ";
        }

/*         if($section!=$p['section']){
            $section=$p['section'];
            $content.="\n<tr><td class='section' colspan='".((!$clean)?"3":"2")."'><h4>".$section."</h4></td></tr>";
        } 
*/
    
    // $physica_sight="http://physica.spb.ru/program2020/program2020poster/";
    $no_fee=(($fee) && $p['fee']=="---")?"class='text-danger'":"";
    
    if($p['fee']=="---") $no_money++;
    if(strlen($p['poster_file'])>1) $total_posters++;

    $link=(strlen($p['poster_file'])>1)?"<a  href='".SITE."".$p['poster_file']."' class='text-danger'>".$pdfIcon."</a>":"";
    $content.="\n<tr><td class='number text-center'><a name='".$p['poster_num']."'>".$p['poster_num']."</a></td>".
    ((!$clean)?"<td>".$link."</td>":"").
    "<td  $no_fee><span class='speaker'>".$p['name']."</span>, <span class='company'>".$p['company']."</span><br>".$p['title']."</td></tr>";
}

$content.="<tbody></table>";
$content.=($info)?"\n<p>Всего загружено постеров ".$total_posters." </p>":"";
// $content.=(!$clean)?"\n<p><span class='text-danger'>Красные ($no_money) </span> на заплатили или не отмечены инно-миром</p>":"";
$content.=($fee)?"\n<p><span class='text-danger'>Красные ($no_money) </span> на заплатили или не отмечены инно-миром</p>":"";

$str='';
$str.="<h1>".ConfTitle."/".YEAR.": Программа стендовых сессий</h1>";
$content=$str.$content;

$tpl=file_get_contents("adm/schedule/posters_tpl.html");
$content=str_ireplace("{%posters%}", $content, $tpl);
echo $content;

// file_put_contents("../posters".YEAR.".html", $content);
// echo "\n\n\n</body></html>";
?>