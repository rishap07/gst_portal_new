<?php
$obj_gstr2 = new gstr2();

if(!$obj_gstr2->can_read('returnfile_list')) {
    $obj_gstr2->setError($obj_gstr2->getValMsg('can_read'));
    $obj_gstr2->redirect(PROJECT_URL."/?page=dashboard");
    exit();
}

if(isset($_GET['returnmonth']) && !empty($_GET['returnmonth'])) {
	$returnmonth = $_GET['returnmonth'];
} else {
	$obj_gstr2->setError("Please choose return period.");
    $obj_gstr2->redirect(PROJECT_URL."/?page=return_client");
    exit();
}

if (isset($_POST['submit']) && $_POST['submit'] == 'submit') {
	$obj_gstr2->saveGstr2HsnSummary();
}

$hsn = '';
$description = '';
$unit = '';
$qty = '';
$taxable_subtotal = '';
$invoice_total_value = '';
$igst = '';
$cgst = '';
$sgst = '';
$cess = '';

$autoflag = 0;
if(isset($_POST['autopopulate']) && $_POST['autopopulate'] == "Autopopulate") {
	$returnHSNData = $db_obj->getGSTR2HSNInvoices($_SESSION["user_detail"]["user_id"], $returnmonth);
	$autoflag = 1;
} else {
	$returnHSNData = $obj_gstr2->get_results("select * from ". $obj_gstr2->getTableName('return_upload_summary') ." where added_by='" . $_SESSION['user_detail']['user_id'] . "' and financial_month like '" . $returnmonth . "' and is_deleted='0' and type='gstr2hsn' order by id desc limit 0,1");
}
?>

<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="col-md-6 col-sm-6 col-xs-12 heading">
			<h1>GSTR2 HSN-Wise Summary</h1>
		</div>
		<div class="col-md-6 col-sm-6 col-xs-12 text-right breadcrumb-nav"> <a href="#">Home</a> <i class="fa fa-angle-right" aria-hidden="true"></i> <a href="#">File Return</a> <i class="fa fa-angle-right" aria-hidden="true"></i> <span class="active">GSTR-2 Filing</span> </div>
		
		<div class="whitebg formboxcontainer">
		
			<div class="pull-left">
				<form method='post' name="generateGSTR2HSNSummaryForm" id="generateGSTR2HSNSummaryForm">
					<input type="submit" name="autopopulate" id="autopopulate" class="btn btn-success" value="Autopopulate">
				</form>
			</div>
			<div class="pull-right">
				<form method='post' name='gstr2NilMonthForm' id="gstr2NilMonthForm">
					Month Of Return
					<select class="monthselectbox" id="returnmonth" name="returnmonth">
						<?php for($year = 2017; $year <= date('Y'); $year++) { ?>
							<?php for($month = 1; $month <= 12; $month++) { ?>
								<?php if($year >= 2017 && $month >= 6) { ?>
									<option <?php if($returnmonth == date( "Y-m", strtotime($year."-".$month) )) { echo 'selected="selected"'; } ?> value="<?php echo date( "Y-m", strtotime($year."-".$month) ); ?>"><?php echo date( "F Y", strtotime($year."-".$month) ); ?></option>
								<?php } ?>
							<?php } ?>
						<?php } ?>
					</select>
				</form>
			</div>

			<div class="clear"></div>
			<hr>
			<div class="clear"></div>

			<?php $obj_gstr2->showErrorMessage(); ?>
			<?php $obj_gstr2->showSuccessMessge(); ?>
			<?php $obj_gstr2->unsetMessage(); ?>

			<div class="greyheading">1.HSN-Wise Summary of Outward Supplies</div>
			<form method="post" enctype="multipart/form-data" name="hsnsummaryform" id='hsnsummaryform'>

				<div class="tableresponsive">
					<table class="itctable invoice-itemtable table bordernone" id='hsn-table'>
						<thead>
							<tr>
								<th>HSN</th>
								<th>Description</th>
								<th>Unit</th>
								<th>Total Qty</th>
								<th>Taxable Value (<i class="fa fa-inr"></i>)</th>
								<th>TotalValue (<i class="fa fa-inr"></i>)</th>
								<th>IGST Amount (<i class="fa fa-inr"></i>)</th>
								<th>CGST Amount (<i class="fa fa-inr"></i>)</th>
								<th>SGST Amount (<i class="fa fa-inr"></i>)</th>
								<th>CESS Amount (<i class="fa fa-inr"></i>)</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
						<?php
							if (!empty($returnHSNData)) {

								if($autoflag!=1) {

									$HSNDataArray = base64_decode($returnHSNData[0]->return_data);
									$summary_array = json_decode($HSNDataArray);

									foreach($summary_array as $data) { ?>

										<tr>
											<td>
												<input type='text' class='required form-control' data-bind="number" name='hsn[]' value="<?php echo (isset($data->hsn)) ? $data->hsn : '' ?>" />
											</td>
											<td>
												<input type='text' class='required form-control' data-bind="content" name='description[]' value="<?php echo (isset($data->description)) ? $data->description : '' ?>" />
											</td>
											<td>
												<select name="unit[]" class="required form-control">
													<?php $masterUnitArrs = $obj_gstr2->getMasterUnits("unit_id,unit_name,unit_code,(case when status='1' Then 'active' when status='0' then 'deactive' end) as status", "is_deleted='0' AND status='1'"); ?>
													<?php if(!empty($masterUnitArrs)) { ?>
														<option value=''>Select Unit</option>
														<?php foreach($masterUnitArrs as $masterUnitArr) { ?>

															<?php if($masterUnitArr->unit_code == $data->unit) { ?>
																<option value='<?php echo $masterUnitArr->unit_code; ?>' selected="selected"><?php echo $masterUnitArr->unit_name; ?></option>
															<?php } else { ?>
																<option value='<?php echo $masterUnitArr->unit_code; ?>'><?php echo $masterUnitArr->unit_name; ?></option>
															<?php } ?>

														<?php } ?>
													<?php } ?>
												</select>
											</td>
											<td>
												<input type='text' class='required form-control validateDecimalValue' name='qty[]' value="<?php echo (isset($data->qty)) ? $data->qty : '' ?>" />
											</td>
											<td>
												<input type='text' class='required form-control validateDecimalValue' name='taxable_subtotal[]' value="<?php echo (isset($data->taxable_subtotal)) ? $data->taxable_subtotal : '' ?>" />
											</td>
											<td>
												<input type='text' class='required form-control validateDecimalValue' name='invoice_total_value[]' value="<?php echo (isset($data->invoice_total_value)) ? $data->invoice_total_value : '' ?>" />
											</td>
											<td>
												<input type='text' class='required form-control validateDecimalValue' name='igst[]' value="<?php echo (isset($data->igst)) ? $data->igst : '' ?>" />
											</td>
											<td>
												<input type='text' class='required form-control validateDecimalValue' name='cgst[]' value="<?php echo (isset($data->cgst)) ? $data->cgst : '' ?>" />
											</td>
											<td>
												<input type='text' class='required form-control validateDecimalValue' name='sgst[]' value="<?php echo (isset($data->sgst)) ? $data->sgst : '' ?>" />
											</td>
											<td>
												<input type='text' class='required form-control validateDecimalValue' name='cess[]' value="<?php echo (isset($data->cess)) ? $data->cess : '' ?>" />
											</td>
											<td>
												<a class='delete-hsn-row' href='javascript:void(0)'><div class='tooltip2'><i style='font-size:20px;' class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a>
											</td>
										</tr>
									<?php
									}
								} else {

									foreach($returnHSNData as $data) { ?>

										<tr>
											<td>
												<input type='text' class='required form-control' data-bind="number" name='hsn[]' value="<?php echo (isset($data->item_hsncode)) ? $data->item_hsncode : '' ?>" />
											</td>
											<td>
												<input type='text' class='required form-control' data-bind="content" name='description[]' value="<?php echo (isset($data->item_hsncode)) ? $data->item_hsncode.'_'.$data->item_unit : '' ?>" />
											</td>
											<td>
												<select name="unit[]" class="required form-control">
													<?php $masterUnitArrs = $obj_gstr2->getMasterUnits("unit_id,unit_name,unit_code,(case when status='1' Then 'active' when status='0' then 'deactive' end) as status", "is_deleted='0' AND status='1'"); ?>
													<?php if(!empty($masterUnitArrs)) { ?>
														<option value=''>Select Unit</option>
														<?php foreach($masterUnitArrs as $masterUnitArr) { ?>

															<?php if($masterUnitArr->unit_code == $data->item_unit) { ?>
																<option value='<?php echo $masterUnitArr->unit_code; ?>' selected="selected"><?php echo $masterUnitArr->unit_name; ?></option>
															<?php } else { ?>
																<option value='<?php echo $masterUnitArr->unit_code; ?>'><?php echo $masterUnitArr->unit_name; ?></option>
															<?php } ?>

														<?php } ?>
													<?php } ?>
												</select>
											</td>
											<td>
												<input type='text' class='required form-control validateDecimalValue' name='qty[]' value="<?php echo (isset($data->item_quantity)) ? $data->item_quantity : '' ?>" />
											</td>
											<td>
												<input type='text' class='required form-control validateDecimalValue' name='taxable_subtotal[]' value="<?php echo (isset($data->taxable_subtotal)) ? $data->taxable_subtotal : '' ?>" />
											</td>
											<td>
												<input type='text' class='required form-control validateDecimalValue' name='invoice_total_value[]' value="<?php echo (isset($data->invoice_total_value)) ? $data->invoice_total_value : '' ?>" />
											</td>
											<td>
												<input type='text' class='required form-control validateDecimalValue' name='igst[]' value="<?php echo (isset($data->igst_amount)) ? $data->igst_amount : '' ?>" />
											</td>
											<td>
												<input type='text' class='required form-control validateDecimalValue' name='cgst[]' value="<?php echo (isset($data->cgst_amount)) ? $data->cgst_amount : '' ?>" />
											</td>
											<td>
												<input type='text' class='required form-control validateDecimalValue' name='sgst[]' value="<?php echo (isset($data->sgst_amount)) ? $data->sgst_amount : '' ?>" />
											</td>
											<td>
												<input type='text' class='required form-control validateDecimalValue' name='cess[]' value="<?php echo (isset($data->cess_amount)) ? $data->cess_amount : '' ?>" />
											</td>
											<td>
												<a style='font-size:20px;' class='delete-hsn-row' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a>
											</td>
										</tr>
									<?php
									}
								}
							}
						?>
						</tbody>
					</table>

					<input type="button" value="Add New Row" class="btn btn-success add-table-row">
				</div>

				<div class="tableresponsive">
					<div class="adminformbxsubmit" style="width:100%;">
						<div class="tc">
							<a type="button" href="<?php echo PROJECT_URL . "/?page=return_gstr2_summary&returnmonth=" . $returnmonth; ?>" class="btn btn-danger" /><?php echo ucfirst('Back'); ?></a>
							<input type='submit' class="btn btn-success" name='submit' value='submit' id='submit'>
						</div>
					</div>
				</div>

			</form>
		</div>
	</div>
	<div class="clear height40"></div>
</div>
<!--CONTENT START HERE-->
<div class="clear"></div>

<script type="text/javascript">
$(document).ready(function () {

	/* validate decimal values allow only numbers or decimals */
	$(".invoicetable").on("keypress input paste", ".validateDecimalValue", function (event) {
		return validateDecimalValue(event, this);
	});
	/* end of validate decimal values allow only numbers or decimals */
	
	$('#returnmonth').on('change', function () {
		window.location.href = "<?php echo PROJECT_URL; ?>/?page=return_gstr2_hsnwise_summary&returnmonth=" + $(this).val();
	});
	
	$(".add-table-row").click(function () {

		var newtr = "<tr>";
			newtr += "<td>";
				newtr += "<input type='text' class='required form-control' data-bind='number' name='hsn[]' />";
			newtr += "</td>";
			newtr += "<td>";
				newtr += "<input type='text' class='required form-control' data-bind='content' name='description[]' />";
			newtr += "</td>";
			newtr += "<td>";
				newtr += "<select name='unit[]' class='required form-control'>";
					<?php $masterUnitArrs = $obj_gstr2->getMasterUnits("unit_id,unit_name,unit_code,(case when status='1' Then 'active' when status='0' then 'deactive' end) as status", "is_deleted='0' AND status='1'"); ?>
					<?php if(!empty($masterUnitArrs)) { ?>
						newtr += "<option value=''>Select Unit</option>";
						<?php foreach($masterUnitArrs as $masterUnitArr) { ?>
							newtr += "<option value='<?php echo $masterUnitArr->unit_code; ?>'><?php echo $masterUnitArr->unit_name; ?></option>";
						<?php } ?>
					<?php } ?>
				newtr += "</select>";
			newtr += "</td>";
			newtr += "<td>";
				newtr += "<input type='text' class='required form-control validateDecimalValue' name='qty[]' />";
			newtr += "</td>";
			newtr += "<td>";
				newtr += "<input type='text' class='required form-control validateDecimalValue' name='taxable_subtotal[]' />";
			newtr += "</td>";
			newtr += "<td>";
				newtr += "<input type='text' class='required form-control validateDecimalValue' name='invoice_total_value[]' />";
			newtr += "</td>";
			newtr += "<td>";
				newtr += "<input type='text' class='required form-control validateDecimalValue' name='igst[]' />";
			newtr += "</td>";
			newtr += "<td>";
				newtr += "<input type='text' class='required form-control validateDecimalValue' name='cgst[]' />";
			newtr += "</td>";
			newtr += "<td>";
				newtr += "<input type='text' class='required form-control validateDecimalValue' name='sgst[]' />";
			newtr += "</td>";
			newtr += "<td>";
				newtr += "<input type='text' class='required form-control validateDecimalValue' name='cess[]' />";
			newtr += "</td>";
			newtr += "<td>";
				newtr += "<a class='delete-hsn-row' href='javascript:void(0)'><div class='tooltip2'><i style='font-size:20px;' class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a>";
			newtr += "</td>";
		newtr += "</tr>";

		/* insert new row */
		$('#hsn-table').append(newtr);
	});

	$('body').delegate('.delete-hsn-row', 'click', function () {
		$(this).closest('tr').remove();
	});
});
</script>