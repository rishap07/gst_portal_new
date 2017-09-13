<?php
/*
    * 
    *  Developed By        :   Ishwar Lal Ghiya
    *  Date Created        :   July 14, 2017
    *  Last Modification   :   Get Supplier
    * 
*/

$obj_purchase = new purchase();
$item = array();
$counter = 0;
if(isset($_GET['ajax']) && $_GET['ajax'] == "purchase_get_item" && isset($_GET['term'])) {

	if(isset($_GET['itype']) && $_GET['itype'] === "service") {
		$clientMasterItems = $obj_purchase->get_results("select cm.item_id, cm.item_name, cm.item_description, cm.unit_price, cm.item_category, m.item_id as category_id, m.item_name as category_name, m.hsn_code, m.igst_tax_rate, m.csgt_tax_rate, m.sgst_tax_rate, m.cess_tax_rate, cm.item_unit, u.unit_id, u.unit_name, u.unit_code from " . $obj_purchase->getTableName('client_master_item') . " as cm, " . $obj_purchase->getTableName('item') . " as m, " . $obj_purchase->getTableName('unit') . " as u where 1=1 AND m.item_type = '1' AND cm.item_category = m.item_id AND cm.item_unit = u.unit_id AND cm.is_deleted='0' AND cm.status = '1' AND (cm.item_name LIKE '%".trim($_GET['term'])."%' OR m.hsn_code LIKE '%".trim($_GET['term'])."%') AND cm.added_by = '".$obj_purchase->sanitize($_SESSION['user_detail']['user_id'])."'");
	} else {
		$clientMasterItems = $obj_purchase->get_results("select cm.item_id, cm.item_name, cm.item_description, cm.unit_price, cm.item_category, m.item_id as category_id, m.item_name as category_name, m.hsn_code, m.igst_tax_rate, m.csgt_tax_rate, m.sgst_tax_rate, m.cess_tax_rate, cm.item_unit, u.unit_id, u.unit_name, u.unit_code from " . $obj_purchase->getTableName('client_master_item') . " as cm, " . $obj_purchase->getTableName('item') . " as m, " . $obj_purchase->getTableName('unit') . " as u where 1=1 AND cm.item_category = m.item_id AND cm.item_unit = u.unit_id AND cm.is_deleted='0' AND cm.status = '1' AND (cm.item_name LIKE '%".trim($_GET['term'])."%' OR m.hsn_code LIKE '%".trim($_GET['term'])."%') AND cm.added_by = '".$obj_purchase->sanitize($_SESSION['user_detail']['user_id'])."'");
	}

	if(count($clientMasterItems) > 0) {

		foreach($clientMasterItems as $clientMasterItem) {

			$item[$counter]['item_id'] = $clientMasterItem->item_id;
			$item[$counter]['label'] = $clientMasterItem->item_name;
			$item[$counter]['value'] = $clientMasterItem->item_name;
			$item[$counter]['item_description'] = $clientMasterItem->item_description;
			$item[$counter]['unit_price'] = $clientMasterItem->unit_price;
			$item[$counter]['category_id'] = $clientMasterItem->category_id;
			$item[$counter]['category_name'] = $clientMasterItem->category_name;
			$item[$counter]['hsn_code'] = $clientMasterItem->hsn_code;
			$item[$counter]['igst_tax_rate'] = $clientMasterItem->igst_tax_rate;
			$item[$counter]['csgt_tax_rate'] = $clientMasterItem->csgt_tax_rate;
			$item[$counter]['sgst_tax_rate'] = $clientMasterItem->sgst_tax_rate;
			$item[$counter]['cess_tax_rate'] = $clientMasterItem->cess_tax_rate;
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