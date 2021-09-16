<?
header("Content-Type: text/html; charset=utf-8");
include_once("../../config.php");

$dbh = new PDO('mysql:host='.$dbhost.';dbname='.$physica_db, $mysqluser, $mysqlpass);
$dbh->query("SET NAMES utf8");

if(isset($_POST)){
    $recp_group=$_POST['recipients'];
    // перевод занчение selected  в строку для запроса - function WHERE_recp_str()

    // $SQL ="SELECT `s`.`user_id`, `s`.`familyname`, `s`.`givenname`, `s`.`parentname`, `s`.`gender`, `s`.`email`, ";
    $SQL ="SELECT `s`.`user_id`, `s`.`familyname`, `s`.`givenname`, `s`.`parentname`,  `s`.`email` ";
    // $SQL.=", `t`.`title`, `t`.`report_type` "; 
    $SQL.="FROM `".YEAR."_speaker` `s` JOIN `".YEAR."_thesises` `t` ON `s`.`user_id`=`t`.`user_id` ";
    $SQL.=WHERE_recp_str($recp_group);  
    $res=$dbh->query($SQL);
    $data=$res->fetchAll();
    echo json_encode($data);
}


###### FUNCTIONS ######

function WHERE_recp_str($selected){

    $not_accepted="'invited','review','rejected','selfrejected','plagiat','absence','blacklist'";
    
    switch($selected) {
    
        case 'all':
            $where="WHERE `report_type` NOT IN ('selfrejected','plagiat','absence','blacklist','invited')";
            break;

        case 'accepted':
            $where="WHERE `report_type` NOT IN (".$not_accepted.")";
            break;

        case 'poster':
            $where="WHERE `report_type`='rejected'";
            break;

        case 'oral':
            $where="WHERE `report_type`='oral'";
            break;

        case 'invited':
            $where="WHERE `report_type`='invited'";
            break;

        case 'rejected':
            $where="WHERE `report_type`='rejected'";
            break;

        case 'plagiat':
            $where="WHERE `report_type`='plagiat'";
            break;
            
        case 'no_ioffe':
            $where="WHERE `report_type` NOT IN (".$not_accepted.") AND passport!='!Есть пропуск ФТИ'";
            break;

        case 'ioffe':
            $where="WHERE `report_type` NOT IN (".$not_accepted.") AND passport='!Есть пропуск ФТИ'";
            break;

        default:
            $where="WHERE false";
            break;
    }

    return $where;
}

?>