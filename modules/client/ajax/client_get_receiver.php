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

	$clientGetReceivers = $obj_client->get_results("select r.receiver_id, r.name, r.company_name, r.email, r.address, r.city, r.zipcode, r.phone, r.fax, r.pannumber, r.gstid, r.website, r.remarks, s.state_id, s.state_name, s.state_code from " . $obj_client->getTableName('receiver') . " as r, " . $obj_client->getTableName('state') . " as s where 1=1 AND r.state = s.state_id AND r.is_deleted='0' AND r.status = '1' AND r.name LIKE '%".trim($_GET['term'])."%' AND r.added_by = '".$obj_client->sanitize($_SESSION['user_detail']['user_id'])."'");

	if(count($clientGetReceivers) > 0) {

		foreach($clientGetReceivers as $clientGetReceiver) {

			$item[$counter]['receiver_id'] = $clientGetReceiver->receiver_id;
			$item[$counter]['label'] = $clientGetReceiver->name;
			$item[$counter]['value'] = $clientGetReceiver->name;
			$item[$counter]['company_name'] = $clientGetReceiver->company_name;
			$item[$counter]['email'] = $clientGetReceiver->email;
			$item[$counter]['address'] = html_entity_decode($clientGetReceiver->address);
			$item[$counter]['city'] = $clientGetReceiver->city;
			$item[$counter]['state_id'] = $clientGetReceiver->state_id;
			$item[$counter]['state_name'] = $clientGetReceiver->state_name;
			$item[$counter]['state_code'] = $clientGetReceiver->state_code;
			$item[$counter]['zipcode'] = $clientGetReceiver->zipcode;
			$item[$counter]['phone'] = $clientGetReceiver->phone;
			$item[$counter]['fax'] = $clientGetReceiver->fax;
			$item[$counter]['pannumber'] = $clientGetReceiver->pannumber;
			$item[$counter]['gstid'] = $clientGetReceiver->gstid;
			$item[$counter]['website'] = $clientGetReceiver->website;
			$item[$counter]['remarks'] = $clientGetReceiver->remarks;
			$counter++;
		}
	}
}

header('Content-type: application/json');
echo json_encode($item);
die;
?>