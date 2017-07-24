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
    
   public function finalSaveGstr3b()
   {
	$return_id =   isset($_POST['returnid']) ? $_POST['returnid'] : '';
	$userid = $_SESSION['user_detail']['user_id'];
	 if($this->update("gst_client_return_gstr3b", array('final_submit' => 1), array('return_id' => $return_id)))
	 {
		 $this->setSuccess('GSTR3B Submitted Successfully');
     return true;
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
		$dataArr['place_of_supply_unregistered_person'] = isset($_POST['place_of_supply_unregistered_person']) ? $_POST['place_of_supply_unregistered_person'] : '';
		$dataArr['total_taxable_value_unregistered_person'] = isset($_POST['total_taxable_value_unregistered_person']) ? $_POST['total_taxable_value_unregistered_person'] : '';
		 $dataArr['amount_of_integrated_tax_unregistered_person'] = isset($_POST['amount_of_integrated_tax_unregistered_person']) ? $_POST['amount_of_integrated_tax_unregistered_person'] : '';
		 $dataArr['place_of_supply_taxable_person'] = isset($_POST['place_of_supply_taxable_person']) ? $_POST['place_of_supply_taxable_person'] : '';
		$dataArr['total_taxable_value_taxable_person'] = isset($_POST['total_taxable_value_taxable_person']) ? $_POST['total_taxable_value_taxable_person'] : '';
		$dataArr['amount_of_integrated_tax_taxable_person'] = isset($_POST['amount_of_integrated_tax_taxable_person']) ? $_POST['amount_of_integrated_tax_taxable_person'] : '';
		$dataArr['place_of_supply_uin_holder'] = isset($_POST['place_of_supply_uin_holder']) ? $_POST['place_of_supply_uin_holder'] : '';
		$dataArr['total_taxable_value_uin_holder'] = isset($_POST['total_taxable_value_uin_holder']) ? $_POST['total_taxable_value_uin_holder'] : '';
		$dataArr['amount_of_integrated_uin_holder'] = isset($_POST['amount_of_integrated_uin_holder']) ? $_POST['amount_of_integrated_uin_holder'] : '';
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
		$dataArr['state_tax_tcs'] = isset($_POST['state_tax_tcs']) ? $_POST['state_tax_tcs'] : '';
		
		$dataArr['return_filling_date'] = date('Y-m-d H:i:s');
		$dataArr['added_by'] = $_SESSION['user_detail']['user_id'];
		return $dataArr;
	}
    public function saveGstr3b()
    {
		$data = $this->get_results("select * from ".TAB_PREFIX."client_return_gstr3b where added_by='".$_SESSION['user_detail']['user_id']."' and financial_month='".$this->sanitize($_GET['returnmonth'])."'");
		$dataArr = $this->getGSTR3bData();
	    $sql = "select * from " . TAB_PREFIX . "client_kyc where added_by='" . $_SESSION['user_detail']['user_id'] . "' order by id desc limit 0,1";
       
       $clientdata = $this->get_results($sql);
	   $client_gstin_number;
	   if(count($clientdata) > 0 )
	   {
		   $client_gstin_number = $clientdata[0]->gstin_number;
	   }
	   $dataArr['client_gstin_number'] = $client_gstin_number;
	   


		if(empty($data))
		{
			$dataArr['financial_month']=$this->sanitize($_GET['returnmonth']);
			if ($this->insert(TAB_PREFIX.'client_return_gstr3b', $dataArr)) {
				$this->setSuccess('GSTR3B Saved Successfully');
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
    
}