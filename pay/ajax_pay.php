<?
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);

header('Content-Type: text/html; charset=utf-8');
include_once("../config.php");

if(!empty($_POST)){
    $dbh = new PDO('mysql:host='.$dbhost.';dbname='.$physica_db, $mysqluser, $mysqlpass);
    $dbh->query("SET NAMES utf8");
    
    $fee=$_POST['value'];
    $fee_date=date("Y-m-d"); 
    $id=$_POST['id'];

    $sql ="UPDATE `".YEAR."_speaker` SET `fee`=:fee, `fee_date`=:fee_date WHERE `id`=:id";
    //  echo $sql;    
    $stm=$dbh->prepare($sql);
    $stm->bindValue(":fee", $fee);
    $stm->bindValue(":id", $id);
    $stm->bindValue(":fee_date", $fee_date);
    if($stm->execute()) {
        // echo json_encode($_POST);
        echo json_encode(array('fee'=>$fee, 'date'=>$fee_date, 'id'=>$id));

    }
    else echo "Error";
}
//pay.php

?>