<?php
/*
    * 
    *  Developed By        :   Ishwar Lal Ghiya
    *  Date Created        :   June 02, 2017
    *  Last Modification   :   Add new item
    * 
 */

$obj_client = new client();

$item = array();
$counter = 0;
if(isset($_GET['ajax']) && $_GET['ajax'] == "client_get_item" && isset($_GET['term'])) {

	if(isset($_GET['itype']) && $_GET['itype'] === "service") {
		$clientMasterItems = $obj_client->get_results("select cm.item_id, cm.item_name, cm.item_description, cm.unit_price, cm.unit_purchase_price, cm.cgst_tax_rate as client_cgst_tax_rate, cm.sgst_tax_rate as client_sgst_tax_rate, cm.igst_tax_rate as client_igst_tax_rate, cm.cess_tax_rate as client_cess_tax_rate, cm.item_category, m.item_id as category_id, m.item_name as category_name, m.hsn_code, m.igst_tax_rate, m.csgt_tax_rate, m.sgst_tax_rate, m.cess_tax_rate, cm.item_unit, u.unit_id, u.unit_name, u.unit_code from " . $obj_client->getTableName('client_master_item') . " as cm, " . $obj_client->getTableName('item') . " as m, " . $obj_client->getTableName('unit') . " as u where 1=1 AND m.item_type = '1' AND cm.item_category = m.item_id AND cm.item_unit = u.unit_id AND cm.is_deleted='0' AND cm.status = '1' AND (cm.item_name LIKE '%".trim($_GET['term'])."%' OR m.hsn_code LIKE '%".trim($_GET['term'])."%') AND cm.added_by = '".$_SESSION['user_detail']['user_id']."'");
	} else {
		$clientMasterItems = $obj_client->get_results("select cm.item_id, cm.item_name, cm.item_description, cm.unit_price, cm.unit_purchase_price, cm.cgst_tax_rate as client_cgst_tax_rate, cm.sgst_tax_rate as client_sgst_tax_rate, cm.igst_tax_rate as client_igst_tax_rate, cm.cess_tax_rate as client_cess_tax_rate, cm.item_category, m.item_id as category_id, m.item_name as category_name, m.hsn_code, m.igst_tax_rate, m.csgt_tax_rate, m.sgst_tax_rate, m.cess_tax_rate, cm.item_unit, u.unit_id, u.unit_name, u.unit_code from " . $obj_client->getTableName('client_master_item') . " as cm, " . $obj_client->getTableName('item') . " as m, " . $obj_client->getTableName('unit') . " as u where 1=1 AND cm.item_category = m.item_id AND cm.item_unit = u.unit_id AND cm.is_deleted='0' AND cm.status = '1' AND (cm.item_name LIKE '%".trim($_GET['term'])."%' OR m.hsn_code LIKE '%".trim($_GET['term'])."%') AND cm.added_by = '".$_SESSION['user_detail']['user_id']."'");
	}

	if(count($clientMasterItems) > 0) {

		foreach($clientMasterItems as $clientMasterItem) {

			$item[$counter]['item_id'] = $clientMasterItem->item_id;
			$item[$counter]['label'] = html_entity_decode($clientMasterItem->item_name);
			$item[$counter]['value'] = html_entity_decode($clientMasterItem->item_name);
			$item[$counter]['item_description'] = html_entity_decode($clientMasterItem->item_description);
			$item[$counter]['unit_price'] = $clientMasterItem->unit_price;
			$item[$counter]['unit_purchase_price'] = $clientMasterItem->unit_purchase_price;
			$item[$counter]['category_id'] = $clientMasterItem->category_id;
			$item[$counter]['category_name'] = html_entity_decode($clientMasterItem->category_name);
			$item[$counter]['hsn_code'] = $clientMasterItem->hsn_code;

			if($clientMasterItem->client_cgst_tax_rate == NULL) {
				$item[$counter]['csgt_tax_rate'] = $clientMasterItem->csgt_tax_rate;
			} else {
				$item[$counter]['csgt_tax_rate'] = $clientMasterItem->client_cgst_tax_rate;
			}

			if($clientMasterItem->client_sgst_tax_rate == NULL) {
				$item[$counter]['sgst_tax_rate'] = $clientMasterItem->sgst_tax_rate;
			} else {
				$item[$counter]['sgst_tax_rate'] = $clientMasterItem->client_sgst_tax_rate;
			}

			if($clientMasterItem->client_igst_tax_rate == NULL) {
				$item[$counter]['igst_tax_rate'] = $clientMasterItem->igst_tax_rate;
			} else {
				$item[$counter]['igst_tax_rate'] = $clientMasterItem->client_igst_tax_rate;
			}

			if($clientMasterItem->client_cess_tax_rate == NULL) {
				$item[$counter]['cess_tax_rate'] = $clientMasterItem->cess_tax_rate;
			} else {
				$item[$counter]['cess_tax_rate'] = $clientMasterItem->client_cess_tax_rate;
			}

			$item[$counter]['unit_id'] = $clientMasterItem->unit_id;
			$item[$counter]['unit_name'] = $clientMasterItem->unit_name;
			$item[$counter]['unit_code'] = $clientMasterItem->unit_code;
			$counter++;
		}
	}
}

header('Content-type: application/json');
echo json_encode($item);
die;
?>