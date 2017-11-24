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

if(isset($_POST['gstr2ReturnMonth']) && isset($_POST['flag']) && strtoupper($_POST['flag']) === "DOWNLOAD") {
	
	if(!isset($_SERVER['HTTP_REFERER']) || empty($_SERVER['HTTP_REFERER'])){
		$obj_gstr2->setError('Invalid access to files');
	} else {
		$obj_gstr2->downloadGSTR2();
	}
}
?>

<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
	<div class="col-md-12 col-sm-12 col-xs-12">

		<div class="col-md-6 col-sm-6 col-xs-12 heading"><h1>GSTR-2 Filing</h1></div>
		<div class="col-md-6 col-sm-6 col-xs-12 text-right breadcrumb-nav">
			<a href="#">Home</a> <i class="fa fa-angle-right" aria-hidden="true"></i>
			<a href="#">File Return</a> <i class="fa fa-angle-right" aria-hidden="true"></i>
			<span class="active">GSTR-2 Filing</span>
		</div>

		<div class="whitebg formboxcontainer">
			<div class="pull-right">
				<form method='post' name='gstr2PurchaseSummaryForm' id="gstr2PurchaseSummaryForm">
					Month Of Return
					<select class="monthselectbox" id="returnmonth" name="returnmonth">
						<?php for($year = 2017; $year <= date('Y'); $year++) { ?>
							<?php for($month = 1; $month <= 12; $month++) { ?>

								<?php if($year >= 2017 && $month >= 7) { ?>
									<option <?php if($returnmonth == date( "Y-m", strtotime($year."-".$month) )) { echo 'selected="selected"'; } ?> value="<?php echo date( "Y-m", strtotime($year."-".$month) ); ?>"><?php echo date( "M-Y", strtotime($year."-".$month) ); ?></option>
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

			<div class="text-right">
				<?php $dataReturns = $obj_gstr2->get_results("select * from " . TAB_PREFIX . "return where return_month='" . $returnmonth . "' and client_id='" . $_SESSION['user_detail']['user_id'] . "' and status='3' and type='gstr2'"); ?>
				<?php if (!empty($dataReturns)) { ?>
					<div id="sucmsg" style="background-color:#DBEDDF;border-radius:4px;padding:8px 35px 8px 14px;text-shadow:0 1px 0 rgba(255, 255, 255, 0.5);margin-bottom:18px;border-color:#D1E8DA;color:#39A25F;"><i class="fa fa-check"></i> <b>Success:</b> GSTR2 is Already Filed</div>
				<?php } else { ?>
					<form name="form4" id="gstr2-download" method="post">
						<input type="hidden" name="gstr2ReturnMonth" value="<?php if(isset($_GET['returnmonth'])) { echo $_GET['returnmonth']; } ?>">
						<input type="hidden" name="flag" value="download">
						<button type="submit" name="gstr2Download" id="gstr2Download" value="Download" class="btngreen btn"><i class="fa fa-cloud-download" aria-hidden="true"></i> Download GSTR-2A</button>
					</form>
				<?php } ?>
			</div>

			<?php
				$responseCDN = $obj_gstr2->checkUserInvoices($_SESSION['user_detail']['user_id'], $returnmonth,'CDN');
				$responseB2B = $obj_gstr2->checkUserInvoices($_SESSION['user_detail']['user_id'], $returnmonth,'B2B');
				$responseISD = $obj_gstr2->checkUserInvoices($_SESSION['user_detail']['user_id'], $returnmonth,'ISD');
				$responseTableB2B = $responseTableCDN = $responseTableISD = '';

				if(!empty($responseB2B)) {

					$responseTableB2B .= '<div class="table-responsive">';
						$responseTableB2B .= '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="invoice-itemtable" id="mainTable1">';
							$responseTableB2B .= '<thead>';
								$responseTableB2B .= '<tr>';
									$responseTableB2B .= '<th>Sr.No</th>';
									$responseTableB2B .= '<th style="text-align:center">Invoice number</th>';
									$responseTableB2B .= '<th style="text-align:center">Ctin</th>';
									$responseTableB2B .= '<th style="text-align:center">Pos </th>';
									$responseTableB2B .= '<th style="text-align:center">Item </th>';
									$responseTableB2B .= '<th style="text-align:center">Invoice type</th>';
									$responseTableB2B .= '<th style="text-align:center">Invoice date</th>';
									$responseTableB2B .= '<th style="text-align:center">Tax value ( <i class="fa fa-inr"></i> )</th>';
									$responseTableB2B .= '<th style="text-align:center">Rate</th>';
									$responseTableB2B .= '<th style="text-align:center">Iamt ( <i class="fa fa-inr"></i> )</th>';
									$responseTableB2B .= '<th style="text-align:center">Samt ( <i class="fa fa-inr"></i> )</th>';
									$responseTableB2B .= '<th style="text-align:center">Camt ( <i class="fa fa-inr"></i> )</th>';
									$responseTableB2B .= '<th style="text-align:center">Csamt ( <i class="fa fa-inr"></i> )</th>';
									$responseTableB2B .= '<th style="text-align:center">Value ( <i class="fa fa-inr"></i> )</th>';
									$responseTableB2B .= '<th style="text-align:center">Rchrg</th>';
									$responseTableB2B .= '<th style="text-align:center">Filing Status</th>';
								$responseTableB2B .= '</tr>';
							$responseTableB2B .= '</thead>';
							$responseTableB2B .= '<tbody>';
							
							$i1=1;
							$i=1;
							$temp = '';

							foreach ($responseB2B as $key3 => $value) {
								
								if($temp!='' && $temp!=$value->reference_number) {
									$i=1;
								}
								
								$idt = isset($value->invoice_date) ? date('d-m-Y', strtotime($value->invoice_date)) : '';
								
								$responseTableB2B .= '<tr>';
									$responseTableB2B .= '<td align="center">'.$i1++.'</td>';
									$responseTableB2B .= '<td align="center">'.$value->reference_number.'</td>';
									$responseTableB2B .= '<td align="center">'.$value->company_gstin_number.'</td>';
									$responseTableB2B .= '<td align="center">'.$value->pos.'</td>';
									$responseTableB2B .= '<td align="center">'.$i++.'</td>';
									$responseTableB2B .= '<td align="center">'.$value->inv_typ.'</td>';
									$responseTableB2B .= '<td align="center">'.$idt.'</td>';
									$responseTableB2B .= '<td align="center">'.$value->total_taxable_subtotal.'</td>';
									$responseTableB2B .= '<td align="center">'.$value->rate.'</td>';
									$responseTableB2B .= '<td align="center">'.$value->total_igst_amount.'</td>';
									$responseTableB2B .= '<td align="center">'.$value->total_sgst_amount.'</td>';
									$responseTableB2B .= '<td align="center">'.$value->total_cgst_amount.'</td>';
									$responseTableB2B .= '<td align="center">'.$value->total_cess_amount.'</td>';
									$responseTableB2B .= '<td align="right">'.$value->invoice_total_value.'</td>';
									$responseTableB2B .= '<td align="center">'.$value->rchrg.'</td>';
									$responseTableB2B .= '<td align="center">'.$value->cfs.'</td>';
								$responseTableB2B .= '</tr>';

								$temp = $value->reference_number;
							}

							$responseTableB2B .= '</tbody>';
						$responseTableB2B .= '</table>';
					$responseTableB2B .= '</div>';

					echo $responseTableB2B;
				}

				if(!empty($responseCDN)) {
					
					$responseTableCDN .= '<div class="clear height20"></div>';
					$responseTableCDN .= '<div class="table-responsive">';
						$responseTableCDN .= '<table width="80%" border="0" cellspacing="0" cellpadding="0" class="invoice-itemtable" id="mainTable1">';
							$responseTableCDN .= '<thead>';
								$responseTableCDN .= '<tr>';
									$responseTableCDN .= '<th>Sr.No</th>';
									$responseTableCDN .= '<th style="text-align:center">Credit/Debit Note Number</th>';
									$responseTableCDN .= '<th style="text-align:center">Credit/Debit Note Date</th>';
									$responseTableCDN .= '<th style="text-align:center">Ctin </th>';
									$responseTableCDN .= '<th style="text-align:center">Invoice Number</th>';
									$responseTableCDN .= '<th style="text-align:center">Invoice Date</th>';
									$responseTableCDN .= '<th style="text-align:center">Item </th>';
									$responseTableCDN .= '<th style="text-align:center">Pgst</th>';
									$responseTableCDN .= '<th style="text-align:center">Txval ( <i class="fa fa-inr"></i> )</th>';
									$responseTableCDN .= '<th style="text-align:center">Rate</th>';
									$responseTableCDN .= '<th style="text-align:center">Iamt ( <i class="fa fa-inr"></i> )</th>';
									$responseTableCDN .= '<th style="text-align:center">Samt ( <i class="fa fa-inr"></i> )</th>';
									$responseTableCDN .= '<th style="text-align:center">Camt ( <i class="fa fa-inr"></i> )</th>';
									$responseTableCDN .= '<th style="text-align:center">Csamt ( <i class="fa fa-inr"></i> )</th>';
									$responseTableCDN .= '<th style="text-align:center">Val ( <i class="fa fa-inr"></i> )</th>';
									$responseTableCDN .= '<th style="text-align:center">Rsn </th>';
									$responseTableCDN .= '<th style="text-align:center">Ntty</th>';
									$responseTableCDN .= '<th style="text-align:center">Filing Status</th>';
								$responseTableCDN .= '</tr>';
							$responseTableCDN .= '</thead>';
							$responseTableCDN .= '<tbody>';

							$j=1;
							$temp='';
							$i=1;

							foreach ($responseCDN as $key3 => $value) {
								//$obj_gstr2->pr($value->itms);
								if($temp!='' && $temp=!$value->reference_number) {
									$i=1;
								}

								$idt = isset($value->invoice_date) ? date('d-m-Y', strtotime($value->invoice_date)) : '';
								$nt_dt = isset($value->nt_dt) ? date('d-m-Y', strtotime($value->nt_dt)) : '';

								$responseTableCDN .= '<tr>';
									$responseTableCDN .= '<td align="center">'.$j++.'</td>';
									$responseTableCDN .= '<td align="center">'.$value->reference_number.'</td>';
									$responseTableCDN .= '<td align="center">'.$idt.'</td>';
									$responseTableCDN .= '<td align="center">'.$value->company_gstin_number.'</td>';
									$responseTableCDN .= '<td align="center">'.$value->nt_num.'</td>';
									$responseTableCDN .= '<td align="center">'.$nt_dt.'</td>';
									$responseTableCDN .= '<td align="center">'.$value->itms.'</td>';
									$responseTableCDN .= '<td align="center">'.$value->p_gst.'</td>';
									$responseTableCDN .= '<td align="center">'.$value->total_taxable_subtotal.'</td>';
									$responseTableCDN .= '<td align="center">'.$value->rate.'</td>';
									$responseTableCDN .= '<td align="center">'.$value->total_igst_amount.'</td>';
									$responseTableCDN .= '<td align="center">'.$value->total_sgst_amount.'</td>';
									$responseTableCDN .= '<td align="center">'.$value->total_cgst_amount.'</td>';
									$responseTableCDN .= '<td align="center">'.$value->total_cess_amount.'</td>';
									$responseTableCDN .= '<td align="right">'.$value->invoice_total_value.'</td>';
									$responseTableCDN .= '<td align="center">'.$value->rsn.'</td>';
									$responseTableCDN .= '<td align="center">'.$value->ntty.'</td>';
									$responseTableCDN .= '<td align="center">'.$value->cfs.'</td>';
								$responseTableCDN .= '</tr>';

								$temp = $value->reference_number;
							}
							
							$responseTableCDN .= '</tbody>';
						$responseTableCDN .= '</table>';
					$responseTableCDN .= '</div>';
					
					echo $responseTableCDN;
				}

				if(!empty($responseISD)) {

					$responseTableISD .= '<div class="table-responsive">';
						$responseTableISD .= '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="invoice-itemtable" id="mainTable1">';
							$responseTableISD .= '<thead>';
								$responseTableISD .= '<tr>';
									$responseTableISD .= '<th>Sr.No</th>';
									$responseTableISD .= '<th style="text-align:center">Document Number</th>';
									$responseTableISD .= '<th style="text-align:center">Ctin</th>';
									$responseTableISD .= '<th style="text-align:center">Document Date </th>';
									$responseTableISD .= '<th style="text-align:center">Document Type </th>';
									$responseTableISD .= '<th style="text-align:center">ITC eligible</th>';
									$responseTableISD .= '<th style="text-align:center">Iamt ( <i class="fa fa-inr"></i> )</th>';
									$responseTableISD .= '<th style="text-align:center">Samt ( <i class="fa fa-inr"></i> )</th>';
									$responseTableISD .= '<th style="text-align:center">Camt ( <i class="fa fa-inr"></i> )</th>';
									$responseTableISD .= '<th style="text-align:center">Csamt ( <i class="fa fa-inr"></i> )</th>';
									$responseTableISD .= '<th style="text-align:center">Filing Status</th>';
								$responseTableISD .= '</tr>';
							$responseTableISD .= '</thead>';
							$responseTableISD .= '<tbody>';
							
							$i1=1;
							$i=1;
							$temp = '';

							foreach ($responseISD as $key3 => $value) {
								
								if($temp!='' && $temp!=$value->reference_number) {
									$i=1;
								}
								
								$idt = isset($value->invoice_date) ? date('d-m-Y', strtotime($value->invoice_date)) : '';
								
								$responseTableISD .= '<tr>';
									$responseTableISD .= '<td align="center">'.$i1++.'</td>';
									$responseTableISD .= '<td align="center">'.$value->reference_number.'</td>';
									$responseTableISD .= '<td align="center">'.$value->company_gstin_number.'</td>';
									$responseTableISD .= '<td align="center">'.$idt.'</td>';
									$responseTableISD .= '<td align="center">'.$value->isd_docty.'</td>';
									$responseTableISD .= '<td align="center">'.$value->itc_elg.'</td>';
									
									
									$responseTableISD .= '<td align="center">'.$value->total_igst_amount.'</td>';
									$responseTableISD .= '<td align="center">'.$value->total_sgst_amount.'</td>';
									$responseTableISD .= '<td align="center">'.$value->total_cgst_amount.'</td>';
									$responseTableISD .= '<td align="center">'.$value->total_cess_amount.'</td>';
									$responseTableISD .= '<td align="center">'.$value->cfs.'</td>';
								$responseTableISD .= '</tr>';

								$temp = $value->reference_number;
							}

							$responseTableISD .= '</tbody>';
						$responseTableISD .= '</table>';
					$responseTableISD .= '</div>';

					echo $responseTableISD;
				}
			?>
		</div>
		<div class="clear height40"></div>
	</div>
	<div class="clear"></div>
</div>
<?php $obj_gstr1 = new gstr(); ?>
<?php $obj_gstr1->Gstr2DownloadOtpPopupJs(); ?>
<script>
	$(document).ready(function() {
		$('#returnmonth').on('change', function() {
			window.location.href = "<?php echo PROJECT_URL; ?>/?page=return_vendor_invoices&returnmonth=" + $(this).val();
		});
	});
</script>