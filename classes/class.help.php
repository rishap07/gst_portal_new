<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

final class help extends validation {
    
    function __construct() {
        parent::__construct();
    }
       
	private function gethelpData()
	{
		$dataArr = array();
		$dataArr['help_name'] = isset($_POST['help_name']) ? $_POST['help_name'] : '';
		$dataArr['help_message'] = isset($_POST['help_message']) ?  html_entity_decode($_POST['help_message']) : '';
		$dataArr['help_message']  = str_replace("<p>"," ",$dataArr['help_message']);
		$dataArr['help_message']  = str_replace("</p>"," ",$dataArr['help_message']);
		$dataArr['start_date'] = isset($_POST['start_date']) ? $_POST['start_date'] : '';
		$dataArr['end_date'] = isset($_POST['end_date']) ? $_POST['end_date'] : '';
		$dataArr['status'] = isset($_POST['help_status']) ? $_POST['help_status'] : '';
		
			if ($_FILES['help_document']['name'] != '') {
				$help_image = $this->imageUploads($_FILES['help_document'], 'help-images', 'upload', $this->allowImageExt, 1048576, 'Max file Size 1 MB');
				if ($help_image == FALSE) {
					return false;
				} else {
					$dataArr['help_document'] = $help_image;
				}
			}
		return $dataArr;
	}
	public  function strip_tags_content($str) {
    $str = preg_replace("#<(.*)/(.*)>#iUs", "", $str);
	return $str;

 }
	
	
    public function updateHelp()
    {
		$dataArr =array();
		$dataArr = $this->gethelpData();
		
		if(strtotime($dataArr['start_date']) > strtotime($dataArr['end_date']))
		{
			$this->setError('End Date time should be greater then start date time');
			return true;
		}
		if(!empty($_GET["id"]) && (!empty($_GET["action"])))
		{
			$sql="select count(id) as numcount from ".TAB_PREFIX."help WHERE id=".$_GET["id"]."";
			$dataCurrentArr = $this->get_results($sql);
			
			if($dataCurrentArr[0]->numcount > 0)
			{
				 $dataConditionArray['id'] = $_GET["id"];
				 $dataArr['updated_date'] = date('Y-m-d H:i:s');
				 $dataArr['updated_by'] = $_SESSION["user_detail"]["user_id"];
				//var_dump($dataArr);
				if ($this->update(TAB_PREFIX.'help', $dataArr, $dataConditionArray)) {
					$this->setSuccess("Your Help information updated successfully");
					$this->logMsg("User ID : " . $_SESSION['user_detail']['user_id'] . "update the help info","help");
					return true;
				} else {
					$this->setError($this->validationMessage['failed']);
					return false;
				}
				
  			}
			else
			{
				if ($this->insert(TAB_PREFIX.'help', $dataArr)) {
					$this->setSuccess('Help Information Saved Successfully');
					$this->logMsg("User ID Help added : ". $_SESSION['user_detail']['user_id'],"help");
					return true;
				}
				else
				{
					$this->setError('Failed to save Help data');
				   return false;    	   
			   }
			}
		}
		else
		{
			$dataArr['added_date'] = date('Y-m-d H:i:s');
			$dataArr['added_by'] = $_SESSION["user_detail"]["user_id"];
			if ($this->insert(TAB_PREFIX.'help', $dataArr)) {
				$this->logMsg("User ID Help added : " . $_SESSION['user_detail']['user_id'],"help");
				
				$this->setSuccess('Help Saved Successfully');
				return true;
			}
			else
			{
				$this->setError('Failed to save Help data');
				return false;    	   
			}
			
		}

   }
    
}