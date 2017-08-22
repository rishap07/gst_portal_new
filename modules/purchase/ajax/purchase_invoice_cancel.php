<?php
/*
    * 
    *  Developed By        :   Ishwar Lal Ghiya
    *  Date Created        :   July 17, 2017
    *  Last Modification   :   Cancel/Revoke Invoice
    * 
*/
header('Content-type: application/json');
$obj_purchase = new purchase();
$supplier = array();
$counter = 0;
if(isset($_GET['ajax']) && $_GET['ajax'] == "purchase_invoice_cancel" && $_POST['action'] == "cancelPurchaseInvoice") {

	$dataConditionArray['purchase_invoice_id'] = $_POST['purchaseInvoiceId'];
	$dataArr['is_canceled'] = 1;

	if ($obj_purchase->update($obj_purchase->getTableName('client_purchase_invoice'), $dataArr, $dataConditionArray)) {

		$obj_purchase->logMsg("Purchase Invoice Cancelled. ID : " . $dataConditionArray['purchase_invoice_id'] . ".");
		$result['status'] = "success";
		$result['message'] = "Invoice cancelled successfully.";
		echo json_encode($result);
		die;
	} else {

		$result['status'] = "error";
		$result['message'] = $obj_purchase->getValMsg('failed');
		echo json_encode($result);
		die;
	}
}

if(isset($_GET['ajax']) && $_GET['ajax'] == "purchase_invoice_cancel" && $_POST['action'] == "revokePurchaseInvoice") {

	$dataConditionArray['purchase_invoice_id'] = $_POST['purchaseInvoiceId'];
	$dataArr['is_canceled'] = 0;

	if ($obj_purchase->update($obj_purchase->getTableName('client_purchase_invoice'), $dataArr, $dataConditionArray)) {

		$obj_purchase->logMsg("Purchase Invoice Revoked. ID : " . $dataConditionArray['purchase_invoice_id'] . ".");
		$result['status'] = "success";
		$result['message'] = "Invoice revoked successfully.";
		echo json_encode($result);
		die;
	} else {

		$result['status'] = "error";
		$result['message'] = $obj_purchase->getValMsg('failed');
		echo json_encode($result);
		die;
	}
}

if(isset($_GET['ajax']) && $_GET['ajax'] == "purchase_invoice_cancel" && $_POST['action'] == "cancelSelectedPurchaseInvoice") {

	foreach($_POST['purchaseInvoiceIds'] as $purchaseInvoiceId) {

		$dataConditionArray['purchase_invoice_id'] = $purchaseInvoiceId;
		$dataArr['is_canceled'] = 1;

		if ($obj_purchase->update($obj_purchase->getTableName('client_purchase_invoice'), $dataArr, $dataConditionArray)) {

			$obj_purchase->logMsg("Purchase Invoice Cancelled. ID : " . $dataConditionArray['purchase_invoice_id'] . ".");
		} else {

			$result['status'] = "error";
			$result['message'] = $obj_purchase->getValMsg('failed');
			echo json_encode($result);
			die;
		}
	}

	$result['status'] = "success";
	$obj_purchase->setSuccess("Invoice cancelled successfully.");
	echo json_encode($result);
	die;
}

if(isset($_GET['ajax']) && $_GET['ajax'] == "purchase_invoice_cancel" && $_POST['action'] == "revokeSelectedPurchaseInvoice") {

	foreach($_POST['purchaseInvoiceIds'] as $purchaseInvoiceId) {

		$dataConditionArray['purchase_invoice_id'] = $purchaseInvoiceId;
		$dataArr['is_canceled'] = 0;

		if ($obj_purchase->update($obj_purchase->getTableName('client_purchase_invoice'), $dataArr, $dataConditionArray)) {

			$obj_purchase->logMsg("Purchase Invoice Revoked. ID : " . $dataConditionArray['purchase_invoice_id'] . ".");
		} else {

			$result['status'] = "error";
			$result['message'] = $obj_purchase->getValMsg('failed');
			echo json_encode($result);
			die;
		}
	}

	$result['status'] = "success";
	$obj_purchase->setSuccess("Invoice revoked successfully.");
	echo json_encode($result);
	die;
}
?>