<?php

if(isset($_SESSION['publisher']['user_id']) && ($_SESSION['publisher']['user_id']!=''))
{
	//echo $_SESSION['publisher']['user_id'];
}

						 
						 
$obj_transition = new transition();
 
//$obj_login->sendMobileMessage
$returnmonth = date('Y-m');

if(isset($_POST['returnmonth']))
{
    $returnmonth = $_POST['returnmonth'];
	$obj_transition->redirect(PROJECT_URL."/?page=transition_gstr&returnmonth=".$returnmonth);
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
 $flag = $obj_transition->checkVerifyUser();
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
 
  $flag = $obj_transition->checkVerifyUser();
  if($flag=='notverify')
{
						  
} else{
 				  
    if($obj_transition->finalSaveGstrTransition()){
        //$obj_master->redirect(PROJECT_URL."/?page=master_receiver");
    }
}
}
if (isset($_GET['action']) && $_GET['action'] == 'printInvoice' && isset($_GET['id'])) {

    
    $htmlResponse = $obj_transition->generategst_transitionHtml($_GET['id'],$_GET['returnmonth']);

    if ($htmlResponse === false) {

        $obj_transition->setError("No Transition form found.");
        $obj_transition->redirect(PROJECT_URL . "?page=client_invoice_list");
        exit();
    }

    $obj_mpdf = new mPDF();
    $obj_mpdf->SetHeader('Tax Invoice');
    $obj_mpdf->WriteHTML($htmlResponse);

    
}
if (isset($_GET['action']) && $_GET['action'] == 'emailInvoice' && isset($_GET['id'])) {

    $htmlResponse = $obj_transition->generategst_transitionHtml($_GET['id'],$_GET['returnmonth']);
    
    
   
}
if (isset($_GET['action']) && $_GET['action'] == 'downloadInvoice' && isset($_GET['id'])) {

    $htmlResponse = $obj_transition->generategst_transitionHtml($_GET['id'],$_GET['returnmonth']);
    if ($htmlResponse === false) {

        $obj_transition->setError("No Transition form found.");
        $obj_transition->redirect(PROJECT_URL . "?page=trasition_gstr");
        exit();
    }

    $obj_mpdf = new mPDF();
    $obj_mpdf->SetHeader('GST-Transition');
    $obj_mpdf->WriteHTML($htmlResponse);

  
}







       
	  // $sql = "select  *,count(return_id) as totalinvoice from ".TAB_PREFIX."client_return_gstr3b where added_by='" . $_SESSION['user_detail']['user_id'] . "' and financial_month like '%" . $returnmonth . "%' and is_deleted='0'  order by return_id desc limit 0,1";
       $sql = "select  *,count(id) as totalinvoice from gst_transition_form where added_by='" . $_SESSION['user_detail']['user_id'] . "' and financial_month like '%" . $returnmonth . "%' and is_deleted='0'  order by id desc limit 0,1";
       $returndata = $obj_transition->get_results($sql);
	   $sql = "select  *,count(id) as totalinvoice from gst_transition_form where added_by='" . $_SESSION['user_detail']['user_id'] . "' and financial_month like '%" . $returnmonth . "%' and is_deleted='0'  order by id desc limit 0,1";
 
       $returndata1 = $obj_transition->get_results($sql);
	    $sql = "select * from " . TAB_PREFIX . "client_kyc where added_by='" . $_SESSION['user_detail']['user_id'] . "' order by id desc limit 0,1";
	   $clientdata = $obj_transition->get_results($sql);
	   $client_gstin_number;
	   $client_name;
	   
	   if(count($clientdata) > 0 )
	   {
		   $client_gstin_number = $clientdata[0]->gstin_number;
		   $client_name = $clientdata[0]->name;
		   
	   }
		if($returndata1[0]->totalinvoice > 0)
		{
		$arr = $returndata1[0]->gstr_transition_data;
		$arr1= base64_decode($arr);
		 $transition_arr = json_decode($arr1);
		
		$a5_taxperiod_last_return='';
		$a5_registration_no='';
		$a5_taxperiod_last_return='';
		$a5_dateoffilling_return='';
		$a5_balance_cenvat_credit='';
		$a5_cenvat_credit_admissible='';
		$b5bcform_tin_issuer='';
		$b5bcform_nameof_issuer='';
		$b5bcform_no_of_item='';
		$b5bcform_amount='';
		$b5bcform_applicable_vat_rate='';
		$b5bfform_tin_issuer='';
		$b5bfform_nameof_issuer='';
		$b5bfform_no_of_form='';
		$b5bfform_amount='';
		$b5bfform_applicable_vat_rate='';
		$b5bhiform_tin_issuer='';
		$b5bhiform_nameof_issuer='';
		$b5bhiform_no_of_form='';
		$b5bhiform_amount='';
		$b5bhiform_applicable_vat_rate='';
		$c5cform_registration_no='';
		$c5cform_balanceof_itc_val='';
		$c5cform_cform_turnover_form_pending='';
		$c5cform_cform_taxpayable='';
		$c5cform_fform_turnover_form_pending='';
		$c5cform_fform_taxpayable='';
		$c5cform_itcreversal_relatable='';
		$c5cform_hiform_turnover_form_pending='';
		$c5cform_hiform_taxpayable='';
		$c5cform_hiform_transitionitc2='';
		$a6ainvoice_document_no='';
		$a6ainvoice_document_date='';
		$a6asupplier_registration_no='';
		$a6arecipients_registration_no='';
		$a6a_value='';
		$a6a_ed_cvd='';
		$a6a_sad='';
		$a6a_totaleligible_cenvat='';
		$a6a_totalcenvat_credit='';
		$a6a_totalcenvat_credit_unavailed='';
		$b6binvoice_document_no='';
		$b6binvoice_document_date='';
		$b6bsupplier_registration_no='';
		$b6breceipients_registration_no='';
		$b6b_value='';
		$b6b_taxpaid_vat='';
		$b6b_totaleligible_vat='';
		$b6b_totalvat_creditavailed='';
		$b6b_totalvat_creditunavailed='';
		$a7a1_hsncode='';
		$a7a1_unit='';
		$a7a1_qty='';
		$a7a1_value='';
		$a7a1_eligible_duties='';
		$a7a2_hsncode='';
		$a7a2_unit='';
		$a7a2_qty='';
		$a7a2_value='';
		$a7a2_eligible_duties='';
		$a7a3_hsncode='';
		$a7a3_unit='';
		$a7a3_qty='';
		$a7a3_value='';
		$a7a3_eligible_duties='';
		$b7b_nameof_supplier='';
		$b7b_invoice_number='';
		$b7b_invoice_date='';
		$b7b_description='';
		$b7b_quantity='';
		$b7b_uqc='';
		$b7b_value='';
		$b7b_eligible_duties='';
		$b7b_vat='';
		$b7b_dateonwhich_receipients='';
		$c7c1_description='';
		$c7c1_unit='';
		$c7c1_qty='';
		$c7c1_value='';
		$c7c1_vat='';
		$c7c1_totalinput_taxcredit='';
		$c7c1_totalinput_taxcredit_exempt='';
		$c7c1_totalinput_taxcredit_admissible='';
		$d7d_description='';
		$d7d_unit='';
		$d7d_qty='';
		$d7d_value='';
		$d7d_vatentry_taxpad='';
		$b7b_nameof_supplier='';
		$b7b_invoice_number='';
		$b7b_invoice_date='';
		$b7b_description='';
		$b7b_quantity='';
		$b7b_uqc='';
		$b7b_value='';
		$b7b_eligible_duties='';
		$b7b_vat='';
		$b7b_dateonwhich_receipients='';
		$c7c2_description='';
		$c7c2_unit='';
		$c7c2_qty='';
		$c7c2_value='';
		$c7c2_vat='';
		$c7c2_totalinput_taxcredit='';
		$c7c2_totalinput_taxcredit_exempt='';
		$c7c2_totalinput_taxcredit_admissible='';
		$a8registration_no='';
		$a8taxperiod_lastreturn='';
		$a8dateoffilling_return='';
		$a8balanceeligible_cenvat_credit='';
		$a8gstnof_receiver='';
		$a8distributionno='';
		$a8distributiondate='';
		$a8itcofcentral='';
		$a9a1challan_no='';
		$a9a1challan_date='';
		$a9a1typeof_goods='';
		$a9a1_hsn='';
		$a9a1_description='';
		$a9a1_unit='';
		$a9a1_quantity='';
		$a9a1_value='';
		$b9b1challan_no='';
		$b9b1challan_date='';
		$b9b1typeof_goods='';
		$b9b1_hsn='';
		$b9b1_description='';
		$b9b1_unit='';
		$b9b1_quantity='';
		$b9b1_value='';
		$a11aregistration_no='';
		$a11aservicetax_no='';
		$a11ainvoice_documentno='';
		$a11ainvoice_document_date='';
		$a11atax_paid='';
		$a11avatpaid_sgst='';
		$a12a_document_no='';
		$a12a_document_date='';
		$a12a_gstinno_receipient='';
		$a12a_name_receipient='';
		$a12a_hsn='';
		$a12a_description='';
		$a12a_unit='';
		$a12a_quantity='';
		$a12a_value='';
		$a10a_gstn='';
		$a10a_description='';
		$a10a_unit='';
		$a10a_quantity='';
		$a10a_value='';
		$a10a_inputtax='';
		$b10b_gstn='';
		$b10b_description='';
		$b10b_unit='';
		$b10b_quantity='';
		$b10b_value='';
		$b10b_inputtax='';
		

		
		foreach($transition_arr as $item)
		{
			//echo $item->a5_taxperiod_last_return;
			//var_dump($item);
			$a5_taxperiod_last_return=$item->a5_taxperiod_last_return;
			$a5_registration_no=$item->a5_registration_no;
			$a5_taxperiod_last_return=$item->a5_taxperiod_last_return;
			$a5_dateoffilling_return=$item->a5_dateoffilling_return;
			$a5_balance_cenvat_credit=$item->a5_balance_cenvat_credit;
			$a5_cenvat_credit_admissible=$item->a5_cenvat_credit_admissible;
			$b5bcform_tin_issuer=$item->b5bcform_tin_issuer;
			$b5bcform_nameof_issuer=$item->b5bcform_nameof_issuer;
			$b5bcform_no_of_item=$item->b5bcform_no_of_item;
			$b5bcform_amount=$item->b5bcform_amount;
			$b5bcform_applicable_vat_rate=$item->b5bcform_applicable_vat_rate;
		    $b5bfform_tin_issuer=$item->b5bfform_tin_issuer;
			$b5bfform_nameof_issuer=$item->b5bfform_nameof_issuer;
			$b5bfform_no_of_form=$item->b5bfform_no_of_form;
			$b5bfform_amount=$item->b5bfform_amount;
			$b5bfform_applicable_vat_rate=$item->b5bfform_applicable_vat_rate;
			$b5bhiform_tin_issuer=$item->b5bhiform_tin_issuer;
			$b5bhiform_nameof_issuer=$item->b5bhiform_nameof_issuer;
			$b5bhiform_no_of_form=$item->b5bhiform_no_of_form;
			$b5bhiform_amount=$item->b5bhiform_amount;
			$b5bhiform_applicable_vat_rate=$item->b5bhiform_applicable_vat_rate;
			$c5cform_registration_no=$item->c5cform_registration_no;
			$c5cform_balanceof_itc_val=$item->c5cform_balanceof_itc_val;
			$c5cform_cform_turnover_form_pending=$item->c5cform_cform_turnover_form_pending;
			$c5cform_cform_taxpayable=$item->c5cform_cform_taxpayable;
			$c5cform_fform_turnover_form_pending=$item->c5cform_fform_turnover_form_pending;
			$c5cform_fform_taxpayable=$item->c5cform_fform_taxpayable;
			$c5cform_itcreversal_relatable=$item->c5cform_itcreversal_relatable;
			$c5cform_hiform_turnover_form_pending=$item->c5cform_hiform_turnover_form_pending;
			$c5cform_hiform_taxpayable=$item->c5cform_hiform_taxpayable;
			$c5cform_hiform_transitionitc2=$item->c5cform_cform_taxpayable;
			$a6ainvoice_document_no=$item->a6ainvoice_document_no;
			$a6ainvoice_document_date=$item->a6ainvoice_document_date;
		    $a6asupplier_registration_no=$item->a6asupplier_registration_no;
			$a6arecipients_registration_no=$item->a6arecipients_registration_no;
				$a6a_value=$item->a6a_value;
				$a6a_ed_cvd=$item->a6a_ed_cvd;
				$a6a_sad=$item->a6a_sad;
				$a6a_totaleligible_cenvat=$item->a6a_totaleligible_cenvat;
				$a6a_totalcenvat_credit=$item->a6a_totalcenvat_credit;
				$a6a_totalcenvat_credit_unavailed=$item->a6a_totalcenvat_credit_unavailed;
				$b6binvoice_document_no=$item->b6binvoice_document_no;
				$b6binvoice_document_date=$item->b6binvoice_document_date;
				$b6bsupplier_registration_no=$item->b6bsupplier_registration_no;
				$b6breceipients_registration_no=$item->b6breceipients_registration_no;
				$b6b_value=$item->b6b_value;
				$b6b_taxpaid_vat=$item->b6b_taxpaid_vat;
				$b6b_totaleligible_vat=$item->b6b_totaleligible_vat;
				$b6b_totalvat_creditavailed=$item->b6b_totalvat_creditavailed;
				$b6b_totalvat_creditunavailed=$item->b6b_totalvat_creditunavailed;			
			$a7a1_hsncode=$item->a7a1_hsncode;
			$a7a1_unit=$item->a7a1_unit;
			$a7a1_qty=$item->a7a1_qty;
			$a7a1_value=$item->a7a1_value;
			$a7a1_eligible_duties=$item->a7a1_eligible_duties;
			$b7b_nameof_supplier=$item->b7b_nameof_supplier;
			$b7b_invoice_number=$item->b7b_invoice_number;
			$b7b_invoice_date=$item->b7b_invoice_date;
			$b7b_description=$item->b7b_description;
			$b7b_quantity=$item->b7b_quantity;
			$b7b_uqc=$item->b7b_uqc;
			$b7b_value=$item->b7b_value;
			$b7b_eligible_duties=$item->b7b_eligible_duties;
			$b7b_vat=$item->b7b_vat;
			$b7b_dateonwhich_receipients=$item->b7b_dateonwhich_receipients;
			$c7c1_description=$item->c7c1_description;
			$c7c1_unit=$item->c7c1_unit;
			$c7c1_qty=$item->c7c1_qty;
			$c7c1_value=$item->c7c1_value;
			$c7c1_vat=$item->c7c1_vat;
			$c7c1_totalinput_taxcredit=$item->c7c1_totalinput_taxcredit;
			$c7c1_totalinput_taxcredit_exempt=$item->c7c1_totalinput_taxcredit_exempt;
			$c7c1_totalinput_taxcredit_admissible=$item->c7c1_totalinput_taxcredit_admissible;				
			$d7d_description=$item->d7d_description;
			$d7d_unit=$item->d7d_unit;
			$d7d_qty=$item->d7d_qty;
			$d7d_value=$item->d7d_value;
			$d7d_vatentry_taxpad=$item->d7d_vatentry_taxpad;
			$a7a2_hsncode=$item->a7a2_hsncode;
			$a7a2_unit=$item->a7a2_unit;
			$a7a2_qty=$item->a7a2_qty;
			$a7a2_value=$item->a7a2_value;
			$a7a2_eligible_duties=$item->a7a2_eligible_duties;
			$a7a3_hsncode=$item->a7a3_hsncode;
			$a7a3_unit=$item->a7a3_unit;
			$a7a3_qty=$item->a7a3_qty;
			$a7a3_value=$item->a7a3_value;
			$a7a3_eligible_duties=$item->a7a3_eligible_duties;
			$b7b_nameof_supplier=$item->b7b_nameof_supplier;
			$b7b_invoice_number=$item->b7b_invoice_number;
			$b7b_invoice_date=$item->b7b_invoice_date;
			$b7b_description=$item->b7b_description;
			$b7b_quantity=$item->b7b_quantity;
			$b7b_uqc=$item->b7b_uqc;
			$b7b_value=$item->b7b_value;
			$b7b_eligible_duties=$item->b7b_eligible_duties;
			$b7b_vat=$item->b7b_vat;
			$b7b_dateonwhich_receipients=$item->b7b_dateonwhich_receipients;
			$c7c2_description=$item->c7c2_description;
			$c7c2_unit=$item->c7c2_unit;
			$c7c2_qty=$item->c7c2_qty;
			$c7c2_value=$item->c7c2_value;
			$c7c2_vat=$item->c7c2_vat;
			$c7c2_totalinput_taxcredit=$item->c7c2_totalinput_taxcredit;
			$c7c2_totalinput_taxcredit_exempt=$item->c7c2_totalinput_taxcredit_exempt;
			$c7c2_totalinput_taxcredit_admissible=$item->c7c2_totalinput_taxcredit_admissible;
			$a8registration_no=$item->a8registration_no;
			$a8taxperiod_lastreturn=$item->a8taxperiod_lastreturn;
			$a8dateoffilling_return=$item->a8dateoffilling_return;
			$a8balanceeligible_cenvat_credit=$item->a8balanceeligible_cenvat_credit;
			$a8gstnof_receiver=$item->a8gstnof_receiver;
			$a8distributionno=$item->a8distributionno;
			$a8distributiondate=$item->a8distributiondate;
			$a8itcofcentral=$item->a8itcofcentral;
			$a9a1challan_no=$item->a9a1challan_no;
			$a9a1challan_date=$item->a9a1challan_date;
			$a9a1typeof_goods=$item->a9a1typeof_goods;
			$a9a1_hsn=$item->a9a1_hsn;
			$a9a1_description=$item->a9a1_description;
			$a9a1_unit=$item->a9a1_unit;
			$a9a1_quantity=$item->a9a1_quantity;
			$a9a1_value=$item->a9a1_value;
			$b9b1challan_no=$item->b9b1challan_no;
			$b9b1challan_date=$item->b9b1challan_date;
			$b9b1typeof_goods=$item->b9b1typeof_goods;
			$b9b1_hsn=$item->b9b1_hsn;
			$b9b1_description=$item->b9b1_description;
			$b9b1_unit=$item->b9b1_unit;
			$b9b1_quantity=$item->b9b1_quantity;
			$b9b1_value=$item->b9b1_value;
			$a11aregistration_no=$item->a11aregistration_no;
			$a11aservicetax_no=$item->a11aservicetax_no;
			$a11ainvoice_documentno=$item->a11ainvoice_documentno;
			$a11ainvoice_document_date=$item->a11ainvoice_document_date;
			$a11atax_paid=$item->a11atax_paid;
			$a11avatpaid_sgst=$item->a11avatpaid_sgst;
			$a12a_document_no=$item->a12a_document_no;
			$a12a_document_date=$item->a12a_document_date;
			$a12a_gstinno_receipient=$item->a12a_gstinno_receipient;
			$a12a_name_receipient=$item->a12a_name_receipient;
			$a12a_hsn=$item->a12a_hsn;
			$a12a_description=$item->a12a_description;
			$a12a_unit=$item->a12a_unit;
			$a12a_quantity=$item->a12a_quantity;
			$a12a_value=$item->a12a_value;
			$a10a_gstn=$item->a10a_gstn;
			$a10a_description=$item->a10a_description;
			$a10a_unit=$item->a10a_unit;
			$a10a_quantity=$item->a10a_quantity;
			$a10a_value=$item->a10a_value;
			$a10a_inputtax=$item->a10a_inputtax;
			$b10b_gstn=$item->b10b_gstn;
			$b10b_description=$item->b10b_description;
			$b10b_unit=$item->b10b_unit;
			$b10b_quantity=$item->b10b_quantity;
			$b10b_value=$item->b10b_value;
			$b10b_inputtax=$item->b10b_inputtax;
		}
		}
		 
	
		 
		
	  
	   ?>
       <div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
       			<div class="col-md-12 col-sm-12 col-xs-12">
               
                	<div class="col-md-6 col-sm-6 col-xs-12 heading"><h1>GST-Transition Form</h1></div>
                    <div class="col-md-6 col-sm-6 col-xs-12 text-right breadcrumb-nav"><a href="#">Home</a>
					<i class="fa fa-angle-right" aria-hidden="true"></i>  <a href="#">File Return</a> <i class="fa fa-angle-right" aria-hidden="true"></i> <span class="active">GST-Transition Form</span> </div>
                     <div class="whitebg formboxcontainer">
				<?php $obj_transition->showErrorMessage(); ?>
			    <?php if(isset($_POST['finalsubmit']) && $_POST['finalsubmit']=='final submit') {
				echo "<div id='sucmsg' style='background-color:#DBEDDF;border-radius:4px;padding:8px 35px 8px 14px;text-shadow:0 1px 0 rgba(255, 255, 255, 0.5);margin-bottom:18px;border-color:#D1E8DA;color:#39A25F;'><i class='fa fa-check'></i> <b>GST_Transition form month of ".$returnmonth." successfully submitted </div>";
		    
				}else{
				$obj_transition->showSuccessMessge(); }?>
				<?php $obj_transition->unsetMessage(); ?>
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
		      // echo $obj_transition->showSuccessMessge(); 
				} }
				else{
				 if($returndata[0]->final_submit == 1){
		    echo "<div id='sucmsg' style='background-color:#DBEDDF;border-radius:4px;padding:8px 35px 8px 14px;text-shadow:0 1px 0 rgba(255, 255, 255, 0.5);margin-bottom:18px;border-color:#D1E8DA;color:#39A25F;'><i class='fa fa-check'></i> <b>GST-Transition month of  ".$returnmonth." already submitted </div>";
					
				} }?>
				<div class="tab">
                <a href="<?php echo PROJECT_URL . '/?page=transition_gstr&returnmonth='.$returnmonth ?>" class="active">
                    Transition Form1
                </a>
                <a href="<?php echo PROJECT_URL . '/?page=transition_gstr2&returnmonth='.$returnmonth ?>" >
                    Transition Form2
                </a>
              
            </div>
					  <div class="pull-right rgtdatetxt">
                                <form method='post' name='form2'>
                                    Month Of Return
                                    <?php
                                    $dataQuery = "SELECT DATE_FORMAT(invoice_date,'%Y-%m') AS niceDate FROM " . $db_obj->getTableName('client_invoice') . " group by nicedate";
                                    $dataRes = $obj_transition->get_results($dataQuery);
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

                                
                               
                                <li><a href="<?php echo PROJECT_URL; ?>/?page=transition_gstr&action=downloadInvoice&id=<?php echo $returndata[0]->financial_month; ?>&returnmonth=<?php echo $returnmonth; ?>"><div data-toggle="tooltip" data-placement="bottom" title="PDF"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></div></a></li>
                                <li><a href="<?php echo PROJECT_URL; ?>/?page=transition_gstr&action=printInvoice&id=<?php echo $returndata[0]->financial_month; ?>&returnmonth=<?php echo $returnmonth; ?>" target="_blank"><div data-toggle="tooltip" data-placement="bottom" title="PRINT"><i class="fa fa-print" aria-hidden="true"></i></div></a></li>
                                <li><a href="<?php echo PROJECT_URL; ?>/?page=transition_gstr&action=emailInvoice&id=<?php echo $returndata[0]->financial_month; ?>&returnmonth=<?php echo $returnmonth; ?>"><div data-toggle="tooltip" data-placement="bottom" title="Email"><i class="fa fa-envelope-o" aria-hidden="true"></i></div></a></li>
                                <!--<li><a href="#"><div data-toggle="tooltip" data-placement="bottom" title="Attached File"><i class="fa fa-paperclip" aria-hidden="true"></i></div></a></li>-->
                         </ul>
							</div><?php } ?>
                       <form method="post" enctype="multipart/form-data" id='form'> 
					  <div class="row">
                     	 <div class="col-md-12 col-sm-12 col-xs-12 form-group">

                            <label>1.GSTIN-<span class="starred"></span></label>
							 <label><strong><?php echo $client_gstin_number; ?></strong></label>
						     </div>
							 
							    <div class="col-md-12 col-sm-12 col-xs-12 form-group">

                            <label>2.LegalName of the registered person-<span class="starred"></span></label>
							 <label><strong><?php echo $client_name; ?></strong></label>
						    </div>
							    <div class="col-md-4 col-sm-4 col-xs-12 form-group">

                            <label>3.TradeName of any-<span class="starred"></span></label>
							 <input type="text" maxlength="100" id="trader_name"  name="trader_name" value="<?php if(isset($returndata1[0]->trader_name)) { echo $returndata1[0]->trader_name; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /> 
						    
							   </div><div class="clear"></div>
							    
                            <label>4.Wheather all the return required under existing law for the period of six month immediately preceding appoint date have been furnished<span class="starred"></span></label>
							 <div class="col-md-2 col-sm-2 col-xs-12 form-group">

							 <select name='transition_status' id='transition_status' class='required form-control'>
                        
						   <option value='1' <?php
                                    if (isset($returndata1[0]->transition_status) && $returndata1[0]->transition_status == 1) {
                                        echo "selected='selected'";
                                    }
                                    ?>>Active</option>
									 <option value='0' <?php
                                    if (isset($returndata1[0]->transition_status) && $returndata1[0]->transition_status==0) {
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
								<?php
								if(!empty($returndata1[0]->totalinvoice) && ($returndata1[0]->totalinvoice > 0))
								{
								$a5_taxperiod_last_return=(explode(",",$a5_taxperiod_last_return));
								$a5_registration_no=(explode(",",$a5_registration_no));
								$a5_dateoffilling_return=(explode(",",$a5_dateoffilling_return));
								$a5_balance_cenvat_credit=(explode(",",$a5_balance_cenvat_credit));
								$a5_cenvat_credit_admissible=(explode(",",$a5_cenvat_credit_admissible));
			                    $start='';
								if(sizeof($a5_taxperiod_last_return) > 1)
								{
									$start = $a5_taxperiod_last_return;
									
								}
								elseif(sizeof($a5_registration_no) > 1)
								{
									 $start = $a5_registration_no;
								}
								elseif(sizeof($a5_dateoffilling_return) > 1)
								{
									 $start = $a5_dateoffilling_return;
									
								}
								elseif(sizeof($a5_balance_cenvat_credit) > 1)
								{
									$start = $a5_balance_cenvat_credit;
								}
								elseif(sizeof($a5_cenvat_credit_admissible) > 1)
								{
									$start = $a5_cenvat_credit_admissible;
								}
								else{
									$start = $a5_taxperiod_last_return;
								}
								
									
						       $taxreturn=0;
							   $dateoffilling_return=0;
							   $balance_cenvat_credit=0;
							   $cenvat_credit_admissible=0;
							  for($i=0;$i < sizeof($start); $i++) {
								 $sno =0;
								 $sno = $i+1;
								$taxreturn = $a5_taxperiod_last_return[$i] + $taxreturn;
								$dateoffilling_return =$a5_dateoffilling_return[$i]+$dateoffilling_return;
								$balance_cenvat_credit =$a5_balance_cenvat_credit[$i] + $balance_cenvat_credit;
								$cenvat_credit_admissible =$a5_cenvat_credit_admissible[$i] + $cenvat_credit_admissible;
                           ?>
                                <tr>
                                <td class="lftheading" ><?php echo $sno; ?></td>
                                 <td>
								 <?php
								 if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a5_registration_no[$i])) { echo $a5_registration_no[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="5a_registration_no[]"
 class="form-control" value="<?php if(isset($a5_registration_no[$i])) { echo $a5_registration_no[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a5_taxperiod_last_return[$i])) { echo $a5_taxperiod_last_return[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="5a_taxperiod_last_return[]"
 class="form-control" value="<?php if(isset($a5_taxperiod_last_return[$i])) { echo $a5_taxperiod_last_return[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a5_dateoffilling_return[$i])) { echo $a5_dateoffilling_return[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);"  name="5a_dateoffilling_return[]"
 class="form-control" value="<?php if(isset($a5_dateoffilling_return[$i])) { echo $a5_dateoffilling_return[$i]; } else { echo ''; }?>"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a5_balance_cenvat_credit[$i])) { echo $a5_balance_cenvat_credit[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="5a_balance_cenvat_credit[]" value="<?php if(isset($a5_balance_cenvat_credit[$i])) { echo $a5_balance_cenvat_credit[$i]; } else { echo ''; }?>"  class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a5_cenvat_credit_admissible[$i])) { echo $a5_cenvat_credit_admissible[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($a5_cenvat_credit_admissible[$i])) { echo $a5_cenvat_credit_admissible[$i]; } else { echo ''; }?>" name="5a_cenvat_credit_admissible[]"  class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								 <?php if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 0))
								 {   
							        if($i==0){
									 ?>
                                    <td>
									 <a class="addMoreInvoice add-table5a"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
								 </td><?php } else { ?>
                               <td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td>								 
								 <?php } } ?>								 
                                </tr>
								
								<?php if(($sno==sizeof($start)) && ($returndata[0]->final_submit==1))  
								{
									echo '<tr><td></td><td>Total</td><td>'.$taxreturn.'</td><td>'.$dateoffilling_return.'</td><td>'.$balance_cenvat_credit.'</td><td>'.$cenvat_credit_admissible.'</td></tr>';
									
								}
									}  } else { ?>
								<tr>
                                <td class="lftheading" >1.</td>
                                 <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
                                </tr>
								<?php } ?>
								<tr style="display:none;"><td></td><td>Total</td><td></td><td></td><td></td><td></td></tr>
								           
                                                       
                              
                                     
							
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
								<?php
								if(!empty($returndata1[0]->totalinvoice) && ($returndata1[0]->totalinvoice > 0))
								{
								$b5bcform_tin_issuer=(explode(",",$b5bcform_tin_issuer));
								$b5bcform_nameof_issuer=(explode(",",$b5bcform_nameof_issuer));
								$b5bcform_no_of_item=(explode(",",$b5bcform_no_of_item));
								$b5bcform_amount=(explode(",",$b5bcform_amount));
								$b5bcform_applicable_vat_rate=(explode(",",$b5bcform_applicable_vat_rate));
							     $start='';
								if(sizeof($b5bcform_tin_issuer) > 1)
								{
									$start = $b5bcform_tin_issuer;
									
								}
								elseif(sizeof($b5bcform_nameof_issuer) > 1)
								{
									 $start = $b5bcform_nameof_issuer;
								}
								elseif(sizeof($b5bcform_no_of_item) > 1)
								{
									 $start = $b5bcform_no_of_item;
									
								}
								elseif(sizeof($b5bcform_amount) > 1)
								{
									$start = $b5bcform_amount;
								}
								elseif(sizeof($b5bcform_applicable_vat_rate) > 1)
								{
									$start = $b5bcform_applicable_vat_rate;
								}
								else{
									$start = $b5bcform_tin_issuer;
								}
								
						      $applicable_vat_rate=0;
							  $amount=0;
							  
							  for($i=0;$i < sizeof($start); $i++) {
							  $sno =0;
							 $sno = $i+1;	
                             $applicable_vat_rate=	$b5bcform_applicable_vat_rate[$i]+$applicable_vat_rate;
                             $amount = $b5bcform_amount[$i]+ $amount;							 
                           ?>
								<tr>
								 <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($b5bcform_tin_issuer[$i])) { echo $b5bcform_tin_issuer[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" value="<?php if(isset($b5bcform_tin_issuer[$i])) { echo $b5bcform_tin_issuer[$i]; } else { echo ''; } ?>" onKeyPress="return  isNumberKey(event,this);" name="5bcform_tin_issuer[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($b5bcform_nameof_issuer[$i])) { echo $b5bcform_nameof_issuer[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" value="<?php if(isset($b5bcform_nameof_issuer[$i])) { echo $b5bcform_nameof_issuer[$i]; } else { echo ''; } ?>" onKeyPress="return  isNumberKey(event,this);" name="5bcform_nameof_issuer[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($b5bcform_no_of_item[$i])) { echo $b5bcform_no_of_item[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($b5bcform_no_of_item[$i])) { echo $b5bcform_no_of_item[$i]; } else { echo ''; } ?>" name="5bcform_no_of_item[]" 
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($b5bcform_amount[$i])) { echo $b5bcform_amount[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" value="<?php if(isset($b5bcform_amount[$i])) { echo $b5bcform_amount[$i]; } else { echo ''; } ?>" onKeyPress="return  isNumberKey(event,this);" name="5bcform_amount[]" 
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($b5bcform_applicable_vat_rate[$i])) { echo $b5bcform_applicable_vat_rate[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" value="<?php if(isset($b5bcform_applicable_vat_rate[$i])) { echo $b5bcform_applicable_vat_rate[$i]; } else { echo ''; } ?>" onKeyPress="return  isNumberKey(event,this);" name="5bcform_applicable_vat_rate[]" 
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								 <?php if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 0))
								 {
								 if($i==0) { ?>
								 <td>
									 <a class="addMoreInvoice add-table5bcform"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
								 </td> <?php } else { ?>
								  <td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td>								 
								 <?php } } ?>	
								</tr><?php
								
								if(($sno==sizeof($start)) && ($returndata[0]->final_submit==1))  
								{
									
									echo '<tr><td></td><td>Total</td><td></td><td>'.$amount.'</td><td>'.$applicable_vat_rate.'</td></tr>';
									
								}
								} } else {?>

                              <tr>
								 <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="5bcform_no_of_item[]" 
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								<?php }?>
							 </tbody>
                            </table>
							
              <table  class="table  tablecontent tablecontent2 bordernone" id="table5bfform">
                               <tr>
                                <td class="lftheading" >F-Form</td>
                                          
                                </tr><?PHP
								if(!empty($returndata1[0]->totalinvoice) && ($returndata1[0]->totalinvoice > 0))
								{
								
							$b5bfform_tin_issuer=(explode(",",$b5bfform_tin_issuer));
							$b5bfform_nameof_issuer=(explode(",",$b5bfform_nameof_issuer));
							$b5bfform_no_of_form=(explode(",",$b5bfform_no_of_form));
							$b5bfform_amount=(explode(",",$b5bfform_amount));
							$b5bfform_applicable_vat_rate=(explode(",",$b5bfform_applicable_vat_rate));
							   $start='';
								if(sizeof($b5bfform_tin_issuer) > 1)
								{
									$start = $b5bfform_tin_issuer;
									
								}
								elseif(sizeof($b5bfform_nameof_issuer) > 1)
								{
									 $start = $b5bfform_nameof_issuer;
								}
								elseif(sizeof($b5bfform_no_of_form) > 1)
								{
									 $start = $b5bfform_no_of_form;
									
								}
								elseif(sizeof($b5bfform_amount) > 1)
								{
									$start = $b5bfform_amount;
								}
								elseif(sizeof($b5bfform_applicable_vat_rate) > 1)
								{
									$start = $b5bfform_applicable_vat_rate;
								}
								else{
									$start = $b5bfform_tin_issuer;
								}			   
						      $amount=0;
							  $applicable_vat_rate=0;
							  for($i=0;$i < sizeof($start); $i++) {
								$sno=0;
                                $sno = $i+1;
                              $amount=$b5bfform_amount[$i]+$amount;
							  $applicable_vat_rate=$b5bfform_applicable_vat_rate[$i]+$applicable_vat_rate;								
                           ?>
						
								<tr>
								 <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($b5bfform_tin_issuer[$i])) { echo $b5bfform_tin_issuer[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" value="<?php if(isset($b5bfform_tin_issuer[$i])) { echo $b5bfform_tin_issuer[$i]; } else { echo ''; } ?>" onKeyPress="return  isNumberKey(event,this);" name="5bfform_tin_issuer[]" 
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($b5bfform_nameof_issuer[$i])) { echo $b5bfform_nameof_issuer[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($b5bfform_nameof_issuer[$i])) { echo $b5bfform_nameof_issuer[$i]; } else { echo ''; } ?>" name="5bfform_nameof_issuer[]" 
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($b5bfform_no_of_form[$i])) { echo $b5bfform_no_of_form[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($b5bfform_no_of_form[$i])) { echo $b5bfform_no_of_form[$i]; } else { echo ''; } ?>" name="5bfform_no_of_form[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($b5bfform_amount[$i])) { echo $b5bfform_amount[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($b5bfform_amount[$i])) { echo $b5bfform_amount[$i]; } else { echo ''; } ?>" name="5bfform_amount[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($b5bfform_applicable_vat_rate[$i])) { echo $b5bfform_applicable_vat_rate[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($b5bfform_applicable_vat_rate[$i])) { echo $b5bfform_applicable_vat_rate[$i]; } else { echo ''; } ?>" name="5bfform_applicable_vat_rate[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								
								 <?php if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 0))
								 {
								 if($i==0) { ?>
								  <td>
									 <a class="addMoreInvoice add-table5bfform"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								 </a></td><?php } else {?>
								 		  <td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td>								 
						
								 <?php } } ?> 
								</tr> <?php 	if(($sno==sizeof($start)) && ($returndata[0]->final_submit==1))  
								{
									
									echo '<tr><td></td><td>Total</td><td></td><td>'.$amount.'</td><td>'.$applicable_vat_rate.'</td></tr>';
									
								} } } else { ?>
								<tr>
								 <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								</tr>
								<?php } ?>
								
								</table> <table  class="table  tablecontent tablecontent2 bordernone" id="table5bhiform">
								    <tr>
                                <td class="lftheading" >H/I Form</td></tr>
								<?php
								if(!empty($returndata1[0]->totalinvoice) && ($returndata1[0]->totalinvoice > 0))
								{
								$b5bhiform_tin_issuer=(explode(",",$b5bhiform_tin_issuer));
								$b5bhiform_nameof_issuer=(explode(",",$b5bhiform_nameof_issuer));
								$b5bhiform_no_of_form=(explode(",",$b5bhiform_no_of_form));
								$b5bhiform_amount=(explode(",",$b5bhiform_amount));
								$b5bhiform_applicable_vat_rate=(explode(",",$b5bhiform_applicable_vat_rate));
								$start='';
								if(sizeof($b5bhiform_tin_issuer) > 1)
								{
									$start = $b5bhiform_tin_issuer;
									
								}
								elseif(sizeof($b5bhiform_nameof_issuer) > 1)
								{
									 $start = $b5bhiform_nameof_issuer;
								}
								elseif(sizeof($b5bhiform_no_of_form) > 1)
								{
									 $start = $b5bhiform_no_of_form;
									
								}
								elseif(sizeof($b5bhiform_amount) > 1)
								{
									$start = $b5bhiform_amount;
								}
								elseif(sizeof($b5bhiform_applicable_vat_rate) > 1)
								{
									$start = $b5bhiform_applicable_vat_rate;
								}
								else{
									$start = $b5bhiform_tin_issuer;
								}			 					   
						       $amount=0;
							   $applicable_vat_rate=0;
							  for($i=0;$i < sizeof($b5bhiform_tin_issuer); $i++) {
								 $sno=0;
                                $sno = $i+1;
                              $amount=$b5bhiform_amount[$i]+$amount;
							  $applicable_vat_rate=$b5bhiform_applicable_vat_rate[$i]+$applicable_vat_rate;
                           ?>
								
								<tr>
                                 <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($b5bhiform_tin_issuer[$i])) { echo $b5bhiform_tin_issuer[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($b5bhiform_tin_issuer[$i])) { echo $b5bhiform_tin_issuer[$i]; } else { echo ''; }?>" name="5bhiform_tin_issuer[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($b5bhiform_nameof_issuer[$i])) { echo $b5bhiform_nameof_issuer[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($b5bhiform_nameof_issuer[$i])) { echo $b5bhiform_nameof_issuer[$i]; } else { echo ''; }?>" name="5bhiform_nameof_issuer[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($b5bhiform_no_of_form[$i])) { echo $b5bhiform_no_of_form[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($b5bhiform_no_of_form[$i])) { echo $b5bhiform_no_of_form[$i]; } else { echo ''; }?>" name="5bhiform_no_of_form[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($b5bhiform_amount[$i])) { echo $b5bhiform_amount[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($b5bhiform_amount[$i])) { echo $b5bhiform_amount[$i]; } else { echo ''; }?>" name="5bhiform_amount[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($b5bhiform_applicable_vat_rate[$i])) { echo $b5bhiform_applicable_vat_rate[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($b5bhiform_applicable_vat_rate[$i])) { echo $b5bhiform_applicable_vat_rate[$i]; } else { echo ''; }?>" name="5bhiform_applicable_vat_rate[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								 
								 <?php if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 0))
								 {
								 if($i==0) { ?>
                                     <td>
									 <a class="addMoreInvoice add-table5bhiform"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								 </a></td><?php } else {?> <td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td>								 
								 <?php } } ?>                  
								</tr> <?php  if(($sno==sizeof($start)) && ($returndata[0]->final_submit==1))  
								{
									
									echo '<tr><td></td><td>Total</td><td></td><td>'.$amount.'</td><td>'.$applicable_vat_rate.'</td></tr>';
									
								} } } else { ?>
                              <tr>
                                 <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								<?php } ?>		                                   
                              
                                     
							
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
								<?php
								if(!empty($returndata1[0]->totalinvoice) && ($returndata1[0]->totalinvoice > 0))
								{
									 $c5cform_registration_no=(explode(",",$c5cform_registration_no));
					                $c5cform_balanceof_itc_val=(explode(",",$c5cform_balanceof_itc_val));
									$c5cform_cform_turnover_form_pending=(explode(",",$c5cform_cform_turnover_form_pending));
									$c5cform_cform_taxpayable=(explode(",",$c5cform_cform_taxpayable));
									$c5cform_fform_turnover_form_pending=(explode(",",$c5cform_fform_turnover_form_pending));
									$c5cform_fform_taxpayable=(explode(",",$c5cform_fform_taxpayable));
									$c5cform_itcreversal_relatable=(explode(",",$c5cform_itcreversal_relatable));
									$c5cform_hiform_turnover_form_pending=(explode(",",$c5cform_hiform_turnover_form_pending));
									$c5cform_hiform_taxpayable=(explode(",",$c5cform_hiform_taxpayable));
									$c5cform_hiform_transitionitc2=(explode(",",$c5cform_hiform_transitionitc2));
								   	$start='';
								if(sizeof($c5cform_registration_no) > 1)
								{
									$start = $c5cform_registration_no;
									
								}
								elseif(sizeof($c5cform_balanceof_itc_val) > 1)
								{
									 $start = $c5cform_balanceof_itc_val;
								}
								elseif(sizeof($c5cform_cform_turnover_form_pending) > 1)
								{
									 $start = $c5cform_cform_turnover_form_pending;
									
								}
								elseif(sizeof($c5cform_cform_taxpayable) > 1)
								{
									$start = $c5cform_cform_taxpayable;
								}
								elseif(sizeof($c5cform_fform_turnover_form_pending) > 1)
								{
									$start = $c5cform_fform_turnover_form_pending;
								}
								elseif(sizeof($c5cform_fform_taxpayable) > 1)
								{
									$start = $c5cform_fform_taxpayable;
								}
								elseif(sizeof($c5cform_itcreversal_relatable) > 1)
								{
									$start = $c5cform_itcreversal_relatable;
								}
								elseif(sizeof($c5cform_hiform_turnover_form_pending) > 1)
								{
									$start = $c5cform_hiform_turnover_form_pending;
								}
								elseif(sizeof($c5cform_hiform_taxpayable) > 1)
								{
									$start = $c5cform_hiform_taxpayable;
								}
								elseif(sizeof($c5cform_hiform_transitionitc2) > 1)
								{
									$start = $c5cform_hiform_transitionitc2;
								}
								else{
									$start = $c5cform_registration_no;
								}				
							       
								
							  for($i=0;$i < sizeof($start); $i++) {
								 
                           ?>
                                <tr>
                               <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($c5cform_registration_no[$i])) { echo $c5cform_registration_no[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="5cform_registration_no[]" value="<?php if(isset($c5cform_registration_no[$i])) { echo $c5cform_registration_no[$i]; } else { echo ''; }?>" class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($c5cform_balanceof_itc_val[$i])) { echo $c5cform_balanceof_itc_val[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($c5cform_balanceof_itc_val[$i])) { echo $c5cform_balanceof_itc_val[$i]; } else { echo ''; }?>" name="5cform_balanceof_itc_vat[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($c5cform_cform_turnover_form_pending[$i])) { echo $c5cform_cform_turnover_form_pending[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($c5cform_cform_turnover_form_pending[$i])) { echo $c5cform_cform_turnover_form_pending[$i]; } else { echo ''; }?>" name="5cform_cform_turnover_form_pending[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($c5cform_cform_taxpayable[$i])) { echo $c5cform_cform_taxpayable[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($c5cform_cform_taxpayable[$i])) { echo $c5cform_cform_taxpayable[$i]; } else { echo ''; }?>" name="5cform_cform_taxpayable[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($c5cform_fform_turnover_form_pending[$i])) { echo $c5cform_fform_turnover_form_pending[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($c5cform_fform_turnover_form_pending[$i])) { echo $c5cform_fform_turnover_form_pending[$i]; } else { echo ''; }?>" name="5cform_fform_turnover_form_pending[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($c5cform_fform_taxpayable[$i])) { echo $c5cform_fform_taxpayable[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($c5cform_fform_taxpayable[$i])) { echo $c5cform_fform_taxpayable[$i]; } else { echo ''; }?>" name="5cform_fform_taxpayable[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($c5cform_itcreversal_relatable[$i])) { echo $c5cform_itcreversal_relatable[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($c5cform_itcreversal_relatable[$i])) { echo $c5cform_itcreversal_relatable[$i]; } else { echo ''; }?>" name="5cform_itcreversal_relatable[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($c5cform_hiform_turnover_form_pending[$i])) { echo $c5cform_hiform_turnover_form_pending[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($c5cform_hiform_turnover_form_pending[$i])) { echo $c5cform_hiform_turnover_form_pending[$i]; } else { echo ''; }?>" name="5cform_hiform_turnover_form_pending[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($c5cform_hiform_taxpayable[$i])) { echo $c5cform_hiform_taxpayable[$i]; } else { echo ''; }?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($c5cform_hiform_taxpayable[$i])) { echo $c5cform_hiform_taxpayable[$i]; } else { echo ''; }?>" name="5cform_hiform_taxpayable[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($c5cform_hiform_transitionitc2[$i])) { echo $c5cform_hiform_transitionitc2[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($c5cform_hiform_transitionitc2[$i])) { echo $c5cform_hiform_transitionitc2[$i]; } else { echo ''; }?>" name="5cform_hiform_transitionitc2[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td><?php
								 if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 0))
								 {
								 if($i==0){?>
								 <td>
									 <a class="addMoreInvoice add-table5c"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								 </a></td><?php } else { ?>
								  <td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td>								 
				
								 <?php } } ?>								 
										
                                                      
								</tr><?php } } else { ?>
								    <tr>
                               <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								<?php } ?>								
                              
                                     
							
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
                                
                                <tbody><?php
									if(!empty($returndata1[0]->totalinvoice) && ($returndata1[0]->totalinvoice > 0))
								{
								$a6ainvoice_document_no=(explode(",",$a6ainvoice_document_no));
			                    $a6ainvoice_document_date=(explode(",",$a6ainvoice_document_date));
								$a6asupplier_registration_no=(explode(",",$a6asupplier_registration_no));
								$a6arecipients_registration_no=(explode(",",$a6arecipients_registration_no));
								$a6a_value=(explode(",",$a6a_value));
								$a6a_ed_cvd=(explode(",",$a6a_ed_cvd));
								$a6a_sad=(explode(",",$a6a_sad));
								$a6a_totaleligible_cenvat=(explode(",",$a6a_totaleligible_cenvat));
								$a6a_totalcenvat_credit=(explode(",",$a6a_totalcenvat_credit));
								$a6a_totalcenvat_credit_unavailed=(explode(",",$a6a_totalcenvat_credit_unavailed));
								$start='';
								if(sizeof($a6ainvoice_document_date) > 1)
								{
									$start = $a6ainvoice_document_date;
									
								}
								elseif(sizeof($a6asupplier_registration_no) > 1)
								{
									 $start = $a6asupplier_registration_no;
								}
								elseif(sizeof($a6arecipients_registration_no) > 1)
								{
									 $start = $a6arecipients_registration_no;
									
								}
								elseif(sizeof($a6a_value) > 1)
								{
									$start = $a6a_value;
								}
								elseif(sizeof($a6a_ed_cvd) > 1)
								{
									$start = $a6a_ed_cvd;
								}
								elseif(sizeof($a6a_sad) > 1)
								{
									$start = $a6a_sad;
								}
								elseif(sizeof($a6a_totaleligible_cenvat) > 1)
								{
									$start = $a6a_totaleligible_cenvat;
								}
								elseif(sizeof($a6a_totalcenvat_credit) > 1)
								{
									$start = $a6a_totalcenvat_credit;
								}
								elseif(sizeof($a6a_totalcenvat_credit_unavailed) > 1)
								{
									$start = $a6a_totalcenvat_credit_unavailed;
								}
								
								else{
									$start = $a6ainvoice_document_date;
								}								   
						
							  for($i=0;$i < sizeof($start); $i++) {
								 
                           ?>
				
                                <tr>
                              <td></td>
                                 <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a6ainvoice_document_no[$i])) { echo $a6ainvoice_document_no[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($a6ainvoice_document_no[$i])) { echo $a6ainvoice_document_no[$i]; } else { echo ''; } ?>" name="6ainvoice_document_no[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a6ainvoice_document_date[$i])) { echo $a6ainvoice_document_date[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($a6ainvoice_document_date[$i])) { echo $a6ainvoice_document_date[$i]; } else { echo ''; } ?>" name="6ainvoice_document_date[]" class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a6asupplier_registration_no[$i])) { echo $a6asupplier_registration_no[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($a6asupplier_registration_no[$i])) { echo $a6asupplier_registration_no[$i]; } else { echo ''; } ?>" name="6asupplier_registration_no[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a6arecipients_registration_no[$i])) { echo $a6arecipients_registration_no[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($a6arecipients_registration_no[$i])) { echo $a6arecipients_registration_no[$i]; } else { echo ''; } ?>" name="6arecipients_registration_no[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a6a_value[$i])) { echo $a6a_value[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($a6a_value[$i])) { echo $a6a_value[$i]; } else { echo ''; } ?>" name="6a_value[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a6a_ed_cvd[$i])) { echo $a6a_ed_cvd[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($a6a_ed_cvd[$i])) { echo $a6a_ed_cvd[$i]; } else { echo ''; } ?>" name="6a_ed_cvd[]" class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a6a_sad[$i])) { echo $a6a_sad[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($a6a_sad[$i])) { echo $a6a_sad[$i]; } else { echo ''; } ?>" name="6a_sad[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a6a_totaleligible_cenvat[$i])) { echo $a6a_totaleligible_cenvat[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($a6a_totaleligible_cenvat[$i])) { echo $a6a_totaleligible_cenvat[$i]; } else { echo ''; } ?>" name="6a_totaleligible_cenvat[]" class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a6a_totalcenvat_credit[$i])) { echo $a6a_totalcenvat_credit[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($a6a_totalcenvat_credit[$i])) { echo $a6a_totalcenvat_credit[$i]; } else { echo ''; } ?>" name="6a_totalcenvat_credit1[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a6a_totalcenvat_credit_unavailed[$i])) { echo $a6a_totalcenvat_credit_unavailed[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($a6a_totalcenvat_credit_unavailed[$i])) { echo $a6a_totalcenvat_credit_unavailed[$i]; } else { echo ''; } ?>" name="6a_totalcenvat_credit_unavailed[]" class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                     <?php if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 0))
								 { 
									  if($i==0){
									 ?>
                                    <td>
									 <a class="addMoreInvoice add-table6a"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
								 </td><?php } else { ?>
                               <td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td>								 
								 <?php } } ?>	                  
								</tr> <?php } } else { ?>
								 <tr>
                              <td></td>
                                 <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="6arecipients_registration_no[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="6a_totalcenvat_credit1[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								<?php } ?>
								                    
                                                       
                              
                                     
							
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
								<?php
								if(!empty($returndata1[0]->totalinvoice) && ($returndata1[0]->totalinvoice > 0))
								{
								
			                    $b6binvoice_document_no=(explode(",",$b6binvoice_document_no));
								$b6binvoice_document_date=(explode(",",$b6binvoice_document_date));
								$b6bsupplier_registration_no=(explode(",",$b6bsupplier_registration_no));
								$b6breceipients_registration_no=(explode(",",$b6breceipients_registration_no));
								$b6b_value=(explode(",",$b6b_value));
								$b6b_taxpaid_vat=(explode(",",$b6b_taxpaid_vat));
								$b6b_totaleligible_vat=(explode(",",$b6b_totaleligible_vat));
								$b6b_totalvat_creditavailed=(explode(",",$b6b_totalvat_creditavailed));
								$b6b_totalvat_creditunavailed=(explode(",",$b6b_totalvat_creditunavailed));
								   
						        $start='';
								if(sizeof($b6binvoice_document_no) > 1)
								{
									$start = $b6binvoice_document_no;
									
								}
								elseif(sizeof($b6binvoice_document_date) > 1)
								{
									 $start = $b6binvoice_document_date;
								}
								elseif(sizeof($b6bsupplier_registration_no) > 1)
								{
									 $start = $b6bsupplier_registration_no;
									
								}
								elseif(sizeof($b6breceipients_registration_no) > 1)
								{
									$start = $b6breceipients_registration_no;
								}
								elseif(sizeof($b6b_value) > 1)
								{
									$start = $b6b_value;
								}
								elseif(sizeof($b6b_taxpaid_vat) > 1)
								{
									$start = $b6b_taxpaid_vat;
								}
								elseif(sizeof($b6b_totaleligible_vat) > 1)
								{
									$start = $b6b_totaleligible_vat;
								}
								elseif(sizeof($b6b_totalvat_creditavailed) > 1)
								{
									$start = $b6b_totalvat_creditavailed;
								}
								elseif(sizeof($b6b_totalvat_creditunavailed) > 1)
								{
									$start = $b6b_totalvat_creditunavailed;
								}								
								else{
									$start = $b6binvoice_document_no;
								}
							  for($i=0;$i < sizeof($start); $i++) {
								 
                           ?>
									
                                <tr>
                              <td></td>
                                 
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($b6binvoice_document_no[$i])) { echo $b6binvoice_document_no[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($b6binvoice_document_no[$i])) { echo $b6binvoice_document_no[$i]; } else { echo ''; }?>" name="6binvoice_document_no[]" class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($b6binvoice_document_date[$i])) { echo $b6binvoice_document_date[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($b6binvoice_document_date[$i])) { echo $b6binvoice_document_date[$i]; } else { echo ''; }?>" name="6binvoice_document_date[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($b6bsupplier_registration_no[$i])) { echo $b6bsupplier_registration_no[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($b6bsupplier_registration_no[$i])) { echo $b6bsupplier_registration_no[$i]; } else { echo ''; }?>" name="6bsupplier_registration_no[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($b6breceipients_registration_no[$i])) { echo $b6breceipients_registration_no[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($b6breceipients_registration_no[$i])) { echo $b6breceipients_registration_no[$i]; } else { echo ''; }?>"  name="6breceipients_registration_no[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($b6b_value[$i])) { echo $b6b_value[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($b6b_value[$i])) { echo $b6b_value[$i]; } else { echo ''; }?>" name="6b_value[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($b6b_taxpaid_vat[$i])) { echo $b6b_taxpaid_vat[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($b6b_taxpaid_vat[$i])) { echo $b6b_taxpaid_vat[$i]; } else { echo ''; }?>" name="6b_taxpaid_vat[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($b6b_totaleligible_vat[$i])) { echo $b6b_taxpaid_vat[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="6b_totaleligible_vat[]" value="<?php if(isset($b6b_totaleligible_vat[$i])) { echo $b6b_taxpaid_vat[$i]; } else { echo ''; }?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($b6b_totalvat_creditavailed[$i])) { echo $b6b_totalvat_creditavailed[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="6b_totalvat_creditavailed[]" value="<?php if(isset($b6b_totalvat_creditavailed[$i])) { echo $b6b_totalvat_creditavailed[$i]; } else { echo ''; }?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($b6b_totalvat_creditunavailed[$i])) { echo $b6b_totalvat_creditunavailed[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($b6b_totalvat_creditunavailed[$i])) { echo $b6b_totalvat_creditunavailed[$i]; } else { echo ''; }?>" name="6b_totalvat_creditunavailed[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								
                                    <?php if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 0))
								 { 
									 if($i==0){
									 ?>
                                    <td>
									 <a class="addMoreInvoice add-table6b"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
								 </td><?php } else { ?>
                               <td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td>								 
								 <?php } } ?>	 							 
								</tr><?php } } else { ?>
								 <tr>
                              <td></td>
                                 
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								<?php } ?>								
                                                       
                              
                                     
							
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
                                
                                <tbody><?php
								if(!empty($returndata1[0]->totalinvoice) && ($returndata1[0]->totalinvoice > 0))
								{
								
								 $a7a1_hsncode=(explode(",",$a7a1_hsncode));
								$a7a1_unit=(explode(",",$a7a1_unit));
								$a7a1_qty=(explode(",",$a7a1_qty));
								$a7a1_value=(explode(",",$a7a1_value));
								$a7a1_eligible_duties=(explode(",",$a7a1_eligible_duties));
				                 $start='';
								if(sizeof($a7a1_hsncode) > 1)
								{
									$start = $a7a1_hsncode;
									
								}
								elseif(sizeof($a7a1_unit) > 1)
								{
									 $start = $a7a1_unit;
								}
								elseif(sizeof($a7a1_qty) > 1)
								{
									 $start = $a7a1_qty;
									
								}
								elseif(sizeof($a7a1_value) > 1)
								{
									$start = $a7a1_value;
								}
								elseif(sizeof($a7a1_eligible_duties) > 1)
								{
									$start = $a7a1_eligible_duties;
								}
																
								else{
									$start = $a7a1_hsncode;
								}
								   
						
							  for($i=0;$i < sizeof($start); $i++) {
								 
                           ?>					   
                                <tr>                                           
								 
								 <td></td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a7a1_hsncode[$i])) { echo $a7a1_hsncode[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="7a1_hsncode[]" value="<?php if(isset($a7a1_hsncode[$i])) { echo $a7a1_hsncode[$i]; } else { echo ''; }?>" 
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a7a1_unit[$i])) { echo $a7a1_unit[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="7a1_unit[]" value="<?php if(isset($a7a1_unit[$i])) { echo $a7a1_unit[$i]; } else { echo ''; }?>" 
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a7a1_qty[$i])) { echo $a7a1_qty[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="7a1_qty[]" value="<?php if(isset($a7a1_qty[$i])) { echo $a7a1_qty[$i]; } else { echo ''; }?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a7a1_value[$i])) { echo $a7a1_value[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="7a1_value[]" value="<?php if(isset($a7a1_value[$i])) { echo $a7a1_value[$i]; } else { echo ''; }?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a7a1_eligible_duties[$i])) { echo $a7a1_eligible_duties[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="7a1_eligible_duties[]" value="<?php if(isset($a7a1_eligible_duties[$i])) { echo $a7a1_eligible_duties[$i]; } else { echo ''; }?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                     <?php if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 0))
								 { 
									  if($i==0){
									 ?>
                                    <td>
									 <a class="addMoreInvoice add-table7a1"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
								 </td><?php } else { ?>
                               <td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td>								 
								 <?php } } ?>	                     
                                </tr>
								<?php } } else { ?>
								<tr>                                           
								 
								 <td></td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
                                </tr>
								<?php } ?>
								</tbody></table>
                                  <table  class="table  tablecontent tablecontent2 bordernone" id="table7a2">
								<tr><td colspan="6">Input Contained in finished and semi finished goods</td></tr>
								<?php
								if(!empty($returndata1[0]->totalinvoice) && ($returndata1[0]->totalinvoice > 0))
								{
								
			                    $a7a2_hsncode=(explode(",",$a7a2_hsncode));
								$a7a2_unit=(explode(",",$a7a2_unit));
								$a7a2_qty=(explode(",",$a7a2_qty));
								$a7a2_value=(explode(",",$a7a2_value));
								$a7a2_eligible_duties=(explode(",",$a7a2_eligible_duties));
								$start='';
								if(sizeof($a7a2_hsncode) > 1)
								{
									$start = $a7a2_hsncode;
									
								}
								elseif(sizeof($a7a2_unit) > 1)
								{
									 $start = $a7a2_unit;
								}
								elseif(sizeof($a7a2_qty) > 1)
								{
									 $start = $a7a2_qty;
									
								}
								elseif(sizeof($a7a2_value) > 1)
								{
									$start = $a7a2_value;
								}
								elseif(sizeof($a7a2_eligible_duties) > 1)
								{
									$start = $a7a2_eligible_duties;
								}
																
								else{
									$start = $a7a2_hsncode;
								}   
						
							  for($i=0;$i < sizeof($start); $i++) {
								 
                           ?>  
                                             
								  <tr>
								 <td></td>
								   <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a7a2_hsncode[$i])) { echo $a7a2_hsncode[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($a7a2_hsncode[$i])) { echo $a7a2_hsncode[$i]; } else { echo ''; }?>" name="7a2_hsncode[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a7a2_unit[$i])) { echo $a7a2_unit[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="7a2_unit[]" value="<?php if(isset($a7a2_unit[$i])) { echo $a7a2_unit[$i]; } else { echo ''; }?>" class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a7a2_qty[$i])) { echo $a7a2_qty[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="7a2_qty[]" value="<?php if(isset($a7a2_qty[$i])) { echo $a7a2_qty[$i]; } else { echo ''; }?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a7a2_value[$i])) { echo $a7a2_value[$i]; } else { echo ''; }; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="7a2_value[]" value="<?php if(isset($a7a2_value[$i])) { echo $a7a2_value[$i]; } else { echo ''; }?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a7a2_eligible_duties[$i])) { echo $a7a2_eligible_duties[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="7a2_eligible_duties[]" value="<?php if(isset($a7a2_eligible_duties[$i])) { echo $a7a2_eligible_duties[$i]; } else { echo ''; }?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                     <?php if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 0))
								 { 
									 if($i==0){
									 ?>
                                    <td>
									 <a class="addMoreInvoice add-table7a2"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
								 </td><?php } else { ?>
                               <td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td>								 
								 <?php } } ?>	                  
								</tr><?php } } else { ?>
								<tr>
								 <td></td>
								   <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
                                </tr>
								<?php } ?>

								</table>
 <table  class="table  tablecontent tablecontent2 bordernone" id="table7a3">								
                                  <tr><td colspan="6">7B Where duty paid invoices are not available</td></tr>
										<?php
								if(!empty($returndata1[0]->totalinvoice) && ($returndata1[0]->totalinvoice > 0))
								{
								
			                    $a7a3_hsncode=(explode(",",$a7a3_hsncode));
								$a7a3_unit=(explode(",",$a7a3_unit));
								$a7a3_qty=(explode(",",$a7a3_qty));
								$a7a3_value=(explode(",",$a7a3_value));
								$a7a3_eligible_duties=(explode(",",$a7a3_eligible_duties));
								   
						        $start='';
								if(sizeof($a7a3_hsncode) > 1)
								{
									$start = $a7a3_hsncode;
									
								}
								elseif(sizeof($a7a3_unit) > 1)
								{
									 $start = $a7a3_unit;
								}
								elseif(sizeof($a7a3_qty) > 1)
								{
									 $start = $a7a3_qty;
									
								}
								elseif(sizeof($a7a3_value) > 1)
								{
									$start = $a7a3_value;
								}
								elseif(sizeof($a7a3_eligible_duties) > 1)
								{
									$start = $a7a3_eligible_duties;
								}
																
								else{
									$start = $a7a3_hsncode;
								}   
							  for($i=0;$i < sizeof($start); $i++) {
								 
                           ?>          
								  <tr>                                             
								 <td></td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a7a3_hsncode[$i])) { echo $a7a3_hsncode[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="7a3_hsncode[]" value="<?php if(isset($a7a3_hsncode[$i])) { echo $a7a3_hsncode[$i]; } else { echo ''; } ?>" class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a7a3_unit[$i])) { echo $a7a3_unit[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);"  value="<?php if(isset($a7a3_unit[$i])) { echo $a7a3_unit[$i]; } else { echo ''; } ?>"  name="7a3_unit[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a7a3_qty[$i])) { echo $a7a3_qty[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($a7a3_qty[$i])) { echo $a7a3_qty[$i]; } else { echo ''; } ?>" name="7a3_qty[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a7a3_value[$i])) { echo $a7a3_value[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($a7a3_value[$i])) { echo $a7a3_value[$i]; } else { echo ''; } ?>" name="7a3_value[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a7a3_eligible_duties[$i])) { echo $a7a3_eligible_duties[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($a7a3_eligible_duties[$i])) { echo $a7a3_eligible_duties[$i]; } else { echo ''; } ?>" name="7a3_eligible_duties[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                    <?php if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 0))
								 { 
									if($i==0){
									 ?>
                                    <td>
									 <a class="addMoreInvoice add-table7a3"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
								 </td><?php } else { ?>
                               <td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td>								 
								 <?php } } ?>	                  
								</tr> <?php } } else { ?>
								  <tr>                                             
								 <td></td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								<?php } ?>								
                                     
							
                               
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
                                
                                <tbody><?php
								if(!empty($returndata1[0]->totalinvoice) && ($returndata1[0]->totalinvoice > 0))
								{
								
			                    $b7b_nameof_supplier=(explode(",",$b7b_nameof_supplier));
								$b7b_invoice_number=(explode(",",$b7b_invoice_number));
								$b7b_invoice_date=(explode(",",$b7b_invoice_date));
								$b7b_description=(explode(",",$b7b_description));
								$b7b_quantity=(explode(",",$b7b_quantity));
								$b7b_uqc=(explode(",",$b7b_uqc));
								$b7b_value=(explode(",",$b7b_value));
								$b7b_eligible_duties=(explode(",",$b7b_eligible_duties));
								$b7b_vat=(explode(",",$b7b_vat));
								$b7b_dateonwhich_receipients=(explode(",",$b7b_dateonwhich_receipients));
								 $start='';
								if(sizeof($b7b_nameof_supplier) > 1)
								{
									$start = $b7b_nameof_supplier;
									
								}
								elseif(sizeof($b7b_invoice_number) > 1)
								{
									 $start = $b7b_invoice_number;
								}
								elseif(sizeof($b7b_invoice_date) > 1)
								{
									 $start = $b7b_invoice_date;
									
								}
								elseif(sizeof($b7b_description) > 1)
								{
									$start = $b7b_description;
								}
								elseif(sizeof($b7b_quantity) > 1)
								{
									$start = $b7b_quantity;
								}
								elseif(sizeof($b7b_uqc) > 1)
								{
									$start = $b7b_uqc;
								}
								elseif(sizeof($b7b_value) > 1)
								{
									$start = $b7b_value;
								}
								elseif(sizeof($b7b_eligible_duties) > 1)
								{
									$start = $b7b_eligible_duties;
								}
								elseif(sizeof($b7b_vat) > 1)
								{
									$start = $b7b_vat;
								}
								elseif(sizeof($b7b_dateonwhich_receipients) > 1)
								{
									$start = $b7b_dateonwhich_receipients;
								}			
																
								else{
									$start = $b7b_nameof_supplier;
								}      
						
							  for($i=0;$i < sizeof($start); $i++) {
								 
                           ?>
		
                                <tr>
                              
                                 <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($b7b_nameof_supplier[$i])) { echo $b7b_nameof_supplier[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($b7b_nameof_supplier[$i])) { echo $b7b_nameof_supplier[$i]; } else { echo ''; } ?>" name="7b_nameof_supplier[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($b7b_invoice_number[$i])) { echo $b7b_invoice_number[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($b7b_invoice_number[$i])) { echo $b7b_invoice_number[$i]; } else { echo ''; } ?>"  name="7b_invoice_number[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($b7b_invoice_date[$i])) { echo $b7b_invoice_date[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($b7b_invoice_date[$i])) { echo $b7b_invoice_date[$i]; } else { echo ''; } ?>" name="7b_invoice_date[]" 
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($b7b_description[$i])) { echo $b7b_description[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($b7b_description[$i])) { echo $b7b_description[$i]; } else { echo ''; } ?>" name="7b_description[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($b7b_quantity[$i])) { echo $b7b_quantity[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="7b_quantity[]" value="<?php if(isset($b7b_quantity[$i])) { echo $b7b_quantity[$i]; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($b7b_uqc[$i])) { echo $b7b_uqc[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="7b_uqc[]" value="<?php if(isset($b7b_uqc[$i])) { echo $b7b_uqc[$i]; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($b7b_value[$i])) { echo $b7b_value[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="7b_value[]" value="<?php if(isset($b7b_value[$i])) { echo $b7b_value[$i]; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($b7b_eligible_duties[$i])) { echo $b7b_eligible_duties[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="7b_eligible_duties[]" value="<?php if(isset($b7b_eligible_duties[$i])) { echo $b7b_eligible_duties[$i]; } else { echo ''; } ?>" class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($b7b_vat[$i])) { echo $b7b_vat[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($b7b_vat[$i])) { echo $b7b_vat[$i]; } else { echo ''; } ?>" name="7b_vat[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($b7b_dateonwhich_receipients[$i])) { echo $b7b_dateonwhich_receipients[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="7b_dateonwhich_receipients[]" value="<?php if(isset($b7b_dateonwhich_receipients[$i])) { echo $b7b_dateonwhich_receipients[$i]; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                    <?php if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 0))
								 { 
									if($i==0){
									 ?>
                                    <td>
									 <a class="addMoreInvoice add-table7b"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
								 </td><?php } else { ?>
                               <td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td>								 
								 <?php } } ?>	                    
								</tr><?php } } else { ?>
								 <tr>
                              
                                 <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
                                                       
								<?php } ?>
                                     
							
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
								<?php
								if(!empty($returndata1[0]->totalinvoice) && ($returndata1[0]->totalinvoice > 0))
								{
								
			                    $c7c1_description=(explode(",",$c7c1_description));
								$c7c1_unit=(explode(",",$c7c1_unit));
								$c7c1_qty=(explode(",",$c7c1_qty));
								$c7c1_value=(explode(",",$c7c1_value));
								$c7c1_vat=(explode(",",$c7c1_vat));
								$c7c1_totalinput_taxcredit=(explode(",",$c7c1_totalinput_taxcredit));
								$c7c1_totalinput_taxcredit_exempt=(explode(",",$c7c1_totalinput_taxcredit_exempt));
								$c7c1_totalinput_taxcredit_admissible=(explode(",",$c7c1_totalinput_taxcredit_admissible));
                                 $start='';
								if(sizeof($c7c1_description) > 1)
								{
									$start = $c7c1_description;
									
								}
								elseif(sizeof($c7c1_unit) > 1)
								{
									 $start = $c7c1_unit;
								}
								elseif(sizeof($c7c1_qty) > 1)
								{
									 $start = $c7c1_qty;
									
								}
								elseif(sizeof($c7c1_value) > 1)
								{
									$start = $c7c1_value;
								}
								elseif(sizeof($c7c1_vat) > 1)
								{
									$start = $c7c1_vat;
								}
								elseif(sizeof($c7c1_totalinput_taxcredit) > 1)
								{
									$start = $c7c1_totalinput_taxcredit;
								}
								elseif(sizeof($c7c1_totalinput_taxcredit_exempt) > 1)
								{
									$start = $c7c1_totalinput_taxcredit_exempt;
								}
								elseif(sizeof($c7c1_totalinput_taxcredit_admissible) > 1)
								{
									$start = $c7c1_totalinput_taxcredit_admissible;
								}				
																
								else{
									$start = $c7c1_description;
								}      
								   
						
							  for($i=0;$i < sizeof($start); $i++) {
								 
                           ?>
		                        <tr>                              
								  
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($c7c1_description[$i])) { echo $c7c1_description[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($c7c1_description[$i])) { echo $c7c1_description[$i]; } else { echo ''; } ?>"  name="7c1_description[]" class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($c7c1_unit[$i])) { echo $c7c1_unit[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($c7c1_unit[$i])) { echo $c7c1_unit[$i]; } else { echo ''; } ?>" name="7c1_unit[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($c7c1_qty[$i])) { echo $c7c1_qty[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="7c1_qty[]" value="<?php if(isset($c7c1_qty[$i])) { echo $c7c1_qty[$i]; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($c7c1_value[$i])) { echo $c7c1_value[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="7c1_value[]" value="<?php if(isset($c7c1_value[$i])) { echo $c7c1_value[$i]; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($c7c1_vat[$i])) { echo $c7c1_vat[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="7c1_vat[]" value="<?php if(isset($c7c1_vat[$i])) { echo $c7c1_vat[$i]; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($c7c1_totalinput_taxcredit[$i])) { echo $c7c1_totalinput_taxcredit[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($c7c1_totalinput_taxcredit[$i])) { echo $c7c1_totalinput_taxcredit[$i]; } else { echo ''; } ?>" name="7c1_totalinput_taxcredit[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($c7c1_totalinput_taxcredit_exempt[$i])) { echo $c7c1_totalinput_taxcredit_exempt[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($c7c1_totalinput_taxcredit_exempt[$i])) { echo $c7c1_totalinput_taxcredit_exempt[$i]; } else { echo ''; } ?>" name="7c1_totalinput_taxcredit_exempt[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($c7c1_totalinput_taxcredit_admissible[$i])) { echo $c7c1_totalinput_taxcredit_admissible[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($c7c1_totalinput_taxcredit_admissible[$i])) { echo $c7c1_totalinput_taxcredit_admissible[$i]; } else { echo ''; } ?>" name="7c1_totalinput_taxcredit_admissible[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                     <?php if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 0))
								 { 
									if($i==0){
									 ?>
                                    <td>
									 <a class="addMoreInvoice add-table7c1"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
								 </td><?php } else { ?>
                               <td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td>								 
								 <?php } } ?>	                 
								</tr><?php } } else { ?>
								<tr>                              
								  
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);"  name="7c1_description[]" class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
                                </tr>
								<?php } ?>
								</tbody></table>
 <table  class="table  tablecontent tablecontent2 bordernone" id="table7c2">								
<tr><td colspan='8'>Inputs contained in semi-finished and finished goods</td></tr>
<?php
if(!empty($returndata1[0]->totalinvoice) && ($returndata1[0]->totalinvoice > 0))
								{
								
			                    $c7c2_description=(explode(",",$c7c2_description));
								$c7c2_unit=(explode(",",$c7c2_unit));
								$c7c2_qty=(explode(",",$c7c2_qty));
								$c7c2_value=(explode(",",$c7c2_value));
								$c7c2_vat=(explode(",",$c7c2_vat));
								$c7c2_totalinput_taxcredit=(explode(",",$c7c2_totalinput_taxcredit));
								$c7c2_totalinput_taxcredit_exempt=(explode(",",$c7c2_totalinput_taxcredit_exempt));
								$c7c2_totalinput_taxcredit_admissible=(explode(",",$c7c2_totalinput_taxcredit_admissible));
								$start='';
								if(sizeof($c7c2_description) > 1)
								{
									$start = $c7c2_description;
									
								}
								elseif(sizeof($c7c2_unit) > 1)
								{
									 $start = $c7c2_unit;
								}
								elseif(sizeof($c7c2_qty) > 1)
								{
									 $start = $c7c2_qty;
									
								}
								elseif(sizeof($c7c2_value) > 1)
								{
									$start = $c7c2_value;
								}
								elseif(sizeof($c7c2_totalinput_taxcredit) > 1)
								{
									$start = $c7c2_totalinput_taxcredit;
								}
								elseif(sizeof($c7c2_totalinput_taxcredit_exempt) > 1)
								{
									$start = $c7c2_totalinput_taxcredit_exempt;
								}
								elseif(sizeof($c7c1_totalinput_taxcredit_exempt) > 1)
								{
									$start = $c7c1_totalinput_taxcredit_exempt;
								}
								elseif(sizeof($c7c2_totalinput_taxcredit_admissible) > 1)
								{
									$start = $c7c2_totalinput_taxcredit_admissible;
								}				
																
								else{
									$start = $c7c2_description;
								}      	  
								   
						
							  for($i=0;$i < sizeof($start); $i++) {
								 
                           ?>
                          <tr>                      				  
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($c7c2_description[$i])) { echo $c7c2_description[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($c7c2_description[$i])) { echo $c7c2_description[$i]; } else { echo ''; }?>" name="7c2_description[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($c7c2_unit[$i])) { echo $c7c2_unit[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($c7c2_unit[$i])) { echo $c7c2_unit[$i]; } else { echo ''; }?>" name="7c2_unit[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($c7c2_qty[$i])) { echo $c7c2_qty[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($c7c2_qty[$i])) { echo $c7c2_qty[$i]; } else { echo ''; }?>" name="7c2_qty[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($c7c2_value[$i])) { echo $c7c2_value[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($c7c2_value[$i])) { echo $c7c2_value[$i]; } else { echo ''; }?>" name="7c2_value[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($c7c2_vat[$i])) { echo $c7c2_vat[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($c7c2_vat[$i])) { echo $c7c2_vat[$i]; } else { echo ''; }?>" name="7c2_vat[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($c7c2_totalinput_taxcredit[$i])) { echo $c7c2_totalinput_taxcredit[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($c7c2_totalinput_taxcredit[$i])) { echo $c7c2_totalinput_taxcredit[$i]; } else { echo ''; }?>" name="7c2_totalinput_taxcredit[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($c7c2_totalinput_taxcredit_exempt[$i])) { echo $c7c2_totalinput_taxcredit_exempt[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($c7c2_totalinput_taxcredit_exempt[$i])) { echo $c7c2_totalinput_taxcredit_exempt[$i]; } else { echo ''; }?>" name="7c2_totalinput_taxcredit_exempt[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($c7c2_totalinput_taxcredit_admissible[$i])) { echo $c7c2_totalinput_taxcredit_admissible[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($c7c2_totalinput_taxcredit_admissible[$i])) { echo $c7c2_totalinput_taxcredit_admissible[$i]; } else { echo ''; }?>" name="7c2_totalinput_admissible[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                   <?php if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 0))
								 {  
								   if($i==0){
									 ?>
                                    <td>
									 <a class="addMoreInvoice add-table7c2"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
								 </td><?php } else { ?>
                               <td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td>								 
								 <?php } } ?>	                  
								</tr><?php } } else { ?>
								<tr>                      				  
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								<?php } ?>                                                 
                              
                                     
							
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
								<?php
								if(!empty($returndata1[0]->totalinvoice) && ($returndata1[0]->totalinvoice > 0))
								{
								
			                    $d7d_description=(explode(",",$d7d_description));
								$d7d_unit=(explode(",",$d7d_unit));
								$d7d_qty=(explode(",",$d7d_qty));
								$d7d_value=(explode(",",$d7d_value));
								$d7d_vatentry_taxpad=(explode(",",$d7d_vatentry_taxpad));
								$start='';
								if(sizeof($d7d_description) > 1)
								{
									$start = $d7d_description;
									
								}
								elseif(sizeof($d7d_unit) > 1)
								{
									 $start = $d7d_unit;
								}
								elseif(sizeof($d7d_qty) > 1)
								{
									 $start = $d7d_qty;
									
								}
								elseif(sizeof($d7d_value) > 1)
								{
									$start = $d7d_value;
								}
								elseif(sizeof($d7d_vatentry_taxpad) > 1)
								{
									$start = $d7d_vatentry_taxpad;
								}														
								else{
									$start = $d7d_description;
								}   
						
							  for($i=0;$i < sizeof($start); $i++) {
								 
                           ?>
		
                                <tr>    
                                							 
								   <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($d7d_description[$i])) { echo $d7d_description[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($d7d_description[$i])) { echo $d7d_description[$i]; } else { echo ''; } ?>" name="7d_description[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($d7d_unit[$i])) { echo $d7d_unit[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($d7d_unit[$i])) { echo $d7d_unit[$i]; } else { echo ''; } ?>" name="7d_unit[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($d7d_qty[$i])) { echo $d7d_qty[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($d7d_qty[$i])) { echo $d7d_qty[$i]; } else { echo ''; } ?>" name="7d_qty[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($d7d_value[$i])) { echo $d7d_value[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($d7d_value[$i])) { echo $d7d_value[$i]; } else { echo ''; } ?>" name="7d_value[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($d7d_vatentry_taxpad[$i])) { echo $d7d_vatentry_taxpad[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($d7d_vatentry_taxpad[$i])) { echo $d7d_vatentry_taxpad[$i]; } else { echo ''; } ?>" name="7d_vatentry_taxpad[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                     <?php if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 0))
								 { 
									   if($i==0){
									 ?>
                                    <td>
									 <a class="addMoreInvoice add-table7d"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
								 </td><?php } else { ?>
                               <td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td>								 
								 <?php } } ?>	             
								</tr><?php } } else { ?>
								 <tr>    
                                							 
								   <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								<?php } ?>								
             
                              
                                     
							
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
                                
                                <tbody><?php
								if(!empty($returndata1[0]->totalinvoice) && ($returndata1[0]->totalinvoice > 0))
								{
								
			                    $a8registration_no=(explode(",",$a8registration_no));
								$a8taxperiod_lastreturn=(explode(",",$a8taxperiod_lastreturn));
								$a8dateoffilling_return=(explode(",",$a8dateoffilling_return));
								$a8balanceeligible_cenvat_credit=(explode(",",$a8balanceeligible_cenvat_credit));
								$a8gstnof_receiver=(explode(",",$a8gstnof_receiver));
								$a8distributionno=(explode(",",$a8distributionno));
								$a8distributiondate=(explode(",",$a8distributiondate));
								$a8itcofcentral=(explode(",",$a8itcofcentral));
								$start='';
								if(sizeof($a8registration_no) > 1)
								{
									$start = $a8registration_no;
									
								}
								elseif(sizeof($a8taxperiod_lastreturn) > 1)
								{
									 $start = $a8taxperiod_lastreturn;
								}
								elseif(sizeof($a8dateoffilling_return) > 1)
								{
									 $start = $a8dateoffilling_return;
									
								}
								elseif(sizeof($a8balanceeligible_cenvat_credit) > 1)
								{
									$start = $a8balanceeligible_cenvat_credit;
								}
								elseif(sizeof($a8gstnof_receiver) > 1)
								{
									$start = $a8gstnof_receiver;
								}
								elseif(sizeof($a8distributionno) > 1)
								{
									$start = $a8distributionno;
								}
								elseif(sizeof($a8distributiondate) > 1)
								{
									$start = $a8distributiondate;
								}
								elseif(sizeof($a8itcofcentral) > 1)
								{
									$start = $a8itcofcentral;
								}
								
								else{
									$start = $a8registration_no;
								}     
						
							  for($i=0;$i < sizeof($start); $i++) {
								 
                           ?>
		
                                <tr>
                                     <td></td>                     
								 
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a8registration_no[$i])) { echo $a8registration_no[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($a8registration_no[$i])) { echo $a8registration_no[$i]; } else { echo ''; } ?>" name="8registration_no[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a8taxperiod_lastreturn[$i])) { echo $a8taxperiod_lastreturn[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($a8taxperiod_lastreturn[$i])) { echo $a8taxperiod_lastreturn[$i]; } else { echo ''; } ?>" name="8taxperiod_lastreturn[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a8dateoffilling_return[$i])) { echo $a8dateoffilling_return[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($a8dateoffilling_return[$i])) { echo $a8dateoffilling_return[$i]; } else { echo ''; } ?>" name="8dateoffilling_return[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a8balanceeligible_cenvat_credit[$i])) { echo $a8balanceeligible_cenvat_credit[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($a8balanceeligible_cenvat_credit[$i])) { echo $a8balanceeligible_cenvat_credit[$i]; } else { echo ''; } ?>" name="8balanceeligible_cenvat_credit[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a8gstnof_receiver[$i])) { echo $a8gstnof_receiver[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($a8gstnof_receiver[$i])) { echo $a8gstnof_receiver[$i]; } else { echo ''; } ?>" name="8gstnof_receiver[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a8distributionno[$i])) { echo $a8distributionno[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($a8distributionno[$i])) { echo $a8distributionno[$i]; } else { echo ''; } ?>" name="8distributionno[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a8distributiondate[$i])) { echo $a8distributiondate[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($a8distributiondate[$i])) { echo $a8distributiondate[$i]; } else { echo ''; } ?>" name="8distributiondate[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a8itcofcentral[$i])) { echo $a8itcofcentral[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($a8itcofcentral[$i])) { echo $a8itcofcentral[$i]; } else { echo ''; } ?>" name="8itcofcentral[]" class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                    <?php if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 0))
								 { 
									 if($i==0){
									 ?>
                                    <td>
									 <a class="addMoreInvoice add-table8"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
								 </td><?php } else { ?>
                               <td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td>								 
								 <?php } } ?>	                
								</tr><?php } } else { ?>
								<tr>
                                     <td></td>                     
								 
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								<?php } ?>								
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
								  <?php
								  if(!empty($returndata1[0]->totalinvoice) && ($returndata1[0]->totalinvoice > 0))
								{
								
								 $a9a1challan_no=(explode(",",$a9a1challan_no));
								$a9a1challan_date=(explode(",",$a9a1challan_date));
								$a9a1typeof_goods=(explode(",",$a9a1typeof_goods));
								$a9a1_hsn=(explode(",",$a9a1_hsn));
								$a9a1_description=(explode(",",$a9a1_description));
								$a9a1_unit=(explode(",",$a9a1_unit));
								$a9a1_quantity=(explode(",",$a9a1_quantity));
								$a9a1_value=(explode(",",$a9a1_value));
			                   $start='';
								if(sizeof($a9a1challan_no) > 1)
								{
									$start = $a9a1challan_no;
									
								}
								elseif(sizeof($a9a1challan_date) > 1)
								{
									 $start = $a9a1challan_date;
								}
								elseif(sizeof($a9a1typeof_goods) > 1)
								{
									 $start = $a9a1typeof_goods;
									
								}
								elseif(sizeof($a9a1_hsn) > 1)
								{
									$start = $a9a1_hsn;
								}
								elseif(sizeof($a9a1_description) > 1)
								{
									$start = $a9a1_description;
								}
								elseif(sizeof($a9a1_unit) > 1)
								{
									$start = $a9a1_unit;
								}
								elseif(sizeof($a9a1_quantity) > 1)
								{
									$start = $a9a1_quantity;
								}
								elseif(sizeof($a9a1_value) > 1)
								{
									$start = $a9a1_value;
								}
								
								else{
									$start = $a9a1challan_no;
								}     
								   
						
							  for($i=0;$i < sizeof($start); $i++) {
								
                           ?>
		
                                <tr>
                              
                                <td></td>
								  
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a9a1challan_no[$i])) { echo $a9a1challan_no[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($a9a1challan_no[$i])) { echo $a9a1challan_no[$i]; } else { echo ''; } ?>" name="9a1challan_no[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a9a1challan_date[$i])) { echo $a9a1challan_date[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($a9a1challan_date[$i])) { echo $a9a1challan_date[$i]; } else { echo ''; } ?>" name="9a1challan_date[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a9a1typeof_goods[$i])) { echo $a9a1typeof_goods[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($a9a1typeof_goods[$i])) { echo $a9a1typeof_goods[$i]; } else { echo ''; } ?>" name="9a1typeof_goods[]" class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a9a1_hsn[$i])) { echo $a9a1_hsn[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($a9a1_hsn[$i])) { echo $a9a1_hsn[$i]; } else { echo ''; } ?>" name="9a1_hsn[]" class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a9a1_description[$i])) { echo $a9a1_description[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($a9a1_description[$i])) { echo $a9a1_description[$i]; } else { echo ''; } ?>" name="9a1_description[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a9a1_unit[$i])) { echo $a9a1_unit[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($a9a1_unit[$i])) { echo $a9a1_unit[$i]; } else { echo ''; } ?>" name="9a1_unit[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a9a1_quantity[$i])) { echo $a9a1_quantity[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($a9a1_quantity[$i])) { echo $a9a1_quantity[$i]; } else { echo ''; } ?>" name="9a1_quantity[]"  class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a9a1_value[$i])) { echo $a9a1_value[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($a9a1_value[$i])) { echo $a9a1_value[$i]; } else { echo ''; } ?>" name="9a1_value[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                  <?php if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 0))
								 { 
								   if($i==0){
									 ?>
                                    <td>
									 <a class="addMoreInvoice add-table9a"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
								 </td><?php } else { ?>
                               <td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td>								 
								 <?php } } ?>	                     
								</tr><?php } } else { ?>
								 <tr>
                              
                                <td></td>
								  
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								<?php } ?>								
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
								  <?php
								  if(!empty($returndata1[0]->totalinvoice) && ($returndata1[0]->totalinvoice > 0))
								{
								
			                    $b9b1challan_no=(explode(",",$b9b1challan_no));
								$b9b1challan_date=(explode(",",$b9b1challan_date));
								$b9b1typeof_goods=(explode(",",$b9b1typeof_goods));
								$b9b1_hsn=(explode(",",$b9b1_hsn));								
								$b9b1_description=(explode(",",$b9b1_description));
								$b9b1_unit=(explode(",",$b9b1_unit));
								$b9b1_quantity=(explode(",",$b9b1_quantity));
								$b9b1_value=(explode(",",$b9b1_value));
								$start='';
								$flag=0;
                               if(sizeof($b9b1challan_no) > 1)
								{
									 $start = $b9b1challan_no;
									
									
								}
								elseif(sizeof($b9b1challan_date) > 1)
								{
									 $start = $b9b1challan_date;
									 	
								}
								elseif(sizeof($b9b1typeof_goods) > 1)
								{
									 $start = $b9b1typeof_goods;
									 
									
								}
								elseif(sizeof($b9b1_hsn) > 1)
								{
									$start = $b9b1_hsn;
									
								}
								elseif(sizeof($b9b1_description) > 1)
								{
									$start = $b9b1_description;
									
								}
								elseif(sizeof($b9b1_unit) > 1)
								{
									$start = $b9b1_unit;
									
								}
								elseif(sizeof($b9b1_quantity) > 1)
								{
									$start = $b9b1_quantity;
									
								}
								elseif(sizeof($b9b1_value) > 1)
								{
									$start = $b9b1_value;
									
								}
								
								else{
									$start = $b9b1challan_no;
										
									
								}     
								   
						 
							  for($i=0;$i < sizeof($start); $i++) {
								
								
                           ?>
	                            <tr>
                                                             
								 <td></td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($b9b1challan_no[$i])) { echo $b9b1challan_no[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
								?>								 
								  <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($b9b1challan_no[$i])) { echo $b9b1challan_no[$i]; } else { echo ''; } ?>" name="9b1challan_no[]"
								class="form-control"  placeholder="" />    
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($b9b1challan_date[$i])) { echo $b9b1challan_date[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 { ?>
									 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($b9b1challan_date[$i])) { echo $b9b1challan_date[$i]; } else { echo ''; } ?>" name="9b1challan_date[]"
								class="form-control"  placeholder="" />  
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($b9b1typeof_goods[$i])) { echo $b9b1typeof_goods[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
								 ?>
								   <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($b9b1typeof_goods[$i])) { echo $b9b1typeof_goods[$i]; } else { echo ''; } ?>" name="9b1typeof_goods[]"
								class="form-control"  placeholder="" />   
								 <?php } ?>
								
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($b9b1_hsn[$i])) { echo $b9b1_hsn[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								  <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($b9b1_hsn[$i])) { echo $b9b1_hsn[$i]; } else { echo ''; } ?>" name="9b1_hsn[]"
								class="form-control"  placeholder="" />   
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($b9b1_description[$i])) { echo $b9b1_description[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								   <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($b9b1_description[$i])) { echo $b9b1_description[$i]; } else { echo ''; } ?>" name="9b1_description[]"
								class="form-control"  placeholder="" />   
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($b9b1_unit[$i])) { echo $b9b1_unit[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								  <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($b9b1_unit[$i])) { echo $b9b1_unit[$i]; } else { echo ''; } ?>" name="9b1_unit[]"
								class="form-control"  placeholder="" />  
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($b9b1_quantity[$i])) { echo $b9b1_quantity[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								  <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($b9b1_quantity[$i])) { echo $b9b1_quantity[$i]; } else { echo ''; } ?>" name="9b1_quantity[]"
								class="form-control"  placeholder="" />  
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($b9b1_value[$i])) { echo $b9b1_value[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								  <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($b9b1_value[$i])) { echo $b9b1_value[$i]; } else { echo ''; } ?>" name="9b1_value[]"
								class="form-control"  placeholder="" />  
								 <?php } ?>
                                 </td>
                                   <?php if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 0))
								 { 
								  if($i==0){
									 ?>
                                    <td>
									 <a class="addMoreInvoice add-table9b"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
								 </td><?php } else { ?>
                               <td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td>								 
								 <?php } } ?>	                      
						 </tr><?php } } else {  ?>
								 <tr>
                                                             
								 <td></td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php echo $returndata[0]->central_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);"  name="9b1challan_no[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php echo $returndata[0]->state_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);"  name="9b1challan_date[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);"  name="9b1typeof_goods[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);"  name="9b1_hsn[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);"  name="9b1_quantity[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);"  name="9b1_value[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" 
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
								<?php } ?>								
                                </tbody>
                            </table>
                          </div>
						   <div class="greyheading">10.Details of goods held in stock as agent of behalf of the principal under section 142(14) of the SGST Act</div>
						  <div class="greyheading">a.Details of goods held as agent on behalf of the principal</div>
					     <div class="tableresponsive">
						 <table  class="table  tablecontent tablecontent2 bordernone" id="table10a">
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
								<?php
								if(!empty($returndata1[0]->totalinvoice) && ($returndata1[0]->totalinvoice > 0))
								{
								$a10a_gstn=(explode(",",$a10a_gstn));
								$a10a_description=(explode(",",$a10a_description));
								$a10a_unit=(explode(",",$a10a_unit));
								$a10a_quantity=(explode(",",$a10a_quantity));
								$a10a_value=(explode(",",$a10a_value));
								$a10a_inputtax=(explode(",",$a10a_inputtax));
		                        $start='';
                               if(sizeof($a10a_gstn) > 1)
								{
									$start = $a10a_gstn;
									
								}
								elseif(sizeof($a10a_description) > 1)
								{
									 $start = $a10a_description;
								}
								elseif(sizeof($a10a_unit) > 1)
								{
									 $start = $a10a_unit;
									
								}
								elseif(sizeof($a10a_quantity) > 1)
								{
									$start = $a10a_quantity;
								}
								elseif(sizeof($a10a_value) > 1)
								{
									$start = $a10a_value;
								}
								elseif(sizeof($a10a_inputtax) > 1)
								{
									$start = $a10a_inputtax;
								}						
								
								else{
									$start = $a10a_gstn;
								}     
			
								   
						
							  for($i=0;$i < sizeof($start); $i++) {
								 
                           ?>
			
		
								 
                                <tr>                     
                           
								 
								<td></td>
								   <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a10a_gstn[$i])) { echo $a10a_gstn[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($a10a_gstn[$i])) { echo $a10a_gstn[$i]; } else { echo ''; }?>" name="10a_gstn[]" 
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a10a_description[$i])) { echo $a10a_description[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($a10a_description[$i])) { echo $a10a_description[$i]; } else { echo ''; } ?>" name="10a_description[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a10a_unit[$i])) { echo $a10a_unit[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($a10a_unit[$i])) { echo $a10a_unit[$i]; } else { echo ''; } ?>" name="10a_unit[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a10a_quantity[$i])) { echo $a10a_quantity[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="10a_quantity[]" value="<?php if(isset($a10a_quantity[$i])) { echo $a10a_quantity[$i]; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a5_registration_no[$i])) { echo $a5_registration_no[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="10a_value[]" value="<?php if(isset($a10a_value[$i])) { echo $a10a_value[$i]; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a10a_inputtax[$i])) { echo $a10a_inputtax[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="10a_inputtax[]" value="<?php if(isset($a10a_inputtax[$i])) { echo $a10a_inputtax[$i]; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								 <?php if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 0))
								 { 
								 if($i==0){
									 ?>
                                    <td>
									 <a class="addMoreInvoice add-table10a"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
								 </td><?php } else { ?>
                               <td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td>								 
								 <?php } } ?>	
                                                      
								</tr> <?php } } else { ?>
								 <tr>                     
                           
								 
								<td></td>
								   <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="10a_gstn[]" 
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="10a_description[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="10a_unit[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="10a_quantity[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="10a_value[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="10a_inputtax[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								 <td> <td>
									 <a class="addMoreInvoice add-table10a"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
										</td>  </td>
                                                      
                                </tr>	   
								<?php } ?>								
                                </tbody>
                            </table>
                          </div>
						  <div class="greyheading">b.Details of goods held by the agent</div>
					     <div class="tableresponsive">
						 <table  class="table  tablecontent tablecontent2 bordernone" id="table10b" >
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
								<?php
								if(!empty($returndata1[0]->totalinvoice) && ($returndata1[0]->totalinvoice > 0))
								{
								$b10b_gstn=(explode(",",$b10b_gstn));
								$b10b_description=(explode(",",$b10b_description));
								$b10b_unit=(explode(",",$b10b_unit));
								$b10b_quantity=(explode(",",$b10b_quantity));
								$b10b_value=(explode(",",$b10b_value));
								$b10b_inputtax=(explode(",",$b10b_inputtax));
								$start='';
                               if(sizeof($b10b_gstn) > 1)
								{
									$start = $b10b_gstn;
									
								}
								elseif(sizeof($b10b_description) > 1)
								{
									 $start = $b10b_description;
								}
								elseif(sizeof($b10b_unit) > 1)
								{
									 $start = $b10b_unit;
									
								}
								elseif(sizeof($b10b_quantity) > 1)
								{
									$start = $b10b_quantity;
								}
								elseif(sizeof($b10b_value) > 1)
								{
									$start = $b10b_value;
								}
								elseif(sizeof($b10b_inputtax) > 1)
								{
									$start = $b10b_inputtax;
								}						
								
								else{
									$start = $b10b_description;
								}     	
														   
						
							  for($i=0;$i < sizeof($start); $i++) {
								 
                           ?>
								 
                                <tr>                     
                                
								 
								
								 <td></td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($b10b_gstn[$i])) { echo $b10b_gstn[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="10b_gstn[]" value="<?php  if(isset($b10b_gstn[$i])) { echo $b10b_gstn[$i]; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($b10b_description[$i])) { echo $b10b_description[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($b10b_description[$i])) { echo $b10b_description[$i]; } else { echo ''; } ?>" name="10b_description[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($b10b_unit[$i])) { echo $b10b_unit[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($b10b_unit[$i])) { echo $b10b_unit[$i]; } else { echo ''; } ?>" name="10b_unit[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($b10b_quantity[$i])) { echo $b10b_quantity[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($b10b_quantity[$i])) { echo $b10b_quantity[$i]; } else { echo ''; } ?>" name="10b_quantity[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($b10b_value[$i])) { echo $b10b_value[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($b10b_value[$i])) { echo $b10b_value[$i]; } else { echo ''; } ?>" name="10b_value[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($b10b_inputtax[$i])) { echo $b10b_inputtax[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="10b_inputtax[]" value="<?php if(isset($b10b_inputtax[$i])) { echo $b10b_inputtax[$i]; } else { echo ''; } ?>"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                   <?php if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 0))
								 { 
									  if($i==0){
									 ?>
                                    <td>
									 <a class="addMoreInvoice add-table10b"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
								 </td><?php } else { ?>
                               <td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td>								 
								 <?php } } ?>	                 
								</tr><?php } } else { ?>
								 <tr>                     
                                
								 
								
								 <td></td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="10b_gstn[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="10b_description[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="10b_unit[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="10b_quantity[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="10b_value[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="10b_inputtax[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                     <td>
									 <a class="addMoreInvoice add-table10b"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
										</td>                    
                                </tr>
								<?php } ?>			                    
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
                                
                                <tbody><?php
								if(!empty($returndata1[0]->totalinvoice) && ($returndata1[0]->totalinvoice > 0))
								{
							    $a11aregistration_no=(explode(",",$a11aregistration_no));
								$a11aservicetax_no=(explode(",",$a11aservicetax_no));
								$a11ainvoice_documentno=(explode(",",$a11ainvoice_documentno));
								$a11ainvoice_document_date=(explode(",",$a11ainvoice_document_date));
								$a11atax_paid=(explode(",",$a11atax_paid));
								$a11avatpaid_sgst=(explode(",",$a11avatpaid_sgst));
								$start='';
                               if(sizeof($a11aregistration_no) > 1)
								{
									$start = $a11aregistration_no;
									
								}
								elseif(sizeof($a11aservicetax_no) > 1)
								{
									 $start = $a11aservicetax_no;
								}
								elseif(sizeof($a11ainvoice_documentno) > 1)
								{
									 $start = $a11ainvoice_documentno;
									
								}
								elseif(sizeof($a11ainvoice_document_date) > 1)
								{
									$start = $a11ainvoice_document_date;
								}
								elseif(sizeof($a11atax_paid) > 1)
								{
									$start = $a11atax_paid;
								}
								elseif(sizeof($a11avatpaid_sgst) > 1)
								{
									$start = $a11avatpaid_sgst;
								}						
								
								else{
									$start = $a11aregistration_no;
								}     	
								   
						
							  for($i=0;$i < sizeof($start); $i++) {
								 
                           ?>
								 
                                <tr>  <td></td>                   
                                
								 
								
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a11aregistration_no[$i])) { echo $a11aregistration_no[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($a11aregistration_no[$i])) { echo $a11aregistration_no[$i]; } else { echo ''; } ?>" name="11aregistration_no[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a11aservicetax_no[$i])) { echo $a11aservicetax_no[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($a11aservicetax_no[$i])) { echo $a11aservicetax_no[$i]; } else { echo ''; } ?>" name="11aservicetax_no[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a11ainvoice_documentno[$i])) { echo $a11ainvoice_documentno[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($a11ainvoice_documentno[$i])) { echo $a11ainvoice_documentno[$i]; } else { echo ''; } ?>" name="11ainvoice_documentno[]" class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a11ainvoice_document_date[$i])) { echo $a11ainvoice_document_date[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($a11ainvoice_document_date[$i])) { echo $a11ainvoice_document_date[$i]; } else { echo ''; } ?>" name="11ainvoice_document_date[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a11atax_paid[$i])) { echo $a11atax_paid[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($a11atax_paid[$i])) { echo $a11atax_paid[$i]; } else { echo ''; } ?>" name="11atax_paid[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a11avatpaid_sgst[$i])) { echo $a11avatpaid_sgst[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($a11avatpaid_sgst[$i])) { echo $a11avatpaid_sgst[$i]; } else { echo ''; } ?>" name="11avatpaid_sgst[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> 
                                 <?php if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 0))
								 { 
								  if($i==0){
									 ?>
                                    <td>
									 <a class="addMoreInvoice add-table11a"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
								 </td><?php } else { ?>
                               <td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td>								 
								 <?php } } ?>	                      
								</tr><?php } } else { ?>
								<tr>  <td></td>                   
                                
								 
								
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="11ainvoice_documentno[]" class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php echo $returndata[0]->cess_tax_value_supplya; ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" name="11atax_paid[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								<?php } ?>			                    
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
                                
                                <tbody><?php
								if(!empty($returndata1[0]->totalinvoice) && ($returndata1[0]->totalinvoice > 0))
								{
								$a12a_document_no=(explode(",",$a12a_document_no));
								$a12a_document_date=(explode(",",$a12a_document_date));
								$a12a_gstinno_receipient=(explode(",",$a12a_gstinno_receipient));
								$a12a_name_receipient=(explode(",",$a12a_name_receipient));
								$a12a_hsn=(explode(",",$a12a_hsn));
								$a12a_description=(explode(",",$a12a_description));
								$a12a_unit=(explode(",",$a12a_unit));
								$a12a_quantity=(explode(",",$a12a_quantity));
								$a12a_value=(explode(",",$a12a_value));
								$start='';
                               if(sizeof($a12a_document_no) > 1)
								{
									$start = $a12a_document_no;
									
								}
								elseif(sizeof($a12a_document_date) > 1)
								{
									 $start = $a12a_document_date;
								}
								elseif(sizeof($a12a_gstinno_receipient) > 1)
								{
									 $start = $a12a_gstinno_receipient;
									
								}
								elseif(sizeof($a12a_name_receipient) > 1)
								{
									$start = $a12a_name_receipient;
								}
								elseif(sizeof($a12a_hsn) > 1)
								{
									$start = $a12a_hsn;
								}
								elseif(sizeof($a12a_description) > 1)
								{
									$start = $a12a_description;
								}
                                elseif(sizeof($a12a_unit) > 1)
								{
									$start = $a12a_unit;
								}
                               elseif(sizeof($a12a_quantity) > 1)
								{
									$start = $a12a_quantity;
								}
                               elseif(sizeof($a12a_value) > 1)
								{
									$start = $a12a_value;
								}									
								
								else{
									$start = $a12a_document_no;
								}	
								   
						
							  for($i=0;$i < sizeof($start); $i++) {
								 
                           ?>
		
								 
                                <tr>                     
                                <td></td>
								 <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a12a_document_no[$i])) { echo $a12a_document_no[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($a12a_document_no[$i])) { echo $a12a_document_no[$i]; } else { echo ''; } ?>"
			 name="12a_document_no[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td><td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a12a_document_date[$i])) { echo $a12a_document_date[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($a12a_document_date[$i])) { echo $a12a_document_date[$i]; } else { echo ''; } ?>" name="12a_document_date[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a12a_gstinno_receipient[$i])) { echo $a12a_gstinno_receipient[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($a12a_gstinno_receipient[$i])) { echo $a12a_gstinno_receipient[$i]; } else { echo ''; } ?>" name="12a_gstinno_receipient[]" class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
								  <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a12a_name_receipient[$i])) { echo $a12a_name_receipient[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($a12a_name_receipient[$i])) { echo $a12a_name_receipient[$i]; } else { echo ''; } ?>" name="12a_name_receipient[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a12a_hsn[$i])) { echo $a12a_hsn[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($a12a_hsn[$i])) { echo $a12a_hsn[$i]; } else { echo ''; } ?>" name="12a_hsn[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a12a_description[$i])) { echo $a12a_description[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($a12a_description[$i])) { echo $a12a_description[$i]; } else { echo ''; } ?>" name="12a_description[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a12a_unit[$i])) { echo $a12a_unit[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($a12a_unit[$i])) { echo $a12a_unit[$i]; } else { echo ''; } ?>" name="12a_unit[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a12a_quantity[$i])) { echo $a12a_quantity[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($a12a_quantity[$i])) { echo $a12a_quantity[$i]; } else { echo ''; } ?>" name="12a_quantity[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td> <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
								 {
									 ?>
									 <label><?php if(isset($a12a_value[$i])) { echo $a12a_value[$i]; } else { echo ''; } ?><span class="starred"></span></label>
								 <?php } else
								 {
									 ?>
								 <input type="text" maxlength="15" onKeyPress="return  isNumberKey(event,this);" value="<?php if(isset($a12a_value[$i])) { echo $a12a_value[$i]; } else { echo ''; } ?>" name="12a_value[]"
 class="form-control"  placeholder="" /> 
								 <?php } ?>
                                 </td>
                                <?php if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 0))
								 { 
								 if($i==0){
									 ?>
                                    <td>
									 <a class="addMoreInvoice add-table12a"  href="javascript:void(0)">
									<div class="tooltip2">
										<i class="fa fa-plus-circle addicon"></i>
										<span class="tooltiptext">Add More</span>
									</div>
								</a>
								 </td><?php } else { ?>
                               <td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td>								 
								 <?php } } ?>	                  
								</tr><?php } } else { ?>
								 <tr>                     
                                <td></td>
								 <td>
								 <?php
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								  if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
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
								<?php } ?>								
                                </tbody>
                            </table>
                          </div>
                        
							
                         <div class="tableresponsive">
                           
							<?php
							 if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 1))
							{
								if($returndata[0]->final_submit == 0)
							{
								?>
                                <div class="adminformbxsubmit" style="width:100%;"> 
                            <div class="tc">
                               <input type="button" value="<?php echo ucfirst('Edit'); ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=transition_gstr_submit&returnmonth=".$_REQUEST["returnmonth"]; ?>';" class="btn btn-success" class="redbtn marlef10"/>
							    <input type='submit' class="btn btn-danger" name='cleardata' value='clear data' id='cleardata'>
							  <input type='submit' class="btn btn-success" name='finalsubmit' value='final submit' id='finalsubmit'>
							  <input type='hidden' name="returnid" id="returnid" value="<?php echo $returndata[0]->return_id; ?>" />
									
                               
                            </div>
                              </div>
                            <?php } }
							else
							{
								?>
								<div>
								<?php if($returndata[0]->terms_condition==1) {
									?>
								<input type="checkbox" class='form' value="1" checked name="accept" />
								<?php } else { ?>
								<input type="checkbox" class='form' value="1"  name="accept" />
								<?php } ?>
								
								I here by solemnly affirm and declare that the information given herein above is true and correct to the best of my knowledge and belief and nothing has been concealed thereform
								</div>
								  <div class="adminformbxsubmit" style="width:100%;"> 
                            <div class="tc">
                                <input type='submit' class="btn btn-success" name='submit' value='submit' id='submit'>
								<?php 
								if(($returndata[0]->totalinvoice > 0) && ($returndata[0]->final_submit == 0))
							{?>
							  <input type='submit' class="btn btn-success" name='finalsubmit' value='final submit' id='finalsubmit'>
						
							<?php } ?>
								
                               
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
       
			
		    
            var markup = "<tr><td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='5bcform_tin_issuer[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='5bcform_nameof_issuer[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='5bcform_no_of_item[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='5bcform_amount[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='5bcform_applicable_vat_rate[]'/></td><td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td></tr>";
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
       
			
		    
            var markup = "<tr><td></td><td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='6ainvoice_document_no[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='6ainvoice_document_date[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='6asupplier_registration_no[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='6arecipients_registration_no[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='6a_value[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='6a_ed_cvd[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='6a_sad[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='6a_totaleligible_cenvat[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='6a_totalcenvat_credit1[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='6a_totalcenvat_credit_unavailed[]'/></td><td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td></tr>";
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
       
			
		    
            var markup = "<tr><td></td><td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='11aregistration_no[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='11aservicetax_no[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='11ainvoice_documentno[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='11ainvoice_document_date[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='11atax_paid[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='11avatpaid_sgst[]'/></td><td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td></tr>";
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
		
        $(".add-table10a").click(function(){
       
			
		    
            var markup = "<tr><td></td><td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='10a_gstn[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='10a_description[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='10a_unit[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='10a_quantity[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='10a_value[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='10a_inputtax[]'/></td><td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td></tr>";
          // $("table tbody").append(markup);
		   $('#table10a').append(markup);
        });
		$('body').delegate('.del','click',function(){
			$(this).closest('tr').remove();
		});
        
		 
        
    }); 
</script>
<script type="text/javascript">
    $(document).ready(function(){
		
        $(".add-table10b").click(function(){
       
			
		    
            var markup = "<tr><td></td><td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='10b_gstn[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='10b_description[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='10b_unit[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='10b_quantity[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='10b_value[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='10b_inputtax[]'/></td><td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td></tr>";
          // $("table tbody").append(markup);
		   $('#table10b').append(markup);
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
            document.form2.action = '<?php echo PROJECT_URL; ?>/?page=transition_gstr&returnmonth=<?php echo $returnmonth; ?>';
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
            document.form2.action = '<?php echo PROJECT_URL; ?>/?page=transition_gstr&returnmonth=<?php echo $returnmonth; ?>';
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