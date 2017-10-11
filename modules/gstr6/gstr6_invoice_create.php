<?php
	$gstr6 = new gstr6();

	if(!$gstr6->can_read('client_invoice')) {

		$gstr6->setError($gstr6->getValMsg('can_read'));
		$gstr6->redirect(PROJECT_URL."/?page=dashboard");
		exit();
	}

	if(!$gstr6->can_create('client_invoice')) {

		$gstr6->setError($gstr6->getValMsg('can_create'));
		$gstr6->redirect(PROJECT_URL."/?page=gstr6_invoice_create");
		exit();
	}

    $dataCurrentUserArr = $gstr6->getUserDetailsById($gstr6->sanitize($_SESSION['user_detail']['user_id']));
	$currentFinancialYear = $gstr6->generateFinancialYear();
?>
<form name="create-invoice" id="create-invoice" method="POST">

	<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
		<div class="col-md-12 col-sm-12 col-xs-12">

			<div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>Generate GSTR6 Tax Invoice</h1></div>

			<div class="clear"></div>

			<div class="whitebg formboxcontainer">

				<div class="errorValidationContainer">
					<?php $gstr6->showErrorMessage(); ?>
					<?php $gstr6->showSuccessMessge(); ?>
					<?php $gstr6->unsetMessage(); ?>
				</div>

				<div class="row">
					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Invoice Serial Number <span class="starred">*</span></label>
						<input type="text" placeholder="Invoice Serial Number" readonly="true" class="form-control required" value="ISD-000000000001" name="serial_number" id="serial_number" />
					</div>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Invoice Date <span class="starred">*</span></label>
						<input type="text" placeholder="YYYY-MM-DD" class="required form-control" data-bind="date" name="invoice_date" id="invoice_date" value="<?php echo date("Y-m-d"); ?>" />
					</div>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Reference Number <span class="starred">*</span></label>
						<input type="text" placeholder="Invoice Reference Number" class="required form-control" data-bind="content" value="ISD-000000000001" name="reference_number" id="reference_number" />
					</div>
				</div>

				<div class="row">
					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Name of ISD <span class="starred">*</span></label>
						<input type="text" placeholder="Cyfuture India Pvt. Ltd" data-bind="content" readonly="true" class="form-control required" name="company_name" id="company_name" value="<?php if(isset($dataCurrentUserArr['data']->kyc->name)) { echo $dataCurrentUserArr['data']->kyc->name; } ?>" />
					</div>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Address of ISD <span class="starred">*</span></label>					
						<textarea placeholder="IT Park Rd, Sitapura Industrial Area, Sitapura" data-bind="content" readonly="true" class="form-control required" name="company_address" id="company_address"><?php if(isset($dataCurrentUserArr['data']->kyc->full_address)) { echo html_entity_decode($dataCurrentUserArr['data']->kyc->full_address); } ?></textarea>
					</div>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>State of ISD <span class="starred">*</span></label>
						<input type="text" placeholder="Compant State" data-state-id="<?php if(isset($dataCurrentUserArr['data']->kyc->state_id)) { echo $dataCurrentUserArr['data']->kyc->state_id; } ?>" data-state-code="<?php if(isset($dataCurrentUserArr['data']->kyc->state_code)) { echo $dataCurrentUserArr['data']->kyc->state_code; } ?>" data-bind="content" readonly="true" class="form-control required" name="company_state" id="company_state" value="<?php if(isset($dataCurrentUserArr['data']->kyc->state_name)) { echo $dataCurrentUserArr['data']->kyc->state_name; } ?>" />
					</div>
				</div>

				<div class="row">
					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>GSTIN of ISD <span class="starred">*</span></label>
						<input type="text" placeholder="08ABCDE12341ZE" name="company_gstin_number" data-bind="gstin" readonly="true" class="form-control required" id="company_gstin_number" value="<?php if(isset($dataCurrentUserArr['data']->kyc->gstin_number)) { echo $dataCurrentUserArr['data']->kyc->gstin_number; } ?>" />
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
							<th rowspan="2" class="active">S.No</th>
							<th rowspan="2" class="active">Name</th>
							<th rowspan="2" class="active">Address</th>
							<th rowspan="2" class="active">State</th>
							<th rowspan="2" class="active">GSTIN</th>
							<th rowspan="2" class="active">Amount<br>(<i class="fa fa-inr"></i>)</th>
							<th colspan="2" class="active" style="border-bottom:1px solid #dddddd;">CGST</th>
							<th colspan="2" class="active" style="border-bottom:1px solid #dddddd;">SGST/UTGST</th>
							<th colspan="2" class="active" style="border-bottom:1px solid #dddddd;">IGST</th>
							<th colspan="2" class="active" style="border-bottom:1px solid #dddddd;">CESS</th>
							<th class="active" style="border-bottom:1px solid #dddddd;"></th>
						</tr>

						<tr>
							<th class="active">Rate(%)</th>
							<th class="active">Amount (<i class="fa fa-inr"></i>)</th>
							<th class="active">Rate(%)</th>
							<th class="active">Amount (<i class="fa fa-inr"></i>)</th>
							<th class="active">Rate(%)</th>
							<th class="active">Amount (<i class="fa fa-inr"></i>)</th>
							<th class="active">Rate(%)</th>
							<th class="active">Amount (<i class="fa fa-inr"></i>)</th>
							<th class="active"></th>
						</tr>

						<tr class="invoice_tr" data-row-id="1" id="invoice_tr_1">
							<td class="text-center">
								<span class="serialno" id="invoice_tr_1_serialno">1</span>
							</td>
							<td id="invoice_td_1_recipientname">
								<input type="text" id="invoice_tr_1_recipientname" name="invoice_recipientname[]" class="inptxt autocompleterecipientname required" placeholder="Recipient Name" data-bind="content" style="width:120px;" />
							</td>
							<td>
								<input type="text" id="invoice_tr_1_recipientaddress" name="invoice_recipientaddress[]" class="inptxt required" data-bind="content" placeholder="Recipient Address" style="width:120px;" />
							</td>
							<td>
								<select name="invoice_recipientstate[]" id="invoice_tr_1_recipientstate" class="recipientstate required form-control" style="width:175px;">
									<?php $dataISDStateArrs = $gstr6->get_results("select * from ".$gstr6->getTableName('state')." where status='1' and is_deleted='0' order by state_name asc"); ?>
									<?php if(!empty($dataISDStateArrs)) { ?>
										<option value=''>Select State</option>
										<?php foreach($dataISDStateArrs as $dataISDStateArr) { ?>
											<option value='<?php echo $dataISDStateArr->state_id; ?>' data-tin="<?php echo $dataISDStateArr->state_tin; ?>" data-code="<?php echo $dataISDStateArr->state_code; ?>"><?php echo $dataISDStateArr->state_name . " (" . $dataISDStateArr->state_tin . ")"; ?></option>
										<?php } ?>
									<?php } ?>
								</select>
							</td>
							<td>
								<input type="text" id="invoice_tr_1_recipientgstin" name="invoice_recipientgstin[]" class="required inptxt" data-bind="gstin" placeholder="Enter GSTIN" style="width:120px;" />
							</td>
							<td>
								<input type="text" id="invoice_tr_1_taxablevalue" name="invoice_taxablevalue[]" class="required validateDecimalValue invoiceTaxableValue inptxt" data-bind="decimal" placeholder="0.00" style="width:120px;">
							</td>
							<td>
								<input type="text" id="invoice_tr_1_cgstrate" name="invoice_cgstrate[]" class="inptxt validateTaxValue invcgstrate" data-bind="valtax" placeholder="0.00" style="width:75px;" />
							</td>
							<td>
								<input type="text" id="invoice_tr_1_cgstamount" name="invoice_cgstamount[]" readonly="true" class="inptxt invcgstamount" placeholder="0.00" style="width:100px;" />
							</td>
							<td>
								<input type="text" id="invoice_tr_1_sgstrate" name="invoice_sgstrate[]" class="inptxt validateTaxValue invsgstrate" data-bind="valtax" placeholder="0.00" style="width:75px;" />
							</td>
							<td>
								<input type="text" id="invoice_tr_1_sgstamount" name="invoice_sgstamount[]" readonly="true" class="inptxt invsgstamount" placeholder="0.00" style="width:100px;" />
							</td>
							<td>
								<input type="text" id="invoice_tr_1_igstrate" name="invoice_igstrate[]" class="inptxt validateTaxValue invigstrate" data-bind="valtax" placeholder="0.00" style="width:75px;" />
							</td>
							<td>
								<input type="text" id="invoice_tr_1_igstamount" name="invoice_igstamount[]" readonly="true" class="inptxt invigstamount" placeholder="0.00" style="width:100px;" />
							</td>
							<td>
								<input type="text" id="invoice_tr_1_cessrate" name="invoice_cessrate[]" class="inptxt validateTaxValue invcessrate" data-bind="valtax" placeholder="0.00" style="width:75px;" />
							</td>
							<td>
								<input type="text" id="invoice_tr_1_cessamount" name="invoice_cessamount[]" readonly="true" class="inptxt invcessamount" placeholder="0.00" style="width:100px;" />
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
							<td colspan="5" align="right" class="lightblue fontbold textsmall">Total Invoice Value:</td>
							<td class="lightblue fontbold textsmall consolidateTaxableTotal" align="center"><span>0.00</span></td>
							<td class="lightblue fontbold textsmall" align="center"><span>-</span></td>
							<td class="lightblue fontbold textsmall consolidateCGSTTotal" align="center"><span>0.00</span></td>
							<td class="lightblue fontbold textsmall" align="center"><span>-</span></td>
							<td class="lightblue fontbold textsmall consolidateSGSTTotal" align="center"><span>0.00</span></td>
							<td class="lightblue fontbold textsmall" align="center"><span>-</span></td>
							<td class="lightblue fontbold textsmall consolidateIGSTTotal" align="center"><span>0.00</span></td>
							<td class="lightblue fontbold textsmall" align="center"><span>-</span></td>
							<td class="lightblue fontbold textsmall consolidateCESSTotal" align="center"><span>0.00</span></td>
							<td class="lightblue fontbold textsmall" align="center"></td>
						</tr>

						<tr>
							<td colspan="14" align="right" class="lightyellow totalamount">Total Invoice Value <span>(In Figure)</span><div class="totalprice"><i class="fa fa-inr"></i><span class="invoicetotalprice">0.00</span></div></td>
							<td class="lightyellow" align="left"></td>
						</tr>

						<tr>
							<td colspan="14" align="right" class="lightpink fontbold totalamountwords" style="font-size:13px;">Total Invoice Value <small>(In Words):</small> <span class="totalpricewords">Nill</span></td>
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
			<a href="javascript:void(0)" class="btn txtorange orangeborder btngrey" data-toggle="modal" data-target="#addReceiverModal">Add Receiver</a>
			<a href="javascript:void(0)" class="btn btn-default btngrey" id="save_add_new_invoice">Save & Add New Invoice</a>
			<input type='submit' name="save_invoice" id="save_invoice" class="btn btn-default btn-success btnwidth" value="Save Invoice">
		</div>
	</div>
</form>
<!--CONTENT START HERE-->
<div class="clear"></div>

<!-- Add Receiver Modal -->
<div id="addReceiverModal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">

			<form name="add-receiver-form" id="add-receiver-form" method="POST">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Add Receiver</h4>
				</div>

				<div class="modal-body">

					<div class='row'>
						<div class='col-sm-12'>
							<div id="add-receiver-success" class="alert alert-success"></div>
							<div id="add-receiver-error" class="alert alert-danger"></div>
						</div>
					</div>

					<div class='row'>
						<div class='col-sm-4'>    
							<div class='form-group'>
								<label>Contact Name <span class="starred">*</span></label>
								<input type="text" placeholder="Contact Name" name='name' data-bind="content" class="form-control required" />
							</div>
						</div>

						<div class='col-sm-4'>
							<div class='form-group'>
								<label>Business Name</label>
								<input type="text" placeholder="Business Name" name='company_name' data-bind="content" class="form-control" />
							</div>
						</div>

						<div class='col-sm-4'>
							<div class='form-group'>
								<label>Email</label>
								<input type="email" placeholder="Email" name='email' class="form-control" data-bind="email" />
							</div>
						</div>
					</div>

					<div class='row'>
						<div class='col-sm-4'>
							<div class='form-group'>
								<label>Address <span class="starred">*</span></label>
								<input type="text" placeholder="Address" name='address' data-bind="content" class="form-control required" />
							</div>
						</div>

						<div class='col-sm-4'>
							<div class='form-group'>
								<label>City <span class="starred">*</span></label>
								<input type="text" placeholder="City" name='city' data-bind="content" class="form-control required" />
							</div>
						</div>

						<div class='col-sm-4'>
							<div class='form-group'>
								<label>State <span class="starred">*</span></label>
								<select name='state' id='state' class='form-control required'>
									<?php $dataStateArrs = $gstr6->get_results("select * from ".$gstr6->getTableName('state')." where status='1' and is_deleted='0' order by state_name asc"); ?>
									<?php if(!empty($dataStateArrs)) { ?>
										<option value=''>Select State</option>
										<?php foreach($dataStateArrs as $dataStateArr) { ?>
											<option value='<?php echo $dataStateArr->state_id; ?>' data-code='<?php echo $dataStateArr->state_code; ?>' data-tin='<?php echo $dataStateArr->state_tin; ?>'><?php echo $dataStateArr->state_name; ?></option>
										<?php } ?>
									<?php } else { ?>
										<option value=''>No State Found</option>
									<?php } ?>
								</select>
							</div>
						</div>
					</div>
					
					<div class='row'>
						<div class='col-sm-4'>
							<div class='form-group'>
								<label>Country <span class="starred">*</span></label>
								<select name='country' id='country' class='required form-control'>
									<?php $dataCountryArrs = $gstr6->get_results("select * from ".$gstr6->getTableName('country')." order by country_name asc"); ?>
									<?php if(!empty($dataCountryArrs)) { ?>
										<option value=''>Select Country</option>
										<?php foreach($dataCountryArrs as $dataCountryArr) { ?>
											<option value='<?php echo $dataCountryArr->id; ?>' data-code="<?php echo $dataCountryArr->country_code; ?>"><?php echo $dataCountryArr->country_name . " (" . $dataCountryArr->country_code . ")"; ?></option>
										<?php } ?>
									<?php } ?>
								</select>
							</div>
						</div>

						<div class='col-sm-4'>
							<div class='form-group'>
								<label>Zipcode <span class="starred">*</span></label>
								<input type="text" placeholder="Zipcode" name='zipcode' class='form-control required' data-bind="number" />
							</div>
						</div>

						<div class="col-sm-4">
							<div class='form-group'>
								<label>Phone </label>
								<input type="text" placeholder="Phone" name='phone' data-bind="mobilenumber" class='form-control' />
							</div>
						</div>
					</div>

					<div class='row'>
						<div class='col-sm-4'>
							<div class='form-group'>
								<label>Fax </label>
								<input type="text" placeholder="Fax" class="form-control" name='fax' data-bind="number" />
							</div>
						</div>
						
						<div class='col-sm-4'>
							<div class='form-group'>
								<label>PAN Number </label>
								<input type="text" placeholder="PAN Number" class="form-control" name='pannumber' data-bind="pancard" />
							</div>
						</div>

						<div class="col-sm-4">
							<div class='form-group'>
								<label>GSTIN</label>
								<input type="text" placeholder="GSTIN" class="form-control" name='gstid' data-bind="gstin" />
							</div>
						</div>
					</div>

					<div class='row'>
						<div class='col-sm-4'>
							<div class='form-group'>
								<label>Website</label>
								<input type="text" placeholder="Website URL" class="form-control" name='website' data-bind="url" />
							</div>
						</div>
						
						<div class='col-sm-4'>
							<div class='form-group'>
								<label>Remarks</label>
								<textarea placeholder="Remarks" class="form-control" name='remarks' data-bind="content"></textarea>
							</div>
						</div>
						
						<div class="col-sm-4">
							<div class='form-group'>
								<label>Vendor Type <span class="starred">*</span></label>
								<select name='vendor_type' id='vendor_type' class='required form-control'>
									<?php $dataVendorArrs = $gstr6->get_results("select * from " . $gstr6->getTableName('vendor_type') . " where status='1' and is_deleted='0' order by vendor_name asc"); ?>
									<?php if (!empty($dataVendorArrs)) { ?>
										<option value=''>Select Vendor Type</option>
										<?php foreach ($dataVendorArrs as $dataVendorArr) { ?>
											<option value='<?php echo $dataVendorArr->vendor_id; ?>'><?php echo $dataVendorArr->vendor_name; ?></option>
										<?php } ?>
									<?php } ?>
								</select>
							</div>
						</div>
					</div>

					<div class='row'>
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
				</div>
				
				<div class="modal-footer">
					<input type='submit' class="btn btn-success" name='submit' value='SUBMIT' id='add-receiver-submit'>
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

		$('#addReceiverModal').on('shown.bs.modal', function () {
			$("#state").select2();
			$("#country").select2();
			$("#vendor_type").select2();
		});
		
		$("#state").on("change", function(event){
			var stateCode = $('option:selected', this).attr('data-code');
			if(stateCode == "OI") {
				$('#country').val("");
				$("#country").select2();
			} else {
				$('#country').val($('#country option[data-code="IN"]').val());
				$("#country").select2();
			}
		});

		$("#country").on("change", function(event){
			var countryCode = $('option:selected', this).attr('data-code');
			if(countryCode == "IN") {
				var stateCode = $('option:selected', "#state").attr('data-code');
				if(stateCode == "OI") {
					$('#state').val("");
					$("#state").select2();
				}
			} else {
				$('#state').val($('#state option[data-code="OI"]').val());
				$("#state").select2();
			}
		});

		/* validate add receiver form */
        $('#add-receiver-submit').click(function () {

            var mesg = {};
            if (vali.validate(mesg,'add-receiver-form')) {
                return true;
            }
            return false;
        });
		/* end of validate add receiver form */

		/* submit add new receiver form */
        $("#add-receiver-form").submit(function(event){

            event.preventDefault();

            $.ajax({
                data: {receiverData:$("#add-receiver-form").serialize(), action:"addReceiver"},
                dataType: 'json',
                type: 'post',
                url: "<?php echo PROJECT_URL; ?>/?ajax=gstr6_add_receiver",
                success: function(response){

                    if(response.status == "success") {
						$('#add-receiver-success').text(response.message);
						$('#add-receiver-success').fadeIn('slow').delay(3000).fadeOut();
                    } else {
						$('#add-receiver-error').text(response.message);
						$('#add-receiver-error').fadeIn('slow').delay(3000).fadeOut();
					}

                    $('#add-receiver-form')[0].reset();
                }
            });
        });
		/* end of submit add new receiver form */

		/* invoice date */
        $("#invoice_date").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
			maxDate: '0'
        });
		
		$(".recipientstate").on("change", function(event){
			var rowid = $(this).parent().parent().attr("data-row-id");
			rowInvoiceCalculation(rowid);
		});

		/* on taxable amount chnage of item */
        $(".invoicetable").on("input", ".invoiceTaxableValue", function () {

            var rowid = $(this).parent().parent().parent().attr("data-row-id");
			rowInvoiceCalculation(rowid);
        });
        /* end of on taxable amount chnage of item */

		/* on cgst rate chnage of item */
        $(".invoicetable").on("input", ".invcgstrate", function(){

			var rowid = $(this).parent().parent().attr("data-row-id");
            rowInvoiceCalculation(rowid);
        });
        /* end of on cgst rate chnage of item */

		/* on sgst rate chnage of item */
        $(".invoicetable").on("input", ".invsgstrate", function(){

			var rowid = $(this).parent().parent().attr("data-row-id");
            rowInvoiceCalculation(rowid);
        });
        /* end of on sgst rate chnage of item */
		
		/* on igst rate chnage of item */
        $(".invoicetable").on("input", ".invigstrate", function(){

			var rowid = $(this).parent().parent().attr("data-row-id");
            rowInvoiceCalculation(rowid);
        });
        /* end of on igst rate chnage of item */
		
		/* on cess rate chnage of item */
        $(".invoicetable").on("input", ".invcessrate", function(){

			var rowid = $(this).parent().parent().attr("data-row-id");
            rowInvoiceCalculation(rowid);
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
		
		/* autocomplete for select recipients for invoice */
        $(".invoicetable").on("keypress", ".autocompleterecipientname", function(){

            var rowid = $(this).parent().parent().attr("data-row-id");

            $( "#invoice_tr_"+rowid+"_recipientname" ).autocomplete({
                minLength: 1,
                source: "<?php echo PROJECT_URL; ?>/?ajax=gstr6_get_receiver",
                select: function( event, ui ) {

                    /* add selectable choice  */
                    $("#invoice_td_"+rowid+"_recipientname").html('<p id="name_selection_'+rowid+'_choice" class="name_selection_choice" title="'+ui.item.value+'"><span id="name_selection_'+rowid+'_choice_remove" data-selectable-id="'+rowid+'" class="name_selection_choice_remove" role="presentation">×</span>'+ui.item.value+'</p>');
					$("#invoice_tr_"+rowid+"_recipientaddress").val(ui.item.address);
                    $("#invoice_tr_"+rowid+"_recipientstate").val(ui.item.state_id);
					$("#invoice_tr_"+rowid+"_recipientgstin").val(ui.item.gstid);
                
					/* current row invoice calculation */
					rowInvoiceCalculation(rowid);
				}
            });
        });
        /* end of autocomplete for select recipients for invoice */

		/* remove the existing invoice item */
        $(".invoicetable").on("click", ".name_selection_choice_remove", function(){

            var parentPId = $(this).parent().attr("id");
			var parentTdId = $(this).parent().parent().attr("id");
            var parentTrId = $(this).attr("data-selectable-id");

            $("#"+parentPId).remove();
            $("#"+parentTdId).html('<input type="text" id="invoice_tr_'+parentTrId+'_recipientname" name="invoice_recipientname[]" class="inptxt autocompleterecipientname required" placeholder="Recipient Name" data-bind="content" style="width:120px;" />');
            $("#invoice_tr_"+parentTrId+"_recipientaddress").val("");
            $("#invoice_tr_"+parentTrId+"_recipientstate").val("");
			$("#invoice_tr_"+parentTrId+"_recipientgstin").val("");

            /* call function of total invoice */
            totalInvoiceValueCalculation();
        });
        /* end of remove the existing invoice item */

		/* add more invoice row script code */
        $(".invoicetable .addMoreInvoice").click(function() {

            var trlength = $(".invoice_tr").length;
            var nexttrid = parseInt($("tr.invoice_tr:last").attr("data-row-id")) + 1;

            var newtr = '<tr class="invoice_tr" data-row-id="'+nexttrid+'" id="invoice_tr_'+nexttrid+'">';
				newtr += '<td class="text-center"><span class="serialno" id="invoice_tr_'+nexttrid+'_serialno">'+(trlength+1)+'</span></td>';
				newtr += '<td id="invoice_td_'+nexttrid+'_recipientname">';
				newtr += '<input type="text" id="invoice_tr_'+nexttrid+'_recipientname" name="invoice_recipientname[]" class="inptxt autocompleterecipientname required" placeholder="Recipient Name" data-bind="content" style="width:120px;" />';
				newtr += '</td>';
				newtr += '<td><input type="text" id="invoice_tr_'+nexttrid+'_recipientaddress" name="invoice_recipientaddress[]" class="inptxt required" data-bind="content" placeholder="Recipient Address" style="width:120px;" /></td>';

				newtr += '<td>';
					newtr += '<select name="invoice_recipientstate[]" id="invoice_tr_'+nexttrid+'_recipientstate" class="recipientstate required form-control" style="width:175px;">';
						<?php $dataISDStateArrs = $gstr6->get_results("select * from ".$gstr6->getTableName('state')." where status='1' and is_deleted='0' order by state_name asc"); ?>
						<?php if(!empty($dataISDStateArrs)) { ?>
							newtr += '<option value="">Select State</option>';
							<?php foreach($dataISDStateArrs as $dataISDStateArr) { ?>
								newtr += '<option value="<?php echo $dataISDStateArr->state_id; ?>" data-tin="<?php echo $dataISDStateArr->state_tin; ?>" data-code="<?php echo $dataISDStateArr->state_code; ?>"><?php echo $dataISDStateArr->state_name . " (" . $dataISDStateArr->state_tin . ")"; ?></option>';
							<?php } ?>
						<?php } ?>
					newtr += '</select>';
				newtr += '</td>';

				newtr += '<td><input type="text" id="invoice_tr_'+nexttrid+'_recipientgstin" name="invoice_recipientgstin[]" class="required inptxt" data-bind="gstin" placeholder="Enter GSTIN" style="width:120px;" /></td>';
				newtr += '<td><input type="text" id="invoice_tr_'+nexttrid+'_taxablevalue" name="invoice_taxablevalue[]" class="required validateDecimalValue invoiceTaxableValue inptxt" data-bind="decimal" placeholder="0.00" style="width:120px;" /></td>';
				newtr += '<td><input type="text" id="invoice_tr_'+nexttrid+'_cgstrate" name="invoice_cgstrate[]" class="inptxt validateTaxValue invcgstrate" data-bind="valtax" placeholder="0.00" style="width:75px;" /></td>';
				newtr += '<td><input type="text" id="invoice_tr_'+nexttrid+'_cgstamount" name="invoice_cgstamount[]" readonly="true" class="inptxt invcgstamount" placeholder="0.00" style="width:100px;" /></td>';
				newtr += '<td><input type="text" id="invoice_tr_'+nexttrid+'_sgstrate" name="invoice_sgstrate[]" class="inptxt validateTaxValue invsgstrate" data-bind="valtax" placeholder="0.00" style="width:75px;" /></td>';
				newtr += '<td><input type="text" id="invoice_tr_'+nexttrid+'_sgstamount" name="invoice_sgstamount[]" readonly="true" class="inptxt invsgstamount" placeholder="0.00" style="width:100px;" /></td>';
				newtr += '<td><input type="text" id="invoice_tr_'+nexttrid+'_igstrate" name="invoice_igstrate[]" class="inptxt validateTaxValue invigstrate" data-bind="valtax" placeholder="0.00" style="width:75px;" /></td>';
				newtr += '<td><input type="text" id="invoice_tr_'+nexttrid+'_igstamount" name="invoice_igstamount[]" readonly="true" class="inptxt invigstamount" placeholder="0.00" style="width:100px;" /></td>';
				newtr += '<td><input type="text" id="invoice_tr_'+nexttrid+'_cessrate" name="invoice_cessrate[]" class="inptxt validateTaxValue invcessrate" data-bind="valtax" placeholder="0.00" style="width:75px;" /></td>';
				newtr += '<td><input type="text" id="invoice_tr_'+nexttrid+'_cessamount" name="invoice_cessamount[]" readonly="true" class="inptxt invcessamount" placeholder="0.00" style="width:100px;" /></td>';
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
		/*
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
					data: {invoiceData:$("#create-invoice").serialize(), action:"saveNewGSTR6TaxInvoice"},
					dataType: 'json',
					type: 'post',
					url: "<?php echo PROJECT_URL; ?>/?ajax=gstr6_invoice_save",
					success: function(response){

						$("#loading").hide();
						if(response.status == "error") {

							$(".errorValidationContainer").html(response.message);
							$(".errorValidationContainer").show();
							$('html, body').animate({ scrollTop: $(".formcontainer").offset().top }, 1000);
						} else if(response.status == "success") {

							$(".errorValidationContainer").html("");
							$(".errorValidationContainer").hide();
							window.location.href = '<?php echo PROJECT_URL; ?>/?page=gstr6_invoice_create';
						}
					}
				});
			}
		});
		*/
		/* end of save and add new invoice */

		/* save new invoice */
		/*
        $("#create-invoice").submit(function(event){

            event.preventDefault();

			var finalInvoiceValue = $( ".totalprice .invoicetotalprice" ).text();
			if(finalInvoiceValue.length > 16) {
				$("#amountValidationModal").modal("show");
				return false;
			}

			$("#loading").show();
			$.ajax({
                data: {invoiceData:$("#create-invoice").serialize(), action:"saveNewGSTR6TaxInvoice"},
                dataType: 'json',
                type: 'post',
                url: "<?php echo PROJECT_URL; ?>/?ajax=gstr6_invoice_save",
                success: function(response){

					$("#loading").hide();
                    if(response.status == "error") {

						$(".errorValidationContainer").html(response.message);
                        $(".errorValidationContainer").show();
						$('html, body').animate({ scrollTop: $(".formcontainer").offset().top }, 1000);
                    } else if(response.status == "success") {

                        $(".errorValidationContainer").html("");
                        $(".errorValidationContainer").hide();
                        window.location.href = '<?php echo PROJECT_URL; ?>/?page=gstr6_invoice_list';
                    }
                }
            });
        });
		*/
        /* end of save new item */

		/* calculate row invoice function */
        function rowInvoiceCalculation(rowid) {

			var ISDStateId = $("#company_state").attr("data-state-id");
			var receiptStateId = $('option:selected', "#invoice_tr_"+rowid+"_recipientstate").val();

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

			if(ISDStateId === receiptStateId) {

				$("#invoice_tr_"+rowid+"_igstrate").val(0.00);
				$("#invoice_tr_"+rowid+"_igstamount").val(0.00);
				$("#invoice_tr_"+rowid+"_igstamount").attr("data-invoice-tr-"+rowid+"-igstamount", 0.00);

				$("#invoice_tr_"+rowid+"_cgstrate").prop("readonly", false);
				$("#invoice_tr_"+rowid+"_sgstrate").prop("readonly", false);
				$("#invoice_tr_"+rowid+"_igstrate").prop("readonly", true);
				$("#invoice_tr_"+rowid+"_cessrate").prop("readonly", false);

				var cgstTax = parseFloat(currentCGSTRate);
				var cgstTaxAmount = (cgstTax/100) * currentTrTaxableValue;
				$("#invoice_tr_"+rowid+"_cgstamount").val(cgstTaxAmount.toFixed(2));
				$("#invoice_tr_"+rowid+"_cgstamount").attr("data-invoice-tr-"+rowid+"-cgstamount", cgstTaxAmount.toFixed(3));

				var sgstTax = parseFloat(currentSGSTRate);
				var sgstTaxAmount = (sgstTax/100) * currentTrTaxableValue;
				$("#invoice_tr_"+rowid+"_sgstamount").val(sgstTaxAmount.toFixed(2));
				$("#invoice_tr_"+rowid+"_sgstamount").attr("data-invoice-tr-"+rowid+"-sgstamount", sgstTaxAmount.toFixed(3));

				var cessTax = parseFloat(currentCESSRate);
				var cessTaxAmount = (cessTax/100) * currentTrTaxableValue;
				$("#invoice_tr_"+rowid+"_cessamount").val(cessTaxAmount.toFixed(2));
				$("#invoice_tr_"+rowid+"_cessamount").attr("data-invoice-tr-"+rowid+"-cessamount", cessTaxAmount.toFixed(3));
			} else {

				$("#invoice_tr_"+rowid+"_cgstrate").val(0.00);
				$("#invoice_tr_"+rowid+"_sgstrate").val(0.00);
				$("#invoice_tr_"+rowid+"_cgstamount").val(0.00);				
				$("#invoice_tr_"+rowid+"_cgstamount").attr("data-invoice-tr-"+rowid+"-cgstamount", 0.00);
				$("#invoice_tr_"+rowid+"_sgstamount").val(0.00);
				$("#invoice_tr_"+rowid+"_sgstamount").attr("data-invoice-tr-"+rowid+"-sgstamount", 0.00);

				$("#invoice_tr_"+rowid+"_cgstrate").prop("readonly", true);
				$("#invoice_tr_"+rowid+"_sgstrate").prop("readonly", true);
				$("#invoice_tr_"+rowid+"_igstrate").prop("readonly", false);
				$("#invoice_tr_"+rowid+"_cessrate").prop("readonly", false);

				var igstTax = parseFloat(currentIGSTRate);
				var igstTaxAmount = (igstTax/100) * currentTrTaxableValue;
				$("#invoice_tr_"+rowid+"_igstamount").val(igstTaxAmount.toFixed(2));
				$("#invoice_tr_"+rowid+"_igstamount").attr("data-invoice-tr-"+rowid+"-igstamount", igstTaxAmount.toFixed(3));
				
				var cessTax = parseFloat(currentCESSRate);
				var cessTaxAmount = (cessTax/100) * currentTrTaxableValue;
				$("#invoice_tr_"+rowid+"_cessamount").val(cessTaxAmount.toFixed(2));
				$("#invoice_tr_"+rowid+"_cessamount").attr("data-invoice-tr-"+rowid+"-cessamount", cessTaxAmount.toFixed(3));
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

			$( "tr.invoice_tr" ).each(function( index ) {

                var rowid = $(this).attr("data-row-id");

                if($("#invoice_tr_"+rowid+"_recipientstate").val() != '' && $("#invoice_tr_"+rowid+"_recipientstate").val() > 0) {

					var taxablevalue = parseFloat($("#invoice_tr_"+rowid+"_taxablevalue").val());
                    var cgstamount = parseFloat($("#invoice_tr_"+rowid+"_cgstamount").attr("data-invoice-tr-"+rowid+"-cgstamount"));
                    var sgstamount = parseFloat($("#invoice_tr_"+rowid+"_sgstamount").attr("data-invoice-tr-"+rowid+"-sgstamount"));
                    var igstamount = parseFloat($("#invoice_tr_"+rowid+"_igstamount").attr("data-invoice-tr-"+rowid+"-igstamount"));
					var cessamount = parseFloat($("#invoice_tr_"+rowid+"_cessamount").attr("data-invoice-tr-"+rowid+"-cessamount"));

					totalInvoiceCGSTValue += cgstamount;
					totalInvoiceSGSTValue += sgstamount;
					totalInvoiceIGSTValue += igstamount;
					totalInvoiceCESSValue += cessamount;

					totalInvoiceValue += (taxablevalue + cgstamount + sgstamount + igstamount + cessamount);
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
                url: "<?php echo PROJECT_URL; ?>/?ajax=gstr6_convert_number_to_words",
                success: function(response){

                    if(response.status == "success") {
                        $( ".totalamountwords .totalpricewords" ).text(response.invoicevalue);
                    } else {
                        $( ".totalamountwords .totalpricewords" ).text("<?php echo $gstr6->getValMsg('failed'); ?>");
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