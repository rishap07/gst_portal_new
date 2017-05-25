<?php

/*
 * 
 *  Developed By        :   Rishap Gandhi
 *  Date Created        :   Sep 24, 2016
 *  Last Modified       :   Sep 24, 2016
 *  Last Modified By    :   Rishap Gandhi
 *  Last Modification   :   class for Gallery 
 * 
 */

class users extends validation {
    
    public function __construct() {
        parent::__construct();
    }
    
    /*public function checkCurrentUser() {
        
        $dataArr = $this->getUserDetailsById( $_SESSION['user_detail']['user_id'] );
        $this->pr( $dataArr );
        die;
    }*/
    
    public function addAdminUser() {
        
        $dataArr['first_name'] = isset($_POST['first_name']) ? $_POST['first_name'] : '';
        $dataArr['last_name'] = isset($_POST['last_name']) ? $_POST['last_name'] : '';
        $dataArr['username'] = isset($_POST['username']) ? $_POST['username'] : '';
        $dataArr['password'] = isset($_POST['password']) ? $_POST['password'] : '';
        $dataArr['email'] = isset($_POST['emailaddress']) ? $_POST['emailaddress'] : '';
        $dataArr['company_name'] = isset($_POST['company_name']) ? $_POST['company_name'] : '';
        $dataArr['company_code'] = isset($_POST['company_code']) ? $_POST['company_code'] : '';        
        $dataArr['phone_number'] = isset($_POST['phonenumber']) ? $_POST['phonenumber'] : '';
        $dataArr['status'] = isset($_POST['user_status']) ? $_POST['user_status'] : '';
        $dataArr['no_of_client'] = isset($_POST['no_of_client']) ? $_POST['no_of_client'] : '';
        $dataArr['payment_status'] = isset($_POST['payment_status']) ? $_POST['payment_status'] : '';
        
        if (empty($dataArr)) {
            $this->setError($this->validationMessage['mandatory']);
            return false;
        }

        if(!$this->validateAdminUser($dataArr)){
            return false;
        }
        
        if($this->checkUsernameExist($dataArr['username'])){
            $this->setError($this->validationMessage['usernameexist']);
            return false;
        }
        
        if($this->checkEmailAddressExist($dataArr['email'])){
            $this->setError($this->validationMessage['emailexist']);
            return false;
        }
        
        if($this->checkCompanyCodeExist($dataArr['company_code'])){
            $this->setError($this->validationMessage['companycodeexist']);
            return false;
        }
        
        $dataArr['password'] = $this->password_encrypt($dataArr['password']); /* encrypt password */
        $dataArr['user_group'] = 2;
        $dataArr['added_by'] = $_SESSION['user_detail']['user_id'];
        $dataArr['added_date'] = date('Y-m-d H:i:s');
        
        if ($this->insert($this->tableNames['user'], $dataArr)) {
            
            $this->setSuccess($this->validationMessage['useradded']);
            $insertid = $this->getInsertID();
            $this->logMsg("New User Added. ID : " . $insertid . ".");
            return true;
        } else {
            
            $this->setError($this->validationMessage['failed']);
            return false;
        }
        
        return true;
    }
    
    public function validateAdminUser($dataArr) {
        
        $rules = array(
            'first_name' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:First Name',
            'last_name' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Last Name',
            'email' => 'required||email|#|lable_name:Email',
            'company_name' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Company Name',
            'company_code' => 'required||pattern:/^[' . $this->validateType['alphanumeric'] . ']+$/|#|lable_name:Company Code',
            'phone_number' => 'required||pattern:/^[' . $this->validateType['mobilenumber'] . ']+$/|#|lable_name:Phone Number',
            'status' => 'required||pattern:/^[' . $this->validateType['onlyzeroone'] . ']*$/|#|lable_name:Status',
            'no_of_client' => 'required||numeric|#|lable_name:No Of Client',
            'payment_status' => 'required||numeric|#|lable_name:Payment Status'
        );

        if( array_key_exists("username",$dataArr) ) {
            $rules['username'] = 'required||pattern:/^' . $this->validateType['username'] . '+$/|#|lable_name:Username';
        }

        if( array_key_exists("password",$dataArr) ) {
            $rules['password'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/||min:8||max:20|#|lable_name:Password';
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
    
    public function updateAdminUser() {
        
        $dataArr['first_name'] = isset($_POST['first_name']) ? $_POST['first_name'] : '';
        $dataArr['last_name'] = isset($_POST['last_name']) ? $_POST['last_name'] : '';
        
        if(isset($_POST['password']) && $_POST['password'] != '') { $dataArr['password'] = isset($_POST['password']) ? $_POST['password'] : ''; }
        
        $dataArr['email'] = isset($_POST['emailaddress']) ? $_POST['emailaddress'] : '';
        $dataArr['company_name'] = isset($_POST['company_name']) ? $_POST['company_name'] : '';
        $dataArr['company_code'] = isset($_POST['company_code']) ? $_POST['company_code'] : '';        
        $dataArr['phone_number'] = isset($_POST['phonenumber']) ? $_POST['phonenumber'] : '';
        $dataArr['status'] = isset($_POST['user_status']) ? $_POST['user_status'] : '';
        $dataArr['no_of_client'] = isset($_POST['no_of_client']) ? $_POST['no_of_client'] : '';
        $dataArr['payment_status'] = isset($_POST['payment_status']) ? $_POST['payment_status'] : '';
        
        if (empty($dataArr)) {
            $this->setError($this->validationMessage['mandatory']);
            return false;
        }

        if(!$this->validateAdminUser($dataArr)){
            return false;
        }
        
        if($this->checkEmailAddressExist($dataArr['email'], $this->sanitize($_GET['id']))){
            $this->setError($this->validationMessage['emailexist']);
            return false;
        }
        
        if($this->checkCompanyCodeExist($dataArr['company_code'], $this->sanitize($_GET['id']))){
            $this->setError($this->validationMessage['companycodeexist']);
            return false;
        }
        
        $dataArr['password'] = $this->password_encrypt($dataArr['password']); /* encrypt password */
        $dataArr['user_group'] = 2;
        $dataArr['updated_by'] = $_SESSION['user_detail']['user_id'];
        $dataArr['updated_date'] = date('Y-m-d H:i:s');

        $dataConditionArray['user_id'] = $this->sanitize($_GET['id']);
        if ($this->update($this->tableNames['user'], $dataArr, $dataConditionArray)) {
            
            $this->setSuccess($this->validationMessage['useredited']);
            $this->logMsg("User ID : " . $_GET['id'] . " in User has been updated");
            return true;            
        } else {
            
            $this->setError($this->validationMessage['failed']);
            return false;
        }
        
        return true;
    }
        
    public function addSubadminUser() {
        
        $dataArr['first_name'] = isset($_POST['first_name']) ? $_POST['first_name'] : '';
        $dataArr['last_name'] = isset($_POST['last_name']) ? $_POST['last_name'] : '';
        $dataArr['phone_number'] = isset($_POST['phonenumber']) ? $_POST['phonenumber'] : '';
        $dataArr['username'] = isset($_POST['username']) ? $_POST['username'] : '';
        $dataArr['password'] = isset($_POST['password']) ? $_POST['password'] : '';
        $dataArr['email'] = isset($_POST['emailaddress']) ? $_POST['emailaddress'] : '';        
        $dataArr['status'] = isset($_POST['user_status']) ? $_POST['user_status'] : '';
        
        if (empty($dataArr)) {
            $this->setError($this->validationMessage['mandatory']);
            return false;
        }

        if(!$this->validateSubadminUser($dataArr)){
            return false;
        }
        $dataArr['username'] = $_SESSION['user_detail']['username']."_".$dataArr['username'];
        if($this->checkUsernameExist($dataArr['username'])){
            $this->setError($this->validationMessage['usernameexist']);
            return false;
        }
        
//        if($this->checkEmailAddressExist($dataArr['email'])){
//            $this->setError($this->validationMessage['emailexist']);
//            return false;
//        }       
        
        $dataArr['password'] = $this->password_encrypt($dataArr['password']); /* encrypt password */
        $dataArr['user_group'] = 3;
        $dataArr['user_parent'] = $_SESSION['user_detail']['user_id'];
        $dataArr['added_by'] = $_SESSION['user_detail']['user_id'];
        $dataArr['added_date'] = date('Y-m-d H:i:s');
        
        if ($this->insert($this->tableNames['user'], $dataArr)) {
            
            $this->setSuccess($this->validationMessage['useradded']);
            $insertid = $this->getInsertID();
            $this->logMsg("New User Added. ID : " . $insertid . ".");
            return true;
        } else {
            
            $this->setError($this->validationMessage['failed']);
            return false;
        }
        
        return true;
    }
    
    public function updateSubadminUser() {
        
        $dataArr['first_name'] = isset($_POST['first_name']) ? $_POST['first_name'] : '';
        $dataArr['last_name'] = isset($_POST['last_name']) ? $_POST['last_name'] : '';
        $dataArr['phone_number'] = isset($_POST['phonenumber']) ? $_POST['phonenumber'] : '';
        
        if(isset($_POST['password']) && $_POST['password'] != '') { $dataArr['password'] = isset($_POST['password']) ? $_POST['password'] : ''; }
        
        $dataArr['email'] = isset($_POST['emailaddress']) ? $_POST['emailaddress'] : '';
        $dataArr['status'] = isset($_POST['user_status']) ? $_POST['user_status'] : '';
        
        if (empty($dataArr)) {
            $this->setError($this->validationMessage['mandatory']);
            return false;
        }

        if(!$this->validateSubadminUser($dataArr)){
            return false;
        }
        
        $dataArr['password'] = $this->password_encrypt($dataArr['password']); /* encrypt password */
        $dataArr['user_group'] = 3;
        $dataArr['user_parent'] = $_SESSION['user_detail']['user_id'];
        $dataArr['updated_by'] = $_SESSION['user_detail']['user_id'];
        $dataArr['updated_date'] = date('Y-m-d H:i:s');

        $dataConditionArray['user_id'] = $this->sanitize($_GET['id']);
        if ($this->update($this->tableNames['user'], $dataArr, $dataConditionArray)) {
            
            $this->setSuccess($this->validationMessage['useredited']);
            $this->logMsg("User ID : " . $_GET['id'] . " in User has been updated");
            return true;            
        } else {
            
            $this->setError($this->validationMessage['failed']);
            return false;
        }
        
        return true;
    }
    
    public function validateSubadminUser($dataArr) {
        
        $rules = array(
            'first_name' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:First Name',
            'last_name' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Last Name',
            'phone_number' => 'required||pattern:/^[' . $this->validateType['mobilenumber'] . ']+$/|#|lable_name:Phone Number',
            'email' => 'required||email|#|lable_name:Email',
            'status' => 'required||pattern:/^[' . $this->validateType['onlyzeroone'] . ']*$/|#|lable_name:Status'
        );

        if( array_key_exists("username",$dataArr) ) {
            $rules['username'] = 'required||pattern:/^' . $this->validateType['username'] . '+$/|#|lable_name:Username';
        }

        if( array_key_exists("password",$dataArr) ) {
            $rules['password'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/||min:8||max:20|#|lable_name:Password';
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

    public function deleteUser($userid = '') {
        
        $dataConditionArray['user_id'] = $userid;
        $dataUpdateArray['is_deleted'] = "1";
        $dataUpdateArray['deleted_by'] = $_SESSION['user_detail']['user_id'];
        $dataUpdateArray['deleted_date'] = date('Y-m-d H:i:s');
        
        if ($this->update($this->tableNames['user'], $dataUpdateArray, $dataConditionArray)) {
            
            $this->setSuccess($this->validationMessage['userdeleted']);
            $this->logMsg("User ID : " . $userid . " in User has been deleted");
            return true;
        } else {
            
            $this->setError($this->validationMessage['failed']);
            return false;
        }
        
        return true;
    }

    public function changePassword()
    {
        $dataArr= $this->getPassParams();
        if(empty($dataArr))
        {
            $this->setError("Fill all fields");
            return false;
        }
        
        $dataRes = $this->findAll("cms_user","user_id='".$_SESSION['user_detail']['user_id']."'","password");
        if($dataRes[0]->password!=md5($dataArr['old_password']))
        {
            $this->setError("Password not matched");
            return false;
        }
        if($dataArr['new_password']!=$dataArr['confirm_password'])
        {
            $this->setError("New Password not matched");
            return false;
        }
        $data['password'] = md5($dataArr['new_password']);
        if($this->update("cms_user",$data,array("user_id"=>$_SESSION['user_detail']['user_id'])))
        {
             $this->logMsg("Password Updated : User ID : ".$_SESSION['user_detail']['user_id'], "User Management", $_SESSION['user_detail']['user_id']);
            $this->setSuccess("Password updated");
            $this->redirect(ADMIN_URL."/changepassword.php");
            exit();
        }
        $this->setError("Error try again to change the password");
        $this->redirect(ADMIN_URL."/changepassword.php");
        exit();
    }
    
    public function getPassParams()
    {
        $dataArr = array();
        if (isset($_POST['submit']) && $_POST['submit']=='Submit') 
        {
            $dataArr['old_password'] = $this->sanitize($_POST['exisiting_password']);
            $dataArr['new_password'] = $this->sanitize($_POST['new_password']);
            $dataArr['confirm_password'] = $this->sanitize($_POST['confirm_password']);
        }
        return $dataArr;
    }

}
