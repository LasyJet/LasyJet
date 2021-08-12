<?
// schedule index.php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

header('Content-Type: text/html; charset=utf-8');
include_once("../../config.php");
include_once("../../classes/adm.class.php");

$ScheduleTitle=ConfTitle."/".YEAR.": Программа устных докладов.";

$data=TU::SheduleData();

$table=TU::SheduleTable($data);

$tpl=file_get_contents("template.html");
//  $str=str_ireplace("{%ScheduleTable%}", $data, $tpl);
$content=str_ireplace("{%ScheduleTable%}", $table, $tpl);
$content=str_ireplace("{%ScheduleTitle%}", $ScheduleTitle, $content);
$content=str_ireplace("{%testTable%}", testData(), $content);

echo $content; 

##############################################################
function testData(){
    $data="
    <table id='schedule'>
        <thead>
            <tr>
                <th>Data1</th>
                <th>Data2</th>
                <th>Data3</th>
                <th>Data4</th>
            </tr>
            </thead>

            <tbody class='connectedSortable ui-sortable'>
            <tr style='display: table-row;'>
                <td>257</td>
                <td contenteditable='true'>Какой-то текст</td>
                <td>120.45</td>
                <td>1.83</td>
            </tr>
            <tr>
                <td>156</td>
                <td contenteditable='true'>Другой текст</td>
                <td>101.95</td>
                <td>1.82</td>
            </tr>

            <tr style='display: table-row;'>
                <td>256</td>
                <td contenteditable='true'>Дребедень из фывдаооу</td>
                <td>100.95</td>
                <td>1.82</td>
            </tr>

            <tr style='display: table-row;'>
                <td>777</td>
                <td contenteditable='true'>Финальная чепуха</td>
                <td>97.95</td>
                <td>10.5</td>
            </tr>
        </tbody>
    </table>
    ";
return $data;
}
?>

