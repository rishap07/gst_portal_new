<?php
/*
    * 
    *  Developed By        :   Ishwar Lal Ghiya
    *  Date Created        :   July 17, 2017
    *  Last Modification   :   Cancel/Revoke Invoice
    * 
*/
header('Content-type: application/json');
$obj_client = new client();
$supplier = array();
$counter = 0;
if(isset($_GET['ajax']) && $_GET['ajax'] == "client_invoice_cancel" && $_POST['action'] == "cancelSalesInvoice") {

	$dataConditionArray['invoice_id'] = $_POST['salesInvoiceId'];
	$dataArr['is_canceled'] = 1;

	if ($obj_client->update($obj_client->getTableName('client_invoice'), $dataArr, $dataConditionArray)) {

		$obj_client->logMsg("Sales Invoice Cancelled. ID : " . $dataConditionArray['invoice_id'] . ".");
		$result['status'] = "success";
		$result['message'] = "Invoice cancelled successfully.";
		echo json_encode($result);
		die;
	} else {

		$result['status'] = "error";
		$result['message'] = $obj_client->getValMsg('failed');
		echo json_encode($result);
		die;
	}
}

if(isset($_GET['ajax']) && $_GET['ajax'] == "client_invoice_cancel" && $_POST['action'] == "revokeSalesInvoice") {

	$dataConditionArray['invoice_id'] = $_POST['salesInvoiceId'];
	$dataArr['is_canceled'] = 0;

	if ($obj_client->update($obj_client->getTableName('client_invoice'), $dataArr, $dataConditionArray)) {

		$obj_client->logMsg("Sales Invoice Revoked. ID : " . $dataConditionArray['invoice_id'] . ".");
		$result['status'] = "success";
		$result['message'] = "Invoice revoked successfully.";
		echo json_encode($result);
		die;
	} else {

		$result['status'] = "error";
		$result['message'] = $obj_client->getValMsg('failed');
		echo json_encode($result);
		die;
	}
}

if(isset($_GET['ajax']) && $_GET['ajax'] == "client_invoice_cancel" && $_POST['action'] == "cancelSelectedSalesInvoice") {

	foreach($_POST['salesInvoiceIds'] as $salesInvoiceId) {

		$dataConditionArray['invoice_id'] = $salesInvoiceId;
		$dataArr['is_canceled'] = 1;

		if ($obj_client->update($obj_client->getTableName('client_invoice'), $dataArr, $dataConditionArray)) {

			$obj_client->logMsg("Sales Invoice Cancelled. ID : " . $dataConditionArray['invoice_id'] . ".");
		} else {

			$result['status'] = "error";
			$result['message'] = $obj_client->getValMsg('failed');
			echo json_encode($result);
			die;
		}
	}

	$result['status'] = "success";
	$obj_client->setSuccess("Invoice cancelled successfully.");
	echo json_encode($result);
	die;
}
?>