<?php
session_start();

use \setasign\Fpdi\Fpdi;
define('FPDF_FONTPATH','../classes/fpdf/font/this/'); 
require_once('../classes/fpdf/fpdf.php');
require_once('../classes/fpdi/autoload.php');

// $poster_num=$_SESSION['poster_num'];
$poster_num="1-8";

$file="../uploads/poster/1-8=kozuskina_alena_ilʹinicna20201005_22-49.pdf";

ob_start(); 

$pdf = new Fpdi();// initiate FPDI

$pageCount = $pdf->setSourceFile($file); // get the page count
$templateId = $pdf->importPage(1);
// $pdf->SetMargins(left, top, right);
$pdf->AddPage();
$pdf->useTemplate($templateId, ['adjustPageSize' => true]); // use the imported page and adjust the page size

$pdf->SetTextColor(220,50,50);
$pdf->SetY(25);
$pdf->SetX(-35);
$pdf->SetFont('Times','',42);
$pdf->Cell(0,10,"-[".$poster_num."]",0,0,'C'); // Poster number

ob_clean();
// Output the new PDF
$file="../uploads/poster/NEW-1-8=kozuskina_alena_ilʹinicna20201005_22-49.pdf";
$pdf->Output("F", $file);

?>