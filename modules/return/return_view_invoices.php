<?php
//session_destroy();
$obj_gstr1 = new gstr1();
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
$returnmonth = '2017-07';
if ($_REQUEST['returnmonth'] != '') {
    $returnmonth = $_REQUEST['returnmonth'];
}
$type='B2B';

if(isset($_POST['invoice_type']))
{
    $type=$_POST['invoice_type'];
}
//$obj_gstr1->pr($_POST);
if($type=="B2B")
{
	if (isset($_POST['returnmonth'])) 
    {
        $returnmonth = $_POST['returnmonth'];

        $obj_gstr1->redirect(PROJECT_URL . "/?page=return_view_invoices&returnmonth=" . $returnmonth);
        exit();
    }
}

//$obj_gstr1->pr($_POST);

if((isset($_POST['submit_up']) && $_POST['submit_up']=='Upload TO GSTN') || isset($_POST['name']))
{

   if(isset($_POST['name']) && $_POST['name']!='')
    {
        $ids = implode(',',  $_POST['name']);
        if(!empty($ids)) {
            if ($obj_gstr1->gstr1Upload($ids)) 
            {

            }
        }   
    }
    else {
        $obj_gstr1->setError('Sorry! No Invoices are selected');
    }
    
}



?>

<link type="text/css" rel="stylesheet" href="<?php echo THEME_URL; ?>/css/bootstrap-multiselect.css"/>
<script type="text/javascript" src="<?php echo THEME_URL; ?>/js/bootstrap-multiselect.js"></script>
    <div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr">

    <div class="col-md-11 col-sm-12 col-xs-12 mobpadlr">
        <div class="col-md-12 col-sm-12 col-xs-12 heading">
            <div class="tab col-md-12 col-sm-12 col-xs-12">
                <a href="<?php echo PROJECT_URL . '/?page=return_summary&returnmonth=' . $returnmonth ?>" >
                    View GSTR1 Summary
                </a>
                <a href="<?php echo PROJECT_URL . '/?page=return_view_invoices&returnmonth=' . $returnmonth ?>" class="active">
                    View My Invoice
                </a>
                <a href="<?php echo PROJECT_URL . '/?page=return_upload_invoices&returnmonth=' . $returnmonth ?>">
                    Upload To GSTN
                </a>
                <a href="<?php echo PROJECT_URL . '/?page=return_get_summary&returnmonth=' . $returnmonth ?>">
                    GSTR1 SUMMARY
                </a>
                
                <a href="<?php echo PROJECT_URL . '/?page=return_filling_summary&returnmonth=' . $returnmonth ?>">
                    File GSTr-1
                </a>
            </div>
            <div id="view_invoice" class="tabcontent">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="col-md-6 col-sm-12 col-xs-12"><h3>My Invoices</h3></div>
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
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <a class='btn btn-default btn-success btnwidth' style="margin-top:-71px;"  href='<?php echo PROJECT_URL; ?>/?page=client_create_invoice'>Add New Invoice</a>
                            </div>  
                            <div class="clearfix"></div>
                            <?php $obj_gstr1->showErrorMessage(); ?>
                            <?php $obj_gstr1->showSuccessMessge(); ?>
                            <?php $obj_gstr1->unsetMessage(); ?>
                            <?php
                            $flag=0;
                            $dataReturns = $obj_gstr1->get_results("select * from ".TAB_PREFIX."return where return_month='".$returnmonth."' and client_id='".$_SESSION['user_detail']['user_id']."' and status='3' and type='gstr1'");
                            if(!empty($dataReturns))
                            {
                                $flag=0;
                                ?>
                                <div id="sucmsg" style="background-color:#DBEDDF;border-radius:4px;padding:8px 35px 8px 14px;text-shadow:0 1px 0 rgba(255, 255, 255, 0.5);margin-bottom:18px;border-color:#D1E8DA;color:#39A25F;">
                                    <i class="fa fa-check"></i> <b>Success:</b> GSTR1 is Already Filed
                                </div>
                                <?php
                            }
                            else
                            {
                                $flag=1;
                            ?> 
                            <?php
                            }
                            ?>
                            <form method='post' name='form3'>
                            <div class="invoice-types"><div class="invoice-types__heading">Types</div>

                                <div class="invoice-types__content">
                                    <label for="invoice-types__B2B"><input type="radio" id="invoice-types__B2B" name="invoice_type" value="B2B" class="type" <?php if(isset($_POST['invoice_type']) && $_POST['invoice_type']=='B2B'){ echo 'checked=""';}else{echo 'checked=""';}?>>B2B</label>
                                    <label for="invoice-types__B2CL"><input type="radio" id="invoice-types__B2CL" name="invoice_type" value="B2CL" class="type" <?php if(isset($_POST['invoice_type']) && $_POST['invoice_type']=='B2CL'){ echo 'checked=""';}?>>B2CL</label>
                                    <label for="invoice-types__B2CS"><input type="radio" id="invoice-types__B2CS" name="invoice_type" value="B2CS" class="type" <?php if(isset($_POST['invoice_type']) && $_POST['invoice_type']=='B2CS'){ echo 'checked=""';}?>>B2CS</label>

                                    <label for="invoice-types__CDNR"><input type="radio" id="invoice-types__CDNR" name="invoice_type" value="CDNR" class="type" <?php if(isset($_POST['invoice_type']) && $_POST['invoice_type']=='CDNR') echo 'checked=""';?>>CDNR</label>
                                    <label for="invoice-types__CDNUR"><input type="radio" id="invoice-types__CDNUR" name="invoice_type" value="CDNUR" class="type" <?php if(isset($_POST['invoice_type']) && $_POST['invoice_type']=='CDNUR') echo 'checked=""';?>>CDNUR</label>
                                    <label for="invoice-types__AT"><input type="radio" id="invoice-types__AT" name="invoice_type" value="AT" class="type" <?php if(isset($_POST['invoice_type']) && $_POST['invoice_type']=='AT') echo 'checked=""';?>>AT</label>
                                    <label for="invoice-types__EXP"><input type="radio" id="invoice-types__EXP" name="invoice_type" value="EXP" class="type" <?php if(isset($_POST['invoice_type']) && $_POST['invoice_type']=='EXP') echo 'checked=""';?>>EXP</label>
                                    <!-- <label for="invoice-types__summary"><input type="radio" id="invoice-types__summary" name="invoice_type" value="all" class="type" <?php if(isset($_POST['invoice_type']) && $_POST['invoice_type']=='all') echo 'checked=""';?>>All Type Summary</label> -->

                                </div>
                            </div>
                            </form>
                            <form style="width:auto; display: inline-block;margin-bottom:10px;" method="post" name="form4" id="UploadForm">

                                <?php
                                $invCount= 0;
                                $taxableTotal= 0;
                                $igstTotal= 0;
                                $cgstTotal= 0;
                                $sgstTotal= 0;
                                $cessTotal= 0;
                                $invTotal=0;
                                $Data = $b2bData = $b2clData = $b2csData = $cdnrData = $cdnurData = $atData = $expData = array();
                                if($type=='B2B')
                                {
                                    $Data = $b2bData = $obj_gstr1->getB2BInvoices($_SESSION['user_detail']['user_id'], $returnmonth);
                                    $total = $invoice_total_value = $sumTotal = $igstTotal = $sgstTotal = $cgstTotal = $cessTotal = 0;
                                    if (!empty($b2bData)) {
                                        foreach ($b2bData as $key => $b2bDatavalue) {
                                            $invoice_total_value += isset($b2bDatavalue->taxable_subtotal)?$b2bDatavalue->taxable_subtotal:0;
                                            $total += $b2bDatavalue->cgst_amount + $b2bDatavalue->sgst_amount + $b2bDatavalue->igst_amount + $b2bDatavalue->cess_amount;
                                            $igstTotal += $b2bDatavalue->igst_amount;
                                            $sgstTotal += $b2bDatavalue->sgst_amount;
                                            $cgstTotal += $b2bDatavalue->cgst_amount;
                                            $cessTotal += $b2bDatavalue->cess_amount;
                                            $sumTotal += isset($b2bDatavalue->invoice_total_value)?$b2bDatavalue->invoice_total_value:0;
                                        }

                                        //$sumTotal = $invoice_total_value + $total;
                                        $invCount = count($b2bData);
                                    }
                                    // /$obj_gstr1->pr($b2bData);    
                                }
                                if($type=='B2CL')
                                {
                                    $Data = $b2clData = $obj_gstr1->getB2CLInvoices($_SESSION['user_detail']['user_id'], $returnmonth);
                                    $total = $invoice_total_value = $sumTotal = $igstTotal = $sgstTotal = $cgstTotal = $cessTotal = 0;
                                    if (!empty($b2clData)) {
                                        foreach ($b2clData as $key => $b2clDatavalue) {
                                            $invoice_total_value += isset($b2clDatavalue->taxable_subtotal)?$b2clDatavalue->taxable_subtotal:0;
                                            $total += $b2clDatavalue->cgst_amount + $b2clDatavalue->sgst_amount + $b2clDatavalue->igst_amount + $b2clDatavalue->cess_amount;
                                            $igstTotal += $b2clDatavalue->igst_amount;
                                            $sgstTotal += $b2clDatavalue->sgst_amount;
                                            $cgstTotal += $b2clDatavalue->cgst_amount;
                                            $cessTotal += $b2clDatavalue->cess_amount;
                                            $sumTotal += isset($b2clDatavalue->invoice_total_value)?$b2clDatavalue->invoice_total_value:0;
                                        }

                                        //$sumTotal = $invoice_total_value + $total;
                                        $invCount = count($b2clData);
                                    }

                                }
                                if($type=='B2CS')
                                {
                                    $Data = $b2csData = $obj_gstr1->getB2CSInvoices($_SESSION['user_detail']['user_id'], $returnmonth);
                                    $total = $invoice_total_value = $sumTotal = $igstTotal = $sgstTotal = $cgstTotal = $cessTotal = 0;
                                    if (!empty($b2csData)) {
                                        foreach ($b2csData as $key => $b2csDatavalue) {
                                            $invoice_total_value += isset($b2csDatavalue->taxable_subtotal)?$b2csDatavalue->taxable_subtotal:0;
                                            $total += $b2csDatavalue->cgst_amount + $b2csDatavalue->sgst_amount + $b2csDatavalue->igst_amount + $b2csDatavalue->cess_amount;
                                            $igstTotal += $b2csDatavalue->igst_amount;
                                            $sgstTotal += $b2csDatavalue->sgst_amount;
                                            $cgstTotal += $b2csDatavalue->cgst_amount;
                                            $cessTotal += $b2csDatavalue->cess_amount;
                                            $sumTotal += isset($b2csDatavalue->invoice_total_value)?$b2csDatavalue->invoice_total_value:0;
                                        }


                                        $invCount = count($b2csData);
                                    }

                                }
                                if($type=='CDNR')
                                {
                                    $Data = $cdnrData = $obj_gstr1->getCDNRInvoices($_SESSION['user_detail']['user_id'], $returnmonth);
                                    $total = $invoice_total_value = $sumTotal = $igstTotal = $sgstTotal = $cgstTotal = $cessTotal = 0;
                                    if (!empty($cdnrData)) {
                                        foreach ($cdnrData as $key => $cdnrDatavalue) {
                                            $invoice_total_value += isset($cdnrDatavalue->taxable_subtotal)?$cdnrDatavalue->taxable_subtotal:0;
                                            $total += $cdnrDatavalue->cgst_amount + $cdnrDatavalue->sgst_amount + $cdnrDatavalue->igst_amount + $cdnrDatavalue->cess_amount;
                                            $igstTotal += $cdnrDatavalue->igst_amount;
                                            $sgstTotal += $cdnrDatavalue->sgst_amount;
                                            $cgstTotal += $cdnrDatavalue->cgst_amount;
                                            $cessTotal += $cdnrDatavalue->cess_amount;
                                            $sumTotal += isset($cdnrDatavalue->invoice_total_value)?$cdnrDatavalue->invoice_total_value:0;

                                        }

                                       //$sumTotal = $invoice_total_value + $total;
                                       $invCount = count($cdnrData);
                                    }

                                }
                                if($type=='CDNUR')
                                {
                                    $Data = $cdnurData = $obj_gstr1->getCDNURInvoices($_SESSION['user_detail']['user_id'], $returnmonth);
                                    $total = $invoice_total_value = $sumTotal = $igstTotal = $sgstTotal = $cgstTotal = $cessTotal = 0;
                                    if (!empty($cdnurData)) {
                                        foreach ($cdnurData as $key => $cdnurDatavalue) {
                                            $invoice_total_value += isset($cdnurDatavalue->taxable_subtotal)?$cdnurDatavalue->taxable_subtotal:0;
                                            $total +=  $cdnurDatavalue->igst_amount + $cdnurDatavalue->cess_amount;
                                            $igstTotal += $cdnurDatavalue->igst_amount;
                                            $sgstTotal += $cdnurDatavalue->sgst_amount;
                                            $cgstTotal += $cdnurDatavalue->cgst_amount;
                                            $cessTotal += $cdnurDatavalue->cess_amount;
                                            $sumTotal += isset($cdnurDatavalue->invoice_total_value)?$cdnurDatavalue->invoice_total_value:0;
                                        }

                                        //$sumTotal = $invoice_total_value + $total;
                                        $invCount = count($cdnurData);
                                    }

                                }
                                if($type=='AT')
                                {
                                    $Data = $atData = $obj_gstr1->getATInvoices($_SESSION['user_detail']['user_id'], $returnmonth);
                                    $total = $invoice_total_value = $sumTotal = $igstTotal = $sgstTotal = $cgstTotal = $cessTotal = 0;
                                    if (!empty($atData)) {
                                        foreach ($atData as $key => $atDatavalue) {
                                            $invoice_total_value += isset($atDatavalue->taxable_subtotal)?$atDatavalue->taxable_subtotal:0;
                                            $total += $atDatavalue->cgst_amount + $atDatavalue->sgst_amount + $atDatavalue->igst_amount + $atDatavalue->cess_amount;
                                            $igstTotal += $atDatavalue->igst_amount;
                                            $sgstTotal += $atDatavalue->sgst_amount;
                                            $cgstTotal += $atDatavalue->cgst_amount;
                                            $cessTotal += $atDatavalue->cess_amount;
                                            $sumTotal += isset($atDatavalue->invoice_total_value)?$atDatavalue->invoice_total_value:0;
                                        }
                                        $invCount = count($atData);
                                    }

                                }
                                if($type=='EXP')
                                {
                                    $Data = $expData = $obj_gstr1->getEXPInvoices($_SESSION['user_detail']['user_id'], $returnmonth);
                                    $total = $invoice_total_value = $sumTotal = $igstTotal = $sgstTotal = $cgstTotal = $cessTotal = 0;
                                    if (!empty($expData)) {
                                        foreach ($expData as $key => $expDatavalue) {
                                            $invoice_total_value += isset($expDatavalue->taxable_subtotal)?$expDatavalue->taxable_subtotal:0;
                                            $total += $expDatavalue->cgst_amount + $expDatavalue->sgst_amount + $expDatavalue->igst_amount + $expDatavalue->cess_amount;
                                            $igstTotal += $expDatavalue->igst_amount;
                                            $sgstTotal += $expDatavalue->sgst_amount;
                                            $cgstTotal += $expDatavalue->cgst_amount;
                                            $cessTotal += $expDatavalue->cess_amount;
                                            $sumTotal += isset($expDatavalue->invoice_total_value)?$expDatavalue->invoice_total_value:0;
                                        }
                                        $invCount = count($expData);
                                    }
                                }
                                if($invCount >0) {
                                ?>
                                <?php
                                    if($flag==1)
                                    {
                                    ?>
                                        <div style="text-align: center;">
                                            <input type="submit" name="submit_up" id="up" value="Upload TO GSTN" class="btn  btn-success " >

                                            <input type="submit" name="submit_dwn" id="down" value="Download GSTR1" class="btn btn-warning ">
                                            
                                        </div>
                                        <div class="clear"></div><br>
                                    <?php } 
                                }
                                ?>
                                <table width="100%" border="0" cellspacing="0" cellpadding="0"  class="table table-striped invoice-filter-table" id="mainTable1">
                                    <thead>
                                        <tr>
                                            <th align='left'>Total Transactions</th>
                                            <th align='left'>Taxable Amount </th>
                                            <th align='left'>Total IGST</th>
                                            <th align='left'>Total SGST</th>
                                            <th align='left'>Total CGST</th>
                                            <th align='left'>Total Cess</th>
                                            <th align='left'>Total Amount</th>
                                        </tr>
                                        <tr>
                                            <td><?php echo $invCount;?></td>
                                            <td><?php echo $invoice_total_value;?></td>
                                            <td><?php echo $igstTotal; ?></td>
                                            <td><?php echo $sgstTotal; ?></td>
                                            <td><?php echo $cgstTotal; ?></td>
                                            <td><?php echo $cessTotal; ?></td>
                                            <td><?php echo $sumTotal; ?></td>
                                        </tr>
                                    </thead>
                                </table>
                                <br/>
                                    <div class="adminformbx">
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="invoice-itemtable" id="mainTable1">
                                            <thead>
                                                <tr>
                                                    <th align='left'><input name="select_all" value="1" id="example-select-all" type="checkbox" />
                                                    </th>
                                                    <th align='left'>No.</th>
                                                    <th align='left'>Date</th>
                                                    <th align='left'>Invoice Number</th>
                                                    <th align='left'>Customer</th>
                                                    <th align='left'>GSTIN</th>
                                                    <th style='text-align:right'>Taxable AMT</th>
                                                    <th style='text-align:right'>Total Tax</th>
                                                    <th style='text-align:right'>Total Amt</th>
                                                    <th align='center'>Type</th>
                                                    <th align='center'>Status</th>
                                                    <th align='center'></th>
                                                </tr>
                                                <?php
                                                if($invCount >0) {
                                                    if(!empty($type))
                                                    {
                                                        if(!empty($Data))
                                                        {
                                                            $flag=1;
                                                            $i=1;
                                                            foreach($Data as $Item)
                                                            {
                                                                ?>
                                                                <tr>
                                                                    <td align="center" bgcolor="#FFFFFF">
                                                                       <input type="checkbox" class="name" name="name[]" value="<?php echo $Item->invoice_id;?>"/> 
                                                                    </td>
                                                                    <td align='left'><?php echo $i++;?></td>
                                                                    <td align='left'><?php echo $Item->invoice_date;?></td>
                                                                    <td align='left'><?php echo $Item->reference_number;?></td>
                                                                    <td align='left'><?php echo $Item->billing_name;?></td>
                                                                    <td align='left'><?php echo $Item->billing_gstin_number;?></td>
                                                                    <td style='text-align:right'><?php echo $Item->taxable_subtotal;?></td>
                                                                    <td style='text-align:right'><?php echo $Item->cgst_amount + $Item->sgst_amount + $Item->igst_amount + $Item->cess_amount;?></td>
                                                                    <td style='text-align:right'><?php echo $Item->invoice_total_value;?></td>
                                                                    <td align='center'><?php echo $type; ?></td>
                                                                    <td align='center'><?php echo (isset($Item->is_gstr1_uploaded) && $Item->is_gstr1_uploaded=='0') ? 'Pending':'Uploaded';?></td>
                                                                    <?php 
                                                                    //$obj_gstr1->pr($Item->is_gstr1_uploaded);
                                                                    $url = 'javascript:;';
                                                                    if($type == 'B2B' || $type == 'B2CL' || $type == 'B2CS') {
                                                                        if($Item->invoice_type == 'taxinvoice') {
                                                                            $url = PROJECT_URL.'/?page=client_update_invoice&action=editInvoice&id='.$Item->invoice_id;
                                                                        }
                                                                        if($Item->invoice_type == 'sezunitinvoice' || $Item->invoice_type == 'deemedexportinvoice' ) {
                                                                            $url = PROJECT_URL.'/?page=client_update_export_invoice&action=editInvoice&id='.$Item->invoice_id;
                                                                        }
                                                                        
                                                                    }
                                                                    if($type == 'AT') {
                                                                        $url = PROJECT_URL.'/?page=client_update_receipt_voucher_invoice&action=editRVInvoice&id='.$Item->invoice_id;
                                                                    }
                                                                    if($type == 'EXP') {
                                                                        $url = PROJECT_URL.'/?page=client_update_export_invoice&action=editInvoice&id='.$Item->invoice_id;
                                                                    }
                                                                    if($type == 'CDNR' || $type == 'CDNUR'  ) {
                                                                        if($Item->invoice_type == 'creditnote' || $Item->invoice_type == 'debitnote') {
                                                                            $url = PROJECT_URL.'/?page=client_update_revised_tax_invoice&action=editRTInvoice&id='.$Item->invoice_id;
                                                                        }
                                                                        if($Item->invoice_type == 'refundvoucherinvoice') {
                                                                            $url = PROJECT_URL.'/?page=client_refund_voucher_invoice_list&action=viewRFInvoice&id='.$Item->invoice_id;
                                                                        }
                                                                        
                                                                    }
                                                                    
                                                                    ?>
                                                                    <td align='center'><a href="<?php echo $url; ?>" target="_blank">View</a></td>
                                                                </tr>
                                                                <?php
                                                            }
                                                        }						
                                                    }
                                                }
                                                else { ?>
                                                    <tr>
                                                        <td colspan="13" align="center" bgcolor="#FFFFFF">
                                                           Sorry! No Invoices are found.
                                                        </td>
                                                    </tr>
                                                <?php }

                                                ?>
                                            </thead>
                                        </table> 
                                    </div>  
                                
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div id="otpModalBox" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">       
      
      <div class="modal-body">
      <label>OTP:</label>
       <input id="otp_code" type="textbox" name="otp" class="form-control" data-bind="numeric">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button id="otpModalBoxSubmit" type="button" value="OTP" class="btn btn-success" >Submit</button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#down').click(function () {
            flag=0;
             $(".name").each(function(){
                if ($(this).prop('checked')==true){ 
                    flag=1;
                }
            });
            if(flag==1)
            {
                document.form4.action = '<?php echo PROJECT_URL.'/?ajax=return_gstr_payload';?>&returnmonth=<?php echo $returnmonth; ?>';
                document.form4.submit();
            }
            else
            {
                alert('No Invoices are selected?');
                return false;
            }
        });
        /*$('#up').on('click', function () {
            document.form4.action = '<?php echo PROJECT_URL.'/?page=return_view_invoices';?>&returnmonth=<?php echo $returnmonth; ?>';
            document.form4.submit();
        });*/
        $('#multiple-checkboxes').multiselect();
        $('#returnmonth').on('change', function () {
            document.form2.action = '<?php echo PROJECT_URL; ?>/?page=return_view_invoices&returnmonth=<?php echo $returnmonth; ?>';
            document.form2.submit();
        });
        $('.type').on('change', function () {
            document.form3.action = '<?php echo PROJECT_URL; ?>/?page=return_view_invoices&returnmonth=<?php echo $returnmonth; ?>';
            document.form3.submit();
        });
    });
    $('#example-select-all').on('change', function(){
        $('input[type="checkbox"]').prop('checked', this.checked);
        if ($('#example-select-all').is(':checked')) {
           // $('#uploadchecked').removeAttr('disabled');
        } else {
            // $('#uploadchecked').attr('disabled', 'disabled');
        }
    });
    $(function () {
        $("#selectall").click(function () {
            $('.name').attr('checked', this.checked);
        });
        $(".name").click(function () {

            if ($(".name").length == $(".name:checked").length) {
                $("#selectall").attr("checked", "checked");
            } 
            else {
                $("#selectall").removeAttr("checked");
            }
        });

       

    });
    $('#up').on('click', function (event) {
        flag=0;
        $(".name").each(function(){
            if ($(this).prop('checked')==true){ 
                flag=1;
            }
        });
        if(flag==1)
        {
            //event.preventDefault();
            $.ajax({
                url: "<?php echo PROJECT_URL; ?>/?ajax=return_gstr1_details_check",
                type: "json",
                success: function (response) {
                    //alert(response);
                    if(response == 1) {
                        $('#otpModalBox').modal('show');
                        return false;
                    }
                    if(response == 0) {
                       document.form4.submit();
                    }
                    else {
                        location.reload();
                    }
                },
                error: function() {
                    alert('Please try again.');
                    return false;
                }
            });
            return false;
            
        }
        else
        {
            alert('No Invoices are selected?');
            return false;
        }
        return false;

    });
    $( "#otpModalBoxSubmit" ).click(function( event ) {
      var otp = $('#otp_code').val();
      //event.preventDefault();
      if(otp != '') {
        $.ajax({
            url: "<?php echo PROJECT_URL; ?>/?ajax=return_gstr1_otp_request",
            type: "post",
            data: {otp:otp},
            success: function (response) {
                //alert(response);
                var arr = $.parseJSON(response);
                if(arr.error == 1) {
                    location.reload();
                    return false;
                }
                else {
                    document.form4.submit();
                    //return true;
                }
            },
            error: function() {
                alert('Enter OTP First');
                return false;
            }
        });
        return false;
      }
      else {
        alert('Enter OTP First');
        return false;
      }
      return false;
    });
</script>
