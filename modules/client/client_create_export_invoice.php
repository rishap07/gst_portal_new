<?php
    $obj_client = new client();
    if( !isset($_SESSION['user_detail']['user_id']) || $_SESSION['user_detail']['user_id'] == '' ) {
        $obj_client->redirect(PROJECT_URL);
        exit();
    }

	if(!$obj_client->can_read('client_invoice')) {

		$obj_client->setError($obj_client->getValMsg('can_read'));
		$obj_client->redirect(PROJECT_URL."/?page=dashboard");
		exit();
	}
	
	if(!$obj_client->can_create('client_invoice')) {
		
		$obj_client->setError($obj_client->getValMsg('can_create'));
		$obj_client->redirect(PROJECT_URL."/?page=client_invoice_list");
		exit();
	}

    $dataCurrentUserArr = $obj_client->getUserDetailsById( $obj_client->sanitize($_SESSION['user_detail']['user_id']) );
    $invoiceNumber = $obj_client->generateInvoiceNumber( $obj_client->sanitize($_SESSION['user_detail']['user_id']) );
?>
<!--POPUP START HERE-->
<div style="display:none;position:fixed;" id="popup" class="formpopup topanimation">
    <div class="popupform">

        <p style="text-align:center;"> <a class="closebtn" id="btnclose" ><img src="image/icon-close.png" alt="#"></a> </p>
        <h3 class="txtorange">ADD ITEM</h3>
        
        <div class="adminformbx">
            
            <form name="add-item-form" id="add-item-form" method="POST">
                
                <div class="formcol">
                    <label>Item <span class="starred">*</span></label>
                    <input type="text" placeholder="Item name" name='item_name' id="item_name" data-bind="content" class="required" value='<?php if(isset($_POST['item_name'])){ echo $_POST['item_name']; } ?>' />
                </div>
				
				<div class="formcol two">
					<label>Category <span class="starred">*</span></label>
					<input type="text" placeholder="Item Category" name='item_category_name' id="item_category_name" data-bind="content" class="required" />
					<input type="hidden" name='item_category' id="item_category" class="required" />
				</div>                
				
				<div class="formcol third">
                    <label>HSN/SAC Code </label>
                    <div class="clear"></div>
                    <div class="readonly-section" id="item_hsn_code"><?php echo "HSN/SAC Code"; ?></div>
                </div>

				<div class="clear"></div>
                
                <div class="formcol">
                    <label>Unit <span class="starred">*</span></label>
                    <select name="item_unit" id="item_unit" class="required" data-bind="numnzero">
                        <?php $dataUnitArrs = $obj_client->getMasterUnits("unit_id,unit_name,unit_code,(case when status='1' Then 'active' when status='0' then 'deactive' end) as status", "is_deleted='0' AND status='1'"); ?>
                        <?php if(!empty($dataUnitArrs)) { ?>
                            <option value=''>Select Unit</option>
                            <?php foreach($dataUnitArrs as $dataUnit) { ?>
                                <option value='<?php echo $dataUnit->unit_id; ?>' data-unitcode="<?php echo $dataUnit->unit_code; ?>" <?php if(isset($_POST['unit_id']) && $_POST['unit_id'] === $dataUnit->unit_id){ echo 'selected="selected"'; } ?>><?php echo $dataUnit->unit_name; ?></option>
                            <?php } ?>
                        <?php } ?>
                    </select>
                </div>
                
                <div class="formcol two">
                    <label>Unit Price(Rs.)<span class="starred">*</span></label>
                    <input type="text" placeholder="Item Unit Price" name='unit_price' id="unit_price" class="required itemUnitPrice" data-bind="demical" />
                </div>

                <div class="formcol third">
                    <label>Status<span class="starred">*</span></label>
                    <div class="clear"></div>
                    <input type="radio" name="status" checked="checked" value="1" /><span>Active</span>
                    <input type="radio" name="status" value="0" /><span>Inactive</span>
                </div>
                <div class="clear"></div>
                
                <div class="formcol">
                    <div class="clear" style="height:40px;"></div>
                    <input type='submit' class="btn orangebg" name='submit' value='SUBMIT' id='add-item-submit'>
                </div>
            </form>

        </div>
        <div class="clear"> </div>
    </div>
</div>
<div style="display:none;" id="fade" class="black_overlay"></div>

<!--POPUP END HERE-->
<!--========================admincontainer start=========================-->
<div class="admincontainer greybg">
    <div class="formcontainer" style="padding:20px;">

        <div class="errorValidationContainer"></div>

        <h1>Generate Tax Export Invoice</h1>
        <hr class="headingborder">
        
        <form name="create-invoice" id="create-invoice" method="POST">
            <div class="adminformbx">
				
				<?php $obj_client->showErrorMessage(); ?>
				<?php $obj_client->showSuccessMessge(); ?>
				<?php $obj_client->unsetMessage(); ?>
			
                <div class="kycform">
                    <div class="kycmainbox invoiceform">

                        <div class="clear"></div>

						<div class="formcol formcolfull">
                            <label>Export Supply Meant <span class="starred">*</span></label>
                            <div class="clear"></div>
                            <input type="radio" name="export_supply_meant" value="withpayment" checked="checked" /><span class="inputtxt">SUPPLY MEANT FOR EXPORT ON PAYMENT OF INTEGRATED TAX</span>
							<div class="clear"></div>
							<input type="radio" name="export_supply_meant" value="withoutpayment" /><span class="inputtxt">SUPPLY MEANT FOR EXPORT UNDER BOND OR LETTER OF UNDERTAKING WITHOUT PAYMENT OF INTEGRATED TAX</span>
                        </div>

						<div class="clear height10"></div>

						<div class="formcol">
                            <label>Invoice Serial Number <span class="starred">*</span></label>
                            <input type="text" placeholder="Invoice Serial Number" readonly="true" class="readonly required" value="<?php echo $invoiceNumber; ?>" name="invoice_serial_number" id="invoice_serial_number" />
                        </div>

                        <div class="formcol two">
                            <label>Invoice Date <span class="starred">*</span></label>
                            <input type="text" placeholder="YYYY-MM-DD" class="required" data-bind="date" name="invoice_date" id="invoice_date" value="<?php echo date("Y-m-d"); ?>" />
                        </div>
						
						<div class="formcol third">
                            <label>Reference Number <span class="starred">*</span></label>
                            <input type="text" placeholder="Invoice Reference Number" class="required" data-bind="content" value="<?php echo $invoiceNumber; ?>" name="invoice_reference_number" id="invoice_reference_number" />
                        </div>

						<div class="clear height10"></div>
						
						<div class="formcol">
                            <label>Supplier Name <span class="starred">*</span></label>
                            <input type="text" placeholder="Cyfuture India Pvt. Ltd" data-bind="content" readonly="true" class="readonly required" name="company_name" id="company_name" value="<?php if(isset($dataCurrentUserArr['data']->kyc->name)) { echo $dataCurrentUserArr['data']->kyc->name; } ?>" />
                        </div>
						
						<div class="formcol two">
                            <label>Supplier Address <span class="starred">*</span></label>
                            <input type="text" placeholder="Cyfuture India Pvt. Ltd" data-bind="content" readonly="true" class="readonly required" name="company_address" id="company_address" value="<?php if(isset($dataCurrentUserArr['data']->kyc->registered_address)) { echo $dataCurrentUserArr['data']->kyc->registered_address; } ?>" />
                        </div>

						<div class="formcol third">
                            <label>Supplier State <span class="starred">*</span></label>
                            <input type="text" placeholder="Compant State" data-bind="content" readonly="true" class="readonly required" name="company_state" id="company_state" value="<?php if(isset($dataCurrentUserArr['data']->kyc->state_name)) { echo $dataCurrentUserArr['data']->kyc->state_name; } ?>" />
                        </div>
						
						<div class="clear height10"></div>

                        <div class="formcol">
                            <label>Supplier GSTIN <span class="starred">*</span></label>
                            <input type="text" placeholder="BYRAJ14N3KKT" name="company_gstin_number" data-bind="gstin" readonly="true" class="readonly required" id="company_gstin_number" value="<?php if(isset($dataCurrentUserArr['data']->kyc->gstin_number)) { echo $dataCurrentUserArr['data']->kyc->gstin_number; } ?>" />
                        </div>

						<div class="clear height10"></div>

						<div class="formcol">
                            <label>Advance Adjustment <span class="starred">*</span></label>
                            <div class="clear"></div>
                            <input type="radio" name="advance_adjustment" value="1" /><span class="inputtxt">Yes</span>
							<input type="radio" name="advance_adjustment" value="0" checked="checked" /><span class="inputtxt">No</span>
                        </div>

						<div class="formcol two receiptvouchernumber">
                            <label>Receipt Voucher Number <span class="starred">*</span></label>
							<select name='receipt_voucher_number' id='receipt_voucher_number'>
								<?php $dataReceiptVoucherArrs = $obj_client->get_results("select serial_number, invoice_date, supply_place, is_canceled from ".$obj_client->getTableName('client_rv_invoice')." where status='1' and is_deleted='0' AND added_by = ".$obj_client->sanitize($_SESSION['user_detail']['user_id'])." order by serial_number asc"); ?>
								<?php if(!empty($dataReceiptVoucherArrs)) { ?>
									<option value=''>Select Receipt Voucher</option>
									<?php foreach($dataReceiptVoucherArrs as $dataReceiptVoucherArr) { ?>
										<option value='<?php echo $dataReceiptVoucherArr->serial_number; ?>' data-date="<?php echo $dataReceiptVoucherArr->invoice_date; ?>"><?php echo $dataReceiptVoucherArr->serial_number; ?></option>
									<?php } ?>
								<?php } ?>
							</select>
						</div>

                        <div class="clear height20"></div>

                        <div class="col-md-6" style="padding-left:0px;">
                            <div class="inovicedeatil">

                                <h4>Recipient Detail</h4>
                                <div class="formcol">
                                    <label>Name <span class="starred">*</span></label>
                                    <input type="text" placeholder="Name" data-bind="content" class="required" name="billing_name" id="billing_name" />
                                </div>

                                <div class="formcol">
                                    <label>Address <span class="starred">*</span></label>
                                    <textarea placeholder="Address" data-bind="content" class="required" name="billing_address" id="billing_address"></textarea>
                                </div>

								<div class="formcol">
                                    <label>State <span class="starred">*</span></label>
                                    <input type="text" placeholder="Billing State Name" name='billing_state_name' class="required" id='billing_state_name' />
                                </div>

                                <div class="formcol">
                                    <label>Country <span class="starred">*</span></label>
                                    <select name='billing_country' id='billing_country' class='required'>
                                        <?php $dataBCountryArrs = $obj_client->get_results("select * from ".$obj_client->getTableName('country')." order by country_name asc"); ?>
                                        <?php if(!empty($dataBCountryArrs)) { ?>
                                            <option value=''>Select Country</option>
                                            <?php foreach($dataBCountryArrs as $dataBCountryArr) { ?>
                                                <option value='<?php echo $dataBCountryArr->id; ?>' data-code="<?php echo $dataBCountryArr->country_code; ?>"><?php echo $dataBCountryArr->country_name . " (" . $dataBCountryArr->country_code . ")"; ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="clear"></div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 greyborder">
                            <div class="inovicedeatil">
                                <h4>Address Of Delivery / Shipping Detail<small class="pull-right">Same AS Billing<input name="same_as_billing" id="same_as_billing" value="1" type="checkbox"></small></h4>

                                <div class="formcol">
                                    <label>Name <span class="starred">*</span></label>
                                    <input type="text" placeholder="Name" data-bind="content" class="required" name="shipping_name" id="shipping_name" />
                                </div>

                                <div class="formcol">
                                    <label>Address <span class="starred">*</span></label>
                                    <textarea placeholder="Address" data-bind="content" class="required" name="shipping_address" id="shipping_address"></textarea>
                                </div>

								<div class="formcol">
                                    <label>State <span class="starred">*</span></label>
                                    <input type="text" placeholder="Shipping State Name" name='shipping_state_name' class="required" id='shipping_state_name' />
                                </div>

                                <div class="formcol">
                                    <label>Country <span class="starred">*</span></label>
                                    <select name='shipping_country' id='shipping_country' class='required'>
                                        <?php $dataSCountryArrs = $obj_client->get_results("select * from ".$obj_client->getTableName('country')." order by country_name asc"); ?>
                                        <?php if(!empty($dataSCountryArrs)) { ?>
                                            <option value=''>Select Country</option>
                                            <?php foreach($dataSCountryArrs as $dataSCountryArr) { ?>
                                                <option value='<?php echo $dataSCountryArr->id; ?>' data-code="<?php echo $dataSCountryArr->country_code; ?>"><?php echo $dataSCountryArr->country_name . " (" . $dataSCountryArr->country_code . ")"; ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>

								<div class="formcol">
                                    <label>Export Bill Number <span class="starred">*</span></label>
                                    <input type="text" placeholder="Export Bill Number" name='export_bill_number' class="required" data-bind="content" id='export_bill_number' />
                                </div>
								
								<div class="formcol">
                                    <label>Export Bill Date <span class="starred">*</span></label>
                                    <input type="text" placeholder="Export Bill Date" name='export_bill_date' class="required" data-bind="date" id='export_bill_date' />
                                </div>

                                <div class="clear"></div>
                            </div>
                        </div>

                        <div class="clear height20"></div>
						<table width="100%" border="0" cellspacing="0" cellpadding="4" class="tablecontent invoicetable">
							<tr>
								<th rowspan="2">S.No</th>
								<th rowspan="2">Description<br/> of Goods/Services</th>
								<th rowspan="2">HSN/SAC <br/>Code<br/>(GST)</th>
								<th rowspan="2">Qty</th>
								<th rowspan="2">Unit</th>
								<th rowspan="2">Rate <br/><span style="font-family: open_sans; font-size:11px;">per item</span></th>
								<th rowspan="2">Total</th>
								<th rowspan="2">Discount(%)</th>
								<th rowspan="2" class="advancecol">Advance</th>
								<th rowspan="2">Taxable<br/>value</th>
								<th colspan="2" style="border-bottom:1px solid #808080;">IGST</th>
								<th colspan="2" style="border-bottom:1px solid #808080;">CESS</th>
								<th style="border-bottom:1px solid #808080;"></th>
							</tr>
							<tr>
								<th>Rate(%)</th>
								<th>Amount</th>
								<th>Rate(%)</th>
								<th>Amount</th>
								<th></th>
							</tr>

							<tr class="invoice_tr" data-row-id="1" id="invoice_tr_1">
								<td><span class="serialno" id="invoice_tr_1_serialno" style="width:20px;">1</span><input type="hidden" id="invoice_tr_1_itemid" name="invoice_itemid[]" /></td>
								<td id="invoice_td_1_itemname"><input type="text" id="invoice_tr_1_itemname" name="invoice_itemname[]" class="inptxt autocompleteitemname required" placeholder="Enter Item" style="width:120px;" /></td>
								<td><input type="text" id="invoice_tr_1_hsncode" name="invoice_hsncode[]" readonly="true" class="readonly" placeholder="HSN/SAC Code" style="width:100px;" /></td>
								<td><input type="number" min="1" id="invoice_tr_1_quantity" name="invoice_quantity[]" class="required invoiceQuantity inptxt" value="0" placeholder="0" style="width:40px;" /></td>
								<td><input type="text" id="invoice_tr_1_unit" name="invoice_unit[]" readonly="true" class="readonly pricinput" placeholder="Unit" style="width:40px;" /></td>
								<td><div class="inptxt padrgt0" style="width:70px;"><i class="fa fa-inr"></i><input type="text" id="invoice_tr_1_rate" name="invoice_rate[]" readonly="true" class="readonly pricinput" placeholder="0.00" /></div></td>
								<td><div style="width:70px;" class="inptxt padrgt0"><i class="fa fa-inr"></i><input type="text" id="invoice_tr_1_total" name="invoice_total[]" readonly="true" class="readonly pricinput" placeholder="0.00" /></div></td>
								<td><input type="text" class="inptxt invoiceDiscount" id="invoice_tr_1_discount" name="invoice_discount[]" data-bind="decimal" value="0" placeholder="0" style="width:90%;" /></td>
								<td class="advancecol"><div style="width:70px;" class="inptxt padrgt0"><i class="fa fa-inr"></i><input type="text" id="invoice_tr_1_advancevalue" name="invoice_advancevalue[]" class="validateInvoiceAmount invoiceAdvanceValue pricinput" data-bind="decimal" value="0" placeholder="0.00"></div></td>
								<td><div style="width:70px;" class="inptxt padrgt0"><i class="fa fa-inr"></i><input type="text" id="invoice_tr_1_taxablevalue" name="invoice_taxablevalue[]" readonly="true" class="readonly pricinput" placeholder="0.00" /></div></td>
								<td><input type="text" id="invoice_tr_1_igstrate" name="invoice_igstrate[]" readonly="true" class="readonly pricinput" placeholder="0.00" style="width:90%;" /></td>
								<td><div style="width:70px;" class="inptxt padrgt0"><i class="fa fa-inr"></i><input type="text" id="invoice_tr_1_igstamount" name="invoice_igstamount[]" readonly="true" class="readonly pricinput invigstamount" placeholder="0.00" /></div></td>
								<td><input type="text" id="invoice_tr_1_cessrate" name="invoice_cessrate[]" readonly="true" class="readonly pricinput" placeholder="0.00" style="width:90%;" /></td>
								<td><div style="width:70px;" class="inptxt padrgt0"><i class="fa fa-inr"></i><input type="text" id="invoice_tr_1_cessamount" name="invoice_cessamount[]" readonly="true" class="readonly pricinput invcessamount" placeholder="0.00" /></div></td>	
								<td nowrap="nowrap" class="icon"><a class="addMoreInvoice" href="javascript:void(0)"><div class="tooltip"><i class="fa fa-plus-circle addicon"></i><span class="tooltiptext">Add More</span></div></a></td>
							</tr>

							<tr>
								<td colspan="13" align="right" class="lightyellow totalamount">Total Invoice Value <span>(In Figure)</span><div class="totalprice"><i class="fa fa-inr"></i><span class="invoicetotalprice">0.00</span></div></td>
								<td class="lightyellow" align="left"></td>
							</tr>

							<tr>
								<td colspan="13" align="right" class="lightpink fontbold totalamountwords" style="font-size:13px;">Total Invoice Value <small>(In Words):</small> <span class="totalpricewords">Nill</span></td>
								<td class="lightpink" align="left"></td>
							</tr>

						</table>

                        <div class="clear height40"></div>
						
						<div class="invoicebtn" style="width:30%;float:left;margin-right:10%;">
							<div class="tc">
								<textarea placeholder="Enter Description" name="description" id="description" data-bind="content"></textarea>
							</div>
							<div class="clear height20"></div>
						</div>

                        <div class="invoicebtn" style="width:60%;float:left;">
							<div class="tc">
                                <a href="javascript:void(0)" class="btn txtorange orangeborder popupbtn">Add Item</a>
								<a href="javascript:void(0)" class="btn txtorange orangeborder" id="save_add_new_invoice">Save & Add New Invoice</a>
                                <input type='submit' name="save_invoice" id="save_invoice" class="btn txtorange orangeborder" value="Save Invoice">
                                <a href="#" class="btn txtorange orangeborder">Print</a>
                            </div>
                            <div class="clear height20"></div>
                        </div>

                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function () {

		/* open add new item popup */
		$(".popupbtn").click(function() {
            $("#popup").css({"display":"block"});
            $("#fade").css({"display":"block"});
        });
        /* end of open add new item popup */

		/* close add new item popup */
        $('#btnclose').click(function(){
            $("#popup").hide();
            $("#fade").hide();
		});
		/* end of close add new item popup */

		/* Get HSN/SAC Code */
        $( "#item_category_name" ).autocomplete({
            minLength: 3,
            source: "<?php echo PROJECT_URL; ?>/?ajax=client_hsnsac_code",
            select: function( event, ui ) {
				$("#item_category").val(ui.item.item_id);
				$("#item_hsn_code").text(ui.item.hsn_code);
            }
        });
        /* End of Get HSN/SAC Code */

		/* validate item unit price allow only numbers or decimals */
        $(".popupform").on("keypress input paste", ".itemUnitPrice", function (event) {
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
                        alert(response.message);
                    }
                    
                    $('#add-item-form')[0].reset();
                }
            });
        });
		/* end of submit add new item form */


		/* Supplier State OR Company State */
        var supplierStateId = '<?php echo $dataCurrentUserArr['data']->kyc->state_id; ?>';

		/* invoice date */
        $("#invoice_date").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd'
        });

		/* export bill date */
        $("#export_bill_date").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd'
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

                $("#billing_address").val(ui.item.address);

				/* calculate row invoice and invoice total on state change */
                rowInvoiceCalculationOnStateChnage();
            }
        });
        /* End of Get Billing Receivers */
        
        /* Get Shipping Receivers */
        $( "#shipping_name" ).autocomplete({
            minLength: 1,
            source: "<?php echo PROJECT_URL; ?>/?ajax=client_get_receiver",
            select: function( event, ui ) {

                $("#shipping_address").val(ui.item.address);

                /* calculate row invoice and invoice total on state change */
                rowInvoiceCalculationOnStateChnage();
            }
        });
        /* End of Get Shipping Receivers */
        
        /* If shipping address is same as billing address */
        $("#same_as_billing").change(function(){

            if($(this).is(":checked")) {

                $("#shipping_name").val($("#billing_name").val());
                $("#shipping_address").val($("#billing_address").val());
                $("#shipping_state_name").val($("#billing_state_name").val());
                $("#shipping_country").val($("#billing_country").val());

                $("#shipping_name").prop("readonly", true);
                $("#shipping_address").prop("readonly", true);
				$("#shipping_state_name").prop("readonly", true);
                $('#shipping_country').attr('disabled', true);
                $("#shipping_country").select2();

            } else {
                
                $("#shipping_name").prop("readonly", false);
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

            /* call function of total invoice */
            totalInvoiceValueCalculation();
        });
        /* end of remove the existing invoice item */
		
		/* add more invoice row script code */
        $(".invoicetable .addMoreInvoice").click(function() {

            var trlength = $(".invoice_tr").length;
            var nexttrid = parseInt($("tr.invoice_tr:last").attr("data-row-id")) + 1;

            var newtr = '<tr class="invoice_tr" data-row-id="'+nexttrid+'" id="invoice_tr_'+nexttrid+'">';
                newtr += '<td><span class="serialno" id="invoice_tr_'+nexttrid+'_serialno" style="width:20px;">'+(trlength+1)+'</span><input type="hidden" id="invoice_tr_'+nexttrid+'_itemid" name="invoice_itemid[]" /></td>';
                newtr += '<td id="invoice_td_'+nexttrid+'_itemname"><input type="text" id="invoice_tr_'+nexttrid+'_itemname" name="invoice_itemname[]" class="inptxt autocompleteitemname required" placeholder="Enter Item" style="width:120px;" /></td>';
                newtr += '<td><input type="text" id="invoice_tr_'+nexttrid+'_hsncode" name="invoice_hsncode[]" readonly="true" class="readonly" placeholder="HSN/SAC Code" style="width:100px;" /></td>';
                newtr += '<td><input type="number" min="1" id="invoice_tr_'+nexttrid+'_quantity" name="invoice_quantity[]" class="required invoiceQuantity inptxt" value="0" placeholder="0" style="width:40px;" /></td>';
                newtr += '<td><input type="text" id="invoice_tr_'+nexttrid+'_unit" name="invoice_unit[]" readonly="true" class="readonly pricinput" placeholder="Unit" style="width:40px;" /></td>';
                newtr += '<td><div class="inptxt padrgt0" style="width:70px;"><i class="fa fa-inr"></i><input type="text" id="invoice_tr_'+nexttrid+'_rate" name="invoice_rate[]" readonly="true" class="readonly pricinput" placeholder="0.00" /></div></td>';
                newtr += '<td><div style="width:70px;" class="inptxt padrgt0"><i class="fa fa-inr"></i><input type="text" id="invoice_tr_'+nexttrid+'_total" name="invoice_total[]" readonly="true" class="readonly pricinput" placeholder="0.00" /></div></td>';
                newtr += '<td><input type="text" class="inptxt invoiceDiscount" id="invoice_tr_'+nexttrid+'_discount" name="invoice_discount[]" data-bind="decimal" value="0" placeholder="0" style="width:90%;" /></td>';
				newtr += '<td class="advancecol"><div style="width:70px;" class="inptxt padrgt0"><i class="fa fa-inr"></i><input type="text" id="invoice_tr_'+nexttrid+'_advancevalue" name="invoice_advancevalue[]" class="validateInvoiceAmount invoiceAdvanceValue pricinput" data-bind="decimal" value="0" placeholder="0.00" /></div></td>';
				newtr += '<td><div style="width:70px;" class="inptxt padrgt0"><i class="fa fa-inr"></i><input type="text" id="invoice_tr_'+nexttrid+'_taxablevalue" name="invoice_taxablevalue[]" readonly="true" class="readonly pricinput" placeholder="0.00" /></div></td>';
                newtr += '<td><input type="text" id="invoice_tr_'+nexttrid+'_igstrate" name="invoice_igstrate[]" readonly="true" class="readonly pricinput" placeholder="0.00" style="width:90%;" /></td>';
                newtr += '<td><div style="width:70px;" class="inptxt padrgt0"><i class="fa fa-inr"></i><input type="text" id="invoice_tr_'+nexttrid+'_igstamount" name="invoice_igstamount[]" readonly="true" class="readonly pricinput invigstamount" placeholder="0.00" /></div></td>';
				newtr += '<td><input type="text" id="invoice_tr_'+nexttrid+'_cessrate" name="invoice_cessrate[]" readonly="true" class="readonly pricinput" placeholder="0.00" style="width:90%;" /></td>';
                newtr += '<td><div style="width:70px;" class="inptxt padrgt0"><i class="fa fa-inr"></i><input type="text" id="invoice_tr_'+nexttrid+'_cessamount" name="invoice_cessamount[]" readonly="true" class="readonly pricinput invcessamount" placeholder="0.00" /></div></td>';
                newtr += '<td nowrap="nowrap" class="icon"><a class="deleteInvoice" data-invoice-id="'+nexttrid+'" href="javascript:void(0)"><div class="tooltip"><i class="fa fa-trash deleteicon"></i><span class="tooltiptext">Delete</span></div></a></td>';
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

				$.ajax({
					data: {invoiceData:$("#create-invoice").serialize(), action:"saveNewInvoice"},
					dataType: 'json',
					type: 'post',
					url: "<?php echo PROJECT_URL; ?>/?ajax=client_save_export_invoice",
					success: function(response){

						if(response.status == "error") {
							
							$(".errorValidationContainer").html(response.message);
							$(".errorValidationContainer").show();
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

			$.ajax({
                data: {invoiceData:$("#create-invoice").serialize(), action:"saveNewInvoice"},
                dataType: 'json',
                type: 'post',
                url: "<?php echo PROJECT_URL; ?>/?ajax=client_save_export_invoice",
                success: function(response){

                    if(response.status == "error") {
                        
						$(".errorValidationContainer").html(response.message);
                        $(".errorValidationContainer").show();
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
                    parseInt($("#invoice_tr_"+rowid+"_quantity").val()) > 0 && 
                    $("#invoice_tr_"+rowid+"_rate").val() != '' && 
                    parseFloat($("#invoice_tr_"+rowid+"_rate").val()) > 0
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
                    var currentTrRate = parseFloat(response.unit_price);

                    if($.trim($("#invoice_tr_"+rowid+"_discount").val()).length == 0 || $.trim($("#invoice_tr_"+rowid+"_discount").val()).length == '' || $.trim($("#invoice_tr_"+rowid+"_discount").val()) == '.') {
                        var currentTrDiscount = 0.00;
                    } else {
                        var currentTrDiscount = parseFloat($("#invoice_tr_"+rowid+"_discount").val());
                    }
					
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

                    var currentTotal = (currentTrQuantity * currentTrRate).toFixed(2);
                    $("#invoice_tr_"+rowid+"_total").val(currentTotal);

                    var currentTrDiscountAmount = (currentTrDiscount/100) * currentTotal;
					var currentTrReduceAmount = advAdjustmentAmount + currentTrDiscountAmount;
                    var currentTrTaxableValue = (currentTotal - currentTrReduceAmount).toFixed(2);
                    $("#invoice_tr_"+rowid+"_taxablevalue").val(currentTrTaxableValue);
					
					var exportSupplyMeant = $('input[name=export_supply_meant]:checked', '#create-invoice').val();

					if(exportSupplyMeant == "withpayment") {

						$("#invoice_tr_"+rowid+"_igstrate").val(response.igst_tax_rate);
						$("#invoice_tr_"+rowid+"_cessrate").val(response.cess_tax_rate);

						var igstTax = parseFloat(response.igst_tax_rate);
						var igstTaxAmount = (igstTax/100) * currentTrTaxableValue;
						$("#invoice_tr_"+rowid+"_igstamount").val(igstTaxAmount.toFixed(2));
						
						var cessTax = parseFloat(response.cess_tax_rate);
						var cessTaxAmount = (cessTax/100) * currentTrTaxableValue;
						$("#invoice_tr_"+rowid+"_cessamount").val(cessTaxAmount.toFixed(2));
					} else {
						
						$("#invoice_tr_"+rowid+"_cgstrate").val(0.00);
						$("#invoice_tr_"+rowid+"_sgstrate").val(0.00);
						$("#invoice_tr_"+rowid+"_cgstamount").val(0.00);
						$("#invoice_tr_"+rowid+"_sgstamount").val(0.00);
						$("#invoice_tr_"+rowid+"_igstrate").val(0.00);
						$("#invoice_tr_"+rowid+"_igstamount").val(0.00);
						$("#invoice_tr_"+rowid+"_cessrate").val(0.00);
						$("#invoice_tr_"+rowid+"_cessamount").val(0.00);
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
                    parseInt($("#invoice_tr_"+rowid+"_quantity").val()) > 0 && 
                    $("#invoice_tr_"+rowid+"_rate").val() != '' && 
                    parseFloat($("#invoice_tr_"+rowid+"_rate").val()) > 0
                ) {
                    
                    var taxablevalue = parseFloat($("#invoice_tr_"+rowid+"_taxablevalue").val());
                    var igstamount = parseFloat($("#invoice_tr_"+rowid+"_igstamount").val());
					var cessamount = parseFloat($("#invoice_tr_"+rowid+"_cessamount").val());

					totalInvoiceValue += taxablevalue + igstamount + cessamount;
                }
            });
            
            totalFinalInvoiceValue = totalInvoiceValue.toFixed(2);
            $( ".totalprice .invoicetotalprice" ).text(totalFinalInvoiceValue);

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