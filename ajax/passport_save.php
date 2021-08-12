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


if(!empty($_POST['passport']) && !empty($_SESSION['user_id'])){

    // $breaks = array("<br />","<br>","<br/>");  
    // $passport = str_ireplace($breaks, "\r\n", $passport); 
    
    $passport=strip_tags($_POST['passport'],"<p>, <br>, <div>");
    $passport=str_replace("&nbsp;", ' ', $passport);
    $passport=trim($passport, ' \t\n\r\x0B\xC2\xA0');

    $sql ="UPDATE `".YEAR."_speaker` SET `passport`=:passport, `passport_update`=now() WHERE `user_id`=:user_id";
    // // echo $sql;
    $stm=$dbh->prepare($sql);
    $stm->bindValue(":user_id",$_SESSION['user_id']);
    $stm->bindValue(":passport",$passport);
    if($stm->execute()){
        echo true;
    }
    echo false;

}
else echo false;

?>

