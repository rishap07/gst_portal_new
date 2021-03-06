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
	$_POST['unit_purchase_price'] = $params['unit_purchase_price'];
	$_POST['cgst_tax_rate'] = $params['cgst_tax_rate'];
	$_POST['sgst_tax_rate'] = $params['sgst_tax_rate'];
	$_POST['igst_tax_rate'] = $params['igst_tax_rate'];
	$_POST['cess_tax_rate'] = $params['cess_tax_rate'];
	$_POST['status'] = $params['status'];
	$_POST['item_description'] = $params['item_description'];
	$_POST['is_applicable'] = $params['is_applicable'];

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