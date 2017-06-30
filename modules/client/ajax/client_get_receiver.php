<?php
/*
    * 
    *  Developed By        :   Ishwar Lal Ghiya
    *  Date Created        :   June 07, 2017
    *  Last Modification   :   Get Receiver
    * 
 */

$obj_client = new client();

$item = array();
$counter = 0;
if(isset($_GET['ajax']) && $_GET['ajax'] == "client_get_receiver" && isset($_GET['term'])) {

	$clientGetReceivers = $obj_client->get_results("select r.receiver_id, r.gstid, r.name, r.address, s.state_id, s.state_name, s.state_code from " . $obj_client->getTableName('receiver') . " as r, " . $obj_client->getTableName('state') . " as s where 1=1 AND r.state = s.state_id AND r.is_deleted='0' AND r.status = '1' AND r.name LIKE '%".trim($_GET['term'])."%' AND r.added_by = '".$_SESSION['user_detail']['user_id']."'");

	if(count($clientGetReceivers) > 0) {

		foreach($clientGetReceivers as $clientGetReceiver) {

			$item[$counter]['receiver_id'] = $clientGetReceiver->receiver_id;
			$item[$counter]['label'] = $clientGetReceiver->name;
			$item[$counter]['value'] = $clientGetReceiver->name;
			$item[$counter]['gstid'] = $clientGetReceiver->gstid;
			$item[$counter]['address'] = html_entity_decode($clientGetReceiver->address);
			$item[$counter]['state_id'] = $clientGetReceiver->state_id;
			$item[$counter]['state_name'] = $clientGetReceiver->state_name;
			$item[$counter]['state_code'] = $clientGetReceiver->state_code;
			$counter++;
		}
	}
}

header('Content-type: application/json');
echo json_encode($item);
die;
?>