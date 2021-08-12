<?
//excusion_status_update
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);


header("Content-Type: text/html; charset=utf-8");
include_once("../config.php");


$dbh = new PDO('mysql:host='.$dbhost.';dbname='.$physica_db, $mysqluser, $mysqlpass);
$dbh->query("SET NAMES utf8");

if(!empty($_POST['excursion_status']) && !empty($_SESSION['user_id'])){

    $excursion_status=($_POST['excursion_status']=='true')?1:0;

    $sql ="UPDATE `".YEAR."_speaker` SET `excursion_status`=:excursion_status WHERE `user_id`=:user_id";
    // // echo $sql;
    $stm=$dbh->prepare($sql);
    $stm->bindValue(":user_id",$_SESSION['user_id']);
    $stm->bindValue(":excursion_status",$excursion_status, PDO::PARAM_BOOL);
    if($stm->execute()){
        echo $excursion_status;
    }
    else echo "Something wrong";

}
else echo "Something wrong";

?>

