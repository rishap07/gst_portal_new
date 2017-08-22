<?php
/*
    * 
    *  Developed By        :   Ishwar Lal Ghiya
    *  Date Created        :   June 02, 2017
    *  Last Modification   :   save new invoice
    * 
*/
header('Content-type: application/json');
$obj_purchase = new purchase();
$result = array();
$invoiceErrorMessage = array();
$invoiceErrorMessageContent = '';
$counter = 0;
$errorcounter = 1;
if(isset($_POST['invoiceData']) && isset($_POST['action']) && $_POST['action'] == "saveUpdatePurchaseBillInvoice" && isset($_GET['ajax']) && $_GET['ajax'] == "purchase_bill_of_supply_invoice_save_update") {
	
	/* get current user data */
	$dataCurrentUserArr = $obj_purchase->getUserDetailsById( $obj_purchase->sanitize($_SESSION['user_detail']['user_id']) );

	$params = array();
	parse_str($_POST['invoiceData'], $params);

	if (empty($params)) {
		$result['status'] = "error";
		$result['message'] = "<div style='color:#f00;background-color:#eddbe3;border-radius:4px;padding:8px 35px 8px 14px;text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);margin-bottom:18px;border-color:#e8d1df;color:#bd4247;'><i class='fa fa-exclamation-triangle'></i>&nbsp;1.&nbsp;".$obj_purchase->getValMsg('mandatory')."</div>";
		echo json_encode($result);
		die;
	}

	$dataArr['invoice_type'] = 'billofsupplyinvoice';
	$dataArr['invoice_nature'] = 'purchaseinvoice';	
	$dataArr['invoice_date'] = isset($params['invoice_date']) ? $params['invoice_date'] : '';
	$dataArr['reference_number'] = isset($params['invoice_reference_number']) ? $params['invoice_reference_number'] : '';
	$dataArr['description'] = isset($params['description']) ? trim($params['description']) : '';
	
	$dataArr['supplier_billing_name'] = isset($params['supplier_billing_name']) ? $params['supplier_billing_name'] : '';
	$dataArr['supplier_billing_company_name'] = isset($params['supplier_billing_company_name']) ? $params['supplier_billing_company_name'] : '';
	$dataArr['supplier_billing_address'] = isset($params['supplier_billing_address']) ? $params['supplier_billing_address'] : '';
	$dataArr['supplier_billing_vendor_type'] = isset($params['supplier_billing_vendor_type']) ? $params['supplier_billing_vendor_type'] : '';
	$dataArr['supplier_billing_gstin_number'] = isset($params['supplier_billing_gstin_number']) ? $params['supplier_billing_gstin_number'] : '';
	
	$supplier_billing_state_code = isset($params['supplier_billing_state_code']) ? $params['supplier_billing_state_code'] : '';
	$supplier_billing_state_data = $obj_purchase->getStateDetailByStateCode($supplier_billing_state_code);

	if($supplier_billing_state_data['status'] === "success") {
		$dataArr['supplier_billing_state'] = $supplier_billing_state_data['data']->state_id;
		$dataArr['supplier_billing_state_name'] = $supplier_billing_state_data['data']->state_name;
	} else {
		$dataArr['supplier_billing_state'] = '';
		$dataArr['supplier_billing_state_name'] = '';
	}

	$supplier_billing_country_code = isset($params['supplier_billing_country_code']) ? $params['supplier_billing_country_code'] : '';
	$supplier_billing_country_data = $obj_purchase->getCountryDetailByCountryCode($supplier_billing_country_code);

	if($supplier_billing_country_data['status'] === "success") {
		$dataArr['supplier_billing_country'] = $supplier_billing_country_data['data']->id;
	} else {
		$dataArr['supplier_billing_country'] = '';
	}

	if(isset($params['same_as_billing']) && $params['same_as_billing'] == 1) {

		$dataArr['same_as_billing'] = "1";
		$dataArr['recipient_shipping_name'] = $dataCurrentUserArr['data']->kyc->name;
		$dataArr['recipient_shipping_company_name'] = $dataCurrentUserArr['data']->kyc->name;
		$dataArr['recipient_shipping_address'] = $dataCurrentUserArr['data']->kyc->full_address;
		$dataArr['recipient_shipping_state'] = $dataCurrentUserArr['data']->kyc->state_id;
		$dataArr['recipient_shipping_state_name'] = $dataCurrentUserArr['data']->kyc->state_name;
		$dataArr['recipient_shipping_country'] = $dataCurrentUserArr['data']->kyc->country_id;
		$dataArr['recipient_shipping_vendor_type'] = $dataCurrentUserArr['data']->kyc->vendor_type;
		$dataArr['recipient_shipping_gstin_number'] = $dataCurrentUserArr['data']->kyc->gstin_number;
	} else {
		
		$dataArr['recipient_shipping_name'] = isset($params['recipient_shipping_name']) ? $params['recipient_shipping_name'] : '';
		$dataArr['recipient_shipping_company_name'] = isset($params['recipient_shipping_company_name']) ? $params['recipient_shipping_company_name'] : '';
		$dataArr['recipient_shipping_address'] = isset($params['recipient_shipping_address']) ? $params['recipient_shipping_address'] : '';
		$dataArr['recipient_shipping_vendor_type'] = isset($params['recipient_shipping_vendor_type']) ? $params['recipient_shipping_vendor_type'] : '';
		$dataArr['recipient_shipping_gstin_number'] = isset($params['recipient_shipping_gstin_number']) ? $params['recipient_shipping_gstin_number'] : '';

		$recipient_shipping_state_code = isset($params['recipient_shipping_state_code']) ? $params['recipient_shipping_state_code'] : '';
		$recipient_shipping_state_data = $obj_purchase->getStateDetailByStateCode($recipient_shipping_state_code);

		if($recipient_shipping_state_data['status'] === "success") {
			$dataArr['recipient_shipping_state'] = $recipient_shipping_state_data['data']->state_id;
			$dataArr['recipient_shipping_state_name'] = $recipient_shipping_state_data['data']->state_name;
		} else {
			$dataArr['recipient_shipping_state'] = '';
			$dataArr['recipient_shipping_state_name'] = '';
		}

		$recipient_shipping_country_code = isset($params['recipient_shipping_country_code']) ? $params['recipient_shipping_country_code'] : '';
		$recipient_shipping_country_data = $obj_purchase->getCountryDetailByCountryCode($recipient_shipping_country_code);

		if($recipient_shipping_country_data['status'] === "success") {
			$dataArr['recipient_shipping_country'] = $recipient_shipping_country_data['data']->id;
		} else {
			$dataArr['recipient_shipping_country'] = '';
		}
	}
	
	/* check reference number */
	$referenceStatus = $obj_purchase->checkPurchaseReferenceNumberExist($dataArr['reference_number'], $obj_purchase->sanitize($_SESSION['user_detail']['user_id']), $obj_purchase->sanitize(base64_decode($params['purchase_invoice_id'])));
	if($referenceStatus == true) {
		array_push($invoiceErrorMessage, "You have already used this reference number.");
	}

	/* validate invoice data */
	$invoiceErrors = $obj_purchase->validateClientPurchaseInvoice($dataArr);
	if ($invoiceErrors !== true) {
		$invoiceErrorMessage = array_merge($invoiceErrors, $invoiceErrorMessage);
	}

	$invoiceItemArray = array();
	$invoiceTotalAmount = 0.00;
	if(isset($params['invoice_itemid']) && count($params['invoice_itemid']) > 0) {

		$invoiceitems = count($params['invoice_itemid']);
		for($i=0; $i < $invoiceitems; $i++) {

			$dataInvoiceArr = array();
			$dataInvoiceArr['invoice_itemid'] = isset($params['invoice_itemid'][$i]) ? $params['invoice_itemid'][$i] : '';
			$dataInvoiceArr['invoice_quantity'] = isset($params['invoice_quantity'][$i]) ? $params['invoice_quantity'][$i] : 0.00;
			$dataInvoiceArr['invoice_unit'] = isset($params['invoice_unit'][$i]) ? $params['invoice_unit'][$i] : '';
			$dataInvoiceArr['invoice_discount'] = isset($params['invoice_discount'][$i]) ? $params['invoice_discount'][$i] : 0.00;
			$dataInvoiceArr['invoice_rate'] = isset($params['invoice_rate'][$i]) ? $params['invoice_rate'][$i] : 0.00;

			/* validate invoice data item */
			$invoiceItemErrors = $obj_purchase->validateClientPurchaseInvoiceItem($dataInvoiceArr, ($i+1));
			if ($invoiceItemErrors !== true) {
				$invoiceErrorMessage = array_merge($invoiceItemErrors, $invoiceErrorMessage);
			}

			$clientMasterItem = $obj_purchase->get_row("select cm.item_id, cm.item_name, cm.unit_price, cm.item_category, m.item_id as category_id, m.item_name as category_name, m.hsn_code, m.igst_tax_rate, m.csgt_tax_rate, m.sgst_tax_rate, m.cess_tax_rate, cm.item_unit, u.unit_id, u.unit_name, u.unit_code from " . $obj_purchase->getTableName('client_master_item') . " as cm, " . $obj_purchase->getTableName('item') . " as m, " . $obj_purchase->getTableName('unit') . " as u where 1=1 AND cm.item_category = m.item_id AND cm.item_unit = u.unit_id AND cm.item_id = ".$dataInvoiceArr['invoice_itemid']." AND cm.is_deleted='0' AND cm.status = '1' AND cm.added_by = '".$obj_purchase->sanitize($_SESSION['user_detail']['user_id'])."'");
			if (!empty($clientMasterItem)) {

				$itemUnitPrice = (float)$dataInvoiceArr['invoice_rate'];
				$invoiceItemUnit = $dataInvoiceArr['invoice_unit'];
				$invoiceItemQuantity = (float)$dataInvoiceArr['invoice_quantity'];
				$invoiceItemDiscount = (float)$dataInvoiceArr['invoice_discount'];

				$invoiceItemTotal = $invoiceItemQuantity * $itemUnitPrice;
				$invoiceItemDiscountAmount = ($invoiceItemDiscount/100) * $invoiceItemTotal;
				$invoiceItemTaxableAmount = $invoiceItemTotal - $invoiceItemDiscountAmount;

				$invoiceItemTotalAmount = $invoiceItemTaxableAmount;
				$invoiceTotalAmount += $invoiceItemTotalAmount;

				$ItemArray = array(
								"item_id" => $clientMasterItem->item_id,
								"item_name" => $clientMasterItem->item_name,
								"item_hsncode" => $clientMasterItem->hsn_code,
								"item_quantity" => $invoiceItemQuantity,
								"item_unit" => $invoiceItemUnit,
								"item_unit_price" => $itemUnitPrice,
								"subtotal" => round($invoiceItemTotal, 2),
								"discount" => $invoiceItemDiscount,
								"taxable_subtotal" => round($invoiceItemTaxableAmount, 2),
								"total" => round($invoiceItemTotalAmount, 2),
								"status" => 1,
								"added_by" => $obj_purchase->sanitize($_SESSION['user_detail']['user_id']),
								"added_date" => date('Y-m-d H:i:s')
							);

				array_push($invoiceItemArray,$ItemArray);
			}
		}
	}

	$dataArr['invoice_total_value'] = number_format($invoiceTotalAmount, 2, '.', '');
	$dataArr['status'] = 1;
	$dataArr['updated_by'] = $obj_purchase->sanitize($_SESSION['user_detail']['user_id']);
	$dataArr['updated_date'] = date('Y-m-d H:i:s');
	$dataConditionArray['purchase_invoice_id'] = $obj_purchase->sanitize(base64_decode($params['purchase_invoice_id']));
	
	if(!empty($invoiceErrorMessage) && count($invoiceErrorMessage) > 0) {

		$invoiceErrorMessage = array_reverse($invoiceErrorMessage);
		$invoiceErrorMessageContent .= "<div style='color:#f00;background-color:#eddbe3;border-radius:4px;padding:8px 35px 8px 14px;text-shadow:0 1px 0 rgba(255, 255, 255, 0.5);margin-bottom:18px;border-color:#e8d1df;color:#bd4247;'>";
		foreach($invoiceErrorMessage as $errorMessage) {
			$invoiceErrorMessageContent .= "<i class='fa fa-exclamation-triangle'></i>&nbsp;" . $errorcounter . ".&nbsp;" . $errorMessage . "<br>";
			$errorcounter++;
		}
		$invoiceErrorMessageContent .= "</div>";
	}

	if($invoiceErrorMessageContent != '') {

		$result['status'] = "error";
		$result['message'] = $invoiceErrorMessageContent;
		$obj_purchase->unsetMessage();
		echo json_encode($result);
		die;
	} else {

		if( !empty($invoiceItemArray) && count($invoiceItemArray) > 0 ) {

			if ($obj_purchase->update($obj_purchase->getTableName('client_purchase_invoice'), $dataArr, $dataConditionArray)) {

				$updatedid = $obj_purchase->sanitize(base64_decode($params['purchase_invoice_id']));
				$obj_purchase->logMsg("Purchase Bill of Supply Invoice Updated. ID : " . $updatedid . ".");

				$processedInvoiceItemArray = array();
				foreach($invoiceItemArray as $itemArr) {

					$itemArr['purchase_invoice_id'] = $updatedid;
					array_push($processedInvoiceItemArray, $itemArr);
				}

				if($obj_purchase->deletData($obj_purchase->getTableName('client_purchase_invoice_item'), $dataConditionArray)) {

					if ($obj_purchase->insertMultiple($obj_purchase->getTableName('client_purchase_invoice_item'), $processedInvoiceItemArray)) {

						$obj_purchase->setSuccess($obj_purchase->getValMsg('invoiceadded'));
						$iteminsertid = $obj_purchase->getInsertID();
						$obj_purchase->logMsg("Purchase Bill of Supply Invoice Item Added. ID : " . $iteminsertid . ".");
						$result['status'] = "success";
						echo json_encode($result);
						die;
					} else {

						$obj_purchase->setError($obj_purchase->getValMsg('failed'));
						$result['status'] = "error";
						$result['message'] = $obj_purchase->getErrorMessage();
						$obj_purchase->unsetMessage();
						echo json_encode($result);
						die;
					}

				} else {
					
					$obj_purchase->setError($obj_purchase->getValMsg('failed'));
					$result['status'] = "error";
					$result['message'] = $obj_purchase->getErrorMessage();
					$obj_purchase->unsetMessage();
					echo json_encode($result);
					die;
				}

			} else {

				$obj_purchase->setError($obj_purchase->getValMsg('failed'));
				$result['status'] = "error";
				$result['message'] = $obj_purchase->getErrorMessage();
				$obj_purchase->unsetMessage();
				echo json_encode($result);
				die;
			}
		
		} else {

			$obj_purchase->setError($obj_purchase->getValMsg('noiteminvoice'));
			$result['status'] = "error";
			$result['message'] = $obj_purchase->getErrorMessage();
			$obj_purchase->unsetMessage();
			echo json_encode($result);
			die;
		}
	}
}
?>