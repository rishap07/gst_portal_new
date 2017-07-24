<?php
/*
    * 
    *  Developed By        :   Ishwar Lal Ghiya
    *  Date Created        :   July 17, 2017
    *  Last Modification   :   save new purchase invoice
    * 
*/
header('Content-type: application/json');
$obj_purchase = new purchase();

$result = array();
$invoiceErrorMessage = '';
$counter = 0;
if(isset($_POST['invoiceData']) && isset($_POST['action']) && $_POST['action'] == "saveUpdatePurchaseTaxInvoice" && isset($_GET['ajax']) && $_GET['ajax'] == "purchase_invoice_save_update") {

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

	$dataArr['company_name'] = isset($params['company_name']) ? $params['company_name'] : '';
	$dataArr['company_address'] = isset($params['company_address']) ? $params['company_address'] : '';
	$dataArr['company_state'] = isset($params['company_state_id']) ? $params['company_state_id'] : '';
	$dataArr['company_gstin_number'] = isset($params['company_gstin_number']) ? $params['company_gstin_number'] : '';
	$dataArr['supply_type'] = isset($params['supply_type']) ? $params['supply_type'] : '';
	$dataArr['invoice_date'] = isset($params['invoice_date']) ? $params['invoice_date'] : '';
	$dataArr['description'] = isset($params['description']) ? trim($params['description']) : '';

	$supply_place = isset($params['place_of_supply']) ? $params['place_of_supply'] : '';
	$supply_state_data = $obj_purchase->getStateDetailByStateId($supply_place);

	if($supply_state_data['status'] === "success") {
		$dataArr['supply_place'] = $supply_state_data['data']->state_id;
	} else {
		$dataArr['supply_place'] = '';
	}

	$dataArr['advance_adjustment'] = isset($params['advance_adjustment']) ? $params['advance_adjustment'] : '';
	if($dataArr['advance_adjustment'] == 1) {
		$dataArr['receipt_voucher_number'] = isset($params['receipt_voucher_number']) ? $params['receipt_voucher_number'] : '';
	}

	$dataArr['supplier_billing_name'] = isset($params['supplier_billing_name']) ? $params['supplier_billing_name'] : '';
	$dataArr['supplier_billing_company_name'] = isset($params['supplier_billing_company_name']) ? $params['supplier_billing_company_name'] : '';
	$dataArr['supplier_billing_address'] = isset($params['supplier_billing_address']) ? $params['supplier_billing_address'] : '';
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

	if(isset($params['same_as_billing']) && $params['same_as_billing'] == 1) {

		$dataArr['recipient_shipping_name'] = $dataArr['company_name'];
		$dataArr['recipient_shipping_company_name'] = $dataArr['company_name'];
		$dataArr['recipient_shipping_address'] = $dataArr['company_address'];
		$dataArr['recipient_shipping_state'] = $dataArr['company_state'];
		$dataArr['recipient_shipping_state_name'] = isset($params['company_state']) ? $params['company_state'] : '';
		$dataArr['recipient_shipping_gstin_number'] = $dataArr['company_gstin_number'];
	} else {

		$dataArr['recipient_shipping_name'] = isset($params['recipient_shipping_name']) ? $params['recipient_shipping_name'] : '';
		$dataArr['recipient_shipping_company_name'] = isset($params['recipient_shipping_company_name']) ? $params['recipient_shipping_company_name'] : '';
		$dataArr['recipient_shipping_address'] = isset($params['recipient_shipping_address']) ? $params['recipient_shipping_address'] : '';
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
	}

	/* validate invoice data */
	$obj_purchase->validateClientPurchaseInvoice($dataArr);

	$invoiceItemArray = array();
	$invoiceTotalAmount = 0.00;
	if(isset($params['invoice_itemid']) && count($params['invoice_itemid']) > 0) {

		$invoiceitems = count($params['invoice_itemid']);
		for($i=0; $i < $invoiceitems; $i++) {

			$dataInvoiceArr = array();
			$dataInvoiceArr['invoice_itemid'] = isset($params['invoice_itemid'][$i]) ? $params['invoice_itemid'][$i] : '';
			$dataInvoiceArr['invoice_quantity'] = isset($params['invoice_quantity'][$i]) ? $params['invoice_quantity'][$i] : '';
			$dataInvoiceArr['invoice_unit'] = isset($params['invoice_unit'][$i]) ? $params['invoice_unit'][$i] : 'NA';
			$dataInvoiceArr['invoice_rate'] = isset($params['invoice_rate'][$i]) ? $params['invoice_rate'][$i] : 0.00;
			$dataInvoiceArr['invoice_discount'] = isset($params['invoice_discount'][$i]) ? $params['invoice_discount'][$i] : 0.00;
			$dataInvoiceArr['invoice_advancevalue'] = isset($params['invoice_advancevalue'][$i]) ? $params['invoice_advancevalue'][$i] : 0.00;
			$dataInvoiceArr['invoice_cgstrate'] = isset($params['invoice_cgstrate'][$i]) ? $params['invoice_cgstrate'][$i] : 0.00;
			$dataInvoiceArr['invoice_sgstrate'] = isset($params['invoice_sgstrate'][$i]) ? $params['invoice_sgstrate'][$i] : 0.00;
			$dataInvoiceArr['invoice_igstrate'] = isset($params['invoice_igstrate'][$i]) ? $params['invoice_igstrate'][$i] : 0.00;
			$dataInvoiceArr['invoice_cessrate'] = isset($params['invoice_cessrate'][$i]) ? $params['invoice_cessrate'][$i] : 0.00;

			/* validate invoice data item */
			$obj_purchase->validateClientPurchaseInvoiceItem($dataInvoiceArr, ($i+1));

			$clientMasterItem = $obj_purchase->get_row("select cm.item_id, cm.item_name, cm.unit_price, cm.item_category, m.item_id as category_id, m.item_name as category_name, m.hsn_code, m.igst_tax_rate, m.csgt_tax_rate, m.sgst_tax_rate, m.cess_tax_rate from " . $obj_purchase->getTableName('client_master_item') . " as cm, " . $obj_purchase->getTableName('item') . " as m where 1=1 AND cm.item_category = m.item_id AND cm.item_id = ".$dataInvoiceArr['invoice_itemid']." AND cm.is_deleted='0' AND cm.status = '1' AND cm.added_by = '".$obj_purchase->sanitize($_SESSION['user_detail']['user_id'])."'");
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
					$dataArr['invoice_type'] === "deemedimportinvoice"
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

					if($dataArr['supplier_billing_state'] === $dataArr['supply_place']) {

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
	$dataArr['status'] = 1;
	$dataArr['updated_by'] = $obj_purchase->sanitize($_SESSION['user_detail']['user_id']);
	$dataArr['updated_date'] = date('Y-m-d H:i:s');
	$dataConditionArray['purchase_invoice_id'] = $obj_purchase->sanitize(base64_decode($params['purchase_invoice_id']));

	if($obj_purchase->getErrorMessage() != '') {

		$result['status'] = "error";
		$result['message'] = $obj_purchase->getErrorMessage();
		$obj_purchase->unsetMessage();
		echo json_encode($result);
		die;
	} else {

		if( !empty($invoiceItemArray) && count($invoiceItemArray) > 0 ) {

			if ($obj_purchase->update($obj_purchase->getTableName('client_purchase_invoice'), $dataArr, $dataConditionArray)) {

				$updatedid = $obj_purchase->sanitize(base64_decode($params['purchase_invoice_id']));
				$obj_purchase->logMsg("Purchase Invoice Updated. ID : " . $updatedid . ".");

				$processedInvoiceItemArray = array();
				foreach($invoiceItemArray as $itemArr) {

					$itemArr['purchase_invoice_id'] = $updatedid;
					array_push($processedInvoiceItemArray, $itemArr);
				}

				if($obj_purchase->deletData($obj_purchase->getTableName('client_purchase_invoice_item'), $dataConditionArray)) {
					
					if ($obj_purchase->insertMultiple($obj_purchase->getTableName('client_purchase_invoice_item'), $processedInvoiceItemArray)) {

						$obj_purchase->setSuccess($obj_purchase->getValMsg('invoiceadded'));
						$iteminsertid = $obj_purchase->getInsertID();
						$obj_purchase->logMsg("New Purchase Invoice Item Added. ID : " . $iteminsertid . ".");

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

				}  else {
					
					$obj_client->setError($obj_client->getValMsg('failed'));
					$result['status'] = "error";
					$result['message'] = $obj_client->getErrorMessage();
					$obj_client->unsetMessage();
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