<?
class TU extends Config { //thesis and users

    private static  $dbh;
    // protected $sth;
    // protected static $dbhost="localhost";
    // protected static $physica_db="papaioffru_phys";
    // protected static $mysqluser="papaioffru_phys";
    // protected static $mysqlpass="f1z1ka";

    private function __construct(){

    }
    
    public static function db(){
        self::$dbh = new PDO('mysql:host='.self::$dbhost.';dbname='.self::$physica_db
        , self::$mysqluser
        , self::$mysqlpass
        , array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'")
        );
        return self::$dbh; 
    }

     public static function getAllUsers($arFields="*"){
        if(is_array($arFields)) 
            $fields=implode(", ", $arFields);
        else 
            $fields=$arFields;
        $sth=self::db()->prepare("SELECT $fields FROM `".YEAR."_speaker` WHERE 1 ORDER BY `id` DESC");
        $sth->execute();
        $data=$sth->fetchAll(PDO::FETCH_ASSOC);
        // echo $sth->rowCount();
        return $data;
    }

    public static function getAllThesis($arFields="*"){
        if(is_array($arFields)) 
            // $fields=implode(", ", $arFields);    
            $fields="tt.".implode(", tt.", $arFields);
        else 
            $fields=$arFields;

        // $sth=self::db()->prepare("SELECT $fields FROM `".YEAR."_thesises` WHERE 1");
        $sth=self::db()->prepare("SELECT $fields FROM `".YEAR."_thesises` tt JOIN `".YEAR."_speaker` ts on tt.user_id=ts.user_id");
        // 
        $sth->execute();
        $data=$sth->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }

    public static function getCompleteTheses($accepted=false){
        
        $sql="SELECT `tt`.`id`, `tt`.`thesis_id`, `tt`.`section` `sect_id`, `ss`.`ru` `sect_title`, 
        `ts`.`user_id`, `tt`.`title`, `tt`.`speaker`, `tt`.`coauthors`, `tt`.`affiliations`, 
        `ts`.`email`, `tt`.`text`, `tt`.`literature`,
        `tt`.`rfbr_title`, `tt`.`rfbr`, 
        `tt`.`date`, 
        {$_SESSION['expert_id']} as `expert_id`,
        (SELECT `grade` FROM `".YEAR."_grades` `gr` 
        WHERE `expert_id`='{$_SESSION['expert_id']}' AND `gr`.`thesis_id`=`tt`.`thesis_id`) as `grade`
        FROM `".YEAR."_thesises` `tt` JOIN `".YEAR."_speaker` `ts` ON `tt`.`user_id`=`ts`.`user_id` 
        JOIN `sections` `ss` ON `ss`.`id`=`tt`.`section` 
        WHERE `ts`.`deleted`='-' "
        .(($accepted)?"AND `tt`.`report_type` IN ('poster','oral','invited')":"") 
        .((hideEmpty)?"AND length(`tt`.`text`)>200 ":"")
        ." ORDER BY `tt`.`section`";

        $sth=self::db()->prepare($sql);
        // 
        $sth->execute();
        $data=$sth->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }

    public static function countSection(){
        $sql ="SELECT `t`.`section` id, `s`.`ru` `title`, count(`t`.`section`) `cnt` ";
        $sql.="FROM `".YEAR."_thesises` t, sections s ";
        $sql.="WHERE s.id=t.section AND t.deleted='-' ";
        $sql.=(!hideEmpty)?"":" AND length(`t`.`text`)>200 ";
        $sql.="GROUP by `t`.`section` ORDER BY `t`.`section` ASC";
        // echo $sql;
        $sth=self::db()->prepare($sql);
        $sth->execute();
        $data=$sth->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }   
    
    public static function getSection(){
        $sth=self::db()->prepare("SELECT * FROM `sections`");
        $sth->execute();
        $data=$sth->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
        
    public static function getReports(){ 
        $sql="DESCRIBE `".YEAR."_thesises` `report_type`";
        //row: Field Type Null Key Default Extra
        $sth=self::db()->prepare($sql);
        $sth->execute();
        $res=$sth->fetchAll(PDO::FETCH_ASSOC);
        // var_dump($res);
        $data=explode(",",substr($res[0]['Type'],4,-1));
        function strip($s){
                return substr($s,1,-1);
            }
        $data=array_map(strip,$data);
        $ctrl_key=array_search("-control-",$data); //удаление из выдачи записей типа -control- предназначенных для служебных "тезисов"
        unset($data[$ctrl_key]);
        unset($ctrl_key);

        foreach($data as $d){ //установка ключа равного значению для правильной работы edit-in-place со списком
            $result["{$d}"]=$d;
        }
        return json_encode($result);
    }
    
    public static function  AuthForm(){
        $str="<div class='col-md-4 offset-md-4'>\n";
        $str.="<form method='post' action='".$_SERVER['PHP_SELF']."'>\n";
        $str.="<div class='form-group'>\n";
        $str.="<label for='usr'>Login:</label>\n";
        $str.="<input type='text' class='form-control' id='usr' name='expert'>\n";
        $str.="</div>\n";
        $str.="<div class='form-group'>\n";
        $str.="<label for='pwd'>Password:</label>\n";
        $str.="<input type='password' class='form-control' id='pwd' name='admpassword' autocomplete='new-password'>\n";
        $str.="</div>\n";
        $str.="<button type='submit' class='btn btn-primary'>Submit</button>\n";
        $str.=($_SESSION['loginError']=="")?"":"<div class='alert alert-warning text-center'>".$_SESSION['loginError']."</div>\n";
        $str.="</div>\n";
        
        return $str;
    }
            
    public static function  Authorise(){
        if(!empty($_SESSION['admin'])) {//уже авторизован
            return true;
        }
        elseif(!empty($_POST['expert'])){
            // echo "<pre>";
            // var_dump($_POST);
            $sql="SELECT `id`, `realname`, `password`,`type` FROM `".YEAR."_experts` WHERE `login`=:login LIMIT 1";
            // echo $_POST['expert'];
            $sth=self::db()->prepare($sql);
            $sth->execute(array(":login"=>$_POST['expert']));
            $data=$sth->fetchAll(PDO::FETCH_ASSOC)[0];
            // var_dump($data);
            if($data['password']==$_POST['admpassword']){
                // echo "Да";
                $_SESSION['admin']=$data['type'];
                $_SESSION['login']=$_POST['login'];
                $_SESSION['expert_id']=$data['id'];
                $_SESSION['adminName']=$data['realname'];
                $_SESSION['loginError']="";
                return true;
            }
            else {
                $_SESSION['loginError']="Что-то не так!";
                $_SESSION['adminName']='';
                $_SESSION['admin']='';
            }
        }  
        else {
            $_SESSION['loginError']="";
            $_SESSION['adminName']='';
            $_SESSION['admin']='';
            return false;
        }
            
            // echo "</pre>";
        
    }
    
    
    public static function Data2Table($array){
        $tbl="<table>";
        $tbl.="\r\n<thead>\r<tr>";
        foreach(array_keys($array[0]) as $k){
            $tbl.="<th id='{$k}'>$k</th>";
        }
        $tbl.="</tr>\r\n</thead>";
        $tbl.="<tbody>\r\n";
        foreach($array as $row){
            $tbl.="<tr>\r\n";
            foreach($row as $cell){
                $tbl.="<td>$cell</td>";
            }
            $tbl.="\r\n</tr>";
        }
        $tbl.="\r\n</tbody>\r\n</table>";
        return $tbl;
    }
    
    public static function setActiveLink($getparam){
    
        if(!empty($_GET) && in_array($getparam, array_keys($_GET))){
            $actlink=' active';
        }
        elseif(empty($_GET) && $getparam=='list') {
            $actlink='active';
        }
        else $actlink="";
        
        return $actlink;
    }
    
    public static function setHeader(){
        
        $arHeader=array(
            "none"=>"Экспертиза",
            "list"=>"Список участников",
            "theses"=>"Список тезисов",
            "stat"=>"Статистика",
            "grades"=>"Оценки");
    
        if(!empty($_GET)){
            return $arHeader[array_keys($_GET)[0]];
        }
        elseif(!empty($_SESSION['admin'])) {
            return $arHeader["list"];
        }
        else 
            return $arHeader["none"];
    
    }
    

    public static function  bkAuthorise(){
        if(!empty($_SESSION['b_keeper'])) {//уже авторизован
            return true;
        }
        
        if(!empty($_POST['expert'])){
            // echo "<pre>";
            // var_dump($_POST);
            $sql="SELECT `realname`, `password`,`type` FROM `".YEAR."_bookkeepers` WHERE `login`=:login LIMIT 1";
            // echo $_POST['expert'];
            $sth=self::db()->prepare($sql);
            $sth->execute(array(":login"=>$_POST['expert']));
            $data=$sth->fetchAll(PDO::FETCH_ASSOC)[0];

            if($data['password']==$_POST['admpassword']){
                // echo "Да";
                $_SESSION['b_keeper']=$data['type'];
                $_SESSION['login']=$_POST['login'];
                $_SESSION['adminName']=$data['realname'];
                $_SESSION['loginError']="";
                return true;
            }
            else {
                $_SESSION['loginError']="Что-то не так!";
                $_SESSION['adminName']='';
                $_SESSION['admin']='';
            }
        }  
        else {
            $_SESSION['loginError']="";
            $_SESSION['adminName']='';
            $_SESSION['admin']='';
            return false;
        }
            
            // echo "</pre>";
        
    }
    

    public static function bkSpeakers(){
        // $sql ="SELECT `ts`.`id`, `ts`.`user_id`, concat_ws(' ',`ts`.`familyname`,`ts`.`givenname`,`ts`.`parentname`) as Speaker, ";
        $sql ="SELECT `ts`.`id`,  concat_ws(' ',`ts`.`familyname`,`ts`.`givenname`,`ts`.`parentname`) as 'name', ";
        $sql.="`ts`.`substitute`, `ts`.`city`, `ts`.`country`, ";
        $sql.="`ts`.`email`, `ts`.`company`, `tt`.`title`, `tt`.`thesis_id`, ";
        $sql.="`tt`.`rfbr` as `rfbr`,`tt`.`rfbr_title` as `rfbr_title`, ";
        $sql.=" `ts`.`fee`, `ts`.`fee_date` ";
        $sql.="FROM `".YEAR."_speaker` ts,  `".YEAR."_thesises` tt WHERE `tt`.`user_id`=`ts`.`user_id` ";
        $sql.="AND `tt`.`report_type` NOT IN ('plagiat','rejected','selfrejected','blacklist','-control-','absence') ";
        // echo $sql;
        $sth=self::db()->prepare($sql);
        // 
        $sth->execute();
        $data=$sth->fetchAll(PDO::FETCH_ASSOC);
        return $data;

    }

    public static function bkData2Table($array){
            $tbl="<table>";
            $tbl.="<col class='col1'>";
            $tbl.="<col class='col2'>";
            $tbl.="<col class='col3'>";
            $tbl.="<col class='col4'>";
            $tbl.="<col class='col5'>";
            $tbl.="<col class='col6'>";
            $tbl.="<col class='col7'>";
            $tbl.="<col class='col8'>";
            $tbl.="<col class='col9'>";
            
            $tbl.="\r\n<thead>\r<tr>    ";
            $tbl.="<th id='id'>id</th>";
            $tbl.="<th id='name'>ФИО, ";
            $tbl.="email</th>";
            $tbl.="<th id='company'>Организация</th>";
            $tbl.="<th id='city'>Город</th>";
            $tbl.="<th id='country'>Страна</th>";
            $tbl.="<th id='thesis_id'>ID Тезисов</th>";
            $tbl.="<th id='thesis_id'>РФФИ</th>";
            $tbl.="<th id='fee'>Сумма</th>";
            $tbl.="<th id='fee_date'>Дата оплаты</th>";
            $tbl.="</tr>\r\n</thead>";
        
        $tbl.="<tbody>\r\n";
        foreach($array as $row){
            $row['frbr']=strip_tags($row['frbr']);
            $row['rfbr_title']=strip_tags($row['rfbr_title']);
            
            $tbl.="<tr>\r\n";
            $tbl.="<td>{$row['id']}</td>";
            $tbl.="<td>{$row['name']}<br/>";
            $tbl.="<a href='mailto:{$row['email']}'>{$row['email']}</a>";
            $substitute=str_replace("#","<br>", $row['substitute']);

            $tbl.=(($row['substitute']!="")?"<br><span class='text-danger'>$substitute</class>":"")."</td>";

            $tbl.="<td>{$row['company']}</td>";
            $tbl.="<td>{$row['city']}</td>";
            $tbl.="<td>{$row['country']}</td>";
            $tbl.="<td title='".$row['title']."'>{$row['thesis_id']}</td>";
            $tbl.="<td title='{$row['rfbr_title']}'".((strlen($row['rfbr'])>5)?"class='text-success'":"").">".$row['rfbr']."</td>";
            $tbl.="<td><div class='btn btn-info btn-sm'>{$row['fee']}</div></td>";
            $tbl.="<td>{$row['fee_date']}</td>";
            $tbl.="\r\n</tr>";
        }
        $tbl.="\r\n</tbody>\r\n</table>";
        
        return $tbl;
    }


    public static  function get_session_title($section_id){
        $sth=self::db()->prepare("SELECT * FROM `sections` WHERE `id`=:id");
        $sth->execute();
        $st->execute(array(':id'=>$section_id));
        return $data=$st->fetch(PDO::FETCH_ASSOC);
    }


####################### СТАТИСТИКА #######################

    public static function statCity($hideEmpty=hideEmpty){
        //$report_type=" `t`.`report_type` in ('poster','oral', 'invited') AND";
        $report_type="";
        $sql="SELECT `s`.`City`, count(`s`.`city`) `Count` 
        FROM `".YEAR."_speaker` `s`  LEFT JOIN `".YEAR."_thesises` `t` 
        ON `s`.`user_id`=`t`.`user_id`  
        WHERE $report_type s.deleted='-' "
        .(($hideEmpty)?"AND length(t.text)>200 ":" ")
        ."GROUP BY `city` 
        ORDER BY `count` DESC";
        
        $sth=self::db()->prepare($sql);
        $sth->execute();
        $data=$sth->fetchALL(PDO::FETCH_ASSOC);
        return $data;
    }    
    
    public static function statCountry($hideEmpty=hideEmpty){
        //$report_type=" `t`.`report_type` in ('poster','oral', 'invited') AND";
        $report_type="";
        $sql="SELECT `s`.`Country`, count(`s`.`Country`) `Count` 
        FROM `".YEAR."_speaker` `s`  LEFT JOIN `".YEAR."_thesises` `t` 
        ON `s`.`user_id`=`t`.`user_id`  
        WHERE ".$report_type." s.deleted='-' "
        .(($hideEmpty)?"AND length(t.text)>200 ":" ")
        ."GROUP BY `Country` 
        ORDER BY `count` DESC";

        $sth=self::db()->prepare($sql);
        $sth->execute();
        $data=$sth->fetchALL(PDO::FETCH_ASSOC);
        return $data;
    }

    public static function statCompany($hideEmpty=hideEmpty){
        //$report_type=" `t`.`report_type` in ('poster','oral', 'invited') AND";
        $report_type="";
        $sql="SELECT s.company as  `Affiliation`, count(s.company) as `Count`  
        FROM `".YEAR."_speaker` `s` LEFT JOIN `".YEAR."_thesises` `t` 
        ON t.user_id=s.user_id 
        WHERE ".$report_type." s.deleted='-' "
        .(($hideEmpty)?"AND length(t.text)>200 ":"")
        ."GROUP by s.company 
        ORDER BY `count` DESC";
        
        $sth=self::db()->prepare($sql);
        $sth->execute();
        $data=$sth->fetchALL(PDO::FETCH_ASSOC);
        return $data;
    }

    public static function statSection($hideEmpty=hideEmpty){
        //$report_type=" `t`.`report_type` in ('poster','oral', 'invited') AND";
        $report_type="";
        $sql="SELECT `s`.`Ru` as `Section`, count(`t`.`Section`) as `Count` 
        FROM `".YEAR."_thesises` t JOIN `sections` s 
        ON t.section=s.id 
        WHERE $report_type "
        .(($hideEmpty)?" length(t.text)>200 ":" ")
        ."GROUP BY `Section` "
        ."ORDER BY `count` DESC";
        $sth=self::db()->prepare($sql);
        $sth->execute();
        $data=$sth->fetchALL(PDO::FETCH_ASSOC);
        return $data;
    }

    public static function totalRegistered(){
        $sql="SELECT count(*) AS `count` from `".YEAR."_speaker` where `deleted`='-'";
        $sth=self::db()->prepare($sql);
        $sth->execute();
        $data=$sth->fetchALL(PDO::FETCH_ASSOC);
        return $data;
    }

    public static function todayRegistered(){
        $sql="SELECT count(*) AS `count` from `".YEAR."_speaker` where (`reg_datetime` > curdate())";
        $sth=self::db()->prepare($sql);
        $sth->execute();
        $data=$sth->fetchALL(PDO::FETCH_ASSOC);
        return $data;
    }
    
    public static function theses_grades(){
        //+datetime?
        $sql="SELECT `g`.`thesis_id`,`t`.`report_type`,`t`.`rfbr`,`t`.`rfbr_title`,`t`.`section`, `t`.`title`, `t`.`speaker`,
         `t`.`coauthors`,  `t`.`affiliations`,  
        GROUP_CONCAT(concat_ws(':',CONCAT('\"',`e`.`id`,'\"'), `g`.`grade`))  AS `grades`  
        FROM `".YEAR."_experts` `e` 
        RIGHT JOIN `".YEAR."_grades` `g` on e.id=g.expert_id 
        LEFT JOIN `".YEAR."_thesises` `t` on t.thesis_id=g.thesis_id 
        WHERE length(`t`.`text`)>200 
        GROUP BY `t`.`thesis_id`  
        ORDER BY `grades` ASC";
        // echo $sql;
        
        $sth=self::db()->prepare($sql);
        $sth->execute();
        $data=$sth->fetchALL(PDO::FETCH_ASSOC);
        return $data;
    }

    public static function realExperts(){
        $sql="SELECT `e`.`id`, `e`.realname 
            FROM `".YEAR."_experts` `e` JOIN `".YEAR."_grades` `g` 
            ON `e`.id=`g`.`expert_id` GROUP BY `e`.`id`";
        $sth=self::db()->prepare($sql);
        $sth->execute();
        $data=$sth->fetchALL(PDO::FETCH_ASSOC);
        return $data;
    }

    
    public static function ExpertsRating(){
        //exclude 1  - Losev 20 - Serin
        $sql="SELECT `e`.`id`, `e`.`realname` AS 'Name',  count(`g`.`grade`) `Count`  
        FROM `".YEAR."_experts` `e` LEFT JOIN `".YEAR."_grades` `g` 
        on `e`.`id`=`g`.`expert_id` 
        WHERE `e`.`id` NOT IN(1,20) 
        group by `e`.`id` 
        ORDER BY `count` DESC";
        $sth=self::db()->prepare($sql);
        $sth->execute();
        $data=$sth->fetchALL(PDO::FETCH_ASSOC);
        return $data;
    }



    public static function statGrades(){
        $array=self::theses_grades();
        $experts=self::realExperts();
        $cntExperts=sizeof($experts);
        
        $tbl="<table>";
        $tbl.="\r\n<thead>\r<tr>";
        $tbl.="<th id='thesis_id'>thesis_id</th>";
        $tbl.="<th id='report'>report_type</th>";
        $tbl.="<th id='rfbr'>РФФИ</th>";
        $tbl.="<th id='section'>Section</th>";
        $tbl.="<th id='title'>Speaker, Title</th>";
        $tbl.="<th id='misc'>Coauthors, Affiliations</th>";
        foreach($experts AS $exp){
             $tbl.="<th title='".$exp['realname']."'>".$exp['id']."</th>";
        }
        $tbl.="<th title='Количество оценок'><b>N</b></th>";
        $tbl.="<th title='Среднее'><b class='overline'>x</b></th>";
        $tbl.="<th><b>&sigma;<sup>2</sup></b></th>";
        $tbl.="</tr>\r\n</thead>";

        foreach($array as $s){
            $jsonGrades="{".$s['grades']."}";
            $thisGr=get_object_vars(json_decode($jsonGrades));
            $Num=count($thisGr);
            $mean=array_sum($thisGr)/count($thisGr);
            $mean=number_format($mean, 2, '.', '');
            $sum=0; //вычисление  дисперсии
            foreach($thisGr as $n){
                $sum=$sum+pow(2,$n-$mean);
            }
            $sigma=$sum/(count($thisGr))-1;
            $sigma=number_format($sigma, 2, '.', '');

            if($sigma>=5) $class="sigma5";
            elseif($sigma>=4 && $sigma<5) $class="sigma3";
            elseif($sigma>3 && $sigma<=4)  $class="sigma1";

            $class="class='$class'";

            $tbl.="<tr>";
            $tbl.="<td>{$s['thesis_id']}</td>";
            $tbl.="<td>{$s['report_type']}</td>";
            $tbl.="<td title='".$s['rfbr_title']."'>{$s['rfbr']}</td>";
            $tbl.="<td>{$s['section']}</td>";
            $tbl.="<td class='text-left'><b class='font-weight-bold'>{$s['speaker']}</b><br>{$s['title']}</td>";
            $s['coauthors']=nl2br($s['coauthors']);
            $s['coauthors']=strip_tags($s['coauthors'],"<sup>");
            $tbl.="<td>{$s['coauthors']}<br><i>{$s['affiliations']}</i></td>";

            foreach($experts AS $exp){
                $id=$exp['id'];
                $thisGrade=(isset($thisGr[$id]))?$thisGr[$id]:"-";
                $tbl.="<td>{$thisGrade}</td>";
            }

            $tbl.="<td>".$Num."</td>";
            $tbl.="<td>".$mean."</td>";
            $tbl.="<td $class>".$sigma."</td>";
            $tbl.="</tr>";
        }

        $tbl.="\r\n</tr>\r</table>";
        return $tbl;
    }

    
    public static function gradeCount(){
    //количество тезисов-количество оценок
        $sql="SELECT `grade_cnt` AS `Num of grades`, count(`grade_cnt`) as `Num of thesises` 
        FROM (SELECT `thesis_id`, count(`thesis_id`) as `grade_cnt`
            FROM `".YEAR."_grades`
            WHERE `thesis_id` NOT IN (SELECT `thesis_id` FROM `".YEAR."_thesises` WHERE length(`text`)<200)
            GROUP BY `thesis_id`) AS `tmp`
        GROUP BY `grade_cnt`  ORDER BY `tmp`.`grade_cnt` ASC";
        $sth=self::db()->prepare($sql);
        $sth->execute();
        $data=$sth->fetchALL(PDO::FETCH_ASSOC);
        return $data;
    }
        
    public static function fewGradeCount($count=4){
    //количество тезисов-количество оценок

        $sql="SELECT thesis_id thes_id, grade_cnt  
        FROM (SELECT thesis_id, count(thesis_id) as `grade_cnt` FROM `".YEAR."_grades` GROUP BY `thesis_id`) AS tmp 
        WHERE `thesis_id` NOT IN (SELECT `thesis_id` FROM `".YEAR."_thesises` WHERE length(`text`)<200) 
        AND grade_cnt <".$count."  
        ORDER BY `tmp`.`grade_cnt` DESC";
        $sth=self::db()->prepare($sql);
        $sth->execute();
        $data=$sth->fetchALL(PDO::FETCH_ASSOC);
        return $data;
    }
        
    public static function howMuchMoney(){   //общая сумма оплат    
    
        $sql="SELECT count(*) as 'count', sum(`fee`) as 'money' FROM `".YEAR."_speaker` where `fee`!='---'";
        $sth=self::db()->prepare($sql);
        $sth->execute();
        $data=$sth->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    public static function reportTypeCount(){   // статус докладов
            
        $sql="SELECT `report_type` `report`, count(`report_type`) count FROM `".YEAR."_thesises` WHERE 1 GROUP BY `report_type`";
        $sth=self::db()->prepare($sql);
        $sth->execute();
        $data=$sth->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }

    public static function statRFBR(){
        $sql="SELECT count(*) FROM `".YEAR."_thesises` 
        WHERE ((`".YEAR."_thesises`.`rfbr` <> '') 
        AND (`".YEAR."_thesises`.`report_type` IN ('poster','invited','oral')))";
        $sth=self::db()->prepare($sql);
        $sth->execute();
        $data=$sth->fetchColumn();
        return $data;
    }

    
    public static function getPosters(){
       //`t`.`id`, 
       $query="SELECT `t`.`section` `num_sect`, `s`.`ru` as `section`, `spkr`.`fee`, `t`.`poster_session`, concat_ws('-', `t`.`poster_session`, `t`.`poster_num`) as `poster_num`, 
       `t`.`poster_file`, concat_ws(' ',`spkr`.`familyname`, `spkr`.`givenname`, `spkr`.`parentname`) `name`, 
                `spkr`.`company`, `t`.`title` 
                FROM `".YEAR."_thesises` `t` 
                    JOIN `sections` `s` on `s`.`id`=`t`.`section` 
                    JOIN `".YEAR."_speaker` `spkr` on `t`.`user_id`=`spkr`.`user_id` 
                WHERE `t`.`poster_session`>0 ORDER by `t`.`poster_session`, `t`.`poster_num`";
        $sth=self::db()->prepare($query);
        $sth->execute();
        $data=$sth->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }

    public static function getPassport(){
        $query="SELECT `s`.`id`, s.`user_id` AS `user_id`, concat_ws(' ',`familyname`,`givenname`,`parentname`) 
        AS `Name`, `s`.`email`, `s`.`substitute` AS `substitute`, `s`.`passport` AS `passport`, `s`.`fee`,
        `s`.`passport_update` 
        FROM `".YEAR."_speaker` `s` JOIN `".YEAR."_thesises` `t` ON `s`.`user_id`=`t`.`user_id` 
        WHERE `passport` not like '%онлайн%' AND `passport` not like '%!есть%' AND `passport` <> '' AND `passport` not like '%иностранный%' 
        AND `t`.`report_type` in ('poster', 'oral', 'invited')
        ORDER BY `id` ASC";

        $sth=self::db()->prepare($query);
        $sth->execute();
        $data['data']=$sth->fetchAll(PDO::FETCH_ASSOC);
        $data['count']=$sth->rowCount();
        return $data;
    }

    public static function getPWD(){
        $query="SELECT `id`, `user_id`, `email`, `password`, concat_ws(' ', familyname, givenname, parentname) AS `name` FROM `".YEAR."_speaker`";
        $sth=self::db()->prepare($query);
        $sth->execute();
        $data=$sth->fetchAll(PDO::FETCH_ASSOC);
        // `id`, `user_id`, `email`, `password`, `name`
        $tbl="<table><thead><tr><th>id</th><th>user_id</th><th>email</th><th>password</th><th>name</th></tr></thead>";
        foreach($data as $row){
            extract($row);
            $tbl.="<tr><td>$id</td><td>$user_id</td><td>$email</td><td>$password</td><td>$name</td></tr>";

        }
        $tbl.="<tbody>";
        $tbl.="</tbody>";
        $tbl.="</table>";
        return $tbl;
    } 

    public static function getOnlineParticipants(){
        // $query="SELECT count(*) FROM `".YEAR."_speaker` WHERE `passport` like '%онлайн%'";
        $query="SELECT count(*) FROM `".YEAR."_speaker` `s`  JOIN `".YEAR."_thesises` `t` ON `s`.`user_id`=`t`.`user_id` WHERE `s`.`passport` like '%онлайн%'  AND `t`.`report_type` in ('poster', 'oral', 'invited')";
        $sth=self::db()->prepare($query);
        $sth->execute();
        return $sth->fetchColumn();
    }

    public static function getRUPassport(){
        // $query="SELECT count(*) FROM `".YEAR."_speaker` WHERE `passport` not like '%!есть%' and `passport` not like '%иностранный%'";
        $query="SELECT count(*) FROM `".YEAR."_speaker` `s`  JOIN `".YEAR."_thesises` `t` ON `s`.`user_id`=`t`.`user_id` WHERE  `s`.`passport` not like '%!есть%' AND `s`.`passport` not like '%иностранный%'  AND `t`.`report_type` in ('poster', 'oral', 'invited')";
        $sth=self::db()->prepare($query);
        $sth->execute();
        return $sth->fetchColumn();
    }

    public static function getLifts($session){
        //`id`, `poster_session`, `poster_num`,
        $query="SELECT concat_ws(' ', `s`.familyname, `s`.givenname, `s`.parentname) AS `name`, `t`.`poster_session`, `t`.`poster_num`, `t`.`lift_file` 
        FROM `".YEAR."_thesises` `t` JOIN `".YEAR."_speaker` `s` ON `s`.`user_id`=`t`.`user_id` 
        WHERE `poster_session`=$session and `lift_file`!='' 
        ORDER BY `t`.`poster_num`";
        
        $sth=self::db()->prepare($query);
        $sth->execute();
        return $data=$sth->fetchAll(PDO::FETCH_ASSOC);
    }


    public static function SheduleData(){

     $sql="SELECT 
        concat_ws('','<td>',`ss`.`ru`,'</td>') as `Section`, 
        concat_ws('','<td title=\"',`tt`.`thesis_id`,'\">', '<b>',`tt`.`speaker`,'</b>',
        '#',`tt`.`affiliations`,
        '#<em>', `tt`.`title`,'</em>',
        '</td>'
        ) as `Talk`,
        -- concat_ws('','<td>', `tt`.`date`, '</td>') as `Date`, 
        concat_ws('','<td>', `tt`.`report_type`, '</td>') as `report`
        
        FROM `".YEAR."_thesises` `tt` 
        JOIN `sections` `ss` 
        ON `ss`.`id`=`tt`.`section`  
        WHERE `tt`.`report_type` IN ('accepted','poster','oral','invited','-control-')
        ORDER BY `Section`  DESC
        limit 20
        -- work where
        -- WHERE `tt`.`report_type` IN ('oral','invited')
        ";
        // echo $sql;

        $sth=self::db()->prepare($sql);
        $sth->execute();
        $data=$sth->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }

    public static function SheduleTable($array){
        $tbl="<table id='schedule'>";
        
        $tbl.="\r\n<thead>\r<tr>";
        $tbl.="<th>Order</th>";
        foreach(array_keys($array[0]) as $k){
            $tbl.="<th id='{$k}'>$k</th>\n\r";
        }
        $tbl.="</tr>\r\n</thead>\n\r";

        $tbl.="<tbody class='connectedSortable ui-sortable'>\r\n";
        foreach($array as $row){
            $tbl.="\n\r<tr style='display: table-row;'>\r\n";
            $tbl.="\n\r";
            $tbl.="\t<td></td>\n\t";

            foreach($row as $cell){
                $cell=strip_tags($cell, "<td>,<b>,<sub>,<sup>, <em>");
                $cell=str_replace("#","<br>", $cell);
                $tbl.=$cell."\n\t";
            }
            $tbl.="\r\n</tr>";
        }
        $tbl.="\r\n</tbody>\r\n</table>";
        return $tbl;
    }
    


}


?>