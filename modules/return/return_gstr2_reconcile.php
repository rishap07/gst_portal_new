<?php
$obj_gstr2 = new gstr2();
$obj_json = new json();

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

if (isset($_POST['reconcileData']) && $_POST['reconcileData'] == 'Auto Populate Data') {
	
	/* missing data */
	$missingDataResult = $obj_json->getGSTR2ADownlodedMissingData($_SESSION['user_detail']['user_id'], $returnmonth, false);

	$finalInsertArray = array();
	foreach($missingDataResult as $missingResult) {

		$missingArray['reference_number'] = $missingResult['reference_number'];
		$missingArray['invoice_date'] = $missingResult['invoice_date'];
		$missingArray['invoice_total_value'] = $missingResult['invoice_total_value'];
		$missingArray['total_taxable_subtotal'] = '';
		$missingArray['invoice_status'] = 'missing';
		$missingArray['company_gstin_number'] = $missingResult['company_gstin_number'];
		$missingArray['total_cgst_amount'] = '';
		$missingArray['total_sgst_amount'] = '';
		$missingArray['total_igst_amount'] = '';
		$missingArray['total_cess_amount'] = '';
		$missingArray['added_by'] = $_SESSION['user_detail']['user_id'];
		$missingArray['added_date'] = date('Y-m-d H:i:s');
		$missingArray['updated_date'] = date('Y-m-d H:i:s');
		$missingArray['financial_month'] = $missingResult['financial_month'];

		array_push($finalInsertArray, $missingArray);
	}

	/* additional data */
	$additionalDataResult = $obj_json->getGSTR2ADownlodedAdditionalData($_SESSION['user_detail']['user_id'], $returnmonth, false);
	foreach($additionalDataResult as $additionalResult) {

		$additionalArray['reference_number']=$additionalResult['reference_number'];
		$additionalArray['invoice_date']=$additionalResult['invoice_date'];
		$additionalArray['invoice_total_value']=$additionalResult['invoice_total_value'];
		$additionalArray['total_taxable_subtotal']='';
		$additionalArray['invoice_status']='additional';
		$additionalArray['company_gstin_number']=$additionalResult['company_gstin_number'];
		$additionalArray['total_cgst_amount']='';
		$additionalArray['total_sgst_amount']='';
		$additionalArray['total_igst_amount']='';
		$additionalArray['total_cess_amount']='';
		$additionalArray['added_by']=$_SESSION['user_detail']['user_id'];
		$additionalArray['added_date']=date('Y-m-d',time());
		$additionalArray['updated_date']=date('Y-m-d',time());
		$additionalArray['financial_month']=$additionalResult['financial_month'];
		
		array_push($finalInsertArray, $additionalArray);
	}

	/* matchmis data */
	$MatchMisData = $obj_json->getGSTR2ADownlodedMatchMisData($_SESSION['user_detail']['user_id'], $returnmonth, false);
	foreach($MatchMisData as $mmdata) {

		/* consolidate data from purchase invoice */
		$queryPurchase ='Select 
                                pi.reference_number,
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
                                                ELSE (SELECT reference_number FROM '.$obj_gstr2->getTableName('client_purchase_invoice').' WHERE purchase_invoice_id = pi.corresponding_document_number)
                                        END
                                ) AS nt_num,
                                pi.corresponding_document_date as nt_dt,
                                GROUP_CONCAT(DISTINCT CAST(pii.consolidate_rate AS UNSIGNED) ORDER BY CAST(pii.consolidate_rate AS UNSIGNED) ASC SEPARATOR ",") as rate,
                                pi.supply_place as pos,
                                DATE_FORMAT(pi.invoice_date,"%Y-%m") as financial_month 
                                from '.$obj_gstr2->getTableName('client_purchase_invoice').' as pi 
                                INNER JOIN '.$obj_gstr2->getTableName('client_purchase_invoice_item').' as pii 
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
					GROUP_CONCAT(DISTINCT CAST(di.rate AS UNSIGNED) ORDER BY CAST(di.rate AS UNSIGNED) ASC SEPARATOR ",") as rate,
					di.pos,
					di.financial_month 
					from '.$obj_gstr2->getTableName('client_reconcile_purchase_invoice1').' as di 
					where 1=1 
					and di.added_by = '.$_SESSION['user_detail']['user_id'].' 
					and di.reference_number = "'.$mmdata['reference_number'].'" 
					and di.company_gstin_number = "'.$mmdata['ctin'].'" 
					and DATE_FORMAT(di.invoice_date,"%Y-%m") = "'.$returnmonth.'"';
		
		$resultDownPurchase = $obj_json->get_results($queryDownPurchase, false);
		//echo "<pre>";print_r($resultDownPurchase[0]['invoice_date']);die;
		if(
			$resultPurchase[0]['invoice_total_value'] == $resultDownPurchase[0]['invoice_total_value'] && 
			$resultPurchase[0]['total_taxable_subtotal'] == $resultDownPurchase[0]['total_taxable_subtotal'] &&
			$resultPurchase[0]['total_cgst_amount'] == $resultDownPurchase[0]['total_cgst_amount'] &&
			$resultPurchase[0]['total_sgst_amount'] == $resultDownPurchase[0]['total_sgst_amount'] &&
			$resultPurchase[0]['total_igst_amount'] == $resultDownPurchase[0]['total_igst_amount'] &&
			$resultPurchase[0]['total_cess_amount'] == $resultDownPurchase[0]['total_cess_amount'] &&
			$resultPurchase[0]['invoice_date'] == $resultDownPurchase[0]['invoice_date']
		){
			//match
			$matchtArray['reference_number']	=	$resultPurchase[0]['reference_number'];
			$matchtArray['invoice_date']	=	$resultPurchase[0]['invoice_date'];
			$matchtArray['invoice_total_value']	=	$resultPurchase[0]['invoice_total_value'];
			$matchtArray['total_taxable_subtotal']=$resultPurchase[0]['total_taxable_subtotal'];
			$matchtArray['invoice_status']='match';
			$matchtArray['company_gstin_number']=$resultPurchase[0]['company_gstin_number'];
			$matchtArray['total_cgst_amount']=$resultPurchase[0]['total_cgst_amount'];
			$matchtArray['total_sgst_amount']=$resultPurchase[0]['total_sgst_amount'];
			$matchtArray['total_igst_amount']=$resultPurchase[0]['total_igst_amount'];
			$matchtArray['total_cess_amount']=$resultPurchase[0]['total_cess_amount'];
			$matchtArray['added_by']=$_SESSION['user_detail']['user_id'];
			$matchtArray['added_date']=date('Y-m-d',time());
			$matchtArray['updated_date']=date('Y-m-d',time());
			$matchtArray['financial_month']=$resultPurchase[0]['financial_month'];
			
			array_push($finalInsertArray,$matchtArray);
			
		}else
		{
			//mimatch
			$mimatchtArray['reference_number'] = $resultPurchase[0]['reference_number'];
			$mimatchtArray['invoice_date'] = $resultPurchase[0]['invoice_date'].','.$resultDownPurchase[0]['invoice_date'];
			$mimatchtArray['invoice_total_value'] = $resultPurchase[0]['invoice_total_value'].','.$resultDownPurchase[0]['invoice_total_value'];
			$mimatchtArray['total_taxable_subtotal'] = $resultPurchase[0]['total_taxable_subtotal'].','.$resultDownPurchase[0]['total_taxable_subtotal'];
			$mimatchtArray['invoice_status'] = 'mismatch';
			$mimatchtArray['company_gstin_number'] = $resultPurchase[0]['company_gstin_number'].','.$resultDownPurchase[0]['company_gstin_number'];
			$mimatchtArray['total_cgst_amount'] = $resultPurchase[0]['total_cgst_amount'].','.$resultDownPurchase[0]['total_cgst_amount'];;
			$mimatchtArray['total_sgst_amount'] = $resultPurchase[0]['total_sgst_amount'].','.$resultDownPurchase[0]['total_sgst_amount'];;
			$mimatchtArray['total_igst_amount'] = $resultPurchase[0]['total_igst_amount'].','.$resultDownPurchase[0]['total_igst_amount'];;
			$mimatchtArray['total_cess_amount'] = $resultPurchase[0]['total_cess_amount'].','.$resultDownPurchase[0]['total_cess_amount'];;
			$mimatchtArray['added_by'] = $_SESSION['user_detail']['user_id'];
			$mimatchtArray['added_date'] = date('Y-m-d',time());
			$mimatchtArray['updated_date'] = date('Y-m-d',time());
			$mimatchtArray['financial_month'] = $resultPurchase[0]['financial_month'];
			
			array_push($finalInsertArray,$mimatchtArray);
		}
		//echo "<hr style = 'color:#000000'>";
	}

    $dataConditionArray['added_by']=$_SESSION['user_detail']['user_id'];;
	$dataConditionArray['financial_month']=$returnmonth;
	$obj_json->deletData($obj_json->getTableName('gstr2_reconcile_final'), $dataConditionArray);
  	$obj_json->insertMultiple($obj_json->getTableName('gstr2_reconcile_final'), $finalInsertArray);

 }
	$missingFinalData	= $obj_json->getGst2ReconcileFinalQuery($_SESSION['user_detail']['user_id'],$returnmonth,'missing');
	$mismatchFinalData	= $obj_json->getGst2ReconcileFinalQuery($_SESSION['user_detail']['user_id'],$returnmonth,'mismatch');
	$additionalFinalData= $obj_json->getGst2ReconcileFinalQuery($_SESSION['user_detail']['user_id'],$returnmonth,'additional');
	$matchFinalData=	  $obj_json->getGst2ReconcileFinalQuery($_SESSION['user_detail']['user_id'],$returnmonth,'match');
	
 ?>

<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="col-md-6 col-sm-6 col-xs-12 heading">
      <h1>GSTR-2 Filing</h1>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-12 text-right breadcrumb-nav"> <a href="#">Home</a> <i class="fa fa-angle-right" aria-hidden="true"></i> <a href="#">File Return</a> <i class="fa fa-angle-right" aria-hidden="true"></i> <span class="active">GSTR-2 Filing</span> </div>
    <div class="whitebg formboxcontainer">
      <form method='post' name="autoPopulateReconcile" id="autoPopulateReconcile">
        <input type="submit" name="reconcileData" id="reconcileData" class="btn btn-success" value="Auto Populate Data">
      </form>
      <div class="pull-right">
        <form method='post' name='gstr2ReconcileForm' id="gstr2ReconcileForm">
          Month Of Return
          <select class="dateselectbox" id="returnmonth" name="returnmonth">
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
      <div class="tab col-md-12 col-sm-12 col-xs-12">
        <?php include(PROJECT_ROOT . "/modules/return/include/tab.php");?>
      </div>
      <div class="clear"></div>
      <div class="row gstr2-reconcile">
        <div class="row reconciliation">
          <div class="col-md-3 col-sm-3 col-xs-12">
            <div class="lightgreen col-text">
              <div class="dashcoltxt">
                <div class="boxtextheading pull-left">Matched</div>
                <div class="pull-right btn bordergreen"><a href="<?=PROJECT_URL?>/?page=return_gstr2_view_reconcile_invoices&returnmonth=<?=$returnmonth;?>&invoice_status=match">View Records</a></div>
                <div class="clear height10"></div>
                <div class="txtnumber col-md-4 col-sm-4">
                  <?php if(!empty($matchFinalData)){echo count($matchFinalData);}else{echo '0';} ?>
                  <br>
                  <span>RECORDS</span><br>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-3 col-sm-3 col-xs-12">
            <div class="lightblue col-text">
              <div class="dashcoltxt">
                <div class="boxtextheading pull-left">Missing</div>
                <div class="pull-right btn borderblue"><a href="<?=PROJECT_URL?>/?page=return_gstr2_view_reconcile_invoices&returnmonth=<?=$returnmonth;?>&invoice_status=missing">View Records</a></div>
                <div class="clear height10"></div>
                <div class="txtnumber col-md-4 col-sm-4">
                  <?php if(!empty($missingFinalData)){echo count($missingFinalData);}else{echo '0';} ?>
                  <br>
                  <span>RECORDS</span><br>
                </div>
                <div class="txtnumber col-md-4 col-sm-4">0<br>
                  <span>ADDRESSED</span><br>
                </div>
                <div class="txtnumber redtxt col-md-4 col-sm-4">0<br>
                  <span>PENDING</span><br>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-3 col-sm-3 col-xs-12">
            <div class="lightyellowbg col-text">
              <div class="dashcoltxt">
                <div class="boxtextheading pull-left">Additional</div>
                <div class="pull-right btn borderbrown"><a href="<?=PROJECT_URL?>/?page=return_gstr2_view_reconcile_invoices&returnmonth=<?=$returnmonth;?>&invoice_status=additional">View Records</a></div>
                <div class="clear height10"></div>
                <div class="txtnumber col-md-4 col-sm-4">
                  <?php if(!empty($additionalFinalData)){echo count($additionalFinalData);}else{echo '0';} ?>
                  <br>
                  <span>RECORDS</span><br>
                </div>
                <div class="txtnumber col-md-4 col-sm-4">0<br>
                  <span>ADDRESSED</span><br>
                </div>
                <div class="txtnumber redtxt col-md-4 col-sm-4">0<br>
                  <span>PENDING</span><br>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-3 col-sm-3 col-xs-12">
            <div class="pinkbg col-text">
              <div class="dashcoltxt">
                <div class="boxtextheading pull-left">Mismatch</div>
                <div class="pull-right btn borderred"><a href="<?=PROJECT_URL?>/?page=return_gstr2_view_reconcile_invoices&returnmonth=<?=$returnmonth;?>&invoice_status=mismatch">View Records</a></div>
                <div class="clear height10"></div>
                <div class="txtnumber col-md-4 col-sm-4">
                  <?php if(!empty($mismatchFinalData)){echo count($mismatchFinalData);}else{echo 0;}?>
                  <br>
                  <span>RECORDS</span><br>
                </div>
                <div class="txtnumber col-md-4 col-sm-4">0<br>
                  <span>ADDRESSED</span><br>
                </div>
                <div class="txtnumber redtxt col-md-4 col-sm-4">0<br>
                  <span>PENDING</span><br>
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