<?php
$obj_client = new client();
if( !isset($_SESSION['user_detail']['user_id']) || $_SESSION['user_detail']['user_id'] == '' ) {
    $obj_client->redirect(PROJECT_URL);
    exit();
}
?>
<!--POPUP START HERE-->
<div style="display: none; position: fixed;" id="popup" class="formpopup topanimation">
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
                    <div class="readonly-section" id="item_hsn_code"><?php if(isset($dataArr[0]->hsn_code)){ echo $dataArr[0]->hsn_code; } else { echo "HSN Code"; } ?></div>
                </div>
                <div class="clear"></div>

                <div class="formcol">
                    <label>Unit Price(Rs.)<span class="starred">*</span></label>
                    <input type="text" placeholder="Item Unit Price" name='unit_price' id="unit_price" class="required" data-bind="demical" value='<?php if(isset($_POST['unit_price'])) { echo $_POST['unit_price']; } else if(isset($dataArr[0]->unit_price)){ echo $dataArr[0]->unit_price; } ?>'/>
                </div>

                <div class="formcol two">
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
        <h1>Generate Invoice</h1>
        <hr class="headingborder">

        <form name="create-invoice" id="create-invoice" method="POST">
            <div class="adminformbx">
                <div class="kycform">
                    <div class="kycmainbox invoiceform">
                        
                        <div class="clear"></div>
                        <div class="formcol">
                            <label>Company Name <span class="starred">*</span></label>
                            <input type="text" placeholder="Cyfuture India Pvt. Ltd" data-bind="content" class="required" name="company_name" id="company_name" />
                        </div>
                        
                        <div class="formcol">
                            <label>GSTIN Number <span class="starred">*</span></label>
                            <input type="text" placeholder="BYRAJ14N3KKT" name="company_gstn_number" data-bind="alphanum" class="required" id="company_gstn_number" />
                        </div>

                        <div class="formcol third">
                            <label>Tax Is Payable On Reverse Charge <span class="starred">*</span></label>
                            <div class="clear"></div>
                            <input type="radio" name="tax_reverse_charge" value="1" checked="checked" /><span class="inputtxt">Yes</span>
                            <input type="radio" name="tax_reverse_charge" value="0" /><span class="inputtxt">No</span>
                        </div>

                        <div class="clear"></div>

                        <div class="formcol">
                            <label>Invoice Serial Number <span class="starred">*</span></label>
                            <input type="text" placeholder="Invoice Serial Number" class="required" name="invoice_serial_number" id="invoice_serial_number" />
                        </div>

                        <div class="formcol">
                            <label>Invoice Date<span class="starred">*</span></label>
                            <input type="text" placeholder="YYYY-MM-DD" class="required" data-bind="date" name="invoice_date" id="invoice_date" />
                        </div>

                        <div class="formcol third">
                            <label>Transportation Mode <span class="starred">*</span></label>
                            <div class="clear"></div>
                            <input type="radio" name="transportation_mode" value="1" checked="checked" /><span class="inputtxt">Yes</span>
                            <input type="radio" name="transportation_mode" value="0" /><span class="inputtxt">No</span>
                        </div>

                        <div class="formcol two">
                            <label>Date & Time of Supply <span class="starred">*</span></label>
                            <input type="text" placeholder="YYYY-MM-DD HH:MM:SS" class="required" data-bind="datetime" name="date_time_of_supply" id="date_time_of_supply" />
                        </div>
                        
                        <div class="formcol third">
                            <label>Place Of Supply <span class="starred">*</span></label>
                            <input type="text" placeholder="Place Of Supply" data-bind="content" class="required" name="place_of_supply" id="place_of_supply" />
                        </div>
                        <div class="clear height20"></div>

                        <div class="col-md-6" style="padding-left:0px;">
                            <div class="inovicedeatil">
                                
                                <h4>Billing Detail</h4>
                                <div class="formcol">
                                    <label>Place Of Supply <span class="starred">*</span></label>
                                    <input type="text" placeholder="Place Of Supply" data-bind="content" class="required" name="billing_place_of_supply" id="billing_place_of_supply" />
                                </div>

                                <div class="formcol">
                                    <label>Address <span class="starred">*</span></label>
                                    <textarea placeholder="Address" data-bind="address" class="required" name="billing_address" id="billing_address"></textarea>
                                </div>

                                <div class="formcol">
                                    <label>State <span class="starred">*</span></label>
                                    <select name='billing_state' id='billing_state' class='required'>
                                        <?php $dataBStateArrs = $obj_client->get_results("select * from ".$obj_client->getTableName('state')." where status='1' and is_deleted='0' order by state_name asc"); ?>
                                        <?php if(!empty($dataBStateArrs)) { ?>
                                            <option value=''>Select State</option>
                                            <?php foreach($dataBStateArrs as $dataBStateArr) { ?>
                                                <option value='<?php echo $dataBStateArr->state_id; ?>' data-code="<?php echo $dataBStateArr->state_code;?>"><?php echo $dataBStateArr->state_name; ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="formcol">
                                    <label>State Code <span class="starred">*</span></label>
                                    <input type="text" placeholder="State Code" name='billing_state_code' readonly="true" class="readonly required" id='billing_state_code' />
                                </div>

                                <div class="formcol">
                                    <label>GSTIN Number <span class="starred">*</span></label>
                                    <input type="text" placeholder="GSTIN Number" name='billing_gstn_number' data-bind="alphanum" class="required" id='billing_gstn_number' />
                                </div>
                                
                                <div class="clear"></div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 greyborder">
                            <div class="inovicedeatil">
                                <h4>Shipping Detail</h4>
                                
                                <div class="formcol">
                                    <label>Place Of Supply <span class="starred">*</span></label>
                                    <input type="text" placeholder="Place Of Supply" data-bind="content" class="required" name="shipping_place_of_supply" id="shipping_place_of_supply" />
                                </div>

                                <div class="formcol">
                                    <label>Address <span class="starred">*</span></label>
                                    <textarea placeholder="Address" data-bind="address" class="required" name="shipping_address" id="shipping_address"></textarea>
                                </div>

                                <div class="formcol">
                                    <label>State <span class="starred">*</span></label>
                                    <select name='shipping_state' id='shipping_state' class='required'>
                                        <?php $dataStateArrs = $obj_client->get_results("select * from ".$obj_client->getTableName('state')." where status='1' and is_deleted='0' order by state_name asc"); ?>
                                        <?php if(!empty($dataStateArrs)) { ?>
                                            <option value=''>Select State</option>
                                            <?php foreach($dataStateArrs as $dataStateArr) { ?>
                                                <option value='<?php echo $dataStateArr->state_id; ?>' data-code="<?php echo $dataStateArr->state_code;?>"><?php echo $dataStateArr->state_name; ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="formcol">
                                    <label>State Code <span class="starred">*</span></label>
                                    <input type="text" placeholder="State Code" name='shipping_state_code' readonly="true" class="readonly required" id='shipping_state_code' />
                                </div>

                                <div class="formcol">
                                    <label>GSTIN Number <span class="starred">*</span></label>
                                    <input type="text" placeholder="GSTIN Number" name='shipping_gstn_number' data-bind="alphanum" class="required" id='shipping_gstn_number' />
                                </div>
                                
                                <div class="clear"></div>
                            </div>
                        </div>

                        <div class="clear height20"></div>
                        <div class="tableresponsive">
                            
                            <table width="100%" border="0" cellspacing="0" cellpadding="4" class="tablecontent invoicetable">
                                <tr>
                                    <th rowspan="2">S.No</th>
                                    <th rowspan="2">Description<br/> of Goods</th>
                                    <th rowspan="2">HSN <br/>Code<br/>(GST)</th>
                                    <th rowspan="2">Qty</th>
                                    <th rowspan="2">Unit</th>
                                    <th rowspan="2">Rate <br/><span style="font-family: open_sans; font-size:11px;">per item</span></th>
                                    <th rowspan="2">Total</th>
                                    <th rowspan="2">Discount(%)</th>
                                    <th rowspan="2">Taxable<br/>value</th>
                                    <th colspan="2" style="border-bottom:1px solid #808080;">CGST</th>
                                    <th colspan="2" style="border-bottom:1px solid #808080;">SGST</th>
                                    <th colspan="2" style="border-bottom:1px solid #808080;">IGST</th>
                                    <th style="border-bottom:1px solid #808080;"></th>
                                </tr>
                                <tr>
                                    <th>Rate(%)</th>
                                    <th>Amount</th>
                                    <th>Rate(%)</th>
                                    <th>Amount</th>
                                    <th>Rate(%)</th>
                                    <th>Amount</th>
                                    <th></th>
                                </tr>

                                <tr class="invoice_tr" data-row-id="1" id="invoice_tr_1">
                                    <td><span id="invoice_tr_1_serialno" style="width:20px;">1</span></td>
                                    <td><input type="text" id="invoice_tr_1_itemname" class="inptxt autocompleteinput required" placeholder="Enter Item" style="width:120px;" /></td>
                                    <td><input type="text" id="invoice_tr_1_hsncode" readonly="true" class="inptxt readonly required" placeholder="HSN Code" style="width:50px;" /></td>
                                    <td><input type="text" id="invoice_tr_1_quantity" class="required inptxt" placeholder="0" style="width:40px;" /></td>
                                    <td><input type="text" id="invoice_tr_1_unit" class="required inptxt" placeholder="0" style="width:40px;" /></td>
                                    <td><div class="inptxt padrgt0" style="width:70px;"><i class="fa fa-inr"></i><input type="text" id="invoice_tr_1_rate" class="required pricinput" placeholder="0.00" /></div></td>
                                    <td><div style="width:70px;" class="inptxt padrgt0"><i class="fa fa-inr"></i><input type="text" id="invoice_tr_1_total" class="required readonly pricinput" placeholder="0.00" /></div></td>
                                    <td><input type="text" class="inptxt" id="invoice_tr_1_discount" placeholder="0" style="width:90%;" /></td>
                                    <td><div style="width:70px;" class="inptxt padrgt0"><i class="fa fa-inr"></i><input type="text" id="invoice_tr_1_taxablevalue" class="required readonly pricinput" placeholder="0.00" /></div></td>
                                    <td><input type="text" id="invoice_tr_1_cgstrate" class="required readonly inptxt" placeholder="6" style="width:40px;" /></td>
                                    <td><div style="width:70px;" class="inptxt padrgt0"><i class="fa fa-inr"></i><input type="text" id="invoice_tr_1_cgstamount" class="required readonly pricinput" placeholder="0.00" /></div></td>
                                    <td><input type="text" id="invoice_tr_1_sgstrate" class="required readonly inptxt" placeholder="12" style="width:90%;" /></td>
                                    <td><div style="width:70px;" class="inptxt padrgt0"><i class="fa fa-inr"></i><input type="text" id="invoice_tr_1_sgstamount" class="required readonly pricinput" placeholder="0.00" /></div></td>
                                    <td><input type="text" id="invoice_tr_1_igstrate" class="required readonly inptxt" placeholder="18" style="width:90%;" /></td>
                                    <td><div style="width:70px;" class="inptxt padrgt0"><i class="fa fa-inr"></i><input type="text" id="invoice_tr_1_igstamount" class="required readonly pricinput" placeholder="0.00" /></div></td>
                                    <td nowrap="nowrap" class="icon"><a class="addMoreInvoice" href="javascript:void(0)"><div class="tooltip"><i class="fa fa-plus-circle addicon"></i><span class="tooltiptext">Add More</span></div></a></td>
                                </tr>

                                <tr>
                                    <td colspan="15" align="right" class="lightyellow totalamount">Total Invoice Value <span>(In Figure)</span><div class="totalprice"><i class="fa fa-inr"></i>10000</div></td>
                                    <td class="lightyellow" align="left"></td>
                                </tr>

                                <tr>
                                    <td colspan="15" align="right" class="lightpink fontbold" style="font-size:13px;">Total Invoice Value <span>(In Words) Ten Thousands Only</span></td> 
                                    <td class="lightpink" align="left"></td>
                                </tr>

                                <tr>
                                    <td colspan="9" align="right" class="lightgreen fontbold textsmall">Amount of Tax Subject to Reverse Charge</td>  
                                    <td class="lightgreen fontbold textsmall" align="center">18%</td>
                                    <td class="lightgreen fontbold textsmall" align="left"><i class="fa fa-inr"></i>1000</td>
                                    <td class="lightgreen fontbold textsmall" align="center">18%</td>
                                    <td class="lightgreen fontbold textsmall" align="left"><i class="fa fa-inr"></i>1000</td>
                                    <td class="lightgreen fontbold textsmall" align="center">18%</td>
                                    <td class="lightgreen fontbold textsmall" align="left"><i class="fa fa-inr"></i>1000</td>
                                    <td class="lightgreen fontbold textsmall" align="left"></td>
                                </tr>
                            </table>
                        </div>

                        <div class="clear height40"></div>
                        <div class="adminformbxsubmit invoicebtn" style="width:100%;"> 
                            <div class="tc">
                                <a href="javascript:void(0)" class="btn txtorange orangeborder popupbtn">Add item</a> 
                                <a href="#" class="btn txtorange orangeborder">Add New Invoice</a> 
                                <a href="#" class="btn txtorange orangeborder">Search Invoice</a>
                                <input type='submit' name="save_invoice" id="save_invoice" class="btn txtorange orangeborder" value="Save">
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
        
        $(".invoicetable").on("change", ".autocompleteinput", function(){
            
            $( "#invoice_tr_2_itemname" ).autocomplete({
                minLength: 1,
                source: "<?php echo PROJECT_URL; ?>/?ajax=client_get_item",
                select: function( event, ui ) {

                    console.log(ui.item);
                }
            });
        });

        $(".popupbtn").click(function() {
            $("#popup").css({"display":"block"});
            $("#fade").css({"display":"block"});
        });
        
        $('#btnclose').click(function(){
            $("#popup").hide();
            $("#fade").hide();
	});
        
        $("#btn").click(function() {
            $("#show").slideToggle(500);
            $(this).addClass("active");
        });
        
        /* add more invoice row script code */
        $(".invoicetable .addMoreInvoice").click(function() {
            
            var trlength = $(".invoice_tr").length;
            var totaltr = trlength + 1;
            
            var newtr = '<tr class="invoice_tr" data-row-id="'+totaltr+'" id="invoice_tr_'+totaltr+'">';
                newtr += '<td><span id="invoice_tr_'+totaltr+'_serialno" style="width:20px;">'+totaltr+'</span></td>';
                newtr += '<td><input type="text" id="invoice_tr_'+totaltr+'_itemname" class="inptxt required" placeholder="Enter Item" style="width:120px;" /></td>';
                newtr += '<td><input type="text" id="invoice_tr_'+totaltr+'_hsncode" readonly="true" class="inptxt readonly required" placeholder="HSN Code" style="width:50px;" /></td>';
                newtr += '<td><input type="text" id="invoice_tr_'+totaltr+'_quantity" class="required inptxt" placeholder="0" style="width:40px;" /></td>';
                newtr += '<td><input type="text" id="invoice_tr_'+totaltr+'_unit" class="required inptxt" placeholder="0" style="width:40px;" /></td>';
                newtr += '<td><div class="inptxt padrgt0" style="width:70px;"><i class="fa fa-inr"></i><input type="text" id="invoice_tr_'+totaltr+'_rate" class="required pricinput" placeholder="0.00" /></div></td>';
                newtr += '<td><div style="width:70px;" class="inptxt padrgt0"><i class="fa fa-inr"></i><input type="text" id="invoice_tr_'+totaltr+'_total" class="required readonly pricinput" placeholder="0.00" /></div></td>';
                newtr += '<td><input type="text" class="inptxt" id="invoice_tr_'+totaltr+'_discount" placeholder="0" style="width:90%;" /></td>';
                newtr += '<td><div style="width:70px;" class="inptxt padrgt0"><i class="fa fa-inr"></i><input type="text" id="invoice_tr_'+totaltr+'_taxablevalue" class="required readonly pricinput" placeholder="0.00" /></div></td>';
                newtr += '<td><input type="text" id="invoice_tr_'+totaltr+'_cgstrate" class="required readonly inptxt" placeholder="6" style="width:40px;" /></td>';
                newtr += '<td><div style="width:70px;" class="inptxt padrgt0"><i class="fa fa-inr"></i><input type="text" id="invoice_tr_'+totaltr+'_cgstamount" class="required readonly pricinput" placeholder="0.00" /></div></td>';
                newtr += '<td><input type="text" id="invoice_tr_'+totaltr+'_sgstrate" class="required readonly inptxt" placeholder="12" style="width:90%;" /></td>';
                newtr += '<td><div style="width:70px;" class="inptxt padrgt0"><i class="fa fa-inr"></i><input type="text" id="invoice_tr_'+totaltr+'_sgstamount" class="required readonly pricinput" placeholder="0.00" /></div></td>';
                newtr += '<td><input type="text" id="invoice_tr_'+totaltr+'_igstrate" class="required readonly inptxt" placeholder="18" style="width:90%;" /></td>';
                newtr += '<td><div style="width:70px;" class="inptxt padrgt0"><i class="fa fa-inr"></i><input type="text" id="invoice_tr_'+totaltr+'_igstamount" class="required readonly pricinput" placeholder="0.00" /></div></td>';
                newtr += '<td nowrap="nowrap" class="icon"><a class="deleteInvoice" data-invoice-id="'+totaltr+'" href="javascript:void(0)"><div class="tooltip"><i class="fa fa-trash deleteicon"></i><span class="tooltiptext">Delete</span></div></a></td>';
                newtr += '</tr>';

                /* insert new row */
                $(".invoice_tr").last().after(newtr);
        });
        
        /* delete invoice row script code */
        $(".invoicetable").on("click", ".deleteInvoice", function() {

            var invoiceId = $(this).attr("data-invoice-id");
            $("#invoice_tr_"+invoiceId).remove();
        });
        
        /* invoice date */
        $("#invoice_date").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            minDate: '0'
        });
        
        /* date time picker */
        $('#date_time_of_supply').datetimepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            timeFormat: 'hh:mm:ss',
            minDate: '0'
        });
        
        /* Billing state code */
        $("#billing_state").change(function () {
           
            var statecode = $(this).find(':selected').attr("data-code");
            if(typeof(statecode) === "undefined") {
                $("#billing_state_code").val("");
            } else {
                $("#billing_state_code").val(statecode);
            }
        });
        
        /* shipping state code */
        $("#shipping_state").change(function () {
           
            var statecode = $(this).find(':selected').attr("data-code");
            if(typeof(statecode) === "undefined") {
                $("#shipping_state_code").val("");
            } else {
                $("#shipping_state_code").val(statecode);
            }
        });
        
        /* select2 js for billing state */
        $("#billing_state").select2();
        
        /* select2 js for shipping state */
        $("#shipping_state").select2();
        
        $("#item_category").change(function () {
           
            var hsncode = $(this).find(':selected').attr("data-hsncode");
            if(typeof(hsncode) === "undefined") {
                $("#item_hsn_code").text("HSN Code");
            } else {
                $("#item_hsn_code").text(hsncode);
            }
        });
        
        $('#save_invoice').click(function () {
            var mesg = {};
            if (vali.validate(mesg,'create-invoice')) {
                return true;
            }           
            return false;
        });
        
        $('#add-item-submit').click(function () {
            var mesg = {};
            if (vali.validate(mesg,'add-item-form')) {
                return true;
            }           
            return false;
        });
        
        /* add new item */
        $("#add-item-form").submit(function(event){
            
            event.preventDefault();

            $.ajax({
                data: {itemData:$("#add-item-form").serialize(),action:"addItem"},
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
    });
</script>