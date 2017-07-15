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
if(isset($_POST['invoiceData']) && isset($_POST['action']) && $_POST['action'] == "saveUpdateExportInvoice" && isset($_GET['ajax']) && $_GET['ajax'] == "client_save_update_export_invoice") {

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

	$dataArr['invoice_type'] = 'exportinvoice';
	$dataArr['invoice_nature'] = 'salesinvoice';
	$dataArr['reference_number'] = isset($params['invoice_reference_number']) ? $params['invoice_reference_number'] : '';
	$dataArr['export_supply_meant'] = isset($params['export_supply_meant']) ? $params['export_supply_meant'] : '';
	$dataArr['invoice_date'] = isset($params['invoice_date']) ? $params['invoice_date'] : '';
	$dataArr['company_name'] = isset($params['company_name']) ? $params['company_name'] : '';
	$dataArr['company_address'] = isset($params['company_address']) ? $params['company_address'] : '';
	$dataArr['company_state'] = isset($params['company_state_id']) ? $params['company_state_id'] : '';
	$dataArr['gstin_number'] = isset($params['company_gstin_number']) ? $params['company_gstin_number'] : '';
	$dataArr['description'] = isset($params['description']) ? trim($params['description']) : '';

	$dataArr['advance_adjustment'] = isset($params['advance_adjustment']) ? $params['advance_adjustment'] : '';

	$dataArr['advance_adjustment'] = isset($params['advance_adjustment']) ? $params['advance_adjustment'] : '';
	if($dataArr['advance_adjustment'] == 1) {
		$dataArr['receipt_voucher_number'] = isset($params['receipt_voucher_number']) ? $params['receipt_voucher_number'] : '';
	}

	$dataArr['billing_name'] = isset($params['billing_name']) ? $params['billing_name'] : '';
	$dataArr['billing_company_name'] = isset($params['billing_company_name']) ? $params['billing_company_name'] : '';
	$dataArr['billing_address'] = isset($params['billing_address']) ? $params['billing_address'] : '';
	$dataArr['billing_state_name'] = isset($params['billing_state_name']) ? $params['billing_state_name'] : '';
	$dataArr['billing_state'] = 0;
	$dataArr['billing_country'] = isset($params['billing_country']) ? $params['billing_country'] : '';

	if(isset($params['same_as_billing']) && $params['same_as_billing'] == 1) {

		$dataArr['shipping_name'] = $dataArr['billing_name'];
		$dataArr['shipping_company_name'] = $dataArr['billing_company_name'];
		$dataArr['shipping_address'] = $dataArr['billing_address'];
		$dataArr['shipping_state'] = $dataArr['billing_state'];
		$dataArr['shipping_state_name'] = $dataArr['billing_state_name'];
		$dataArr['shipping_country'] = $dataArr['billing_country'];
	} else {

		$dataArr['shipping_name'] = isset($params['shipping_name']) ? $params['shipping_name'] : '';
		$dataArr['shipping_company_name'] = isset($params['shipping_company_name']) ? $params['shipping_company_name'] : '';
		$dataArr['shipping_address'] = isset($params['shipping_address']) ? $params['shipping_address'] : '';
		$dataArr['shipping_state'] = 0;
		$dataArr['shipping_state_name'] = isset($params['shipping_state_name']) ? $params['shipping_state_name'] : '';
		$dataArr['shipping_country'] = isset($params['shipping_country']) ? $params['shipping_country'] : '';
	}

	$dataArr['export_bill_number'] = isset($params['export_bill_number']) ? $params['export_bill_number'] : '';
	$dataArr['export_bill_date'] = isset($params['export_bill_date']) ? $params['export_bill_date'] : '';

	/* validate invoice data */
	$obj_client->validateClientInvoice($dataArr);

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
			$dataInvoiceArr['invoice_igstrate'] = isset($params['invoice_igstrate'][$i]) ? $params['invoice_igstrate'][$i] : 0.00;
			$dataInvoiceArr['invoice_cessrate'] = isset($params['invoice_cessrate'][$i]) ? $params['invoice_cessrate'][$i] : 0.00;

			/* validate invoice data item */
			$obj_client->validateClientInvoiceItem($dataInvoiceArr, ($i+1));

			$clientMasterItem = $obj_client->get_row("select cm.item_id, cm.item_name, cm.unit_price, cm.item_category, m.item_id as category_id, m.item_name as category_name, m.hsn_code, m.igst_tax_rate, m.csgt_tax_rate, m.sgst_tax_rate, m.cess_tax_rate, cm.item_unit, u.unit_id, u.unit_name, u.unit_code from " . $obj_client->getTableName('client_master_item') . " as cm, " . $obj_client->getTableName('item') . " as m, " . $obj_client->getTableName('unit') . " as u where 1=1 AND cm.item_category = m.item_id AND cm.item_unit = u.unit_id AND cm.item_id = ".$dataInvoiceArr['invoice_itemid']." AND cm.is_deleted='0' AND cm.status = '1' AND cm.added_by = '".$obj_client->sanitize($_SESSION['user_detail']['user_id'])."'");
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

				if($dataArr['export_supply_meant'] === "withpayment") {

					$itemIGSTTax = (float)$dataInvoiceArr['invoice_igstrate'];
					$itemCESSTax = (float)$dataInvoiceArr['invoice_cessrate'];

					$invoiceItemIGSTTaxAmount = ($itemIGSTTax/100) * $invoiceItemTaxableAmount;
					$invoiceItemCESSTaxAmount = ($itemCESSTax/100) * $invoiceItemTaxableAmount;
				} else {

					$itemIGSTTax = 0.00;
					$itemCESSTax = (float)$dataInvoiceArr['invoice_cessrate'];

					$invoiceItemIGSTTaxAmount = 0.00;
					$invoiceItemCESSTaxAmount = ($itemCESSTax/100) * $invoiceItemTaxableAmount;
				}

				$invoiceItemTotalAmount = ($invoiceItemTaxableAmount + $invoiceItemIGSTTaxAmount + $invoiceItemCESSTaxAmount);
				$invoiceTotalAmount += $invoiceItemTotalAmount;

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
								"cgst_rate" => 0.00,
								"cgst_amount" => 0.00,
								"sgst_rate" => 0.00,
								"sgst_amount" => 0.00,
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
	$dataArr['status'] = 1;	
	$dataArr['updated_by'] = $obj_client->sanitize($_SESSION['user_detail']['user_id']);
	$dataArr['updated_date'] = date('Y-m-d H:i:s');
	$dataConditionArray['invoice_id'] = $obj_client->sanitize(base64_decode($params['invoice_id']));

	if($obj_client->getErrorMessage() != '') {
		
		$result['status'] = "error";
		$result['message'] = $obj_client->getErrorMessage();
		$obj_client->unsetMessage();
		echo json_encode($result);
		die;
	} else {

		if( !empty($invoiceItemArray) && count($invoiceItemArray) > 0 ) {
			
			if ($obj_client->update($obj_client->getTableName('client_invoice'), $dataArr, $dataConditionArray)) {
				
				$updatedid = $obj_client->sanitize(base64_decode($params['invoice_id']));
				$obj_client->logMsg("Invoice Updated. ID : " . $updatedid . ".");

				$processedInvoiceItemArray = array();
				foreach($invoiceItemArray as $itemArr) {

					$itemArr['invoice_id'] = $updatedid;
					array_push($processedInvoiceItemArray, $itemArr);
				}

				if($obj_client->deletData($obj_client->getTableName('client_invoice_item'), $dataConditionArray)) {

					if ($obj_client->insertMultiple($obj_client->getTableName('client_invoice_item'), $processedInvoiceItemArray)) {

						$obj_client->setSuccess($obj_client->getValMsg('invoiceadded'));
						$iteminsertid = $obj_client->getInsertID();
						$obj_client->logMsg("New Invoice Export Item Added. ID : " . $iteminsertid . ".");
						
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