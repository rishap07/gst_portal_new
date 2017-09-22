<?php
if(!$db_obj->can_read('client_invoice')) {

    $db_obj->setError($db_obj->getValMsg('can_read'));
    $db_obj->redirect(PROJECT_URL."/?page=dashboard");
    exit();
}
?>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">

		<div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>Search Sales Invoice</h1></div>

		<div class="whitebg formboxcontainer">

			<?php $db_obj->showErrorMessage(); ?>
			<?php $db_obj->showSuccessMessge(); ?>
			<?php $db_obj->unsetMessage(); ?>
			<div class="clear"></div>

			<form name="search-sales-invoice" id="search-sales-invoice" action="<?php echo PROJECT_URL; ?>/?page=search_sales_invoice" method="POST">
				<div class="row">
					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>From Date</label>
						<input type="text" placeholder="YYYY-MM-DD" class="form-control" data-bind="date" name="from_date" id="from_date" value="<?php if(isset($_POST['from_date'])) { echo $_POST['from_date']; } ?>">
					</div>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>To Date</label>
						<input type="text" placeholder="YYYY-MM-DD" class="form-control" data-bind="date" name="to_date" id="to_date" value="<?php if(isset($_POST['to_date'])) { echo $_POST['to_date']; } ?>">
					</div>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Invoice Type</label>
						<select name="invoice_type" id="invoice_type" class="form-control">
							<option value="">Select Invoice Type</option>
							<option value="taxinvoice" <?php if(isset($_POST['invoice_type']) && $_POST['invoice_type'] == "taxinvoice") { echo 'selected="selected"'; } ?>>Tax Invoice</option>
							<option value="exportinvoice" <?php if(isset($_POST['invoice_type']) && $_POST['invoice_type'] == "exportinvoice") { echo 'selected="selected"'; } ?>>Export Invoice</option>
							<option value="sezunitinvoice" <?php if(isset($_POST['invoice_type']) && $_POST['invoice_type'] == "sezunitinvoice") { echo 'selected="selected"'; } ?>>SEZ Unit Invoice</option>
							<option value="deemedexportinvoice" <?php if(isset($_POST['invoice_type']) && $_POST['invoice_type'] == "deemedexportinvoice") { echo 'selected="selected"'; } ?>>Deemed Export Invoice</option>
							<option value="billofsupplyinvoice" <?php if(isset($_POST['invoice_type']) && $_POST['invoice_type'] == "billofsupplyinvoice") { echo 'selected="selected"'; } ?>>Bill Of Supply Invoice</option>
							<option value="receiptvoucherinvoice"<?php if(isset($_POST['invoice_type']) && $_POST['invoice_type'] == "receiptvoucherinvoice") { echo 'selected="selected"'; } ?>>Receipt Voucher Invoice</option>
							<option value="refundvoucherinvoice" <?php if(isset($_POST['invoice_type']) && $_POST['invoice_type'] == "refundvoucherinvoice") { echo 'selected="selected"'; } ?>>Refund Voucher Invoice</option>
							<option value="revisedtaxinvoice" <?php if(isset($_POST['invoice_type']) && $_POST['invoice_type'] == "revisedtaxinvoice") { echo 'selected="selected"'; } ?>>Revised Tax Invoice</option>
							<option value="creditnote" <?php if(isset($_POST['invoice_type']) && $_POST['invoice_type'] == "creditnote") { echo 'selected="selected"'; } ?>>Credit Note</option>
							<option value="debitnote" <?php if(isset($_POST['invoice_type']) && $_POST['invoice_type'] == "debitnote") { echo 'selected="selected"'; } ?>>Debit Note</option>
							<option value="deliverychallaninvoice" <?php if(isset($_POST['invoice_type']) && $_POST['invoice_type'] == "deliverychallaninvoice") { echo 'selected="selected"'; } ?>>Delivery Challan</option>
						</select>
					</div>
					<div class="clear"></div>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Supply Type</label>
						<select name="supply_type" id="supply_type" class="form-control">
							<option value="">Select Supply Type</option>
							<option value="normal" <?php if(isset($_POST['supply_type']) && $_POST['supply_type'] == "normal") { echo 'selected="selected"'; } ?>>Normal</option>
							<option value="reversecharge" <?php if(isset($_POST['supply_type']) && $_POST['supply_type'] == "reversecharge") { echo 'selected="selected"'; } ?>>Reverse Charge</option>
							<option value="tds" <?php if(isset($_POST['supply_type']) && $_POST['supply_type'] == "tds") { echo 'selected="selected"'; } ?>>TDS</option>
							<option value="tcs" <?php if(isset($_POST['supply_type']) && $_POST['supply_type'] == "tcs") { echo 'selected="selected"'; } ?>>TCS</option>
						</select>
					</div>
					
					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Reference Number</label>
						<input type="text" placeholder="Reference Number" class="form-control" data-bind="content" name="reference_number" id="reference_number" value="<?php if(isset($_POST['reference_number'])) { echo $_POST['reference_number']; } ?>">
					</div>
					
					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Place Of Supply</label>
						<select name='place_of_supply' id='place_of_supply' class="form-control">
							<?php $dataSupplyStateArrs = $db_obj->get_results("select * from ".$db_obj->getTableName('state')." where status='1' and is_deleted='0' order by state_name asc"); ?>
							<?php if(!empty($dataSupplyStateArrs)) { ?>
								<option value=''>Select Place Of Supply</option>
								<?php foreach($dataSupplyStateArrs as $dataSupplyStateArr) { ?>

									<?php if(isset($_POST['place_of_supply']) && $_POST['place_of_supply'] == $dataSupplyStateArr->state_id) { ?>
										<option value='<?php echo $dataSupplyStateArr->state_id; ?>' selected="selected"><?php echo $dataSupplyStateArr->state_name . " (" . $dataSupplyStateArr->state_tin . ")"; ?></option>
									<?php } else { ?>
										<option value='<?php echo $dataSupplyStateArr->state_id; ?>'><?php echo $dataSupplyStateArr->state_name . " (" . $dataSupplyStateArr->state_tin . ")"; ?></option>
									<?php } ?>

								<?php } ?>
							<?php } ?>
						</select>
					</div>
					
					<div class="clear"></div>
					
					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Billing State</label>
						<select name='billing_state' id='billing_state' class='form-control'>
							<?php $dataBStateArrs = $db_obj->get_results("select * from ".$db_obj->getTableName('state')." where status='1' and is_deleted='0' order by state_name asc"); ?>
							<?php if(!empty($dataBStateArrs)) { ?>
								<option value=''>Select State</option>
								<?php foreach($dataBStateArrs as $dataBStateArr) { ?>
								
									<?php if(isset($_POST['billing_state']) && $_POST['billing_state'] == $dataBStateArr->state_id) { ?>
										<option value='<?php echo $dataBStateArr->state_id; ?>'><?php echo $dataBStateArr->state_name . " (" . $dataBStateArr->state_tin . ")"; ?></option>
									<?php } else { ?>
										<option value='<?php echo $dataBStateArr->state_id; ?>'><?php echo $dataBStateArr->state_name . " (" . $dataBStateArr->state_tin . ")"; ?></option>
									<?php } ?>

								<?php } ?>
							<?php } ?>
						</select>
					</div>
					
					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Billing GSTIN/UIN</label>
						<input type="text" placeholder="GSTIN/UIN" class="form-control" data-bind="content" name="billing_gstin_number" id="billing_gstin_number" value="<?php if(isset($_POST['billing_gstin_number'])) { echo $_POST['billing_gstin_number']; } ?>">
					</div>

					<div class="clear"></div>

					<div class="adminformbxsubmit" style="width:100%;">
						<div class="tc">
							<input type='submit' class="btn btn-success" name='submit' value='Search Invoice' id='submit'>
						</div>
					</div>

				</div>
			</form>
			
			<div class="adminformbx">
				<div class="tableresponsive">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainTable" class="invoice-itemtable searchInvoiceTable">
						<thead>
							<tr>
								<th align='center' width="50">#</th>
								<th align='center'>Invoice Type</th>
								<th align='center'>Reference Number</th>
								<th align='center'>Invoice Date</th>
								<th align='center'>Supply Place</th>
								<th align='center'>Billing Name</th>
								<th align='center'>Billing GSTIN</th>
								<th align='center'>Billing State</th>
								<th align='center'>Invoice Value(<i class="fa fa-inr" aria-hidden="true"></i>)</th>
								<th width="120">Action</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>

		</div>
	</div>
</div>
<!--========================sidemenu over=========================-->
<script>
    $(document).ready(function () {

		/* from date */
        $("#from_date").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
			maxDate: '0'
        });

		/* to date */
        $("#to_date").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
			maxDate: '0'
        });
		
		/* select2 js for invoice type */
        $("#invoice_type").select2();
		
		/* select2 js for supply type */
        $("#supply_type").select2();

		/* select2 js for place of supply OR receiver state */
        $("#place_of_supply").select2();
		
		/* select2 js for billing state */
        $("#billing_state").select2();

        $('#submit').click(function () {
            var mesg = {};
            if (vali.validate(mesg,'search-sales-invoice')) {
				return true;
            }
            return false;
        });

		/* submit search form */
        $("#search-sales-invoice").submit(function(event){
			event.preventDefault();
			TableManaged.init($("#search-sales-invoice").serialize());
        });
		/* end of submit search form */
    });
	
	var TableManaged = function () {
        return {
            init: function (salesSearchData) {
                if (!jQuery().dataTable) {
                    return;
                }
                var sgHREF = window.location.pathname;
                $.ajaxSetup({'type': 'POST', 'data' : {'salesSearchData':salesSearchData}, 'url': sgHREF, 'dataType': 'json'});
                $.extend($.fn.dataTable.defaults, {'sServerMethod': 'POST'});
                $('#mainTable').dataTable({
                    "aoColumns": [
                        {"bSortable": false},
                        {"bSortable": false},
                        {"bSortable": false},
                        {"bSortable": false},
						{"bSortable": false},
                        {"bSortable": false},
						{"bSortable": false},
                        {"bSortable": false},
						{"bSortable": false},
                        {"bSortable": false}
                    ],
                    "sDom": "lfrtip",
                    "aLengthMenu": [
                        [10, 20, 50, 100, 500],
                        [10, 20, 50, 100, 500],
                    ],
                    "bProcessing": true,
                    "bServerSide": true,
                    "bStateSave": false,
                    "bDestroy": true,
                    "searching": true,
                    "bLengthChange": true,
                    "sAjaxSource": "<?php echo PROJECT_URL; ?>/?ajax=search_sales_invoice",
                    "fnServerParams": function (aoData) {
                    },
                    "iDisplayLength": 20
                });
            }
        };
    }();
</script>