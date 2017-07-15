<?php
    $obj_client = new client();

	if(!$obj_client->can_read('client_invoice')) {

		$obj_client->setError($obj_client->getValMsg('can_read'));
		$obj_client->redirect(PROJECT_URL."/?page=dashboard");
		exit();
	}

	if(!$obj_client->can_update('client_invoice')) {

		$obj_client->setError($obj_client->getValMsg('can_update'));
		$obj_client->redirect(PROJECT_URL."/?page=client_invoice_list");
		exit();
	}

	if( isset($_GET['action']) && $_GET['action'] == 'editInvoice' && isset($_GET['id']) && $obj_client->validateId($_GET['id'])) {

		$invid = $obj_client->sanitize($_GET['id']);
		$invoiceData = $obj_client->get_results("select 
													ci.invoice_id, 
													ci.invoice_type, 
													ci.invoice_nature, 
													ci.reference_number, 
													ci.serial_number, 
													ci.company_name, 
													ci.company_address, 
													ci.company_state, 
													ci.gstin_number, 
													ci.supply_type, 
													ci.export_supply_meant, 
													ci.invoice_date, 
													ci.supply_place, 
													ci.ecommerce_gstin_number, 
													ci.ecommerce_vendor_code, 
													ci.advance_adjustment, 
													ci.receipt_voucher_number, 
													ci.billing_name, 
													ci.billing_company_name, 
													ci.billing_address, 
													ci.billing_state, 
													ci.billing_state_name, 
													ci.billing_country, 
													ci.billing_gstin_number, 
													ci.shipping_name, 
													ci.shipping_company_name, 
													ci.shipping_address, 
													ci.shipping_state, 
													ci.shipping_state_name, 
													ci.shipping_country, 
													ci.export_bill_number, 
													ci.export_bill_date, 
													ci.shipping_gstin_number, 
													ci.description, 
													ci.invoice_total_value, 
													ci.status, 
													ci.is_canceled, 
													cii.invoice_item_id, 
													cii.item_id, 
													cii.item_name, 
													cii.item_hsncode, 
													cii.item_quantity, 
													cii.item_unit, 
													cii.item_unit_price, 
													cii.subtotal, 
													cii.discount, 
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
													cii.total ,
													cii.status as item_status 
													from 
												" . $obj_client->getTableName('client_invoice') ." as ci INNER JOIN " . $obj_client->getTableName('client_invoice_item') ." as cii ON ci.invoice_id = cii.invoice_id where ci.invoice_id = ".$invid." AND ci.added_by = '".$obj_client->sanitize($_SESSION['user_detail']['user_id'])."' AND cii.added_by = '".$obj_client->sanitize($_SESSION['user_detail']['user_id'])."' AND ci.is_deleted='0' AND cii.is_deleted='0'");

		if (empty($invoiceData)) {
			$obj_client->setError("No invoice found.");
			$obj_client->redirect(PROJECT_URL."?page=client_invoice_list");
		}
	} else {
		$obj_client->redirect(PROJECT_URL."?page=client_invoice_list");
	}

    $dataCurrentUserArr = $obj_client->getUserDetailsById( $obj_client->sanitize($_SESSION['user_detail']['user_id']) );
	$currentFinancialYear = $obj_client->generateFinancialYear();
?>
<!--========================admincontainer start=========================-->
<form name="create-invoice" id="create-invoice" method="POST">
	<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
		<div class="col-md-12 col-sm-12 col-xs-12">

			<div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>Generate Tax Export Invoice</h1></div>

			<div class="clear"></div>

			<div class="whitebg formboxcontainer">
				
				<div class="errorValidationContainer">
					<?php $obj_client->showErrorMessage(); ?>
					<?php $obj_client->showSuccessMessge(); ?>
					<?php $obj_client->unsetMessage(); ?>
				</div>
				
				<div class="row">
					
					<div class="col-md-12 col-sm-12 col-xs-12 form-group">
						<label>Export Supply Meant <span class="starred">*</span></label>
						<div class="radio">
							<label><input type="radio" name="export_supply_meant" value="withpayment" <?php if($invoiceData[0]->export_supply_meant === "withpayment") { echo 'checked="checked"'; } ?>>SUPPLY MEANT FOR EXPORT ON PAYMENT OF INTEGRATED TAX</label>
						</div>
						<div class="radio">
							<label><input type="radio" name="export_supply_meant" value="withoutpayment" <?php if($invoiceData[0]->export_supply_meant === "withoutpayment") { echo 'checked="checked"'; } ?>>SUPPLY MEANT FOR EXPORT UNDER BOND OR LETTER OF UNDERTAKING WITHOUT PAYMENT OF INTEGRATED TAX</label>
						</div>
					</div>
				</div>

				 <div class="row">

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Invoice Serial Number <span class="starred">*</span></label>
						<input type="text" placeholder="Invoice Serial Number" readonly="true" class="form-control required" value="<?php echo $invoiceData[0]->serial_number; ?>" name="invoice_serial_number" id="invoice_serial_number" />
						<input type="hidden" class="required" value="<?php echo base64_encode($invoiceData[0]->invoice_id); ?>" name="invoice_id" />
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
						<label>Supplier Name <span class="starred">*</span></label>
						<input type="text" placeholder="Cyfuture India Pvt. Ltd" data-bind="content" readonly="true" class="form-control required" name="company_name" id="company_name" value="<?php echo $invoiceData[0]->company_name; ?>" />
					</div>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Supplier Address <span class="starred">*</span></label>
						<input type="text" placeholder="Cyfuture India Pvt. Ltd" data-bind="content" readonly="true" class="form-control required" name="company_address" id="company_address" value="<?php echo $invoiceData[0]->company_address; ?>" />
					</div>
					
					<?php $company_state_data = $obj_client->getStateDetailByStateId($invoiceData[0]->company_state); ?>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Supplier State <span class="starred">*</span></label>
						<input type="text" placeholder="Compant State" data-bind="content" readonly="true" class="form-control required" name="company_state" id="company_state" value="<?php echo $company_state_data['data']->state_name; ?>" />
						<input type="hidden" readonly="true" class="required" name="company_state_id" id="company_state_id" value="<?php echo $invoiceData[0]->company_state; ?>" />
					</div>

				 </div>
				 
				 <div class="row">

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Supplier GSTIN <span class="starred">*</span></label>
						<input type="text" placeholder="BYRAJ14N3KKT" name="company_gstin_number" data-bind="gstin" readonly="true" class="form-control required" id="company_gstin_number" value="<?php echo $invoiceData[0]->gstin_number; ?>" />
					</div>

				 </div>

				 <div class="row">
					
					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Advance Adjustment <span class="starred">*</span></label><br>
						<label class="radio-inline"><input type="radio" name="advance_adjustment" value="1" <?php if($invoiceData[0]->advance_adjustment == 1) { echo 'checked="checked"'; } ?> />Yes</label>
						<label class="radio-inline"><input type="radio" name="advance_adjustment" value="0" <?php if($invoiceData[0]->advance_adjustment == 0) { echo 'checked="checked"'; } ?> />No</label>
					</div>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group receiptvouchernumber" <?php if($invoiceData[0]->advance_adjustment == 1) { echo 'style="display:block;"'; } ?>>
						<label>Receipt Voucher Number <span class="starred">*</span></label>
						<select name='receipt_voucher_number' id='receipt_voucher_number' class="form-control">
							<?php $dataReceiptVoucherArrs = $obj_client->get_results("select invoice_id, serial_number, invoice_date, supply_place, is_canceled from ".$obj_client->getTableName('client_rv_invoice')." where status='1' and is_deleted='0' AND financial_year = '".$currentFinancialYear."' AND added_by = ".$obj_client->sanitize($_SESSION['user_detail']['user_id'])." order by serial_number asc"); ?>
							<?php if(!empty($dataReceiptVoucherArrs)) { ?>
								<option value=''>Select Receipt Voucher</option>
								<?php foreach($dataReceiptVoucherArrs as $dataReceiptVoucherArr) { ?>

									<?php if($invoiceData[0]->invoice_id === $dataReceiptVoucherArr->invoice_id) { ?>
										<option value='<?php echo $dataReceiptVoucherArr->invoice_id; ?>' data-date="<?php echo $dataReceiptVoucherArr->invoice_date; ?>" selected="selected"><?php echo $dataReceiptVoucherArr->serial_number; ?></option>
									<?php } else { ?>
										<option value='<?php echo $dataReceiptVoucherArr->invoice_id; ?>' data-date="<?php echo $dataReceiptVoucherArr->invoice_date; ?>"><?php echo $dataReceiptVoucherArr->serial_number; ?></option>
									<?php } ?>

								<?php } ?>
							<?php } ?>
						</select>
					</div>

				 </div>

				 <div class="row">
					
					<div class="col-md-6">
						<div class="greyborder inovicedeatil">
							<div class="formtitle">Recipient Detail</div>

							<div class="row form-group">
								<div class="col-md-4 col-sm-3 col-xs-12 padleftnone"><label>Contact Name</label> <span class="starred">*</span></div>
								<div class="col-md-8 col-sm-3 col-xs-12"><input type="text" placeholder="Contact Name" data-bind="content" class="required form-control" name="billing_name" id="billing_name" value="<?php echo $invoiceData[0]->billing_name; ?>" /></div>
							</div>
							
							<div class="row form-group">
								<div class="col-md-4 col-sm-3 col-xs-12 padleftnone"><label>Business Name</label> <span class="starred">*</span></div>
								<div class="col-md-8 col-sm-3 col-xs-12"><input type="text" placeholder="Business Name" data-bind="content" class="form-control" name="billing_company_name" id="billing_company_name" value="<?php echo $invoiceData[0]->billing_company_name; ?>" /></div>
							</div>

							<div class="row form-group">
								<div class="col-md-4 col-sm-3 col-xs-12 padleftnone"><label>Address</label> <span class="starred">*</span></div>
								<div class="col-md-8 col-sm-3 col-xs-12"><textarea placeholder="Address" data-bind="content" class="form-control required" name="billing_address" id="billing_address"><?php echo $invoiceData[0]->billing_address; ?></textarea></div>
							</div>

							<div class="row form-group">
								<div class="col-md-4 col-sm-3 col-xs-12 padleftnone"><label>State</label> <span class="starred">*</span></div>
								<div class="col-md-8 col-sm-3 col-xs-12"><input type="text" placeholder="Billing State Name" data-bind="content" class="form-control required" name="billing_state_name" id="billing_state_name" value="<?php echo $invoiceData[0]->billing_state_name; ?>" /></div>
							</div>

							<div class="row form-group">
								<div class="col-md-4 col-sm-3 col-xs-12 padleftnone"><label>Country</label> <span class="starred">*</span></div>
								<div class="col-md-8 col-sm-3 col-xs-12">
									<select name='billing_country' id='billing_country' class='required form-control'>
										<?php $dataBCountryArrs = $obj_client->get_results("select * from ".$obj_client->getTableName('country')." order by country_name asc"); ?>
										<?php if(!empty($dataBCountryArrs)) { ?>
											<option value=''>Select Country</option>
											<?php foreach($dataBCountryArrs as $dataBCountryArr) { ?>
												
												<?php if($invoiceData[0]->billing_country == $dataBCountryArr->id) { ?>
													<option value='<?php echo $dataBCountryArr->id; ?>' data-code="<?php echo $dataBCountryArr->country_code; ?>" selected="selected"><?php echo $dataBCountryArr->country_name . " (" . $dataBCountryArr->country_code . ")"; ?></option>
												<?php } else { ?>
													<option value='<?php echo $dataBCountryArr->id; ?>' data-code="<?php echo $dataBCountryArr->country_code; ?>"><?php echo $dataBCountryArr->country_name . " (" . $dataBCountryArr->country_code . ")"; ?></option>
												<?php } ?>
											
											<?php } ?>

										<?php } ?>
									</select>
								</div>
							</div>

						</div>
					</div>

					<div class="col-md-6">
						<div class="greyborder inovicedeatil">
							<div class="formtitle">Address Of Delivery / Shipping Detail <small class="pull-right">Same as billing <input name="same_as_billing" id="same_as_billing" value="1" type="checkbox"></small></div>

							<div class="row form-group">
								<div class="col-md-4 col-sm-3 col-xs-12 padleftnone"><label>Contact Name</label> <span class="starred">*</span></div>
								<div class="col-md-8 col-sm-3 col-xs-12"><input type="text" placeholder="Contact Name" data-bind="content" class="required form-control" name="shipping_name" id="shipping_name" value="<?php echo $invoiceData[0]->shipping_name; ?>" /></div>
							</div>
							
							<div class="row form-group">
								<div class="col-md-4 col-sm-3 col-xs-12 padleftnone"><label>Business Name</label></div>
								<div class="col-md-8 col-sm-3 col-xs-12"><input type="text" placeholder="Business Name" data-bind="content" class="form-control" name="shipping_company_name" id="shipping_company_name" value="<?php echo $invoiceData[0]->shipping_company_name; ?>" /></div>
							</div>

							<div class="row form-group">
								<div class="col-md-4 col-sm-3 col-xs-12 padleftnone"><label>Address</label> <span class="starred">*</span></div>
								<div class="col-md-8 col-sm-3 col-xs-12"><textarea placeholder="Address" data-bind="content" class="required form-control" name="shipping_address" id="shipping_address"><?php echo $invoiceData[0]->shipping_address; ?></textarea></div>
							</div>
							
							<div class="row form-group">
								<div class="col-md-4 col-sm-3 col-xs-12 padleftnone"><label>State</label> <span class="starred">*</span></div>
								<div class="col-md-8 col-sm-3 col-xs-12"><input type="text" placeholder="Shipping State Name" data-bind="content" class="required form-control" name="shipping_state_name" id="shipping_state_name" value="<?php echo $invoiceData[0]->shipping_state_name; ?>" /></div>
							</div>

							<div class="row form-group">
								<div class="col-md-4 col-sm-3 col-xs-12 padleftnone"><label>Country</label> <span class="starred">*</span></div>
								<div class="col-md-8 col-sm-3 col-xs-12">
									<select name='shipping_country' id='shipping_country' class='required form-control'>
										<?php $dataSCountryArrs = $obj_client->get_results("select * from ".$obj_client->getTableName('country')." order by country_name asc"); ?>
										<?php if(!empty($dataSCountryArrs)) { ?>
											<option value=''>Select Country</option>
											<?php foreach($dataSCountryArrs as $dataSCountryArr) { ?>

												<?php if($invoiceData[0]->shipping_country == $dataSCountryArr->id) { ?>
													<option value='<?php echo $dataSCountryArr->id; ?>' data-code="<?php echo $dataSCountryArr->country_code; ?>" selected="selected"><?php echo $dataSCountryArr->country_name . " (" . $dataSCountryArr->country_code . ")"; ?></option>
												<?php } else { ?>
													<option value='<?php echo $dataSCountryArr->id; ?>' data-code="<?php echo $dataSCountryArr->country_code; ?>"><?php echo $dataSCountryArr->country_name . " (" . $dataSCountryArr->country_code . ")"; ?></option>
												<?php } ?>

											<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>

							<div class="row form-group">
								<div class="col-md-4 col-sm-3 col-xs-12 padleftnone"><label>Export Bill Number</label> <span class="starred">*</span></div>
								<div class="col-md-8 col-sm-3 col-xs-12"><input type="text" placeholder="Export Bill Number" name='export_bill_number' class="form-control required" id='export_bill_number' data-bind="content" value="<?php echo $invoiceData[0]->export_bill_number; ?>" /></div>
							</div>

							<div class="row form-group">
								<div class="col-md-4 col-sm-3 col-xs-12 padleftnone"><label>Export Bill Date</label> <span class="starred">*</span></div>
								<div class="col-md-8 col-sm-3 col-xs-12"><input type="text" placeholder="Export Bill Date" class="form-control required" name='export_bill_date' id='export_bill_date' data-bind="date" value="<?php echo $invoiceData[0]->export_bill_date; ?>" /></div>
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
							<th rowspan="2" class="active">Qty</th>
							<th rowspan="2" class="active">Unit</th>
							<th rowspan="2" class="active">Rate <br/><span style="font-family: open_sans; font-size:11px;">per item</span></th>
							<th rowspan="2" class="active">Total</th>
							<th rowspan="2" class="active">Discount</th>
							<th rowspan="2" class="advancecol active" <?php if($invoiceData[0]->advance_adjustment == 1) { echo 'style="display:table-cell;"'; } ?>>Advance</th>
							<th rowspan="2" class="active">Taxable<br/>value</th>
							<th colspan="2" class="active" style="border-bottom:1px solid #dddddd;">IGST</th>
							<th colspan="2" class="active" style="border-bottom:1px solid #dddddd;">CESS</th>
							<th class="active" style="border-bottom:1px solid #dddddd;"></th>
						</tr>

						<tr>
							<th class="active">Rate(%)</th>
							<th class="active">Amount</th>
							<th class="active">Rate(%)</th>
							<th class="active">Amount</th>
							<th class="active"></th>
						</tr>

						<?php $counter = 1; ?>
						<?php foreach($invoiceData as $invData) { ?>

							<tr class="invoice_tr" data-row-id="<?php echo $counter; ?>" id="invoice_tr_<?php echo $counter; ?>">
								<td>
									<span class="serialno" id="invoice_tr_<?php echo $counter; ?>_serialno"><?php echo $counter; ?></span>
									<input type="hidden" id="invoice_tr_<?php echo $counter; ?>_itemid" name="invoice_itemid[]" value="<?php echo $invData->item_id; ?>" class="required" />
								</td>
								<td id="invoice_td_<?php echo $counter; ?>_itemname">
									<p id="name_selection_<?php echo $counter; ?>_choice" class="name_selection_choice" title="<?php echo $invData->item_name; ?>">
										<span id="name_selection_<?php echo $counter; ?>_choice_remove" data-selectable-id="<?php echo $counter; ?>" class="name_selection_choice_remove" role="presentation">×</span>
										<?php echo $invData->item_name; ?>
									</p>
								</td>
								<td>
									<input type="text" id="invoice_tr_<?php echo $counter; ?>_hsncode" name="invoice_hsncode[]" readonly="true" class="inptxt" data-bind="content" placeholder="HSN/SAC Code" style="width:120px;" value="<?php echo $invData->item_hsncode; ?>" />
								</td>
								<td>
									<input type="number" min="1" id="invoice_tr_<?php echo $counter; ?>_quantity" name="invoice_quantity[]" class="required invoiceQuantity inptxt" value="<?php echo $invData->item_quantity; ?>" placeholder="0" style="width:100px;" />
								</td>
								<td>
									<select name="invoice_unit[]" id="invoice_tr_<?php echo $counter; ?>_unit" class="required inptxt" style="width:100px;">
										<?php $masterUnitArrs = $obj_client->getMasterUnits("unit_id,unit_name,unit_code,(case when status='1' Then 'active' when status='0' then 'deactive' end) as status", "is_deleted='0' AND status='1'"); ?>
										<?php if(!empty($masterUnitArrs)) { ?>
											<option value=''>Select Unit</option>
											<?php foreach($masterUnitArrs as $masterUnitArr) { ?>

												<?php if($masterUnitArr->unit_code == $invData->item_unit) { ?>
													<option value='<?php echo $masterUnitArr->unit_code; ?>' selected="selected"><?php echo $masterUnitArr->unit_name; ?></option>
												<?php } else { ?>
													<option value='<?php echo $masterUnitArr->unit_code; ?>'><?php echo $masterUnitArr->unit_name; ?></option>
												<?php } ?>

											<?php } ?>
										<?php } ?>
									</select>
								</td>
								<td>
									<div class="padrgt0" style="width:100px;">
										<i class="fa fa-inr"></i><input type="text" style="width:90%;" id="invoice_tr_<?php echo $counter; ?>_rate" name="invoice_rate[]" class="required validateInvoiceAmount invoiceRateValue inptxt" data-bind="decimal" value="<?php echo $invData->item_unit_price; ?>" placeholder="0.00" />
									</div>
								</td>
								<td>
									<div class="padrgt0" style="width:100px;">
										<i class="fa fa-inr"></i><input type="text" style="width:90%;" id="invoice_tr_<?php echo $counter; ?>_total" name="invoice_total[]" readonly="true" class="inptxt" value="<?php echo $invData->subtotal; ?>" class="inptxt" placeholder="0.00" />
									</div>
								</td>
								<td>
									<input type="text" style="width:100%;" id="invoice_tr_<?php echo $counter; ?>_discount" name="invoice_discount[]" class="inptxt invoiceDiscount" value="<?php echo $invData->discount; ?>" data-bind="decimal" placeholder="0.00" />
								</td>
								<td class="advancecol" <?php if($invoiceData[0]->advance_adjustment == 1) { echo 'style="display:table-cell;"'; } ?>>
									<div style="width:100px;" class="padrgt0">
										<i class="fa fa-inr"></i><input type="text" style="width:90%;" id="invoice_tr_<?php echo $counter; ?>_advancevalue" name="invoice_advancevalue[]" class="validateInvoiceAmount invoiceAdvanceValue inptxt" value="<?php echo $invData->advance_amount; ?>" data-bind="decimal" placeholder="0.00">
									</div>
								</td>
								<td>
									<div style="width:100px;" class="padrgt0">
										<i class="fa fa-inr"></i><input type="text" style="width:90%;" id="invoice_tr_<?php echo $counter; ?>_taxablevalue" name="invoice_taxablevalue[]" readonly="true" class="inptxt" value="<?php echo $invData->taxable_subtotal; ?>" data-bind="decimal" placeholder="0.00" />
									</div>
								</td>
								<td>
									<input type="text" id="invoice_tr_<?php echo $counter; ?>_igstrate" name="invoice_igstrate[]" class="inptxt validateInvoiceAmount invigstrate" value="<?php echo $invData->igst_rate; ?>" placeholder="0.00" style="width:75px;" />
								</td>
								<td>
									<div style="width:100px;" class="padrgt0">
										<i class="fa fa-inr"></i><input type="text" style="width:90%;" id="invoice_tr_<?php echo $counter; ?>_igstamount" name="invoice_igstamount[]" readonly="true" class="inptxt invsgstamount" placeholder="0.00" value="<?php echo $invData->igst_amount; ?>" />
									</div>
								</td>
								<td>
									<input type="text" id="invoice_tr_<?php echo $counter; ?>_cessrate" name="invoice_cessrate[]" class="inptxt validateInvoiceAmount invcessrate" value="<?php echo $invData->cess_rate; ?>" placeholder="0.00" style="width:75px;" />
								</td>
								<td>
									<div style="width:100px;" class="padrgt0">
										<i class="fa fa-inr"></i><input type="text" style="width:90%;" id="invoice_tr_<?php echo $counter; ?>_cessamount" name="invoice_cessamount[]" readonly="true" class="inptxt invsgstamount" placeholder="0.00" value="<?php echo $invData->cess_amount; ?>" />
									</div>
								</td>

								<?php if($counter == 1) { ?>
									
									<td nowrap="nowrap" class="icon">
										<a class="addMoreInvoice" href="javascript:void(0)">
											<div class="tooltip2">
												<i class="fa fa-plus-circle addicon"></i>
												<span class="tooltiptext">Add More</span>
											</div>
										</a>
									</td>

								<?php } else { ?>

									<td nowrap="nowrap" class="icon">
										<a class="deleteInvoice" data-invoice-id="<?php echo $counter; ?>" href="javascript:void(0)">
											<div class="tooltip2">
												<i class="fa fa-trash deleteicon"></i>
												<span class="tooltiptext">Delete</span>
											</div>
										</a>
									</td>

								<?php } ?>

							</tr>

							<?php $counter++; ?>
						<?php } ?>

						<tr>
							<td <?php if($invoiceData[0]->advance_adjustment == 1) { echo 'colspan="14"'; } else { echo 'colspan="13"'; } ?> align="right" class="lightyellow totalamount">Total Invoice Value <span>(In Figure)</span><div class="totalprice"><i class="fa fa-inr"></i><span class="invoicetotalprice"><?php echo $invoiceData[0]->invoice_total_value; ?></span></div></td>
							<td class="lightyellow" align="left"></td>
						</tr>

						<?php $invoice_total_value_words = $obj_client->convert_number_to_words($invoiceData[0]->invoice_total_value); ?>
						
						<tr>
							<td <?php if($invoiceData[0]->advance_adjustment == 1) { echo 'colspan="14"'; } else { echo 'colspan="13"'; } ?> align="right" class="lightpink fontbold totalamountwords" style="font-size:13px;">Total Invoice Value <small>(In Words):</small> <span class="totalpricewords"><?php echo ucwords($invoice_total_value_words); ?></span></td>
							<td class="lightpink" align="left"></td>
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
								<label for="item_category_name">Category <span class="starred">*</span></label>
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
								<label for="item_unit">Unit <span class="starred">*</span></label>
								<select name="item_unit" id="item_unit" class="required form-control" data-bind="numnzero">
									<?php $dataUnitArrs = $obj_client->getMasterUnits("unit_id,unit_name,unit_code,(case when status='1' Then 'active' when status='0' then 'deactive' end) as status", "is_deleted='0' AND status='1'"); ?>
									<?php if(!empty($dataUnitArrs)) { ?>
										<option value=''>Select Unit</option>
										<?php foreach($dataUnitArrs as $dataUnit) { ?>
											<option value='<?php echo $dataUnit->unit_id; ?>' data-unitcode="<?php echo $dataUnit->unit_code; ?>"><?php echo $dataUnit->unit_name; ?></option>
										<?php } ?>
									<?php } ?>
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
							<label for="status">Status <span class="starred">*</span></label>
							<select name="status" id="status" class="required form-control">
								<option value="1">Active</option>
								<option value="0">Inactive</option>
							</select>
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

		/* Get HSN/SAC Code */
        $( "#item_category_name" ).autocomplete({
            minLength: 3,
            source: "<?php echo PROJECT_URL; ?>/?ajax=client_hsnsac_code",
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
            return validateInvoiceAmount(event, this);
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
                url: "<?php echo PROJECT_URL; ?>/?ajax=client_add_item",
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

		/* export bill date */
        $("#export_bill_date").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
			maxDate: '0'
        });

		/* select2 js for receipt voucher number */
        $("#receipt_voucher_number").select2();

		/* select2 js for billing state */
        $("#billing_country").select2();

        /* select2 js for shipping state */
        $("#shipping_country").select2();

		/* Get Billing Receivers */
        $( "#billing_name" ).autocomplete({
            minLength: 1,
            source: "<?php echo PROJECT_URL; ?>/?ajax=client_get_receiver",
            select: function( event, ui ) {

				$("#billing_company_name").val(ui.item.company_name);
				$("#billing_address").val(ui.item.address);

				/* calculate row invoice and invoice total on state change */
                rowInvoiceCalculationOnStateChnage();
            }
        });
        /* End of Get Billing Receivers */

		/* Get Billing Receivers By Business Name */
        $( "#billing_company_name" ).autocomplete({
            minLength: 1,
            source: "<?php echo PROJECT_URL; ?>/?ajax=client_get_receiver_by_business_name",
            select: function( event, ui ) {

				$("#billing_name").val(ui.item.name);
                $("#billing_address").val(ui.item.address);

				/* calculate row invoice and invoice total on state change */
                rowInvoiceCalculationOnStateChnage();
            }
        });
        /* End of Get Billing Receivers By Business Name */

        /* Get Shipping Receivers */
        $( "#shipping_name" ).autocomplete({
            minLength: 1,
            source: "<?php echo PROJECT_URL; ?>/?ajax=client_get_receiver",
            select: function( event, ui ) {

				$("#shipping_company_name").val(ui.item.company_name);
                $("#shipping_address").val(ui.item.address);

                /* calculate row invoice and invoice total on state change */
                rowInvoiceCalculationOnStateChnage();
            }
        });
        /* End of Get Shipping Receivers */
		
		/* Get Shipping Receivers By Business Name */
        $( "#shipping_company_name" ).autocomplete({
            minLength: 1,
            source: "<?php echo PROJECT_URL; ?>/?ajax=client_get_receiver_by_business_name",
            select: function( event, ui ) {

				$("#shipping_name").val(ui.item.name);
                $("#shipping_address").val(ui.item.address);

                /* calculate row invoice and invoice total on state change */
                rowInvoiceCalculationOnStateChnage();
            }
        });
        /* End of Get Shipping Receivers By Business Name */

        /* If shipping address is same as billing address */
        $("#same_as_billing").change(function(){

            if($(this).is(":checked")) {

                $("#shipping_name").val($("#billing_name").val());
				$("#shipping_company_name").val($("#billing_company_name").val());
                $("#shipping_address").val($("#billing_address").val());
                $("#shipping_state_name").val($("#billing_state_name").val());
                $("#shipping_country").val($("#billing_country").val());

                $("#shipping_name").prop("readonly", true);
				$("#shipping_company_name").prop("readonly", true);
                $("#shipping_address").prop("readonly", true);
				$("#shipping_state_name").prop("readonly", true);
                $('#shipping_country').attr('disabled', true);
                $("#shipping_country").select2();
            } else {

                $("#shipping_name").prop("readonly", false);
				$("#shipping_company_name").prop("readonly", false);
                $("#shipping_address").prop("readonly", false);
				$("#shipping_state_name").prop("readonly", false);
                $('#shipping_country').attr('disabled', false);
                $("#shipping_country").select2();
            }

            /* calculate row invoice and invoice total on state change */
            rowInvoiceCalculationOnStateChnage();
        });
        /* If shipping address is same as billing address */

		/* on quantity chnage of item */
        $(".invoicetable").on("input", ".invoiceQuantity", function(){

            var rowid = $(this).parent().parent().attr("data-row-id");
            var currentTrItemId = parseInt($("#invoice_tr_"+rowid+"_itemid").val());
            rowInvoiceCalculation(currentTrItemId, rowid);
        });
        /* end of on quantity chnage of item */
		
		/* on unit price chnage of item */
        $(".invoicetable").on("input", ".invoiceRateValue", function(){

			var rowid = $(this).parent().parent().parent().attr("data-row-id");
            var currentTrItemId = parseInt($("#invoice_tr_"+rowid+"_itemid").val());
            rowInvoiceCalculation(currentTrItemId, rowid);
        });
        /* end of on unit price chnage of item */

		/* on discount chnage of item */
        $(".invoicetable").on("input", ".invoiceDiscount", function(){

            var rowid = $(this).parent().parent().attr("data-row-id");
            var currentTrItemId = parseInt($("#invoice_tr_"+rowid+"_itemid").val());
            rowInvoiceCalculation(currentTrItemId, rowid);
        });
        /* end of on discount chnage of item */

		/* validate invoice discount allow only numbers or decimals */
        $(".invoicetable").on("keypress input paste", ".invoiceDiscount", function (event) {
			return validateInvoiceDiscount(event, this);
        });
        /* end of validate invoice discount allow only numbers or decimals */

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

		/* on change advance adjustment */
		$('input[type=radio][name=advance_adjustment]').change(function() {

			var advanceAdjustment = $('input[name=advance_adjustment]:checked', '#create-invoice').val();
			if(advanceAdjustment == 1) {

				$(".receiptvouchernumber").show();
				$("#receipt_voucher_number").addClass('required');
				$("#receipt_voucher_number").select2();
				$(".advancecol").show();
				$(".totalamount").attr("colspan", 14);
				$(".totalamountwords").attr("colspan", 14);
			} else {

				$(".receiptvouchernumber").hide();
				$("#receipt_voucher_number").val("");
				$("#receipt_voucher_number").removeClass('required');
				$("#receipt_voucher_number").select2();
				$(".advancecol").hide();
				$(".totalamount").attr("colspan", 13);
				$(".totalamountwords").attr("colspan", 13);
				$(".invoiceAdvanceValue").val(0.00);
			}

			/* calculate row invoice and invoice total on receiver state change */
            rowInvoiceCalculationOnStateChnage();
		});
		/* end of on change advance adjustment */

		/* on advance amount chnage of item */
        $(".invoicetable").on("input", ".invoiceAdvanceValue", function(){

            var rowid = $(this).parent().parent().parent().attr("data-row-id");
            var currentTrItemId = parseInt($("#invoice_tr_"+rowid+"_itemid").val());
            rowInvoiceCalculation(currentTrItemId, rowid);
        });
        /* end of on advance amount chnage of item */

		/* validate invoice amount allow only numbers or decimals */
        $(".invoicetable").on("keypress input paste", ".validateInvoiceAmount", function (event) {
            return validateInvoiceAmount(event, this);
        });
        /* end of validate invoice amount allow only numbers or decimals */
		
		/* on change export supply meant */
		$('input[type=radio][name=export_supply_meant]').change(function() {

			/* calculate row invoice and invoice total on receiver state change */
            rowInvoiceCalculationOnStateChnage();
		});
		/* end of on change export supply meant */

		/* autocomplete for select items for invoice */
        $(".invoicetable").on("keypress", ".autocompleteitemname", function(){

            var rowid = $(this).parent().parent().attr("data-row-id");

            $( "#invoice_tr_"+rowid+"_itemname" ).autocomplete({
                minLength: 1,
                source: "<?php echo PROJECT_URL; ?>/?ajax=client_get_item",
                select: function( event, ui ) {

                    /* add selectable choice  */
                    $("#invoice_td_"+rowid+"_itemname").html('<p id="name_selection_'+rowid+'_choice" class="name_selection_choice" title="'+ui.item.value+'"><span id="name_selection_'+rowid+'_choice_remove" data-selectable-id="'+rowid+'" class="name_selection_choice_remove" role="presentation">×</span>'+ui.item.value+'</p>');

					$("#invoice_tr_"+rowid+"_itemid").val(ui.item.item_id);
                    $("#invoice_tr_"+rowid+"_hsncode").val(ui.item.hsn_code);
                    $("#invoice_tr_"+rowid+"_quantity").val(1);
                    $("#invoice_tr_"+rowid+"_unit").val(ui.item.unit_code);
                    $("#invoice_tr_"+rowid+"_rate").val(ui.item.unit_price);
                    $("#invoice_tr_"+rowid+"_total").val(ui.item.unit_price);
                    $("#invoice_tr_"+rowid+"_discount").val(0);
                    $("#invoice_tr_"+rowid+"_taxablevalue").val(ui.item.unit_price);
					$("#invoice_tr_"+rowid+"_igstrate").val(ui.item.igst_tax_rate);
					$("#invoice_tr_"+rowid+"_cessrate").val(ui.item.cess_tax_rate);

                    /* current row invoice calculation */
                    rowInvoiceCalculation(ui.item.item_id, rowid);
                }
            });
        });
        /* end of autocomplete for select items for invoice */

        /* remove the existing invoice item */
        $(".invoicetable").on("click", ".name_selection_choice_remove", function(){

            var parentPId = $(this).parent().attr("id");
			var parentTdId = $(this).parent().parent().attr("id");
            var parentTrId = $(this).attr("data-selectable-id");

            $("#"+parentPId).remove();
            $("#"+parentTdId).html('<input type="text" id="invoice_tr_'+parentTrId+'_itemname" name="invoice_itemname[]" class="inptxt autocompleteitemname required" placeholder="Enter Item" style="width:120px;" />');
            $("#invoice_tr_"+parentTrId+"_itemid").val("");
            $("#invoice_tr_"+parentTrId+"_hsncode").val("");
            $("#invoice_tr_"+parentTrId+"_quantity").val(0);
            $("#invoice_tr_"+parentTrId+"_unit").val("");
            $("#invoice_tr_"+parentTrId+"_rate").val("");
            $("#invoice_tr_"+parentTrId+"_total").val("");
            $("#invoice_tr_"+parentTrId+"_discount").val(0);
			$("#invoice_tr_"+parentTrId+"_advancevalue").val(0);
            $("#invoice_tr_"+parentTrId+"_taxablevalue").val("");
            $("#invoice_tr_"+parentTrId+"_igstrate").val("");
            $("#invoice_tr_"+parentTrId+"_igstamount").val("");
			$("#invoice_tr_"+parentTrId+"_cessrate").val("");
            $("#invoice_tr_"+parentTrId+"_cessamount").val("");
			
			
			
			var parentPId = $(this).parent().attr("id");
			var parentTdId = $(this).parent().parent().attr("id");
            var parentTrId = $(this).attr("data-selectable-id");

            $("#"+parentPId).remove();
            $("#"+parentTdId).html('<input type="text" id="invoice_tr_'+parentTrId+'_itemname" name="invoice_itemname[]" class="inptxt autocompleteitemname required" placeholder="Enter Item" style="width:120px;" />');
            $("#invoice_tr_"+parentTrId+"_itemid").val("");
            $("#invoice_tr_"+parentTrId+"_hsncode").val("");
            $("#invoice_tr_"+parentTrId+"_quantity").val(0);
            $("#invoice_tr_"+parentTrId+"_unit").val("");
            $("#invoice_tr_"+parentTrId+"_rate").val("");
            $("#invoice_tr_"+parentTrId+"_total").val("");
            $("#invoice_tr_"+parentTrId+"_discount").val(0);
			$("#invoice_tr_"+parentTrId+"_advancevalue").val(0);
            $("#invoice_tr_"+parentTrId+"_taxablevalue").val("");
			$("#invoice_tr_"+parentTrId+"_igstrate").val("");
            $("#invoice_tr_"+parentTrId+"_igstamount").val("");
			$("#invoice_tr_"+parentTrId+"_cessrate").val("");
            $("#invoice_tr_"+parentTrId+"_cessamount").val("");

            /* call function of total invoice */
            totalInvoiceValueCalculation();
        });
        /* end of remove the existing invoice item */

		/* add more invoice row script code */
        $(".invoicetable .addMoreInvoice").click(function() {

            var trlength = $(".invoice_tr").length;
            var nexttrid = parseInt($("tr.invoice_tr:last").attr("data-row-id")) + 1;

            var newtr = '<tr class="invoice_tr" data-row-id="'+nexttrid+'" id="invoice_tr_'+nexttrid+'">';
                newtr += '<td><span class="serialno" id="invoice_tr_'+nexttrid+'_serialno">'+(trlength+1)+'</span><input type="hidden" id="invoice_tr_'+nexttrid+'_itemid" name="invoice_itemid[]" class="required" /></td>';
                newtr += '<td id="invoice_td_'+nexttrid+'_itemname">';
				newtr += '<input type="text" id="invoice_tr_'+nexttrid+'_itemname" name="invoice_itemname[]" class="inptxt autocompleteitemname required" placeholder="Enter Item" data-bind="content" style="width:120px;" />';
				newtr += '</td>';
                newtr += '<td><input type="text" id="invoice_tr_'+nexttrid+'_hsncode" name="invoice_hsncode[]" readonly="true" class="inptxt" data-bind="content" placeholder="HSN/SAC Code" style="width:120px;" /></td>';
                newtr += '<td><input type="number" min="1" id="invoice_tr_'+nexttrid+'_quantity" name="invoice_quantity[]" class="required invoiceQuantity inptxt" value="0" placeholder="0" style="width:100px;" /></td>';

				newtr += '<td>';
					newtr += '<select name="invoice_unit[]" id="invoice_tr_'+nexttrid+'_unit" class="required inptxt" style="width:100px;">';
						<?php $unitArrs = $obj_client->getMasterUnits("unit_id,unit_name,unit_code,(case when status='1' Then 'active' when status='0' then 'deactive' end) as status", "is_deleted='0' AND status='1'"); ?>
						<?php if(!empty($unitArrs)) { ?>
							newtr += '<option value="">Select Unit</option>';
							<?php foreach($unitArrs as $unitArr) { ?>
								newtr += '<option value="<?php echo $unitArr->unit_code; ?>"><?php echo $unitArr->unit_name; ?></option>';
							<?php } ?>
						<?php } ?>
					newtr += '</select>';
				newtr += '</td>';

				newtr += '<td><div class="padrgt0" style="width:100px;"><i class="fa fa-inr"></i><input type="text" style="width:90%;" id="invoice_tr_'+nexttrid+'_rate" name="invoice_rate[]" class="required validateInvoiceAmount invoiceRateValue inptxt" data-bind="decimal" placeholder="0.00" /></div></td>';
                newtr += '<td><div class="padrgt0" style="width:100px;"><i class="fa fa-inr"></i><input type="text" style="width:90%;" id="invoice_tr_'+nexttrid+'_total" name="invoice_total[]" readonly="true" class="inptxt" data-bind="decimal" placeholder="0.00" /></div></td>';
                newtr += '<td><input type="text" style="width:100%;" id="invoice_tr_'+nexttrid+'_discount" name="invoice_discount[]" class="inptxt invoiceDiscount" value="0.00" data-bind="decimal" placeholder="0.00" /></td>';
				newtr += '<td class="advancecol"><div style="width:100px;" class="padrgt0"><i class="fa fa-inr"></i><input type="text" style="width:90%;" id="invoice_tr_'+nexttrid+'_advancevalue" name="invoice_advancevalue[]" class="validateInvoiceAmount invoiceAdvanceValue inptxt" value="0.00" data-bind="decimal" placeholder="0.00" /></div></td>';
				newtr += '<td><div style="width:100px;" class="padrgt0"><i class="fa fa-inr"></i><input type="text" style="width:90%;" id="invoice_tr_'+nexttrid+'_taxablevalue" name="invoice_taxablevalue[]" readonly="true" class="inptxt" data-bind="decimal" placeholder="0.00" /></div></td>';
				newtr += '<td><input type="text" id="invoice_tr_'+nexttrid+'_igstrate" name="invoice_igstrate[]" class="inptxt validateInvoiceAmount invigstrate" placeholder="0.00" style="width:75px;" /></td>';
				newtr += '<td><div style="width:100px;" class="padrgt0"><i class="fa fa-inr"></i><input type="text" style="width:90%;" id="invoice_tr_'+nexttrid+'_igstamount" name="invoice_igstamount[]" readonly="true" class="inptxt invigstamount" placeholder="0.00" /></div></td>';
				newtr += '<td><input type="text" id="invoice_tr_'+nexttrid+'_cessrate" name="invoice_cessrate[]" class="inptxt validateInvoiceAmount invcessrate" placeholder="0.00" style="width:75px;" /></td>';
                newtr += '<td><div style="width:100px;" class="padrgt0"><i class="fa fa-inr"></i><input type="text" style="width:90%;" id="invoice_tr_'+nexttrid+'_cessamount" name="invoice_cessamount[]" readonly="true" class="inptxt invcessamount" placeholder="0.00" /></div></td>';
                newtr += '<td nowrap="nowrap" class="icon"><a class="deleteInvoice" data-invoice-id="'+nexttrid+'" href="javascript:void(0)"><div class="tooltip2"><i class="fa fa-trash deleteicon"></i><span class="tooltiptext">Delete</span></div></a></td>';
                newtr += '</tr>';

			/* insert new row */
			$(".invoice_tr").last().after(newtr);

			/* trigger advance adjustment */
			$( "input[name=advance_adjustment]" ).trigger( "change" );

			/* update tr serial number */
			var trCounter = 1;
			$( "tr.invoice_tr" ).each(function( index ) {
				$(this).find("span.serialno").text(trCounter);
				trCounter++;
			});

            /* call function of total invoice */
            totalInvoiceValueCalculation();
        });

        /* delete invoice row script code */
        $(".invoicetable").on("click", ".deleteInvoice", function() {

            var invoiceId = $(this).attr("data-invoice-id");
            $("#invoice_tr_"+invoiceId).remove();

			/* trigger advance adjustment */
			$( "input[name=advance_adjustment]" ).trigger( "change" );

            /* update tr serial number */
            var trCounter = 1;
            $( "tr.invoice_tr" ).each(function( index ) {
                $(this).find("span.serialno").text(trCounter);
                trCounter++;
            });

            /* call function of total invoice */
            totalInvoiceValueCalculation();
        });

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

				$.ajax({
					data: {invoiceData:$("#create-invoice").serialize(), action:"saveUpdateExportInvoice"},
					dataType: 'json',
					type: 'post',
					url: "<?php echo PROJECT_URL; ?>/?ajax=client_save_update_export_invoice",
					success: function(response){

						if(response.status == "error") {
							
							$(".errorValidationContainer").html(response.message);
							$(".errorValidationContainer").show();
							$('html, body').animate({ scrollTop: $(".formcontainer").offset().top }, 1000);
						} else if(response.status == "success") {
							
							$(".errorValidationContainer").html("");
							$(".errorValidationContainer").hide();
							window.location.href = '<?php echo PROJECT_URL; ?>/?page=client_create_export_invoice';
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

			$.ajax({
                data: {invoiceData:$("#create-invoice").serialize(), action:"saveUpdateExportInvoice"},
                dataType: 'json',
                type: 'post',
                url: "<?php echo PROJECT_URL; ?>/?ajax=client_save_update_export_invoice",
                success: function(response){

                    if(response.status == "error") {

						$(".errorValidationContainer").html(response.message);
                        $(".errorValidationContainer").show();
						$('html, body').animate({ scrollTop: $(".formcontainer").offset().top }, 1000);
                    } else if(response.status == "success") {

                        $(".errorValidationContainer").html("");
                        $(".errorValidationContainer").hide();
                        window.location.href = '<?php echo PROJECT_URL; ?>/?page=client_invoice_list';
                    }
                }
            });
        });
        /* end of save new item */

        /* calculate row invoice on state change function */
        function rowInvoiceCalculationOnStateChnage() {

            $( "tr.invoice_tr" ).each(function( index ) {

                var rowid = $(this).attr("data-row-id");

                if(
					$("#invoice_tr_"+rowid+"_itemid").val() != '' && 
					parseInt($("#invoice_tr_"+rowid+"_quantity").val()) > 0
				) {
                    var itemid = $("#invoice_tr_"+rowid+"_itemid").val();
                    rowInvoiceCalculation(itemid, rowid);
                }
            });
        }
        /* end of calculate row invoice on state change function */

        /* calculate row invoice function */
        function rowInvoiceCalculation(itemid, rowid) {

            /* fetch item details by its id */
            $.ajax({
                data: {itemId:itemid, action:"getItemDetail"},
                dataType: 'json',
                type: 'post',
                url: "<?php echo PROJECT_URL; ?>/?ajax=client_get_item_detail",
                success: function(response){

                    /* calculation */
                    var currentTrQuantity = parseInt($("#invoice_tr_"+rowid+"_quantity").val());

					if($.trim($("#invoice_tr_"+rowid+"_rate").val()).length == 0 || $.trim($("#invoice_tr_"+rowid+"_rate").val()).length == '' || $.trim($("#invoice_tr_"+rowid+"_rate").val()) == '.') {
						var currentTrRate = 0.00;
					} else {
						var currentTrRate = parseFloat($("#invoice_tr_"+rowid+"_rate").val());
					}
					
					if($.trim($("#invoice_tr_"+rowid+"_discount").val()).length == 0 || $.trim($("#invoice_tr_"+rowid+"_discount").val()).length == '' || $.trim($("#invoice_tr_"+rowid+"_discount").val()) == '.') {
						var currentTrDiscount = 0.00;
					} else {
						var currentTrDiscount = parseFloat($("#invoice_tr_"+rowid+"_discount").val());
					}

					if(parseFloat(currentTrDiscount) > 100) {
						var currentTrDiscount = 100;
					}

					/* get all tax rates */
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

					/* advance adjustment */
					var advAdjustment = $('input[name=advance_adjustment]:checked', '#create-invoice').val();
					if(advAdjustment == 1) {
						
						if($.trim($("#invoice_tr_"+rowid+"_advancevalue").val()).length == 0 || $.trim($("#invoice_tr_"+rowid+"_advancevalue").val()).length == '' || $.trim($("#invoice_tr_"+rowid+"_advancevalue").val()) == '.') {
							var advAdjustmentAmount = 0.00;
						} else {
							var advAdjustmentAmount = parseFloat($("#invoice_tr_"+rowid+"_advancevalue").val());
						}
					} else {
						var advAdjustmentAmount = 0.00;
					}

					var currentTotal = currentTrQuantity * currentTrRate;
					$("#invoice_tr_"+rowid+"_total").val(currentTotal.toFixed(2));

					var currentTrDiscountAmount = (currentTrDiscount/100) * currentTotal;
					var currentTrReduceAmount = advAdjustmentAmount + currentTrDiscountAmount;
					var currentTrTaxableValue = currentTotal - currentTrReduceAmount;
					
					$("#invoice_tr_"+rowid+"_taxablevalue").val(currentTrTaxableValue.toFixed(2));
					var exportSupplyMeant = $('input[name=export_supply_meant]:checked', '#create-invoice').val();

					if(exportSupplyMeant == "withpayment") {

						var igstTax = parseFloat(currentIGSTRate);
						var igstTaxAmount = (igstTax/100) * currentTrTaxableValue;
						$("#invoice_tr_"+rowid+"_igstamount").val(igstTaxAmount.toFixed(2));
						
						var cessTax = parseFloat(currentCESSRate);
						var cessTaxAmount = (cessTax/100) * currentTrTaxableValue;
						$("#invoice_tr_"+rowid+"_cessamount").val(cessTaxAmount.toFixed(2));
					} else {

						$("#invoice_tr_"+rowid+"_igstrate").val(0.00);
						$("#invoice_tr_"+rowid+"_igstamount").val(0.00);

						$("#invoice_tr_"+rowid+"_igstrate").prop("readonly", true);

						var cessTax = parseFloat(currentCESSRate);
						var cessTaxAmount = (cessTax/100) * currentTrTaxableValue;
						$("#invoice_tr_"+rowid+"_cessamount").val(cessTaxAmount.toFixed(2));
					}
					/* end of calculation */

                    /* call function of total invoice */
                    totalInvoiceValueCalculation();
                }
            });
			/* end of fetch item details by its id */
        }
        /* end of calculate row invoice function */

        /* calculate total invoice value function */
        function totalInvoiceValueCalculation() {
            
            var totalInvoiceValue = 0.00;
			$( "tr.invoice_tr" ).each(function( index ) {
            
                var rowid = $(this).attr("data-row-id");

                if(
                    $("#invoice_tr_"+rowid+"_itemid").val() != '' && 
                    parseInt($("#invoice_tr_"+rowid+"_quantity").val()) > 0 
                ) {

                    var taxablevalue = parseFloat($("#invoice_tr_"+rowid+"_taxablevalue").val());
                    var igstamount = parseFloat($("#invoice_tr_"+rowid+"_igstamount").val());
					var cessamount = parseFloat($("#invoice_tr_"+rowid+"_cessamount").val());

					totalInvoiceValue += (taxablevalue + igstamount + cessamount);
                }
            });

            totalFinalInvoiceValue = totalInvoiceValue.toFixed(2);
            $( ".totalprice .invoicetotalprice" ).text(totalFinalInvoiceValue);
			
			if(totalFinalInvoiceValue.length > 16) {
				$("#amountValidationModal").modal("show");
				return false;
			}

            $.ajax({
                data: {totalInvoiceValue:totalFinalInvoiceValue, action:"numberToWords"},
                dataType: 'json',
                type: 'post',
                url: "<?php echo PROJECT_URL; ?>/?ajax=client_convert_number_to_words",
                success: function(response){

					if(response.status == "success") {
                        $( ".totalamountwords .totalpricewords" ).text(response.invoicevalue);
                    } else {
                        $( ".totalamountwords .totalpricewords" ).text("<?php echo $obj_client->getValMsg('failed'); ?>");
                    }
                }
            });
        }
        /* end of calculate total invoice value function */
    });
</script>