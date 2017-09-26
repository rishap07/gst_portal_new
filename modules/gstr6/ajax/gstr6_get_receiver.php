<?php
/*
    * 
    *  Developed By        :   Ishwar Lal Ghiya
    *  Date Created        :   June 07, 2017
    *  Last Modification   :   Get Receiver
    * 
*/

$gstr6 = new gstr6();

$item = array();
$counter = 0;
if(isset($_GET['ajax']) && $_GET['ajax'] == "gstr6_get_receiver" && isset($_GET['term'])) {

	$clientGetReceivers = $gstr6->get_results("select r.receiver_id, r.name, r.company_name, r.email, r.address, r.city, r.zipcode, r.phone, r.fax, r.pannumber, r.gstid, r.website, r.remarks, r.vendor_type, s.state_id, s.state_name, s.state_code, c.id as country_id, c.country_code, c.country_name from " . $gstr6->getTableName('receiver') . " as r left join " . $gstr6->getTableName('state') . " as s on r.state = s.state_id left join " . $gstr6->getTableName('country') . " as c on r.country = c.id where 1=1 AND r.is_deleted='0' AND r.status = '1' AND r.name LIKE '%".trim($_GET['term'])."%' AND r.added_by = '".$gstr6->sanitize($_SESSION['user_detail']['user_id'])."'");

	if(count($clientGetReceivers) > 0) {

		foreach($clientGetReceivers as $clientGetReceiver) {

			$item[$counter]['receiver_id'] = $clientGetReceiver->receiver_id;
			$item[$counter]['label'] = html_entity_decode($clientGetReceiver->name);
			$item[$counter]['value'] = html_entity_decode($clientGetReceiver->name);
			$item[$counter]['company_name'] = html_entity_decode($clientGetReceiver->company_name);
			$item[$counter]['email'] = $clientGetReceiver->email;

			$fullAddress =  $clientGetReceiver->address . ", " . $clientGetReceiver->city . ", " . $clientGetReceiver->state_name . ", " . $clientGetReceiver->zipcode . ", " . $clientGetReceiver->country_name;
			$item[$counter]['address'] = html_entity_decode($fullAddress);

			$item[$counter]['city'] = html_entity_decode($clientGetReceiver->city);
			$item[$counter]['state_id'] = $clientGetReceiver->state_id;
			$item[$counter]['state_name'] = html_entity_decode($clientGetReceiver->state_name);
			$item[$counter]['state_code'] = $clientGetReceiver->state_code;
			$item[$counter]['zipcode'] = $clientGetReceiver->zipcode;
			$item[$counter]['phone'] = $clientGetReceiver->phone;
			$item[$counter]['fax'] = $clientGetReceiver->fax;
			$item[$counter]['pannumber'] = $clientGetReceiver->pannumber;
			$item[$counter]['gstid'] = $clientGetReceiver->gstid;
			$item[$counter]['website'] = $clientGetReceiver->website;
			$item[$counter]['remarks'] = html_entity_decode($clientGetReceiver->remarks);
			$item[$counter]['vendor_type'] = $clientGetReceiver->vendor_type;
			$item[$counter]['country_id'] = $clientGetReceiver->country_id;
			$item[$counter]['country_code'] = $clientGetReceiver->country_code;
			$item[$counter]['country_name'] = html_entity_decode($clientGetReceiver->country_name);
			$counter++;
		}
	}
}

header('Content-type: application/json');
echo json_encode($item);
die;
?>