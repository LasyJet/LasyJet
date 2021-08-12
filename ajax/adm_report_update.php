<?
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
header("Content-Type: text/html; charset=utf-8");
include_once("../config.php");

$dbh = new PDO('mysql:host='.$dbhost.';dbname='.$physica_db, $mysqluser, $mysqlpass);
$dbh->query("SET NAMES utf8");

if(isset($_POST)){

    $thesis_id=$_POST['id'];
    $content=$_POST['value'];
    $field_name='report_type';
    $duration=(in_array($content, array_keys(TALK_DURATION)))?TALK_DURATION[$content]:0;

    $sql ="UPDATE `".YEAR."_thesises` SET `$field_name`='$content', `schedule_duration`='".$duration."' WHERE `thesis_id`='$thesis_id'";
    // echo $sql;
    $stm=$dbh->prepare($sql);
    $res=$stm->execute();
    if($res){
        echo $content;
    }
    
}


?>

