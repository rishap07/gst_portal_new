<?php
$obj_gstr2 = new gstr2();
$dataCurrentUserArr = $obj_gstr2->getUserDetailsById($_SESSION['user_detail']['user_id']);
$returnmonth = date('Y-m');
if (isset($_REQUEST['returnmonth']) && $_REQUEST['returnmonth'] != '') {
    $returnmonth = $_REQUEST['returnmonth'];
    $startdate=$returnmonth."-10";
    $enddate=$returnmonth."-18";
}
?>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="col-md-6 col-sm-6 col-xs-12 heading"><h1>GSTR-2 Filing</h1></div>
        <div class="col-md-6 col-sm-6 col-xs-12 text-right breadcrumb-nav">
            <a href="#">Home</a><i class="fa fa-angle-right" aria-hidden="true"></i>
            <a href="#">File Return</a><i class="fa fa-angle-right" aria-hidden="true"></i>
            <span class="active">GSTR-2 Filing</span>
        </div>

        <div class="whitebg formboxcontainer">
            <div class="pull-right rgtdatetxt">
                <form method='post' name='form5' id="form5">
                    Month Of Return 
                    <?php
                    $dataQuery = "SELECT DATE_FORMAT(invoice_date,'%Y-%m') AS niceDate FROM gst_client_invoice group by nicedate";
                    $dataRes = $obj_gstr2->get_results($dataQuery);
                    if (!empty($dataRes)) {
                        ?>
                        <select class="dateselectbox" id="returnmonth" name="returnmonth">
                            <?php foreach ($dataRes as $dataRe) { ?>
                                <option value="<?php echo $dataRe->niceDate; ?>" <?php
                                if ($dataRe->niceDate == $returnmonth) {
                                    echo 'selected';
                                }
                                ?>><?php echo $dataRe->niceDate; ?></option>
                                    <?php }
                                    ?>
                        </select>
                    <?php } else {
                        ?>
                        <select class="dateselectbox" id="returnmonth" name="returnmonth">
                            <option value="2017-07">2017-07</option>
                        </select>
                    <?php }
                    ?>
                </form>
            </div>

            <div class="col-md-12 col-sm-12 col-xs-12 tablistnav padleft0">
                <?php
                              include(PROJECT_ROOT."/modules/return/include/tab.php");
               ?>
            </div>
            <div class="clear"></div>
            <?php
            $dataReturns = $obj_gstr2->get_results("select * from " . TAB_PREFIX . "return where return_month='" . $returnmonth . "' and client_id='" . $_SESSION['user_detail']['user_id'] . "' and status='3' and type='gstr2'");
            if (!empty($dataReturns)) {
                ?>
                <div id="sucmsg" style="background-color:#DBEDDF;border-radius:4px;padding:8px 35px 8px 14px;text-shadow:0 1px 0 rgba(255, 255, 255, 0.5);margin-bottom:18px;border-color:#D1E8DA;color:#39A25F;"><i class="fa fa-check"></i> <b>Success:</b> GSTR2 is Already Filed</div>
                <?php
            }
            ?>
            <div class="clear"></div>

            <div class="row gstr2-reconcile">

                <?php
                $missingAddressed = 0;
                $additionalAddressed = 0;
                $mismatchedAddressed = 0;
                $matched = 0;
                $missing = 0;
                $additional = 0;
                $mismatched = 0;
                $matchId = array();
                $mismatchId = array();
                $additionalId = array();
                $missingId = array();
                $addressed = 0;
                $query="select
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
                    AND i.billing_gstin_number='" . $dataCurrentUserArr['data']->kyc->gstin_number . "'
                    AND i.status='1' 
                    AND i.is_canceled='0' 
                    AND i.is_deleted='0' 
                    AND i.is_gstr1_uploaded != '0'
                    AND i.is_gstr2_downloaded = '1' 
                    AND i.invoice_date BETWEEN '".$startdate."' AND  '".$enddate."'
                    group by i.invoice_id 
                    order by i.invoice_date ASC";
                $gstr2DownlodedInvoices = $obj_gstr2->get_results($query);
                //echo $query;
               
           //print_r($gstr2DownlodedInvoices);

                    $query = "select 
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
                        AND ci.invoice_date BETWEEN '".$startdate."' AND  '".$enddate."'
                       
                        AND ci.is_deleted='0' 
                        group by ci.purchase_invoice_id";

                    $purchaseInvoices = $obj_gstr2->get_results($query);
                   // print_r($purchaseInvoices);
$x=0;
$dataArr = array();
if($gstr2DownlodedInvoices){
foreach($gstr2DownlodedInvoices as $gstr2DownlodedInvoice)
{
    $flag = 0;
    foreach($purchaseInvoices as $purchaseInvoice)
    {
        if($gstr2DownlodedInvoice->reference_number==$purchaseInvoice->reference_number)
        {
            $flag=1;
            
            if($gstr2DownlodedInvoice->invoice_date!=$purchaseInvoice->invoice_date || $gstr2DownlodedInvoice->supply_place!=$purchaseInvoice->supply_place || $gstr2DownlodedInvoice->invoice_total_value!=$purchaseInvoice->invoice_total_value || $gstr2DownlodedInvoice->total_taxable_subtotal!=$purchaseInvoice->total_taxable_subtotal || $gstr2DownlodedInvoice->total_cgst_amount!=$purchaseInvoice->total_cgst_amount || $gstr2DownlodedInvoice->total_sgst_amount!=$purchaseInvoice->total_sgst_amount || $gstr2DownlodedInvoice->total_igst_amount!=$purchaseInvoice->total_igst_amount || $gstr2DownlodedInvoice->total_cess_amount!=$purchaseInvoice->total_cess_amount)
            {




                $dataArr[$x]['serial_number']=$gstr2DownlodedInvoice->serial_number;
                $dataArr[$x]['reference_number']=$gstr2DownlodedInvoice->reference_number;
                $dataArr[$x]['invoice_type']=$gstr2DownlodedInvoice->invoice_type;
                $dataArr[$x]['invoice_total_value']=$gstr2DownlodedInvoice->invoice_total_value;
                $dataArr[$x]['total_taxable_subtotal']=$gstr2DownlodedInvoice->total_taxable_subtotal;
                $dataArr[$x]['invoice_nature']=$gstr2DownlodedInvoice->invoice_nature;
                $dataArr[$x]['company_gstin_number']=$gstr2DownlodedInvoice->billing_gstin_number;
                $dataArr[$x]['invoice_date']=$gstr2DownlodedInvoice->invoice_date;
                $dataArr[$x]['total_cgst_amount']=$gstr2DownlodedInvoice->total_cgst_amount;
                $dataArr[$x]['total_igst_amount']=$gstr2DownlodedInvoice->total_igst_amount;
                $dataArr[$x]['total_sgst_amount']=$gstr2DownlodedInvoice->total_sgst_amount;
                $dataArr[$x]['total_cess_amount']=$gstr2DownlodedInvoice->total_cess_amount;
                $dataArr[$x]['invoice_status']='3';
                $dataArr[$x]['status']='1';
                $dataArr[$x]['added_by']=$_SESSION['user_detail']['user_id'];
                $dataArr[$x]['added_date']=date('Y-m-d H:i:s');
                 $x++;
                 $mismatched++;
            }
            else
            {
                $dataArr[$x]['serial_number']=$gstr2DownlodedInvoice->serial_number;
                $dataArr[$x]['reference_number']=$gstr2DownlodedInvoice->reference_number;
                $dataArr[$x]['invoice_type']=$gstr2DownlodedInvoice->invoice_type;
                $dataArr[$x]['invoice_total_value']=$gstr2DownlodedInvoice->invoice_total_value;
                $dataArr[$x]['total_taxable_subtotal']=$gstr2DownlodedInvoice->total_taxable_subtotal;   
                $dataArr[$x]['invoice_nature']=$gstr2DownlodedInvoice->invoice_nature;
                $dataArr[$x]['company_gstin_number']=$gstr2DownlodedInvoice->billing_gstin_number;             
                
                $dataArr[$x]['invoice_date']=$gstr2DownlodedInvoice->invoice_date;
                $dataArr[$x]['total_cgst_amount']=$gstr2DownlodedInvoice->total_cgst_amount;
                $dataArr[$x]['total_igst_amount']=$gstr2DownlodedInvoice->total_igst_amount;
                $dataArr[$x]['total_sgst_amount']=$gstr2DownlodedInvoice->total_sgst_amount;
                $dataArr[$x]['total_cess_amount']=$gstr2DownlodedInvoice->total_cess_amount;               
                $dataArr[$x]['invoice_status']='0';
                $dataArr[$x]['status']='1';
                $dataArr[$x]['added_by']=$_SESSION['user_detail']['user_id'];
                $dataArr[$x]['added_date']=date('Y-m-d H:i:s');
                 $x++;
                 $matched++;
            }
        }
    }
    if($flag==0)
    {
        $dataArr[$x]['serial_number']=$gstr2DownlodedInvoice->serial_number;
        $dataArr[$x]['reference_number']=$gstr2DownlodedInvoice->reference_number;
        $dataArr[$x]['invoice_type']=$gstr2DownlodedInvoice->invoice_type;
        $dataArr[$x]['invoice_total_value']=$gstr2DownlodedInvoice->invoice_total_value;
        $dataArr[$x]['total_taxable_subtotal']=$gstr2DownlodedInvoice->total_taxable_subtotal;
        $dataArr[$x]['invoice_nature']=$gstr2DownlodedInvoice->invoice_nature;
        $dataArr[$x]['company_gstin_number']=$gstr2DownlodedInvoice->billing_gstin_number;
        
        $dataArr[$x]['invoice_date']=$gstr2DownlodedInvoice->invoice_date;
        $dataArr[$x]['total_cgst_amount']=$gstr2DownlodedInvoice->total_cgst_amount;
        $dataArr[$x]['total_igst_amount']=$gstr2DownlodedInvoice->total_igst_amount;
        $dataArr[$x]['total_sgst_amount']=$gstr2DownlodedInvoice->total_sgst_amount;
        $dataArr[$x]['total_cess_amount']=$gstr2DownlodedInvoice->total_cess_amount;       
        $dataArr[$x]['invoice_status']='1';
        $dataArr[$x]['status']='1';
        $dataArr[$x]['added_by']=$_SESSION['user_detail']['user_id'];
        $dataArr[$x]['added_date']=date('Y-m-d H:i:s');
         $x++;
         $missing++;
    }
}

foreach($purchaseInvoices as $purchaseInvoice)
{
    $flag = 0;
    
    foreach($gstr2DownlodedInvoices as $gstr2DownlodedInvoice)
    {
        //echo $gstr2DownlodedInvoice->reference_number."__".$purchaseInvoice->reference_number."<br>";
        if($gstr2DownlodedInvoice->reference_number==$purchaseInvoice->reference_number)
        {
             $flag=1;
        }
    }
    if($flag==0)
    {
        $dataArr[$x]['serial_number']=$purchaseInvoice->serial_number;
        $dataArr[$x]['reference_number']=$purchaseInvoice->reference_number;
        $dataArr[$x]['invoice_type']=$purchaseInvoice->invoice_type;
        $dataArr[$x]['invoice_total_value']=$purchaseInvoice->invoice_total_value;
        $dataArr[$x]['total_taxable_subtotal']=$purchaseInvoice->total_taxable_subtotal;  
        $dataArr[$x]['invoice_nature']=$purchaseInvoice->invoice_nature;
        $dataArr[$x]['company_gstin_number']=$purchaseInvoice->supplier_billing_gstin_number;
        $dataArr[$x]['invoice_date']=$purchaseInvoice->invoice_date;
        $dataArr[$x]['total_cgst_amount']=$purchaseInvoice->total_cgst_amount;
        $dataArr[$x]['total_igst_amount']=$purchaseInvoice->total_igst_amount;
        $dataArr[$x]['total_sgst_amount']=$purchaseInvoice->total_sgst_amount;
        $dataArr[$x]['total_cess_amount']=$purchaseInvoice->total_cess_amount;        
        $dataArr[$x]['invoice_status']='2';
        $dataArr[$x]['status']='1';
        $dataArr[$x]['added_by']=$_SESSION['user_detail']['user_id'];
        $dataArr[$x]['added_date']=date('Y-m-d H:i:s');
         $x++;
         $additional++;
    }
}
//rint_r($dataArr);
$dataRes = $db_obj->get_results('select * from gst_client_reconcile_purchase_invoice1 where   added_by="'.$_SESSION['user_detail']['user_id'].'" and invoice_date BETWEEN "'.$startdate.'" AND  "'.$enddate.'"',false);
//print_r($dataRes);
$key = array_column($dataRes,'reference_number');
//print_r($key);
$dataArr1 = $dataArr;
$dataUpdate = array();
$y=0;
for($x=0;$x<count($dataArr);$x++)
{
    //echo $dataArr[$x]['reference_number']."<br>" ;  
    
    if(in_array($dataArr[$x]['reference_number'], $key))
    {
        $dataUpdate[$y]['where']['reference_number'] = $dataArr[$x]['reference_number'];
        $dataUpdate[$y]['set']['invoice_status'] = $dataArr[$x]['invoice_status'];
        $y++;
        unset($dataArr1[$x]);

    }
}
$i=0;
$dataArr2 = array();
foreach($dataArr1 as $dataAr)
{
   $dataArr2[$i]=$dataAr;
   $i++;
}
if(!empty($dataArr1))
{
    $db_obj->insertMultiple('gst_client_reconcile_purchase_invoice1',$dataArr2);
}
if(!empty($dataUpdate))
{
    $db_obj->updateMultiple('gst_client_reconcile_purchase_invoice1',$dataUpdate);
}

$dataRes = $db_obj->get_results('select invoice_status,status from gst_client_reconcile_purchase_invoice1 where   added_by="'.$_SESSION['user_detail']['user_id'].'" and invoice_date BETWEEN "'.$startdate.'" AND  "'.$enddate.'"',false);
$matached = 0;
$missing = 0;
$missStatus = 0;
$additional= 0;
$additionalStatus=0;
$mismatched = 0;
$mismatchedStatus=0;
for($x=0;$x<count($dataRes);$x++)
{
   if($dataRes[$x]['invoice_status']=='0')
   {
       $matached++;
   }
   if($dataRes[$x]['invoice_status']=='1')
   {
       $missing++;
       if($dataRes[$x]['status']!='1')
       {
          $missStatus++;
       }
   }
   if($dataRes[$x]['invoice_status']=='2')
   {
       $additional++;
       if($dataRes[$x]['status']!='1')
       {
          $additionalStatus++;
       }
   }
   if($dataRes[$x]['invoice_status']=='3')
   {
       $mismatched++;
       if($dataRes[$x]['status']!='1')
       {
          $mismatchedStatus++;
       }
   }
}

?>

                    <div class="row reconciliation">

                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <div class="lightgreen col-text">
                                <div class="dashcoltxt">
                                    <div class="boxtextheading pull-left">Matched</div>
                                    <?php if ($matched > 0) { ?> 
                                        <a class="pull-right btn bordergreen" href="<?php echo PROJECT_URL . '/?page=return_view_reconcile&returnmonth=' . $returnmonth . '&action=match' ?>">View Records</a>
                                    <?php } ?>
                                    <div class="clear height10"></div>
                                    <div class="txtnumber col-md-4 col-sm-4"><?php echo $matached ?><br/><span>RECORDS</span><br/></div>


                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <div class="lightblue col-text">
                                <div class="dashcoltxt">
                                    <div class="boxtextheading pull-left">Missing</div>            <?php if ($missing > 0) { ?> 
                                        <a class="pull-right btn borderblue" href="<?php echo PROJECT_URL . '/?page=return_view_reconcile&returnmonth=' . $returnmonth . '&action=missing' ?>">View Records</a>
                                    <?php } ?>
                                    <div class="clear height10"></div>
                                    <div class="txtnumber col-md-4 col-sm-4"><?php echo $missing ?><br/><span>RECORDS</span><br/></div>
                                    <div class="txtnumber col-md-4 col-sm-4"><?php echo $missStatus ?><br/><span>ADDRESSED</span><br/></div>
                                    <div class="txtnumber redtxt col-md-4 col-sm-4"><?php echo $missing - $missStatus ?><br/><span>PENDING</span><br/></div>

                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <div class="lightyellowbg col-text">
                                <div class="dashcoltxt">
                                    <div class="boxtextheading pull-left">Additional</div>
                                    <?php if ($additional > 0) { ?> 
                                        <a class="pull-right btn borderbrown" href="<?php echo PROJECT_URL . '/?page=return_view_reconcile&returnmonth=' . $returnmonth . '&action=additional' ?>">View Records</a>
                                    <?php } ?>
                                    <div class="clear height10"></div>
                                    <div class="txtnumber col-md-4 col-sm-4"><?php echo $additional ?><br/><span>RECORDS</span><br/></div>
                                    <div class="txtnumber col-md-4 col-sm-4"><?php echo $additionalStatus ?><br/><span>ADDRESSED</span><br/></div>
                                    <div class="txtnumber redtxt col-md-4 col-sm-4"><?php echo $additional - $additionalStatus ?><br/><span>PENDING</span><br/></div>

                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <div class="pinkbg col-text">
                                <div class="dashcoltxt">
                                    <div class="boxtextheading pull-left">Mismatch</div>
                                    <?php if ($mismatched > 0) { ?> 
                                        <a class="pull-right btn borderred" href="<?php echo PROJECT_URL . '/?page=return_view_reconcile&returnmonth=' . $returnmonth . '&action=mismatch'?>">View Records</a>
                                    <?php } ?>
                                    <div class="clear height10"></div>
                                    <div class="txtnumber col-md-4 col-sm-4"><?php echo $mismatched ?><br/><span>RECORDS</span><br/></div>
                                    <div class="txtnumber col-md-4 col-sm-4"><?php echo $mismatchedStatus ?><br/><span>ADDRESSED</span><br/></div>
                                    <div class="txtnumber redtxt col-md-4 col-sm-4"><?php echo $mismatched - $mismatchedStatus ?><br/><span>PENDING</span><br/></div>

                                </div>
                            </div>
                        </div>



                        <?php
                    } else {
                        echo "<div class='alert alert-danger fade in'> <strong>Error!</strong> Please download GSTR2A From GSTN For current Month. </div>";
                    }
                    ?>

   <script>
    $(document).ready(function () {
        $('#returnmonth').on('change', function () {
            window.location.href = "<?php echo PROJECT_URL; ?>/?page=return_reconcile&returnmonth=" + $(this).val();
        });
    });
</script>