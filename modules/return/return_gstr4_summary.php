<?php
//$obj_gstr4 = new client();
$obj_gstr4 = new gstr4();
if(!$obj_gstr4->can_read('returnfile_list'))
{
    $obj_gstr4->setError($obj_gstr4->getValMsg('can_read'));
    $obj_gstr4->redirect(PROJECT_URL."/?page=dashboard");
    exit();
}
$dataCurrentUserArr = $obj_gstr4->getUserDetailsById( $obj_gstr4->sanitize($_SESSION['user_detail']['user_id']) );
//$obj_gstr4->pr($dataCurrentUserArr['data']);die;

if(isset($dataCurrentUserArr['data']->kyc->vendor_type) && $dataCurrentUserArr['data']->kyc->vendor_type!='1'){
   // $obj_gstr4->setError("Invalid Access to file");
    //$obj_gstr4->redirect(PROJECT_URL . "/?page=dashboard");
   //exit();
}


if (!isset($_REQUEST['returnmonth']) || $_REQUEST['returnmonth'] == '') {
    $obj_gstr4->redirect(PROJECT_URL . "/?page=return_client");
    exit();
}
if (isset($_POST['returnmonth'])) {
    $returnmonth = $_POST['returnmonth'];
    $obj_gstr4->redirect(PROJECT_URL . "/?page=return_gstr4_summary&returnmonth=" . $returnmonth);
    exit();
}
$returnmonth = date('Y-m');
if ($_REQUEST['returnmonth'] != '') {
    $returnmonth = $_REQUEST['returnmonth'];
	
}
if(isset($_REQUEST['returnmonth']) && $_REQUEST['returnmonth'] != '')
{
    $returnmonth= $_REQUEST['returnmonth'];
	$obj_gstr4->startGstr4($returnmonth);
}
else
{
	$dataQuery = "SELECT DATE_FORMAT(invoice_date,'%Y-%m') AS niceDate FROM gst_client_invoice group by nicedate ORDER by invoice_date desc LIMIT 0,1";
	$dataRes = $obj_gstr4->get_results($dataQuery);
	if(!empty($dataRes))
	{
	$returnmonth=$dataRes[0]->niceDate;
	}	
	
}
?>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr">
    <div class="col-md-11 col-sm-12 col-xs-12 mobpadlr">
        <div class="col-md-12 col-sm-12 col-xs-12 heading">
		<div class="clear"></div>
            <div class="tab col-md-12 col-sm-12 col-xs-12">
               
                <a href="<?php echo PROJECT_URL . '/?page=return_gstr4_summary&returnmonth=' . $returnmonth ?>" class="active">
                    1.View GSTR4 Summary
                </a>
                <a href="<?php echo PROJECT_URL . '/?page=return_gstr4view_invoices&returnmonth=' . $returnmonth ?>" >
                    2.View My Invoice
                </a>
				
               
                <a href="<?php echo PROJECT_URL . '/?page=return_gstr4get_summary&returnmonth=' . $returnmonth ?>">
                    3.GSTR4 SUMMARY
                </a>
                <a href="<?php echo PROJECT_URL . '/?page=return_gstr4filling_summary&returnmonth=' . $returnmonth ?>">
                    4.File GSTr-4
                </a>
                
            </div>
            <div id="London" class="tabcontent">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="col-md-6 col-sm-12 col-xs-12"><h3>GSTR4 Summary</h3></div>
                </div>
				
                <div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="whitebg formboxcontainer">
						
                            <div class="pull-right rgtdatetxt">
                                <form method='post' name='form2'>
                                   Month Of Return
						<?php $invoiceMonthYear = $obj_gstr4->getInvoiceMonthList($obj_gstr4->getTableName('client_purchase_invoice')); ?>
						<select class="monthselectbox" id="returnmonth" name="returnmonth">
							<option value="">Select</option>
							<?php foreach($invoiceMonthYear as $monthYear) { ?>
								<option <?php if($returnmonth == $monthYear->invoiceDate) { echo 'selected="selected"'; } ?> value="<?php echo $monthYear->invoiceDate; ?>"><?php echo date("M-y", strtotime($monthYear->invoiceDate)); ?></option>
							<?php } ?>
						</select>
                                </form>
                            </div>
                            <?php $obj_gstr4->showErrorMessage(); ?>
                            <?php $obj_gstr4->showSuccessMessge(); ?>
                            <?php $obj_gstr4->unsetMessage(); ?>
                            <div class="clearfix height80"></div>
                            <div class="adminformbx">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="invoice-itemtable" id="mainTable1">
                                    <thead>
                                        <tr>
                                            <th>Type Of Invoice</th>
                                            <th style="text-align:right">No. Invoices</th>
                                            <th style="text-align:right">Taxable / Advance adjusted Amount ( <i class="fa fa-inr"></i> )</th>
                                            <th style="text-align:right">Tax Amt ( <i class="fa fa-inr"></i> )</th>
                                            <th style="text-align:right">TotalAmount ( <i class="fa fa-inr"></i> )</th>
                                        </tr>
                                        <tr>
                                            <?php
                                            $b2bData =  $obj_gstr4->getPurchaseB2BInvoices($_SESSION['user_detail']['user_id'], $returnmonth,'b2b');
                                            //$b2bData = $obj_gstr4->getB2BInvoices($_SESSION['user_detail']['user_id'], $returnmonth,'all');
                                            //$obj_gstr4->pr($b2bData);
											$b2bCount=0;
											$b2b_invoice_total_value=0;
											$b2b_total=0;
											$b2b_sumTotal=0;
											
                                           if(!empty($b2bData))
										   {
											 $b2bCount=  $b2bData[0]->totalinvoice;
											 $b2b_sumTotal=  $b2bData[0]->totalamount;
											 $b2b_total=  $b2bData[0]->cgst+$b2bData[0]->igst+$b2bData[0]->sgst+$b2bData[0]->cess;
											 
										   }
                                            ?>
                                            <td>B2B</th>
                                            <td align='right'><?php echo $b2bCount; ?></td>
                                            <td align='right'><?php echo $b2b_invoice_total_value; ?></td>
                                            <td align='right'><?php echo $b2b_total; ?></td>
                                            <td align='right'><?php echo $b2b_sumTotal; ?></td>
                                        </tr>
                                        <tr>
                                            <?php
                                           $b2burData =  $obj_gstr4->getPurchaseB2BurInvoices($_SESSION['user_detail']['user_id'], $returnmonth,'b2b');
                                            //$b2bData = $obj_gstr4->getB2BInvoices($_SESSION['user_detail']['user_id'], $returnmonth,'all');
                                           
											$b2burCount=0;
											$b2bur_invoice_total_value=0;
											$b2bur_total=0;
											$b2bur_sumTotal=0;
                                           if(!empty($b2burData))
										   {
											 $b2burCount=  $b2burData[0]->totalinvoice;
											 $b2bur_sumTotal=  $b2burData[0]->totalamount;
											 $b2bur_total=  $b2burData[0]->cgst+$b2burData[0]->igst+$b2burData[0]->sgst+$b2burData[0]->cess;
											 
										   }
                                            ?>
                                            <td>B2BUR Large</td>
                                            <td align='right'><?php echo $b2burCount; ?></td>
                                            <td align='right'><?php echo $b2bur_invoice_total_value; ?></td>
                                            <td align='right'><?php echo $b2bur_total; ?></td>
                                            <td align='right'><?php echo $b2bur_sumTotal; ?></td>
                                        </tr>
                                        <tr>
                                        <?php
                                           $impsData =  $obj_gstr4->getPurchaseImportInvoices($_SESSION['user_detail']['user_id'], $returnmonth,'b2b');
                                            //$b2bData = $obj_gstr4->getB2BInvoices($_SESSION['user_detail']['user_id'], $returnmonth,'all');
                                            //$obj_gstr4->pr($b2bData);
											$impsCount=0;
											$imps_invoice_total_value=0;
											$imps_total=0;
											$imps_sumTotal=0;
                                           if(!empty($impsData))
										   {
											 $impsCount=  $impsData[0]->totalinvoice;
											 $imps_sumTotal=  $impsData[0]->totalamount;
											 $imps_total=  $impsData[0]->cgst+$impsData[0]->igst+$impsData[0]->sgst+$impsData[0]->cess;
											 
										   }
                                            ?>
                                            <td>Import Of service</td>
                                            <td align='right'><?php echo $impsCount; ?></td>
                                            <td align='right'><?php echo $imps_invoice_total_value; ?></td>
                                            <td align='right'><?php echo $imps_total; ?></td>
                                            <td align='right'><?php echo $imps_sumTotal; ?></td>
                                        </tr>
                                        <tr>
                                            <?php
											 $cdnrData =  $obj_gstr4->getPurchaseCdnrInvoices($_SESSION['user_detail']['user_id'], $returnmonth,'b2b');
											
											$cdnrCount=0;
											$cdnr_invoice_total_value=0;
											$cdnr_total=0;
											$cdnr_sumTotal=0;
                                           if(!empty($cdnrData))
										   {
											 $cdnrCount=  $cdnrData[0]->totalinvoice;
											 $cdnr_sumTotal=  $cdnrData[0]->totalamount;
											 $cdnr_total=  $cdnrData[0]->cgst+$cdnrData[0]->igst+$cdnrData[0]->sgst+$cdnrData[0]->cess;
											 
										   }
											?>
                                            <td>Credit Debit Notes Registered</td>
                                            <td align='right'><?php echo $cdnrCount; ?></td>
                                            <td align='right'><?php echo $cdnr_invoice_total_value; ?></td>
                                            <td align='right'><?php echo $cdnr_total; ?></td>
                                            <td align='right'><?php echo $cdnr_sumTotal; ?></td>
                                        </tr>
                                        <tr>
                                            <?php
                                           $cdnurData =  $obj_gstr4->getPurchaseCdnurInvoices($_SESSION['user_detail']['user_id'], $returnmonth,'b2b');
											
											$cdnurCount=0;
											$cdnur_invoice_total_value=0;
											$cdnur_total=0;
											$cdnur_sumTotal=0;
                                           if(!empty($cdnurData))
										   {
											 $cdnurCount=  $cdnurData[0]->totalinvoice;
											 $cdnur_sumTotal=  $cdnurData[0]->totalamount;
											 $cdnur_total=  $cdnurData[0]->cgst+$cdnurData[0]->igst+$cdnurData[0]->sgst+$cdnurData[0]->cess;
											 
										   }
                                            ?>
                                            <td>Credit Debit Notes Unregistered</td>
                                            <td align='right'><?php echo $cdnurCount; ?></td>
                                            <td align='right'><?php echo $cdnur_invoice_total_value; ?></td>
                                            <td align='right'><?php echo $cdnur_total; ?></td>
                                            <td align='right'><?php echo $cdnur_sumTotal; ?></td>
                                        </tr>
                                       <tr>
                                            <?php
                                           $atData =  $obj_gstr4->getPurchaseATInvoices($_SESSION['user_detail']['user_id'], $returnmonth,'b2b');
											
											$atCount=0;
											$at_invoice_total_value=0;
											$at_total=0;
											$at_sumTotal=0;
                                           if(!empty($atData))
										   {
											 $atCount=  $atData[0]->totalinvoice;
											 $at_sumTotal=  $atData[0]->totalamount;
											 $at_total=  $atData[0]->cgst+$atData[0]->igst+$atData[0]->sgst+$atData[0]->cess;
											 
										   }
                                            ?>
                                            <td>AdvanceTax</td>
                                            <td align='right'><?php echo $atCount; ?></td>
                                            <td align='right'><?php echo $at_invoice_total_value; ?></td>
                                            <td align='right'><?php echo $at_total; ?></td>
                                            <td align='right'><?php echo $at_sumTotal; ?></td>
                                        </tr>
										<tr>
                                            <?php
                                           $atadjData =  $obj_gstr4->getPurchaseAtadjInvoices($_SESSION['user_detail']['user_id'], $returnmonth,'b2b');
											
											$atadjCount=0;
											$atadj_invoice_total_value=0;
											$atadj_total=0;
											$atadj_sumTotal=0;
                                           if(!empty($atadjData))
										   {
											 $atadjCount=  $atadjData[0]->totalinvoice;
											 $atadj_sumTotal=  $atadjData[0]->totalamount;
											 $atadj_total=  $atadjData[0]->cgst+$atadjData[0]->igst+$atadjData[0]->sgst+$atadjData[0]->cess;
											 
										   }
                                            ?>
                                            <td>Advance Adjustment Tax</td>
                                            <td align='right'><?php echo $atadjCount; ?></td>
                                            <td align='right'><?php echo $atadj_invoice_total_value; ?></td>
                                            <td align='right'><?php echo $atadj_total; ?></td>
                                            <td align='right'><?php echo $atadj_sumTotal; ?></td>
                                        </tr>
                                       
                                       
                                    </thead>
                                </table>
								<h4>Tax on outward supplies made(Net of advance and goods returned)</h4>
								  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="invoice-itemtable" id="mainTable1">
                                    <thead>
                                        <tr>
                                            <th>Rate Of Tax</th>
                                            <th style="text-align:right">TurnOver</th>
                                            <th style="text-align:right">Central Tax ( <i class="fa fa-inr"></i> )</th>
                                            <th style="text-align:right">State TAx ( <i class="fa fa-inr"></i> )</th>
                                        </tr>
										<tr>
										<?php 
										$cdnrData =  $obj_gstr4->getTotalSale($_SESSION['user_detail']['user_id'], $returnmonth,'b2b');
											
											$total_sale=0.00;
											$turnover=0.00;
											$central_tax=0.00;
											$state_tax=0.00;
											$rate=1.00;
											
                                           if(!empty($cdnrData))
										   {
											 if($dataCurrentUserArr['data']->kyc->vendor_type==2 && $dataCurrentUserArr['data']->kyc->composite_type=='manufacture')
											{
												$rate=1.00;
												 $turnover=  $cdnrData[0]->totalsale;
												 $total_tax = ($turnover*1)/100;
												 $central_tax=$total_tax;
												$state_tax=$total_tax;
											} 
											if($dataCurrentUserArr['data']->kyc->vendor_type==2 && $dataCurrentUserArr['data']->kyc->composite_type=='traders')
											{	
												$rate=2.00;
												 $turnover=  $cdnrData[0]->totalsale;
												 $total_tax = ($turnover*0.50)/100;
												 $central_tax=$total_tax;
												$state_tax=$total_tax;
											}  											
											 if($dataCurrentUserArr['data']->kyc->vendor_type==2 && $dataCurrentUserArr['data']->kyc->composite_type=='supplier')
											{
												$rate=5.00;
												 $turnover=  $cdnrData[0]->totalsale;
												 $total_tax = ($turnover*2.5)/100;
												 $central_tax=$total_tax;
												$state_tax=$total_tax;
											}  
											
										   }
										  
										
										?>
										 <td><?php echo $rate; ?> %</td>
                                            <td align='right'><?php echo $turnover; ?></td>
                                            <td align='right'><?php echo $central_tax; ?></td>
											<td align='right'><?php echo $state_tax; ?></td>
										</tr>
										
										
										</thead>
								</table>
                            </div>  
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
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
            document.form2.action = '<?php echo PROJECT_URL; ?>/?page=return_gstr4_summary&returnmonth=<?php echo $returnmonth; ?>';
                        document.form2.submit();
                    });
                });
</script>