<?php
    session_start();
    // ini_set('error_reporting', E_ALL);
    // ini_set('display_errors', 1);
    // ini_set('display_startup_errors', 1);
    include_once("../config.php");
    
    use \setasign\Fpdi\Fpdi;
    define('FPDF_FONTPATH','../classes/fpdf/font/this/'); 
    require_once('../classes/fpdf/fpdf.php');
    require_once('../classes/fpdi/autoload.php');

    $dbh = new PDO('mysql:host='.$dbhost.';dbname='.$physica_db, $mysqluser, $mysqlpass);
    $dbh->query("SET NAMES utf8");
    $status="";
    if ( 0 < $_FILES['file']['error'] ) {
        $status.='Error: ' . $_FILES['file']['error'] . '<br>';
    }
    else {
        $allowed = array('pdf',"jpg");//, 'ppt', 'pptx');
        $filename = $_FILES['file']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        
        if (in_array($ext, $allowed)) {

            $path='/uploads/lift/';
            $poster_num=$_SESSION['poster_num'];
            $filename=transliterator_transliterate('Any-Latin; Latin-ASCII; Lower()', $_SESSION['fullname']).date("Ymd_H-i").".".$ext;
            $filename=str_replace([" ","*"],["_",""],$filename);
            $filename=$poster_num."-".$filename;
            if(!move_uploaded_file($_FILES['file']['tmp_name'], "../".$path.$filename))
                  $err=" ERROR";
            
            #========================

            // $poster_num=$_SESSION['poster_num'];
            // //$poster_num="1-8";

            // $file="../".$path.$filename;

            // ob_start(); 

            // $pdf = new Fpdi();// initiate FPDI

            // $pageCount = $pdf->setSourceFile($file); // get the page count
            // $templateId = $pdf->importPage(1);
            // // $pdf->SetMargins(left, top, right);
            // $pdf->AddPage();
            // $pdf->useTemplate($templateId, ['adjustPageSize' => true]); // use the imported page and adjust the page size

            // $pdf->SetTextColor(220,50,50);
            // $pdf->SetY(5);
            // $pdf->SetX(-10  );
            // $pdf->SetFont('Times','',14);
            // $pdf->Cell(0,10,"[".$poster_num."]",0,0,'C'); // Poster number

            // ob_clean();
            // // Output the new PDF
            // $pdf->Output("F", $file);

            #========================
            

            if(!empty($_SESSION['user_id'])){
                $data=["thesis_id"=>$_SESSION['thesis_id'], "lift_file"=>$path.$filename];
                $sql="UPDATE ".YEAR."_thesises SET `lift_file`=:lift_file WHERE `thesis_id`=:thesis_id";
                $stmt = $dbh->prepare($sql);
                $stmt->execute($data);
            }
            $file=SITE.$path.$filename;
            $status.="Your file <a href='".$file."'>successfully uploaded</a>".$err;
        }
        else $status.= "Wrong file";
        
    }
    echo $status;
    // move_uploaded_file($_FILES['file']['tmp_name'], 'uploads/lift/' . $_FILES['file']['name']);
?>