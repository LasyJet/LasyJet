<?
############# FUNCTIONS.PHP ##############
function get_session_title($session_id, $pdo){
    $st=$pdo->prepare("SELECT * FROM `sections` WHERE `id`=:id");
    $st->execute(array(':id'=>$session_id));
    return $data=$st->fetch(PDO::FETCH_ASSOC);
}



function new_thesis_id($user_id){
	global $dbh;
	$sql="SELECT id FROM `".YEAR."_speaker` WHERE `user_id`=:user_id";
    $stmt = $dbh->prepare($sql);
	$stmt->bindValue(":user_id", $user_id);
    $stmt->execute();

	$data=$stmt->fetch(PDO::FETCH_ASSOC);
	
	$prefix=substr(YEAR,-2)."000";
    return $thesis_id=((int)$prefix+$data['id'])*10; // на 10 умножается на будущее, если тезисов одного человека будет больше одного
}

function get_user_data($user_id){
	global $dbh;
	$sql="SELECT `familyname`, `givenname`, `parentname`, `company`, `email` FROM `".YEAR."_speaker` WHERE user_id=:user_id";
	$stmt=$dbh->prepare($sql);
	$stmt->bindValue(":user_id", $user_id);
	$stmt->execute();
	return $stmt->fetch(PDO::FETCH_ASSOC);
}

function insert_user_into_thesis($data){
	global $dbh;
	
 	$speaker =$data['familyname']."&nbsp;";
	$speaker.=mb_substr($data['givenname'],0,1,'UTF-8').".&nbsp;";
	$speaker.=mb_substr($data['parentname'],0,1,'UTF-8').".<sup>1</sup>";//speaker=user+affiliation number
	
	$affiliations="<sup>1</sup>".$data['company'];
	$sql="INSERT INTO `".YEAR."_thesises` (`thesis_id`, `user_id`, `speaker`, `affiliations`)
		SELECT * FROM (SELECT :thesis_id, :user_id, :speaker, :affiliations) as tmp
		WHERE NOT EXISTS (SELECT `thesis_id` from `".YEAR."_thesises` WHERE `thesis_id`=:thesis_id)";
	
	$stmt=$dbh->prepare($sql);
	$stmt->bindValue(":user_id", $data['user_id']);
	$stmt->bindValue(":thesis_id", $data['thesis_id']);
	$stmt->bindValue(":speaker", $speaker);
	$stmt->bindValue(":affiliations", $affiliations);
	$stmt->execute();
	return $stmt;
}

function get_thesis_data($thesis_id){
	global $dbh;

	// $sql="SELECT * FROM `".YEAR."_thesises` WHERE `thesis_id`=:thesis_id";
	$sql="SELECT t1.*, t2.email FROM `".YEAR."_thesises` t1 LEFT JOIN `".YEAR."_speaker` t2 ON t1.user_id=t2.user_id WHERE t1.thesis_id=:thesis_id";
	$stmt=$dbh->prepare($sql);
	$stmt->bindValue(":thesis_id", $thesis_id);
	$stmt->execute();
	$data=$stmt->fetch(PDO::FETCH_ASSOC);
	$data['cnt']=$stmt->rowCount();

	return $data;
}

function is_owner($thesis_id, $user_id){
	global $dbh;
	$sql="SELECT count(*) as `cnt` FROM `".YEAR."_thesises` WHERE `thesis_id`='$thesis_id' AND `user_id`='$user_id'";
	$stmt=$dbh->prepare($sql);
	$stmt->execute();
	$count=$stmt->fetch(PDO::FETCH_ASSOC);
	
	return (int)$count['cnt'];
}

?>