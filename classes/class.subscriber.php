<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

final class subscriber extends validation {
    
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
  
  
   
	 public function validateSub($dataArr) {
      $rules[]='';
		   if (isset($_POST['password']) && $_POST['password'] != '') {
	
        $rules = array(
          
            'first_name' => 'required||pattern:/^' . $dataArr['first_name'] . '+$/|#|lable_name:First Name',
            'last_name' => 'required||pattern:/^' . $dataArr['last_name'] . '+$/|#|lable_name:Last Name',
            'company_name' => 'pattern:/^[' . $dataArr['company_name'] . ']+$/|#|lable_name:Company Name',
            'email' => 'required||email|#|lable_name:Email',
          
            'password' => 'required||pattern:/^[' . $dataArr['password']. ']+$/||min:8||max:20|#|lable_name:Password',
			 'phone_number' => 'required||pattern:/^' . $dataArr['phone_number'] . '+$/|#|lable_name:Mobile Number'
        );
	
		   }
		   else
		   {
			      $rules = array(
          
            'first_name' => 'required||pattern:/^' . $dataArr['first_name'] . '+$/|#|lable_name:First Name',
            'last_name' => 'required||pattern:/^' . $dataArr['last_name'] . '+$/|#|lable_name:Last Name',
            'company_name' => 'pattern:/^[' . $dataArr['company_name'] . ']+$/|#|lable_name:Company Name',
            'email' => 'required||email|#|lable_name:Email',
			 'phone_number' => 'required||pattern:/^' . $dataArr['phone_number'] . '+$/|#|lable_name:Mobile Number'
          
            
        );
		   }
	
		
         $valid = $this->vali_obj->validate($dataArr, $rules);
        if ($valid->hasErrors()) {
            $err_arr = $valid->allErrors();
            $this->setError($err_arr);
            $valid->clearMessages();
            return false;
        }
        return true;
    }
	public function getUserProfilePics($userid)
	{
		$sql="select profile_pics from ".TAB_PREFIX."user WHERE (user_group='3' or user_group='4') and user_id='".$_SESSION["user_detail"]["user_id"]."'";
		$dataCurrentArr = $this->get_results($sql);
		if(!empty($dataCurrentArr))
		{
			
		}
			
	}
    public function updateSubsriber()
    {
		
		$dataArr['first_name'] = isset($_POST['first_name']) ? $_POST['first_name'] : '';
		$dataArr['last_name'] = isset($_POST['last_name']) ? $_POST['last_name'] : '';
		$dataArr['company_name'] = isset($_POST['company_name']) ? $_POST['company_name'] : '';
		$dataArr['phone_number'] = isset($_POST['phonenumber']) ? $_POST['phonenumber'] : '';
		$dataArr['email'] = isset($_POST['emailaddress']) ? $_POST['emailaddress'] : '';
		$mobile_flag=0;
		$email_flag=0;
		if(!empty($_POST['phonenumber']))
		{
			$sql="select * from ".TAB_PREFIX."user WHERE (user_group='3' or user_group='4') and user_id='".$_SESSION["user_detail"]["user_id"]."'";
			$dataCurrentArr = $this->get_results($sql);
			
			if($dataCurrentArr[0]->phone_number == $_POST['phonenumber'])
			{
				$mobile_flag=0;
  			}
			else
			{
				$mobile_flag=1;
			}
        }
		if(!empty($_POST['emailaddress']))
		{
			$sql="select * from ".TAB_PREFIX."user WHERE (user_group='3' or user_group='4') and user_id='".$_SESSION["user_detail"]["user_id"]."'";
			$dataCurrentArr = $this->get_results($sql);
			if($dataCurrentArr[0]->email == $_POST['emailaddress'])
			{
				$email_flag=0;
  			}
			else
			{
				$email_flag=1;
			}
        }
		 if ($_FILES['profile_pics']['name'] != '') {

            $profile_pics = $this->imageUploads($_FILES['profile_pics'], 'profile-picture', 'upload', $this->allowImageExt, 1048576, 'Max file Size 1 MB');
            if ($profile_pics == FALSE) {
                return false;
            } else {
                $dataArr['profile_pics'] = $profile_pics;
            }
        }

		if (!empty($_POST['password'])) {
            $dataArr['password'] = isset($_POST['password']) ? $_POST['password'] : '';
        }
	     if (!$this->validateSub($dataArr)) {
            return false;
        }
	   if(!empty($_POST['password']) || (!empty($_POST['confirmpassword'])))
	   {
	   if ($dataArr['password'] != $_POST['confirmpassword']) {
            $this->setError($this->validationMessage['passwordnotmatched']);
            return false;
        }
	   }    

        
        if (empty($dataArr)) {
            $this->setError($this->validationMessage['mandatory']);
            return false;
        }
       
        if(isset($_POST['password']) && (isset($dataArr['password'])))
		{
			$dataArr['password'] = md5($dataArr['password']);
		}
       
        $dataArr['updated_by'] = $_SESSION['user_detail']['user_id'];
        $dataArr['updated_date'] = date('Y-m-d H:i:s');
		if($mobile_flag==0)
		{
		}
		else
		{
		 $dataArr['mobileno_verify'] = 0;
		}
		if($email_flag==0)
		{
			
		}
		else
		{
			$dataArr['email_verify'] = 0;
		
		}
      
        $dataConditionArray['user_id'] = $_SESSION['user_detail']['user_id'];
        if ($this->update($this->tableNames['user'], $dataArr, $dataConditionArray)) {
			
			if(!empty($dataArr['profile_pics']))
			{
			$_SESSION["user_detail"]["profile_picture"] = $dataArr['profile_pics'];
			}
			else{
				$sql="select * from ".TAB_PREFIX."user WHERE (user_group='3' or user_group='4') and user_id='".$_SESSION["user_detail"]["user_id"]."'";
			$dataCurrentArr = $this->get_results($sql);
			$_SESSION["user_detail"]["profile_picture"] = $dataCurrentArr[0]->profile_pics;
			}
            $this->setSuccess("Your profile update successfully");
            $this->logMsg("User Profile ID : " . $_SESSION['user_detail']['user_id'] . " has been updated","subscriber_update");
            return true;
        } else {

            $this->setError($this->validationMessage['failed']);
            return false;
        }

        return true;
	
   }
    
}