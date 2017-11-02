<?php
/*
    * 
    *  Developed By        :   Ishwar Lal Ghiya
    *  Date Created        :   October 25, 2017
    *  Last Modification   :   Update Reconcile Status
    * 
*/
header('Content-type: application/json');
$obj_json = new json();
$result = array();
if(isset($_POST['reconcileId']) && isset($_POST['reconcileStatus']) && isset($_POST['action']) && $_POST['action'] == "updateReconcile" && isset($_GET['ajax']) && $_GET['ajax'] == "return_gstr2_update_reconcile") {

	$reconcileId = $_POST['reconcileId'];
	$reconcileStatus = $_POST['reconcileStatus'];

	$dataArr['reconciliation_status'] = $reconcileStatus;
	$dataArr['updated_by'] = $obj_json->sanitize($_SESSION['user_detail']['user_id']);
	$dataArr['updated_date'] = date('Y-m-d H:i:s');
	$dataConditionArray['id'] = $reconcileId;
	$dataConditionArray['added_by'] = $obj_json->sanitize($_SESSION['user_detail']['user_id']);

	if ($obj_json->update($obj_json->getTableName('gstr2_reconcile_final'), $dataArr, $dataConditionArray)) {

		$obj_json->logMsg("Reconcile Status Updated. ID : " . $reconcileId . ".");
		$result['message'] = '<div id="sucmsg" style="background-color:#DBEDDF;border-radius:4px;padding:8px 35px 8px 14px;text-shadow:0 1px 0 rgba(255, 255, 255, 0.5);margin-bottom:18px;border-color:#D1E8DA;color:#39A25F;"><i class="fa fa-check"></i> <b>Success:</b>Reconcile Status Updated Successfully.</div>';
		$result['status'] = "success";
		echo json_encode($result);
		die;
	} else {

		$obj_json->setError($obj_json->getValMsg('failed'));
		$result['status'] = "error";
		$result['message'] = $obj_json->getErrorMessage();
		$obj_json->unsetMessage();
		echo json_encode($result);
		die;
	}
}
?>