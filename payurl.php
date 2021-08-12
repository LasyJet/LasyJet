<?

include_once("config.php");
include_once("classes/index.fn.php");


$PDO = new PDO('mysql:host='.$dbhost.';dbname='.$physica_db, $mysqluser, $mysqlpass);
$PDO->query("SET NAMES utf8");

$user_id= $_GET['h']; 
$sql="SELECT `id`, `familyname`, `givenname`, `parentname`, `email`, `phone` FROM ".YEAR."_speaker WHERE user_id=:user_id";
$stmt = $PDO->prepare($sql);
$stmt->bindValue(":user_id", $user_id);
$stmt->execute();
$data=$stmt->fetch(PDO::FETCH_ASSOC);



$pay_link="http://inno-mir.ru/"."ru/payment?";
$pay_link="https://reg.physica.spb.ru/payget.php?";
$pay_url.="userid=".$data['id'];
$pay_url.="&familyname=".$data['familyname'];
$pay_url.="&givenname=".$data['givenname'];
$pay_url.="&parentname=".$data['parentname'];
$pay_url.="&email=".$data['email'];
$pay_url.="&MobilePhone=".$data['phone'];
$pay_url.="&f=".FEE;
$pay_url =base64_encode($pay_url);

echo $pay_link.$pay_url;
// header("Location: $pay_link.$pay_url");  
exit;
?>