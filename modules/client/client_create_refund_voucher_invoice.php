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
		$obj_client->redirect(PROJECT_URL."/?page=client_refund_voucher_invoice_list");
		exit();
	}

    $dataCurrentUserArr = $obj_client->getUserDetailsById( $obj_client->sanitize($_SESSION['user_detail']['user_id']) );
    $rfInvoiceNumber = $obj_client->generateRFInvoiceNumber( $obj_client->sanitize($_SESSION['user_detail']['user_id']) );
?>
<!--POPUP START HERE-->
<div style="display:none;position:fixed;" id="popup" class="formpopup topanimation">
    <div class="popupform">
        
        <p style="text-align:center;"> <a class="closebtn" id="btnclose" ><img src="image/icon-close.png" alt="#"></a> </p>
        <h3 class="txtorange">ADD ITEM</h3>
        
        <div class="adminformbx">
            
            <form name="add-item-form" id="add-item-form" method="POST">
                
                <div class="formcol">
                    <label>Item<span class="starred">*</span></label>
                    <input type="text" placeholder="Item name" name='item_name' id="item_name" data-bind="content" class="required" value='<?php if(isset($_POST['item_name'])){ echo $_POST['item_name']; } ?>' />
                </div>
                
                <div class="formcol two">
                    <label>Category<span class="starred">*</span></label>
                    <select name="item_category" id="item_category" class="required" data-bind="numnzero">
                        <?php $dataItemArrs = $obj_client->getMasterItems("item_id,item_name,hsn_code,(case when status='1' Then 'active' when status='0' then 'deactive' end) as status", "is_deleted='0' AND status='1'"); ?>
                        <?php if(!empty($dataItemArrs)) { ?>
                            <option value=''>Select Category</option>
                            <?php foreach($dataItemArrs as $dataItem) { ?>
                                <option value='<?php echo $dataItem->item_id; ?>' data-hsncode="<?php echo $dataItem->hsn_code; ?>" <?php if(isset($_POST['item_category']) && $_POST['item_category'] === $dataItem->item_id){ echo 'selected="selected"'; } ?>><?php echo $dataItem->item_name; ?></option>
                            <?php } ?>
                        <?php } ?>
                    </select>
                </div>
				
				<div class="formcol third">
                    <label>HSN Code</label>
                    <div class="clear"></div>
                    <div class="readonly-section" id="item_hsn_code"><?php echo "HSN Code"; ?></div>
                </div>

				<div class="clear"></div>
                
                <div class="formcol">
                    <label>Unit<span class="starred">*</span></label>
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
                    <input type="text" placeholder="Item Unit Price" name='unit_price' id="unit_price" class="required" data-bind="demical" />
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

        <h1>Generate Refund Voucher Invoice</h1>
        <hr class="headingborder">
        
        <form name="create-invoice" id="create-invoice" method="POST">
            <div class="adminformbx">

				<?php $obj_client->showErrorMessage(); ?>
				<?php $obj_client->showSuccessMessge(); ?>
				<?php $obj_client->unsetMessage(); ?>

                <div class="kycform">
                    <div class="kycmainbox invoiceform">

                        <div class="clear"></div>

						<div class="formcol">
                            <label>Invoice Serial Number <span class="starred">*</span></label>
                            <input type="text" placeholder="Invoice Serial Number" readonly="true" class="readonly required" value="<?php echo $rfInvoiceNumber; ?>" name="invoice_serial_number" id="invoice_serial_number" />
                        </div>

                        <div class="formcol two">
                            <label>Invoice Date <span class="starred">*</span></label>
                            <input type="text" placeholder="YYYY-MM-DD" class="required" data-bind="date" name="invoice_date" id="invoice_date" value="<?php echo date("Y-m-d"); ?>" />
                        </div>     

						<div class="clear height10"></div>
						
						<div class="formcol">
                            <label>Company Name <span class="starred">*</span></label>
                            <input type="text" placeholder="Cyfuture India Pvt. Ltd" data-bind="content" readonly="true" class="readonly required" name="company_name" id="company_name" value="<?php if(isset($dataCurrentUserArr['data']->kyc->name)) { echo $dataCurrentUserArr['data']->kyc->name; } ?>" />
                        </div>
						
						<div class="formcol two">
                            <label>Company Address <span class="starred">*</span></label>
                            <input type="text" placeholder="Cyfuture India Pvt. Ltd" data-bind="address" readonly="true" class="readonly required" name="company_address" id="company_address" value="<?php if(isset($dataCurrentUserArr['data']->kyc->registered_address)) { echo $dataCurrentUserArr['data']->kyc->registered_address; } ?>" />
                        </div>

						<div class="formcol third">
                            <label>Company State <span class="starred">*</span></label>
                            <input type="text" placeholder="Compant State" data-bind="content" readonly="true" class="readonly required" name="company_state" id="company_state" value="<?php if(isset($dataCurrentUserArr['data']->kyc->state_name)) { echo $dataCurrentUserArr['data']->kyc->state_name; } ?>" />
                        </div>
						
						<div class="clear height10"></div>

                        <div class="formcol">
                            <label>Company GSTIN <span class="starred">*</span></label>
                            <input type="text" placeholder="BYRAJ14N3KKT" name="company_gstin_number" data-bind="alphanum" readonly="true" class="readonly required" id="company_gstin_number" value="<?php if(isset($dataCurrentUserArr['data']->kyc->gstin_number)) { echo $dataCurrentUserArr['data']->kyc->gstin_number; } ?>" />
                        </div>

						<div class="clear height10"></div>
						
						<div class="formcol">
                            <label>Tax Is Payable On Reverse Charge <span class="starred">*</span></label>
                            <div class="clear"></div>
                            <input type="radio" name="tax_reverse_charge" value="1" checked="checked" /><span class="inputtxt">Yes</span>
                            <input type="radio" name="tax_reverse_charge" value="0" /><span class="inputtxt">No</span>
                        </div>
						
						<div class="formcol two">
                            <label>Place Of Supply<span class="starred">*</span></label>
                            <input type="text" placeholder="Place Of Supply State" name="place_of_supply_state" data-bind="content" readonly="true" class="readonly required" id="place_of_supply_state" />
							<input type="hidden" name="place_of_supply" readonly="true" class="readonly required" id="place_of_supply" />
                        </div>

						<div class="formcol third">
                            <label>Canceled Invoice <span class="starred">*</span></label>
                            <div class="clear"></div>
                            <input type="radio" name="is_canceled" value="1" /><span class="inputtxt">Yes</span>
                            <input type="radio" name="is_canceled" value="0" checked="checked" /><span class="inputtxt">No</span>
                        </div>

						<div class="clear height10"></div>
						
						<div class="formcol two">
                            <label>Receipt Voucher Number <span class="starred">*</span></label>
							<select name='receipt_voucher_number' id='receipt_voucher_number' class="required" data-bind="content">
								<?php $dataReceiptVoucherArrs = $obj_client->get_results("select serial_number, invoice_date, supply_place, is_canceled from ".$obj_client->getTableName('client_rv_invoice')." where status='1' and is_deleted='0' order by serial_number asc"); ?>
								<?php if(!empty($dataReceiptVoucherArrs)) { ?>
									<option value=''>Select Receipt Voucher</option>
									<?php foreach($dataReceiptVoucherArrs as $dataReceiptVoucherArr) { ?>
										<option value='<?php echo $dataReceiptVoucherArr->serial_number; ?>' data-date="<?php echo $dataReceiptVoucherArr->invoice_date; ?>"><?php echo $dataReceiptVoucherArr->serial_number; ?></option>
									<?php } ?>
								<?php } ?>
							</select>
						</div>
						
						<div class="formcol two">
                            <label>Receipt Voucher Date <span class="starred">*</span></label>
                            <input type="text" placeholder="YYYY-MM-DD" readonly="true" class="readonly required" data-bind="date" name="receipt_voucher_date" id="receipt_voucher_date" />
                        </div>

                        <div class="clear height20"></div>

                        <div class="col-md-6" style="padding-left:0px;">
                            <div class="inovicedeatil">

                                <h4>Recipient Detail</h4>
                                <div class="formcol">
                                    <label>Name <span class="starred">*</span></label>
                                    <input type="text" placeholder="Name" data-bind="content" readonly="true" class="readonly required" name="billing_name" id="billing_name" />
                                </div>

                                <div class="formcol">
                                    <label>Address <span class="starred">*</span></label>
                                    <textarea placeholder="Address" data-bind="address" readonly="true" class="readonly required" name="billing_address" id="billing_address"></textarea>
                                </div>

                                <div class="formcol">
                                    <label>State <span class="starred">*</span></label>
									<input type="text" placeholder="State" data-bind="content" readonly="true" class="readonly required" name="billing_state" id="billing_state" />
                                </div>

                                <div class="formcol">
                                    <label>State Code <span class="starred">*</span></label>
                                    <input type="text" placeholder="State Code" name='billing_state_code' readonly="true" class="readonly required" id='billing_state_code' />
                                </div>

                                <div class="formcol">
                                    <label>GSTIN</label>
                                    <input type="text" placeholder="GSTIN" name='billing_gstin_number' data-bind="alphanum" readonly="true" class="readonly" id='billing_gstin_number' />
                                </div>
                                
                                <div class="clear"></div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 greyborder">
                            <div class="inovicedeatil">
                                <h4>Address Of Delivery / Shipping Detail</h4>

                                <div class="formcol">
                                    <label>Name <span class="starred">*</span></label>
                                    <input type="text" placeholder="Name" data-bind="content" readonly="true" class="readonly required" name="shipping_name" id="shipping_name" />
                                </div>

                                <div class="formcol">
                                    <label>Address <span class="starred">*</span></label>
                                    <textarea placeholder="Address" data-bind="address" readonly="true" class="readonly required" name="shipping_address" id="shipping_address"></textarea>
                                </div>

                                <div class="formcol">
                                    <label>State <span class="starred">*</span></label>
                                    <input type="text" placeholder="State" data-bind="content" readonly="true" class="readonly required" name="shipping_state" id="shipping_state" />
                                </div>

                                <div class="formcol">
                                    <label>State Code <span class="starred">*</span></label>
                                    <input type="text" placeholder="State Code" name='shipping_state_code' readonly="true" class="readonly required" id='shipping_state_code' />
                                </div>

                                <div class="formcol">
                                    <label>GSTIN</label>
                                    <input type="text" placeholder="GSTIN" name='shipping_gstin_number' data-bind="alphanum" readonly="true" class="readonly" id='shipping_gstin_number' />
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
								<th rowspan="2">Refund Value</th>
								<th colspan="2" style="border-bottom:1px solid #808080;">CGST</th>
								<th colspan="2" style="border-bottom:1px solid #808080;">SGST</th>
								<th colspan="2" style="border-bottom:1px solid #808080;">IGST</th>
								<th colspan="2" style="border-bottom:1px solid #808080;">CESS</th>
								<th style="border-bottom:1px solid #808080;"></th>
							</tr>
							<tr class="gst-refund-vouchers">
								<th>Rate(%)</th>
								<th>Amount</th>
								<th>Rate(%)</th>
								<th>Amount</th>
								<th>Rate(%)</th>
								<th>Amount</th>
								<th>Rate(%)</th>
								<th>Amount</th>
								<th></th>
							</tr>

							<tr>
								<td colspan="12" align="right" class="lightyellow totalamount">Total Invoice Value <span>(In Figure)</span><div class="totalprice"><i class="fa fa-inr"></i><span class="invoicetotalprice">0.00</span></div></td>
								<td class="lightyellow" align="left"></td>
							</tr>

							<tr>
								<td colspan="12" align="right" class="lightpink fontbold totalamountwords" style="font-size:13px;">Total Invoice Value <small>(In Words):</small> <span class="totalpricewords">Nill</span></td>
								<td class="lightpink" align="left"></td>
							</tr>
						</table>

                        <div class="clear height40"></div>
                        <div class="adminformbxsubmit invoicebtn" style="width:100%;"> 
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
		
		/* change of item category */
		$("#item_category").change(function () {

            var hsncode = $(this).find(':selected').attr("data-hsncode");
            if(typeof(hsncode) === "undefined") {
                $("#item_hsn_code").text("HSN Code");
            } else {
                $("#item_hsn_code").text(hsncode);
            }
        });
		/* end of change of item category */
        
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

		/* select2 js for receipt voucher number */
        $("#receipt_voucher_number").select2();
		
		/* on change receipt voucher */
        $("#receipt_voucher_number").change(function () {
           
            var receiptvoucherdate = $(this).find(':selected').attr("data-date");
            if(typeof(receiptvoucherdate) === "undefined") {
                $("#receipt_voucher_date").val("");
				return false;
            } else {
                $("#receipt_voucher_date").val(receiptvoucherdate);
            }

			/* get receipt voucher detail */
			$.ajax({
				data: {receiptVoucherNumber:$(this).find(':selected').val(), action:"getReceiptVoucher"},
				dataType: 'json',
				type: 'post',
				url: "<?php echo PROJECT_URL; ?>/?ajax=client_get_receipt_voucher_detail",
				success: function(response){
					
					$(".invoice_tr").remove();
					if(response.status == "success") {

						$("#place_of_supply").val(response.supply_place);
						$("#place_of_supply_state").val(response.supply_state_name);

						$("#billing_name").val(response.billing_name);
						$("#billing_address").val(response.billing_address);
						$("#billing_state").val(response.billing_state_name);
						$("#billing_state_code").val(response.billing_state_code);
						$("#billing_gstin_number").val(response.billing_gstin_number);

						$("#shipping_name").val(response.shipping_name);
						$("#shipping_address").val(response.shipping_address);
						$("#shipping_state").val(response.shipping_state_name);
						$("#shipping_state_code").val(response.shipping_state_code);
						$("#shipping_gstin_number").val(response.shipping_gstin_number);
						
						$(".gst-refund-vouchers").after(response.rv_items);
                    } else {
						alert(response.status);
					}

					/* call function of total invoice */
					totalInvoiceValueCalculation();
				}
			});
			/* end of get receipt voucher details */
        });
		/* end of on change receipt voucher */

		/* on refund amount chnage of item */
        $(".invoicetable").on("input", ".invoiceTaxableValue", function(){

            var rowid = $(this).parent().parent().parent().attr("data-row-id");
            var currentTrItemId = parseInt($("#invoice_tr_"+rowid+"_itemid").val());
            rowInvoiceCalculation(currentTrItemId, rowid);
        });
        /* end of on refund amount chnage of item */

		/* validate invoice discount allow only numbers or decimals */
        $(".invoicetable").on("keypress input paste", ".validateInvoiceAmount", function (event) {
            return validateInvoiceAmount(event, this);
        });
        /* end of validate invoice discount allow only numbers or decimals */

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
					data: {invoiceData:$("#create-invoice").serialize(), action:"saveNewRFInvoice"},
					dataType: 'json',
					type: 'post',
					url: "<?php echo PROJECT_URL; ?>/?ajax=client_save_refund_voucher_invoice",
					success: function(response){

						if(response.status == "error") {
							
							$(".errorValidationContainer").html(response.message);
							$(".errorValidationContainer").show();
						} else if(response.status == "success") {
							
							$(".errorValidationContainer").html("");
							$(".errorValidationContainer").hide();
							window.location.href = '<?php echo PROJECT_URL; ?>/?page=client_create_refund_voucher_invoice';
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
                data: {invoiceData:$("#create-invoice").serialize(), action:"saveNewRFInvoice"},
                dataType: 'json',
                type: 'post',
                url: "<?php echo PROJECT_URL; ?>/?ajax=client_save_refund_voucher_invoice",
                success: function(response){

                    if(response.status == "error") {

						$(".errorValidationContainer").html(response.message);
                        $(".errorValidationContainer").show();
                    } else if(response.status == "success") {

                        $(".errorValidationContainer").html("");
                        $(".errorValidationContainer").hide();
                        window.location.href = '<?php echo PROJECT_URL; ?>/?page=client_refund_voucher_invoice_list';
                    }
                }
            });
        });
        /* end of save new item */

        /* calculate row invoice function */
        function rowInvoiceCalculation(itemid, rowid) {

			var receiverStateId = $("#place_of_supply").val();

            /* fetch item details by its id */
            $.ajax({
                data: {itemId:itemid, action:"getItemDetail"},
                dataType: 'json',
                type: 'post',
                url: "<?php echo PROJECT_URL; ?>/?ajax=client_get_item_detail",
                success: function(response){

                    /* calculation */
                    if($.trim($("#invoice_tr_"+rowid+"_taxablevalue").val()).length == 0 || $.trim($("#invoice_tr_"+rowid+"_taxablevalue").val()).length == '' || $.trim($("#invoice_tr_"+rowid+"_taxablevalue").val()) == '.') {
                        var currentTrAmount = 0.00;
                    } else {
                        var currentTrAmount = parseFloat($("#invoice_tr_"+rowid+"_taxablevalue").val());
                    }

					var currentTrTaxableValue = currentTrAmount.toFixed(2);

					if(supplierStateId === receiverStateId) {

						$("#invoice_tr_"+rowid+"_cgstrate").val(response.csgt_tax_rate);
						$("#invoice_tr_"+rowid+"_sgstrate").val(response.sgst_tax_rate);
						$("#invoice_tr_"+rowid+"_cessrate").val(response.cess_tax_rate);
						$("#invoice_tr_"+rowid+"_igstrate").val(0.00);
						$("#invoice_tr_"+rowid+"_igstamount").val(0.00);
						
						var cgstTax = parseFloat(response.csgt_tax_rate);
						var cgstTaxAmount = (cgstTax/100) * currentTrTaxableValue;
						$("#invoice_tr_"+rowid+"_cgstamount").val(cgstTaxAmount.toFixed(2));

						var sgstTax = parseFloat(response.sgst_tax_rate);
						var sgstTaxAmount = (sgstTax/100) * currentTrTaxableValue;
						$("#invoice_tr_"+rowid+"_sgstamount").val(sgstTaxAmount.toFixed(2));
						
						var cessTax = parseFloat(response.cess_tax_rate);
						var cessTaxAmount = (cessTax/100) * currentTrTaxableValue;
						$("#invoice_tr_"+rowid+"_cessamount").val(cessTaxAmount.toFixed(2));
					} else {

						$("#invoice_tr_"+rowid+"_cgstrate").val(0.00);
						$("#invoice_tr_"+rowid+"_sgstrate").val(0.00);
						$("#invoice_tr_"+rowid+"_cgstamount").val(0.00);
						$("#invoice_tr_"+rowid+"_sgstamount").val(0.00);
						$("#invoice_tr_"+rowid+"_igstrate").val(response.igst_tax_rate);
						$("#invoice_tr_"+rowid+"_cessrate").val(response.cess_tax_rate);

						var igstTax = parseFloat(response.igst_tax_rate);
						var igstTaxAmount = (igstTax/100) * currentTrTaxableValue;
						$("#invoice_tr_"+rowid+"_igstamount").val(igstTaxAmount.toFixed(2));
						
						var cessTax = parseFloat(response.cess_tax_rate);
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
					$("#invoice_tr_"+rowid+"_taxablevalue").val() != '' && 
                    parseFloat($("#invoice_tr_"+rowid+"_taxablevalue").val()) > 0
                ) {

                    var taxablevalue = parseFloat($("#invoice_tr_"+rowid+"_taxablevalue").val());
                    var cgstamount = parseFloat($("#invoice_tr_"+rowid+"_cgstamount").val());
                    var sgstamount = parseFloat($("#invoice_tr_"+rowid+"_sgstamount").val());
                    var igstamount = parseFloat($("#invoice_tr_"+rowid+"_igstamount").val());
					var cessamount = parseFloat($("#invoice_tr_"+rowid+"_cessamount").val());

                    totalInvoiceValue += taxablevalue + cgstamount + sgstamount + igstamount + cessamount;
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