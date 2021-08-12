<?
//thesis_update.php
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);


header("Content-Type: text/html; charset=utf-8");
include_once("../config.php");
include_once("../functions.php");
include_once("../".$lang_file);

$dbh = new PDO('mysql:host='.$dbhost.';dbname='.$physica_db, $mysqluser, $mysqlpass);
$dbh->query("SET NAMES utf8");
//  print($_POST['id']." ".$_POST['value']);

if(isset($_POST)){

    $content=$_POST['content'];
    $field_name=$_POST['field_name'];
    $thesis_id=$_POST['thesis_id'];
// echo $field_name." ".$thesis_id." ".$content;

    if($field_name=='affiliations') {
        $content=ltrim(strip_tags($content,"<p><br/><br><sup>")," ,\t");
        $content=str_replace(array('<p>','</p>'),array('','<br>'), $content);
        $content=str_replace('<br><br>','<br>', $content);
        // $content=mb_ereg_replace('[<br>|<br />]$', '', $content);
    }
    elseif($field_name=='title'){
        $content=strip_tags($content,"<p><img><i><em><sub><sup>");
    }
    else 
        $content=strip_tags($content,"<br/><br><p><img><b><strong><i><em><ul><li><ol><sub><sup>");

	$content=mb_ereg_replace('data-cke-saved-src="[^"]*"', '', $content);
	$content=mb_ereg_replace('src="file://[^"]*"', '', $content);

    if($field_name!='text')  $content=addslashes($content);
	
    $sql ="UPDATE `".YEAR."_thesises` SET `{$field_name}`=:{$field_name},`lastupdate`=now() WHERE `thesis_id`='$thesis_id'";
    // // echo $sql;
    $stm=$dbh->prepare($sql);
    $stm->bindValue(":".$field_name, $content);
    if($stm->execute()) echo 1;
    // echo $content;
    // $data=$stm->fetch(PDO::FETCH_ASSOC);
    // echo $sql;//true;
}


?>

