<?php
	$obj_gstr2 = new gstr2();
	$dataCurrentUserArr = $obj_gstr2->getUserDetailsById( $obj_gstr2->sanitize($_SESSION['user_detail']['user_id']) );
		$returnmonth= date('Y-m');
	if(isset($_REQUEST['returnmonth']) && $_REQUEST['returnmonth'] != '') {
		$returnmonth = $_REQUEST['returnmonth'];
	}
		if(isset($_REQUEST['ids']) && $_REQUEST['ids'] != '') {
		$ids = $_REQUEST['ids'];

	}
			if(isset($_REQUEST['mismatchId']) && $_REQUEST['mismatchId'] != '') {
		$mismatchId = $_REQUEST['mismatchId'];

	}
			if(isset($_REQUEST['missingId']) && $_REQUEST['missingId'] != '') {
		$missingId = $_REQUEST['missingId'];

	}
			if(isset($_REQUEST['additionalId']) && $_REQUEST['additionalId'] != '') {
		$additionalId = $_REQUEST['additionalId'];

	}
			if(isset($_REQUEST['matchedFlag']) && $_REQUEST['matchedFlag'] != '') {
		$matchedFlag = $_REQUEST['matchedFlag'];

	}
		$ids=explode(",",$ids);
		$ttl_rec=count($ids);
?>
   	<div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>Reconciliation</h1></div>
                     <div class="whitebg formboxcontainer">
                 
                    <!--/row-->    
                    <!--/col-12-->
<div class="col-md-6 col-sm-6 col-xs-6 padleft0">Showing 1-<?php echo "$ttl_rec of $ttl_rec"?> </div>  
<div class="col-md-6 col-sm-6 col-xs-6 text-right padrgtnone"><select class="selectbox"><option>Records 10</option></select></div>                
<div class="clear height20"></div>
                     
 <div class="tableresponsive" style="overflow-x:scroll;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="4" class="table table-striped  tablecontent" >
       <tr>
    <th class="active">Inovice Detail</th>
    <th class="active">GSTIN/UID</th>
    <th class="active">Invoice Value</th>
    <th class="active">Taxable Value</th>
    <th class="active">Tax Amount</th>
    <th class="active" width="16%">Status</th>
  </tr>               
                    <?php
		foreach ($ids as $id) {
			$query="select i.invoice_id, 
					i.reference_number, 
					i.serial_number, 
					i.invoice_type, 
					i.gstin_number as company_gstin_number, 
					i.supply_type, 
					i.export_supply_meant, 
					i.invoice_date, 
					i.supply_place, 
					i.billing_gstin_number, 
					i.shipping_gstin_number, 
					i.invoice_total_value, 
					i.financial_year, 
					sum(it.taxable_subtotal) as total_taxable_subtotal, 
					sum(it.cgst_amount) as total_cgst_amount, 
					sum(it.sgst_amount) as total_sgst_amount, 
					sum(it.igst_amount) as total_igst_amount, 
					sum(it.cess_amount) as total_cess_amount 
					from " . $obj_gstr2->getTableName('client_invoice') . " i inner join " . $obj_gstr2->getTableName("client_invoice_item") . " it on i.invoice_id=it.invoice_id 
					where 
					i.invoice_id=".$id;
	$invoideData = $obj_gstr2->get_results($query);
	$invoideData=$invoideData[0];
//echo $query;
//$obj_gstr2->pr($invoideData);die;
$taxAmt=$invoideData->total_igst_amount + $invoideData->total_cgst_amount +$invoideData->total_sgst_amount+ $invoideData->total_cess_amount;

	?>

	   <tr>
    <td class="boldfont"><?php echo $invoideData->reference_number?><br/><span class="table-date-txt"><?php echo $invoideData->invoice_date?></span></td>
    <td><?php echo $invoideData->company_gstin_number?></td>
    <td><?php echo $invoideData->invoice_total_value?></td>
    <td><?php echo $invoideData->total_taxable_subtotal?></td>
    <td><?php echo $taxAmt?></td> 
    <td><a href="#" class="btnaccepted">Accepted</a></td> 
     <?php
		}

?>

</table>
</div>