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

if(isset($_POST['clientID']) && $obj_client->validateId(base64_decode($_POST['clientID'])) && isset($_POST['action']) && $_POST['action'] == "submitKYCBySubscriber" && $_GET['ajax'] == "client_submit_kyc_by_subscriber") {

	if($obj_client->saveClientKYCBySubscriber()) {
		$result['status'] = "success";
	} else {
		$result['status'] = "error";
		$result['message'] = $obj_client->getErrorMessage();
		$obj_client->unsetMessage();
	}
}

echo json_encode($result);
die;
?>