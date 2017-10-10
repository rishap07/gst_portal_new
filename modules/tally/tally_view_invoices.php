<?php
$obj_tally = new tally();

if(!$obj_tally->can_read('returnfile_list')) {

    $obj_tally->setError($obj_tally->getValMsg('can_read'));
	$obj_tally->redirect(PROJECT_URL."/?page=dashboard");
    exit();
}

$returnPeriod = $obj_tally->get_results("SELECT return_period FROM ".$obj_tally->getTableName('gstr1_return_summary')." where 1=1 AND added_by='".$obj_tally->sanitize($_SESSION['user_detail']['user_id'])."' group by return_period order by return_period DESC");
if(count($returnPeriod) <= 0) {
	$obj_tally->setError("There is no invoice exist.");
	$obj_tally->redirect(PROJECT_URL."/?page=dashboard");
    exit();
}

if(isset($_GET['return_month']) && !empty($_GET['return_month'])) {
	$return_month = $_GET['return_month'];
} else {
	$return_period_row = $obj_tally->get_row("SELECT return_period FROM ".$obj_tally->getTableName('gstr1_return_summary')." WHERE 1=1 AND added_by='".$obj_tally->sanitize($_SESSION['user_detail']['user_id'])."' group by return_period order by return_period DESC Limit 0,1");
	$return_month = $return_period_row->return_period;
}

if(isset($_GET['invoice_nature']) && !empty($_GET['invoice_nature'])) {
	$invoice_nature = $_GET['invoice_nature'];
} else {
	$invoice_nature = "b2b";
}
?>

<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">

		<div class="col-md-12 col-sm-12 col-xs-12 heading"><h1><?php echo strtoupper($invoice_nature); ?> Invoice Listing</h1></div>

		<div class="whitebg formboxcontainer">

			<?php $obj_tally->showErrorMessage(); ?>
			<?php $obj_tally->showSuccessMessge(); ?>
			<?php $obj_tally->unsetMessage(); ?>

			<div class="clear"></div>

			<div class="rgtdatetxt">
				<div class="pull-left">
					<input type="button" value="<?php echo 'Download GSTR1'; ?>" onclick="javascript:void(0);" class="btn btn-warning" />
				</div>

				<div class="pull-right">
					<form class="form-inline" name="return-month-nature-form" id="return-month-nature-form" method="get">
						<div class="form-group">
							<label for="invoice_nature">Type Of Invoice:</label>
							<select class="form-control" id="invoice_nature" name="invoice_nature">
								<option <?php if(isset($invoice_nature) && $invoice_nature == "b2b") { echo 'selected="selected"'; } ?> value="b2b">B2B</option>
								<option <?php if(isset($invoice_nature) && $invoice_nature == "b2cl") { echo 'selected="selected"'; } ?> value="b2cl">B2C Large</option>
								<option <?php if(isset($invoice_nature) && $invoice_nature == "b2cs") { echo 'selected="selected"'; } ?> value="b2cs">B2C Small</option>
								<option <?php if(isset($invoice_nature) && $invoice_nature == "cdnr") { echo 'selected="selected"'; } ?> value="cdnr">Credit Debit Notes Registered</option>
								<option <?php if(isset($invoice_nature) && $invoice_nature == "cdnur") { echo 'selected="selected"'; } ?> value="cdnur">Credit Debit Notes Unregistered</option>
								<option <?php if(isset($invoice_nature) && $invoice_nature == "exp") { echo 'selected="selected"'; } ?> value="exp">Export</option>
								<option <?php if(isset($invoice_nature) && $invoice_nature == "at") { echo 'selected="selected"'; } ?> value="at">Advance Tax</option>
								<option <?php if(isset($invoice_nature) && $invoice_nature == "atadj") { echo 'selected="selected"'; } ?> value="atadj">Advance Tax Adjustment</option>
							</select>
						</div>

						<div class="form-group">
							<label for="return_month">Month Of Return:</label>
							<select class="form-control" id="return_month" name="return_month">
								<?php foreach($returnPeriod as $monthYear) { ?>
									<option <?php if($return_month == $monthYear->return_period) { echo 'selected="selected"'; } ?> value="<?php echo $monthYear->return_period; ?>"><?php echo $monthYear->return_period; ?></option>
								<?php } ?>
							</select>
						</div>
					</form>
				</div>
				<div class="clear"></div>
			</div>

			<div class="adminformbx">

				<?php if($invoice_nature == "b2b") { ?>
				
					<table width="100%" border="0" cellspacing="0" cellpadding="0" class="invoice-itemtable" id="mainTable1">
						<thead>
							<tr>
								<th style="text-align:left;">GSTIN/UIN of Recipient</th>
								<th style="text-align:left;">Invoice Number</th>
								<th style="text-align:left;">Invoice Date</th>
								<th style="text-align:left;">Invoice Value</th>
								<th style="text-align:left;">Place Of Supply</th>
								<th style="text-align:left;">Reverse Charge</th>
								<th style="text-align:left;">Invoice Type</th>
								<th style="text-align:left;">E-Commerce GSTIN</th>
								<th style="text-align:left;">Rate(%)</th>
								<th style="text-align:left;">Taxable Value</th>
								<th style="text-align:left;">Cess Amount</th>
							</tr>
						</thead>
						<tbody>
							<?php $fetchB2BData = $obj_tally->get_results("SELECT *, (case when invoice_type='R' Then 'Regular' when invoice_type='DE' then 'Deemed Export' when invoice_type='SEWP' then 'SEZ Supplies With Payment'  when invoice_type='SEWOP' then 'SEZ Supplies Without Payment' end) as invoice_type, (case when reverse_charge='Y' Then 'Yes' when reverse_charge='N' then 'No' end) as reverse_charge FROM ".$obj_tally->getTableName('gstr1_return_summary')." WHERE 1=1 AND return_period = '".$return_month."' AND invoice_nature = '".$invoice_nature."' AND added_by='".$obj_tally->sanitize($_SESSION['user_detail']['user_id'])."'"); ?>
							<?php foreach($fetchB2BData as $b2bData) { ?>
							
								<?php $b2b_supply_state_data = $obj_tally->getStateDetailByStateTin($b2bData->place_of_supply); ?>

								<tr>
									<td style="text-align:left;"><?php echo $b2bData->recipient_gstin; ?></td>
									<td style="text-align:left;"><?php echo $b2bData->invoice_number; ?></td>
									<td style="text-align:left;"><?php echo date("d-M-y", strtotime($b2bData->invoice_date)); ?></td>
									<td style="text-align:left;"><?php echo $b2bData->invoice_value; ?></td>
									<td style="text-align:left;"><?php echo $b2b_supply_state_data['data']->state_tin ."-". $b2b_supply_state_data['data']->state_name; ?></td>
									<td style="text-align:left;"><?php echo $b2bData->reverse_charge; ?></td>
									<td style="text-align:left;"><?php echo $b2bData->invoice_type; ?></td>
									<td style="text-align:left;"><?php echo $b2bData->ecommerce_gstin_number; ?></td>
									<td style="text-align:left;"><?php echo $b2bData->rate; ?></td>
									<td style="text-align:left;"><?php echo $b2bData->taxable_value; ?></td>
									<td style="text-align:left;"><?php echo $b2bData->cess_amount; ?></td>
								</tr>

							<?php } ?>
							
						</tbody>
					</table>
				
				<?php } ?>
				
				<?php if($invoice_nature == "b2cl") { ?>
				
					<table width="100%" border="0" cellspacing="0" cellpadding="0" class="invoice-itemtable" id="mainTable1">
						<thead>
							<tr>
								<th style="text-align:left;">Invoice Number</th>
								<th style="text-align:left;">Invoice Date</th>
								<th style="text-align:left;">Invoice Value</th>
								<th style="text-align:left;">Place Of Supply</th>
								<th style="text-align:left;">Rate(%)</th>
								<th style="text-align:left;">Taxable Value</th>
								<th style="text-align:left;">Cess Amount</th>
								<th style="text-align:left;">E-Commerce GSTIN</th>
							</tr>
						</thead>
						<tbody>
							<?php $fetchB2CLData = $obj_tally->get_results("SELECT * FROM ".$obj_tally->getTableName('gstr1_return_summary')." WHERE 1=1 AND return_period = '".$return_month."' AND invoice_nature = '".$invoice_nature."' AND added_by='".$obj_tally->sanitize($_SESSION['user_detail']['user_id'])."'"); ?>
							<?php foreach($fetchB2CLData as $b2clData) { ?>

								<?php $b2cl_supply_state_data = $obj_tally->getStateDetailByStateTin($b2clData->place_of_supply); ?>

								<tr>
									<td style="text-align:left;"><?php echo $b2clData->invoice_number; ?></td>
									<td style="text-align:left;"><?php echo date("d-M-y", strtotime($b2clData->invoice_date)); ?></td>
									<td style="text-align:left;"><?php echo $b2clData->invoice_value; ?></td>
									<td style="text-align:left;"><?php echo $b2cl_supply_state_data['data']->state_tin ."-". $b2cl_supply_state_data['data']->state_name; ?></td>
									<td style="text-align:left;"><?php echo $b2clData->rate; ?></td>
									<td style="text-align:left;"><?php echo $b2clData->taxable_value; ?></td>
									<td style="text-align:left;"><?php echo $b2clData->cess_amount; ?></td>
									<td style="text-align:left;"><?php echo $b2clData->ecommerce_gstin_number; ?></td>
								</tr>

							<?php } ?>
							
						</tbody>
					</table>
				
				<?php } ?>
				
				<?php if($invoice_nature == "b2cs") { ?>
				
					<table width="100%" border="0" cellspacing="0" cellpadding="0" class="invoice-itemtable" id="mainTable1">
						<thead>
							<tr>
								<th style="text-align:left;">Type</th>
								<th style="text-align:left;">Place Of Supply</th>
								<th style="text-align:left;">Rate(%)</th>
								<th style="text-align:left;">Taxable Value</th>
								<th style="text-align:left;">Cess Amount</th>
								<th style="text-align:left;">E-Commerce GSTIN</th>
							</tr>
						</thead>
						<tbody>
							<?php $fetchB2CSData = $obj_tally->get_results("SELECT *, (case when type='E' Then 'Ecommerce' when type='OE' then 'Other Than Ecommerce' end) as type FROM ".$obj_tally->getTableName('gstr1_return_summary')." WHERE 1=1 AND return_period = '".$return_month."' AND invoice_nature = '".$invoice_nature."' AND added_by='".$obj_tally->sanitize($_SESSION['user_detail']['user_id'])."'"); ?>
							<?php foreach($fetchB2CSData as $b2csData) { ?>
							
								<?php $b2cs_supply_state_data = $obj_tally->getStateDetailByStateTin($b2csData->place_of_supply); ?>

								<tr>
									<td style="text-align:left;"><?php echo $b2csData->type; ?></td>
									<td style="text-align:left;"><?php echo $b2cs_supply_state_data['data']->state_tin ."-". $b2cs_supply_state_data['data']->state_name; ?></td>
									<td style="text-align:left;"><?php echo $b2csData->rate; ?></td>
									<td style="text-align:left;"><?php echo $b2csData->taxable_value; ?></td>
									<td style="text-align:left;"><?php echo $b2csData->cess_amount; ?></td>
									<td style="text-align:left;"><?php echo $b2csData->ecommerce_gstin_number; ?></td>
								</tr>

							<?php } ?>

						</tbody>
					</table>

				<?php } ?>

				<?php if($invoice_nature == "cdnr") { ?>
					<div class="table-responsive">
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="invoice-itemtable" id="mainTable1">
							<thead>
								<tr>
									<th style="text-align:left;">GSTIN/UIN of Recipient</th>
									<th style="text-align:left;">Invoice/Advance Receipt Number</th>
									<th style="text-align:left;">Invoice/Advance Receipt Date</th>
									<th style="text-align:left;">Note/Refund Voucher Number</th>
									<th style="text-align:left;">Note/Refund Voucher Date</th>
									<th style="text-align:left;">Document Type</th>
									<th style="text-align:left;">Reason For Issuing Document</th>
									<th style="text-align:left;">Place Of Supply</th>
									<th style="text-align:left;">Note/Refund Voucher Value</th>
									<th style="text-align:left;">Rate(%)</th>
									<th style="text-align:left;">Taxable Value</th>
									<th style="text-align:left;">Cess Amount</th>
									<th style="text-align:left;">Pre GST</th>
								</tr>
							</thead>
							<tbody>
								<?php $fetchCDNRData = $obj_tally->get_results("SELECT *, (case when document_type='C' Then 'Credit Note' when document_type='D' then 'Debit Note' when document_type='R' then 'Refund Voucher' end) as document_type, (case when pre_gst='Y' Then 'Yes' when pre_gst='N' then 'No' end) as pre_gst FROM ".$obj_tally->getTableName('gstr1_return_summary')." WHERE 1=1 AND return_period = '".$return_month."' AND invoice_nature = '".$invoice_nature."' AND added_by='".$obj_tally->sanitize($_SESSION['user_detail']['user_id'])."'"); ?>
								<?php foreach($fetchCDNRData as $cdnrData) { ?>
								
									<?php $cdnr_supply_state_data = $obj_tally->getStateDetailByStateTin($cdnrData->place_of_supply); ?>

									<tr>
										<td style="text-align:left;"><?php echo $cdnrData->recipient_gstin; ?></td>
										<td style="text-align:left;"><?php echo $cdnrData->original_invoice_number; ?></td>
										<td style="text-align:left;"><?php echo $cdnrData->original_invoice_date; ?></td>
										<td style="text-align:left;"><?php echo $cdnrData->invoice_number; ?></td>
										<td style="text-align:left;"><?php echo date("d-M-y", strtotime($cdnrData->invoice_date)); ?></td>
										<td style="text-align:left;"><?php echo $cdnrData->document_type; ?></td>
										<td style="text-align:left;"><?php echo $cdnrData->reason_for_issuing_document; ?></td>
										<td style="text-align:left;"><?php echo $cdnr_supply_state_data['data']->state_tin ."-". $cdnr_supply_state_data['data']->state_name; ?></td>
										<td style="text-align:left;"><?php echo $cdnrData->invoice_value; ?></td>
										<td style="text-align:left;"><?php echo $cdnrData->rate; ?></td>
										<td style="text-align:left;"><?php echo $cdnrData->taxable_value; ?></td>
										<td style="text-align:left;"><?php echo $cdnrData->cess_amount; ?></td>
										<td style="text-align:left;"><?php echo $cdnrData->pre_gst; ?></td>
									</tr>

								<?php } ?>
								
							</tbody>
						</table>
					</div>
				<?php } ?>

				<?php if($invoice_nature == "cdnur") { ?>
					<div class="table-responsive">
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="invoice-itemtable" id="mainTable1">
							<thead>
								<tr>
									<th style="text-align:left;">GSTIN/UIN of Recipient</th>
									<th style="text-align:left;">Note/Refund Voucher Number</th>
									<th style="text-align:left;">Note/Refund Voucher Date</th>
									<th style="text-align:left;">Document Type</th>
									<th style="text-align:left;">Invoice/Advance Receipt Number</th>
									<th style="text-align:left;">Invoice/Advance Receipt Date</th>
									<th style="text-align:left;">Reason For Issuing Document</th>
									<th style="text-align:left;">Place Of Supply</th>
									<th style="text-align:left;">Note/Refund Voucher Value</th>
									<th style="text-align:left;">Rate(%)</th>
									<th style="text-align:left;">Taxable Value</th>
									<th style="text-align:left;">Cess Amount</th>
									<th style="text-align:left;">Pre GST</th>
								</tr>
							</thead>
							<tbody>
								<?php $fetchCDNURData = $obj_tally->get_results("SELECT *, (case when ur_type='B2CL' Then 'B2CL' when ur_type='EXPWP' then 'Export With Payment' when ur_type='EXPWOP' Then 'Export Without Payment' end) as ur_type, (case when document_type='C' Then 'Credit Note' when document_type='D' then 'Debit Note' when document_type='R' then 'Refund Voucher' end) as document_type, (case when pre_gst='Y' Then 'Yes' when pre_gst='N' then 'No' end) as pre_gst FROM ".$obj_tally->getTableName('gstr1_return_summary')." WHERE 1=1 AND return_period = '".$return_month."' AND invoice_nature = '".$invoice_nature."' AND added_by='".$obj_tally->sanitize($_SESSION['user_detail']['user_id'])."'"); ?>
								<?php foreach($fetchCDNURData as $cdnurData) { ?>
								
									<?php $cdnur_supply_state_data = $obj_tally->getStateDetailByStateTin($cdnurData->place_of_supply); ?>

									<tr>
										<td style="text-align:left;"><?php echo $cdnurData->ur_type; ?></td>
										<td style="text-align:left;"><?php echo $cdnurData->invoice_number; ?></td>
										<td style="text-align:left;"><?php echo date("d-M-y", strtotime($cdnurData->invoice_date)); ?></td>
										<td style="text-align:left;"><?php echo $cdnurData->document_type; ?></td>
										<td style="text-align:left;"><?php echo $cdnurData->original_invoice_number; ?></td>
										<td style="text-align:left;"><?php echo $cdnurData->original_invoice_date; ?></td>
										<td style="text-align:left;"><?php echo $cdnurData->reason_for_issuing_document; ?></td>
										<td style="text-align:left;"><?php echo $cdnur_supply_state_data['data']->state_tin ."-". $cdnur_supply_state_data['data']->state_name; ?></td>
										<td style="text-align:left;"><?php echo $cdnurData->invoice_value; ?></td>
										<td style="text-align:left;"><?php echo $cdnurData->rate; ?></td>
										<td style="text-align:left;"><?php echo $cdnurData->taxable_value; ?></td>
										<td style="text-align:left;"><?php echo $cdnurData->cess_amount; ?></td>
										<td style="text-align:left;"><?php echo $cdnurData->pre_gst; ?></td>
									</tr>

								<?php } ?>
								
							</tbody>
						</table>
					</div>
				<?php } ?>

				<?php if($invoice_nature == "exp") { ?>
				
					<table width="100%" border="0" cellspacing="0" cellpadding="0" class="invoice-itemtable" id="mainTable1">
						<thead>
							<tr>
								<th style="text-align:left;">Export Type</th>
								<th style="text-align:left;">Invoice Number</th>
								<th style="text-align:left;">Invoice Date</th>
								<th style="text-align:left;">Invoice Value</th>
								<th style="text-align:left;">Port Code</th>
								<th style="text-align:left;">Shipping Bill Number</th>
								<th style="text-align:left;">Shipping Bill Date</th>
								<th style="text-align:left;">Rate(%)</th>
								<th style="text-align:left;">Taxable Value</th>
							</tr>
						</thead>
						<tbody>
							<?php $fetchEXPData = $obj_tally->get_results("SELECT *, (case when invoice_type='WOPAY' Then 'Without Payment' when invoice_type='WPAY' then 'With Payment' end) as invoice_type FROM ".$obj_tally->getTableName('gstr1_return_summary')." WHERE 1=1 AND return_period = '".$return_month."' AND invoice_nature = '".$invoice_nature."' AND added_by='".$obj_tally->sanitize($_SESSION['user_detail']['user_id'])."'"); ?>
							<?php foreach($fetchEXPData as $expData) { ?>

								<tr>
									<td style="text-align:left;"><?php echo $expData->invoice_type; ?></td>
									<td style="text-align:left;"><?php echo $expData->invoice_number; ?></td>
									<td style="text-align:left;"><?php echo date("d-M-y", strtotime($expData->invoice_date)); ?></td>
									<td style="text-align:left;"><?php echo $expData->invoice_value; ?></td>
									<td style="text-align:left;"><?php echo $expData->port_code; ?></td>
									<td style="text-align:left;"><?php echo $expData->shipping_bill_number; ?></td>
									<td style="text-align:left;"><?php echo $expData->shipping_bill_date; ?></td>
									<td style="text-align:left;"><?php echo $expData->rate; ?></td>
									<td style="text-align:left;"><?php echo $expData->taxable_value; ?></td>
								</tr>

							<?php } ?>

						</tbody>
					</table>
				
				<?php } ?>

				<?php if($invoice_nature == "at") { ?>
				
					<table width="100%" border="0" cellspacing="0" cellpadding="0" class="invoice-itemtable" id="mainTable1">
						<thead>
							<tr>
								<th style="text-align:left;">Place Of Supply</th>
								<th style="text-align:left;">Rate(%)</th>
								<th style="text-align:left;">Gross Advance Received</th>
								<th style="text-align:left;">Cess Amount</th>
							</tr>
						</thead>
						<tbody>
							<?php $fetchATData = $obj_tally->get_results("SELECT * FROM ".$obj_tally->getTableName('gstr1_return_summary')." WHERE 1=1 AND return_period = '".$return_month."' AND invoice_nature = '".$invoice_nature."' AND added_by='".$obj_tally->sanitize($_SESSION['user_detail']['user_id'])."'"); ?>
							<?php foreach($fetchATData as $atData) { ?>
							
								<?php $at_supply_state_data = $obj_tally->getStateDetailByStateTin($atData->place_of_supply); ?>

								<tr>
									<td style="text-align:left;"><?php echo $at_supply_state_data['data']->state_tin ."-". $at_supply_state_data['data']->state_name; ?></td>
									<td style="text-align:left;"><?php echo $atData->rate; ?></td>
									<td style="text-align:left;"><?php echo $atData->taxable_value; ?></td>
									<td style="text-align:left;"><?php echo $atData->cess_amount; ?></td>
								</tr>

							<?php } ?>
							
						</tbody>
					</table>

				<?php } ?>

				<?php if($invoice_nature == "atadj") { ?>

					<table width="100%" border="0" cellspacing="0" cellpadding="0" class="invoice-itemtable" id="mainTable1">
						<thead>
							<tr>
								<th style="text-align:left;">Place Of Supply</th>
								<th style="text-align:left;">Rate(%)</th>
								<th style="text-align:left;">Gross Advance Adjusted</th>
								<th style="text-align:left;">Cess Amount</th>
							</tr>
						</thead>
						<tbody>
							<?php $fetchATADJData = $obj_tally->get_results("SELECT * FROM ".$obj_tally->getTableName('gstr1_return_summary')." WHERE 1=1 AND return_period = '".$return_month."' AND invoice_nature = '".$invoice_nature."' AND added_by='".$obj_tally->sanitize($_SESSION['user_detail']['user_id'])."'"); ?>
							<?php foreach($fetchATADJData as $atadjData) { ?>
							
								<?php $atadj_supply_state_data = $obj_tally->getStateDetailByStateTin($atadjData->place_of_supply); ?>

								<tr>
									<td style="text-align:left;"><?php echo $atadj_supply_state_data['data']->state_tin ."-". $atadj_supply_state_data['data']->state_name; ?></td>
									<td style="text-align:left;"><?php echo $atadjData->rate; ?></td>
									<td style="text-align:left;"><?php echo $atadjData->taxable_value; ?></td>
									<td style="text-align:left;"><?php echo $atadjData->cess_amount; ?></td>
								</tr>

							<?php } ?>
							
						</tbody>
					</table>

				<?php } ?>

			</div>

		</div>
	</div>
</div>
<!--========================sidemenu over=========================-->
<script>
    $(document).ready(function () {
        $('#submit').click(function () {
            var mesg = {};
            if (vali.validate(mesg,'return-month-nature-form')) {
				return true;
            }
            return false;
        });

		$('#return_month').change(function () {
			window.location.href = "<?php echo PROJECT_URL; ?>/?page=tally_view_invoices&return_month=" + $(this).val() + "&invoice_nature=" + $("#invoice_nature option:selected").val();
		});
		
		$('#invoice_nature').change(function () {
			window.location.href = "<?php echo PROJECT_URL; ?>/?page=tally_view_invoices&return_month=" + $("#return_month option:selected").val() + "&invoice_nature=" + $(this).val();
		});
    });
</script>