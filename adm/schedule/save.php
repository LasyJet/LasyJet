<?
//save schedule
header("Content-Type: text/html; charset=utf-8");
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

include_once("../../config.php");

$pdo = new PDO('mysql:host='.$dbhost.';dbname='.$physica_db, $mysqluser, $mysqlpass);
$pdo->query("SET NAMES utf8");

// id,
// arrange, date, time, duration, talk, report, thesis_id - fields from database  
// :Order, :Date, :Time, :Duration, :Talk, :Report, :thesis_id - from POST['data']

if(!empty($_POST)){
    $data=json_decode($_POST['data'], true);
    
    $stmt=$pdo->query("SELECT id FROM `".YEAR."_schedule`");
    $stmt->execute();
    $res=$stmt->fetchALL(PDO::FETCH_COLUMN);
    $exist_ids=implode(",", $res);

    $stmt = $pdo->prepare(
        "INSERT INTO `".YEAR."_schedule`  (arrange, date, time, duration, talk, report, thesis_id) 
                               VALUES 
                                   (:order, :date, :time, :duration, :talk, :report, :thesis_id)
                           ");

    foreach ($data as $key=>$d){
        // echo "\n\n"."Element ".$key."\r\n";
        unset($d['Section']);

        foreach($d as $k=>$v) {
                $d[":".strtolower($k)]=$d[$k];
                unset($d[$k]);
        }
        try{
           $stmt->execute($d);

        }
        catch (Exception $e) {
            echo $e->getMessage();
        }
    
    }

    if (empty($e)){
    // echo "DELETE  FROM `".YEAR."_schedule` WHERE id in (".$exist_ids.")";
       $stmt=$pdo->query("DELETE FROM `".YEAR."_schedule` WHERE id in (".$exist_ids.")");
       $stmt->execute();
       
    }
    echo 'saved';
}
else {
    echo "error";
}


?>