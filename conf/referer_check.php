<?php
ob_start();
//echo $_SERVER['HTTP_REFERER'];
if(!isset($_SERVER['HTTP_REFERER']) || empty($_SERVER['HTTP_REFERER'])){
    die('Invalid access to files');
}
?>