<?php
    $obj_purchase = new purchase();

	if(!$obj_purchase->can_read('client_invoice')) {

		$obj_purchase->setError($obj_purchase->getValMsg('can_read'));
		$obj_purchase->redirect(PROJECT_URL."/?page=dashboard");
		exit();
	}

	if(!$obj_purchase->can_create('client_invoice')) {
		
		$obj_purchase->setError($obj_purchase->getValMsg('can_create'));
		$obj_purchase->redirect(PROJECT_URL."/?page=purchase_bill_of_supply_invoice_list");
		exit();
	}

    $dataCurrentUserArr = $obj_purchase->getUserDetailsById( $obj_purchase->sanitize($_SESSION['user_detail']['user_id']) );
    $billPurchaseInvoiceNumber = $obj_purchase->generatePurchaseBillInvoiceNumber( $obj_purchase->sanitize($_SESSION['user_detail']['user_id']) );
?>
<!--========================admincontainer start=========================-->
<form name="create-invoice" id="create-invoice" method="POST">
	<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
		<div class="col-md-12 col-sm-12 col-xs-12">

			<div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>Purchase Bill of Supply</h1></div>

			<div class="clear"></div>

			<div class="whitebg formboxcontainer">

				 <div class="errorValidationContainer">
					<?php $obj_purchase->showErrorMessage(); ?>
					<?php $obj_purchase->showSuccessMessge(); ?>
					<?php $obj_purchase->unsetMessage(); ?>
				</div>

				<div class="row">
					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Invoice Serial Number <span class="starred">*</span></label>
						<input type="text" placeholder="Invoice Serial Number" readonly="true" class="form-control required" value="<?php echo $billPurchaseInvoiceNumber; ?>" name="invoice_serial_number" id="invoice_serial_number" />
					</div>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Invoice Date <span class="starred">*</span></label>
						<input type="text" placeholder="YYYY-MM-DD" class="required form-control" data-bind="date" name="invoice_date" id="invoice_date" value="<?php echo date("Y-m-d"); ?>" />
					</div>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Reference Number <span class="starred">*</span></label>
						<input type="text" placeholder="Invoice Reference Number" class="required form-control" data-bind="content" value="<?php echo $billPurchaseInvoiceNumber; ?>" name="invoice_reference_number" id="invoice_reference_number" />
					</div>
				</div>

				 <div class="row">
					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Recipient Name <span class="starred">*</span></label>
						<input type="text" placeholder="Cyfuture India Pvt. Ltd" data-vendor-type="<?php if(isset($dataCurrentUserArr['data']->kyc->vendor_type)) { echo $dataCurrentUserArr['data']->kyc->vendor_type; } ?>" data-bind="content" readonly="true" class="form-control required" name="company_name" id="company_name" value="<?php if(isset($dataCurrentUserArr['data']->kyc->name)) { echo $dataCurrentUserArr['data']->kyc->name; } ?>" />
					</div>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Recipient Address <span class="starred">*</span></label>					
						<textarea placeholder="IT Park Rd, Sitapura Industrial Area, Sitapura" data-bind="content" readonly="true" class="form-control required" name="company_address" id="company_address"><?php if(isset($dataCurrentUserArr['data']->kyc->full_address)) { echo html_entity_decode($dataCurrentUserArr['data']->kyc->full_address); } ?></textarea>
					</div>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Recipient State <span class="starred">*</span></label>
						<input type="text" placeholder="Compant State" data-state-id="<?php if(isset($dataCurrentUserArr['data']->kyc->state_id)) { echo $dataCurrentUserArr['data']->kyc->state_id; } ?>" data-state-code="<?php if(isset($dataCurrentUserArr['data']->kyc->state_code)) { echo $dataCurrentUserArr['data']->kyc->state_code; } ?>" data-country-id="<?php if(isset($dataCurrentUserArr['data']->kyc->country_id)) { echo $dataCurrentUserArr['data']->kyc->country_id; } ?>" data-country-code="<?php if(isset($dataCurrentUserArr['data']->kyc->country_code)) { echo $dataCurrentUserArr['data']->kyc->country_code; } ?>" data-bind="content" readonly="true" class="form-control required" name="company_state" id="company_state" value="<?php if(isset($dataCurrentUserArr['data']->kyc->state_name)) { echo $dataCurrentUserArr['data']->kyc->state_name; } ?>" />
					</div>
				</div>

				 <div class="row">
					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Recipient GSTIN <span class="starred">*</span></label>
						<input type="text" placeholder="BYRAJ14N3KKT" name="company_gstin_number" data-bind="gstin" readonly="true" class="form-control required" id="company_gstin_number" value="<?php if(isset($dataCurrentUserArr['data']->kyc->gstin_number)) { echo $dataCurrentUserArr['data']->kyc->gstin_number; } ?>" />
					</div>
				 </div>

				 <div class="row">

					<div class="col-md-6">
						<div class="greyborder inovicedeatil">
							<div class="formtitle">Supplier Detail</div>

							<div class="row form-group">
								<div class="col-md-4 col-sm-3 col-xs-12 padleftnone"><label>Contact Name</label> <span class="starred">*</span></div>
								<div class="col-md-8 col-sm-3 col-xs-12"><input type="text" placeholder="Contact Name" data-bind="content" class="required form-control" name="supplier_billing_name" id="supplier_billing_name" /></div>
							</div>

							<div class="row form-group">
								<div class="col-md-4 col-sm-3 col-xs-12 padleftnone"><label>Business Name</label></div>
								<div class="col-md-8 col-sm-3 col-xs-12"><input type="text" placeholder="Business Name" data-bind="content" class="form-control" name="supplier_billing_company_name" id="supplier_billing_company_name" /></div>
							</div>

							<div class="row form-group">
								<div class="col-md-4 col-sm-3 col-xs-12 padleftnone"><label>Address</label> <span class="starred">*</span></div>
								<div class="col-md-8 col-sm-3 col-xs-12"><textarea placeholder="Address" data-bind="content" class="form-control required" name="supplier_billing_address" id="supplier_billing_address"></textarea></div>
							</div>

							<div class="row form-group">
								<div class="col-md-4 col-sm-3 col-xs-12 padleftnone"><label>State</label> <span class="starred">*</span></div>
								<div class="col-md-8 col-sm-3 col-xs-12">
									<select name='supplier_billing_state' id='supplier_billing_state' class='required form-control'>
										<?php $dataBStateArrs = $obj_purchase->get_results("select * from ".$obj_purchase->getTableName('state')." where status='1' and is_deleted='0' order by state_name asc"); ?>
										<?php if(!empty($dataBStateArrs)) { ?>
											<option value=''>Select State</option>
											<?php foreach($dataBStateArrs as $dataBStateArr) { ?>
												<option value='<?php echo $dataBStateArr->state_id; ?>' data-tin="<?php echo $dataBStateArr->state_tin; ?>" data-code="<?php echo $dataBStateArr->state_code; ?>"><?php echo $dataBStateArr->state_name . " (" . $dataBStateArr->state_tin . ")"; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
									<input type="hidden" name='supplier_billing_state_code' id='supplier_billing_state_code' />
								</div>
							</div>

							<div class="row form-group">
								<div class="col-md-4 col-sm-3 col-xs-12 padleftnone"><label>Country</label> <span class="starred">*</span></div>
								<div class="col-md-8 col-sm-3 col-xs-12">
									<select name='supplier_billing_country' id='supplier_billing_country' class='required form-control'>
										<?php $dataBCountryArrs = $obj_purchase->get_results("select * from ".$obj_purchase->getTableName('country')." where status='1' and is_deleted='0' order by country_name asc"); ?>
										<?php if(!empty($dataBCountryArrs)) { ?>
											<option value=''>Select Country</option>
											<?php foreach($dataBCountryArrs as $dataBCountryArr) { ?>
												<option value='<?php echo $dataBCountryArr->id; ?>' data-code="<?php echo $dataBCountryArr->country_code; ?>"><?php echo $dataBCountryArr->country_name . " (" . $dataBCountryArr->country_code . ")"; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
									<input type="hidden" name='supplier_billing_country_code' id='supplier_billing_country_code' />
								</div>
							</div>

							<div class="row form-group">
								<div class="col-md-4 col-sm-3 col-xs-12 padleftnone"><label>Vendor Type</label> <span class="starred">*</span></div>
								<div class="col-md-8 col-sm-3 col-xs-12">
									<select name='supplier_billing_vendor_type' id='supplier_billing_vendor_type' class='required form-control'>
										<?php $dataVendorBArrs = $obj_purchase->get_results("select * from " . $obj_purchase->getTableName('vendor_type') . " where status='1' and is_deleted='0' order by vendor_name asc"); ?>
										<?php if (!empty($dataVendorBArrs)) { ?>
											<option value=''>Select Vendor Type</option>
											<?php foreach ($dataVendorBArrs as $dataVendorBArr) { ?>
												<option value='<?php echo $dataVendorBArr->vendor_id; ?>'><?php echo $dataVendorBArr->vendor_name; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>

							<div class="row form-group">
								<div class="col-md-4 col-sm-3 col-xs-12 padleftnone"><label>GSTIN/UIN</label></div>
								<div class="col-md-8 col-sm-3 col-xs-12"><input type="text" placeholder="GSTIN/UIN" name='supplier_billing_gstin_number' class="form-control" data-bind="gstin" id='supplier_billing_gstin_number' /></div>
							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="greyborder inovicedeatil">
							<div class="formtitle">Address Of Recipient / Shipping Detail <small class="pull-right">Same as billing <input name="same_as_billing" id="same_as_billing" value="1" type="checkbox"></small></div>

							<div class="row form-group">
								<div class="col-md-4 col-sm-3 col-xs-12 padleftnone"><label>Contact Name</label> <span class="starred">*</span></div>
								<div class="col-md-8 col-sm-3 col-xs-12"><input type="text" placeholder="Contact Name" data-bind="content" class="required form-control" name="recipient_shipping_name" id="recipient_shipping_name" /></div>
							</div>
							
							<div class="row form-group">
								<div class="col-md-4 col-sm-3 col-xs-12 padleftnone"><label>Business Name</label></div>
								<div class="col-md-8 col-sm-3 col-xs-12"><input type="text" placeholder="Business Name" data-bind="content" class="form-control" name="recipient_shipping_company_name" id="recipient_shipping_company_name" /></div>
							</div>

							<div class="row form-group">
								<div class="col-md-4 col-sm-3 col-xs-12 padleftnone"><label>Address</label> <span class="starred">*</span></div>
								<div class="col-md-8 col-sm-3 col-xs-12"><textarea placeholder="Address" data-bind="content" class="required form-control" name="recipient_shipping_address" id="recipient_shipping_address"></textarea></div>
							</div>

							<div class="row form-group">
								<div class="col-md-4 col-sm-3 col-xs-12 padleftnone"><label>State</label> <span class="starred">*</span></div>
								<div class="col-md-8 col-sm-3 col-xs-12">
									<select name='recipient_shipping_state' id='recipient_shipping_state' class='required form-control'>
										<?php $dataSStateArrs = $obj_purchase->get_results("select * from ".$obj_purchase->getTableName('state')." where status='1' and is_deleted='0' order by state_name asc"); ?>
										<?php if(!empty($dataSStateArrs)) { ?>
											<option value=''>Select State</option>
											<?php foreach($dataSStateArrs as $dataSStateArr) { ?>
												<option value='<?php echo $dataSStateArr->state_id; ?>' data-tin="<?php echo $dataSStateArr->state_tin; ?>" data-code="<?php echo $dataSStateArr->state_code; ?>"><?php echo $dataSStateArr->state_name . " (" . $dataSStateArr->state_tin . ")"; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
									<input type="hidden" name='recipient_shipping_state_code' id='recipient_shipping_state_code' />
								</div>
							</div>

							<div class="row form-group">
								<div class="col-md-4 col-sm-3 col-xs-12 padleftnone"><label>Country</label> <span class="starred">*</span></div>
								<div class="col-md-8 col-sm-3 col-xs-12">
									<select name='recipient_shipping_country' id='recipient_shipping_country' class='required form-control'>
										<?php $dataSCountryArrs = $obj_purchase->get_results("select * from ".$obj_purchase->getTableName('country')." where status='1' and is_deleted='0' order by country_name asc"); ?>
										<?php if(!empty($dataSCountryArrs)) { ?>
											<option value=''>Select Country</option>
											<?php foreach($dataSCountryArrs as $dataSCountryArr) { ?>
												<option value='<?php echo $dataSCountryArr->id; ?>' data-code="<?php echo $dataSCountryArr->country_code; ?>"><?php echo $dataSCountryArr->country_name . " (" . $dataSCountryArr->country_code . ")"; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
									<input type="hidden" name='recipient_shipping_country_code' id='recipient_shipping_country_code' />
								</div>
							</div>

							<div class="row form-group">
								<div class="col-md-4 col-sm-3 col-xs-12 padleftnone"><label>Vendor Type</label> <span class="starred">*</span></div>
								<div class="col-md-8 col-sm-3 col-xs-12">
									<select name='recipient_shipping_vendor_type' id='recipient_shipping_vendor_type' class='required form-control'>
										<?php $dataVendorSArrs = $obj_purchase->get_results("select * from " . $obj_purchase->getTableName('vendor_type') . " where status='1' and is_deleted='0' order by vendor_name asc"); ?>
										<?php if (!empty($dataVendorSArrs)) { ?>
											<option value=''>Select Vendor Type</option>
											<?php foreach ($dataVendorSArrs as $dataVendorSArr) { ?>
												<option value='<?php echo $dataVendorSArr->vendor_id; ?>'><?php echo $dataVendorSArr->vendor_name; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>

							<div class="row form-group">
								<div class="col-md-4 col-sm-3 col-xs-12 padleftnone"><label>GSTIN/UIN</label></div>
								<div class="col-md-8 col-sm-3 col-xs-12"><input type="text" placeholder="GSTIN/UIN" class="form-control" name='recipient_shipping_gstin_number' data-bind="gstin" id='recipient_shipping_gstin_number' /></div>
							</div>
						</div>
					</div>

				 </div>

				 <div class="clear height20"></div>

				 <div class="row">
					<div class="col-md-12 form-group">
						<label>Additional Notes</label>
						<textarea placeholder="Enter Additional Notes" class="form-control" name="description" id="description" data-bind="content"></textarea>
					</div>
				</div>

				 <div class="clear height40"></div>

				 <div class="table-responsive">
					<table width="100%" border="0" cellspacing="0" cellpadding="4" class="table invoicetable tablecontent">
						<tr>
							<th class="active">S.No</th>
							<th class="active">Description<br/> of Goods/Services</th>
							<th class="active">HSN/SAC Code<br/>(GST)</th>
							<th class="active">Item Description</th>
							<th class="active">Qty</th>
							<th class="active">Unit</th>
							<th class="active">Rate (<i class="fa fa-inr"></i>)<br/><span style="font-family: open_sans; font-size:11px;">per item</span></th>
							<th class="active">Total (<i class="fa fa-inr"></i>)</th>
							<th class="active">Discount<br/>(%)</th>
							<th class="active">Net Total<br/>value (<i class="fa fa-inr"></i>)</th>
							<th class="active" style="border-bottom:1px solid #dddddd;"></th>
						</tr>

						<tr class="invoice_tr" data-row-id="1" id="invoice_tr_1">
							<td class="text-center">
								<span class="serialno" id="invoice_tr_1_serialno">1</span>
								<input type="hidden" id="invoice_tr_1_itemid" name="invoice_itemid[]" class="required" />
							</td>
							<td id="invoice_td_1_itemname">
								<input type="text" id="invoice_tr_1_itemname" name="invoice_itemname[]" class="inptxt autocompleteitemname required" placeholder="Enter Item" data-bind="content" style="width:120px;" />
							</td>
							<td>
								<input type="text" id="invoice_tr_1_hsncode" name="invoice_hsncode[]" readonly="true" class="inptxt" data-bind="content" placeholder="HSN/SAC Code" style="width:120px;" />
							</td>
							<td>
								<input type="text" id="invoice_tr_1_description" name="invoice_description[]" class="inptxt" data-bind="content" placeholder="Enter Description" style="width:120px;" />
							</td>
							<td>
								<input type="text" id="invoice_tr_1_quantity" name="invoice_quantity[]" class="required validateDecimalValue invoiceQuantity inptxt" data-bind="decimal" value="0" placeholder="Enter Quantity" style="width:100px;" />
							</td>
							<td>
								<select name="invoice_unit[]" id="invoice_tr_1_unit" class="required inptxt" style="width:100px;">
									<?php $masterUnitArrs = $obj_purchase->getMasterUnits("unit_id,unit_name,unit_code,(case when status='1' Then 'active' when status='0' then 'deactive' end) as status", "is_deleted='0' AND status='1'"); ?>
									<?php if(!empty($masterUnitArrs)) { ?>
										<option value=''>Select Unit</option>
										<?php foreach($masterUnitArrs as $masterUnitArr) { ?>
											<option value='<?php echo $masterUnitArr->unit_code; ?>'><?php echo $masterUnitArr->unit_name; ?></option>
										<?php } ?>
									<?php } ?>
								</select>
							</td>
							<td>
								<div class="padrgt0" style="width:100px;">
									<input type="text" style="width:100%;" id="invoice_tr_1_rate" name="invoice_rate[]" class="required validateDecimalValue invoiceRateValue inptxt" data-bind="decimal" placeholder="0.00" />
								</div>
							</td>
							<td>
								<div class="padrgt0" style="width:100px;">
									<input type="text" style="width:100%;" id="invoice_tr_1_total" name="invoice_total[]" readonly="true" class="inptxt" data-bind="decimal" placeholder="0.00" />
								</div>
							</td>
							<td>
								<input type="text" style="width:100%;" id="invoice_tr_1_discount" name="invoice_discount[]" class="inptxt invoiceDiscount" value="0.00" data-bind="decimal" placeholder="0.00" />
							</td>
							<td>
								<div style="width:100px;" class="padrgt0">
									<input type="text" style="width:100%;" id="invoice_tr_1_taxablevalue" name="invoice_taxablevalue[]" readonly="true" class="inptxt" data-bind="decimal" placeholder="0.00" />
								</div>
							</td>
							<td nowrap="nowrap" class="icon">
								<a class="addMoreInvoice" href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
							</td>
						</tr>
						
						<tr class="consolidateTotal">
							<td colspan="9" align="right" class="lightblue fontbold textsmall">Total Invoice Value:</td>
							<td class="lightblue fontbold textsmall consolidateTaxableTotal" align="center"><span>0.00</span></td>
							<td class="lightblue fontbold textsmall" align="center"></td>
						</tr>

						<tr>
							<td colspan="10" align="right" class="lightyellow totalamount">Total Invoice Value <span>(In Figure)</span><div class="totalprice"><i class="fa fa-inr"></i><span class="invoicetotalprice">0.00</span></div></td>
							<td class="lightyellow" align="left"></td>
						</tr>

						<tr>
							<td colspan="10" align="right" class="lightpink fontbold totalamountwords" style="font-size:13px;">Total Invoice Value <small>(In Words):</small> <span class="totalpricewords">Nill</span></td>
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
			<a href="javascript:void(0)" class="btn btn-default btngrey" id="save_add_new_invoice">Save & Add New Invoice</a>
			<input type='submit' name="save_invoice" id="save_invoice" class="btn btn-default btn-success btnwidth" value="Save Invoice">
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
								<label for="unit_price">Sales Unit Price(Rs.) </label>
								<input type="text" placeholder="Item Sales Unit Price" name='unit_price' id="unit_price" data-bind="demical" class="form-control itemUnitPrice" />
							</div>
						</div>
						
						<div class='col-sm-4'>
							<div class='form-group'>
								<label for="unit_purchase_price">Purchase Unit Price(Rs.) </label>
								<input type="text" placeholder="Item Purchase Unit Price" name='unit_purchase_price' id="unit_purchase_price" data-bind="demical" class="form-control itemUnitPrice" />
							</div>
						</div>
					</div>
					
					<div class='row'>
						<div class='col-sm-4'>
							<div class='form-group'>
								<label for="cgst_tax_rate">CGST Tax Rate(%)</label>
								<input type="text" placeholder="CGST Tax Rate(%)" name='cgst_tax_rate' id="cgst_tax_rate" class="validateTaxValue form-control" data-bind="valtax" />
							</div>
						</div>

						<div class='col-sm-4'>
							<div class='form-group'>
								<label for="sgst_tax_rate">SGST Tax Rate(%)</label>
								<input type="text" placeholder="SGST Tax Rate(%)" name='sgst_tax_rate' id="sgst_tax_rate" class="validateTaxValue form-control" data-bind="valtax" />
							</div>
						</div>

						<div class='col-sm-4'>
							<div class='form-group'>
								<label for="igst_tax_rate">IGST Tax Rate(%)</label>
								<input type="text" placeholder="IGST Tax Rate(%)" name='igst_tax_rate' id="igst_tax_rate" class="validateTaxValue form-control" data-bind="valtax" />
							</div>
						</div>
					</div>

					<div class='row'>
						<div class="col-md-4 col-sm-4 col-xs-12 form-group">
							<label>CESS Tax Rate(%)</label>
							<input type="text" placeholder="CESS Tax Rate(%)" name='cess_tax_rate' id="cess_tax_rate" class="validateTaxValue form-control" data-bind="valtax" />
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
						
						<div class='col-sm-4'>
							<div class='form-group'>
								<label for="status">Status <span class="starred">*</span></label>
								<select name="status" id="status" class="required form-control">
									<option value="1">Active</option>
									<option value="0">Inactive</option>
								</select>
							</div>
						</div>
					</div>
					
					<div class='row'>
						<div class='col-sm-4'>
							<div class='form-group'>
								<label for="item_description">Description </label>
								<textarea placeholder="Item Description" name='item_description' id="item_description" data-bind="content" class="form-control" /></textarea>
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

		/* Get HSN/SAC Code */
        $( "#item_category_name" ).autocomplete({
            minLength: 3,
            source: "<?php echo PROJECT_URL; ?>/?ajax=purchase_hsnsac_code",
            select: function( event, ui ) {
				$("#item_category").val(ui.item.item_id);
				$("#item_hsn_code").val(ui.item.hsn_code);
				$("#cgst_tax_rate").val(ui.item.cgst_tax_rate);
				$("#sgst_tax_rate").val(ui.item.sgst_tax_rate);
				$("#igst_tax_rate").val(ui.item.igst_tax_rate);
				$("#cess_tax_rate").val(ui.item.cess_tax_rate);
            }
        });
        /* End of Get HSN/SAC Code */

		/* Get on chnage of item category */
		$("#item_category_name").on("input", function() {
			$("#item_category").val("");
			$("#item_hsn_code").val("");
			$("#cgst_tax_rate").val("");
			$("#sgst_tax_rate").val("");
			$("#igst_tax_rate").val("");
			$("#cess_tax_rate").val("");
		});
		/* End of on chnage of item category */

		/* validate item unit price allow only numbers or decimals */
        $("#addItemModal").on("keypress input paste", ".itemUnitPrice", function (event) {
            return validateDecimalValue(event, this);
        });
        /* end of validate item unit price allow only numbers or decimals */

		/* validate invoice tax decimal values allow only numbers or decimals */
        $("#addItemModal").on("keypress input paste", ".validateTaxValue", function (event) {
            return validateTaxValue(event, this);
        });
        /* end of validate invoice tax decimal values allow only numbers or decimals */

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

		/* validate invoice decimal values allow only numbers or decimals */
        $(".invoicetable").on("keypress input paste", ".validateDecimalValue", function (event) {
            return validateDecimalValue(event, this);
        });
        /* end of validate invoice decimal values allow only numbers or decimals */

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
					$("#invoice_tr_"+rowid+"_rate").val(ui.item.unit_purchase_price);
					$("#invoice_tr_"+rowid+"_total").val(ui.item.unit_purchase_price);
					$("#invoice_tr_"+rowid+"_discount").val(0);
					$("#invoice_tr_"+rowid+"_taxablevalue").val(ui.item.unit_purchase_price);

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
            $("#invoice_tr_"+parentTrId+"_taxablevalue").val("");

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
				newtr += '<td><input type="text" id="invoice_tr_'+nexttrid+'_quantity" name="invoice_quantity[]" class="required validateDecimalValue invoiceQuantity inptxt" value="0" placeholder="Enter Quantity" style="width:100px;" /></td>';

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
				newtr += '<td><div style="width:100px;" class="padrgt0"><input type="text" style="width:100%;" id="invoice_tr_'+nexttrid+'_taxablevalue" name="invoice_taxablevalue[]" readonly="true" class="inptxt" data-bind="decimal" placeholder="0.00" /></div></td>';
                newtr += '<td nowrap="nowrap" class="icon"><a class="deleteInvoice" data-invoice-id="'+nexttrid+'" href="javascript:void(0)"><div class="tooltip2"><i class="fa fa-trash deleteicon"></i><span class="tooltiptext">Delete</span></div></a></td>';
                newtr += '</tr>';

			/* insert new row */
			$(".invoice_tr").last().after(newtr);

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
					data: {invoiceData:$("#create-invoice").serialize(), action:"saveNewPurchaseBillInvoice"},
					dataType: 'json',
					type: 'post',
					url: "<?php echo PROJECT_URL; ?>/?ajax=purchase_bill_of_supply_invoice_save",
					success: function(response){

						$("#loading").hide();
						if(response.status == "error") {
							
							$(".errorValidationContainer").html(response.message);
							$(".errorValidationContainer").show();
							$('html, body').animate({ scrollTop: $(".formcontainer").offset().top }, 1000);
						} else if(response.status == "success") {
							
							$(".errorValidationContainer").html("");
							$(".errorValidationContainer").hide();
							window.location.href = '<?php echo PROJECT_URL; ?>/?page=purchase_bill_of_supply_invoice_create';
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
                data: {invoiceData:$("#create-invoice").serialize(), action:"saveNewPurchaseBillInvoice"},
                dataType: 'json',
                type: 'post',
                url: "<?php echo PROJECT_URL; ?>/?ajax=purchase_bill_of_supply_invoice_save",
                success: function(response){

					$("#loading").hide();
					if(response.status == "error") {

						$(".errorValidationContainer").html(response.message);
                        $(".errorValidationContainer").show();
						$('html, body').animate({ scrollTop: $(".formcontainer").offset().top }, 1000);
                    } else if(response.status == "success") {

                        $(".errorValidationContainer").html("");
                        $(".errorValidationContainer").hide();
                        window.location.href = '<?php echo PROJECT_URL; ?>/?page=purchase_bill_of_supply_invoice_list';
                    }
                }
            });
        });
        /* end of save new item */

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

			var currentTotal = currentTrQuantity * currentTrRate;
			$("#invoice_tr_"+rowid+"_total").val(currentTotal.toFixed(2));
			$("#invoice_tr_"+rowid+"_total").attr("data-invoice-tr-"+rowid+"-total", currentTotal.toFixed(3));

			var currentTrDiscountAmount = (currentTrDiscount/100) * currentTotal;
			var currentTrTaxableValue = currentTotal - currentTrDiscountAmount;

			$("#invoice_tr_"+rowid+"_taxablevalue").val(currentTrTaxableValue.toFixed(2));
			$("#invoice_tr_"+rowid+"_taxablevalue").attr("data-invoice-tr-"+rowid+"-taxablevalue", currentTrTaxableValue.toFixed(3));
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

					var taxablevalue = parseFloat($("#invoice_tr_"+rowid+"_taxablevalue").attr("data-invoice-tr-"+rowid+"-taxablevalue"));
                    totalInvoiceValue += taxablevalue;
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
		}
		/* end of calculate consolidate total function */
    });
</script>