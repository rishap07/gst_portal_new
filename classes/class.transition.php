<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

final class transition extends validation {
    
    function __construct() {
        parent::__construct();
    }
    
   public function deleteGstrTransition()
   {
		$return_id =   isset($_POST['returnid']) ? $_POST['returnid'] : '';
		$userid = $_SESSION['user_detail']['user_id'];
		 if($this->update(TAB_PREFIX.'client_return_gstr3b', array('is_deleted' => 1), array('return_id' => $return_id)))
		 {
		 $this->setSuccess('GSTR3B Data clear successfully');
		   $this->logMsg("GSTR3B ClearData Financial month :".$this->sanitize($_GET['returnmonth']),"gstr_3b");
  
		 return true;
		 }
   }
    public function gstTransitionData()
	{
		$dataArr = array();
		$data = array();
		$data['5a_registration_no']='';
		$data['5a_taxperiod_last_return']='';
		$data['5a_dateoffilling_return']='';
		$data['5a_balance_cenvat_credit']='';
		$data['5a_cenvat_credit_admissible']='';
		$data['5bcform_tin_issuer']='';
		$data['5bcform_nameof_issuer']='';
		$data['5bcform_no_of_item']='';
		$data['5bcform_amount']='';
		$data['5bcform_applicable_vat_rate']='';
		$data['5bfform_tin_issuer']='';
		$data['5bfform_nameof_issuer']='';
		$data['5bfform_no_of_form']='';
		$data['5bfform_amount']='';
		$data['5bfform_applicable_vat_rate']='';
		$data['5bhiform_tin_issuer']='';
		$data['5bhiform_nameof_issuer']='';
		$data['5bhiform_no_of_form']='';
		$data['5bhiform_amount']='';
		$data['5bhiform_applicable_vat_rate']='';
		$data['6ainvoice_document_no']='';
		$data['6ainvoice_document_date']='';
		$data['6asupplier_registration_no']='';
		$data['6arecipients_registration_no']='';
		$data['6a_value']='';
		$data['6a_ed_cvd']='';
		$data['6a_sad']='';
		$data['6a_totaleligible_cenvat']='';
		$data['6a_totalcenvat_credit']='';
		$data['6a_totalcenvat_credit_unavailed']='';
		$data['6binvoice_document_no']='';
		$data['6binvoice_document_date']='';
		$data['6bsupplier_registration_no']='';
		$data['6breceipients_registration_no']='';
		$data['6b_value']='';
		$data['6b_taxpaid_vat']='';
		$data['6b_totaleligible_vat']='';
		$data['6b_totalvat_creditavailed']='';
		$data['6b_totalvat_creditunavailed']='';
				
		 if(!empty($_POST['5a_registration_no'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['5a_registration_no'] as $selected){
			 
             $data['5a_registration_no'] = $data['5a_registration_no'].$selected.',';
			
			} 
			$data['5a_registration_no'] = rtrim($data['5a_registration_no'],",");
			}
			 if(!empty($_POST['5a_taxperiod_last_return'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['5a_taxperiod_last_return'] as $selected){
			 
             $data['5a_taxperiod_last_return'] = $data['5a_taxperiod_last_return'].$selected.',';
			
			} 
			$data['5a_taxperiod_last_return'] = rtrim($data['5a_taxperiod_last_return'],",");
			}
			 if(!empty($_POST['5a_dateoffilling_return'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['5a_dateoffilling_return'] as $selected){
			  if(!empty($selected)){
             $data['5a_dateoffilling_return'] = $data['5a_dateoffilling_return'].$selected.',';
			  }
			} 
			$data['5a_dateoffilling_return']=rtrim($data['5a_dateoffilling_return'],",");
			}
			if(!empty($_POST['5a_balance_cenvat_credit'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['5a_balance_cenvat_credit'] as $selected){
			 if(!empty($selected))
			 {
             $data['5a_balance_cenvat_credit'] = $data['5a_balance_cenvat_credit'].$selected.',';
			 }
			} 
			$data['5a_balance_cenvat_credit'] =rtrim($data['5a_balance_cenvat_credit'],",");
			}
			if(!empty($_POST['5a_cenvat_credit_admissible'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['5a_cenvat_credit_admissible'] as $selected){
			 
             $data['5a_cenvat_credit_admissible'] = $data['5a_cenvat_credit_admissible'].$selected.',';
			
			}
			
			$data['5a_cenvat_credit_admissible'] = rtrim($data['5a_cenvat_credit_admissible'], ",");
			
			}
			//code for 5bcform 
			if(!empty($_POST['5bcform_tin_issuer'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['5bcform_tin_issuer'] as $selected){
			 
             $data['5bcform_tin_issuer'] = $data['5bcform_tin_issuer'].$selected.',';
			
			}
			
			$data['5bcform_tin_issuer'] = rtrim($data['5bcform_tin_issuer'], ",");
			
			}
			if(!empty($_POST['5bcform_nameof_issuer'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['5bcform_nameof_issuer'] as $selected){
			 
             $data['5bcform_nameof_issuer'] = $data['5bcform_nameof_issuer'].$selected.',';
			
			}
			
			$data['5bcform_nameof_issuer'] = rtrim($data['5bcform_nameof_issuer'], ",");
			
			}
			if(!empty($_POST['5bcform_no_of_item'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['5bcform_no_of_item'] as $selected){
			 
             $data['5bcform_no_of_item'] = $data['5bcform_no_of_item'].$selected.',';
			
			}
			
			$data['5bcform_no_of_item'] = rtrim($data['5bcform_no_of_item'], ",");
			
			}
			if(!empty($_POST['5bcform_amount'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['5bcform_amount'] as $selected){
			 
             $data['5bcform_amount'] = $data['5bcform_amount'].$selected.',';
			
			}
			
			$data['5bcform_amount'] = rtrim($data['5bcform_amount'], ",");
			
			}
			if(!empty($_POST['5bcform_applicable_vat_rate'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['5bcform_applicable_vat_rate'] as $selected){
			 
             $data['5bcform_applicable_vat_rate'] = $data['5bcform_applicable_vat_rate'].$selected.',';
			
			}
			
			$data['5bcform_applicable_vat_rate'] = rtrim($data['5bcform_applicable_vat_rate'], ",");
			
			}
			
			//end here
			//code for 5bform start here
			if(!empty($_POST['5bfform_tin_issuer'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['5bfform_tin_issuer'] as $selected){
			 
             $data['5bfform_tin_issuer'] = $data['5bfform_tin_issuer'].$selected.',';
			
			}
			
			$data['5bfform_tin_issuer'] = rtrim($data['5bfform_tin_issuer'], ",");
			
			}
			if(!empty($_POST['5bfform_nameof_issuer'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['5bfform_nameof_issuer'] as $selected){
			 
             $data['5bfform_nameof_issuer'] = $data['5bfform_nameof_issuer'].$selected.',';
			
			}
			
			$data['5bfform_nameof_issuer'] = rtrim($data['5bfform_nameof_issuer'], ",");
			
			}
			if(!empty($_POST['5bfform_no_of_form'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['5bfform_no_of_form'] as $selected){
			 
             $data['5bfform_no_of_form'] = $data['5bfform_no_of_form'].$selected.',';
			
			}
			
			$data['5bfform_no_of_form'] = rtrim($data['5bfform_no_of_form'], ",");
			
			}
			if(!empty($_POST['5bfform_amount'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['5bfform_amount'] as $selected){
			 
             $data['5bfform_amount'] = $data['5bfform_amount'].$selected.',';
			
			}
			$data['5bfform_amount'] = rtrim($data['5bfform_amount'], ",");
			}
			if(!empty($_POST['5bfform_applicable_vat_rate'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['5bfform_applicable_vat_rate'] as $selected){
			 
             $data['5bfform_applicable_vat_rate'] = $data['5bfform_applicable_vat_rate'].$selected.',';
			
			}
			
			$data['5bfform_applicable_vat_rate'] = rtrim($data['5bfform_applicable_vat_rate'], ",");
			
			}
			
			//end here
			//code for 5bhiform start here
			if(!empty($_POST['5bhiform_tin_issuer'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['5bhiform_tin_issuer'] as $selected){
			 
             $data['5bhiform_tin_issuer'] = $data['5bhiform_tin_issuer'].$selected.',';
			
			}
			
			$data['5bhiform_tin_issuer'] = rtrim($data['5bhiform_tin_issuer'], ",");
			
			}
			if(!empty($_POST['5bhiform_nameof_issuer'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['5bhiform_nameof_issuer'] as $selected){
			 
             $data['5bhiform_nameof_issuer'] = $data['5bhiform_nameof_issuer'].$selected.',';
			
			}
			
			$data['5bhiform_nameof_issuer'] = rtrim($data['5bhiform_nameof_issuer'], ",");
			
			}
			if(!empty($_POST['5bhiform_no_of_form'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['5bhiform_no_of_form'] as $selected){
			 
             $data['5bhiform_no_of_form'] = $data['5bhiform_no_of_form'].$selected.',';
			
			}
			
			$data['5bhiform_no_of_form'] = rtrim($data['5bhiform_no_of_form'], ",");
			
			}
			if(!empty($_POST['5bhiform_amount'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['5bhiform_amount'] as $selected){
			 
             $data['5bhiform_amount'] = $data['5bhiform_amount'].$selected.',';
			
			}
			
			$data['5bhiform_amount'] = rtrim($data['5bhiform_amount'], ",");
			
			}
			if(!empty($_POST['5bhiform_applicable_vat_rate'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['5bhiform_applicable_vat_rate'] as $selected){
			 
             $data['5bhiform_applicable_vat_rate'] = $data['5bhiform_applicable_vat_rate'].$selected.',';
			
			}
			
			$data['5bhiform_applicable_vat_rate'] = rtrim($data['5bhiform_applicable_vat_rate'], ",");
			
			}
			//code for 6a transition form
			if(!empty($_POST['6ainvoice_document_no'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['6ainvoice_document_no'] as $selected){
			 
             $data['6ainvoice_document_no'] = $data['6ainvoice_document_no'].$selected.',';
			
			}
			
			$data['6ainvoice_document_no'] = rtrim($data['6ainvoice_document_no'], ",");
			
			}
			if(!empty($_POST['6ainvoice_document_date'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['6ainvoice_document_date'] as $selected){
			 
             $data['6ainvoice_document_date'] = $data['6ainvoice_document_date'].$selected.',';
			
			}
			
			$data['6ainvoice_document_date'] = rtrim($data['6ainvoice_document_date'], ",");
			
			}
			if(!empty($_POST['6ainvoice_registration_no'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['6ainvoice_registration_no'] as $selected){
			 
             $data['6ainvoice_registration_no'] = $data['6ainvoice_registration_no'].$selected.',';
			
			}
			
			$data['6ainvoice_registration_no'] = rtrim($data['6ainvoice_registration_no'], ",");
			
			}
			if(!empty($_POST['6arecipients_registration_no'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['6arecipients_registration_no'] as $selected){
			 
             $data['6arecipients_registration_no'] = $data['6arecipients_registration_no'].$selected.',';
			
			}
			
			$data['6arecipients_registration_no'] = rtrim($data['6arecipients_registration_no'], ",");
			
			}
			if(!empty($_POST['6a_value'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['6a_value'] as $selected){
			 
             $data['6a_value'] = $data['6a_value'].$selected.',';
			
			}
			
			$data['6a_value'] = rtrim($data['6a_value'], ",");
			
			}
			if(!empty($_POST['6a_ed_cvd'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['6a_ed_cvd'] as $selected){
			 
             $data['6a_ed_cvd'] = $data['6a_ed_cvd'].$selected.',';
			
			}
			
			$data['6a_ed_cvd'] = rtrim($data['6a_ed_cvd'], ",");
			
			}
			if(!empty($_POST['6a_sad'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['6a_sad'] as $selected){
			 
             $data['6a_sad'] = $data['6a_sad'].$selected.',';
			
			}
			
			$data['6a_sad'] = rtrim($data['6a_sad'], ",");
			
			}
			if(!empty($_POST['6a_totaleligible_cenvat'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['6a_totaleligible_cenvat'] as $selected){
			 
             $data['6a_totaleligible_cenvat'] = $data['6a_totaleligible_cenvat'].$selected.',';
			
			}
			
			$data['6a_totaleligible_cenvat'] = rtrim($data['6a_totaleligible_cenvat'], ",");
			
			}
			if(!empty($_POST['6a_totaleligible_credit'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['6a_totalcenvat_credit'] as $selected){
			 
             $data['6a_totalcenvat_credit'] = $data['6a_totalcenvat_credit'].$selected.',';
			
			}
			
			$data['6a_totalcenvat_credit'] = rtrim($data['6a_totalcenvat_credit'], ",");
			
			}
			if(!empty($_POST['6a_totalcenvat_credit_unavailed'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['6a_totalcenvat_credit_unavailed'] as $selected){
			 
             $data['6a_totalcenvat_credit_unavailed'] = $data['6a_totalcenvat_credit_unavailed'].$selected.',';
			
			}
			
			$data['6a_totalcenvat_credit_unavailed'] = rtrim($data['6a_totalcenvat_credit_unavailed'], ",");
			
			}
			if(!empty($_POST['6binvoice_document_no'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['6binvoice_document_no'] as $selected){
			 
             $data['6binvoice_document_no'] = $data['6binvoice_document_no'].$selected.',';
			
			}
			
			$data['6binvoice_document_no'] = rtrim($data['6binvoice_document_no'], ",");
			
			}
			if(!empty($_POST['6binvoice_document_date'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['6binvoice_document_date'] as $selected){
			 
             $data['6binvoice_document_date'] = $data['6binvoice_document_date'].$selected.',';
			
			}
			
			$data['6binvoice_document_date'] = rtrim($data['6binvoice_document_date'], ",");
			
			}
			if(!empty($_POST['6bsupplier_registration_no'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['6bsupplier_registration_no'] as $selected){
			 
             $data['6bsupplier_registration_no'] = $data['6bsupplier_registration_no'].$selected.',';
			
			}
			
			$data['6bsupplier_registration_no'] = rtrim($data['6bsupplier_registration_no'], ",");
			
			}
			if(!empty($_POST['6breceipients_registration_no'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['6breceipients_registration_no'] as $selected){
			 
             $data['6breceipients_registration_no'] = $data['6breceipients_registration_no'].$selected.',';
			
			}
			
			$data['6breceipients_registration_no'] = rtrim($data['6breceipients_registration_no'], ",");
			
			}
			if(!empty($_POST['6b_value'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['6b_value'] as $selected){
			 
             $data['6b_value'] = $data['6b_value'].$selected.',';
			
			}
			
			$data['6b_value'] = rtrim($data['6b_value'], ",");
			
			}
			if(!empty($_POST['6b_taxpaid_vat'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['6b_taxpaid_vat'] as $selected){
			 
             $data['6b_taxpaid_vat'] = $data['6b_taxpaid_vat'].$selected.',';
			
			}
			
			$data['6b_taxpaid_vat'] = rtrim($data['6b_taxpaid_vat'], ",");
			
			}
			if(!empty($_POST['6b_totaleligible_vat'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['6b_totaleligible_vat'] as $selected){
			 
             $data['6b_totaleligible_vat'] = $data['6b_totaleligible_vat'].$selected.',';
			
			}
			
			$data['6b_totaleligible_vat'] = rtrim($data['6b_totaleligible_vat'], ",");
			
			}
			if(!empty($_POST['6b_totalvat_creditavailed'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['6b_totalvat_creditavailed'] as $selected){
			 
             $data['6b_totalvat_creditavailed'] = $data['6b_totalvat_creditavailed'].$selected.',';
			
			}
			
			$data['6b_totalvat_creditavailed'] = rtrim($data['6b_totalvat_creditavailed'], ",");
			
			}
			if(!empty($_POST['6b_totalvat_creditunavailed'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['6b_totalvat_creditunavailed'] as $selected){
			 
             $data['6b_totalvat_creditunavailed'] = $data['6b_totalvat_creditunavailed'].$selected.',';
			
			}
			
			$data['6b_totalvat_creditunavailed'] = rtrim($data['6b_totalvat_creditunavailed'], ",");
			
			}
			
			//
			//end here
			//7a Details of input held in stock
			if(!empty($_POST['7a1_hsncode'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['7a1_hsncode'] as $selected){
			 
             $data['7a1_hsncode'] = $data['7a1_hsncode'].$selected.',';
			
			}
			
			$data['7a1_hsncode'] = rtrim($data['7a1_hsncode'], ",");
			
			}
			if(!empty($_POST['7a1_unit'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['7a1_unit'] as $selected){
			 
             $data['7a1_unit'] = $data['7a1_unit'].$selected.',';
			
			}
			
			$data['7a1_unit'] = rtrim($data['7a1_unit'], ",");
			
			}
			if(!empty($_POST['7a1_qty'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['7a1_qty'] as $selected){
			 
             $data['7a1_qty'] = $data['7a1_qty'].$selected.',';
			
			}
			
			$data['7a1_qty'] = rtrim($data['7a1_qty'], ",");
			
			}
			if(!empty($_POST['7a1_value'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['7a1_value'] as $selected){
			 
             $data['7a1_value'] = $data['7a1_value'].$selected.',';
			
			}
			
			$data['7a1_value'] = rtrim($data['7a1_value'], ",");
			
			}
			if(!empty($_POST['7a1_eligible_duties'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['7a1_eligible_duties'] as $selected){
			 
             $data['7a1_eligible_duties'] = $data['7a1_eligible_duties'].$selected.',';
			
			}
			
			$data['7a1_eligible_duties'] = rtrim($data['7a1_eligible_duties'], ",");
			
			}
		    //end here
			//7b code start here
			if(!empty($_POST['7b_nameof_supplier'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['7b_nameof_supplier'] as $selected){
			 
             $data['7b_nameof_supplier'] = $data['7b_nameof_supplier'].$selected.',';
			
			}
			
			$data['7b_nameof_supplier'] = rtrim($data['7b_nameof_supplier'], ",");
			
			}
			if(!empty($_POST['7b_invoice_number'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['7b_invoice_number'] as $selected){
			 
             $data['7b_invoice_number'] = $data['7b_invoice_number'].$selected.',';
			
			}
			
			$data['7b_invoice_number'] = rtrim($data['7b_invoice_number'], ",");
			
			}
			if(!empty($_POST['7b_invoice_date'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['7b_invoice_date'] as $selected){
			 
             $data['7b_invoice_date'] = $data['7b_invoice_date'].$selected.',';
			
			}
			
			$data['7b_invoice_date'] = rtrim($data['7b_invoice_date'], ",");
			
			}
			if(!empty($_POST['7b_description'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['7b_description'] as $selected){
			 
             $data['7b_description'] = $data['7b_description'].$selected.',';
			
			}
			
			$data['7b_description'] = rtrim($data['7b_description'], ",");
			
			}
			if(!empty($_POST['7b_quantity'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['7b_quantity'] as $selected){
			 
             $data['7b_quantity'] = $data['7b_quantity'].$selected.',';
			
			}
			
			$data['7b_quantity'] = rtrim($data['7b_quantity'], ",");
			
			}
			if(!empty($_POST['7b_uqc'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['7b_uqc'] as $selected){
			 
             $data['7b_uqc'] = $data['7b_uqc'].$selected.',';
			
			}
			
			$data['7b_uqc'] = rtrim($data['7b_uqc'], ",");
			
			}
			if(!empty($_POST['7b_value'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['7b_value'] as $selected){
			 
             $data['7b_value'] = $data['7b_value'].$selected.',';
			
			}
		
			$data['7b_value'] = rtrim($data['7b_value'], ",");
			
			}
			if(!empty($_POST['7b_eligible_duties'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['7b_eligible_duties'] as $selected){
			 
             $data['7b_eligible_duties'] = $data['7b_eligible_duties'].$selected.',';
			
			}
			$data['7b_eligible_duties'] = rtrim($data['7b_eligible_duties'], ",");
			}
			if(!empty($_POST['7b_vat'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['7b_vat'] as $selected){
			 
             $data['7b_vat'] = $data['7b_vat'].$selected.',';
			
			}
			$data['7b_vat'] = rtrim($data['7b_vat'], ",");
			}
			if(!empty($_POST['7b_dateonwhich_receipients'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['7b_dateonwhich_receipients'] as $selected){
			 
             $data['7b_dateonwhich_receipients'] = $data['7b_dateonwhich_receipients'].$selected.',';
			
			}
			$data['7b_dateonwhich_receipients'] = rtrim($data['7b_dateonwhich_receipients'], ",");
			}
			//end here
			//7c1 code start here
			if(!empty($_POST['7c1_description'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['7c1_description'] as $selected){
			 
             $data['7c1_description'] = $data['7c1_description'].$selected.',';
			
			}	
			
			$data['7c1_description'] = rtrim($data['7c1_description'], ",");
			
			}
			if(!empty($_POST['7c1_unit'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['7c1_unit'] as $selected){
			 
             $data['7c1_unit'] = $data['7c1_unit'].$selected.',';
			
			}	
			
			$data['7c1_unit'] = rtrim($data['7c1_unit'], ",");
			
			}
			if(!empty($_POST['7c1_qty'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['7c1_qty'] as $selected){
			 
             $data['7c1_qty'] = $data['7c1_qty'].$selected.',';
			
			}	
			
			$data['7c1_qty'] = rtrim($data['7c1_qty'], ",");
			
			}
			if(!empty($_POST['7c1_value'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['7c1_value'] as $selected){
			 
             $data['7c1_value'] = $data['7c1_value'].$selected.',';
			
			}	
			
			$data['7c1_value'] = rtrim($data['7c1_value'], ",");
			
			}
			if(!empty($_POST['7c1_vat'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['7c1_vat'] as $selected){
			 
             $data['7c1_vat'] = $data['7c1_vat'].$selected.',';
			
			}	
			
			$data['7c1_vat'] = rtrim($data['7c1_vat'], ",");
			
			}
			if(!empty($_POST['7c1_totalinput_taxcredit'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['7c1_totalinput_taxcredit'] as $selected){
			 
             $data['7c1_totalinput_taxcredit'] = $data['7c1_totalinput_taxcredit'].$selected.',';
			
			}	
			
			$data['7c1_totalinput_taxcredit'] = rtrim($data['7c1_totalinput_taxcredit'], ",");
			
			}
			if(!empty($_POST['7c1_totalinput_taxcredit_exempt'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['7c1_totalinput_taxcredit_exempt'] as $selected){
			 
             $data['7c1_totalinput_taxcredit_exempt'] = $data['7c1_totalinput_taxcredit_exempt'].$selected.',';
			
			}	
			
			$data['7c1_totalinput_taxcredit_exempt'] = rtrim($data['7c1_totalinput_taxcredit_exempt'], ",");
			
			}
			if(!empty($_POST['7c1_totalinput_taxcredit_admissible'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['7c1_totalinput_taxcredit_admissible'] as $selected){
			 
             $data['7c1_totalinput_taxcredit_admissible'] = $data['7c1_totalinput_taxcredit_admissible'].$selected.',';
			
			}	
			
			$data['7c1_totalinput_taxcredit_admissible'] = rtrim($data['7c1_totalinput_taxcredit_admissible'], ",");
			
			}
			//end here
			//7d code start here
			if(!empty($_POST['7d_description'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['7d_description'] as $selected){
			 
             $data['7d_description'] = $data['7d_description'].$selected.',';
			
			}	
			
			$data['7d_description'] = rtrim($data['7d_description'], ",");
			
			}
			if(!empty($_POST['7d_unit'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['7d_unit'] as $selected){
			 
             $data['7d_unit'] = $data['7d_unit'].$selected.',';
			
			}	
			
			$data['7d_unit'] = rtrim($data['7d_unit'], ",");
			
			}
			if(!empty($_POST['7d_qty'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['7d_qty'] as $selected){
			 
             $data['7d_qty'] = $data['7d_qty'].$selected.',';
			
			}	
			
			$data['7d_qty'] = rtrim($data['7d_qty'], ",");
			
			}
			if(!empty($_POST['7d_value'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['7d_value'] as $selected){
			 
             $data['7d_value'] = $data['7d_value'].$selected.',';
			
			}	
			
			$data['7d_value'] = rtrim($data['7d_value'], ",");
			
			}
			if(!empty($_POST['7d_vatentry_taxpad'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['7d_vatentry_taxpad'] as $selected){
			 
             $data['7d_vatentry_taxpad'] = $data['7d_vatentry_taxpad'].$selected.',';
			
			}	
			
			$data['7d_vatentry_taxpad'] = rtrim($data['7d_vatentry_taxpad'], ",");
			
			}
			//end here
			//8 code start here
			if(!empty($_POST['8registration_no'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['8registration_no'] as $selected){
			 
             $data['8registration_no'] = $data['8registration_no'].$selected.',';
			
			}	
			
			$data['8registration_no'] = rtrim($data['8registration_no'], ",");
			
			}
			if(!empty($_POST['8taxperiod_lastreturn'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['8taxperiod_lastreturn'] as $selected){
			 
             $data['8taxperiod_lastreturn'] = $data['8taxperiod_lastreturn'].$selected.',';
			
			}	
			
			$data['8taxperiod_lastreturn'] = rtrim($data['8taxperiod_lastreturn'], ",");
			
			}
			if(!empty($_POST['8dateoffilling_return'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['8dateoffilling_return'] as $selected){
			 
             $data['8dateoffilling_return'] = $data['8dateoffilling_return'].$selected.',';
			
			}	
			
			$data['8dateoffilling_return'] = rtrim($data['8dateoffilling_return'], ",");
			
			}
			if(!empty($_POST['8balanceeligible_cenvat_credit'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['8balanceeligible_cenvat_credit'] as $selected){
			 
             $data['8balanceeligible_cenvat_credit'] = $data['8balanceeligible_cenvat_credit'].$selected.',';
			
			}	
			
			$data['8balanceeligible_cenvat_credit'] = rtrim($data['8balanceeligible_cenvat_credit'], ",");
			
			}
			if(!empty($_POST['8gstnof_receiver'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['8gstnof_receiver'] as $selected){
			 
             $data['8gstnof_receiver'] = $data['8gstnof_receiver'].$selected.',';
			
			}	
			
			$data['8gstnof_receiver'] = rtrim($data['8gstnof_receiver'], ",");
			
			}
			if(!empty($_POST['8distributionno'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['8distributionno'] as $selected){
			 
             $data['8distributionno'] = $data['8distributionno'].$selected.',';
			
			}	
			$data['8distributionno'] = rtrim($data['8distributionno'], ",");
			}
			if(!empty($_POST['8distributiondate'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['8distributiondate'] as $selected){
			 
             $data['8distributiondate'] = $data['8distributiondate'].$selected.',';
			
			}	
			
			$data['8distributiondate'] = rtrim($data['8distributiondate'], ",");
			
			}
			if(!empty($_POST['8itcofcentral'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['8itcofcentral'] as $selected){
			 
             $data['8itcofcentral'] = $data['8itcofcentral'].$selected.',';
			
			}	
			
			$data['8itcofcentral'] = rtrim($data['8itcofcentral'], ",");
			
			}
			//end here
			//start 91 here
			if(!empty($_POST['9a1challan_no'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['9a1challan_no'] as $selected){
			 
             $data['9a1challan_no'] = $data['9a1challan_no'].$selected.',';
			
			}	
			
			$data['9a1challan_no'] = rtrim($data['9a1challan_no'], ",");
			
			}
			if(!empty($_POST['9a1challan_date'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['9a1challan_date'] as $selected){
			 
             $data['9a1challan_date'] = $data['9a1challan_date'].$selected.',';
			
			}	
			
			$data['9a1challan_date'] = rtrim($data['9a1challan_date'], ",");
			
			}
			if(!empty($_POST['9a1typeof_goods'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['9a1typeof_goods'] as $selected){
			 
             $data['9a1typeof_goods'] = $data['9a1typeof_goods'].$selected.',';
			
			}	
			
			$data['9a1typeof_goods'] = rtrim($data['9a1typeof_goods'], ",");
			
			}
			if(!empty($_POST['9a1_hsn'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['9a1_hsn'] as $selected){
			 
             $data['9a1_hsn'] = $data['9a1_hsn'].$selected.',';
			
			}	
			
			$data['9a1_hsn'] = rtrim($data['9a1_hsn'], ",");
			
			}
			if(!empty($_POST['9a1_description'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['9a1_description'] as $selected){
			 
             $data['9a1_description'] = $data['9a1_description'].$selected.',';
			
			}	
			
			$data['9a1_description'] = rtrim($data['9a1_description'], ",");
			
			}
			if(!empty($_POST['9a1_unit'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['9a1_unit'] as $selected){
			 
             $data['9a1_unit'] = $data['9a1_unit'].$selected.',';
			
			}	
			
			$data['9a1_unit'] = rtrim($data['9a1_unit'], ",");
			
			}
			if(!empty($_POST['9a1_quantity'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['9a1_quantity'] as $selected){
			 
             $data['9a1_quantity'] = $data['9a1_quantity'].$selected.',';
			
			}	
			
			$data['9a1_quantity'] = rtrim($data['9a1_quantity'], ",");
			
			}
			if(!empty($_POST['9a1_value'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['9a1_value'] as $selected){
			 
             $data['9a1_value'] = $data['9a1_value'].$selected.',';
			
			}	
			
			$data['9a1_value'] = rtrim($data['9a1_value'], ",");
			
			}	
			
			//end here
			//start code for 9b1 here
			//start 91 here
			if(!empty($_POST['9b1challan_no'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['9b1challan_no'] as $selected){
			 
             $data['9b1challan_no'] = $data['9b1challan_no'].$selected.',';
			
			}	
			
			$data['9b1challan_no'] = rtrim($data['9b1challan_no'], ",");
			
			}
			if(!empty($_POST['9b1challan_date'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['9b1challan_date'] as $selected){
			 
             $data['9b1challan_date'] = $data['9b1challan_date'].$selected.',';
			
			}	
			
			$data['9b1challan_date'] = rtrim($data['9b1challan_date'], ",");
			
			}
			if(!empty($_POST['9b1typeof_goods'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['9b1typeof_goods'] as $selected){
			 
             $data['9b1typeof_goods'] = $data['9b1typeof_goods'].$selected.',';
			
			}	
			
			$data['9b1typeof_goods'] = rtrim($data['9b1typeof_goods'], ",");
			
			}
			if(!empty($_POST['9b1_hsn'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['9b1_hsn'] as $selected){
			 
             $data['9b1_hsn'] = $data['9b1_hsn'].$selected.',';
			
			}	
			
			$data['9b1_hsn'] = rtrim($data['9b1_hsn'], ",");
			
			}
			if(!empty($_POST['9b1_hsn'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['9b1_hsn'] as $selected){
			 
             $data['9b1_hsn'] = $data['9b1_hsn'].$selected.',';
			
			}	
			
			$data['9b1_hsn'] = rtrim($data['9b1_hsn'], ",");
			
			}
			if(!empty($_POST['9b1_unit'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['9b1_unit'] as $selected){
			 
             $data['9b1_unit'] = $data['9b1_unit'].$selected.',';
			
			}	
			
			$data['9b1_unit'] = rtrim($data['9b1_unit'], ",");
			
			}
			if(!empty($_POST['9b1_quantity'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['9b1_quantity'] as $selected){
			 
             $data['9b1_quantity'] = $data['9b1_quantity'].$selected.',';
			
			}	
			
			$data['9b1_quantity'] = rtrim($data['9b1_quantity'], ",");
			
			}
			if(!empty($_POST['9b1_value'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['9b1_value'] as $selected){
			 
             $data['9b1_value'] = $data['9b1_value'].$selected.',';
			
			}	
			
			$data['9b1_value'] = rtrim($data['9b1_value'], ",");
			
			}	
			
			//end here
			//start code for 11a
			if(!empty($_POST['11aregistration_no'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['11aregistration_no'] as $selected){
			 
             $data['11aregistration_no'] = $data['11aregistration_no'].$selected.',';
			
			}	
			
			$data['11aregistration_no'] = rtrim($data['11aregistration_no'], ",");
			
			}	
			if(!empty($_POST['11aservicetax_no'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['11aservicetax_no'] as $selected){
			 
             $data['11aservicetax_no'] = $data['11aservicetax_no'].$selected.',';
			
			}	
			
			$data['11aservicetax_no'] = rtrim($data['11aservicetax_no'], ",");
			
			}	
			if(!empty($_POST['11ainvoice_documentno'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['11ainvoice_documentno'] as $selected){
			 
             $data['11ainvoice_documentno'] = $data['11ainvoice_documentno'].$selected.',';
			
			}	
			
			$data['11ainvoice_documentno'] = rtrim($data['11ainvoice_documentno'], ",");
			
			}	
			if(!empty($_POST['11ainvoice_document_date'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['11ainvoice_document_date'] as $selected){
			 
             $data['11ainvoice_document_date'] = $data['11ainvoice_document_date'].$selected.',';
			
			}	
			
			$data['11ainvoice_document_date'] = rtrim($data['11ainvoice_document_date'], ",");
			
			}	
			if(!empty($_POST['11atax_period'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['11atax_period'] as $selected){
			 
             $data['11atax_period'] = $data['11atax_period'].$selected.',';
			
			}	
			
			$data['11atax_period'] = rtrim($data['11atax_period'], ",");
			
			}	
			if(!empty($_POST['11avatpaid_sgst'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['11avatpaid_sgst'] as $selected){
			 
             $data['11avatpaid_sgst'] = $data['11avatpaid_sgst'].$selected.',';
			
			}	
			
			$data['11avatpaid_sgst'] = rtrim($data['11avatpaid_sgst'], ",");
			
			}	
			//end here 111a code here
			//start code for 12 a5_balance_cenvat_credit
			if(!empty($_POST['12a_document_no'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['12a_document_no'] as $selected){
			 
             $data['12a_document_no'] = $data['12a_document_no'].$selected.',';
			
			}	
			
			$data['12a_document_no'] = rtrim($data['12a_document_no'], ",");
			
			}	
			if(!empty($_POST['12a_document_date'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['12a_document_date'] as $selected){
			 
             $data['12a_document_date'] = $data['12a_document_date'].$selected.',';
			
			}	
			
			$data['12a_document_date'] = rtrim($data['12a_document_date'], ",");
			
			}	
			if(!empty($_POST['12a_gstinno_receipient'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['12a_gstinno_receipient'] as $selected){
			 
             $data['12a_gstinno_receipient'] = $data['12a_gstinno_receipient'].$selected.',';
			
			}	
			$data['12a_gstinno_receipient'] = rtrim($data['12a_gstinno_receipient'], ",");
			}
			if(!empty($_POST['12a_name_receipient'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['12a_name_receipient'] as $selected){
			 
             $data['12a_name_receipient'] = $data['12a_name_receipient'].$selected.',';
			
			}	
			
			$data['12a_name_receipient'] = rtrim($data['12a_name_receipient'], ",");
			
			}	
			if(!empty($_POST['12a_hsn'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['12a_hsn'] as $selected){
			 
             $data['12a_hsn'] = $data['12a_hsn'].$selected.',';
			
			}	
			
			$data['12a_hsn'] = rtrim($data['12a_hsn'], ",");
			
			}	
			if(!empty($_POST['12a_description'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['12a_description'] as $selected){
			 
             $data['12a_description'] = $data['12a_description'].$selected.',';
			
			}	
			
			$data['12a_description'] = rtrim($data['12a_description'], ",");
			
			}
			if(!empty($_POST['12a_unit'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['12a_unit'] as $selected){
			 
             $data['12a_unit'] = $data['12a_unit'].$selected.',';
			
			}	
			
			$data['12a_unit'] = rtrim($data['12a_unit'], ",");
			
			}
			if(!empty($_POST['12a_quantity'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['12a_quantity'] as $selected){
			 
             $data['12a_quantity'] = $data['12a_quantity'].$selected.',';
			
			}	
			
			$data['12a_quantity'] = rtrim($data['12a_quantity'], ",");
			
			}
			if(!empty($_POST['12a_value'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['12a_value'] as $selected){
			 
             $data['12a_value'] = $data['12a_value'].$selected.',';
			
			}	
			
			$data['12a_value'] = rtrim($data['12a_value'], ",");
			
			}
			//end here code for 12 a
			//9b1 code end here
			$data5a[]=array("a5_taxperiod_last_return"=>$dataArr['5a_taxperiod_last_return'],"a5_taxperiod_last_return"=>$data['5a_taxperiod_last_return'],"a5_dateoffilling_return"=>$data["5a_dateoffilling_return"],"a5_balance_cenvat_credit"=>$data["5a_balance_cenvat_credit"],"a5_cenvat_credit_admissible"=>$data["5a_cenvat_credit_admissible"],"b5bcform_tin_issuer"=>$data["5bcform_tin_issuer"],"b5bcform_nameof_issuer"=>$data["5bcform_nameof_issuer"],"b5bcform_no_of_item"=>$data["5bcform_no_of_item"],"b5bcform_amount"=>$data["5bcform_amount"],"b5bcform_applicable_vat_rate"=>$data["5bcform_applicable_vat_rate"],"b5bfform_tin_issuer"=>$data["5bfform_tin_issuer"],"b5bfform_nameof_issuer"=>$data["5bfform_nameof_issuer"],"b5bfform_no_of_form"=>$data["5bfform_no_of_form"],"b5bfform_amount"=>$data["5bfform_amount"],"b5bfform_applicable_vat_rate"=>$data["5bfform_applicable_vat_rate"],"b5bhiform_tin_issuer"=>$data["5bhiform_tin_issuer"],"b5bhiform_nameof_issuer"=>$data["5bhiform_nameof_issuer"],"b5bhiform_no_of_form"=>$data["5bhiform_no_of_form"],"b5bhiform_amount"=>$data["5bhiform_amount"],"b5bhiform_applicable_vat_rate"=>$data["b5bhiform_applicable_vat_rate"],"a6ainvoice_document_no"=>$data['6ainvoice_document_no'],"a6ainvoice_document_date"=>$data['6ainvoice_document_date'],"a6asupplier_registration_no"=>$data['6asupplier_registration_no'],"a6arecipients_registration_no"=>$data['6arecipients_registration_no'],"a6a_value"=>$data['6a_value'],"a6a_sad"=>$data['6a_sad'],"a6a_totaleligible_cenvat"=>$data['6a_totaleligible_cenvat'],"a6a_totalcenvat_credit"=>$data['6a_totalcenvat_credit'],"a6a_totalcenvat_credit_unavailed"=>$data['6a_totalcenvat_credit_unavailed'],"b6binvoice_document_no"=>$data['6binvoice_document_no'],"b6binvoice_document_date"=>$data['6binvoice_document_date'],"b6bsupplier_registration_no"=>$data['6bsupplier_registration_no'],"b6breceipients_registration_no"=>$data['6breceipients_registration_no'],"b6b_value"=>$data['6b_value'],"b6b_taxpaid_vat"=>$data['6b_taxpaid_vat'],"b6b_totaleligible_vat"=>$data['6b_totaleligible_vat'],"b6b_totalvat_creditavailed"=>$data['6b_totalvat_creditavailed'],"b6b_totalvat_creditavailed"=>$data['6b_totalvat_creditavailed']);
			//$data5b[]=array("5a_taxperiod_last_return"=>$dataArr['5a_taxperiod_last_return'],"5a_taxperiod_last_return"=>$data['5a_taxperiod_last_return'],"5a_dateoffilling_return"=>$data["5a_dateoffilling_return"],"5a_balance_cenvat_credit"=>$data["5a_balance_cenvat_credit"],"5a_cenvat_credit_admissible"=>$data["5a_cenvat_credit_admissible"]);
			

			
			
			//$dataArr['gstr_transition_data'] = base64_encode(json_encode(array("table5a"=>$data5a,"table5b"=>$data5b)));
		$dataArr['gstr_transition_data'] = base64_encode(json_encode($data5a));
			
			return $dataArr;
			
	}
    public function saveGstrTransition()
    {
		$data = $this->get_results("select * from gst_transition where added_by='".$_SESSION['user_detail']['user_id']."' and financial_month='".$this->sanitize($_GET['returnmonth'])."'");
		$dataArr = $this->gstTransitionData();
	
		//$dataPlaceOfSupply = $this->getPlaceOfSupply();
		
	    $sql = "select * from " . TAB_PREFIX . "client_kyc where added_by='" . $_SESSION['user_detail']['user_id'] . "' order by id desc limit 0,1";
       
       $clientdata = $this->get_results($sql);
	   $client_gstin_number;
	   if(count($clientdata) > 0 )
	   {
		   $client_gstin_number = $clientdata[0]->gstin_number;
	   }
	   //$dataArr['client_gstin_number'] = $client_gstin_number;
	   

           $returnmonth = $this->sanitize($_GET['returnmonth']);
		if(empty($data))
		{
			$dataArr['financial_month']=$this->sanitize($_GET['returnmonth']);
			$dataArr['added_by']=$this->sanitize($_SESSION["user_detail"]["user_id"]);
			
			if ($this->insert('gst_transition', $dataArr)) {
				//$this->getPlaceOfSupplyUnregistered();
				//$this->getPlaceOfSupplyComposition();
				//$this->getPlaceOfSupplyUinHolder();
				$this->setSuccess('GST transition form Saved Successfully');
				$this->logMsg("GST transition Inserted financial month : " . $returnmonth,"gst_transition");
				return true;
			}
			else
			{
				$this->setError('Failed to save GSTR3B data');
			   return false;    	   
		   }

		}
		else
		{
			if ($this->update('gst_transition', $dataArr,array('added_by'=>$_SESSION['user_detail']['user_id'],'financial_month'=>$this->sanitize($_GET['returnmonth'])))) {
				//$this->getPlaceOfSupplyUnregistered();
				//$this->getPlaceOfSupplyComposition();
				//$this->getPlaceOfSupplyUinHolder();
		                      
				$this->setSuccess('GST transition month of return'.$returnmonth."updated Successfully");
				//$this->logMsg("GSTR3B updated financial month : " . $returnmonth,"gstr_3b");
				return true;
			}
			else
			{
				$this->setError('Failed to save GSTR3B data');
			   return false;    	   
		   }
		}
	   
   }
  
  
    public function finalSaveGstrTransition()
   {
		$return_id =   isset($_POST['returnid']) ? $_POST['returnid'] : '';
		$userid = $_SESSION['user_detail']['user_id'];
		 if($this->update(TAB_PREFIX.'client_return_gstr3b', array('final_submit' => 1), array('return_id' => $return_id)))
		 {
		 $this->setSuccess('GSTR3B Submitted Successfully');
		 $this->logMsg("GSTR3B final submit financial month :".$this->sanitize($_GET['returnmonth']),"gstr_3b");
   
		 return true;
		 }
   }
   
   
  
   
  
   private function getPlaceOfSupplyUnregistered()
   {    $dataArr = array();
	     $dataArr['place_of_supply']='';
	     if(!empty($_POST['place_of_supply_unregistered_person'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['place_of_supply_unregistered_person'] as $selected){
			 
             $dataArr['place_of_supply'] = $dataArr['place_of_supply'].$selected.',';
			
			} 
			}
			$dataArr['totaltaxable_value']='';
			  if(!empty($_POST['total_taxable_value_unregistered_person'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['total_taxable_value_unregistered_person'] as $selected){
			 
             $dataArr['totaltaxable_value'] = $dataArr['totaltaxable_value'].$selected.',';
			
			} 
			}
			$dataArr['amount_of_integrated_tax']='';
			  if(!empty($_POST['amount_of_integrated_tax_unregistered_person'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['amount_of_integrated_tax_unregistered_person'] as $selected){
			 
             $dataArr['amount_of_integrated_tax'] = $dataArr['amount_of_integrated_tax'].$selected.',';
			
			} 
			}
	  $sql="select * from gst_place_of_supply where added_by='".$_SESSION['user_detail']['user_id']."' and financial_month='".$this->sanitize($_GET['returnmonth'])."' and type='0'";		
	   $data = $this->get_results($sql);
		if(empty($data))
		{
			$dataArr['financial_month']=$this->sanitize($_GET['returnmonth']);
			$dataArr['type']=0;
			$dataArr['added_by']=$_SESSION["user_detail"]["user_id"];
			
			if ($this->insert('gst_place_of_supply', $dataArr)) {
			
				$this->setSuccess('GSTR3B Saved Successfully');
			
				//return true;
			}
			else
			{
				$this->setError('Failed to save GSTR3B data');
				
			   return false;    	   
		   }

		}
		else
		{
			if ($this->update('gst_place_of_supply', $dataArr,array('added_by'=>$_SESSION['user_detail']['user_id'],'type'=>'0','financial_month'=>$this->sanitize($_GET['returnmonth'])))) {
				
		                      
				$this->setSuccess('GSTR3B Saved Successfully');
				
				return true;
			}
			else
			{
				$this->setError('Failed to save GSTR3B data');
			   return false;    	   
		   }
		}
	    
			 			
   }
   public function checkVerifyUser() {
        if (isset($_SESSION['user_detail']['user_id']) && $_SESSION['user_detail']['user_id'] != '') {
			$data = $this->get_results("select * from " . TAB_PREFIX . "user where user_id='" . $_SESSION['user_detail']['user_id'] . "'");
          
                if ($data[0]->email_verify == '0' || $data[0]->mobileno_verify == '0') {
					
					 $this->setError("GSTR-3B File first verify your email and mobile number");
					return "notverify";
				}
				return 'verify';
         
        }
    }
   
    private function getGSTR3bData()
	{
		$dataArr = array();
		 $dataArr['total_tax_value_supplya'] = isset($_POST['total_tax_value_supplya']) ? $_POST['total_tax_value_supplya'] : '';
        $dataArr['integrated_tax_value_supplya'] = isset($_POST['integrated_tax_value_supplya']) ? $_POST['integrated_tax_value_supplya'] : '';
        $dataArr['central_tax_value_supplya'] = isset($_POST['central_tax_value_supplya']) ? $_POST['central_tax_value_supplya'] : '';
        $dataArr['state_tax_value_supplya'] = isset($_POST['state_tax_value_supplya']) ? $_POST['state_tax_value_supplya'] : '';
        $dataArr['cess_tax_value_supplya'] = isset($_POST['cess_tax_value_supplya']) ? $_POST['cess_tax_value_supplya'] : '';
        $dataArr['total_tax_value_supplyb'] = isset($_POST['total_tax_value_supplyb']) ? $_POST['total_tax_value_supplyb'] : '';
        $dataArr['integrated_tax_value_supplyb'] = isset($_POST['integrated_tax_value_supplyb']) ? $_POST['integrated_tax_value_supplyb'] : '';
        $dataArr['central_tax_value_supplyb'] = isset($_POST['central_tax_value_supplyb']) ? $_POST['central_tax_value_supplyb'] : '';
        $dataArr['state_tax_value_supplyb'] = isset($_POST['state_tax_value_supplyb']) ? $_POST['state_tax_value_supplyb'] : '';
        $dataArr['cess_tax_value_supplyb'] = isset($_POST['cess_tax_value_supplyb']) ? $_POST['cess_tax_value_supplyb'] : '';
        $dataArr['total_tax_value_supplyc'] = isset($_POST['total_tax_value_supplyc']) ? $_POST['total_tax_value_supplyc'] : '';
        $dataArr['integrated_tax_value_supplyc'] = isset($_POST['integrated_tax_value_supplyc']) ? $_POST['integrated_tax_value_supplyc'] : '';
        $dataArr['central_tax_value_supplyc'] = isset($_POST['central_tax_value_supplyc']) ? $_POST['central_tax_value_supplyc'] : '';
        $dataArr['state_tax_value_supplyc'] = isset($_POST['state_tax_value_supplyc']) ? $_POST['state_tax_value_supplyc'] : '';
         $dataArr['cess_tax_value_supplyc'] = isset($_POST['cess_tax_value_supplyc']) ? $_POST['cess_tax_value_supplyc'] : '';
        $dataArr['total_tax_value_supplyd'] = isset($_POST['total_tax_value_supplyd']) ? $_POST['total_tax_value_supplyd'] : '';
        $dataArr['integrated_tax_value_supplyd'] = isset($_POST['integrated_tax_value_supplyd']) ? $_POST['integrated_tax_value_supplyd'] : '';
         $dataArr['central_tax_value_supplyd'] = isset($_POST['central_tax_value_supplyd']) ? $_POST['central_tax_value_supplyd'] : '';
         $dataArr['state_tax_value_supplyd'] = isset($_POST['state_tax_value_supplyd']) ? $_POST['state_tax_value_supplyd'] : '';
         $dataArr['cess_tax_value_supplyd'] = isset($_POST['cess_tax_value_supplyd']) ? $_POST['cess_tax_value_supplyd'] : '';
         $dataArr['total_tax_value_supplye'] = isset($_POST['total_tax_value_supplye']) ? $_POST['total_tax_value_supplye'] : '';
		 /*
         $dataArr['integrated_tax_value_supplye'] = isset($_POST['integrated_tax_value_supplye']) ? $_POST['integrated_tax_value_supplye'] : '';
         $dataArr['central_tax_value_supplye'] = isset($_POST['central_tax_value_supplye']) ? $_POST['central_tax_value_supplye'] : '';
		 $dataArr['state_tax_value_supplye'] = isset($_POST['state_tax_value_supplye']) ? $_POST['state_tax_value_supplye'] : '';
		$dataArr['cess_tax_value_supplye'] = isset($_POST['cess_tax_value_supplye']) ? $_POST['cess_tax_value_supplye'] : '';
		*/
		$dataArr['integrated_tax_itcavailable_a'] = isset($_POST['integrated_tax_itcavailable_a']) ? $_POST['integrated_tax_itcavailable_a'] : '';
    	$dataArr['central_tax_itcavailable_a'] = isset($_POST['central_tax_itcavailable_a']) ? $_POST['central_tax_itcavailable_a'] : '';
	   	$dataArr['state_tax_itcavailable_a'] = isset($_POST['state_tax_itcavailable_a']) ? $_POST['state_tax_itcavailable_a'] : '';
		$dataArr['cess_tax_itcavailable_a'] = isset($_POST['cess_tax_itcavailable_a']) ? $_POST['cess_tax_itcavailable_a'] : '';
		$dataArr['integrated_tax_import_of_goods'] = isset($_POST['integrated_tax_import_of_goods']) ? $_POST['integrated_tax_import_of_goods'] : '';
		$dataArr['central_tax_import_of_goods'] = isset($_POST['central_tax_import_of_goods']) ? $_POST['central_tax_import_of_goods'] : '';
		$dataArr['state_tax_import_of_goods'] = isset($_POST['state_tax_import_of_goods']) ? $_POST['state_tax_import_of_goods'] : '';
		$dataArr['cess_tax_import_of_goods'] = isset($_POST['cess_tax_import_of_goods']) ? $_POST['cess_tax_import_of_goods'] : '';
		$dataArr['integrated_tax_import_of_services'] = isset($_POST['integrated_tax_import_of_services']) ? $_POST['integrated_tax_import_of_services'] : '';
		$dataArr['central_tax_import_of_services'] = isset($_POST['central_tax_import_of_services']) ? $_POST['central_tax_import_of_services'] : '';
		$dataArr['state_tax_import_of_services'] = isset($_POST['state_tax_import_of_services']) ? $_POST['state_tax_import_of_services'] : '';
		$dataArr['cess_tax_import_of_services'] = isset($_POST['cess_tax_import_of_services']) ? $_POST['cess_tax_import_of_services'] : '';
		$dataArr['integrated_tax_inward_supplies_reverse_charge'] = isset($_POST['integrated_tax_inward_supplies_reverse_charge']) ? $_POST['integrated_tax_inward_supplies_reverse_charge'] : '';
		$dataArr['central_tax_inward_supplies_reverse_charge'] = isset($_POST['central_tax_inward_supplies_reverse_charge']) ? $_POST['central_tax_inward_supplies_reverse_charge'] : '';
		$dataArr['state_tax_inward_supplies_reverse_charge'] = isset($_POST['state_tax_inward_supplies_reverse_charge']) ? $_POST['state_tax_inward_supplies_reverse_charge'] : '';
		$dataArr['cess_tax_inward_supplies_reverse_charge'] = isset($_POST['cess_tax_inward_supplies_reverse_charge']) ? $_POST['cess_tax_inward_supplies_reverse_charge'] : '';
		$dataArr['integrated_tax_inward_supplies'] = isset($_POST['integrated_tax_inward_supplies']) ? $_POST['integrated_tax_inward_supplies'] : '';
		$dataArr['central_tax_inward_supplies'] = isset($_POST['central_tax_inward_supplies']) ? $_POST['central_tax_inward_supplies'] : '';
		$dataArr['state_tax_inward_supplies'] = isset($_POST['state_tax_inward_supplies']) ? $_POST['state_tax_inward_supplies'] : '';
		$dataArr['cess_tax_inward_supplies'] = isset($_POST['cess_tax_inward_supplies']) ? $_POST['cess_tax_inward_supplies'] : '';
		$dataArr['integrated_tax_allother_itc'] = isset($_POST['integrated_tax_allother_itc']) ? $_POST['integrated_tax_allother_itc'] : '';
		$dataArr['central_tax_allother_itc'] = isset($_POST['central_tax_allother_itc']) ? $_POST['central_tax_allother_itc'] : '';
		$dataArr['state_tax_allother_itc'] = isset($_POST['state_tax_allother_itc']) ? $_POST['state_tax_allother_itc'] : '';
		$dataArr['cess_tax_allother_itc'] = isset($_POST['cess_tax_allother_itc']) ? $_POST['cess_tax_allother_itc'] : '';
		$dataArr['integrated_tax_itc_reversed_b'] = isset($_POST['integrated_tax_itc_reversed_b']) ? $_POST['integrated_tax_itc_reversed_b'] : '';
		$dataArr['central_tax_itc_reversed_b'] = isset($_POST['central_tax_itc_reversed_b']) ? $_POST['central_tax_itc_reversed_b'] : '';
		$dataArr['state_tax_itc_reversed_b'] = isset($_POST['state_tax_itc_reversed_b']) ? $_POST['state_tax_itc_reversed_b'] : '';
		$dataArr['cess_tax_itc_reversed_b'] = isset($_POST['cess_tax_itc_reversed_b']) ? $_POST['cess_tax_itc_reversed_b'] : '';
		$dataArr['integrated_tax_itc_reversed_cgstrules'] = isset($_POST['integrated_tax_itc_reversed_cgstrules']) ? $_POST['integrated_tax_itc_reversed_cgstrules'] : '';
		$dataArr['central_tax_itc_reversed_cgstrules'] = isset($_POST['central_tax_itc_reversed_cgstrules']) ? $_POST['central_tax_itc_reversed_cgstrules'] : '';
		$dataArr['state_tax_itc_reversed_cgstrules'] = isset($_POST['state_tax_itc_reversed_cgstrules']) ? $_POST['state_tax_itc_reversed_cgstrules'] : '';
		$dataArr['cess_tax_itc_reversed_cgstrules'] = isset($_POST['cess_tax_itc_reversed_cgstrules']) ? $_POST['cess_tax_itc_reversed_cgstrules'] : '';
		$dataArr['integrated_tax_itc_reversed_other'] = isset($_POST['integrated_tax_itc_reversed_other']) ? $_POST['integrated_tax_itc_reversed_other'] : '';
		$dataArr['central_tax_itc_reversed_other'] = isset($_POST['central_tax_itc_reversed_other']) ? $_POST['central_tax_itc_reversed_other'] : '';
		$dataArr['state_tax_itc_reversed_other'] = isset($_POST['state_tax_itc_reversed_other']) ? $_POST['state_tax_itc_reversed_other'] : '';
		$dataArr['cess_tax_itc_reversed_other'] = isset($_POST['cess_tax_itc_reversed_other']) ? $_POST['cess_tax_itc_reversed_other'] : '';
		$dataArr['integrated_tax_net_itc_a_b'] = isset($_POST['integrated_tax_net_itc_a_b']) ? $_POST['integrated_tax_net_itc_a_b'] : '';
		$dataArr['central_tax_net_itc_a_b'] = isset($_POST['central_tax_net_itc_a_b']) ? $_POST['central_tax_net_itc_a_b'] : '';
		$dataArr['state_tax_net_itc_a_b'] = isset($_POST['state_tax_net_itc_a_b']) ? $_POST['state_tax_net_itc_a_b'] : '';
		$dataArr['cess_tax_net_itc_a_b'] = isset($_POST['cess_tax_net_itc_a_b']) ? $_POST['cess_tax_net_itc_a_b'] : '';
		$dataArr['integrated_tax_inligible_itc'] = isset($_POST['integrated_tax_inligible_itc']) ? $_POST['integrated_tax_inligible_itc'] : '';
		$dataArr['central_tax_inligible_itc'] = isset($_POST['central_tax_inligible_itc']) ? $_POST['central_tax_inligible_itc'] : '';
		$dataArr['state_tax_inligible_itc'] = isset($_POST['state_tax_inligible_itc']) ? $_POST['state_tax_inligible_itc'] : '';
		$dataArr['cess_tax_inligible_itc'] = isset($_POST['cess_tax_inligible_itc']) ? $_POST['cess_tax_inligible_itc'] : '';
		$dataArr['integrated_tax_inligible_itc_17_5'] = isset($_POST['integrated_tax_inligible_itc_17_5']) ? $_POST['integrated_tax_inligible_itc_17_5'] : '';
		$dataArr['central_tax_inligible_itc_17_5'] = isset($_POST['central_tax_inligible_itc_17_5']) ? $_POST['central_tax_inligible_itc_17_5'] : '';
		$dataArr['state_tax_inligible_itc_17_5'] = isset($_POST['state_tax_inligible_itc_17_5']) ? $_POST['state_tax_inligible_itc_17_5'] : '';
		$dataArr['cess_tax_inligible_itc_17_5'] = isset($_POST['cess_tax_inligible_itc_17_5']) ? $_POST['cess_tax_inligible_itc_17_5'] : '';
		$dataArr['integrated_tax_inligible_itc_others'] = isset($_POST['integrated_tax_inligible_itc_others']) ? $_POST['integrated_tax_inligible_itc_others'] : '';
		$dataArr['central_tax_inligible_itc_others'] = isset($_POST['central_tax_inligible_itc_others']) ? $_POST['central_tax_inligible_itc_others'] : '';
		$dataArr['state_tax_inligible_itc_others'] = isset($_POST['state_tax_inligible_itc_others']) ? $_POST['state_tax_inligible_itc_others'] : '';
		$dataArr['cess_tax_inligible_itc_others'] = isset($_POST['cess_tax_inligible_itc_others']) ? $_POST['cess_tax_inligible_itc_others'] : '';
		$dataArr['inter_state_supplies_composition_scheme'] = isset($_POST['inter_state_supplies_composition_scheme']) ? $_POST['inter_state_supplies_composition_scheme'] : '';
		$dataArr['intra_state_supplies_composition_scheme'] = isset($_POST['intra_state_supplies_composition_scheme']) ? $_POST['intra_state_supplies_composition_scheme'] : '';
		$dataArr['inter_state_supplies_nongst_supply'] = isset($_POST['inter_state_supplies_nongst_supply']) ? $_POST['inter_state_supplies_nongst_supply'] : '';
		$dataArr['intra_state_supplies_nongst_supply'] = isset($_POST['intra_state_supplies_nongst_supply']) ? $_POST['intra_state_supplies_nongst_supply'] : '';
		$dataArr['tax_payable_integrated_tax'] = isset($_POST['tax_payable_integrated_tax']) ? $_POST['tax_payable_integrated_tax'] : '';
		$dataArr['integrated_fee_integrated_tax'] = isset($_POST['integrated_fee_integrated_tax']) ? $_POST['integrated_fee_integrated_tax'] : '';
		$dataArr['central_integrated_tax'] = isset($_POST['central_integrated_tax']) ? $_POST['central_integrated_tax'] : '';
		$dataArr['state_integrated_tax'] = isset($_POST['state_integrated_tax']) ? $_POST['state_integrated_tax'] : '';
		$dataArr['cess_integrated_tax'] = isset($_POST['cess_integrated_tax']) ? $_POST['cess_integrated_tax'] : '';
		$dataArr['taxpaid_tdstcs_integrated_tax'] = isset($_POST['taxpaid_tdstcs_integrated_tax']) ? $_POST['taxpaid_tdstcs_integrated_tax'] : '';
		$dataArr['taxpaid_cess_integrated_tax'] = isset($_POST['taxpaid_cess_integrated_tax']) ? $_POST['taxpaid_cess_integrated_tax'] : '';
		$dataArr['interest_integrated_tax'] = isset($_POST['interest_integrated_tax']) ? $_POST['interest_integrated_tax'] : '';
		$dataArr['latefee_integrated_tax'] = isset($_POST['latefee_integrated_tax']) ? $_POST['latefee_integrated_tax'] : '';
		$dataArr['tax_payable_central_tax'] = isset($_POST['tax_payable_central_tax']) ? $_POST['tax_payable_central_tax'] : '';
		$dataArr['integrated_fee_central_tax'] = isset($_POST['integrated_fee_central_tax']) ? $_POST['integrated_fee_central_tax'] : '';
		$dataArr['central_central_tax'] = isset($_POST['central_central_tax']) ? $_POST['central_central_tax'] : '';
		$dataArr['state_central_tax'] = isset($_POST['state_central_tax']) ? $_POST['state_central_tax'] : '';
		$dataArr['cess_central_tax'] = isset($_POST['cess_central_tax']) ? $_POST['cess_central_tax'] : '';
		$dataArr['taxpaid_tdstcs_central_tax'] = isset($_POST['taxpaid_tdstcs_central_tax']) ? $_POST['taxpaid_tdstcs_central_tax'] : '';
		$dataArr['taxpaid_cess_central_tax'] = isset($_POST['taxpaid_cess_central_tax']) ? $_POST['taxpaid_cess_central_tax'] : '';
		$dataArr['interest_central_tax'] = isset($_POST['interest_central_tax']) ? $_POST['interest_central_tax'] : '';
		$dataArr['latefee_central_tax'] = isset($_POST['latefee_central_tax']) ? $_POST['latefee_central_tax'] : '';
		$dataArr['tax_payable_stateut_tax'] = isset($_POST['tax_payable_stateut_tax']) ? $_POST['tax_payable_stateut_tax'] : '';
		$dataArr['integrated_stateut_tax'] = isset($_POST['integrated_stateut_tax']) ? $_POST['integrated_stateut_tax'] : '';
		$dataArr['central_stateut_tax'] = isset($_POST['central_stateut_tax']) ? $_POST['central_stateut_tax'] : '';
		$dataArr['state_stateut_tax'] = isset($_POST['state_stateut_tax']) ? $_POST['state_stateut_tax'] : '';
		$dataArr['cess_stateut_tax'] = isset($_POST['cess_stateut_tax']) ? $_POST['cess_stateut_tax'] : '';
		$dataArr['taxpaid_tcs_stateut_tax'] = isset($_POST['taxpaid_tcs_stateut_tax']) ? $_POST['taxpaid_tcs_stateut_tax'] : '';
		$dataArr['taxpaid_cess_stateut_tax'] = isset($_POST['taxpaid_cess_stateut_tax']) ? $_POST['taxpaid_cess_stateut_tax'] : '';
		$dataArr['interest_stateut_tax'] = isset($_POST['interest_stateut_tax']) ? $_POST['interest_stateut_tax'] : '';
		$dataArr['latefee_stateut_tax'] = isset($_POST['latefee_stateut_tax']) ? $_POST['latefee_stateut_tax'] : '';
		$dataArr['integrated_tax_tds'] = isset($_POST['integrated_tax_tds']) ? $_POST['integrated_tax_tds'] : '';
		$dataArr['central_tax_tds'] = isset($_POST['central_tax_tds']) ? $_POST['central_tax_tds'] : '';
		$dataArr['state_tax_tds'] = isset($_POST['state_tax_tds']) ? $_POST['state_tax_tds'] : '';
		$dataArr['integrated_tax_tcs'] = isset($_POST['integrated_tax_tcs']) ? $_POST['integrated_tax_tcs'] : '';
		$dataArr['central_tax_tcs'] = isset($_POST['central_tax_tcs']) ? $_POST['central_tax_tcs'] : '';
		$dataArr['state_tax_tcs'] = isset($_POST['state_tax_tcs']) ? $_POST['state_tax_tcs'] : '';
		$dataArr['latefee_cess_tax'] = isset($_POST['latefee_cess_tax']) ? $_POST['latefee_cess_tax'] : '';
		$dataArr['interest_cess_tax'] = isset($_POST['interest_cess_tax']) ? $_POST['interest_cess_tax'] : '';
		$dataArr['taxpaid_cess_cess_tax'] = isset($_POST['taxpaid_cess_cess_tax']) ? $_POST['taxpaid_cess_cess_tax'] : '';
		$dataArr['taxpaid_tcs_cess_tax'] = isset($_POST['taxpaid_tcs_cess_tax']) ? $_POST['taxpaid_tcs_cess_tax'] : '';
		$dataArr['cess_cess_tax'] = isset($_POST['cess_cess_tax']) ? $_POST['cess_cess_tax'] : '';
		$dataArr['state_cess_tax'] = isset($_POST['state_cess_tax']) ? $_POST['state_cess_tax'] : '';
		$dataArr['central_cess_tax'] = isset($_POST['central_cess_tax']) ? $_POST['central_cess_tax'] : '';
		$dataArr['tax_payable_cess_tax'] = isset($_POST['tax_payable_cess_tax']) ? $_POST['tax_payable_cess_tax'] : '';
		$dataArr['integrated_cess_tax'] = isset($_POST['integrated_cess_tax']) ? $_POST['integrated_cess_tax'] : '';
		$dataArr['interest_latefees_integrated_tax'] = isset($_POST['interest_latefees_integrated_tax']) ? $_POST['interest_latefees_integrated_tax'] : '';
	    $dataArr['interest_latefees_central_tax'] = isset($_POST['interest_latefees_central_tax']) ? $_POST['interest_latefees_central_tax'] : '';
	    $dataArr['interest_latefees_state_tax'] = isset($_POST['interest_latefees_state_tax']) ? $_POST['interest_latefees_state_tax'] : '';
	    $dataArr['interest_latefees_cess_tax'] = isset($_POST['interest_latefees_cess_tax']) ? $_POST['interest_latefees_cess_tax'] : '';
		$dataArr['return_filling_date'] = date('Y-m-d H:i:s');
		$dataArr['added_by'] = $_SESSION['user_detail']['user_id'];
		$dataArr['is_deleted'] = 0;
		return $dataArr;
	}
	 
   
    
}