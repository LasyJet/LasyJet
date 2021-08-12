<?
// header("Content-Length: 0");
// header("Connection: close");
// flush();
header('Content-Type: text/html; charset=utf-8');
include("config.php");
include_once($lang_file);
include_once("classes/phpmailer/sendmail.php");


## functions set are below

if(isset($_POST)): 

    //mysql connect
    $dbh = new PDO('mysql:host='.$dbhost.';dbname='.$physica_db, $mysqluser, $mysqlpass);
    $dbh->query("SET NAMES utf8");

    // CLEAN AND CHECK DATA
    $data=array_map('strip_tags', $_POST);
    $data=array_map('trim', $_POST);

    // ioffe_pass
    if(isset($data['ioffe_pass'])){
        $data['passport']="!Есть пропуск ФТИ";
    }
    else  $data['passport']="";

    // foreign passport
    if(isset($data['foreign'])){
        $data['passport'].="+У меня иностранный паспорт";
    }

    $data['user_id']=user_id();

    $emailExist= check_email_exist($data['email']);

if(!$emailExist):

    $sql ="INSERT INTO `".YEAR."_speaker`
           (`user_id`, `familyname`, `givenname`, `parentname`, `birthday`,`gender`, `email` ,`phone`,`country`, `city`, `company`, `position`, `degree`, `password`, `passport`, `user_agent`, `ip`)
    values (:user_id, :familyname, :givenname, :parentname,  :birthday, :gender, :email, :phone, :country, :city, :company, :position, :degree, :password, :passport, :user_agent, :ip)";

    // $user_agent=$_SERVER["HTTP_USER_AGENT"];
    $ua=getBrowser();
    $user_agent=$ua['name']." ".$ua['version']." on ".$ua['platform'];
    $ip = $_SERVER['REMOTE_ADDR'];

//Вставка данных в БД с проверкой единственности записи с указанной электропочтой
// ############ нельзя - если есть два одинаковых значения - ошибка


    $stmt =$dbh->prepare($sql);
    $stmt->bindValue(":user_id", $data['user_id']);
    $stmt->bindValue(":familyname", $data['familyname']);
    $stmt->bindValue(":givenname", $data['givenname']);
    $stmt->bindValue(":parentname", $data['parentname']);
    $stmt->bindValue(":birthday", $data['birthday']);
    $stmt->bindValue(":gender", $data['gender']);
    $stmt->bindValue(":email", $data['email']);
    $stmt->bindValue(":phone", $data['phone']);
    $stmt->bindValue(":country", $data['country']);
    $stmt->bindValue(":city", $data['city']);
    $stmt->bindValue(":company", $data['company']);
    $stmt->bindValue(":position", $data['position']);
    $stmt->bindValue(":degree", $data['degree']);
    $stmt->bindValue(":password", $data['password']);
    $stmt->bindValue(":passport", $data['passport']);
    $stmt->bindValue(":user_agent", $user_agent);
    $stmt->bindValue(":ip", $ip);

    $stmt->execute();

    if( $stmt ) {
        $data['success']="yess!";
        $data['cnt']=$stmt->rowCount();

    }

    if($data['cnt']==1){
        congratulation_mail($data['email']);
    }
    else{
        unset($data);
        $data['cnt']=0;
    }
else:
        $data['cnt']=0;

endif;

    print(json_encode($data['cnt']));

    unset($data);
else:
    echo("No data here!");
endif;



############### FUNCTIONS ###############

function myHash(){
    return substr(md5(date("Ymdhis").rand(1,100)), 1, 9);
}

function user_id(){
    global $dbh, $YEAR;
    $hash=myHash();
    $sql="SELECT count(*) FROM `{$YEAR}_speaker` WHERE `user_id`=:hash";
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':hash',$hash);
    $stmt->execute();
    $cnt=(int)$stmt->fetchColumn();
    if($cnt>0)
        $hash=myHash();
    else
        return $hash;
}


function congratulation_mail($to_email){

    $subject="PhysicA.SPb/".YEAR." registration";
    $message=get_congratulation_msg();
    $from_site="PhysicA.SPb/".YEAR;
    $from_email=CONF_EMAIL;
    return smtp_mail($to_email, $subject, $message);
    // return php_mail($to_email, $subject, $message);

}

function php_mail($to, $subject, $message){
    $headers = "From: ".CONF_EMAIL."\r\n";
    $headers .= "Reply-To: ".CONF_EMAIL."\r\n";
    $headers .= "Return-Path: ".CONF_EMAIL."\r\n";
    // $headers .= "CC: sombodyelse@example.com\r\n";  
    $headers .= "BCC: ".CONF_TECHEMAIL."\r\n";
    $headers .= "Organization: Conference Physica.Spb\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/plain; charset=UTF-8\r\n";
    // $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "X-Priority: 3\r\n";
    $headers .= "X-Mailer: PHP". phpversion() ."\r\n" ;
    $headers .= "List-Unsubscribe: <".SITE."/unsubscribe/>\r\n" ;
    $message_id = time() .'-' . md5(CONF_EMAIL . $to);
    $headers .= "Message-id: <".$message_id."@physica.spb.ru>\r\n" ;

    if ( mail($to,$subject,$message,$headers) ) {
        return true;
    } 
    else {
        // write_log("Ошибка отправки письма '".$subject."' по адресу ".$to);
    }

}

function get_congratulation_msg(){
    global $data, $LANG;
    $link=SITE."?user_id=".$data['user_id'];
    // $link="<a href='".$link."'>".$link."</a>";
    $msg=$LANG['reg_msg']; //Шаблон содержит переменные виде %variable%
    $search =array("%familyname%", "%givenname%","%parentname%","%link%", "%YEAR%");
	$replace=array($data['familyname'], $data['givenname'], $data['parentname'], $link, YEAR);

    $message=str_ireplace($search,$replace,$msg);
    return $message;
}

function write_log($log){
	return file_put_contents("log/maillog.txt", $log, FILE_APPEND);
}


function check_email_exist($email){
    global $dbh;
    $sql="SELECT * FROM `".YEAR."_speaker` WHERE `email`=:email";
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':email',$email);
    $stmt->execute();
    $count = $stmt->rowCount();
    if ($count>0) return true;
    else return false;
}


function getBrowser() //from PHP.net
{
    $u_agent = $_SERVER['HTTP_USER_AGENT'];
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version= "";

    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';
    }
    elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'windows';
    }

    // Next get the name of the useragent yes seperately and for good reason
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
    {
        $bname = 'Internet Explorer';
        $ub = "MSIE";
    }
    elseif(preg_match('/Firefox/i',$u_agent))
    {
        $bname = 'Mozilla Firefox';
        $ub = "Firefox";
    }
    elseif(preg_match('/Chrome/i',$u_agent))
    {
        $bname = 'Google Chrome';
        $ub = "Chrome";
    }
    elseif(preg_match('/Safari/i',$u_agent))
    {
        $bname = 'Apple Safari';
        $ub = "Safari";
    }
    elseif(preg_match('/Opera/i',$u_agent))
    {
        $bname = 'Opera';
        $ub = "Opera";
    }
    elseif(preg_match('/Netscape/i',$u_agent))
    {
        $bname = 'Netscape';
        $ub = "Netscape";
    }

    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    }

    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
            $version= $matches['version'][0];
        }
        else {
            $version= $matches['version'][1];
        }
    }
    else {
        $version= $matches['version'][0];
    }

    // check if we have a number
    if ($version==null || $version=="") {$version="?";}

    return array(
        'userAgent' => $u_agent,
        'name'      => $bname,
        'version'   => $version,
        'platform'  => $platform,
        'pattern'    => $pattern
    );
}

?>