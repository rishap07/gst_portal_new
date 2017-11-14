<?php
/*
    * 
    *  Developed By        :   Ishwar Lal Ghiya
    *  Date Created        :   November 04, 2017
    *  Last Modification   :   Save ITC Row Data
    * 
*/
header('Content-type: application/json');
$obj_gstr2 = new gstr2();
if(isset($_GET['ajax']) && $_GET['ajax'] == "return_gstr2_save_claim_itc" && $_POST['action'] == "saveRowITCData") {

	$currentRowId = $_POST['currentRowId'];
	$dataArr['eligibility'] = $_POST['currentRowITCType'];

	if($dataArr['eligibility'] == "no") {
		$dataArr['total_itc_cgst_amount'] = "0.00";
		$dataArr['total_itc_sgst_amount'] = "0.00";
		$dataArr['total_itc_igst_amount'] = "0.00";
		$dataArr['total_itc_cess_amount'] = "0.00";
	} else {
		$dataArr['total_itc_cgst_amount'] = ((isset($_POST['currentRowITCCGST'])) && (!empty($_POST['currentRowITCCGST']))) ? $_POST['currentRowITCCGST'] : "0.00";
		$dataArr['total_itc_sgst_amount'] = ((isset($_POST['currentRowITCSGST'])) && (!empty($_POST['currentRowITCSGST']))) ? $_POST['currentRowITCSGST'] : "0.00";
		$dataArr['total_itc_igst_amount'] = ((isset($_POST['currentRowITCIGST'])) && (!empty($_POST['currentRowITCIGST']))) ? $_POST['currentRowITCIGST'] : "0.00";
		$dataArr['total_itc_cess_amount'] = ((isset($_POST['currentRowITCCESS'])) && (!empty($_POST['currentRowITCCESS']))) ? $_POST['currentRowITCCESS'] : "0.00";
	}

	$dataConditionArray['id'] = $_POST['currentRowId'];
	$dataConditionArray['added_by'] = $_SESSION['user_detail']['user_id'];

	if ($obj_gstr2->update($obj_gstr2->getTableName('gstr2_reconcile_final'), $dataArr, $dataConditionArray)) {

		$obj_gstr2->logMsg("ITC data saved. ID : " . $dataConditionArray['id'] . ".");
		$result['total_itc_cgst_amount'] = $dataArr['total_itc_cgst_amount'];
		$result['total_itc_sgst_amount'] = $dataArr['total_itc_sgst_amount'];
		$result['total_itc_igst_amount'] = $dataArr['total_itc_igst_amount'];
		$result['total_itc_cess_amount'] = $dataArr['total_itc_cess_amount'];
		$result['status'] = "success";
		$result['message'] = "ITC data saved successfully.";
		echo json_encode($result);
		die;
	} else {

		$result['status'] = "error";
		$result['message'] = $obj_gstr2->getValMsg('failed');
		echo json_encode($result);
		die;
	}
}

if(isset($_GET['ajax']) && $_GET['ajax'] == "return_gstr2_save_claim_itc" && $_POST['action'] == "saveallRowITCData") {

	$invoiceITCArray = array();
	$allITCData = $_POST['allITCData'];
	if(count($allITCData) > 0) {

		foreach($allITCData as $itcData) {

			$dataArray = array();
			$dataArray['eligibility'] = $itcData['currentRowITCType'];
			if($dataArray['eligibility'] == "no") {
				$dataArray['total_itc_cgst_amount'] = "0.00";
				$dataArray['total_itc_sgst_amount'] = "0.00";
				$dataArray['total_itc_igst_amount'] = "0.00";
				$dataArray['total_itc_cess_amount'] = "0.00";
			} else {
				$dataArray['total_itc_cgst_amount'] = ((isset($itcData['currentRowITCCGST'])) && (!empty($itcData['currentRowITCCGST']))) ? $itcData['currentRowITCCGST'] : "0.00";
				$dataArray['total_itc_sgst_amount'] = ((isset($itcData['currentRowITCSGST'])) && (!empty($itcData['currentRowITCSGST']))) ? $itcData['currentRowITCSGST'] : "0.00";
				$dataArray['total_itc_igst_amount'] = ((isset($itcData['currentRowITCIGST'])) && (!empty($itcData['currentRowITCIGST']))) ? $itcData['currentRowITCIGST'] : "0.00";
				$dataArray['total_itc_cess_amount'] = ((isset($itcData['currentRowITCCESS'])) && (!empty($itcData['currentRowITCCESS']))) ? $itcData['currentRowITCCESS'] : "0.00";
			}

			$dataConditionArray['id'] = $itcData['currentRowId'];
			$dataConditionArray['added_by'] = $_SESSION['user_detail']['user_id'];

			array_push($invoiceITCArray, array('set' => $dataArray, 'where' => $dataConditionArray));
		}

		if ($obj_gstr2->updateMultiple($obj_gstr2->getTableName('gstr2_reconcile_final'), $invoiceITCArray)) {

			$obj_gstr2->logMsg("ITC multiple data saved.", "gstr2_reconcile_final");
			$result['status'] = "success";
			$obj_gstr2->setSuccess("ITC multiple data saved.", "gstr2_reconcile_final");
			echo json_encode($result);
			die;
		} else {

			$result['status'] = "error";
			$result['message'] = $obj_gstr2->getValMsg('failed');
			echo json_encode($result);
			die;
		}
	}
}
?>