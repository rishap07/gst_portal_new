<?php
$obj_gstr1 = new gstr1();
if (!isset($_REQUEST['returnmonth']) || $_REQUEST['returnmonth'] == '') 
{
    $obj_gstr1->redirect(PROJECT_URL . "/?page=return_client");
    exit();
}
if (isset($_POST['returnmonth'])) 
{
    $returnmonth = $_POST['returnmonth'];
    $obj_gstr1->redirect(PROJECT_URL . "/?page=return_gstr1a_upload_invoices&returnmonth=" . $returnmonth);
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
                  <a href="<?php echo PROJECT_URL . '/?page=return_gstr1a_download&returnmonth=' . $returnmonth ?>" >
                    View GSTR1 Summary
                </a>
                <a href="<?php echo PROJECT_URL . '/?page=return_gstr1a_reconcile&returnmonth=' . $returnmonth ?>"  >
                    View My Invoice
                </a>
                <a href="<?php echo PROJECT_URL . '/?page=return_gstr1a_upload_invoices&returnmonth=' . $returnmonth ?>" class="active">
                    Upload GSTR1A
                </a>
                </a>
                <a href="<?php echo PROJECT_URL . '/?page=return_gstr1a_filling_summary&returnmonth=' . $returnmonth ?>">
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
                              <div class="clear"></div>
                            <?php $obj_gstr1->showErrorMessage(); ?>
                            <?php $obj_gstr1->showSuccessMessge(); ?>
                            <?php $obj_gstr1->unsetMessage(); ?>
                             <div class="clear"></div>
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
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <form method="post">
                                    <input type="submit" name="submit" value="Upload GSTR1A" class="btn btn-default btn-success btnwidth addnew">
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
                                            <th style="text-align:right">Total Amount ( <i class="fa fa-inr"></i> )</th>
                                        </tr>
                                        <tr>
                                            <?php
                                            $b2bItemquery = "select sum(it.cgst_amount) as cgst_amount,sum(it.sgst_amount) as sgst_amount,sum(it.igst_amount) as igst_amount,sum(it.cess_amount) as cess_amount from " . $obj_gstr1->getTableName('client_invoice') . " i inner join " . $obj_gstr1->getTableName("client_invoice_item") . " it on i.invoice_id=it.invoice_id  where i.invoice_nature='salesinvoice' and i.added_by='" . $_SESSION['user_detail']['user_id'] . "' and i.status='1' and i.is_canceled='0' and i.is_deleted='0' and i.billing_gstin_number!='' and i.invoice_date like '%" . $returnmonth . "%'  and is_gstr1_uploaded='0'";
                                            $b2bItemData = $obj_gstr1->get_results($b2bItemquery);
                                            $b2bquery = "select * from " . $obj_gstr1->getTableName('client_invoice') . " where invoice_nature='salesinvoice' and added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0' and billing_gstin_number!='' and invoice_date like '%" . $returnmonth . "%'  and is_gstr1_uploaded='0'";
                                            $b2bData = $obj_gstr1->get_results($b2bquery);
                                            $b2bTotquery = "select sum(invoice_total_value) as invoice_total_value from " . $obj_gstr1->getTableName('client_invoice') . " where invoice_nature='salesinvoice' and added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0' and billing_gstin_number!='' and invoice_date like '%" . $returnmonth . "%'  and is_gstr1_uploaded='0'";
                                            $b2bTotData = $obj_gstr1->get_results($b2bTotquery);
                                            $total = 0;
                                            if (!empty($b2bItemData)) {
                                                $total = $b2bItemData[0]->cgst_amount + $b2bItemData[0]->sgst_amount + $b2bItemData[0]->igst_amount + $b2bItemData[0]->cess_amount;
                                            }
                                            ?>
                                            <td>B2B</th>
                                            <td align='right'><?php echo !empty($b2bData) ? count($b2bData): 0; ?></td>
                                            <td align='right'><?php echo (!empty($b2bTotData) && !is_null($b2bTotData[0]->invoice_total_value)) ? $b2bTotData[0]->invoice_total_value : 0; ?></td>
                                            <td align='right'><?php echo $total; ?></td>
                                            <td align='right'><?php echo (!empty($b2bTotData) && !is_null($b2bTotData[0]->invoice_total_value)) ? $b2bTotData[0]->invoice_total_value : 0; ?></td>
                                        </tr>
                                        <tr>
                                            <?php
                                            $b2bItemquery = "select sum(it.cgst_amount) as cgst_amount,sum(it.sgst_amount) as sgst_amount,sum(it.igst_amount) as igst_amount,sum(it.cess_amount) as cess_amount from " . $obj_gstr1->getTableName('client_invoice') . " i inner join " . $obj_gstr1->getTableName("client_invoice_item") . " it on i.invoice_id=it.invoice_id  where i.invoice_nature='salesinvoice' and i.added_by='" . $_SESSION['user_detail']['user_id'] . "' and i.status='1' and i.is_canceled='0' and i.is_deleted='0' and i.billing_gstin_number='' and i.invoice_total_value>'250000'  and i.invoice_date like '%" . $returnmonth . "%'  and is_gstr1_uploaded='0'";
                                            $b2bItemData = $obj_gstr1->get_results($b2bItemquery);
                                            $b2bquery = "select * from " . $obj_gstr1->getTableName('client_invoice') . " where invoice_nature='salesinvoice' and added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0' and billing_gstin_number='' and invoice_total_value>'250000'  and invoice_date like '%" . $returnmonth . "%'  and is_gstr1_uploaded='0'";
                                            $b2bData = $obj_gstr1->get_results($b2bquery);
                                            $b2bTotquery = "select sum(invoice_total_value) as invoice_total_value from " . $obj_gstr1->getTableName('client_invoice') . " where invoice_nature='salesinvoice' and added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0' and billing_gstin_number='' and invoice_total_value>'250000'  and invoice_date like '%" . $returnmonth . "%'  and is_gstr1_uploaded='0'";
                                            $b2bTotData = $obj_gstr1->get_results($b2bTotquery);
                                            $total = 0;
                                            if (!empty($b2bItemData)) {
                                                $total = $b2bItemData[0]->cgst_amount + $b2bItemData[0]->sgst_amount + $b2bItemData[0]->igst_amount + $b2bItemData[0]->cess_amount;
                                            }
                                            ?>
                                            <td>B2C Large</td>
                                            <td align='right'><?php echo !empty($b2bData) ? count($b2bData): 0; ?></td>
                                            <td align='right'><?php echo (!empty($b2bTotData) && !is_null($b2bTotData[0]->invoice_total_value)) ? $b2bTotData[0]->invoice_total_value : 0; ?></td>
                                            <td align='right'><?php echo $total; ?></td>
                                            <td align='right'><?php echo (!empty($b2bTotData) && !is_null($b2bTotData[0]->invoice_total_value)) ? $b2bTotData[0]->invoice_total_value : 0; ?></td>
                                        </tr>
                                        <tr>
                                            <?php
                                            $b2bItemquery = "select sum(it.cgst_amount) as cgst_amount,sum(it.sgst_amount) as sgst_amount,sum(it.igst_amount) as igst_amount,sum(it.cess_amount) as cess_amount from " . $obj_gstr1->getTableName('client_invoice') . " i inner join " . $obj_gstr1->getTableName("client_invoice_item") . " it on i.invoice_id=it.invoice_id  where i.invoice_nature='salesinvoice' and i.added_by='" . $_SESSION['user_detail']['user_id'] . "' and i.status='1' and i.is_canceled='0' and i.is_deleted='0' and i.billing_gstin_number='' and i.invoice_total_value<='250000'  and i.invoice_date like '%" . $returnmonth . "%'  and i.is_gstr1_uploaded='0'";
                                            $b2bItemData = $obj_gstr1->get_results($b2bItemquery);
                                            $b2bquery = "select * from " . $obj_gstr1->getTableName('client_invoice') . " where invoice_nature='salesinvoice' and added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0' and billing_gstin_number='' and invoice_total_value<='250000'  and invoice_date like '%" . $returnmonth . "%'  and is_gstr1_uploaded='0'";
                                            $b2bData = $obj_gstr1->get_results($b2bquery);
                                            $b2bTotquery = "select sum(invoice_total_value) as invoice_total_value from " . $obj_gstr1->getTableName('client_invoice') . " where invoice_nature='salesinvoice' and added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0' and billing_gstin_number='' and invoice_total_value<='250000'  and invoice_date like '%" . $returnmonth . "%'  and is_gstr1_uploaded='0'";
                                            $b2bTotData = $obj_gstr1->get_results($b2bTotquery);
                                            $total = 0;
                                            if (!empty($b2bItemData)) {
                                                $total = $b2bItemData[0]->cgst_amount + $b2bItemData[0]->sgst_amount + $b2bItemData[0]->igst_amount + $b2bItemData[0]->cess_amount;
                                            }
                                            ?>
                                            <td>B2C Small</td>
                                            <td align='right'><?php echo !empty($b2bData) ? count($b2bData): 0; ?></td>
                                            <td align='right'><?php echo (!empty($b2bTotData) && !is_null($b2bTotData[0]->invoice_total_value)) ? $b2bTotData[0]->invoice_total_value : 0; ?></td>
                                            <td align='right'><?php echo $total; ?></td>
                                            <td align='right'><?php echo (!empty($b2bTotData) && !is_null($b2bTotData[0]->invoice_total_value)) ? $b2bTotData[0]->invoice_total_value : 0; ?></td>
                                        </tr>
                                        <tr>
                                            <?php
                                            $b2bItemquery = "select sum(it.cgst_amount) as cgst_amount,sum(it.sgst_amount) as sgst_amount,sum(it.igst_amount) as igst_amount,sum(it.cess_amount) as cess_amount from " . $obj_gstr1->getTableName('client_rt_invoice') . " i inner join " . $obj_gstr1->getTableName("client_rt_invoice_item") . " it on i.invoice_id=it.invoice_id  where (i.invoice_document_nature='creditnote' or i.invoice_document_nature='debitnote') and i.billing_gstin_number!='' and  i.added_by='" . $_SESSION['user_detail']['user_id'] . "' and i.status='1' and i.is_canceled='0' and i.is_deleted='0'  and i.invoice_date like '%" . $returnmonth . "%'";
                                            $b2bItemData = $obj_gstr1->get_results($b2bItemquery);
                                            $b2bquery = "select * from " . $obj_gstr1->getTableName('client_rt_invoice') . " where (invoice_document_nature='creditnote' or invoice_document_nature='debitnote') and billing_gstin_number!='' and  added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0'  and invoice_date like '%" . $returnmonth . "%'";
                                            $b2bData = $obj_gstr1->get_results($b2bquery);
                                            $b2bTotquery = "select sum(invoice_total_value) as invoice_total_value from " . $obj_gstr1->getTableName('client_rt_invoice') . " where (invoice_document_nature='creditnote' or invoice_document_nature='debitnote') and billing_gstin_number!='' and  added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0'  and invoice_date like '%" . $returnmonth . "%'";
                                            $b2bTotData = $obj_gstr1->get_results($b2bTotquery);
                                            $total = 0;
                                            if (!empty($b2bItemData)) {
                                                $total = $b2bItemData[0]->cgst_amount + $b2bItemData[0]->sgst_amount + $b2bItemData[0]->igst_amount + $b2bItemData[0]->cess_amount;
                                            }
                                            ?>
                                            <td>Credit Debit Notes Registered</td>
                                            <td align='right'><?php echo !empty($b2bData) ? count($b2bData): 0; ?></td>
                                            <td align='right'><?php echo (!empty($b2bTotData) && !is_null($b2bTotData[0]->invoice_total_value)) ? $b2bTotData[0]->invoice_total_value : 0; ?></td>
                                            <td align='right'><?php echo $total; ?></td>
                                            <td align='right'><?php echo (!empty($b2bTotData) && !is_null($b2bTotData[0]->invoice_total_value)) ? $b2bTotData[0]->invoice_total_value : 0; ?></td>
                                        </tr>
                                        <tr>
                                            <?php
                                            $b2bItemquery = "select sum(it.cgst_amount) as cgst_amount,sum(it.sgst_amount) as sgst_amount,sum(it.igst_amount) as igst_amount,sum(it.cess_amount) as cess_amount from " . $obj_gstr1->getTableName('client_invoice') . " i inner join " . $obj_gstr1->getTableName("client_invoice_item") . " it on i.invoice_id=it.invoice_id  where i.invoice_nature='salesinvoice' and i.added_by='" . $_SESSION['user_detail']['user_id'] . "' and i.status='1' and i.is_canceled='0' and i.is_deleted='0'  and i.invoice_date like '%" . $returnmonth . "%' and i.invoice_type='exportinvoice'  and i.is_gstr1_uploaded='0'";
                                            $b2bItemData = $obj_gstr1->get_results($b2bItemquery);
                                            $b2bquery = "select * from " . $obj_gstr1->getTableName('client_invoice') . " where invoice_nature='salesinvoice' and added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0'  and invoice_date like '%" . $returnmonth . "%'  and invoice_type='exportinvoice' and is_gstr1_uploaded='0'";
                                            $b2bData = $obj_gstr1->get_results($b2bquery);
                                            $b2bTotquery = "select sum(invoice_total_value) as invoice_total_value from " . $obj_gstr1->getTableName('client_invoice') . " where invoice_nature='salesinvoice' and added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0'  and invoice_date like '%" . $returnmonth . "%'  and invoice_type='exportinvoice' and is_gstr1_uploaded='0'";
                                            $b2bTotData = $obj_gstr1->get_results($b2bTotquery);
                                            $total = 0;
                                            if (!empty($b2bItemData)) {
                                                $total = $b2bItemData[0]->cgst_amount + $b2bItemData[0]->sgst_amount + $b2bItemData[0]->igst_amount + $b2bItemData[0]->cess_amount;
                                            }
                                            ?>
                                            <td>Export</td>
                                            <td align='right'><?php echo !empty($b2bData) ? count($b2bData): 0; ?></td>
                                            <td align='right'><?php echo (!empty($b2bTotData) && !is_null($b2bTotData[0]->invoice_total_value)) ? $b2bTotData[0]->invoice_total_value : 0; ?></td>
                                            <td align='right'><?php echo $total; ?></td>
                                            <td align='right'><?php echo (!empty($b2bTotData) && !is_null($b2bTotData[0]->invoice_total_value)) ? $b2bTotData[0]->invoice_total_value : 0; ?></td>
                                        </tr>
                                        <tr>
                                            <?php
                                            $b2bItemquery = "select sum(it.cgst_amount) as cgst_amount,sum(it.sgst_amount) as sgst_amount,sum(it.igst_amount) as igst_amount,sum(it.cess_amount) as cess_amount from " . $obj_gstr1->getTableName('client_rv_invoice') . " i inner join " . $obj_gstr1->getTableName("client_rv_invoice_item") . " it on i.invoice_id=it.invoice_id  where  i.added_by='" . $_SESSION['user_detail']['user_id'] . "' and i.status='1' and i.is_canceled='0' and i.is_deleted='0'  and i.invoice_date like '%" . $returnmonth . "%'";
                                            $b2bItemData = $obj_gstr1->get_results($b2bItemquery);
                                            $b2bquery = "select * from " . $obj_gstr1->getTableName('client_rv_invoice') . " where added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0'  and invoice_date like '%" . $returnmonth . "%' ";
                                            $b2bData = $obj_gstr1->get_results($b2bquery);
                                            $b2bTotquery = "select sum(invoice_total_value) as invoice_total_value from " . $obj_gstr1->getTableName('client_rv_invoice') . " where added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0'  and invoice_date like '%" . $returnmonth . "%'";
                                            $b2bTotData = $obj_gstr1->get_results($b2bTotquery);
                                            $total = 0;
                                            if (!empty($b2bItemData)) {
                                                $total = $b2bItemData[0]->cgst_amount + $b2bItemData[0]->sgst_amount + $b2bItemData[0]->igst_amount + $b2bItemData[0]->cess_amount;
                                            }
                                            ?>
                                            <td>Advance Tax</td>
                                            <td align='right'><?php echo !empty($b2bData) ? count($b2bData): 0; ?></td>
                                            <td align='right'><?php echo (!empty($b2bTotData) && !is_null($b2bTotData[0]->invoice_total_value)) ? $b2bTotData[0]->invoice_total_value : 0; ?></td>
                                            <td align='right'><?php echo $total; ?></td>
                                            <td align='right'><?php echo (!empty($b2bTotData) && !is_null($b2bTotData[0]->invoice_total_value)) ? $b2bTotData[0]->invoice_total_value : 0; ?></td>
                                        </tr>
                                        <tr>
                                            <?php
                                            $b2bItemquery = "select sum(it.cgst_amount) as cgst_amount,sum(it.sgst_amount) as sgst_amount,sum(it.igst_amount) as igst_amount,sum(it.cess_amount) as cess_amount from " . $obj_gstr1->getTableName('client_rt_invoice') . " i inner join " . $obj_gstr1->getTableName("client_rt_invoice_item") . " it on i.invoice_id=it.invoice_id  where (i.invoice_document_nature='creditnote' or i.invoice_document_nature='debitnote') and i.billing_gstin_number='' and  i.added_by='" . $_SESSION['user_detail']['user_id'] . "' and i.status='1' and i.is_canceled='0' and i.is_deleted='0'  and i.invoice_date like '%" . $returnmonth . "%'";
                                            $b2bItemData = $obj_gstr1->get_results($b2bItemquery);
                                            $b2bquery = "select * from " . $obj_gstr1->getTableName('client_rt_invoice') . " where (invoice_document_nature='creditnote' or invoice_document_nature='debitnote') and billing_gstin_number='' and  added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0'  and invoice_date like '%" . $returnmonth . "%' ";
                                            $b2bData = $obj_gstr1->get_results($b2bquery);
                                            $b2bTotquery = "select sum(invoice_total_value) as invoice_total_value from " . $obj_gstr1->getTableName('client_rt_invoice') . " where (invoice_document_nature='creditnote' or invoice_document_nature='debitnote') and billing_gstin_number='' and  added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0'  and invoice_date like '%" . $returnmonth . "%'";
                                            $b2bTotData = $obj_gstr1->get_results($b2bTotquery);
                                            $total = 0;
                                            if (!empty($b2bItemData)) {
                                                $total = $b2bItemData[0]->cgst_amount + $b2bItemData[0]->sgst_amount + $b2bItemData[0]->igst_amount + $b2bItemData[0]->cess_amount;
                                            }
                                            ?>
                                            <td>Credit Debit Notes Unregistered</td>
                                            <td align='right'><?php echo !empty($b2bData) ? count($b2bData): 0; ?></td>
                                            <td align='right'><?php echo (!empty($b2bTotData) && !is_null($b2bTotData[0]->invoice_total_value)) ? $b2bTotData[0]->invoice_total_value : 0; ?></td>
                                            <td align='right'><?php echo $total; ?></td>
                                            <td align='right'><?php echo (!empty($b2bTotData) && !is_null($b2bTotData[0]->invoice_total_value)) ? $b2bTotData[0]->invoice_total_value : 0; ?></td>
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
            document.form2.action = '<?php echo PROJECT_URL; ?>/?page=return_gstr1a_upload_invoices&returnmonth=<?php echo $returnmonth; ?>';
            document.form2.submit();
        });
    });
</script>