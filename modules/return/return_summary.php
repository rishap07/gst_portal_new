<?php
//$obj_gstr1 = new client();
$obj_gstr1 = new gstr1();
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
            <div class="tab col-md-12 col-sm-12 col-xs-12">
                
                <a href="<?php echo PROJECT_URL . '/?page=return_summary&returnmonth=' . $returnmonth ?>" class="active">
                    View GSTR1 Summary
                </a>
                <a href="<?php echo PROJECT_URL . '/?page=return_view_invoices&returnmonth=' . $returnmonth ?>" >
                    View My Invoice
                </a>
                <a href="<?php echo PROJECT_URL . '/?page=return_upload_invoices&returnmonth=' . $returnmonth ?>">
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
            <div id="London" class="tabcontent">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="col-md-6 col-sm-12 col-xs-12"><h3>GSTR1 Summary</h3></div>
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
                                            $b2bTotData = $obj_gstr1->getB2BInvoices($_SESSION['user_detail']['user_id'], $returnmonth,'all');
                                            $total = $invoice_total_value = $sumTotal = 0;
                                            if (!empty($b2bTotData)) {
                                                foreach ($b2bTotData as $key => $b2bTotDatavalue) {
                                                    $invoice_total_value += isset($b2bTotDatavalue->invoice_total_value)?$b2bTotDatavalue->invoice_total_value:0;
                                                    $total += $b2bTotDatavalue->cgst_amount + $b2bTotDatavalue->sgst_amount + $b2bTotDatavalue->igst_amount + $b2bTotDatavalue->cess_amount;
 
                                                }
                                                
                                                $sumTotal = $invoice_total_value + $total;
                                            }


                                            ?>
                                            <td>B2B</th>
                                            <td align='right'><?php echo count($b2bTotData); ?></td>
                                            <td align='right'><?php echo $invoice_total_value; ?></td>
                                            <td align='right'><?php echo $total; ?></td>
                                            <td align='right'><?php echo $sumTotal; ?></td>
                                        </tr>
                                        <tr>
                                            <?php
                                            $b2clData = $obj_gstr1->getB2CLInvoices($_SESSION['user_detail']['user_id'], $returnmonth,'all');
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
                                            $b2csData = $obj_gstr1->getB2CSInvoices($_SESSION['user_detail']['user_id'], $returnmonth,'all');
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
                                            /*$b2bItemquery = "select sum(it.cgst_amount) as cgst_amount,sum(it.sgst_amount) as sgst_amount,sum(it.igst_amount) as igst_amount,sum(it.cess_amount) as cess_amount from " . $obj_gstr1->getTableName('client_invoice') . " i inner join " . $obj_gstr1->getTableName("client_invoice_item") . " it on i.invoice_id=it.invoice_id  where (i.invoice_type='creditnote' or i.invoice_type='debitnote') and i.billing_gstin_number!='' and  i.added_by='" . $_SESSION['user_detail']['user_id'] . "' and i.status='1' and i.is_canceled='0' and i.is_deleted='0'  and i.invoice_date like '%" . $returnmonth . "%'";
                                            $b2bItemData = $obj_gstr1->get_results($b2bItemquery);
                                            $b2bquery = "select * from " . $obj_gstr1->getTableName('client_invoice') . " where  (invoice_type='creditnote' or invoice_type='debitnote') and billing_gstin_number!='' and  added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0'  and invoice_date like '%" . $returnmonth . "%' ";
                                            $b2bData = $obj_gstr1->get_results($b2bquery);
                                            $b2bTotquery = "select sum(invoice_total_value) as invoice_total_value from " . $obj_gstr1->getTableName('client_invoice') . " where  (invoice_type='creditnote' or invoice_type='debitnote') and billing_gstin_number!='' and  added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0'  and invoice_date like '%" . $returnmonth . "%'";
                                            $b2bTotData = $obj_gstr1->get_results($b2bTotquery);
                                            $total = 0;
                                            if (!empty($b2bItemData)) {
                                                $total = $b2bItemData[0]->cgst_amount + $b2bItemData[0]->sgst_amount + $b2bItemData[0]->igst_amount + $b2bItemData[0]->cess_amount;
                                            }*/
                                            $cdnrData = $obj_gstr1->getCDNRInvoices($_SESSION['user_detail']['user_id'], $returnmonth,'all');
                                            $obj_gstr1->pr($cdnrData);
                                            
                                            $cdnr_total = $cdnr_invoice_total_value = $cdnr_sumTotal = 0;
                                            if (!empty($b2csData)) {
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
                                            $b2bItemquery = "select sum(it.cgst_amount) as cgst_amount,sum(it.sgst_amount) as sgst_amount,sum(it.igst_amount) as igst_amount,sum(it.cess_amount) as cess_amount from " . $obj_gstr1->getTableName('client_invoice') . " i inner join " . $obj_gstr1->getTableName("client_invoice_item") . " it on i.invoice_id=it.invoice_id  where (i.invoice_type='exportinvoice' or i.invoice_type='sezunitinvoice' or i.invoice_type='deemedexportinvoice') and i.invoice_nature='salesinvoice' and i.added_by='" . $_SESSION['user_detail']['user_id'] . "' and i.status='1' and i.is_canceled='0' and i.is_deleted='0'  and i.invoice_date like '%" . $returnmonth . "%'";
                                            $b2bItemData = $obj_gstr1->get_results($b2bItemquery);
                                            $b2bquery = "select * from " . $obj_gstr1->getTableName('client_invoice') . " where (invoice_type='exportinvoice' or invoice_type='sezunitinvoice' or invoice_type='deemedexportinvoice') and invoice_nature='salesinvoice' and added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0'  and invoice_date like '%" . $returnmonth . "%'  ";
                                            $b2bData = $obj_gstr1->get_results($b2bquery);
                                            $b2bTotquery = "select sum(invoice_total_value) as invoice_total_value from " . $obj_gstr1->getTableName('client_invoice') . " where (invoice_type='exportinvoice' or invoice_type='sezunitinvoice' or invoice_type='deemedexportinvoice') and  invoice_nature='salesinvoice' and added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0'  and invoice_date like '%" . $returnmonth . "%'  ";
                                            $b2bTotData = $obj_gstr1->get_results($b2bTotquery);
                                            $total = 0;
                                            if (!empty($b2bItemData)) {
                                                $total = $b2bItemData[0]->cgst_amount + $b2bItemData[0]->sgst_amount + $b2bItemData[0]->igst_amount + $b2bItemData[0]->cess_amount;
                                            }
                                            ?>
                                            <td>Export</td>
                                            <td align='right'><?php echo count($b2bData); ?></td>
                                            <td align='right'><?php echo (!empty($b2bTotData) && !is_null($b2bTotData[0]->invoice_total_value)) ? $b2bTotData[0]->invoice_total_value : 0; ?></td>
                                            <td align='right'><?php echo $total; ?></td>
                                            <td align='right'><?php echo (!empty($b2bTotData) && !is_null($b2bTotData[0]->invoice_total_value)) ? $b2bTotData[0]->invoice_total_value : 0; ?></td>
                                        </tr>
<!--                                        <tr>
                                            <td>Export Amendments</td>
                                            <td align='right'>NA</td>
                                            <td align='right'>NA</td>
                                            <td align='right'>NA</td>
                                            <td align='right'>NA</td>
                                        </tr>-->
                                        <tr>
                                            <?php
                                            $b2bItemquery = "select sum(it.cgst_amount) as cgst_amount,sum(it.sgst_amount) as sgst_amount,sum(it.igst_amount) as igst_amount,sum(it.cess_amount) as cess_amount from " . $obj_gstr1->getTableName('client_invoice') . " i inner join " . $obj_gstr1->getTableName("client_invoice_item") . " it on i.invoice_id=it.invoice_id  where i.invoice_type='receiptvoucherinvoice' and i.added_by='" . $_SESSION['user_detail']['user_id'] . "' and i.status='1' and i.is_canceled='0' and i.is_deleted='0'  and i.invoice_date like '%" . $returnmonth . "%'";
                                            $b2bItemData = $obj_gstr1->get_results($b2bItemquery);
                                            $b2bquery = "select * from " . $obj_gstr1->getTableName('client_invoice') . " where invoice_type='receiptvoucherinvoice' and added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0'  and invoice_date like '%" . $returnmonth . "%' ";
                                            $b2bData = $obj_gstr1->get_results($b2bquery);
                                            $b2bTotquery = "select sum(invoice_total_value) as invoice_total_value from " . $obj_gstr1->getTableName('client_invoice') . " where invoice_type='receiptvoucherinvoice' and added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0'  and invoice_date like '%" . $returnmonth . "%'";
                                            $b2bTotData = $obj_gstr1->get_results($b2bTotquery);
                                            $total = 0;
                                            if (!empty($b2bItemData)) {
                                                $total = $b2bItemData[0]->cgst_amount + $b2bItemData[0]->sgst_amount + $b2bItemData[0]->igst_amount + $b2bItemData[0]->cess_amount;
                                            }
                                            ?>
                                            <td>Advance Tax</td>
                                            <td align='right'><?php echo count($b2bData); ?></td>
                                            <td align='right'><?php echo (!empty($b2bTotData) && !is_null($b2bTotData[0]->invoice_total_value)) ? $b2bTotData[0]->invoice_total_value : 0; ?></td>
                                            <td align='right'><?php echo $total; ?></td>
                                            <td align='right'><?php echo (!empty($b2bTotData) && !is_null($b2bTotData[0]->invoice_total_value)) ? $b2bTotData[0]->invoice_total_value : 0; ?></td>
                                        </tr>
<!--                                        <tr>
                                            <td>Advance Tax Amendments</td>
                                            <td align='right'>NA</td>
                                            <td align='right'>NA</td>
                                            <td align='right'>NA</td>
                                            <td align='right'>NA</td>
                                        </tr>-->
                                        <tr>
                                            <?php
                                            $b2bItemquery = "select sum(it.cgst_amount) as cgst_amount,sum(it.sgst_amount) as sgst_amount,sum(it.igst_amount) as igst_amount,sum(it.cess_amount) as cess_amount from " . $obj_gstr1->getTableName('client_invoice') . " i inner join " . $obj_gstr1->getTableName("client_invoice_item") . " it on i.invoice_id=it.invoice_id  where (i.invoice_type='creditnote' or i.invoice_type='debitnote') and i.billing_gstin_number='' and  i.added_by='" . $_SESSION['user_detail']['user_id'] . "' and i.status='1' and i.is_canceled='0' and i.is_deleted='0'  and i.invoice_date like '%" . $returnmonth . "%'";
                                            $b2bItemData = $obj_gstr1->get_results($b2bItemquery);
                                            $b2bquery = "select * from " . $obj_gstr1->getTableName('client_invoice') . " where(invoice_type='creditnote' or invoice_type='debitnote') and billing_gstin_number='' and  added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0'  and invoice_date like '%" . $returnmonth . "%' ";
                                            $b2bData = $obj_gstr1->get_results($b2bquery);
                                            $b2bTotquery = "select sum(invoice_total_value) as invoice_total_value from " . $obj_gstr1->getTableName('client_invoice') . " where (invoice_type='creditnote' or invoice_type='debitnote') and billing_gstin_number='' and  added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0'  and invoice_date like '%" . $returnmonth . "%'";
                                            $b2bTotData = $obj_gstr1->get_results($b2bTotquery);
                                            $total = 0;
                                            if (!empty($b2bItemData)) {
                                                $total = $b2bItemData[0]->cgst_amount + $b2bItemData[0]->sgst_amount + $b2bItemData[0]->igst_amount + $b2bItemData[0]->cess_amount;
                                            }
                                            ?>
                                            <td>Credit Debit Notes Unregistered</td>
                                            <td align='right'><?php echo count($b2bData); ?></td>
                                            <td align='right'><?php echo (!empty($b2bTotData) && !is_null($b2bTotData[0]->invoice_total_value)) ? $b2bTotData[0]->invoice_total_value : 0; ?></td>
                                            <td align='right'><?php echo $total; ?></td>
                                            <td align='right'><?php echo (!empty($b2bTotData) && !is_null($b2bTotData[0]->invoice_total_value)) ? $b2bTotData[0]->invoice_total_value : 0; ?></td>
                                        </tr>
<!--                                        <tr>
                                            <td>Credit Debit Notes Amendments Unregistered</td>
                                            <td align='right'>NA</td>
                                            <td align='right'>NA</td>
                                            <td align='right'>NA</td>
                                            <td align='right'>NA</td>
                                        </tr>-->
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