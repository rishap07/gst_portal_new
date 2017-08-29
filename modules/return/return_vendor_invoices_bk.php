<?php
	$obj_gstr2 = new gstr2();
	$dataCurrentUserArr = $obj_gstr2->getUserDetailsById( $obj_gstr2->sanitize($_SESSION['user_detail']['user_id']) );
	
	if(isset($_POST['gstr2Download']) && $_POST['gstr2Download'] === "Download" && isset($_POST['flag']) && strtoupper($_POST['flag']) === "DOWNLOAD") {

		if(!isset($_SERVER['HTTP_REFERER']) || empty($_SERVER['HTTP_REFERER'])){
			$obj_gstr2->setError('Invalid access to files');
		} else {
			$obj_gstr2->downloadGSTR2();
		}
	}

	$returnmonth= date('Y-m');
	if(isset($_REQUEST['returnmonth']) && $_REQUEST['returnmonth'] != '') {
		$returnmonth= $_REQUEST['returnmonth'];
	}
?>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
	<div class="col-md-12 col-sm-12 col-xs-12">

		<div class="col-md-6 col-sm-6 col-xs-12 heading"><h1>GSTR-2 Filing</h1></div>
		<div class="col-md-6 col-sm-6 col-xs-12 text-right breadcrumb-nav">
			<a href="#">Home</a>
			<i class="fa fa-angle-right" aria-hidden="true"></i>
			<a href="#">File Return</a>
			<i class="fa fa-angle-right" aria-hidden="true"></i>
			<span class="active">GSTR-2 Filing</span>
		</div>

		<div class="whitebg formboxcontainer">

			<div class="pull-right rgtdatetxt">
				<form method='post' name='form3' id="form3">
					Month Of Return 
					<?php
						$dataQuery = "SELECT DATE_FORMAT(invoice_date,'%Y-%m') AS niceDate FROM gst_client_invoice group by nicedate";
						$dataRes = $obj_gstr2->get_results($dataQuery);
						
						if (!empty($dataRes)) { ?>
							<select class="dateselectbox" id="returnmonth" name="returnmonth">
								<?php foreach ($dataRes as $dataRe) { ?>
									<option value="<?php echo $dataRe->niceDate; ?>" <?php if ($dataRe->niceDate == $returnmonth) { echo 'selected'; } ?>><?php echo $dataRe->niceDate; ?></option>
								<?php } ?>
							</select>
					<?php } else { ?>
					
						<select class="dateselectbox" id="returnmonth" name="returnmonth">
							<option>July 2017</option>
						</select>
					<?php } ?>
				</form>
			</div>

			<div class="col-md-12 col-sm-12 col-xs-12 tablistnav padleft0">
<?php
                              include(PROJECT_ROOT."/modules/return/include/tab.php");
               ?>
			</div>
			<div class="clear"></div>
			
			<?php $obj_gstr2->showErrorMessage(); ?>
			<?php $obj_gstr2->showSuccessMessge(); ?>
			<?php $obj_gstr2->unsetMessage(); ?>
			<div class="clear"></div>

			<div class="text-right">
				<?php
				$dataReturns = $obj_gstr2->get_results("select * from " . TAB_PREFIX . "return where return_month='" . $returnmonth . "' and client_id='" . $_SESSION['user_detail']['user_id'] . "' and status='3' and type='gstr2'");
				if (!empty($dataReturns)) {
					?>
					<div id="sucmsg" style="background-color:#DBEDDF;border-radius:4px;padding:8px 35px 8px 14px;text-shadow:0 1px 0 rgba(255, 255, 255, 0.5);margin-bottom:18px;border-color:#D1E8DA;color:#39A25F;"><i class="fa fa-check"></i> <b>Success:</b> GSTR2 is Already Filed</div>
					<?php
				} else {
					?>
					<form name="gstr2-download" id="gstr2-download" method="post">
						<input type="hidden" name="gstr2ReturnMonth" value="<?php if(isset($_GET['returnmonth'])) { echo $_GET['returnmonth']; } ?>">
						<input type="hidden" name="flag" value="download">
						<button type="submit" name="gstr2Download" id="gstr2Download" value="Download" class="btngreen btn"><i class="fa fa-cloud-download" aria-hidden="true"></i> Download GSTR-2A</button>
					</form>
					<?php
				}
				?>
				
			</div>

			<div class="invoice-types">
				<div class="invoice-types__heading">Types</div>
				<div class="invoice-types__content">
					<label for="invoice-types__invoice"><input type="radio" id="invoice-types__invoice" name="invoice_type" value="invoice" class="type" <?php if(isset($_POST['invoice_type']) && $_POST['invoice_type']=='invoice'){ echo 'checked=""';}else{echo 'checked=""';}?>>Invoice</label>
					<label for="invoice-types__cdn"><input type="radio" id="invoice-types__cdn" name="invoice_type" value="cdn" class="type" <?php if(isset($_POST['invoice_type']) && $_POST['invoice_type']=='cdn') echo 'checked=""';?>>Credit/Debit Note</label>
				</div>
				<div class="clear"></div>

				<table width="100%" border="0" cellspacing="0" cellpadding="0"  class="table table-striped invoice-filter-table" id="mainTable1">

					<thead>
						<tr>
							<th align='left'>Total Transactions</th>
							<th align='left'>Total IGST</th>
							<th align='left'>Total SGST</th>
							<th align='left'>Total CGST</th>
							<th align='left'>Total Cess</th>
							<th align='left'>Total Amount</th>
						</tr>
						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
					</thead>
				</table>
			
				<div class="tableresponsive">

					<table  class="table tablecontent tablecontent2">
						<thead>
							<tr>
								<th>Date</th>
								<th>Id</th>
								<th>GSTIN</th>
								<th class="text-right">TaxableAmt</th>
								<th class="text-right">TotalTax</th>
								<th class="text-right">TotalAmount</th>
								<th class="text-right">Type</th>
								<th class="text-center">Status</th>
							</tr>
						</thead>
						<tbody>
							<?php
								$invCount= 0;
								$igstTotal= 0;
								$cgstTotal= 0;
								$sgstTotal= 0;
								$cessTotal= 0;
								$invTotal=0;
								$flag=0;

								$b2bItemquery = "select i.is_gstr1_uploaded,i.invoice_date,i.invoice_id,i.reference_number,i.invoice_total_value,i.billing_gstin_number,i.billing_name,sum(it.cgst_amount) as cgst_amount,sum(it.sgst_amount) as sgst_amount,sum(it.igst_amount) as igst_amount,sum(it.cess_amount) as cess_amount from " . $obj_gstr2->getTableName('client_invoice') . " i inner join " . $obj_gstr2->getTableName("client_invoice_item") . " it on i.invoice_id=it.invoice_id  where i.invoice_nature='salesinvoice' and i.billing_gstin_number='".$dataCurrentUserArr['data']->kyc->gstin_number."' and i.status='1' and i.is_canceled='0' and i.is_deleted='0' and i.is_gstr1_uploaded != '0' AND i.is_gstr2_downloaded = '1' AND i.invoice_date like '%" . $returnmonth . "%' group by i.invoice_id order by i.invoice_date desc";
								$b2bItemData = $obj_gstr2->get_results($b2bItemquery);

								if(!empty($b2bItemData)) {

									$flag=1;
									foreach($b2bItemData as $b2bItem)
									{
										$totaltax = (isset($b2bItem->igst_amount)) ? $b2bItem->igst_amount : '0' + (isset($b2bItem->cgst_amount)) ? $b2bItem->cgst_amount : '0' + (isset($b2bItem->sgst_amount)) ? $b2bItem->sgst_amount : '0' + (isset($b2bItem->cess_amount)) ? $b2bItem->cess_amount : '0';
										$type ='';
										if($b2bItem->billing_gstin_number=='' && $b2bItem->invoice_total_value > 25000)
										{
											$type = 'B2CL';
										}
										else if($b2bItem->billing_gstin_number=='' && $b2bItem->invoice_total_value <= 25000)
										{
											$type = 'B2CS';
										}
										else if($b2bItem->billing_gstin_number!='')
										{
											$type = 'B2B';
										}
										$type = ($b2bItem->billing_gstin_number=='') ? 'B2C' : 'B2B';
										?>
										<tr>
											<td align='left'><?php echo $b2bItem->invoice_date;?></td>
											<td align='left'><?php echo $b2bItem->reference_number;?></td>
											<td align='left'><?php echo $b2bItem->billing_gstin_number;?></td>
											<td style='text-align:right'><?php echo $b2bItem->invoice_total_value;?></td>
											<td style='text-align:right'><?php echo $totaltax?></td>
											<td style='text-align:right'><?php echo $b2bItem->invoice_total_value;?></td>
											<td align='center'><?php echo $type; ?></td>
											<td align='center'>Downloaded</td>
										</tr>
										<?php
									}
								} else {
									echo '<tr><td colspan="10">No Invoices </td></tr>';
								}
							?>
						</tbody>
					</table>

				</div>
			</div> 

		</div>

		<div class="clear height40"></div>
	</div>
	<div class="clear"></div>
</div>
<script>
	$(document).ready(function () {
		$('#returnmonth').on('change', function () {
			window.location.href = "<?php echo PROJECT_URL; ?>/?page=return_vendor_invoices&returnmonth=" + $(this).val();
		});
	});
</script>