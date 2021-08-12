<?
//thesis_update.php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);


header("Content-Type: text/html; charset=utf-8");
include_once("../config.php");
include_once("../functions.php");
include_once("../".$lang_file);

$dbh = new PDO('mysql:host='.$dbhost.';dbname='.$physica_db, $mysqluser, $mysqlpass);
$dbh->query("SET NAMES utf8");
//  print($_POST['id']." ".$_POST['value']);

if(isset($_POST)){

    $content=$_POST['value'];
    unset($_POST['value']);
    $field_name=$_POST[key($_POST)];
    
    list($tmp,$thesis_id)=explode("~",key($_POST));
    
    if ($field_name=='section'){
        $session_id=$content;
        $sess_title=get_session_title($session_id, $dbh);
        
        // print(json_encode($data));
    }


    if($field_name=='affiliations') 
        $content=ltrim(strip_tags($content,"<br/><br><sup>")," ,\t");
    else 
        $content=strip_tags($content,"<br/><br><p><img><b><strong><i><em><ul><li><ol><sub><sup>");

	$content=mb_ereg_replace('data-cke-saved-src="[^"]*"', '', $content);
	$content=mb_ereg_replace('src="file://[^"]*"', '', $content);

	$content=addslashes($content);
	
    $sql ="UPDATE `".YEAR."_thesises` SET `$field_name`='$content' WHERE `thesis_id`='$thesis_id'";
    // echo $sql;
    $stm=$dbh->prepare($sql);
    $stm->execute();
    
    if ($field_name=='section')
        echo $sess_title[$_SESSION['lang']];
    else
        echo $content;
    // $data=$stm->fetch(PDO::FETCH_ASSOC);
}


?>

