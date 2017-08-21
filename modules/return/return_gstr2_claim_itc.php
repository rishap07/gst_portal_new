<?php
$obj_gstr2 = new gstr2();
$dataCurrentUserArr = $obj_gstr2->getUserDetailsById($_SESSION['user_detail']['user_id']);
$returnmonth = date('Y-m');
if (isset($_REQUEST['returnmonth']) && $_REQUEST['returnmonth'] != '') {
    $returnmonth = $_REQUEST['returnmonth'];
}
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

            <div class="col-md-12 col-sm-12 col-xs-12 tablistnav padleft0">
                <ul>
                    <li><a href="<?php echo PROJECT_URL . '/?page=return_gstr2&returnmonth=' . $returnmonth ?>">View GSTR2 Summary</a></li>
                    <li><a href="<?php echo PROJECT_URL . '/?page=return_purchase_all&returnmonth=' . $returnmonth ?>" > View My Data</a></li>
                    <li><a href="<?php echo PROJECT_URL . '/?page=return_vendor_invoices&returnmonth=' . $returnmonth ?>">Download GSTR-2A</a></li>
                    <li><a href="<?php echo PROJECT_URL . '/?page=return_gstr2_reconcile&returnmonth=' . $returnmonth ?>" class="active">GSTR-2 Reconcile</a></li>
                    <li><a href="<?php echo PROJECT_URL . '/?page=return_gstr2_claim_itc&returnmonth=' . $returnmonth ?>" >Claim ITC</a></li>                   
                    <li><a href="<?php echo PROJECT_URL . '/?page=return_gstr2_upload_invoices&returnmonth=' . $returnmonth ?>">Upload To GSTN</a></li>
                    <li><a href="<?php echo PROJECT_URL . '/?page=return_gstr2_file&returnmonth=' . $returnmonth ?>">GSTR-2 Filing</a></li>


                </ul>
            </div>
            <div class="clear"></div>
            <?php
$claim_data=$obj_gstr2->claimItc();

if(isset($_POST['sub']) && $_POST['sub']=="Save ITC Values")
{
	
	$dataArr = array();
	for($x=0;$x<count($_POST['category']);$x++)
	{
		$dataArr[$x]['set']['category']=isset($_POST['category'][$x]) ? $_POST['category'][$x] : '';
		$dataArr[$x]['set']['claim_rate']=isset($_POST['claim_rate'][$x]) ? $_POST['claim_rate'][$x] : '';
		$dataArr[$x]['set']['claim_value']=isset($_POST['claim_value'][$x]) ? $_POST['claim_value'][$x] : '';
		$dataArr[$x]['where']['reference_number']=isset($_POST['id'][$x]) ? $_POST['id'][$x] : '';

	}
	//$obj_gstr2->updateMultiple(tablaname,$dataArr);
	print_r($dataArr);
	if($obj_gstr2->updateMultiple($obj_gstr2->getTableName('client_reconcile_purchase_invoice1'), $dataArr))
	{
		echo "updated";
	}
	else
	{
		echo "not";
	}
	
}


?>

<form method="post" action="">
<div  >
<table width="100%" border="0" cellspacing="0" cellpadding="4" class="table table-striped  tablecontent" >
            <thead>
                <tr>
                	<th><input type="checkbox" name="checkbox[]" value="" id="checkbox"></th>
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
                <?php foreach ($claim_data as $data) {?>
 
	   <tr>
    <td><?php echo '<input type="checkbox" name="checkbox[]" value="" id="checkbox">';?></td>
    <td><?php echo $data->invoice_date ?></td>
    <td><?php echo $data->reference_number ?></td>
    <td><?php echo $data->company_name ?></td>
    <td><?php echo $data->gstin_number ?></td>
    <td><?php echo $data->taxable_subtotal ?></td> 
     <td>
     <select class="categorey_claim" name="category[]">
      <option value='inp' id="categorey_claim" class="categorey_claim" data-id=<?php echo $data->id ?>>Input</option>
      <option value="cg" id="categorey_claim" class="categorey_claim"  data-id=<?php echo $data->id ?>>Capital Good</option>
      <option value="is" id="categorey_claim" class="categorey_claim"  data-id=<?php echo $data->id ?>>Input Services</option>
      <option value="ine" id="categorey_claim" class="categorey_claim"  data-id=<?php echo $data->id ?>>Ineligble</option>
    </select>
     </td>
    <td><input type="number" name="claim_rate[]" id="claim_rate" data-bind=<?php echo $data->taxable_subtotal ?> class="claim_rate" value="0.00" min="0" max="100" step="0.01"></td> 
    <td><input name="claim_value[]" type="text" id="claim_value" class="claim_value" value="0.00"></td>
    <input type="hidden" name=id[] value=<?php echo $data->reference_number ?>>      
     </tr>
               <?php
                }
               ?>
            </tbody>
        </table>
</div>
<input type="submit" name="sub" value="Save ITC Values" class="btn btn-default">
</form>

 <script>
	$(document).ready(function () {
		$('.categorey_claim').on('change', function () {
			var Category=$('option:selected', this).val();
     

/*	$(this).closest('tr').find('.claim_value').val($('option:selected', this).attr('data-bind'));		 */
			//$('#loading').show();
/*			$.ajax({
                data: {invoiceId:$(this).attr('data-id'),returnmonth:<?php echo $returnmonth ?>,status:$(this).attr('data-bind'),case:'additional'},
                dataType: 'json',
                type: 'post',
                url: "<?php echo PROJECT_URL; ?>/?ajax=return_reconcile_purchase_invoice",
                success: function(response){
                $('#loading').hide();
                }
            });*/

		});


		$('.claim_rate').on('focusout', function () {
			var claimRate=$(this).closest('tr').find('.claim_rate').val();
			$(this).closest('tr').find('.claim_rate').val();
			var taxval=$(this).attr('data-bind');
			var Available=(taxval*claimRate)/100;
			$(this).closest('tr').find('.claim_value').val(Available);
			});
	});
</script>