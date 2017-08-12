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
$invoiceErrorMessage = array();
$invoiceErrorMessageContent = '';
$counter = 0;
$errorcounter = 1;
if(isset($_POST['invoiceData']) && isset($_POST['action']) && $_POST['action'] == "saveUpdateRTInvoice" && isset($_GET['ajax']) && $_GET['ajax'] == "client_save_update_revised_tax_invoice") {

	/* get current user data */
	$dataCurrentUserArr = $obj_client->getUserDetailsById( $obj_client->sanitize($_SESSION['user_detail']['user_id']) );

	$params = array();
	parse_str($_POST['invoiceData'], $params);

	if (empty($params)) {
		$result['status'] = "error";
		$result['message'] = "<div style='color:#f00;background-color:#eddbe3;border-radius:4px;padding:8px 35px 8px 14px;text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);margin-bottom:18px;border-color:#e8d1df;color:#bd4247;'><i class='fa fa-exclamation-triangle'></i>&nbsp;1.&nbsp;".$obj_client->getValMsg('mandatory')."</div>";
		echo json_encode($result);
		die;
	}

	$dataArr['invoice_type'] = isset($params['invoice_type']) ? $params['invoice_type'] : '';
	$dataArr['invoice_nature'] = 'salesinvoice';
	$dataArr['invoice_date'] = isset($params['invoice_date']) ? $params['invoice_date'] : '';
	$dataArr['reference_number'] = isset($params['invoice_reference_number']) ? $params['invoice_reference_number'] : '';
	$dataArr['company_state'] = isset($params['company_state']) ? $params['company_state'] : '';
	$dataArr['invoice_corresponding_type'] = isset($params['invoice_corresponding_type']) ? trim($params['invoice_corresponding_type']) : '';
	$dataArr['corresponding_document_number'] = isset($params['corresponding_document_number']) ? trim($params['corresponding_document_number']) : '';
	$dataArr['corresponding_document_date'] = isset($params['corresponding_document_date']) ? trim($params['corresponding_document_date']) : '';
	$dataArr['description'] = isset($params['description']) ? trim($params['description']) : '';

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

	if(isset($params['same_as_billing']) && $params['same_as_billing'] == 1) {

		$dataArr['same_as_billing'] = "1";
		$dataArr['shipping_name'] = $dataArr['billing_name'];
		$dataArr['shipping_company_name'] = $dataArr['billing_company_name'];
		$dataArr['shipping_address'] = $dataArr['billing_address'];
		$dataArr['shipping_state'] = $dataArr['billing_state'];
		$dataArr['shipping_state_name'] = $dataArr['billing_state_name'];
		$dataArr['shipping_country'] = $dataArr['billing_country'];
		$dataArr['shipping_vendor_type'] = $dataArr['billing_vendor_type'];
		$dataArr['shipping_gstin_number'] = $dataArr['billing_gstin_number'];
	} else {

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
	}

	/* check reference number */
	$referenceStatus = $obj_client->checkReferenceNumberExist($dataArr['reference_number'], $obj_client->sanitize($_SESSION['user_detail']['user_id']), $obj_client->sanitize(base64_decode($params['invoice_id'])));
	if($referenceStatus == true) {
		array_push($invoiceErrorMessage, "You have already used this reference number.");
	}
	
	/* validate invoice data */
	$invoiceErrors = $obj_client->validateClientSalesInvoice($dataArr);
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
			$dataInvoiceArr['invoice_quantity'] = isset($params['invoice_quantity'][$i]) ? $params['invoice_quantity'][$i] : 0.00;
			$dataInvoiceArr['invoice_unit'] = isset($params['invoice_unit'][$i]) ? $params['invoice_unit'][$i] : '';
			$dataInvoiceArr['invoice_discount'] = isset($params['invoice_discount'][$i]) ? $params['invoice_discount'][$i] : 0.00;
			$dataInvoiceArr['invoice_rate'] = isset($params['invoice_rate'][$i]) ? $params['invoice_rate'][$i] : 0.00;
			$dataInvoiceArr['invoice_cgstrate'] = isset($params['invoice_cgstrate'][$i]) ? $params['invoice_cgstrate'][$i] : 0.00;
			$dataInvoiceArr['invoice_sgstrate'] = isset($params['invoice_sgstrate'][$i]) ? $params['invoice_sgstrate'][$i] : 0.00;
			$dataInvoiceArr['invoice_igstrate'] = isset($params['invoice_igstrate'][$i]) ? $params['invoice_igstrate'][$i] : 0.00;
			$dataInvoiceArr['invoice_cessrate'] = isset($params['invoice_cessrate'][$i]) ? $params['invoice_cessrate'][$i] : 0.00;

			/* validate invoice data item */
			$invoiceItemErrors = $obj_client->validateClientSalesInvoiceItem($dataInvoiceArr, ($i+1));
			if ($invoiceItemErrors !== true) {
				$invoiceErrorMessage = array_merge($invoiceItemErrors, $invoiceErrorMessage);
			}

			$clientMasterItem = $obj_client->get_row("select cm.item_id, cm.item_name, cm.unit_price, cm.item_category, m.item_id as category_id, m.item_name as category_name, m.hsn_code, m.igst_tax_rate, m.csgt_tax_rate, m.sgst_tax_rate, m.cess_tax_rate, cm.item_unit, u.unit_id, u.unit_name, u.unit_code from " . $obj_client->getTableName('client_master_item') . " as cm, " . $obj_client->getTableName('item') . " as m, " . $obj_client->getTableName('unit') . " as u where 1=1 AND cm.item_category = m.item_id AND cm.item_unit = u.unit_id AND cm.item_id = ".$dataInvoiceArr['invoice_itemid']." AND cm.is_deleted='0' AND cm.status = '1' AND cm.added_by = '".$obj_client->sanitize($_SESSION['user_detail']['user_id'])."'");
			if (!empty($clientMasterItem)) {

				$itemUnitPrice = (float)$dataInvoiceArr['invoice_rate'];
				$invoiceItemUnit = $dataInvoiceArr['invoice_unit'];
				$invoiceItemQuantity = (float)$dataInvoiceArr['invoice_quantity'];
				$invoiceItemDiscount = (float)$dataInvoiceArr['invoice_discount'];

				$invoiceItemTotal = $invoiceItemQuantity * $itemUnitPrice;
				$invoiceItemDiscountAmount = ($invoiceItemDiscount/100) * $invoiceItemTotal;
				$invoiceItemTaxableAmount = $invoiceItemTotal - $invoiceItemDiscountAmount;

				if($dataArr['company_state'] === $dataArr['supply_place']) {

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

				$invoiceItemTotalAmount = ($invoiceItemTaxableAmount + $invoiceItemCSGTTaxAmount + $invoiceItemSGSTTaxAmount + $invoiceItemIGSTTaxAmount + $invoiceItemCESSTaxAmount);
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
								"added_by" => $obj_client->sanitize($_SESSION['user_detail']['user_id']),
								"added_date" => date('Y-m-d H:i:s')
							);

				array_push($invoiceItemArray,$ItemArray);
			}
		}
	}

	$dataArr['invoice_total_value'] = number_format($invoiceTotalAmount, 2, '.', '');
	$dataArr['status'] = 1;
	$dataArr['updated_by'] = $obj_client->sanitize($_SESSION['user_detail']['user_id']);
	$dataArr['updated_date'] = date('Y-m-d H:i:s');
	$dataConditionArray['invoice_id'] = $obj_client->sanitize(base64_decode($params['invoice_id']));

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
		$obj_client->unsetMessage();
		echo json_encode($result);
		die;
	} else {

		if( !empty($invoiceItemArray) && count($invoiceItemArray) > 0 ) {

			if ($obj_client->update($obj_client->getTableName('client_invoice'), $dataArr, $dataConditionArray)) {

				$updatedid = $obj_client->sanitize(base64_decode($params['invoice_id']));
				$obj_client->logMsg("Revised Tax Invoice Invoice Updated. ID : " . $updatedid . ".");

				$processedInvoiceItemArray = array();
				foreach($invoiceItemArray as $itemArr) {

					$itemArr['invoice_id'] = $updatedid;
					array_push($processedInvoiceItemArray, $itemArr);
				}

				if($obj_client->deletData($obj_client->getTableName('client_invoice_item'), $dataConditionArray)) {

					if ($obj_client->insertMultiple($obj_client->getTableName('client_invoice_item'), $processedInvoiceItemArray)) {

						$obj_client->setSuccess($obj_client->getValMsg('invoiceadded'));
						$iteminsertid = $obj_client->getInsertID();
						$obj_client->logMsg("Revised Tax Invoice Item Added. ID : " . $iteminsertid . ".");
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