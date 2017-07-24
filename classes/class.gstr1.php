<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

final class gstr1 extends validation {
    
    function __construct() {
        parent::__construct();
    }
    
    public function gstr1Upload()
    {
        $fmonth = isset($_GET['returnmonth']) ? $_GET['returnmonth'] : date('Y-m');
        $this->getGSTR1Data($fmonth);
    }
	 public function selectgstr1Upload()
    {
        $fmonth = isset($_GET['returnmonth']) ? $_GET['returnmonth'] : date('Y-m');
        $this->selectgetGSTR1Data($fmonth);
    }
   
     private function selectgetGSTR1Data($fmonth)
    {
        $dataRes = $this->generalGSTR1InvoiceList($fmonth);
        $flag=0;
        if(!empty($dataRes))
        {
            $x=0;
            $dataUpdate= array();
            
			if(!empty($_POST['name'])){
// Loop to store and display values of individual checked checkbox.
			foreach($_POST['name'] as $selected){
			  $dataUpdate[$x]['set']['is_gstr1_uploaded']= '1';
                $dataUpdate[$x]['where']['invoice_id']= $selected;
				$x++;
			} 
			}
			else
			{
				$this->setError("No invoice selected to Upload");
			}
            if($this->updateMultiple($this->getTableName('client_invoice'), $dataUpdate))
            {
				
                $flag=1;
                $dataReturn = $this->get_results("select * from ".TAB_PREFIX."return where where return_month='".$fmonth."' and client_id='".$_SESSION['user_detail']['user_id']."' and type='gstr1'");
                if(!empty($dataReturn))
                {
                    $dataGST1_set['financial_year']='2017-2018';
                    $dataGST1_set['return_month']=$fmonth;
                    $dataGST1_set['status']='2';
                    
                    
                    $dataGST1['type']='gstr1';
                    $dataGST1['client_id']=$_SESSION['user_detail']['user_id'];
                    
                    $this->update(TAB_PREFIX."return",$dataGST1_set,$dataGST1);
                }
                else {
                    $dataGST1['financial_year']='2017-2018';
                    $dataGST1['return_month']=$fmonth;
                    $dataGST1['type']='gstr1';
                    $dataGST1['client_id']=$_SESSION['user_detail']['user_id'];
                    $dataGST1['status']='2';
                    $this->insert(TAB_PREFIX."return",$dataGST1);
                }
				
                $this->setSuccess("GSTR1 Data Uploaded") ;
            }
            else 
            {
                $flag=2;
                $this->setError("Failed to Upload Data");
            }
        }
        if($flag==1)
        {
            return true;
        }
        if($flag==2)
        {
            return false;
        }
        if($flag==0)
        {
            $this->setError('No new data to upload');
            return false;
        }
        return false;
    }
    
    private function getGSTR1Data($fmonth)
    {
        $dataRes = $this->generalGSTR1InvoiceList($fmonth);
        $flag=0;
        if(!empty($dataRes))
        {
            $x=0;
            $dataUpdate= array();
            foreach($dataRes as $dataRe)
            {
                $dataUpdate[$x]['set']['is_gstr1_uploaded']= '1';
                $dataUpdate[$x]['where']['invoice_id']= $dataRe->invoice_id;
                $x++;
            }
            if($this->updateMultiple($this->getTableName('client_invoice'), $dataUpdate))
            {
                $flag=1;
              //  $dataReturn = $this->get_results("select * from ".TAB_PREFIX."return where client_id='".$_SESSION['client_detail']['user_id']."'");
			     $dataReturn = $this->get_results("select * from ".TAB_PREFIX."return where where return_month='".$fmonth."' and client_id='".$_SESSION['user_detail']['user_id']."' and type='gstr1'");
             
                if(!empty($dataReturn))
                {
                    $dataGST1_set['financial_year']='2017-2018';
                    $dataGST1_set['return_month']=$fmonth;
                    $dataGST1_set['status']='2';
                    
                    
                    $dataGST1['type']='gstr1';
                    $dataGST1['client_id']=$_SESSION['user_detail']['user_id'];
                    
                    $this->update(TAB_PREFIX."return",$dataGST1_set,$dataGST1);
                }
                else {
                    $dataGST1['financial_year']='2017-2018';
                    $dataGST1['return_month']=$fmonth;
                    $dataGST1['type']='gstr1';
                    $dataGST1['client_id']=$_SESSION['user_detail']['user_id'];
                    $dataGST1['status']='2';
                    $this->insert(TAB_PREFIX."return",$dataGST1);
                }
                $this->setSuccess("GSTR1 Data Uploaded") ;
            }
            else 
            {
                $flag=2;
                $this->setError("Failed to Upload Data");
            }
        }
        if($flag==1)
        {
            return true;
        }
        if($flag==2)
        {
            return false;
        }
        if($flag==0)
        {
            $this->setError('No new data to upload');
            return false;
        }
        return false;
    }
    
    public function gstr1File()
    {
        $fmonth = isset($_GET['returnmonth']) ? $_GET['returnmonth'] : date('Y-m');
        $this->getGSTR1FileData($fmonth);
    }
    
    private function getGSTR1FileData($fmonth)
    {
        $dataRes = $this->generalGSTR1InvoiceList($fmonth,'1');
        $flag=0;
        if(!empty($dataRes))
        {
            $x=0;
            $dataUpdate= array();
            foreach($dataRes as $dataRe)
            {
                $dataUpdate[$x]['set']['is_gstr1_uploaded']= '2';
                $dataUpdate[$x]['where']['invoice_id']= $dataRe->invoice_id;
                $x++;
            }
            if($this->updateMultiple($this->getTableName('client_invoice'), $dataUpdate))
            {
                $flag=1;
                if(!empty($dataReturn))
                {
                    $dataGST1_set['financial_year']='2017-2018';
                    $dataGST1_set['return_month']=$fmonth;
                    $dataGST1_set['status']='3';
                    
                    
                    $dataGST1['type']='gstr1';
                    $dataGST1['client_id']=$_SESSION['client_detail']['user_id'];
                    
                    $this->update(TAB_PREFIX."return",$dataGST1_set,$dataGST1);
                }
                else {
                    $dataGST1['financial_year']='2017-2018';
                    $dataGST1['return_month']=$fmonth;
                    $dataGST1['type']='gstr1';
                    $dataGST1['client_id']=$_SESSION['user_detail']['user_id'];
                    $dataGST1['status']='3';
                    $this->insert(TAB_PREFIX."return",$dataGST1);
                }
                $this->setSuccess("GSTR1 is Filed") ;
            }
            else 
            {
                $flag=2;
                $this->setError("Failed to File GSTR1");
            }
        }
        if($flag==1)
        {
            return true;
        }
        if($flag==2)
        {
            return false;
        }
        if($flag==0)
        {
            $this->setError('No invoices are uploaded to GST');
            return false;
        }
        return false;
    }
}