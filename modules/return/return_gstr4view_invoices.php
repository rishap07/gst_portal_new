<?php
//session_destroy();
$obj_gstr4 = new gstr4();
$obj_gstr = new gstr();

//$obj_gstr4->pr($_SESSION);
if(!$obj_gstr4->can_read('returnfile_list'))
{
    $obj_gstr4->setError($obj_gstr4->getValMsg('can_read'));
    $obj_gstr4->redirect(PROJECT_URL."/?page=dashboard");
    exit();
}
$dataCurrentUserArr = $obj_gstr4->getUserDetailsById( $obj_gstr4->sanitize($_SESSION['user_detail']['user_id']) );
//$obj_gstr4->pr($dataCurrentUserArr['data']);die;
if($dataCurrentUserArr['data']->kyc->vendor_type!='1'){
   // $obj_gstr4->setError("Invalid Access to file");
   // $obj_gstr4->redirect(PROJECT_URL . "/?page=dashboard");
    //exit();
}
if (!isset($_REQUEST['returnmonth']) || $_REQUEST['returnmonth'] == '') {
    $obj_gstr4->redirect(PROJECT_URL . "/?page=return_client");
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
//$obj_gstr4->pr($_POST);
if($type=="B2B")
{
    if (isset($_POST['returnmonth'])) 
    {
        $returnmonth = $_POST['returnmonth'];

        $obj_gstr4->redirect(PROJECT_URL . "/?page=return_gstr4view_invoices&returnmonth=" . $returnmonth);
        exit();
    }
}
$obj_gstr4->gstr4Data($_SESSION["user_detail"]["user_id"],$returnmonth); die;

$obj_gstr2 = new gstr2();
$generate_gstr2_at_summary_query = "select 
												p.purchase_invoice_id, 
												p.invoice_type, 
												p.supplier_billing_name, 
												p.financial_year, 
												p.invoice_date, 
												p.reference_number, 
												p.supplier_billing_gstin_number, 
												cs.state_tin as company_state, 
												ps.state_tin as supply_place, 
												sum(pi.taxable_subtotal) as taxable_subtotal, 
												sum(pi.cgst_amount) as cgst_amount, 
												sum(pi.sgst_amount) as sgst_amount, 
												sum(pi.igst_amount) as igst_amount, 
												sum(pi.cess_amount) as cess_amount, 
												pi.consolidate_rate 
												from ".$obj_gstr4->getTableName('client_purchase_invoice')." p 
												left join ".$obj_gstr4->getTableName('client_purchase_invoice')." as inv on p.purchase_invoice_id = inv.receipt_voucher_number 
												AND (
													(inv.invoice_date > p.invoice_date AND (DATE_FORMAT(inv.invoice_date, '%Y-%m') = '".$returnmonth."' ) AND (inv.purchase_invoice_id is not NULL)) or 
													(inv.purchase_invoice_id is NULL)
												  ) 
												inner join ".$obj_gstr4->getTableName('client_purchase_invoice_item')." pi on p.purchase_invoice_id = pi.purchase_invoice_id 
												inner join ".$obj_gstr4->getTableName('state')." cs on cs.state_id = p.company_state  
												inner join ".$obj_gstr4->getTableName('state')." ps on p.supply_place = ps.state_id where 1=1 ";

		$generate_gstr2_at_summary_query .= " AND 
											  p.status='1' AND 
											  p.added_by='".$_SESSION["user_detail"]["user_id"]."' AND 
											  DATE_FORMAT(p.invoice_date,'%Y-%m') = '".$returnmonth."' AND 
											  p.invoice_type='receiptvoucherinvoice' AND 
											  p.is_canceled='0' AND 
											  p.is_deleted='0' 
											  group by p.supply_place, pi.consolidate_rate ORDER BY p.supply_place";
											  
		//echo $generate_gstr2_at_summary_query; die;
//$obj_gstr4->getPurchaseATInvoicesDetails(144,$returnmonth); die;		
		  //$obj_gstr4->getPurchaseAtadjInvoices(194,$returnmonth);die;
		  
		//$generate_gstr2_at_summary_result = $this->get_results($generate_gstr2_at_summary_query, $array_type);
		//return $generate_gstr2_at_summary_result;

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
                <a href="<?php echo PROJECT_URL . '/?page=return_gstr4view_invoices&returnmonth=' . $returnmonth ?>" class="active">
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
							<?php $invoiceMonthYear = $obj_gstr4->getInvoiceMonthList($obj_gstr4->getTableName('client_purchase_invoice')); ?>
							<select class="monthselectbox" id="returnmonth" name="returnmonth">
								<option value="">Select</option>
								<?php foreach($invoiceMonthYear as $monthYear) { ?>
									<option <?php if($returnmonth == $monthYear->invoiceDate) { echo 'selected="selected"'; } ?> value="<?php echo $monthYear->invoiceDate; ?>"><?php echo date("M-y", strtotime($monthYear->invoiceDate)); ?></option>
								<?php } ?>
							</select>
                                </form>  
                            </div> 
                            <div class="clearfix"></div>
                           
                            <?php $obj_gstr4->showErrorMessage(); ?>
                            <?php $obj_gstr4->showSuccessMessge(); ?>
                            <?php $obj_gstr4->unsetMessage(); ?>
                            <?php
                            $flag=0;
                            
                            ?>
                            <form method='post' name='form3'>
                            <div class="invoice-types"><div class="invoice-types__heading">Types</div>

                                <div class="invoice-types__content">
                                    <label for="invoice-types__B2B"><input type="radio" id="invoice-types__B2B" name="invoice_type" value="B2B" class="type" <?php if(isset($_POST['invoice_type']) && $_POST['invoice_type']=='B2B'){ echo 'checked=""';}else{echo 'checked=""';}?>>B2B</label>
                                    <label for="invoice-types__B2BUR"><input type="radio" id="invoice-types__B2BUR" name="invoice_type" value="B2BUR" class="type" <?php if(isset($_POST['invoice_type']) && $_POST['invoice_type']=='B2BUR'){ echo 'checked=""';}?>>B2BUR</label>
                                    <label for="invoice-types__IMPS"><input type="radio" id="invoice-types__IMPS" name="invoice_type" value="IMPS" class="type" <?php if(isset($_POST['invoice_type']) && $_POST['invoice_type']=='IMPS'){ echo 'checked=""';}?>>IMPS</label>
                                    <label for="invoice-types__CDNR"><input type="radio" id="invoice-types__CDNR" name="invoice_type" value="CDNR" class="type" <?php if(isset($_POST['invoice_type']) && $_POST['invoice_type']=='CDNR') echo 'checked=""';?>>CDNR</label>
                                    <label for="invoice-types__CDNUR"><input type="radio" id="invoice-types__CDNUR" name="invoice_type" value="CDNUR" class="type" <?php if(isset($_POST['invoice_type']) && $_POST['invoice_type']=='CDNUR') echo 'checked=""';?>>CDNUR</label>
                                    <label for="invoice-types__AT"><input type="radio" id="invoice-types__AT" name="invoice_type" value="AT" class="type" <?php if(isset($_POST['invoice_type']) && $_POST['invoice_type']=='AT') echo 'checked=""';?>>AT</label>
                                    <label for="invoice-types__TXPD"><input type="radio" id="invoice-types__TXPD" name="invoice_type" value="ATADJ" class="type" <?php if(isset($_POST['invoice_type']) && $_POST['invoice_type']=='ATADJ') echo 'checked=""';?>>ATADJ</label>
                                  
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
                                    $Data = $b2bData =  $obj_gstr4->getPurchaseB2BInvoicesDetails($_SESSION['user_detail']['user_id'], $returnmonth);
                                    //$Data1 = $b2bData1 = $obj_gstr4->getB2BInvoices($_SESSION['user_detail']['user_id'], $returnmonth,'all');
                                    $total = $invoice_total_value = $sumTotal = $igstTotal = $sgstTotal = $cgstTotal = $cessTotal = 0;
                                    $invoice_temp = $invoice_temp_reference_number = '';
                                    $invoice_total_value_temp = '';
                                    $invCount =0;
                                    if (!empty($b2bData)) {
                                        
                                        foreach ($b2bData as $key => $b2bDatavalue) {
                                            if($invoice_temp!='' and $invoice_temp!=$b2bDatavalue->invoice_id)
                                            {
                                                $invCount++;
                                               // $invoice_total_value +=$invoice_total_value_temp;
                                                $sumTotal +=$sumTotal_temp;
												
                                            }
											
											$invoice_total_value_temp += isset($b2bDatavalue->taxable_subtotal)?$b2bDatavalue->taxable_subtotal:0;
										    $total += $b2bDatavalue->cgst + $b2bDatavalue->sgst + $b2bDatavalue->igst + $b2bDatavalue->cess;
                                            $igstTotal += $b2bDatavalue->igst;
                                            $sgstTotal += $b2bDatavalue->sgst;
                                            $cgstTotal += $b2bDatavalue->cgst;
                                            $cessTotal += $b2bDatavalue->cess;
                                            $sumTotal_temp = isset($b2bDatavalue->totalamount)?$b2bDatavalue->totalamount:0;
                                            $invoice_temp=$b2bDatavalue->invoice_id;
                                           
                                        }
                                        if($invoice_temp!='')
                                        {
                                            $invCount++;
                                            $invoice_total_value +=$invoice_total_value_temp;
                                            $sumTotal +=$sumTotal_temp;
                                        }
                                    }
                                }
								if($type=='B2BUR')
                                {
                                    $Data = $b2bData =  $obj_gstr4->getPurchaseB2BurInvoicesDetails($_SESSION['user_detail']['user_id'], $returnmonth);
                                    //$Data1 = $b2bData1 = $obj_gstr4->getB2BInvoices($_SESSION['user_detail']['user_id'], $returnmonth,'all');
                                    $total = $invoice_total_value = $sumTotal = $igstTotal = $sgstTotal = $cgstTotal = $cessTotal = 0;
                                    $invoice_temp = $invoice_temp_reference_number = '';
                                    $invoice_total_value_temp = '';
                                    $invCount =0;
                                    if (!empty($b2bData)) {
                                        
                                        foreach ($b2bData as $key => $b2bDatavalue) {
                                            if($invoice_temp!='' and $invoice_temp!=$b2bDatavalue->invoice_id)
                                            {
                                                $invCount++;
                                               // $invoice_total_value +=$invoice_total_value_temp;
                                                $sumTotal +=$sumTotal_temp;
												
                                            }
											
											$invoice_total_value_temp += isset($b2bDatavalue->taxable_subtotal)?$b2bDatavalue->taxable_subtotal:0;
										    $total += $b2bDatavalue->cgst + $b2bDatavalue->sgst + $b2bDatavalue->igst + $b2bDatavalue->cess;
                                            $igstTotal += $b2bDatavalue->igst;
                                            $sgstTotal += $b2bDatavalue->sgst;
                                            $cgstTotal += $b2bDatavalue->cgst;
                                            $cessTotal += $b2bDatavalue->cess;
                                            $sumTotal_temp = isset($b2bDatavalue->totalamount)?$b2bDatavalue->totalamount:0;
                                            $invoice_temp=$b2bDatavalue->invoice_id;
                                           
                                        }
                                        if($invoice_temp!='')
                                        {
                                            $invCount++;
                                            $invoice_total_value +=$invoice_total_value_temp;
                                            $sumTotal +=$sumTotal_temp;
                                        }
                                    }
                                }
								if($type=='IMPS')
                                {
                                    $Data = $b2bData =  $obj_gstr4->getPurchaseImportInvoicesDetails($_SESSION['user_detail']['user_id'], $returnmonth);
                                    //$Data1 = $b2bData1 = $obj_gstr4->getB2BInvoices($_SESSION['user_detail']['user_id'], $returnmonth,'all');
                                    $total = $invoice_total_value = $sumTotal = $igstTotal = $sgstTotal = $cgstTotal = $cessTotal = 0;
                                    $invoice_temp = $invoice_temp_reference_number = '';
                                    $invoice_total_value_temp = '';
                                    $invCount =0;
                                    if (!empty($b2bData)) {
                                        
                                        foreach ($b2bData as $key => $b2bDatavalue) {
                                            if($invoice_temp!='' and $invoice_temp!=$b2bDatavalue->invoice_id)
                                            {
                                                $invCount++;
                                               // $invoice_total_value +=$invoice_total_value_temp;
                                                $sumTotal +=$sumTotal_temp;
												
                                            }
											
											$invoice_total_value_temp += isset($b2bDatavalue->taxable_subtotal)?$b2bDatavalue->taxable_subtotal:0;
										    $total += $b2bDatavalue->cgst + $b2bDatavalue->sgst + $b2bDatavalue->igst + $b2bDatavalue->cess;
                                            $igstTotal += $b2bDatavalue->igst;
                                            $sgstTotal += $b2bDatavalue->sgst;
                                            $cgstTotal += $b2bDatavalue->cgst;
                                            $cessTotal += $b2bDatavalue->cess;
                                            $sumTotal_temp = isset($b2bDatavalue->totalamount)?$b2bDatavalue->totalamount:0;
                                            $invoice_temp=$b2bDatavalue->invoice_id;
                                           
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
                                    $Data = $b2bData =  $obj_gstr4->getPurchaseCdnrInvoicesDetails($_SESSION['user_detail']['user_id'], $returnmonth);
                                    //$Data1 = $b2bData1 = $obj_gstr4->getB2BInvoices($_SESSION['user_detail']['user_id'], $returnmonth,'all');
                                    $total = $invoice_total_value = $sumTotal = $igstTotal = $sgstTotal = $cgstTotal = $cessTotal = 0;
                                    $invoice_temp = $invoice_temp_reference_number = '';
                                    $invoice_total_value_temp = '';
                                    $invCount =0;
                                    if (!empty($b2bData)) {
                                        
                                        foreach ($b2bData as $key => $b2bDatavalue) {
                                            if($invoice_temp!='' and $invoice_temp!=$b2bDatavalue->invoice_id)
                                            {
                                                $invCount++;
                                               // $invoice_total_value +=$invoice_total_value_temp;
                                                $sumTotal +=$sumTotal_temp;
												
                                            }
											
											$invoice_total_value_temp += isset($b2bDatavalue->taxable_subtotal)?$b2bDatavalue->taxable_subtotal:0;
										    $total += $b2bDatavalue->cgst + $b2bDatavalue->sgst + $b2bDatavalue->igst + $b2bDatavalue->cess;
                                            $igstTotal += $b2bDatavalue->igst;
                                            $sgstTotal += $b2bDatavalue->sgst;
                                            $cgstTotal += $b2bDatavalue->cgst;
                                            $cessTotal += $b2bDatavalue->cess;
                                            $sumTotal_temp = isset($b2bDatavalue->totalamount)?$b2bDatavalue->totalamount:0;
                                            $invoice_temp=$b2bDatavalue->invoice_id;
                                           
                                        }
                                        if($invoice_temp!='')
                                        {
                                            $invCount++;
                                            $invoice_total_value +=$invoice_total_value_temp;
                                            $sumTotal +=$sumTotal_temp;
                                        }
                                    }
                                }
								if($type=='CDNUR')
                                {
                                    $Data = $b2bData =  $obj_gstr4->getPurchaseCdnurInvoicesDetails($_SESSION['user_detail']['user_id'], $returnmonth);
                                    //$Data1 = $b2bData1 = $obj_gstr4->getB2BInvoices($_SESSION['user_detail']['user_id'], $returnmonth,'all');
                                    $total = $invoice_total_value = $sumTotal = $igstTotal = $sgstTotal = $cgstTotal = $cessTotal = 0;
                                    $invoice_temp = $invoice_temp_reference_number = '';
                                    $invoice_total_value_temp = '';
                                    $invCount =0;
                                    if (!empty($b2bData)) {
                                        
                                        foreach ($b2bData as $key => $b2bDatavalue) {
                                            if($invoice_temp!='' and $invoice_temp!=$b2bDatavalue->invoice_id)
                                            {
                                                $invCount++;
                                               // $invoice_total_value +=$invoice_total_value_temp;
                                                $sumTotal +=$sumTotal_temp;
												
                                            }
											
											$invoice_total_value_temp += isset($b2bDatavalue->taxable_subtotal)?$b2bDatavalue->taxable_subtotal:0;
										    $total += $b2bDatavalue->cgst + $b2bDatavalue->sgst + $b2bDatavalue->igst + $b2bDatavalue->cess;
                                            $igstTotal += $b2bDatavalue->igst;
                                            $sgstTotal += $b2bDatavalue->sgst;
                                            $cgstTotal += $b2bDatavalue->cgst;
                                            $cessTotal += $b2bDatavalue->cess;
                                            $sumTotal_temp = isset($b2bDatavalue->totalamount)?$b2bDatavalue->totalamount:0;
                                            $invoice_temp=$b2bDatavalue->invoice_id;
                                           
                                        }
                                        if($invoice_temp!='')
                                        {
                                            $invCount++;
                                            $invoice_total_value +=$invoice_total_value_temp;
                                            $sumTotal +=$sumTotal_temp;
                                        }
                                    }
                                }
								if($type=='AT')
                                {
                                    $Data = $b2bData =  $obj_gstr4->getPurchaseATInvoicesDetails($_SESSION['user_detail']['user_id'], $returnmonth);
                                    //$Data1 = $b2bData1 = $obj_gstr4->getB2BInvoices($_SESSION['user_detail']['user_id'], $returnmonth,'all');
                                    $total = $invoice_total_value = $sumTotal = $igstTotal = $sgstTotal = $cgstTotal = $cessTotal = 0;
                                    $invoice_temp = $invoice_temp_reference_number = '';
                                    $invoice_total_value_temp = '';
                                    $invCount =0;
                                    if (!empty($b2bData)) {
                                        
                                        foreach ($b2bData as $key => $b2bDatavalue) {
                                            if($invoice_temp!='' and $invoice_temp!=$b2bDatavalue->invoice_id)
                                            {
                                                $invCount++;
                                               // $invoice_total_value +=$invoice_total_value_temp;
                                                $sumTotal +=$sumTotal_temp;
												
                                            }
											
											$invoice_total_value_temp += isset($b2bDatavalue->taxable_subtotal)?$b2bDatavalue->taxable_subtotal:0;
										    $total += $b2bDatavalue->cgst + $b2bDatavalue->sgst + $b2bDatavalue->igst + $b2bDatavalue->cess;
                                            $igstTotal += $b2bDatavalue->igst;
                                            $sgstTotal += $b2bDatavalue->sgst;
                                            $cgstTotal += $b2bDatavalue->cgst;
                                            $cessTotal += $b2bDatavalue->cess;
                                            $sumTotal_temp = isset($b2bDatavalue->totalamount)?$b2bDatavalue->totalamount:0;
                                            $invoice_temp=$b2bDatavalue->invoice_id;
                                           
                                        }
                                        if($invoice_temp!='')
                                        {
                                            $invCount++;
                                            $invoice_total_value +=$invoice_total_value_temp;
                                            $sumTotal +=$sumTotal_temp;
                                        }
                                    }
                                }
								if($type=='ATADJ')
                                {
                                    $Data = $b2bData =  $obj_gstr4->getPurchaseAtadjInvoices($_SESSION['user_detail']['user_id'], $returnmonth);
									
                                    //$Data1 = $b2bData1 = $obj_gstr4->getB2BInvoices($_SESSION['user_detail']['user_id'], $returnmonth,'all');
                                    $total = $invoice_total_value = $sumTotal = $igstTotal = $sgstTotal = $cgstTotal = $cessTotal = 0;
                                    $invoice_temp = $invoice_temp_reference_number = '';
                                    $invoice_total_value_temp = '';
                                    $invCount =0;
                                    if (!empty($b2bData)) {
                                        
                                        foreach ($b2bData as $key => $b2bDatavalue) {
                                            if($invoice_temp!='' and $invoice_temp!=$b2bDatavalue->invoice_id)
                                            {
                                                $invCount++;
                                               // $invoice_total_value +=$invoice_total_value_temp;
                                                $sumTotal +=$sumTotal_temp;
												
                                            }
											
											$invoice_total_value_temp += isset($b2bDatavalue->taxable_subtotal)?$b2bDatavalue->taxable_subtotal:0;
										    $total += $b2bDatavalue->cgst + $b2bDatavalue->sgst + $b2bDatavalue->igst + $b2bDatavalue->cess;
                                            $igstTotal += $b2bDatavalue->igst;
                                            $sgstTotal += $b2bDatavalue->sgst;
                                            $cgstTotal += $b2bDatavalue->cgst;
                                            $cessTotal += $b2bDatavalue->cess;
                                            $sumTotal_temp = isset($b2bDatavalue->totalamount)?$b2bDatavalue->totalamount:0;
                                            $invoice_temp=$b2bDatavalue->invoice_id;
                                            $invCount= $b2bDatavalue->totalinvoice;
                                        }
                                        if($invoice_temp!='')
                                        {
                                           
                                            $invoice_total_value +=$invoice_total_value_temp;
                                            $sumTotal +=$sumTotal_temp;
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
                                            $sql = "select id from " . $obj_gstr4->getTableName('gstr1_return_summary') . " where return_period='" . $returnmonth . "' and added_by='" . $_SESSION['user_detail']['user_id'] . "' and is_uploaded='1' and status = '1' "; 
                                            $dataReturn = $obj_gstr4->get_results($sql);
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
                                    <table style="width:80%;" width="80%" border="0" cellspacing="0" cellpadding="0"  class="table table-striped invoice-filter-table" id="mainTable1">
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
                                
                                else { ?>
                                    <table style="width:80%;"   width="80%" border="0" cellspacing="0" cellpadding="0"  class="table table-striped invoice-filter-table" id="mainTable1">
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
									<?php 
									if($type=='CDNR')
												{  ?>
									 <table style="display: block;overflow-x: auto;white-space: nowrap;width:80%;" width="100%" border="0" cellspacing="0" cellpadding="0" class="invoice-itemtable" id="mainTable1">
								<?php }else { ?>  <table style="display: block;overflow-x: auto;white-space: nowrap;width:100%;" width="100%" border="0" cellspacing="0" cellpadding="0" class="invoice-itemtable" id="mainTable1"><?php } ?>
                                            <thead>
                                                
                                                <?PHP 
												if($type=='B2B')
												{
													?>
                                                    <tr>
                                                       
                                                        <th align='left'>No.</th>
                                                        <th align='left'>GSTIN</th>
                                                        <th align='left'>InvoiceNumber</th>
														<th align='left'>InvoiceDate</th>
														<th align='left'>Invoicevalue</th>
														<th align='left'>POS</th>
														
														<th align='left'>Rate</th>
														<th align='left'>Taxablevalue</th>
														<th align='left'>IGST</th>
														<th align='left'>CGST</th>
														<th align='left'>SGST</th>
														<th align='left'>CESS</th> 
														<th align='left'>View</th>														
														
                                                        
                                                    </tr>
													<tr>
													 <tr>
													 <?php
											$b2bData =  $obj_gstr4->getPurchaseB2BInvoicesDetails($_SESSION['user_detail']['user_id'], $returnmonth);
											$i=1;
                                            if(!empty($b2bData))
											{ foreach($b2bData as $Item) 
												{ 
												?>
                                           <td align='left'><?php echo $i++;?></td>
                                           <td align='left'><?php echo $Item->supplier_gstn;?></td>
                                           <td align='left'><?php echo $Item->invoice_number;?></td>
                                           <td align='left'><?php echo $Item->invoice_date;?></td>
                                           <td align='left'><?php echo $Item->totalamount;?></td>
										   <td align='left'><?php echo $Item->state_name;?></td>
                                           <td style='text-align:right'><?php echo $Item->consolidate_rate; ?></td>
                                           <td style='text-align:right'><?php echo $Item->taxable_subtotal;?></td>
                                           <td align='center'><?php echo $Item->igst;?></td>
										   <td align='center'><?php echo $Item->cgst;?></td>
											<td align='center'><?php echo $Item->sgst;?></td>
											<td align='center'><?php echo $Item->cess;?></td>
                                           <td align='center'><a href="<?php echo $url; ?>" target="_blank">View</a></td>
										  </tr>                             
												<?php } } ?>
													<?php } ?>
													
													<?PHP 
												if($type=='B2BUR')
												{
													?>
                                                    <tr>
                                                       
                                                        <th align='left'>No.</th>
                                                        <th align='left'>Invoice Number</th>
														<th align='left'>Invoice Date</th>
														<th align='left'>Invoice value</th>
														<th align='left'>POS</th>
														<th align='left'>Rate</th>
														<th align='left'>Taxablevalue</th>
														<th align='left'>IGST</th>
														<th align='left'>CGST</th>
														<th align='left'>SGST</th>
														<th align='left'>CESS</th> 
														<th align='left'>View</th>														
														
                                                        
                                                    </tr>
													<tr>
													 <tr>
													 <?php
											$b2burData =  $obj_gstr4->getPurchaseB2BurInvoicesDetails($_SESSION['user_detail']['user_id'], $returnmonth);
											$i=1;
                                            if(!empty($b2burData))
											{ foreach($b2burData as $Item) 
												{ 
												?>
                                           <td align='left'><?php echo $i++;?></td>
                                           <td align='left'><?php echo $Item->invoice_number;?></td>
                                           <td align='left'><?php echo $Item->invoice_date;?></td>
                                           <td align='left'><?php echo $Item->totalamount;?></td>
										   <td align='left'><?php echo $Item->state_name;?></td>
                                          <td style='text-align:right'><?php echo $Item->consolidate_rate; ?></td>
                                           <td style='text-align:right'><?php echo $Item->taxable_subtotal;?></td>
                                           <td align='center'><?php echo $Item->igst;?></td>
										   <td align='center'><?php echo $Item->cgst;?></td>
											<td align='center'><?php echo $Item->sgst;?></td>
											<td align='center'><?php echo $Item->cess;?></td>
                                           <td align='center'><a href="<?php echo $url; ?>" target="_blank">View</a></td>
										  </tr>                             
												<?php } } ?>
													<?php } ?>
													<?PHP 
												if($type=='IMPS')
												{
													?>
                                                    <tr>
                                                       
                                                        <th align='left'>No.</th>
                                                        <th align='left'>Invoice Number</th>
														<th align='left'>Invoice Date</th>
														<th align='left'>Invoice value</th>
														<th align='left'>POS</th>
														<th align='left'>Rate</th>
														<th align='left'>Taxablevalue</th>
														<th align='left'>IGST</th>
														<th align='left'>CESS</th> 
														<th align='left'>View</th>														
														
                                                        
                                                    </tr>
													<tr>
													 <tr>
													 <?php
											$b2burData =  $obj_gstr4->getPurchaseImportInvoicesDetails($_SESSION['user_detail']['user_id'], $returnmonth);
											$i=1;
                                            if(!empty($b2burData))
											{ foreach($b2burData as $Item) 
												{ 
												?>
                                           <td align='left'><?php echo $i++;?></td>
                                           <td align='left'><?php echo $Item->invoice_number;?></td>
                                           <td align='left'><?php echo $Item->invoice_date;?></td>
                                           <td align='left'><?php echo $Item->totalamount;?></td>
										   <td align='left'><?php echo $Item->state_name;?></td>
                                          <td style='text-align:right'><?php echo $Item->consolidate_rate; ?></td>
                                           <td style='text-align:right'><?php echo $Item->taxable_subtotal;?></td>
                                           <td align='center'><?php echo $Item->igst;?></td>
										   <td align='center'><?php echo $Item->cess;?></td>
                                           <td align='center'><a href="<?php echo $url; ?>" target="_blank">View</a></td>
										  </tr>                             
												<?php } } ?>
													<?php } ?>
														<?PHP 
												if($type=='CDNR')
												{
													?>
                                                    <tr>
                                                       
                                                        <th align='left'>No.</th>
														<th align='left'>GSTN Supp.</th>
														<th align='left'>RefundVoucher Number</th>
														<th align='left'>Invoice Number</th>
														<th align='left'>Invoice Date</th>
														<th align='left'>Invoice value</th>
														<th align='left'>POS</th>
														<th align='left'>Rate</th>
														<th align='left'>Taxablevalue</th>
														<th align='left'>IGST</th>
														<th align='left'>CESS</th> 
														<th align='left'>View</th>														
														
                                                        
                                                    </tr>
													<tr>
													 <tr>
													 <?php
											$cdnrData =  $obj_gstr4->getPurchaseCdnrInvoicesDetails($_SESSION['user_detail']['user_id'], $returnmonth);
											$i=1;
                                            if(!empty($cdnrData))
											{ foreach($cdnrData as $Item) 
												{ 
												?>
                                           <td align='left'><?php echo $i++;?></td>
                                           <td align='left'><?php echo $Item->invoice_number;?></td>
										   <td align='left'><?php echo $Item->supplier_gstn;?></td>
										    <td align='left'><?php echo $Item->reference_number;?></td>
										
                                           <td align='left'><?php echo $Item->invoice_date;?></td>
                                           <td align='left'><?php echo $Item->totalamount;?></td>
										   <td align='left'><?php echo $Item->state_name;?></td>
                                          <td style='text-align:right'><?php echo $Item->consolidate_rate; ?></td>
                                           <td style='text-align:right'><?php echo $Item->taxable_subtotal;?></td>
                                           <td align='center'><?php echo $Item->igst;?></td>
										   <td align='center'><?php echo $Item->cess;?></td>
                                           <td align='center'><a href="<?php echo $url; ?>" target="_blank">View</a></td>
										  </tr>                             
												<?php } } ?>
													<?php } ?>
													<?PHP 
												if($type=='CDNUR')
												{
													?>
                                                    <tr>
                                                       
                                                        <th align='left'>No.</th>
														<th align='left'>GSTN Supp.</th>
														<th align='left'>RefundVoucher Number</th>
														<th align='left'>Invoice Number</th>
														<th align='left'>Invoice Date</th>
														<th align='left'>Invoice value</th>
														<th align='left'>POS</th>
														<th align='left'>Rate</th>
														<th align='left'>Taxablevalue</th>
														<th align='left'>IGST</th>
														<th align='left'>CESS</th> 
														<th align='left'>View</th>														
														
                                                        
                                                    </tr>
													<tr>
													 <tr>
													 <?php
											$cdnurData =  $obj_gstr4->getPurchaseCdnurInvoicesDetails($_SESSION['user_detail']['user_id'], $returnmonth);
											$i=1;
                                            if(!empty($cdnurData))
											{ foreach($cdnurData as $Item) 
												{ 
												?>
                                           <td align='left'><?php echo $i++;?></td>
                                           <td align='left'><?php echo $Item->invoice_number;?></td>
										   <td align='left'><?php echo $Item->supplier_gstn;?></td>
										    <td align='left'><?php echo $Item->reference_number;?></td>
										
                                           <td align='left'><?php echo $Item->invoice_date;?></td>
                                           <td align='left'><?php echo $Item->totalamount;?></td>
										   <td align='left'><?php echo $Item->state_name;?></td>
                                          <td style='text-align:right'><?php echo $Item->consolidate_rate; ?></td>
                                           <td style='text-align:right'><?php echo $Item->taxable_subtotal;?></td>
                                           <td align='center'><?php echo $Item->igst;?></td>
										   <td align='center'><?php echo $Item->cess;?></td>
                                           <td align='center'><a href="<?php echo $url; ?>" target="_blank">View</a></td>
										  </tr>                             
												<?php } } ?>
													<?php } ?>
													<?PHP 
												if($type=='AT')
												{
													?>
                                                    <tr>
                                                       
                                                        <th align='left'>No.</th>
														<th align='left'>PlaceOfSupply</th>
														<th align='left'>SupplyType</th>
														<th align='left'>Rate</th>
														<th align='left'>GrossAdvancePaid</th>
														<th align='left'>IGST</th>
														<th align='left'>CGST</th>
														<th align='left'>SGST</th>
														<th align='left'>CESS</th> 
														<th align='left'>View</th>														
														
                                                        
                                                    </tr>
													<tr>
													 <tr>
													 <?php
											$atData =  $obj_gstr4->getPurchaseATInvoicesDetails($_SESSION['user_detail']['user_id'], $returnmonth);
											$i=1;
                                            if(!empty($atData))
											{ foreach($atData as $Item) 
												{ 
												?>
                                           <td align='left'><?php echo $i++;?></td>
                                           <td align='left'><?php echo $Item->state_name;?></td>
										   <td align='left'><?php if($Item->company_state==$Item->supply_place) { echo 'Inter-state'; } else { echo 'Intra-state'; }?></td>
										 
                                           <td align='left'><?php echo $Item->consolidate_rate;?></td>
                                           <td align='left'><?php echo $Item->totalamount;?></td>
										   <td align='left'><?php echo $Item->igst;?></td>
                                          <td style='text-align:right'><?php echo $Item->cgst; ?></td>
                                           <td style='text-align:right'><?php echo $Item->sgst;?></td>
                                          <td align='center'><?php echo $Item->cess;?></td>
                                           <td align='center'><a href="<?php echo $url; ?>" target="_blank">View</a></td>
										  </tr>                             
												<?php } } ?>
													<?php } ?>
													<?PHP 
												if($type=='ATADJ')
												{
													?>
                                                    <tr>
                                                       
                                                        <th align='left'>No.</th>
														<th align='left'>PlaceOfSupply</th>
														<th align='left'>SupplyType</th>
														<th align='left'>Rate</th>
														<th align='left'>GrossAdvancePaid</th>
														<th align='left'>IGST</th>
														<th align='left'>CGST</th>
														<th align='left'>SGST</th>
														<th align='left'>CESS</th> 
														<th align='left'>View</th>														
														
                                                        
                                                    </tr>
													<tr>
													 <tr>
													 <?php
											$atData =  $obj_gstr4->getPurchaseAtadjInvoicesDetails($_SESSION['user_detail']['user_id'], $returnmonth);
											$i=1;
                                            if(!empty($atData))
											{ foreach($atData as $Item) 
												{ 
												?>
                                           <td align='left'><?php echo $i++;?></td>
                                           <td align='left'><?php echo $Item->state_name;?></td>
										   <td align='left'><?php if($Item->company_state==$Item->supply_place) { echo 'Inter-state'; } else { echo 'Intra-state'; }?></td>
										 
                                           <td align='left'><?php echo $Item->consolidate_rate;?></td>
                                           <td align='left'><?php echo $Item->totalamount;?></td>
										   <td align='left'><?php echo $Item->igst;?></td>
                                          <td style='text-align:right'><?php echo $Item->cgst; ?></td>
                                           <td style='text-align:right'><?php echo $Item->sgst;?></td>
                                          <td align='center'><?php echo $Item->cess;?></td>
                                           <td align='center'><a href="<?php echo $url; ?>" target="_blank">View</a></td>
										  </tr>                             
												<?php } } ?>
													<?php } ?>
                                               
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
            document.form2.action = '<?php echo PROJECT_URL; ?>/?page=return_gstr4view_invoices&returnmonth=<?php echo $returnmonth; ?>';
            document.form2.submit();
        });
        $('.type').on('change', function () {
            document.form3.action = '<?php echo PROJECT_URL; ?>/?page=return_gstr4view_invoices&returnmonth=<?php echo $returnmonth; ?>';
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