<?
//save schedule
header("Content-Type: text/html; charset=utf-8");
include_once("../../config.php");

$pdo = new PDO('mysql:host='.$dbhost.';dbname='.$physica_db, $mysqluser, $mysqlpass);
$pdo->query("SET NAMES utf8");

// $sql ="UPDATE `".YEAR."_thesises` SET `$field_name`='$content', `schedule_duration`='".$duration."' WHERE `thesis_id`='$thesis_id'";
// echo $sql;

// id,arrange, date, time, duration, item, report, thesis_id

 $stmt = $pdo->prepare(
     "INSERT INTO `".YEAR."_schedule`  (arrange, date, time, duration, item, report, thesis_id) 
                            VALUES 
                                (:Order, :Date, :Time, :Duration, :Item, :Report, :thesis_id)
                        ");

$sql="INSERT INTO `".YEAR."_schedule` (arrange, date, time, duration, item, report, thesis_id) VALUES ";


/*
 try {
    $pdo->beginTransaction();
    foreach ($data as $row)
    {
        $stmt->execute($row);
    }
    $pdo->commit();
}catch (Exception $e){
    $pdo->rollback();
    throw $e;
}
 */

if(!empty($_POST)){
    $data=json_decode($_POST['data'], true);
    foreach($_POST['data']){
    
    }

    print_r($data);
}
else {
    echo "ERROR";
}
?>