<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$pdf_file="test.pdf";
$out_file=pathinfo($pdf_file, PATHINFO_FILENAME)."_small.jpg";

$exec_info="identify  -format '%w x %h %x x %y' ".$pdf_file;
exec($exec_info, $info );
var_dump($info);
// $im = new Imagick($pdf_file.'[0]');
// $im->setImageFormat('jpg');

// echo $resize="/usr/bin/convert -density 200 -background white -alpha remove $pdf_file $out_file";
// shell_exec($resize);

exec("gs -dSAFER -dBATCH -sDEVICE=jpeg -dTextAlphaBits=4 -dGraphicsAlphaBits=4 -r70  -sOutputFile=$out_file $pdf_file");
// exec("gs -dSAFER -dBATCH -sDEVICE=jpeg -dTextAlphaBits=4 -dGraphicsAlphaBits=4 -r100  -sOutputFile=$out_file $pdf_file");
// echo "<p>".$info."</p>";
echo "<img src='".$out_file."' alt=''>";
// header('Content-Type: image/jpeg');
// echo $im;

?>

