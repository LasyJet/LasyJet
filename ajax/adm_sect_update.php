<?
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
header("Content-Type: text/html; charset=utf-8");
include_once("../config.php");

$dbh = new PDO('mysql:host='.$dbhost.';dbname='.$physica_db, $mysqluser, $mysqlpass);
$dbh->query("SET NAMES utf8");
//  print($_POST['id']." ".$_POST['value']);

// echo json_encode($_POST);

if(isset($_POST)){

    $thesis_id=$_POST['id'];
    $content=$_POST['value'];
    $field_name='section';

    $sql ="UPDATE `".YEAR."_thesises` SET `$field_name`='$content' WHERE `thesis_id`='$thesis_id'";
    // echo $sql;
    $stm=$dbh->prepare($sql);
    $res=$stm->execute();
    if($res){
        echo $content;
    }
    
    // if ($field_name=='section')
    //     echo $sess_title[$_SESSION['lang']];
    // else
    //     echo $content;
    // $data=$stm->fetch(PDO::FETCH_ASSOC);
}


?>

