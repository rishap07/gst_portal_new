<?php
$obj_gstr1 = new gstr1();
$obj_client = new client();
if(!$obj_client->can_read('returnfile_list'))
{
    $obj_client->setError($obj_client->getValMsg('can_read'));
    $obj_client->redirect(PROJECT_URL."/?page=dashboard");
    exit();
}
$dataCurrentUserArr = $obj_gstr1->getUserDetailsById( $obj_gstr1->sanitize($_SESSION['user_detail']['user_id']) );
//$obj_gstr1->pr($dataCurrentUserArr['data']);die;
if($dataCurrentUserArr['data']->kyc->vendor_type!='1'){
    $obj_gstr1->setError("Invalid Access to file");
    $obj_gstr1->redirect(PROJECT_URL . "/?page=dashboard");
    exit();
}
if (!isset($_REQUEST['returnmonth']) || $_REQUEST['returnmonth'] == '') {
    $obj_gstr1->redirect(PROJECT_URL . "/?page=return_client");
    exit();
}
if (isset($_POST['returnmonth'])) 
{
    $returnmonth = $_POST['returnmonth'];
    $obj_gstr1->redirect(PROJECT_URL . "/?page=return_filling_summary&returnmonth=" . $returnmonth);
    exit();
}
$returnmonth = date('Y-m');
if ($_REQUEST['returnmonth'] != '') {
    $returnmonth = $_REQUEST['returnmonth'];
}
if(isset($_POST['submit']) && ($_POST['submit']='File with Aadhar' || $_POST['submit']=='File with Digital Signature'))
{
    if (!isset($_SERVER['HTTP_REFERER']) || empty($_SERVER['HTTP_REFERER'])) 
    {
        $obj_gstr1->setError('Invalid access to files');
    } 
    else 
    {
        if ($obj_gstr1->gstr1File()) 
        {
            
        }
    }
}
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr">
    <div class="col-md-11 col-sm-12 col-xs-12 mobpadlr">
        <div class="col-md-12 col-sm-12 col-xs-12 heading">
            <div class="tab col-md-12 col-sm-12 col-xs-12">
                <a href="<?php echo PROJECT_URL . '/?page=return_summary&returnmonth=' . $returnmonth ?>" >1.View GSTR1 Summary</a>

                <a href="<?php echo PROJECT_URL . '/?page=return_view_invoices&returnmonth=' . $returnmonth ?>" >
                2.View My Invoice</a>
                <!-- <a href="<?php echo PROJECT_URL . '/?page=return_upload_invoices&returnmonth=' . $returnmonth ?>">Upload To GSTN</a> -->
                <a href="<?php echo PROJECT_URL . '/?page=return_get_summary&returnmonth=' . $returnmonth ?>">
                    3.GSTR1 SUMMARY
                </a>
                <a href="<?php echo PROJECT_URL . '/?page=return_filling_summary&returnmonth=' . $returnmonth ?>" class="active">4.File GSTr-1</a>
                
                
            </div>
            <div id="upload_invoice" class="tabcontent">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="col-md-6 col-sm-12 col-xs-12"><h3>GSTR-1 Filing Summary</h3></div>
                    <div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="whitebg formboxcontainer">
                                <div class="pull-right rgtdatetxt">
                                    <form method='post' name='form2'>
                                        Month Of Return 
                                        <?php
                                        $dataQuery = "SELECT DATE_FORMAT(invoice_date,'%Y-%m') AS niceDate FROM gst_client_invoice group by nicedate";
                                        $dataRes = $obj_gstr1->get_results($dataQuery);
                                        if (!empty($dataRes)) 
                                        {
                                            ?>
                                            <select class="dateselectbox" id="returnmonth" name="returnmonth">
                                            <?php
                                                foreach ($dataRes as $dataRe) 
                                                {
                                                ?>
                                                    <option value="<?php echo $dataRe->niceDate; ?>" <?php if ($dataRe->niceDate == $returnmonth) { echo 'selected'; } ?>><?php echo $dataRe->niceDate; ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                            <?php
                                        } 
                                        else 
                                        {
                                            ?>
                                            <select class="dateselectbox" id="returnmonth" name="returnmonth">
                                                <option>July 2017</option>
                                            </select>
                                        <?php } ?>
                                    </form>
                                </div>
                                <div class="clearfix height80"></div>
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
                                <!--
								<div class="col-md-6 col-sm-12 col-xs-12">
                                    <button type="button" class="btn btn-default btn-success btnwidth addnew" data-toggle="modal" data-target="#myModal">File GSTR1</button>
                                </div>
								-->
                                <?php
                                }
                                ?>
                                <div class="clearfix"></div>
                                <div class="adminformbx">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="invoice-itemtable" id="mainTable1">
                                        <thead>
                                            <tr>
                                                <th align='left'>TYPE OF INVOICE</th>
                                                <th class="text-right">NO. INVOICES</th>
                                                <th class="text-right">TAXABLE AMT (₹)</th>
                                                <th class="text-right">TAX AMT (₹)</th>
                                                <th class="text-right">THROUGH E-COM (₹)</th>
                                                <th class="text-right">REV.CHARGE (₹)	</th>
                                            </tr>
                                            <tr>
											  <?php
                                          
											$b2bItemquery = "select sum(it.cgst_amount) as cgst_amount,sum(it.sgst_amount) as sgst_amount,sum(it.igst_amount) as igst_amount,sum(it.cess_amount) as cess_amount from " . $obj_client->getTableName('client_invoice') . " i inner join " . $obj_client->getTableName("client_invoice_item") . " it on i.invoice_id=it.invoice_id  where i.invoice_type='taxinvoice' and i.invoice_nature='salesinvoice' and i.added_by='" . $_SESSION['user_detail']['user_id'] . "' and i.status='1' and i.is_canceled='0' and i.is_deleted='0' and i.billing_gstin_number!='' and i.invoice_date like '%" . $returnmonth . "%' and is_gstr1_uploaded='1' ";
                                            $b2bItemData = $obj_client->get_results($b2bItemquery);
											$b2bquery = "select * from " . $obj_client->getTableName('client_invoice') . " where invoice_type='taxinvoice' and invoice_nature='salesinvoice' and added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0' and billing_gstin_number!='' and invoice_date like '%" . $returnmonth . "%' and is_gstr1_uploaded='1'";
                                            $b2bData = $obj_client->get_results($b2bquery);
                                             $b2bTotquery = "select sum(invoice_total_value) as invoice_total_value from " . $obj_client->getTableName('client_invoice') . " where invoice_type='taxinvoice' and invoice_nature='salesinvoice' and added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0' and billing_gstin_number!='' and invoice_date like '%" . $returnmonth . "%' and is_gstr1_uploaded='1'";
                                            $b2bTotData = $obj_client->get_results($b2bTotquery);
                                         
                                            $total = 0;
                                            if (!empty($b2bItemData)) {
                                                $total = $b2bItemData[0]->cgst_amount + $b2bItemData[0]->sgst_amount + $b2bItemData[0]->igst_amount + $b2bItemData[0]->cess_amount;
                                            }
                                            ?>
                                               
                                                <td>B2B</th>
                                                <td align='right'><?php echo count($b2bData); ?></td>
                                                <td align='right'><?php echo (!empty($b2bTotData) && !is_null($b2bTotData[0]->invoice_total_value)) ? $b2bTotData[0]->invoice_total_value : 0; ?></td>
                                                <td align='right'><?php echo $total; ?></td>
                                                <td align='right'><?php echo (!empty($b2bTotData) && !is_null($b2bTotData[0]->invoice_total_value)) ? $b2bTotData[0]->invoice_total_value : 0; ?></td>.
                                                <td align='right'><?php echo (!empty($b2bTotData) && !is_null($b2bTotData[0]->invoice_total_value)) ? $b2bTotData[0]->invoice_total_value : 0; ?></td>
                                            </tr>
<!--                                            <tr>
                                                <td>B2B Amendments</td>
                                                <td align='right'>NA</td>
                                                <td align='right'>NA</td>
                                                <td align='right'>NA</td>
                                                <td align='right'>NA</td>
                                                <td align='right'>NA</td>
                                            </tr>-->
                                            <tr>
                                               <?php
                                            $b2bItemquery = "select sum(it.cgst_amount) as cgst_amount,sum(it.sgst_amount) as sgst_amount,sum(it.igst_amount) as igst_amount,sum(it.cess_amount) as cess_amount from " . $obj_client->getTableName('client_invoice') . " i inner join " . $obj_client->getTableName("client_invoice_item") . " it on i.invoice_id=it.invoice_id  where i.invoice_type='taxinvoice' and i.invoice_nature='salesinvoice' and i.added_by='" . $_SESSION['user_detail']['user_id'] . "' and i.status='1' and i.is_canceled='0' and i.is_deleted='0' and  i.invoice_total_value>'250000' and i.company_state!=i.supply_place  and i.invoice_date like '%" . $returnmonth . "%' and is_gstr1_uploaded='1' ";
                                            $b2bItemData = $obj_client->get_results($b2bItemquery);
                                            $b2bquery = "select * from " . $obj_client->getTableName('client_invoice') . " where invoice_type='taxinvoice' and invoice_nature='salesinvoice' and added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0' and invoice_total_value>'250000' and company_state!=supply_place  and invoice_date like '%" . $returnmonth . "%' and is_gstr1_uploaded='1'";
                                            $b2bData = $obj_client->get_results($b2bquery);
                                          $b2bTotquery = "select sum(invoice_total_value) as invoice_total_value from " . $obj_client->getTableName('client_invoice') . " where invoice_type='taxinvoice' and invoice_nature='salesinvoice' and added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0' and  invoice_total_value>'250000' and company_state!=supply_place and invoice_date like '%" . $returnmonth . "%' and is_gstr1_uploaded='1'";
                                            $b2bTotData = $obj_client->get_results($b2bTotquery);
                                            $total = 0;
                                            if (!empty($b2bItemData)) {
                                                $total = $b2bItemData[0]->cgst_amount + $b2bItemData[0]->sgst_amount + $b2bItemData[0]->igst_amount + $b2bItemData[0]->cess_amount;
                                            }
                                            ?>
                                                <td>B2C Large</td>
                                                <td align='right'><?php echo count($b2bData); ?></td>
                                                <td align='right'><?php echo (!empty($b2bTotData) && !is_null($b2bTotData[0]->invoice_total_value)) ? $b2bTotData[0]->invoice_total_value : 0; ?></td>
                                                <td align='right'><?php echo $total; ?></td>
                                                <td align='right'><?php echo (!empty($b2bTotData) && !is_null($b2bTotData[0]->invoice_total_value)) ? $b2bTotData[0]->invoice_total_value : 0; ?></td>
                                                <td align='right'><?php echo (!empty($b2bTotData) && !is_null($b2bTotData[0]->invoice_total_value)) ? $b2bTotData[0]->invoice_total_value : 0; ?></td>
                                            </tr>
<!--                                            <tr>
                                                <td>B2C Large Amendments</td>
                                                <td align='right'>NA</td>
                                                <td align='right'>NA</td>
                                                <td align='right'>NA</td>
                                                <td align='right'>NA</td>
                                                <td align='right'>NA</td>
                                            </tr>-->
                                            <tr>
                                               <?php
                                            $b2bItemquery = "select sum(it.cgst_amount) as cgst_amount,sum(it.sgst_amount) as sgst_amount,sum(it.igst_amount) as igst_amount,sum(it.cess_amount) as cess_amount from " . $obj_client->getTableName('client_invoice') . " i inner join " . $obj_client->getTableName("client_invoice_item") . " it on i.invoice_id=it.invoice_id  where i.invoice_type='taxinvoice' and i.invoice_nature='salesinvoice' and i.added_by='" . $_SESSION['user_detail']['user_id'] . "' and i.status='1' and i.is_canceled='0' and i.is_deleted='0' and i.billing_gstin_number='' and i.invoice_total_value<='250000'  and i.invoice_date like '%" . $returnmonth . "%' and i.is_gstr1_uploaded='1' ";
                                            $b2bItemData = $obj_client->get_results($b2bItemquery);
                                            $b2bquery = "select * from " . $obj_client->getTableName('client_invoice') . " where invoice_type='taxinvoice' and invoice_nature='salesinvoice' and added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0' and billing_gstin_number='' and invoice_total_value<='250000'  and invoice_date like '%" . $returnmonth . "%' and is_gstr1_uploaded='1'";
                                            $b2bData = $obj_client->get_results($b2bquery);
                                            $b2bTotquery = "select sum(invoice_total_value) as invoice_total_value from " . $obj_client->getTableName('client_invoice') . " where invoice_type='taxinvoice' and invoice_nature='salesinvoice' and added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0' and billing_gstin_number='' and invoice_total_value<='250000'  and invoice_date like '%" . $returnmonth . "%' and is_gstr1_uploaded='1'";
                                            $b2bTotData = $obj_client->get_results($b2bTotquery);
                                            $total = 0;
                                            if (!empty($b2bItemData)) {
                                                $total = $b2bItemData[0]->cgst_amount + $b2bItemData[0]->sgst_amount + $b2bItemData[0]->igst_amount + $b2bItemData[0]->cess_amount;
                                            }
                                            ?>
                                                <td>B2C Small</td>
                                                <td align='right'><?php echo count($b2bData); ?></td>
                                                <td align='right'><?php echo (!empty($b2bTotData) && !is_null($b2bTotData[0]->invoice_total_value)) ? $b2bTotData[0]->invoice_total_value : 0; ?></td>
                                                <td align='right'><?php echo $total; ?></td>
                                                <td align='right'><?php echo (!empty($b2bTotData) && !is_null($b2bTotData[0]->invoice_total_value)) ? $b2bTotData[0]->invoice_total_value : 0; ?></td>
                                                <td align='right'><?php echo (!empty($b2bTotData) && !is_null($b2bTotData[0]->invoice_total_value)) ? $b2bTotData[0]->invoice_total_value : 0; ?></td>
                                            </tr>
<!--                                            <tr>
                                                <td>B2C Small Amendments</td>
                                                <td align='right'>NA</td>
                                                <td align='right'>NA</td>
                                                <td align='right'>NA</td>
                                                <td align='right'>NA</td>
                                                <td align='right'>NA</td>
                                            </tr>-->
                                            <tr>
                                               <?php
                                            $b2bItemquery = "select sum(it.cgst_amount) as cgst_amount,sum(it.sgst_amount) as sgst_amount,sum(it.igst_amount) as igst_amount,sum(it.cess_amount) as cess_amount from " . $obj_client->getTableName('client_invoice') . " i inner join " . $obj_client->getTableName("client_invoice_item") . " it on i.invoice_id=it.invoice_id  where (i.invoice_type='creditnote' or i.invoice_type='debitnote') and i.billing_gstin_number!='' and  i.added_by='" . $_SESSION['user_detail']['user_id'] . "' and i.status='1' and i.is_canceled='0' and i.is_deleted='0'  and i.invoice_date like '%" . $returnmonth . "%' and i.is_gstr1_uploaded='1'";
                                            $b2bItemData = $obj_client->get_results($b2bItemquery);
                                            $b2bquery = "select * from " . $obj_client->getTableName('client_invoice') . " where  (invoice_type='creditnote' or invoice_type='debitnote') and billing_gstin_number!='' and  added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0'  and invoice_date like '%" . $returnmonth . "%' and is_gstr1_uploaded='1' ";
                                            $b2bData = $obj_client->get_results($b2bquery);
                                            $b2bTotquery = "select sum(invoice_total_value) as invoice_total_value from " . $obj_client->getTableName('client_invoice') . " where  (invoice_type='creditnote' or invoice_type='debitnote') and billing_gstin_number!='' and  added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0'  and invoice_date like '%" . $returnmonth . "%' and is_gstr1_uploaded='1'";
                                            $b2bTotData = $obj_client->get_results($b2bTotquery);
                                            $total = 0;
                                            if (!empty($b2bItemData)) {
                                                $total = $b2bItemData[0]->cgst_amount + $b2bItemData[0]->sgst_amount + $b2bItemData[0]->igst_amount + $b2bItemData[0]->cess_amount;
                                            }
                                            ?>
                                                <td>Credit Debit Notes Registered</td>
                                                <td align='right'><?php echo count($b2bData); ?></td>
                                                <td align='right'><?php echo (!empty($b2bTotData) && !is_null($b2bTotData[0]->invoice_total_value)) ? $b2bTotData[0]->invoice_total_value : 0; ?></td>
                                                <td align='right'><?php echo $total; ?></td>
                                                <td align='right'><?php echo (!empty($b2bTotData) && !is_null($b2bTotData[0]->invoice_total_value)) ? $b2bTotData[0]->invoice_total_value : 0; ?></td>
                                                <td class="text-right">0</td>
                                            </tr>
<!--                                            <tr>
                                                <td>Credit Debit Notes Amendments Registered</td>
                                                <td align='right'>NA</td>
                                                <td align='right'>NA</td>
                                                <td align='right'>NA</td>
                                                <td align='right'>NA</td>
                                                <td align='right'>NA</td>
                                            </tr>-->
<!--                                            <tr>
                                                <td>NIL</td>
                                                <td>1</td>
                                                <td class="text-right">201.15</td>
                                                <td class="text-right">14.05</td>
                                                <td class="text-right">0.0</td>
                                                <td class="text-right">0.0</td>
                                            </tr>-->
                                            <tr>
                                               <?php
                                            $b2bItemquery = "select sum(it.cgst_amount) as cgst_amount,sum(it.sgst_amount) as sgst_amount,sum(it.igst_amount) as igst_amount,sum(it.cess_amount) as cess_amount from " . $obj_client->getTableName('client_invoice') . " i inner join " . $obj_client->getTableName("client_invoice_item") . " it on i.invoice_id=it.invoice_id  where (i.invoice_type='exportinvoice' or i.invoice_type='sezunitinvoice' or i.invoice_type='deemedexportinvoice') and i.invoice_nature='salesinvoice' and i.added_by='" . $_SESSION['user_detail']['user_id'] . "' and i.status='1' and i.is_canceled='0' and i.is_deleted='0'  and i.invoice_date like '%" . $returnmonth . "%' and i.is_gstr1_uploaded='1'";
                                            $b2bItemData = $obj_client->get_results($b2bItemquery);
                                            $b2bquery = "select * from " . $obj_client->getTableName('client_invoice') . " where (invoice_type='exportinvoice' or invoice_type='sezunitinvoice' or invoice_type='deemedexportinvoice') and invoice_nature='salesinvoice' and added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0'  and invoice_date like '%" . $returnmonth . "%' and is_gstr1_uploaded='1'  ";
                                            $b2bData = $obj_client->get_results($b2bquery);
                                            $b2bTotquery = "select sum(invoice_total_value) as invoice_total_value from " . $obj_client->getTableName('client_invoice') . " where (invoice_type='exportinvoice' or invoice_type='sezunitinvoice' or invoice_type='deemedexportinvoice') and  invoice_nature='salesinvoice' and added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0'  and invoice_date like '%" . $returnmonth . "%' and is_gstr1_uploaded='1'  ";
                                            $b2bTotData = $obj_client->get_results($b2bTotquery);
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
                                                <td class="text-right">0</td>
                                            </tr>
<!--                                            <tr>
                                                <td>Export Amendments</td>
                                                <td align='right'>NA</td>
                                                <td align='right'>NA</td>
                                                <td align='right'>NA</td>
                                                <td align='right'>NA</td>
                                                <td align='right'>NA</td>
                                            </tr>-->
                                            <tr>
                                                <?php
                                            $b2bItemquery = "select sum(it.cgst_amount) as cgst_amount,sum(it.sgst_amount) as sgst_amount,sum(it.igst_amount) as igst_amount,sum(it.cess_amount) as cess_amount from " . $obj_client->getTableName('client_invoice') . " i inner join " . $obj_client->getTableName("client_invoice_item") . " it on i.invoice_id=it.invoice_id  where i.invoice_type='receiptvoucherinvoice' and i.added_by='" . $_SESSION['user_detail']['user_id'] . "' and i.status='1' and i.is_canceled='0' and i.is_deleted='0'  and i.invoice_date like '%" . $returnmonth . "%' and i.is_gstr1_uploaded='1'";
                                            $b2bItemData = $obj_client->get_results($b2bItemquery);
                                            $b2bquery = "select * from " . $obj_client->getTableName('client_invoice') . " where invoice_type='receiptvoucherinvoice' and added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0'  and invoice_date like '%" . $returnmonth . "%' and is_gstr1_uploaded='1' ";
                                            $b2bData = $obj_client->get_results($b2bquery);
                                            $b2bTotquery = "select sum(invoice_total_value) as invoice_total_value from " . $obj_client->getTableName('client_invoice') . " where invoice_type='receiptvoucherinvoice' and added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0'  and invoice_date like '%" . $returnmonth . "%' and is_gstr1_uploaded='1'";
                                            $b2bTotData = $obj_client->get_results($b2bTotquery);
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
                                                <td class="text-right">0</td>
                                            </tr>
<!--                                            <tr>
                                                <td>Advance Tax Amendments</td>
                                                <td align='right'>NA</td>
                                                <td align='right'>NA</td>
                                                <td align='right'>NA</td>
                                                <td align='right'>NA</td>
                                                <td align='right'>NA</td>
                                            </tr>-->
                                            <tr>
                                               <?php
                                            $b2bItemquery = "select sum(it.cgst_amount) as cgst_amount,sum(it.sgst_amount) as sgst_amount,sum(it.igst_amount) as igst_amount,sum(it.cess_amount) as cess_amount from " . $obj_client->getTableName('client_invoice') . " i inner join " . $obj_client->getTableName("client_invoice_item") . " it on i.invoice_id=it.invoice_id  where (i.invoice_type='creditnote' or i.invoice_type='debitnote') and i.billing_gstin_number='' and  i.added_by='" . $_SESSION['user_detail']['user_id'] . "' and i.status='1' and i.is_canceled='0' and i.is_deleted='0'  and i.invoice_date like '%" . $returnmonth . "%' and is_gstr1_uploaded='1'";
                                            $b2bItemData = $obj_client->get_results($b2bItemquery);
                                            $b2bquery = "select * from " . $obj_client->getTableName('client_invoice') . " where(invoice_type='creditnote' or invoice_type='debitnote') and billing_gstin_number='' and  added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0'  and invoice_date like '%" . $returnmonth . "%' and is_gstr1_uploaded='1' ";
                                            $b2bData = $obj_client->get_results($b2bquery);
                                            $b2bTotquery = "select sum(invoice_total_value) as invoice_total_value from " . $obj_client->getTableName('client_invoice') . " where (invoice_type='creditnote' or invoice_type='debitnote') and billing_gstin_number='' and  added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0'  and invoice_date like '%" . $returnmonth . "%' and is_gstr1_uploaded='1'";
                                            $b2bTotData = $obj_client->get_results($b2bTotquery);
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
                                                <td class="text-right">0</td>
                                            </tr>
<!--                                            <tr>
                                                <td>Credit Debit Notes Amendments Unregistered</td>
                                                <td align='right'>NA</td>
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
                <div id="upload_invoice" class="tabcontent">
                    
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg" style="margin-top:20%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">E-Filing</h4>
            </div>
            <div class="modal-body">
                <div style="width:50%; margin: auto">
                    <form method="post">
                        <input type="submit" name="submit" value="File with Aadhar" class="btn btn-default btn-success btnwidth addnew">
                        <input type="submit" name="submit" value="File with Digital Signature" class="btn btn-default btn-success btnwidth addnew" style="margin-right: 20px;">

                    </form>
                </div>
                <div class="clearfix height80"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function () {
    $('#returnmonth').on('change', function () {
        document.form2.action = '<?php echo PROJECT_URL; ?>/?page=return_filling_summary&returnmonth=<?php echo $returnmonth; ?>';
        document.form2.submit();
    });
});
</script>