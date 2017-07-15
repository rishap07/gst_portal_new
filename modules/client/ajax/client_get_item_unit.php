<?php
/*
    * 
    *  Developed By        :   Ishwar Lal Ghiya
    *  Date Created        :   July 07, 2017
    *  Last Modification   :   Get Item Unit
    * 
 */

$obj_client = new client();

$unit = array();
$counter = 0;
if(isset($_GET['ajax']) && $_GET['ajax'] == "client_get_item_unit" && isset($_GET['term'])) {

	$clientMasterUnits = $obj_client->get_results("select u.unit_id, u.unit_name, u.unit_code from " . $obj_client->getTableName('unit') . " as u where 1=1 AND u.is_deleted='0' AND u.status = '1' AND (u.unit_name LIKE '%".trim($_GET['term'])."%' OR u.unit_code LIKE '%".trim($_GET['term'])."%')");

	if(count($clientMasterUnits) > 0) {

		foreach($clientMasterUnits as $clientMasterUnit) {

			$unit[$counter]['unit_id'] = $clientMasterUnit->unit_id;
			$unit[$counter]['label'] = $clientMasterUnit->unit_name;
			$unit[$counter]['value'] = $clientMasterUnit->unit_code;
			$counter++;
		}
	}
}

header('Content-type: application/json');
echo json_encode($unit);
die;
?>