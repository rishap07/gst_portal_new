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

	$dataArray = array();
	$reconcileDataArray = array();

	/* purchase invoice data */
	$resultTempPurchase = $resultPurchase = $obj_json->getGSTR2APurchaseInvoiceData($_SESSION['user_detail']['user_id'], $returnmonth, false);
	
	/* downloaded invoice data */
	$resultTempDownloadPurchase = $resultDownloadPurchase = $obj_json->getGSTR2ADownlodedInvoiceData($_SESSION['user_detail']['user_id'], $returnmonth, false);

	foreach($resultTempPurchase as $key => $purchaseInvoice) {

		$flag = 0;
		foreach($resultTempDownloadPurchase as $dkey => $gstr2DownlodedInvoice) {

			if($purchaseInvoice['reference_number'] == $gstr2DownlodedInvoice['reference_number'] && $purchaseInvoice['company_gstin_number'] == $gstr2DownlodedInvoice['company_gstin_number']) {

				if(
					$purchaseInvoice['invoice_total_value'] == $gstr2DownlodedInvoice['invoice_total_value'] && 
					$purchaseInvoice['total_taxable_subtotal'] == $gstr2DownlodedInvoice['total_taxable_subtotal'] && 
					$purchaseInvoice['total_cgst_amount'] == $gstr2DownlodedInvoice['total_cgst_amount'] && 
					$purchaseInvoice['total_sgst_amount'] == $gstr2DownlodedInvoice['total_sgst_amount'] && 
					$purchaseInvoice['total_igst_amount'] == $gstr2DownlodedInvoice['total_igst_amount'] && 
					$purchaseInvoice['total_cess_amount'] == $gstr2DownlodedInvoice['total_cess_amount'] && 
					$purchaseInvoice['pos'] == $gstr2DownlodedInvoice['pos'] && 
					$purchaseInvoice['invoice_date'] == $gstr2DownlodedInvoice['invoice_date'] && 
					$purchaseInvoice['rate'] == $gstr2DownlodedInvoice['rate']
				){
					//match
					if($gstr2DownlodedInvoice['type'] == "CDN" && $gstr2DownlodedInvoice['ntty'] == "C") {
						$dataArray['invoice_type'] = "creditnote";
					} else if($gstr2DownlodedInvoice['type'] == "CDN" && $gstr2DownlodedInvoice['ntty'] == "D") {
						$dataArray['invoice_type'] = "debitnote";
					} else {
						$dataArray['invoice_type'] = "taxinvoice";
					}

					$dataArray['reference_number'] = $gstr2DownlodedInvoice['reference_number'];
					$dataArray['invoice_date'] = $gstr2DownlodedInvoice['invoice_date'];
					$dataArray['invoice_total_value'] = $gstr2DownlodedInvoice['invoice_total_value'];
					$dataArray['total_taxable_subtotal'] = $gstr2DownlodedInvoice['total_taxable_subtotal'];
					$dataArray['company_gstin_number'] = $gstr2DownlodedInvoice['company_gstin_number'];
					$dataArray['total_cgst_amount'] = $gstr2DownlodedInvoice['total_cgst_amount'];
					$dataArray['total_sgst_amount'] = $gstr2DownlodedInvoice['total_sgst_amount'];
					$dataArray['total_igst_amount'] = $gstr2DownlodedInvoice['total_igst_amount'];
					$dataArray['total_cess_amount'] = $gstr2DownlodedInvoice['total_cess_amount'];
					$dataArray['nt_num'] = $gstr2DownlodedInvoice['nt_num'];
					$dataArray['nt_dt'] = $gstr2DownlodedInvoice['nt_dt'];
					$dataArray['p_gst'] = $gstr2DownlodedInvoice['p_gst'];
					$dataArray['rate'] = $gstr2DownlodedInvoice['rate'];
					$dataArray['pos'] = $gstr2DownlodedInvoice['pos'];
					$dataArray['advance_adjustment'] = 0;
					$dataArray['receipt_voucher_number'] = 0;
					$dataArray['advance_amount'] = 0.00;
					$dataArray['inv_typ'] = $gstr2DownlodedInvoice['inv_typ'];
					$dataArray['import_supply_meant'] = "withpayment";
					$dataArray['import_bill_number'] = "";
					$dataArray['import_bill_date'] = "";
					$dataArray['import_bill_port_code'] = "";
					$dataArray['ntty'] = $gstr2DownlodedInvoice['ntty'];
					$dataArray['rsn'] = $gstr2DownlodedInvoice['rsn'];
					$dataArray['reverse_charge'] = $gstr2DownlodedInvoice['reverse_charge'];
					$dataArray['reconciliation_status'] = "accept";
					$dataArray['invoice_status'] = 'match';
					$dataArray['financial_month'] = $gstr2DownlodedInvoice['financial_month'];
					$dataArray['status'] = '1';
					$dataArray['added_by'] = $_SESSION['user_detail']['user_id'];
					$dataArray['added_date'] = date('Y-m-d H:i:s');
				} else {
					//mismatch
					if($gstr2DownlodedInvoice['type'] == "CDN" && $gstr2DownlodedInvoice['ntty'] == "C") {
						$dataArray['invoice_type'] = "creditnote";
					} else if($gstr2DownlodedInvoice['type'] == "CDN" && $gstr2DownlodedInvoice['ntty'] == "D") {
						$dataArray['invoice_type'] = "debitnote";
					} else {
						$dataArray['invoice_type'] = "taxinvoice";
					}

					$dataArray['reference_number'] = $gstr2DownlodedInvoice['reference_number'];
					$dataArray['invoice_date'] = $gstr2DownlodedInvoice['invoice_date'];
					$dataArray['invoice_total_value'] = $gstr2DownlodedInvoice['invoice_total_value'];
					$dataArray['total_taxable_subtotal'] = $gstr2DownlodedInvoice['total_taxable_subtotal'];
					$dataArray['company_gstin_number'] = $gstr2DownlodedInvoice['company_gstin_number'];
					$dataArray['total_cgst_amount'] = $gstr2DownlodedInvoice['total_cgst_amount'];
					$dataArray['total_sgst_amount'] = $gstr2DownlodedInvoice['total_sgst_amount'];
					$dataArray['total_igst_amount'] = $gstr2DownlodedInvoice['total_igst_amount'];
					$dataArray['total_cess_amount'] = $gstr2DownlodedInvoice['total_cess_amount'];
					$dataArray['nt_num'] = $gstr2DownlodedInvoice['nt_num'];
					$dataArray['nt_dt'] = $gstr2DownlodedInvoice['nt_dt'];
					$dataArray['p_gst'] = $gstr2DownlodedInvoice['p_gst'];
					$dataArray['rate'] = $gstr2DownlodedInvoice['rate'];
					$dataArray['pos'] = $gstr2DownlodedInvoice['pos'];
					$dataArray['advance_adjustment'] = 0;
					$dataArray['receipt_voucher_number'] = 0;
					$dataArray['advance_amount'] = 0.00;
					$dataArray['inv_typ'] = $gstr2DownlodedInvoice['inv_typ'];
					$dataArray['import_supply_meant'] = "withpayment";
					$dataArray['import_bill_number'] = "";
					$dataArray['import_bill_date'] = "";
					$dataArray['import_bill_port_code'] = "";
					$dataArray['ntty'] = $gstr2DownlodedInvoice['ntty'];
					$dataArray['rsn'] = $gstr2DownlodedInvoice['rsn'];
					$dataArray['reverse_charge'] = $gstr2DownlodedInvoice['reverse_charge'];
					$dataArray['reconciliation_status'] = "pending";
					$dataArray['invoice_status'] = 'mismatch';
					$dataArray['financial_month'] = $gstr2DownlodedInvoice['financial_month'];
					$dataArray['status'] = '1';
					$dataArray['added_by'] = $_SESSION['user_detail']['user_id'];
					$dataArray['added_date'] = date('Y-m-d H:i:s');
				}

				array_push($reconcileDataArray, $dataArray);
				$flag = 1;
				unset($resultTempDownloadPurchase[$dkey]);
			}
		}

		if($flag == 0) {
			//missing
			$dataArray['invoice_type'] = $purchaseInvoice['invoice_type'];
			$dataArray['reference_number'] = $purchaseInvoice['reference_number'];
			$dataArray['invoice_date'] = $purchaseInvoice['invoice_date'];
			$dataArray['invoice_total_value'] = $purchaseInvoice['invoice_total_value'];
			$dataArray['total_taxable_subtotal'] = $purchaseInvoice['total_taxable_subtotal'];
			$dataArray['company_gstin_number'] = $purchaseInvoice['company_gstin_number'];
			$dataArray['total_cgst_amount'] = $purchaseInvoice['total_cgst_amount'];
			$dataArray['total_sgst_amount'] = $purchaseInvoice['total_sgst_amount'];
			$dataArray['total_igst_amount'] = $purchaseInvoice['total_igst_amount'];
			$dataArray['total_cess_amount'] = $purchaseInvoice['total_cess_amount'];
			$dataArray['nt_num'] = $purchaseInvoice['nt_num'];
			$dataArray['nt_dt'] = $purchaseInvoice['nt_dt'];
			$dataArray['p_gst'] = 'N';
			$dataArray['rate'] = $purchaseInvoice['rate'];
			$dataArray['pos'] = $purchaseInvoice['pos'];
			$dataArray['advance_adjustment'] = $purchaseInvoice['advance_adjustment'];
			$dataArray['receipt_voucher_number'] = $purchaseInvoice['receipt_voucher_number'];
			$dataArray['advance_amount'] = $purchaseInvoice['advance_amount'];

			if($purchaseInvoice['invoice_type'] == "taxinvoice" && !empty($purchaseInvoice['company_gstin_number'])) {
				$dataArray['inv_typ'] = "R";
			} else if($purchaseInvoice['invoice_type'] == "sezunitinvoice" && $purchaseInvoice['import_supply_meant'] == "withpayment") {
				$dataArray['inv_typ'] = 'SEWP';
			} else if($purchaseInvoice['invoice_type'] == "sezunitinvoice" && $purchaseInvoice['import_supply_meant'] == "withoutpayment") {
				$dataArray['inv_typ'] = 'SEWOP';
			} else if($purchaseInvoice['invoice_type'] == "deemedimportinvoice") {
				$dataArray['inv_typ'] = 'DE';
			} else if($purchaseInvoice['invoice_type'] == "importinvoice") {
				$dataArray['inv_typ'] = 'IMP';
			} else {
				$dataArray['inv_typ'] = '';
			}

			$dataArray['import_supply_meant'] = $purchaseInvoice['import_supply_meant'];
			$dataArray['import_bill_number'] = $purchaseInvoice['import_bill_number'];
			$dataArray['import_bill_date'] = $purchaseInvoice['import_bill_date'];
			$dataArray['import_bill_port_code'] = $purchaseInvoice['import_bill_port_code'];

			if($purchaseInvoice['invoice_type'] == "creditnote") {
				$dataArray['ntty'] = 'C';
			} else if($purchaseInvoice['invoice_type'] == "debitnote") {
				$dataArray['ntty'] = 'D';
			} else if($purchaseInvoice['invoice_type'] == "refundvoucherinvoice") {
				$dataArray['ntty'] = 'R';
			} else {
				$dataArray['ntty'] = '';
			}

			$dataArray['rsn'] = $purchaseInvoice['reason_issuing_document'];
			$dataArray['reverse_charge'] = $purchaseInvoice['reverse_charge'];
			$dataArray['reconciliation_status'] = "pending";
			$dataArray['invoice_status'] = 'missing';
			$dataArray['financial_month'] = $purchaseInvoice['financial_month'];
			$dataArray['status'] = '1';
			$dataArray['added_by'] = $_SESSION['user_detail']['user_id'];
			$dataArray['added_date'] = date('Y-m-d H:i:s');
			array_push($reconcileDataArray, $dataArray);
		}
	}

	foreach($resultTempDownloadPurchase as $dtkey => $gstr2TempDownlodedInvoice) {

		//additional
		if($gstr2TempDownlodedInvoice['type'] == "CDN" && $gstr2TempDownlodedInvoice['ntty'] == "C") {
			$dataArray['invoice_type'] = "creditnote";
		} else if($gstr2TempDownlodedInvoice['type'] == "CDN" && $gstr2TempDownlodedInvoice['ntty'] == "D") {
			$dataArray['invoice_type'] = "debitnote";
		} else {
			$dataArray['invoice_type'] = "taxinvoice";
		}

		$dataArray['reference_number'] = $gstr2TempDownlodedInvoice['reference_number'];
		$dataArray['invoice_date'] = $gstr2TempDownlodedInvoice['invoice_date'];
		$dataArray['invoice_total_value'] = $gstr2TempDownlodedInvoice['invoice_total_value'];
		$dataArray['total_taxable_subtotal'] = $gstr2TempDownlodedInvoice['total_taxable_subtotal'];
		$dataArray['company_gstin_number'] = $gstr2TempDownlodedInvoice['company_gstin_number'];
		$dataArray['total_cgst_amount'] = $gstr2TempDownlodedInvoice['total_cgst_amount'];
		$dataArray['total_sgst_amount'] = $gstr2TempDownlodedInvoice['total_sgst_amount'];
		$dataArray['total_igst_amount'] = $gstr2TempDownlodedInvoice['total_igst_amount'];
		$dataArray['total_cess_amount'] = $gstr2TempDownlodedInvoice['total_cess_amount'];
		$dataArray['nt_num'] = $gstr2TempDownlodedInvoice['nt_num'];
		$dataArray['nt_dt'] = $gstr2TempDownlodedInvoice['nt_dt'];
		$dataArray['p_gst'] = $gstr2TempDownlodedInvoice['p_gst'];
		$dataArray['pos'] = $gstr2TempDownlodedInvoice['pos'];
		$dataArray['rate'] = $gstr2TempDownlodedInvoice['rate'];
		$dataArray['advance_adjustment'] = 0;
		$dataArray['receipt_voucher_number'] = 0;
		$dataArray['advance_amount'] = 0.00;
		$dataArray['inv_typ'] = $gstr2TempDownlodedInvoice['inv_typ'];
		$dataArray['import_supply_meant'] = "withpayment";
		$dataArray['import_bill_number'] = "";
		$dataArray['import_bill_date'] = "";
		$dataArray['import_bill_port_code'] = "";
		$dataArray['ntty'] = $gstr2TempDownlodedInvoice['ntty'];
		$dataArray['rsn'] = $gstr2TempDownlodedInvoice['rsn'];
		$dataArray['reverse_charge'] = $gstr2TempDownlodedInvoice['reverse_charge'];
		$dataArray['reconciliation_status'] = "pending";
		$dataArray['invoice_status'] = 'additional';
		$dataArray['financial_month'] = $gstr2TempDownlodedInvoice['financial_month'];
		$dataArray['status'] = '1';
		$dataArray['added_by'] = $_SESSION['user_detail']['user_id'];
		$dataArray['added_date'] = date('Y-m-d H:i:s');
		array_push($reconcileDataArray, $dataArray);
		unset($resultTempDownloadPurchase[$dtkey]);
	}

    $dataConditionArray['added_by'] = $_SESSION['user_detail']['user_id'];
	$dataConditionArray['financial_month'] = $returnmonth;
	$obj_json->deletData($obj_json->getTableName('gstr2_reconcile_final'), $dataConditionArray);
  	$obj_json->insertMultiple($obj_json->getTableName('gstr2_reconcile_final'), $reconcileDataArray);
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