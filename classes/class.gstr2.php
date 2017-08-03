<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

final class gstr2 extends validation {
    
    function __construct() {
        parent::__construct();
    }

    public function downloadGSTR2() {
        
		$gstr2ReturnMonth = isset($_POST['gstr2ReturnMonth']) ? $_POST['gstr2ReturnMonth'] : '';

		if (empty($gstr2ReturnMonth)) {
            $this->setError($this->validationMessage['mandatory']);
            return false;
        }
		$dataRes = $this->generalGSTR2InvoiceList($gstr2ReturnMonth);

        if(!empty($dataRes)) {

            $x=0;
            $dataUpdate= array();
            foreach($dataRes as $dataRe) {
                $dataUpdate[$x]['set']['is_gstr2_downloaded']= '1';
                $dataUpdate[$x]['where']['invoice_id']= $dataRe->invoice_id;
                $x++;
            }

			if($this->updateMultiple($this->getTableName('client_invoice'), $dataUpdate)) {
				
				$this->setSuccess("GSTR2 Data Downloaded") ;
            } else {
                $this->setError("Failed to Download Data");
            }
        } else {
			$this->setError("There is no invoice to Download");
		}
    }
	public function startGstr2()
	{
		 $sql = "select * from " . TAB_PREFIX . "return where client_id='" . $_SESSION['user_detail']['user_id'] . "' and return_month='".$_GET["returnmonth"]."' and type='gstr2'";
     
       $clientdata = $this->get_results($sql);
	   
	   if(empty($clientdata))
	   {
		    
		    $dataArr['return_month']=$this->sanitize($_GET['returnmonth']);
			$dataArr['type']='gstr2';
			$dataArr['client_id']= $_SESSION['user_detail']['user_id'];
			$year = $this->generateFinancialYear();
			$dataArr['financial_year']=$year;
			$dataArr['status']=1;
			
			if ($this->insert(TAB_PREFIX.'return', $dataArr)) {
				//$this->setSuccess('GSTR2 Saved Successfully');
				return true;
			}
			else
			{
				$this->setError('Failed to save GSTR2 data');
			   return false;    	   
		   }
	   }
	   else
	   {
		   /*
		   if ($this->update(TAB_PREFIX.'client_return_gstr3b', $dataArr,array('added_by'=>$_SESSION['user_detail']['user_id'],'financial_month'=>$this->sanitize($_GET['returnmonth'])))) {
				$this->setSuccess('GSTR3B Saved Successfully');
				return true;
			}
			else
			{
				$this->setError('Failed to save GSTR3B data');
			   return false;    	   
		   }
		   */
	   }
	}
}