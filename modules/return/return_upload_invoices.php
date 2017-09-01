<?php
$obj_gstr1 = new gstr1();
$dataCurrentUserArr = $obj_gstr1->getUserDetailsById( $obj_gstr1->sanitize($_SESSION['user_detail']['user_id']) );
//$obj_gstr1->pr($dataCurrentUserArr['data']);die;
if($dataCurrentUserArr['data']->kyc->vendor_type!='1'){
    $obj_gstr1->setError("Invalid Access to file");
    $obj_gstr1->redirect(PROJECT_URL . "/?page=dashboard");
    exit();
}
if (!isset($_REQUEST['returnmonth']) || $_REQUEST['returnmonth'] == '') 
{
    $obj_gstr1->redirect(PROJECT_URL . "/?page=return_client");
    exit();
}
if (isset($_POST['returnmonth'])) 
{
    $returnmonth = $_POST['returnmonth'];
    $obj_gstr1->redirect(PROJECT_URL . "/?page=return_upload_invoices&returnmonth=" . $returnmonth);
    exit();
}
if(isset($_POST['submit']) && $_POST['submit']=='Upload TO GSTN')
{
    if (!isset($_SERVER['HTTP_REFERER']) || empty($_SERVER['HTTP_REFERER'])) 
    {
        $obj_gstr1->setError('Invalid access to files');
    } 
    else 
    {
        if ($obj_gstr1->gstr1Upload()) 
        {
        }
    }
}
$returnmonth = '2017-07';
if ($_REQUEST['returnmonth'] != '') {
    $returnmonth = $_REQUEST['returnmonth'];
}
?>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr">
    <div class="col-md-11 col-sm-12 col-xs-12 mobpadlr">
        <div class="col-md-12 col-sm-12 col-xs-12 heading">
            <div class="tab col-md-12 col-sm-12 col-xs-12">
                <a href="<?php echo PROJECT_URL . '/?page=return_summary&returnmonth=' . $returnmonth ?>" >
                    View GSTR1 Summary
                </a>   
                <a href="<?php echo PROJECT_URL . '/?page=return_view_invoices&returnmonth=' . $returnmonth ?>">
                    View My Invoice
                </a>
                <a href="<?php echo PROJECT_URL . '/?page=return_upload_invoices&returnmonth=' . $returnmonth ?>" class="active" >
                    Upload To GSTN
                </a>
                </a>
                <a href="<?php echo PROJECT_URL . '/?page=return_get_summary&returnmonth=' . $returnmonth ?>">
                    GSTR1 SUMMARY
                </a>
                <a href="<?php echo PROJECT_URL . '/?page=return_filling_summary&returnmonth=' . $returnmonth ?>">
                    File GSTr-1
                </a>
               
                             
            </div>

            <div id="upload_invoice" class="tabcontent">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="col-md-6 col-sm-12 col-xs-12"><h3>Upload Summary</h3></div>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="whitebg formboxcontainer">
                            <div class="pull-right rgtdatetxt">
                                <form method='post' name='form2'>
                                    Month Of Return 
                                    <?php
                                    $dataQuery = "SELECT DATE_FORMAT(invoice_date,'%Y-%m') AS niceDate FROM gst_client_invoice group by nicedate";
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
                            <div class="clearfix"></div>
                            <?php $obj_gstr1->showErrorMessage(); ?>
                            <?php $obj_gstr1->showSuccessMessge(); ?>
                            <?php $obj_gstr1->unsetMessage(); ?>
                             <div class="clearfix"></div>
                            <?php
                            $dataReturns = $obj_gstr1->get_results("select * from ".TAB_PREFIX."return where return_month='".$returnmonth."' and client_id='".$_SESSION['user_detail']['user_id']."' and status='3' and type='gstr1'");
                            if(!empty($dataReturns))
                            {
                            ?>
                            <div id="sucmsg" style="background-color:#DBEDDF;border-radius:4px;padding:8px 35px 8px 14px;text-shadow:0 1px 0 rgba(255, 255, 255, 0.5);margin-bottom:18px;border-color:#D1E8DA;color:#39A25F;"><i class="fa fa-check"></i> <b>Success:</b> GSTR1 is Already Filed</div>
                            <?php
                            }
                            else
                            {
                            ?>
                            <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                                <form method="post" style="width:auto; display: inline-block;">
                                    <input type="submit" name="submit" value="Upload TO GSTN" class="btn btn-default btn-success btnwidth">
                                </form>
                                <form style="width:auto; display: inline-block;" method="post" action ="<?php echo PROJECT_URL.'/?ajax=return_gstr_payload';?>&returnmonth=<?php echo $returnmonth; ?>">
                                    <!-- <input type="submit" name="submit" value="Download GSTR1" class="btn btn-orange btnwidth" style="margin-top: 0px;"> -->
                                    <input type="submit" name="submit" value="Download GSTR1" class="btn btn-default btn-warning btnwidth">
                                </form>

                            </div>
                           
                            <?php
                            }
                            ?>
                            
                            <div class="clearfix"></div>
                            <div class="adminformbx">
                                <?php
                                if(empty($dataReturns))
                                {
                                ?>
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
                                            $b2bData = $obj_gstr1->getB2BInvoices($_SESSION['user_detail']['user_id'], $returnmonth);
                                            $b2b_total = $b2b_invoice_total_value = $b2b_sumTotal = 0;
                                            if (!empty($b2bData)) {
                                                foreach ($b2bData as $key => $b2bDatavalue) {
                                                    $b2b_invoice_total_value += isset($b2bDatavalue->invoice_total_value)?$b2bDatavalue->invoice_total_value:0;
                                                    $b2b_total += $b2bDatavalue->cgst_amount + $b2bDatavalue->sgst_amount + $b2bDatavalue->igst_amount + $b2bDatavalue->cess_amount;
 
                                                }
                                                
                                                $b2b_sumTotal = $b2b_invoice_total_value + $b2b_total;
                                            }


                                            ?>
                                            <td>B2B</th>
                                            <td align='right'><?php echo count($b2bData); ?></td>
                                            <td align='right'><?php echo $b2b_invoice_total_value; ?></td>
                                            <td align='right'><?php echo $b2b_total; ?></td>
                                            <td align='right'><?php echo $b2b_sumTotal; ?></td>
                                        </tr>
                                        <tr>
                                            <?php
                                            $b2clData = $obj_gstr1->getB2CLInvoices($_SESSION['user_detail']['user_id'], $returnmonth);
                                            $b2cl_total = $b2cl_invoice_total_value = $b2cl_sumTotal = 0;
                                            if (!empty($b2clData)) {
                                                foreach ($b2clData as $key => $b2clDatavalue) {
                                                    $b2cl_invoice_total_value += isset($b2clDatavalue->invoice_total_value)?$b2clDatavalue->invoice_total_value:0;
                                                    $b2cl_total += $b2clDatavalue->cgst_amount + $b2clDatavalue->sgst_amount + $b2clDatavalue->igst_amount + $b2clDatavalue->cess_amount;
 
                                                }
                                                
                                                $b2cl_sumTotal = $b2cl_invoice_total_value + $b2cl_total;
                                            }

                                            ?>
                                            <td>B2C Large</td>
                                            <td align='right'><?php echo count($b2clData); ?></td>
                                            <td align='right'><?php echo $b2cl_invoice_total_value; ?></td>
                                            <td align='right'><?php echo $b2cl_total; ?></td>
                                            <td align='right'><?php echo $b2cl_sumTotal; ?></td>
                                        </tr>
                                        <tr>
                                            <?php
                                            $b2csData = $obj_gstr1->getB2CSInvoices($_SESSION['user_detail']['user_id'], $returnmonth);
                                            $b2cs_total = $b2cs_invoice_total_value = $b2cs_sumTotal = 0;
                                            if (!empty($b2csData)) {
                                                foreach ($b2csData as $key => $b2csDatavalue) {
                                                    $b2cs_invoice_total_value += isset($b2csDatavalue->invoice_total_value)?$b2csDatavalue->invoice_total_value:0;
                                                    $b2cs_total += $b2csDatavalue->cgst_amount + $b2csDatavalue->sgst_amount + $b2csDatavalue->igst_amount + $b2csDatavalue->cess_amount;
 
                                                }
                                                
                                                $b2cs_sumTotal = $b2cs_invoice_total_value + $b2cs_total;
                                            }

                                            ?>
                                            <td>B2C Small</td>
                                            <td align='right'><?php echo count($b2csData); ?></td>
                                            <td align='right'><?php echo $b2cs_invoice_total_value; ?></td>
                                            <td align='right'><?php echo $b2cs_total; ?></td>
                                            <td align='right'><?php echo $b2cs_sumTotal; ?></td>
                                        </tr>
                                        <tr>
                                            <?php
                                            $cdnrData = $obj_gstr1->getCDNRInvoices($_SESSION['user_detail']['user_id'], $returnmonth);

                                            $cdnr_total = $cdnr_invoice_total_value = $cdnr_sumTotal = 0;
                                            if (!empty($cdnrData)) {
                                                foreach ($cdnrData as $key => $cdnrDatavalue) {
                                                    $cdnr_invoice_total_value += isset($cdnrDatavalue->invoice_total_value)?$cdnrDatavalue->invoice_total_value:0;
                                                    $cdnr_total += $cdnrDatavalue->cgst_amount + $cdnrDatavalue->sgst_amount + $cdnrDatavalue->igst_amount + $cdnrDatavalue->cess_amount;
 
                                                }
                                                
                                                $cdnr_sumTotal = $cdnr_invoice_total_value + $cdnr_total;
                                            }
                                            ?>
                                            <td>Credit Debit Notes Registered</td>
                                            <td align='right'><?php echo count($cdnrData); ?></td>
                                            <td align='right'><?php echo $cdnr_invoice_total_value; ?></td>
                                            <td align='right'><?php echo $cdnr_total; ?></td>
                                            <td align='right'><?php echo $cdnr_sumTotal; ?></td>
                                        </tr>
                                        <tr>
                                            <?php
                                            $expData = $obj_gstr1->getEXPInvoices($_SESSION['user_detail']['user_id'], $returnmonth);

                                            $exp_total = $exp_invoice_total_value = $exp_sumTotal = 0;
                                            if (!empty($expData)) {
                                                foreach ($expData as $key => $expDatavalue) {
                                                    $exp_invoice_total_value += isset($expDatavalue->invoice_total_value)?$expDatavalue->invoice_total_value:0;
                                                    $exp_total += $expDatavalue->cgst_amount + $expDatavalue->sgst_amount + $expDatavalue->igst_amount + $expDatavalue->cess_amount;
 
                                                }
                                                
                                                $exp_sumTotal = $exp_invoice_total_value + $exp_total;
                                            }
                                            ?>
                                            <td>Export</td>
                                            <td align='right'><?php echo count($expData); ?></td>
                                            <td align='right'><?php echo $exp_invoice_total_value; ?></td>
                                            <td align='right'><?php echo $exp_total; ?></td>
                                            <td align='right'><?php echo $exp_sumTotal; ?></td>
                                        </tr>
                                        <tr>
                                            <?php
                                            $atData = $obj_gstr1->getATInvoices($_SESSION['user_detail']['user_id'], $returnmonth);

                                            $at_total = $at_invoice_total_value = $at_sumTotal = 0;
                                            if (!empty($atData)) {
                                                foreach ($atData as $key => $atDatavalue) {
                                                    $at_invoice_total_value += isset($atDatavalue->invoice_total_value)?$atDatavalue->invoice_total_value:0;
                                                    $at_total += $atDatavalue->cgst_amount + $atDatavalue->sgst_amount + $atDatavalue->igst_amount + $atDatavalue->cess_amount;
 
                                                }
                                                
                                                $at_sumTotal = $at_invoice_total_value + $at_total;
                                            }
                                            ?>
                                            <td>Advance Tax</td>
                                            <td align='right'><?php echo count($atData); ?></td>
                                            <td align='right'><?php echo $at_invoice_total_value; ?></td>
                                            <td align='right'><?php echo $at_total; ?></td>
                                            <td align='right'><?php echo $at_sumTotal; ?></td>
                                        </tr>
                                        <tr>
                                            <?php
                                            $cdnurData = $obj_gstr1->getCDNURInvoices($_SESSION['user_detail']['user_id'], $returnmonth);
                                            $cdnur_total = $cdnur_invoice_total_value = $cdnur_sumTotal = 0;
                                            if (!empty($cdnurData)) {
                                                foreach ($cdnurData as $key => $cdnurDatavalue) {
                                                    $cdnur_invoice_total_value += isset($cdnurDatavalue->invoice_total_value)?$cdnurDatavalue->invoice_total_value:0;
                                                    $cdnur_total +=  $cdnurDatavalue->igst_amount + $cdnurDatavalue->cess_amount;
 
                                                }
                                                
                                                $cdnur_sumTotal = $cdnur_invoice_total_value + $cdnur_total;
                                            }
                                            ?>
                                            <td>Credit Debit Notes Unregistered</td>
                                            <td align='right'><?php echo count($cdnurData); ?></td>
                                            <td align='right'><?php echo $cdnur_invoice_total_value; ?></td>
                                            <td align='right'><?php echo $cdnur_total; ?></td>
                                            <td align='right'><?php echo $cdnur_sumTotal; ?></td>
                                        </tr>
                                    </thead>
                                </table>
                                <?php
                                }
                                ?>
                            </div>  
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#returnmonth').on('change', function () {
            document.form2.action = '<?php echo PROJECT_URL; ?>/?page=return_upload_invoices&returnmonth=<?php echo $returnmonth; ?>';
            document.form2.submit();
        });
    });
</script>