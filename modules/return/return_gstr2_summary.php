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

if (isset($_POST['generateGSTR2Summary']) && $_POST['generateGSTR2Summary'] == 'Generate GSTR2 Summary') {
	
	//$obj_gstr2->generateGSTR2B2BSummaryData($_SESSION['user_detail']['user_id'], $returnmonth, false);
	//$obj_gstr2->generateGSTR2B2BURSummaryData($_SESSION['user_detail']['user_id'], $returnmonth, false);
	//$obj_gstr2->generateGSTR2IMPSSummaryData($_SESSION['user_detail']['user_id'], $returnmonth, false);
	//$obj_gstr2->generateGSTR2IMPGSummaryData($_SESSION['user_detail']['user_id'], $returnmonth, false);
	$obj_gstr2->generateGSTR2IMPGSummaryData($_SESSION['user_detail']['user_id'], $returnmonth, false);
}

//$GSTR2B2BSummaryData = $obj_gstr2->generateGSTR2B2BPayloadData($_SESSION['user_detail']['user_id'], $returnmonth, false);
//$obj_gstr2->pr($GSTR2B2BSummaryData);

//$GSTR2B2BURSummaryData = $obj_gstr2->generateGSTR2B2BURPayloadData($_SESSION['user_detail']['user_id'], $returnmonth, false);
//$obj_gstr2->pr($GSTR2B2BURSummaryData);

//$GSTR2IMPSSummaryData = $obj_gstr2->generateGSTR2IMPSPayloadData($_SESSION['user_detail']['user_id'], $returnmonth, false);
//$obj_gstr2->pr($GSTR2IMPSSummaryData);

//$GSTR2IMPGSummaryData = $obj_gstr2->generateGSTR2IMPGPayloadData($_SESSION['user_detail']['user_id'], $returnmonth, false);
//$obj_gstr2->pr($GSTR2IMPGSummaryData);

//$GSTR2CDNRSummaryData = $obj_gstr2->generateGSTR2CDNRPayloadData($_SESSION['user_detail']['user_id'], $returnmonth, false);
//$obj_gstr2->pr($GSTR2CDNRSummaryData);

//$GSTR2CDNURSummaryData = $obj_gstr2->generateGSTR2CDNURPayloadData($_SESSION['user_detail']['user_id'], $returnmonth, false);
//$obj_gstr2->pr($GSTR2CDNURSummaryData);

//$GSTR2ATSummaryData = $obj_gstr2->generateGSTR2ATPayloadData($_SESSION['user_detail']['user_id'], $returnmonth, false);
//$obj_gstr2->pr($GSTR2ATSummaryData);

//$GSTR2ATADJSummaryData = $obj_gstr2->generateGSTR2ATADJPayloadData($_SESSION['user_detail']['user_id'], $returnmonth, false);
//$obj_gstr2->pr($GSTR2ATADJSummaryData);
?>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="col-md-6 col-sm-6 col-xs-12 heading">
      <h1>GSTR-2 Filing</h1>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-12 text-right breadcrumb-nav"> <a href="#">Home</a> <i class="fa fa-angle-right" aria-hidden="true"></i> <a href="#">File Return</a> <i class="fa fa-angle-right" aria-hidden="true"></i> <span class="active">GSTR-2 Filing</span> </div>
    <div class="whitebg formboxcontainer">

	  <div class="pull-left">
		<form method='post' name="createGSTR2Summary" id="createGSTR2Summary">
			<input type="submit" name="generateGSTR2Summary" id="generateGSTR2Summary" class="btn btn-success" value="Generate GSTR2 Summary">
		</form>
	  </div>

      <div class="pull-right">
        <form method='post' name='gstr2ReconcileForm' id="gstr2ReconcileForm">
          Month Of Return
          <select class="monthselectbox" id="returnmonth" name="returnmonth">
            <?php for($year = 2017; $year <= date('Y'); $year++) { ?>
				<?php for($month = 1; $month <= 12; $month++) { ?>
					<?php if($year >= 2017 && $month >= 6) { ?>
						<option <?php if($returnmonth == date("Y-m", strtotime($year."-".$month))) { echo 'selected="selected"'; } ?> value="<?php echo date( "Y-m", strtotime($year."-".$month) ); ?>"><?php echo date( "F Y", strtotime($year."-".$month) ); ?></option>
					<?php } ?>
				<?php } ?>
            <?php } ?>
          </select>
        </form>
      </div>

	  <div class="clear"></div>
	  <hr>
	  <div class="clear"></div>

	  <div class="pull-left">
		<a href="<?php echo PROJECT_URL."/?page=return_gstr2_nil_summary&returnmonth=" . $returnmonth; ?>" class="btn btn-success">Update Nil Summary</a>
		<a href="<?php echo PROJECT_URL."/?page=return_gstr2_hsnwise_summary&returnmonth=" . $returnmonth; ?>" class="btn btn-success">Update HSN Summary</a>
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

    </div>
  </div>
</div>
<div class="clear"></div>
<script>
$(document).ready(function () {
	$('#returnmonth').on('change', function () {
		window.location.href = "<?php echo PROJECT_URL; ?>/?page=return_gstr2_summary&returnmonth=" + $(this).val();
	});
});
</script>