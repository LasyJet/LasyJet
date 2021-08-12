<?
// schedule index.php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

header('Content-Type: text/html; charset=utf-8');
include_once("../../config.php");
include_once("../../classes/adm.class.php");

$ScheduleTitle=ConfTitle."/".YEAR.": Программа устных докладов.";

$itemsdata=TU::item4ScheduleData();
$itemsTable=TU::item4ScheduleTable($itemsdata);
$ScheduleData=TU::ScheduleData();
$ScheduleTable=TU::ScheduleTable($ScheduleData);


$tpl=file_get_contents("template.html");
//  $str=str_ireplace("{%ScheduleTable%}", $data, $tpl);
$content=str_ireplace("{%ScheduleTitle%}", $ScheduleTitle, $tpl);
$content=str_ireplace("{%itemsScheduleTable%}", $itemsTable, $content);
$content=str_ireplace("{%ScheduleTable%}", $ScheduleTable, $content);
// $content=str_ireplace("{%testTable%}", testData(), $content);

echo $content; 

##############################################################
function testData(){
    $data="
    <table id='schedule_opt' class='schedule'>
        <thead>
            <tr>
                <th>order</th>
                <th>Time</th>
                <th>Duration</th>
                <th>Section</th>
                <th>Talk</th>
                <th>Report</th>
                <th>id</th>
            </tr>
            </thead>

            <tbody class='connectedSortable ui-sortable'>
            <tr style='display: table-row;'>
                <td>1</td>
                <td>10:00</td>
                <td>00:05</td>
                <td contenteditable='true'>10</td>
                <td>Обед</td>
                <td>--</td>
                <td>--</td>
            </tr>
            <tr>
                <td>2</td>
                <td></td>
                <td></td>
                <td contenteditable='true'>20</td>
                <td>Кофебрейк</td>
                <td>--</td>
                <td>--</td>
            </tr>

            <tr style='display: table-row;'>
                <td>3</td>
                <td></td>
                <td></td>
                <td contenteditable='true'>10</td>
                <td>Устная презентация стендовых докладов</td>
                <td></td>
                <td></td>
            </tr>

            <tr style='display: table-row;'>
                <td>4</td>
                <td></td>
                <td></td>
                <td contenteditable='true'>180</td>
                <td>Фуршет</td>
                <td>-</td>
                <td>-</td>
            </tr>
        </tbody>
    </table>
    ";
return $data;
}


?>

