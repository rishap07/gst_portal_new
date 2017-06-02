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

final class client extends validation {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function saveClientGSTN() {
        
        $dataArr['gstn_number'] = isset($_POST['gstn_number']) ? $_POST['gstn_number'] : '';
        $dataArr['gstn_issue_date'] = isset($_POST['gstn_issue_date']) ? $_POST['gstn_issue_date'] : '';

        if (empty($dataArr)) {
            $this->setError($this->validationMessage['mandatory']);
            return false;
        }
        
        if(!$this->validateGSTNNumber($dataArr)){
            return false;
        }
        
        if( $this->checkGSTNNumberExist($dataArr['gstn_number'], $_SESSION['user_detail']['user_id']) ) {
            $this->setError($this->validationMessage['gstnexist']);
            return false;
        }
        
        $dataCurrentArr = $this->getUserDetailsById( $this->sanitize($_SESSION['user_detail']['user_id']) );
        if($dataCurrentArr['data']->gstn != '') {
            
            $dataArr['updated_by'] = $_SESSION['user_detail']['user_id'];
            $dataArr['updated_date'] = date('Y-m-d H:i:s');

            $dataConditionArray['added_by'] = $this->sanitize($_SESSION['user_detail']['user_id']);
            if ($this->update($this->tableNames['client_gstn_detail'], $dataArr, $dataConditionArray)) {

                $this->setSuccess($this->validationMessage['gstnupdated']);
                $this->logMsg("User GSTN ID : " . $_SESSION['user_detail']['user_id'] . " has been updated.");
                return true;            
            } else {

                $this->setError($this->validationMessage['failed']);
                return false;
            }
        } else {
            
            $dataArr['added_by'] = $_SESSION['user_detail']['user_id'];
            $dataArr['added_date'] = date('Y-m-d H:i:s');

            if ($this->insert($this->tableNames['client_gstn_detail'], $dataArr)) {

                $this->setSuccess($this->validationMessage['gstnupdated']);
                $insertid = $this->getInsertID();
                $this->logMsg("User GSTN Added. ID : " . $insertid . ".");
                return true;
            } else {
                $this->setError($this->validationMessage['failed']);
                return false;
            }
        }
    }
    
    public function validateGSTNNumber($dataArr) {
        
        $rules = array(
            'gstn_number' => 'required||min:16||max:16|#|lable_name:GSTN Number',
            'gstn_issue_date' => 'required||date|#|lable_name:GSTN Issue Date'
        );

        $valid = $this->vali_obj->validate($dataArr, $rules);
        if ($valid->hasErrors()) {
            $err_arr = $valid->allErrors();
            $this->setError($err_arr);
            $valid->clearMessages();
            return false;
        }
        return true;
    }
    
    public function saveClientKYC() {
        
        $dataArr['name'] = isset($_POST['name']) ? $_POST['name'] : '';
        $dataArr['companion_name'] = isset($_POST['companion_name']) ? $_POST['companion_name'] : '';
        $dataArr['gender'] = isset($_POST['gender']) ? $_POST['gender'] : '';
        $dataArr['martial_status'] = isset($_POST['martial_status']) ? $_POST['martial_status'] : '';
        $dataArr['date_of_birth'] = isset($_POST['date_of_birth']) ? $_POST['date_of_birth'] : '';
        $dataArr['nationality'] = isset($_POST['nationality']) ? $_POST['nationality'] : '';
        $dataArr['status'] = isset($_POST['status']) ? $_POST['status'] : '';        
        $dataArr['pan_card_number'] = isset($_POST['pan_card_number']) ? $_POST['pan_card_number'] : '';
        $dataArr['identity_proof'] = isset($_POST['identity_proof']) ? $_POST['identity_proof'] : '';
        $dataArr['correspondence_address'] = isset($_POST['correspondence_address']) ? $_POST['correspondence_address'] : '';
        $dataArr['correspondence_details'] = isset($_POST['correspondence_details']) ? $_POST['correspondence_details'] : '';
        $dataArr['permanent_address'] = isset($_POST['permanent_address']) ? $_POST['permanent_address'] : '';
        $dataArr['occupation'] = isset($_POST['occupation']) ? $_POST['occupation'] : '';
        
        //$dataArr['proof_photograph'] = isset($_FILES['proof_photograph']['name']) ? $_FILES['proof_photograph']['name'] : '';
        //$dataArr['address_proof'] = isset($_FILES['address_proof']['name']) ? $_FILES['address_proof']['name'] : '';
        
        if (empty($dataArr)) {
            $this->setError($this->validationMessage['mandatory']);
            return false;
        }
        
        $dataArr['uid_number'] = isset($_POST['uid_number']) ? $_POST['uid_number'] : '';
        
        if(!$this->validateClientKYC($dataArr)){
            return false;
        }
        
        if( $_FILES['proof_photograph']['name'] != '' ) {
            
            $proof_photograph = $this->imageUploads($_FILES['proof_photograph'], 'kyc-docs', 'upload', $this->allowImageExt, 1048576, 'Max file Size 1 MB');
            if ($proof_photograph == FALSE) {
                return false;
            } else {
                $dataArr['proof_photograph'] = $proof_photograph;
            }
        }
        
        if( $_FILES['address_proof']['name'] != '' ) {
            
            $address_proof = $this->imageUploads($_FILES['address_proof'], 'kyc-docs', 'upload', $this->allowImageExt, 1048576, 'Max file Size 1 MB');
            if ($address_proof == FALSE) {
                return false;
            } else {
                $dataArr['address_proof'] = $address_proof;
            }
        }
        
        $dataCurrentArr = $this->getUserDetailsById( $this->sanitize($_SESSION['user_detail']['user_id']) );
        if($dataCurrentArr['data']->kyc != '') {

            $dataArr['updated_by'] = $_SESSION['user_detail']['user_id'];
            $dataArr['updated_date'] = date('Y-m-d H:i:s');

            $dataConditionArray['added_by'] = $this->sanitize($_SESSION['user_detail']['user_id']);
            if ($this->update($this->tableNames['client_kyc'], $dataArr, $dataConditionArray)) {

                $this->setSuccess($this->validationMessage['kycupdated']);
                $this->logMsg("User KYC ID : " . $_SESSION['user_detail']['user_id'] . " has been updated.");
                return true;            
            } else {

                $this->setError($this->validationMessage['failed']);
                return false;
            }
        } else {
            
            $dataArr['added_by'] = $_SESSION['user_detail']['user_id'];
            $dataArr['added_date'] = date('Y-m-d H:i:s');

            if ($this->insert($this->tableNames['client_kyc'], $dataArr)) {

                $this->setSuccess($this->validationMessage['kycupdated']);
                $insertid = $this->getInsertID();
                $this->logMsg("User KYC Added. ID : " . $insertid . ".");
                return true;
            } else {
                $this->setError($this->validationMessage['failed']);
                return false;
            }
        }
    }
    
    public function validateClientKYC($dataArr) {
        
        $rules = array(
            'name' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Name',
            'companion_name' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Companion Name',
            'gender' => 'required||gender|#|lable_name:Gender',
            'martial_status' => 'required||martialstatus|#|lable_name:Martial Status',
            'date_of_birth' => 'required|#|lable_name:Date of birth',
            'nationality' => 'required||nationality|#|lable_name:Nationality',
            'status' => 'required||pattern:/^[' . $this->validateType['onlyzeroone'] . ']*$/|#|lable_name:Status',
            'pan_card_number' => 'required||pattern:/^' . $this->validateType['pancard'] . '*$/|#|lable_name:PAN Card',
            'uid_number' => 'pattern:/^[' . $this->validateType['alphanumeric'] . ']+$/|#|lable_name:UID',
            'identity_proof' => 'required||identityproof|#|lable_name:Identity Proof',
            'correspondence_address' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Correspondence Address',
            'correspondence_details' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Correspondence Details',
            'permanent_address' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Permanent Address',
            'occupation' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Occupation'
        );
        
        if( array_key_exists("proof_photograph",$dataArr) ) {
            $rules['proof_photograph'] = 'image|#|lable_name:Proof Photograph';
        }
        
        if( array_key_exists("address_proof",$dataArr) ) {
            $rules['address_proof'] = 'image|#|lable_name:Address Proof';
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
    
    public function addClientItem() {

        $dataArr['item_name'] = isset($_POST['item_name']) ? $_POST['item_name'] : '';
        $dataArr['item_category'] = isset($_POST['item_category']) ? $_POST['item_category'] : '';
        $dataArr['unit_price'] = isset($_POST['unit_price']) ? $_POST['unit_price'] : '';
        $dataArr['status'] = isset($_POST['status']) ? $_POST['status'] : '';
        
        if (empty($dataArr)) {
            $this->setError($this->validationMessage['mandatory']);
            return false;
        }

        if(!$this->validateClientItem($dataArr)){
            return false;
        }

        $dataArr['added_by'] = $_SESSION['user_detail']['user_id'];
        $dataArr['added_date'] = date('Y-m-d H:i:s');
        
        if ($this->insert($this->tableNames['client_master_item'], $dataArr)) {
            
            $this->setSuccess($this->validationMessage['iteminserted']);
            $insertid = $this->getInsertID();
            $this->logMsg("New Item Added. ID : " . $insertid . ".");
            return true;
        } else {
            
            $this->setError($this->validationMessage['failed']);
            return false;
        }
        
        return true;
    }
    
    public function updateClientItem() {
        
        $dataArr['item_name'] = isset($_POST['item_name']) ? $_POST['item_name'] : '';
        $dataArr['item_category'] = isset($_POST['item_category']) ? $_POST['item_category'] : '';
        $dataArr['unit_price'] = isset($_POST['unit_price']) ? $_POST['unit_price'] : '';
        $dataArr['status'] = isset($_POST['status']) ? $_POST['status'] : '';
        
        if (empty($dataArr)) {
            $this->setError($this->validationMessage['mandatory']);
            return false;
        }

        if(!$this->validateClientItem($dataArr)){
            return false;
        }
        
        $dataArr['updated_by'] = $_SESSION['user_detail']['user_id'];
        $dataArr['updated_date'] = date('Y-m-d H:i:s');

        $dataConditionArray['item_id'] = $this->sanitize($_GET['id']);
        if ($this->update($this->tableNames['client_master_item'], $dataArr, $dataConditionArray)) {
            
            $this->setSuccess($this->validationMessage['itemupdated']);
            $this->logMsg("Item ID : " . $_GET['id'] . " has been updated");
            return true;
        } else {
            
            $this->setError($this->validationMessage['failed']);
            return false;
        }
        
        return true;
    }
    
    public function validateClientItem($dataArr) {
        
        $rules = array(
            'item_name' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Item Name',
            'item_category' => 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '*$/|#|lable_name:Item Category',
            'unit_price' => 'required||decimal|#|lable_name:Price',
            'status' => 'required||pattern:/^[' . $this->validateType['onlyzeroone'] . ']*$/|#|lable_name:Status'
        );

        $valid = $this->vali_obj->validate($dataArr, $rules);
        if ($valid->hasErrors()) {
            $err_arr = $valid->allErrors();
            $this->setError($err_arr);
            $valid->clearMessages();
            return false;
        }
        return true;
    }
    
    public function deleteClientItem($itemid = '') {
        
        $dataConditionArray['item_id'] = $itemid;
        $dataUpdateArray['is_deleted'] = "1";
        $dataUpdateArray['deleted_by'] = $_SESSION['user_detail']['user_id'];
        $dataUpdateArray['deleted_date'] = date('Y-m-d H:i:s');
        
        if ($this->update($this->tableNames['client_master_item'], $dataUpdateArray, $dataConditionArray)) {
            
            $this->setSuccess($this->validationMessage['itemdeleted']);
            $this->logMsg("Item ID : " . $itemid . " in Client Master Item has been deleted");
            return true;
        } else {
            
            $this->setError($this->validationMessage['failed']);
            return false;
        }
        
        return true;
    }
    
    public function addClientUser() {

        $dataArr['first_name'] = isset($_POST['first_name']) ? $_POST['first_name'] : '';
        $dataArr['last_name'] = isset($_POST['last_name']) ? $_POST['last_name'] : '';
        $dataArr['company_name'] = isset($_POST['company_name']) ? $_POST['company_name'] : '';
        $dataArr['username'] = isset($_POST['username']) ? $_POST['username'] : '';
        $dataArr['password'] = isset($_POST['password']) ? $_POST['password'] : '';
        $dataArr['email'] = isset($_POST['emailaddress']) ? $_POST['emailaddress'] : '';
        $dataArr['phone_number'] = isset($_POST['phonenumber']) ? $_POST['phonenumber'] : '';
        $dataArr['company_code'] = isset($_POST['company_code']) ? $_POST['company_code'] : '';        
        $dataArr['status'] = isset($_POST['user_status']) ? $_POST['user_status'] : '';
        
        if (empty($dataArr)) {
            $this->setError($this->validationMessage['mandatory']);
            return false;
        }

        if(!$this->validateClientUser($dataArr)){
            return false;
        }
        
        $dataCurrentArr = $this->getUserDetailsById( $this->sanitize($_SESSION['user_detail']['user_id']) );
        
        $dataArr['username'] = $dataCurrentArr['data']->subscriber_code."_".$dataArr['username'];
        if($this->checkUsernameExist($dataArr['username'])){
            $this->setError($this->validationMessage['usernameexist']);
            return false;
        }
        
        if($this->checkEmailAddressExist($dataArr['email'])){
            $this->setError($this->validationMessage['emailexist']);
            return false;
        }

        $dataArr['password'] = $this->password_encrypt($dataArr['password']); /* encrypt password */
        $dataArr['user_group'] = 4;
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
    
    public function updateClientUser() {
        
        $dataArr['first_name'] = isset($_POST['first_name']) ? $_POST['first_name'] : '';
        $dataArr['last_name'] = isset($_POST['last_name']) ? $_POST['last_name'] : '';
        $dataArr['company_name'] = isset($_POST['company_name']) ? $_POST['company_name'] : '';

        if(isset($_POST['password']) && $_POST['password'] != '') { $dataArr['password'] = isset($_POST['password']) ? $_POST['password'] : ''; }

        $dataArr['email'] = isset($_POST['emailaddress']) ? $_POST['emailaddress'] : '';
        $dataArr['phone_number'] = isset($_POST['phonenumber']) ? $_POST['phonenumber'] : '';
        $dataArr['company_code'] = isset($_POST['company_code']) ? $_POST['company_code'] : '';   
        $dataArr['status'] = isset($_POST['user_status']) ? $_POST['user_status'] : '';
        
        if (empty($dataArr)) {
            $this->setError($this->validationMessage['mandatory']);
            return false;
        }

        if(!$this->validateClientUser($dataArr)){
            return false;
        }
        
        if(isset($dataArr['password']) && $dataArr['password'] != '') { $dataArr['password'] = $this->password_encrypt($dataArr['password']); } /* encrypt password */
        $dataArr['updated_by'] = $_SESSION['user_detail']['user_id'];
        $dataArr['updated_date'] = date('Y-m-d H:i:s');

        $dataConditionArray['user_id'] = $this->sanitize($_GET['id']);
        if ($this->update($this->tableNames['user'], $dataArr, $dataConditionArray)) {
            
            $this->setSuccess($this->validationMessage['useredited']);
            $this->logMsg("User ID : " . $_GET['id'] . " has been updated");
            return true;            
        } else {
            
            $this->setError($this->validationMessage['failed']);
            return false;
        }
        
        return true;
    }
    
    public function validateClientUser($dataArr) {
        
        $rules = array(
            'first_name' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:First Name',
            'last_name' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Last Name',
            'company_name' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Company Name',
	    'company_code' => 'required||pattern:/^[' . $this->validateType['alphanumeric'] . ']+$/|#|lable_name:Company Code',
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
    
    public function deleteClientUser($userid = '') {
        
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
}