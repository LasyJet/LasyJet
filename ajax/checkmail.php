<?
if(isset($_POST['email'])){
    include("../config.php");
    $dbh = new PDO('mysql:host='.$dbhost.';dbname='.$physica_db, $mysqluser, $mysqlpass);
    $sql="SELECT count(*) FROM ".$YEAR."_speaker WHERE email=:email";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(array('email'=>$_POST['email']));
    $data=(int)$stmt->fetchColumn();
    if($data>0) echo(1);
      else echo(0);
    // echo(json_encode($_POST['email']));
    }
else{
    echo("No data!");
}
?>