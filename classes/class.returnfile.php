<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

final class returnfile extends validation {
    
    function __construct() {
        parent::__construct();
    }
    
   
   
  
  
   
	 
	private function getreturnfiledata()
	{
		 $dataArr = array();
		
		  $dataArr['returnform_name'] = isset($_POST['returnform_name']) ? $_POST['returnform_name'] : '';
		  $dataArr['returnfile_description'] = isset($_POST['returnfile_description']) ? $_POST['returnfile_description'] : '';
		  $dataArr['returnfile_description']  = str_replace("<p>"," ",$dataArr['returnfile_description']);
		  $dataArr['returnfile_description']  = str_replace("</p>"," ",$dataArr['returnfile_description']);
		  $dataArr['returnfile_date'] = isset($_POST['returnfile_date']) ? $_POST['returnfile_date'] : '';
		  $dataArr['returnfile_type'] = isset($_POST['returnfile_type']) ? $_POST['returnfile_type'] : '';
		  $dataArr['returntofile_vendor_id'] = isset($_POST['vendor_type']) ? $_POST['vendor_type'] : '';
		  $dataArr['status'] = isset($_POST['returnfile_status']) ? $_POST['returnfile_status'] : '';
		  return $dataArr;
		      
	}	
     
    public function updateReturnFile()
    {
		$dataArr =array();
		$dataArr = $this->getreturnfiledata();
	   if(!empty($_GET["id"]) && (!empty($_GET["action"])))
		{
			$sql="select count(id) as numcount from ".TAB_PREFIX."returnfile_setting WHERE id=".$_GET["id"]."";
			$dataCurrentArr = $this->get_results($sql);
			
			if($dataCurrentArr[0]->numcount > 0)
			{
				$dataConditionArray['id'] = $_GET["id"];
				 $dataArr['updated_date'] = date('Y-m-d H:i:s');
				 $dataArr['updated_by'] = $_SESSION["user_detail"]["user_id"];
				//var_dump($dataArr);
				if ($this->update(TAB_PREFIX.'returnfile_setting', $dataArr, $dataConditionArray)) {

					$this->setSuccess("Returnfile information updated successfully");
					$this->logMsg("User ID : " . $_SESSION['user_detail']['user_id'] . " update the returnfile setting info","returnfile_setting");
					return true;
				} else {

					$this->setError($this->validationMessage['failed']);
					return false;
				}
				
  			}
			else
			{
				$sql="select count(id) as numcount from ".TAB_PREFIX."returnfile_setting WHERE returnform_name='".$dataArr["returnform_name"]."'";
				$dataCurrentArr = $this->get_results($sql);
				
				if($dataCurrentArr[0]->numcount > 0)

				{
					$this->setError('ReturnForm Name already exists');
					   return false; 
				}	
				else
				{
				
					if ($this->insert(TAB_PREFIX.'returnfile_setting', $dataArr)) {
						$this->setSuccess('Returnfile setting Saved Successfully');
							$this->logMsg("User ID Returnfile setting added : " . $_SESSION['user_detail']['user_id'],"returnfile_setting");
											
						return true;
					}
					else
					{
						$this->setError('Failed to save returnfile data');
					   return false;    	   
				   }
				}

			}
		}
		else
		{
			$sql="select count(id) as numcount from ".TAB_PREFIX."returnfile_setting WHERE returnform_name='".$dataArr["returnform_name"]."'";
			$dataCurrentArr = $this->get_results($sql);
			if($dataCurrentArr[0]->numcount > 0)

			{
				$this->setError('ReturnForm Name already exists');
				   return false; 
			}	
			else
			{
				 $dataArr['added_date'] = date('Y-m-d H:i:s');
				 $dataArr['added_by'] = $_SESSION["user_detail"]["user_id"];
				if ($this->insert(TAB_PREFIX.'returnfile_setting', $dataArr)) {
						$this->logMsg("User ID returnfile setting info added : " . $_SESSION['user_detail']['user_id'],"returnfile_setting");
											
					$this->setSuccess('Returnfile setting Saved Successfully');
					return true;
				}
				else
				{
					$this->setError('Failed to save returnfile data');
				   return false;    	   
			   }
			}
		}
        
	
   }
    
}