<?php
$obj_json = new json();

if(!$obj_json->can_read('returnfile_list')) {
    $obj_json->setError($obj_json->getValMsg('can_read'));
    $obj_json->redirect(PROJECT_URL."/?page=dashboard");
    exit();
}

if(isset($_GET['returnmonth']) && !empty($_GET['returnmonth'])) {
	$returnmonth = $_GET['returnmonth'];
} else {
	$obj_json->setError("Please choose return period.");
    $obj_json->redirect(PROJECT_URL."/?page=return_client");
    exit();
}

if (isset($_POST['reconcileData']) && $_POST['reconcileData'] == 'Auto Populate Data') {

	$finalInsertArray = array();

	/* missing data */
	$missingDataResult = $obj_json->getGSTR2ADownlodedMissingData($_SESSION['user_detail']['user_id'], $returnmonth, false);
	foreach($missingDataResult as $missingResult) {

		$missingArray['reference_number'] = $missingResult['reference_number'];
		$missingArray['invoice_date'] = $missingResult['invoice_date'];
		$missingArray['invoice_total_value'] = $missingResult['invoice_total_value'];
		$missingArray['total_taxable_subtotal'] = $missingResult['total_taxable_subtotal'];
		$missingArray['invoice_status'] = 'missing';
		$missingArray['reconciliation_status'] = 'pending';
		$missingArray['company_gstin_number'] = $missingResult['company_gstin_number'];
		$missingArray['total_cgst_amount'] = $missingResult['total_cgst_amount'];
		$missingArray['total_sgst_amount'] = $missingResult['total_sgst_amount'];
		$missingArray['total_igst_amount'] = $missingResult['total_igst_amount'];
		$missingArray['total_cess_amount'] = $missingResult['total_cess_amount'];
		$missingArray['pos'] = $missingResult['pos'];
		$missingArray['nt_num'] = $missingResult['nt_num'];
		$missingArray['rate'] = $missingResult['rate'];
		$missingArray['added_by'] = $_SESSION['user_detail']['user_id'];
		$missingArray['added_date'] = date('Y-m-d H:i:s');
		$missingArray['reverse_charge'] = $missingResult['reverse_charge'];
		$missingArray['financial_month'] = $missingResult['financial_month'];

		array_push($finalInsertArray, $missingArray);
	}

	/* additional data */
	$additionalDataResult = $obj_json->getGSTR2ADownlodedAdditionalData($_SESSION['user_detail']['user_id'], $returnmonth, false);
	foreach($additionalDataResult as $additionalResult) {

		$additionalArray['reference_number'] = $additionalResult['reference_number'];
		$additionalArray['invoice_date'] = $additionalResult['invoice_date'];
		$additionalArray['invoice_total_value'] = $additionalResult['invoice_total_value'];
		$additionalArray['total_taxable_subtotal'] = $additionalResult['total_taxable_subtotal'];
		$additionalArray['invoice_status'] = 'additional';
		$additionalArray['reconciliation_status'] = 'pending';
		$additionalArray['company_gstin_number'] = $additionalResult['company_gstin_number'];
		$additionalArray['total_cgst_amount'] = $additionalResult['total_cgst_amount'];
		$additionalArray['total_sgst_amount'] = $additionalResult['total_sgst_amount'];
		$additionalArray['total_igst_amount'] = $additionalResult['total_igst_amount'];
		$additionalArray['total_cess_amount'] = $additionalResult['total_cess_amount'];
		$additionalArray['pos'] = $additionalResult['pos'];
		$additionalArray['nt_num'] = $additionalResult['nt_num'];
		$additionalArray['rate'] = $additionalResult['rate'];
		$additionalArray['added_by'] = $_SESSION['user_detail']['user_id'];
		$additionalArray['added_date'] = date('Y-m-d H:i:s',time());
		$additionalArray['reverse_charge'] = $additionalResult['reverse_charge'];
		$additionalArray['financial_month'] = $additionalResult['financial_month'];

		array_push($finalInsertArray, $additionalArray);
	}

	/* matchmis data */
	$MatchMisData = $obj_json->getGSTR2ADownlodedMatchMisData($_SESSION['user_detail']['user_id'], $returnmonth, false);
	
	$obj_json->pr($MatchMisData);
	die;

	foreach($MatchMisData as $mmdata) {

		/* consolidate data from purchase invoice */
		$queryPurchase ='Select 
							pi.reference_number, 
							(
								CASE 
									WHEN pi.is_tax_payable = "1" THEN "Y" 
									ELSE "N" 
								END
							) AS reverse_charge, 
							pi.invoice_date,
							pi.invoice_total_value,
							sum(pii.taxable_subtotal) as total_taxable_subtotal,
							pi.supplier_billing_gstin_number as company_gstin_number,
							sum(pii.cgst_amount) as total_cgst_amount,
							sum(pii.sgst_amount) as total_sgst_amount,
							sum(pii.igst_amount) as total_igst_amount,
							sum(pii.cess_amount) as total_cess_amount,
							(
								CASE
									WHEN pi.corresponding_document_number = "0" THEN pi.corresponding_document_number 
									ELSE (SELECT reference_number FROM '.$obj_json->getTableName('client_purchase_invoice').' WHERE purchase_invoice_id = pi.corresponding_document_number)
								END
							) AS nt_num, 
							pi.corresponding_document_date as nt_dt,
							GROUP_CONCAT(DISTINCT CONVERT(pii.consolidate_rate USING utf8) ORDER BY CONVERT(pii.consolidate_rate USING utf8) ASC SEPARATOR ",") as rate,
							(SELECT state_tin FROM '.$obj_json->getTableName('state').' WHERE state_id = pi.supply_place) as pos,
							DATE_FORMAT(pi.invoice_date,"%Y-%m") as financial_month 
							from '.$obj_json->getTableName('client_purchase_invoice').' as pi 
							INNER JOIN '.$obj_json->getTableName('client_purchase_invoice_item').' as pii 
							ON pi.purchase_invoice_id = pii.purchase_invoice_id 
							where 1=1 
							and pi.added_by = '.$_SESSION['user_detail']['user_id'].' 
							and pii.added_by = '.$_SESSION['user_detail']['user_id'].' 
							and pi.purchase_invoice_id = '.$mmdata['purchase_invoice_id'].' 
							and pi.reference_number = "'.$mmdata['pi_reference_number'].'" 
							and pi.supplier_billing_gstin_number = "'.$mmdata['pi_ctin'].'" 
							and DATE_FORMAT(pi.invoice_date,"%Y-%m") = "'.$returnmonth.'"';

		$resultPurchase = $obj_json->get_results($queryPurchase, false);

		/* consolidate data from downloaded GSTR-2 invoice */
		$queryDownPurchase = 'Select 
								di.reference_number,
								di.invoice_date,
								di.invoice_total_value,
								sum(di.total_taxable_subtotal) as total_taxable_subtotal,
								di.company_gstin_number,
								sum(di.total_cgst_amount) as total_cgst_amount,
								sum(di.total_sgst_amount) as total_sgst_amount,
								sum(di.total_igst_amount) as total_igst_amount,
								sum(di.total_cess_amount) as total_cess_amount,
								di.nt_num,
								di.nt_dt, 
								GROUP_CONCAT(DISTINCT CAST(di.rate USING utf8) ORDER BY CAST(di.rate USING utf8) ASC SEPARATOR ",") as rate, 
								di.pos, 
								(
									CASE 
										WHEN di.rchrg = "Y" THEN "Y" 
										ELSE "N" 
									END
								) AS reverse_charge, 
								di.financial_month 
								from '.$obj_json->getTableName('client_reconcile_purchase_invoice1').' as di 
								where 1=1 
								and di.added_by = '.$_SESSION['user_detail']['user_id'].' 
								and di.reference_number = "'.$mmdata['reference_number'].'" 
								and di.company_gstin_number = "'.$mmdata['ctin'].'" 
								and DATE_FORMAT(di.invoice_date,"%Y-%m") = "'.$returnmonth.'"';

		$resultDownPurchase = $obj_json->get_results($queryDownPurchase, false);

		if(
			$resultPurchase[0]['invoice_total_value'] == $resultDownPurchase[0]['invoice_total_value'] && 
			$resultPurchase[0]['total_taxable_subtotal'] == $resultDownPurchase[0]['total_taxable_subtotal'] && 
			$resultPurchase[0]['total_cgst_amount'] == $resultDownPurchase[0]['total_cgst_amount'] && 
			$resultPurchase[0]['total_sgst_amount'] == $resultDownPurchase[0]['total_sgst_amount'] && 
			$resultPurchase[0]['total_igst_amount'] == $resultDownPurchase[0]['total_igst_amount'] && 
			$resultPurchase[0]['total_cess_amount'] == $resultDownPurchase[0]['total_cess_amount'] && 
			$resultPurchase[0]['pos'] == $resultDownPurchase[0]['pos'] && 
			$resultPurchase[0]['invoice_date'] == $resultDownPurchase[0]['invoice_date']
		){

			//match
			$matchtArray['reference_number'] = $resultPurchase[0]['reference_number'];
			$matchtArray['invoice_date'] = $resultPurchase[0]['invoice_date'];
			$matchtArray['invoice_total_value'] = $resultPurchase[0]['invoice_total_value'];
			$matchtArray['total_taxable_subtotal'] = $resultPurchase[0]['total_taxable_subtotal'];
			$matchtArray['invoice_status'] = 'match';
			$matchtArray['reconciliation_status'] = 'accept';
			$matchtArray['company_gstin_number'] = $resultPurchase[0]['company_gstin_number'];
			$matchtArray['total_cgst_amount'] = $resultPurchase[0]['total_cgst_amount'];
			$matchtArray['total_sgst_amount'] = $resultPurchase[0]['total_sgst_amount'];
			$matchtArray['total_igst_amount'] = $resultPurchase[0]['total_igst_amount'];
			$matchtArray['total_cess_amount'] = $resultPurchase[0]['total_cess_amount'];
			$matchtArray['pos'] = $resultPurchase[0]['pos'];
			$matchtArray['nt_num'] = $resultPurchase[0]['nt_num'];
			$matchtArray['rate'] = $resultPurchase[0]['rate'];
			$matchtArray['added_by'] = $_SESSION['user_detail']['user_id'];
			$matchtArray['added_date'] = date('Y-m-d H:i:s',time());
			$matchtArray['reverse_charge'] = $resultPurchase[0]['reverse_charge'];
			$matchtArray['financial_month'] = $resultPurchase[0]['financial_month'];

			array_push($finalInsertArray, $matchtArray);
			
		} else {

			//mimatch
			$mimatchtArray['reference_number'] = $resultPurchase[0]['reference_number'];
			$mimatchtArray['invoice_date'] = $resultPurchase[0]['invoice_date'].'|||'.$resultDownPurchase[0]['invoice_date'];
			$mimatchtArray['invoice_total_value'] = $resultPurchase[0]['invoice_total_value'].'|||'.$resultDownPurchase[0]['invoice_total_value'];
			$mimatchtArray['total_taxable_subtotal'] = $resultPurchase[0]['total_taxable_subtotal'].'|||'.$resultDownPurchase[0]['total_taxable_subtotal'];
			$mimatchtArray['invoice_status'] = 'mismatch';
			$mimatchtArray['reconciliation_status'] = 'pending';
			$mimatchtArray['company_gstin_number'] = $resultPurchase[0]['company_gstin_number'];
			$mimatchtArray['total_cgst_amount'] = $resultPurchase[0]['total_cgst_amount'].'|||'.$resultDownPurchase[0]['total_cgst_amount'];
			$mimatchtArray['total_sgst_amount'] = $resultPurchase[0]['total_sgst_amount'].'|||'.$resultDownPurchase[0]['total_sgst_amount'];
			$mimatchtArray['total_igst_amount'] = $resultPurchase[0]['total_igst_amount'].'|||'.$resultDownPurchase[0]['total_igst_amount'];
			$mimatchtArray['total_cess_amount'] = $resultPurchase[0]['total_cess_amount'].'|||'.$resultDownPurchase[0]['total_cess_amount'];
			$mimatchtArray['pos'] = $resultPurchase[0]['pos'].'|||'.$resultDownPurchase[0]['pos'];
			$mimatchtArray['nt_num'] = $resultPurchase[0]['nt_num'];
			$mimatchtArray['rate'] = $resultPurchase[0]['rate'].'|||'.$resultDownPurchase[0]['rate'];
			$mimatchtArray['added_by'] = $_SESSION['user_detail']['user_id'];
			$mimatchtArray['added_date'] = date('Y-m-d H:i:s',time());
			$mimatchtArray['reverse_charge'] = $resultPurchase[0]['reverse_charge'].'|||'.$resultDownPurchase[0]['reverse_charge'];
			$mimatchtArray['financial_month'] = $resultPurchase[0]['financial_month'];

			array_push($finalInsertArray, $mimatchtArray);
		}
	}

    $dataConditionArray['added_by'] = $_SESSION['user_detail']['user_id'];;
	$dataConditionArray['financial_month'] = $returnmonth;
	$obj_json->deletData($obj_json->getTableName('gstr2_reconcile_final'), $dataConditionArray);
  	$obj_json->insertMultiple($obj_json->getTableName('gstr2_reconcile_final'), $finalInsertArray);
	$obj_json->query("UPDATE ".$obj_json->getTableName('client_purchase_invoice')." SET update_status = '0' WHERE 1=1 AND added_by = " . $_SESSION['user_detail']['user_id'] . " AND DATE_FORMAT(invoice_date,'%Y-%m') = '" . $returnmonth . "'");
}

$matchFinalData = $obj_json->getGst2ReconcileFinalQuery($returnmonth, 'match');

$missingFinalData = $obj_json->getGst2ReconcileFinalQuery($returnmonth, 'missing');
$missingAddressedData = $obj_json->getGst2ReconcileFinalQuery($returnmonth, 'missing', 'count(id) as addressed', 'reconciliation_status!="pending"');
$missingPendingData = $obj_json->getGst2ReconcileFinalQuery($returnmonth, 'missing', 'count(id) as pending', 'reconciliation_status="pending"');

$additionalFinalData = $obj_json->getGst2ReconcileFinalQuery($returnmonth, 'additional');
$additionalAddressedData = $obj_json->getGst2ReconcileFinalQuery($returnmonth, 'additional', 'count(id) as addressed', 'reconciliation_status!="pending"');
$additionalPendingData = $obj_json->getGst2ReconcileFinalQuery($returnmonth, 'additional', 'count(id) as pending', 'reconciliation_status="pending"');

$mismatchFinalData = $obj_json->getGst2ReconcileFinalQuery($returnmonth, 'mismatch');
$mismatchAddressedData = $obj_json->getGst2ReconcileFinalQuery($returnmonth, 'mismatch', 'count(id) as addressed', 'reconciliation_status!="pending"');
$mismatchPendingData = $obj_json->getGst2ReconcileFinalQuery($returnmonth, 'mismatch', 'count(id) as pending', 'reconciliation_status="pending"');
?>

<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="col-md-6 col-sm-6 col-xs-12 heading">
      <h1>GSTR-2 Filing</h1>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-12 text-right breadcrumb-nav"> <a href="#">Home</a> <i class="fa fa-angle-right" aria-hidden="true"></i> <a href="#">File Return</a> <i class="fa fa-angle-right" aria-hidden="true"></i> <span class="active">GSTR-2 Filing</span> </div>
    <div class="whitebg formboxcontainer">
	
	  <?php if($obj_json->getPurchaseUpdateStatus($returnmonth) !=0 ) { ?>
		  <div class="alert alert-warning">
			<strong>Suggestion!</strong> You have recently made changes in invoices so Please Re-generate data for Reconcile.
		  </div>
		  <div class="clear"></div>
	  <?php } ?>

	  <div class="pull-left">
		<form method='post' name="autoPopulateReconcile" id="autoPopulateReconcile">
			<input type="submit" name="reconcileData" id="reconcileData" class="btn btn-success" value="Auto Populate Data">
		</form>
	  </div>
      <div class="pull-right">
        <form method='post' name='gstr2ReconcileForm' id="gstr2ReconcileForm">
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
	  
	  <?php $obj_json->showErrorMessage(); ?>
	  <?php $obj_json->showSuccessMessge(); ?>
	  <?php $obj_json->unsetMessage(); ?>

	  <div class="row heading">
		<div class="tab">
			<?php include(PROJECT_ROOT."/modules/return/include/tab.php"); ?>
		</div>
	  </div>
	  <div class="clear"></div>
      
	  <div class="row gstr2-reconcile">
        <div class="row reconciliation">
          <div class="col-md-3 col-sm-3 col-xs-12">
            <div class="lightgreen col-text">
              <div class="dashcoltxt">
                <div class="boxtextheading pull-left">Matched</div>
                <div class="pull-right btn bordergreen"><a href="<?=PROJECT_URL?>/?page=return_gstr2_view_reconcile_invoices&returnmonth=<?php echo $returnmonth; ?>&invoice_status=match">View Records</a></div>
                <div class="clear height10"></div>
                <div class="txtnumber col-md-4 col-sm-4">
					<?php if(!empty($matchFinalData)) { echo count($matchFinalData); } else { echo "0"; } ?><br><span>RECORDS</span><br>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-3 col-sm-3 col-xs-12">
            <div class="lightblue col-text">
              <div class="dashcoltxt">
                <div class="boxtextheading pull-left">Missing</div>
                <div class="pull-right btn borderblue"><a href="<?=PROJECT_URL?>/?page=return_gstr2_view_reconcile_invoices&returnmonth=<?php echo $returnmonth; ?>&invoice_status=missing">View Records</a></div>
                <div class="clear height10"></div>
                <div class="txtnumber col-md-4 col-sm-4">
					<?php if(!empty($missingFinalData)) { echo count($missingFinalData); } else { echo "0"; } ?><br><span>RECORDS</span><br>
                </div>
                <div class="txtnumber col-md-4 col-sm-4">
					<?php if(isset($missingAddressedData[0]['addressed'])) { echo $missingAddressedData[0]['addressed']; } else { echo "0"; } ?><br><span>ADDRESSED</span><br>
                </div>
                <div class="txtnumber redtxt col-md-4 col-sm-4">
					<?php if(isset($missingPendingData[0]['pending'])) { echo $missingPendingData[0]['pending']; } else { echo "0"; } ?><br><span>PENDING</span><br>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-3 col-sm-3 col-xs-12">
            <div class="lightyellowbg col-text">
              <div class="dashcoltxt">
                <div class="boxtextheading pull-left">Additional</div>
                <div class="pull-right btn borderbrown"><a href="<?=PROJECT_URL?>/?page=return_gstr2_view_reconcile_invoices&returnmonth=<?php echo $returnmonth; ?>&invoice_status=additional">View Records</a></div>
                <div class="clear height10"></div>
                <div class="txtnumber col-md-4 col-sm-4">
					<?php if(!empty($additionalFinalData)) { echo count($additionalFinalData); } else { echo "0"; } ?><br><span>RECORDS</span><br>
                </div>
                <div class="txtnumber col-md-4 col-sm-4">
					<?php if(isset($additionalAddressedData[0]['addressed'])) { echo $additionalAddressedData[0]['addressed']; } else { echo "0"; } ?><br><span>ADDRESSED</span><br>
                </div>
                <div class="txtnumber redtxt col-md-4 col-sm-4">
					<?php if(isset($additionalPendingData[0]['pending'])) { echo $additionalPendingData[0]['pending']; } else { echo "0"; } ?><br><span>PENDING</span><br>
                </div>
              </div>
            </div>
          </div> 
          <div class="col-md-3 col-sm-3 col-xs-12">
            <div class="pinkbg col-text">
              <div class="dashcoltxt">
                <div class="boxtextheading pull-left">Mismatch</div>
                <div class="pull-right btn borderred"><a href="<?=PROJECT_URL?>/?page=return_gstr2_view_reconcile_invoices&returnmonth=<?php echo $returnmonth; ?>&invoice_status=mismatch">View Records</a></div>
                <div class="clear height10"></div>
                 
                <div class="txtnumber col-md-4 col-sm-4">
					<?php if(!empty($mismatchFinalData)) { echo count($mismatchFinalData); } else { echo "0"; } ?><br><span>RECORDS</span><br>
                </div>
                <div class="txtnumber col-md-4 col-sm-4">
					<?php if(isset($mismatchAddressedData[0]['addressed'])) { echo $mismatchAddressedData[0]['addressed']; } else { echo "0"; } ?><br><span>ADDRESSED</span><br>
                </div>
               
                <div class="txtnumber redtxt col-md-4 col-sm-4">
					<?php if(isset($mismatchPendingData[0]['pending'])) { echo $mismatchPendingData[0]['pending']; } else { echo "0"; } ?><br><span>PENDING</span><br>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="clear"></div>
<script>
$(document).ready(function () {
	$('#returnmonth').on('change', function () {
		window.location.href = "<?php echo PROJECT_URL; ?>/?page=return_gstr2_reconcile&returnmonth=" + $(this).val();
	});
});
</script>