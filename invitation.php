<?php
session_start();

use \setasign\Fpdi\Fpdi;
define('FPDF_FONTPATH','classes/fpdf/font/this/'); 
require_once('classes/fpdf/fpdf.php');
require_once('classes/fpdi/autoload.php');

/**
* Склонение слов по падежам. С использованием api Яндекса
* @var string $text - текст 
* @var integer $numForm - нужный падеж. Число от 0 до 5
*
* @return - вернет false при неудаче. При успехе вернет нужную форму слова
*/


function getNewFormText($text, $numForm){
   $url = "https://ws3.morpher.ru/russian/declension?s=".urlencode($text)."&format=json";
   // $url='{
	  // "Р": "Пигусова",
	  // "Д": "Пигусову",
	  // "В": "Пигусова",
	  // "Т": "Пигусовым",
	  // "П": "Пигусове",
	  // "ФИО": {
		// "Ф": "Пигусов",
		// "И": "",
		// "О": ""
	  // }
	// }'; 
	$json=@file_get_contents($url);
	$result = json_decode($json);
	
	if($result){
		return $p=$result->{$numForm}; 
    }
    return false;
}

function stripWhitespaces($string) {
	$old_string = $string;
	$string = strip_tags($string);
	$string = preg_replace('/([^\pL\pN\pP\pS\pZ])|([\xC2\xA0])/u', ' ', $string);
	// $string = preg_replace('/[\x00-\x1F\x7F]/u', '', $string);
	$string = str_replace('  ',' ', $string);
	$string = trim($string);
	
	if ($string === $old_string) {
	  return $string;
	} else {
	  return stripWhitespaces($string); 
	}  
  }
// echo getNewFormText($_SESSION['family'], "Д");


$thesisTile=$_SESSION['title'];

$thesisTile=strip_tags(html_entity_decode(trim($_SESSION['title'])));
$thesisTile= stripWhitespaces($thesisTile);


$family_ini=$_SESSION['family'];
$family_ini=getNewFormText($_SESSION['family'], "Д").$_SESSION['initials'];
$AuthorName=$_SESSION['name'];
$affiliation=$_SESSION['affiliation'];

$suffix=($_SESSION['gender']=="w")?"ая":"ый";

echo $thesisTile;

$Author="Уважаем".$suffix." $AuthorName!";

if ($_SESSION['this_report_type']=='poster'){
$text="    От имени Организационного комитета конференции ФизикА.СПб, информируем Вас о том, что Ваша работа «".$thesisTile."» принята для стендового доклада. Для размещения Ваших материалов предоставляется стенд размером 1мx1м. Кроме этого, стендовым докладчикам предоставляется возможность краткого устного представления аннотации своего доклада в формате 1 минута + 1 слайд. О начале приема слайдов для устного представления аннотаций стендовых докладов будет сообщено дополнительно за несколько недель до начала конференции. Тезисы Вашего доклада будут включены в Сборник тезисов, издание которого планируется к началу конференции. 
    Также напоминаем Вам о необходимости своевременной оплаты оргвзноса. Информация о способах оплаты оргвзноса размещена на сайте конференции: http://physica.spb.ru/payment/.";
}
elseif($_SESSION['this_report_type']=='oral'){
$text="    От имени Организационного комитета конференции ФизикА.СПб, информируем Вас о том, что Ваша работа «".$thesisTile."» принята для устного доклада. Тезисы Вашего доклада будут включены в Сборник тезисов, издание которого планируется к началу конференции. 
    Также напоминаем Вам о необходимости своевременной оплаты оргвзноса. Информация о способах оплаты оргвзноса размещена на сайте конференции: http://physica.spb.ru/payment/.";
}
else $text="Произошла ошибка. Обратитесь в оргкомитет по почте.";


ob_start(); 
// initiate FPDI
$pdf = new Fpdi();

// get the page count
$pageCount = $pdf->setSourceFile('asset/invitation_tpl.pdf');
// iterate through all pages



$templateId = $pdf->importPage(1);

$pdf->SetMargins(32, 100, 32);
$pdf->AddPage();
// use the imported page and adjust the page size
$pdf->useTemplate($templateId, ['adjustPageSize' => true]);

$pdf->SetFont('Times','',13);
// $pdf->SetFont('freeserif', '', 14);
// $pdf->SetXY(PDF_MARGIN_LEFT+20, 100, 160);


$affiliation = iconv('UTF-8', 'windows-1251', $affiliation);
$pdf->Cell(0, 8, "[ ".$affiliation." ]", 0, true, 'R');

$family_ini = iconv('UTF-8', 'windows-1251', $family_ini);
$pdf->Cell(0, 8, $family_ini, 0, true, 'R');

$pdf->SetFont('Times','',13);
$Author = iconv('UTF-8', 'windows-1251', "\n".$Author);
$pdf->MultiCell(146, 6, $Author,'',"C");


$pdf->SetFont('Times','',13);
$text = iconv('UTF-8', 'windows-1251', "\n".$text);
$pdf->MultiCell(146, 6, $text);


//$pdf->Write(5, $text);




// for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
//     // import a page
//     $templateId = $pdf->importPage($pageNo);

//     $pdf->AddPage();
//     // use the imported page and adjust the page size
//     $pdf->useTemplate($templateId, ['adjustPageSize' => true]);

//     $pdf->SetFont('Helvetica');
//     $pdf->SetXY(5, 5);
//     $pdf->Write(8, 'A complete document imported with FPDI');
// }
ob_clean();
// Output the new PDF
$pdf->Output();

?>