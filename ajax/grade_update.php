<?
// grade_update.php
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);


header("Content-Type: text/html; charset=utf-8");
include_once("../config.php");


$dbh = new PDO('mysql:host='.$dbhost.';dbname='.$physica_db, $mysqluser, $mysqlpass);
$dbh->query("SET NAMES utf8");


if(!empty($_POST['grade']) && !empty($_POST['thesis_id']) && !empty($_POST['expert_id'])){
 
    $sql="SELECT * FROM `".YEAR."_grades` WHERE `thesis_id`=:thesis_id AND `expert_id`=:expert_id";
    $stm=$dbh->prepare($sql);
    $stm->bindValue(":thesis_id",$_POST['thesis_id']);
    $stm->bindValue(":expert_id",$_POST['expert_id']);
    $stm->execute();
    $count = $stm->rowCount();

    if ($count==0){
        $sql ="INSERT INTO `".YEAR."_grades`  
        SET `thesis_id`=:thesis_id,`expert_id`=:expert_id,`grade`=:grade";
        echo "<br>".$sql;
        $stm=$dbh->prepare($sql);
        $stm->bindValue(":expert_id",$_POST['expert_id']);
        $stm->bindValue(":thesis_id",$_POST['thesis_id']);
        $stm->bindValue(":grade",$_POST['grade']);
        $stm->execute();
    }
    else{
        $sql="UPDATE `".YEAR."_grades` SET `grade`=:grade, `datetime`=now()  WHERE `thesis_id`=:thesis_id AND `expert_id`=:expert_id";
        $stm=$dbh->prepare($sql);
        $stm->bindValue(":thesis_id",$_POST['thesis_id']);
        $stm->bindValue(":grade",$_POST['grade']);
        $stm->bindValue(":expert_id",$_POST['expert_id']);
        $stm->execute();
    }
    echo true;
}
else 
    echo false;

?>

