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
    
  
    public function gstTransitionData()
	{
		$dataArr = array();
		$data = array();
		$data['5a_registration_no']='';
		if(!empty($_POST['5a_registration_no'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['5a_registration_no'] as $selected){
			 
             $data['5a_registration_no'] = $data['5a_registration_no'].$selected.',';
			
			} 
			$data['5a_registration_no'] = rtrim($data['5a_registration_no'],",");
			}
			
		
		
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
		//code s5cform
		$data['5cform_registration_no']='';
		$data['5cform_balanceof_itc_vat']='';
		$data['5cform_cform_turnover_form_pending']='';
		$data['5cform_cform_taxpayable']='';
		$data['5cform_fform_turnover_form_pending']='';
		$data['5cform_fform_taxpayable']='';
		$data['5cform_itcreversal_relatable']='';
		$data['5cform_hiform_turnover_form_pending']='';
		$data['5cform_hiform_taxpayable']='';
		$data['5cform_hiform_transitionitc2']='';
		//end here
		$data['6ainvoice_document_no']='';
		$data['6ainvoice_document_date']='';
		$data['6asupplier_registration_no']='';
		$data['6arecipients_registration_no']='';
		$data['6a_value']='';
		$data['6a_ed_cvd']='';
		$data['6a_sad']='';
		$data['6a_totaleligible_cenvat']='';
		$data['6a_totalcenvat_credit1']='';
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
		$data['6binvoice_document_no']='';
		$data['6binvoice_document_date']='';
		$data['6bsupplier_registration_no']='';
		$data['6breceipients_registration_no']='';
		$data['6b_value']='';
		$data['6b_taxpaid_vat']='';
		$data['6b_totaleligible_vat']='';
		$data['6b_totalvat_creditavailed']='';
		$data['6b_totalvat_creditunavailed']='';
		$data['7a1_hsncode']='';
		$data['7a1_unit']='';
		$data['7a1_qty']='';
		$data['7a1_value']='';
		$data['7a1_eligible_duties']='';
		$data['7a2_hsncode']='';
		$data['7a2_unit']='';
		$data['7a2_qty']='';
		$data['7a2_value']='';
		$data['7a2_eligible_duties']='';
		$data['7a3_hsncode']='';
		$data['7a3_unit']='';
		$data['7a3_qty']='';
		$data['7a3_value']='';
		$data['7a3_eligible_duties']='';
		$data['7b_nameof_supplier']='';
		$data['7b_invoice_number']='';
		$data['7b_invoice_date']='';
		$data['7b_description']='';
		$data['7b_quantity']='';
		$data['7b_uqc']='';
		$data['7b_value']='';
		$data['7b_eligible_duties']='';
		$data['7b_vat']='';
		$data['7b_dateonwhich_receipients']='';
		$data['7c1_description']='';
		$data['7c1_unit']='';
		$data['7c1_qty']='';
		$data['7c1_value']='';
		$data['7c1_vat']='';
		$data['7c1_totalinput_taxcredit']='';
		$data['7c1_totalinput_taxcredit_exempt']='';
		$data['7c1_totalinput_taxcredit_admissible']='';
		$data['7c2_description']='';
		$data['7c2_unit']='';
		$data['7c2_qty']='';
		$data['7c2_value']='';
		$data['7c2_vat']='';
		$data['7c2_totalinput_taxcredit']='';
		$data['7c2_totalinput_taxcredit_exempt']='';
		$data['7c2_totalinput_taxcredit_admissible']='';
		$data['7d_description']='';
		$data['7d_unit']='';
		$data['7d_qty']='';
		$data['7d_value']='';
		$data['7d_vatentry_taxpad']='';
		$data['8registration_no']='';
		$data['8taxperiod_lastreturn']='';
		$data['8dateoffilling_return']='';
		$data['8balanceeligible_cenvat_credit']='';
		$data['8gstnof_receiver']='';
		$data['8distributionno']='';
		$data['8distributiondate']='';
		$data['8itcofcentral']='';
		$data['9a1challan_no']='';
		$data['9a1challan_date']='';
		$data['9a1typeof_goods']='';
		$data['9a1_hsn']='';
		$data['9a1_description']='';
		$data['9a1_unit']='';
		$data['9a1_quantity']='';
		$data['9a1_value']='';
		$data['9b1challan_no']='';
		$data['9b1challan_date']='';
		$data['9b1typeof_goods']='';
		$data['9b1_hsn']='';
		$data['9b1_description']='';
		$data['9b1_unit']='';
		$data['9b1_quantity']='';
		$data['9b1_value']='';
		$data['11aregistration_no']='';
		$data['11aservicetax_no']='';
		$data['11ainvoice_documentno']='';
		$data['11ainvoice_document_date']='';
		$data['11atax_paid']='';
		$data['11avatpaid_sgst']='';
		$data['12a_document_no']='';
		$data['12a_document_date']='';
		$data['12a_gstinno_receipient']='';
		$data['12a_name_receipient']='';
		$data['12a_hsn']='';
		$data['12a_description']='';
		$data['12a_unit']='';
		$data['12a_quantity']='';
		$data['12a_value']='';
		$data['b5bhiform_applicable_vat_rate']='';
		$data['5a_taxperiod_last_return']='';
		$data['10a_gstn']='';
		$data['10a_description']='';
		$data['10a_unit']='';
		$data['10a_quantity']='';
		$data['10a_value']='';
		$data['10a_inputtax']='';
		$data['10b_gstn']='';
		$data['10b_description']='';
		$data['10b_unit']='';
		$data['10b_quantity']='';
		$data['10b_value']='';
		$data['10b_inputtax']='';	
        $data['trader_name']='';
        $data['transition_status']='';		
				
		 $data['trader_name'] = isset($_POST['trader_name']) ? $_POST['trader_name'] : ''; 
		
		 $data['transition_status'] = isset($_POST['transition_status']) ? $_POST['transition_status'] : ''; 
		
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
			if(!empty($_POST['6asupplier_registration_no'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['6asupplier_registration_no'] as $selected){
			 
             $data['6asupplier_registration_no'] = $data['6asupplier_registration_no'].$selected.',';
			
			}
			
			$data['6asupplier_registration_no'] = rtrim($data['6asupplier_registration_no'], ",");
			
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
			if(!empty($_POST['6a_totalcenvat_credit1'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['6a_totalcenvat_credit1'] as $selected){
			 
             $data['6a_totalcenvat_credit1'] = $data['6a_totalcenvat_credit1'].$selected.',';
			
			}
			
			$data['6a_totalcenvat_credit1'] = rtrim($data['6a_totalcenvat_credit1'], ",");
			
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
			if(!empty($_POST['9b1_description'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['9b1_description'] as $selected){
			 
             $data['9b1_description'] = $data['9b1_description'].$selected.',';
			
			}	
			
			$data['9b1_description'] = rtrim($data['9b1_description'], ",");
			
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
			if(!empty($_POST['11atax_paid'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['11atax_paid'] as $selected){
			 
             $data['11atax_paid'] = $data['11atax_paid'].$selected.',';
			
			}	
			
			$data['11atax_paid'] = rtrim($data['11atax_paid'], ",");
			
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
			
			//10a code start here
			if(!empty($_POST['10a_gstn'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['10a_gstn'] as $selected){
			 
             $data['10a_gstn'] = $data['10a_gstn'].$selected.',';
			
			}	
			
			$data['10a_gstn'] = rtrim($data['10a_gstn'], ",");
			
			}
			if(!empty($_POST['10a_description'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['10a_description'] as $selected){
			 
             $data['10a_description'] = $data['10a_description'].$selected.',';
			
			}	
			
			$data['10a_description'] = rtrim($data['10a_description'], ",");
			
			}
			if(!empty($_POST['10a_unit'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['10a_unit'] as $selected){
			 
             $data['10a_unit'] = $data['10a_unit'].$selected.',';
			
			}	
			
			$data['10a_unit'] = rtrim($data['10a_unit'], ",");
			
			}
			if(!empty($_POST['10a_quantity'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['10a_quantity'] as $selected){
			 
             $data['10a_quantity'] = $data['10a_quantity'].$selected.',';
			
			}	
			
			$data['10a_quantity'] = rtrim($data['10a_quantity'], ",");
			
			}
			if(!empty($_POST['10a_value'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['10a_value'] as $selected){
			 
             $data['10a_value'] = $data['10a_value'].$selected.',';
			
			}	
			
			$data['10a_value'] = rtrim($data['10a_value'], ",");
			
			}
			if(!empty($_POST['10a_inputtax'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['10a_inputtax'] as $selected){
			 
             $data['10a_inputtax'] = $data['10a_inputtax'].$selected.',';
			
			}	
			
			$data['10a_inputtax'] = rtrim($data['10a_inputtax'], ",");
			
			}
			
			//end here
			//start code for 10b
			if(!empty($_POST['10b_gstn'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['10b_gstn'] as $selected){
			 
             $data['10b_gstn'] = $data['10b_gstn'].$selected.',';
			
			}	
			
			$data['10b_gstn'] = rtrim($data['10b_gstn'], ",");
			
			}
			if(!empty($_POST['10b_description'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['10b_description'] as $selected){
			 
             $data['10b_description'] = $data['10b_description'].$selected.',';
			
			}	
			
			$data['10b_description'] = rtrim($data['10b_description'], ",");
			
			}
			if(!empty($_POST['10b_unit'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['10b_unit'] as $selected){
			 
             $data['10b_unit'] = $data['10b_unit'].$selected.',';
			
			}	
			
			$data['10b_unit'] = rtrim($data['10b_unit'], ",");
			
			}
			if(!empty($_POST['10b_quantity'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['10b_quantity'] as $selected){
			 
             $data['10b_quantity'] = $data['10b_quantity'].$selected.',';
			
			}	
			
			$data['10b_quantity'] = rtrim($data['10b_quantity'], ",");
			
			}
			if(!empty($_POST['10b_value'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['10b_value'] as $selected){
			 
             $data['10b_value'] = $data['10b_value'].$selected.',';
			
			}	
			
			$data['10b_value'] = rtrim($data['10b_value'], ",");
			
			}
			if(!empty($_POST['10b_inputtax'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['10b_inputtax'] as $selected){
			 
             $data['10b_inputtax'] = $data['10b_inputtax'].$selected.',';
			
			}	
			
			$data['10b_inputtax'] = rtrim($data['10b_inputtax'], ",");
			
			}
			//code for 5cform 
			if(!empty($_POST['5cform_registration_no'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['5cform_registration_no'] as $selected){
			 
             $data['5cform_registration_no'] = $data['5cform_registration_no'].$selected.',';
			
			}	
			
			$data['5cform_registration_no'] = rtrim($data['5cform_registration_no'], ",");
			
			}
			if(!empty($_POST['5cform_balanceof_itc_vat'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['5cform_balanceof_itc_vat'] as $selected){
			 
             $data['5cform_balanceof_itc_vat'] = $data['5cform_balanceof_itc_vat'].$selected.',';
			
			}	
			
			$data['5cform_balanceof_itc_vat'] = rtrim($data['5cform_balanceof_itc_vat'], ",");
			
			}
			if(!empty($_POST['5cform_cform_turnover_form_pending'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['5cform_cform_turnover_form_pending'] as $selected){
			 
             $data['5cform_cform_turnover_form_pending'] = $data['5cform_cform_turnover_form_pending'].$selected.',';
			
			}	
			
			$data['5cform_cform_turnover_form_pending'] = rtrim($data['5cform_cform_turnover_form_pending'], ",");
			
			}
			if(!empty($_POST['5cform_fform_turnover_form_pending'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['5cform_fform_turnover_form_pending'] as $selected){
			 
             $data['5cform_fform_turnover_form_pending'] = $data['5cform_fform_turnover_form_pending'].$selected.',';
			
			}	
			
			$data['5cform_fform_turnover_form_pending'] = rtrim($data['5cform_fform_turnover_form_pending'], ",");
			
			}
			
			if(!empty($_POST['5cform_cform_taxpayable'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['5cform_cform_taxpayable'] as $selected){
			 
             $data['5cform_cform_taxpayable'] = $data['5cform_cform_taxpayable'].$selected.',';
			
			}	
			
			$data['5cform_cform_taxpayable'] = rtrim($data['5cform_cform_taxpayable'], ",");
			
			}
			if(!empty($_POST['5cform_cform_turnover_form_pending'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['5cform_cform_turnover_form_pending'] as $selected){
			 
             $data['5cform_cform_turnover_form_pending'] = $data['5cform_cform_turnover_form_pending'].$selected.',';
			
			}	
			
			$data['5cform_cform_turnover_form_pending'] = rtrim($data['5cform_cform_turnover_form_pending'], ",");
			
			}
			if(!empty($_POST['5cform_fform_taxpayable'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['5cform_fform_taxpayable'] as $selected){
			 
             $data['5cform_fform_taxpayable'] = $data['5cform_fform_taxpayable'].$selected.',';
			
			}	
			
			$data['5cform_fform_taxpayable'] = rtrim($data['5cform_fform_taxpayable'], ",");
			
			}
			
			if(!empty($_POST['5cform_itcreversal_relatable'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['5cform_itcreversal_relatable'] as $selected){
			 
             $data['5cform_itcreversal_relatable'] = $data['5cform_itcreversal_relatable'].$selected.',';
			
			}	
			
			$data['5cform_itcreversal_relatable'] = rtrim($data['5cform_itcreversal_relatable'], ",");
			
			}
			if(!empty($_POST['5cform_hiform_turnover_form_pending'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['5cform_hiform_turnover_form_pending'] as $selected){
			 
             $data['5cform_hiform_turnover_form_pending'] = $data['5cform_hiform_turnover_form_pending'].$selected.',';
			
			}	
			
			$data['5cform_hiform_turnover_form_pending'] = rtrim($data['5cform_hiform_turnover_form_pending'], ",");
			
			}
			if(!empty($_POST['5cform_hiform_taxpayable'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['5cform_hiform_taxpayable'] as $selected){
			 
             $data['5cform_hiform_taxpayable'] = $data['5cform_hiform_taxpayable'].$selected.',';
			
			}	
			
			$data['5cform_hiform_taxpayable'] = rtrim($data['5cform_hiform_taxpayable'], ",");
			
			}
			if(!empty($_POST['5cform_hiform_transitionitc2'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['5cform_hiform_transitionitc2'] as $selected){
			 
             $data['5cform_hiform_transitionitc2'] = $data['5cform_hiform_transitionitc2'].$selected.',';
			
			}	
			
			$data['5cform_hiform_transitionitc2'] = rtrim($data['5cform_hiform_transitionitc2'], ",");
			
			}
			//end here
			//code for 7a2 and 7a3 
			if(!empty($_POST['7a2_hsncode'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['7a2_hsncode'] as $selected){
			 
             $data['7a2_hsncode'] = $data['7a2_hsncode'].$selected.',';
			
			}	
			
			$data['7a2_hsncode'] = rtrim($data['7a2_hsncode'], ",");
			
			}
			if(!empty($_POST['7a2_unit'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['7a2_unit'] as $selected){
			 
             $data['7a2_unit'] = $data['7a2_unit'].$selected.',';
			
			}	
			
			$data['7a2_unit'] = rtrim($data['7a2_unit'], ",");
			
			}
			if(!empty($_POST['7a2_qty'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['7a2_qty'] as $selected){
			 
             $data['7a2_qty'] = $data['7a2_qty'].$selected.',';
			
			}	
			
			$data['7a2_qty'] = rtrim($data['7a2_qty'], ",");
			
			}
			if(!empty($_POST['7a2_value'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['7a2_value'] as $selected){
			 
             $data['7a2_value'] = $data['7a2_value'].$selected.',';
			
			}	
			
			$data['7a2_value'] = rtrim($data['7a2_value'], ",");
			
			}
			if(!empty($_POST['7a2_eligible_duties'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['7a2_eligible_duties'] as $selected){
			 
             $data['7a2_eligible_duties'] = $data['7a2_eligible_duties'].$selected.',';
			
			}	
			
			$data['7a2_eligible_duties'] = rtrim($data['7a2_eligible_duties'], ",");
			
			}
			if(!empty($_POST['7a3_hsncode'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['7a3_hsncode'] as $selected){
			 
             $data['7a3_hsncode'] = $data['7a3_hsncode'].$selected.',';
			
			}	
			
			$data['7a3_hsncode'] = rtrim($data['7a3_hsncode'], ",");
			
			}
			if(!empty($_POST['7a3_unit'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['7a3_unit'] as $selected){
			 
             $data['7a3_unit'] = $data['7a3_unit'].$selected.',';
			
			}	
			
			$data['7a3_unit'] = rtrim($data['7a3_unit'], ",");
			
			}
			if(!empty($_POST['7a3_qty'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['7a3_qty'] as $selected){
			 
             $data['7a3_qty'] = $data['7a3_qty'].$selected.',';
			
			}	
			
			$data['7a3_qty'] = rtrim($data['7a3_qty'], ",");
			
			}
			if(!empty($_POST['7a3_value'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['7a3_value'] as $selected){
			 
             $data['7a3_value'] = $data['7a3_value'].$selected.',';
			
			}	
			
			$data['7a3_value'] = rtrim($data['7a3_value'], ",");
			
			}
			if(!empty($_POST['7a3_eligible_duties'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['7a3_eligible_duties'] as $selected){
			 
             $data['7a3_eligible_duties'] = $data['7a3_eligible_duties'].$selected.',';
			
			}	
			
			$data['7a3_eligible_duties'] = rtrim($data['7a3_eligible_duties'], ",");
			
			}
			//end here
			//7c2 code start here
			
	
		
		
		if(!empty($_POST['7c2_description'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['7c2_description'] as $selected){
			 
             $data['7c2_description'] = $data['7c2_description'].$selected.',';
			
			}	
			
			$data['7c2_description'] = rtrim($data['7c2_description'], ",");
			
			}
			if(!empty($_POST['7c2_unit'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['7c2_unit'] as $selected){
			 
             $data['7c2_unit'] = $data['7c2_unit'].$selected.',';
			
			}	
			
			$data['7c2_unit'] = rtrim($data['7c2_unit'], ",");
			
			}
			if(!empty($_POST['7c2_qty'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['7c2_qty'] as $selected){
			 
             $data['7c2_qty'] = $data['7c2_qty'].$selected.',';
			
			}	
			
			$data['7c2_qty'] = rtrim($data['7c2_qty'], ",");
			
			}
			if(!empty($_POST['7c2_vat'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['7c2_vat'] as $selected){
			 
             $data['7c2_vat'] = $data['7c2_vat'].$selected.',';
			
			}	
			
			$data['7c2_vat'] = rtrim($data['7c2_vat'], ",");
			
			}
			if(!empty($_POST['7c2_value'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['7c2_value'] as $selected){
			 
             $data['7c2_value'] = $data['7c2_value'].$selected.',';
			
			}	
			
			$data['7c2_value'] = rtrim($data['7c2_value'], ",");
			
			}
			if(!empty($_POST['7c2_totalinput_taxcredit'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['7c2_totalinput_taxcredit'] as $selected){
			 
             $data['7c2_totalinput_taxcredit'] = $data['7c2_totalinput_taxcredit'].$selected.',';
			
			}	
			
			$data['7c2_totalinput_taxcredit'] = rtrim($data['7c2_totalinput_taxcredit'], ",");
			
			}
			if(!empty($_POST['7c2_totalinput_taxcredit_exempt'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['7c2_totalinput_taxcredit_exempt'] as $selected){
			 
             $data['7c2_totalinput_taxcredit_exempt'] = $data['7c2_totalinput_taxcredit_exempt'].$selected.',';
			
			}	
			
			$data['7c2_totalinput_taxcredit_exempt'] = rtrim($data['7c2_totalinput_taxcredit_exempt'], ",");
			
			}
			if(!empty($_POST['7c2_totalinput_taxcredit_admissible'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['7c2_totalinput_taxcredit_admissible'] as $selected){
			 
             $data['7c2_totalinput_taxcredit_admissible'] = $data['7c2_totalinput_taxcredit_admissible'].$selected.',';
			
			}	
			
			$data['7c2_totalinput_taxcredit_admissible'] = rtrim($data['7c2_totalinput_taxcredit_admissible'], ",");
			
			}
			
		
		
			//end here
			//9b1 code end here
			$data5a[]=array("a5_registration_no"=>$data['5a_registration_no'],"a5_taxperiod_last_return"=>$data['5a_taxperiod_last_return'],"a5_taxperiod_last_return"=>$data['5a_taxperiod_last_return'],"a5_dateoffilling_return"=>$data["5a_dateoffilling_return"],"a5_balance_cenvat_credit"=>$data["5a_balance_cenvat_credit"],"a5_cenvat_credit_admissible"=>$data["5a_cenvat_credit_admissible"],"b5bcform_tin_issuer"=>$data["5bcform_tin_issuer"],"b5bcform_nameof_issuer"=>$data["5bcform_nameof_issuer"],"b5bcform_no_of_item"=>$data["5bcform_no_of_item"],"b5bcform_amount"=>$data["5bcform_amount"],"b5bcform_applicable_vat_rate"=>$data["5bcform_applicable_vat_rate"],"b5bfform_tin_issuer"=>$data["5bfform_tin_issuer"],"b5bfform_nameof_issuer"=>$data["5bfform_nameof_issuer"],"b5bfform_no_of_form"=>$data["5bfform_no_of_form"],"b5bfform_amount"=>$data["5bfform_amount"],"b5bfform_applicable_vat_rate"=>$data["5bfform_applicable_vat_rate"],"b5bhiform_tin_issuer"=>$data["5bhiform_tin_issuer"],"b5bhiform_nameof_issuer"=>$data["5bhiform_nameof_issuer"],"b5bhiform_no_of_form"=>$data["5bhiform_no_of_form"],"b5bhiform_amount"=>$data["5bhiform_amount"],"b5bhiform_applicable_vat_rate"=>$data["5bhiform_applicable_vat_rate"],"a6ainvoice_document_no"=>$data['6ainvoice_document_no'],"a6ainvoice_document_date"=>$data['6ainvoice_document_date'],"a6asupplier_registration_no"=>$data['6asupplier_registration_no'],"a6arecipients_registration_no"=>$data['6arecipients_registration_no'],"a6a_value"=>$data['6a_value'],"a6a_ed_cvd"=>$data['6a_ed_cvd'],"a6a_sad"=>$data['6a_sad'],"a6a_totaleligible_cenvat"=>$data['6a_totaleligible_cenvat'],"a6a_totalcenvat_credit"=>$data['6a_totalcenvat_credit1'],"a6a_totalcenvat_credit_unavailed"=>$data['6a_totalcenvat_credit_unavailed'],"b6binvoice_document_no"=>$data['6binvoice_document_no'],"b6binvoice_document_date"=>$data['6binvoice_document_date'],"b6bsupplier_registration_no"=>$data['6bsupplier_registration_no'],"b6breceipients_registration_no"=>$data['6breceipients_registration_no'],"b6b_value"=>$data['6b_value'],"b6b_taxpaid_vat"=>$data['6b_taxpaid_vat'],"b6b_totaleligible_vat"=>$data['6b_totaleligible_vat'],"b6b_totalvat_creditavailed"=>$data['6b_totalvat_creditavailed'],"b6b_totalvat_creditunavailed"=>$data['6b_totalvat_creditunavailed'],"b6b_totalvat_creditavailed"=>$data['6b_totalvat_creditavailed'],"a7a1_hsncode"=>$data['7a1_hsncode'],"a7a1_unit"=>$data['7a1_unit'],"a7a1_qty"=>$data['7a1_qty'],"a7a1_value"=>$data['7a1_value'],"a7a1_eligible_duties"=>$data['7a1_eligible_duties'],"b7b_nameof_supplier"=>$data['7b_nameof_supplier'],"b7b_invoice_number"=>$data['7b_invoice_number'],"b7b_invoice_date"=>$data['7b_invoice_date'],"b7b_description"=>$data['7b_description'],"b7b_quantity"=>$data['7b_quantity'],"b7b_uqc"=>$data['7b_uqc'],"b7b_value"=>$data['7b_value'],"b7b_eligible_duties"=>$data['7b_eligible_duties'],"b7b_vat"=>$data['7b_vat'],"b7b_dateonwhich_receipients"=>$data['7b_dateonwhich_receipients'],"c7c1_description"=>$data['7c1_description'],"c7c1_unit"=>$data['7c1_unit'],"c7c1_qty"=>$data['7c1_qty'],"c7c1_value"=>$data['7c1_value'],"c7c1_vat"=>$data['7c1_vat'],"c7c1_totalinput_taxcredit"=>$data['7c1_totalinput_taxcredit'],"c7c1_totalinput_taxcredit_exempt"=>$data['7c1_totalinput_taxcredit_exempt'],"c7c1_totalinput_taxcredit_admissible"=>$data['7c1_totalinput_taxcredit_admissible'],"d7d_description"=>$data['7d_description'],"d7d_unit"=>$data['7d_unit'],"d7d_qty"=>$data['7d_qty'],"d7d_value"=>$data['7d_value'],"d7d_vatentry_taxpad"=>$data['7d_vatentry_taxpad'],"a8registration_no"=>$data['8registration_no'],"a8taxperiod_lastreturn"=>$data['8taxperiod_lastreturn'],"a8dateoffilling_return"=>$data['8dateoffilling_return'],"a8balanceeligible_cenvat_credit"=>$data['8balanceeligible_cenvat_credit'],"a8gstnof_receiver"=>$data['8gstnof_receiver'],"a8distributionno"=>$data['8distributionno'],"a8distributiondate"=>$data['8distributiondate'],"a8itcofcentral"=>$data['8itcofcentral'],"a9a1challan_no"=>$data['9a1challan_no'],"a9a1challan_date"=>$data['9a1challan_date'],"a9a1typeof_goods"=>$data['9a1typeof_goods'],"a9a1_hsn"=>$data['9a1_hsn'],"a9a1_description"=>$data['9a1_description'],"a9a1_unit"=>$data['9a1_unit'],"a9a1_quantity"=>$data['9a1_quantity'],"a9a1_value"=>$data['9a1_value'],"b9b1challan_no"=>$data['9b1challan_no'],"b9b1challan_date"=>$data['9b1challan_date'],"b9b1typeof_goods"=>$data['9b1typeof_goods'],"b9b1_hsn"=>$data['9b1_hsn'],"b9b1_description"=>$data['9b1_description'],"b9b1_unit"=>$data['9b1_unit'],"b9b1_quantity"=>$data['9b1_quantity'],"b9b1_value"=>$data['9b1_value'],"a11aregistration_no"=>$data['11aregistration_no'],"a11aservicetax_no"=>$data['11aservicetax_no'],"a11ainvoice_documentno"=>$data['11ainvoice_documentno'],"a11ainvoice_document_date"=>$data['11ainvoice_document_date'],"a11atax_paid"=>$data['11atax_paid'],"a11avatpaid_sgst"=>$data['11avatpaid_sgst'],"a12a_document_no"=>$data['12a_document_no'],"a12a_document_no"=>$data['12a_document_no'],"a12a_document_date"=>$data['12a_document_date'],"a12a_gstinno_receipient"=>$data['12a_gstinno_receipient'],"a12a_name_receipient"=>$data['12a_name_receipient'],"a12a_hsn"=>$data['12a_hsn'],"a12a_description"=>$data['12a_description'],"a12a_unit"=>$data['12a_unit'],"a12a_quantity"=>$data['12a_quantity'],"a12a_value"=>$data['12a_value'],"a10a_gstn"=>$data['10a_gstn'],"a10a_description"=>$data['10a_description'],"a10a_unit"=>$data['10a_unit'],"a10a_quantity"=>$data['10a_quantity'],"a10a_value"=>$data['10a_value'],"a10a_inputtax"=>$data['10a_inputtax'],"b10b_gstn"=>$data['10b_gstn'],"b10b_description"=>$data['10b_description'],"b10b_unit"=>$data['10b_unit'],"b10b_quantity"=>$data['10b_quantity'],"b10b_value"=>$data['10b_value'],"b10b_inputtax"=>$data['10b_inputtax'],"c5cform_registration_no"=>$data['5cform_registration_no'],"c5cform_balanceof_itc_val"=>$data['5cform_balanceof_itc_vat'],"c5cform_cform_turnover_form_pending"=>$data['5cform_cform_turnover_form_pending'],"c5cform_cform_taxpayable"=>$data['5cform_cform_taxpayable'],"c5cform_fform_turnover_form_pending"=>$data['5cform_fform_turnover_form_pending'],"c5cform_fform_taxpayable"=>$data['5cform_fform_taxpayable'],"c5cform_itcreversal_relatable"=>$data['5cform_itcreversal_relatable'],"c5cform_hiform_turnover_form_pending"=>$data['5cform_hiform_turnover_form_pending'],"c5cform_hiform_taxpayable"=>$data['5cform_hiform_taxpayable'],"c5cform_hiform_transitionitc2"=>$data['5cform_hiform_transitionitc2'],"a7a1_hsncode"=>$data['7a1_hsncode'],"a7a1_hsncode"=>$data['7a1_hsncode'],"a7a1_qty"=>$data['7a1_qty'],"a7a1_value"=>$data['7a1_value'],"a7a1_eligible_duties"=>$data['7a1_eligible_duties'],"a7a2_hsncode"=>$data['7a2_hsncode'],"a7a2_unit"=>$data['7a2_unit'],"a7a3_unit"=>$data['7a3_unit'],"a7a2_qty"=>$data['7a2_qty'],"a7a2_value"=>$data['7a2_value'],"a7a2_eligible_duties"=>$data['7a2_eligible_duties'],"a7a3_hsncode"=>$data['7a3_hsncode'],"a7a3_qty"=>$data['7a3_qty'],"a7a3_value"=>$data['7a3_value'],"a7a3_eligible_duties"=>$data['7a3_eligible_duties'],"c7c2_description"=>$data['7c2_description'],"c7c2_unit"=>$data['7c2_unit'],"c7c2_qty"=>$data['7c2_qty'],"c7c2_value"=>$data['7c2_value'],"c7c2_vat"=>$data['7c2_vat'],"c7c2_totalinput_taxcredit"=>$data['7c2_totalinput_taxcredit'],"c7c2_totalinput_taxcredit_exempt"=>$data['7c2_totalinput_taxcredit_exempt'],"c7c2_totalinput_taxcredit_admissible"=>$data['7c2_totalinput_taxcredit_admissible'],"trader_name"=>$data["trader_name"],"transition_status_name"=>$data["transition_status"]);
			//$data5b[]=array("5a_taxperiod_last_return"=>$dataArr['5a_taxperiod_last_return'],"5a_taxperiod_last_return"=>$data['5a_taxperiod_last_return'],"5a_dateoffilling_return"=>$data["5a_dateoffilling_return"],"5a_balance_cenvat_credit"=>$data["5a_balance_cenvat_credit"],"5a_cenvat_credit_admissible"=>$data["5a_cenvat_credit_admissible"]);
			

			
			
			//$dataArr['gstr_transition_data'] = base64_encode(json_encode(array("table5a"=>$data5a,"table5b"=>$data5b)));
		$dataArr['gstr_transition_data'] = base64_encode(json_encode($data5a));
			
			return $dataArr;
			
	}
    public function saveGstrTransition()
    {
		$data = $this->get_results("select * from gst_transition_form where added_by='".$_SESSION['user_detail']['user_id']."' and financial_month='".$this->sanitize($_GET['returnmonth'])."'");
		$dataArr = $this->gstTransitionData();
		 $dataArr['trader_name'] = isset($_POST['trader_name']) ? $_POST['trader_name'] : ''; 
	     $dataArr['transition_status'] = isset($_POST['transition_status']) ? $_POST['transition_status'] : '';
        $dataArr['terms_condition'] = isset($_POST['accept']) ? $_POST['accept'] : ''; 		 
		if($dataArr['terms_condition']==1)
		{
		}
		else{
			$this->setError('Please check the information provided by you is correct');
			return false;  
		}
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
			
			if ($this->insert('gst_transition_form', $dataArr)) {
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
			
			if ($this->update('gst_transition_form', $dataArr,array('added_by'=>$_SESSION['user_detail']['user_id'],'financial_month'=>$this->sanitize($_GET['returnmonth'])))) {
				//$this->getPlaceOfSupplyUnregistered();
				//$this->getPlaceOfSupplyComposition();
				//$this->getPlaceOfSupplyUinHolder();
		                      
				$this->setSuccess('GST transition month of '.$returnmonth."updated Successfully");
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
  
   public function generategst_transitionHtml($returnid,$returnmonth)
	{
	    
	       $htmlResponse = $this->generategst_transitionPdf($_SESSION['user_detail']['user_id'],$returnid,$returnmonth);
	        if ($htmlResponse === false) {

	            $obj_client->setError("No Plan Pdf found.");
	            return false;
	        }
	        $obj_mpdf = new mPDF();
	        $obj_mpdf->SetHeader('GST-Transition Form');
	        $obj_mpdf->WriteHTML($htmlResponse);
	        $datetime=date('Y-m-d-His');
	       
	       $taxInvoicePdf = 'gsttransitionform-' . $_SESSION['user_detail']['user_id'] . '_' .$datetime. '.pdf';
		   $filepath ="/upload/transition-form/".$taxInvoicePdf;
	        ob_clean();
	        //$proof_photograph = $this->gstr3bUploads($taxInvoicePdf, 'plan-invoice', 'upload','.pdf');
	        $pic = $taxInvoicePdf;
	     
			  ob_clean();
			  if($_GET['action'] == 'printInvoice')
			  {
				  
			 $obj_mpdf->Output($taxInvoicePdf, 'I');
			  }
			  else if($_GET['action'] == 'emailInvoice')
			  {
				  //$obj_mpdf->Output($taxInvoicePdf, PROJECT_URL ."/upload/gstr3b-file/");
				  $dataCurrentUserArr = $this->getUserDetailsById($this->sanitize($_SESSION['user_detail']['user_id']));
			$sendmail = $dataCurrentUserArr['data']->kyc->email;
			$name = $dataCurrentUserArr['data']->kyc->name;
			$userid = $_SESSION["user_detail"]["user_id"];
				  $obj_mpdf->Output("upload/gstr3b-file/".$taxInvoicePdf);
				  $mpdfHtml = $this->gstr3bemail($name,$returnmonth);
				 // return $mpdfHtml;
				  
			 if ($this->sendMail('Email GSTR-3Bfile', 'User ID : ' . $userid . ' email GSTR-3B', $sendmail, 'noreply@gstkeeper.com', '', 'rishap07@gmail.com,sheetalprasad95@gmail.com', $filepath, 'GSTR-3B return month '.$returnmonth.'',$mpdfHtml )) {

					$this->setSuccess('Kindly check your email');
					$this->redirect(PROJECT_URL . "?page=return_gstr3b_file&returnmonth=" . $returnmonth);
	               // return true;
	            } else {
	                $this->setError('Try again some issue in sending in email.');
						$this->redirect(PROJECT_URL . "?page=return_gstr3b_file&returnmonth=" . $returnmonth);
	               // return false;
	            }
			  }
			  else
			  {
				  $obj_mpdf->Output($taxInvoicePdf, 'D');
			  }
		 
				$this->logMsg("User ID : " . $_SESSION['user_detail']['user_id'] . " in User has been updated");
			   
	}
	private function generategst_transitionPdf($invid,$returnid,$returnmonth) {
		$sql = "select  *,count(id) as totalinvoice from gst_transition_form where added_by='" . $_SESSION['user_detail']['user_id'] . "' and financial_month like '%" . $returnmonth . "%' and is_deleted='0'  order by id desc limit 0,1";
       $returndata = $this->get_results($sql);
	   $sql = "select  *,count(id) as totalinvoice from gst_transition_form where added_by='" . $_SESSION['user_detail']['user_id'] . "' and financial_month like '%" . $returnmonth . "%' and is_deleted='0'  order by id desc limit 0,1";
 
       $returndata1 = $this->get_results($sql);
	    $sql = "select * from " . TAB_PREFIX . "client_kyc where added_by='" . $_SESSION['user_detail']['user_id'] . "' order by id desc limit 0,1";
	   $clientdata = $this->get_results($sql);
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
		  $mpdfHtml .='<html>';
		  $mpdfHtml .='<body>';
		    $mpdfHtml .= ' <div style="position: relative;min-height: 1px;padding-right: 0px;padding-left: 0px;" class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
		   <div style="width: 100%;position: relative;min-height: 1px;padding-right: 15px;padding-left:0px;position: relative;min-height: 1px padding-right: 0px;
		    padding-left: 15px; font-size:12px !important;" class="col-md-12 col-sm-12 col-xs-12">
		    <div style="position: relative;min-height: 1px padding-right: 15px;
		    padding-left: 15px;" class="col-md-6 col-sm-6 col-xs-12 text-right breadcrumb-nav"></div>
		    <div class="whitebg formboxcontainer">
			<div class="greyheading" style="float: left;width: 100%;font-size: 15px;background: #adadad;padding: 7px 10px;color: #FFF;font-family: opensans_bold;font-weight: normal;" class="greyheading" class="greyheading"
>5.Amount of Tax credit carried forward in the return file under existing laws</div>
		<br><div class="greyheading" style="float: left;width: 100%;font-size: 15px;background: #adadad;padding: 7px 10px;color: #FFF;font-family: opensans_bold;font-weight: normal;" class="greyheading" class="greyheading"
>5.(A)Amount of cenvat Credit carried forward to electronic credit ledger as central tax</div>';
$mpdfHtml .='<div class="tableresponsive">
<table border="1" bordercolor="#ccc" cellpadding="5" cellspacing="0" style="font-size:13px;   font-family: opensans_bold; font-weight:normal; width: 100%;font-weight:normal;" class="table  tablecontent tablecontent2 bordernone">
 <thead><tr><th>S.No</th><th>Registration(Central Excise and service tax)</th>
                       <th>Tax Return to which last return filled</th>
                       <th>DateOfFillingReturnSpecified in Column3</th>
                       <th>BalacneCenvat Carried forward in the said last return</th>
                       <th>Cenvat Carried admissible as ITC of central Tax</th>
                       </tr></thead><tbody>';                                          		   
                                
                                
                                   
								
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
								
									
						 
							  for($i=0;$i < sizeof($start); $i++) {
						   $sno =0;
                     		$sno = $i+1;				   
                           $mpdfHtml .='<tr> <td class="leftheading" >'.$sno.'</td>';
						   
                           $mpdfHtml .='<td>
									 <label>'.(!empty($a5_taxperiod_last_return[$i])?$a5_taxperiod_last_return[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						   $mpdfHtml .='<td>
									 <label>'.(!empty($a5_registration_no[$i])?$a5_registration_no[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						   $mpdfHtml .='<td>
									 <label>'.(!empty($a5_dateoffilling_return[$i])?$a5_dateoffilling_return[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						   $mpdfHtml .='<td>
									 <label>'.(!empty($a5_balance_cenvat_credit[$i])?$a5_balance_cenvat_credit[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						   $mpdfHtml .='<td>
									 <label>'.(!empty($a5_cenvat_credit_admissible[$i])?$a5_cenvat_credit_admissible[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						  $mpdfHtml .='</tr>';
								}	}							
						$mpdfHtml .='</tbody></table></div>';
                          
                       
        $mpdfHtml .='<br><div style="float: left;width: 100%;font-size: 15px;background: #adadad;padding: 7px 10px;color: #FFF;font-family: opensans_bold;font-weight: normal;" class="greyheading" class="greyheading">B. Details of statutory form received for which credit is being carried forward</div><br>
						 <div class="tableresponsive">
						 <div style="float: left;width: 100%;font-size: 15px;background: #adadad;padding: 7px 10px;color: #FFF;font-family: opensans_bold;font-weight: normal;" class="greyheading" class="greyheading">Amount of Tax credit forward to electronic credit</div>
						
                          <table border="1" bordercolor="#ccc" cellpadding="5" cellspacing="0" style="font-size:13px;   font-family: opensans_bold; font-weight:normal; width: 100%;font-weight:normal;" class="table  tablecontent tablecontent2 bordernone">

                                <thead>
                                <tr>
								 <th>S.No.</th>
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
                                            
                                </tr>';
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
								
						
							  for($i=0;$i < sizeof($start); $i++) {
						    $sno =0;
                     		$sno = $i+1;				   
                           $mpdfHtml .='<tr> <td class="leftheading" >'.$sno.'</td>';
						   
                           $mpdfHtml .='<td>
									 <label>'.(!empty($b5bcform_tin_issuer[$i])?$b5bcform_tin_issuer[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						   $mpdfHtml .='<td>
									 <label>'.(!empty($b5bcform_nameof_issuer[$i])?$b5bcform_nameof_issuer[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						   $mpdfHtml .='<td>
									 <label>'.(!empty($b5bcform_no_of_item[$i])?$b5bcform_no_of_item[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						   $mpdfHtml .='<td>
									 <label>'.(!empty($b5bcform_amount[$i])?$b5bcform_amount[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						   $mpdfHtml .='<td>
									 <label>'.(!empty($b5bcform_applicable_vat_rate[$i])?$b5bcform_applicable_vat_rate[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						  $mpdfHtml .='</tr>';
								}	}							
		$mpdfHtml .='</tbody></table></div>';
		$mpdfHtml .=' <table border="1" bordercolor="#ccc" cellpadding="5" cellspacing="0" style="font-size:13px;   font-family: opensans_bold; font-weight:normal; width: 100%;font-weight:normal;" class="table  tablecontent tablecontent2 bordernone">

                     <tr><td class="lftheading" >F-Form</td></tr>';
                                
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
						
							  for($i=0;$i < sizeof($start); $i++) {
								 $mpdfHtml .='<tr> <td class="leftheading" >'.$sno.'</td>';
						   
                           $mpdfHtml .='<td>
									 <label>'.(!empty($b5bfform_tin_issuer[$i])?$b5bfform_tin_issuer[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						   $mpdfHtml .='<td>
									 <label>'.(!empty($b5bfform_nameof_issuer[$i])?$b5bfform_nameof_issuer[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						   $mpdfHtml .='<td>
									 <label>'.(!empty($b5bfform_no_of_form[$i])?$b5bfform_no_of_form[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						   $mpdfHtml .='<td>
									 <label>'.(!empty($b5bfform_amount[$i])?$b5bfform_amount[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						   $mpdfHtml .='<td>
									 <label>'.(!empty($b5bfform_applicable_vat_rate[$i])?$b5bfform_applicable_vat_rate[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						  $mpdfHtml .='</tr>';
								}	}			                          
		$mpdfHtml .='</table>';	
        $mpdfHtml .=' <table border="1" bordercolor="#ccc" cellpadding="5" cellspacing="0" style="font-size:13px;   font-family: opensans_bold; font-weight:normal; width: 100%;font-weight:normal;" class="table  tablecontent tablecontent2 bordernone">

                     <tr><td class="lftheading" >H/I-Form</td></tr>';
                                
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
						
							  for($i=0;$i < sizeof($b5bhiform_tin_issuer); $i++) {
								 
								 $mpdfHtml .='<tr> <td class="leftheading" >'.$sno.'</td>';
						   
                           $mpdfHtml .='<td>
									 <label>'.(!empty($b5bhiform_tin_issuer[$i])?$b5bhiform_tin_issuer[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						   $mpdfHtml .='<td>
									 <label>'.(!empty($b5bhiform_nameof_issuer[$i])?$b5bhiform_nameof_issuer[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						   $mpdfHtml .='<td>
									 <label>'.(!empty($b5bhiform_no_of_form[$i])?$b5bhiform_no_of_form[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						   $mpdfHtml .='<td>
									 <label>'.(!empty($b5bhiform_amount[$i])?$b5bhiform_amount[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						   $mpdfHtml .='<td>
									 <label>'.(!empty($b5bhiform_applicable_vat_rate[$i])?$b5bhiform_applicable_vat_rate[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						  $mpdfHtml .='</tr>';
								}	}			                          
		$mpdfHtml .='</table><br>';
$mpdfHtml .='<div class="greyheading" style="float: left;width: 100%;font-size: 15px;background: #adadad;padding: 7px 10px;color: #FFF;font-family: opensans_bold;font-weight: normal;" class="greyheading" class="greyheading">C.Amount of tax credit carried forward to electronic credit ledger as state/UT Tax</div>
					     <div class="tableresponsive">
						 <table border="1" bordercolor="#ccc" cellpadding="5" cellspacing="0" style="font-size:13px;   font-family: opensans_bold; font-weight:normal; width: 100%;font-weight:normal;" class="table  tablecontent tablecontent2 bordernone">

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
                                
                                <tbody>';	
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
					      $mpdfHtml .='<tr>';
						   
                           $mpdfHtml .='<td>
									 <label>'.(!empty($c5cform_registration_no[$i])?$c5cform_registration_no[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						   $mpdfHtml .='<td>
									 <label>'.(!empty($c5cform_balanceof_itc_val[$i])?$c5cform_balanceof_itc_val[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						   $mpdfHtml .='<td>
									 <label>'.(!empty($c5cform_cform_turnover_form_pending[$i])?$c5cform_cform_turnover_form_pending[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						   $mpdfHtml .='<td>
									 <label>'.(!empty($c5cform_cform_taxpayable[$i])?$c5cform_cform_taxpayable[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						   $mpdfHtml .='<td>
									 <label>'.(!empty($c5cform_fform_turnover_form_pending[$i])?$c5cform_fform_turnover_form_pending[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						    $mpdfHtml .='<td>
									 <label>'.(!empty($c5cform_fform_taxpayable[$i])?$c5cform_fform_taxpayable[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						   $mpdfHtml .='<td>
									 <label>'.(!empty($c5cform_itcreversal_relatable[$i])?$c5cform_itcreversal_relatable[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						    $mpdfHtml .='<td>
									 <label>'.(!empty($c5cform_hiform_turnover_form_pending[$i])?$c5cform_hiform_turnover_form_pending[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						    $mpdfHtml .='<td>
									 <label>'.(!empty($c5cform_hiform_taxpayable[$i])?$c5cform_hiform_taxpayable[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						    $mpdfHtml .='<td>
									 <label>'.(!empty($c5cform_hiform_transitionitc2[$i])?$c5cform_hiform_transitionitc2[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						  $mpdfHtml .='</tr>';
								}}		
         
                            
        $mpdfHtml .= '</tbody></table></div>';	
        $mpdfHtml .= '<br><div class="greyheading" style="float: left;width: 100%;font-size: 15px;background: #adadad;padding: 7px 10px;color: #FFF;font-family: opensans_bold;font-weight: normal;" class="greyheading" class="greyheading"
 >6.Details of Capital goods for which Unavailed credit has not been carried</div><br>
<div class="greyheading" style="float: left;width: 100%;font-size: 15px;background: #adadad;padding: 7px 10px;color: #FFF;font-family: opensans_bold;font-weight: normal;" class="greyheading" class="greyheading"
>A.Amount of unavailed cenvat credit in respect of capital goods carried forward to electronic credit ledger as central tax</div>
					     <div class="tableresponsive">
						 	<table border="1" bordercolor="#ccc" cellpadding="5" cellspacing="0" style="font-size:13px;   font-family: opensans_bold; font-weight:normal; width: 100%;font-weight:normal;" class="table  tablecontent tablecontent2 bordernone">

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
                                <tbody>';	
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
								  $sno =0;
                     		$sno = $i+1;
								 $mpdfHtml .='<tr>';
						    $mpdfHtml .='<td>
									 <label>'.$sno.'</label>'; 
						  $mpdfHtml .='</td>';
                           $mpdfHtml .='<td>
									 <label>'.(!empty($a6ainvoice_document_no[$i])?$a6ainvoice_document_no[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						  $mpdfHtml .='<td>
									 <label>'.(!empty($a6ainvoice_document_date[$i])?$a6ainvoice_document_date[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						   $mpdfHtml .='<td>
									 <label>'.(!empty($a6asupplier_registration_no[$i])?$a6asupplier_registration_no[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						   $mpdfHtml .='<td>
									 <label>'.(!empty($a6arecipients_registration_no[$i])?$a6arecipients_registration_no[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						   $mpdfHtml .='<td>
									 <label>'.(!empty($a6a_value[$i])?$a6a_value[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						    $mpdfHtml .='<td>
									 <label>'.(!empty($a6a_ed_cvd[$i])?$a6a_ed_cvd[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						   $mpdfHtml .='<td>
									 <label>'.(!empty($a6a_sad[$i])?$a6a_sad[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						    $mpdfHtml .='<td>
									 <label>'.(!empty($a6a_totaleligible_cenvat[$i])?$a6a_totaleligible_cenvat[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						    $mpdfHtml .='<td>
									 <label>'.(!empty($a6a_totalcenvat_credit[$i])?$a6a_totalcenvat_credit[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						    $mpdfHtml .='<td>
									 <label>'.(!empty($a6a_totalcenvat_credit_unavailed[$i])?$a6a_totalcenvat_credit_unavailed[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						  $mpdfHtml .='</tr>';
								}}	
      
                            
        $mpdfHtml .=' </tbody></table></div>';
$mpdfHtml .= '<br><div class="greyheading" style="float: left;width: 100%;font-size: 15px;background: #adadad;padding: 7px 10px;color: #FFF;font-family: opensans_bold;font-weight: normal;" class="greyheading" class="greyheading"
>B.Amount of unavailed input tax credit carried forward to electronic credit ledger</div>
					    <div class="tableresponsive">
							 <table border="1" bordercolor="#ccc" cellpadding="5" cellspacing="0" style="font-size:13px;   font-family: opensans_bold; font-weight:normal; width: 100%;font-weight:normal;" class="table  tablecontent tablecontent2 bordernone">

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
                                
                                <tbody>';	
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
								 $sno =0;
                     		$sno = $i+1;
								 $mpdfHtml .='<tr>';
						    $mpdfHtml .='<td>
									 <label>'.$sno.'</label>'; 
						  $mpdfHtml .='</td>';
                           $mpdfHtml .='<td>
									 <label>'.(!empty($b6binvoice_document_no[$i])?$b6binvoice_document_no[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						  $mpdfHtml .='<td>
									 <label>'.(!empty($b6binvoice_document_date[$i])?$b6binvoice_document_date[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						   $mpdfHtml .='<td>
									 <label>'.(!empty($b6bsupplier_registration_no[$i])?$b6bsupplier_registration_no[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						   $mpdfHtml .='<td>
									 <label>'.(!empty($b6breceipients_registration_no[$i])?$b6breceipients_registration_no[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						   $mpdfHtml .='<td>
									 <label>'.(!empty($b6b_value[$i])?$b6b_value[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						    $mpdfHtml .='<td>
									 <label>'.(!empty($b6b_taxpaid_vat[$i])?$b6b_taxpaid_vat[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						   $mpdfHtml .='<td>
									 <label>'.(!empty($b6b_totaleligible_vat[$i])?$b6b_totaleligible_vat[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						    $mpdfHtml .='<td>
									 <label>'.(!empty($b6b_totalvat_creditavailed[$i])?$b6b_totalvat_creditavailed[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						    $mpdfHtml .='<td>
									 <label>'.(!empty($b6b_totalvat_creditunavailed[$i])?$b6b_totalvat_creditunavailed[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						   
						  $mpdfHtml .='</tr>';
								}}	
      
                            
        $mpdfHtml .=' </tbody></table></div>';
        $mpdfHtml .='<br><div class="greyheading" style="float: left;width: 100%;font-size: 15px;background: #adadad;padding: 7px 10px;color: #FFF;font-family: opensans_bold;font-weight: normal;" class="greyheading" class="greyheading"
>7.Details of Input held in terms of section </div>
					     <div class="tableresponsive">
						 <table border="1" bordercolor="#ccc" cellpadding="5" cellspacing="0" style="font-size:13px;   font-family: opensans_bold; font-weight:normal; width: 100%;font-weight:normal;" class="table  tablecontent tablecontent2 bordernone">

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
                                
                                <tbody>';   
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
								   $sno =0;
                     		$sno = $i+1;
					       $sno =0;
							$sno = $i+1;	  
						   $mpdfHtml .='<tr>';	
                           $mpdfHtml .='<td>
									 <label>'.$sno.'</label>'; 
						  $mpdfHtml .='</td>';					   
                           $mpdfHtml .='<td>
									 <label>'.(!empty($a7a1_hsncode[$i])?$a7a1_hsncode[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						  $mpdfHtml .='<td>
									 <label>'.(!empty($a7a1_unit[$i])?$a7a1_unit[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						   $mpdfHtml .='<td>
									 <label>'.(!empty($a7a1_qty[$i])?$a7a1_qty[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						   $mpdfHtml .='<td>
									 <label>'.(!empty($a7a1_value[$i])?$a7a1_value[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						   $mpdfHtml .='<td>
									 <label>'.(!empty($a7a1_eligible_duties[$i])?$a7a1_eligible_duties[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';					  
						   
						  $mpdfHtml .='</tr>';
								}}	
		$mpdfHtml .='</tbody></table>';
        $mpdfHtml .=' <table border="1" bordercolor="#ccc" cellpadding="5" cellspacing="0" style="font-size:13px;   font-family: opensans_bold; font-weight:normal; width: 100%;font-weight:normal;" class="table  tablecontent tablecontent2 bordernone">
					<tr><td colspan="6">Input Contained in finished and semi finished goods</td></tr>';		
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
								$sno =0;
							$sno = $i+1;	  
						   $mpdfHtml .='<tr>';	
                           $mpdfHtml .='<td>
									 <label>'.$sno.'</label>'; 
						  $mpdfHtml .='</td>';						   
                           $mpdfHtml .='<td>
									 <label>'.(!empty($a7a2_hsncode[$i])?$a7a2_hsncode[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						  $mpdfHtml .='<td>
									 <label>'.(!empty($a7a2_unit[$i])?$a7a2_unit[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						   $mpdfHtml .='<td>
									 <label>'.(!empty($a7a2_qty[$i])?$a7a2_qty[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						   $mpdfHtml .='<td>
									 <label>'.(!empty($a7a2_value[$i])?$a7a2_value[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						   $mpdfHtml .='<td>
									 <label>'.(!empty($a7a2_eligible_duties[$i])?$a7a2_eligible_duties[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';					  
						   
						  $mpdfHtml .='</tr>';
								}}
		$mpdfHtml .='</table>';	
        $mpdfHtml .=' <table border="1" bordercolor="#ccc" cellpadding="5" cellspacing="0" style="font-size:13px;   font-family: opensans_bold; font-weight:normal; width: 100%;font-weight:normal;" class="table  tablecontent tablecontent2 bordernone">
								
                    <tr><td colspan="6">7B Where duty paid invoices are not available</td></tr>';
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
							$sno =0;
							$sno = $i+1;	  
						   $mpdfHtml .='<tr>';	
                           $mpdfHtml .='<td>
									 <label>'.$sno.'</label>'; 
						  $mpdfHtml .='</td>';									
                           $mpdfHtml .='<td>
									 <label>'.(!empty($a7a3_hsncode[$i])?$a7a3_hsncode[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						  $mpdfHtml .='<td>
									 <label>'.(!empty($a7a3_unit[$i])?$a7a3_unit[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						   $mpdfHtml .='<td>
									 <label>'.(!empty($a7a3_qty[$i])?$a7a3_qty[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						   $mpdfHtml .='<td>
									 <label>'.(!empty($a7a3_value[$i])?$a7a3_value[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						   $mpdfHtml .='<td>
									 <label>'.(!empty($a7a3_eligible_duties[$i])?$a7a3_eligible_duties[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';					  
						   
						  $mpdfHtml .='</tr>';
								}}
		
        $mpdfHtml .='</table></div>';
        $mpdfHtml .='<div class="greyheading" style="float: left;width: 100%;font-size: 15px;background: #adadad;padding: 7px 10px;color: #FFF;font-family: opensans_bold;font-weight: normal;" class="greyheading" class="greyheading"
>B.Amount of eligible duties and taxes</div>
					     <div class="tableresponsive">
						  <table border="1" bordercolor="#ccc" cellpadding="5" cellspacing="0" style="font-size:13px;   font-family: opensans_bold; font-weight:normal; width: 100%;font-weight:normal;" class="table  tablecontent tablecontent2 bordernone">
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
                                
                                <tbody>';
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
								  $sno =0;
							$sno = $i+1;	  
						   $mpdfHtml .='<tr>';	                          								
                           $mpdfHtml .='<td>
									 <label>'.(!empty($b7b_nameof_supplier[$i])?$b7b_nameof_supplier[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						  $mpdfHtml .='<td>
									 <label>'.(!empty($b7b_invoice_number[$i])?$b7b_invoice_number[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						   $mpdfHtml .='<td>
									 <label>'.(!empty($b7b_invoice_date[$i])?$b7b_invoice_date[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						   $mpdfHtml .='<td>
									 <label>'.(!empty($b7b_description[$i])?$b7b_description[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						   $mpdfHtml .='<td>
									 <label>'.(!empty($b7b_quantity[$i])?$b7b_quantity[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';	
                          $mpdfHtml .='<td>
									 <label>'.(!empty($b7b_uqc[$i])?$b7b_uqc[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
                          $mpdfHtml .='<td>
									 <label>'.(!empty($b7b_value[$i])?$b7b_value[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';	
                          $mpdfHtml .='<td>
									 <label>'.(!empty($b7b_eligible_duties[$i])?$b7b_eligible_duties[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';	
                          $mpdfHtml .='<td>
									 <label>'.(!empty($b7b_vat[$i])?$b7b_vat[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
                          $mpdfHtml .='<td>
									 <label>'.(!empty($b7b_dateonwhich_receipients[$i])?$b7b_dateonwhich_receipients[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';						  
						   
						  $mpdfHtml .='</tr>';
								}}
		
                            
        $mpdfHtml .='</tbody></table></div>';
        $mpdfHtml .='<div style="float: left;width: 100%;font-size: 15px;background: #adadad;padding: 7px 10px;color: #FFF;font-family: opensans_bold;font-weight: normal;" class="greyheading" class="greyheading"
 class="greyheading">C.Amount of vat and entry tax paid on input supported by invoices/documents evidencing payment of tax carried forward to electronic credit ledger as SGST/UTGST </div>
					     <div class="tableresponsive">
						<table border="1" bordercolor="#ccc" cellpadding="5" cellspacing="0" style="font-size:13px;   font-family: opensans_bold; font-weight:normal; width: 100%;font-weight:normal;" class="table  tablecontent tablecontent2 bordernone">

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
								<th></th></tr></thead><tbody> <tr><td>Inputs</td></tr>';
	                       
                                
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
                              $sno =0;
							$sno = $i+1;	  
						   $mpdfHtml .='<tr>';	                          								
                           $mpdfHtml .='<td>
									 <label>'.(!empty($c7c1_description[$i])?$c7c1_description[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						  $mpdfHtml .='<td>
									 <label>'.(!empty($c7c1_unit[$i])?$c7c1_unit[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						   $mpdfHtml .='<td>
									 <label>'.(!empty($c7c1_qty[$i])?$c7c1_qty[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						   $mpdfHtml .='<td>
									 <label>'.(!empty($c7c1_value[$i])?$c7c1_value[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
						   $mpdfHtml .='<td>
									 <label>'.(!empty($c7c1_vat[$i])?$c7c1_vat[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';	
                          $mpdfHtml .='<td>
									 <label>'.(!empty($c7c1_totalinput_taxcredit[$i])?$c7c1_totalinput_taxcredit[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';
                          $mpdfHtml .='<td>
									 <label>'.(!empty($c7c1_totalinput_taxcredit_exempt[$i])?$c7c1_totalinput_taxcredit_exempt[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';	
                          $mpdfHtml .='<td>
									 <label>'.(!empty($c7c1_totalinput_taxcredit_admissible[$i])?$c7c1_totalinput_taxcredit_admissible[$i]:'').'</label>'; 
						  $mpdfHtml .='</td>';                        					  
						   
						  $mpdfHtml .='</tr>';
								}}	
        $mpdfHtml .='</tbody></table>';								
		$mpdfHtml .='</div></div></div>'; 
		$mpdfHtml .='</div>';
		$mpdfHtml .='</body>';
		$mpdfHtml .='</html>';
		return $mpdfHtml;

    }
    public function finalSaveGstrTransition()
   {
		//$return_id =   isset($_POST['returnid']) ? $_POST['returnid'] : '';
		$fmonth =   $this->sanitize($_GET['returnmonth']);
		$userid = $_SESSION['user_detail']['user_id'];
	
		 if($this->update('gst_transition_form', array('final_submit' => 1), array('financial_month' => $fmonth)))
		 {
		 $this->setSuccess('GST-Transition submitted successfully');
		 $this->logMsg("GST-Transition form final submit month :".$this->sanitize($_GET['returnmonth']),"gst_transition");
   
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