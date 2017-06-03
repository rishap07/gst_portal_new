<?php
/*
    * 
    *  Developed By        :   Ishwar Lal Ghiya
    *  Date Created        :   June 02, 2017
    *  Last Modification   :   Add new item
    * 
 */

$obj_client = new client();

$items = array();
if(isset($_GET['ajax']) && $_GET['ajax'] == "client_get_item" && isset($_GET['term'])) {
		
	$items[0]['n1'] = "success1";
	$items[0]['n2'] = "success2";
	$items[0]['n3'] = "success3";
	$items[0]['n4'] = "success4";
	$items[0]['n5'] = "success5";
	$items[0]['n6'] = "success6";
	$items[0]['n7'] = "success7";
	$items[0]['n8'] = "success8";
}

header('Content-type: application/json');
echo json_encode($items);
die;
?>