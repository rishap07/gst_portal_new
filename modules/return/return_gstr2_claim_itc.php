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

if (isset($_POST['generateGSTR2Summary']) && $_POST['generateGSTR2Summary'] == 'Generate GSTR2 Summary For ITC') {

	$finalInsertArray = array();
	$GSTR2ClaimITCData = $obj_gstr2->generateGSTR2ClaimITCData($returnmonth, false);
	$obj_gstr2->pr($GSTR2ClaimITCData);
}

//$claim_data = $obj_gstr2->generateGSTR2ClaimITC();
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

			<div class="pull-left">
			  <form method='post' name="generateGSTR2Form" id="generateGSTR2Form">
				<input type="submit" name="generateGSTR2Summary" id="generateGSTR2Summary" class="btn btn-success" value="Generate GSTR2 Summary For ITC">
			  </form>
			</div>
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





			<form method="post">
				<div>
				<table width="100%" border="0" cellspacing="0" cellpadding="4" class="table table-striped  tablecontent" >
				<thead>

                <tr>
                    <th class="active">Date</th>
                    <th class="active">Invoice Id</th>
                    <th class="active">Vendor</th>
                    <th class="active">GSTIN</td>
                    <th class="active">Total Tax</td>
                    <th class="active">Category</td>
                    <th class="active">Rate(%)</td>
                    <th class="active">Available</td>
                </tr>
            </thead>
            <tbody>
                <?php 
                if(!empty($claim_data))
                {
                foreach ($claim_data as $data) {?>
 
	   <tr>
    <td><?php echo $data->invoice_date ?></td>
    <td><?php echo $data->reference_number ?></td>
    <td><?php echo $data->company_name ?></td>
    <td><?php echo $data->gstin_number ?></td>
    <td><?php echo $data->taxable_subtotal ?></td> 
     <td>
     <select class="categorey_claim" name="category[]" >
      <option value='inp' <?php if (!empty($data->category) && $data->category == 'inp')  echo 'selected = "selected"'; ?> id="categorey_claim"  data-id=<?php echo $data->id ?>>Input</option>
      <option value="cg" <?php if (!empty($data->category) && $data->category == 'cg')  echo 'selected = "selected"'; ?> id="categorey_claim"   data-id=<?php echo $data->id ?>>Capital Good</option>
      <option value="is" <?php if (!empty($data->category) && $data->category == 'is')  echo 'selected = "selected"'; ?> id="categorey_claim"   data-id=<?php echo $data->id ?>>Input Services</option>
      <option value="ine" <?php if (!empty($data->category) && $data->category == 'ine')  echo 'selected = "selected"'; ?> id="categorey_claim" data-id=<?php echo $data->id ?>>Ineligble</option>
    </select>
     </td>
    <td><input type="number" name="claim_rate[]" id="claim_rate" data-bind=<?php echo $data->taxable_subtotal ?> class="claim_rate" value=<?php echo $data->claim_rate ?> min="0" max="100" step="0.01"></td> 
    <td><div name="claim_value[]" id="claim_value" class="claim_value"></div></td>
    <input type="hidden" name=id[] value=<?php echo $data->reference_number ?>>      
     </tr>
               <?php
                }
                }
               ?>
            </tbody>
        </table>
</div>

<div id="txtPopup" style="display:none">
<table width="100%" border="0" cellspacing="0" cellpadding="4" class="table table-striped  tablecontent" >

            <tbody>
                
 			<thead>
                <tr>

                    <th class="active">Category</td>
                    <th class="active">Rate(%)</td>
               
                </tr>
            </thead>
	   <tr>

     <td>
     <select class="categorey_claim_all" name="category[]" >
      <option value='inp' id="categorey_claim">Input</option>
      <option value="cg"  class="categorey_claim"  data-id=<?php echo $data->id ?>>Capital Good</option>
      <option value="is" id="categorey_claim"  data-id=<?php echo $data->id ?>>Input Services</option>
      <option value="ine"  id="categorey_claim"   data-id=<?php echo $data->id ?>>Ineligble</option>
    </select>
     </td>
    <td><input type="number" name="claim_rate[]" id="claim_rate_all" class="claim_rate_all"  min="0" max="100" step="0.01"></td> 
     </tr>
            </tbody>
        </table>
</div>
<input type="submit" name="sub"  value="Save ITC Values" class="btn btn-primary">
</form>




		
		
		</div>
		<div class="clear height40"></div>
	</div>
	<div class="clear"></div>
</div>

<script>
$(document).ready(function () {

	$('#returnmonth').on('change', function () {
		window.location.href = "<?php echo PROJECT_URL; ?>/?page=return_gstr2_claim_itc&returnmonth=" + $(this).val();
	});


	$(".claim_rate").each(function() {
				var taxval=$(this).val();
					$(this).val(taxval);
					var x = parseFloat($(this).val())*parseFloat($(this).attr('data-bind'))/100;
					$(this).closest('tr').find('.claim_value').html(x);
					
			});

		$('.categorey_claim').on('change', function () {
			var Category=$('option:selected', this).val();
 
		});

			$('.categorey_claim_all').on('change', function () {
			var cat=$('option:selected', this).val();
						$(".claim_rate").each(function() {
				if($(this).closest('tr').find('.checkbox').is(':checked')) {
						$(this).closest('tr').find('.categorey_claim').val(cat);
				}
 
		});
			});
          
		$('.claim_rate_all').on('input', function () {
			
			var taxval=$(this).val();
			$(".claim_rate").each(function() {
				if($(this).closest('tr').find('.checkbox').is(':checked')) {
					$(this).val(taxval);
					var x = parseFloat($(this).val())*parseFloat($(this).attr('data-bind'))/100;
					$(this).closest('tr').find('.claim_value').html(x);
					


				}
			});
		/*	var Available=(taxval*claimRate)/100;
			$(this).closest('tr').find('.claim_value').val(Available);*/
			
		});
		
		$('.claim_rate').on('input', function () {
			var claimRate=$(this).closest('tr').find('.claim_rate').val();
			$(this).closest('tr').find('.claim_rate').val();
			var taxval=$(this).attr('data-bind');
			var Available=(taxval*claimRate)/100;
			$(this).closest('tr').find('.claim_value').html(Available);
			});
	});
</script>