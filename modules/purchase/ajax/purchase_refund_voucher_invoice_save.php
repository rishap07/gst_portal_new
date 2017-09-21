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
if(isset($_POST['invoiceData']) && isset($_POST['action']) && $_POST['action'] == "saveNewPurchaseRFInvoice" && isset($_GET['ajax']) && $_GET['ajax'] == "purchase_refund_voucher_invoice_save") {

	/* get current user data */
	$dataCurrentUserArr = $obj_purchase->getUserDetailsById( $obj_purchase->sanitize($_SESSION['user_detail']['user_id']) );
	$currentFinancialYear = $obj_purchase->generateFinancialYear();

	$params = array();
	parse_str($_POST['invoiceData'], $params);

	if(empty($params)) {
		$result['status'] = "error";
		$result['message'] = "<div style='color:#f00;background-color:#eddbe3;border-radius:4px;padding:8px 35px 8px 14px;text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);margin-bottom:18px;border-color:#e8d1df;color:#bd4247;'><i class='fa fa-exclamation-triangle'></i>&nbsp;1.&nbsp;".$obj_purchase->getValMsg('mandatory')."</div>";
		echo json_encode($result);
		die;
	}

	$dataArr['invoice_type'] = 'refundvoucherinvoice';
	$dataArr['invoice_nature'] = 'purchaseinvoice';
	$dataArr['invoice_date'] = isset($params['invoice_date']) ? $params['invoice_date'] : '';
	$dataArr['reference_number'] = isset($params['invoice_reference_number']) ? $params['invoice_reference_number'] : '';
	$dataArr['company_name'] = $dataCurrentUserArr['data']->kyc->name;
	$dataArr['company_address'] = $dataCurrentUserArr['data']->kyc->full_address;
	$dataArr['company_email'] = $dataCurrentUserArr['data']->kyc->email;
	$dataArr['company_phone_number'] = $dataCurrentUserArr['data']->kyc->phone_number;
	$dataArr['company_state'] = $dataCurrentUserArr['data']->kyc->state_id;
	$dataArr['company_gstin_number'] = $dataCurrentUserArr['data']->kyc->gstin_number;
	$dataArr['is_tax_payable'] = isset($params['tax_reverse_charge']) ? $params['tax_reverse_charge'] : '';
	$dataArr['description'] = isset($params['description']) ? trim($params['description']) : '';
	$dataArr['refund_voucher_receipt'] = isset($params['receipt_voucher_number']) ? $params['receipt_voucher_number'] : '';
	$dataArr['corresponding_document_date'] = isset($params['receipt_voucher_date']) ? $params['receipt_voucher_date'] : '';

	if($dataArr['is_tax_payable'] == "1") {
		$dataArr['supply_type'] = "reversecharge";
	}

	$supply_place = isset($params['place_of_supply']) ? $params['place_of_supply'] : '';
	$supply_state_data = $obj_purchase->getStateDetailByStateId($supply_place);

	if($supply_state_data['status'] === "success") {
		$dataArr['supply_place'] = $supply_state_data['data']->state_id;
	} else {
		$dataArr['supply_place'] = '';
	}
	
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

	/* check reference number */
	$referenceStatus = $obj_purchase->checkPurchaseReferenceNumberExist($dataArr['reference_number'], $obj_purchase->sanitize($_SESSION['user_detail']['user_id']));
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
	$consolidateRate = 0.00;
	if(isset($params['invoice_itemid']) && count($params['invoice_itemid']) > 0) {

		$invoiceitems = count($params['invoice_itemid']);
		for($i=0; $i < $invoiceitems; $i++) {

			$dataInvoiceArr = array();
			$dataInvoiceArr['invoice_itemid'] = isset($params['invoice_itemid'][$i]) ? $params['invoice_itemid'][$i] : '';
			$dataInvoiceArr['invoice_description'] = isset($params['invoice_description'][$i]) ? $params['invoice_description'][$i] : '';
			$dataInvoiceArr['invoice_receiptvalue'] = isset($params['invoice_receiptvalue'][$i]) ? $params['invoice_receiptvalue'][$i] : 0.00;
			$dataInvoiceArr['invoice_taxablevalue'] = isset($params['invoice_taxablevalue'][$i]) ? $params['invoice_taxablevalue'][$i] : 0.00;
			$dataInvoiceArr['invoice_cgstrate'] = isset($params['invoice_cgstrate'][$i]) ? $params['invoice_cgstrate'][$i] : 0.00;
			$dataInvoiceArr['invoice_sgstrate'] = isset($params['invoice_sgstrate'][$i]) ? $params['invoice_sgstrate'][$i] : 0.00;
			$dataInvoiceArr['invoice_igstrate'] = isset($params['invoice_igstrate'][$i]) ? $params['invoice_igstrate'][$i] : 0.00;
			$dataInvoiceArr['invoice_cessrate'] = isset($params['invoice_cessrate'][$i]) ? $params['invoice_cessrate'][$i] : 0.00;

			/* validate invoice data item */
			$invoiceItemErrors = $obj_purchase->validateClientPurchaseInvoiceItem($dataInvoiceArr, ($i+1));
			if ($invoiceItemErrors !== true) {
				$invoiceErrorMessage = array_merge($invoiceItemErrors, $invoiceErrorMessage);
			}
			
			$clientMasterItem = $obj_purchase->get_row("select cm.item_id, cm.item_name, cm.is_applicable, cm.unit_price, cm.item_category, m.item_id as category_id, m.item_name as category_name, m.hsn_code, m.igst_tax_rate, m.csgt_tax_rate, m.sgst_tax_rate, m.cess_tax_rate, cm.item_unit, u.unit_id, u.unit_name, u.unit_code from " . $obj_purchase->getTableName('client_master_item') . " as cm, " . $obj_purchase->getTableName('item') . " as m, " . $obj_purchase->getTableName('unit') . " as u where 1=1 AND cm.item_category = m.item_id AND cm.item_unit = u.unit_id AND cm.item_id = ".$dataInvoiceArr['invoice_itemid']." AND cm.is_deleted='0' AND cm.status = '1' AND cm.added_by = '".$obj_purchase->sanitize($_SESSION['user_detail']['user_id'])."'");
			if (!empty($clientMasterItem)) {

				$invoiceItemTaxableAmount = (float)$dataInvoiceArr['invoice_taxablevalue'];
				$invoiceItemReceiptAmount = (float)$dataInvoiceArr['invoice_receiptvalue'];

				if($dataArr['supplier_billing_state'] === $dataArr['supply_place']) {

					$itemCSGTTax = (float)$dataInvoiceArr['invoice_cgstrate'];
					$itemSGSTTax = (float)$dataInvoiceArr['invoice_sgstrate'];
					$itemIGSTTax = 0.00;
					$itemCESSTax = (float)$dataInvoiceArr['invoice_cessrate'];
					$consolidateRate = $itemCSGTTax + $itemSGSTTax;

					$invoiceItemCSGTTaxAmount = ($itemCSGTTax/100) * $invoiceItemTaxableAmount;
					$invoiceItemSGSTTaxAmount = ($itemSGSTTax/100) * $invoiceItemTaxableAmount;
					$invoiceItemIGSTTaxAmount = 0.00;
					$invoiceItemCESSTaxAmount = ($itemCESSTax/100) * $invoiceItemTaxableAmount;
					
					if($itemCSGTTax != $itemSGSTTax) {
						array_push($invoiceErrorMessage, "CGST and SGST rate should be same for item number " . ($i+1) . ".");
					}
				} else {

					$itemCSGTTax = 0.00;
					$itemSGSTTax = 0.00;
					$itemIGSTTax = (float)$dataInvoiceArr['invoice_igstrate'];
					$itemCESSTax = (float)$dataInvoiceArr['invoice_cessrate'];
					$consolidateRate = $itemIGSTTax;

					$invoiceItemCSGTTaxAmount = 0.00;
					$invoiceItemSGSTTaxAmount = 0.00;
					$invoiceItemIGSTTaxAmount = ($itemIGSTTax/100) * $invoiceItemTaxableAmount;
					$invoiceItemCESSTaxAmount = ($itemCESSTax/100) * $invoiceItemTaxableAmount;
				}

				if($dataArr['is_tax_payable'] == "1") {

					$invoiceItemTotalAmount = $invoiceItemTaxableAmount;
					$invoiceTotalAmount += $invoiceItemTotalAmount;
				} else {

					$invoiceItemTotalAmount = ($invoiceItemTaxableAmount + $invoiceItemCSGTTaxAmount + $invoiceItemSGSTTaxAmount + $invoiceItemIGSTTaxAmount + $invoiceItemCESSTaxAmount);
					$invoiceTotalAmount += $invoiceItemTotalAmount;
				}

				$ItemArray = array(
								"item_id" => $clientMasterItem->item_id,
								"item_name" => $clientMasterItem->item_name,
								"item_description" => $dataInvoiceArr['invoice_description'],
								"item_hsncode" => $clientMasterItem->hsn_code,
								"is_applicable" => $clientMasterItem->is_applicable,
								"advance_amount" => round($invoiceItemReceiptAmount, 2),
								"taxable_subtotal" => round($invoiceItemTaxableAmount, 2),
								"cgst_rate" => $itemCSGTTax,
								"cgst_amount" => round($invoiceItemCSGTTaxAmount, 2),
								"sgst_rate" => $itemSGSTTax,
								"sgst_amount" => round($invoiceItemSGSTTaxAmount, 2),
								"igst_rate" => $itemIGSTTax,
								"igst_amount" => round($invoiceItemIGSTTaxAmount, 2),
								"cess_rate" => $itemCESSTax,
								"cess_amount" => round($invoiceItemCESSTaxAmount, 2),
								"consolidate_rate" => $consolidateRate,
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
	$dataArr['financial_year'] = $obj_purchase->generateFinancialYear();
	$dataArr['status'] = 1;
	$dataArr['added_by'] = $obj_purchase->sanitize($_SESSION['user_detail']['user_id']);
	$dataArr['added_date'] = date('Y-m-d H:i:s');

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

			$dataArr['serial_number'] = $obj_purchase->generatePurchaseRFInvoiceNumber($obj_purchase->sanitize($_SESSION['user_detail']['user_id']));

			if ($obj_purchase->insert($obj_purchase->getTableName('client_purchase_invoice'), $dataArr)) {

				$insertid = $obj_purchase->getInsertID();
				$obj_purchase->logMsg("Purchase Refund Voucher Invoice Added. ID : " . $insertid . ".");

				$processedInvoiceItemArray = array();
				foreach($invoiceItemArray as $itemArr) {

					$itemArr['purchase_invoice_id'] = $insertid;
					array_push($processedInvoiceItemArray, $itemArr);
				}

				if ($obj_purchase->insertMultiple($obj_purchase->getTableName('client_purchase_invoice_item'), $processedInvoiceItemArray)) {

					$obj_purchase->setSuccess($obj_purchase->getValMsg('invoiceadded'));
					$iteminsertid = $obj_purchase->getInsertID();
					$obj_purchase->logMsg("Purchase Refund Voucher Invoice Item Added. ID : " . $iteminsertid . ".");
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