<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

final class faq extends validation {
    
    function __construct() {
        parent::__construct();
    }
   
   private function getfaqData()
	{
		$dataArr = array();
		$dataArr['question'] = isset($_POST['question']) ? $_POST['question'] : '';
		$dataArr['answer'] = isset($_POST['answer']) ? $_POST['answer'] : '';
		$dataArr['answer']  = $this->replaceSpecialChar($dataArr['answer']);
		$dataArr['status'] = isset($_POST['faq_status']) ? $_POST['faq_status'] : '';
		return $dataArr;
	}
	
	public  function strip_tags_content($str) {

    $str = preg_replace("#<(.*)/(.*)>#iUs", "", $str);
	return $str;
	 }
	
    public function updateFaq()
    {
		$dataArr =array();
		$dataArr = $this->getfaqData();
		
		if(!empty($_GET["id"]) && (!empty($_GET["action"])))
		{
			$sql="select count(id) as numcount from ".TAB_PREFIX."faq WHERE id=".$_GET["id"]."";
			$dataCurrentArr = $this->get_results($sql);
			
			if($dataCurrentArr[0]->numcount > 0)
			{
				 $dataConditionArray['id'] = $_GET["id"];
				 $dataArr['updated_date'] = date('Y-m-d H:i:s');
				 $dataArr['updated_by'] = $_SESSION["user_detail"]["user_id"];
				if ($this->update(TAB_PREFIX.'faq', $dataArr, $dataConditionArray)) {

					$this->setSuccess("Your FAQ information updated successfully");
					$this->logMsg("User ID : " . $_SESSION['user_detail']['user_id'] . "update the faq info","faq");
					return true;
				} else {

					$this->setError($this->validationMessage['failed']);
					return false;
				}
				
  			}
			else
			{
				if ($this->insert(TAB_PREFIX.'faq', $dataArr)) {
					$this->setSuccess('FAQ Information Saved Successfully');
					$this->logMsg("User ID FAQ added : ". $_SESSION['user_detail']['user_id'],"faq");
					return true;
				}
				else
				{
					$this->setError('Failed to save FAQ data');
				   return false;    	   
			   }
			}
		}
		else
		{
			
			$dataArr['added_date'] = date('Y-m-d H:i:s');
			$dataArr['added_by'] = $_SESSION["user_detail"]["user_id"];
			if ($this->insert(TAB_PREFIX.'faq', $dataArr)) {
				$this->logMsg("User ID Faq added : " . $_SESSION['user_detail']['user_id'],"faq");
				
				$this->setSuccess('Faq Saved Successfully');
				return true;
			}
			else
			{
				$this->setError('Failed to save Faq data');
				return false;    	   
			}
			
		}

   }
   
   public function getFaq()
   {
	  $query='Select id,question, answer
	   from '.TAB_PREFIX.'faq
	   where is_deleted="0" and status="1"';
	 return $this->get_results($query);
   }
    
}