<?php
$obj_client = new client();
$obj_gstr2 = new gstr2();
if(!$obj_gstr2->can_read('returnfile_list'))
{
    $obj_gstr2->setError($obj_gstr2->getValMsg('can_read'));
    $obj_gstr2->redirect(PROJECT_URL."/?page=dashboard");
    exit();
}
	$dataCurrentUserArr = $obj_gstr2->getUserDetailsById( $obj_gstr2->sanitize($_SESSION['user_detail']['user_id']) );

	$returnmonth= date('Y-m');
	if(isset($_REQUEST['returnmonth']) && $_REQUEST['returnmonth'] != '') {
		$returnmonth = $_REQUEST['returnmonth'];
	}
if (!isset($_REQUEST['returnmonth']) || $_REQUEST['returnmonth'] == '') {
    $obj_client->redirect(PROJECT_URL . "/?page=return_client");
    exit();
}
if (isset($_POST['returnmonth'])) {
    $returnmonth = $_POST['returnmonth'];
    $obj_client->redirect(PROJECT_URL . "/?page=return_gstr1a_reconcile&returnmonth=" . $returnmonth);
    exit();
}
$returnmonth = date('Y-m');
if ($_REQUEST['returnmonth'] != '') {
    $returnmonth = $_REQUEST['returnmonth'];
}
?>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr">
    <div class="col-md-11 col-sm-12 col-xs-12 mobpadlr">
        <div class="col-md-12 col-sm-12 col-xs-12 heading">
            <div class="tab col-md-12 col-sm-12 col-xs-12">
                 <a href="<?php echo PROJECT_URL . '/?page=return_gstr1a_download&returnmonth=' . $returnmonth ?>" >
                    Download GSTR-1A
                </a>
                <a href="<?php echo PROJECT_URL . '/?page=return_gstr1a_reconcile&returnmonth=' . $returnmonth ?>" class="active"  >
                  GSTR-1A Reconcile
                </a>
                <a href="<?php echo PROJECT_URL . '/?page=return_gstr1a_upload_invoices&returnmonth=' . $returnmonth ?>" >
                    Upload GSTR-1A
                </a>
                </a>
                <a href="<?php echo PROJECT_URL . '/?page=return_gstr1a_filling_summary&returnmonth=' . $returnmonth ?>">
                    File GSTR-1A
                </a>         
               
            </div>
            <div id="London" class="tabcontent">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="col-md-6 col-sm-12 col-xs-12"><h3>GSTR1A Summary</h3></div>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="whitebg formboxcontainer">
                            <div class="pull-right rgtdatetxt">
                                <form method='post' name='form2'>
                                    Month Of Return
                                    <?php
                                    $dataQuery = "SELECT DATE_FORMAT(invoice_date,'%Y-%m') AS niceDate FROM gst_client_invoice group by nicedate";
                                    $dataRes = $obj_client->get_results($dataQuery);
                                    if (!empty($dataRes)) {
                                        ?>
                                        <select class="dateselectbox" id="returnmonth" name="returnmonth">
                                        <?php
                                        foreach ($dataRes as $dataRe) {
                                            ?>
                                                <option value="<?php echo $dataRe->niceDate; ?>" <?php if ($dataRe->niceDate == $returnmonth) { echo 'selected'; } ?>><?php echo $dataRe->niceDate; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                        <?php
                                    } else {
                                        ?>
                                        <select class="dateselectbox" id="returnmonth" name="returnmonth">
                                            <option>July 2017</option>
                                        </select>
                                    <?php }
                                    ?>
                                </form>
                            </div> <div class="clear"></div>
                            <?php $obj_client->showErrorMessage(); ?>
                            <?php $obj_client->showSuccessMessge(); ?>
                            <?php $obj_client->unsetMessage(); ?>
                         
		<div class="row gstr2-reconcile">

			<?php
				$matched = 0;
				$missing = 0;
				$additional = 0;
				$mismatched = 0;
				$matchId=array();
				$mismatchId=array();
				$additionalId=array();
				$missingId=array();
				$gstr2DownlodedInvoices = $obj_gstr2->get_results("select
					i.invoice_id, 
					i.reference_number, 
					i.serial_number, 
					i.invoice_type, 
					i.invoice_nature,
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
					i.invoice_nature='salesinvoice' 
					AND i.billing_gstin_number='".$dataCurrentUserArr['data']->kyc->gstin_number."'
					AND i.status='1' 
					AND i.is_canceled='0' 
					AND i.is_deleted='0' 
					AND i.is_gstr1_uploaded != '0'
					AND i.is_gstr2_downloaded = '1' 
					AND i.invoice_date like '%" . $returnmonth . "%'
					group by i.invoice_id 
					order by i.invoice_date ASC");
				//echo "<pre>";
                //   print_r($gstr2DownlodedInvoices);
                 // echo "<br><br>";

				$query="select 
							ci.purchase_invoice_id, 
							ci.reference_number, 
							ci.serial_number, 
							ci.invoice_type, 
							ci.invoice_nature,
							ci.company_gstin_number, 
							ci.supply_type, 
							ci.import_supply_meant, 
							ci.invoice_date, 
							ci.supply_place, 
							ci.supplier_billing_gstin_number, 
							ci.recipient_shipping_gstin_number, 
							ci.invoice_total_value, 
							ci.financial_year, 
							sum(cii.taxable_subtotal) as total_taxable_subtotal, 
							sum(cii.cgst_amount) as total_cgst_amount, 
							sum(cii.sgst_amount) as total_sgst_amount, 
							sum(cii.igst_amount) as total_igst_amount, 
							sum(cii.cess_amount) as total_cess_amount 
							from " . $obj_gstr2->getTableName('client_purchase_invoice') . " as ci INNER JOIN " . $obj_gstr2->getTableName("client_purchase_invoice_item") . " as cii ON ci.purchase_invoice_id = cii.purchase_invoice_id
							where 
							ci.invoice_nature='purchaseinvoice' 
							AND ci.recipient_shipping_gstin_number='".$dataCurrentUserArr['data']->kyc->gstin_number."'
							AND ci.is_deleted='0' 
							group by ci.purchase_invoice_id";
							//echo "<br><pre>";
							$purchaseInvoices = $obj_gstr2->get_results($query);
							//print_r($purchaseInvoices);



foreach ($gstr2DownlodedInvoices as $gstr2DownlodedInvoice) {
	$flag=0;
	foreach($purchaseInvoices as $purchaseInvoice)	
	{
		if ($gstr2DownlodedInvoice->reference_number===$purchaseInvoice->reference_number)
		{
			$flag=1;
			//echo 'missmatched ';
		if(($gstr2DownlodedInvoice->invoice_total_value == $purchaseInvoice->invoice_total_value)&&
		   ($gstr2DownlodedInvoice->total_taxable_subtotal == $purchaseInvoice->total_taxable_subtotal)&&
		   ($gstr2DownlodedInvoice->total_cgst_amount == $purchaseInvoice->total_cgst_amount)&&
		   ($gstr2DownlodedInvoice->total_igst_amount == $purchaseInvoice->total_igst_amount)&&
		   ($gstr2DownlodedInvoice->total_cess_amount == $purchaseInvoice->total_cess_amount)&&
		   ($gstr2DownlodedInvoice->total_sgst_amount == $purchaseInvoice->total_sgst_amount))

		 {
		 $matched++;
		array_push($matchId,$purchaseInvoice->purchase_invoice_id);
}
else
{
	$mismatched++;
	array_push($mismatchId,$purchaseInvoice->purchase_invoice_id);
} 

		} 
	}
	if($flag==0)
	{
		$missing++ ;
		array_push($missingId,$gstr2DownlodedInvoice->invoice_id);
		//echo "id".$gstr2DownlodedInvoice->invoice_id."<br>";
	}
}




	
	foreach($purchaseInvoices as $purchaseInvoice)	
	{
		$addFlag=0;
		foreach ($gstr2DownlodedInvoices as $gstr2DownlodedInvoice) {
		if ($purchaseInvoice->reference_number===$gstr2DownlodedInvoice->reference_number)
		{
			$addFlag=1;
		} 
	}
	if($addFlag==0)
	{
		$additional++ ;
		array_push($additionalId,$purchaseInvoice->purchase_invoice_id);
		//echo "additional id".$purchaseInvoice->purchase_invoice_id."<br>";
	}
}
//echo $mismatched." ".$matched." ".$missing." ".$additional;




$matchId  =implode(",",$matchId);
//echo "<br>$matchId<br>";
if(sizeof($mismatchId)>0)
{
$mismatchId  =implode(",",$mismatchId);
//echo "$mismatchId<br>";	
}
else
{
$mismatchId =0;	
//echo "$mismatchId<br>";
}
$missingId  =implode(",",$missingId);
//echo "$missingId<br>";
$additionalId  =implode(",",$additionalId);
//echo "$additionalId<br>";						
					
?>








                 
                    <div class="row reconciliation">
                    
                     <div class="col-md-3 col-sm-3 col-xs-12">
                            <div class="lightgreen col-text">
                                <div class="dashcoltxt">
                                    <div class="boxtextheading pull-left">Matched</div> 
                                    <div class="pull-right btn bordergreen"><a href="<?php echo PROJECT_URL . '/?page=return_gstr1a_view_reconcile_invoices&matchedFlag=1&ids=' . $matchId?>">View Records</a></div>
                                    <div class="clear height10"></div>
                                    <div class="txtnumber col-md-4 col-sm-4"><?php echo $matched?><br/><span>RECORDS</span><br/></div>
   
                                     
                                </div>
                            </div>
                        </div>
                        
                         <div class="col-md-3 col-sm-3 col-xs-12">
                            <div class="lightblue col-text">
                                <div class="dashcoltxt">
                                    <div class="boxtextheading pull-left">Missing</div> <div class="pull-right btn borderblue"><a href="<?php echo PROJECT_URL . '/?page=return_gstr1a_view_reconcile_invoices&matchedFlag=0&ids=' . $missingId?>">View Records</a></div>
                                    <div class="clear height10"></div>
                                    <div class="txtnumber col-md-4 col-sm-4"><?php echo $missing?><br/><span>RECORDS</span><br/></div>
                                    <div class="txtnumber col-md-4 col-sm-4">0<br/><span>ADDRESSED</span><br/></div>
                                     <div class="txtnumber redtxt col-md-4 col-sm-4"><?php echo $missing?><br/><span>PENDING</span><br/></div>
                                     
                                </div>
                            </div>
                        </div>
                        
                         <div class="col-md-3 col-sm-3 col-xs-12">
                            <div class="lightyellowbg col-text">
                                <div class="dashcoltxt">
                                    <div class="boxtextheading pull-left">Additional</div> <div class="pull-right btn borderbrown"><a href="<?php echo PROJECT_URL . '/?page=return_gstr1a_view_reconcile_invoices&matchedFlag=0&ids=' . $additionalId?>">View Records</a></div>
                                    <div class="clear height10"></div>
                                    <div class="txtnumber col-md-4 col-sm-4"><?php echo $additional?><br/><span>RECORDS</span><br/></div>
                                    <div class="txtnumber col-md-4 col-sm-4">0<br/><span>ADDRESSED</span><br/></div>
                                     <div class="txtnumber redtxt col-md-4 col-sm-4"><?php echo $additional?><br/><span>PENDING</span><br/></div>
                                     
                                </div>
                            </div>
                        </div>
                        
                         <div class="col-md-3 col-sm-3 col-xs-12">
                            <div class="pinkbg col-text">
                                <div class="dashcoltxt">
                                    <div class="boxtextheading pull-left">Mismatch</div> <div class="pull-right btn borderred"><a href="<?php echo PROJECT_URL . '/?page=return_gstr1a_view_reconcile_invoices&matchedFlag=0&ids=' . $mismatchId?>">View Records</a></div>
                                    <div class="clear height10"></div>
                                     <div class="txtnumber col-md-4 col-sm-4"><?php echo $mismatched?><br/><span>RECORDS</span><br/></div>
                                    <div class="txtnumber col-md-4 col-sm-4">0<br/><span>ADDRESSED</span><br/></div>
                                     <div class="txtnumber redtxt col-md-4 col-sm-4"><?php echo $mismatched?><br/><span>PENDING</span><br/></div>
                                     
                                </div>
                            </div>
                        </div>

						
                              
						</div>  
                        </div>
                    </div>
                </div>
            </div>
        </div>
  <script>
$(document).ready(function() {
	$('.row-offcanvas-left').addClass('');
	$(".mobilemenu").click(function() {
		$("#sidebar").toggle();
		$('.row-offcanvas-left').addClass('mobileactive');
        
    });
    
});


</script>
<script>
    function openCity(evt, cityName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(cityName).style.display = "block";
        evt.currentTarget.className += " active";
    }
</script>   
<script>
    $(document).ready(function () {
        $('#returnmonth').on('change', function () {
            document.form2.action = '<?php echo PROJECT_URL; ?>/?page=return_gstr1a_reconcile&returnmonth=<?php echo $returnmonth; ?>';
                        document.form2.submit();
                    });
                });
</script>