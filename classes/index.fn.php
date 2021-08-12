<?
//index.php functions
function account_data($PDO){
	global $LANG;
	if(!empty($_SESSION['user_id'])){
		$user_id= $_SESSION['user_id'];
		$sql="SELECT * FROM ".YEAR."_speaker WHERE user_id=:user_id";
		$stmt = $PDO->prepare($sql);
		$stmt->bindValue(":user_id", $user_id);
		$stmt->execute();
		$data=$stmt->fetch(PDO::FETCH_ASSOC);
		$cnt=$stmt->rowCount();
	}

	if(!empty($cnt)){
		$_SESSION['user_id']=$data['user_id'];
		$_SESSION['id']=$data['id'];
		$_SESSION['email']=$data['email'];
		$_SESSION['phone']=$data['phone'];
		$account_data ="<h5 class='card-title'>".$data['familyname']." ".$data['givenname']." ".$data['parentname']."</h5>";
		$account_data.="<p class='card-text'><span class='bg-light border border-primary pl-1 pr-1'>".$LANG['your_id']." ".$data['id']."</span></p>"; 
		$account_data.="<p class='card-text'>".$data['country'].", ".$data['city']."</p>"; 
		$account_data.="<p class='card-text'>".$data['company']."</p>";
		$account_data.="<p class='card-text'>".$data['position']."</p>";  
		$account_data.="<p class='card-text'>".$data['degree']."</p>";
		$account_data.="<p class='card-text'>".$data['email']."</p>";  
		$account_data.="<p class='card-text'>".$data['birthday']."</p>";
		$_SESSION['affiliation']=$data['company'];
		$_SESSION['gender']=$data['gender'];
		mb_internal_encoding("UTF-8");
		$_SESSION['family']=$data['familyname']; 
		$_SESSION['initials']=" ".mb_substr(trim($data['givenname']),0,1).". ".mb_substr(trim($data['parentname']),0,1)."."; 
		$_SESSION['name']=$data['givenname']." ".$data['parentname'];
		$_SESSION['fullname']=trim($data['familyname']." ".$data['givenname']." ".$data['parentname']);
		$_SESSION['fee']=$data['fee']; // оплата
		$_SESSION['city']=$data['city']; // из какого города
		$_SESSION['excursion_status']=$data['excursion_status']; // едет ли на экскурсию
	}
	else $account_data="unknown_user";

 return $account_data;
}


function info_block($PDO){
	global $LANG;
	// var_dump($_SESSION['user_id']);
	if(!empty($_SESSION['user_id'])){
	
		$user_id= $_SESSION['user_id'];
		$sql="SELECT `thesis_id`, `title`, `text`, `report_type`, concat_ws('-', `poster_session`, `poster_num`) `poster_num`, `poster_file` FROM `".YEAR."_thesises` WHERE user_id=:user_id";
		// echo $user_id;
		$stmt = $PDO->prepare($sql);
		$stmt->bindValue(":user_id", $user_id);
		$stmt->execute();
		$count_row=$stmt->rowCount();
	
		if($count_row>0){
			$str ="<div class='row'>";
			$str.="\r<div class='col-8'>";
			$str.="<h5 class='card-title text-info'>{$LANG['your_thesises']}</h5>";
			$str.="\r</div>";
			$str.="\r<div class='col-4'>";
			$str.="	<h5 class='card-title text-info'>{$LANG['thesise_status']}</h5>";
			$str.="\r</div>";
			$str.="</div>";
			while($thesis=$stmt->fetch(PDO::FETCH_ASSOC)){
				$this_report=$thesis['report_type'];
				$this_poster_file=$thesis['poster_file'];
				$report_key="report_".$thesis['report_type'];
				$_SESSION['thesis_id']=$thesis['thesis_id'];
				$_SESSION['poster_num']=$thesis['poster_num'];
				$report_type=$LANG[$report_key];

				$str.="<div class='row border-bottom border-info'>";
				$str.="<p class='col-8 small'>";
				$str.=(!allowThesisEdit)?"":$LANG['you_can_edit'];
				$str.="</p>";
				$str.=(strlen($thesis['text'])>255)?"":"<p class='col-4 small text-danger lead'>".$LANG['textNotComplete']."</p>";
				$str.="\t<div class='col-8'><a href='".SITE."/thesis.php?id={$thesis['thesis_id']}'>";
				$_SESSION['title']=$thesis['title'];
				$str.="&laquo;{$thesis['title']}&raquo;";
				$str.=(!allowThesisEdit)?"":"&nbsp;<i class='fa fa-edit font-weight-bold'></i>";
				$str.="</a>";
				$str.=(!allowThesisEdit)?"<p class='font-italic'><i class='fa fa-info-circle' aria-hidden='true'></i> {$LANG['when_edit_info']} </p>":"";
				$str.="</div>";
				$str.=(ShowThesisStatus)?"\t<div class='col-4'><p>$report_type</p></div>":"\t<div class='col-4'><p>on review</p></div>";
				$str.="</div>";

			
			}
		}
		else {
				$str=$LANG['info-content'];
				$this_report='';
				$this_poster_file='';
		}
	}
	return  array('info'=>$str, 'count'=>$count_row, 'report_type'=>$this_report, 'poster_file'=>$this_poster_file);
}

function messages($PDO){
	$messages="";
	if(!empty($_SESSION['user_id'])){
		// $_SESSION['user_id']
		$sql="SELECT `messages` FROM `".YEAR."_messages` WHERE `user_id`=:user_id";
		$stmt = $PDO->prepare($sql);
		$stmt->bindValue(":user_id", $_SESSION['user_id']);
		$stmt->execute();
		// $count_row=$stmt->rowCount();
		while($msgs=$stmt->fetch(PDO::FETCH_ASSOC)){
			// var_dump($msgs);
			$messages.="<div class='message border border-info m-1 p-1'>".$msgs['messages']."</div>";
		}

	}
	return $messages;
}


function getPassport($PDO){
	global $LANG;
	if(!empty($_SESSION['user_id'])){
		$sql="SELECT `passport` FROM `".YEAR."_speaker` WHERE `user_id`=:user_id";
		$stmt = $PDO->prepare($sql);
		$stmt->bindValue(":user_id", $_SESSION['user_id']);
		$stmt->execute();
		$passport=$stmt->fetchColumn();
		// var_dump($passport);
	}
	return $passport;
}

function getPoster($PDO){
	if(!empty($_SESSION['thesis_id'])){
		$sql="SELECT `poster_file` FROM `".YEAR."_thesises` WHERE `thesis_id`=:thesis_id";
		$stmt = $PDO->prepare($sql);
		$stmt->bindValue(":thesis_id", $_SESSION['thesis_id']);
		$stmt->execute();
		$poster=$stmt->fetchColumn();
	}
	return $poster;
}

function getLift($PDO){
	if(!empty($_SESSION['thesis_id'])){
		$sql="SELECT `lift_file` FROM `".YEAR."_thesises` WHERE `thesis_id`=:thesis_id";
		$stmt = $PDO->prepare($sql);
		$stmt->bindValue(":thesis_id", $_SESSION['thesis_id']);
		$stmt->execute();
		$lift=$stmt->fetchColumn();
	}
	return $lift;
}


?>