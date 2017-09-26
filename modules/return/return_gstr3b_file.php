<?php
$obj_gstr = new gstr();
$obj_return = new gstr3b();
//$obj_master = new master();
$returnmonth = date('Y-m');
//$obj_return->pr($_POST);
if(isset($_POST['returnmonth']))
{
    $returnmonth = $_POST['returnmonth'];
	$obj_return->redirect(PROJECT_URL."/?page=return_gstr3b_file&returnmonth=".$returnmonth);
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
    //$flag = $obj_return->checkVerifyUser();
    //if($flag=='verify')
    //{
        if($obj_return->saveGstr3b()){
            //$obj_master->redirect(PROJECT_URL."/?page=master_receiver");
        } 
   // }
}
if(isset($_POST['final_returnid']) && $_POST['final_returnid']!='') {
 
  //$flag = $obj_return->checkVerifyUser();
 // if($flag=='verify')
   // {
    	if($obj_return->finalSaveGstr3b()){
            //$obj_master->redirect(PROJECT_URL."/?page=master_receiver");
        }					  
   // } 
}
if(isset($_POST['cleardata']) && $_POST['cleardata']=='clear data') {
	
    if($obj_return->deleteSaveGstr3b()){
        //$obj_master->redirect(PROJECT_URL."/?page=master_receiver");
    }
}
if (isset($_GET['action']) && $_GET['action'] == 'downloadInvoice' && isset($_GET['id']) && $obj_return->validateId($_GET['id'])) {

    $htmlResponse = $obj_return->generategstr3bHtml($_GET['id'],$_GET['returnmonth']);
    if ($htmlResponse === false) {

        $obj_return->setError("No invoice found.");
        $obj_return->redirect(PROJECT_URL . "?page=return_gstr3b_file");
        exit();
    }

    $obj_mpdf = new mPDF();
    $obj_mpdf->SetHeader('GSTR-3B');
    $obj_mpdf->WriteHTML($htmlResponse);

  
}
if (isset($_GET['action']) && $_GET['action'] == 'emailInvoice' && isset($_GET['id']) && $obj_return->validateId($_GET['id'])) {

    $htmlResponse = $obj_return->generategstr3bHtml($_GET['id'],$_GET['returnmonth']);
    
    
   
}

if (isset($_GET['action']) && $_GET['action'] == 'downloadExcelInvoice' && isset($_GET['id']) && $obj_return->validateId($_GET['id'])) {
	//The Header Row


$filename =$obj_return->write_excel();

}
if (isset($_GET['action']) && $_GET['action'] == 'printInvoice' && isset($_GET['id']) && $obj_return->validateId($_GET['id'])) {


    $htmlResponse = $obj_return->generategstr3bHtml($_GET['id'],$_GET['returnmonth']);

    if ($htmlResponse === false) {

        $obj_return->setError("No invoice found.");
        $obj_return->redirect(PROJECT_URL . "?page=client_invoice_list");
        exit();
    }

    $obj_mpdf = new mPDF();
    $obj_mpdf->SetHeader('Tax Invoice');
    $obj_mpdf->WriteHTML($htmlResponse);

    
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
        $nature_of_supply_a_Totquery = "SELECT sum(item.taxable_subtotal) as taxable_subtotal, COUNT(i.invoice_id) as numcount,sum(taxable_subtotal) as taxable_subtotal,sum(item.cgst_amount) as cgst_amount,sum(item.sgst_amount) as sgst_amount,sum(igst_amount) as igst_amount,sum(cess_amount) as cess_amount FROM " . $db_obj->getTableName('client_invoice') . " as i inner join " . $db_obj->getTableName('client_invoice_item') . " as item on item.invoice_id = i.invoice_id INNER join gst_vendor_type as v on v.vendor_id=i.billing_vendor_type WHERE i.invoice_nature='salesinvoice' and i.added_by='" . $_SESSION["user_detail"]["user_id"] . "' and i.is_canceled='0' and invoice_type not in('exportinvoice') and (item.igst_rate > 0 or (item.sgst_rate > 0 and item.cgst_rate > 0)) and (v.vendor_id<>3 and v.vendor_id<>'5') and (item.is_applicable='0') and invoice_date like '%" . $returnmonth . "%'";
         
		
	    $nature_of_supply_a_TotData = $obj_return->get_results($nature_of_supply_a_Totquery);
        $total = 0; 
        if (!empty($nature_of_supply_a_TotData)) {
         // $total = $tdsTotData[0]->cgst_amount + $b2bItemData[0]->sgst_amount + $tdsTotData[0]->igst_amount + $tdsTotData[0]->cess_amount;
         }
	   $nature_of_supply_b_Totquery = "SELECT COUNT(i.invoice_id) as numcount,sum(item.taxable_subtotal) as taxable_subtotal,sum(item.cgst_amount) as cgst_amount,sum(item.sgst_amount) as sgst_amount,sum(igst_amount) as igst_amount,sum(cess_amount) as cess_amount FROM " . $db_obj->getTableName('client_invoice') . " as i inner join " . $db_obj->getTableName('client_invoice_item') . " as item on item.invoice_id = i.invoice_id WHERE i.invoice_nature='salesinvoice'  and i.added_by='" . $_SESSION["user_detail"]["user_id"] . "' and i.is_canceled='0' and invoice_type in('exportinvoice','sezunitinvoice','deemedexportinvoice') and export_supply_meant = 'withpayment' and invoice_date like '" . $returnmonth . "%'";

	     $nature_of_supply_b_TotData = $obj_return->get_results($nature_of_supply_b_Totquery);
        $total = 0;
        if (!empty($nature_of_supply_b_TotData)) {
         // $total = $tdsTotData[0]->cgst_amount + $b2bItemData[0]->sgst_amount + $tdsTotData[0]->igst_amount + $tdsTotData[0]->cess_amount;
         }
		//echo "<br>";
	    $nature_of_supply_c_Totquery = "SELECT COUNT(i.invoice_id) as numcount,sum(taxable_subtotal) as taxable_subtotal,sum(item.cgst_amount) as cgst_amount,sum(item.sgst_amount) as sgst_amount,sum(igst_amount) as igst_amount,sum(cess_amount) as cess_amount FROM " . $db_obj->getTableName('client_invoice') . " as i inner join " . $db_obj->getTableName('client_invoice_item') . " as item on item.invoice_id = i.invoice_id WHERE i.invoice_nature='salesinvoice'  and i.added_by='" . $_SESSION["user_detail"]["user_id"] . "' and i.is_canceled='0' and ((item.is_applicable='2') OR (item.igst_rate = 0 and item.sgst_rate = 0 and item.cgst_rate = 0)) and invoice_date like '" . $returnmonth . "%'";
   
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
		 
	    $flag = $db_obj->getVendorName('Composition vendor');
	   $vendor_id1=0;
		if($flag!=0)
		{
			$vendor_id1 = $flag;
		}
      $flag = $db_obj->getVendorName('uin holder');
	   $vendor_id2=0;
		if($flag!=0)
		{
			$vendor_id2 = $flag;
		}			
    $supply_unregistered="SELECT i.billing_state as state, COUNT(i.invoice_id) as numcount,sum(igst_amount) as igst_amount,sum(item.taxable_subtotal) as totaltaxable_value FROM gst_client_invoice as i inner join gst_client_invoice_item as item on item.invoice_id = i.invoice_id inner join gst_vendor_type as v on v.vendor_id = i.billing_vendor_type WHERE i.invoice_nature='salesinvoice' and (i.invoice_type <> 'deliverychallaninvoice' and i.invoice_type<>'creditnote' and i.invoice_type<>'refundvoucherinvoice') and i.added_by='" . $_SESSION["user_detail"]["user_id"] . "' and i.is_canceled='0' and i.is_deleted='0' and billing_gstin_number='' and (v.vendor_id<>'".$vendor_id1."' and v.vendor_id<>'".$vendor_id2."') and i.status='1' and i.invoice_date like '%" . $returnmonth . "%' GROUP by i.billing_state";
	 $supply_unregistered_data = $obj_return->get_results($supply_unregistered);
        $total = 0;
        if (!empty($supply_unregistered_data)) {
         // $total = $tdsTotData[0]->cgst_amount + $b2bItemData[0]->sgst_amount + $tdsTotData[0]->igst_amount + $tdsTotData[0]->cess_amount;
         }
	   $flag = $db_obj->getVendorName('Composition vendor');
	   $vendor_id=0;
		if($flag!=0)
		{
			$vendor_id = $flag;
		}
	
     $supply_composition="SELECT i.billing_state as state, COUNT(i.invoice_id) as numcount,sum(igst_amount) as igst_amount,sum(item.taxable_subtotal) as totaltaxable_value FROM gst_client_invoice as i inner join gst_client_invoice_item as item on item.invoice_id = i.invoice_id inner join gst_vendor_type as v on v.vendor_id = i.billing_vendor_type WHERE i.invoice_nature='salesinvoice' and (i.invoice_type <> 'deliverychallaninvoice' and i.invoice_type<>'creditnote' and i.invoice_type<>'refundvoucherinvoice') and i.added_by='" . $_SESSION["user_detail"]["user_id"] . "' and i.is_canceled='0' and i.is_deleted='0' and v.vendor_id='".$vendor_id."' and i.status='1' and i.invoice_date like '%" . $returnmonth . "%' GROUP by i.billing_state";
	 $supply_composition_data = $obj_return->get_results($supply_composition);
        $total = 0;
        if (!empty($supply_composition_data)) {
         // $total = $tdsTotData[0]->cgst_amount + $b2bItemData[0]->sgst_amount + $tdsTotData[0]->igst_amount + $tdsTotData[0]->cess_amount;
         }
	$flag = $db_obj->getVendorName('uin holder');
	   $vendor_id=0;
		if($flag!=0)
		{
			$vendor_id = $flag;
		}	 
	 $supply_uin_holder="SELECT i.billing_state as state, COUNT(i.invoice_id) as numcount,sum(igst_amount) as igst_amount,sum(item.taxable_subtotal) as totaltaxable_value FROM gst_client_invoice as i inner join gst_client_invoice_item as item on item.invoice_id = i.invoice_id inner join gst_vendor_type as v on v.vendor_id = i.billing_vendor_type WHERE i.invoice_nature='salesinvoice' and (i.invoice_type <> 'deliverychallaninvoice' and i.invoice_type<>'creditnote' and i.invoice_type<>'refundvoucherinvoice') and i.added_by='" . $_SESSION["user_detail"]["user_id"] . "' and i.is_canceled='0' and i.is_deleted='0' and v.vendor_id='".$vendor_id."' and i.status='1' and i.invoice_date like '%" . $returnmonth . "%' GROUP by i.billing_state";
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
                <a href="<?php echo PROJECT_URL . '/?page=return_gstr3b_file&returnmonth='.$returnmonth ?>" class="active">
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

                                
                                  <li><a href="<?php echo PROJECT_URL; ?>/?page=return_gstr3b_file&action=downloadExcelInvoice&id=<?php echo $returndata[0]->return_id; ?>&returnmonth=<?php echo $returnmonth; ?>"><div data-toggle="tooltip" data-placement="bottom" title="Excel"><i class="fa fa-file-excel-o" aria-hidden="true"></i></div></a></li>
                                <li><a href="<?php echo PROJECT_URL; ?>/?page=return_gstr3b_file&action=downloadInvoice&id=<?php echo $returndata[0]->return_id; ?>&returnmonth=<?php echo $returnmonth; ?>"><div data-toggle="tooltip" data-placement="bottom" title="PDF"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></div></a></li>
                                <li><a href="<?php echo PROJECT_URL; ?>/?page=return_gstr3b_file&action=printInvoice&id=<?php echo $returndata[0]->return_id; ?>&returnmonth=<?php echo $returnmonth; ?>" target="_blank"><div data-toggle="tooltip" data-placement="bottom" title="PRINT"><i class="fa fa-print" aria-hidden="true"></i></div></a></li>
                                <li><a href="<?php echo PROJECT_URL; ?>/?page=return_gstr3b_file&action=emailInvoice&id=<?php echo $returndata[0]->return_id; ?>&returnmonth=<?php echo $returnmonth; ?>"><div data-toggle="tooltip" data-placement="bottom" title="Email"><i class="fa fa-envelope-o" aria-hidden="true"></i></div></a></li>
                                <!--<li><a href="#"><div data-toggle="tooltip" data-placement="bottom" title="Attached File"><i class="fa fa-paperclip" aria-hidden="true"></i></div></a></li>-->
                         </ul>
							</div><?php } ?>
                       <form method="post" enctype="multipart/form-data" id='form' name="form4"> 
                    	<div class="greyheading">3.1 Details of Outward Supplies and inward supplies liable to reverse charge</div>
                           <div class="tableresponsive">
						  
                            <table  class="table  tablecontent tablecontent2 bordernone">
                                <thead>
                                <tr>
                                <th>Nature of Supplies</th>
                                <th>Total Taxable value</th>
                                <th>Integrated Tax</th>
                                <th>Central Tax</th>
                                <th>State/UT Tax</th>
                                <th>Cess</th>
                                </tr>
                                </thead>
                                
                                <tbody>
                                <tr>
                                <td class="lftheading" width="25%">(a) Outward taxable supplies (other than zero rated, nil rated and exempted)</td>
                                 <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->total_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="total_tax_value_supplya" value="<?php if(isset($nature_of_supply_a_TotData[0]->taxable_subtotal)) { echo $nature_of_supply_a_TotData[0]->taxable_subtotal; } else { echo ''; } ?>"
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
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="integrated_tax_value_supplya" value="<?php if(isset($nature_of_supply_a_TotData[0]->igst_amount)) { echo $nature_of_supply_a_TotData[0]->igst_amount; } else { echo ''; } ?>"
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
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="central_tax_value_supplya" value="<?php if(isset($nature_of_supply_a_TotData[0]->cgst_amount)) { echo $nature_of_supply_a_TotData[0]->cgst_amount; } else { echo ''; } ?>"
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
                                 </td>
                                                      
                                </tr>
                                 <tr>
                                <td class="lftheading" width="20%">(b) Outward taxable supplies (zero rated )</td>
								 <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->total_tax_value_supplyb; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="total_tax_value_supplyb" value="<?php if(isset($nature_of_supply_b_TotData[0]->taxable_subtotal)) { echo $nature_of_supply_b_TotData[0]->taxable_subtotal; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								 <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_tax_value_supplyb; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="integrated_tax_value_supplyb" value="<?php if(isset($nature_of_supply_b_TotData[0]->igst_amount)) { echo $nature_of_supply_b_TotData[0]->igst_amount; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								 <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_value_supplyb; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="central_tax_value_supplyb" value="<?php if(isset($nature_of_supply_b_TotData[0]->cgst_amount)) { echo $nature_of_supply_b_TotData[0]->cgst_amount; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_value_supplyb; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="state_tax_value_supplyb"  value="<?php if(isset($nature_of_supply_b_TotData[0]->sgst_amount)) { echo $nature_of_supply_b_TotData[0]->sgst_amount; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								 <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplyb; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="cess_tax_value_supplyb" value="<?php if(isset($nature_of_supply_b_TotData[0]->cess_amount)) { echo $nature_of_supply_b_TotData[0]->cess_amount; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>                             
                                                            
                                    
                            
                                </tr>
                                
                                <tr>
                                <td class="lftheading" width="20%">(c) Other outward supplies (Nil rated, exempted)</td>
								 <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->total_tax_value_supplyc; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="total_tax_value_supplyc" value="<?php if(isset($nature_of_supply_c_TotData[0]->taxable_subtotal)) { echo $nature_of_supply_c_TotData[0]->taxable_subtotal; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
								 <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_tax_value_supplyc; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="integrated_tax_value_supplyc" value="<?php if(isset($nature_of_supply_c_TotData[0]->igst_amount)) { echo $nature_of_supply_c_TotData[0]->igst_amount; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_value_supplyc; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="central_tax_value_supplyc" value="<?php if(isset($nature_of_supply_c_TotData[0]->cgst_amount)) { echo $nature_of_supply_c_TotData[0]->cgst_amount; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_value_supplyc; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="state_tax_value_supplyc" value="<?php if(isset($nature_of_supply_c_TotData[0]->sgst_amount)) { echo $nature_of_supply_c_TotData[0]->sgst_amount; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplyc; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="cess_tax_value_supplyc" value="<?php if(isset($nature_of_supply_c_TotData[0]->cess_amount)) { echo $nature_of_supply_c_TotData[0]->cess_amount; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>              
                               
                                
                                  
                                  
                                </tr>
                                
                                 <tr>
                                <td class="lftheading" width="20%">(d) Inward supplies (liable to reverse charge)</td>
								 <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->total_tax_value_supplyd; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="total_tax_value_supplyd" value="<?php if(isset($nature_of_supply_d_TotData[0]->taxable_subtotal)) { echo $nature_of_supply_d_TotData[0]->taxable_subtotal; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_tax_value_supplyd; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="integrated_tax_value_supplyd" value="<?php if(isset($nature_of_supply_d_TotData[0]->igst_amount)) { echo $nature_of_supply_d_TotData[0]->igst_amount; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
								 <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_value_supplyd; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="central_tax_value_supplyd" value="<?php if(isset($nature_of_supply_d_TotData[0]->cgst_amount)) { echo $nature_of_supply_d_TotData[0]->cgst_amount; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_value_supplyd; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="state_tax_value_supplyd" value="<?php if(isset($nature_of_supply_d_TotData[0]->sgst_amount)) { echo $nature_of_supply_d_TotData[0]->sgst_amount; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                 <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplyd; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="cess_tax_value_supplyd" value="<?php if(isset($nature_of_supply_d_TotData[0]->cess_amount)) { echo $nature_of_supply_d_TotData[0]->cess_amount; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 	          
                               
                              
                                   
                                    
                                     
								</tr>
                                 <tr>
                                <td class="lftheading" width="20%">(e) Non-GST outward supplies</td>
                               
								   <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->total_tax_value_supplye; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="total_tax_value_supplye" value="<?php if(isset($nature_of_supply_e_TotData[0]->taxable_subtotal)) { echo $nature_of_supply_e_TotData[0]->taxable_subtotal; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 	   
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_tax_value_supplye; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" disabled onKeyPress="return  isNumberKey(event,this);" name="integrated_tax_value_supplye" value="<?php if(isset($nature_of_supply_e_TotData[0]->igst_amount)) { echo ''; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
								  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_value_supplye; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" disabled onKeyPress="return  isNumberKey(event,this);" name="central_tax_value_supplye" value="<?php if(isset($nature_of_supply_e_TotData[0]->cgst_amount)) { echo ''; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
                                <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_value_supplye; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" disabled onKeyPress="return  isNumberKey(event,this);" name="state_tax_value_supplye" value="<?php if(isset($nature_of_supply_e_TotData[0]->sgst_amount)) { echo ''; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
                                <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplye; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" disabled onKeyPress="return  isNumberKey(event,this);" name="cess_tax_value_supplye" value="<?php if(isset($nature_of_supply_e_TotData[0]->cess_amount)) { echo ''; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 								 
								                                                                  
                                   
                                     
								</tr>
                                </tbody>
                            </table>
                        </div>
                        
                        	<div class="greyheading">3.2 Of the supplies shown in 3.1 (a) above, details of inter-State supplies made to unregistered persons,
composition taxable persons and UIN holders</div>
                            
                            <div class="tableresponsive" id="unregistered">
                            <table  class="table  tablecontent tablecontent2 bordernone" id="table1">
                                <thead>
                                
                                <tr>
                                <th></th>
                                <th>Place of Supply (State/UT)</th>
                                <th>Total Taxable value</th>
                                <th>Amount of Integrated Tax</th>
								
                                </tr>
                                </thead>
                                <tbody>

								
								<?php  
								
							// $sql = "select  *,count(returnid) as totalinvoice from gst_place_of_supply where added_by='" . $_SESSION['user_detail']['user_id'] . "' and financial_month like '%" . $returnmonth . "%' and type='0'   order by id desc limit 0,1";
							$sql="select *,final_submit,count(returnid) as totalinvoice,final_submit from ".TAB_PREFIX."client_return_gstr3b_pos as s INNER join ".TAB_PREFIX."client_return_gstr3b as client3b on client3b.financial_month=s.financial_month and s.added_by='".$_SESSION["user_detail"]["user_id"]."' and s.financial_month like '%".$returnmonth."%' and type='0'";
                             $editflag=0;
                            $return_a = $obj_return->get_results($sql);
							if($return_a[0]->totalinvoice > 0 )
							{
								 if (isset($return_a[0]->totalinvoice)) {
									 $editflag=1;
									    $str1  = substr($return_a[0]->place_of_supply,0,-1);
										$str1 = (explode(",",$str1));
										$str2  = substr($return_a[0]->totaltaxable_value,0,-1);
										$str2 = (explode(",",$str2));
										$str3  = substr($return_a[0]->amount_of_integrated_tax,0,-1);
										$str3 = (explode(",",$str3));
								
						
							  for($i=0;$i < sizeof($str1); $i++) {
                               
							  
             
                           ?>
                                    <tr>
                                    <td class="lftheading" width="25%">Supplies made to Unregistered Persons</td>
                                     <td>
									 <div class="clear"></div>
										 <div id='TextBoxesGroup3'>
	                               <div id="TextBoxDiv3">
								   <div class="input_fields_wrap3">
						<select  name="place_of_supply_unregistered_person[]"   id='place_of_supply_unregistered_person' class="required form-control">
							<?php $dataSupplyStateArrs = $obj_return->get_results("select * from ".$obj_return->getTableName('state')." where status='1' and is_deleted='0' order by state_name asc"); ?>
							<?php if(!empty($dataSupplyStateArrs)) { ?>
								<option value=''>Select Place Of Supply</option>
								<?php foreach($dataSupplyStateArrs as $dataSupplyStateArr) { ?>
								<option value='<?php echo $dataSupplyStateArr->state_id; ?>' <?php
                                    
										
										if($dataSupplyStateArr->state_id==$str1[$i])
										{
                                        echo "selected='selected'";
                                        }
									
                                    ?>><?php echo $dataSupplyStateArr->state_name . " (" . $dataSupplyStateArr->state_tin . ")"; ?></option>
								<?php } ?>
							<?php } ?>
						</select> </div></div></div>
									 </td>
									  <td>
								 <?php
								 if ((isset($return_a[0]->totalinvoice)) && ($return_a[0]->final_submit=='1')) {
							
									
									 ?>
									 <label><?php if(!empty($str2[$i])){echo $str2[$i]; }else{''; }; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								<div id='TextBoxesGroup'>
								<div id="TextBoxDiv1">
								
								<div class="input_fields_wrap1">
	
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="total_taxable_value_unregistered_person[]" id ="total_taxable_value_unregistered_person[]" value="<?php if(isset($return_a[0]->totalinvoice)) { echo $str2[$i]; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /></div></div>
</div>
								 <?php } ?>
                                 </td> 	
                                <td>
								 <?php
								 if ((isset($return_a[0]->totalinvoice)) && ($return_a[0]->final_submit=='1')) {
							
								
									 ?>
									 <label><?php if(!empty($str2[$i])){echo $str2[$i]; }else{''; }; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
									 <div id='TextBoxesGroup2'>
	<div id="TextBoxDiv2">
	<div class="input_fields_wrap2">
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="amount_of_integrated_tax_unregistered_person[]" id="amount_of_integrated_tax_unregistered_person" value="<?php if(isset($return_a[0]->totalinvoice)) { echo $str3[$i]; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /> </div></div></div>
								 <?php } ?>
                                 </td> 	
                                  		<?php								 
                                 if($returndata[0]->totalinvoice > 0)
							{
								if($returndata[0]->final_submit == 0)
								{	
									
								 
								}
							}
								 
								 else{
									
                                     if($i==0) 
										{
                                  
                                     ?>											
                                     <td>
									 <a class="addMoreInvoice add-row1"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
										</td><?php } else { ?>	
									<td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td>								 
								 <?php } } ?>
						</tr><?php } } } elseif(isset($supply_unregistered_data[0]->numcount))
						{  $i=0;
							foreach($supply_unregistered_data as $supply) {  
							$i++;
						?>
						
						              <tr>
                                    <td class="lftheading" width="25%">Supplies made to Unregistered Persons</td>
                                     <td>
									 <div class="clear"></div>
										 <div id='TextBoxesGroup3'>
	                               <div id="TextBoxDiv3">
								   <div class="input_fields_wrap3">
						<select  name="place_of_supply_unregistered_person[]"   id='place_of_supply_unregistered_person' class="required form-control">
							<?php $dataSupplyStateArrs = $obj_return->get_results("select * from ".$obj_return->getTableName('state')." where status='1' and is_deleted='0' order by state_name asc"); ?>
							<?php if(!empty($dataSupplyStateArrs)) { ?>
								<option value=''>Select Place Of Supply</option>
								<?php foreach($dataSupplyStateArrs as $dataSupplyStateArr) { ?>
								<option value='<?php echo $dataSupplyStateArr->state_id; ?>' <?php
                                    
										
										if($dataSupplyStateArr->state_id==$supply->state)
										{
                                        echo "selected='selected'";
                                        }
									
                                    ?>><?php echo $dataSupplyStateArr->state_name . " (" . $dataSupplyStateArr->state_tin . ")"; ?></option>
								<?php } ?>
							<?php } ?>
						</select> </div></div></div>
									 </td>
									  <td>
								 <?php
								 if ((isset($return_a[0]->totalinvoice)) && ($return_a[0]->final_submit=='1')) {
							
									
									 ?>
									 <label><?php if(!empty($supply->totaltaxable_value)){echo $supply->totaltaxable_value; }else{''; }; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								<div id='TextBoxesGroup'>
								<div id="TextBoxDiv1">
								
								<div class="input_fields_wrap1">
	
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="total_taxable_value_unregistered_person[]" id ="total_taxable_value_unregistered_person[]" value="<?php if(isset($supply->totaltaxable_value)) { echo $supply->totaltaxable_value; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /></div></div>
</div>
								 <?php } ?>
                                 </td> 	
                                <td>
								 <?php
								 if ((isset($return_a[0]->totalinvoice)) && ($return_a[0]->final_submit=='1')) {
							
								
									 ?>
									 <label><?php if(!empty($supply->igst_amount)){echo $supply->igst_amount; }else{''; }; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
									 <div id='TextBoxesGroup2'>
	<div id="TextBoxDiv2">
	<div class="input_fields_wrap2">
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="amount_of_integrated_tax_unregistered_person[]" id="amount_of_integrated_tax_unregistered_person" value="<?php if(isset($supply->igst_amount)) { echo $supply->igst_amount; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /> </div></div></div>
								 <?php } ?>
                                 </td> 	
                                  		<?php			
                            if($returndata[0]->totalinvoice > 0)
							{
								if($returndata[0]->final_submit == 0)
								{	
									
								 
								}
							}
								 else{
									
                                     if($i==1) 
										{
                                  
                                     ?>											
                                     <td>
									 <a class="addMoreInvoice add-row1"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
										</td><?php } else { ?>	
									<td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td>								 
								 <?php } } ?>
						</tr><?php }
							
						}
						else {
						
							?>
							 <tr>
                                    <td class="lftheading" width="25%">Supplies made to Unregistered Persons</td>
                                     <td width="35.5%">
									 <div class="clear"></div>
										 
						<select  name="place_of_supply_unregistered_person[]"   id='place_of_supply_unregistered_person' class="required form-control">
							<?php $dataSupplyStateArrs = $obj_return->get_results("select * from ".$obj_return->getTableName('state')." where status='1' and is_deleted='0' order by state_name asc"); ?>
							<?php if(!empty($dataSupplyStateArrs)) { ?>
								<option value=''>Select Place Of Supply</option>
								<?php foreach($dataSupplyStateArrs as $dataSupplyStateArr) { ?>
								<option value='<?php echo $dataSupplyStateArr->state_id; ?>' <?php
                                     if (isset($returndata[0]->place_of_supply_uin_holder)) {
										$str = (explode(",",$returndata[0]->place_of_supply_uin_holder));

                                        foreach($str as $s)
                                    {
										if($dataSupplyStateArr->state_id==$s)
										{
                                        echo "selected='selected'";
                                        }
									}
									}
                                    ?>><?php echo $dataSupplyStateArr->state_name . " (" . $dataSupplyStateArr->state_tin . ")"; ?></option>
								<?php } ?>
							<?php } ?>
						</select> 
									 </td>
									  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
							
									
									 ?>
									 <label><?php echo $returndata[0]->total_taxable_value_unregistered_person; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								<div id='TextBoxesGroup'>
								<div id="TextBoxDiv1">
								
								<div class="input_fields_wrap1">
	
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="total_taxable_value_unregistered_person[]" id ="total_taxable_value_unregistered_person[]" value="<?php if(isset($returndata[0]->total_taxable_value_unregistered_person)) { echo $returndata[0]->total_taxable_value_unregistered_person; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /></div></div>
</div>
								 <?php } ?>
                                 </td> 	
                                <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
								
									 ?>
									 <label><?php echo $returndata[0]->amount_of_integrated_tax_unregistered_person; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
									 <div id='TextBoxesGroup2'>
	<div id="TextBoxDiv2">
	<div class="input_fields_wrap2">
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="amount_of_integrated_tax_unregistered_person[]" id="amount_of_integrated_tax_unregistered_person" value="<?php if(isset($returndata[0]->amount_of_integrated_tax_unregistered_person)) { echo $returndata[0]->amount_of_integrated_tax_unregistered_person; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /> </div></div></div>
								 <?php } ?>
                                 </td> 										                       
                                     <td>
									  <a class="addMoreInvoice add-row1"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
    
                                    </td>									 
						</tr>
						<?php } 
						?>
                                   </tbody>
                            </table></div>
							   <div class="tableresponsive">
						  <table  class="table  tablecontent tablecontent2 bordernone" id="table2">
						  
							<?php
							// $sql = "select  *,count(returnid) as totalinvoice from gst_place_of_supply where added_by='" . $_SESSION['user_detail']['user_id'] . "' and financial_month like '%" . $returnmonth . "%' and type='1'   order by id desc limit 0,1";
							 $sql="select *,final_submit,count(returnid) as totalinvoice,final_submit from ".TAB_PREFIX."client_return_gstr3b_pos as s INNER join ".TAB_PREFIX."client_return_gstr3b as client3b on client3b.financial_month=s.financial_month and s.added_by='".$_SESSION["user_detail"]["user_id"]."' and s.financial_month like '%".$returnmonth."%' and type='1'";
                        
                             $editflag=0;
                            $return_a = $obj_return->get_results($sql);
							if($return_a[0]->totalinvoice > 0 )
							{
								 if (isset($return_a[0]->totalinvoice)) {
									 $editflag=1;
									    $str1  = substr($return_a[0]->place_of_supply,0,-1);
										$str1 = (explode(",",$str1));
										$str2  = substr($return_a[0]->totaltaxable_value,0,-1);
										$str2 = (explode(",",$str2));
										$str3  = substr($return_a[0]->amount_of_integrated_tax,0,-1);
										$str3 = (explode(",",$str3));
								
						
							  for($i=0;$i < sizeof($str1); $i++) {
                               
							  
             
                           ?>
                                     <tr>
                                    <td class="lftheading" width="25%">Supplies made to Composition Taxable Persons</td>
                                   <td width="35.5%">
									
						<select name='place_of_supply_taxable_person[]'  id='place_of_supply_taxable_person' class="required form-control">
							<?php $dataSupplyStateArrs = $obj_return->get_results("select * from ".$obj_return->getTableName('state')." where status='1' and is_deleted='0' order by state_name asc"); ?>
							<?php if(!empty($dataSupplyStateArrs)) { ?>
								<option value=''>Select Place Of Supply</option>
								<?php foreach($dataSupplyStateArrs as $dataSupplyStateArr) { ?>
								<option value='<?php echo $dataSupplyStateArr->state_id; ?>' <?php
                                    
										
										if($dataSupplyStateArr->state_id==$str1[$i])
										{
                                        echo "selected='selected'";
                                        }
									
                                    ?>><?php echo $dataSupplyStateArr->state_name . " (" . $dataSupplyStateArr->state_tin . ")"; ?></option>
								<?php } ?>
							<?php } ?>
						</select>
									 </td>
									  <td>
								 <?php
								  if ((isset($return_a[0]->totalinvoice)) && ($return_a[0]->final_submit=='1')){
							
									 ?>
									 <label><?php if(!empty($str2[$i])){echo $str2[$i]; }else{''; }; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="total_taxable_value_taxable_person[]"
 class="form-control" value="<?php if(isset($return_a[0]->totalinvoice)) { echo $str2[$i]; } else { echo ''; } ?>"  placeholder="" /> 
								 <?php } ?>
                                 </td> 	
                                  <td>
								 <?php
								 if ((isset($return_a[0]->totalinvoice)) && ($return_a[0]->final_submit=='1')){
							
									 ?>
									 <label><?php if(!empty($str3[$i])){echo $str3[$i]; }else{''; }; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="amount_of_integrated_tax_taxable_person[]"
 class="form-control" value="<?php if(isset($return_a[0]->totalinvoice)) { echo $str3[$i]; } else { echo ''; } ?>"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
                                 <?php								 
                                   if($returndata[0]->totalinvoice > 0)
							{
								if($returndata[0]->final_submit == 0)
								{	
									
								 
								}
							} else {
                                     if($i==0) 
										{
                                  
                                     ?>											
                                     <td>
									 <a class="addMoreInvoice add-row2"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
										</td><?php } else { ?>	
								<td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td>
								 <?php } } ?>
                                  
									  
                                  </tr>   <?php } } }elseif(isset($supply_composition_data[0]->numcount))
						{ $i=0; foreach($supply_composition_data as $supplycomposition) { $i++; 
								?>
							        <tr>
                                    <td class="lftheading" width="25%">Supplies made to Composition Taxable Persons</td>
                                    <td width="35.5%">
						<select name='place_of_supply_taxable_person[]'  id='place_of_supply_taxable_person' class="required form-control">
							<?php $dataSupplyStateArrs = $obj_return->get_results("select * from ".$obj_return->getTableName('state')." where status='1' and is_deleted='0' order by state_name asc"); ?>
							<?php if(!empty($dataSupplyStateArrs)) { ?>
								<option value=''>Select Place Of Supply</option>
								<?php foreach($dataSupplyStateArrs as $dataSupplyStateArr) { ?>
								<option value='<?php echo $dataSupplyStateArr->state_id; ?>' <?php
                                    
										
										if($dataSupplyStateArr->state_id==$supplycomposition->state)
										{
                                        echo "selected='selected'";
                                        }
									
                                    ?>><?php echo $dataSupplyStateArr->state_name . " (" . $dataSupplyStateArr->state_tin . ")"; ?></option>
								<?php } ?>
							<?php } ?>
						</select>
									 </td>
									  <td>
								 <?php
								  if ((isset($return_a[0]->totalinvoice)) && ($return_a[0]->final_submit=='1')){
							
									 ?>
									 <label><?php if(!empty($str2[$i])){echo $str2[$i]; }else{''; }; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="total_taxable_value_taxable_person[]"
 class="form-control" value="<?php if(isset($supplycomposition->totaltaxable_value)) { echo $supplycomposition->totaltaxable_value; } else { echo ''; } ?>"  placeholder="" /> 
								 <?php } ?>
                                 </td> 	
                                  <td>
								 <?php
								 if ((isset($return_a[0]->totalinvoice)) && ($return_a[0]->final_submit=='1')){
							
									 ?>
									 <label><?php if(!empty($supply[0]->igst_amount)){echo $supply[0]->igst_amount; }else{''; }; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="amount_of_integrated_tax_taxable_person[]"
 class="form-control" value="<?php if(isset($supplycomposition->igst_amount)) { echo $supplycomposition->igst_amount; } else { echo ''; } ?>"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
                                 <?php								 
                                 if($returndata[0]->totalinvoice > 0)
							{
								if($returndata[0]->final_submit == 0)
								{	
									
								 
								}
							}
								 else{
                                     if($i==1) 
										{
                                  
                                     ?>											
                                     <td>
									 <a class="addMoreInvoice add-row2"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
										</td><?php } else { ?>	
								<td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td>
								 <?php } }  ?>
                                  
									  
						</tr> <?php 
						}	} else {					
						?>
						
							  <tr>
                                       <td class="lftheading" width="25%">Supplies made to Composition Taxable Persons</td>
                               
                                     <td width="35.5%">
						<select name='place_of_supply_taxable_person[]'  id='place_of_supply_taxable_person' class="required form-control">
								<?php $dataSupplyStateArrs = $obj_return->get_results("select * from ".$obj_return->getTableName('state')." where status='1' and is_deleted='0' order by state_name asc"); ?>
							<?php if(!empty($dataSupplyStateArrs)) { ?>
								<option value=''>Select Place Of Supply</option>
								<?php foreach($dataSupplyStateArrs as $dataSupplyStateArr) { ?>
								<option value='<?php echo $dataSupplyStateArr->state_id; ?>' <?php
                                     if (isset($returndata[0]->place_of_supply_uin_holder)) {
										$str = (explode(",",$returndata[0]->place_of_supply_uin_holder));

                                        foreach($str as $s)
                                    {
										if($dataSupplyStateArr->state_id==$s)
										{
                                        echo "selected='selected'";
                                        }
									}
									}
                                    ?>><?php echo $dataSupplyStateArr->state_name . " (" . $dataSupplyStateArr->state_tin . ")"; ?></option>
								<?php } ?>
							<?php } ?>
						</select>
									 </td>
									  <td>
								 <?php
								   if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->total_taxable_value_taxable_person; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="total_taxable_value_taxable_person[]"
 class="form-control" value="<?php if(isset($returndata[0]->total_taxable_value_taxable_person)) { echo $returndata[0]->total_taxable_value_taxable_person; } else { echo ''; } ?>"  placeholder="" /> 
								 <?php } ?>
                                 </td> 	
                                  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->amount_of_integrated_tax_taxable_person; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="amount_of_integrated_tax_taxable_person[]"
 class="form-control" value=""  placeholder="" /> 
								 <?php } ?>
                                 </td> 									 
                                      
                                    <td>
									 <a class="addMoreInvoice add-row2"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a></td>
                                      </tr> <?php } ?></table></div>
									   <div class="tableresponsive">
									  <table  class="table  tablecontent tablecontent2 bordernone" id="table3">
									  <tbody>
                                 
								   <?php
							 //$sql = "select  *,count(returnid) as totalinvoice from gst_place_of_supply where added_by='" . $_SESSION['user_detail']['user_id'] . "' and financial_month like '%" . $returnmonth . "%' and type='2'   order by id desc limit 0,1";
							 $sql="select *,final_submit,count(returnid) as totalinvoice,final_submit from ".TAB_PREFIX."client_return_gstr3b_pos as s INNER join ".TAB_PREFIX."client_return_gstr3b as client3b on client3b.financial_month=s.financial_month and s.added_by='".$_SESSION["user_detail"]["user_id"]."' and s.financial_month like '%".$returnmonth."%' and type='2'";
                        
                             $editflag=0;
                            $return_a = $obj_return->get_results($sql);
							if($return_a[0]->totalinvoice > 0 )
							{
								 if (isset($return_a[0]->totalinvoice)) {
									 $editflag=1;
									    $str1  = substr($return_a[0]->place_of_supply,0,-1);
										$str1 = (explode(",",$str1));
										$str2  = substr($return_a[0]->totaltaxable_value,0,-1);
										$str2 = (explode(",",$str2));
										$str3  = substr($return_a[0]->amount_of_integrated_tax,0,-1);
										$str3 = (explode(",",$str3));
								
						
							  for($i=0;$i < sizeof($str1); $i++) {
                               
							  
             
                           ?>
								   <tr>
                                    <td class="lftheading" width="25%">Supplies made to UIN holders</td>
                                    <td width="35.5%">
								
						<select name='place_of_supply_uin_holder[]'  id='place_of_supply_uin_holder' class="required form-control">
							<?php $dataSupplyStateArrs = $obj_return->get_results("select * from ".$obj_return->getTableName('state')." where status='1' and is_deleted='0' order by state_name asc"); ?>
							<?php if(!empty($dataSupplyStateArrs)) { ?>
								<option value=''>Select Place Of Supply</option>
								<?php foreach($dataSupplyStateArrs as $dataSupplyStateArr) { ?>
								<option value='<?php echo $dataSupplyStateArr->state_id; ?>' <?php
                                    
										
										if($dataSupplyStateArr->state_id==$str1[$i])
										{
                                        echo "selected='selected'";
                                        }
									
                                    ?>><?php echo $dataSupplyStateArr->state_name . " (" . $dataSupplyStateArr->state_tin . ")"; ?></option>
								<?php } ?>
							<?php } ?>
						</select>
									 </td>
									  <td>
								 <?php
								 if ((isset($return_a[0]->totalinvoice)) && ($return_a[0]->final_submit=='1')) {
							
									 ?>
									
									 <label><?php echo $str2[$i]; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="total_taxable_value_uin_holder[]"
 class="form-control" value="<?php if(isset($return_a[0]->totalinvoice)) { echo $str2[$i]; } else { echo ''; } ?>"   placeholder="" /> 
								 <?php } ?>
                                 </td>
                                 <td>
								 <?php
								  if ((isset($return_a[0]->totalinvoice)) && ($return_a[0]->final_submit=='1')) {
							
									 ?>
									 <label><?php echo $str2[$i]; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="amount_of_integrated_uin_holder[]"
 class="form-control" value="<?php if(isset($return_a[0]->totalinvoice)) { echo $str3[$i]; } else { echo ''; } ?>" placeholder="" /> 
								 <?php } ?>
                                 </td> 
								 <?php 
								 if($returndata[0]->totalinvoice > 0)
							{
								if($returndata[0]->final_submit == 0)
								{	
									
								 
								}
							}
								 else{
                                     if($i==0) 
										{
                                     ?>											
                                     <td>
									 <a class="addMoreInvoice add-row3"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
										</td><?php } else { ?>	
									<td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td>								 
								 <?php } }?>
                                      
                                   </tr><?php } } } elseif(isset($supply_uin_holder_data[0]->numcount))
						{ $i=0; foreach($supply_uin_holder_data as $supplyuin) { $i++;?>
						<tr>
                                    <td class="lftheading" width="25%">Supplies made to UIN holders</td>
                                     <td width="35.5%">
									
						<select name='place_of_supply_uin_holder[]'  id='place_of_supply_uin_holder' class="required form-control">
							<?php $dataSupplyStateArrs = $obj_return->get_results("select * from ".$obj_return->getTableName('state')." where status='1' and is_deleted='0' order by state_name asc"); ?>
							<?php if(!empty($dataSupplyStateArrs)) { ?>
								<option value=''>Select Place Of Supply</option>
								<?php foreach($dataSupplyStateArrs as $dataSupplyStateArr) { ?>
								<option value='<?php echo $dataSupplyStateArr->state_id; ?>' <?php
                                    
										
										if($dataSupplyStateArr->state_id==$supplyuin->state)
										{
                                        echo "selected='selected'";
                                        }
									
                                    ?>><?php echo $dataSupplyStateArr->state_name . " (" . $dataSupplyStateArr->state_tin . ")"; ?></option>
								<?php } ?>
							<?php } ?>
						</select>
									 </td>
									  <td>
								 <?php
								 if ((isset($return_a[0]->totalinvoice)) && ($return_a[0]->final_submit=='1')) {
							
									 ?>
									
									 <label><?php echo $str2[$i]; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="total_taxable_value_uin_holder[]"
 class="form-control" value="<?php if(isset($supplyuin->totaltaxable_value)) { echo $supplyuin->totaltaxable_value; } else { echo ''; } ?>"   placeholder="" /> 
								 <?php } ?>
                                 </td>
                                 <td>
								 <?php
								  if ((isset($return_a[0]->totalinvoice)) && ($return_a[0]->final_submit=='1')) {
							
									 ?>
									 <label><?php echo $str2[$i]; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="amount_of_integrated_uin_holder[]"
 class="form-control" value="<?php if(isset($supplyuin->igst_amount)) { echo $supplyuin->igst_amount; } else { echo ''; } ?>" placeholder="" /> 
								 <?php } ?>
                                 </td> 
								 <?php 
								 if($returndata[0]->totalinvoice > 0)
							{
								if($returndata[0]->final_submit == 0)
								{	
									
								 
								}
							}
								 else{
									 
                                     if($i==1) 
										{
                                     ?>											
                                     <td>
									 <a class="addMoreInvoice add-row3"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
										</td><?php } else { ?>	
									<td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td>								 
										<?php } } ?>
						</tr><?php }
							
						}
						else {
						?>
							<tr>
                                    <td class="lftheading" width="25%">Supplies made to UIN holders</td>
                                    <td width="35.5%">
						<select name='place_of_supply_uin_holder[]'  id='place_of_supply_uin_holder' class="required form-control">
							<?php $dataSupplyStateArrs = $obj_return->get_results("select * from ".$obj_return->getTableName('state')." where status='1' and is_deleted='0' order by state_name asc"); ?>
							<?php if(!empty($dataSupplyStateArrs)) { ?>
								<option value=''>Select Place Of Supply</option>
								<?php foreach($dataSupplyStateArrs as $dataSupplyStateArr) { ?>
								<option value='<?php echo $dataSupplyStateArr->state_id; ?>' <?php
                                     if (isset($returndata[0]->place_of_supply_uin_holder)) {
										$str = (explode(",",$returndata[0]->place_of_supply_uin_holder));

                                        foreach($str as $s)
                                    {
										if($dataSupplyStateArr->state_id==$s)
										{
                                        echo "selected='selected'";
                                        }
									}
									}
                                    ?>><?php echo $dataSupplyStateArr->state_name . " (" . $dataSupplyStateArr->state_tin . ")"; ?></option>
								<?php } ?>
							<?php } ?>
						</select>
									 </td>
									  <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->total_taxable_value_uin_holder; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="total_taxable_value_uin_holder[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                 <td>
								 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->amount_of_integrated_uin_holder; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="amount_of_integrated_uin_holder[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 										 
                                      <td>
									 <a class="addMoreInvoice add-row3"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a></td>
                                      
                                   </tr><?php } ?>
                               
                            </table>
                        </div>
                        
                        <div class="greyheading">4. Eligible ITC</div>
                        
                        <div class="tableresponsive">
                            <table  class="table  tablecontent tablecontent2 bordernone">
                                <thead>
                                
                                <tr>
                                <th>Details</th>
                                <th>Integrated Tax</th>
                                <th>Central Tax</th>
                                <th>State/UT Tax</th>
                                 <th>Cess</th>
   
                                </tr>
                                </thead>
                                
                                <tbody>
                                    <tr>
                                    <td class="lftheading" width="25%"><strong>(A) ITC Available (whether in full or part)</strong></td>
                              
                                                    
                                    
                                    </tr>
                                    
                                    <tr>
                                    <td class="lftheading">(1) Import of goods</td>
									  <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_tax_import_of_goods; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="integrated_tax_import_of_goods" value="<?php if(isset($import_of_goods_TotData[0]->igst_amount)) { echo $import_of_goods_TotData[0]->igst_amount; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
								  <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_import_of_goods; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" disabled name="central_tax_import_of_goods" value="<?php if(isset($import_of_goods_TotData[0]->cgst_amount)) { echo $import_of_goods_TotData[0]->cgst_amount; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_import_of_goods; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" disabled name="state_tax_import_of_goods" value="<?php if(isset($import_of_goods_TotData[0]->sgst_amount)) { echo $import_of_goods_TotData[0]->sgst_amount; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 	
                                <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_import_of_goods; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="cess_tax_import_of_goods" value="<?php if(isset($import_of_goods_TotData[0]->cess_amount)) { echo $import_of_goods_TotData[0]->cess_amount; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>							 
						 				 
                                  
                                    
                                      
                                      
                                    </tr>
                                    
                                     <tr>
                                    <td class="lftheading">(2) Import of services</td>
									 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_tax_import_of_services; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="integrated_tax_import_of_services" value="<?php if(isset($import_of_services_TotData[0]->igst_amount)) { echo $import_of_services_TotData[0]->igst_amount; } else { echo ''; }?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>	
								  <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_import_of_services; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" disabled name="central_tax_import_of_services" value="<?php if(isset($import_of_services_TotData[0]->cgst_amount)) { echo $import_of_services_TotData[0]->cgst_amount; } else { echo ''; }?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_import_of_services; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" disabled name="state_tax_import_of_services" value="<?php if(isset($import_of_services_TotData[0]->sgst_amount)) { echo $import_of_services_TotData[0]->sgst_amount; } else { echo ''; }?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_import_of_services; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="cess_tax_import_of_services" value="<?php if(isset($import_of_services_TotData[0]->cess_amount)) { echo $import_of_services_TotData[0]->cess_amount; } else { echo ''; }?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>                                 
                                                                      
                                      
                                    </tr>
                                    
                                    <tr>
                                    <td class="lftheading">(3) Inward supplies liable to reverse charge (other than 1 & 2 above)</td>
									 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_tax_inward_supplies_reverse_charge; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="integrated_tax_inward_supplies_reverse_charge"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
								 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_inward_supplies_reverse_charge; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="central_tax_inward_supplies_reverse_charge"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
                               <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_inward_supplies_reverse_charge; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="state_tax_inward_supplies_reverse_charge"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
                                <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_inward_supplies_reverse_charge; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="cess_tax_inward_supplies_reverse_charge"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>	 
                                   
                                       
                                      
                                    </tr>
                                    <tr>
                                    <td class="lftheading">(4) Inward supplies from ISD</td>
									 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_tax_inward_supplies; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="integrated_tax_inward_supplies"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>	
								 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_inward_supplies; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="central_tax_inward_supplies"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_inward_supplies; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="state_tax_inward_supplies"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                               <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_inward_supplies; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="cess_tax_inward_supplies"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>						 
                                                                 
                                    
                                       
                                    </tr>
                                    
                                     <tr>
                                    <td class="lftheading" >(5) All other ITC</td>
									<td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_tax_allother_itc; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="integrated_tax_allother_itc"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>	
								 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_allother_itc; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="central_tax_allother_itc"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>	
								  <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_allother_itc; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="state_tax_allother_itc"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_allother_itc; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="cess_tax_allother_itc"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                                
                                     
                                    </tr>
                                    <tr>
                                    <td class="lftheading"><strong>(B) ITC Reversed</strong></td>
									
							
                                      
                                    </tr>
                                    
                                     <tr>
                                    <td class="lftheading" >(1) As per rules 42 & 43 of CGST Rules</td>
									<td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_tax_itc_reversed_cgstrules; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="integrated_tax_itc_reversed_cgstrules"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
								 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_itc_reversed_cgstrules; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="central_tax_itc_reversed_cgstrules"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
								  <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_itc_reversed_cgstrules; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="state_tax_itc_reversed_cgstrules"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
								  <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_itc_reversed_cgstrules; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="cess_tax_itc_reversed_cgstrules"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>                        
                                  
                                      
                                       
                                    </tr>
                                    
                                     <tr>
                                    <td class="lftheading" >(2) Others</td>
									
									  <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_tax_itc_reversed_other; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="integrated_tax_itc_reversed_other"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>  
                                <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_itc_reversed_other; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="central_tax_itc_reversed_other"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
                                <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_itc_reversed_other; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="state_tax_itc_reversed_other"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_itc_reversed_other; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="cess_tax_itc_reversed_other"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>    								 
                                    
                                     
                                      
                                      
                                    </tr>
                                    <tr>
                                    <td class="lftheading"><strong>(C) Net ITC Available (A) – (B)</strong></td>
									<td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_tax_net_itc_a_b; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="integrated_tax_net_itc_a_b"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_net_itc_a_b; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="central_tax_net_itc_a_b"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_net_itc_a_b; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="state_tax_net_itc_a_b"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_net_itc_a_b; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="cess_tax_net_itc_a_b"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>                       
                                                                        
                                      
                                    </tr>
                                    
                                    <tr>
                                    <td class="lftheading"><strong>(D) Ineligible ITC</strong></td>
											          
                                      
                                      
                                      
                                    </tr>
                                     <tr>
                                    <td class="lftheading">(1) As per section 17(5)</td>
									 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_tax_inligible_itc_17_5; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="integrated_tax_inligible_itc_17_5"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_inligible_itc_17_5; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="central_tax_inligible_itc_17_5"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_inligible_itc_17_5; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="state_tax_inligible_itc_17_5"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_inligible_itc_17_5; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="cess_tax_inligible_itc_17_5"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>                      
                            </tr>
                                     <tr>
                                    <td class="lftheading">(2) Others</td>
									 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_tax_inligible_itc_others; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="integrated_tax_inligible_itc_others"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>  
								 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_inligible_itc_others; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="central_tax_inligible_itc_others"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                               <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_inligible_itc_others; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="state_tax_inligible_itc_others"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                               <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_inligible_itc_others; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="cess_tax_inligible_itc_others"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 								 
                                    
                                     
                                       
                                     
                                    </tr>
                                    
                                    
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="greyheading">5. Values of exempt, nil-rated and non-GST inward supplies</div>
                         <div class="tableresponsive">
                            <table  class="table  tablecontent tablecontent2 bordernone">
                                <thead>
                                
                                <tr>
                                <th>Nature of supplies</th>
                                <th>Inter-State supplies</th>
                                <th>Intra-State supplies</th>                               
                                   </tr>
                                </thead>
                                
                                <tbody>
                                    <tr>
                                    <td class="lftheading" width="25%">From a supplier under composition scheme, Exempt and Nil rated supply</td>
									 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->inter_state_supplies_composition_scheme; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="inter_state_supplies_composition_scheme"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 	
                              <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->intra_state_supplies_composition_scheme; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="intra_state_supplies_composition_scheme" 
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 	                                   
                                    
                                    </tr>
                                    
                                     <tr>
                                    <td class="lftheading" width="25%">Non GST supply</td>
									<td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->inter_state_supplies_nongst_supply; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="inter_state_supplies_nongst_supply"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 	
                               <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->intra_state_supplies_nongst_supply; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="intra_state_supplies_nongst_supply"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 									 
                                    
                                  
                                    </tr>
                                    
                                </tbody>
                            </table>
                        </div>
						  <div class="greyheading">5.1 Interest and late fee payable</div>
                         <div class="tableresponsive">
                            <table  class="table  tablecontent tablecontent2 bordernone">
                                <thead>
                                
                                <tr>
								 <th>Interest and late fee</th>
                                <th>IntegratedTax</th>
                                <th>CentralTax</th>
                                <th>State/UT</th>  
                                 <th>Cess</th>  								
                                   </tr>
                                </thead>
                                
                                <tbody>
                                    <tr>
                                    <td class="lftheading" width="25%">Interest amount</td>
									 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->interest_latefees_integrated_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="interest_latefees_integrated_tax"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 	
                              <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->interest_latefees_central_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="interest_latefees_central_tax" 
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 	 
                               <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->interest_latefees_state_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="interest_latefees_state_tax" 
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 	 
<td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->interest_latefees_cess_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="interest_latefees_cess_tax" 
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 	 								 
                                    
                                    </tr>
                                    
                             
                                </tbody>
                            </table>
                        </div>
                        
                     <div class="greyheading">6.1 Payment of tax</div>
                         <div class="tableresponsive">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table  tablecontent tablecontent2 bordernone">
                                  <tr>
                                    <th>Description</th>
                                    <th>Tax payable</th>
                                    <th colspan="4" align="center">Paid through ITC</th>
                                    <th>Tax paid <br/>TDS./TCS</th>
                                    <th>Tax/Cess <br/>paid in<br/>cash</th>
                                    <th>Interest</th>
                                    <th>Late Fee</th>
                                  </tr>
                                  
                                  <tr>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th>Integrated Fee<br> Tax</th>
                                    <th>Central<br>Tax</th>
                                    <th>State/UT<br>Tax</th>
                                    <th>Cess</th>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                  </tr>
                                  <tr>
                                    <td class="lftheading" width="25%">Integrated Tax</td>
									 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->tax_payable_integrated_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="tax_payable_integrated_tax"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
								 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_fee_integrated_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="integrated_fee_integrated_tax"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
								 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_integrated_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="central_integrated_tax"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                             <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_integrated_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" name="state_integrated_tax"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_integrated_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="cess_integrated_tax"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                               <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->taxpaid_tdstcs_integrated_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="taxpaid_tdstcs_integrated_tax"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                               <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->taxpaid_cess_integrated_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="taxpaid_cess_integrated_tax"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>	
                              <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->interest_integrated_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="interest_integrated_tax"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                            <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->latefee_integrated_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="latefee_integrated_tax"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>								 
                               
                                    
                                  </tr>
                                  
                                   <tr>
                                    <td class="lftheading">Central Tax</td>
									 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->tax_payable_central_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="tax_payable_central_tax"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>	
								  <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_fee_central_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="integrated_fee_central_tax"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_central_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="central_central_tax"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_central_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
									 	 <label><span class="starred"></span></label>
							
								 <?php } ?>
                                 </td>
                                <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_central_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="cess_central_tax"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                             <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->taxpaid_tdstcs_central_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="taxpaid_tdstcs_central_tax"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>	
                             <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->taxpaid_cess_central_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="taxpaid_cess_central_tax"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                             <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->interest_central_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="interest_central_tax"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>	
                             <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->latefee_central_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="latefee_central_tax"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>									 
                                   
                                  </tr>
                                  
                                   <tr>
                                    <td class="lftheading">State/UT Tax</td>
									  <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->tax_payable_stateut_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="tax_payable_stateut_tax"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_stateut_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="integrated_stateut_tax"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_stateut_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <label><span class="starred"></span></label>
								 <?php } ?>
                                 </td>
								  <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_stateut_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="state_stateut_tax"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_stateut_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="cess_stateut_tax"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->taxpaid_tcs_stateut_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="taxpaid_tcs_stateut_tax"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->taxpaid_cess_stateut_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="taxpaid_cess_stateut_tax"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->interest_stateut_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="interest_stateut_tax"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->latefee_stateut_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="latefee_stateut_tax"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                  </tr>
								   <tr>
                                    <td class="lftheading">Cess</td>
									  <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->tax_payable_cess_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="tax_payable_cess_tax"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_cess_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
							 <label><span class="starred"></span></label>
								 <?php } ?>
                                 </td>
								  <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_cess_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <label><span class="starred"></span></label>
								 <?php } ?>
                                 </td>
								  <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_cess_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
							 <label><span class="starred"></span></label>
								 <?php } ?>
                                 </td>
								 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_stateut_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="cess_stateut_tax"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->taxpaid_tcs_cess_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="taxpaid_tcs_cess_tax"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->taxpaid_cess_cess_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="taxpaid_cess_cess_tax"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->interest_cess_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="interest_cess_tax"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->latefee_stateut_tax; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="latefee_cess_tax"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                  </tr>
                                  
                                </table>
                            </div>
                        
                        <div class="greyheading">6.2 TDS/TCS Credit</div>
                         <div class="tableresponsive">
                            <table  class="table  tablecontent tablecontent2 bordernone">
                                <thead>
                                
                                <tr>
                                <th>Details</th>
                                <th>Integrated Tax</th>
                                <th>Central Tax</th> 
                                 <th>State/UT Tax</th>                                  
                                   </tr>
                                </thead>
                                
                                <tbody>
                                    <tr>
                                    <td class="lftheading" width="25%">TDS</td>
									 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_tax_tds; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="integrated_tax_tds" value="<?php echo $tdsTotData[0]->igst_amount; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_tds; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="central_tax_tds" value="<?php echo $tdsTotData[0]->cgst_amount; ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_tds; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="state_tax_tds" value="<?php echo $tdsTotData[0]->sgst_amount; ?>" 
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                    </tr>
                                    
                                     <tr>
                                    <td class="lftheading" width="25%">TCS</td>
									 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->integrated_tax_tcs; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="integrated_tax_tcs" value="<?php echo $tcsTotData[0]->igst_amount; ?>" 
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_tcs; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="central_tax_tcs" value="<?php echo $tcsTotData[0]->cgst_amount; ?>" 
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								 <td> 
							 <?php
								 if($returndata[0]->totalinvoice > 0)
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_tcs; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="state_tax_tcs" value="<?php echo $tcsTotData[0]->sgst_amount; ?>" 
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                    </tr>
                                    
                                   
                                    
                                </tbody>
                            </table></div>
							
                         <div class="tableresponsive">
                           
							<?php
							if($returndata[0]->totalinvoice > 0)
							{
								if($returndata[0]->final_submit == 0)
							{
								?>
                                <div class="adminformbxsubmit" style="width:100%;"> 
                            <div class="tc">
                               <input type="button" value="<?php echo ucfirst('Edit'); ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=return_gstr3b_file_submit&returnmonth=".$_REQUEST["returnmonth"]; ?>';" class="btn btn-success" class="redbtn marlef10"/>
							    <input type='submit' class="btn btn-danger" name='cleardata' value='clear data' id='cleardata'>
							  <input type='submit' class="btn btn-success" name='finalsubmit' value='Upload to GSTN' id='gstr1_summary_download'>
							  <input type='hidden' name="returnid" id="returnid" value="<?php echo $returndata[0]->return_id; ?>" />
							<input type='hidden' name="final_returnid" id="final_returnid" value="<?php echo $returndata[0]->return_id; ?>" />
									
                               
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
<?php 
$obj_gstr->DownloadSummaryOtpPopupJs();
?>		
	
<script type="text/javascript">
    $(document).ready(function(){
		
        $(".add-row1").click(function(){
       
			var data1 ='<select class="required form-control" id="place_of_supply_unregistered_person"  name="place_of_supply_unregistered_person[]">';
			 var data='';
			 data +=<?php $dataSupplyStateArrs = $obj_return->get_results("select * from ".$obj_return->getTableName('state')." where status='1' and is_deleted='0' order by state_name asc"); ?>
						<?php if(!empty($dataSupplyStateArrs)) { ?>
							data += '<option value="">Select Place of Supply</option>';
							<?php foreach($dataSupplyStateArrs as $dataSupplyStateArr) { ?>
								data += '<option value="<?php echo $dataSupplyStateArr->state_id; ?>"><?php echo $dataSupplyStateArr->state_name; ?></option>';
							<?php } ?>
						<?php } ?>
							
			data = data1+ data+'</select>';
		
		    
            var markup = "<tr><td><input type='hidden' name='record'></td><td>" + data + "</td><td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='total_taxable_value_unregistered_person[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='amount_of_integrated_tax_unregistered_person[]'/></td><td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td></tr>";
          // $("table tbody").append(markup);
		   $('#table1').append(markup);
        });
		$('body').delegate('.del','click',function(){
			$(this).closest('tr').remove();
		});
        
		 
        
    }); 

</script>
<script>

</script>
<script type="text/javascript">
    $(document).ready(function(){
		
        $(".add-row2").click(function(){
       
			var data1 ='<select class="required form-control" id="place_of_supply_taxable_person"  name="place_of_supply_taxable_person[]">';
			 var data='';
			 data +=<?php $dataSupplyStateArrs = $obj_return->get_results("select * from ".$obj_return->getTableName('state')." where status='1' and is_deleted='0' order by state_name asc"); ?>
						<?php if(!empty($dataSupplyStateArrs)) { ?>
							data += '<option value="">Select Place of Supply</option>';
							<?php foreach($dataSupplyStateArrs as $dataSupplyStateArr) { ?>
								data += '<option value="<?php echo $dataSupplyStateArr->state_id; ?>"><?php echo $dataSupplyStateArr->state_name; ?></option>';
							<?php } ?>
						<?php } ?>
							
			data = data1+ data+'</select>';
		
		    
            var markup = "<tr><td><input type='hidden' name='record'></td><td>" + data + "</td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='total_taxable_value_taxable_person[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='amount_of_integrated_tax_taxable_person[]'/></td><td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td></tr>";
          // $("table tbody").append(markup);
		   $('#table2').append(markup);
        });
        
		 
      $('body').delegate('.del','click',function(){
			$(this).closest('tr').remove();
		});
    });    
</script>
<script type="text/javascript">
    $(document).ready(function(){
		
        $(".add-row3").click(function(){
       
			var data1 ='<select class="required form-control" id="place_of_supply_uin_holder"  name="place_of_supply_uin_holder[]">';
			 var data='';
			 data +=<?php $dataSupplyStateArrs = $obj_return->get_results("select * from ".$obj_return->getTableName('state')." where status='1' and is_deleted='0' order by state_name asc"); ?>
						<?php if(!empty($dataSupplyStateArrs)) { ?>
							data += '<option value="">Select Place of Supply</option>';
							<?php foreach($dataSupplyStateArrs as $dataSupplyStateArr) { ?>
								data += '<option value="<?php echo $dataSupplyStateArr->state_id; ?>"><?php echo $dataSupplyStateArr->state_name; ?></option>';
							<?php } ?>
						<?php } ?>
							
			data = data1+ data+'</select>';
		
		    
            var markup = "<tr><td><input type='hidden' name='record'></td><td>" + data + "</td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='total_taxable_value_uin_holder[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='amount_of_integrated_uin_holder[]'/></td><td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td></tr>";
          // $("table tbody").append(markup);
		   $('#table3').append(markup);
        });
         $('body').delegate('.del','click',function(){
			$(this).closest('tr').remove();
		});
		 
        
    });    
</script>
<script>
    $(document).ready(function () {
        $('#returnmonth').on('change', function () {
            document.form2.action = '<?php echo PROJECT_URL; ?>/?page=return_gstr3b_file&returnmonth=<?php echo $returnmonth; ?>';
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
            document.form2.action = '<?php echo PROJECT_URL; ?>/?page=return_gstr3b_file&returnmonth=<?php echo $returnmonth; ?>';
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