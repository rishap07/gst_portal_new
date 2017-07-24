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

if(isset($_POST['action']) && $_POST['action'] == "submitKYC" && $_GET['ajax'] == "client_submit_kyc") {

	if($obj_client->saveClientKYC()) {
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