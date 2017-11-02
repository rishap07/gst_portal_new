<?php
//session_destroy();
$obj_gstr = new gstr();
$obj_gstr1 = new gstr1();
//$obj_gstr1->pr($_SESSION);
if(!$obj_gstr1->can_read('returnfile_list'))
{
    $obj_gstr1->setError($obj_gstr1->getValMsg('can_read'));
    $obj_gstr1->redirect(PROJECT_URL."/?page=dashboard");
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
//die;
if((isset($_POST['submit_up']) && $_POST['submit_up']=='Upload TO GSTN') || isset($_POST['name']) || isset($_POST['type']) && isset($_POST['btn_type']) && $_POST['btn_type'] == 'upload')
{
    //echo "upload";
    $invoice_type = isset($_REQUEST['type'])?$_REQUEST['type']:'';
    if ($invoice_type == 'HSN' || $invoice_type == 'NIL' || $invoice_type == 'DOCISSUE') {
        if ($obj_gstr1->gstr1Upload('',$invoice_type)) 
        {
           
        }
    }
    elseif(isset($_POST['name']) && $_POST['name']!='')
    {   
        $ids = implode(',',  $_POST['name']);  
        if(!empty($ids)) {
            if ($obj_gstr1->gstr1Upload($ids,$invoice_type)) 
            {
                
                
            }
        }   
    }
    else {
        $obj_gstr1->setError('Sorry! No Invoices are selected');
    }
    
}

if((isset($_POST['submit_freeze']) && $_POST['submit_freeze']=='Final Submit To GSTN')|| (isset($_POST['btn_type']) && $_POST['btn_type'] == 'final_submit' ))
{
    //echo "submit";
    //de
    $obj_gstr1->gstr1FinalSubmit();
    
}



?>

<link type="text/css" rel="stylesheet" href="<?php echo THEME_URL; ?>/css/bootstrap-multiselect.css"/>
<script type="text/javascript" src="<?php echo THEME_URL; ?>/js/bootstrap-multiselect.js"></script>
    <div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr">

    <div class="col-md-11 col-sm-12 col-xs-12 mobpadlr">
        <div class="col-md-12 col-sm-12 col-xs-12 heading">
            <div class="tab col-md-12 col-sm-12 col-xs-12">
                <a href="<?php echo PROJECT_URL . '/?page=return_summary&returnmonth=' . $returnmonth ?>" >
                    1.View GSTR1 Summary
                </a>
                <a href="<?php echo PROJECT_URL . '/?page=return_view_invoices&returnmonth=' . $returnmonth ?>" class="active">
                    2.View My Invoice
                </a>
                <!-- <a href="<?php echo PROJECT_URL . '/?page=return_upload_invoices&returnmonth=' . $returnmonth ?>">
                    Upload To GSTN
                </a> -->
                <a href="<?php echo PROJECT_URL . '/?page=return_get_summary&returnmonth=' . $returnmonth ?>">
                    3.GSTR1 SUMMARY
                </a>
                
                <a href="<?php echo PROJECT_URL . '/?page=return_filling_summary&returnmonth=' . $returnmonth ?>">
                    4.File GSTr-1
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
                                    <label for="invoice-types__TXPD"><input type="radio" id="invoice-types__TXPD" name="invoice_type" value="TXPD" class="type" <?php if(isset($_POST['invoice_type']) && $_POST['invoice_type']=='TXPD') echo 'checked=""';?>>TXPD</label>
                                    <label for="invoice-types__EXP"><input type="radio" id="invoice-types__EXP" name="invoice_type" value="EXP" class="type" <?php if(isset($_POST['invoice_type']) && $_POST['invoice_type']=='EXP') echo 'checked=""';?>>EXP</label>
                                    <label for="invoice-types__HSN"><input type="radio" id="invoice-types__HSN" name="invoice_type" value="HSN" class="type" <?php if(isset($_POST['invoice_type']) && $_POST['invoice_type']=='HSN') echo 'checked=""';?>>HSN</label>
                                    <label for="invoice-types__NIL"><input type="radio" id="invoice-types__NIL" name="invoice_type" value="NIL" class="type" <?php if(isset($_POST['invoice_type']) && $_POST['invoice_type']=='NIL') echo 'checked=""';?>>NIL</label>
                                    <label for="invoice-types__DOCISSUE"><input type="radio" id="invoice-types__DOCISSUE" name="invoice_type" value="DOCISSUE" class="type" <?php if(isset($_POST['invoice_type']) && $_POST['invoice_type']=='DOCISSUE') echo 'checked=""';?>>DOC</label>
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
                                    $Data = $b2bData =  $obj_gstr1->getAllInvoices($_SESSION['user_detail']['user_id'], $returnmonth,'b2b');
                                    //$Data1 = $b2bData1 = $obj_gstr1->getB2BInvoices($_SESSION['user_detail']['user_id'], $returnmonth,'all');
                                    $total = $invoice_total_value = $sumTotal = $igstTotal = $sgstTotal = $cgstTotal = $cessTotal = 0;
                                    $invoice_temp = $invoice_temp_reference_number = '';
                                    $invoice_total_value_temp = '';
                                    $invCount =0;
                                    if (!empty($b2bData)) {
                                        
                                        foreach ($b2bData as $key => $b2bDatavalue) {
                                            if($invoice_temp!='' and $invoice_temp!=$b2bDatavalue->invoice_id)
                                            {
                                                $invCount++;
                                                $invoice_total_value +=$invoice_total_value_temp;
                                                //$sumTotal +=$sumTotal_temp;
                                            }
                                            if($invoice_temp_reference_number!='' and $invoice_temp_reference_number!=$b2bDatavalue->reference_number)
                                            {
                                                $sumTotal +=$sumTotal_temp;
                                            }
                                            $invoice_total_value_temp = isset($b2bDatavalue->taxable_subtotal)?$b2bDatavalue->taxable_subtotal:0;
                                            $total += $b2bDatavalue->cgst_amount + $b2bDatavalue->sgst_amount + $b2bDatavalue->igst_amount + $b2bDatavalue->cess_amount;
                                            $igstTotal += $b2bDatavalue->igst_amount;
                                            $sgstTotal += $b2bDatavalue->sgst_amount;
                                            $cgstTotal += $b2bDatavalue->cgst_amount;
                                            $cessTotal += $b2bDatavalue->cess_amount;
                                            $sumTotal_temp = isset($b2bDatavalue->invoice_total_value)?$b2bDatavalue->invoice_total_value:0;
                                            $invoice_temp=$b2bDatavalue->invoice_id;
                                            $invoice_temp_reference_number=$b2bDatavalue->reference_number;
                                        }
                                        if($invoice_temp!='')
                                        {
                                            $invCount++;
                                            $invoice_total_value +=$invoice_total_value_temp;
                                            $sumTotal +=$sumTotal_temp;
                                        }
                                    }
                                }
                                if($type=='B2CL')
                                {
                                    $group_by = "";
                                    $order_by = 'a.reference_number';
                                    $Data = $b2clData =  $obj_gstr1->getAllInvoices($_SESSION['user_detail']['user_id'], $returnmonth,'b2cl');
                                    // /$Data = $b2clData = $obj_gstr1->getB2CLInvoices($_SESSION['user_detail']['user_id'], $returnmonth,'all','',$group_by,$order_by);
                                    $total = $invoice_total_value = $sumTotal = $igstTotal = $sgstTotal = $cgstTotal = $cessTotal = 0;  
                                    
                                    $invoice_temp = '';
                                    $invCount = 0;
                                    if (!empty($b2clData)) {
                                        foreach ($b2clData as $key => $b2clDatavalue) {
                                            if($invoice_temp!='' and $invoice_temp!=$b2clDatavalue->reference_number)
                                            {
                                                $invCount++;
                                                //$invoice_total_value +=$invoice_total_value_temp;
                                                $sumTotal +=$sumTotal_temp;
                                            }
                                            $invoice_total_value += isset($b2clDatavalue->taxable_subtotal)?$b2clDatavalue->taxable_subtotal:0;
                                            $total += $b2clDatavalue->cgst_amount + $b2clDatavalue->sgst_amount + $b2clDatavalue->igst_amount + $b2clDatavalue->cess_amount;
                                            $igstTotal += $b2clDatavalue->igst_amount;
                                            $sgstTotal += $b2clDatavalue->sgst_amount;
                                            $cgstTotal += $b2clDatavalue->cgst_amount;
                                            $cessTotal += $b2clDatavalue->cess_amount;
                                            $sumTotal_temp = isset($b2clDatavalue->invoice_total_value)?$b2clDatavalue->invoice_total_value:0;
                                            $invoice_temp=$b2clDatavalue->reference_number;
                                        }
                                        if($invoice_temp!='')
                                        {
                                            $invCount++;
                                            //$invoice_total_value +=$invoice_total_value_temp;
                                            $sumTotal +=$sumTotal_temp;
                                        }
                                    }

                                }
                                if($type=='B2CS')
                                {
                                    $group_by = "a.reference_number,b.consolidate_rate";
                                    $order_by = 'a.reference_number';
                                    $Data = $b2csData =  $obj_gstr1->getAllInvoices($_SESSION['user_detail']['user_id'], $returnmonth,'b2cs');
                                    // /$Data = $b2csData = $obj_gstr1->getB2CSInvoices($_SESSION['user_detail']['user_id'], $returnmonth,'all','',$group_by,$order_by);
                                    $total = $invoice_total_value = $sumTotal = $igstTotal = $sgstTotal = $cgstTotal = $cessTotal = 0;
                                    $invoice_temp = '';
                                    $invCount = 0;
                                    if (!empty($b2csData)) {
                                        foreach ($b2csData as $key => $b2csDatavalue) {
                                            if($invoice_temp!='' and $invoice_temp!=$b2csDatavalue->invoice_id)
                                            {
                                                $invCount++;
                                                $invoice_total_value +=$invoice_total_value_temp;
                                                $sumTotal +=$sumTotal_temp;
                                            }
                                            $invoice_total_value_temp = isset($b2csDatavalue->taxable_subtotal)?$b2csDatavalue->taxable_subtotal:0;
                                            $total += $b2csDatavalue->cgst_amount + $b2csDatavalue->sgst_amount + $b2csDatavalue->igst_amount + $b2csDatavalue->cess_amount;
                                            $igstTotal += $b2csDatavalue->igst_amount;
                                            $sgstTotal += $b2csDatavalue->sgst_amount;
                                            $cgstTotal += $b2csDatavalue->cgst_amount;
                                            $cessTotal += $b2csDatavalue->cess_amount;
                                            $sumTotal_temp = isset($b2csDatavalue->invoice_total_value)?$b2csDatavalue->invoice_total_value:0;
                                            $invoice_temp=$b2csDatavalue->invoice_id;
                                        }
                                        if($invoice_temp!='')
                                        {
                                            $invCount++;
                                            $invoice_total_value +=$invoice_total_value_temp;
                                            $sumTotal +=$sumTotal_temp;
                                        }
                                    }

                                }
                                if($type=='CDNR')
                                {
                                     $Data = $cdnrData =  $obj_gstr1->getAllInvoices($_SESSION['user_detail']['user_id'], $returnmonth,'cdnr');
                                    //$Data = $cdnrData = $obj_gstr1->getCDNRInvoices($_SESSION['user_detail']['user_id'], $returnmonth,'all');
                                    $total = $invoice_total_value = $sumTotal = $igstTotal = $sgstTotal = $cgstTotal = $cessTotal = 0;
                                    $invoice_temp = '';
                                    $invCount = 0;
                                    if (!empty($cdnrData)) {
                                        foreach ($cdnrData as $key => $cdnrDatavalue) {
                                            if($invoice_temp!='' and $invoice_temp!=$cdnrDatavalue->reference_number)
                                            {
                                                $invCount++;
                                                //$invoice_total_value +=$invoice_total_value_temp;
                                                $sumTotal +=$sumTotal_temp;
                                            }
                                            $invoice_total_value += isset($cdnrDatavalue->taxable_subtotal)?$cdnrDatavalue->taxable_subtotal:0;
                                            $total += $cdnrDatavalue->cgst_amount + $cdnrDatavalue->sgst_amount + $cdnrDatavalue->igst_amount + $cdnrDatavalue->cess_amount;
                                            $igstTotal += $cdnrDatavalue->igst_amount;
                                            $sgstTotal += $cdnrDatavalue->sgst_amount;
                                            $cgstTotal += $cdnrDatavalue->cgst_amount;
                                            $cessTotal += $cdnrDatavalue->cess_amount;
                                            $sumTotal_temp = isset($cdnrDatavalue->invoice_total_value)?$cdnrDatavalue->invoice_total_value:0;
                                            $invoice_temp=$cdnrDatavalue->reference_number;
                                        }
                                        if($invoice_temp!='')
                                        {
                                            $invCount++;
                                            //$invoice_total_value +=$invoice_total_value_temp;
                                            $sumTotal +=$sumTotal_temp;
                                        }
                                    }

                                }
                                if($type=='CDNUR')
                                {
                                    $group_by = "";
                                    $order_by = 'a.reference_number';
                                    $Data = $cdnurData =  $obj_gstr1->getAllInvoices($_SESSION['user_detail']['user_id'], $returnmonth,'cdnur');
                                    //$Data = $cdnurData = $obj_gstr1->getCDNURInvoices($_SESSION['user_detail']['user_id'], $returnmonth,'all','',$group_by,$order_by);
                                    $total = $invoice_total_value = $sumTotal = $igstTotal = $sgstTotal = $cgstTotal = $cessTotal = 0;
                                    $sumTotal_temp = '';
                                    $invoice_temp = '';
                                    $invCount = 0;
                                    if (!empty($cdnurData)) {
                                        foreach ($cdnurData as $key => $cdnurDatavalue) {
                                            if($invoice_temp!='' and $invoice_temp!=$cdnurDatavalue->reference_number)
                                            {
                                                $invCount++;
                                                //$invoice_total_value +=$invoice_total_value_temp;
                                                $sumTotal +=$sumTotal_temp;
                                            }
                                            $invoice_total_value += isset($cdnurDatavalue->taxable_subtotal)?$cdnurDatavalue->taxable_subtotal:0;
                                            $invoice_total_value_temp =  $cdnurDatavalue->igst_amount + $cdnurDatavalue->cess_amount;
                                            $igstTotal += $cdnurDatavalue->igst_amount;
                                            $sgstTotal += $cdnurDatavalue->sgst_amount;
                                            $cgstTotal += $cdnurDatavalue->cgst_amount;
                                            $cessTotal += $cdnurDatavalue->cess_amount;
                                            $sumTotal_temp = isset($cdnurDatavalue->invoice_total_value)?$cdnurDatavalue->invoice_total_value:0;
                                            $invoice_temp=$cdnurDatavalue->reference_number;
                                        }
                                        if($invoice_temp!='')
                                        {
                                            $invCount++;
                                            //$invoice_total_value +=$invoice_total_value_temp;
                                            $sumTotal +=$sumTotal_temp;
                                        }
                                    }

                                }
                                if($type=='AT')
                                {
                                    $group_by = " a.reference_number ,b.consolidate_rate ";
                                    $order_by = 'a.reference_number';
                                    $Data = $atData =  $obj_gstr1->getAllInvoices($_SESSION['user_detail']['user_id'], $returnmonth,'at');
                                    $total = $invoice_total_value = $sumTotal = $igstTotal = $sgstTotal = $cgstTotal = $cessTotal = 0;
                                    $sumTotal_temp = '';
                                    $invoice_temp = '';
                                    $invCount = 0;
                                    if (!empty($atData)) {
                                        $invoice_temp='';
                                        foreach ($atData as $key => $atDatavalue) {
                                            if($invoice_temp!='' and $invoice_temp!=$atDatavalue->invoice_id)
                                            {
                                                $invCount++;
                                                $invoice_total_value +=$invoice_total_value_temp;
                                                $sumTotal +=$sumTotal_temp;
                                            }
                                            $invoice_total_value_temp = isset($atDatavalue->taxable_subtotal)?$atDatavalue->taxable_subtotal:0;
                                            $total += $atDatavalue->cgst_amount + $atDatavalue->sgst_amount + $atDatavalue->igst_amount + $atDatavalue->cess_amount;
                                            $igstTotal += $atDatavalue->igst_amount;
                                            $sgstTotal += $atDatavalue->sgst_amount;
                                            $cgstTotal += $atDatavalue->cgst_amount;
                                            $cessTotal += $atDatavalue->cess_amount;
                                            $sumTotal_temp = isset($atDatavalue->invoice_total_value)?$atDatavalue->invoice_total_value:0;
                                            $invoice_temp=$atDatavalue->invoice_id;
                                        }
                                        if($invoice_temp!='')
                                        {
                                            $invCount++;
                                            $invoice_total_value +=$invoice_total_value_temp;
                                            $sumTotal +=$sumTotal_temp;
                                        }
                                    }

                                }
                                if($type=='TXPD')
                                {
                                    $group_by = " a.reference_number ,b.consolidate_rate ";
                                    $order_by = 'a.reference_number';
                                    $Data = $atData =  $obj_gstr1->getAllInvoices($_SESSION['user_detail']['user_id'], $returnmonth,'atadj');
                                    //$obj_gstr1->pr($atData);
                                    //$Data1  = $obj_gstr1->getTXPDInvoices($_SESSION['user_detail']['user_id'], $returnmonth,'all','',$group_by,$order_by);
                                    //$obj_gstr1->pr($Data1);
                                    $total = $invoice_total_value = $sumTotal = $igstTotal = $sgstTotal = $cgstTotal = $cessTotal = 0;
                                    $sumTotal_temp = '';
                                    $invoice_temp = '';
                                    $invCount = 0;
                                    if (!empty($atData)) {
                                        $invoice_temp='';
                                        foreach ($atData as $key => $atDatavalue) {
                                            if($invoice_temp!='' and $invoice_temp!=$atDatavalue->invoice_id)
                                            {
                                                $invCount++;
                                                $invoice_total_value +=$invoice_total_value_temp;
                                                $sumTotal +=$sumTotal_temp;
                                            }
                                            $invoice_total_value_temp = isset($atDatavalue->taxable_subtotal)?$atDatavalue->taxable_subtotal:0;
                                            $total += $atDatavalue->cgst_amount + $atDatavalue->sgst_amount + $atDatavalue->igst_amount + $atDatavalue->cess_amount;
                                            $igstTotal += $atDatavalue->igst_amount;
                                            $sgstTotal += $atDatavalue->sgst_amount;
                                            $cgstTotal += $atDatavalue->cgst_amount;
                                            $cessTotal += $atDatavalue->cess_amount;
                                            $sumTotal_temp = isset($atDatavalue->invoice_total_value)?$atDatavalue->invoice_total_value:0;
                                            $invoice_temp=$atDatavalue->invoice_id;
                                        }
                                        if($invoice_temp!='')
                                        {
                                            $invCount++;
                                            $invoice_total_value +=$invoice_total_value_temp;
                                            $sumTotal +=$sumTotal_temp;
                                        }
                                    }

                                }
                                if($type=='EXP')
                                {
                                    $group_by = " a.export_supply_meant,a.reference_number,b.consolidate_rate";
                                    $order_by = 'a.export_supply_meant,a.reference_number';
                                    $Data = $expData =  $obj_gstr1->getAllInvoices($_SESSION['user_detail']['user_id'], $returnmonth,'exp');
                                   // $Data = $expData = $obj_gstr1->getEXPInvoices($_SESSION['user_detail']['user_id'], $returnmonth,'all','',$group_by,$order_by);
                                    $total = $invoice_total_value = $sumTotal = $igstTotal = $sgstTotal = $cgstTotal = $cessTotal = 0;
                                    $sumTotal_temp = '';
                                    $invoice_temp = '';
                                    $invCount = 0;
                                    if (!empty($expData)) {
                                        $invoice_temp='';
                                        foreach ($expData as $key => $expDatavalue) {
                                            if($invoice_temp!='' and $invoice_temp!=$expDatavalue->reference_number)
                                            {
                                                $invCount++;
                                                //$invoice_total_value +=$invoice_total_value_temp;
                                                $sumTotal +=$sumTotal_temp;
                                            }
                                            $invoice_total_value += isset($expDatavalue->taxable_subtotal)?$expDatavalue->taxable_subtotal:0;
                                            $total += $expDatavalue->cgst_amount + $expDatavalue->sgst_amount + $expDatavalue->igst_amount + $expDatavalue->cess_amount;
                                            $igstTotal += $expDatavalue->igst_amount;
                                            $sgstTotal += $expDatavalue->sgst_amount;
                                            $cgstTotal += $expDatavalue->cgst_amount;
                                            $cessTotal += $expDatavalue->cess_amount;
                                            $sumTotal_temp = isset($expDatavalue->invoice_total_value)?$expDatavalue->invoice_total_value:0;
                                            $invoice_temp=$expDatavalue->reference_number;
                                        }
                                        if($invoice_temp!='')
                                        {
                                            $invCount++;
                                           // $invoice_total_value +=$invoice_total_value_temp;
                                            $sumTotal +=$sumTotal_temp;
                                        }
                                    }
                                }
                                if($type=='HSN')
                                {
                                    $hsnData = $obj_gstr1->getReturnUploadSummary($_SESSION['user_detail']['user_id'], $returnmonth,'gstr1hsn');
                                    $total = $invoice_total_value = $sumTotal = $igstTotal = $sgstTotal = $cgstTotal = $cessTotal = 0;
                                    $invCount = 0;
                                    if (!empty($hsnData)) {
                                        $json = $hsnData[0]->return_data;
                                        $hsn_is_uploaded =  (isset($hsnData[0]->is_uploaded) && $hsnData[0]->is_uploaded=='0') ? 'Pending':'Uploaded';
                                        if(!empty($json)) {
                                            $Data = $decodeJson = json_decode(base64_decode($json));
                                            //$obj_gstr1->pr($decodeJson);
                                            //die;
                                            foreach ($decodeJson as $key => $hsnDatavalue) {  
                                                $invoice_total_value +=isset($hsnDatavalue->taxable_subtotal)?$hsnDatavalue->taxable_subtotal:0;
                                                
                                                $igstTotal += isset($hsnDatavalue->igst)?$hsnDatavalue->igst:0;
                                                $sgstTotal += isset($hsnDatavalue->sgst)?$hsnDatavalue->sgst:0;
                                                $cgstTotal += isset($hsnDatavalue->cgst)?$hsnDatavalue->cgst:0;
                                                $cessTotal += isset($hsnDatavalue->cess)?$hsnDatavalue->cess:0;
                                                $sumTotal += isset($hsnDatavalue->invoice_total_value)?$hsnDatavalue->invoice_total_value:0;
                                                $invCount++;
                                            }
                                        }
                                        
                                    }
                                }
                                if($type=='DOCISSUE')
                                {
                                    $docissueData = $obj_gstr1->getReturnUploadSummary($_SESSION['user_detail']['user_id'], $returnmonth,'gstr1document');
                                    $total = $totnum = $totcancel = $net_issue = 0;
                                    $invCount = 0;
                                    if (!empty($docissueData)) {
                                        $json = $docissueData[0]->return_data;
                                        $docissue_is_uploaded =  (isset($docissueData[0]->is_uploaded) && $docissueData[0]->is_uploaded=='0') ? 'Pending':'Uploaded';
                                        
                                        if(!empty($json)) {
                                            $Data = $decodeJson = json_decode(base64_decode($json));
                                            foreach ($decodeJson as $key => $arr_value) {
                                                foreach ($arr_value as $key => $value) {
                                                    $totnum += isset($value->totnum)?$value->totnum:0;
                                                    $totcancel += isset($value->cancel)?$value->cancel:0;
                                                    $net_issue += isset($value->net_issue)?$value->net_issue:0;
                                                    $invCount++;
                                                }
                                            }
                                        }
                                        
                                    }
                                }
                                if($type=='NIL')
                                {
                                    $nilData = $obj_gstr1->getReturnUploadSummary($_SESSION['user_detail']['user_id'], $returnmonth,'gstr1nil');
                                    $tot_expt_amt = $tot_nil_amt = $tot_ngsup_amt = 0;
                                    $invCount = 0;
                                    if (!empty($nilData)) {
                                        $json = $nilData[0]->return_data;
                                        $nil_is_uploaded =  (isset($nilData[0]->is_uploaded) && $nilData[0]->is_uploaded=='0') ? 'Pending':'Uploaded';
                                        if(!empty($json)) {
                                            $Data = $decodeJson = json_decode(base64_decode($json));
                                            
                                            foreach ($decodeJson as $key => $nilDatavalue) {  
                                                $tot_expt_amt += isset($nilDatavalue->expt_amt)?$nilDatavalue->expt_amt:0;
                                                $tot_nil_amt += isset($nilDatavalue->nil_amt)?$nilDatavalue->nil_amt:0;
                                                $tot_ngsup_amt += isset($nilDatavalue->ngsup_amt)?$nilDatavalue->ngsup_amt:0;
                                                $invCount++;
                                            }
                                        }
                                        
                                    }
                                }
                                if($invCount >0) 
                                {
                                    if($flag==1)
                                    {
                                    ?>
                                        <div style="text-align: center;">
                                            <input type="hidden" name="type" value="<?php echo $type;?>" readonly>
                                            <input type="hidden" name="btn_type" value="upload" readonly id="btn_type">
                                            <?php //if($_SESSION['user_detail']['user_id'] == '896') { ?>
                                            <input itype="<?php echo $type?>" type="submit" name="submit_up" id="up" value="Upload TO GSTN" class="btn  btn-success uploadBtn" >
                                            <?php //} ?>
                                            <input itype="<?php echo $type?>" type="submit" name="submit_dwn" id="down" value="Download GSTR1" class="btn btn-warning ">
                                            &nbsp;&nbsp;
                                            <?php
                                            $sql = "select id from " . $obj_gstr1->getTableName('gstr1_return_summary') . " where return_period='" . $returnmonth . "' and added_by='" . $_SESSION['user_detail']['user_id'] . "' and is_uploaded='1' and status = '1' "; 
                                            $dataReturn = $obj_gstr1->get_results($sql);
                                            if(!empty($dataReturn)) { ?>
                                            <input  type="submit" name="submit_freeze" id="freeze" value="Final Submit To GSTN" class="btn btn-primary ">
                                            <?php } ?>
                                            
                                        </div>
                                        <div class="clear"></div><br>
                                    <?php } 
                                }
                                ?>
                                <?php 
                                if($type=='DOCISSUE') { ?>
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0"  class="table table-striped invoice-filter-table" id="mainTable1">
                                        <thead>
                                            <tr>
                                                <th align='left'>Total Transactions</th>
                                                <th align='left'>Total Number</th>
                                                <th align='left'>Total Cancelled</th>
                                                <th align='left'>Net Issued</th>
                                            </tr>
                                            <tr>
                                                <td><?php echo $invCount;?></td>
                                                <td><?php echo $totnum; ?></td>
                                                <td><?php echo $totcancel; ?></td>
                                                <td><?php echo $net_issue; ?></td>
                                            </tr>
                                        </thead>
                                    </table>
                                <?php 
                                }
                                else if($type=='NIL') { ?>
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0"  class="table table-striped invoice-filter-table" id="mainTable1">
                                        <thead>
                                            <tr>
                                                <th align='left'>Total Transactions</th>
                                                <th align='left'>Total Exempted outward supplies</th>
                                                <th align='left'>Total Non GST outward supplies</th>
                                                <th align='left'>Total Nil rated outward supplies</th>
                                            </tr>
                                            <tr>
                                                <td><?php echo $invCount;?></td>
                                                <td><?php echo $tot_expt_amt; ?></td>
                                                <td><?php echo $tot_nil_amt; ?></td>
                                                <td><?php echo $tot_ngsup_amt; ?></td>
                                            </tr>
                                        </thead>
                                    </table>
                                <?php 
                                }
                                else { ?>
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0"  class="table table-striped invoice-filter-table" id="mainTable1">
                                        <thead>
                                            <tr>
                                                <th align='left'>Total Transactions</th>
                                                <?php if($type=='TXPD'  ) { ?>
                                                    <th align='left'>Advance  Adjusted Amount </th>
                                                <?php } 

                                                else { ?>
                                                <th align='left'>Taxable Amount </th>
                                                <?php  } ?>
                                                
                                                <th align='left'>Total IGST</th>
                                                <th align='left'>Total SGST</th>
                                                <th align='left'>Total CGST</th>
                                                <th align='left'>Total Cess</th>
                                                <?php if($type=='B2CS' || $type=='AT' || $type=='TXPD'  ) { ?>
                                                    <th align='left'>Total Tax</th>
                                                <?php } 

                                                else { ?>
                                                <th align='left'>Total Amount</th>
                                                <?php  } ?>
                                                
                                            </tr>
                                            <tr>
                                                <td><?php echo $invCount;?></td>
                                                <td><?php echo $invoice_total_value;?></td>
                                                <td><?php echo $igstTotal; ?></td>
                                                <td><?php echo $sgstTotal; ?></td>
                                                <td><?php echo $cgstTotal; ?></td>
                                                <td><?php echo $cessTotal; ?></td>
                                                <?php if($type=='B2CS' || $type=='AT'|| $type=='TXPD'  ) { ?>
                                                    <td><?php echo $igstTotal+$sgstTotal+$cgstTotal+$cessTotal; ?></td>
                                                <?php } 
                                                else { ?>
                                                <td><?php echo $sumTotal; ?></td>
                                                <?php } ?>
                                                
                                            </tr>
                                        </thead>
                                    </table>
                                <?php } ?>
                                <br/>
                                    <div class="adminformbx">
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="invoice-itemtable" id="mainTable1">
                                            <thead>
                                                <?php 
                                                if ($type == 'HSN') { ?>
                                                    <tr>
                                                        <th align='left'>No.</th>
                                                        <th align='left'>HSN Code</th>
                                                        <th align='left'>Description</th>
                                                        <th align='left'>Unit</th>
                                                        <th align='left'>Qty</th>
                                                        <th style='text-align:right'>Taxable AMT</th>
                                                        <th style='text-align:right'>Total Tax</th>
                                                        <th style='text-align:right'>Total Amt</th>
                                                        <th align='center'>Status</th>
                                                        <th align='center'></th>
                                                    </tr>
                                                <?php }
                                                else if ($type == 'DOCISSUE') { ?>
                                                    <tr>
                                                        <th align='left'>No.</th>
                                                        <th align='left'>Type</th>
                                                        <th align='left'>From serial number</th>
                                                        <th align='left'>To serial number</th>
                                                        <th style='text-align:right'>Total Number</th>
                                                        <th style='text-align:right'>Cancelled</th>
                                                        <th style='text-align:right'>Net issued</th>
                                                        <th align='center'>Status</th>
                                                        <th align='center'></th>
                                                    </tr>
                                                <?php }
                                                else if ($type == 'NIL') { ?>
                                                    <tr>
                                                        <th align='left'>No.</th>
                                                        <th align='left'>Supply Type</th>
                                                        <th style='text-align:right'>Exempted Amount</th>
                                                        <th style='text-align:right'>Nil Amount</th>
                                                        <th style='text-align:right'>Non Gst Amount</th>
                                                        <th align='center'>Status</th>
                                                        <th align='center'></th>
                                                    </tr>
                                                <?php }
                                                else { ?>
                                                    <tr>
                                                        <th align='left'>&nbsp;<!-- <input name="select_all" value="1" id="example-select-all" type="checkbox" /> --></th>
                                                        <th align='left'>No.</th>
                                                        <th align='left'>Date</th>
                                                        <th align='left'>Invoice Number</th>
                                                        
                                                        <?php
                                                        if($type!='B2CL' && $type!='B2CS' && $type!='CDNUR' &&  $type!='AT' &&  $type!='TXPD')
                                                        {
                                                        ?>
                                                        <th align='left'>GSTIN</th>
                                                        <?php
                                                        }
                                                        ?>
                                                        <?php if($type=='TXPD'  ) { ?>
                                                           <th style='text-align:right'>Advance Adjusted AMT </th>
                                                        <?php } 

                                                        else { ?>
                                                        <th style='text-align:right'>Taxable AMT</th>
                                                        <?php  } ?>
                                                
                                                        
                                                        <th align='left'>Rate</th>
                                                        <th style='text-align:right'>Total Tax</th>
                                                         <?php if($type!='B2CS' && $type != 'AT' &&  $type!='TXPD') { ?>
                                                           <th style='text-align:right'>Total Amt</th>
                                                        <?php } ?>
                                                        <?php if($type == 'B2B' ) { ?>
                                                            <th align='center'>Type</th>
                                                        <?php }  ?>
                                                        <th align='center'>POS</th>
                                                        <th align='center'>Status</th>
                                                        <!-- <th align='center'></th> -->
                                                    </tr>
                                                <?php
                                                }
                                                if($invCount >0) {
                                                    if(!empty($type))
                                                    {
                                                        if(!empty($Data))
                                                        {
                                                            if($type == 'HSN') {
                                                                $i=1;
                                                                $url = PROJECT_URL.'/?page=return_hsnwise_summary&returnmonth='.$returnmonth;
                                                                foreach($Data as $Item)
                                                                { 
                                                                    $total = 0;
                                                                    $total += $Item->cgst + $Item->sgst + $Item->igst + $Item->cess;

                                                                    ?>
                                                                    <tr>
                                                                        <td align='left'><?php echo $i++;?></td>
                                                                        <td align='left'><?php echo $Item->hsn;?></td>
                                                                        <td align='left'><?php echo $Item->description;?></td>
                                                                        <td align='left'><?php echo $Item->unit;?></td>
                                                                        <td align='left'><?php echo $Item->qty;?></td>
                                                                        <td style='text-align:right'><?php echo $Item->taxable_subtotal;?></td>
                                                                        <td style='text-align:right'><?php echo $total;?></td>
                                                                        <td style='text-align:right'><?php echo $Item->invoice_total_value;?></td>
                                                                        <td align='center'><?php echo $hsn_is_uploaded;?></td>
                                                                        <td align='center'><a href="<?php echo $url; ?>" target="_blank">View</a></td>
                                                                    </tr>
                                                                <?php }
                                                            }
                                                            else if($type == 'DOCISSUE') {
                                                                $i=1;
                                                                $url = PROJECT_URL.'/?page=return_document_summary&returnmonth='.$returnmonth;
                                                                foreach($Data as $doc => $arr_value)
                                                                { 
                                                                    foreach ($arr_value as $key => $Item) {  
                                                                        if(!empty($Item->from) && !empty($Item->to)) {
                                                                        ?>
                                                                        <tr>
                                                                            <td align='left'><?php echo $i++;?></td>
                                                                            <td align='left'><?php echo $obj_gstr->doc_issue_key_name($doc);?></td>
                                                                            <td align='left'><?php echo $Item->from;?></td>
                                                                            <td align='left'><?php echo $Item->to;?></td>
                                                                            <td style='text-align:right'><?php echo $Item->totnum;?></td>
                                                                            <td style='text-align:right'><?php echo $Item->cancel;?></td>
                                                                            <td style='text-align:right'><?php echo $Item->net_issue;?></td>
                                                                            <td align='center'><?php echo $docissue_is_uploaded;?></td>
                                                                            <td align='center'><a href="<?php echo $url; ?>" target="_blank">View</a></td>
                                                            
                                                                        </tr>
                                                                    <?php } 
                                                                    }
                                                                }
                                                            }
                                                            else if($type == 'NIL') {
                                                                $i=1;
                                                                $url = PROJECT_URL.'/?page=return_nil_summary&returnmonth='.$returnmonth;
                                                                foreach($Data as $Item)
                                                                { 
                                                                    $total = 0;
                                                                    ?>
                                                                    <tr>
                                                                        <td align='left'><?php echo $i++;?></td>
                                                                        <td align='left'><?php echo $Item->sply_ty;?></td>
                                                                        <td style='text-align:right'><?php echo $Item->expt_amt;?></td>
                                                                        <td style='text-align:right'><?php echo $Item->nil_amt;?></td>
                                                                        <td style='text-align:right'><?php echo $Item->ngsup_amt;?></td>
                                                                        <td align='center'><?php echo $nil_is_uploaded;?></td>
                                                                        <td align='center'><a href="<?php echo $url; ?>" target="_blank">View</a></td>
                                                        
                                                                    </tr>
                                                                <?php }
                                                            }
                                                            else {
                                                                $status = $type;
                                                                $flag=1;
                                                                $i=1;
                                                                $temp_inv = '';
                                                                $tax = 0;
                                                                foreach($Data as $Item)
                                                                {
                                                                    if($temp_inv!='' and $temp_inv!=$Item->invoice_id)   
                                                                    {
                                                                    ?>
                                                                    <tr>
                                                                        <td align="center" bgcolor="#FFFFFF">
                                                                           <input type="checkbox" class="name" name="name[]" value="<?php echo $temp_inv;?>"/> 
                                                                        </td>
                                                                        <td align='left'><?php echo $i++;?></td>
                                                                        <td align='left'><?php echo $invoice_date;?></td>
                                                                        <td align='left'><?php echo $reference_number;?></td>
                                                                        <?php
                                                                        if($type!='B2CL' && $type!='B2CS' && $type!='CDNUR' &&  $type!='AT' && $type!='TXPD')
                                                                        {
                                                                        ?>
                                                                        <td align='left'><?php echo $billing_gstin_number;?></td>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                        
                                                                        
                                                                        <td style='text-align:right'><?php echo $taxable_subtotal;?></td>
                                                                        <td align='left'><?php echo $rate;?></td> 
                                                                        <td style='text-align:right'><?php echo $tax?></td>
                                                                        <?php if($type!='B2CS'  && $type!='AT'&& $type!='TXPD') { ?>
                                                                           <td style='text-align:right'><?php echo $invoice_total_value;?></td>
                                                                        <?php } ?>

                                                                        <?php if($type == 'B2B') { ?>
                                                                            <td align='center'><?php echo $Item->invoice_type;; ?></td>
                                                                        <?php } ?>
                                                                        <td align='center'><?php echo $pos; ?></td>
                                                                        <td align='center'><?php echo $is_uploaded;?></td>
                                                                        <?php 
                                                                        //$obj_gstr1->pr($Item->is_gstr1_uploaded);
                                                                        $url = 'javascript:;';
                                                                        if($type == 'B2B' || $type == 'B2CL' || $type == 'B2CS') {
                                                                            if($invoice_type == 'taxinvoice') {
                                                                                $url = PROJECT_URL.'/?page=client_update_invoice&action=editInvoice&id='.$temp_inv;
                                                                            }
                                                                            if($invoice_type == 'sezunitinvoice' || $invoice_type == 'deemedexportinvoice' ) {
                                                                                $url = PROJECT_URL.'/?page=client_update_export_invoice&action=editInvoice&id='.$temp_inv;
                                                                            }
                                                                            
                                                                        }
                                                                        if($type == 'AT') {
                                                                            $url = PROJECT_URL.'/?page=client_update_receipt_voucher_invoice&action=editRVInvoice&id='.$temp_inv;
                                                                        }
                                                                        if($type == 'EXP') {
                                                                            $url = PROJECT_URL.'/?page=client_update_export_invoice&action=editInvoice&id='.$temp_inv;
                                                                        }
                                                                        if($type == 'CDNR' || $type == 'CDNUR'  ) {
                                                                            if($invoice_type == 'creditnote' || $invoice_type == 'debitnote') {
                                                                                $url = PROJECT_URL.'/?page=client_update_revised_tax_invoice&action=editRTInvoice&id='.$temp_inv;
                                                                            }
                                                                            if($invoice_type == 'refundvoucherinvoice') {
                                                                                $url = PROJECT_URL.'/?page=client_refund_voucher_invoice_list&action=viewRFInvoice&id='.$temp_inv;
                                                                            }
                                                                            
                                                                        }
                                                                        
                                                                        ?>
                                                                        <!-- <td align='center'><a href="<?php echo $url; ?>" target="_blank">View</a></td> -->
                                                                    </tr>
                                                                    <?php
                                                                     $tax=0;
                                                                    }
                                                                    $invoice_date = $Item->invoice_date;
                                                                    $reference_number = $Item->reference_number;
                                                                    $billing_name = isset($Item->billing_name)?$Item->billing_name:'';
                                                                    $rate = isset($Item->rate)?$Item->rate:'';
                                                                    $billing_gstin_number = $Item->billing_gstin_number;
                                                                    $taxable_subtotal = $Item->taxable_subtotal;
                                                                    $invoice_total_value = $Item->invoice_total_value;
                                                                    $is_uploaded =  (isset($Item->is_uploaded) && $Item->is_uploaded=='0') ? 'Pending':'Uploaded';
                                                                    $invoice_type = $Item->invoice_type;
                                                                    $tax += $Item->cgst_amount + $Item->sgst_amount + $Item->igst_amount + $Item->cess_amount;
                                                                    $temp_inv=$Item->invoice_id;
                                                                    $pos = $obj_gstr->place_of_supply($Item->supply_place);
                                                                }
                                                                if($temp_inv!='')   
                                                                {
                                                                ?>
                                                                    <tr>
                                                                        <td align="center" bgcolor="#FFFFFF">
                                                                           <input type="checkbox" class="name" name="name[]" value="<?php echo $temp_inv;?>"/> 
                                                                        </td>
                                                                        <td align='left'><?php echo $i++;?></td>
                                                                        <td align='left'><?php echo $invoice_date;?></td>
                                                                        <td align='left'><?php echo $reference_number;?></td>
                                                                        
                                                                        <?php
                                                                        if($type!='B2CL' && $type!='B2CS' && $type!='CDNUR' &&  $type!='AT' && $type!='TXPD')
                                                                        {
                                                                        ?>
                                                                        <td align='left'><?php echo $billing_gstin_number;?></td>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                        <td style='text-align:right'><?php echo $taxable_subtotal;?></td>
                                                                        <td align='left'><?php echo $rate;?></td> 

                                                                        <td style='text-align:right'><?php echo $tax?></td>
                                                                        <?php if($type!='B2CS' && $type!='AT' && $type!='TXPD') { ?>
                                                                           <td style='text-align:right'><?php echo $invoice_total_value;?></td>
                                                                        <?php } ?>
                                                                        
                                                                        <?php if($type == 'B2B') { ?>
                                                                            <td align='center'><?php echo $Item->invoice_type; ?></td>
                                                                        <?php } ?>
                                                                        <td align='center'><?php echo $pos; ?></td>
                                                                        <td align='center'><?php echo $is_uploaded;?></td>
                                                                        <?php 
                                                                        //$obj_gstr1->pr($Item->is_gstr1_uploaded);
                                                                        $url = 'javascript:;';
                                                                        if($type == 'B2B' || $type == 'B2CL' || $type == 'B2CS') {
                                                                            if($invoice_type == 'taxinvoice') {
                                                                                $url = PROJECT_URL.'/?page=client_update_invoice&action=editInvoice&id='.$temp_inv;
                                                                            }
                                                                            if($invoice_type == 'sezunitinvoice' || $invoice_type == 'deemedexportinvoice' ) {
                                                                                $url = PROJECT_URL.'/?page=client_update_export_invoice&action=editInvoice&id='.$temp_inv;
                                                                            }
                                                                        }
                                                                        if($type == 'AT') {
                                                                            $url = PROJECT_URL.'/?page=client_update_receipt_voucher_invoice&action=editRVInvoice&id='.$temp_inv;
                                                                        }
                                                                        if($type == 'EXP') {
                                                                            $url = PROJECT_URL.'/?page=client_update_export_invoice&action=editInvoice&id='.$temp_inv;
                                                                        }
                                                                        if($type == 'CDNR' || $type == 'CDNUR'  ) {
                                                                            if($invoice_type == 'creditnote' || $invoice_type == 'debitnote') {
                                                                                $url = PROJECT_URL.'/?page=client_update_revised_tax_invoice&action=editRTInvoice&id='.$temp_inv;
                                                                            }
                                                                            if($invoice_type == 'refundvoucherinvoice') {
                                                                                $url = PROJECT_URL.'/?page=client_refund_voucher_invoice_list&action=viewRFInvoice&id='.$temp_inv;
                                                                            }
                                                                            
                                                                        }
                                                                        
                                                                        ?>
                                                                        <!-- <td align='center'><a href="<?php echo $url; ?>" target="_blank">View</a></td> -->
                                                                    </tr>
                                                                <?php
                                                                }
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
<?php 
$obj_gstr->uploadOtpPopupJs();
?>
 <div id="finalotpModalBox" class="modal fade" role="dialog" style="z-index: 999999;top: 78px;">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body">
                <label>Enter OTP </label>
                <input id="final_otp_code" type="textbox" name="otp" class="form-control" data-bind="numeric" autocomplete="off">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button id="finalotpModalBoxSubmit" type="button" value="OTP" class="btn btn-success" >Submit</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#down').click(function () {
            flag=0;
            var itype =  $(this).attr('itype');
            if(itype == 'HSN' || itype == 'NIL' || itype == 'DOCISSUE') {
                document.form4.action = '<?php echo PROJECT_URL.'/?ajax=return_gstr_payload';?>&returnmonth=<?php echo $returnmonth; ?>&type='+itype;
                document.form4.submit();
            }
            else {
                $(".name").each(function(){
                    if ($(this).prop('checked')==true){ 
                        flag=1;
                    }
                });
                if(flag==1)
                {
                    document.form4.action = '<?php echo PROJECT_URL.'/?ajax=return_gstr_payload';?>&returnmonth=<?php echo $returnmonth; ?>&type='+itype;
                    document.form4.submit();
                }
                else
                {
                    alert('No Invoices are selected?');
                    return false;
                }   
            }
            
        });

        $(document).ready(function () {
            $("#freeze").on("click", function () {
                $('#btn_type').val('final_submit');
                $.ajax({
                    url: "<?php echo PROJECT_URL; ?>/?ajax=return_gstr1_details_check",
                    type: "json",
                    success: function (response) {
                       // alert(response);
                        if(response == 1) {
                            $("#finalotpModalBox").modal("show");
                            return false;
                        }
                        else if(response == 0) {
                           if(!confirm("Are you sure you want to final submit data of current month?"))
                            {
                                return false;
                            }
                            else {
                                document.form4.submit();
                            }
                        }
                        else {
                            location.reload();
                            return false;
                        }
                    },
                    error: function() {

                        alert("Please try again.");
                        return false;
                    }
                });
                return false;
            });
            return false;

        });
        $( "#finalotpModalBoxSubmit" ).click(function( event ) {
            var otp = $('#final_otp_code').val();
            //event.preventDefault();
            if(otp != " ") {
                $.ajax({
                    url: "<?php echo PROJECT_URL; ?>/?ajax=return_gstr1_otp_request",
                    type: "post",
                    data: {otp:otp},
                    success: function (response) {
                        var arr = $.parseJSON(response);
                        if(arr.error_code == 0) {
                            $("#finalotpModalBox").modal("hide");
                            if(!confirm("Are you sure you want to final submit data of current month?"))
                            {
                                return false;
                            }
                            else {
                                document.form4.submit();
                            }
                        }
                        else {
                          location.reload();
                            return false;
                        }
                    },
                    error: function() {
                        alert("Enter OTP First");
                        return false;
                    }
                });
                return false;
            }
            else {
                alert("Enter OTP First");
                return false;
            }
            return false;
        });
        
        $('.uploadBtn').on('click', function () {
            $('#btn_type').val('upload');
        });
        $('#multiple-checkboxes').multiselect();
        $('#returnmonth').on('change', function () {
            document.form2.action = '<?php echo PROJECT_URL; ?>/?page=return_view_invoices&returnmonth=<?php echo $returnmonth; ?>';
            document.form2.submit();
        });
        $('.type').on('change', function () {
            document.form3.action = '<?php echo PROJECT_URL; ?>/?page=return_view_invoices&returnmonth=<?php echo $returnmonth; ?>';
            document.form3.submit();
        });
        $('#example-select-all').on('change', function(){
            $('input[type="checkbox"]').prop('checked', this.checked);
            if ($('#example-select-all').is(':checked')) {
                
               // $('#uploadchecked').removeAttr('disabled');
            } else {
                // $('#uploadchecked').attr('disabled', 'disabled');
            }
        });
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
</script>