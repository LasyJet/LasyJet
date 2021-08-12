<?
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
include("config.php");
include_once("classes/phpmailer/sendmail.php");

$dbh = new PDO('mysql:host='.$dbhost.';dbname='.$physica_db, $mysqluser, $mysqlpass);
$dbh->query("SET NAMES utf8");

if(isset($_POST['forgot'])){

    $sql="SELECT `user_id`, `password` FROM `".YEAR."_speaker` WHERE email=:email";
    $stmt =$dbh->prepare($sql);
    $stmt->bindValue(":email", $_POST['email']);
    $stmt->execute();
    $data=$stmt->fetch(PDO::FETCH_ASSOC);
    $cnt=$stmt->rowCount();
    if($cnt==1){
        
        if (recall_passwd_mail($_POST['email'],$data['password'])){
            $data['recall_pwd']=1;
        }
    }
    else $data['recall_pwd']=0;
    
    print(json_encode($data));
    
    exit();
}

if(isset($_POST['email'])):

    $sql="SELECT `user_id` FROM `".$YEAR."_speaker` WHERE email=:email AND password=:password";
    $stmt =$dbh->prepare($sql);
    $stmt->bindValue(":email", $_POST['email']);
    $stmt->bindValue(":password", $_POST['password']);
    $stmt->execute();
    $data=$stmt->fetch(PDO::FETCH_ASSOC);
    $cnt=$stmt->rowCount();
    
    if($cnt==1){
        $_SESSION['user_id']=$data['user_id'];
        // print(json_encode($_SESSION));
        print(json_encode($data));
    }
    else{
        $data['user_id']=false;
        print(json_encode($data));
    }
else:
    print(json_encode($data['user_id']=false));
endif;

#####################################
function recall_passwd_mail($email, $password){
  
    $subj="PhysicA.SPb/".YEAR." restore access";
    $msg= "Your password is \"{$password}\"";
    
    return smtp_mail($email, $subj, $msg);
    
    // if (smtp_mail($to_email, $subj, $msg)){
    //     write_log("\n\nMail to $to_email \n----\n was sent at $now\n-------------\n".strip_tags($msg));
    //     return true;
	// }
    // else {
    //     write_log("\n ERROR> $now : Мail to $to_email did not send");
    //     return false;

}

?>