<?php
/*
    * 
    *  Developed By        :   Ishwar Lal Ghiya
    *  Date Created        :   June 02, 2017
    *  Last Modification   :   Get item detail
    * 
*/

$obj_purchase = new client();
$result = array();
if(isset($_POST['itemId']) && isset($_POST['action']) && $_POST['action'] == "getItemDetail") {

	$itemId = $_POST['itemId'];
	$clientMasterItems = $obj_purchase->get_results("select cm.item_id, cm.item_name, cm.item_description, cm.unit_price, cm.unit_purchase_price, cm.cgst_tax_rate as client_cgst_tax_rate, cm.sgst_tax_rate as client_sgst_tax_rate, cm.igst_tax_rate as client_igst_tax_rate, cm.cess_tax_rate as client_cess_tax_rate, cm.item_category, m.item_id as category_id, m.item_name as category_name, m.hsn_code, m.igst_tax_rate, m.csgt_tax_rate, m.sgst_tax_rate, m.cess_tax_rate, cm.item_unit, u.unit_id, u.unit_name, u.unit_code from " . $obj_purchase->getTableName('client_master_item') . " as cm, " . $obj_purchase->getTableName('item') . " as m, " . $obj_purchase->getTableName('unit') . " as u where 1=1 AND cm.item_category = m.item_id AND cm.item_unit = u.unit_id AND cm.item_id = ".$itemId." AND cm.is_deleted='0' AND cm.status = '1' AND cm.added_by = '".$obj_purchase->sanitize($_SESSION['user_detail']['user_id'])."'");

	if(count($clientMasterItems) > 0) {

		foreach($clientMasterItems as $clientMasterItem) {

			$result['item_id'] = $clientMasterItem->item_id;
			$result['item_name'] = html_entity_decode($clientMasterItem->item_name);
			$result['item_description'] = html_entity_decode($clientMasterItem->item_description);
			$result['unit_price'] = $clientMasterItem->unit_price;
			$result['unit_purchase_price'] = $clientMasterItem->unit_purchase_price;
			$result['category_id'] = $clientMasterItem->category_id;
			$result['category_name'] = html_entity_decode($clientMasterItem->category_name);
			$result['hsn_code'] = $clientMasterItem->hsn_code;

			if($clientMasterItem->client_cgst_tax_rate == NULL) {
				$result['csgt_tax_rate'] = $clientMasterItem->csgt_tax_rate;
			} else {
				$result['csgt_tax_rate'] = $clientMasterItem->client_cgst_tax_rate;
			}

			if($clientMasterItem->client_sgst_tax_rate == NULL) {
				$result['sgst_tax_rate'] = $clientMasterItem->sgst_tax_rate;
			} else {
				$result['sgst_tax_rate'] = $clientMasterItem->client_sgst_tax_rate;
			}

			if($clientMasterItem->client_igst_tax_rate == NULL) {
				$result['igst_tax_rate'] = $clientMasterItem->igst_tax_rate;
			} else {
				$result['igst_tax_rate'] = $clientMasterItem->client_igst_tax_rate;
			}

			if($clientMasterItem->client_cess_tax_rate == NULL) {
				$result['cess_tax_rate'] = $clientMasterItem->cess_tax_rate;
			} else {
				$result['cess_tax_rate'] = $clientMasterItem->client_cess_tax_rate;
			}

			$result['unit_id'] = $clientMasterItem->unit_id;
			$result['unit_name'] = $clientMasterItem->unit_name;
			$result['unit_code'] = $clientMasterItem->unit_code;
		}

		$result['status'] = "success";
	} else {
		$result['status'] = "error";
	}
}

echo json_encode($result);
die;
?>