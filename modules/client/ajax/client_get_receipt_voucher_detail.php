<?php
/*
    * 
    *  Developed By        :   Ishwar Lal Ghiya
    *  Date Created        :   June 02, 2017
    *  Last Modification   :   Get item detail
    * 
*/

$obj_client = new client();
$result = array();
if(isset($_POST['receiptVoucherNumber']) && isset($_POST['action']) && $_POST['action'] == "getReceiptVoucher") {
	
	$receiptVoucherNumber = $obj_client->sanitize($_POST['receiptVoucherNumber']);
	$receiptVoucherData = $obj_client->get_results("select crv.invoice_id, crv.serial_number, crv.invoice_date, crv.supply_place, sp.state_name as supply_state_name, sp.state_code as supply_state_code, sp.state_tin as supply_state_tin, crv.billing_name, crv.billing_address, crv.billing_state, bs.state_name as billing_state_name, bs.state_code as billing_state_code, bs.state_tin as billing_state_tin, crv.billing_gstin_number, crv.shipping_name, crv.shipping_address, crv.shipping_state, ss.state_name as shipping_state_name, ss.state_code as shipping_state_code, ss.state_tin as shipping_state_tin, crv.shipping_gstin_number, crv.invoice_total_value, crv.is_canceled from " . $obj_client->getTableName('client_rv_invoice') . " as crv INNER JOIN " . $obj_client->getTableName('state') . " as sp on crv.supply_place = sp.state_id INNER JOIN " . $obj_client->getTableName('state') . " as bs on crv.billing_state = bs.state_id INNER JOIN " . $obj_client->getTableName('state') . " as ss ON crv.shipping_state = ss.state_id where 1=1 AND crv.serial_number = '".$receiptVoucherNumber."' AND crv.is_deleted = '0' AND crv.status = '1' AND crv.added_by = ". $obj_client->sanitize($_SESSION['user_detail']['user_id']));

	if(count($receiptVoucherData) > 0) {

		$result['invoice_id'] = $receiptVoucherData[0]->invoice_id;
		$result['serial_number'] = $receiptVoucherData[0]->serial_number;
		$result['invoice_date'] = $receiptVoucherData[0]->invoice_date;
		$result['supply_place'] = $receiptVoucherData[0]->supply_place;
		$result['supply_state_name'] = $receiptVoucherData[0]->supply_state_name;
		$result['supply_state_code'] = $receiptVoucherData[0]->supply_state_code;
		$result['supply_state_tin'] = $receiptVoucherData[0]->supply_state_tin;		
		$result['billing_name'] = $receiptVoucherData[0]->billing_name;
		$result['billing_address'] = $receiptVoucherData[0]->billing_address;
		$result['billing_state'] = $receiptVoucherData[0]->billing_state;
		$result['billing_state_name'] = $receiptVoucherData[0]->billing_state_name;
		$result['billing_state_code'] = $receiptVoucherData[0]->billing_state_code;
		$result['billing_state_tin'] = $receiptVoucherData[0]->billing_state_tin;
		$result['billing_gstin_number'] = $receiptVoucherData[0]->billing_gstin_number;
		$result['shipping_name'] = $receiptVoucherData[0]->shipping_name;
		$result['shipping_address'] = $receiptVoucherData[0]->shipping_address;
		$result['shipping_state'] = $receiptVoucherData[0]->shipping_state;
		$result['shipping_state_name'] = $receiptVoucherData[0]->shipping_state_name;
		$result['shipping_state_code'] = $receiptVoucherData[0]->shipping_state_code;
		$result['shipping_state_tin'] = $receiptVoucherData[0]->shipping_state_tin;
		$result['shipping_gstin_number'] = $receiptVoucherData[0]->shipping_gstin_number;
		$result['invoice_total_value'] = $receiptVoucherData[0]->invoice_total_value;
		$result['is_canceled'] = $receiptVoucherData[0]->is_canceled;

		$counter = 1;
		$rv_items = '';
		$receiptVoucherItems = $obj_client->get_results("select crvi.invoice_item_id, crvi.invoice_id, crvi.item_id, crvi.item_name, crvi.item_hsncode, crvi.taxable_subtotal, crvi.cgst_rate, crvi.cgst_amount, crvi.sgst_rate, crvi.sgst_amount, crvi.igst_rate, crvi.igst_amount, crvi.cess_rate, crvi.cess_amount, crvi.total from " . $obj_client->getTableName('client_rv_invoice_item') . " as crvi where 1=1 AND crvi.invoice_id = ".$result['invoice_id']." AND crvi.is_deleted = '0' AND crvi.status = '1' AND crvi.added_by = ". $obj_client->sanitize($_SESSION['user_detail']['user_id']));
		if(count($receiptVoucherItems) > 0) {
			
			foreach($receiptVoucherItems as $receiptVoucherItem) {

				$rv_items .= '<tr class="invoice_tr" data-row-id="'.$counter.'" id="invoice_tr_'.$counter.'">';
					$rv_items .= '<td><span class="serialno" id="invoice_tr_'.$counter.'_serialno" style="width:20px;">'.$counter.'</span><input type="hidden" id="invoice_tr_'.$counter.'_itemid" name="invoice_itemid[]" value="'.$receiptVoucherItem->item_id.'" /></td>';
					$rv_items .= '<td id="invoice_td_'.$counter.'_itemname"><p id="name_selection_'.$counter.'_choice" class="name_selection_choice" title="'.$receiptVoucherItem->item_name.'">'.$receiptVoucherItem->item_name.'</p></td>';
					$rv_items .= '<td><input type="text" id="invoice_tr_'.$counter.'_hsncode" name="invoice_hsncode[]" readonly="true" class="readonly" placeholder="HSN Code" value="'.$receiptVoucherItem->item_hsncode.'" style="width:100px;" /></td>';
					$rv_items .= '<td><div style="width:70px;" class="inptxt padrgt0"><i class="fa fa-inr"></i><input type="text" id="invoice_tr_'.$counter.'_taxablevalue" name="invoice_taxablevalue[]" class="validateInvoiceAmount invoiceTaxableValue pricinput required" placeholder="0.00" value="'.$receiptVoucherItem->taxable_subtotal.'" /></div></td>';
					$rv_items .= '<td><input type="text" id="invoice_tr_'.$counter.'_cgstrate" name="invoice_cgstrate[]" readonly="true" class="readonly pricinput" placeholder="0.00" value="'.$receiptVoucherItem->cgst_rate.'" style="width:40px;" /></td>';
					$rv_items .= '<td><div style="width:70px;" class="inptxt padrgt0"><i class="fa fa-inr"></i><input type="text" id="invoice_tr_'.$counter.'_cgstamount" name="invoice_cgstamount[]" readonly="true" class="readonly pricinput" placeholder="0.00" value="'.$receiptVoucherItem->cgst_amount.'" /></div></td>';
					$rv_items .= '<td><input type="text" id="invoice_tr_'.$counter.'_sgstrate" name="invoice_sgstrate[]" readonly="true" class="readonly pricinput" placeholder="0.00" value="'.$receiptVoucherItem->sgst_rate.'" style="width:90%;" /></td>';
					$rv_items .= '<td><div style="width:70px;" class="inptxt padrgt0"><i class="fa fa-inr"></i><input type="text" id="invoice_tr_'.$counter.'_sgstamount" name="invoice_sgstamount[]" readonly="true" class="readonly pricinput" placeholder="0.00" value="'.$receiptVoucherItem->sgst_amount.'" /></div></td>';
					$rv_items .= '<td><input type="text" id="invoice_tr_'.$counter.'_igstrate" name="invoice_igstrate[]" readonly="true" class="readonly pricinput" placeholder="0.00" value="'.$receiptVoucherItem->igst_rate.'" style="width:90%;" /></td>';
					$rv_items .= '<td><div style="width:70px;" class="inptxt padrgt0"><i class="fa fa-inr"></i><input type="text" id="invoice_tr_'.$counter.'_igstamount" name="invoice_igstamount[]" readonly="true" class="readonly pricinput" placeholder="0.00" value="'.$receiptVoucherItem->igst_amount.'" /></div></td>';
					$rv_items .= '<td><input type="text" id="invoice_tr_'.$counter.'_cessrate" name="invoice_cessrate[]" readonly="true" class="readonly pricinput" placeholder="0.00" value="'.$receiptVoucherItem->cess_rate.'" style="width:90%;" /></td>';
					$rv_items .= '<td><div style="width:70px;" class="inptxt padrgt0"><i class="fa fa-inr"></i><input type="text" id="invoice_tr_'.$counter.'_cessamount" name="invoice_cessamount[]" readonly="true" class="readonly pricinput" placeholder="0.00" value="'.$receiptVoucherItem->cess_amount.'" /></div></td>';
				$rv_items .= '</tr>';

				$counter++;
			}
		}
		
		$result['rv_items'] = $rv_items;
		$result['status'] = "success";
	} else {
		$result['status'] = "error";
	}
}

echo json_encode($result);
die;
?>