<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

final class gstr3b extends validation {
    
    function __construct() {
        parent::__construct();
    }
    
   public function deleteSaveGstr3b()
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
    public function finalSaveGstr3b()
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
    public function sendMail($module = '', $module_message = '', $to_send, $from_send, $cc = '', $bcc = '', $attachment = '', $subject, $body) {
        $dataInsertArray['module'] = $module;
        $dataInsertArray['module_message'] = $module_message;
        $dataInsertArray['to_send'] = $to_send;
        $dataInsertArray['from_send'] = $from_send;
        $dataInsertArray['cc'] = $cc;
        $dataInsertArray['bcc'] = $bcc;
        $dataInsertArray['attachment'] = $attachment;
        $dataInsertArray['subject'] = $subject;
        $dataInsertArray['body'] = $body;
        if ($this->insert($this->tableNames['email'], $dataInsertArray)) {
            return true;
        } else {
            return false;
        }
    }
   public function UpdateGstr3b()
   {
	   $dataArr = $this->getGSTR3bData();
	   $sql = "select * from " . TAB_PREFIX . "client_kyc where added_by='" . $_SESSION['user_detail']['user_id'] . "' order by id desc limit 0,1";
       $clientdata = $this->get_results($sql);
	   $client_gstin_number;
	   if(count($clientdata) > 0 )
	   {
		   $client_gstin_number = $clientdata[0]->gstin_number;
	   }
	   $dataArr['client_gstin_number'] = $client_gstin_number;
       if ($this->insert(TAB_PREFIX.'client_return_gstr3b', $dataArr)) {
			return true;
		}
		else
		{
           return false;    	   
       }
	   
   }
    
   public function generategstr3bHtml($returnid,$returnmonth)
   {
	         
               $htmlResponse = $this->generategstr3bPdf($_SESSION['user_detail']['user_id'],$returnid,$returnmonth);
                if ($htmlResponse === false) {

                    $obj_client->setError("No Plan Pdf found.");
                    return false;
                }
                $obj_mpdf = new mPDF();
                $obj_mpdf->SetHeader('GSTR 3B File');
                $obj_mpdf->WriteHTML($htmlResponse);
                $datetime=date('YmdHis');
               
                $taxInvoicePdf = 'gstr3bfile-' . $_SESSION['user_detail']['username'] . '_' .$datetime. '.pdf';
                ob_clean();
                //$proof_photograph = $this->imageUploads($taxInvoicePdf, 'plan-invoice', 'upload','.pdf');
                $pic = $taxInvoicePdf;
             
				  ob_clean();
				  if($_GET['action'] == 'printInvoice')
				  {
					  
				 $obj_mpdf->Output($taxInvoicePdf, 'I');
				  }
				  else if($_GET['action'] == 'emailInvoice')
				  {
					   return $htmlResponse;
				  }
				  else
				  {
					  $obj_mpdf->Output($taxInvoicePdf, 'D');
				  }
			 
					$this->logMsg("User ID : " . $_SESSION['user_detail']['user_id'] . " in User has been updated");
				   
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
   private function getPlaceOfSupplyComposition()
   {
		$dataArr['place_of_supply']='';
		if(!empty($_POST['place_of_supply_taxable_person'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['place_of_supply_taxable_person'] as $selected){
			 
             $dataArr['place_of_supply'] = $dataArr['place_of_supply'].$selected.',';
			
			} 
			}
			$dataArr['totaltaxable_value']='';
			if(!empty($_POST['total_taxable_value_taxable_person'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['total_taxable_value_taxable_person'] as $selected){
			 
             $dataArr['totaltaxable_value'] = $dataArr['totaltaxable_value'].$selected.',';
			
			} 
			}
			$dataArr['amount_of_integrated_tax']='';
				if(!empty($_POST['amount_of_integrated_tax_taxable_person'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['amount_of_integrated_tax_taxable_person'] as $selected){
			 
             $dataArr['amount_of_integrated_tax'] = $dataArr['amount_of_integrated_tax'].$selected.',';
			
			} 
			}
			 $sql="select * from gst_place_of_supply where added_by='".$_SESSION['user_detail']['user_id']."' and financial_month='".$this->sanitize($_GET['returnmonth'])."' and type='1'";		
	   $data = $this->get_results($sql);
		if(empty($data))
		{
			$dataArr['financial_month']=$this->sanitize($_GET['returnmonth']);
			$dataArr['type']=1;
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
			if ($this->update('gst_place_of_supply', $dataArr,array('added_by'=>$_SESSION['user_detail']['user_id'],'type'=>'1','financial_month'=>$this->sanitize($_GET['returnmonth'])))) {
					
		                      
				//$this->setSuccess('GSTR3B Saved Successfully');
				return true;
			}
			else
			{
				$this->setError('Failed to save GSTR3B data');
			   return false;    	   
		   }
		}
   }
   private function getPlaceOfSupplyUinHolder()
   {
		$dataArr['place_of_supply']='';
		if(!empty($_POST['place_of_supply_uin_holder'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['place_of_supply_uin_holder'] as $selected){
			 
             $dataArr['place_of_supply'] = $dataArr['place_of_supply'].$selected.',';
			
			} 
			}
			$dataArr['totaltaxable_value']='';
		if(!empty($_POST['total_taxable_value_uin_holder'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['total_taxable_value_uin_holder'] as $selected){
			 
             $dataArr['totaltaxable_value'] = $dataArr['totaltaxable_value'].$selected.',';
			
			} 
			}
				$dataArr['amount_of_integrated_tax']='';
		if(!empty($_POST['amount_of_integrated_uin_holder'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['amount_of_integrated_uin_holder'] as $selected){
			 
             $dataArr['amount_of_integrated_tax'] = $dataArr['amount_of_integrated_tax'].$selected.',';
			
			} 
			}
		
			 $sql="select * from gst_place_of_supply where added_by='".$_SESSION['user_detail']['user_id']."' and financial_month='".$this->sanitize($_GET['returnmonth'])."' and type='2'";		
	   $data = $this->get_results($sql);
		if(empty($data))
		{
			$dataArr['financial_month']=$this->sanitize($_GET['returnmonth']);
			$dataArr['type']=2;
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
			if ($this->update('gst_place_of_supply', $dataArr,array('added_by'=>$_SESSION['user_detail']['user_id'],'type'=>'2','financial_month'=>$this->sanitize($_GET['returnmonth'])))) {
					
		                      
				//$this->setSuccess('GSTR3B Saved Successfully');
				return true;
			}
			else
			{
				$this->setError('Failed to save GSTR3B data');
			   return false;    	   
		   }
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
         $dataArr['integrated_tax_value_supplye'] = isset($_POST['integrated_tax_value_supplye']) ? $_POST['integrated_tax_value_supplye'] : '';
         $dataArr['central_tax_value_supplye'] = isset($_POST['central_tax_value_supplye']) ? $_POST['central_tax_value_supplye'] : '';
		 $dataArr['state_tax_value_supplye'] = isset($_POST['state_tax_value_supplye']) ? $_POST['state_tax_value_supplye'] : '';
		$dataArr['cess_tax_value_supplye'] = isset($_POST['cess_tax_value_supplye']) ? $_POST['cess_tax_value_supplye'] : '';
		//$dataArr['place_of_supply_unregistered_person'] = isset($_POST['place_of_supply_unregistered_person']) ? $_POST['place_of_supply_unregistered_person'] : '';
		/*
		$dataArr['place_of_supply_unregistered_person']='';
		  if(!empty($_POST['place_of_supply_unregistered_person'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['place_of_supply_unregistered_person'] as $selected){
			 
             $dataArr['place_of_supply_unregistered_person'] = $dataArr['place_of_supply_unregistered_person'].$selected.',';
			
			} 
			}
			
		$dataArr['total_taxable_value_unregistered_person'] = isset($_POST['total_taxable_value_unregistered_person']) ? $_POST['total_taxable_value_unregistered_person'] : '';
		 $dataArr['amount_of_integrated_tax_unregistered_person'] = isset($_POST['amount_of_integrated_tax_unregistered_person']) ? $_POST['amount_of_integrated_tax_unregistered_person'] : '';
		// $dataArr['place_of_supply_taxable_person'] = isset($_POST['place_of_supply_taxable_person']) ? $_POST['place_of_supply_taxable_person'] : '';
		$dataArr['place_of_supply_taxable_person']='';
		if(!empty($_POST['place_of_supply_taxable_person'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['place_of_supply_taxable_person'] as $selected){
			 
             $dataArr['place_of_supply_taxable_person'] = $dataArr['place_of_supply_taxable_person'].$selected.',';
			
			} 
			}
		$dataArr['total_taxable_value_taxable_person'] = isset($_POST['total_taxable_value_taxable_person']) ? $_POST['total_taxable_value_taxable_person'] : '';
		$dataArr['amount_of_integrated_tax_taxable_person'] = isset($_POST['amount_of_integrated_tax_taxable_person']) ? $_POST['amount_of_integrated_tax_taxable_person'] : '';
		//$dataArr['place_of_supply_uin_holder'] = isset($_POST['place_of_supply_uin_holder']) ? $_POST['place_of_supply_uin_holder'] : '';
		$dataArr['place_of_supply_uin_holder']='';
		if(!empty($_POST['place_of_supply_uin_holder'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['place_of_supply_uin_holder'] as $selected){
			 
             $dataArr['place_of_supply_uin_holder'] = $dataArr['place_of_supply_uin_holder'].$selected.',';
			
			} 
			}
		$dataArr['total_taxable_value_uin_holder'] = isset($_POST['total_taxable_value_uin_holder']) ? $_POST['total_taxable_value_uin_holder'] : '';
		$dataArr['amount_of_integrated_uin_holder'] = isset($_POST['amount_of_integrated_uin_holder']) ? $_POST['amount_of_integrated_uin_holder'] : '';
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
		$dataArr['return_filling_date'] = date('Y-m-d H:i:s');
		$dataArr['added_by'] = $_SESSION['user_detail']['user_id'];
		$dataArr['is_deleted'] = 0;
		return $dataArr;
	}
	 public function generategstr3bPdf($invid,$returnid,$returnmonth) {
	   $sql = "select  *,count(return_id) as totalinvoice from " . TAB_PREFIX . "client_return_gstr3b where added_by='" . $_SESSION['user_detail']['user_id'] . "' and financial_month like '%" . $returnmonth . "%' order by return_id desc limit 0,1";
       $returndata = $this->get_results($sql);
	   $place_of_supply;
	   if(isset($returndata[0]->place_of_supply_unregistered_person))
	   {
	   $place_of_supply = substr($returndata[0]->place_of_supply_unregistered_person,0,-1);
	   }
	   	$str = (explode(",",$place_of_supply));
       $place_of_supply_arr='';
        foreach($str as $s)
      {
		$sql = "select  *,count(state_id) as numcount from " . TAB_PREFIX . "state where state_id =".$s."";
      
		$return_place_of_supplydata = $this->get_results($sql);
		
		 if($return_place_of_supplydata[0]->numcount > 0 )
				   {
					   foreach($return_place_of_supplydata as $item)
					   {
						 $place_of_supply_arr = $place_of_supply_arr.$item->state_name.'('.$item->state_tin.')'.',';
					   }
				   }
												
		}
	  
	   $place_of_taxable_person;
	   if(isset($returndata[0]->place_of_supply_taxable_person))
	   {
	    $place_of_taxable_person = substr($returndata[0]->place_of_supply_taxable_person,0,-1);
	   }
	  
	   $str = (explode(",",$place_of_taxable_person));
	 
       $place_of_supply_arr_taxable='';
        foreach($str as $s)
      {
		$sql = "select  *,count(state_id) as numcount from " . TAB_PREFIX . "state where state_id =".$s."";
      
		$return_place_of_supplydata = $this->get_results($sql);
		
		 if($return_place_of_supplydata[0]->numcount > 0 )
				   {
					   foreach($return_place_of_supplydata as $item)
					   {
						 $place_of_supply_arr_taxable = $place_of_supply_arr_taxable.$item->state_name.'('.$item->state_tin.')'.',';
					   }
				   }
												
		}
	   //
	  
	   //uin holder
	     $place_of_supply_uin_holder;
	   if(isset($returndata[0]->place_of_supply_uin_holder))
	   {
	    $place_of_supply_uin_holder = substr($returndata[0]->place_of_supply_uin_holder,0,-1);
	   }
	   $str = (explode(",",$place_of_supply_uin_holder));
       $place_of_supply_arr_uin_holder='';
        foreach($str as $s)
      {
		$sql = "select  *,count(state_id) as numcount from " . TAB_PREFIX . "state where state_id =".$s."";
      
		$return_place_of_supplydata = $this->get_results($sql);
		
		 if($return_place_of_supplydata[0]->numcount > 0 )
				   {
					   foreach($return_place_of_supplydata as $item)
					   {
						 $place_of_supply_arr_uin_holder = $place_of_supply_arr_uin_holder.$item->state_name.'('.$item->state_tin.')'.',';
					   }
				   }
												
		}
	  
	   $sql = "select  * from " . TAB_PREFIX . "client_kyc where added_by='" . $_SESSION['user_detail']['user_id'] . "'";
 
       $kycdata = $this->get_results($sql);
	   $mpdfHtml .='<html>';
	   $mpdfHtml .='<body>';
	   
	  $mpdfHtml .='<div style="font-size:12px !important;">';
      $mpdfHtml .= '<table cellpadding="0" cellspacing="0" width="100%">
      <tr class="top"><td colspan="2">';
	 $mpdfHtml .=  '<table width="100%"><tr><td width="50%">';
    if (isset($dataThemeSettingArr['data']->theme_logo) && $dataThemeSettingArr['data']->theme_logo != "") {
            $mpdfHtml .= '<img src="' . PROJECT_URL . '/upload/theme-logo/' . $dataThemeSettingArr['data']->theme_logo . '" style="width:100%;max-width:200px;">';
        } else {
            $mpdfHtml .= '<img src="' . PROJECT_URL . '/image/gst-k-logo.png" style="width:100%;max-width:200px;">';
        }

    $mpdfHtml .='</td><td align="right" width="50%">
   <b>Company Name #</b>: '.$kycdata[0]->name.'<br>
   <b>GSTIN #</b>: '.$kycdata[0]->gstin_number.'<br></td></tr></table></td></tr></table>';
   $mpdfHtml .= ' <div style="position: relative;min-height: 1px;padding-right: 0px;padding-left: 0px;" class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
   <div style="width: 100%;position: relative;min-height: 1px;padding-right: 15px;padding-left:0px;position: relative;min-height: 1px padding-right: 0px;
    padding-left: 15px; font-size:12px !important;" class="col-md-12 col-sm-12 col-xs-12">
    <div style="position: relative;min-height: 1px padding-right: 15px;
    padding-left: 15px;" class="col-md-6 col-sm-6 col-xs-12 text-right breadcrumb-nav"></div>
    <div class="whitebg formboxcontainer"><div style="float: left;width: 100%;font-size: 15px;background: #adadad;padding: 7px 10px;color: #FFF;font-family: opensans_bold;font-weight: normal;" class="greyheading"  >3.1 Details of Outward Supplies and inward supplies liable to reverse charge</div>
    <div class="tableresponsive">
	 <table border="1" bordercolor="#ccc" cellpadding="5" cellspacing="0" style="font-size:13px;   font-family: opensans_bold; font-weight:normal; width: 100%;font-weight:normal;" class="table  tablecontent tablecontent2 bordernone">
							<thead><tr><th align="left">Nature of Supplies</th><th align="left">Total Taxable value</th>
    <th align="left">Integrated Tax</th><th align="left">Central Tax</th>
    <th align="left">State/UT Tax</th><th align="left">Cess</th></tr></thead>';
                                
    $mpdfHtml .= '<tbody><tr><td class="lftheading" style="font-size: 13px; background: #fdede8;color: #333;
    border-bottom: 1px solid #f4d4ca;" width="40%">(a) Outward taxable supplies (other than zero rated, nil rated and exempted)</td><td>
	<label>'.$returndata[0]->total_tax_value_supplya.'<span class="starred"></span></label>
	</td><td><label>'.$returndata[0]->integrated_tax_value_supplya.'<span class="starred"></span></label></td><td>
	<label>'.$returndata[0]->central_tax_value_supplya.'<span class="starred"></span></label>
	</td><td><label>'.$returndata[0]->state_tax_value_supplya.'<span class="starred"></span></label>
	</td><td><label>'.$returndata[0]->cess_tax_value_supplya.'<span class="starred"></span></label></td>
    </tr><tr><td class="lftheading" style="font-size: 13px;background: #fdede8;color: #333;border-bottom: 1px solid #f4d4ca;" width="20%">(b) Outward taxable supplies (zero rated )</td>
	<td><label>'.$returndata[0]->total_tax_value_supplyb.'<span class="starred"></span></label></td>
	<td><label>'.$returndata[0]->integrated_tax_value_supplyb.'<span class="starred"></span></label>
	</td><td><label>'.$returndata[0]->central_tax_value_supplyb.'<span class="starred"></span></label>
</td><td><label>'.$returndata[0]->state_tax_value_supplyb.'<span class="starred"></span></label></td> 
 <td><label>'.$returndata[0]->cess_tax_value_supplyb.'<span class="starred"></span></label>
</td> </tr> <tr><td class="lftheading" style="font-size: 13px;background: #fdede8;color: #333;border-bottom: 1px solid #f4d4ca;" width="20%">(c) Other outward supplies (Nil rated, exempted)</td>
<td><label>'.$returndata[0]->total_tax_value_supplyc.'<span class="starred"></span></label></td> 
<td><label>'.$returndata[0]->integrated_tax_value_supplyc.'<span class="starred"></span></label></td> 
<td><label>'.$returndata[0]->central_tax_value_supplyc.'<span class="starred"></span></label> </td> 
<td><label>'.$returndata[0]->state_tax_value_supplyc.'<span class="starred"></span></label></td> 
<td><label>'.$returndata[0]->cess_tax_value_supplyc.'<span class="starred"></span></label> </td></tr>
<tr><td class="lftheading" style="font-size: 13px;background: #fdede8;color: #333;border-bottom: 1px solid #f4d4ca;" width="20%">(d) Inward supplies (liable to reverse charge)</td>
<td><label>'.$returndata[0]->total_tax_value_supplyd.'<span class="starred"></span></label> </td> 
<td><label>'.$returndata[0]->integrated_tax_value_supplyd.'<span class="starred"></span></label></td>
<td><label>'.$returndata[0]->central_tax_value_supplyd.'<span class="starred"></span></label></td> 
<td><label>'.$returndata[0]->state_tax_value_supplyd.'<span class="starred"></span></label></td>
<td><label>'.$returndata[0]->cess_tax_value_supplyd.'<span class="starred"></span></label></td></tr>
<tr><td class="lftheading" style="font-size: 13px;background: #fdede8;color: #333;border-bottom: 1px solid #f4d4ca;" width="20%">(e) Non-GST outward supplies</td>
<td><label>'.$returndata[0]->total_tax_value_supplye.'<span class="starred"></span></label></td>
<td><label>'.$returndata[0]->integrated_tax_value_supplye.'<span class="starred"></span></label></td> 
<td><label>'.$returndata[0]->central_tax_value_supplye.'<span class="starred"></span></label></td> 
 <td> <label>'.$returndata[0]->state_tax_value_supplye.'<span class="starred"></span></label></td> 
 <td><label>'.$returndata[0]->cess_tax_value_supplye.'<span class="starred"></span></label></td></tr></tbody></table></div>';
$mpdfHtml .= '<div class="greyheading" style="float: left;width: 100%;font-size: 15px;margin: 15px 0 15px 0;background: #adadad;padding: 7px 10px;color: #FFF;font-family: opensans_bold;font-weight: normal;">3.2 Of the supplies shown in 3.1 (a) above, details of inter-State supplies made to unregistered persons,
composition taxable persons and UIN holders</div>
<div class="tableresponsive"><table border="1" bordercolor="#ccc" cellpadding="5" cellspacing="0" style="font-size:13px;   font-family: opensans_bold; font-weight:normal; width: 100%;font-weight:normal;" class="table  tablecontent tablecontent2 bordernone">
<thead><tr><th align="left" style="background:#f0f0f0 !important;"></th>
<th align="left" style="background:#f0f0f0 !important;">Place of Supply (State/UT)</th>
<th align="left" style="background:#f0f0f0 !important;">Total Taxable value</th>
<th align="left" style="background:#f0f0f0 !important;">Amount Of Integrated Tax</th></tr></thead><tbody>
<tr><td class="lftheading" style="font-size: 13px;background: #fdede8;color: #333;border-bottom: 1px solid #f4d4ca;" width="25%">Supplies made to Unregistered Persons</td>
<td><label>'.$place_of_supply_arr.'<span class="starred"></span></label></td>
<td><label>'.$returndata[0]->total_taxable_value_unregistered_person.'<span class="starred"></span></label></td> 	
<td><label>'.$returndata[0]->amount_of_integrated_tax_unregistered_person.'<span class="starred"></span></label></td></tr> 										                       
<tr><td class="lftheading" style="font-size: 13px;background: #fdede8;color: #333;border-bottom: 1px solid #f4d4ca;" width="25%">Supplies made to Composition Taxable Persons</td>
<td><label>'.$place_of_supply_arr_taxable.'<span class="starred"></span></label></td>
<td><label>'.$returndata[0]->total_taxable_value_taxable_person.'<span class="starred"></span></label></td> 	
<td><label>'.$returndata[0]->amount_of_integrated_tax_taxable_person.'<span class="starred"></span></label></td></tr>									 
<tr><td class="lftheading" style="font-size: 13px;background: #fdede8;color: #333;border-bottom: 1px solid #f4d4ca;" width="25%">Supplies made to UIN holders</td>
<td><label>'.$place_of_supply_arr_uin_holder.'<span class="starred"></span></label></td>
<td><label>'.$returndata[0]->total_taxable_value_uin_holder.'<span class="starred"></span></label></td>
<td><label>'.$returndata[0]->amount_of_integrated_uin_holder.'<span class="starred"></span></label></td></tr></tbody></table></div>';				
$mpdfHtml .='<div class="greyheading" style="float: left;width: 100%;font-size: 15px;margin: 15px 0 15px 0;background: #adadad;padding: 7px 10px;color: #FFF;font-family: opensans_bold;font-weight: normal;">4. Eligible ITC</div><div class="tableresponsive">
<table border="1" bordercolor="#ccc" cellpadding="5" cellspacing="0" style="font-size:13px;   font-family: opensans_bold; font-weight:normal; width: 100%;font-weight:normal;" class="table  tablecontent tablecontent2 bordernone"><thead>
<tr><th align="left">Details</th><th align="left">Integrated Tax</th><th align="left">Central Tax</th><th align="left">State/UT Tax</th>
<th align="left">Cess</th></tr></thead> <tbody><tr><td class="lftheading" style="font-size: 13px;background: #fdede8;color: #333;
border-bottom: 1px solid #f4d4ca;" width="25%"><strong>(A) ITC Available (whether in full or part)</strong></td>
<td> <label>'.$returndata[0]->integrated_tax_itcavailable_a.'<span class="starred"></span></label></td> 		
<td> <label>'.$returndata[0]->central_tax_itcavailable_a.'<span class="starred"></span></label> </td>
<td><label>'.$returndata[0]->state_tax_itcavailable_a.'<span class="starred"></span></label></td> 
<td><label>'.$returndata[0]->cess_tax_itcavailable_a.'<span class="starred"></span></label></td> </tr>
<tr><td style="font-size: 13px;background: #fdede8; color: #333;border-bottom: 1px solid #f4d4ca;" class="lftheading">(1) Import of goods</td>								 
 <td><label>'.$returndata[0]->integrated_tax_import_of_goods.'<span class="starred"></span></label> </td> 
<td> <label>'.$returndata[0]->central_tax_import_of_goods.'<span class="starred"></span></label> </td>
 <td><label>'.$returndata[0]->state_tax_import_of_goods.'<span class="starred"></span></label>                            </td> 	
<td> <label>'.$returndata[0]->cess_tax_import_of_goods.'<span class="starred"></span></label> </td></tr>	
  <tr> <td style="font-size: 13px;background: #fdede8;color: #333; border-bottom: 1px solid #f4d4ca;" class="lftheading">(2) Import of services</td>								 
<td><label>'.$returndata[0]->integrated_tax_import_of_services.'<span class="starred"></span></label> </td>
 <td> <label>'.$returndata[0]->central_tax_import_of_services.'<span class="starred"></span></label>  </td>
  <td> <label>'.$returndata[0]->state_tax_import_of_services.'<span class="starred"></span></label> </td>
<td> <label>'.$returndata[0]->cess_tax_import_of_services.'<span class="starred"></span></label> </td></tr> 
  <tr>  <td style="font-size: 13px; background: #fdede8;  color: #333; border-bottom: 1px solid #f4d4ca;" class="lftheading">(3) Inward supplies liable to reverse charge (other than 1 & 2 above)</td>																                    
<td> <label>'.$returndata[0]->integrated_tax_inward_supplies_reverse_charge.'<span class="starred"></span></label>  </td> 
 <td> <label>'.$returndata[0]->central_tax_inward_supplies_reverse_charge.'<span class="starred"></span></label> </td> 
 <td><label>'.$returndata[0]->state_tax_inward_supplies_reverse_charge.'<span class="starred"></span></label>  </td> 
 <td> <label>'.$returndata[0]->cess_tax_inward_supplies_reverse_charge.'<span class="starred"></span></label> </td></tr>
<tr> <td style="font-size: 13px;background: #fdede8;color: #333;border-bottom: 1px solid #f4d4ca;" class="lftheading" style="font-size: 13px;background: #fdede8;color: #333;border-bottom: 1px solid #f4d4ca;">(4) Inward supplies from ISD</td>            
<td><label>'.$returndata[0]->integrated_tax_inward_supplies.'<span class="starred"></span></label> </td>
 <td> <label>'.$returndata[0]->central_tax_inward_supplies.'<span class="starred"></span></label></td>
<td> <label>'.$returndata[0]->state_tax_inward_supplies.'<span class="starred"></span></label>  </td>
 <td> <label>'.$returndata[0]->cess_tax_inward_supplies.'<span class="starred"></span></label> </td> </tr>
 <tr> <td style="font-size: 13px; background: #fdede8;  color: #333;border-bottom: 1px solid #f4d4ca;" class="lftheading" >(5) All other ITC</td>
 <td><label>'.$returndata[0]->integrated_tax_allother_itc.'<span class="starred"></span></label>  </td>
<td><label>'.$returndata[0]->central_tax_allother_itc.'<span class="starred"></span></label>   </td>
  <td> <label>'.$returndata[0]->state_tax_allother_itc.'<span class="starred"></span></label> </td>
 <td> <label>'.$returndata[0]->cess_tax_allother_itc.'<span class="starred"></span></label> </td> </tr>
  <tr>  <td style="font-size: 13px; background: #fdede8; color: #333; border-bottom: 1px solid #f4d4ca;" class="lftheading"><strong>(B) ITC Reversed</strong></td>
   <td> <label>'.$returndata[0]->integrated_tax_itc_reversed_b.'<span class="starred"></span></label>   </td>
  <td> <label>'.$returndata[0]->central_tax_itc_reversed_b.'<span class="starred"></span></label>  </td>	
<td> <label>'.$returndata[0]->state_tax_itc_reversed_b.'<span class="starred"></span></label></td>						 
<td> <label>'.$returndata[0]->cess_tax_itc_reversed_b.'<span class="starred"></span></label> </td> </tr>   
  <tr> <td style="font-size: 13px; background: #fdede8; color: #333;border-bottom: 1px solid #f4d4ca;" class="lftheading" >(1) As per rules 42 & 43 of CGST Rules</td>
 	<td> <label>'.$returndata[0]->integrated_tax_itc_reversed_cgstrules.'<span class="starred"></span></label></td> 
  <td> <label>'.$returndata[0]->central_tax_itc_reversed_cgstrules.'<span class="starred"></span></label> </td>
 <td><label>'.$returndata[0]->state_tax_itc_reversed_cgstrules.'<span class="starred"></span></label> </td>	
 <td> <label>'.$returndata[0]->cess_tax_itc_reversed_cgstrules.'<span class="starred"></span></label>  </td>  </tr> 
 <tr> <td style="font-size: 13px;   background: #fdede8;color: #333;border-bottom: 1px solid #f4d4ca;" class="lftheading" >(2) Others</td>                      
<td> <label>'.$returndata[0]->integrated_tax_itc_reversed_other.'<span class="starred"></span></label>  </td>	
<td> <label>'.$returndata[0]->central_tax_itc_reversed_other.'<span class="starred"></span></label>   </td> 
<td> <label>'.$returndata[0]->state_tax_itc_reversed_other.'<span class="starred"></span></label> </td>	
<td><label>'.$returndata[0]->cess_tax_itc_reversed_other.'<span class="starred"></span></label> </td>  </tr><tr> <td style="font-size: 13px; background: #fdede8;  color: #333;   border-bottom: 1px solid #f4d4ca;" class="lftheading"><strong>(C) Net ITC Available (A) – (B)</strong></td>
<td><label>'.$returndata[0]->integrated_tax_net_itc_a_b.'<span class="starred"></span></label>  </td>
<td> <label>'.$returndata[0]->central_tax_net_itc_a_b.'<span class="starred"></span></label>  </td>
<td> <label>'.$returndata[0]->state_tax_net_itc_a_b.'<span class="starred"></span></label>  </td>
<td>	 <label>'.$returndata[0]->cess_tax_net_itc_a_b.'<span class="starred"></span></label> </td> </tr>
<tr>   <td style="font-size: 13px; background: #fdede8;color: #333; border-bottom: 1px solid #f4d4ca;" class="lftheading"><strong>(D) Ineligible ITC</strong></td> <td> <label>'.$returndata[0]->integrated_tax_inligible_itc.'<span class="starred"></span></label>  </td> 
<td>	 <label>'.$returndata[0]->central_tax_inligible_itc.'<span class="starred"></span></label> </td> 
<td><label>'.$returndata[0]->state_tax_inligible_itc.'<span class="starred"></span></label>  </td>
<td> <label>'.$returndata[0]->cess_tax_inligible_itc.'<span class="starred"></span></label>  </td> </tr>
<tr><td style="font-size: 13px;background: #fdede8; color: #333;border-bottom: 1px solid #f4d4ca;" class="lftheading">(1) As per section 17(5)</td>
<td> <label>'.$returndata[0]->integrated_tax_inligible_itc_17_5.'<span class="starred"></span></label>  </td> 
 <td> <label>'.$returndata[0]->central_tax_inligible_itc_17_5.'<span class="starred"></span></label>    </td>	
<td> <label>'.$returndata[0]->state_tax_inligible_itc_17_5.'<span class="starred"></span></label>   </td>	
 <td> <label>'.$returndata[0]->cess_tax_inligible_itc_17_5.'<span class="starred"></span></label>  </td> </tr>
 <tr> <td style="font-size: 13px;background: #fdede8;color: #333; border-bottom: 1px solid #f4d4ca;" class="lftheading">(2) Others</td>                       
<td> <label>'.$returndata[0]->integrated_tax_inligible_itc_others.'<span class="starred"></span></label>  </td>   
 <td> <label>'.$returndata[0]->central_tax_inligible_itc_others.'<span class="starred"></span></label> </td>
 <td> <label>'.$returndata[0]->state_tax_inligible_itc_others.'<span class="starred"></span></label>  </td>
  <td> <label>'.$returndata[0]->cess_tax_inligible_itc_others.'<span class="starred"></span></label> </td></tr></tbody>  </table> </div>
  <div class="greyheading" style="float: left;width: 100%;font-size: 15px;margin: 15px 0 15px 0;background: #adadad;padding: 7px 10px;color: #FFF;font-family: opensans_bold;font-weight: normal;">5. Values of exempt, nil-rated and non-GST inward supplies</div>
 <div class="tableresponsive">  <table border="1" bordercolor="#ccc" cellpadding="5" cellspacing="0" style="font-size:13px;   font-family: opensans_bold; font-weight:normal; width: 100%;font-weight:normal;" class="table  tablecontent tablecontent2 bordernone">
   <thead><tr><th align="left">Nature of supplies</th> <th align="left">Inter-State supplies</th>  <th align="left">Intra-State supplies</th>   </tr> </thead>
  <tbody><tr><td style="font-size: 13px;background: #fdede8;color: #333; border-bottom: 1px solid #f4d4ca;" class="lftheading" width="25%">From a supplier under composition scheme, Exempt and Nil rated supply</td>
  <td> <label>'.$returndata[0]->inter_state_supplies_composition_scheme.'<span class="starred"></span></label> </td> 
 <td> <label>'.$returndata[0]->intra_state_supplies_composition_scheme.'<span class="starred"></span></label> </td>  </tr>
 <tr>  <td style="font-size: 13px; background: #fdede8; color: #333;border-bottom: 1px solid #f4d4ca;" class="lftheading" width="25%">Non GST supply</td>                             	                                   
	<td> <label>'.$returndata[0]->inter_state_supplies_nongst_supply.'<span class="starred"></span></label></td>   
 <td> <label>'.$returndata[0]->intra_state_supplies_nongst_supply.'<span class="starred"></span></label>  </td></tr>
 </tbody></table></div><div class="greyheading" style="float: left;width: 100%;font-size: 15px;margin: 15px 0 15px 0;background: #adadad;padding: 7px 10px;color: #FFF;font-family: opensans_bold;font-weight: normal;">6.1 Payment of tax</div>
  <div class="tableresponsive"> <table border="1" bordercolor="#ccc" cellpadding="5" cellspacing="0" style="font-size:13px;   font-family: opensans_bold; font-weight:normal; width: 100%;font-weight:normal;" class="table  tablecontent tablecontent2 bordernone">
 <tr><th align="left">Description</th><th align="left">Tax payable</th><th colspan="4" align="center">Paid through ITC</th><th align="left">Tax paid <br/>TDS./TCS</th>
 <th align="left">Tax/Cess <br/>paid in<br/>cash</th>  <th align="left">Interest</th>  <th align="left">Late Fee</th>   </tr>	
<tr>  <th>&nbsp;</th>  <th>&nbsp;</th><th align="left">Integrated Fee<br> Tax</th> <th align="left">Central<br>Tax</th>  <th align="left">State/UT<br>Tax</th>
   <th align="left">Cess</th>     <th>&nbsp;</th>  <th>&nbsp;</th>  <th>&nbsp;</th>    <th>&nbsp;</th>     </tr>                               
<tr><td style="font-size: 13px;background: #fdede8; color: #333;border-bottom: 1px solid #f4d4ca;" class="lftheading" width="25%">Integrated Tax</td>                                 
 <td> <label>'.$returndata[0]->tax_payable_integrated_tax.'<span class="starred"></span></label>  </td>
<td> <label>'.$returndata[0]->integrated_fee_integrated_tax.'<span class="starred"></span></label>  </td> 
<td><label>'.$returndata[0]->central_integrated_tax.'<span class="starred"></span></label> </td>
<td> <label>'.$returndata[0]->state_integrated_tax.'<span class="starred"></span></label></td>
<td><label>'.$returndata[0]->cess_integrated_tax.'<span class="starred"></span></label> </td>								 
 <td> <label>'.$returndata[0]->taxpaid_tdstcs_integrated_tax.'<span class="starred"></span></label></td>							
  <td><label>'.$returndata[0]->taxpaid_cess_integrated_tax.'<span class="starred"></span></label>  </td>								 
<td> <label>'.$returndata[0]->interest_integrated_tax.'<span class="starred"></span></label></td>
 <td><label>'.$returndata[0]->latefee_integrated_tax.'<span class="starred"></span></label> </td>  </tr>
   <tr>    <td style="font-size: 13px;  background: #fdede8; color: #333; border-bottom: 1px solid #f4d4ca;" class="lftheading">Central Tax</td>                              									 
<td>  <label>'.$returndata[0]->tax_payable_central_tax.'<span class="starred"></span></label>    </td>	
 <td> <label>'.$returndata[0]->integrated_fee_central_tax.'<span class="starred"></span></label>  </td>
<td> <label>'.$returndata[0]->central_central_tax.'<span class="starred"></span></label>   </td>								 
 <td style="background:black;"> <label>'.$returndata[0]->state_central_tax.'<span class="starred"></span></label>  </td>						
 <td> <label>'.$returndata[0]->cess_central_tax.'<span class="starred"></span></label>    </td>								 
 <td>	 <label>'.$returndata[0]->taxpaid_tdstcs_central_tax.'<span class="starred"></span></label>   </td>									
 <td> <label>'.$returndata[0]->taxpaid_cess_central_tax.'<span class="starred"></span></label></td>							
<td>  <label>'.$returndata[0]->interest_central_tax.'<span class="starred"></span></label> </td>							 
  <td>	 <label>'.$returndata[0]->latefee_central_tax.'<span class="starred"></span></label>   </td>   </tr>
  <tr>  <td style="font-size: 13px; background: #fdede8; color: #333;  border-bottom: 1px solid #f4d4ca;" class="lftheading">State/UT Tax</td>							                  
 <td><label>'.$returndata[0]->tax_payable_stateut_tax.'<span class="starred"></span></label> </td>                          
<td><label>'.$returndata[0]->integrated_stateut_tax.'<span class="starred"></span></label> </td>  
 <td style="background:black;">	 <label>'.$returndata[0]->central_stateut_tax.'<span class="starred"></span></label> </td>
 <td> <label>'.$returndata[0]->state_stateut_tax.'<span class="starred"></span></label>  </td>	
 <td> <label>'.$returndata[0]->cess_stateut_tax.'<span class="starred"></span></label> </td>
 <td><label>'.$returndata[0]->taxpaid_tcs_stateut_tax.'<span class="starred"></span></label>    </td>
<td> <label>'.$returndata[0]->taxpaid_cess_stateut_tax.'<span class="starred"></span></label>  </td>							                      
<td> <label>'.$returndata[0]->interest_stateut_tax.'<span class="starred"></span></label> </td>								 
<td><label>'.$returndata[0]->latefee_stateut_tax.'<span class="starred"></span></label>  </td></tr> 
<tr> <td style="font-size: 13px; background: #fdede8;color: #333; border-bottom: 1px solid #f4d4ca;" class="lftheading">Cess</td>							
<td><label>'.$returndata[0]->tax_payable_cess_tax.'<span class="starred"></span></label>  </td>
 <td style="background:black;"><label>'.$returndata[0]->integrated_cess_tax.'<span class="starred"></span></label> </td>  
  <td style="background:black;"> <label>'.$returndata[0]->central_cess_tax.'<span class="starred"></span></label>  </td>	
<td style="background:black;"> <label>'.$returndata[0]->state_cess_tax.'<span class="starred"></span></label>  </td>                              
<td><label>'.$returndata[0]->cess_cess_tax.'<span class="starred"></span></label>     </td>
 <td><label>'.$returndata[0]->taxpaid_tcs_cess_tax.'<span class="starred"></span></label>    </td>								 
 <td>  <label>'.$returndata[0]->taxpaid_cess_cess_tax.'<span class="starred"></span></label>  </td>
 <td> <label>'.$returndata[0]->interest_cess_tax.'<span class="starred"></span></label> </td>								
 <td>	 <label>'.$returndata[0]->latefee_cess_tax.'<span class="starred"></span></label></td>	</tr> </table>  </div>	
  <div class="greyheading" style="float: left;width: 100%;font-size: 15px;margin: 15px 0 15px 0;background: #adadad;padding: 7px 10px;color: #FFF;font-family: opensans_bold;font-weight: normal;">6.2 TDS/TCS Credit</div>
 <div class="tableresponsive">  <table border="1" bordercolor="#ccc" cellpadding="5" cellspacing="0" style="font-size:13px;   font-family: opensans_bold; font-weight:normal; width: 100%;font-weight:normal;" class="table  tablecontent tablecontent2 bordernone">
 <thead>
                                
                                <tr>
                                 <th align="left">Details</th>
                                 <th align="left">Integrated Tax</th>
                                 <th align="left">Central Tax</th> 
                                  <th align="left">State/UT Tax</th>                                  
                                   </tr>
                                </thead>
                                
                                <tbody>
                                    <tr>
                                    <td class="lftheading" style="font-size: 13px;
    background: #fdede8;
    color: #333;
    border-bottom: 1px solid #f4d4ca;" width="25%">TDS</td>
									 <td> 
							
									 <label>'.$returndata[0]->integrated_tax_tds.'<span class="starred"></span></label>
								
                                 </td>
								  <td> 
						
									 <label>'.$returndata[0]->central_tax_tds.'<span class="starred"></span></label>
							
                                 </td>
								 <td> 
						
									 <label>'.$returndata[0]->state_tax_tds.'<span class="starred"></span></label>
								
                                 </td>
                                    </tr>
                                    
                                     <tr>
                                    <td style="font-size: 13px;
    background: #fdede8;
    color: #333;
    border-bottom: 1px solid #f4d4ca;" class="lftheading" width="25%">TCS</td>
									 <td> 
							
									 <label>'.$returndata[0]->integrated_tax_tcs.'<span class="starred"></span></label>
							
                                 </td>
								 <td> 
						
						
									 <label>'.$returndata[0]->central_tax_tcs.'<span class="starred"></span></label>
								
                                 </td>
								 <td> 
							
									 <label>'.$returndata[0]->state_tax_tcs.'<span class="starred"></span></label>
							
                                 </td>
                                    </tr>
                                    
                                   
                                    
                                </tbody>
                            </table>
								
                          								
                        </div>
                        
                        
                        </div> 
                    
       	  </div>
 		 
    
    </div>';
           $mpdfHtml .='</div>';
		    $mpdfHtml .='</body>';
	   $mpdfHtml .='</html>';
	   
        					
							
      

       

        
     
     

        return $mpdfHtml;

    }
    public function saveGstr3b()
    {
		$data = $this->get_results("select * from ".TAB_PREFIX."client_return_gstr3b where added_by='".$_SESSION['user_detail']['user_id']."' and financial_month='".$this->sanitize($_GET['returnmonth'])."'");
		$dataArr = $this->getGSTR3bData();
	
		//$dataPlaceOfSupply = $this->getPlaceOfSupply();
		
	    $sql = "select * from " . TAB_PREFIX . "client_kyc where added_by='" . $_SESSION['user_detail']['user_id'] . "' order by id desc limit 0,1";
       
       $clientdata = $this->get_results($sql);
	   $client_gstin_number;
	   if(count($clientdata) > 0 )
	   {
		   $client_gstin_number = $clientdata[0]->gstin_number;
	   }
	   $dataArr['client_gstin_number'] = $client_gstin_number;
	   

$returnmonth = $this->sanitize($_GET['returnmonth']);
		if(empty($data))
		{
			$dataArr['financial_month']=$this->sanitize($_GET['returnmonth']);
			
			if ($this->insert(TAB_PREFIX.'client_return_gstr3b', $dataArr)) {
				$this->getPlaceOfSupplyUnregistered();
				$this->getPlaceOfSupplyComposition();
				$this->getPlaceOfSupplyUinHolder();
				$this->setSuccess('GSTR3B Saved Successfully');
				$this->logMsg("GSTR3B Inserted financial month : " . $returnmonth,"gstr_3b");
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
			if ($this->update(TAB_PREFIX.'client_return_gstr3b', $dataArr,array('added_by'=>$_SESSION['user_detail']['user_id'],'financial_month'=>$this->sanitize($_GET['returnmonth'])))) {
				$this->getPlaceOfSupplyUnregistered();
				$this->getPlaceOfSupplyComposition();
				$this->getPlaceOfSupplyUinHolder();
		                      
				$this->setSuccess('GSTR3B Saved Successfully');
				$this->logMsg("GSTR3B updated financial month : " . $returnmonth,"gstr_3b");
				return true;
			}
			else
			{
				$this->setError('Failed to save GSTR3B data');
			   return false;    	   
		   }
		}
	   
   }
    
}