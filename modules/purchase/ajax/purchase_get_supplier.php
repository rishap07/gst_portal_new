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

	$clientGetSuppliers = $obj_purchase->get_results("select su.supplier_id, su.name, su.company_name, su.email, su.address, su.city, su.zipcode, su.phone, su.fax, su.pannumber, su.gstid, su.website, su.remarks, su.vendor_type, s.state_id, s.state_name, s.state_code, s.state_tin, c.id as country_id, c.country_code, c.country_name from " . $obj_purchase->getTableName('supplier') . " as su left join " . $obj_purchase->getTableName('state') . " as s on su.state = s.state_id left join " . $obj_purchase->getTableName('country') . " as c on su.country = c.id where 1=1 AND su.is_deleted='0' AND su.status = '1' AND su.name LIKE '%".trim($_GET['term'])."%' AND su.added_by = '".$obj_purchase->sanitize($_SESSION['user_detail']['user_id'])."'");

	if(count($clientGetSuppliers) > 0) {

		foreach($clientGetSuppliers as $clientGetSupplier) {

			$supplier[$counter]['supplier_id'] = $clientGetSupplier->supplier_id;
			$supplier[$counter]['label'] = html_entity_decode($clientGetSupplier->name);
			$supplier[$counter]['value'] = html_entity_decode($clientGetSupplier->name);
			$supplier[$counter]['company_name'] = html_entity_decode($clientGetSupplier->company_name);
			$supplier[$counter]['email'] = $clientGetSupplier->email;

			$fullAddress =  $clientGetSupplier->address . ", " . $clientGetSupplier->city . ", " . $clientGetSupplier->state_name . ", " . $clientGetSupplier->zipcode . ", " . $clientGetSupplier->country_name;
			$supplier[$counter]['address'] = html_entity_decode($fullAddress);

			$supplier[$counter]['city'] = html_entity_decode($clientGetSupplier->city);
			$supplier[$counter]['state_id'] = $clientGetSupplier->state_id;
			$supplier[$counter]['state_name'] = html_entity_decode($clientGetSupplier->state_name);
			$supplier[$counter]['state_code'] = $clientGetSupplier->state_code;
			$supplier[$counter]['state_tin'] = $clientGetSupplier->state_tin;
			$supplier[$counter]['zipcode'] = $clientGetSupplier->zipcode;
			$supplier[$counter]['phone'] = $clientGetSupplier->phone;
			$supplier[$counter]['fax'] = $clientGetSupplier->fax;
			$supplier[$counter]['pannumber'] = $clientGetSupplier->pannumber;
			$supplier[$counter]['gstid'] = $clientGetSupplier->gstid;
			$supplier[$counter]['website'] = $clientGetSupplier->website;
			$supplier[$counter]['remarks'] = html_entity_decode($clientGetSupplier->remarks);
			$supplier[$counter]['vendor_type'] = $clientGetSupplier->vendor_type;
			$supplier[$counter]['country_id'] = $clientGetSupplier->country_id;
			$supplier[$counter]['country_code'] = $clientGetSupplier->country_code;
			$supplier[$counter]['country_name'] = html_entity_decode($clientGetSupplier->country_name);
			$counter++;
		}
	}
}

header('Content-type: application/json');
echo json_encode($supplier);
die;
?>