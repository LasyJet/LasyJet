<? 
#email_send.php
header('Content-Type: text/html; charset=utf-8');
include("../../config.php");
include_once("../../classes/phpmailer/sendmail.php");

$pdo = new PDO('mysql:host='.$dbhost.';dbname='.$physica_db, $mysqluser, $mysqlpass);
$pdo->query("SET NAMES utf8");

function writeSendMailStatus($user_id, $message){
    $SQL="INSERT INTO `".YEAR."_messages` SET `user_id`=:user_id, `messages`=:message, `datetime`=now()";
}

if(empty($_POST['user_id'])){
    echo "no user data";
}
else{


    $SQL ="SELECT `s`.`user_id`, `s`.`familyname`, `s`.`givenname`, `s`.`parentname`, `s`.`gender`, `s`.`email`, ";
    $SQL.="`t`.`title`, `t`.`report_type` "; 
    $SQL.="FROM `".YEAR."_speaker` `s` JOIN `".YEAR."_thesises` `t` ON `s`.`user_id`=`t`.`user_id` ";
    $SQL.="WHERE `s`.`user_id`=:user_id"; 

    $stmt =$pdo->prepare($SQL);
    $stmt->bindValue(":user_id", $_POST['user_id']);
    $stmt->execute();
    $data=$stmt->fetch(PDO::FETCH_ASSOC);
    
    $email=$data["email"];
    $subject=$_POST["subject"];
    $message=$_POST["message"];
    
    // if(smtp_mail($email, $subject, $message)){
    //     writeSendMailStatus($user_id, $message);
    //     echo $_POST['user_id'];
    // }

    //  echo json_encode( $_POST);    
    //  echo json_encode( $data);    
    // echo $data['email']." ".$_POST['subject']." ".$_POST['message'];    
}


?>