<?php
    $obj_purchase = new purchase();

	if(!$obj_purchase->can_read('client_invoice')) {

		$obj_purchase->setError($obj_purchase->getValMsg('can_read'));
		$obj_purchase->redirect(PROJECT_URL."/?page=dashboard");
		exit();
	}

	if(!$obj_purchase->can_update('client_invoice')) {

		$obj_purchase->setError($obj_purchase->getValMsg('can_update'));
		$obj_purchase->redirect(PROJECT_URL."/?page=client_invoice_list");
		exit();
	}

	if( isset($_GET['action']) && $_GET['action'] == 'editPurchaseInvoice' && isset($_GET['id']) && $obj_purchase->validateId($_GET['id'])) {

		$invoicePurchaseId = $obj_purchase->sanitize($_GET['id']);
		$invoiceData = $obj_purchase->get_results("select 
													ci.*, 
													cii.purchase_invoice_item_id, 
													cii.item_id, 
													cii.item_name, 
													cii.item_hsncode, 
													cii.item_description, 
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
													cii.total 
													from 												
												" . $obj_purchase->getTableName('client_purchase_invoice') ." as ci INNER JOIN " . $obj_purchase->getTableName('client_purchase_invoice_item') ." as cii ON ci.purchase_invoice_id = cii.purchase_invoice_id where ci.purchase_invoice_id = ".$invoicePurchaseId." AND ci.invoice_type IN('importinvoice','sezunitinvoice','deemedimportinvoice') AND ci.added_by = '".$obj_purchase->sanitize($_SESSION['user_detail']['user_id'])."' AND cii.added_by = '".$obj_purchase->sanitize($_SESSION['user_detail']['user_id'])."' AND ci.is_deleted='0' AND cii.is_deleted='0'");
		if (empty($invoiceData)) {
			$obj_purchase->setError("No invoice found.");
			$obj_purchase->redirect(PROJECT_URL."?page=purchase_invoice_list");
		}
	} else {
		$obj_purchase->redirect(PROJECT_URL."?page=purchase_invoice_list");
	}

    $dataCurrentUserArr = $obj_purchase->getUserDetailsById( $obj_purchase->sanitize($_SESSION['user_detail']['user_id']) );
	$currentFinancialYear = $obj_purchase->generateFinancialYear();
?>
<!--========================admincontainer start=========================-->
<form name="create-invoice" id="create-invoice" method="POST">
	
	<?php if($invoiceData[0]->import_supply_meant === "withoutpayment") { ?>
		<input type="hidden" id="taxApplied" name="taxApplied" value="NOTAX">
	<?php } else { ?>
		<input type="hidden" id="taxApplied" name="taxApplied" value="IGST">
	<?php } ?>
	
	<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
		<div class="col-md-12 col-sm-12 col-xs-12">

			<div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>Generate Tax Import Invoice</h1></div>

			<div class="clear"></div>

			<div class="whitebg formboxcontainer">
				
				<div class="errorValidationContainer">
					<?php $obj_purchase->showErrorMessage(); ?>
					<?php $obj_purchase->showSuccessMessge(); ?>
					<?php $obj_purchase->unsetMessage(); ?>
				</div>

				<div class="row">
					<div class="col-md-5 col-sm-5 col-xs-12 form-group">
						<label>Type of Invoice <span class="starred">*</span></label><br/>
						<label class="radio-inline"><input type="radio" name="invoice_type" value="deemedimportinvoice" <?php if($invoiceData[0]->invoice_type === "deemedimportinvoice") { echo 'checked="checked"'; } ?>>Deemed Import</label>
						<label class="radio-inline"><input type="radio" name="invoice_type" value="importinvoice" <?php if($invoiceData[0]->invoice_type === "importinvoice") { echo 'checked="checked"'; } ?>>Import</label>
						<label class="radio-inline"><input type="radio" name="invoice_type" value="sezunitinvoice" <?php if($invoiceData[0]->invoice_type === "sezunitinvoice") { echo 'checked="checked"'; } ?>>SEZ Unit or Developer</label>
					</div>

					<div class="col-md-7 col-sm-7 col-xs-12 form-group">
						<label>Import Supply Meant <span class="starred">*</span></label>
						<div class="radio">
							<label><input type="radio" name="import_supply_meant" value="withpayment" <?php if($invoiceData[0]->import_supply_meant === "withpayment") { echo 'checked="checked"'; } ?>>SUPPLY MEANT FOR EXPORT ON PAYMENT OF INTEGRATED TAX</label>
						</div>
						<div class="radio">
							<label><input type="radio" name="import_supply_meant" value="withoutpayment" <?php if($invoiceData[0]->import_supply_meant === "withoutpayment") { echo 'checked="checked"'; } ?>>SUPPLY MEANT FOR IMPORT UNDER BOND OR LETTER OF UNDERTAKING WITHOUT PAYMENT OF INTEGRATED TAX</label>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Invoice Serial Number <span class="starred">*</span></label>
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
						<textarea placeholder="IT Park Rd, Sitapura Industrial Area, Sitapura" data-bind="content" readonly="true" class="form-control required" name="company_address" id="company_address"><?php echo html_entity_decode($invoiceData[0]->company_address); ?></textarea>
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

					<div class="col-md-4 col-sm-4 col-xs-12 form-group placeofsupply">
						<label>Place Of Supply <span class="starred">*</span></label>
						<select name='place_of_supply' id='place_of_supply' class="required form-control">
							<?php $dataSupplyStateArrs = $obj_purchase->get_results("select * from ".$obj_purchase->getTableName('state')." where status='1' and is_deleted='0' order by state_name asc"); ?>
							<?php if(!empty($dataSupplyStateArrs)) { ?>
								<option value=''>Select Place Of Supply</option>
								<?php foreach($dataSupplyStateArrs as $dataSupplyStateArr) { ?>

									<?php if($invoiceData[0]->supply_place === $dataSupplyStateArr->state_id) { ?>
										<option value='<?php echo $dataSupplyStateArr->state_id; ?>' data-code="<?php echo $dataSupplyStateArr->state_code;?>" selected="selected"><?php echo $dataSupplyStateArr->state_name . " (" . $dataSupplyStateArr->state_tin . ")"; ?></option>
									<?php } else { ?>
										<option value='<?php echo $dataSupplyStateArr->state_id; ?>' data-code="<?php echo $dataSupplyStateArr->state_code;?>"><?php echo $dataSupplyStateArr->state_name . " (" . $dataSupplyStateArr->state_tin . ")"; ?></option>
									<?php } ?>

								<?php } ?>
							<?php } ?>
						</select>
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
							<option value=''>Select Receipt Voucher</option>
							<?php $dataReceiptVoucherArrs = $obj_purchase->get_results("select purchase_invoice_id, serial_number, reference_number, invoice_date, supply_place, is_canceled from ".$obj_purchase->getTableName('client_purchase_invoice')." where 1=1 AND invoice_type = 'receiptvoucherinvoice' AND is_canceled='0' AND status='1' AND is_deleted='0' AND financial_year = '".$currentFinancialYear."' AND added_by = ".$obj_purchase->sanitize($_SESSION['user_detail']['user_id'])." order by serial_number ASC"); ?>
							<?php if(!empty($dataReceiptVoucherArrs)) { ?>
								<?php foreach($dataReceiptVoucherArrs as $dataReceiptVoucherArr) { ?>

									<?php if($invoiceData[0]->receipt_voucher_number === $dataReceiptVoucherArr->purchase_invoice_id) { ?>
										<option value='<?php echo $dataReceiptVoucherArr->purchase_invoice_id; ?>' data-date="<?php echo $dataReceiptVoucherArr->invoice_date; ?>" selected="selected"><?php echo $dataReceiptVoucherArr->reference_number; ?></option>
									<?php } else { ?>
										<option value='<?php echo $dataReceiptVoucherArr->purchase_invoice_id; ?>' data-date="<?php echo $dataReceiptVoucherArr->invoice_date; ?>"><?php echo $dataReceiptVoucherArr->reference_number; ?></option>
									<?php } ?>

								<?php } ?>
							<?php } ?>
						</select>
					</div>
				</div>
					
				<div class="row">

					<div class="col-md-6">
						<div class="greyborder inovicedeatil">
							<div class="formtitle">Supplier Detail</div>

							<div class="row form-group">
								<div class="col-md-4 col-sm-3 col-xs-12 padleftnone"><label>Contact Name</label> <span class="starred">*</span></div>
								<div class="col-md-8 col-sm-3 col-xs-12"><input type="text" placeholder="Contact Name" data-bind="content" class="required form-control" name="supplier_billing_name" id="supplier_billing_name" value="<?php echo $invoiceData[0]->supplier_billing_name; ?>" /></div>
							</div>

							<div class="row form-group">
								<div class="col-md-4 col-sm-3 col-xs-12 padleftnone"><label>Business Name</label></div>
								<div class="col-md-8 col-sm-3 col-xs-12"><input type="text" placeholder="Business Name" data-bind="content" class="form-control" name="supplier_billing_company_name" id="supplier_billing_company_name" value="<?php echo $invoiceData[0]->supplier_billing_company_name; ?>" /></div>
							</div>

							<div class="row form-group">
								<div class="col-md-4 col-sm-3 col-xs-12 padleftnone"><label>Address</label> <span class="starred">*</span></div>
								<div class="col-md-8 col-sm-3 col-xs-12"><textarea placeholder="Address" data-bind="content" class="form-control required" name="supplier_billing_address" id="supplier_billing_address"><?php echo $invoiceData[0]->supplier_billing_address; ?></textarea></div>
							</div>

							<?php $supplier_billing_state_data = $obj_purchase->getStateDetailByStateId($invoiceData[0]->supplier_billing_state); ?>
							<div class="row form-group">
								<div class="col-md-4 col-sm-3 col-xs-12 padleftnone"><label>State</label> <span class="starred">*</span></div>
								<div class="col-md-8 col-sm-3 col-xs-12">
									<select name='supplier_billing_state' id='supplier_billing_state' class='required form-control'>
										<?php $dataBStateArrs = $obj_purchase->get_results("select * from ".$obj_purchase->getTableName('state')." where status='1' and is_deleted='0' order by state_name asc"); ?>
										<?php if(!empty($dataBStateArrs)) { ?>
											<option value=''>Select State</option>
											<?php foreach($dataBStateArrs as $dataBStateArr) { ?>

												<?php if($invoiceData[0]->supplier_billing_state == $dataBStateArr->state_id) { ?>
													<option value='<?php echo $dataBStateArr->state_id; ?>' data-tin="<?php echo $dataBStateArr->state_tin; ?>" data-code="<?php echo $dataBStateArr->state_code; ?>" selected="selected"><?php echo $dataBStateArr->state_name . " (" . $dataBStateArr->state_tin . ")"; ?></option>
												<?php } else { ?>
													<option value='<?php echo $dataBStateArr->state_id; ?>' data-tin="<?php echo $dataBStateArr->state_tin; ?>" data-code="<?php echo $dataBStateArr->state_code; ?>"><?php echo $dataBStateArr->state_name . " (" . $dataBStateArr->state_tin . ")"; ?></option>
												<?php } ?>

											<?php } ?>
										<?php } ?>
									</select>
									<input type="hidden" name='supplier_billing_state_code' id='supplier_billing_state_code' value="<?php echo $supplier_billing_state_data['data']->state_code; ?>" />
								</div>
							</div>

							<?php $supplier_billing_country_data = $obj_purchase->getCountryDetailByCountryId($invoiceData[0]->supplier_billing_country); ?>
							<div class="row form-group">
								<div class="col-md-4 col-sm-3 col-xs-12 padleftnone"><label>Country</label> <span class="starred">*</span></div>
								<div class="col-md-8 col-sm-3 col-xs-12">
									<select name='supplier_billing_country' id='supplier_billing_country' class='required form-control'>
										<?php $dataBCountryArrs = $obj_purchase->get_results("select * from ".$obj_purchase->getTableName('country')." where status='1' and is_deleted='0' order by country_name asc"); ?>
										<?php if(!empty($dataBCountryArrs)) { ?>
											<option value=''>Select Country</option>
											<?php foreach($dataBCountryArrs as $dataBCountryArr) { ?>

												<?php if($invoiceData[0]->supplier_billing_country == $dataBCountryArr->id) { ?>
													<option value='<?php echo $dataBCountryArr->id; ?>' data-code="<?php echo $dataBCountryArr->country_code; ?>" selected="selected"><?php echo $dataBCountryArr->country_name . " (" . $dataBCountryArr->country_code . ")"; ?></option>
												<?php } else { ?>
													<option value='<?php echo $dataBCountryArr->id; ?>' data-code="<?php echo $dataBCountryArr->country_code; ?>"><?php echo $dataBCountryArr->country_name . " (" . $dataBCountryArr->country_code . ")"; ?></option>
												<?php } ?>

											<?php } ?>
										<?php } ?>
									</select>
									<input type="hidden" name='supplier_billing_country_code' id='supplier_billing_country_code' value="<?php echo $supplier_billing_country_data['data']->country_code; ?>" />
								</div>
							</div>
							
							<div class="row form-group">
								<div class="col-md-4 col-sm-3 col-xs-12 padleftnone"><label>Vendor Type</label> <span class="starred">*</span></div>
								<div class="col-md-8 col-sm-3 col-xs-12">
									<select name='supplier_billing_vendor_type' id='supplier_billing_vendor_type' class='required form-control'>
										<?php $dataVendorArrs = $obj_purchase->get_results("select * from " . $obj_purchase->getTableName('vendor_type') . " where status='1' and is_deleted='0' order by vendor_name asc"); ?>
										<?php if (!empty($dataVendorArrs)) { ?>
											<option value=''>Select Vendor Type</option>
											<?php foreach ($dataVendorArrs as $dataVendorArr) { ?>

												<?php if($invoiceData[0]->supplier_billing_vendor_type == $dataVendorArr->vendor_id) { ?>													
													<option value='<?php echo $dataVendorArr->vendor_id; ?>' selected="selected"><?php echo $dataVendorArr->vendor_name; ?></option>
												<?php } else { ?>
													<option value='<?php echo $dataVendorArr->vendor_id; ?>'><?php echo $dataVendorArr->vendor_name; ?></option>
												<?php } ?>

											<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>

							<div class="row form-group">
								<div class="col-md-4 col-sm-3 col-xs-12 padleftnone"><label>GSTIN/UIN</label></div>
								<div class="col-md-8 col-sm-3 col-xs-12"><input type="text" placeholder="GSTIN/UIN" class="form-control" name='supplier_billing_gstin_number' data-bind="gstin" id='supplier_billing_gstin_number' value="<?php echo $invoiceData[0]->supplier_billing_gstin_number; ?>" /></div>
							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="greyborder inovicedeatil">
							<div class="formtitle">Address Of Recipient / Shipping Detail <small class="pull-right">Same as billing <input name="same_as_billing" id="same_as_billing" value="1" type="checkbox" <?php if($invoiceData[0]->same_as_billing == '1') { echo 'checked="checked"'; } ?>></small></div>

							<div class="row form-group">
								<div class="col-md-4 col-sm-3 col-xs-12 padleftnone"><label>Contact Name</label> <span class="starred">*</span></div>
								<div class="col-md-8 col-sm-3 col-xs-12"><input type="text" placeholder="Contact Name" data-bind="content" class="required form-control" name="recipient_shipping_name" id="recipient_shipping_name" value="<?php echo $invoiceData[0]->recipient_shipping_name; ?>" /></div>
							</div>

							<div class="row form-group">
								<div class="col-md-4 col-sm-3 col-xs-12 padleftnone"><label>Business Name</label></div>
								<div class="col-md-8 col-sm-3 col-xs-12"><input type="text" placeholder="Business Name" data-bind="content" class="form-control" name="recipient_shipping_company_name" id="recipient_shipping_company_name" value="<?php echo $invoiceData[0]->recipient_shipping_company_name; ?>" /></div>
							</div>

							<div class="row form-group">
								<div class="col-md-4 col-sm-3 col-xs-12 padleftnone"><label>Address</label> <span class="starred">*</span></div>
								<div class="col-md-8 col-sm-3 col-xs-12"><textarea placeholder="Address" data-bind="content" class="required form-control" name="recipient_shipping_address" id="recipient_shipping_address"><?php echo $invoiceData[0]->recipient_shipping_address; ?></textarea></div>
							</div>

							<?php $recipient_shipping_state_data = $obj_purchase->getStateDetailByStateId($invoiceData[0]->recipient_shipping_state); ?>
							<div class="row form-group">
								<div class="col-md-4 col-sm-3 col-xs-12 padleftnone"><label>State</label> <span class="starred">*</span></div>
								<div class="col-md-8 col-sm-3 col-xs-12">
									<select name='recipient_shipping_state' id='recipient_shipping_state' class='required form-control'>
										<?php $dataSStateArrs = $obj_purchase->get_results("select * from ".$obj_purchase->getTableName('state')." where status='1' and is_deleted='0' order by state_name asc"); ?>
										<?php if(!empty($dataSStateArrs)) { ?>
											<option value=''>Select State</option>
											<?php foreach($dataSStateArrs as $dataSStateArr) { ?>

												<?php if($invoiceData[0]->recipient_shipping_state == $dataSStateArr->state_id) { ?>													
													<option value='<?php echo $dataSStateArr->state_id; ?>' data-tin="<?php echo $dataSStateArr->state_tin; ?>" data-code="<?php echo $dataSStateArr->state_code; ?>" selected="selected"><?php echo $dataSStateArr->state_name . " (" . $dataSStateArr->state_tin . ")"; ?></option>
												<?php } else { ?>
													<option value='<?php echo $dataSStateArr->state_id; ?>' data-tin="<?php echo $dataSStateArr->state_tin; ?>" data-code="<?php echo $dataSStateArr->state_code; ?>"><?php echo $dataSStateArr->state_name . " (" . $dataSStateArr->state_tin . ")"; ?></option>
												<?php } ?>

											<?php } ?>
										<?php } ?>
									</select>									
									<input type="hidden" name='recipient_shipping_state_code' id='recipient_shipping_state_code' value="<?php echo $recipient_shipping_state_data['data']->state_code; ?>" />
								</div>
							</div>

							<?php $recipient_shipping_country_data = $obj_purchase->getCountryDetailByCountryId($invoiceData[0]->recipient_shipping_country); ?>
							<div class="row form-group">
								<div class="col-md-4 col-sm-3 col-xs-12 padleftnone"><label>Country</label> <span class="starred">*</span></div>
								<div class="col-md-8 col-sm-3 col-xs-12">
									<select name='recipient_shipping_country' id='recipient_shipping_country' class='required form-control'>
										<?php $dataSCountryArrs = $obj_purchase->get_results("select * from ".$obj_purchase->getTableName('country')." where status='1' and is_deleted='0' order by country_name asc"); ?>
										<?php if(!empty($dataSCountryArrs)) { ?>
											<option value=''>Select Country</option>
											<?php foreach($dataSCountryArrs as $dataSCountryArr) { ?>

												<?php if($invoiceData[0]->recipient_shipping_country == $dataSCountryArr->id) { ?>
													<option value='<?php echo $dataSCountryArr->id; ?>' data-code="<?php echo $dataSCountryArr->country_code; ?>" selected="selected"><?php echo $dataSCountryArr->country_name . " (" . $dataSCountryArr->country_code . ")"; ?></option>
												<?php } else { ?>
													<option value='<?php echo $dataSCountryArr->id; ?>' data-code="<?php echo $dataSCountryArr->country_code; ?>"><?php echo $dataSCountryArr->country_name . " (" . $dataSCountryArr->country_code . ")"; ?></option>
												<?php } ?>

											<?php } ?>
										<?php } ?>
									</select>
									<input type="hidden" name='recipient_shipping_country_code' id='recipient_shipping_country_code' value="<?php echo $recipient_shipping_country_data['data']->country_code; ?>" />
								</div>
							</div>

							<div class="row form-group">
								<div class="col-md-4 col-sm-3 col-xs-12 padleftnone"><label>Vendor Type</label> <span class="starred">*</span></div>
								<div class="col-md-8 col-sm-3 col-xs-12">
									<select name='recipient_shipping_vendor_type' id='recipient_shipping_vendor_type' class='required form-control'>
										<?php $dataVendorArrs = $obj_purchase->get_results("select * from " . $obj_purchase->getTableName('vendor_type') . " where status='1' and is_deleted='0' order by vendor_name asc"); ?>
										<?php if (!empty($dataVendorArrs)) { ?>
											<option value=''>Select Vendor Type</option>
											<?php foreach ($dataVendorArrs as $dataVendorArr) { ?>

												<?php if($invoiceData[0]->recipient_shipping_vendor_type == $dataVendorArr->vendor_id) { ?>													
													<option value='<?php echo $dataVendorArr->vendor_id; ?>' selected="selected"><?php echo $dataVendorArr->vendor_name; ?></option>
												<?php } else { ?>
													<option value='<?php echo $dataVendorArr->vendor_id; ?>'><?php echo $dataVendorArr->vendor_name; ?></option>
												<?php } ?>

											<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>

							<div class="row form-group">
								<div class="col-md-4 col-sm-3 col-xs-12 padleftnone"><label>GSTIN/UIN</label></div>
								<div class="col-md-8 col-sm-3 col-xs-12"><input type="text" placeholder="GSTIN/UIN" class="form-control" name='recipient_shipping_gstin_number' data-bind="gstin" id='recipient_shipping_gstin_number' value="<?php echo $invoiceData[0]->recipient_shipping_gstin_number; ?>" /></div>
							</div>

							<div class="row form-group importinformation">
								<div class="col-md-4 col-sm-3 col-xs-12 padleftnone"><label>Import Bill Number</label> <span class="starred">*</span></div>
								<div class="col-md-8 col-sm-3 col-xs-12"><input type="text" placeholder="Import Bill Number" name='import_bill_number' class="form-control" id='import_bill_number' data-bind="content" value="<?php echo $invoiceData[0]->import_bill_number; ?>" /></div>
							</div>

							<div class="row form-group importinformation">
								<div class="col-md-4 col-sm-3 col-xs-12 padleftnone"><label>Import Bill Port Code</label> <span class="starred">*</span></div>
								<div class="col-md-8 col-sm-3 col-xs-12"><input type="text" placeholder="Import Bill Port Code" name='import_bill_port_code' class="form-control" id='import_bill_port_code' data-bind="content" value="<?php echo $invoiceData[0]->import_bill_port_code; ?>" /></div>
							</div>

							<div class="row form-group importinformation">
								<div class="col-md-4 col-sm-3 col-xs-12 padleftnone"><label>Import Bill Date</label> <span class="starred">*</span></div>
								<div class="col-md-8 col-sm-3 col-xs-12"><input type="text" placeholder="Import Bill Date" class="form-control" name='import_bill_date' id='import_bill_date' data-bind="date" value="<?php if(isset($invoiceData[0]->import_bill_date) && $invoiceData[0]->import_bill_date != "0000-00-00") { echo $invoiceData[0]->import_bill_date; } ?>" /></div>
							</div>

						</div>
					</div>

				</div>

				<div class="clear height20"></div>

				<div class="row">
					<div class="col-md-12 form-group">
						<label>Additional Notes</label>
						<textarea placeholder="Enter Additional Notes" class="form-control" name="description" id="description" data-bind="content"><?php echo $invoiceData[0]->description; ?></textarea>
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
							<th rowspan="2" class="active">Qty</th>
							<th rowspan="2" class="active">Unit</th>
							<th rowspan="2" class="active">Rate (<i class="fa fa-inr"></i>)<br/><span style="font-family: open_sans; font-size:11px;">per item</span></th>
							<th rowspan="2" class="active">Total (<i class="fa fa-inr"></i>)</th>
							<th rowspan="2" class="active">Discount<br/>(%)</th>
							<th rowspan="2" class="advancecol active" <?php if($invoiceData[0]->advance_adjustment == 1) { echo 'style="display:table-cell;"'; } ?>>Advance (<i class="fa fa-inr"></i>)</th>
							<th rowspan="2" class="active">Taxable<br/>value (<i class="fa fa-inr"></i>)</th>
							<th colspan="2" class="active" style="border-bottom:1px solid #dddddd;">IGST</th>
							<th colspan="2" class="active" style="border-bottom:1px solid #dddddd;">CESS</th>
							<th class="active" style="border-bottom:1px solid #dddddd;"></th>
						</tr>

						<tr>
							<th class="active">Rate(%)</th>
							<th class="active">Amount (<i class="fa fa-inr"></i>)</th>
							<th class="active">Rate(%)</th>
							<th class="active">Amount (<i class="fa fa-inr"></i>)</th>
							<th class="active"></th>
						</tr>

						<?php $counter = 1; ?>
						<?php foreach($invoiceData as $invData) { ?>

							<tr class="invoice_tr" data-row-id="<?php echo $counter; ?>" id="invoice_tr_<?php echo $counter; ?>">
								<td class="text-center">
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
									<input type="text" id="invoice_tr_<?php echo $counter; ?>_description" name="invoice_description[]" class="inptxt" data-bind="content" placeholder="Enter Description" style="width:120px;" value="<?php echo $invData->item_description; ?>" />
								</td>
								<td>
									<input type="text" id="invoice_tr_<?php echo $counter; ?>_quantity" name="invoice_quantity[]" class="required validateDecimalValue invoiceQuantity inptxt" data-bind="decimal" value="<?php echo $invData->item_quantity; ?>" placeholder="0" style="width:100px;" />
								</td>
								<td>
									<select name="invoice_unit[]" id="invoice_tr_<?php echo $counter; ?>_unit" class="required inptxt" style="width:100px;">
										<?php $masterUnitArrs = $obj_purchase->getMasterUnits("unit_id,unit_name,unit_code,(case when status='1' Then 'active' when status='0' then 'deactive' end) as status", "is_deleted='0' AND status='1'"); ?>
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
										<input type="text" style="width:100%;" id="invoice_tr_<?php echo $counter; ?>_rate" name="invoice_rate[]" class="required validateDecimalValue invoiceRateValue inptxt" data-bind="decimal" value="<?php echo $invData->item_unit_price; ?>" placeholder="0.00" />
									</div>
								</td>
								<td>
									<div class="padrgt0" style="width:100px;">
										<input type="text" style="width:100%;" id="invoice_tr_<?php echo $counter; ?>_total" name="invoice_total[]" readonly="true" class="inptxt" data-bind="decimal" value="<?php echo $invData->subtotal; ?>" placeholder="0.00" />
									</div>
								</td>
								<td>
									<input type="text" style="width:100%;" id="invoice_tr_<?php echo $counter; ?>_discount" name="invoice_discount[]" class="inptxt invoiceDiscount" value="<?php echo $invData->discount; ?>" data-bind="decimal" placeholder="0.00" />
								</td>
								<td class="advancecol" <?php if($invoiceData[0]->advance_adjustment == 1) { echo 'style="display:table-cell;"'; } ?>>
									<div style="width:100px;" class="padrgt0">
										<input type="text" style="width:100%;" id="invoice_tr_<?php echo $counter; ?>_advancevalue" name="invoice_advancevalue[]" class="validateDecimalValue invoiceAdvanceValue inptxt" value="<?php echo $invData->advance_amount; ?>" data-bind="decimal" placeholder="0.00">
									</div>
								</td>
								<td>
									<div style="width:100px;" class="padrgt0">
										<input type="text" style="width:100%;" id="invoice_tr_<?php echo $counter; ?>_taxablevalue" name="invoice_taxablevalue[]" readonly="true" class="inptxt" value="<?php echo $invData->taxable_subtotal; ?>" data-bind="decimal" placeholder="0.00" />
									</div>
								</td>

								<?php if($invoiceData[0]->import_supply_meant == "withpayment") { ?>

									<td>
										<input type="text" id="invoice_tr_<?php echo $counter; ?>_igstrate" name="invoice_igstrate[]" class="inptxt validateTaxValue invigstrate" data-bind="valtax" value="<?php echo $invData->igst_rate; ?>" placeholder="0.00" style="width:75px;" />
									</td>
									<td>
										<div style="width:100px;" class="padrgt0">
											<input type="text" style="width:100%;" id="invoice_tr_<?php echo $counter; ?>_igstamount" name="invoice_igstamount[]" readonly="true" class="inptxt invigstamount" value="<?php echo $invData->igst_amount; ?>" placeholder="0.00" />
										</div>
									</td>
								
								<?php } else { ?>
								
									<td>
										<input type="text" id="invoice_tr_<?php echo $counter; ?>_igstrate" name="invoice_igstrate[]" class="inptxt validateTaxValue invigstrate" data-bind="valtax" value="0.00" placeholder="0.00" style="width:75px;" />
									</td>
									<td>
										<div style="width:100px;" class="padrgt0">
											<input type="text" style="width:100%;" id="invoice_tr_<?php echo $counter; ?>_igstamount" name="invoice_igstamount[]" readonly="true" class="inptxt invigstamount" value="0.00" placeholder="0.00" />
										</div>
									</td>

								<?php } ?>

								<td>
									<input type="text" id="invoice_tr_<?php echo $counter; ?>_cessrate" name="invoice_cessrate[]" class="inptxt validateTaxValue invcessrate" data-bind="valtax" value="<?php echo $invData->cess_rate; ?>"  placeholder="0.00" style="width:75px;" />
								</td>
								<td>
									<div style="width:100px;" class="padrgt0">
										<input type="text" style="width:100%;" id="invoice_tr_<?php echo $counter; ?>_cessamount" name="invoice_cessamount[]" readonly="true" class="inptxt invcessamount" value="<?php echo $invData->cess_amount; ?>" placeholder="0.00" />
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

						<tr class="consolidateTotal">
							<td colspan="9" align="right" class="lightblue fontbold textsmall">Total Invoice Value</td>
							<td class="lightblue fontbold textsmall advancecol consolidateAdvanceTotal" <?php if($invoiceData[0]->advance_adjustment == 1) { echo 'style="display:table-cell;"'; } ?> align="center"><span>0.00</span></td>
							<td class="lightblue fontbold textsmall consolidateTaxableTotal" align="center"><span>0.00</span></td>
							<td class="lightblue fontbold textsmall" align="center"><span>&nbsp;</span></td>
							<td class="lightblue fontbold textsmall consolidateIGSTTotal" align="center"><span>0.00</span></td>
							<td class="lightblue fontbold textsmall" align="center"><span>&nbsp;</span></td>
							<td class="lightblue fontbold textsmall consolidateCESSTotal" align="center"><span>0.00</span></td>
							<td class="lightblue fontbold textsmall" align="center"></td>
						</tr>

						<tr>
							<td <?php if($invoiceData[0]->advance_adjustment == 1) { echo 'colspan="15"'; } else { echo 'colspan="14"'; } ?> align="right" class="lightyellow totalamount">Total Invoice Value <span>(In Figure)</span><div class="totalprice"><i class="fa fa-inr"></i><span class="invoicetotalprice"><?php echo $invoiceData[0]->invoice_total_value; ?></span></div></td>
							<td class="lightyellow" align="left"></td>
						</tr>
						
						<?php $invoice_total_value_words = $obj_purchase->convert_number_to_words($invoiceData[0]->invoice_total_value); ?>

						<tr>
							<td <?php if($invoiceData[0]->advance_adjustment == 1) { echo 'colspan="15"'; } else { echo 'colspan="14"'; } ?> align="right" class="lightpink fontbold totalamountwords" style="font-size:13px;">Total Invoice Value <small>(In Words):</small> <span class="totalpricewords"><?php echo ucwords($invoice_total_value_words); ?></span></td>
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

		/* call invoice type change function */
		invoiceTypeChange();
		
		<?php if($invoiceData[0]->same_as_billing == '1') { ?>

			$("#recipient_shipping_name").prop("readonly", true);
			$("#recipient_shipping_company_name").prop("readonly", true);
			$("#recipient_shipping_address").prop("readonly", true);
			$('#recipient_shipping_state').attr('disabled', true);
			$('#recipient_shipping_country').attr('disabled', true);
			$('#recipient_shipping_vendor_type').attr('disabled', true);
			$("#recipient_shipping_gstin_number").prop("readonly", true);
			$("#recipient_shipping_state").select2();
			$("#recipient_shipping_country").select2();
			$("#recipient_shipping_vendor_type").select2();
		<?php } ?>

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

		/* import bill date */
        $("#import_bill_date").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
			maxDate: '0'
        });

		/* select2 js for place of supply OR receiver state */
        $("#place_of_supply").select2();

		/* select2 js for receipt voucher number */
        $("#receipt_voucher_number").select2();

		/* select2 js for billing state */
        $("#supplier_billing_state").select2();
		
		/* select2 js for billing country */
        $("#supplier_billing_country").select2();
		
		/* select2 js for billing vendor type */
        $("#supplier_billing_vendor_type").select2();

		/* select2 js for shipping state */
        $("#recipient_shipping_state").select2();
		
		/* select2 js for shipping country */
        $("#recipient_shipping_country").select2();

		/* select2 js for shipping vendor type */
        $("#recipient_shipping_vendor_type").select2();

		/* Get Billing Supplier */
        $( "#supplier_billing_name" ).autocomplete({
            minLength: 1,
            source: "<?php echo PROJECT_URL; ?>/?ajax=purchase_get_supplier",
            select: function( event, ui ) {

				$("#supplier_billing_company_name").val(ui.item.company_name);
                $("#supplier_billing_address").val(ui.item.address);
                $("#supplier_billing_state").val(ui.item.state_id);
                $("#supplier_billing_state_code").val(ui.item.state_code);
				$("#supplier_billing_country").val(ui.item.country_id);
                $("#supplier_billing_country_code").val(ui.item.country_code);
                $("#supplier_billing_vendor_type").val(ui.item.vendor_type);
				$("#supplier_billing_gstin_number").val(ui.item.gstid);
                $("#supplier_billing_state").select2();
				$("#supplier_billing_country").select2();
				$("#supplier_billing_vendor_type").select2();

				/* calculate row invoice and invoice total on state change */
                rowInvoiceCalculationOnStateChnage();
            }
        });
        /* End of Get Billing Supplier */

		/* Get Billing Supplier By Business Name */
        $( "#supplier_billing_company_name" ).autocomplete({
            minLength: 1,
            source: "<?php echo PROJECT_URL; ?>/?ajax=purchase_get_supplier_by_business_name",
            select: function( event, ui ) {

				$("#supplier_billing_name").val(ui.item.name);
                $("#supplier_billing_address").val(ui.item.address);
                $("#supplier_billing_state").val(ui.item.state_id);
                $("#supplier_billing_state_code").val(ui.item.state_code);
				$("#supplier_billing_country").val(ui.item.country_id);
                $("#supplier_billing_country_code").val(ui.item.country_code);
                $("#supplier_billing_vendor_type").val(ui.item.vendor_type);
				$("#supplier_billing_gstin_number").val(ui.item.gstid);
				$("#supplier_billing_state").select2();
				$("#supplier_billing_country").select2();
				$("#supplier_billing_vendor_type").select2();

				/* calculate row invoice and invoice total on state change */
                rowInvoiceCalculationOnStateChnage();
            }
        });
        /* End of Get Billing Supplier By Business Name */

        /* recipient shipping address is same as recipient address */
        $("#same_as_billing").change(function(){

            if($(this).is(":checked")) {

                $("#recipient_shipping_name").val($("#company_name").val());
				$("#recipient_shipping_company_name").val($("#company_name").val());
                $("#recipient_shipping_address").val($("#company_address").val());
				$("#recipient_shipping_state").val($("#company_state").attr("data-state-id"));
                $("#recipient_shipping_state_code").val($("#company_state").attr("data-state-code"));
				$("#recipient_shipping_country").val($("#company_state").attr("data-country-id"));
                $("#recipient_shipping_country_code").val($("#company_state").attr("data-country-code"));
				$("#recipient_shipping_vendor_type").val($("#company_name").attr("data-vendor-type"));
                $("#recipient_shipping_gstin_number").val($("#company_gstin_number").val());

                $("#recipient_shipping_name").prop("readonly", true);
				$("#recipient_shipping_company_name").prop("readonly", true);
                $("#recipient_shipping_address").prop("readonly", true);
                $('#recipient_shipping_state').attr('disabled', true);
				$('#recipient_shipping_country').attr('disabled', true);
				$('#recipient_shipping_vendor_type').attr('disabled', true);
                $("#recipient_shipping_gstin_number").prop("readonly", true);

                $("#recipient_shipping_state").select2();
				$("#recipient_shipping_country").select2();
				$("#recipient_shipping_vendor_type").select2();

				if($("#place_of_supply").val() == '') {
					$("#place_of_supply").val($("#company_state").attr("data-state-id"));
					$("#place_of_supply").select2();
				}
            } else {

                $("#recipient_shipping_name").prop("readonly", false);
				$("#recipient_shipping_company_name").prop("readonly", false);
                $("#recipient_shipping_address").prop("readonly", false);
                $('#recipient_shipping_state').attr('disabled', false);
				$('#recipient_shipping_country').attr('disabled', false);
				$('#recipient_shipping_vendor_type').attr('disabled', false);
                $("#recipient_shipping_gstin_number").prop("readonly", false);
				
				$("#recipient_shipping_state").select2();
				$("#recipient_shipping_country").select2();
				$("#recipient_shipping_vendor_type").select2();
            }

            /* calculate row invoice and invoice total on state change */
            rowInvoiceCalculationOnStateChnage();
        });
        /* end of recipient shipping address is same as recipient address */
		
		/* supplier billing state code */
        $("#supplier_billing_state").change(function () {

			var statecode = $(this).find(':selected').attr("data-code");
			if(typeof(statecode) === "undefined") {
				$("#supplier_billing_state_code").val("");
			} else {
				$("#supplier_billing_state_code").val(statecode);
			}
			
			/* calculate row invoice and invoice total on receiver state change */
            rowInvoiceCalculationOnStateChnage();
        });
		
		/* on chnage supplier billing country */
		$("#supplier_billing_country").on("change", function(){

			$("#supplier_billing_country_code").val($("#supplier_billing_country option:selected").attr("data-code"));

			if($("#supplier_billing_country option:selected").attr("data-code") != "IN") {
				$("#supplier_billing_state").val($("#supplier_billing_state option[data-code=OI]").val());
				$("#supplier_billing_state_code").val("OI");
				$("#supplier_billing_state").select2();
			} else {
				$("#supplier_billing_state").val('');
				$("#supplier_billing_state_code").val('');
				$("#supplier_billing_state").select2();
			}
			
			/* calculate row invoice and invoice total on receiver state change */
            rowInvoiceCalculationOnStateChnage();
		});
		/* end of on chnage supplier billing country */

		/* on chnage shipping state */
        $("#create-invoice").on("change", "#recipient_shipping_state", function(){

            /* update state code */
            var statecode = $(this).find(':selected').attr("data-code");
			var stateid = $(this).find(':selected').val();
            if(typeof(statecode) === "undefined") {
				$("#recipient_shipping_state_code").val("");
            } else {

				$("#recipient_shipping_state_code").val(statecode);

				if($("#place_of_supply").val() == '') {
					$("#place_of_supply").val(stateid);
					$("#place_of_supply").select2();
				}
            }
            /* end of update state code */

            /* calculate row invoice and invoice total on state change */
            rowInvoiceCalculationOnStateChnage();
        });
        /* end of on chnage shipping state */

		/* on chnage recipient shipping country */
		$("#recipient_shipping_country").on("change", function(){

			$("#recipient_shipping_country_code").val($("#recipient_shipping_country option:selected").attr("data-code"));

			if($("#recipient_shipping_country option:selected").attr("data-code") != "IN") {
				$("#recipient_shipping_state").val($("#recipient_shipping_state option[data-code=OI]").val());
				$("#recipient_shipping_state_code").val("OI");
				$("#recipient_shipping_state").select2();
			} else {
				$("#recipient_shipping_state").val('');
				$("#recipient_shipping_state_code").val('');
				$("#recipient_shipping_state").select2();
			}
		});
		/* end of on chnage recipient shipping country */

		/* on chnage place of receiver state */
        $("#create-invoice").on("change", "#place_of_supply", function(){

			/* calculate row invoice and invoice total on receiver state change */
            rowInvoiceCalculationOnStateChnage();
        });
        /* end of on chnage place of receiver state */

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
				$(".totalamount").attr("colspan", 15);
				$(".totalamountwords").attr("colspan", 15);
			} else {

				$(".receiptvouchernumber").hide();
				$("#receipt_voucher_number").val("");
				$("#receipt_voucher_number").removeClass('required');
				$("#receipt_voucher_number").select2();
				$(".advancecol").hide();
				$(".totalamount").attr("colspan", 14);
				$(".totalamountwords").attr("colspan", 14);
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

		/* on change export supply meant */
		$('input[type=radio][name=import_supply_meant]').change(function() {

			/* calculate row invoice and invoice total on receiver state change */
            rowInvoiceCalculationOnStateChnage();
		});
		/* end of on change export supply meant */

		/* on change invoice type */
		$('input[type=radio][name=invoice_type]').change(function() {
            invoiceTypeChange();
		});
		/* end of on change invoice type */

		/* autocomplete for select items for invoice */
        $(".invoicetable").on("keypress", ".autocompleteitemname", function(){

            var rowid = $(this).parent().parent().attr("data-row-id");

            $( "#invoice_tr_"+rowid+"_itemname" ).autocomplete({
                minLength: 1,
                source: "<?php echo PROJECT_URL; ?>/?ajax=purchase_get_item",
                select: function( event, ui ) {

                    /* add selectable choice  */
                    $("#invoice_td_"+rowid+"_itemname").html('<p id="name_selection_'+rowid+'_choice" class="name_selection_choice" title="'+ui.item.value+'"><span id="name_selection_'+rowid+'_choice_remove" data-selectable-id="'+rowid+'" class="name_selection_choice_remove" role="presentation">×</span>'+ui.item.value+'</p>');

                    $("#invoice_tr_"+rowid+"_itemid").val(ui.item.item_id);
                    $("#invoice_tr_"+rowid+"_hsncode").val(ui.item.hsn_code);
					$("#invoice_tr_"+rowid+"_description").val(ui.item.item_description);
                    $("#invoice_tr_"+rowid+"_quantity").val(1);
                    $("#invoice_tr_"+rowid+"_unit").val(ui.item.unit_code);
                    $("#invoice_tr_"+rowid+"_rate").val(ui.item.unit_price);
                    $("#invoice_tr_"+rowid+"_total").val(ui.item.unit_price);
                    $("#invoice_tr_"+rowid+"_discount").val(0);
                    $("#invoice_tr_"+rowid+"_taxablevalue").val(ui.item.unit_price);
					$("#invoice_tr_"+rowid+"_advancevalue").val(0.00);
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
			$("#invoice_tr_"+parentTrId+"_description").val("");
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
                newtr += '<td class="text-center"><span class="serialno" id="invoice_tr_'+nexttrid+'_serialno">'+(trlength+1)+'</span><input type="hidden" id="invoice_tr_'+nexttrid+'_itemid" name="invoice_itemid[]" class="required" /></td>';
                newtr += '<td id="invoice_td_'+nexttrid+'_itemname">';
				newtr += '<input type="text" id="invoice_tr_'+nexttrid+'_itemname" name="invoice_itemname[]" class="inptxt autocompleteitemname required" placeholder="Enter Item" data-bind="content" style="width:120px;" />';
				newtr += '</td>';
                newtr += '<td><input type="text" id="invoice_tr_'+nexttrid+'_hsncode" name="invoice_hsncode[]" readonly="true" class="inptxt" data-bind="content" placeholder="HSN/SAC Code" style="width:120px;" /></td>';
                newtr += '<td><input type="text" id="invoice_tr_'+nexttrid+'_description" name="invoice_description[]" class="inptxt" data-bind="content" placeholder="Enter Description" style="width:120px;" /></td>';
				newtr += '<td><input type="text" id="invoice_tr_'+nexttrid+'_quantity" name="invoice_quantity[]" class="required validateDecimalValue invoiceQuantity inptxt" value="0" placeholder="0" style="width:100px;" /></td>';

				newtr += '<td>';
					newtr += '<select name="invoice_unit[]" id="invoice_tr_'+nexttrid+'_unit" class="required inptxt" style="width:100px;">';
						<?php $unitArrs = $obj_purchase->getMasterUnits("unit_id,unit_name,unit_code,(case when status='1' Then 'active' when status='0' then 'deactive' end) as status", "is_deleted='0' AND status='1'"); ?>
						<?php if(!empty($unitArrs)) { ?>
							newtr += '<option value="">Select Unit</option>';
							<?php foreach($unitArrs as $unitArr) { ?>
								newtr += '<option value="<?php echo $unitArr->unit_code; ?>"><?php echo $unitArr->unit_name; ?></option>';
							<?php } ?>
						<?php } ?>
					newtr += '</select>';
				newtr += '</td>';

				newtr += '<td><div class="padrgt0" style="width:100px;"><input type="text" style="width:100%;" id="invoice_tr_'+nexttrid+'_rate" name="invoice_rate[]" class="required validateDecimalValue invoiceRateValue inptxt" data-bind="decimal" placeholder="0.00" /></div></td>';
                newtr += '<td><div class="padrgt0" style="width:100px;"><input type="text" style="width:100%;" id="invoice_tr_'+nexttrid+'_total" name="invoice_total[]" readonly="true" class="inptxt" data-bind="decimal" placeholder="0.00" /></div></td>';
                newtr += '<td><input type="text" style="width:100%;" id="invoice_tr_'+nexttrid+'_discount" name="invoice_discount[]" class="inptxt invoiceDiscount" value="0.00" data-bind="decimal" placeholder="0.00" /></td>';
				newtr += '<td class="advancecol"><div style="width:100px;" class="padrgt0"><input type="text" style="width:100%;" id="invoice_tr_'+nexttrid+'_advancevalue" name="invoice_advancevalue[]" class="validateDecimalValue invoiceAdvanceValue inptxt" value="0.00" data-bind="decimal" placeholder="0.00" /></div></td>';
				newtr += '<td><div style="width:100px;" class="padrgt0"><input type="text" style="width:100%;" id="invoice_tr_'+nexttrid+'_taxablevalue" name="invoice_taxablevalue[]" readonly="true" class="inptxt" data-bind="decimal" placeholder="0.00" /></div></td>';
				newtr += '<td><input type="text" id="invoice_tr_'+nexttrid+'_igstrate" name="invoice_igstrate[]" class="inptxt validateTaxValue invigstrate" data-bind="valtax" placeholder="0.00" style="width:75px;" /></td>';
				newtr += '<td><div style="width:100px;" class="padrgt0"><input type="text" style="width:100%;" id="invoice_tr_'+nexttrid+'_igstamount" name="invoice_igstamount[]" readonly="true" class="inptxt invigstamount" placeholder="0.00" /></div></td>';
				newtr += '<td><input type="text" id="invoice_tr_'+nexttrid+'_cessrate" name="invoice_cessrate[]" class="inptxt validateTaxValue invcessrate" data-bind="valtax" placeholder="0.00" style="width:75px;" /></td>';
                newtr += '<td><div style="width:100px;" class="padrgt0"><input type="text" style="width:100%;" id="invoice_tr_'+nexttrid+'_cessamount" name="invoice_cessamount[]" readonly="true" class="inptxt invcessamount" placeholder="0.00" /></div></td>';
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

				$("#loading").show();
				$.ajax({
					data: {invoiceData:$("#create-invoice").serialize(), action:"saveUpdatePurchaseTaxImportInvoice"},
					dataType: 'json',
					type: 'post',
					url: "<?php echo PROJECT_URL; ?>/?ajax=purchase_import_invoice_save_update",
					success: function(response){

						$("#loading").hide();
						if(response.status == "error") {

							$(".errorValidationContainer").html(response.message);
							$(".errorValidationContainer").show();
							$('html, body').animate({ scrollTop: $(".formcontainer").offset().top }, 1000);
						} else if(response.status == "success") {

							$(".errorValidationContainer").html("");
							$(".errorValidationContainer").hide();
							window.location.href = '<?php echo PROJECT_URL; ?>/?page=purchase_import_invoice_create';
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
                data: {invoiceData:$("#create-invoice").serialize(), action:"saveUpdatePurchaseTaxImportInvoice"},
                dataType: 'json',
                type: 'post',
                url: "<?php echo PROJECT_URL; ?>/?ajax=purchase_import_invoice_save_update",
                success: function(response){

					$("#loading").hide();
                    if(response.status == "error") {

						$(".errorValidationContainer").html(response.message);
                        $(".errorValidationContainer").show();
						$('html, body').animate({ scrollTop: $(".formcontainer").offset().top }, 1000);
                    } else if(response.status == "success") {

                        $(".errorValidationContainer").html("");
                        $(".errorValidationContainer").hide();
                        window.location.href = '<?php echo PROJECT_URL; ?>/?page=purchase_invoice_list';
                    }
                }
            });
        });
        /* end of save new item */

		function invoiceTypeChange() {

			var invoiceType = $('input[name=invoice_type]:checked', '#create-invoice').val();

			if(invoiceType === "importinvoice") {

				$(".importinformation").show();
				$("#import_bill_number").addClass('required');
				$("#import_bill_port_code").addClass('required');
				$("#import_bill_date").addClass('required');
			} else {

				$(".importinformation").hide();
				$("#import_bill_number").removeClass('required');
				$("#import_bill_port_code").removeClass('required');
				$("#import_bill_date").removeClass('required');
			}

			/* calculate row invoice and invoice total on receiver state change */
            rowInvoiceCalculationOnStateChnage();
		}

		/* calculate row invoice on state change function */
        function rowInvoiceCalculationOnStateChnage() {

			var importSupplyMeant = $('input[name=import_supply_meant]:checked', '#create-invoice').val();
			var taxOldApplied = $("#taxApplied").val();
			var taxFlag = false;

			if(importSupplyMeant == "withoutpayment") {
				var taxNewApplied = "NOTAX";
			} else {
				var taxNewApplied = "IGST";
			}

			if(taxOldApplied === taxNewApplied) {
				taxFlag = false;
				$("#taxApplied").val(taxOldApplied);
			} else {
				taxFlag = true;
				$("#taxApplied").val(taxNewApplied);
			}

			$( "tr.invoice_tr" ).each(function( index ) {

                var rowid = $(this).attr("data-row-id");

                if($("#invoice_tr_"+rowid+"_itemid").val() != '' && $("#invoice_tr_"+rowid+"_itemid").val() > 0) {

					var itemid = $("#invoice_tr_"+rowid+"_itemid").val();
					if(taxFlag === true) {

						/* fetch item details by its id */
						$.ajax({
							data: {itemId:itemid, action:"getItemDetail"},
							dataType: 'json',
							type: 'post',
							url: "<?php echo PROJECT_URL; ?>/?ajax=client_get_item_detail",
							success: function(response){

								/* calculation */
								if(importSupplyMeant == "withoutpayment") {
									$("#invoice_tr_"+rowid+"_igstrate").val(0.00);
								} else {
									$("#invoice_tr_"+rowid+"_igstrate").val(response.igst_tax_rate);
								}
								/* end of calculation */

								rowInvoiceCalculation(itemid, rowid);
							}
						});
						/* end of fetch item details by its id */
					} else {
						rowInvoiceCalculation(itemid, rowid);
					}
                }
            });
        }
        /* end of calculate row invoice on state change function */

        /* calculate row invoice function */
        function rowInvoiceCalculation(itemid, rowid) {

			/* calculation */
			if($.trim($("#invoice_tr_"+rowid+"_quantity").val()).length == 0 || $.trim($("#invoice_tr_"+rowid+"_quantity").val()).length == '' || $.trim($("#invoice_tr_"+rowid+"_quantity").val()) == '.') {
				var currentTrQuantity = 0.00;
			} else {
				var currentTrQuantity = parseFloat($("#invoice_tr_"+rowid+"_quantity").val());
			}

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
			var importSupplyMeant = $('input[name=import_supply_meant]:checked', '#create-invoice').val();

			if(importSupplyMeant == "withpayment") {

				$("#invoice_tr_"+rowid+"_igstrate").prop("readonly", false);

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
        /* end of calculate row invoice function */

        /* calculate total invoice value function */
        function totalInvoiceValueCalculation() {
			
			var totalInvoiceValue = 0.00;
			$( "tr.invoice_tr" ).each(function( index ) {

                var rowid = $(this).attr("data-row-id");

                if($("#invoice_tr_"+rowid+"_itemid").val() != '' && $("#invoice_tr_"+rowid+"_itemid").val() > 0) {
					
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
			$('input[name="invoice_advancevalue[]"]').each(function() {

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