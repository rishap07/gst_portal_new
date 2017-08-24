<?php
/*
    * 
    *  Developed By        :   Ishwar Lal Ghiya
    *  Date Created        :   June 02, 2017
    *  Last Modification   :   Get item detail
    * 
*/

$obj_purchase = new purchase();
$result = array();
if(isset($_POST['receiptVoucherId']) && isset($_POST['action']) && $_POST['action'] == "getReceiptVoucher") {

	$receiptVoucherId = $obj_purchase->sanitize($_POST['receiptVoucherId']);
	$currentFinancialYear = $obj_purchase->generateFinancialYear();
	$receiptVoucherData = $obj_purchase->get_results("select 
													crv.purchase_invoice_id, 
													crv.serial_number, 
													crv.reference_number, 
													crv.invoice_type, 
													crv.invoice_date, 
													crv.company_state, 
													crv.supply_place, 
													sp.state_name as supply_state_name, 
													sp.state_code as supply_state_code, 
													sp.state_tin as supply_state_tin, 
													crv.supplier_billing_name, 
													crv.supplier_billing_company_name, 
													crv.supplier_billing_address, 
													crv.supplier_billing_state, 
													bs.state_name as supplier_billing_state_name, 
													bs.state_code as supplier_billing_state_code, 
													bs.state_tin as supplier_billing_state_tin, 
													crv.supplier_billing_country, 
													bc.country_code as supplier_billing_country_code, 
													bc.country_name as supplier_billing_country_name, 
													crv.supplier_billing_vendor_type, 
													bv.vendor_name as supplier_billing_vendor_name, 
													crv.supplier_billing_gstin_number, 
													crv.recipient_shipping_name, 
													crv.recipient_shipping_company_name, 
													crv.recipient_shipping_address, 
													crv.recipient_shipping_state, 
													ss.state_name as recipient_shipping_state_name, 
													ss.state_code as recipient_shipping_state_code, 
													ss.state_tin as recipient_shipping_state_tin, 
													crv.recipient_shipping_country, 
													sc.country_code as recipient_shipping_country_code, 
													sc.country_name as recipient_shipping_country_name, 
													crv.recipient_shipping_vendor_type, 
													sv.vendor_name as recipient_shipping_vendor_name, 
													crv.recipient_shipping_gstin_number, 
													crv.invoice_total_value, 
													crv.is_canceled 
													from " . $obj_purchase->getTableName('client_purchase_invoice') . " as crv 
													LEFT JOIN " . $obj_purchase->getTableName('state') . " as sp on crv.supply_place = sp.state_id 
													LEFT JOIN " . $obj_purchase->getTableName('state') . " as bs on crv.supplier_billing_state = bs.state_id 
													LEFT JOIN " . $obj_purchase->getTableName('state') . " as ss ON crv.recipient_shipping_state = ss.state_id 
													LEFT JOIN " . $obj_purchase->getTableName('country') . " as bc ON crv.supplier_billing_country = bc.id 
													LEFT JOIN " . $obj_purchase->getTableName('country') . " as sc ON crv.recipient_shipping_country = sc.id 
													LEFT JOIN " . $obj_purchase->getTableName('vendor_type') . " as bv ON crv.supplier_billing_vendor_type = bv.vendor_id 
													LEFT JOIN " . $obj_purchase->getTableName('vendor_type') . " as sv ON crv.recipient_shipping_vendor_name = sv.vendor_id 
													where 1=1 AND crv.purchase_invoice_id = '".$receiptVoucherId."' AND crv.invoice_type = 'receiptvoucherinvoice' AND crv.is_deleted = '0' AND crv.status = '1' AND crv.financial_year = '".$currentFinancialYear."' AND crv.added_by = ". $obj_purchase->sanitize($_SESSION['user_detail']['user_id']));
													
	if(count($receiptVoucherData) > 0) {

		$result['purchase_invoice_id'] = $receiptVoucherData[0]->purchase_invoice_id;
		$result['serial_number'] = $receiptVoucherData[0]->serial_number;
		$result['reference_number'] = $receiptVoucherData[0]->reference_number;
		$result['invoice_date'] = $receiptVoucherData[0]->invoice_date;
		$result['company_state'] = $receiptVoucherData[0]->company_state;
		$result['supply_place'] = $receiptVoucherData[0]->supply_place;
		$result['supply_state_name'] = $receiptVoucherData[0]->supply_state_name;
		$result['supply_state_code'] = $receiptVoucherData[0]->supply_state_code;
		$result['supply_state_tin'] = $receiptVoucherData[0]->supply_state_tin;
		$result['supplier_billing_state'] = html_entity_decode($receiptVoucherData[0]->supplier_billing_state);
		$result['billing_company_name'] = html_entity_decode($receiptVoucherData[0]->billing_company_name);
		$result['billing_address'] = html_entity_decode($receiptVoucherData[0]->billing_address);
		$result['billing_state'] = $receiptVoucherData[0]->billing_state;
		$result['billing_state_name'] = html_entity_decode($receiptVoucherData[0]->billing_state_name);
		$result['billing_state_code'] = $receiptVoucherData[0]->billing_state_code;
		$result['billing_state_tin'] = $receiptVoucherData[0]->billing_state_tin;
		$result['billing_country'] = $receiptVoucherData[0]->billing_country;
		$result['billing_country_code'] = $receiptVoucherData[0]->billing_country_code;
		$result['billing_country_name'] = html_entity_decode($receiptVoucherData[0]->billing_country_name);
		$result['billing_vendor_type'] = $receiptVoucherData[0]->billing_vendor_type;
		$result['billing_vendor_name'] = html_entity_decode($receiptVoucherData[0]->billing_vendor_name);
		$result['billing_gstin_number'] = $receiptVoucherData[0]->billing_gstin_number;
		$result['shipping_name'] = html_entity_decode($receiptVoucherData[0]->shipping_name);
		$result['shipping_company_name'] = html_entity_decode($receiptVoucherData[0]->shipping_company_name);
		$result['shipping_address'] = html_entity_decode($receiptVoucherData[0]->shipping_address);
		$result['shipping_state'] = $receiptVoucherData[0]->shipping_state;
		$result['shipping_state_name'] = html_entity_decode($receiptVoucherData[0]->shipping_state_name);
		$result['shipping_state_code'] = $receiptVoucherData[0]->shipping_state_code;
		$result['shipping_state_tin'] = $receiptVoucherData[0]->shipping_state_tin;
		$result['shipping_country'] = $receiptVoucherData[0]->shipping_country;
		$result['shipping_country_code'] = $receiptVoucherData[0]->shipping_country_code;
		$result['shipping_country_name'] = html_entity_decode($receiptVoucherData[0]->shipping_country_name);
		$result['shipping_vendor_type'] = $receiptVoucherData[0]->shipping_vendor_type;
		$result['shipping_vendor_name'] = html_entity_decode($receiptVoucherData[0]->shipping_vendor_name);
		$result['shipping_gstin_number'] = $receiptVoucherData[0]->shipping_gstin_number;
		$result['invoice_total_value'] = $receiptVoucherData[0]->invoice_total_value;
		$result['is_canceled'] = $receiptVoucherData[0]->is_canceled;

		$counter = 1;
		$rv_items = '';
		$receiptVoucherItems = $obj_purchase->get_results("select 
														crvi.purchase_invoice_item_id, 
														crvi.purchase_invoice_id, 
														crvi.item_id, 
														crvi.item_name, 
														crvi.item_hsncode, 
														crvi.taxable_subtotal, 
														crvi.cgst_rate, 
														crvi.cgst_amount, 
														crvi.sgst_rate, 
														crvi.sgst_amount, 
														crvi.igst_rate, 
														crvi.igst_amount, 
														crvi.cess_rate, 
														crvi.cess_amount, 
														crvi.total 
														from " . $obj_purchase->getTableName('client_purchase_invoice_item') . " as crvi where 1=1 AND crvi.purchase_invoice_id = ".$result['purchase_invoice_id']." AND crvi.is_deleted = '0' AND crvi.status = '1' AND crvi.added_by = ". $obj_purchase->sanitize($_SESSION['user_detail']['user_id']));
		if(count($receiptVoucherItems) > 0) {

			foreach($receiptVoucherItems as $receiptVoucherItem) {

				$rv_items .= '<tr class="invoice_tr" data-row-id="'.$counter.'" id="invoice_tr_'.$counter.'">';

					$rv_items .= '<td class="text-center"><span class="serialno" id="invoice_tr_'.$counter.'_serialno">'.$counter.'</span><input type="hidden" id="invoice_tr_'.$counter.'_itemid" name="invoice_itemid[]" value="'.$receiptVoucherItem->item_id.'" class="required" /></td>';
					$rv_items .= '<td id="invoice_td_'.$counter.'_itemname"><p id="name_selection_'.$counter.'_choice" class="name_selection_choice" title="'.$receiptVoucherItem->item_name.'">'.$receiptVoucherItem->item_name.'</p></td>';
					$rv_items .= '<td><input type="text" id="invoice_tr_'.$counter.'_hsncode" name="invoice_hsncode[]" readonly="true" class="inptxt" placeholder="HSN/SAC Code" value="'.$receiptVoucherItem->item_hsncode.'" style="width:120px;" /></td>';
					$rv_items .= '<td><div style="width:100px;" class="padrgt0"><i class="fa fa-inr"></i><input type="text" style="width:90%;" id="invoice_tr_'.$counter.'_receiptvalue" name="invoice_receiptvalue[]" readonly="true" class="required validateDecimalValue invoiceReceiptValue inptxt" value="'.$receiptVoucherItem->taxable_subtotal.'" data-bind="decimal" placeholder="0.00" /></div></td>';
					$rv_items .= '<td><div style="width:100px;" class="padrgt0"><i class="fa fa-inr"></i><input type="text" style="width:90%;" id="invoice_tr_'.$counter.'_taxablevalue" name="invoice_taxablevalue[]" class="required validateDecimalValue invoiceTaxableValue inptxt" placeholder="0.00" data-bind="decimal" value="0.00" /></div></td>';

					if($result['supplier_billing_state'] === $result['supply_place']) {

						$rv_items .= '<td><input type="text" id="invoice_tr_'.$counter.'_cgstrate" name="invoice_cgstrate[]" class="inptxt validateTaxValue invcgstrate" value="'.$receiptVoucherItem->cgst_rate.'" data-bind="valtax" placeholder="0.00" style="width:75px;" /></td>';
						$rv_items .= '<td><div style="width:100px;" class="padrgt0"><i class="fa fa-inr"></i><input type="text" style="width:90%;" id="invoice_tr_'.$counter.'_cgstamount" name="invoice_cgstamount[]" readonly="true" class="inptxt invcgstamount" placeholder="0.00" value="'.$receiptVoucherItem->cgst_amount.'" /></div></td>';
						$rv_items .= '<td><input type="text" id="invoice_tr_'.$counter.'_sgstrate" name="invoice_sgstrate[]" class="inptxt validateTaxValue invsgstrate" data-bind="valtax" value="'.$receiptVoucherItem->sgst_rate.'" placeholder="0.00" style="width:75px;" /></td>';
						$rv_items .= '<td><div style="width:100px;" class="padrgt0"><i class="fa fa-inr"></i><input type="text" style="width:90%;" id="invoice_tr_'.$counter.'_sgstamount" name="invoice_sgstamount[]" readonly="true" class="inptxt invsgstamount" placeholder="0.00" value="'.$receiptVoucherItem->sgst_amount.'" /></div></td>';
						$rv_items .= '<td><input type="text" id="invoice_tr_'.$counter.'_igstrate" name="invoice_igstrate[]" readonly="true" class="inptxt validateTaxValue invigstrate" data-bind="valtax" value="0.00" placeholder="0.00" style="width:75px;" /></td>';
						$rv_items .= '<td><div style="width:100px;" class="padrgt0"><i class="fa fa-inr"></i><input type="text" style="width:90%;" id="invoice_tr_'.$counter.'_igstamount" name="invoice_igstamount[]" readonly="true" class="inptxt invigstamount" value="0.00" placeholder="0.00" /></div></td>';
					} else {

						$rv_items .= '<td><input type="text" id="invoice_tr_'.$counter.'_cgstrate" name="invoice_cgstrate[]" readonly="true" class="inptxt validateTaxValue invcgstrate" value="0.00" data-bind="valtax" placeholder="0.00" style="width:75px;" /></td>';
						$rv_items .= '<td><div style="width:100px;" class="padrgt0"><i class="fa fa-inr"></i><input type="text" style="width:90%;" id="invoice_tr_'.$counter.'_cgstamount" name="invoice_cgstamount[]" readonly="true" class="inptxt invcgstamount" placeholder="0.00" value="0.00" /></div></td>';
						$rv_items .= '<td><input type="text" id="invoice_tr_'.$counter.'_sgstrate" name="invoice_sgstrate[]" readonly="true" class="inptxt validateTaxValue invsgstrate" value="0.00" data-bind="valtax" placeholder="0.00" style="width:75px;" /></td>';
						$rv_items .= '<td><div style="width:100px;" class="padrgt0"><i class="fa fa-inr"></i><input type="text" style="width:90%;" id="invoice_tr_'.$counter.'_sgstamount" name="invoice_sgstamount[]" readonly="true" class="inptxt invsgstamount" placeholder="0.00" value="0.00" /></div></td>';
						$rv_items .= '<td><input type="text" id="invoice_tr_'.$counter.'_igstrate" name="invoice_igstrate[]" class="inptxt validateTaxValue invigstrate" data-bind="valtax" value="'.$receiptVoucherItem->igst_rate.'" placeholder="0.00" style="width:75px;" /></td>';
						$rv_items .= '<td><div style="width:100px;" class="padrgt0"><i class="fa fa-inr"></i><input type="text" style="width:90%;" id="invoice_tr_'.$counter.'_igstamount" name="invoice_igstamount[]" readonly="true" class="inptxt invigstamount" value="'.$receiptVoucherItem->igst_amount.'" placeholder="0.00" /></div></td>';
					}

					$rv_items .= '<td><input type="text" id="invoice_tr_'.$counter.'_cessrate" name="invoice_cessrate[]" class="inptxt validateTaxValue invcessrate" data-bind="valtax" value="'.$receiptVoucherItem->cess_rate.'" placeholder="0.00" style="width:75px;" /></td><td>';
					$rv_items .= '<div style="width:100px;" class="padrgt0"><i class="fa fa-inr"></i><input type="text" style="width:90%;" id="invoice_tr_'.$counter.'_cessamount" name="invoice_cessamount[]" readonly="true" class="inptxt invcessamount" value="'.$receiptVoucherItem->cess_amount.'" placeholder="0.00" /></div></td>';
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