<?
header("Content-Type: text/html; charset=utf-8");
include("../config.php");
// include_once("../".$lang_file);

$dbh = new PDO('mysql:host='.$dbhost.';dbname='.$physica_db, $mysqluser, $mysqlpass);
$dbh->query("SET NAMES utf8");

$sql="SELECT * FROM `sections`";
$stmt = $dbh->prepare($sql);
$stmt->execute();


while($row=$stmt->fetch(PDO::FETCH_ASSOC))
{
   $options[$row['id']]=$row[$_SESSION[lang]];

}

// echo"<pre>";
// var_dump($options);
// echo"</pre>";

 print(json_encode($options));

?>