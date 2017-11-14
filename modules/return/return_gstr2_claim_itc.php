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

if(isset($_POST['sub']) && $_POST['sub']=="Save ITC Values") {
	
	if($obj_gstr2->submitITCClaim()) {

		$obj_gstr2->redirect(PROJECT_URL."/?page=return_gstr2_claim_itc&returnmonth=".$returnmonth);
		exit();
	}
}

$GSTR2ClaimITCData = $obj_gstr2->generateGSTR2ClaimITCData($returnmonth, false);
$dataCurrentUserData = $obj_gstr2->getUserDetailsById($_SESSION['user_detail']['user_id']);
$companyState = $dataCurrentUserData['data']->kyc->state_tin;
?>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
	<div class="col-md-12 col-sm-12 col-xs-12">

		<div class="col-md-6 col-sm-6 col-xs-12 heading">
			<h1>GSTR-2 Filing</h1>
		</div>

		<div class="col-md-6 col-sm-6 col-xs-12 text-right breadcrumb-nav">
			<a href="#">Home</a> <i class="fa fa-angle-right" aria-hidden="true"></i> 
			<a href="#">File Return</a> <i class="fa fa-angle-right" aria-hidden="true"></i> 
			<span class="active">GSTR-2 Filing</span>
		</div>

		<div class="whitebg formboxcontainer">
			
			<?php if($obj_gstr2->getPurchaseUpdateStatus($returnmonth) !=0 ) { ?>
			  <div class="alert alert-warning">
				<strong>Suggestion!</strong> You have recently made changes in invoices so Please Re-generate data for Reconcile.
			  </div>
			  <div class="clear"></div>
			<?php } ?>

			<div class="pull-right">
				<form method='post' name='gstr2SummaryMonthForm' id="gstr2SummaryMonthForm">
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

			<div class="row heading">
				<div class="tab">
					<?php include(PROJECT_ROOT."/modules/return/include/tab.php"); ?>
				</div>
			</div>
			<div class="clear"></div>

			<form method="post" name="itc_form" id="itc_form" method="post">
				<div class="table-responsive">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" class="itctable invoice-itemtable" id="mainTable">
						<thead>
							<tr>
								<th class="text-center active"><input type="checkbox" name="selectAll" id="selectAll"></th>
								<th class="text-center active">Action</th>
								<th class="text-center active">Date</th>
								<th class="text-center active">Reference Number</th>
								<th class="text-center active">GSTIN</th>
								<th class="text-center active">Taxable Amount</th>
								<th class="text-center active">Total Amount</th>
								<th class="text-center active">Supply Type</th>
								<th class="text-center active">Total Tax</th>
								<th class="text-center active">ITC Category</th>
								<th class="text-center active">ITC CGST Amount</th>
								<th class="text-center active">ITC SGST Amount</th>
								<th class="text-center active">ITC IGST Amount</th>
								<th class="text-center active">ITC CESS Amount</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($GSTR2ClaimITCData as $GSTR2ClaimITCRow) { ?>
								<tr data-row-id="<?php echo $GSTR2ClaimITCRow['id']; ?>" class="text-center">
									<td><input type="checkbox" name="itc_checkbox_row_<?php echo $GSTR2ClaimITCRow['id']; ?>" id="itc_checkbox_row_<?php echo $GSTR2ClaimITCRow['id']; ?>" value="<?php echo $GSTR2ClaimITCRow['id']; ?>" class="itcRow"></td>
									<td><button type="button" data-button-id="<?php echo $GSTR2ClaimITCRow['id']; ?>" class="saveITCRow btn-sm btn btn-success" name="saveITC_<?php echo $GSTR2ClaimITCRow['id']; ?>" id="saveITC_<?php echo $GSTR2ClaimITCRow['id']; ?>" value="Save">Save</button></td>
									<td><?php echo $GSTR2ClaimITCRow['invoice_date']; ?></td>
									<td><?php echo $GSTR2ClaimITCRow['reference_number']; ?></td>
									<td><?php echo $GSTR2ClaimITCRow['company_gstin_number']; ?></td>
									<td><?php echo $GSTR2ClaimITCRow['total_taxable_subtotal']; ?></td>
									<td><?php echo $GSTR2ClaimITCRow['invoice_total_value']; ?></td>

									<?php if($companyState == $GSTR2ClaimITCRow['pos']) { ?>
										<td><h4><span class="label label-warning">Intra</span></h4></td>
									<?php } else { ?>
										<td><h4><span class="label label-success">Inter</span></h4></td>
									<?php } ?>

									<td><?php echo $GSTR2ClaimITCRow['total_cgst_amount'] + $GSTR2ClaimITCRow['total_sgst_amount'] + $GSTR2ClaimITCRow['total_igst_amount'] + $GSTR2ClaimITCRow['total_cess_amount']; ?></td>
									<td>									
										<select class="monthselectbox form-control" name="itcType_<?php echo $GSTR2ClaimITCRow['id']; ?>" id="itcType_<?php echo $GSTR2ClaimITCRow['id']; ?>">
											<option value="">Not Selected</option>
											<?php if($GSTR2ClaimITCRow['inv_typ'] == "IMPG") { ?>
												<option value="ip" <?php if($GSTR2ClaimITCRow['eligibility'] == "ip") { echo 'selected="selected"'; } ?>>Inputs</option>
												<option value="cp" <?php if($GSTR2ClaimITCRow['eligibility'] == "cp") { echo 'selected="selected"'; } ?>>Capital Goods</option>
												<option value="no" <?php if($GSTR2ClaimITCRow['eligibility'] == "no") { echo 'selected="selected"'; } ?>>Ineligible</option>
											<?php } else if($GSTR2ClaimITCRow['inv_typ'] == "IMPS") { ?>
												<option value="is" <?php if($GSTR2ClaimITCRow['eligibility'] == "is") { echo 'selected="selected"'; } ?>>Input Services</option>
												<option value="no" <?php if($GSTR2ClaimITCRow['eligibility'] == "no") { echo 'selected="selected"'; } ?>>Ineligible</option>
											<?php } else { ?>
												<option value="ip" <?php if($GSTR2ClaimITCRow['eligibility'] == "ip") { echo 'selected="selected"'; } ?>>Inputs</option>
												<option value="cp" <?php if($GSTR2ClaimITCRow['eligibility'] == "cp") { echo 'selected="selected"'; } ?>>Capital Goods</option>
												<option value="is" <?php if($GSTR2ClaimITCRow['eligibility'] == "is") { echo 'selected="selected"'; } ?>>Input Services</option>
												<option value="no" <?php if($GSTR2ClaimITCRow['eligibility'] == "no") { echo 'selected="selected"'; } ?>>Ineligible</option>
											<?php } ?>
										</select>
									</td>
									<td><input style="width:100px;" type="text" class="form-control validateDecimalValue" name="total_itc_cgst_amount_<?php echo $GSTR2ClaimITCRow['id']; ?>" id="total_itc_cgst_amount_<?php echo $GSTR2ClaimITCRow['id']; ?>" value="<?php echo $GSTR2ClaimITCRow['total_itc_cgst_amount']; ?>"></td>
									<td><input style="width:100px;" type="text" class="form-control validateDecimalValue" name="total_itc_sgst_amount_<?php echo $GSTR2ClaimITCRow['id']; ?>" id="total_itc_sgst_amount_<?php echo $GSTR2ClaimITCRow['id']; ?>" value="<?php echo $GSTR2ClaimITCRow['total_itc_sgst_amount']; ?>"></td>
									<td><input style="width:100px;" type="text" class="form-control validateDecimalValue" name="total_itc_igst_amount_<?php echo $GSTR2ClaimITCRow['id']; ?>" id="total_itc_igst_amount_<?php echo $GSTR2ClaimITCRow['id']; ?>" value="<?php echo $GSTR2ClaimITCRow['total_itc_igst_amount']; ?>"></td>
									<td><input style="width:100px;" type="text" class="form-control validateDecimalValue" name="total_itc_cess_amount_<?php echo $GSTR2ClaimITCRow['id']; ?>" id="total_itc_cess_amount_<?php echo $GSTR2ClaimITCRow['id']; ?>" value="<?php echo $GSTR2ClaimITCRow['total_itc_cess_amount']; ?>"></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
				<div class="clear height20"></div>
				<input type="submit" class="btn btn-success pull-right" value="Save All ITC Data" name="save_all_itc" id="save_all_itc">
				<div class="clear"></div>
			</form>
		</div>
		<div class="clear height40"></div>
	</div>
	<div class="clear"></div>
</div>

<script>
$(document).ready(function () {

	/* validate invoice decimal values allow only numbers or decimals */
	$(".itctable").on("keypress input paste", ".validateDecimalValue", function (event) {
		return validateDecimalValue(event, this);
	});
	/* end of validate invoice decimal values allow only numbers or decimals */

	$("#selectAll").click(function () {
		$(".itcRow").prop('checked', $(this).prop('checked'));
	});

	$(".itcRow").click(function(){

		if($(".itcRow").length == $(".itcRow:checked").length) {
			$("#selectAll").prop('checked', true);
		} else {
			$("#selectAll").prop('checked', false);
		}
	});

	$('#returnmonth').on('change', function () {
		window.location.href = "<?php echo PROJECT_URL; ?>/?page=return_gstr2_claim_itc&returnmonth=" + $(this).val();
	});

	$('.saveITCRow').click(function(){

		var currentRowId = $(this).attr("data-button-id");
		var currentRowITCType = $('#itcType_' + currentRowId).val();
		var currentRowITCCGST = $('#total_itc_cgst_amount_' + currentRowId).val();
		var currentRowITCSGST = $('#total_itc_sgst_amount_' + currentRowId).val();
		var currentRowITCIGST = $('#total_itc_igst_amount_' + currentRowId).val();
		var currentRowITCCESS = $('#total_itc_cess_amount_' + currentRowId).val();
		
		if(currentRowITCType == '') {
			jAlert("ITC Category type is required.");
			return false;
		}

		$("#loading").show();
		$.ajax({
			data: {
					currentRowId:currentRowId, 
					currentRowITCType:currentRowITCType, 
					currentRowITCCGST:currentRowITCCGST, 
					currentRowITCSGST:currentRowITCSGST, 
					currentRowITCIGST:currentRowITCIGST, 
					currentRowITCCESS:currentRowITCCESS, 
					action:"saveRowITCData"
				  },
			dataType: 'json',
			type: 'post',
			url: "<?php echo PROJECT_URL; ?>/?ajax=return_gstr2_save_claim_itc",
			success: function(response){

				$("#loading").hide();
				if(response.status == "error") {
					jAlert(response.message);
				} else if(response.status == "success") {
					$('#total_itc_cgst_amount_' + currentRowId).val(response.total_itc_cgst_amount);
					$('#total_itc_sgst_amount_' + currentRowId).val(response.total_itc_sgst_amount);
					$('#total_itc_igst_amount_' + currentRowId).val(response.total_itc_igst_amount);
					$('#total_itc_cess_amount_' + currentRowId).val(response.total_itc_cess_amount);
					jAlert(response.message);
				}
			}
		});
	});

	$("#save_all_itc").click(function() {

		var allITCData = [];
		$("#loading").show();
		$("#mainTable tbody tr").each(function() {

			var currentRowId = $(this).attr("data-row-id");
			var currentRowITCType = $('#itcType_' + currentRowId).val();
			var currentRowITCCGST = $('#total_itc_cgst_amount_' + currentRowId).val();
			var currentRowITCSGST = $('#total_itc_sgst_amount_' + currentRowId).val();
			var currentRowITCIGST = $('#total_itc_igst_amount_' + currentRowId).val();
			var currentRowITCCESS = $('#total_itc_cess_amount_' + currentRowId).val();

			if($('#itc_checkbox_row_' + currentRowId).is(":checked")) {

				if(currentRowITCType != '') {

					var ITCData = {
									"currentRowId":currentRowId,
									"currentRowITCType":currentRowITCType,
									"currentRowITCCGST":currentRowITCCGST,
									"currentRowITCSGST":currentRowITCSGST,
									"currentRowITCIGST":currentRowITCIGST,
									"currentRowITCCESS":currentRowITCCESS
								};
					allITCData.push(ITCData);
				}
			}
		});

		if(allITCData.length > 0) {

			$.ajax({
				data: {
					allITCData:allITCData, 
					action:"saveallRowITCData"
				},
				dataType: 'json',
				type: 'post',
				url: "<?php echo PROJECT_URL; ?>/?ajax=return_gstr2_save_claim_itc",
				success: function(response){

					if(response.status == "error") {
						jAlert(response.message);
					} else if(response.status == "success") {
						window.location.reload(true);
					}
				}
			});
		}

		$("#loading").hide();
		return false;
	});
});
</script>