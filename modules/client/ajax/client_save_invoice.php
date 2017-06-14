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
if(isset($_POST['invoiceData']) && isset($_POST['action']) && $_POST['action'] == "saveNewInvoice" && isset($_GET['ajax']) && $_GET['ajax'] == "client_save_invoice") {
	
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
	
	$dataArr['company_name'] = $dataCurrentUserArr['data']->company_name;
	$dataArr['gstin_number'] = $dataCurrentUserArr['data']->gstin->gstin_number;
	$dataArr['is_tax_payable'] = isset($params['tax_reverse_charge']) ? $params['tax_reverse_charge'] : '';
	$dataArr['invoice_date'] = isset($params['invoice_date']) ? $params['invoice_date'] : '';
	$dataArr['transportation_mode'] = isset($params['transportation_mode']) ? $params['transportation_mode'] : '';
	$dataArr['supply_datetime'] = isset($params['date_time_of_supply']) ? $params['date_time_of_supply'] : '';
	$dataArr['supply_place'] = $dataCurrentUserArr['data']->kyc->state_id;
	
	$dataArr['billing_name'] = isset($params['billing_name']) ? $params['billing_name'] : '';
	$dataArr['billing_address'] = isset($params['billing_address']) ? $params['billing_address'] : '';
	$dataArr['billing_state'] = isset($params['billing_state']) ? $params['billing_state'] : '';
	$dataArr['billing_gstin_number'] = isset($params['billing_gstin_number']) ? $params['billing_gstin_number'] : '';
	
	if(isset($params['same_as_billing']) && $params['same_as_billing'] == 1) {
		$dataArr['shipping_name'] = $dataArr['billing_name'];
		$dataArr['shipping_address'] = $dataArr['billing_address'];
		$dataArr['shipping_state'] = $dataArr['billing_state'];
		$dataArr['shipping_gstin_number'] = $dataArr['billing_gstin_number'];
	} else {
		
		$dataArr['shipping_name'] = isset($params['shipping_name']) ? $params['shipping_name'] : '';
		$dataArr['shipping_address'] = isset($params['shipping_address']) ? $params['shipping_address'] : '';
		$dataArr['shipping_gstin_number'] = isset($params['shipping_gstin_number']) ? $params['shipping_gstin_number'] : '';

		$shipping_state_code = isset($params['shipping_state_code']) ? $params['shipping_state_code'] : '';
		$state_data = $obj_client->getStateDetailByStateCode($shipping_state_code);
		
		if($state_data['status'] === "success") {
			$dataArr['shipping_state'] = $state_data['data']->state_id;
		} else {
			$dataArr['shipping_state'] = '';
		}
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
			$dataInvoiceArr['invoice_quantity'] = isset($params['invoice_quantity'][$i]) ? $params['invoice_quantity'][$i] : '';
			$dataInvoiceArr['invoice_discount'] = isset($params['invoice_discount'][$i]) ? $params['invoice_discount'][$i] : 0.00;
			
			/* validate invoice data item */
			$obj_client->validateClientInvoiceItem($dataInvoiceArr, ($i+1));
			
			$clientMasterItem = $obj_client->get_row("select cm.item_id, cm.item_name, cm.unit_price, cm.item_category, m.item_id as category_id, m.item_name as category_name, m.hsn_code, m.igst_tax_rate, m.csgt_tax_rate, m.sgst_tax_rate, m.cess_tax_rate, cm.item_unit, u.unit_id, u.unit_name, u.unit_code from " . $obj_client->getTableName('client_master_item') . " as cm, " . $obj_client->getTableName('item') . " as m, " . $obj_client->getTableName('unit') . " as u where 1=1 AND cm.item_category = m.item_id AND cm.item_unit = u.unit_id AND cm.item_id = ".$dataInvoiceArr['invoice_itemid']." AND cm.is_deleted='0' AND cm.status = '1' AND cm.added_by = '".$_SESSION['user_detail']['user_id']."'");
			if (!empty($clientMasterItem)) {

				$itemUnitPrice = (float)$clientMasterItem->unit_price;
				
				$invoiceItemQuantity = (int)$dataInvoiceArr['invoice_quantity'];
				$invoiceItemDiscount = (float)$dataInvoiceArr['invoice_discount'];
				
				$invoiceItemTotal = round(($invoiceItemQuantity * $itemUnitPrice), 2);
				$invoiceItemDiscountAmount = ($invoiceItemDiscount/100) * $invoiceItemTotal;
				$invoiceItemTaxableAmount = round(($invoiceItemTotal - $invoiceItemDiscountAmount), 2);
				
				if($dataArr['supply_place'] === $dataArr['shipping_state']) {

					$itemCSGTTax = (float)$clientMasterItem->csgt_tax_rate;
					$itemSGSTTax = (float)$clientMasterItem->sgst_tax_rate;
					$itemIGSTTax = 0.00;
					
					$invoiceItemCSGTTaxAmount = round((($itemCSGTTax/100) * $invoiceItemTaxableAmount), 2);
					$invoiceItemSGSTTaxAmount = round((($itemSGSTTax/100) * $invoiceItemTaxableAmount), 2);
					$invoiceItemIGSTTaxAmount = 0.00;
				} else {
					
					$itemCSGTTax = 0.00;
					$itemSGSTTax = 0.00;
					$itemIGSTTax = (float)$clientMasterItem->igst_tax_rate;

					$invoiceItemCSGTTaxAmount = 0.00;
					$invoiceItemSGSTTaxAmount = 0.00;
					$invoiceItemIGSTTaxAmount = round((($itemIGSTTax/100) * $invoiceItemTaxableAmount), 2);
				}

				$invoiceItemTotalAmount = round(($invoiceItemTaxableAmount + $invoiceItemCSGTTaxAmount + $invoiceItemSGSTTaxAmount + $invoiceItemIGSTTaxAmount), 2);
				$invoiceTotalAmount += $invoiceItemTotalAmount;

				$ItemArray = array(
								"item_id" => $clientMasterItem->item_id,
								"item_name" => $clientMasterItem->item_name,
								"item_hsncode" => $clientMasterItem->hsn_code,
								"item_quantity" => $invoiceItemQuantity,
								"item_unit" => $clientMasterItem->unit_code,
								"item_unit_price" => $itemUnitPrice,
								"subtotal" => $invoiceItemTotal,
								"discount" => $invoiceItemDiscount,
								"taxable_subtotal" => $invoiceItemTaxableAmount,
								"cgst_rate" => $itemCSGTTax,
								"cgst_amount" => $invoiceItemCSGTTaxAmount,
								"sgst_rate" => $itemSGSTTax,
								"sgst_amount" => $invoiceItemSGSTTaxAmount,
								"igst_rate" => $itemIGSTTax,
								"igst_amount" => $invoiceItemIGSTTaxAmount,
								"total" => $invoiceItemTotalAmount,
								"status" => 1,
								"added_by" => $_SESSION['user_detail']['user_id'],
								"added_date" => date('Y-m-d H:i:s')
							);
				
				array_push($invoiceItemArray,$ItemArray);
			}
		}
	}
	
	$dataArr['invoice_total_value'] = $invoiceTotalAmount;
	$dataArr['status'] = 1;
	$dataArr['added_by'] = $_SESSION['user_detail']['user_id'];
	$dataArr['added_date'] = date('Y-m-d H:i:s');
	
	if($obj_client->getErrorMessage() != '') {
		
		$result['status'] = "error";
		$result['message'] = $obj_client->getErrorMessage();
		$obj_client->unsetMessage();
		echo json_encode($result);
		die;
	} else {

		if( !empty($invoiceItemArray) && count($invoiceItemArray) > 0 ) {

			$dataArr['serial_number'] = $obj_client->generateInvoiceNumber( $obj_client->sanitize($_SESSION['user_detail']['user_id']) );
			
			if ($obj_client->insert($obj_client->getTableName('client_invoice'), $dataArr)) {

				$insertid = $obj_client->getInsertID();
				$obj_client->logMsg("New Invoice Added. ID : " . $insertid . ".");

				$processedInvoiceItemArray = array();
				foreach($invoiceItemArray as $itemArr) {

					$itemArr['invoice_id'] = $insertid;
					array_push($processedInvoiceItemArray,$itemArr);
				}
				
				if ($obj_client->insertMultiple($obj_client->getTableName('client_invoice_item'), $processedInvoiceItemArray)) {
					
					$obj_client->setSuccess($obj_client->getValMsg('invoiceadded'));
					$iteminsertid = $obj_client->getInsertID();
					$obj_client->logMsg("New Invoice Item Added. ID : " . $iteminsertid . ".");
					
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