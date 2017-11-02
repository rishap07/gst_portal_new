<?php
$obj_json = new json();

if(!$obj_json->can_read('returnfile_list')) {
    $obj_json->setError($obj_json->getValMsg('can_read'));
    $obj_json->redirect(PROJECT_URL."/?page=dashboard");
    exit();
}

$dataCurrentUserArr = $obj_json->getUserDetailsById($obj_json->sanitize($_SESSION['user_detail']['user_id']));

if(isset($_REQUEST['returnmonth']) && isset($_REQUEST['invoice_status']) && !empty($_REQUEST['returnmonth']) && !empty($_REQUEST['invoice_status'])) {
	$returnmonth = $_REQUEST['returnmonth'];
	$invoice_status = $_REQUEST['invoice_status'];
} else {
	$obj_json->setError("Please choose return period and invoice status.");
	$obj_json->redirect(PROJECT_URL."/?page=return_client");
	exit();
}

$finalReconcileData = $obj_json->getGst2ReconcileFinalQuery($returnmonth, $invoice_status);
?>

<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="col-md-12 col-sm-12 col-xs-12 heading">
      <h1>Reconciliation</h1>
    </div>
    <div class="whitebg formboxcontainer">

	  <?php if(strtoupper($invoice_status) == "MATCH") { ?>
		<h3 style="margin-top:0px;margin-bottom:10px;">Matched Invoice</h3>
      <?php } elseif(strtoupper($invoice_status) == "ADDITIONAL") { ?>
		<h3 style="margin-top:0px;margin-bottom:10px;">Additional Invoice</h3>
      <?php } elseif(strtoupper($invoice_status) == "MISSING") { ?>
		<h3 style="margin-top:0px;margin-bottom:10px;">Missing Invoice</h3>
      <?php } elseif(strtoupper($invoice_status) == "MISMATCH") { ?>
		<h3 style="margin-top:0px;margin-bottom:10px;">Mismatch Invoice</h3>
      <?php } ?>
	  
	  <div class="clear"></div>
	  <hr>
	  <div class="clear"></div>
	  
	  <?php $obj_json->showErrorMessage(); ?>
	  <?php $obj_json->showSuccessMessge(); ?>
	  <?php $obj_json->unsetMessage(); ?>

	  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="invoice-itemtable" id="reconcileTable">
        <thead>
          <tr>
            <th class="text-center">#</th>
            <th class="text-center">Supplier GSTIN</th>
            <th class="text-center">POS</th>
            <th class="text-center">Invoice Number</th>
            <th class="text-center">Date</th>
            <th class="text-center">Taxable Amount (<i class="fa fa-inr"></i>)</th>
            <th class="text-center">CGST (<i class="fa fa-inr"></i>)</th>
            <th class="text-center">SGST (<i class="fa fa-inr"></i>)</th>
            <th class="text-center">IGST (<i class="fa fa-inr"></i>)</th>
            <th class="text-center">CESS (<i class="fa fa-inr"></i>)</th>
            <th class="text-center">Total Amount (<i class="fa fa-inr"></i>)</th>
			<th class="text-center">Reverse Charge</th>
            <th class="text-center">Options</th>
          </tr>
        </thead>
        <?php
			if(strtoupper($invoice_status) == "MATCH") {

				$counter = 1;
				if(count($finalReconcileData) > 0) {

					foreach($finalReconcileData as $finaReconcile) { ?>
						<tr data-row-id="<?php echo $finaReconcile['id']; ?>" class="text-center">
							<td><?php echo $counter++; ?></td>
							<td><?php echo $finaReconcile['company_gstin_number']; ?></td>
							<td><?php echo $finaReconcile['pos']; ?></td>
							<td><?php echo $finaReconcile['reference_number']; ?></td>
							<td><?php echo $finaReconcile['invoice_date']; ?></td>
							<td><?php echo $finaReconcile['total_taxable_subtotal']; ?></td>
							<td><?php echo $finaReconcile['total_cgst_amount']; ?></td>
							<td><?php echo $finaReconcile['total_sgst_amount']; ?></td>
							<td><?php echo $finaReconcile['total_igst_amount']; ?></td>
							<td><?php echo $finaReconcile['total_cess_amount']; ?></td>
							<td><?php echo $finaReconcile['invoice_total_value']; ?></td>
							<td><?php echo $finaReconcile['reverse_charge']; ?></td>
							<td><h5><span class="label label-success">Approved</span></h5></td>
						</tr>
					<?php
					}
				}
			}

			if(strtoupper($invoice_status) == "ADDITIONAL") {

				$counter = 1;
				if(count($finalReconcileData) > 0) {

					foreach($finalReconcileData as $finaReconcile) { ?>
						<tr data-row-id="<?php echo $finaReconcile['id']; ?>" class="text-center">
							<td><?php echo $counter++; ?></td>
							<td><?php echo $finaReconcile['company_gstin_number']; ?></td>
							<td><?php echo $finaReconcile['pos']; ?></td>
							<td><?php echo $finaReconcile['reference_number']; ?></td>
							<td><?php echo $finaReconcile['invoice_date']; ?></td>
							<td><?php echo $finaReconcile['total_taxable_subtotal']; ?></td>
							<td><?php echo $finaReconcile['total_cgst_amount']; ?></td>
							<td><?php echo $finaReconcile['total_sgst_amount']; ?></td>
							<td><?php echo $finaReconcile['total_igst_amount']; ?></td>
							<td><?php echo $finaReconcile['total_cess_amount']; ?></td>
							<td><?php echo $finaReconcile['invoice_total_value']; ?></td>
							<td><?php echo $finaReconcile['reverse_charge']; ?></td>
							<td>
								<select name="reconcileStatus" class="reconcileStatus btn btn-default">
									<option value="accept" <?php if($finaReconcile['reconciliation_status'] == 'accept'){ echo 'selected="selected"'; } ?>>Accept</option>
									<option value="pending" <?php if($finaReconcile['reconciliation_status'] == 'pending'){ echo 'selected="selected"'; } ?>>Pending</option>
									<option value="reject" <?php if($finaReconcile['reconciliation_status'] == 'reject'){ echo 'selected="selected"'; } ?>>Reject</option>
								</select>
							</td>
						</tr>
					<?php
					}
				}
			}

			if(strtoupper($invoice_status) == "MISSING") {

				$counter = 1;
				if(count($finalReconcileData) > 0) {

					foreach($finalReconcileData as $finaReconcile) { ?>
						<tr data-row-id="<?php echo $finaReconcile['id']; ?>" class="text-center">
							<td><?php echo $counter++; ?></td>
							<td><?php echo $finaReconcile['company_gstin_number']; ?></td>
							<td><?php echo $finaReconcile['pos']; ?></td>
							<td><?php echo $finaReconcile['reference_number']; ?></td>
							<td><?php echo $finaReconcile['invoice_date']; ?></td>
							<td><?php echo $finaReconcile['total_taxable_subtotal']; ?></td>
							<td><?php echo $finaReconcile['total_cgst_amount']; ?></td>
							<td><?php echo $finaReconcile['total_sgst_amount']; ?></td>
							<td><?php echo $finaReconcile['total_igst_amount']; ?></td>
							<td><?php echo $finaReconcile['total_cess_amount']; ?></td>
							<td><?php echo $finaReconcile['invoice_total_value']; ?></td>
							<td><?php echo $finaReconcile['reverse_charge']; ?></td>
							<td>
								<select name="reconcileStatus" class="reconcileStatus btn btn-default">
									<option value="accept" <?php if($finaReconcile['reconciliation_status'] == 'accept'){ echo 'selected="selected"'; } ?>>Accept</option>
									<option value="pending" <?php if($finaReconcile['reconciliation_status'] == 'pending'){ echo 'selected="selected"'; } ?>>Pending</option>
									<option value="reject" <?php if($finaReconcile['reconciliation_status'] == 'reject'){ echo 'selected="selected"'; } ?>>Reject</option>
								</select>
							</td>
						</tr>
					<?php
					}
				}
			}

			if(strtoupper($invoice_status) == "MISMATCH") {

				$counter = 1;
				if(count($finalReconcileData) > 0) {

					foreach($finalReconcileData as $finaReconcile) { ?>
						<tr data-row-id="<?php echo $finaReconcile['id']; ?>" class="text-center">
							<td><?php echo $counter++; ?></td>
							<td><?php echo $finaReconcile['company_gstin_number']; ?></td>
							<td><?php echo $finaReconcile['pos']; ?></td>
							<td><?php echo $finaReconcile['reference_number']; ?></td>
							<td><?php echo $finaReconcile['invoice_date']; ?></td>
							<td><?php echo $finaReconcile['total_taxable_subtotal']; ?></td>
							<td><?php echo $finaReconcile['total_cgst_amount']; ?></td>
							<td><?php echo $finaReconcile['total_sgst_amount']; ?></td>
							<td><?php echo $finaReconcile['total_igst_amount']; ?></td>
							<td><?php echo $finaReconcile['total_cess_amount']; ?></td>
							<td><?php echo $finaReconcile['invoice_total_value']; ?></td>
							<td><?php echo $finaReconcile['reverse_charge']; ?></td>
							<td>
								<select name="reconcileStatus" class="reconcileStatus btn btn-default">
									<option value="accept" <?php if($finaReconcile['reconciliation_status'] == 'accept'){ echo 'selected="selected"'; } ?>>Accept</option>
									<option value="pending" <?php if($finaReconcile['reconciliation_status'] == 'pending'){ echo 'selected="selected"'; } ?>>Pending</option>
									<option value="reject" <?php if($finaReconcile['reconciliation_status'] == 'reject'){ echo 'selected="selected"'; } ?>>Reject</option>  
								</select>
							</td>
						</tr>
					<?php
					}
				}
			}
		?>
      </table>
    </div>
  </div>
</div>
<script>
	$(document).ready(function () {
		
		$(".reconcileStatus").on("change", function(){
			
			var rowId = $(this).parent().parent().attr("data-row-id");
			var reconcileValue = $(this).val();

			$.ajax({
                data: {reconcileId:rowId, reconcileStatus:reconcileValue, action:"updateReconcile"},
                dataType: 'json',
                type: 'post',
                url: "<?php echo PROJECT_URL; ?>/?ajax=return_gstr2_update_reconcile",
                success: function(response){

                    if(response.status == "success") {
						jAlert(response.message);
                    } else {
						jAlert(response.message);
					}
                }
            });
		});
	});
</script>