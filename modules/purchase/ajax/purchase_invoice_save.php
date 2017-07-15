<?php
/*
    * 
    *  Developed By        :   Ishwar Lal Ghiya
    *  Date Created        :   July 15, 2017
    *  Last Modification   :   save new purchase invoice
    * 
*/
header('Content-type: application/json');
$obj_purchase = new purchase();

$result = array();
$invoiceErrorMessage = '';
$counter = 0;
if(isset($_POST['invoiceData']) && isset($_POST['action']) && $_POST['action'] == "saveNewPurchaseTaxInvoice" && isset($_GET['ajax']) && $_GET['ajax'] == "purchase_invoice_save") {

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

	$dataArr['reference_number'] = isset($params['invoice_reference_number']) ? $params['invoice_reference_number'] : '';
	$dataArr['invoice_type'] = isset($params['invoice_type']) ? $params['invoice_type'] : '';
	$dataArr['invoice_nature'] = 'purchaseinvoice';

	$dataArr['company_name'] = $dataCurrentUserArr['data']->kyc->name;
	$dataArr['company_address'] = $dataCurrentUserArr['data']->kyc->registered_address;
	$dataArr['company_state'] = $dataCurrentUserArr['data']->kyc->state_id;
	$dataArr['company_gstin_number'] = $dataCurrentUserArr['data']->kyc->gstin_number;
	$dataArr['supply_type'] = isset($params['supply_type']) ? $params['supply_type'] : '';
	$dataArr['export_supply_meant'] = '';
	$dataArr['invoice_date'] = isset($params['invoice_date']) ? $params['invoice_date'] : '';
	$dataArr['description'] = isset($params['description']) ? trim($params['description']) : '';

	$supply_place = isset($params['place_of_supply']) ? $params['place_of_supply'] : '';
	$supply_state_data = $obj_purchase->getStateDetailByStateId($supply_place);

	if($supply_state_data['status'] === "success") {
		$dataArr['supply_place'] = $supply_state_data['data']->state_id;
	} else {
		$dataArr['supply_place'] = '';
	}

	if($dataArr['supply_type'] == "tcs") {
		$dataArr['ecommerce_gstin_number'] = isset($params['ecommerce_gstin_number']) ? $params['ecommerce_gstin_number'] : '';
		$dataArr['ecommerce_vendor_code'] = isset($params['ecommerce_vendor_code']) ? $params['ecommerce_vendor_code'] : '';
	}

	$dataArr['advance_adjustment'] = isset($params['advance_adjustment']) ? $params['advance_adjustment'] : '';
	if($dataArr['advance_adjustment'] == 1) {
		$dataArr['receipt_voucher_number'] = isset($params['receipt_voucher_number']) ? $params['receipt_voucher_number'] : '';
	}

	$dataArr['billing_name'] = isset($params['billing_name']) ? $params['billing_name'] : '';
	$dataArr['billing_company_name'] = isset($params['billing_company_name']) ? $params['billing_company_name'] : '';
	$dataArr['billing_address'] = isset($params['billing_address']) ? $params['billing_address'] : '';
	$dataArr['billing_gstin_number'] = isset($params['billing_gstin_number']) ? $params['billing_gstin_number'] : '';

	$billing_state_code = isset($params['billing_state_code']) ? $params['billing_state_code'] : '';
	$billing_state_data = $obj_purchase->getStateDetailByStateCode($billing_state_code);

	if($billing_state_data['status'] === "success") {
		$dataArr['billing_state'] = $billing_state_data['data']->state_id;
		$dataArr['billing_state_name'] = $billing_state_data['data']->state_name;
	} else {
		$dataArr['billing_state'] = '';
		$dataArr['billing_state_name'] = '';
	}

	if(isset($params['same_as_billing']) && $params['same_as_billing'] == 1) {

		$dataArr['shipping_name'] = $dataArr['billing_name'];
		$dataArr['shipping_company_name'] = $dataArr['billing_company_name'];
		$dataArr['shipping_address'] = $dataArr['billing_address'];
		$dataArr['shipping_state'] = $dataArr['billing_state'];
		$dataArr['shipping_state_name'] = $dataArr['billing_state_name'];
		$dataArr['shipping_gstin_number'] = $dataArr['billing_gstin_number'];
	} else {

		$dataArr['shipping_name'] = isset($params['shipping_name']) ? $params['shipping_name'] : '';
		$dataArr['shipping_company_name'] = isset($params['shipping_company_name']) ? $params['shipping_company_name'] : '';
		$dataArr['shipping_address'] = isset($params['shipping_address']) ? $params['shipping_address'] : '';
		$dataArr['shipping_gstin_number'] = isset($params['shipping_gstin_number']) ? $params['shipping_gstin_number'] : '';

		$shipping_state_code = isset($params['shipping_state_code']) ? $params['shipping_state_code'] : '';
		$state_data = $obj_purchase->getStateDetailByStateCode($shipping_state_code);

		if($state_data['status'] === "success") {
			$dataArr['shipping_state'] = $state_data['data']->state_id;
			$dataArr['shipping_state_name'] = $state_data['data']->state_name;
		} else {
			$dataArr['shipping_state'] = '';
			$dataArr['shipping_state_name'] = '';
		}
	}

	/* validate invoice data */
	$obj_purchase->validateClientInvoice($dataArr);

	$invoiceItemArray = array();
	$invoiceTotalAmount = 0.00;
	if(isset($params['invoice_itemid']) && count($params['invoice_itemid']) > 0) {

		$invoiceitems = count($params['invoice_itemid']);
		for($i=0; $i < $invoiceitems; $i++) {

			$dataInvoiceArr = array();
			$dataInvoiceArr['invoice_itemid'] = isset($params['invoice_itemid'][$i]) ? $params['invoice_itemid'][$i] : '';
			$dataInvoiceArr['invoice_quantity'] = isset($params['invoice_quantity'][$i]) ? $params['invoice_quantity'][$i] : '';
			$dataInvoiceArr['invoice_unit'] = isset($params['invoice_unit'][$i]) ? $params['invoice_unit'][$i] : '';
			$dataInvoiceArr['invoice_discount'] = isset($params['invoice_discount'][$i]) ? $params['invoice_discount'][$i] : 0.00;
			$dataInvoiceArr['invoice_advancevalue'] = isset($params['invoice_advancevalue'][$i]) ? $params['invoice_advancevalue'][$i] : 0.00;			
			$dataInvoiceArr['invoice_rate'] = isset($params['invoice_rate'][$i]) ? $params['invoice_rate'][$i] : 0.00;
			$dataInvoiceArr['invoice_cgstrate'] = isset($params['invoice_cgstrate'][$i]) ? $params['invoice_cgstrate'][$i] : 0.00;
			$dataInvoiceArr['invoice_sgstrate'] = isset($params['invoice_sgstrate'][$i]) ? $params['invoice_sgstrate'][$i] : 0.00;
			$dataInvoiceArr['invoice_igstrate'] = isset($params['invoice_igstrate'][$i]) ? $params['invoice_igstrate'][$i] : 0.00;
			$dataInvoiceArr['invoice_cessrate'] = isset($params['invoice_cessrate'][$i]) ? $params['invoice_cessrate'][$i] : 0.00;

			/* validate invoice data item */
			$obj_purchase->validateClientInvoiceItem($dataInvoiceArr, ($i+1));

			$clientMasterItem = $obj_purchase->get_row("select cm.item_id, cm.item_name, cm.unit_price, cm.item_category, m.item_id as category_id, m.item_name as category_name, m.hsn_code, m.igst_tax_rate, m.csgt_tax_rate, m.sgst_tax_rate, m.cess_tax_rate, cm.item_unit, u.unit_id, u.unit_name, u.unit_code from " . $obj_purchase->getTableName('client_master_item') . " as cm, " . $obj_purchase->getTableName('item') . " as m, " . $obj_purchase->getTableName('unit') . " as u where 1=1 AND cm.item_category = m.item_id AND cm.item_unit = u.unit_id AND cm.item_id = ".$dataInvoiceArr['invoice_itemid']." AND cm.is_deleted='0' AND cm.status = '1' AND cm.added_by = '".$obj_purchase->sanitize($_SESSION['user_detail']['user_id'])."'");
			if (!empty($clientMasterItem)) {

				$itemUnitPrice = (float)$dataInvoiceArr['invoice_rate'];
				$invoiceItemUnit = $dataInvoiceArr['invoice_unit'];
				$invoiceItemQuantity = (int)$dataInvoiceArr['invoice_quantity'];
				$invoiceItemDiscount = (float)$dataInvoiceArr['invoice_discount'];
				$invoiceItemAdvanceAmount = (float)$dataInvoiceArr['invoice_advancevalue'];

				$invoiceItemTotal = $invoiceItemQuantity * $itemUnitPrice;
				$invoiceItemDiscountAmount = ($invoiceItemDiscount/100) * $invoiceItemTotal;
				$invoiceItemReduceAmount = $invoiceItemAdvanceAmount + $invoiceItemDiscountAmount;
				$invoiceItemTaxableAmount = $invoiceItemTotal - $invoiceItemReduceAmount;

				if(
					$dataArr['invoice_type'] === "sezunitinvoice" || 
					$dataArr['invoice_type'] === "deemedexportinvoice"
				) {

					$itemCSGTTax = 0.00;
					$invoiceItemCSGTTaxAmount = 0.00;

					$itemSGSTTax = 0.00;
					$invoiceItemSGSTTaxAmount = 0.00;

					$itemIGSTTax = (float)$dataInvoiceArr['invoice_igstrate'];
					$itemCESSTax = (float)$dataInvoiceArr['invoice_cessrate'];

					$invoiceItemIGSTTaxAmount = ($itemIGSTTax/100) * $invoiceItemTaxableAmount;
					$invoiceItemCESSTaxAmount = ($itemCESSTax/100) * $invoiceItemTaxableAmount;
				} else {

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
				}

				if($dataArr['supply_type'] == "reversecharge") {

					$invoiceItemTotalAmount = $invoiceItemTaxableAmount;
					$invoiceTotalAmount += $invoiceItemTotalAmount;
				} else {

					$invoiceItemTotalAmount = ($invoiceItemTaxableAmount + $invoiceItemCSGTTaxAmount + $invoiceItemSGSTTaxAmount + $invoiceItemIGSTTaxAmount + $invoiceItemCESSTaxAmount);
					$invoiceTotalAmount += $invoiceItemTotalAmount;
				}

				$ItemArray = array(
								"item_id" => $clientMasterItem->item_id,
								"item_name" => $clientMasterItem->item_name,
								"item_hsncode" => $clientMasterItem->hsn_code,
								"item_quantity" => $invoiceItemQuantity,
								"item_unit" => $invoiceItemUnit,
								"item_unit_price" => round($itemUnitPrice, 2),
								"subtotal" => round($invoiceItemTotal, 2),
								"discount" => $invoiceItemDiscount,
								"advance_amount" => round($invoiceItemAdvanceAmount, 2),
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

	if($obj_purchase->getErrorMessage() != '') {

		$result['status'] = "error";
		$result['message'] = $obj_purchase->getErrorMessage();
		$obj_purchase->unsetMessage();
		echo json_encode($result);
		die;
	} else {

		if( !empty($invoiceItemArray) && count($invoiceItemArray) > 0 ) {

			$dataArr['serial_number'] = $obj_purchase->generateInvoiceNumber( $obj_purchase->sanitize($_SESSION['user_detail']['user_id']) );

			if ($obj_purchase->insert($obj_purchase->getTableName('client_invoice'), $dataArr)) {

				$insertid = $obj_purchase->getInsertID();
				$obj_purchase->logMsg("New Invoice Added. ID : " . $insertid . ".");

				$processedInvoiceItemArray = array();
				foreach($invoiceItemArray as $itemArr) {

					$itemArr['invoice_id'] = $insertid;
					array_push($processedInvoiceItemArray, $itemArr);
				}

				if ($obj_purchase->insertMultiple($obj_purchase->getTableName('client_invoice_item'), $processedInvoiceItemArray)) {

					$obj_purchase->setSuccess($obj_purchase->getValMsg('invoiceadded'));
					$iteminsertid = $obj_purchase->getInsertID();
					$obj_purchase->logMsg("New Invoice Item Added. ID : " . $iteminsertid . ".");

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