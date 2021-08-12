<?
//thesis_update.php
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
header("Content-Type: text/html; charset=utf-8");
include_once("../config.php");
// include_once("../functions.php");
// include_once("../".$lang_file);

$dbh = new PDO('mysql:host='.$dbhost.';dbname='.$physica_db, $mysqluser, $mysqlpass);
$dbh->query("SET NAMES utf8");

if(!empty($_POST['id']) && !empty($_SESSION['admin'])){

    $sql ="UPDATE `".YEAR."_speaker` SET `deleted`='deleted' WHERE `id`=:id";
    $stm=$dbh->prepare($sql);
    $stm->bindValue(":id",$_POST['id']);
    if($stm->execute()){
        echo true;
    }
    echo false;

}
else echo false;

?>

