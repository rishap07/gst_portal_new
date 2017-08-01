<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

final class coupon extends validation {
    
    function __construct() {
        parent::__construct();
    }
    
   
   
  
  
   
	 
	private function getcouponData()
	{
		 $dataArr = array();
		 if(!empty($_GET["id"]) && (!empty($_GET["action"])))
		{
			
		 $dataArr['coupon_uses'] = isset($_POST['coupon_uses']) ? $_POST['coupon_uses'] : '';
		  $dataArr['hidden'] = isset($_POST['coupon_hidden']) ? $_POST['coupon_hidden'] : '';
		  $dataArr['status'] = isset($_POST['coupon_status']) ? $_POST['coupon_status'] : '';
		  
		}
		else
		{
			 $dataArr['name'] = isset($_POST['coupon_name']) ? $_POST['coupon_name'] : '';
		 $dataArr['type'] = isset($_POST['coupon_type']) ? $_POST['coupon_type'] : '';
		 $dataArr['coupon_value'] = isset($_POST['coupon_value']) ? $_POST['coupon_value'] : '';
		 $dataArr['coupon_uses'] = isset($_POST['coupon_uses']) ? $_POST['coupon_uses'] : '';
		  $dataArr['hidden'] = isset($_POST['coupon_hidden']) ? $_POST['coupon_hidden'] : '';
		  $dataArr['status'] = isset($_POST['coupon_status']) ? $_POST['coupon_status'] : '';
		  
		}
		
		 return $dataArr;
		 
       
	}
	 public function getCouponDetails( $couponid = '' ) {

        $data = $this->get_row("select * from ".TAB_PREFIX."coupon' where coupon_id = '".$couponid."'");
        $dataArr = array();
        if (!empty($data)) {
            $dataArr['data'] = $data;
            $dataArr['message'] = "couponexist";
            $dataArr['status'] = 'success';
        } else {
            $dataArr['data'] = '';
            $dataArr['message'] = $this->validationMessage['couponnotexist'];
            $dataArr['status'] = 'error';
        }
        
        return $dataArr;
    }    
     
    public function updateCoupon()
    {
		$dataArr =array();
		$dataArr = $this->getcouponData();
		if(!empty($_GET["id"]) && (!empty($_GET["action"])))
		{
			$sql="select count(coupon_id) as numcount from ".TAB_PREFIX."coupon WHERE coupon_id=".$_GET["id"]."";
			$dataCurrentArr = $this->get_results($sql);
			
			if($dataCurrentArr[0]->numcount > 0)
			{
				$dataConditionArray['coupon_id'] = $_GET["id"];
				
				if ($this->update(TAB_PREFIX.'coupon', $dataArr, $dataConditionArray)) {

					$this->setSuccess("Your coupon information updated successfully");
					$this->logMsg("User ID : " . $_SESSION['user_detail']['user_id'] . " update the coupon info");
					return true;
				} else {

					$this->setError($this->validationMessage['failed']);
					return false;
				}
				
  			}
			else
			{
				$sql="select count(coupon_id) as numcount from ".TAB_PREFIX."coupon WHERE name='".$dataArr["name"]."'";
				$dataCurrentArr = $this->get_results($sql);
				
				if($dataCurrentArr[0]->numcount > 0)

				{
					$this->setError('Coupon Name already exists');
					   return false; 
				}	
				else
				{
				
					if ($this->insert(TAB_PREFIX.'coupon', $dataArr)) {
						$this->setSuccess('Coupon Saved Successfully');
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
		else
		{
				$sql="select count(coupon_id) as numcount from ".TAB_PREFIX."coupon WHERE name='".$dataArr["name"]."'";
			$dataCurrentArr = $this->get_results($sql);
			
			if($dataCurrentArr[0]->numcount > 0)

			{
				$this->setError('Coupon Name already exists');
				   return false; 
			}	
			else
			{
				if ($this->insert(TAB_PREFIX.'coupon', $dataArr)) {
					$this->setSuccess('Coupon Saved Successfully');
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