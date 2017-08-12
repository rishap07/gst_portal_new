<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

final class module extends validation {
    
    function __construct() {
        parent::__construct();
    }
    
   
   
  
  
   
	 
	private function getmoduleData()
	{
		 $dataArr = array();
		 
			
		 $dataArr['module_name'] = isset($_POST['module_name']) ? $_POST['module_name'] : '';
		 $dataArr['url'] = isset($_POST['module_url']) ? $_POST['module_url'] : '';
		 $dataArr['Title'] = isset($_POST['module_title']) ? $_POST['module_title'] : '';
		 $dataArr['status'] = isset($_POST['module_status']) ? $_POST['module_status'] : '';
		  
			
		 return $dataArr;
		 
       
	}
	 
     
    public function updateModule()
    {
		$dataArr =array();
		$dataArr = $this->getmoduleData();
		if(!empty($_GET["id"]) && (!empty($_GET["action"])))
		{
			$sql="select count(module_id) as numcount from ".TAB_PREFIX."module WHERE module_id=".$_GET["id"]."";
			$dataCurrentArr = $this->get_results($sql);
			
			if($dataCurrentArr[0]->numcount > 0)
			{
				$dataConditionArray['module_id'] = $_GET["id"];
				
				if ($this->update(TAB_PREFIX.'module', $dataArr, $dataConditionArray)) {

					$this->setSuccess("Module information updated successfully");
					$this->logMsg("User ID : " . $_SESSION['user_detail']['user_id'] . " update the Module info");
					return true;
				} else {

					$this->setError($this->validationMessage['failed']);
					return false;
				}
				
  			}
			else
			{
				$sql="select count(module_id) as numcount from ".TAB_PREFIX."module WHERE module_name='".$dataArr["module_name"]."'";
				$dataCurrentArr = $this->get_results($sql);
				
				if($dataCurrentArr[0]->numcount > 0)

				{
					$this->setError('Module Name already exists');
					   return false; 
				}	
				else
				{
				
					if ($this->insert(TAB_PREFIX.'module', $dataArr)) {
						$this->setSuccess('Module Saved Successfully');
						return true;
					}
					else
					{
						$this->setError('Failed to save Module data');
					   return false;    	   
				   }
				}

			}
		}
		else
		{
				$sql="select count(module_id) as numcount from ".TAB_PREFIX."module WHERE module_name='".$dataArr["module_name"]."'";
			$dataCurrentArr = $this->get_results($sql);
			
			if($dataCurrentArr[0]->numcount > 0)

			{
				$this->setError('Module Name already exists');
				   return false; 
			}	
			else
			{
				if ($this->insert(TAB_PREFIX.'module', $dataArr)) {
					$this->setSuccess('Module Saved Successfully');
					return true;
				}
				else
				{
					$this->setError('Failed to save Coupon data');
				   return false;    	   
			   }
			}
		}
		
		
		
		
		
	 

        
       
       
       
       
      
      

        
	
   }
    
}