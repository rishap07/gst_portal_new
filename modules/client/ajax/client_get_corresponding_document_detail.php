<?php
/*
    * 
    *  Developed By        :   Ishwar Lal Ghiya
    *  Date Created        :   August 01, 2017
    *  Last Modification   :   Get corresponding document detail
    * 
*/

$obj_client = new client();
$result = array();
if(isset($_POST['correspondingType']) && isset($_POST['action']) && $_POST['action'] == "getCorrespondingType") {

	$correspondingType = $_POST['correspondingType'];
	$currentFinancialYear = $obj_client->generateFinancialYear();

	$correspondingTypeData = $obj_client->get_results("select 
													invoice_id, 
													serial_number, 
													reference_number, 
													invoice_type, 
													invoice_date 
													from " . $obj_client->getTableName('client_invoice') . " 
													where 1=1 AND invoice_type = '".$correspondingType."' AND is_canceled='0' AND is_deleted = '0' AND status = '1' AND financial_year = '".$currentFinancialYear."' AND added_by = ".$obj_client->sanitize($_SESSION['user_detail']['user_id'])." order by serial_number ASC");

	if(count($correspondingTypeData) > 0) {

		$correspondingTypeItem = '';
		$correspondingTypeItem .= '<option value="">Select Document Number</option>';

		foreach($correspondingTypeData as $correspondingType) {
			$correspondingTypeItem .= '<option value="'. $correspondingType->invoice_id .'" data-reference="'. $correspondingType->reference_number .'" data-date="'. $correspondingType->invoice_date .'">'. $correspondingType->serial_number .'</option>';
		}

		$result['message'] = $correspondingTypeItem;
		$result['status'] = "success";
	} else {
		$result['message'] = "No Document Found";
		$result['status'] = "error";
	}
}

echo json_encode($result);
die;
?>