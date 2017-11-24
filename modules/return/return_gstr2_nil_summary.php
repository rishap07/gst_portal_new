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

if (isset($_POST['saveNilSummary']) && $_POST['saveNilSummary'] == 'Submit') {

	if ($obj_gstr2->saveGstr2nilexemptSummary()) {
		//$obj_master->redirect(PROJECT_URL."/?page=master_receiver");
	}
}

if(isset($_POST['generateGSTR2NILSummary']) && $_POST['generateGSTR2NILSummary'] == "Generate GSTR2 NIL Summary") {

	$GSTR2CPDDRINTRANILSummaryData = $obj_gstr2->generateGSTR2CPDDRNILSummaryData($_SESSION['user_detail']['user_id'], $returnmonth, "INTRA", false);
	$GSTR2CPDDRINTERNILSummaryData = $obj_gstr2->generateGSTR2CPDDRNILSummaryData($_SESSION['user_detail']['user_id'], $returnmonth, "INTER", false);

	if(isset($GSTR2CPDDRINTRANILSummaryData[0]['taxable_subtotal']) && $GSTR2CPDDRINTRANILSummaryData[0]['taxable_subtotal'] != NULL) {
		$intra_cpddr = $GSTR2CPDDRINTRANILSummaryData[0]['taxable_subtotal'];
	} else {
		$intra_cpddr = 0.00;
	}

	if(isset($GSTR2CPDDRINTERNILSummaryData[0]['taxable_subtotal']) && $GSTR2CPDDRINTERNILSummaryData[0]['taxable_subtotal'] != NULL) {
		$inter_cpddr = $GSTR2CPDDRINTERNILSummaryData[0]['taxable_subtotal'];				
	} else {
		$inter_cpddr = 0.00;
	}

	$GSTR2INTRANILRATEDSummaryData = $obj_gstr2->generateGSTR2NILRATEDSummaryData($_SESSION['user_detail']['user_id'], $returnmonth, "INTRA", false);
	$GSTR2INTERNILRATEDSummaryData = $obj_gstr2->generateGSTR2NILRATEDSummaryData($_SESSION['user_detail']['user_id'], $returnmonth, "INTER", false);

	if(isset($GSTR2INTRANILRATEDSummaryData[0]['taxable_subtotal']) && $GSTR2INTRANILRATEDSummaryData[0]['taxable_subtotal'] != NULL) {
		$intra_nilsply = $GSTR2INTRANILRATEDSummaryData[0]['taxable_subtotal'];
	} else {
		$intra_nilsply = 0.00;
	}

	if(isset($GSTR2INTERNILRATEDSummaryData[0]['taxable_subtotal']) && $GSTR2INTERNILRATEDSummaryData[0]['taxable_subtotal'] != NULL) {
		$inter_nilsply = $GSTR2INTERNILRATEDSummaryData[0]['taxable_subtotal'];				
	} else {
		$inter_nilsply = 0.00;
	}

	$GSTR2INTRANILNONGSTSummaryData = $obj_gstr2->generateGSTR2NILNONGSTSummaryData($_SESSION['user_detail']['user_id'], $returnmonth, "INTRA", false);
	$GSTR2INTERNILNONGSTSummaryData = $obj_gstr2->generateGSTR2NILNONGSTSummaryData($_SESSION['user_detail']['user_id'], $returnmonth, "INTER", false);

	if(isset($GSTR2INTRANILNONGSTSummaryData[0]['taxable_subtotal']) && $GSTR2INTRANILNONGSTSummaryData[0]['taxable_subtotal'] != NULL) {
		$intra_ngsply = $GSTR2INTRANILNONGSTSummaryData[0]['taxable_subtotal'];
	} else {
		$intra_ngsply = 0.00;
	}

	if(isset($GSTR2INTERNILNONGSTSummaryData[0]['taxable_subtotal']) && $GSTR2INTERNILNONGSTSummaryData[0]['taxable_subtotal'] != NULL) {
		$inter_ngsply = $GSTR2INTERNILNONGSTSummaryData[0]['taxable_subtotal'];				
	} else {
		$inter_ngsply = 0.00;
	}

	$GSTR2INTRANILEXESummaryData = $obj_gstr2->generateGSTR2NILEXESummaryData($_SESSION['user_detail']['user_id'], $returnmonth, "INTRA", false);
	$GSTR2INTERNILEXESummaryData = $obj_gstr2->generateGSTR2NILEXESummaryData($_SESSION['user_detail']['user_id'], $returnmonth, "INTER", false);

	if(isset($GSTR2INTRANILEXESummaryData[0]['taxable_subtotal']) && $GSTR2INTRANILEXESummaryData[0]['taxable_subtotal'] != NULL) {
		$intra_exptdsply = $GSTR2INTRANILEXESummaryData[0]['taxable_subtotal'];
	} else {
		$intra_exptdsply = 0.00;
	}

	if(isset($GSTR2INTERNILEXESummaryData[0]['taxable_subtotal']) && $GSTR2INTERNILEXESummaryData[0]['taxable_subtotal'] != NULL) {
		$inter_exptdsply = $GSTR2INTERNILEXESummaryData[0]['taxable_subtotal'];				
	} else {
		$inter_exptdsply = 0.00;
	}

} else {
	
	$returnNILData = $obj_gstr2->get_results("select *, count(id) as totalinvoice from ". $obj_gstr2->getTableName('return_upload_summary') ." where added_by = '" . $_SESSION['user_detail']['user_id'] . "' and financial_month like '" . $returnmonth . "' and is_deleted = '0' and type = 'gstr2nil' order by id desc limit 0,1");
	if($returnNILData[0]->totalinvoice > 0) {

		$arr = $returnNILData[0]->return_data;
		$arr1 = base64_decode($arr);
		$summary_arr = json_decode($arr1);

		$inter1 = array();
		$inter1 = !empty($summary_arr->inter)?$summary_arr->inter:'';
		$intra2 = array();
		$intra2 = !empty($summary_arr->intra)?$summary_arr->intra:'';

		$inter_cpddr = '';
		$inter_exptdsply = '';
		$inter_ngsply = '';
		$inter_nilsply = '';
		$intra_cpddr = '';
		$intra_exptdsply = '';
		$intra_ngsply = '';
		$intra_nilsply = '';

		if(!empty($inter1)) {

			foreach($inter1 as $item) {
				$inter_cpddr = $item->cpddr;
				$inter_exptdsply = $item->exptdsply;
				$inter_ngsply = $item->ngsply;
				$inter_nilsply = $item->nilsply;
			}
		}

		if(!empty($intra2)) {

			foreach($intra2 as $item) {
				$intra_cpddr = $item->cpddr;
				$intra_exptdsply = $item->exptdsply;
				$intra_ngsply = $item->ngsply;
				$intra_nilsply = $item->nilsply;
			}
		}
	}
}
?>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="col-md-6 col-sm-6 col-xs-12 heading">
		  <h1>GSTR-2 Nil Summary</h1>
		</div>
		<div class="col-md-6 col-sm-6 col-xs-12 text-right breadcrumb-nav"> <a href="#">Home</a> <i class="fa fa-angle-right" aria-hidden="true"></i> <a href="#">File Return</a> <i class="fa fa-angle-right" aria-hidden="true"></i> <span class="active">GSTR-2 Filing</span> </div>
		<div class="whitebg formboxcontainer">

			<div class="pull-left">
				<form method='post' name="generateGSTR2NILSummaryForm" id="generateGSTR2NILSummaryForm">
					<input type="submit" name="generateGSTR2NILSummary" id="generateGSTR2NILSummary" class="btn btn-success" value="Generate GSTR2 NIL Summary">
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
			
			<div class="greyheading">1.Nil rated, exempted and Non-GST supplies</div>
			<form method="post" enctype="multipart/form-data" id='form'>
				<div class="tableresponsive">
					<table class="table tablecontent tablecontent2 bordernone" id='table1a'>
						<thead>
							<tr>
								<th>SupplyType</th>
								<th>Value Of Supplies Received From Compounding Dealer(<i class="fa fa-inr"></i>)</th>
								<th>Value Of Exempted Supplies Received (<i class="fa fa-inr"></i>)</th>
								<th>Total Non GST Outward Supplies(<i class="fa fa-inr"></i>)</th>
								<th>Nil Rated Supply(<i class="fa fa-inr"></i>)</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="lftheading">Inter-State</td>
								<td><input type='text' class='required form-control' name='inter_cpddr' value="<?php echo (isset($inter_cpddr)) ? $inter_cpddr : ''; ?>"/></td>
								<td><input type='text' class='required form-control' name='inter_exptdsply' value="<?php echo (isset($inter_exptdsply)) ? $inter_exptdsply : ''; ?>"/></td>
								<td><input type='text' class='required form-control' name='inter_ngsply' value="<?php echo (isset($inter_ngsply)) ? $inter_ngsply : ''; ?>"/></td>
								<td><input type='text' class='required form-control' name='inter_nilsply' value="<?php echo (isset($inter_nilsply)) ? $inter_nilsply : ''; ?>"/></td>
							</tr>
							<tr>
								<td class="lftheading">Intra-State</td>
								<td><input type='text' class='required form-control' name='intra_cpddr' value="<?php echo (isset($intra_cpddr)) ? $intra_cpddr : ''; ?>"/></td>
								<td><input type='text' class='required form-control' name='intra_exptdsply' value="<?php echo (isset($intra_exptdsply)) ? $intra_exptdsply : ''; ?>"/></td>
								<td><input type='text' class='required form-control' name='intra_ngsply' value="<?php echo (isset($intra_ngsply)) ? $intra_ngsply : ''; ?>"/></td>
								<td><input type='text' class='required form-control' name='intra_nilsply' value="<?php echo (isset($intra_nilsply)) ? $intra_nilsply : ''; ?>"/></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="tableresponsive">
					<div class="adminformbxsubmit" style="width:100%;"> 
						<div class="tc">
							<a type="button" href="<?php echo PROJECT_URL . "/?page=return_gstr2_summary&returnmonth=" . $returnmonth; ?>" class="btn btn-danger" /><?php echo ucfirst('Back'); ?></a>
							<input type='submit' class="btn btn-success" name='saveNilSummary' value='Submit' id='submit'>
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
<script>
	$(document).ready(function () {
		$('#returnmonth').on('change', function () {
			window.location.href = "<?php echo PROJECT_URL; ?>/?page=return_gstr2_summary&returnmonth=" + $(this).val();
		});
	});
</script>