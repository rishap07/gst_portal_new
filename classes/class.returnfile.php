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
		  $dataArr['cat_id'] = isset($_POST['return_cat']) ? $_POST['return_cat'] : '';
		  $dataArr['subcat_id'] = isset($_POST['return_subcat']) ? $_POST['return_subcat'] : '';
		  
		
		  return $dataArr;
		      
	}
    private function getreturnfiledatedata()
	{
		 $dataArr = array();
		
		  $dataArr['return_name'] = isset($_POST['returnform_name']) ? $_POST['returnform_name'] : '';
		  $dataArr['returnfile_date'] = isset($_POST['returnfile_date']) ? $_POST['returnfile_date'] : '';
		  $dataArr['return_month'] = isset($_POST['returnfile_month']) ? $_POST['returnfile_month'] : '';
		  $dataArr['status'] = isset($_POST['returnfile_status']) ? $_POST['returnfile_status'] : '';
		  $dataArr['cat_id'] = isset($_POST['return_cat']) ? $_POST['return_cat'] : '';
		  $dataArr['subcat_id'] = isset($_POST['return_subcat']) ? $_POST['return_subcat'] : '';
		  		
		  return $dataArr;
		      
	}
    private function getreturnsubcatdata()
	{
		 $dataArr = array();
		
		  $dataArr['subcat_name'] = isset($_POST['return_subcat']) ? $_POST['return_subcat'] : '';
		  $dataArr['status'] = isset($_POST['returnfile_status']) ? $_POST['returnfile_status'] : '';
		  $dataArr['cat_id'] = isset($_POST['return_cat']) ? $_POST['return_cat'] : '';
		  $dataArr['order_value'] = isset($_POST['order_value']) ? $_POST['order_value'] : '';
		  return $dataArr;
		      
	}
    private function getreturncatdata()
	{
		  $dataArr = array();
		  $dataArr['return_name'] = isset($_POST['return_cat']) ? $_POST['return_cat'] : '';
		  $dataArr['status'] = isset($_POST['returnfile_status']) ? $_POST['returnfile_status'] : '';
          $dataArr['returnfile_type'] = isset($_POST['returnfile_type']) ? $_POST['returnfile_type'] : '';
		  $dataArr['returntofile_vendor_id'] = isset($_POST['vendor_type']) ? $_POST['vendor_type'] : '';
          $dataArr['return_subheading'] = isset($_POST['return_subheading']) ? $_POST['return_subheading'] : '';
		  $dataArr['return_url'] = isset($_POST['return_url']) ? $_POST['return_url'] : '';
		  return $dataArr;
		      
	}
    public function updateReturnCategory()
    {
	 $dataArr =array();
		$dataArr = $this->getreturncatdata();
	  if(!empty($_GET["id"]) && (!empty($_GET["action"])))
		{
			$sql="select count(id) as numcount from ".TAB_PREFIX."return_categories WHERE id=".$_GET["id"]."";
			$dataCurrentArr = $this->get_results($sql);
			
			if($dataCurrentArr[0]->numcount > 0)
			{
				$dataConditionArray['id'] = $_GET["id"];
				 $dataArr['updated_date'] = date('Y-m-d H:i:s');
				 $dataArr['updated_by'] = $_SESSION["user_detail"]["user_id"];
				//var_dump($dataArr);
				if ($this->update(TAB_PREFIX.'return_categories', $dataArr, $dataConditionArray)) {

					$this->setSuccess("Return category information updated successfully");
					$this->logMsg("User ID : " . $_SESSION['user_detail']['user_id'] . " update the returnfile setting info","returnfile_setting");
					return true;
				} else {

					$this->setError($this->validationMessage['failed']);
					return false;
				}
				
  			}
			else
			{
			    $sql="select count(id) as numcount from ".TAB_PREFIX."return_categories WHERE return_name='".$dataArr["return_name"]."'";
				$dataCurrentArr = $this->get_results($sql);
				if($dataCurrentArr[0]->numcount > 0)

				{
					$this->setError('Return category already added');
					 return false; 
				}
           	   else
			   {
					if ($this->insert(TAB_PREFIX.'return_categories', $dataArr)) {
						$this->setSuccess('Return category Saved Successfully');
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
	           $sql="select count(id) as numcount from ".TAB_PREFIX."return_categories WHERE return_name='".$dataArr["return_name"]."'";
			   $dataCurrentArr = $this->get_results($sql);
				if($dataCurrentArr[0]->numcount > 0)

				{
					$this->setError('Return category already added');
					 return false; 
				}
           	   else
			   {	
	
				$dataArr['added_date'] = date('Y-m-d H:i:s');
				$dataArr['added_by'] = $_SESSION["user_detail"]["user_id"];
				if ($this->insert(TAB_PREFIX.'return_categories', $dataArr)) {
					$this->logMsg("User ID returnfile category info added : " . $_SESSION['user_detail']['user_id'],"returnfile_setting");
						
					$this->setSuccess('Returnfile category Saved Successfully');
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
	public function updateReturnSubcategory()
    {
	 $dataArr =array();
		$dataArr = $this->getreturnsubcatdata();
	  if(!empty($_GET["id"]) && (!empty($_GET["action"])))
		{
			$sql="select count(id) as numcount from ".TAB_PREFIX."return_subcategories WHERE id=".$_GET["id"]."";
			$dataCurrentArr = $this->get_results($sql);
			
			if($dataCurrentArr[0]->numcount > 0)
			{
				$dataConditionArray['id'] = $_GET["id"];
				 $dataArr['updated_date'] = date('Y-m-d H:i:s');
				 $dataArr['updated_by'] = $_SESSION["user_detail"]["user_id"];
				//var_dump($dataArr);
				$sql="select count(id) as numcount from ".TAB_PREFIX."return_subcategories WHERE cat_id='".$dataArr["cat_id"]."' AND id <> '".$_GET["id"]."' and order_value='".$dataArr["order_value"]."'";
				$dataCurrentArr = $this->get_results($sql);
				if($dataCurrentArr[0]->numcount > 0)
				{
					$this->setError('This order value already exists');
					 return false; 
				}
				if ($this->update(TAB_PREFIX.'return_subcategories', $dataArr, $dataConditionArray)) {

					$this->setSuccess("Return subcategory information updated successfully");
					$this->logMsg("User ID : " . $_SESSION['user_detail']['user_id'] . " update the returnfile setting info","returnfile_setting");
					return true;
				} else {

					$this->setError($this->validationMessage['failed']);
					return false;
				}
				
  			}
			else
			{
			    $sql="select count(id) as numcount from ".TAB_PREFIX."return_subcategories WHERE cat_id='".$dataArr["cat_id"]."' AND subcat_name='".$dataArr["subcat_name"]."'";
				$dataCurrentArr = $this->get_results($sql);
				if($dataCurrentArr[0]->numcount > 0)
				{
					$this->setError('Return subcategory already added');
					 return false; 
				}
           	   else
			   {
					if ($this->insert(TAB_PREFIX.'return_subcategories', $dataArr)) {
						$this->setSuccess('Return subcategory Saved Successfully');
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
              $sql="select count(id) as numcount from ".TAB_PREFIX."return_subcategories WHERE cat_id='".$dataArr["cat_id"]."' AND subcat_name='".$dataArr["subcat_name"]."'";
			  $dataCurrentArr = $this->get_results($sql);
				if($dataCurrentArr[0]->numcount > 0)

				{
					$this->setError('Return subcategory already added');
					 return false; 
				}
           	   else
			   {	
	
				$dataArr['added_date'] = date('Y-m-d H:i:s');
				$dataArr['added_by'] = $_SESSION["user_detail"]["user_id"];
				if ($this->insert(TAB_PREFIX.'return_subcategories', $dataArr)) {
					$this->logMsg("User ID returnfile date info added : " . $_SESSION['user_detail']['user_id'],"returnfile_setting");
						
					$this->setSuccess('Return subcategory Saved Successfully');
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
    public function updateReturnFileDate()
    {
		$dataArr =array();
		$dataArr = $this->getreturnfiledatedata();
		//print_r($dataArr);die;
	  if(!empty($_GET["id"]) && (!empty($_GET["action"])))
		{
			$sql="select count(id) as numcount from ".TAB_PREFIX."returnfile_dates WHERE id=".$_GET["id"]."";
			$dataCurrentArr = $this->get_results($sql);
			
			if($dataCurrentArr[0]->numcount > 0)
			{
				$dataConditionArray['id'] = $_GET["id"];
				 $dataArr['updated_date'] = date('Y-m-d H:i:s');
				 $dataArr['updated_by'] = $_SESSION["user_detail"]["user_id"];
				
				if ($this->update(TAB_PREFIX.'returnfile_dates', $dataArr, $dataConditionArray)) {

					$this->setSuccess("Returnfile dates information updated successfully");
					$this->logMsg("User ID : " . $_SESSION['user_detail']['user_id'] . " update the returnfile setting info","returnfile_setting");
					return true;
				} else {

					$this->setError($this->validationMessage['failed']);
					return false;
				}
				
  			}
			else
			{
			    $sql="select count(id) as numcount from ".TAB_PREFIX."returnfile_dates WHERE cat_id='".$dataArr["cat_id"]."' AND subcat_id='".$dataArr["subcat_id"]."' AND return_month='".$dataArr["return_month"]."'";
				$dataCurrentArr = $this->get_results($sql);
				if($dataCurrentArr[0]->numcount > 0)

				{
					$this->setError('Return file dates already added');
					 return false; 
				}
           	   else
			   {
					if ($this->insert(TAB_PREFIX.'returnfile_dates', $dataArr)) {
						$this->setSuccess('Returnfile dates setting Saved Successfully');
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
              $sql="select count(id) as numcount from ".TAB_PREFIX."returnfile_dates WHERE cat_id='".$dataArr["cat_id"]."' AND subcat_id='".$dataArr["subcat_id"]."' AND return_month='".$dataArr["return_month"]."'";
			  $dataCurrentArr = $this->get_results($sql);
				if($dataCurrentArr[0]->numcount > 0)

				{
					$this->setError('Return file dates already added');
					 return false; 
				}
           	   else
			   {	
	
				$dataArr['added_date'] = date('Y-m-d H:i:s');
				$dataArr['added_by'] = $_SESSION["user_detail"]["user_id"];
				if ($this->insert(TAB_PREFIX.'returnfile_dates', $dataArr)) {
						$this->logMsg("User ID returnfile date info added : " . $_SESSION['user_detail']['user_id'],"returnfile_setting");
											
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
				/*
				$sql="select count(id) as numcount from ".TAB_PREFIX."returnfile_setting WHERE returnform_name='".$dataArr["returnform_name"]."'";
				$dataCurrentArr = $this->get_results($sql);
				
				if($dataCurrentArr[0]->numcount > 0)

				{
					$this->setError('ReturnForm Name already exists');
					   return false; 
				}
                */				
				
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
		else
		{
			/*
			$sql="select count(id) as numcount from ".TAB_PREFIX."returnfile_setting WHERE returnform_name='".$dataArr["returnform_name"]."'";
			$dataCurrentArr = $this->get_results($sql);
			if($dataCurrentArr[0]->numcount > 0)

			{
				//$this->setError('ReturnForm Name already exists');
				//   return false; 
			}
           */			
			
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