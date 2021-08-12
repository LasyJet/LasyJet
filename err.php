<?
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
ini_set('error_reporting', E_ALL^E_NOTICE);
error_reporting(E_ALL ^ E_NOTICE); 

var_dump($_GET,$_POST);
include(key($_GET).".php");

?>
