<?
session_start();
if (basename($_SERVER['PHP_SELF']) == 'config.php') {
	die('You cannot load this page directly.');
};

define("ConfTitle",'ФизикА.СПб');
define("YEAR",2021);
define("SITE","http://reg.physica.spb.ru");
define("CONF_EMAIL","mail@physica.spb.ru");
define("CONF_TECHEMAIL","tech@physica.spb.ru");
// настройка почты в файле sendmail.php в папке класса phpmailer

$SITE=SITE; // где-то может использоваться переменная. нужно просмотреть все файлы и устранить
$YEAR=YEAR; //
define("DEADLINE","2021-02-29");
define("dateConf","18-22 октября ".YEAR);
define("Excursion",true);

define("FEE_DATE1","2021-09-03");
define("FEE_DATE2","2021-09-15");

//оплата оргвзноса
if(strtotime(date("Y-m-d"))<strtotime(FEE_DATE1)){
    define("FEE",5250);
}
elseif(strtotime(date("Y-m-d"))>strtotime(FEE_DATE1) && strtotime(date("Y-m-d"))<=strtotime(FEE_DATE2)){
    define("FEE",6300);
}
else define("FEE",99999);


define('TALK_DURATION', array(
        'poster'=>'0',
        'plenary'=>'40',
        'invited'=>'20', 
        'oral'=>'15'));


$Accepted=array('accepted', 'poster','oral','invited','plenary'); // принятые доклады

define("NeedPassport",false); // Вкл формы сообщения паспортных данных
define("accessSertificate",false); //Разрешить скачивание сертификата участника
define("accessInvitation",true); //Разрешить скачивание приглашения

define("ShowThesisStatus",true); //Показывать статус тезисов (приняты/отклонены ...)
define("accessLift",FALSE); //Разрешить загрузку презентации в лифте
define("AllowUploadPoster",FALSE); //Разрешить загрузку постеров
define("allowThesisEdit",true); // разрешить редатктирование тезисов
define("allowGrade",true);




define("allowRFBR",false); //Разрешить поля РФФИ
define("editRFBR",false); //редактиование поля РФФИ после окончания сроков редактирования тезисов

define("allowedThesisNum",1); // число тезисов на доладчика (функционал >1 не доделан)

define("ThesisMaxChar",4200);
define("maxOverflow",200);
define("hideEmpty",true); //прятать пустые тезисы из выдачи


// Ссылка для регистрации на текущий сайт JPCS. Используется в языковых файлах.
// define("JPCS_SITE","https://physicaspb2021.iopconferenceseries.rivervalleytechnologies.com"); 
define("JPCS_SITE","https://physicaspb2021.iopconferenceseries.rivervalley.io"); 



$fee=array('---'=>'---','4000'=>'4000','5000'=>'5000','6000'=>'6000','7000'=>'7000');
$fee=json_encode($fee);

$bd=array('test','ioffe','skl','pn','kea'); //бэкдор-ссылки (form.php)

//mysql в двух форматах из-за разницы в стилях программирования
$dbhost="localhost";
$physica_db="papaioffru_phys";
$mysqluser="papaioffru_phys";
$mysqlpass="f1z1ka";

class Config {
    protected static $dbhost="localhost";
    protected static $physica_db="papaioffru_phys";
    protected static $mysqluser="papaioffru_phys";
    protected static $mysqlpass="f1z1ka";
}

// значики в тексте
$imageIcon='<i class="fa fa-picture-o" aria-hidden="true"></i>';
$infoIcon='<i class="fa fa-info-circle text-primary" aria-hidden="true" ></i>';
$pdfIcon='<i class="fa fa-file-pdf-o text-danger" aria-hidden="true"></i>';



// список языков
$lang_files = array(
"ru" => "lang/ru_RU.php",
"en" => "lang/en_US.php"
);

// получаем язык
$lang_browser = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
// проверяем язык если непонятно что такое, то английский
if (!in_array($lang_browser, array_keys($lang_files ))){
    $lang_browser = 'en';
}
//принудительная смена языка
if(isset($_GET['lang']))
    $_SESSION['lang']=(($_GET['lang']=='ru')?'ru':'en');
elseif(!isset($_SESSION['lang']))
    $_SESSION['lang']=$lang_browser;

$lang_file=$lang_files[$_SESSION['lang']];
include_once($lang_file);

?>

