<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

final class adminsetting extends validation {
    
    function __construct() {
        parent::__construct();
    }
    
        
	 
	private function getsettingData()
	{
		
		 $dataArr = array();
			
		 $dataArr['status'] = isset($_POST['setting_status']) ? $_POST['setting_status'] : '';
		 $dataArr['tollfree_setting'] = isset($_POST['tollfree_setting']) ? $_POST['tollfree_setting'] : '';
		 $dataArr['livechat_setting'] = isset($_POST['livechat_setting']) ? $_POST['livechat_setting'] : '';
		  	
		//$dataArr['tollfree_setting']  = mysqli_real_escape_string($dataArr['tollfree_setting'],'');
		//$dataArr['livechat_setting']  = mysqli_real_escape_string($dataArr['livechat_setting'],'');
	
		 return $dataArr;
		 
       
	}
	 
     
    public function updateSetting()
    {
		$dataArr =array();
		$dataArr = $this->getsettingData();
		if(!empty($_GET["id"]) && (!empty($_GET["action"])))
		{
			$sql="select count(id) as numcount from ".TAB_PREFIX."admin_setting WHERE id=".$_GET["id"]."";
			$dataCurrentArr = $this->get_results($sql);
			
			if($dataCurrentArr[0]->numcount > 0)
			{
				$dataConditionArray['id'] = $_GET["id"];
			   $dataArr['updated_by'] = $_SESSION['user_detail']['user_id'];
               $dataArr['updated_date'] = date('Y-m-d H:i:s');
			 
				if ($this->update(TAB_PREFIX.'admin_setting', $dataArr, $dataConditionArray)) {
                    $_SESSION["user_detail"]["tollfree_info"] = $dataArr["tollfree_setting"];
					$_SESSION["user_detail"]["livechat_info"] = $dataArr["livechat_setting"];
					
					$this->setSuccess("admin setting updated successfully");
					$this->logMsg("User ID : " . $_SESSION['user_detail']['user_id'] . " update the admin setting info","adminsetting");
					return true;
				} else {

					$this->setError($this->validationMessage['failed']);
					return false;
				}
				
  			}
			else
			{
				   
				

			}
		}
		else
		{
				    
		}
		
		
		
		
		
	 

        
       
       
       
       
      
      

        
	
   }
    
}