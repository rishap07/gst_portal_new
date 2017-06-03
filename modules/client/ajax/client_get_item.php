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
if(isset($_POST['term'])) {
	
	$result['status'] = "success";
	$obj_client->unsetMessage();
}

echo json_encode($result);
die;
?>