<?php
/*
    * 
    *  Developed By        :   Ishwar Lal Ghiya
    *  Date Created        :   June 02, 2017
    *  Last Modification   :   save new invoice
    * 
 */
header('Content-type: application/json');
$obj_client = new client();

$result = array();
$invoiceErrorMessage = '';
$counter = 0;
if(isset($_POST['invoiceData']) && isset($_POST['action']) && $_POST['action'] == "saveNewRFInvoice" && isset($_GET['ajax']) && $_GET['ajax'] == "client_save_refund_voucher_invoice") {

	/* get current user data */
	$dataCurrentUserArr = $obj_client->getUserDetailsById( $obj_client->sanitize($_SESSION['user_detail']['user_id']) );
	$currentFinancialYear = $obj_client->generateFinancialYear();

	$params = array();
	parse_str($_POST['invoiceData'], $params);

	if(empty($params)) {
		$result['status'] = "error";
		$result['message'] = "<div style='color:#f00;background-color:#eddbe3;border-radius:4px;padding:8px 35px 8px 14px;text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);margin-bottom:18px;border-color:#e8d1df;color:#bd4247;'><i class='fa fa-exclamation-triangle'></i>&nbsp;1.&nbsp;".$obj_client->getValMsg('mandatory')."</div>";
		echo json_encode($result);
		die;
	}

	$dataArr['invoice_type'] = 'refundvoucherinvoice';
	$dataArr['invoice_nature'] = 'salesinvoice';
	$dataArr['invoice_date'] = isset($params['invoice_date']) ? $params['invoice_date'] : '';
	$dataArr['reference_number'] = isset($params['invoice_reference_number']) ? $params['invoice_reference_number'] : '';
	$dataArr['company_state'] = isset($params['company_state']) ? $params['company_state'] : '';
	$dataArr['is_tax_payable'] = isset($params['tax_reverse_charge']) ? $params['tax_reverse_charge'] : '';
	$dataArr['description'] = isset($params['description']) ? trim($params['description']) : '';
	$dataArr['refund_voucher_receipt'] = isset($params['receipt_voucher_number']) ? $params['receipt_voucher_number'] : '';

	$supply_place = isset($params['place_of_supply']) ? $params['place_of_supply'] : '';
	$supply_state_data = $obj_client->getStateDetailByStateId($supply_place);

	if($supply_state_data['status'] === "success") {
		$dataArr['supply_place'] = $supply_state_data['data']->state_id;
	} else {
		$dataArr['supply_place'] = '';
	}

	$dataArr['billing_name'] = isset($params['billing_name']) ? $params['billing_name'] : '';
	$dataArr['billing_company_name'] = isset($params['billing_company_name']) ? $params['billing_company_name'] : '';
	$dataArr['billing_address'] = isset($params['billing_address']) ? $params['billing_address'] : '';
	$dataArr['billing_vendor_type'] = isset($params['billing_vendor_type']) ? $params['billing_vendor_type'] : '';
	$dataArr['billing_gstin_number'] = isset($params['billing_gstin_number']) ? $params['billing_gstin_number'] : '';

	$billing_state_code = isset($params['billing_state_code']) ? $params['billing_state_code'] : '';
	$billing_state_data = $obj_client->getStateDetailByStateCode($billing_state_code);

	if($billing_state_data['status'] === "success") {
		$dataArr['billing_state'] = $billing_state_data['data']->state_id;
		$dataArr['billing_state_name'] = $billing_state_data['data']->state_name;
	} else {
		$dataArr['billing_state'] = '';
		$dataArr['billing_state_name'] = '';
	}

	$billing_country_code = isset($params['billing_country_code']) ? $params['billing_country_code'] : '';
	$billing_country_data = $obj_client->getCountryDetailByCountryCode($billing_country_code);

	if($billing_country_data['status'] === "success") {
		$dataArr['billing_country'] = $billing_country_data['data']->id;
	} else {
		$dataArr['billing_country'] = '';
	}
	
	$dataArr['shipping_name'] = isset($params['shipping_name']) ? $params['shipping_name'] : '';
	$dataArr['shipping_company_name'] = isset($params['shipping_company_name']) ? $params['shipping_company_name'] : '';
	$dataArr['shipping_address'] = isset($params['shipping_address']) ? $params['shipping_address'] : '';
	$dataArr['shipping_vendor_type'] = isset($params['shipping_vendor_type']) ? $params['shipping_vendor_type'] : '';
	$dataArr['shipping_gstin_number'] = isset($params['shipping_gstin_number']) ? $params['shipping_gstin_number'] : '';

	$shipping_state_code = isset($params['shipping_state_code']) ? $params['shipping_state_code'] : '';
	$state_data = $obj_client->getStateDetailByStateCode($shipping_state_code);

	if($state_data['status'] === "success") {
		$dataArr['shipping_state'] = $state_data['data']->state_id;
		$dataArr['shipping_state_name'] = $state_data['data']->state_name;
	} else {
		$dataArr['shipping_state'] = '';
		$dataArr['shipping_state_name'] = '';
	}

	$shipping_country_code = isset($params['shipping_country_code']) ? $params['shipping_country_code'] : '';
	$shipping_country_data = $obj_client->getCountryDetailByCountryCode($shipping_country_code);

	if($shipping_country_data['status'] === "success") {
		$dataArr['shipping_country'] = $shipping_country_data['data']->id;
	} else {
		$dataArr['shipping_country'] = '';
	}

	/* validate invoice data */
	$obj_client->validateClientInvoice($dataArr);

	$invoiceItemArray = array();
	$invoiceTotalAmount = 0.00;
	if(isset($params['invoice_itemid']) && count($params['invoice_itemid']) > 0) {

		$invoiceitems = count($params['invoice_itemid']);
		for($i=0; $i < $invoiceitems; $i++) {

			$dataInvoiceArr = array();
			$dataInvoiceArr['invoice_itemid'] = isset($params['invoice_itemid'][$i]) ? $params['invoice_itemid'][$i] : '';
			$dataInvoiceArr['invoice_receiptvalue'] = isset($params['invoice_receiptvalue'][$i]) ? $params['invoice_receiptvalue'][$i] : 0.00;
			$dataInvoiceArr['invoice_taxablevalue'] = isset($params['invoice_taxablevalue'][$i]) ? $params['invoice_taxablevalue'][$i] : 0.00;
			$dataInvoiceArr['invoice_cgstrate'] = isset($params['invoice_cgstrate'][$i]) ? $params['invoice_cgstrate'][$i] : 0.00;
			$dataInvoiceArr['invoice_sgstrate'] = isset($params['invoice_sgstrate'][$i]) ? $params['invoice_sgstrate'][$i] : 0.00;
			$dataInvoiceArr['invoice_igstrate'] = isset($params['invoice_igstrate'][$i]) ? $params['invoice_igstrate'][$i] : 0.00;
			$dataInvoiceArr['invoice_cessrate'] = isset($params['invoice_cessrate'][$i]) ? $params['invoice_cessrate'][$i] : 0.00;

			/* validate invoice data item */
			$obj_client->validateClientInvoiceItem($dataInvoiceArr, ($i+1));
			
			$clientMasterItem = $obj_client->get_row("select cm.item_id, cm.item_name, cm.unit_price, cm.item_category, m.item_id as category_id, m.item_name as category_name, m.hsn_code, m.igst_tax_rate, m.csgt_tax_rate, m.sgst_tax_rate, m.cess_tax_rate, cm.item_unit, u.unit_id, u.unit_name, u.unit_code from " . $obj_client->getTableName('client_master_item') . " as cm, " . $obj_client->getTableName('item') . " as m, " . $obj_client->getTableName('unit') . " as u where 1=1 AND cm.item_category = m.item_id AND cm.item_unit = u.unit_id AND cm.item_id = ".$dataInvoiceArr['invoice_itemid']." AND cm.is_deleted='0' AND cm.status = '1' AND cm.added_by = '".$obj_client->sanitize($_SESSION['user_detail']['user_id'])."'");
			if (!empty($clientMasterItem)) {

				$invoiceItemTaxableAmount = (float)$dataInvoiceArr['invoice_taxablevalue'];
				$invoiceItemReceiptAmount = (float)$dataInvoiceArr['invoice_receiptvalue'];

				if($dataArr['company_state'] === $dataArr['supply_place']) {

					$itemCSGTTax = (float)$dataInvoiceArr['invoice_cgstrate'];
					$itemSGSTTax = (float)$dataInvoiceArr['invoice_sgstrate'];
					$itemIGSTTax = 0.00;
					$itemCESSTax = (float)$dataInvoiceArr['invoice_cessrate'];

					$invoiceItemCSGTTaxAmount = ($itemCSGTTax/100) * $invoiceItemTaxableAmount;
					$invoiceItemSGSTTaxAmount = ($itemSGSTTax/100) * $invoiceItemTaxableAmount;
					$invoiceItemIGSTTaxAmount = 0.00;
					$invoiceItemCESSTaxAmount = ($itemCESSTax/100) * $invoiceItemTaxableAmount;
				} else {

					$itemCSGTTax = 0.00;
					$itemSGSTTax = 0.00;
					$itemIGSTTax = (float)$dataInvoiceArr['invoice_igstrate'];
					$itemCESSTax = (float)$dataInvoiceArr['invoice_cessrate'];

					$invoiceItemCSGTTaxAmount = 0.00;
					$invoiceItemSGSTTaxAmount = 0.00;
					$invoiceItemIGSTTaxAmount = ($itemIGSTTax/100) * $invoiceItemTaxableAmount;
					$invoiceItemCESSTaxAmount = ($itemCESSTax/100) * $invoiceItemTaxableAmount;
				}

				$invoiceItemTotalAmount = ($invoiceItemTaxableAmount + $invoiceItemCSGTTaxAmount + $invoiceItemSGSTTaxAmount + $invoiceItemIGSTTaxAmount + $invoiceItemCESSTaxAmount);
				$invoiceTotalAmount += $invoiceItemTotalAmount;

				$ItemArray = array(
								"item_id" => $clientMasterItem->item_id,
								"item_name" => $clientMasterItem->item_name,
								"item_hsncode" => $clientMasterItem->hsn_code,
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
								"total" => round($invoiceItemTotalAmount, 2),
								"status" => 1,
								"added_by" => $obj_client->sanitize($_SESSION['user_detail']['user_id']),
								"added_date" => date('Y-m-d H:i:s')
							);

				array_push($invoiceItemArray,$ItemArray);
			}
		}
	}

	$dataArr['invoice_total_value'] = number_format($invoiceTotalAmount, 2, '.', '');
	$dataArr['financial_year'] = $obj_client->generateFinancialYear();
	$dataArr['status'] = 1;
	$dataArr['added_by'] = $obj_client->sanitize($_SESSION['user_detail']['user_id']);
	$dataArr['added_date'] = date('Y-m-d H:i:s');

	if($obj_client->getErrorMessage() != '') {

		$result['status'] = "error";
		$result['message'] = $obj_client->getErrorMessage();
		$obj_client->unsetMessage();
		echo json_encode($result);
		die;
	} else {

		if( !empty($invoiceItemArray) && count($invoiceItemArray) > 0 ) {

			$dataArr['serial_number'] = $obj_client->generateRFInvoiceNumber($obj_client->sanitize($_SESSION['user_detail']['user_id']));

			if ($obj_client->insert($obj_client->getTableName('client_invoice'), $dataArr)) {

				$insertid = $obj_client->getInsertID();
				$obj_client->logMsg("New Refund Voucher Invoice Added. ID : " . $insertid . ".");

				$processedInvoiceItemArray = array();
				foreach($invoiceItemArray as $itemArr) {

					$itemArr['invoice_id'] = $insertid;
					array_push($processedInvoiceItemArray, $itemArr);
				}

				if ($obj_client->insertMultiple($obj_client->getTableName('client_invoice_item'), $processedInvoiceItemArray)) {

					$obj_client->setSuccess($obj_client->getValMsg('invoiceadded'));
					$iteminsertid = $obj_client->getInsertID();
					$obj_client->logMsg("New Refund Voucher Invoice Item Added. ID : " . $iteminsertid . ".");

					$result['status'] = "success";
					echo json_encode($result);
					die;
				} else {

					$obj_client->setError($obj_client->getValMsg('failed'));
					$result['status'] = "error";
					$result['message'] = $obj_client->getErrorMessage();
					$obj_client->unsetMessage();
					echo json_encode($result);
					die;
				}

			} else {

				$obj_client->setError($obj_client->getValMsg('failed'));
				$result['status'] = "error";
				$result['message'] = $obj_client->getErrorMessage();
				$obj_client->unsetMessage();
				echo json_encode($result);
				die;
			}

		} else {

			$obj_client->setError($obj_client->getValMsg('noiteminvoice'));
			$result['status'] = "error";
			$result['message'] = $obj_client->getErrorMessage();
			$obj_client->unsetMessage();
			echo json_encode($result);
			die;
		}
	}
}
?>