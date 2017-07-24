<?php
/*
    * 
    *  Developed By        :   Ishwar Lal Ghiya
    *  Date Created        :   July 14, 2017
    *  Last Modification   :   Get Supplier
    * 
*/

$obj_purchase = new purchase();
$supplier = array();
$counter = 0;
if(isset($_GET['ajax']) && $_GET['ajax'] == "purchase_get_supplier" && isset($_GET['term'])) {

	$clientGetSuppliers = $obj_purchase->get_results("select su.supplier_id, su.name, su.company_name, su.email, su.address, su.city, su.zipcode, su.phone, su.fax, su.pannumber, su.gstid, su.website, su.remarks, s.state_id, s.state_name, s.state_code, s.state_tin from " . $obj_purchase->getTableName('supplier') . " as su, " . $obj_purchase->getTableName('state') . " as s where 1=1 AND su.state = s.state_id AND su.is_deleted='0' AND su.status = '1' AND su.name LIKE '%".trim($_GET['term'])."%' AND su.added_by = '".$obj_purchase->sanitize($_SESSION['user_detail']['user_id'])."'");

	if(count($clientGetSuppliers) > 0) {

		foreach($clientGetSuppliers as $clientGetSupplier) {

			$supplier[$counter]['supplier_id'] = $clientGetSupplier->supplier_id;
			$supplier[$counter]['label'] = html_entity_decode($clientGetSupplier->name);
			$supplier[$counter]['value'] = html_entity_decode($clientGetSupplier->name);
			$supplier[$counter]['company_name'] = html_entity_decode($clientGetSupplier->company_name);
			$supplier[$counter]['email'] = $clientGetSupplier->email;
			$supplier[$counter]['address'] = html_entity_decode($clientGetSupplier->address);
			$supplier[$counter]['city'] = $clientGetSupplier->city;
			$supplier[$counter]['zipcode'] = $clientGetSupplier->zipcode;
			$supplier[$counter]['phone'] = $clientGetSupplier->phone;
			$supplier[$counter]['fax'] = $clientGetSupplier->fax;
			$supplier[$counter]['pannumber'] = $clientGetSupplier->pannumber;
			$supplier[$counter]['gstid'] = $clientGetSupplier->gstid;
			$supplier[$counter]['website'] = $clientGetSupplier->website;
			$supplier[$counter]['remarks'] = $clientGetSupplier->remarks;
			$supplier[$counter]['state_id'] = $clientGetSupplier->state_id;
			$supplier[$counter]['state_name'] = $clientGetSupplier->state_name;
			$supplier[$counter]['state_code'] = $clientGetSupplier->state_code;
			$supplier[$counter]['state_tin'] = $clientGetSupplier->state_tin;
			$counter++;
		}
	}
}

header('Content-type: application/json');
echo json_encode($supplier);
die;
?>