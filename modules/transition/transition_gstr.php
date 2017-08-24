<?php

$obj_return = new gstr3b();
$obj_transition = new transition();
$returnmonth = date('Y-m');

if(isset($_POST['returnmonth']))
{
    $returnmonth = $_POST['returnmonth'];
	$obj_return->redirect(PROJECT_URL."/?page=transaction_gstr&returnmonth=".$returnmonth);
	exit();
}
$returnmonth= date('Y-m');
if(isset($_REQUEST['returnmonth']) && $_REQUEST['returnmonth'] != '')
{
    $returnmonth= $_REQUEST['returnmonth'];
}
$returnmonth = date('Y-m');
if ($_REQUEST['returnmonth'] != '') {
    $returnmonth = $_REQUEST['returnmonth'];
}
if(isset($_POST['submit']) && $_POST['submit']=='submit') {
 $flag = $obj_return->checkVerifyUser();
  if($flag=='notverify')
  {
	  
  }
  else{
    if($obj_transition->saveGstrTransition()){
        //$obj_master->redirect(PROJECT_URL."/?page=master_receiver");
    }
  }
}
if(isset($_POST['finalsubmit']) && $_POST['finalsubmit']=='final submit') {
 
  $flag = $obj_return->checkVerifyUser();
  if($flag=='notverify')
{
						  
} else{
 				  
    if($obj_return->finalSaveGstr3b()){
        //$obj_master->redirect(PROJECT_URL."/?page=master_receiver");
    }
}
}
if(isset($_POST['cleardata']) && $_POST['cleardata']=='clear data') {
 
			
    if($obj_return->deleteSaveGstr3b()){
        //$obj_master->redirect(PROJECT_URL."/?page=master_receiver");
    }
}






       
	   $sql = "select  *,count(return_id) as totalinvoice from ".TAB_PREFIX."client_return_gstr3b where added_by='" . $_SESSION['user_detail']['user_id'] . "' and financial_month like '%" . $returnmonth . "%' and is_deleted='0'  order by return_id desc limit 0,1";
 
        $returndata = $obj_return->get_results($sql);
		
		
		
		
		 
		 
		 
	     $tdsTotquery = "SELECT COUNT(i.invoice_id) as numcount,sum(item.cgst_amount) as cgst_amount,sum(item.sgst_amount) as sgst_amount,sum(igst_amount) as igst_amount,sum(cess_amount) as cess_amount FROM " . $db_obj->getTableName('client_invoice') . " as i inner join " . $db_obj->getTableName('client_invoice_item') . " as item on item.invoice_id = i.invoice_id WHERE i.invoice_nature='salesinvoice'  and i.added_by='" . $_SESSION["user_detail"]["user_id"] . "' and i.is_canceled='0' and supply_type='tds' and  invoice_date like '%" . $returnmonth . "%'";
      // echo "<br>";
	    $tdsTotData = $obj_return->get_results($tdsTotquery);
        $total = 0;
        if (!empty($tdsTotData)) {
         // $total = $tdsTotData[0]->cgst_amount + $b2bItemData[0]->sgst_amount + $tdsTotData[0]->igst_amount + $tdsTotData[0]->cess_amount;
         }
	    $tcsTotquery = "SELECT COUNT(i.invoice_id) as numcount,sum(item.cgst_amount) as cgst_amount,sum(item.sgst_amount) as sgst_amount,sum(igst_amount) as igst_amount,sum(cess_amount) as cess_amount FROM " . $db_obj->getTableName('client_invoice') . " as i inner join " . $db_obj->getTableName('client_invoice_item') . " as item on item.invoice_id = i.invoice_id WHERE i.invoice_nature='salesinvoice'  and i.added_by='" . $_SESSION["user_detail"]["user_id"] . "' and i.is_canceled='0' and supply_type='tcs' and  invoice_date like '%" . $returnmonth . "%'";
  
	    $tcsTotData = $obj_return->get_results($tcsTotquery);
        $total = 0;
        if (!empty($tcsTotData)) {
         // $total = $tdsTotData[0]->cgst_amount + $b2bItemData[0]->sgst_amount + $tdsTotData[0]->igst_amount + $tdsTotData[0]->cess_amount;
         }
  $nature_of_supply_a_Totquery = "SELECT sum(item.taxable_subtotal) as taxable_subtotal, COUNT(i.invoice_id) as numcount,sum(taxable_subtotal) as taxable_subtotal,sum(item.cgst_amount) as cgst_amount,sum(item.sgst_amount) as sgst_amount,sum(igst_amount) as igst_amount,sum(cess_amount) as cess_amount FROM " . $db_obj->getTableName('client_invoice') . " as i inner join " . $db_obj->getTableName('client_invoice_item') . " as item on item.invoice_id = i.invoice_id INNER join gst_vendor_type as v on v.vendor_id=i.billing_vendor_type WHERE i.invoice_nature='salesinvoice' and i.added_by='" . $_SESSION["user_detail"]["user_id"] . "' and i.is_canceled='0' and invoice_type not in('exportinvoice','sezunitinvoice','deemedexportinvoice') and (item.igst_rate > 0 or (item.sgst_rate > 0 and item.cgst_rate > 0)) and (v.vendor_id<>3 and v.vendor_id<>'5') and invoice_date like '%" . $returnmonth . "%'";
         
		
	    $nature_of_supply_a_TotData = $obj_return->get_results($nature_of_supply_a_Totquery);
        $total = 0; 
        if (!empty($nature_of_supply_a_TotData)) {
         // $total = $tdsTotData[0]->cgst_amount + $b2bItemData[0]->sgst_amount + $tdsTotData[0]->igst_amount + $tdsTotData[0]->cess_amount;
         }
	   $nature_of_supply_b_Totquery = "SELECT COUNT(i.invoice_id) as numcount,sum(item.taxable_subtotal) as taxable_subtotal,sum(item.cgst_amount) as cgst_amount,sum(item.sgst_amount) as sgst_amount,sum(igst_amount) as igst_amount,sum(cess_amount) as cess_amount FROM " . $db_obj->getTableName('client_invoice') . " as i inner join " . $db_obj->getTableName('client_invoice_item') . " as item on item.invoice_id = i.invoice_id WHERE i.invoice_nature='salesinvoice'  and i.added_by='" . $_SESSION["user_detail"]["user_id"] . "' and i.is_canceled='0' and invoice_type in('exportinvoice','sezunitinvoice','deemedexportinvoice') and  invoice_date like '%" . $returnmonth . "%'";

	     $nature_of_supply_b_TotData = $obj_return->get_results($nature_of_supply_b_Totquery);
        $total = 0;
        if (!empty($nature_of_supply_b_TotData)) {
         // $total = $tdsTotData[0]->cgst_amount + $b2bItemData[0]->sgst_amount + $tdsTotData[0]->igst_amount + $tdsTotData[0]->cess_amount;
         }
		//echo "<br>";
	   $nature_of_supply_c_Totquery = "SELECT COUNT(i.invoice_id) as numcount,sum(taxable_subtotal) as taxable_subtotal,sum(item.cgst_amount) as cgst_amount,sum(item.sgst_amount) as sgst_amount,sum(igst_amount) as igst_amount,sum(cess_amount) as cess_amount FROM " . $db_obj->getTableName('client_invoice') . " as i inner join " . $db_obj->getTableName('client_invoice_item') . " as item on item.invoice_id = i.invoice_id WHERE i.invoice_nature='salesinvoice'  and i.added_by='" . $_SESSION["user_detail"]["user_id"] . "' and i.is_canceled='0' and item.igst_rate = 0 and item.sgst_rate = 0 and item.cgst_rate = 0 and invoice_date like '%" . $returnmonth . "%'";
   
	    $nature_of_supply_c_TotData = $obj_return->get_results($nature_of_supply_c_Totquery);
        $total = 0;
        if (!empty($nature_of_supply_c_TotData)) {
         // $total = $tdsTotData[0]->cgst_amount + $b2bItemData[0]->sgst_amount + $tdsTotData[0]->igst_amount + $tdsTotData[0]->cess_amount;
         }
		 //echo "<br>";
		//$nature_of_supply_d_Totquery = "SELECT COUNT(i.invoice_id) as numcount,sum(taxable_subtotal) as taxable_subtotal,sum(item.cgst_amount) as cgst_amount,sum(item.sgst_amount) as sgst_amount,sum(igst_amount) as igst_amount,sum(cess_amount) as cess_amount FROM " . $db_obj->getTableName('client_invoice') . " as i inner join " . $db_obj->getTableName('client_invoice_item') . " as item on item.invoice_id = i.invoice_id WHERE i.invoice_nature='purchaseinvoice'  and supply_type='reversecharge' and i.added_by='" . $_SESSION["user_detail"]["user_id"] . "' and i.is_canceled='0' and invoice_date like '%" . $returnmonth . "%'";
         $nature_of_supply_d_Totquery = "SELECT COUNT(i.purchase_invoice_id) as numcount,sum(taxable_subtotal) as taxable_subtotal,sum(item.cgst_amount) as cgst_amount,sum(item.sgst_amount) as sgst_amount,sum(igst_amount) as igst_amount,sum(cess_amount) as cess_amount FROM " . $db_obj->getTableName('client_purchase_invoice') . " as i inner join " . $db_obj->getTableName('client_purchase_invoice_item') . " as item on item.purchase_invoice_id = i.purchase_invoice_id WHERE i.invoice_nature='purchaseinvoice'  and supply_type='reversecharge' and i.added_by='" . $_SESSION["user_detail"]["user_id"] . "' and i.is_canceled='0' and invoice_date like '%" . $returnmonth . "%'";
   
	    $nature_of_supply_d_TotData = $obj_return->get_results($nature_of_supply_d_Totquery);
        $total = 0;
        if (!empty($nature_of_supply_d_TotData)) {
         // $total = $tdsTotData[0]->cgst_amount + $b2bItemData[0]->sgst_amount + $tdsTotData[0]->igst_amount + $tdsTotData[0]->cess_amount;
         }
	
	 $nature_of_supply_e_Totquery = "SELECT COUNT(i.invoice_id) as numcount,sum(taxable_subtotal) as taxable_subtotal,sum(item.cgst_amount) as cgst_amount,sum(item.sgst_amount) as sgst_amount,sum(igst_amount) as igst_amount,sum(cess_amount) as cess_amount FROM " . $db_obj->getTableName('client_invoice') . " as i inner join " . $db_obj->getTableName('client_invoice_item') . " as item on item.invoice_id = i.invoice_id INNER join gst_client_master_item as ci on ci.item_id=item.item_id WHERE i.invoice_nature='salesinvoice' and  i.added_by='" . $_SESSION["user_detail"]["user_id"] . "' and i.is_canceled='0' and ci.is_applicable='1' and invoice_date like '%" . $returnmonth . "%'";
   
	    $nature_of_supply_e_TotData = $obj_return->get_results($nature_of_supply_e_Totquery);
        $total = 0;
        if (!empty($nature_of_supply_e_TotData)) {
         // $total = $tdsTotData[0]->cgst_amount + $b2bItemData[0]->sgst_amount + $tdsTotData[0]->igst_amount + $tdsTotData[0]->cess_amount;
         }
		 $supply_unregistered_Totquery = "SELECT COUNT(i.invoice_id) as numcount,sum(taxable_subtotal) as taxable_subtotal,sum(item.cgst_amount) as cgst_amount,sum(item.sgst_amount) as sgst_amount,sum(igst_amount) as igst_amount,sum(cess_amount) as cess_amount FROM " . $db_obj->getTableName('client_invoice') . " as i inner join " . $db_obj->getTableName('client_invoice_item') . " as item on item.invoice_id = i.invoice_id WHERE i.invoice_nature='salesinvoice' and billing_gstin_number=''  and billing_state <> company_state and i.added_by='" . $_SESSION["user_detail"]["user_id"] . "' and i.is_canceled='0' and invoice_date like '%" . $returnmonth . "%'";
   
	    $supply_unregistered_TotData = $obj_return->get_results($supply_unregistered_Totquery);
        $total = 0;
        if (!empty($supply_unregistered_TotData)) {
         // $total = $tdsTotData[0]->cgst_amount + $b2bItemData[0]->sgst_amount + $tdsTotData[0]->igst_amount + $tdsTotData[0]->cess_amount;
         }
	    $import_of_goods_Totquery = "SELECT COUNT(i.invoice_id) as numcount,sum(taxable_subtotal) as taxable_subtotal,sum(item.cgst_amount) as cgst_amount,sum(item.sgst_amount) as sgst_amount,sum(igst_amount) as igst_amount,sum(cess_amount) as cess_amount FROM " . $db_obj->getTableName('client_invoice') . " as i inner join " . $db_obj->getTableName('client_invoice_item') . " as item on item.invoice_id = i.invoice_id INNER join " . $db_obj->getTableName('item') . " as m on m.hsn_code = item.item_hsncode WHERE i.invoice_nature='purchaseinvoice' and i.added_by='" . $_SESSION["user_detail"]["user_id"] . "' and i.is_canceled='0' and m.item_type=0 and invoice_date like '%" . $returnmonth . "%'";
   
	    $import_of_goods_TotData = $obj_return->get_results($import_of_goods_Totquery);
        $total = 0;
        if (!empty($import_of_goods_TotData)) {
         // $total = $tdsTotData[0]->cgst_amount + $b2bItemData[0]->sgst_amount + $tdsTotData[0]->igst_amount + $tdsTotData[0]->cess_amount;
         }
		 $import_of_services_Totquery = "SELECT COUNT(i.invoice_id) as numcount,sum(taxable_subtotal) as taxable_subtotal,sum(item.cgst_amount) as cgst_amount,sum(item.sgst_amount) as sgst_amount,sum(igst_amount) as igst_amount,sum(cess_amount) as cess_amount FROM " . $db_obj->getTableName('client_invoice') . " as i inner join " . $db_obj->getTableName('client_invoice_item') . " as item on item.invoice_id = i.invoice_id INNER join " . $db_obj->getTableName('item') . " as m on m.hsn_code = item.item_hsncode WHERE i.invoice_nature='purchaseinvoice' and i.added_by='" . $_SESSION["user_detail"]["user_id"] . "' and i.is_canceled='0' and m.item_type=1 and invoice_date like '%" . $returnmonth . "%'";
   
	    $import_of_services_TotData = $obj_return->get_results($import_of_services_Totquery);
        $total = 0;
        if (!empty($import_of_services_TotData)) {
         // $total = $tdsTotData[0]->cgst_amount + $b2bItemData[0]->sgst_amount + $tdsTotData[0]->igst_amount + $tdsTotData[0]->cess_amount;
         }
		 $inward_supplies_r_Totquery = "SELECT COUNT(i.invoice_id) as numcount,sum(taxable_subtotal) as taxable_subtotal,sum(item.cgst_amount) as cgst_amount,sum(item.sgst_amount) as sgst_amount,sum(igst_amount) as igst_amount,sum(cess_amount) as cess_amount FROM " . $db_obj->getTableName('client_invoice') . " as i inner join " . $db_obj->getTableName('client_invoice_item') . " as item on item.invoice_id = i.invoice_id INNER join " . $db_obj->getTableName('item') . " as m on m.hsn_code = item.item_hsncode WHERE i.invoice_nature='purchaseinvoice' and i.added_by='" . $_SESSION["user_detail"]["user_id"] . "' and i.is_canceled='0' and m.item_type=1 and invoice_date like '%" . $returnmonth . "%'";
   
	    $inward_supplies_r_Data = $obj_return->get_results($inward_supplies_r_Totquery);
        $total = 0;
        if (!empty($inward_supplies_r_Data)) {
         // $total = $tdsTotData[0]->cgst_amount + $b2bItemData[0]->sgst_amount + $tdsTotData[0]->igst_amount + $tdsTotData[0]->cess_amount;
         }
		 
	// echo  $nature_of_supply_a_5aTotquery = "SELECT COUNT(i.invoice_id) as numcount,sum(taxable_subtotal) as taxable_subtotal,sum(item.cgst_amount) as cgst_amount,sum(item.sgst_amount) as sgst_amount,sum(igst_amount) as igst_amount,sum(cess_amount) as cess_amount FROM " . $db_obj->getTableName('client_invoice') . " as i inner join " . $db_obj->getTableName('client_invoice_item') . " as item on item.invoice_id = i.invoice_id WHERE i.invoice_nature='purchaseinvoice'  and i.added_by='" . $_SESSION["user_detail"]["user_id"] . "' and i.is_canceled='0' and item.igst_rate = 0 and item.sgst_rate = 0 and item.cgst_rate = 0 and invoice_date like '%" . $returnmonth . "%'";
   /*
	   $nature_of_supply_a_5a_TotData = $obj_return->get_results($nature_of_supply_a_5aTotquery);
        $total = 0;
        if (!empty($nature_of_supply_a_5a_TotData)) {
         // $total = $tdsTotData[0]->cgst_amount + $b2bItemData[0]->sgst_amount + $tdsTotData[0]->igst_amount + $tdsTotData[0]->cess_amount;
         }
	*/	 
	  $supply_unregistered="SELECT i.billing_state as state, COUNT(i.invoice_id) as numcount,sum(igst_amount) as igst_amount,sum(item.taxable_subtotal) as totaltaxable_value FROM gst_client_invoice as i inner join gst_client_invoice_item as item on item.invoice_id = i.invoice_id inner join gst_vendor_type as v on v.vendor_id = i.billing_vendor_type WHERE i.invoice_nature='salesinvoice' and (i.invoice_type <> 'deliverychallaninvoice' and i.invoice_type<>'creditnote' and i.invoice_type<>'refundvoucherinvoice') and i.added_by='" . $_SESSION["user_detail"]["user_id"] . "' and i.is_canceled='0' and i.is_deleted='0' and billing_gstin_number='' and (v.vendor_id<>'2' and v.vendor_id<>'4') and i.status='1' GROUP by i.billing_state";
	 $supply_unregistered_data = $obj_return->get_results($supply_unregistered);
        $total = 0;
        if (!empty($supply_unregistered_data)) {
         // $total = $tdsTotData[0]->cgst_amount + $b2bItemData[0]->sgst_amount + $tdsTotData[0]->igst_amount + $tdsTotData[0]->cess_amount;
         }
		
	$supply_composition="SELECT i.billing_state as state, COUNT(i.invoice_id) as numcount,sum(igst_amount) as igst_amount,sum(item.taxable_subtotal) as totaltaxable_value FROM gst_client_invoice as i inner join gst_client_invoice_item as item on item.invoice_id = i.invoice_id inner join gst_vendor_type as v on v.vendor_id = i.billing_vendor_type WHERE i.invoice_nature='salesinvoice' and (i.invoice_type <> 'deliverychallaninvoice' and i.invoice_type<>'creditnote' and i.invoice_type<>'refundvoucherinvoice') and i.added_by='" . $_SESSION["user_detail"]["user_id"] . "' and i.is_canceled='0' and i.is_deleted='0' and v.vendor_id='2' and i.status='1' GROUP by i.billing_state";
	 $supply_composition_data = $obj_return->get_results($supply_composition);
        $total = 0;
        if (!empty($supply_composition_data)) {
         // $total = $tdsTotData[0]->cgst_amount + $b2bItemData[0]->sgst_amount + $tdsTotData[0]->igst_amount + $tdsTotData[0]->cess_amount;
         }
	 $supply_uin_holder="SELECT i.billing_state as state, COUNT(i.invoice_id) as numcount,sum(igst_amount) as igst_amount,sum(item.taxable_subtotal) as totaltaxable_value FROM gst_client_invoice as i inner join gst_client_invoice_item as item on item.invoice_id = i.invoice_id inner join gst_vendor_type as v on v.vendor_id = i.billing_vendor_type WHERE i.invoice_nature='salesinvoice' and (i.invoice_type <> 'deliverychallaninvoice' and i.invoice_type<>'creditnote' and i.invoice_type<>'refundvoucherinvoice') and i.added_by='" . $_SESSION["user_detail"]["user_id"] . "' and i.is_canceled='0' and i.is_deleted='0' and v.vendor_id='4' and i.status='1' GROUP by i.billing_state";
	 $supply_uin_holder_data = $obj_return->get_results($supply_uin_holder);
        $total = 0;
        if (!empty($supply_uin_holder_data)) {
         // $total = $tdsTotData[0]->cgst_amount + $b2bItemData[0]->sgst_amount + $tdsTotData[0]->igst_amount + $tdsTotData[0]->cess_amount;
         }
	   ?>
       <div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
       			<div class="col-md-12 col-sm-12 col-xs-12">
               
                	<div class="col-md-6 col-sm-6 col-xs-12 heading"><h1>GSTR-3B</h1></div>
                    <div class="col-md-6 col-sm-6 col-xs-12 text-right breadcrumb-nav"><a href="#">Home</a>
					<i class="fa fa-angle-right" aria-hidden="true"></i>  <a href="#">File Return</a> <i class="fa fa-angle-right" aria-hidden="true"></i> <span class="active">GSTR-3B Filing</span> </div>
                     <div class="whitebg formboxcontainer">
				<?php $obj_return->showErrorMessage(); ?>
			    <?php if(isset($_POST['finalsubmit']) && $_POST['finalsubmit']=='final submit') {
				echo "<div id='sucmsg' style='background-color:#DBEDDF;border-radius:4px;padding:8px 35px 8px 14px;text-shadow:0 1px 0 rgba(255, 255, 255, 0.5);margin-bottom:18px;border-color:#D1E8DA;color:#39A25F;'><i class='fa fa-check'></i> <b>GSTR3B month of return ".$returnmonth." successfully submitted </div>";
		    
				}else{
				$obj_return->showSuccessMessge(); }?>
				<?php $obj_return->unsetMessage(); ?>
				<?php
				if(isset($_POST['submit']) && $_POST['submit']=='submit') {
                  
					  if($flag=='notverify')
					  {
						  
					  } else{
 
					 //echo "<div id='sucmsg' style='background-color:#DBEDDF;border-radius:4px;padding:8px 35px 8px 14px;text-shadow:0 1px 0 rgba(255, 255, 255, 0.5);margin-bottom:18px;border-color:#D1E8DA;color:#39A25F;'><i class='fa fa-check'></i> <b>GSTR3B successfully submitted </div>";
					  }
                 }
				if(isset($_POST['finalsubmit']) && $_POST['finalsubmit']=='final submit') {
                  
					  if($flag=='notverify')
					  {
						  
					  } else{
 
				 //echo "<div id='sucmsg' style='background-color:#DBEDDF;border-radius:4px;padding:8px 35px 8px 14px;text-shadow:0 1px 0 rgba(255, 255, 255, 0.5);margin-bottom:18px;border-color:#D1E8DA;color:#39A25F;'><i class='fa fa-check'></i> <b>GSTR3B month of return ".$returnmonth." successfully submitted </div>";
		      // echo $obj_return->showSuccessMessge(); 
				} }
				else{
				 if($returndata[0]->final_submit == 1){
		    echo "<div id='sucmsg' style='background-color:#DBEDDF;border-radius:4px;padding:8px 35px 8px 14px;text-shadow:0 1px 0 rgba(255, 255, 255, 0.5);margin-bottom:18px;border-color:#D1E8DA;color:#39A25F;'><i class='fa fa-check'></i> <b>GSTR3B month of return ".$returnmonth." already submitted </div>";
					
				} }?>
				<div class="tab">
                <a href="<?php echo PROJECT_URL . '/?page=transaction_gstr&returnmonth='.$returnmonth ?>" class="active">
                    Prepare GSTR-3B 
                </a>
                <a href="<?php echo PROJECT_URL . '/?page=return_filegstr3b_file&returnmonth='.$returnmonth ?>" >
                    File GSTR-3B
                </a>
              
            </div>
					  <div class="pull-right rgtdatetxt">
                                <form method='post' name='form2'>
                                    Month Of Return
                                    <?php
                                    $dataQuery = "SELECT DATE_FORMAT(invoice_date,'%Y-%m') AS niceDate FROM " . $db_obj->getTableName('client_invoice') . " group by nicedate";
                                    $dataRes = $obj_return->get_results($dataQuery);
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
							<?php
							if($returndata[0]->final_submit == 1)
							{
								?>
							<div class="inovicergttop">
                            <ul class="iconlist">

                                
                                  <li><a href="<?php echo PROJECT_URL; ?>/?page=transaction_gstr&action=downloadExcelInvoice&id=<?php echo $returndata[0]->return_id; ?>&returnmonth=<?php echo $returnmonth; ?>"><div data-toggle="tooltip" data-placement="bottom" title="Excel"><i class="fa fa-file-excel-o" aria-hidden="true"></i></div></a></li>
                                <li><a href="<?php echo PROJECT_URL; ?>/?page=transaction_gstr&action=downloadInvoice&id=<?php echo $returndata[0]->return_id; ?>&returnmonth=<?php echo $returnmonth; ?>"><div data-toggle="tooltip" data-placement="bottom" title="PDF"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></div></a></li>
                                <li><a href="<?php echo PROJECT_URL; ?>/?page=transaction_gstr&action=printInvoice&id=<?php echo $returndata[0]->return_id; ?>&returnmonth=<?php echo $returnmonth; ?>" target="_blank"><div data-toggle="tooltip" data-placement="bottom" title="PRINT"><i class="fa fa-print" aria-hidden="true"></i></div></a></li>
                                <li><a href="<?php echo PROJECT_URL; ?>/?page=transaction_gstr&action=emailInvoice&id=<?php echo $returndata[0]->return_id; ?>&returnmonth=<?php echo $returnmonth; ?>"><div data-toggle="tooltip" data-placement="bottom" title="Email"><i class="fa fa-envelope-o" aria-hidden="true"></i></div></a></li>
                                <!--<li><a href="#"><div data-toggle="tooltip" data-placement="bottom" title="Attached File"><i class="fa fa-paperclip" aria-hidden="true"></i></div></a></li>-->
                         </ul>
							</div><?php } ?>
                       <form method="post" enctype="multipart/form-data" id='form'> 
					  <div class="row">
                     	 <div class="col-md-12 col-sm-12 col-xs-12 form-group">

                            <label>1.GSTIN-<span class="starred"></span></label>
							 <label></label>
						     </div>
							 
							    <div class="col-md-12 col-sm-12 col-xs-12 form-group">

                            <label>2.LegalName of the registered person-<span class="starred"></span></label>
							 <label></label>
						 
							   </div>
							    <div class="col-md-4 col-sm-4 col-xs-12 form-group">

                            <label>3.TradeName of any-<span class="starred"></span></label>
							 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="total_tax_value_supplya" value="<?php if(isset($nature_of_supply_a_TotData[0]->taxable_subtotal)) { echo $nature_of_supply_a_TotData[0]->taxable_subtotal; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /> 
						    
							   </div><div class="clear"></div>
							    
                            <label>Wheather all the return required under existing law for the period of six month immediately preceding appoint date have been furnished<span class="starred"></span></label>
							 <div class="col-md-2 col-sm-2 col-xs-12 form-group">

							 <select name='coupon_status' id='coupon_status' class='required form-control'>
                        
						   <option value='1' <?php
                                    if (isset($dataCurrentArr[0]->status) && $dataCurrentArr[0]->status == 1) {
                                        echo "selected='selected'";
                                    }
                                    ?>>Active</option>
									 <option value='0' <?php
                                    if (isset($dataCurrentArr[0]->status) && $dataCurrentArr[0]->status==0) {
                                        echo "selected='selected'";
                                    }
                                    ?>>InActive</option>
							
                        </select>
						    
							   </div>
							   </div>
                    	<div class="greyheading">5.Amount of Tax credit carried forward in the return file under existing laws</div>
						<div class="greyheading">5.(A)Amount of cenvat Credit carried forward to electronic credit ledger as central tax</div>
                           <div class="tableresponsive">
						   <form method="post" enctype="multipart/form-data" id='form'>
                            <table  class="table  tablecontent tablecontent2 bordernone" id='table5a'>
                                <thead>
                                <tr>
                                <th>S.No</th>
                                <th>Registration(Central Excise and service tax)</th>
                                <th>Tax Return to which last return filled</th>
                                <th>DateOfFillingReturnSpecified in Column3</th>
                                <th>BalacneCenvat Carried forward in the said last return</th>
                              <th>Cenvat Carried admissible as ITC of central Tax</th>
                                </tr>
                                </thead>
                                
                                <tbody>
                                <tr>
                                <td class="lftheading" >1.</td>
                                 <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->total_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="5a_registration_no[]" 
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="5a_taxperiod_last_return[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="5a_dateoffilling_return[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="5a_balance_cenvat_credit[]"  class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="5a_cenvat_credit_admissible[]"  class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                    <td>
									 <a class="addMoreInvoice add-table5a"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
										</td>                  
                                </tr><tr style="display:none;"><td></td><td>Total</td><td></td><td></td><td></td><td></td></tr>
								           
                                                       
                              
                                     
							
                                </tbody>
                            </table>
                        </div>
						
						<div class="greyheading">B. Details of statutory form received for which credit is being carried forward</div>
						 <div class="tableresponsive">
						 <div class="greyheading">Amount of Tax credit forward to electronic credit</div>
						   <form method="post" enctype="multipart/form-data" id='form'>
                            <table  class="table  tablecontent tablecontent2 bordernone" id="table5bcform">
                                <thead>
                                <tr>
                                <th>TIN Issuer</th>
                                <th>Name Of Issuer</th>
                                <th>Sr.no. of Form</th>
                                <th>Amount</th>
                                <th>Applicable VatRate</th>
                             
                                </tr>
                                </thead>
                                
                                <tbody>
                                <tr>
                                <td class="lftheading" >CForm</td>
                                            
                                </tr>
								<tr>
								 <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->total_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="5bcform_tin_issuer[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="5bcform_nameof_issuer[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="5bcform_no_of_form[]" 
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="5bcform_amount[]" 
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="5bcform_applicable_vat_rate[]" 
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								 <td>
									 <a class="addMoreInvoice add-table5bcform"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
										</td> 
								</tr>
							 </tbody>
                            </table>
							
              <table  class="table  tablecontent tablecontent2 bordernone" id="table5bfform">
                               <tr>
                                <td class="lftheading" >F-Form</td>
                                            
                                </tr>
								<tr>
								 <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->total_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="5bfform_tin_issuer[]" 
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="5bfform_nameof_issuer[]" 
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="5bfform_no_of_form[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="5bfform_amount[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="5bfform_applicable_vat_rate[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
									 <a class="addMoreInvoice add-table5bfform"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
										</td> 
								</tr></table> <table  class="table  tablecontent tablecontent2 bordernone" id="table5bhiform">
								    <tr>
                                <td class="lftheading" >H/I Form</td></tr>
                                 <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->total_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="5bhiform_tin_issuer[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="5bhiform_name_of_issuer[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="5bhiform_no_of_form[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="5bhiform_amount[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="5bhiform_applicable_vat_rate[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                     <td>
									 <a class="addMoreInvoice add-table5bhiform"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
										</td>                  
                                </tr>      								
                                           
                              
                                     
							
                                </tbody>
                            </table>
                        </div>
					     <div class="greyheading">C.Amount of tax credit carried forward to electronic credit ledger as state/UT Tax</div>
					     <div class="tableresponsive">
						 <table  class="table  tablecontent tablecontent2 bordernone" id="table5c" >
                                <thead>
                                <tr>
                                <th>Registration No. in existing law</th>
                                <th>Balance Of ITC Of vat</th>
								<th colspan="2" align="center">C-Form</th>
								<th colspan="2" align="center">F-Form</th>
                               
								<th>ITC Reversal relatable to (3) and (5)</th>
								<th colspan="2" align="center">Hi-Form</th>
							
								 <th>Transition ITC 2-(4+6+7+9)</th>
                                </tr>
								<tr>
								<th>&nbsp;</th>
								<th>&nbsp;</th>
								 <th>Turnover for which form pending</th>
                                <th>Difference Tax payable On 3</th>
                                <th>Turnover which for form pending</th>
                                <th>Taxpayable on(5)</th>
								<th>&nbsp;</th>
								<th>Turnover which form pending</th>
								<th>Taxpayable on(7)</th>
								<th>&nbsp;</th>
								</tr>
                                </thead>
                                
                                <tbody>
                                <tr>
                              
                                 <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->total_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="5cform_registration_no[]" class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="5cform_balanceof_itc_vat[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="5cform_cform_turnover_form_pending[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="5cform_cform_taxpayable[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="5cform_fform_turnover_form_pending[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="5cform_fform_taxpayable[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="5cform_itcreversal_relatable[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="5cform_hiform_turnover_form_pending[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="5cform_hiform_taxpayable[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="5cform_hiform_transitionitc2[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
									 <a class="addMoreInvoice add-table5c"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
										</td> 
                                                      
                                </tr>
								                    
                                                       
                              
                                     
							
                                </tbody>
                            </table>
                          </div>		
<div class="greyheading">6.Details of Capital goods for which Unavailed credit has not been carried</div>
<div class="greyheading">A.Amount of unavailed cenvat credit in respect of capital goods carried forward to electronic credit ledger as central tax</div>
					     <div class="tableresponsive">
						 <table  class="table  tablecontent tablecontent2 bordernone" id="table6a">
                                <thead>
                                <tr>
                                <th>Sr.No.</th>
                                <th>Invoice<br>Documentno.</th>
								<th>Invoice<br> Document<br> Date</th>
								<th>Supplier<br> Registration<br> no <br>under<br> existing law</th>
                               
								<th>Recipient<br>Registration<br>Under<br> existing law</th>
								<th colspan="3" align="center" >Details of<br> capital<br> goods<br></th>
							
								 <th>Eligible<br>credit under<br> existing law</th>
								  <th>credit<br> availed under<br> existing law</th>
								    <th>ITC of centralTax(9-10)</th>
                                </tr>
								<tr>
								<th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th>
								<th>Value</th><th colspan="2">Duties and taxpad</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th>
								</tr>
								<tr>
								<th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th>
								<th>&nbsp;</th><th >ED</th><th>SAD</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th>
								</tr>
								
                                </thead>
                                
                                <tbody>
                                <tr>
                              <td></td>
                                 <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->total_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="6ainvoice_document_no[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="6ainvoice_document_date[]" class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="6asupplier_registration_no[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="6arecipients_registration_mo[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="6a_value[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="6a_ed_cvd[]" class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="6a_sad[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="6a_totaleligible_cenvat[]" class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="6a_totalcenvat_credit[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="6a_totalcenvat_credit_unavailed[]" class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                     <td>
									 <a class="addMoreInvoice add-table6a"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
										</td>                  
                                </tr>
								                    
                                                       
                              
                                     
							
                                </tbody>
                            </table>
                          </div>
<div class="greyheading">B.Amount of unavailed input tax credit carried forward to electronic credit ledger</div>
					     <div class="tableresponsive">
						 <table  class="table  tablecontent tablecontent2 bordernone" id="table6b">
                                <thead>
                                <tr>
                                <th>Sr.No.</th>
                                <th>Invoice<br>Documentno.</th>
								<th>Invoice<br> Document<br> Date</th>
								<th>Supplier<br> Registration<br> no <br>under<br> existing law</th>
                               
								<th>Recipient<br>Registration<br>Under<br> existing law</th>
								<th colspan="2" align="center" >Details of<br> capital<br> goods<br>on which credit is not availed</th>
							
								 <th>Eligible<br>credit under<br> existing law</th>
								  <th>credit<br> availed under<br> existing law</th>
								    <th>ITC of centralTax(9-10)</th>
                                </tr>
								<tr>
								<th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th>
								<th>Value</th><th colspan="2">Duties and taxpad</th><th>&nbsp;</th><th>&nbsp;</th>
								</tr>
								
								
                                </thead>
                                
                                <tbody>
                                <tr>
                              <td></td>
                                 
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="6binvoice_document_no[]" class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="6binvoice_document_date[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="6bsupplier_registration_no[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="6breceipients_registration_no[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="6b_value[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="6b_taxpaid_vat[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="6b_totaleligible_vat[]" value="<?php if(isset($nature_of_supply_a_TotData[0]->cess_amount)) { echo $nature_of_supply_a_TotData[0]->cess_amount; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="6b_totalvat_creditavailed[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="6b_totalvat_creditunavailed[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                    <td>
									 <a class="addMoreInvoice add-table6b"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
										</td>                   
                                </tr>				                    
                                                       
                              
                                     
							
                                </tbody>
                            </table>
                          </div>
<div class="greyheading">7.Details of Input held in terms of section </div>
					     <div class="tableresponsive">
						 <table  class="table  tablecontent tablecontent2 bordernone" id="table7a1">
                                <thead>
                                <tr>
                                <th>Sr.No.</th>
                                  <th colspan="5">Details of Input held in stock</th>
                              
                                </tr>
								<tr>
								<th>&nbsp;</th>
								<th>HSN(at 6 digit level)</th>
								<th>Unit</th>
								<th>Qty</th>
								<th>value</th>
								<th>Eligible Duties paid on such input</th>
							     </tr>
								 <tr><td colspan="6">7A where duties paid invoices are available</td></tr>
								
                                </thead>
                                
                                <tbody>
                                <tr>
                                             
								 
								 <td></td>
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="7a1_hsncode[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="7a1_unit[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="7a1_qty[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="7a1_value[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="7a1_eligible_duties[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                     <td>
									 <a class="addMoreInvoice add-table7a1"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
										</td>                     
                                </tr></tbody></table>
                                  <table  class="table  tablecontent tablecontent2 bordernone" id="table7a2">
								<tr><td colspan="6">Input Contained in finished and semi finished goods</td></tr>
										         <tr>
                                             
								 
								 <td></td>
								   <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="7a2_hsncode[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="7a2_unit[]" class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="7a2_unit[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="7a2_value[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="7a2_eligible_duties[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                    <td>
									 <a class="addMoreInvoice add-table7a2"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
										</td>                  
                                </tr></table>
 <table  class="table  tablecontent tablecontent2 bordernone" id="table7a3">								
                                  <tr><td colspan="6">7B Where duty paid invoices are not available</td></tr>
										         <tr>
                                             
								 
								 <td></td>
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="7a3_hsncode[]" class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="cess_tax_value_supplya" name="7a3_unit[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="cess_tax_value_supplya" name="7a3_qty[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="cess_tax_value_supplya" name="7a3_value[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="cess_tax_value_supplya" name="7a3_eligible_duties[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                   <td>
									 <a class="addMoreInvoice add-table7a3"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
										</td>                     
                                </tr>                                      
                                     
							
                               
                            </table>
                          </div>								  
						   						  
						   <div class="greyheading">B.Amount of eligible duties and taxes</div>
					     <div class="tableresponsive">
						 <table  class="table  tablecontent tablecontent2 bordernone" id="table7b">
                                <thead>
                                <tr>
                                <th>Name of supplier</th>
                                <th>Invoice Number</th>
								<th>Invoice Date</th>
								<th>Description</th>
                               	<th>Qty</th>
								<th>UQC</th>
							
								<th>Value</th>
								 <th>Eligible Duties and taxes</th>
								  <th>Vat</th>
								    <th>Date on which entered in receipt book </th>
                                </tr>						
																
                                </thead>
                                
                                <tbody>
                                <tr>
                              
                                 <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->total_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="7b_nameof_supplier[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="7b_invoice_number[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="7b_invoice_date[]" 
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="7b_description[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="7b_quantity[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="7b_uqc[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="7b_value[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="7b_eligible_duties[]" class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="7b_vat[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="7b_dateonwhich_receipients[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                   <td>
									 <a class="addMoreInvoice add-table7b"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
										</td>                      
                                </tr>				                    
                                                       
                              
                                     
							
                                </tbody>
                            </table>
                          </div>
						   <div class="greyheading">C.Amount of vat and entry tax paid on input supported by invoices/documents evidencing payment of tax carried forward to electronic credit ledger as SGST/UTGST </div>
					     <div class="tableresponsive">
						 <table  class="table  tablecontent tablecontent2 bordernone" id="table7c1" >
                                <thead>
                                <tr>
                                <th colspan="5" align="center">Details of Input in stock</th>
                                <th>Totaltax carried claimed under earlier law</th>
								<th>Total Input tax credit related to exempt sales</th>
								<th>Total Input tax credit admissible as SGST/UTGST</th>
                               	</tr><tr>
								<th>Description</th>
								<th>Unit</th>
								<th>Qty</th>
								<th>Value</th>
								<th>Vat(and entry) tax pad</th>
								<th>&nbsp;</th><th>&nbsp;</th>
								<th></th>
								
								</tr>						
																
                                </thead>
                                
                                <tbody> <tr><td>Inputs</td></tr>
                                <tr>
                              
                                
								  
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="7c1_description[]" class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="7c1_unit[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="7c1_qty[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="7c1_value[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="7c1_vat[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="7c1_totalinput_taxcredit[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="7c1_totalinput_taxcredit_exempt[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="7c1_totalinput_taxcredit_admissible[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                    <td>
									 <a class="addMoreInvoice add-table7c1"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
										</td>                     
                                </tr></tbody></table>
 <table  class="table  tablecontent tablecontent2 bordernone" id="table7c2">								
<tr><td colspan='8'>Inputs contained in semi-finished and finished goods</td></tr>
                                <tr>                           
                                				  
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="7c2_description[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="7c2_unit[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="7c2_qty[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="7c2_value[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="7c2_vat[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="7c2_totalinput_taxcredit[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="7c2_totalinput_taxcredit_exempt[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="7c2_totalinput_admissible[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                  <td>
									 <a class="addMoreInvoice add-table7c2"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
										</td>                     
                                </tr>					                    
                                                       
                              
                                     
							
                                </tbody>
                            </table>
                          </div>
						   <div class="greyheading">D.Stock of goods not supported by invoices/document evidencing payment of tax(credit in terms of rules 117(4)) To be there only vat at single point </div>
					     <div class="tableresponsive">
						 <table  class="table  tablecontent tablecontent2 bordernone" id="table7d">
                                <thead>
                                <tr>
                                <th colspan="5" align="center">Details of Input in stock</th></tr>
								<tr>
								<th>Description</th>
								<th>Unit</th>
								<th>Qty</th>
								<th>Value</th>
								<th>Vat(and entry) tax pad</th>
								
								
								</tr>
																
                                </thead>
                                <tbody> 
                                <tr>    
                                							 
								   <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="7d_description[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="7d_unit[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="7d_qty[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="7d_value[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="7d_vatentry_taxpad[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                      <td>
									 <a class="addMoreInvoice add-table7d"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
										</td>                 
                                </tr>	
             
                              
                                     
							
                                </tbody>
                            </table>
                          </div>
						   <div class="greyheading">8.Details of transfer of cenvat credit for registered person having centralized registration under existing law</div>
					     <div class="tableresponsive">
						 <table  class="table  tablecontent tablecontent2 bordernone" id="table8">
                                <thead>
                                <tr>
                                <th>Sr.no.</th>
                                <th>Registration no.<br>under existing law</th>
								<th>TaxPeriod To<br> which last<br> return file<br> existing law</th>
								<th>Date Of Filling<br>The return<br>Specified in column no.3</th>
								<th>Balance eligible<br> cenvat credit<br> carried forward<br> in the said last return</th>
								<th>GSTN of receiver same pan of ITC of central tax</th>
								<th colspan="2">Distribution document invoice</th>
								<th>ITC of central tax transfered</th>
								</tr>
								<tr><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th>
								<th>No.</th><th>Date</th><th>&nbsp;</th>
								</tr>
																
                                </thead>
                                
                                <tbody>
                                <tr>
                                     <td></td>                     
								 
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="8registration_no[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="8taxperiod_lastreturn[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="8dateoffilling_return[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="8balanceeligible_cenvat_credit[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="8gstnof_receiver[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="8distributionno[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="8distributiondate[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="8itcofcentral[]" class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                    <td>
									 <a class="addMoreInvoice add-table8"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
										</td>                   
                                </tr>				                    
                                </tbody>
                            </table>
                          </div>
						  <div class="greyheading">9.Details of goods sent to job worker and held in stock behalf of principal </div>
						    <div class="greyheading">a.Details of goods sent as principal to the job worker under section 141 </div>
					     <div class="tableresponsive">
						 <table  class="table  tablecontent tablecontent2 bordernone" id="table9a">
                                <thead>
                                <tr>
                                <th>Sr.no.</th>
                                <th>Challan No.</th>
								<th>ChallanDate</th>
								<th>Type of goods/Input/Semi-finished/Finished</th>
								<th colspan="5" style="text-align: center;">Details of goods with job-worker</th>
								
								</tr>
								<tr><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th>
								<th>HSN</th><th>Description</th><th>Unit</th><th>Qty</th><th>Value</th>
								</tr>
																
                                </thead>
                                
                                <tbody>
								  <tr><td colspan="9">GSTIN of job worker if is available</td></tr>
                                <tr>
                              
                                <td></td>
								  
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="9a1challan_no[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="9a1challan_date[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="9a1typeof_goods[]" class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="9a1_hsn[]" class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="9a1_description[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="9a1_unit[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="9a1_quantity[]"  class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="9a1_value[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                   <td>
									 <a class="addMoreInvoice add-table9a"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
										</td>                       
                                </tr>				                    
                                </tbody>
                            </table>
                          </div>
						   <div class="greyheading">b.Details of goods held in stock as job worker on behalf of the principal under section 141 </div>
					     <div class="tableresponsive">
						 <table  class="table  tablecontent tablecontent2 bordernone" id="table9b">
                                <thead>
                                <tr>
                                <th>Sr.no.</th>
                                <th>Challan No.</th>
								<th>ChallanDate</th>
								<th>Type of goods/Input/Semi-finished/Finished</th>
								<th colspan="5" style="text-align: center;">Details of goods with job-worker</th>
								
								</tr>
								<tr><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th>
								<th>HSN</th><th>Description</th><th>Unit</th><th>Qty</th><th>Value</th>
								</tr>
																
                                </thead>
                                
                                <tbody>
								  <tr><td colspan="9">GSTIN of manufacturer</td></tr>
                                <tr>
                              
                                
								 <td></td>
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="9b1challan_no[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="9b1challan_date[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="9b1typeof_goods[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="9b1_hsn[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="9b1_description[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="9b1_quantity[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="9b1_value[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="cess_tax_value_supplya" value="<?php if(isset($nature_of_supply_a_TotData[0]->cess_amount)) { echo $nature_of_supply_a_TotData[0]->cess_amount; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                  <td>
									 <a class="addMoreInvoice add-table9b"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
										</td>                        
                                </tr>				                    
                                </tbody>
                            </table>
                          </div>
						   <div class="greyheading">10.Details of goods held in stock as agent of behalf of the principal under section 142(14) of the SGST Act</div>
						  <div class="greyheading">a.Details of goods held as agent on behalf of the principal</div>
					     <div class="tableresponsive">
						 <table  class="table  tablecontent tablecontent2 bordernone">
                                <thead>
                                <tr>
                                <th>Sr.no.</th>
                                <th>GSTIN of principal</th>
							
								<th colspan="5" style="text-align: center;">Details of goods with agent</th>
								
								</tr>
								<tr><th>&nbsp;</th><th>&nbsp;</th>
								<th>Description</th><th>Unit</th><th>Qty</th><th>Value</th><th>InputTax to be taken</th>
								</tr>
																
                                </thead>
                                
                                <tbody>
								 
                                <tr>                     
                                
								 
								
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="state_tax_value_supplya" value="<?php if(isset($nature_of_supply_a_TotData[0]->sgst_amount)) { echo $nature_of_supply_a_TotData[0]->sgst_amount; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="cess_tax_value_supplya" value="<?php if(isset($nature_of_supply_a_TotData[0]->cess_amount)) { echo $nature_of_supply_a_TotData[0]->cess_amount; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="cess_tax_value_supplya" value="<?php if(isset($nature_of_supply_a_TotData[0]->cess_amount)) { echo $nature_of_supply_a_TotData[0]->cess_amount; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="cess_tax_value_supplya" value="<?php if(isset($nature_of_supply_a_TotData[0]->cess_amount)) { echo $nature_of_supply_a_TotData[0]->cess_amount; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="cess_tax_value_supplya" value="<?php if(isset($nature_of_supply_a_TotData[0]->cess_amount)) { echo $nature_of_supply_a_TotData[0]->cess_amount; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="cess_tax_value_supplya" value="<?php if(isset($nature_of_supply_a_TotData[0]->cess_amount)) { echo $nature_of_supply_a_TotData[0]->cess_amount; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="cess_tax_value_supplya" value="<?php if(isset($nature_of_supply_a_TotData[0]->cess_amount)) { echo $nature_of_supply_a_TotData[0]->cess_amount; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                                      
                                </tr><tr><td></td><td>Total</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>				                    
                                </tbody>
                            </table>
                          </div>
						  <div class="greyheading">b.Details of goods held by the agent</div>
					     <div class="tableresponsive">
						 <table  class="table  tablecontent tablecontent2 bordernone">
                                <thead>
                                <tr>
                                <th>Sr.no.</th>
                                <th>GSTIN of principal</th>
							
								<th colspan="5" style="text-align: center;">Details of goods with agent</th>
								
								</tr>
								<tr><th>&nbsp;</th><th>&nbsp;</th>
								<th>Description</th><th>Unit</th><th>Qty</th><th>Value</th><th>InputTax to be taken</th>
								</tr>
																
                                </thead>
                                
                                <tbody>
								 
                                <tr>                     
                                
								 
								
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="state_tax_value_supplya" value="<?php if(isset($nature_of_supply_a_TotData[0]->sgst_amount)) { echo $nature_of_supply_a_TotData[0]->sgst_amount; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="cess_tax_value_supplya" value="<?php if(isset($nature_of_supply_a_TotData[0]->cess_amount)) { echo $nature_of_supply_a_TotData[0]->cess_amount; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="cess_tax_value_supplya" value="<?php if(isset($nature_of_supply_a_TotData[0]->cess_amount)) { echo $nature_of_supply_a_TotData[0]->cess_amount; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="cess_tax_value_supplya" value="<?php if(isset($nature_of_supply_a_TotData[0]->cess_amount)) { echo $nature_of_supply_a_TotData[0]->cess_amount; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="cess_tax_value_supplya" value="<?php if(isset($nature_of_supply_a_TotData[0]->cess_amount)) { echo $nature_of_supply_a_TotData[0]->cess_amount; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="cess_tax_value_supplya" value="<?php if(isset($nature_of_supply_a_TotData[0]->cess_amount)) { echo $nature_of_supply_a_TotData[0]->cess_amount; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="cess_tax_value_supplya" value="<?php if(isset($nature_of_supply_a_TotData[0]->cess_amount)) { echo $nature_of_supply_a_TotData[0]->cess_amount; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                                      
                                </tr><tr><td></td><td>Total</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>				                    
                                </tbody>
                            </table>
                          </div>
						  <div class="greyheading">11.Details of credit availed in terms of section 142(11c)</div>
					     <div class="tableresponsive">
						 <table  class="table  tablecontent tablecontent2 bordernone" id="table11a">
                                <thead>
                                <tr>
                                <th>Sr.no.</th>
                                <th>Registration no. of vat</th>
								 <th>Servicetax registration no.</th>
								  <th>Invoice Documentno.</th>
								   <th>Invoice Documentdate</th> <th>Taxpaid</th>
							<th>Vat paid takes as SGST Credit or servicetax</th>
								</tr>						
																
                                </thead>
                                
                                <tbody>
								 
                                <tr>  <td></td>                   
                                
								 
								
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="11aregistration_no[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="11aservicetax_no[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="11ainvoice_document_no[]" class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="11ainvoice_document_date[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="11tax_paid[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="11avatpaid_sgst[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
                                 <td>
									 <a class="addMoreInvoice add-table11a"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
										</td>                      
                                </tr>			                    
                                </tbody>
                            </table>
                          </div>
						  <div class="greyheading">12.Details of goods sent on approval basis six month prior to the appointment day</div>
					     <div class="tableresponsive">
						 <table  class="table  tablecontent tablecontent2 bordernone" id="table12a">
                                <thead>
                                <tr>
                                <th>Sr.no.</th>
                                <th>documentno.</th>
								 <th>documentdate</th>
								  <th>GSTIN no. of receipient</th>
								   <th>Name & Address of receipient</th> 
								   <th colspan="5" style="text-align:center;">Details of goods sent on approval basis</th> 
								
								</tr><tr><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th>
								<th>HSN</th><th>Description</th><th>Unit</th><th>Qty</th><th>Value</th>
								</tr>						
																
                                </thead>
                                
                                <tbody>
								 
                                <tr>                     
                                <td></td>
								 <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="12a_document_no[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td><td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="12a_document_date[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="12a_gstinno_receipient[]" class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="12a_hsn[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="12a_description[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="12a_unit[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="12a_quantity[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="12a_value[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="cess_tax_value_supplya" value="<?php if(isset($nature_of_supply_a_TotData[0]->cess_amount)) { echo $nature_of_supply_a_TotData[0]->cess_amount; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                 <td>
									 <a class="addMoreInvoice add-table12a"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
										</td>                      
                                </tr>				                    
                                </tbody>
                            </table>
                          </div>
                        
							
                         <div class="tableresponsive">
                           
							<?php
							if($returndata[0]->totalinvoice > 0)
							{
								if($returndata[0]->final_submit == 0)
							{
								?>
                                <div class="adminformbxsubmit" style="width:100%;"> 
                            <div class="tc">
                               <input type="button" value="<?php echo ucfirst('Edit'); ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=transaction_gstr_submit&returnmonth=".$_REQUEST["returnmonth"]; ?>';" class="btn btn-success" class="redbtn marlef10"/>
							    <input type='submit' class="btn btn-danger" name='cleardata' value='clear data' id='cleardata'>
							  <input type='submit' class="btn btn-success" name='finalsubmit' value='final submit' id='finalsubmit'>
							  <input type='hidden' name="returnid" id="returnid" value="<?php echo $returndata[0]->return_id; ?>" />
									
                               
                            </div>
                              </div>
                            <?php } }
							else
							{
								?>
								  <div class="adminformbxsubmit" style="width:100%;"> 
                            <div class="tc">
                                <input type='submit' class="btn btn-success" name='submit' value='submit' id='submit'>
								
                               
                            </div>
                              </div>
							  <?php 
							} 
							?>		
                          								
                        </div>
                        
                        
                        </div> 
                   
       	  </div>
 		 <div class="clear height40"></div>     
    
    </div>
           <!--CONTENT START HERE-->
		   </form>
        <div class="clear"></div>  	
<script type="text/javascript">
    $(document).ready(function(){
		
        $(".add-table5a").click(function(){
       
			
		    
            var markup = "<tr><td></td><td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='5a_registration_no[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='5a_taxperiod_last_return[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='5a_dateoffilling_return[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='5a_balance_cenvat_credit[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='5a_cenvat_credit_admissible[]'/></td><td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td></tr>";
          // $("table tbody").append(markup);
		   $('#table5a').append(markup);
        });
		$('body').delegate('.del','click',function(){
			$(this).closest('tr').remove();
		});
        
		 
        
    }); 

</script>
<script type="text/javascript">
    $(document).ready(function(){
		
        $(".add-table5bcform").click(function(){
       
			
		    
            var markup = "<tr><td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='5bcform_tin_issuer[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='5bcform_nameof_issuer[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='5bcform_no_of_form[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='5bcform_amount[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='5bcform_applicable_vat_rate[]'/></td><td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td></tr>";
          // $("table tbody").append(markup);
		   $('#table5bcform').append(markup);
        });
		$('body').delegate('.del','click',function(){
			$(this).closest('tr').remove();
		});
        
		 
        
    }); 

</script>
<script type="text/javascript">
    $(document).ready(function(){
		
        $(".add-table5bfform").click(function(){
       
			
		    
            var markup = "<tr><td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='5bfform_tin_issuer[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='5bfform_nameof_issuer[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='5bfform_no_of_form[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='5bfform_amount[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='5bfform_applicable_vat_rate[]'/></td><td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td></tr>";
          // $("table tbody").append(markup);
		   $('#table5bfform').append(markup);
        });
		$('body').delegate('.del','click',function(){
			$(this).closest('tr').remove();
		});
        
		 
        
    }); 

</script>
<script type="text/javascript">
    $(document).ready(function(){
		
        $(".add-table5bhiform").click(function(){
       
			
		    
            var markup = "<tr><td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='5bhiform_tin_issuer[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='5bhiform_nameof_issuer[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='5bhiform_no_of_form[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='5bhiform_amount[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='5bhiform_applicable_vat_rate[]'/></td><td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td></tr>";
          // $("table tbody").append(markup);
		   $('#table5bhiform').append(markup);
        });
		$('body').delegate('.del','click',function(){
			$(this).closest('tr').remove();
		});
        
		 
        
    }); 

</script>
<script type="text/javascript">
    $(document).ready(function(){
		
        $(".add-table5c").click(function(){
       
			
		    
            var markup = "<tr><td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='5cform_registration_no[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='5cform_balanceof_itc_vat[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='5cform_cform_turnover_form_pending[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='5cform_cform_taxpayable[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='5cform_fform_turnover_form_pending[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='5cform_fform_taxpayable[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='5cform_itcreversal_relatable[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='5cform_hiform_turnover_form_pending[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='5cform_hiform_taxpayable[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='5cform_hiform_transitionitc2[]'/></td><td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td></tr>";
          // $("table tbody").append(markup);
		   $('#table5c').append(markup);
        });
		$('body').delegate('.del','click',function(){
			$(this).closest('tr').remove();
		});
        
		 
        
    }); 

</script>
<script type="text/javascript">
    $(document).ready(function(){
		
        $(".add-table6a").click(function(){
       
			
		    
            var markup = "<tr><td></td><td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='6ainvoice_document_no[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='6ainvoice_document_date[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='6asupplier_registration_no[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='6arecipients_registration_mo[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='6a_value[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='6a_ed_cvd[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='6a_sad[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='6a_totaleligible_cenvat[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='6a_totalcenvat_credit[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='6a_totalcenvat_credit_unavailed[]'/></td><td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td></tr>";
          // $("table tbody").append(markup);
		   $('#table6a').append(markup);
        });
		$('body').delegate('.del','click',function(){
			$(this).closest('tr').remove();
		});
        
		 
        
    }); 

</script>
<script type="text/javascript">
    $(document).ready(function(){
		
        $(".add-table6b").click(function(){
       
			
		    
            var markup = "<tr><td></td><td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='6binvoice_document_no[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='6binvoice_document_date[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='6bsupplier_registration_no[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='6breceipients_registration_no[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='6b_value[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='6b_taxpaid_vat[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='6b_totaleligible_vat[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='6b_totalvat_creditavailed[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='6b_totalvat_creditunavailed[]'/></td><td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td></tr>";
          // $("table tbody").append(markup);
		   $('#table6b').append(markup);
        });
		$('body').delegate('.del','click',function(){
			$(this).closest('tr').remove();
		});
        
		 
        
    }); 

</script>
<script type="text/javascript">
    $(document).ready(function(){
		
        $(".add-table7a1").click(function(){
       
			
		    
            var markup = "<tr><td></td><td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='7a1_hsncode[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='7a1_unit[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='7a1_qty[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='7a1_value[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='7a1_eligible_duties[]'/></td><td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td></tr>";
          // $("table tbody").append(markup);
		   $('#table7a1').append(markup);
        });
		$('body').delegate('.del','click',function(){
			$(this).closest('tr').remove();
		});
        
		 
        
    }); 
</script>
<script type="text/javascript">
    $(document).ready(function(){
		
        $(".add-table7a2").click(function(){
       
			
		    
            var markup = "<tr><td></td><td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='7a2_hsncode[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='7a2_unit[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='7a2_qty[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='7a2_value[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='7a2_eligible_duties[]'/></td><td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td></tr>";
          // $("table tbody").append(markup);
		   $('#table7a2').append(markup);
        });
		$('body').delegate('.del','click',function(){
			$(this).closest('tr').remove();
		});
        
		 
        
    }); 
</script>
<script type="text/javascript">
    $(document).ready(function(){
		
        $(".add-table7a3").click(function(){
       
			
		    
            var markup = "<tr><td></td><td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='7a3_hsncode[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='7a3_unit[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='7a3_qty[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='7a3_value[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='7a3_eligible_duties[]'/></td><td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td></tr>";
          // $("table tbody").append(markup);
		   $('#table7a3').append(markup);
        });
		$('body').delegate('.del','click',function(){
			$(this).closest('tr').remove();
		});
        
		 
        
    }); 
</script>
<script type="text/javascript">
    $(document).ready(function(){
		
        $(".add-table7b").click(function(){
       
			
		    
            var markup = "<tr><td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='7b_nameof_supplier[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='7b_invoice_number[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='7b_invoice_date[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='7b_description[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='7b_quantity[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='7b_uqc[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='7b_value[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='7b_eligible_duties[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='7b_vat[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='7b_dateonwhich_receipients[]'/></td><td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td></tr>";
          // $("table tbody").append(markup);
		   $('#table7b').append(markup);
        });
		$('body').delegate('.del','click',function(){
			$(this).closest('tr').remove();
		});
        
		 
        
    }); 
</script>
<script type="text/javascript">
    $(document).ready(function(){
		
        $(".add-table7c1").click(function(){
       
			
		    
            var markup = "<tr><td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='7c1_description[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='7c1_unit[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='7c1_qty[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='7c1_value[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='7c1_vat[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='7c1_totalinput_taxcredit[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='7c1_totalinput_taxcredit_exempt[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='7c1_totalinput_taxcredit_admissible[]'/></td><td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td></tr>";
          // $("table tbody").append(markup);
		   $('#table7c1').append(markup);
        });
		$('body').delegate('.del','click',function(){
			$(this).closest('tr').remove();
		});
        
		 
        
    }); 
</script>
<script type="text/javascript">
    $(document).ready(function(){
		
        $(".add-table7c2").click(function(){
       
			
		    
            var markup = "<tr><td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='7c2_description[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='7c2_unit[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='7c2_qty[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='7c2_value[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='7c2_vat[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='7c2_totalinput_taxcredit[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='7c2_totalinput_taxcredit_exempt[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='7c2_totalinput_taxcredit_admissible[]'/></td><td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td></tr>";
          // $("table tbody").append(markup);
		   $('#table7c2').append(markup);
        });
		$('body').delegate('.del','click',function(){
			$(this).closest('tr').remove();
		});
        
		 
        
    }); 
</script>
<script type="text/javascript">
    $(document).ready(function(){
		
        $(".add-table7d").click(function(){
       
			
		    
            var markup = "<tr><td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='7d_description[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='7d_unit[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='7d_qty[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='7d_value[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='7d_vatentry_taxpad[]'/></td><td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td></tr>";
          // $("table tbody").append(markup);
		   $('#table7d').append(markup);
        });
		$('body').delegate('.del','click',function(){
			$(this).closest('tr').remove();
		});
        
		 
        
    }); 
</script>
<script type="text/javascript">
    $(document).ready(function(){
		
        $(".add-table8").click(function(){
       
			
		    
            var markup = "<tr><td></td><td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='8registration_no[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='8taxperiod_lastreturn[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='8dateoffilling_return[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='8balanceeligible_cenvat_credit[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='8gstnof_receiver[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='8distributionno[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='8distributiondate[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='8itcofcentral[]'/></td><td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td></tr>";
          // $("table tbody").append(markup);
		   $('#table8').append(markup);
        });
		$('body').delegate('.del','click',function(){
			$(this).closest('tr').remove();
		});
        
		 
        
    }); 
</script>
<script type="text/javascript">
    $(document).ready(function(){
		
        $(".add-table9a").click(function(){
       
			
		    
            var markup = "<tr><td></td><td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='9a1challan_no[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='9a1challan_date[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='9a1typeof_goods[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='9a1_hsn[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='9a1_description[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='9a1_unit[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='9a1_quantity[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='9a1_value[]'/></td><td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td></tr>";
          // $("table tbody").append(markup);
		   $('#table9a').append(markup);
        });
		$('body').delegate('.del','click',function(){
			$(this).closest('tr').remove();
		});
        
		 
        
    }); 
</script>
<script type="text/javascript">
    $(document).ready(function(){
		
        $(".add-table9b").click(function(){
       
			
		    
            var markup = "<tr><td></td><td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='9b1challan_no[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='9b1challan_date[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='9b1typeof_goods[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='9b1_hsn[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='9b1_description[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='9b1_unit[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='9b1_quantity[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='9b1_value[]'/></td><td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td></tr>";
          // $("table tbody").append(markup);
		   $('#table9b').append(markup);
        });
		$('body').delegate('.del','click',function(){
			$(this).closest('tr').remove();
		});
        
		 
        
    }); 
</script>
<script type="text/javascript">
    $(document).ready(function(){
		
        $(".add-table11a").click(function(){
       
			
		    
            var markup = "<tr><td></td><td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='11aregistration_no[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='11aservicetax_no[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='11ainvoice_document_no[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='11ainvoice_document_date[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='11atax_paid[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='11avatpaid_sgst[]'/></td><td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td></tr>";
          // $("table tbody").append(markup);
		   $('#table11a').append(markup);
        });
		$('body').delegate('.del','click',function(){
			$(this).closest('tr').remove();
		});
        
		 
        
    }); 
</script>
<script type="text/javascript">
    $(document).ready(function(){
		
        $(".add-table12a").click(function(){
       
			
		    
            var markup = "<tr><td></td><td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='12a_document_no[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='12a_document_date[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='12a_gstinno_receipient[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='12a_name_receipient[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='12a_hsn[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='12a_description[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='12a_unit[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='12a_quantity[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='12a_value[]'/></td><td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td></tr>";
          // $("table tbody").append(markup);
		   $('#table12a').append(markup);
        });
		$('body').delegate('.del','click',function(){
			$(this).closest('tr').remove();
		});
        
		 
        
    }); 
</script>




<script>
    $(document).ready(function () {
        $('#returnmonth').on('change', function () {
            document.form2.action = '<?php echo PROJECT_URL; ?>/?page=transaction_gstr&returnmonth=<?php echo $returnmonth; ?>';
                        document.form2.submit();
                    });
                });
</script>

  <script type="text/javascript">
        $(document).ready(function() {
           // $('#place_of_supply_unregistered_person').multiselect();
        });
   </script>
    <script type="text/javascript">
        $(document).ready(function() {
          //  $('#place_of_supply_taxable_person').multiselect();
        });
   </script>
    <script type="text/javascript">
        $(document).ready(function() {
          //  $('#place_of_supply_uin_holder').multiselect();
        });
   </script>
  
<script>
    $(document).ready(function () {
        $('#returnmonth').on('change', function () {
            document.form2.action = '<?php echo PROJECT_URL; ?>/?page=transaction_gstr&returnmonth=<?php echo $returnmonth; ?>';
                        document.form2.submit();
                    });
                });
</script>

       
 <script>
	$(document).ready(function () {
		
		/* select2 js for state */
		//$("#place_of_supply_unregistered_person").select2();
	
	
	});
</script>
 
        <script type="text/javascript">
        function isNumberKey(evt)
      {
         
        var charCode = (evt.which) ? evt.which : event.keyCode
                
        if ((charCode >= 40) && (charCode <= 57) &&(charCode!=47) &&(charCode!=42) && (charCode!=43) && (charCode!=44) && (charCode!=45) || (charCode == 8))
       {
       return true;
           
       }
    else
    {
     return false;

     }
	  }
</script>