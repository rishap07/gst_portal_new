<?php
/*
    * 
    *  Developed By        :   Ishwar Lal Ghiya
    *  Date Created        :   September 25, 2017
    *  Last Modification   :   Add new receiver
    * 
*/

$obj_master = new master();
$result = array();

if(isset($_POST['receiverData']) && isset($_POST['action']) && $_POST['action'] == "addReceiver") {

	$params = array();
	parse_str($_POST['receiverData'], $params);

	$_POST['name'] = $params['name'];
	$_POST['company_name'] = $params['company_name'];
	$_POST['email'] = $params['email'];
	$_POST['address'] = $params['address'];
	$_POST['city'] = $params['city'];
	$_POST['country'] = $params['country'];
	$_POST['state'] = $params['state'];
	$_POST['zipcode'] = $params['zipcode'];
	$_POST['phone'] = $params['phone'];
	$_POST['fax'] = $params['fax'];
	$_POST['pannumber'] = $params['pannumber'];
	$_POST['gstid'] = $params['gstid'];
	$_POST['website'] = $params['website'];
	$_POST['remarks'] = $params['remarks'];
	$_POST['status'] = $params['status'];
	$_POST['vendor_type'] = $params['vendor_type'];
	$_POST['submit'] = 'submit';

	if($obj_master->addReceiver()) {
		$result['status'] = "success";
		$result['message'] = $obj_master->getValMsg('inserted');
	} else {
		$result['status'] = "error";
		$result['message'] = $obj_master->getValMsg('failed');
	}

	$obj_master->unsetMessage();
}

echo json_encode($result);
die;
?>