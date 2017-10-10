<?php
//$obj_gstr1 = new client();
$obj_gstr1 = new gstr1();
if(!$obj_gstr1->can_read('returnfile_list'))
{
    $obj_gstr1->setError($obj_gstr1->getValMsg('can_read'));
    $obj_gstr1->redirect(PROJECT_URL."/?page=dashboard");
    exit();
}
$dataCurrentUserArr = $obj_gstr1->getUserDetailsById( $obj_gstr1->sanitize($_SESSION['user_detail']['user_id']) );
//z$obj_gstr1->pr($dataCurrentUserArr['data']);die;

if(isset($dataCurrentUserArr['data']->kyc->vendor_type) && $dataCurrentUserArr['data']->kyc->vendor_type!='1'){
    $obj_gstr1->setError("Invalid Access to file");
    $obj_gstr1->redirect(PROJECT_URL . "/?page=dashboard");
   exit();
}


if (!isset($_REQUEST['returnmonth']) || $_REQUEST['returnmonth'] == '') {
    $obj_gstr1->redirect(PROJECT_URL . "/?page=return_client");
    exit();
}
if (isset($_POST['returnmonth'])) {
    $returnmonth = $_POST['returnmonth'];
    $obj_gstr1->redirect(PROJECT_URL . "/?page=return_summary&returnmonth=" . $returnmonth);
    exit();
}
$returnmonth = date('Y-m');
if ($_REQUEST['returnmonth'] != '') {
    $returnmonth = $_REQUEST['returnmonth'];
	
}
if(isset($_REQUEST['returnmonth']) && $_REQUEST['returnmonth'] != '')
{
    $returnmonth= $_REQUEST['returnmonth'];
	if($obj_gstr1->startGstr1())
	{
		
	}
}
else
{
	$dataQuery = "SELECT DATE_FORMAT(invoice_date,'%Y-%m') AS niceDate FROM gst_client_invoice group by nicedate ORDER by invoice_date desc LIMIT 0,1";
	$dataRes = $obj_gstr1->get_results($dataQuery);
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
               
                <a href="<?php echo PROJECT_URL . '/?page=return_summary&returnmonth=' . $returnmonth ?>" class="active">
                    1.View GSTR1 Summary
                </a>
                <a href="<?php echo PROJECT_URL . '/?page=return_view_invoices&returnmonth=' . $returnmonth ?>" >
                    2.View My Invoice
                </a>
				<?php
				/*
                <a  href="<?php echo PROJECT_URL . '/?page=return_upload_invoices&returnmonth=' . $returnmonth ?>">
                    Upload To GSTN
                </a>
				*/ ?>
               
                <a href="<?php echo PROJECT_URL . '/?page=return_get_summary&returnmonth=' . $returnmonth ?>">
                    3.GSTR1 SUMMARY
                </a>
                <a href="<?php echo PROJECT_URL . '/?page=return_filling_summary&returnmonth=' . $returnmonth ?>">
                    4.File GSTr-1
                </a>
                
            </div>
            <div id="London" class="tabcontent">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="col-md-6 col-sm-12 col-xs-12"><h3>GSTR1 Summary</h3></div>
                </div>
				
                <div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="whitebg formboxcontainer">
						<div class="tab col-md-12 col-sm-12 col-xs-12">
						
						 <input type="button" value="<?php echo ucfirst('Update document summary'); ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=return_document_summary&returnmonth=".$_REQUEST["returnmonth"]; ?>';" class="btn btn-success" class="redbtn marlef10"/>
				         <input type="button" value="<?php echo ucfirst('Update hsn summary'); ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=return_hsnwise_summary&returnmonth=".$_REQUEST["returnmonth"]; ?>';" class="btn btn-success" class="redbtn marlef10"/>
				         <input type="button" value="<?php echo ucfirst('Update nil summary'); ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=return_nil_summary&returnmonth=".$_REQUEST["returnmonth"]; ?>';" class="btn btn-success" class="redbtn marlef10"/>
				
						
              
                          </div>
                            <div class="pull-right rgtdatetxt">
                                <form method='post' name='form2'>
                                    Month Of Return
                                    <?php
                                    $dataQuery = "SELECT DATE_FORMAT(invoice_date,'%Y-%m') AS niceDate FROM gst_client_invoice group by nicedate ";
                                    $dataRes = $obj_gstr1->get_results($dataQuery);
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
                            </div>
                            <?php $obj_gstr1->showErrorMessage(); ?>
                            <?php $obj_gstr1->showSuccessMessge(); ?>
                            <?php $obj_gstr1->unsetMessage(); ?>
                            <div class="clearfix height80"></div>
                            <div class="adminformbx">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="invoice-itemtable" id="mainTable1">
                                    <thead>
                                        <tr>
                                            <th>Type Of Invoice</th>
                                            <th style="text-align:right">No. Invoices</th>
                                            <th style="text-align:right">Taxable Amount ( <i class="fa fa-inr"></i> )</th>
                                            <th style="text-align:right">Tax Amt ( <i class="fa fa-inr"></i> )</th>
                                            <th style="text-align:right">TotalAmount ( <i class="fa fa-inr"></i> )</th>
                                        </tr>
                                        <tr>
                                            <?php
                                            $b2bData =  $obj_gstr1->getAllInvoices($_SESSION['user_detail']['user_id'], $returnmonth,'b2b');
                                            //$b2bData = $obj_gstr1->getB2BInvoices($_SESSION['user_detail']['user_id'], $returnmonth,'all');
                                            //$obj_gstr1->pr($b2bData);
                                            $b2b_total = $b2b_invoice_total_value = $b2b_sumTotal = 0;
                                            $b2bCount = $tempTotVal = $tempInvTot=  0;
                                            if (!empty($b2bData)) {
                                                $tempInv = '';
                                                foreach ($b2bData as $key => $b2bDatavalue) {
                                                    if($tempInv!='' && $tempInv!=$b2bDatavalue->invoice_id)
                                                    {
                                                        $b2bCount++;
                                                        $b2b_sumTotal +=$tempTotVal;
                                                        $b2b_invoice_total_value +=$tempInvTot;
                                                    }
                                                    $tempInvTot = isset($b2bDatavalue->taxable_subtotal)?$b2bDatavalue->taxable_subtotal:0;
                                                    $b2b_total += $b2bDatavalue->cgst_amount + $b2bDatavalue->sgst_amount + $b2bDatavalue->igst_amount + $b2bDatavalue->cess_amount;
                                                    $tempTotVal = isset($b2bDatavalue->invoice_total_value)?$b2bDatavalue->invoice_total_value:0;
                                                    
                                                    $tempInv=$b2bDatavalue->invoice_id;
                                                }
                                                if($tempInv!='')
                                                {
                                                    $b2bCount++;
                                                    $b2b_sumTotal +=$tempTotVal;
                                                    $b2b_invoice_total_value +=$tempInvTot;
                                                }
                                                
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
                                            $group_by = "";
                                            $order_by = 'a.reference_number';
                                            $b2clData =  $obj_gstr1->getAllInvoices($_SESSION['user_detail']['user_id'], $returnmonth,'b2cl');
                                            //$b2clData = $obj_gstr1->getB2CLInvoices($_SESSION['user_detail']['user_id'], $returnmonth,'all','',$group_by,$order_by);
                                            $b2cl_total = $b2cl_invoice_total_value = $b2cl_sumTotal = 0;
                                            $b2clCount = $tempTotVal = $tempInvTot = 0;
                                            if (!empty($b2clData)) {
                                                $tempInv = '';
                                                foreach ($b2clData as $key => $b2clDatavalue) {
                                                    if($tempInv!='' && $tempInv!=$b2clDatavalue->invoice_id)
                                                    {
                                                        $b2clCount++;
                                                        $b2cl_sumTotal +=$tempTotVal;
                                                        $b2cl_invoice_total_value +=$tempInvTot;
                                                    }
                                                    $tempInvTot = isset($b2clDatavalue->taxable_subtotal)?$b2clDatavalue->taxable_subtotal:0;
                                                    $b2cl_total += $b2clDatavalue->cgst_amount + $b2clDatavalue->sgst_amount + $b2clDatavalue->igst_amount + $b2clDatavalue->cess_amount;
                                                    $tempTotVal =isset($b2clDatavalue->invoice_total_value)?$b2clDatavalue->invoice_total_value:0;
                                                    $tempInv=$b2clDatavalue->invoice_id;
                                                }
                                                if($tempInv!='')
                                                {
                                                    $b2clCount++;
                                                    $b2cl_sumTotal +=$tempTotVal;
                                                    $b2cl_invoice_total_value +=$tempInvTot;
                                                }
                                                
                                            }

                                            ?>
                                            <td>B2C Large</td>
                                            <td align='right'><?php echo $b2clCount; ?></td>
                                            <td align='right'><?php echo $b2cl_invoice_total_value; ?></td>
                                            <td align='right'><?php echo $b2cl_total; ?></td>
                                            <td align='right'><?php echo $b2cl_sumTotal; ?></td>
                                        </tr>
                                        <tr>
                                            <?php
                                            $group_by = "";
                                            $order_by = 'a.reference_number';
                                            $b2csData =  $obj_gstr1->getAllInvoices($_SESSION['user_detail']['user_id'], $returnmonth,'b2cs');
                                            //$b2csData = $obj_gstr1->getB2CSInvoices($_SESSION['user_detail']['user_id'], $returnmonth,'all','',$group_by,$order_by);
                                            $b2cs_total = $b2cs_taxable_value = $b2cs_sumTotal = 0;
                                            $b2csCount = $tempTotVal = $tempInvTot = 0;
                                            if (!empty($b2csData)) {
                                                $tempInv = '';
                                                foreach ($b2csData as $key => $b2csDatavalue) {
                                                    
                                                    if($tempInv!='' && ($tempInv!=$b2csDatavalue->supply_place || $consolidate_rate!=$b2csDatavalue->consolidate_rate))
                                                    {
                                                        $b2csCount++;
                                                        if($tempInv!=$b2csDatavalue->supply_place) {
                                                           $b2cs_sumTotal +=$tempTotVal; 
                                                        }
                                                        
                                                        $b2cs_taxable_value +=$tempInvTot;
                                                    }
                                                    $tempInvTot = isset($b2csDatavalue->taxable_subtotal)?$b2csDatavalue->taxable_subtotal:0;
                                                    $b2cs_total += $b2csDatavalue->cgst_amount + $b2csDatavalue->sgst_amount + $b2csDatavalue->igst_amount + $b2csDatavalue->cess_amount;
                                                    $tempTotVal =isset($b2csDatavalue->invoice_total_value)?$b2csDatavalue->invoice_total_value:0;
                                                    $tempInv=$b2csDatavalue->supply_place;
                                                    $consolidate_rate=$b2csDatavalue->consolidate_rate;
                                                }
                                                if($tempInv!='')
                                                {
                                                    $b2csCount++;
                                                    $b2cs_sumTotal +=$tempTotVal;
                                                    $b2cs_taxable_value +=$tempInvTot;
                                                }
                                                
                                             }

                                            ?>
                                            <td>B2C Small</td>
                                            <td align='right'><?php echo $b2csCount; ?></td>
                                            <td align='right'><?php echo $b2cs_taxable_value; ?></td>
                                            <td align='right'><?php echo $b2cs_total; ?></td>
                                            <td align='right'><?php echo $b2cs_sumTotal; ?></td>
                                        </tr>
                                        <tr>
                                            <?php
                                            $cdnrData =  $obj_gstr1->getAllInvoices($_SESSION['user_detail']['user_id'], $returnmonth,'cdnr');
                                            //$cdnrData = $obj_gstr1->getCDNRInvoices($_SESSION['user_detail']['user_id'], $returnmonth,'all');
                                            $cdnr_total = $cdnr_invoice_total_value = $cdnr_sumTotal = 0;
                                            $cdnrCount = $tempTotVal = $tempInvTot = 0;
                                            if (!empty($cdnrData)) {
                                                $tempInv = '';
                                                foreach ($cdnrData as $key => $cdnrDatavalue) {
                                                    if($tempInv!='' && $tempInv!=$cdnrDatavalue->invoice_id )
                                                    {
                                                        $cdnrCount++;
                                                        $cdnr_sumTotal +=$tempTotVal;
                                                        $cdnr_invoice_total_value +=$tempInvTot;
                                                    }
                                                    $tempInvTot = isset($cdnrDatavalue->taxable_subtotal)?$cdnrDatavalue->taxable_subtotal:0;
                                                    $cdnr_total += $cdnrDatavalue->cgst_amount + $cdnrDatavalue->sgst_amount + $cdnrDatavalue->igst_amount + $cdnrDatavalue->cess_amount;
                                                    $tempTotVal =isset($cdnrDatavalue->invoice_total_value)?$cdnrDatavalue->invoice_total_value:0;
                                                    $tempInv=$cdnrDatavalue->invoice_id;
                                                }
                                                if($tempInv!='' )
                                                {
                                                    $cdnrCount++;
                                                    $cdnr_sumTotal +=$tempTotVal;
                                                    $cdnr_invoice_total_value +=$tempInvTot;
                                                }
                                                
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
                                            $group_by = "a.reference_number";
                                            $order_by = 'a.reference_number';
                                            $expData =  $obj_gstr1->getAllInvoices($_SESSION['user_detail']['user_id'], $returnmonth,'exp');
                                            //$expData = $obj_gstr1->getEXPInvoices($_SESSION['user_detail']['user_id'], $returnmonth,'all','',$group_by,$order_by);
											
                                            $exp_total = $exp_invoice_total_value = $exp_sumTotal = 0;
                                            $expCount = $tempTotVal = $tempInvTot = 0;
                                            if (!empty($expData)) {
                                                $tempInv = '';
                                                foreach ($expData as $key => $expDatavalue) {
                                                    if($tempInv!='' && $tempInv!=$expDatavalue->invoice_id )
                                                    {
                                                        $expCount++;
                                                        $exp_sumTotal +=$tempTotVal;
                                                        $exp_invoice_total_value +=$tempInvTot;
                                                    }
                                                    $tempInvTot = isset($expDatavalue->taxable_subtotal)?$expDatavalue->taxable_subtotal:0;
                                                    $exp_total += $expDatavalue->cgst_amount + $expDatavalue->sgst_amount + $expDatavalue->igst_amount + $expDatavalue->cess_amount;
                                                    $tempTotVal =isset($expDatavalue->invoice_total_value)?$expDatavalue->invoice_total_value:0;
                                                    $tempInv=$expDatavalue->invoice_id;
                                                }
                                                if($tempInv!='')
                                                {
                                                    $expCount++;
                                                    $exp_sumTotal +=$tempTotVal;
                                                    $exp_invoice_total_value +=$tempInvTot;
                                                }
                                                
                                            }
                                            ?>
                                            <td>Export</td>
                                            <td align='right'><?php echo $expCount; ?></td>
                                            <td align='right'><?php echo $exp_invoice_total_value; ?></td>
                                            <td align='right'><?php echo $exp_total; ?></td>
                                            <td align='right'><?php echo $exp_sumTotal; ?></td>
                                        </tr>
                                        <tr>
                                            <?php
                                            $group_by = " a.reference_number  ";
                                            $order_by = 'a.reference_number';
                                            $atData =  $obj_gstr1->getAllInvoices($_SESSION['user_detail']['user_id'], $returnmonth,'at');
                                            $atData = $obj_gstr1->getATInvoices($_SESSION['user_detail']['user_id'], $returnmonth,'all','',$group_by,$order_by);
                                            $at_total = $at_invoice_total_value = $at_sumTotal = 0;
                                            $atCount = $tempTotVal = $tempInvTot = 0;
                                            if (!empty($atData)) {
                                                $tempInv = '';
                                                foreach ($atData as $key => $atDatavalue) {
                                                    if($tempInv!='' && $tempInv!=$atDatavalue->invoice_id )
                                                    {
                                                        $atCount++;
                                                        $at_sumTotal +=$tempTotVal;
                                                        $at_invoice_total_value +=$tempInvTot;
                                                    }
                                                    $tempInvTot = isset($atDatavalue->taxable_subtotal)?$atDatavalue->taxable_subtotal:0;
                                                    $at_total += $atDatavalue->cgst_amount + $atDatavalue->sgst_amount + $atDatavalue->igst_amount + $atDatavalue->cess_amount;
                                                    $tempTotVal =isset($atDatavalue->invoice_total_value)?$atDatavalue->invoice_total_value:0;
                                                    $tempInv=$atDatavalue->invoice_id;
                                                }
                                                if($tempInv!='')
                                                {
                                                    $atCount++;
                                                    $at_sumTotal +=$tempTotVal;
                                                    $at_invoice_total_value +=$tempInvTot;
                                                }
                                                
                                            }
                                            ?>
                                            <td>Advance Tax</td>
                                            <td align='right'><?php echo $atCount; ?></td>
                                            <td align='right'><?php echo $at_invoice_total_value; ?></td>
                                            <td align='right'><?php echo $at_total; ?></td>
                                            <td align='right'><?php echo $at_sumTotal; ?></td>
                                        </tr>
                                        <tr>
                                            <?php
                                            $group_by = "";
                                            $order_by = 'a.reference_number';
                                            $cdnurData = $obj_gstr1->getCDNURInvoices($_SESSION['user_detail']['user_id'], $returnmonth,'all','',$group_by,$order_by);
                                            $cdnur_total = $cdnur_invoice_total_value = $cdnur_sumTotal = 0;
                                            $cdnurCount = $tempTotVal = $tempInvTot = 0;
                                            if (!empty($cdnurData)) {
                                                $tempInv = '';
                                                foreach ($cdnurData as $key => $cdnurDatavalue) {
                                                    if($tempInv!='' && $tempInv!=$cdnurDatavalue->invoice_id )
                                                    {
                                                        $cdnurCount++;
                                                        $cdnur_sumTotal +=$tempTotVal;
                                                        $cdnur_invoice_total_value +=$tempInvTot;
                                                    }
                                                    $tempInvTot = isset($cdnurDatavalue->taxable_subtotal)?$cdnurDatavalue->taxable_subtotal:0;
                                                    $cdnur_total +=  $cdnurDatavalue->igst_amount + $cdnurDatavalue->cess_amount;
                                                    $tempTotVal =isset($cdnurDatavalue->invoice_total_value)?$cdnurDatavalue->invoice_total_value:0;
                                                    $tempInv=$cdnurDatavalue->invoice_id;
                                                }
                                                if($tempInv!='' && $tempInv!=$cdnurDatavalue->invoice_id )
                                                {
                                                    $cdnurCount++;
                                                    $cdnur_sumTotal +=$tempTotVal;
                                                    $cdnur_invoice_total_value +=$tempInvTot;
                                                }
                                                
                                            }
                                            ?>
                                            <td>Credit Debit Notes Unregistered</td>
                                            <td align='right'><?php echo $cdnurCount; ?></td>
                                            <td align='right'><?php echo $cdnur_invoice_total_value; ?></td>
                                            <td align='right'><?php echo $cdnur_total; ?></td>
                                            <td align='right'><?php echo $cdnur_sumTotal; ?></td>
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
            document.form2.action = '<?php echo PROJECT_URL; ?>/?page=return_summary&returnmonth=<?php echo $returnmonth; ?>';
                        document.form2.submit();
                    });
                });
</script>