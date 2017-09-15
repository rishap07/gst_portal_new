<?php
    $obj_purchase = new purchase();

	if(!$obj_purchase->can_read('client_invoice')) {

		$obj_purchase->setError($obj_purchase->getValMsg('can_read'));
		$obj_purchase->redirect(PROJECT_URL."/?page=dashboard");
		exit();
	}

	if(!$obj_purchase->can_update('client_invoice')) {

		$obj_purchase->setError($obj_purchase->getValMsg('can_update'));
		$obj_purchase->redirect(PROJECT_URL."/?page=purchase_refund_voucher_invoice_list");
		exit();
	}

	if( isset($_GET['action']) && $_GET['action'] == 'editPurchaseRFInvoice' && isset($_GET['id']) && $obj_purchase->validateId($_GET['id'])) {

		$invid = $obj_purchase->sanitize($_GET['id']);
		$invoiceData = $obj_purchase->get_results("select 
													ci.*, 
													cii.purchase_invoice_item_id, 
													cii.item_id, 
													cii.item_name, 
													cii.item_hsncode, 
													cii.item_description, 
													cii.advance_amount, 
													cii.taxable_subtotal, 
													cii.cgst_rate, 
													cii.cgst_amount, 
													cii.sgst_rate, 
													cii.sgst_amount, 
													cii.igst_rate, 
													cii.igst_amount, 
													cii.cess_rate, 
													cii.cess_amount, 
													cii.total 
													from 
												" . $obj_purchase->getTableName('client_purchase_invoice') . " as ci INNER JOIN " . $obj_purchase->getTableName('client_purchase_invoice_item') . " as cii ON ci.purchase_invoice_id = cii.purchase_invoice_id where ci.purchase_invoice_id = " . $invid . " AND ci.invoice_type = 'refundvoucherinvoice' AND ci.added_by = '" . $obj_purchase->sanitize($_SESSION['user_detail']['user_id']) . "' AND cii.added_by = '" . $obj_purchase->sanitize($_SESSION['user_detail']['user_id']) . "' AND ci.is_deleted='0' AND cii.is_deleted='0'");
		
		if (empty($invoiceData)) {
			$obj_purchase->setError("No invoice found.");
			$obj_purchase->redirect(PROJECT_URL."?page=purchase_refund_voucher_invoice_list");
		}
	} else {
		$obj_purchase->redirect(PROJECT_URL."?page=purchase_refund_voucher_invoice_list");
	}

    $dataCurrentUserArr = $obj_purchase->getUserDetailsById( $obj_purchase->sanitize($_SESSION['user_detail']['user_id']) );
	$currentFinancialYear = $obj_purchase->generateFinancialYear();
?>
<!--========================admincontainer start=========================-->
<form name="create-invoice" id="create-invoice" method="POST">
	<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
		<div class="col-md-12 col-sm-12 col-xs-12">

			<div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>Refund Voucher Invoice</h1></div>

			<div class="clear"></div>

			<div class="whitebg formboxcontainer">

				<div class="errorValidationContainer">
					<?php $obj_purchase->showErrorMessage(); ?>
					<?php $obj_purchase->showSuccessMessge(); ?>
					<?php $obj_purchase->unsetMessage(); ?>
				</div>

				 <div class="row">
					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Refund Voucher <span class="starred">*</span></label>
						<input type="text" placeholder="Invoice Serial Number" readonly="true" class="form-control required" value="<?php echo $invoiceData[0]->serial_number; ?>" name="invoice_serial_number" id="invoice_serial_number" />
                        <input type="hidden" class="required" value="<?php echo base64_encode($invoiceData[0]->purchase_invoice_id); ?>" name="purchase_invoice_id" />
					</div>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Invoice Date <span class="starred">*</span></label>
						<input type="text" placeholder="YYYY-MM-DD" class="required form-control" data-bind="date" name="invoice_date" id="invoice_date" value="<?php echo $invoiceData[0]->invoice_date; ?>" />
					</div>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Reference Number <span class="starred">*</span></label>
						<input type="text" placeholder="Invoice Reference Number" class="required form-control" data-bind="content" value="<?php echo $invoiceData[0]->reference_number; ?>" name="invoice_reference_number" id="invoice_reference_number" />
					</div>
				 </div>

				 <div class="row">
					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Recipient Name <span class="starred">*</span></label>
						<input type="text" placeholder="Cyfuture India Pvt. Ltd" data-vendor-type="<?php if(isset($dataCurrentUserArr['data']->kyc->vendor_type)) { echo $dataCurrentUserArr['data']->kyc->vendor_type; } ?>" data-bind="content" readonly="true" class="form-control required" name="company_name" id="company_name" value="<?php echo $invoiceData[0]->company_name; ?>" />
					</div>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Recipient Address <span class="starred">*</span></label>
						<textarea placeholder="IT Park Rd, Sitapura Industrial Area, Sitapura" data-bind="content" readonly="true" class="form-control required" name="company_address" id="company_address"><?php echo $invoiceData[0]->company_address; ?></textarea>
					</div>
					
					<?php $company_state_data = $obj_purchase->getStateDetailByStateId($invoiceData[0]->company_state); ?>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Recipient State <span class="starred">*</span></label>
						<input type="text" placeholder="Compant State" data-bind="content" readonly="true" class="form-control required" name="company_state_name" id="company_state_name" value="<?php echo $company_state_data['data']->state_name; ?>" />
						<input type="hidden" readonly="true" class="required" class="required" data-state-id="<?php if(isset($company_state_data['data']->state_id)) { echo $company_state_data['data']->state_id; } ?>" data-state-code="<?php if(isset($company_state_data['data']->state_code)) { echo $company_state_data['data']->state_code; } ?>" data-country-id="<?php if(isset($dataCurrentUserArr['data']->kyc->country_id)) { echo $dataCurrentUserArr['data']->kyc->country_id; } ?>" data-country-code="<?php if(isset($dataCurrentUserArr['data']->kyc->country_code)) { echo $dataCurrentUserArr['data']->kyc->country_code; } ?>" name="company_state" id="company_state" value="<?php echo $invoiceData[0]->company_state; ?>" />
					</div>
				 </div>

				 <div class="row">
					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Recipient GSTIN <span class="starred">*</span></label>
						<input type="text" placeholder="BYRAJ14N3KKT" name="company_gstin_number" data-bind="gstin" readonly="true" class="form-control required" id="company_gstin_number" value="<?php echo $invoiceData[0]->company_gstin_number; ?>" />
					</div>
				 </div>

				 <div class="row">
					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Tax Is Payable On Reverse Charge <span class="starred">*</span></label><br/>
						<label class="radio-inline"><input type="radio" name="tax_reverse_charge" value="1" <?php if($invoiceData[0]->is_tax_payable === "1") { echo 'checked="checked"'; } ?> />Yes</label>
						<label class="radio-inline"><input type="radio" name="tax_reverse_charge" value="0" <?php if($invoiceData[0]->is_tax_payable === "0") { echo 'checked="checked"'; } ?> />No</label>
                    </div>

					<?php $supply_place_data = $obj_purchase->getStateDetailByStateId($invoiceData[0]->supply_place); ?>
					<div class="col-md-4 col-sm-4 col-xs-12 form-group placeofsupply">
						<label>Place Of Supply <span class="starred">*</span></label>
						<input type="text" placeholder="Supply State" data-bind="content" readonly="true" class="form-control required" name="place_of_supply_state" id="place_of_supply_state" value="<?php echo $supply_place_data['data']->state_name; ?>" />
						<input type="hidden" name="place_of_supply" readonly="true" class="required" id="place_of_supply" value="<?php echo $invoiceData[0]->supply_place; ?>" />
					</div>
				 </div>

				 <div class="row">
					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Receipt Voucher Number <span class="starred">*</span></label>
						<select name='receipt_voucher_number' id='receipt_voucher_number' class="required form-control">
							<option value=''>Select Receipt Voucher</option>								
							<?php $dataReceiptVoucherArrs = $obj_purchase->get_results("select purchase_invoice_id, serial_number, reference_number, invoice_date, supply_place, is_canceled from ".$obj_purchase->getTableName('client_purchase_invoice')." where status='1' and invoice_type = 'receiptvoucherinvoice' AND is_deleted='0' AND financial_year = '".$currentFinancialYear."' AND added_by = ".$obj_purchase->sanitize($_SESSION['user_detail']['user_id'])." order by serial_number asc"); ?>
							<?php if(!empty($dataReceiptVoucherArrs)) { ?>
								<?php foreach($dataReceiptVoucherArrs as $dataReceiptVoucherArr) { ?>

									<?php if($invoiceData[0]->refund_voucher_receipt === $dataReceiptVoucherArr->purchase_invoice_id) { ?>
										<option value='<?php echo $dataReceiptVoucherArr->purchase_invoice_id; ?>' data-serial="<?php echo $dataReceiptVoucherArr->serial_number; ?>" data-date="<?php echo $dataReceiptVoucherArr->invoice_date; ?>" selected="selected"><?php echo $dataReceiptVoucherArr->reference_number; ?></option>
									<?php } else { ?>
										<option value='<?php echo $dataReceiptVoucherArr->purchase_invoice_id; ?>' data-serial="<?php echo $dataReceiptVoucherArr->serial_number; ?>" data-date="<?php echo $dataReceiptVoucherArr->invoice_date; ?>"><?php echo $dataReceiptVoucherArr->reference_number; ?></option>
									<?php } ?>

								<?php } ?>
							<?php } ?>
						</select>
                    </div>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group placeofsupply">
						<label>Receipt Voucher Date <span class="starred">*</span></label>
						<input type="text" placeholder="YYYY-MM-DD" readonly="true" data-bind="date" name="receipt_voucher_date" id="receipt_voucher_date" class="form-control required" />
					</div>
				 </div>

				 <div class="row">

					<div class="col-md-6">
						<div class="greyborder inovicedeatil">
							<div class="formtitle">Supplier Detail</div>

							<div class="row form-group">
								<div class="col-md-4 col-sm-3 col-xs-12 padleftnone"><label>Contact Name</label> <span class="starred">*</span></div>
								<div class="col-md-8 col-sm-3 col-xs-12"><input type="text" placeholder="Contact Name" data-bind="content" readonly="true" class="required form-control" name="supplier_billing_name" id="supplier_billing_name" value="<?php echo $invoiceData[0]->supplier_billing_name; ?>" /></div>
							</div>

							<div class="row form-group">
								<div class="col-md-4 col-sm-3 col-xs-12 padleftnone"><label>Business Name</label></div>
								<div class="col-md-8 col-sm-3 col-xs-12"><input type="text" placeholder="Business Name" data-bind="content" readonly="true" class="form-control" name="supplier_billing_company_name" id="supplier_billing_company_name" value="<?php echo $invoiceData[0]->supplier_billing_company_name; ?>" /></div>
							</div>

							<div class="row form-group">
								<div class="col-md-4 col-sm-3 col-xs-12 padleftnone"><label>Address</label> <span class="starred">*</span></div>
								<div class="col-md-8 col-sm-3 col-xs-12"><textarea placeholder="Address" data-bind="content" class="form-control required" readonly="true" name="supplier_billing_address" id="supplier_billing_address"><?php echo $invoiceData[0]->supplier_billing_address; ?></textarea></div>
							</div>

							<?php $supplier_billing_state_data = $obj_purchase->getStateDetailByStateId($invoiceData[0]->supplier_billing_state); ?>
							<div class="row form-group">
								<div class="col-md-4 col-sm-3 col-xs-12 padleftnone"><label>State</label> <span class="starred">*</span></div>
								<div class="col-md-8 col-sm-3 col-xs-12"><input type="text" placeholder="State" data-bind="content" readonly="true" class="form-control required" name="supplier_billing_state_name" id="supplier_billing_state_name" value="<?php echo $supplier_billing_state_data['data']->state_name; ?>" /></div>
								<input type="hidden" class="required" name='supplier_billing_state' id='supplier_billing_state' value="<?php echo $invoiceData[0]->supplier_billing_state; ?>" />
								<input type="hidden" class="required" name='supplier_billing_state_code' id='supplier_billing_state_code' value="<?php echo $supplier_billing_state_data['data']->state_code; ?>" />
							</div>

							<?php $supplier_billing_country_data = $obj_purchase->getCountryDetailByCountryId($invoiceData[0]->supplier_billing_country); ?>
							<div class="row form-group">
								<div class="col-md-4 col-sm-3 col-xs-12 padleftnone"><label>Country</label> <span class="starred">*</span></div>
								<div class="col-md-8 col-sm-3 col-xs-12"><input type="text" placeholder="Country" data-bind="content" readonly="true" class="form-control required" name="supplier_billing_country_name" id="supplier_billing_country_name" value="<?php echo $supplier_billing_country_data['data']->country_name; ?>" /></div>
								<input type="hidden" class="required" name='supplier_billing_country' id='supplier_billing_country' value="<?php echo $invoiceData[0]->supplier_billing_country; ?>" />
								<input type="hidden" class="required" name='supplier_billing_country_code' id='supplier_billing_country_code' value="<?php echo $supplier_billing_country_data['data']->country_code; ?>" />
							</div>

							<?php $supplier_billing_vendor_data = $obj_purchase->getVendorDetailByVendorId($invoiceData[0]->supplier_billing_vendor_type); ?>
							<div class="row form-group">
								<div class="col-md-4 col-sm-3 col-xs-12 padleftnone"><label>Vendor Type</label> <span class="starred">*</span></div>
								<div class="col-md-8 col-sm-3 col-xs-12"><input type="text" placeholder="Vendor Type" data-bind="content" readonly="true" class="form-control required" name="supplier_billing_vendor_type_name" id="supplier_billing_vendor_type_name" value="<?php echo $supplier_billing_vendor_data['data']->vendor_name; ?>" /></div>
								<input type="hidden" class="required" name='supplier_billing_vendor_type' id='supplier_billing_vendor_type' value="<?php echo $invoiceData[0]->supplier_billing_vendor_type; ?>" />
							</div>

							<div class="row form-group">
								<div class="col-md-4 col-sm-3 col-xs-12 padleftnone"><label>GSTIN/UIN</label></div>
								<div class="col-md-8 col-sm-3 col-xs-12"><input type="text" placeholder="GSTIN/UIN" class="form-control" readonly="true" name='supplier_billing_gstin_number' data-bind="gstin" id='supplier_billing_gstin_number' value="<?php echo $invoiceData[0]->supplier_billing_gstin_number; ?>" /></div>
							</div>

						</div>
					</div>

					<div class="col-md-6">
						<div class="greyborder inovicedeatil">
							<div class="formtitle">Address Of Recipient / Shipping Detail</div>

							<div class="row form-group">
								<div class="col-md-4 col-sm-3 col-xs-12 padleftnone"><label>Contact Name</label> <span class="starred">*</span></div>
								<div class="col-md-8 col-sm-3 col-xs-12"><input type="text" placeholder="Contact Name" data-bind="content" readonly="true" class="required form-control" name="recipient_shipping_name" id="recipient_shipping_name" value="<?php echo $invoiceData[0]->recipient_shipping_name; ?>" /></div>
							</div>

							<div class="row form-group">
								<div class="col-md-4 col-sm-3 col-xs-12 padleftnone"><label>Business Name</label></div>
								<div class="col-md-8 col-sm-3 col-xs-12"><input type="text" placeholder="Business Name" data-bind="content" readonly="true" class="form-control" name="recipient_shipping_company_name" id="recipient_shipping_company_name" value="<?php echo $invoiceData[0]->recipient_shipping_company_name; ?>" /></div>
							</div>

							<div class="row form-group">
								<div class="col-md-4 col-sm-3 col-xs-12 padleftnone"><label>Address</label> <span class="starred">*</span></div>
								<div class="col-md-8 col-sm-3 col-xs-12"><textarea placeholder="Address" data-bind="content" class="required form-control" readonly="true" name="recipient_shipping_address" id="recipient_shipping_address"><?php echo $invoiceData[0]->recipient_shipping_address; ?></textarea></div>
							</div>
							
							<?php $recipient_shipping_state_data = $obj_purchase->getStateDetailByStateId($invoiceData[0]->recipient_shipping_state); ?>
							<div class="row form-group">
								<div class="col-md-4 col-sm-3 col-xs-12 padleftnone"><label>State</label> <span class="starred">*</span></div>
								<div class="col-md-8 col-sm-3 col-xs-12"><input type="text" placeholder="State" data-bind="content" readonly="true" class="form-control required" name="recipient_shipping_state_name" id="recipient_shipping_state_name" value="<?php echo $recipient_shipping_state_data['data']->state_name; ?>" /></div>
								<input type="hidden" class="required" name='recipient_shipping_state' id='recipient_shipping_state' value="<?php echo $invoiceData[0]->recipient_shipping_state; ?>" />
								<input type="hidden" class="required" name='recipient_shipping_state_code' id='recipient_shipping_state_code' value="<?php echo $recipient_shipping_state_data['data']->state_code; ?>" />
							</div>
							
							<?php $recipient_shipping_country_data = $obj_purchase->getCountryDetailByCountryId($invoiceData[0]->recipient_shipping_country); ?>
							<div class="row form-group">
								<div class="col-md-4 col-sm-3 col-xs-12 padleftnone"><label>Country</label> <span class="starred">*</span></div>
								<div class="col-md-8 col-sm-3 col-xs-12"><input type="text" placeholder="Country" data-bind="content" readonly="true" class="form-control required" name="recipient_shipping_country_name" id="recipient_shipping_country_name" value="<?php echo $recipient_shipping_country_data['data']->country_name; ?>" /></div>
								<input type="hidden" class="required" name='recipient_shipping_country' id='recipient_shipping_country' value="<?php echo $invoiceData[0]->recipient_shipping_country; ?>" />
								<input type="hidden" class="required" name='recipient_shipping_country_code' id='recipient_shipping_country_code' value="<?php echo $recipient_shipping_country_data['data']->country_code; ?>" />
							</div>

							<?php $recipient_shipping_vendor_data = $obj_purchase->getVendorDetailByVendorId($invoiceData[0]->recipient_shipping_vendor_type); ?>
							<div class="row form-group">
								<div class="col-md-4 col-sm-3 col-xs-12 padleftnone"><label>Vendor Type</label> <span class="starred">*</span></div>
								<div class="col-md-8 col-sm-3 col-xs-12"><input type="text" placeholder="Vendor Type" data-bind="content" readonly="true" class="form-control required" name="recipient_shipping_vendor_type_name" id="recipient_shipping_vendor_type_name" value="<?php echo $recipient_shipping_vendor_data['data']->vendor_name; ?>" /></div>
								<input type="hidden" class="required" name='recipient_shipping_vendor_type' id='recipient_shipping_vendor_type' value="<?php echo $invoiceData[0]->recipient_shipping_vendor_type; ?>" />
							</div>

							<div class="row form-group">
								<div class="col-md-4 col-sm-3 col-xs-12 padleftnone"><label>GSTIN</label></div>
								<div class="col-md-8 col-sm-3 col-xs-12"><input type="text" placeholder="GSTIN" class="form-control" readonly="true" name='recipient_shipping_gstin_number' data-bind="gstin" id='recipient_shipping_gstin_number' value="<?php echo $invoiceData[0]->recipient_shipping_gstin_number; ?>" /></div>
							</div>

						</div>
					</div>

				 </div>

				 <div class="clear height20"></div>

				 <div class="row">
					<div class="col-md-12 form-group">
						<label>Description</label>
						<textarea placeholder="Enter Description" class="form-control" name="description" id="description" data-bind="content"><?php echo $invoiceData[0]->description; ?></textarea>
					</div>
				 </div>

				 <div class="clear height40"></div>

				 <div class="table-responsive">
					<table width="100%" border="0" cellspacing="0" cellpadding="4" class="table invoicetable tablecontent">
						<tr>
							<th rowspan="2" class="active">S.No</th>
							<th rowspan="2" class="active">Description<br/> of Goods/Services</th>
							<th rowspan="2" class="active">HSN/SAC Code<br/>(GST)</th>
							<th rowspan="2" class="active">Item Description</th>
							<th rowspan="2" class="active">Advance<br/>value (<i class="fa fa-inr"></i>)</th>
							<th rowspan="2" class="active">Refund<br/>value (<i class="fa fa-inr"></i>)</th>
							<th colspan="2" class="active" style="border-bottom:1px solid #dddddd;">CGST</th>
							<th colspan="2" class="active" style="border-bottom:1px solid #dddddd;">SGST/UTGST</th>
							<th colspan="2" class="active" style="border-bottom:1px solid #dddddd;">IGST</th>
							<th colspan="2" class="active" style="border-bottom:1px solid #dddddd;">CESS</th>
						</tr>

						<tr class="gst-refund-vouchers">
							<th class="active">Rate(%)</th>
							<th class="active">Amount (<i class="fa fa-inr"></i>)</th>
							<th class="active">Rate(%)</th>
							<th class="active">Amount (<i class="fa fa-inr"></i>)</th>
							<th class="active">Rate(%)</th>
							<th class="active">Amount (<i class="fa fa-inr"></i>)</th>
							<th class="active">Rate(%)</th>
							<th class="active">Amount (<i class="fa fa-inr"></i>)</th>
						</tr>

						<?php $counter = 1; ?>
						<?php $total_cgst_amount = 0.00; ?>
						<?php $total_sgst_amount = 0.00; ?>
						<?php $total_igst_amount = 0.00; ?>
						<?php foreach($invoiceData as $invData) { ?>

							<tr class="invoice_tr" data-row-id="<?php echo $counter; ?>" id="invoice_tr_<?php echo $counter; ?>">
								<td>
									<span class="serialno" id="invoice_tr_<?php echo $counter; ?>_serialno"><?php echo $counter; ?></span>
									<input type="hidden" id="invoice_tr_<?php echo $counter; ?>_itemid" name="invoice_itemid[]" value="<?php echo $invData->item_id; ?>" class="required" />
								</td>
								<td id="invoice_td_<?php echo $counter; ?>_itemname">
									<p id="name_selection_<?php echo $counter; ?>_choice" class="name_selection_choice" title="<?php echo $invData->item_name; ?>">
										<?php echo $invData->item_name; ?>
									</p>
								</td>
								<td>
									<input type="text" id="invoice_tr_<?php echo $counter; ?>_hsncode" name="invoice_hsncode[]" readonly="true" class="inptxt" data-bind="content" placeholder="HSN/SAC Code" style="width:120px;" value="<?php echo $invData->item_hsncode; ?>" />
								</td>
								<td>
									<input type="text" id="invoice_tr_<?php echo $counter; ?>_description" name="invoice_description[]" class="inptxt" data-bind="content" placeholder="Enter Description" style="width:120px;" value="<?php echo $invData->item_description; ?>" />
								</td>
								<td>
                                    <div style="width:100px;" class="padrgt0">
                                        <input type="text" style="width:100%;" id="invoice_tr_<?php echo $counter; ?>_receiptvalue" name="invoice_receiptvalue[]" readonly="true" class="required validateDecimalValue invoiceReceiptValue inptxt" value="<?php echo $invData->advance_amount; ?>" data-bind="decimal" placeholder="0.00" />
                                    </div>
								</td>
								<td>
                                    <div style="width:100px;" class="padrgt0">
                                        <input type="text" style="width:100%;" id="invoice_tr_<?php echo $counter; ?>_taxablevalue" name="invoice_taxablevalue[]" class="required validateDecimalValue invoiceTaxableValue inptxt" value="<?php echo $invData->taxable_subtotal; ?>" data-bind="decimal" placeholder="0.00" />
                                    </div>
								</td>

								<?php if($invoiceData[0]->supplier_billing_state == $invoiceData[0]->supply_place) { ?>
								
									<td>
										<input type="text" id="invoice_tr_<?php echo $counter; ?>_cgstrate" name="invoice_cgstrate[]" class="inptxt validateTaxValue invcgstrate" value="<?php echo $invData->cgst_rate; ?>" data-bind="valtax" placeholder="0.00" style="width:75px;" />
									</td>
									<td>
										<div style="width:100px;" class="padrgt0">
											<input type="text" style="width:100%;" id="invoice_tr_<?php echo $counter; ?>_cgstamount" name="invoice_cgstamount[]" readonly="true" class="inptxt invcgstamount" placeholder="0.00" value="<?php echo $invData->cgst_amount; ?>" />
										</div>
									</td>
									<td>
										<input type="text" id="invoice_tr_<?php echo $counter; ?>_sgstrate" name="invoice_sgstrate[]" class="inptxt validateTaxValue invsgstrate" data-bind="valtax" value="<?php echo $invData->sgst_rate; ?>" placeholder="0.00" style="width:75px;" />
									</td>
									<td>
										<div style="width:100px;" class="padrgt0">
											<input type="text" style="width:100%;" id="invoice_tr_<?php echo $counter; ?>_sgstamount" name="invoice_sgstamount[]" readonly="true" class="inptxt invsgstamount" placeholder="0.00" value="<?php echo $invData->sgst_amount; ?>" />
										</div>
									</td>
									<td>
										<input type="text" id="invoice_tr_<?php echo $counter; ?>_igstrate" name="invoice_igstrate[]" readonly="true" class="inptxt validateTaxValue invigstrate" data-bind="valtax" value="0.00" placeholder="0.00" style="width:75px;" />
									</td>
									<td>
										<div style="width:100px;" class="padrgt0">
											<input type="text" style="width:100%;" id="invoice_tr_<?php echo $counter; ?>_igstamount" name="invoice_igstamount[]" readonly="true" class="inptxt invigstamount" value="0.00" placeholder="0.00" />
										</div>
									</td>
								
								<?php } else { ?>
								
									<td>
										<input type="text" id="invoice_tr_<?php echo $counter; ?>_cgstrate" name="invoice_cgstrate[]" readonly="true" class="inptxt validateTaxValue invcgstrate" value="0.00" data-bind="valtax" placeholder="0.00" style="width:75px;" />
									</td>
									<td>
										<div style="width:100px;" class="padrgt0">
											<input type="text" style="width:100%;" id="invoice_tr_<?php echo $counter; ?>_cgstamount" name="invoice_cgstamount[]" readonly="true" class="inptxt invcgstamount" placeholder="0.00" value="0.00" />
										</div>
									</td>
									<td>
										<input type="text" id="invoice_tr_<?php echo $counter; ?>_sgstrate" name="invoice_sgstrate[]" readonly="true" class="inptxt validateTaxValue invsgstrate" data-bind="valtax" value="0.00" placeholder="0.00" style="width:75px;" />
									</td>
									<td>
										<div style="width:100px;" class="padrgt0">
											<input type="text" style="width:100%;" id="invoice_tr_<?php echo $counter; ?>_sgstamount" name="invoice_sgstamount[]" readonly="true" class="inptxt invsgstamount" placeholder="0.00" value="0.00" />
										</div>
									</td>
									<td>
										<input type="text" id="invoice_tr_<?php echo $counter; ?>_igstrate" name="invoice_igstrate[]" class="inptxt validateTaxValue invigstrate" data-bind="valtax" value="<?php echo $invData->igst_rate; ?>" placeholder="0.00" style="width:75px;" />
									</td>
									<td>
										<div style="width:100px;" class="padrgt0">
											<input type="text" style="width:100%;" id="invoice_tr_<?php echo $counter; ?>_igstamount" name="invoice_igstamount[]" readonly="true" class="inptxt invigstamount" value="<?php echo $invData->igst_amount; ?>" placeholder="0.00" />
										</div>
									</td>

								<?php } ?>

								<td>
									<input type="text" id="invoice_tr_<?php echo $counter; ?>_cessrate" name="invoice_cessrate[]" class="inptxt validateTaxValue invcessrate" data-bind="valtax" value="<?php echo $invData->cess_rate; ?>" placeholder="0.00" style="width:75px;" />
								</td>
								<td>
									<div style="width:100px;" class="padrgt0">
										<input type="text" style="width:100%;" id="invoice_tr_<?php echo $counter; ?>_cessamount" name="invoice_cessamount[]" readonly="true" class="inptxt invcessamount" value="<?php echo $invData->cess_amount; ?>" placeholder="0.00" />
									</div>
								</td>

							</tr>

							<?php $total_cgst_amount += $invData->cgst_amount; ?>
							<?php $total_sgst_amount += $invData->sgst_amount; ?>
							<?php $total_igst_amount += $invData->igst_amount; ?>

							<?php $counter++; ?>
						<?php } ?>

						<tr class="consolidateTotal">
							<td colspan="4" align="right" class="lightblue fontbold textsmall">Total Invoice Value:</td>
							<td class="lightblue fontbold textsmall consolidateAdvanceTotal" align="center"><span>0.00</span></td>
							<td class="lightblue fontbold textsmall consolidateTaxableTotal" align="center"><span>0.00</span></td>
							<td class="lightblue fontbold textsmall" align="center"><span>-</span></td>
							<td class="lightblue fontbold textsmall consolidateCGSTTotal" align="center"><span>0.00</span></td>
							<td class="lightblue fontbold textsmall" align="center"><span>-</span></td>
							<td class="lightblue fontbold textsmall consolidateSGSTTotal" align="center"><span>0.00</span></td>
							<td class="lightblue fontbold textsmall" align="center"><span>-</span></td>
							<td class="lightblue fontbold textsmall consolidateIGSTTotal" align="center"><span>0.00</span></td>
							<td class="lightblue fontbold textsmall" align="center"><span>-</span></td>
							<td class="lightblue fontbold textsmall consolidateCESSTotal" align="center"><span>0.00</span></td>
						</tr>

						<tr class="rvcamount" <?php if($invoiceData[0]->is_tax_payable === "1") { echo 'style="display:table-row;"'; } ?>>
							<td colspan="6" align="right" class="lightgreen fontbold textsmall rvcamountftd">Amount of Tax Subject to Reverse Charge:</td>
							<td class="lightgreen fontbold textsmall rvccgst" align="center"><span>-</span></td>
							<td class="lightgreen fontbold textsmall rvccgstamount" align="center"><span>0.00</span></td>
							<td class="lightgreen fontbold textsmall rvcsgst" align="center"><span>-</span></td>
							<td class="lightgreen fontbold textsmall rvcsgstamount" align="center"><span>0.00</span></td>
							<td class="lightgreen fontbold textsmall rvcigst" align="center"><span>-</span></td>
							<td class="lightgreen fontbold textsmall rvcigstamount" align="center"><span>0.00</span></td>
							<td class="lightgreen fontbold textsmall rvccess" align="center"><span>-</span></td>
							<td class="lightgreen fontbold textsmall rvccessamount" align="center"><span>0.00</span></td>
						</tr>

						<tr>
							<td colspan="14" align="right" class="lightyellow totalamount">Total Invoice Value <span>(In Figure)</span><div class="totalprice"><i class="fa fa-inr"></i><span class="invoicetotalprice"><?php echo $invoiceData[0]->invoice_total_value; ?></span></div></td>
						</tr>

                        <?php $invoice_total_value_words = $obj_purchase->convert_number_to_words($invoiceData[0]->invoice_total_value); ?>
						<tr>
							<td colspan="14" align="right" class="lightpink fontbold totalamountwords" style="font-size:13px;">Total Invoice Value <small>(In Words):</small> <span class="totalpricewords"><?php echo ucwords($invoice_total_value_words); ?></span></td>
						</tr>

					</table>
				</div>

			</div>

			<div class="clear height100"></div>

		</div>
	</div>
       
	<div class="fixedfooter shadow">
		<div class="col-md-12">
			<a href="javascript:void(0)" class="btn txtorange orangeborder btngrey" data-toggle="modal" data-target="#addItemModal">Add Item</a>
			<a href="javascript:void(0)" class="btn btn-default btngrey" id="save_add_new_invoice">Update & Add New Invoice</a>
			<input type='submit' name="save_invoice" id="save_invoice" class="btn btn-default btn-success btnwidth" value="Update Invoice">
		</div>
	</div>
</form>
<!--CONTENT START HERE-->
<div class="clear"></div>

<!-- Add Item Modal -->
<div id="addItemModal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">

			<form name="add-item-form" id="add-item-form" method="POST">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Add Item</h4>
				</div>

				<div class="modal-body">

					<div class='row'>
						<div class='col-sm-12'>
							<div id="add-item-success" class="alert alert-success"></div>
							<div id="add-item-error" class="alert alert-danger"></div>
						</div>
					</div>

					<div class='row'>
						<div class='col-sm-4'>    
							<div class='form-group'>
								<label for="item_name">Item <span class="starred">*</span></label>
								<input type="text" placeholder="Item name" name='item_name' id="item_name" data-bind="content" class="required form-control" />
							</div>
						</div>

						<div class='col-sm-4'>    
							<div class='form-group'>
								<label for="item_category_name">HSN/SAC Category <span class="starred">*</span></label>
								<input type="text" placeholder="Item Category" name='item_category_name' id="item_category_name" data-bind="content" class="required form-control" />
								<input type="hidden" name='item_category' id="item_category" class="required" />
							</div>
						</div>

						<div class='col-sm-4'>    
							<div class='form-group'>
								<label for="item_hsn_code">HSN/SAC Code</label>
								<input type="text" placeholder="HSN/SAC Code" name="item_hsn_code" id="item_hsn_code" data-bind="content" class="required form-control" readonly="true" />
							</div>
						</div>
					</div>

					<div class='row'>
						<div class='col-sm-4'>    
							<div class='form-group'>
								<label for="is_applicable">Applicable Taxes <span class="starred">*</span></label>
								<select name="is_applicable" id="is_applicable" class="required form-control">
									<option value="0">Applicable</option>
									<option value="1">Non-GST</option>
									<option value="2">Exempted</option>
								</select>
							</div>
						</div>

						<div class='col-sm-4'>
							<div class='form-group'>
								<label for="unit_price">Unit Price(Rs.) </label>
								<input type="text" placeholder="Item Unit Price" name='unit_price' id="unit_price" data-bind="demical" class="form-control itemUnitPrice" />
							</div>
						</div>

						<div class='col-sm-4'>    
							<div class='form-group'>
								<label for="item_unit">Unit <span class="starred">*</span></label>
								<select name="item_unit" id="item_unit" class="required form-control" data-bind="numnzero">
									<?php $dataUnitArrs = $obj_purchase->getMasterUnits("unit_id,unit_name,unit_code,(case when status='1' Then 'active' when status='0' then 'deactive' end) as status", "is_deleted='0' AND status='1'"); ?>
									<?php if(!empty($dataUnitArrs)) { ?>
										<option value=''>Select Unit</option>
										<?php foreach($dataUnitArrs as $dataUnit) { ?>
											<option value='<?php echo $dataUnit->unit_id; ?>' data-unitcode="<?php echo $dataUnit->unit_code; ?>"><?php echo $dataUnit->unit_name; ?></option>
										<?php } ?>
									<?php } ?>
								</select>
							</div>
						</div>
						
					</div>
					
					<div class='row'>
						
						<div class='col-sm-4'>
							<label for="status">Status <span class="starred">*</span></label>
							<select name="status" id="status" class="required form-control">
								<option value="1">Active</option>
								<option value="0">Inactive</option>
							</select>
						</div>

						<div class='col-sm-4'>
							<div class='form-group'>
								<label for="item_description">Description </label>
								<textarea placeholder="Item Unit Price" name='item_description' id="item_description" data-bind="content" class="form-control" /></textarea>
							</div>
						</div>

					</div>

				</div>
				
				<div class="modal-footer">
					<input type='submit' class="btn btn-success" name='submit' value='SUBMIT' id='add-item-submit'>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>

			</form>

		</div>
	</div>
</div>

<!-- Amount Validation Modal -->
<div id="amountValidationModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Amount Validation</h4>
			</div>

			<div class="modal-body">
				<div class="alert alert-danger fade in">Amount too big.</div>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>

		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function () {
		
		/* call supply type change function */
		supplyTypeChange();

		/* set receipt voucher date  */
		var getreceiptvoucherdate = $("#receipt_voucher_number").find(':selected').attr("data-date");
		$("#receipt_voucher_date").val(getreceiptvoucherdate);

		/* Get HSN/SAC Code */
        $( "#item_category_name" ).autocomplete({
            minLength: 3,
            source: "<?php echo PROJECT_URL; ?>/?ajax=purchase_hsnsac_code",
            select: function( event, ui ) {
				$("#item_category").val(ui.item.item_id);
				$("#item_hsn_code").val(ui.item.hsn_code);
            }
        });
        /* End of Get HSN/SAC Code */

		/* Get on chnage of item category */
		$("#item_category_name").on("input", function() {
			$("#item_category").val("");
			$("#item_hsn_code").val("");
		});
		/* End of on chnage of item category */

		/* validate item unit price allow only numbers or decimals */
        $("#addItemModal").on("keypress input paste", ".itemUnitPrice", function (event) {
            return validateDecimalValue(event, this);
        });
        /* end of validate item unit price allow only numbers or decimals */

		/* validate add item form */
        $('#add-item-submit').click(function () {

            var mesg = {};
            if (vali.validate(mesg,'add-item-form')) {
                return true;
            }
            return false;
        });
		/* end of validate add item form */

		/* submit add new item form */
        $("#add-item-form").submit(function(event){

            event.preventDefault();

            $.ajax({
                data: {itemData:$("#add-item-form").serialize(), action:"addItem"},
                dataType: 'json',
                type: 'post',
                url: "<?php echo PROJECT_URL; ?>/?ajax=purchase_add_item",
                success: function(response){

                    if(response.status == "success") {
						$('#add-item-success').text(response.message);
						$('#add-item-success').fadeIn('slow').delay(3000).fadeOut();
                    } else {
						$('#add-item-error').text(response.message);
						$('#add-item-error').fadeIn('slow').delay(3000).fadeOut();
					}

                    $('#add-item-form')[0].reset();
                }
            });
        });
		/* end of submit add new item form */

		/* invoice date */
        $("#invoice_date").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
			maxDate: '0'
        });

		/* select2 js for receipt voucher number */
        $("#receipt_voucher_number").select2();

		/* on change receipt voucher */
        $("#receipt_voucher_number").change(function () {

            var receiptvoucherdate = $(this).find(':selected').attr("data-date");
            if(typeof(receiptvoucherdate) === "undefined") {

				$("#receipt_voucher_date").val("");
				$("#place_of_supply_state").val("");
				$("#place_of_supply").val("");
				$("#supplier_billing_name").val("");
				$("#supplier_billing_company_name").val("");
				$("#supplier_billing_address").val("");
				$("#supplier_billing_state").val("");
				$("#supplier_billing_state_code").val("");
				$("#supplier_billing_state_name").val("");
				$("#supplier_billing_country_name").val("");
				$("#supplier_billing_country").val("");
				$("#supplier_billing_country_code").val("");
				$("#supplier_billing_vendor_type_name").val("");
				$("#supplier_billing_vendor_type").val("");
				$("#supplier_billing_gstin_number").val("");
				$("#recipient_shipping_name").val("");
				$("#recipient_shipping_company_name").val("");
				$("#recipient_shipping_address").val("");
				$("#recipient_shipping_state_name").val("");
				$("#recipient_shipping_state").val("");
				$("#recipient_shipping_state_code").val("");
				$("#recipient_shipping_country_name").val("");
				$("#recipient_shipping_country").val("");
				$("#recipient_shipping_country_code").val("");
				$("#recipient_shipping_vendor_type_name").val("");
				$("#recipient_shipping_vendor_type").val("");
				$("#recipient_shipping_gstin_number").val("");				
				$('input[name=tax_reverse_charge][value=0]').prop('checked', 'checked');
				$(".rvcamount").hide();
				$(".invoice_tr").remove();
				return false;
            } else {
                $("#receipt_voucher_date").val(receiptvoucherdate);
            }

			/* get receipt voucher detail */
			$.ajax({
				data: {receiptVoucherId:$(this).find(':selected').val(), action:"getReceiptVoucher"},
				dataType: 'json',
				type: 'post',
				url: "<?php echo PROJECT_URL; ?>/?ajax=purchase_get_receipt_voucher_detail",
				success: function(response){

					$(".invoice_tr").remove();
					if(response.status == "success") {

						$("#place_of_supply_state").val(response.supply_state_name);
						$("#place_of_supply").val(response.supply_place);
						$("#supplier_billing_name").val(response.supplier_billing_name);
						$("#supplier_billing_company_name").val(response.supplier_billing_company_name);
						$("#supplier_billing_address").val(response.supplier_billing_address);
						$("#supplier_billing_state").val(response.supplier_billing_state);
						$("#supplier_billing_state_code").val(response.supplier_billing_state_code);
						$("#supplier_billing_state_name").val(response.supplier_billing_state_name);
						$("#supplier_billing_country_name").val(response.supplier_billing_country_name);
						$("#supplier_billing_country").val(response.supplier_billing_country);
						$("#supplier_billing_country_code").val(response.supplier_billing_country_code);
						$("#supplier_billing_vendor_type_name").val(response.supplier_billing_vendor_name);
						$("#supplier_billing_vendor_type").val(response.supplier_billing_vendor_type);
						$("#supplier_billing_gstin_number").val(response.supplier_billing_gstin_number);
						$("#recipient_shipping_name").val(response.recipient_shipping_name);
						$("#recipient_shipping_company_name").val(response.recipient_shipping_company_name);
						$("#recipient_shipping_address").val(response.recipient_shipping_address);
						$("#recipient_shipping_state_name").val(response.recipient_shipping_state_name);
						$("#recipient_shipping_state").val(response.recipient_shipping_state);
						$("#recipient_shipping_state_code").val(response.recipient_shipping_state_code);
						$("#recipient_shipping_country_name").val(response.recipient_shipping_country_name);
						$("#recipient_shipping_country").val(response.recipient_shipping_country);
						$("#recipient_shipping_country_code").val(response.recipient_shipping_country_code);
						$("#recipient_shipping_vendor_type_name").val(response.recipient_shipping_vendor_name);
						$("#recipient_shipping_vendor_type").val(response.recipient_shipping_vendor_type);
						$("#recipient_shipping_gstin_number").val(response.recipient_shipping_gstin_number);
						$(".gst-refund-vouchers").after(response.rv_items);
						$('input[name=tax_reverse_charge][value='+response.tax_reverse_charge+']').prop('checked', 'checked');

						if(response.tax_reverse_charge == "1") {
							$(".rvcamount").show();
						} else {
							$(".rvcamount").hide();
						}
                    } else {
						alert(response.status);
					}

					/* call function of total invoice */
					rowInvoiceCalculationOnStateChnage();
				}
			});
			/* end of get receipt voucher details */
        });
		/* end of on change receipt voucher */

		/* on advance amount chnage of item */
        $(".invoicetable").on("input", ".invoiceTaxableValue", function () {

            var rowid = $(this).parent().parent().parent().attr("data-row-id");
            var currentTrItemId = parseInt($("#invoice_tr_" + rowid + "_itemid").val());
            rowInvoiceCalculation(currentTrItemId, rowid);
        });
        /* end of on advance amount chnage of item */

		/* on cgst rate chnage of item */
        $(".invoicetable").on("input", ".invcgstrate", function(){

			var rowid = $(this).parent().parent().attr("data-row-id");
            var currentTrItemId = parseInt($("#invoice_tr_"+rowid+"_itemid").val());
            rowInvoiceCalculation(currentTrItemId, rowid);
        });
        /* end of on cgst rate chnage of item */

		/* on sgst rate chnage of item */
        $(".invoicetable").on("input", ".invsgstrate", function(){

			var rowid = $(this).parent().parent().attr("data-row-id");
            var currentTrItemId = parseInt($("#invoice_tr_"+rowid+"_itemid").val());
            rowInvoiceCalculation(currentTrItemId, rowid);
        });
        /* end of on sgst rate chnage of item */
		
		/* on igst rate chnage of item */
        $(".invoicetable").on("input", ".invigstrate", function(){

			var rowid = $(this).parent().parent().attr("data-row-id");
            var currentTrItemId = parseInt($("#invoice_tr_"+rowid+"_itemid").val());
            rowInvoiceCalculation(currentTrItemId, rowid);
        });
        /* end of on igst rate chnage of item */
		
		/* on cess rate chnage of item */
        $(".invoicetable").on("input", ".invcessrate", function(){

			var rowid = $(this).parent().parent().attr("data-row-id");
            var currentTrItemId = parseInt($("#invoice_tr_"+rowid+"_itemid").val());
            rowInvoiceCalculation(currentTrItemId, rowid);
        });
        /* end of on cess rate chnage of item */

		/* validate invoice decimal values allow only numbers or decimals */
        $(".invoicetable").on("keypress input paste", ".validateDecimalValue", function (event) {
            return validateDecimalValue(event, this);
        });
        /* end of validate invoice decimal values allow only numbers or decimals */
		
		/* validate invoice tax decimal values allow only numbers or decimals */
        $(".invoicetable").on("keypress input paste", ".validateTaxValue", function (event) {
            return validateTaxValue(event, this);
        });
        /* end of validate invoice tax decimal values allow only numbers or decimals */
		
		/* on change supply type */
		$('input[type=radio][name=tax_reverse_charge]').change(function() {
			supplyTypeChange();
		});
		/* end of on change supply type */

		/* validate invoice form */
        $('#save_invoice').click(function () {

			var mesg = {};
            if (vali.validate(mesg, 'create-invoice')) {
                return true;
            }
            return false;
        });
		/* end of validate invoice form */

		/* save and add new invoice */
		$("#save_add_new_invoice").click(function(){

			var flag = 0;
			var mesg = {};
            if (vali.validate(mesg, 'create-invoice')) {
                flag = 1;
            }

			if(flag === 1) {

				var finalInvoiceValue = $( ".totalprice .invoicetotalprice" ).text();
				if(finalInvoiceValue.length > 16) {
					$("#amountValidationModal").modal("show");
					return false;
				}

				$("#loading").show();
				$.ajax({
					data: {invoiceData:$("#create-invoice").serialize(), action:"saveUpdatePurchaseRFInvoice"},
					dataType: 'json',
					type: 'post',
					url: "<?php echo PROJECT_URL; ?>/?ajax=purchase_refund_voucher_invoice_save_update",
					success: function(response){

						$("#loading").hide();
						if(response.status == "error") {
							
							$(".errorValidationContainer").html(response.message);
							$(".errorValidationContainer").show();
							$('html, body').animate({ scrollTop: $(".formcontainer").offset().top }, 1000);
						} else if(response.status == "success") {
							
							$(".errorValidationContainer").html("");
							$(".errorValidationContainer").hide();
							window.location.href = '<?php echo PROJECT_URL; ?>/?page=purchase_refund_voucher_invoice_create';
						}
					}
				});
			}
		});
		/* end of save and add new invoice */

		/* save new invoice */
        $("#create-invoice").submit(function(event){

            event.preventDefault();
			
			var finalInvoiceValue = $( ".totalprice .invoicetotalprice" ).text();
			if(finalInvoiceValue.length > 16) {
				$("#amountValidationModal").modal("show");
				return false;
			}

			$("#loading").show();
			$.ajax({
                data: {invoiceData:$("#create-invoice").serialize(), action:"saveUpdatePurchaseRFInvoice"},
                dataType: 'json',
                type: 'post',
                url: "<?php echo PROJECT_URL; ?>/?ajax=purchase_refund_voucher_invoice_save_update",
                success: function(response){

					$("#loading").hide();
                    if(response.status == "error") {

						$(".errorValidationContainer").html(response.message);
                        $(".errorValidationContainer").show();
						$('html, body').animate({ scrollTop: $(".formcontainer").offset().top }, 1000);
                    } else if(response.status == "success") {

                        $(".errorValidationContainer").html("");
                        $(".errorValidationContainer").hide();
                        window.location.href = '<?php echo PROJECT_URL; ?>/?page=purchase_refund_voucher_invoice_list';
                    }
                }
            });
        });
        /* end of save new item */
		
		function supplyTypeChange() {

			var supplyType = $('input[name=tax_reverse_charge]:checked', '#create-invoice').val();

			if(supplyType == "1") {
				$(".rvcamount").show();
			} else {
				$(".rvcamount").hide();
			}

			/* calculate row invoice and invoice total on receiver state change */
            rowInvoiceCalculationOnStateChnage();
		}
		
		/* calculate row invoice on state change function */
        function rowInvoiceCalculationOnStateChnage() {

            $( "tr.invoice_tr" ).each(function( index ) {

                var rowid = $(this).attr("data-row-id");

				if($("#invoice_tr_"+rowid+"_itemid").val() != '' && $("#invoice_tr_"+rowid+"_itemid").val() > 0) {

                    var itemid = $("#invoice_tr_"+rowid+"_itemid").val();
                    rowInvoiceCalculation(itemid, rowid);
                }
            });
        }
        /* end of calculate row invoice on state change function */

		/* calculate row invoice function */
        function rowInvoiceCalculation(itemid, rowid) {

			var supplierStateId = $("#supplier_billing_state").val();
			var receiverStateId = $("#place_of_supply").val();

			/* calculation */

			/* get all tax rates */
			if($.trim($("#invoice_tr_"+rowid+"_cgstrate").val()).length == 0 || $.trim($("#invoice_tr_"+rowid+"_cgstrate").val()).length == '' || $.trim($("#invoice_tr_"+rowid+"_cgstrate").val()) == '.') {
				var currentCGSTRate = 0.00;
			} else {
				var currentCGSTRate = parseFloat($("#invoice_tr_"+rowid+"_cgstrate").val());
			}

			if($.trim($("#invoice_tr_"+rowid+"_sgstrate").val()).length == 0 || $.trim($("#invoice_tr_"+rowid+"_sgstrate").val()).length == '' || $.trim($("#invoice_tr_"+rowid+"_sgstrate").val()) == '.') {
				var currentSGSTRate = 0.00;
			} else {
				var currentSGSTRate = parseFloat($("#invoice_tr_"+rowid+"_sgstrate").val());
			}

			if($.trim($("#invoice_tr_"+rowid+"_igstrate").val()).length == 0 || $.trim($("#invoice_tr_"+rowid+"_igstrate").val()).length == '' || $.trim($("#invoice_tr_"+rowid+"_igstrate").val()) == '.') {
				var currentIGSTRate = 0.00;
			} else {
				var currentIGSTRate = parseFloat($("#invoice_tr_"+rowid+"_igstrate").val());
			}

			if($.trim($("#invoice_tr_"+rowid+"_cessrate").val()).length == 0 || $.trim($("#invoice_tr_"+rowid+"_cessrate").val()).length == '' || $.trim($("#invoice_tr_"+rowid+"_cessrate").val()) === '.') {
				var currentCESSRate = 0.00;
			} else {
				var currentCESSRate = parseFloat($("#invoice_tr_"+rowid+"_cessrate").val());
			}

			/* end of get all tax rates */

			if ($.trim($("#invoice_tr_" + rowid + "_taxablevalue").val()).length == 0 || $.trim($("#invoice_tr_" + rowid + "_taxablevalue").val()).length == '' || $.trim($("#invoice_tr_" + rowid + "_taxablevalue").val()) == '.') {
				var currentTrTaxableValue = 0.00;
			} else {
				var currentTrTaxableValue = parseFloat($("#invoice_tr_" + rowid + "_taxablevalue").val());
			}

			if(supplierStateId === receiverStateId) {

				$("#invoice_tr_"+rowid+"_igstrate").val(0.00);
				$("#invoice_tr_"+rowid+"_igstamount").val(0.00);

				$("#invoice_tr_"+rowid+"_cgstrate").prop("readonly", false);
				$("#invoice_tr_"+rowid+"_sgstrate").prop("readonly", false);
				$("#invoice_tr_"+rowid+"_igstrate").prop("readonly", true);
				$("#invoice_tr_"+rowid+"_cessrate").prop("readonly", false);

				var cgstTax = parseFloat(currentCGSTRate);
				var cgstTaxAmount = (cgstTax/100) * currentTrTaxableValue;
				$("#invoice_tr_"+rowid+"_cgstamount").val(cgstTaxAmount.toFixed(2));

				var sgstTax = parseFloat(currentSGSTRate);
				var sgstTaxAmount = (sgstTax/100) * currentTrTaxableValue;
				$("#invoice_tr_"+rowid+"_sgstamount").val(sgstTaxAmount.toFixed(2));
				
				var cessTax = parseFloat(currentCESSRate);
				var cessTaxAmount = (cessTax/100) * currentTrTaxableValue;
				$("#invoice_tr_"+rowid+"_cessamount").val(cessTaxAmount.toFixed(2));
			} else {

				$("#invoice_tr_"+rowid+"_cgstrate").val(0.00);
				$("#invoice_tr_"+rowid+"_sgstrate").val(0.00);
				$("#invoice_tr_"+rowid+"_cgstamount").val(0.00);
				$("#invoice_tr_"+rowid+"_sgstamount").val(0.00);

				$("#invoice_tr_"+rowid+"_cgstrate").prop("readonly", true);
				$("#invoice_tr_"+rowid+"_sgstrate").prop("readonly", true);
				$("#invoice_tr_"+rowid+"_igstrate").prop("readonly", false);
				$("#invoice_tr_"+rowid+"_cessrate").prop("readonly", false);

				var igstTax = parseFloat(currentIGSTRate);
				var igstTaxAmount = (igstTax/100) * currentTrTaxableValue;
				$("#invoice_tr_"+rowid+"_igstamount").val(igstTaxAmount.toFixed(2));
				
				var cessTax = parseFloat(currentCESSRate);
				var cessTaxAmount = (cessTax/100) * currentTrTaxableValue;
				$("#invoice_tr_"+rowid+"_cessamount").val(cessTaxAmount.toFixed(2));
			}
			/* end of calculation */

			/* call function of total invoice */
			totalInvoiceValueCalculation();
        }
        /* end of calculate row invoice function */

		/* calculate total invoice value function */
        function totalInvoiceValueCalculation() {

            var totalInvoiceValue = 0.00;
			var totalInvoiceCGSTValue = 0.00;
			var totalInvoiceSGSTValue = 0.00;
			var totalInvoiceIGSTValue = 0.00;
			var totalInvoiceCESSValue = 0.00;
			var invsupplyType = $('input[name=tax_reverse_charge]:checked', '#create-invoice').val();
			$( "tr.invoice_tr" ).each(function( index ) {

                var rowid = $(this).attr("data-row-id");

                if($("#invoice_tr_"+rowid+"_itemid").val() != '' && $("#invoice_tr_"+rowid+"_itemid").val() > 0) {

					var taxablevalue = parseFloat($("#invoice_tr_"+rowid+"_taxablevalue").val());
                    var cgstamount = parseFloat($("#invoice_tr_"+rowid+"_cgstamount").val());
                    var sgstamount = parseFloat($("#invoice_tr_"+rowid+"_sgstamount").val());
                    var igstamount = parseFloat($("#invoice_tr_"+rowid+"_igstamount").val());
					var cessamount = parseFloat($("#invoice_tr_"+rowid+"_cessamount").val());

					totalInvoiceCGSTValue += cgstamount;
					totalInvoiceSGSTValue += sgstamount;
					totalInvoiceIGSTValue += igstamount;
					totalInvoiceCESSValue += cessamount;

					if(invsupplyType == "1") {
						totalInvoiceValue += taxablevalue;
					} else {
						totalInvoiceValue += (taxablevalue + cgstamount + sgstamount + igstamount + cessamount);
					}
                }
            });

			totalFinalInvoiceValue = totalInvoiceValue.toFixed(2);
			$( ".totalprice .invoicetotalprice" ).text(totalFinalInvoiceValue);

			if(totalFinalInvoiceValue.length > 16) {
				$("#amountValidationModal").modal("show");
				return false;
			}
			
			if(invsupplyType == "1") {

				$(".rvcamount .rvccgst span").html("-");
				$(".rvcamount .rvccgstamount span").html(totalInvoiceCGSTValue.toFixed(2));

				$(".rvcamount .rvcsgst span").html("-");
				$(".rvcamount .rvcsgstamount span").html(totalInvoiceSGSTValue.toFixed(2));

				$(".rvcamount .rvcigst span").html("-");
				$(".rvcamount .rvcigstamount span").html(totalInvoiceIGSTValue.toFixed(2));

				$(".rvcamount .rvccess span").html("-");
				$(".rvcamount .rvccessamount span").html(totalInvoiceCESSValue.toFixed(2));
			}

			$.ajax({
                data: {totalInvoiceValue:totalFinalInvoiceValue, action:"numberToWords"},
                dataType: 'json',
                type: 'post',
                url: "<?php echo PROJECT_URL; ?>/?ajax=purchase_convert_number_to_words",
                success: function(response){

                    if(response.status == "success") {
                        $( ".totalamountwords .totalpricewords" ).text(response.invoicevalue);
                    } else {
                        $( ".totalamountwords .totalpricewords" ).text("<?php echo $obj_purchase->getValMsg('failed'); ?>");
                    }
                }
            });
			
			/* calculate consolidate total */
			calculationConsolidateTotal();
        }
        /* end of calculate total invoice value function */
		
		/* calculate consolidate total function */
        function calculationConsolidateTotal() {

			/* advance total sum */
			var invoiceAdvanceTotal = 0.00;
			$('input[name="invoice_receiptvalue[]"]').each(function() {

				if($.trim($(this).val()).length == 0 || $.trim($(this).val()).length == '' || $.trim($(this).val()) == '.') {
					var invoiceRowAdvanceTotal = 0.00;
				} else {
					var invoiceRowAdvanceTotal = $(this).val();
				}

				invoiceAdvanceTotal += parseFloat(invoiceRowAdvanceTotal);
			});
			$(".consolidateTotal .consolidateAdvanceTotal span").html(invoiceAdvanceTotal.toFixed(2));

			/* taxable total sum */
			var invoiceTaxableTotal = 0.00;
			$('input[name="invoice_taxablevalue[]"]').each(function() {

				if($.trim($(this).val()).length == 0 || $.trim($(this).val()).length == '' || $.trim($(this).val()) == '.') {
					var invoiceRowTaxableTotal = 0.00;
				} else {
					var invoiceRowTaxableTotal = $(this).val();
				}

				invoiceTaxableTotal += parseFloat(invoiceRowTaxableTotal);
			});
			$(".consolidateTotal .consolidateTaxableTotal span").html(invoiceTaxableTotal.toFixed(2));
			
			/* CGST total sum */
			var invoiceCGSTTotal = 0.00;
			$('input[name="invoice_cgstamount[]"]').each(function() {

				if($.trim($(this).val()).length == 0 || $.trim($(this).val()).length == '' || $.trim($(this).val()) == '.') {
					var invoiceRowCGSTTotal = 0.00;
				} else {
					var invoiceRowCGSTTotal = $(this).val();
				}

				invoiceCGSTTotal += parseFloat(invoiceRowCGSTTotal);
			});
			$(".consolidateTotal .consolidateCGSTTotal span").html(invoiceCGSTTotal.toFixed(2));
			
			/* SGST total sum */
			var invoiceSGSTTotal = 0.00;
			$('input[name="invoice_sgstamount[]"]').each(function() {

				if($.trim($(this).val()).length == 0 || $.trim($(this).val()).length == '' || $.trim($(this).val()) == '.') {
					var invoiceRowSGSTTotal = 0.00;
				} else {
					var invoiceRowSGSTTotal = $(this).val();
				}

				invoiceSGSTTotal += parseFloat(invoiceRowSGSTTotal);
			});
			$(".consolidateTotal .consolidateSGSTTotal span").html(invoiceSGSTTotal.toFixed(2));

			/* IGST total sum */
			var invoiceIGSTTotal = 0.00;
			$('input[name="invoice_igstamount[]"]').each(function() {

				if($.trim($(this).val()).length == 0 || $.trim($(this).val()).length == '' || $.trim($(this).val()) == '.') {
					var invoiceRowIGSTTotal = 0.00;
				} else {
					var invoiceRowIGSTTotal = $(this).val();
				}

				invoiceIGSTTotal += parseFloat(invoiceRowIGSTTotal);
			});
			$(".consolidateTotal .consolidateIGSTTotal span").html(invoiceIGSTTotal.toFixed(2));
			
			/* CESS total sum */
			var invoiceCESSTotal = 0.00;
			$('input[name="invoice_cessamount[]"]').each(function() {

				if($.trim($(this).val()).length == 0 || $.trim($(this).val()).length == '' || $.trim($(this).val()) == '.') {
					var invoiceRowCESSTotal = 0.00;
				} else {
					var invoiceRowCESSTotal = $(this).val();
				}

				invoiceCESSTotal += parseFloat(invoiceRowCESSTotal);
			});
			$(".consolidateTotal .consolidateCESSTotal span").html(invoiceCESSTotal.toFixed(2));
		}
		/* end of calculate consolidate total function */
    });
</script>