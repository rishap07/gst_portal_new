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
if(isset($_GET['ajax']) && $_GET['ajax'] == "client_hsnsac_code" && isset($_GET['term'])) {

	$clientItem = $obj_client->get_results("select i.item_id, CONCAT(UCASE(LEFT(i.item_name,1)),LCASE(SUBSTRING(i.item_name,2))) as item_name, i.hsn_code, i.item_type from " . $obj_client->getTableName('item') . " as i where 1=1 AND i.status = '1' AND i.is_deleted='0' AND (i.item_name LIKE '%".trim($_GET['term'])."%' OR i.hsn_code LIKE '%".trim($_GET['term'])."%') ORDER BY i.item_name ASC");

	if(count($clientItem) > 0) {

		foreach($clientItem as $cItem) {

			$item[$counter]['item_id'] = $cItem->item_id;
			$item[$counter]['label'] = $cItem->item_name . "(" . $cItem->hsn_code . ")";
			$item[$counter]['value'] = $cItem->item_name . "(" . $cItem->hsn_code . ")";
			$item[$counter]['hsn_code'] = $cItem->hsn_code;
			$item[$counter]['item_type'] = $cItem->item_type;
			$counter++;
		}
	}
}

header('Content-type: application/json');
echo json_encode($item);
die;
?>