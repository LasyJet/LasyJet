<? 
# unsubscribe from mailing
header('Content-Type: text/html; charset=utf-8');
include("../config.php");

if(empty($_GET['email'])){
    $result=0;
}
else{
    $dbh = new PDO('mysql:host='.$dbhost.';dbname='.$physica_db, $mysqluser, $mysqlpass);
    $dbh->query("SET NAMES utf8");

    $SQL="UPDATE `Total_email_list` SET `deleted`='deleted', `datetime`=now() WHERE `email`=:email";
    
    $stmt =$dbh->prepare($SQL);
    $stmt->bindValue(":email", $_GET['email']);
    $stmt->execute();
    $result=$stmt->rowCount();
}

if($result>0) 
    echo "<p>Your email address has been removed from Conference PhysicA.SPb mailing list.<p>";
else 
    echo "<p>Nothing to do!</p>";

?>