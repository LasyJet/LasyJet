<?
// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
include_once("config.php"); // настройки сайта, в т.ч. БД
$PDO = new PDO('mysql:host='.$dbhost.';dbname='.$physica_db, $mysqluser, $mysqlpass,  array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
$realParam=base64_decode($_SERVER['QUERY_STRING']); //переконвертация из base64
parse_str($realParam, $parts);
$parts=array_map('strip_tags', $parts);//эту строку можно убрать
$disabled=array('familyname', 'givenname','email'); // поля которые пользователь менять не может
$inputs='';
foreach($parts as $key=>$val){
    $inputs.="\n\r<input name='".$key."' type='".(($key!="userid")?"text":"hidden")."' value='".$val."' ".((in_array($key, $disabled))?"disabled":"")."><br>";
}

echo "\n\r<form name=''>$inputs\n\r<input type='submit'>\n\r</form>";

/*
Здесь проводится транзакция
Если успешно, то вставляем запись в БД
*/

//создаём БД, но это однократное действие, которое можно исключить из этого кода
####### отдельным файлом ########

// include_once("config.php"); // настройки сайта, в т.ч. БД
// $PDO = new PDO('mysql:host='.$dbhost.';dbname='.$physica_db, $mysqluser, $mysqlpass,  array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
$DataBase="CREATE TABLE IF NOT EXISTS `payers` ( `id` INT NOT NULL AUTO_INCREMENT , `userid` INT NOT NULL , `fullname` VARCHAR(255) NOT NULL , `email` VARCHAR(320) NOT NULL , `MobilePhone` VARCHAR(18) NOT NULL , `fee` DECIMAL(5,0) NOT NULL , `date` DATETIME NOT NULL , PRIMARY KEY (`id`), UNIQUE (`userid`)) ENGINE = InnoDB;";
$PDO->query($DataBase);

$parts['fullname']=$parts['familyname']." ".$parts['givenname']." ".$parts['parentname']; // в БД будем писать ФИО в одно поле
unset($parts['familyname'],$parts['givenname'],$parts['parentname']); //чтобы не передвать в bindParam

$sql="INSERT INTO `payers` (`userid`, `fullname`, `email`, `MobilePhone`, `fee`) VALUES (:userid, :fullname, :email, :MobilePhone, :f)";
$payerStmt = $PDO->prepare($sql);

foreach($parts as $key=>&$val) $payerStmt->bindParam($key, $val);

$already_exists=false;
try {
    $payerStmt->execute();
} catch (PDOException $e) {
    if ($e->errorInfo[1] == 1062) {
        $already_exists = true;
    } else {
        throw $e;
    }
}
echo ($already_exists)?"Платёж был совершен ранее":"Платёж совершен";

#### ВЫВОД csv-файла ####### отдельным скриптом
// include_once("config.php"); // настройки сайта, в т.ч. БД
// $PDO = new PDO('mysql:host='.$dbhost.';dbname='.$physica_db, $mysqluser, $mysqlpass,  array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
$csv='';
$query="SELECT `userid`, `fullname`, `email`, `MobilePhone`, `fee`, `date` FROM `payers` ORDER by `date`";
$sth=$PDO->prepare($query);
$sth->execute();
$data=$sth->fetchAll(PDO::FETCH_ASSOC);

foreach($data as $row){
    $csv.="<br>".implode(";",$row)."\n\r";
}
echo $csv;

exit;
?>