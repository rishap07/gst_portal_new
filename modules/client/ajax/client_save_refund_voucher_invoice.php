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

	if (empty($params)) {
		$result['status'] = "error";
		$result['message'] = "<div style='color:#f00;background-color:#eddbe3;border-radius:4px;padding:8px 35px 8px 14px;text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);margin-bottom:18px;border-color:#e8d1df;color:#bd4247;'><i class='fa fa-exclamation-triangle'></i>&nbsp;1.&nbsp;".$obj_client->getValMsg('mandatory')."</div>";
		echo json_encode($result);
		die;
	}	

	$dataArr['reference_number'] = isset($params['invoice_reference_number']) ? $params['invoice_reference_number'] : '';
	$dataArr['invoice_date'] = isset($params['invoice_date']) ? $params['invoice_date'] : '';
	$dataArr['receipt_voucher_number'] = isset($params['receipt_voucher_number']) ? $params['receipt_voucher_number'] : '';
	$dataArr['receipt_voucher_date'] = isset($params['receipt_voucher_date']) ? $params['receipt_voucher_date'] : '';
	$dataArr['is_tax_payable'] = isset($params['tax_reverse_charge']) ? $params['tax_reverse_charge'] : '';
	$dataArr['description'] = isset($params['description']) ? trim($params['description']) : '';

	/* validate invoice data */
	$obj_client->validateClientInvoice($dataArr);

	$company_state = $dataCurrentUserArr['data']->kyc->state_id;
	$rvrow =  $obj_client->get_row("select supply_place from " . $obj_client->getTableName('client_rv_invoice') . " where serial_number = '" . $dataArr['receipt_voucher_number'] . "' AND financial_year = '".$currentFinancialYear."' AND added_by = ". $obj_client->sanitize($_SESSION['user_detail']['user_id']));
	$supply_place = $rvrow->supply_place;

	$invoiceItemArray = array();
	$invoiceTotalAmount = 0.00;
	if(isset($params['invoice_itemid']) && count($params['invoice_itemid']) > 0) {

		$invoiceitems = count($params['invoice_itemid']);
		for($i=0; $i < $invoiceitems; $i++) {

			$dataInvoiceArr = array();
			$dataInvoiceArr['invoice_itemid'] = isset($params['invoice_itemid'][$i]) ? $params['invoice_itemid'][$i] : '';
			$dataInvoiceArr['invoice_taxablevalue'] = isset($params['invoice_taxablevalue'][$i]) ? $params['invoice_taxablevalue'][$i] : 0.00;

			/* validate invoice data item */
			$obj_client->validateClientInvoiceItem($dataInvoiceArr, ($i+1));

			$clientMasterItem = $obj_client->get_row("select cm.item_id, cm.item_name, cm.unit_price, cm.item_category, m.item_id as category_id, m.item_name as category_name, m.hsn_code, m.igst_tax_rate, m.csgt_tax_rate, m.sgst_tax_rate, m.cess_tax_rate, cm.item_unit, u.unit_id, u.unit_name, u.unit_code from " . $obj_client->getTableName('client_master_item') . " as cm, " . $obj_client->getTableName('item') . " as m, " . $obj_client->getTableName('unit') . " as u where 1=1 AND cm.item_category = m.item_id AND cm.item_unit = u.unit_id AND cm.item_id = ".$dataInvoiceArr['invoice_itemid']." AND cm.is_deleted='0' AND cm.status = '1' AND cm.added_by = '".$_SESSION['user_detail']['user_id']."'");
			if (!empty($clientMasterItem)) {

				$invoiceItemAmount = (float)$dataInvoiceArr['invoice_taxablevalue'];
				$invoiceItemTaxableAmount = round($invoiceItemAmount, 2);

				if($company_state === $supply_place) {

					$itemCSGTTax = (float)$clientMasterItem->csgt_tax_rate;
					$itemSGSTTax = (float)$clientMasterItem->sgst_tax_rate;
					$itemIGSTTax = 0.00;
					$itemCESSTax = (float)$clientMasterItem->cess_tax_rate;

					$invoiceItemCSGTTaxAmount = round((($itemCSGTTax/100) * $invoiceItemTaxableAmount), 2);
					$invoiceItemSGSTTaxAmount = round((($itemSGSTTax/100) * $invoiceItemTaxableAmount), 2);
					$invoiceItemIGSTTaxAmount = 0.00;
					$invoiceItemCESSTaxAmount = round((($itemCESSTax/100) * $invoiceItemTaxableAmount), 2);
				} else {

					$itemCSGTTax = 0.00;
					$itemSGSTTax = 0.00;
					$itemIGSTTax = (float)$clientMasterItem->igst_tax_rate;
					$itemCESSTax = (float)$clientMasterItem->cess_tax_rate;

					$invoiceItemCSGTTaxAmount = 0.00;
					$invoiceItemSGSTTaxAmount = 0.00;
					$invoiceItemIGSTTaxAmount = round((($itemIGSTTax/100) * $invoiceItemTaxableAmount), 2);
					$invoiceItemCESSTaxAmount = round((($itemCESSTax/100) * $invoiceItemTaxableAmount), 2);
				}

				$invoiceItemTotalAmount = round(($invoiceItemTaxableAmount + $invoiceItemCSGTTaxAmount + $invoiceItemSGSTTaxAmount + $invoiceItemIGSTTaxAmount + $invoiceItemCESSTaxAmount), 2);
				$invoiceTotalAmount += $invoiceItemTotalAmount;

				$ItemArray = array(
								"item_id" => $clientMasterItem->item_id,
								"item_name" => $clientMasterItem->item_name,
								"item_hsncode" => $clientMasterItem->hsn_code,
								"taxable_subtotal" => $invoiceItemTaxableAmount,
								"cgst_rate" => $itemCSGTTax,
								"cgst_amount" => $invoiceItemCSGTTaxAmount,
								"sgst_rate" => $itemSGSTTax,
								"sgst_amount" => $invoiceItemSGSTTaxAmount,
								"igst_rate" => $itemIGSTTax,
								"igst_amount" => $invoiceItemIGSTTaxAmount,
								"cess_rate" => $itemCESSTax,
								"cess_amount" => $invoiceItemCESSTaxAmount,
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
	$dataArr['financial_year'] = $obj_client->generateFinancialYear();
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

			$dataArr['serial_number'] = $obj_client->generateRFInvoiceNumber( $obj_client->sanitize($_SESSION['user_detail']['user_id']) );

			if ($obj_client->insert($obj_client->getTableName('client_rf_invoice'), $dataArr)) {

				$insertid = $obj_client->getInsertID();
				$obj_client->logMsg("New Refund Voucher Invoice Added. ID : " . $insertid . ".");

				$processedInvoiceItemArray = array();
				foreach($invoiceItemArray as $itemArr) {

					$itemArr['invoice_id'] = $insertid;
					array_push($processedInvoiceItemArray, $itemArr);
				}

				if ($obj_client->insertMultiple($obj_client->getTableName('client_rf_invoice_item'), $processedInvoiceItemArray)) {

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