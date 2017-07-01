<?php
/*
    * 
    *  Developed By        :   Ishwar Lal Ghiya
    *  Date Created        :   June 02, 2017
    *  Last Modification   :   Add new item
    * 
 */

$obj_client = new client();

$result = array();
if(isset($_POST['itemData']) && isset($_POST['action']) && $_POST['action'] == "addItem") {
	
	$params = array();
	parse_str($_POST['itemData'], $params);
	
	$_POST['item_name'] = $params['item_name'];
	$_POST['item_category'] = $params['item_category'];
	$_POST['item_unit'] = $params['item_unit'];
	$_POST['unit_price'] = $params['unit_price'];
	$_POST['status'] = $params['status'];

	if($obj_client->addClientItem()) {
		$result['status'] = "success";
		$result['message'] = $obj_client->getValMsg('iteminserted');
	} else {
		$result['status'] = "error";
		$result['message'] = $obj_client->getValMsg('failed');
	}

	$obj_client->unsetMessage();
}

echo json_encode($result);
die;
?>