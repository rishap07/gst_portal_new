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
?>

<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">

		<div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>GSTR1 Summary</h1></div>

		<div class="whitebg formboxcontainer">
			
			<?php $obj_tally->showErrorMessage(); ?>
			<?php $obj_tally->showSuccessMessge(); ?>
			<?php $obj_tally->unsetMessage(); ?>

			<div class="clear"></div>

			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="pull-right">
					<input type="button" value="<?php echo 'Update Document Summary'; ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=return_document_summary&returnmonth=".$_REQUEST["return_month"]; ?>';" class="btn btn-success" />
					<input type="button" value="<?php echo 'Update HSN Summary'; ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=return_hsnwise_summary&returnmonth=".$_REQUEST["return_month"]; ?>';" class="btn btn-success" />
					<input type="button" value="<?php echo 'Update Nil Summary'; ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=return_nil_summary&returnmonth=".$_REQUEST["return_month"]; ?>';" class="btn btn-success" />
				</div>
				<div class="clear"></div>
				<hr>
			</div>
			<div class="clear"></div>

			<div class="rgtdatetxt">
				<div class="pull-right" style="min-width:380px;">
					<form class="form-horizontal" name="return-month-form" id="return-month-form" method="get">
						<div class="form-group">
							<label class="control-label col-sm-4" for="return_month">Month Of Return:</label>
							<div class="col-sm-8">
								<select class="form-control" id="return_month" name="return_month">
									<?php foreach($returnPeriod as $monthYear) { ?>
										<option <?php if($return_month == $monthYear->return_period) { echo 'selected="selected"'; } ?> value="<?php echo $monthYear->return_period; ?>"><?php echo $monthYear->return_period; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
					</form>
				</div>
				<div class="clear"></div>
			</div>

			<div class="adminformbx">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="invoice-itemtable" id="mainTable1">
					<thead>
						<tr>
							<th>Type Of Invoice</th>
							<th style="text-align:right">Total Invoices</th>
							<th style="text-align:right">Taxable Amount (<i class="fa fa-inr"></i>)</th>
							<th style="text-align:right">Tax Amount (<i class="fa fa-inr"></i>)</th>
							<th style="text-align:right">Total Amount (<i class="fa fa-inr"></i>)</th>
							<th style="text-align:center">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php $rowsB2B = $obj_tally->get_results("SELECT count(id) as totalB2B FROM ".$obj_tally->getTableName('gstr1_return_summary')." WHERE 1=1 AND return_period = '".$return_month."' AND invoice_nature = 'b2b' AND added_by='".$obj_tally->sanitize($_SESSION['user_detail']['user_id'])."' group by invoice_number"); ?>
						<?php //$totalB2BData = $obj_tally->get_row("SELECT (SELECT sum(invoice_value) FROM (SELECT invoice_value FROM ".$obj_tally->getTableName('gstr1_return_summary')." WHERE 1=1 AND return_period = '".$return_month."' AND invoice_nature = 'b2b' AND added_by='".$obj_tally->sanitize($_SESSION['user_detail']['user_id'])."' group by invoice_number) as invoice_value) as invoice_value, sum(taxable_value) as taxable_value, sum(cgst_amount) as cgst_amount, sum(sgst_amount) as sgst_amount, sum(igst_amount) as igst_amount, sum(cess_amount) as cess_amount FROM ".$obj_tally->getTableName('gstr1_return_summary')." WHERE 1=1 AND return_period = '".$return_month."' AND invoice_nature = 'b2b' AND added_by='".$obj_tally->sanitize($_SESSION['user_detail']['user_id'])."'"); ?>
						<?php $totalB2BData = $obj_tally->get_row("SELECT (SELECT sum(invoice_value) FROM (SELECT invoice_value FROM ".$obj_tally->getTableName('gstr1_return_summary')." WHERE 1=1 AND return_period = '".$return_month."' AND invoice_nature = 'b2b' AND added_by='".$obj_tally->sanitize($_SESSION['user_detail']['user_id'])."' group by invoice_number) as invoice_value) as invoice_value, sum(taxable_value) as taxable_value, sum(cgst_amount) as cgst_amount, sum(sgst_amount) as sgst_amount, sum(igst_amount) as igst_amount, sum(cess_amount) as cess_amount FROM ".$obj_tally->getTableName('gstr1_return_summary')." WHERE 1=1 AND return_period = '".$return_month."' AND invoice_nature = 'b2b' AND added_by='".$obj_tally->sanitize($_SESSION['user_detail']['user_id'])."'"); ?>
						<tr>
							<td>B2B</td>
							<td align="right"><?php echo count($rowsB2B); ?></td>
							<td align="right"><?php if($totalB2BData->taxable_value != NULL) { echo $totalB2BData->taxable_value; } else { echo "0"; } ?></td>
							<td align="right"><?php echo $totalB2BData->cgst_amount + $totalB2BData->sgst_amount + $totalB2BData->igst_amount + $totalB2BData->cess_amount; ?></td>
							<td align="right"><?php if($totalB2BData->invoice_value != NULL) { echo $totalB2BData->invoice_value; } else { echo "0"; } ?></td>
							<td align="center"><a href="<?php echo PROJECT_URL."/?page=tally_view_invoices&return_month=".$return_month."&invoice_nature=b2b"; ?>" target="_blank" class="btn btn-xs btn-success">View</a></td>
						</tr>
						<?php $rowsB2CL = $obj_tally->get_results("SELECT count(id) as totalB2CL FROM ".$obj_tally->getTableName('gstr1_return_summary')." WHERE 1=1 AND return_period = '".$return_month."' AND invoice_nature = 'b2cl' AND added_by='".$obj_tally->sanitize($_SESSION['user_detail']['user_id'])."' group by invoice_number"); ?>
						<?php $totalB2CLData = $obj_tally->get_row("SELECT (SELECT sum(invoice_value) FROM (SELECT invoice_value FROM ".$obj_tally->getTableName('gstr1_return_summary')." WHERE 1=1 AND return_period = '".$return_month."' AND invoice_nature = 'b2cl' AND added_by='".$obj_tally->sanitize($_SESSION['user_detail']['user_id'])."' group by invoice_number) as invoice_value) as invoice_value, sum(taxable_value) as taxable_value, sum(cgst_amount) as cgst_amount, sum(sgst_amount) as sgst_amount, sum(igst_amount) as igst_amount, sum(cess_amount) as cess_amount FROM ".$obj_tally->getTableName('gstr1_return_summary')." WHERE 1=1 AND return_period = '".$return_month."' AND invoice_nature = 'b2cl' AND added_by='".$obj_tally->sanitize($_SESSION['user_detail']['user_id'])."'"); ?>
						<tr>
							<td>B2C Large</td>
							<td align="right"><?php echo count($rowsB2CL); ?></td>
							<td align="right"><?php if($totalB2CLData->taxable_value != NULL) { echo $totalB2CLData->taxable_value; } else { echo "0"; } ?></td>
							<td align="right"><?php echo $totalB2CLData->cgst_amount + $totalB2CLData->sgst_amount + $totalB2CLData->igst_amount + $totalB2CLData->cess_amount; ?></td>
							<td align="right"><?php if($totalB2CLData->invoice_value != NULL) { echo $totalB2CLData->invoice_value; } else { echo "0"; } ?></td>
							<td align="center"><a href="<?php echo PROJECT_URL."/?page=tally_view_invoices&return_month=".$return_month."&invoice_nature=b2cl"; ?>" target="_blank" class="btn btn-xs btn-success">View</a></td>
						</tr>
						<?php $rowsB2CS = $obj_tally->get_results("SELECT count(id) as totalB2CS FROM ".$obj_tally->getTableName('gstr1_return_summary')." WHERE 1=1 AND return_period = '".$return_month."' AND invoice_nature = 'b2cs' AND added_by='".$obj_tally->sanitize($_SESSION['user_detail']['user_id'])."' group by place_of_supply"); ?>
						<?php $totalB2CSData = $obj_tally->get_row("SELECT sum(invoice_value) as invoice_value, sum(taxable_value) as taxable_value, sum(cgst_amount) as cgst_amount, sum(sgst_amount) as sgst_amount, sum(igst_amount) as igst_amount, sum(cess_amount) as cess_amount FROM ".$obj_tally->getTableName('gstr1_return_summary')." WHERE 1=1 AND return_period = '".$return_month."' AND invoice_nature = 'b2cs' AND added_by='".$obj_tally->sanitize($_SESSION['user_detail']['user_id'])."'"); ?>
						<tr>
							<td>B2C Small</td>
							<td align="right"><?php echo count($rowsB2CS); ?></td>
							<td align="right"><?php if($totalB2CSData->taxable_value != NULL) { echo $totalB2CSData->taxable_value; } else { echo "0"; } ?></td>
							<td align="right"><?php echo $totalB2CSData->cgst_amount + $totalB2CSData->sgst_amount + $totalB2CSData->igst_amount + $totalB2CSData->cess_amount; ?></td>
							<td align="right"><?php if($totalB2CSData->invoice_value != NULL) { echo $totalB2CSData->invoice_value; } else { echo "0"; } ?></td>
							<td align="center"><a href="<?php echo PROJECT_URL."/?page=tally_view_invoices&return_month=".$return_month."&invoice_nature=b2cs"; ?>" target="_blank" class="btn btn-xs btn-success">View</a></td>
						</tr>
						<?php $rowsCDNR = $obj_tally->get_results("SELECT count(id) as totalCDNR FROM ".$obj_tally->getTableName('gstr1_return_summary')." WHERE 1=1 AND return_period = '".$return_month."' AND invoice_nature = 'cdnr' AND added_by='".$obj_tally->sanitize($_SESSION['user_detail']['user_id'])."' group by invoice_number"); ?>
						<?php $totalCDNRData = $obj_tally->get_row("SELECT (SELECT sum(invoice_value) FROM (SELECT invoice_value FROM ".$obj_tally->getTableName('gstr1_return_summary')." WHERE 1=1 AND return_period = '".$return_month."' AND invoice_nature = 'cdnr' AND added_by='".$obj_tally->sanitize($_SESSION['user_detail']['user_id'])."' group by invoice_number) as invoice_value) as invoice_value, sum(taxable_value) as taxable_value, sum(cgst_amount) as cgst_amount, sum(sgst_amount) as sgst_amount, sum(igst_amount) as igst_amount, sum(cess_amount) as cess_amount FROM ".$obj_tally->getTableName('gstr1_return_summary')." WHERE 1=1 AND return_period = '".$return_month."' AND invoice_nature = 'cdnr' AND added_by='".$obj_tally->sanitize($_SESSION['user_detail']['user_id'])."'"); ?>
						<tr>
							<td>Credit Debit Notes Registered</td>
							<td align="right"><?php echo count($rowsCDNR); ?></td>
							<td align="right"><?php if($totalCDNRData->taxable_value != NULL) { echo $totalCDNRData->taxable_value; } else { echo "0"; } ?></td>
							<td align="right"><?php echo $totalCDNRData->cgst_amount + $totalCDNRData->sgst_amount + $totalCDNRData->igst_amount + $totalCDNRData->cess_amount; ?></td>
							<td align="right"><?php if($totalCDNRData->invoice_value != NULL) { echo $totalCDNRData->invoice_value; } else { echo "0"; } ?></td>
							<td align="center"><a href="<?php echo PROJECT_URL."/?page=tally_view_invoices&return_month=".$return_month."&invoice_nature=cdnr"; ?>" target="_blank" class="btn btn-xs btn-success">View</a></td>
						</tr>
						<?php $rowsCDNUR = $obj_tally->get_results("SELECT count(id) as totalCDNUR FROM ".$obj_tally->getTableName('gstr1_return_summary')." WHERE 1=1 AND return_period = '".$return_month."' AND invoice_nature = 'cdnur' AND added_by='".$obj_tally->sanitize($_SESSION['user_detail']['user_id'])."' group by invoice_number"); ?>
						<?php $totalCDNURData = $obj_tally->get_row("SELECT (SELECT sum(invoice_value) FROM (SELECT invoice_value FROM ".$obj_tally->getTableName('gstr1_return_summary')." WHERE 1=1 AND return_period = '".$return_month."' AND invoice_nature = 'cdnur' AND added_by='".$obj_tally->sanitize($_SESSION['user_detail']['user_id'])."' group by invoice_number) as invoice_value) as invoice_value, sum(taxable_value) as taxable_value, sum(cgst_amount) as cgst_amount, sum(sgst_amount) as sgst_amount, sum(igst_amount) as igst_amount, sum(cess_amount) as cess_amount FROM ".$obj_tally->getTableName('gstr1_return_summary')." WHERE 1=1 AND return_period = '".$return_month."' AND invoice_nature = 'cdnur' AND added_by='".$obj_tally->sanitize($_SESSION['user_detail']['user_id'])."'"); ?>
						<tr>
							<td>Credit Debit Notes Unregistered</td>
							<td align="right"><?php echo count($rowsCDNUR); ?></td>
							<td align="right"><?php if($totalCDNURData->taxable_value != NULL) { echo $totalCDNURData->taxable_value; } else { echo "0"; } ?></td>
							<td align="right"><?php echo $totalCDNURData->cgst_amount + $totalCDNURData->sgst_amount + $totalCDNURData->igst_amount + $totalCDNURData->cess_amount; ?></td>
							<td align="right"><?php if($totalCDNURData->invoice_value != NULL) { echo $totalCDNURData->invoice_value; } else { echo "0"; } ?></td>
							<td align="center"><a href="<?php echo PROJECT_URL."/?page=tally_view_invoices&return_month=".$return_month."&invoice_nature=cdnur"; ?>" target="_blank" class="btn btn-xs btn-success">View</a></td>
						</tr>
						<?php $rowsEXP = $obj_tally->get_results("SELECT count(id) as totalEXP FROM ".$obj_tally->getTableName('gstr1_return_summary')." WHERE 1=1 AND return_period = '".$return_month."' AND invoice_nature = 'exp' AND added_by='".$obj_tally->sanitize($_SESSION['user_detail']['user_id'])."' group by invoice_number"); ?>
						<?php $totalEXPData = $obj_tally->get_row("SELECT (SELECT sum(invoice_value) FROM (SELECT invoice_value FROM ".$obj_tally->getTableName('gstr1_return_summary')." WHERE 1=1 AND return_period = '".$return_month."' AND invoice_nature = 'exp' AND added_by='".$obj_tally->sanitize($_SESSION['user_detail']['user_id'])."' group by invoice_number) as invoice_value) as invoice_value, sum(taxable_value) as taxable_value, sum(cgst_amount) as cgst_amount, sum(sgst_amount) as sgst_amount, sum(igst_amount) as igst_amount, sum(cess_amount) as cess_amount FROM ".$obj_tally->getTableName('gstr1_return_summary')." WHERE 1=1 AND return_period = '".$return_month."' AND invoice_nature = 'exp' AND added_by='".$obj_tally->sanitize($_SESSION['user_detail']['user_id'])."'"); ?>
						<tr>
							<td>Export</td>
							<td align="right"><?php echo count($rowsEXP); ?></td>
							<td align="right"><?php if($totalEXPData->taxable_value != NULL) { echo $totalEXPData->taxable_value; } else { echo "0"; } ?></td>
							<td align="right"><?php echo $totalEXPData->cgst_amount + $totalEXPData->sgst_amount + $totalEXPData->igst_amount + $totalEXPData->cess_amount; ?></td>							
							<td align="right"><?php if($totalEXPData->invoice_value != NULL) { echo $totalEXPData->invoice_value; } else { echo "0"; } ?></td>
							<td align="center"><a href="<?php echo PROJECT_URL."/?page=tally_view_invoices&return_month=".$return_month."&invoice_nature=exp"; ?>" target="_blank" class="btn btn-xs btn-success">View</a></td>
						</tr>
						<?php $rowsAT = $obj_tally->get_results("SELECT count(id) as totalAT FROM ".$obj_tally->getTableName('gstr1_return_summary')." WHERE 1=1 AND return_period = '".$return_month."' AND invoice_nature = 'at' AND added_by='".$obj_tally->sanitize($_SESSION['user_detail']['user_id'])."' group by place_of_supply"); ?>
						<?php $totalATData = $obj_tally->get_row("SELECT sum(invoice_value) as invoice_value, sum(taxable_value) as taxable_value, sum(cgst_amount) as cgst_amount, sum(sgst_amount) as sgst_amount, sum(igst_amount) as igst_amount, sum(cess_amount) as cess_amount FROM ".$obj_tally->getTableName('gstr1_return_summary')." WHERE 1=1 AND return_period = '".$return_month."' AND invoice_nature = 'at' AND added_by='".$obj_tally->sanitize($_SESSION['user_detail']['user_id'])."'"); ?>
						<tr>
							<td>Advance Tax</td>
							<td align="right"><?php echo count($rowsAT); ?></td>
							<td align="right"><?php if($totalATData->taxable_value != NULL) { echo $totalATData->taxable_value; } else { echo "0"; } ?></td>
							<td align="right"><?php echo $totalATData->cgst_amount + $totalATData->sgst_amount + $totalATData->igst_amount + $totalATData->cess_amount; ?></td>
							<td align="right"><?php if($totalATData->invoice_value != NULL) { echo $totalATData->invoice_value; } else { echo "0"; } ?></td>
							<td align="center"><a href="<?php echo PROJECT_URL."/?page=tally_view_invoices&return_month=".$return_month."&invoice_nature=at"; ?>" target="_blank" class="btn btn-xs btn-success">View</a></td>
						</tr>
						<?php $rowsATADJ = $obj_tally->get_results("SELECT count(id) as totalATADJ FROM ".$obj_tally->getTableName('gstr1_return_summary')." WHERE 1=1 AND return_period = '".$return_month."' AND invoice_nature = 'atadj' AND added_by='".$obj_tally->sanitize($_SESSION['user_detail']['user_id'])."' group by place_of_supply"); ?>
						<?php $totalATADJData = $obj_tally->get_row("SELECT sum(invoice_value) as invoice_value, sum(taxable_value) as taxable_value, sum(cgst_amount) as cgst_amount, sum(sgst_amount) as sgst_amount, sum(igst_amount) as igst_amount, sum(cess_amount) as cess_amount FROM ".$obj_tally->getTableName('gstr1_return_summary')." WHERE 1=1 AND return_period = '".$return_month."' AND invoice_nature = 'atadj' AND added_by='".$obj_tally->sanitize($_SESSION['user_detail']['user_id'])."'"); ?>
						<tr>
							<td>Advance Tax Adjustment</td>
							<td align="right"><?php echo count($rowsATADJ); ?></td>
							<td align="right"><?php if($totalATADJData->taxable_value != NULL) { echo $totalATADJData->taxable_value; } else { echo "0"; } ?></td>
							<td align="right"><?php echo $totalATADJData->cgst_amount + $totalATADJData->sgst_amount + $totalATADJData->igst_amount + $totalATADJData->cess_amount; ?></td>
							<td align="right"><?php if($totalATADJData->invoice_value != NULL) { echo $totalATADJData->invoice_value; } else { echo "0"; } ?></td>
							<td align="center"><a href="<?php echo PROJECT_URL."/?page=tally_view_invoices&return_month=".$return_month."&invoice_nature=atadj"; ?>" target="_blank" class="btn btn-xs btn-success">View</a></td>
						</tr>
					</tbody>
				</table>
			</div>

		</div>
	</div>
</div>
<!--========================sidemenu over=========================-->
<script>
    $(document).ready(function () {
        $('#submit').click(function () {
            var mesg = {};
            if (vali.validate(mesg,'return-month-form')) {
				return true;
            }
            return false;
        });

		$('#return_month').change(function () {
			window.location.href = "<?php echo PROJECT_URL; ?>/?page=tally_return_summary&return_month=" + $(this).val();
		});
    });
</script>