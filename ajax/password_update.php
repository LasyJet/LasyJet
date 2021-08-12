<?
//thesis_update.php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);


header("Content-Type: text/html; charset=utf-8");
include_once("../config.php");
include_once("../functions.php");
include_once("../".$lang_file);

$dbh = new PDO('mysql:host='.$dbhost.';dbname='.$physica_db, $mysqluser, $mysqlpass);
$dbh->query("SET NAMES utf8");


if(!empty($_POST['passwd']) && !empty($_SESSION['user_id'])){

   $password=strip_tags($_POST['passwd']);

    $sql ="UPDATE `".YEAR."_speaker` SET `password`=:password WHERE `user_id`=:user_id";
    // // echo $sql;
    $stm=$dbh->prepare($sql);
    $stm->bindValue(":user_id",$_SESSION['user_id']);
    $stm->bindValue(":password",$password);
    if($stm->execute()){
        echo true;
    }
    echo false;

}
else echo false;

?>

