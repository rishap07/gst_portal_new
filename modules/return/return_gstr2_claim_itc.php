<?php
$obj_gstr2 = new gstr2();
if(!$obj_gstr2->can_read('returnfile_list'))
{
    $obj_gstr2->setError($obj_gstr2->getValMsg('can_read'));
    $obj_gstr2->redirect(PROJECT_URL."/?page=dashboard");
    exit();
}
$dataCurrentUserArr = $obj_gstr2->getUserDetailsById($_SESSION['user_detail']['user_id']);
$returnmonth = date('Y-m');
if (isset($_REQUEST['returnmonth']) && $_REQUEST['returnmonth'] != '') {
    $returnmonth = $_REQUEST['returnmonth'];
}
if(isset($_POST['sub']) && $_POST['sub']=="Save ITC Values")
{
	
	if($obj_gstr2->submitITCClaim())
	{
		$obj_gstr2->redirect(PROJECT_URL."/?page=return_gstr2_claim_itc&returnmonth=".$returnmonth);
		exit();
	}
	
}
$claim_data=$obj_gstr2->claimItc();
/*echo "<pre>";
print_r($claim_data);*/

?>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="col-md-6 col-sm-6 col-xs-12 heading"><h1>GSTR-2 Filing</h1></div>
        <div class="col-md-6 col-sm-6 col-xs-12 text-right breadcrumb-nav">
            <a href="#">Home</a><i class="fa fa-angle-right" aria-hidden="true"></i>
            <a href="#">File Return</a><i class="fa fa-angle-right" aria-hidden="true"></i>
            <span class="active">GSTR-2 Filing</span>
        </div>

        <div class="whitebg formboxcontainer">
            <div class="pull-right rgtdatetxt">
                <form method='post' name='form5' id="form5">
                    Month Of Return 
                    <?php
                    $dataQuery = "SELECT DATE_FORMAT(invoice_date,'%Y-%m') AS niceDate FROM gst_client_invoice group by nicedate";
                    $dataRes = $obj_gstr2->get_results($dataQuery);
                    if (!empty($dataRes)) {
                        ?>
                        <select class="dateselectbox" id="returnmonth" name="returnmonth">
                            <?php foreach ($dataRes as $dataRe) { ?>
                                <option value="<?php echo $dataRe->niceDate; ?>" <?php
                                if ($dataRe->niceDate == $returnmonth) {
                                    echo 'selected';
                                }
                                ?>><?php echo $dataRe->niceDate; ?></option>
                                    <?php }
                                    ?>
                        </select>
                    <?php } else {
                        ?>
                        <select class="dateselectbox" id="returnmonth" name="returnmonth">
                            <option value="2017-07">2017-07</option>
                        </select>
                    <?php }
                    ?>
                </form>
            </div>

            <div class="col-md-12 col-sm-12 col-xs-12 heading">
            <div class="tab col-md-12 col-sm-12 col-xs-12">
              <?php
                        include(PROJECT_ROOT."/modules/return/include/tab.php");
               ?>
            </div></div>
            <div class="clear"></div>
           <?php $obj_gstr2->showErrorMessage(); ?>
			<?php $obj_gstr2->showSuccessMessge(); ?>
			<?php $obj_gstr2->unsetMessage(); ?>
			 <div class="clear"></div>
<form method="post" action="">
<div >
<table width="100%" border="0" cellspacing="0" cellpadding="4" class="table table-striped  tablecontent" >
            <thead>
			
                <tr>
                	<th><input type="checkbox" value="" id="select_all"></th>
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
    <td><?php echo '<input type="checkbox" class="checkbox" name="checkbox[]" value='.$data->id.' id="checkbox">';?></td>
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


 <script>
	$(document).ready(function () {
		$('#select_all').change(function() {
    var checkboxes = $(this).closest('form').find(':checkbox');
    if($(this).is(':checked')) {
    	 $("#txtPopup").show();
        checkboxes.prop('checked', true);
    } else {
    	 $("#txtPopup").hide();
        checkboxes.prop('checked', false);
    }
    

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
<script>
    $(document).ready(function () {
        $('#returnmonth').on('change', function () {
            window.location.href = "<?php echo PROJECT_URL; ?>/?page=return_gstr2_claim_itc&returnmonth=" + $(this).val();
        });
    });
</script>