<?php

// $file = $_GET['file'];
//     if (ob_get_level()) {
//         ob_end_clean();
//     }


$day="1"; //установить скриптом 

$DIR="../data/lift/{$day}/";
$files=array_diff(scandir($DIR),array(".",".."));

$fullPATH=function($f) {
    global $DIR;
    return $DIR.$f;
};

$files=array_map($fullPATH, $files);
// print_r($files);

$outputFile="Lift-merged-{$day}.pdf";
$cmd = "gs -q -dNOPAUSE -dBATCH -sDEVICE=pdfwrite -sOutputFile=$outputFile ".implode(" ",$files);


$result = shell_exec($cmd);

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename=' . basename($outputFile));
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($outputFile));
// header ("Content-Disposition: attachment; filename=" . $outputFile);
readfile($outputFile);

exit;
?>







<?
// use setasign\Fpdi\Fpdi;

// require_once('./classes/fpdf/fpdf.php');
// require_once('./classes/fpdi/autoload.php');

// class ConcatPdf extends Fpdi
// {
//     public $files = array();

//     public function setFiles($files)
//     {
//         $this->files = $files;
//     }

//     public function concat()
//     {
//         foreach($this->files AS $file) {
//             $pageCount = $this->setSourceFile($file);
//             for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
//                 $pageId = $this->ImportPage($pageNo);
//                 $s = $this->getTemplatesize($pageId);
//                 $this->AddPage($s['orientation'], $s);
//                 $this->useImportedPage($pageId);
//             }
//         }
//     }
// }
// echo "<pre>";
// var_dump($files);
// echo "</pre>";
// $pdf = new ConcatPdf();
// $pdf->setFiles($files);
// $pdf->concat();

// // $pdf->Output('I', 'concat.pdf');
// $pdf->Output();
?>