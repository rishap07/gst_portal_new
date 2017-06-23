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
    
    public function saveClientKYC() {

        $dataArr['name'] = isset($_POST['name']) ? $_POST['name'] : '';
        $dataArr['email'] = isset($_POST['email']) ? $_POST['email'] : '';
        $dataArr['phone_number'] = isset($_POST['phone_number']) ? $_POST['phone_number'] : '';
        $dataArr['date_of_birth'] = isset($_POST['date_of_birth']) ? $_POST['date_of_birth'] : '';
        $dataArr['gstin_number'] = isset($_POST['gstin_number']) ? $_POST['gstin_number'] : '';
        $dataArr['pan_card_number'] = isset($_POST['pan_card_number']) ? $_POST['pan_card_number'] : '';
        $dataArr['identity_proof'] = isset($_POST['identity_proof']) ? $_POST['identity_proof'] : '';
        $dataArr['business_type'] = isset($_POST['business_type']) ? $_POST['business_type'] : '';
        $dataArr['registered_address'] = isset($_POST['registered_address']) ? $_POST['registered_address'] : '';
        $dataArr['state_id'] = isset($_POST['state']) ? $_POST['state'] : '';

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
		
		$dataArr['registration_type'] = 'gstin';

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
            'email' => 'required||email|#|lable_name:Email',
			'phone_number' => 'required||pattern:/^[' . $this->validateType['mobilenumber'] . ']+$/|#|lable_name:Phone Number',
            'date_of_birth' => 'required||date|#|lable_name:Date of birth',
			'gstin_number' => 'required||pattern:/^' . $this->validateType['gstinnumber'] . '+$/||min:15||max:15|#|lable_name:GSTIN Number',
			'pan_card_number' => 'required||pattern:/^' . $this->validateType['pancard'] . '*$/|#|lable_name:PAN Card',
            'uid_number' => 'pattern:/^[' . $this->validateType['alphanumeric'] . ']+$/|#|lable_name:UID',
            'identity_proof' => 'required||identityproof|#|lable_name:Identity Proof',
            'business_type' => 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '*$/|#|lable_name:Business Type',
            'registered_address' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Permanent Address',
            'state_id' => 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '*$/|#|lable_name:State'
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
        $dataArr['item_unit'] = isset($_POST['item_unit']) ? $_POST['item_unit'] : '';
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
        $dataArr['item_unit'] = isset($_POST['item_unit']) ? $_POST['item_unit'] : '';
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
            'item_unit' => 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '*$/|#|lable_name:Item Unit',
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
        
        /*if($this->checkEmailAddressExist($dataArr['email'])){
            $this->setError($this->validationMessage['emailexist']);
            return false;
        }*/

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

	/* validate client invoice */
	public function validateClientInvoice($dataArr) {

        $rules = array('is_canceled' => 'required||pattern:/^[' . $this->validateType['onlyzeroone'] . ']*$/|#|lable_name:Canceled Value');
		
		if( array_key_exists("invoice_type", $dataArr) ) {
            $rules['invoice_type'] = 'required||invoicetype|#|lable_name:Invoice Type';
        }

		if( array_key_exists("invoice_nature", $dataArr) ) {
            $rules['invoice_nature'] = 'required||invoicenature|#|lable_name:Invoice Nature';
        }
		
		if( array_key_exists("invoice_document_nature", $dataArr) ) {
            $rules['invoice_document_nature'] = 'required||invoicedocumentnature|#|lable_name:Invoice Document Nature';
        }

		if( array_key_exists("invoice_corresponding_type", $dataArr) ) {
            $rules['invoice_corresponding_type'] = 'required||invoiecorresponding|#|lable_name:Invoice Corresponding Type';
        }

		if( array_key_exists("corresponding_invoice_number", $dataArr) ) {
            $rules['corresponding_invoice_number'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Corresponding Invoice Number';
        }

		if( array_key_exists("corresponding_invoice_date", $dataArr) ) {
            $rules['corresponding_invoice_date'] = 'required||date|#|lable_name:Corresponding Invoice Date';
        }

		if( array_key_exists("is_tax_payable", $dataArr) ) {
            $rules['is_tax_payable'] = 'required||pattern:/^[' . $this->validateType['onlyzeroone'] . ']*$/|#|lable_name:Tax Reverse Charge';
        }

		if( array_key_exists("company_name", $dataArr) ) {
            $rules['company_name'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Company Name';
        }

		if( array_key_exists("invoice_date", $dataArr) ) {
            $rules['invoice_date'] = 'required||date|#|lable_name:Invoice Date';
        }

		if( array_key_exists("company_address", $dataArr) ) {
            $rules['company_address'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Company Address';
        }

		if( array_key_exists("company_state", $dataArr) ) {
            $rules['company_state'] = 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '*$/|#|lable_name:Company State';
        }

		if( array_key_exists("gstin_number", $dataArr) ) {
            $rules['gstin_number'] = 'pattern:/^' . $this->validateType['gstinnumber'] . '+$/||min:15||max:15|#|lable_name:Company GSTIN Number';
        }

		if( array_key_exists("supply_place", $dataArr) ) {
            $rules['supply_place'] = 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '*$/|#|lable_name:Place Of Supply';
        }

		if( array_key_exists("billing_name", $dataArr) ) {
            $rules['billing_name'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Billing Name';
        }

		if( array_key_exists("billing_address", $dataArr) ) {
            $rules['billing_address'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Billing Address';
        }

		if( array_key_exists("billing_state", $dataArr) ) {
            $rules['billing_state'] = 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '*$/|#|lable_name:Billing State';
        }

		if( array_key_exists("billing_gstin_number", $dataArr) ) {
			$rules['billing_gstin_number'] = 'pattern:/^' . $this->validateType['gstinnumber'] . '+$/||min:15||max:15|#|lable_name:Billing GSTIN Number';
        }

		if( array_key_exists("shipping_name", $dataArr) ) {
			$rules['shipping_name'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Shipping Name';
        }

		if( array_key_exists("shipping_address", $dataArr) ) {
			$rules['shipping_address'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Shipping Address';
        }

		if( array_key_exists("shipping_state", $dataArr) ) {
			$rules['shipping_state'] = 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '*$/|#|lable_name:Shipping State';
        }

		if( array_key_exists("shipping_gstin_number", $dataArr) ) {
			$rules['shipping_gstin_number'] = 'pattern:/^' . $this->validateType['gstinnumber'] . '+$/||min:15||max:15|#|lable_name:Shipping GSTIN Number';
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
	/* end of validate client invoice */

	/* validate client invoice items */
    public function validateClientInvoiceItem($dataArr, $serialno) {

        $rules = array('invoice_itemid' => 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '*$/|#|lable_name:Invoice Item no. '.$serialno);

		if( array_key_exists("invoice_quantity", $dataArr) ) {
            $rules['invoice_quantity'] = 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '*$/|#|lable_name:Quantity of Item no. '.$serialno;
        }

		if( array_key_exists("invoice_discount", $dataArr) ) {
            $rules['invoice_discount'] = 'numeric|#|lable_name:Discount of Item no. '.$serialno;
        }
		
		if( array_key_exists("invoice_taxablevalue", $dataArr) ) {
            $rules['invoice_taxablevalue'] = 'required||numeric|#|lable_name:Advance Amount of Item no. '.$serialno;
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
	/* end of validate client invoice items */
	
	/* validate client invoice excel file */
    public function validateClientInvoiceExcel($dataArr) {

        $rules = array(
            'invoice_number' => 'required||pattern:/^' . $this->validateType['invoicenumber'] . '+$/|#|lable_name:Invoice Number',
            'is_tax_payable' => 'required||pattern:/^[' . $this->validateType['onlyzeroone'] . ']*$/|#|lable_name:Tax Reverse Charge',
            'invoice_date' => 'required||date|#|lable_name:Invoice Date',
            'transportation_mode' => 'required||pattern:/^[' . $this->validateType['onlyzeroone'] . ']*$/|#|lable_name:Transportation Mode',
            'supply_datetime' => 'required||datetime|#|lable_name:Date Time Of Supply',
            'billing_name' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Billing Name',
            'billing_address' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Billing Address',
            'billing_state' => 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '*$/|#|lable_name:Billing State',
            'billing_gstin_number' => 'pattern:/^' . $this->validateType['gstinnumber'] . '+$/||min:15||max:15|#|lable_name:Billing GSTIN Number',
            'shipping_name' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Shipping Name',
            'shipping_address' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Shipping Address',
            'shipping_state' => 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '*$/|#|lable_name:Shipping State',
            'shipping_gstin_number' => 'pattern:/^' . $this->validateType['gstinnumber'] . '+$/||min:15||max:15|#|lable_name:Shipping GSTIN Number',
            'item_name' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Item Name',
            'item_hsn_code' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Item HSN Code',
            'item_quantity' => 'required||pattern:/^' . $this->validateType['integergreaterzero'] . '*$/|#|lable_name:Item Quantity',
            'item_unit' => 'required||pattern:/^[' . $this->validateType['alphanumeric'] . ']+$/|#|lable_name:Item Unit Code',
            'item_rate' => 'required||decimal|#|lable_name:Item Price',
            'item_discount' => 'numeric|#|lable_name:Item Discount'
        );

        $valid = $this->vali_obj->validate($dataArr, $rules);
        if ($valid->hasErrors()) {
            cms_validate::$errors = array();
            $err_arr = $valid->allErrors();
            $valid->clearMessages();
            return $err_arr;
        }
        return true;
    }
    
    /* upload client invoice */
    public function uploadClientInvoice() {
		
        $flag = true;
        $errorflag = false;
        $counter = 1;
        $dataArray = array();
        $indexArray = array();
        $invoiceArray = array();
        $invoiceItemArray = array();
        
        if( $_FILES['invoice_xlsx']['name'] != '' && $_FILES['invoice_xlsx']['error'] == 0 ) {
            
            $invoice_excel = $this->imageUploads($_FILES['invoice_xlsx'], 'invoice-docs', 'upload', $this->allowExcelExt);
            if ($invoice_excel == FALSE) {
                return false;
            }

            $invoice_excel_dir_path = PROJECT_ROOT . UPLOAD_DIR . "/invoice-docs/" . $invoice_excel;
			$invoice_excel_url_path = PROJECT_URL . UPLOAD_DIR . "/invoice-docs/" . $invoice_excel;
            			
			$objPHPExcel = PHPExcel_IOFactory::load($invoice_excel_dir_path);
            $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

            foreach($sheetData as $data) {

                if($flag) { $indexArray = $data; $flag = false; continue; }
				
				$currentItemError = array();
                $counter++;
				
                $dataArray['invoice_number'] = isset($data['A']) ? $data['A'] : '';
                $is_tax_payable = isset($data['B']) ? $data['B'] : '';
                $dataArray['invoice_date'] = isset($data['C']) ? $data['C'] : '';
                $transportation_mode = isset($data['D']) ? $data['D'] : '';
                $dataArray['supply_datetime'] = isset($data['E']) ? $data['E'] : '';
                $dataArray['billing_name'] = isset($data['F']) ? $data['F'] : '';
                $dataArray['billing_address'] = isset($data['G']) ? $data['G'] : '';
                $billing_state = isset($data['H']) ? $data['H'] : '';
                $dataArray['billing_gstin_number'] = isset($data['I']) ? $data['I'] : '';
                $dataArray['shipping_name'] = isset($data['J']) ? $data['J'] : '';
                $dataArray['shipping_address'] = isset($data['K']) ? $data['K'] : '';
                $shipping_state = isset($data['L']) ? $data['L'] : '';
                $dataArray['shipping_gstin_number'] = isset($data['M']) ? $data['M'] : '';
                $item_name = isset($data['N']) ? $data['N'] : '';
                $item_hsn_code = isset($data['O']) ? $data['O'] : '';
                $dataArray['item_quantity'] = isset($data['P']) ? $data['P'] : '';
                $dataArray['item_unit'] = isset($data['Q']) ? $data['Q'] : '';
                $dataArray['item_rate'] = isset($data['R']) ? $data['R'] : '';
                $dataArray['item_discount'] = isset($data['S']) ? $data['S'] : '';
				
				if($is_tax_payable != '' && strtoupper($is_tax_payable) === 'Y') {
					$dataArray['is_tax_payable'] = 1;
				} else if($is_tax_payable != '' && strtoupper($is_tax_payable) === 'N') {
					$dataArray['is_tax_payable'] = 0;
				} else {
					$dataArray['is_tax_payable'] = $is_tax_payable;
				}

				if($transportation_mode != '' && strtoupper($transportation_mode) === 'Y') {
					$dataArray['transportation_mode'] = 1;
				} else if($transportation_mode != '' && strtoupper($transportation_mode) === 'N') {
					$dataArray['transportation_mode'] = 0;
				} else {
					$dataArray['transportation_mode'] = $transportation_mode;
				}
				
				if($item_name != '' && $item_hsn_code != '') {
					
					$checkClientMasterItem = $this->get_row("select cm.item_id, cm.item_name, cm.unit_price, cm.item_category, m.item_id as category_id, m.item_name as category_name, m.hsn_code, m.igst_tax_rate, m.csgt_tax_rate, m.sgst_tax_rate, m.cess_tax_rate, cm.item_unit, u.unit_id, u.unit_name, u.unit_code from " . $this->tableNames['client_master_item'] . " as cm, " . $this->tableNames['item'] . " as m, " . $this->tableNames['unit'] . " as u where 1=1 AND cm.item_category = m.item_id AND cm.item_unit = u.unit_id AND cm.item_name = '".$item_name."' && m.hsn_code = '".$item_hsn_code."' AND cm.is_deleted='0' AND cm.status = '1' AND cm.added_by = '".$_SESSION['user_detail']['user_id']."'");

					if(count($checkClientMasterItem) == 1) {
						
						$dataArray['item_name'] = $item_name;
						$dataArray['item_hsn_code'] = $item_hsn_code;
					} else {
						
						$errorflag = true;
						array_push($currentItemError, "Item not exist with this hsn code.");
						$dataArray['item_name'] = "#####Item not exist#####";
						$dataArray['item_hsn_code'] = "#####HSN code not exist#####";
					}

				} else {
					$dataArray['item_name'] = $item_name;
					$dataArray['item_hsn_code'] = $item_hsn_code;
				}

				if($billing_state != '') {

					$billing_state_data = $this->getStateDetailByStateCode($billing_state);
					if($billing_state_data['status'] === "success") {
						$dataArray['billing_state'] = $billing_state_data['data']->state_id;
					} else {

						$errorflag = true;
						array_push($currentItemError, "Invalid billing state code.");
						$dataArray['billing_state'] = 'Invalid State';
					}
				} else {
					$dataArray['billing_state'] = $billing_state;
				}
				
				if($shipping_state != '') {

					$shipping_state_data = $this->getStateDetailByStateCode($shipping_state);
					if($shipping_state_data['status'] === "success") {
						$dataArray['shipping_state'] = $shipping_state_data['data']->state_id;
					} else {

						$errorflag = true;
						array_push($currentItemError, "Invalid shipping state code.");
						$dataArray['shipping_state'] = 'Invalid State';
					}
				} else {
					$dataArray['shipping_state'] = $shipping_state;
				}
				
				$invoiceErrors = $this->validateClientInvoiceExcel($dataArray);
                if($invoiceErrors !== true || !empty($currentItemError)) {

					$errorflag = true;
					if($invoiceErrors === true) { $invoiceErrors = array(); }
					$invoiceErrors = array_merge($invoiceErrors, $currentItemError);
                    $invoiceErrors = implode(", ", $invoiceErrors);
                    $objPHPExcel->getActiveSheet()->SetCellValue('T' . $counter, $invoiceErrors);
                }

				/* get current user data */
				$dataCurrentUserArr = $this->getUserDetailsById( $this->sanitize($_SESSION['user_detail']['user_id']) );
				
				/* create invoice array */
				$arrayKey = $dataArray['invoice_number'];				
				$invoiceArray[$arrayKey]['company_name'] = $dataCurrentUserArr['data']->company_name;
				$invoiceArray[$arrayKey]['gstin_number'] = $dataCurrentUserArr['data']->kyc->gstin_number;
				$invoiceArray[$arrayKey]['supply_place'] = $dataCurrentUserArr['data']->kyc->state_id;
                $invoiceArray[$arrayKey]['is_tax_payable'] = $dataArray['is_tax_payable'];
                $invoiceArray[$arrayKey]['invoice_date'] = $dataArray['invoice_date'];
                $invoiceArray[$arrayKey]['transportation_mode'] = $dataArray['transportation_mode'];
                $invoiceArray[$arrayKey]['supply_datetime'] = $dataArray['supply_datetime'];
                $invoiceArray[$arrayKey]['billing_name'] = $dataArray['billing_name'];
                $invoiceArray[$arrayKey]['billing_address'] = $dataArray['billing_address'];
                $invoiceArray[$arrayKey]['billing_state'] = $dataArray['billing_state'];
                $invoiceArray[$arrayKey]['billing_gstin_number'] = $dataArray['billing_gstin_number'];
                $invoiceArray[$arrayKey]['shipping_name'] = $dataArray['shipping_name'];
                $invoiceArray[$arrayKey]['shipping_address'] = $dataArray['shipping_address'];
                $invoiceArray[$arrayKey]['shipping_state'] = $dataArray['shipping_state'];
                $invoiceArray[$arrayKey]['shipping_gstin_number'] = $dataArray['shipping_gstin_number'];

                //items
                $invoiceItemArray['item_name'] = $dataArray['item_name'];
                $invoiceItemArray['item_hsncode'] = $dataArray['item_hsn_code'];
                $invoiceItemArray['item_quantity'] = $dataArray['item_quantity'];
                $invoiceItemArray['item_unit'] = $dataArray['item_unit'];
                $invoiceItemArray['item_unit_price'] = $dataArray['item_rate'];
                $invoiceItemArray['item_discount'] = $dataArray['item_discount'];

                $invoiceArray[$arrayKey]['items'][] = $invoiceItemArray;
            }
			
            if($errorflag === true) {

				$objPHPExcel->getActiveSheet()->SetCellValue('T1', "Error Information");
				$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
				$objWriter->save($invoice_excel_dir_path);
				$this->setError($this->validationMessage['excelerror']);
				
				$resultArray = array("status" => "error", "excelurl" => $invoice_excel_url_path);
				return json_encode($resultArray);
            } else {

				foreach($invoiceArray as $invoiceRow) {
					
					$invoiceItemArray = array();
					$invoiceTotalAmount = 0.00;

					foreach($invoiceRow['items'] as $invoiceInnerRow) {

						$clientMasterItem = $this->get_row("select cm.item_id, cm.item_name, cm.unit_price, cm.item_category, m.item_id as category_id, m.item_name as category_name, m.hsn_code, m.igst_tax_rate, m.csgt_tax_rate, m.sgst_tax_rate, m.cess_tax_rate, cm.item_unit, u.unit_id, u.unit_name, u.unit_code from " . $this->tableNames['client_master_item'] . " as cm, " . $this->tableNames['item'] . " as m, " . $this->tableNames['unit'] . " as u where 1=1 AND cm.item_category = m.item_id AND cm.item_unit = u.unit_id AND cm.item_name = '".$invoiceInnerRow['item_name']."' && m.hsn_code = '".$invoiceInnerRow['item_hsncode']."' AND cm.is_deleted='0' AND cm.status = '1' AND cm.added_by = '".$_SESSION['user_detail']['user_id']."'");
						if (!empty($clientMasterItem)) {

							$itemUnitPrice = (float)$invoiceInnerRow['item_unit_price'];
							$invoiceItemQuantity = (int)$invoiceInnerRow['item_quantity'];
							$invoiceItemDiscount = (float)$invoiceInnerRow['item_discount'];
							
							$invoiceItemTotal = round(($invoiceItemQuantity * $itemUnitPrice), 2);
							$invoiceItemDiscountAmount = ($invoiceItemDiscount/100) * $invoiceItemTotal;
							$invoiceItemTaxableAmount = round(($invoiceItemTotal - $invoiceItemDiscountAmount), 2);

							if($invoiceRow['supply_place'] === $invoiceRow['shipping_state']) {

								$itemCSGTTax = (float)$clientMasterItem->csgt_tax_rate;
								$itemSGSTTax = (float)$clientMasterItem->sgst_tax_rate;
								$itemIGSTTax = 0.00;
								
								$invoiceItemCSGTTaxAmount = round((($itemCSGTTax/100) * $invoiceItemTaxableAmount), 2);
								$invoiceItemSGSTTaxAmount = round((($itemSGSTTax/100) * $invoiceItemTaxableAmount), 2);
								$invoiceItemIGSTTaxAmount = 0.00;
							} else {
								
								$itemCSGTTax = 0.00;
								$itemSGSTTax = 0.00;
								$itemIGSTTax = (float)$clientMasterItem->igst_tax_rate;

								$invoiceItemCSGTTaxAmount = 0.00;
								$invoiceItemSGSTTaxAmount = 0.00;
								$invoiceItemIGSTTaxAmount = round((($itemIGSTTax/100) * $invoiceItemTaxableAmount), 2);
							}

							$invoiceItemTotalAmount = round(($invoiceItemTaxableAmount + $invoiceItemCSGTTaxAmount + $invoiceItemSGSTTaxAmount + $invoiceItemIGSTTaxAmount), 2);
							$invoiceTotalAmount += $invoiceItemTotalAmount;

							$ItemArray = array(
											"item_id" => $clientMasterItem->item_id,
											"item_name" => $clientMasterItem->item_name,
											"item_hsncode" => $clientMasterItem->hsn_code,
											"item_quantity" => $invoiceItemQuantity,
											"item_unit" => $clientMasterItem->unit_code,
											"item_unit_price" => $itemUnitPrice,
											"subtotal" => $invoiceItemTotal,
											"discount" => $invoiceItemDiscount,
											"taxable_subtotal" => $invoiceItemTaxableAmount,
											"cgst_rate" => $itemCSGTTax,
											"cgst_amount" => $invoiceItemCSGTTaxAmount,
											"sgst_rate" => $itemSGSTTax,
											"sgst_amount" => $invoiceItemSGSTTaxAmount,
											"igst_rate" => $itemIGSTTax,
											"igst_amount" => $invoiceItemIGSTTaxAmount,
											"total" => $invoiceItemTotalAmount,
											"status" => 1,
											"added_by" => $_SESSION['user_detail']['user_id'],
											"added_date" => date('Y-m-d H:i:s')
										);
							
							array_push($invoiceItemArray,$ItemArray);
						}
					}
					
					if( !empty($invoiceItemArray) && count($invoiceItemArray) > 0 ) {

						$InsertArray['serial_number'] = $this->generateInvoiceNumber( $this->sanitize($_SESSION['user_detail']['user_id']) );
						$InsertArray['company_name'] = $invoiceRow['company_name'];
						$InsertArray['gstin_number'] = $invoiceRow['gstin_number'];
						$InsertArray['is_tax_payable'] = $invoiceRow['is_tax_payable'];
						$InsertArray['invoice_date'] = $invoiceRow['invoice_date'];
						$InsertArray['transportation_mode'] = $invoiceRow['transportation_mode'];
						$InsertArray['supply_datetime'] = $invoiceRow['supply_datetime'];
						$InsertArray['supply_place'] = $invoiceRow['supply_place'];
						$InsertArray['billing_name'] = $invoiceRow['billing_name'];
						$InsertArray['billing_address'] = $invoiceRow['billing_address'];
						$InsertArray['billing_state'] = $invoiceRow['billing_state'];
						$InsertArray['billing_gstin_number'] = $invoiceRow['billing_gstin_number'];
						$InsertArray['shipping_name'] = $invoiceRow['shipping_name'];
						$InsertArray['shipping_address'] = $invoiceRow['shipping_address'];
						$InsertArray['shipping_state'] = $invoiceRow['shipping_state'];
						$InsertArray['shipping_gstin_number'] = $invoiceRow['shipping_gstin_number'];
						$InsertArray['invoice_total_value'] = $invoiceTotalAmount;
						$InsertArray['status'] = 1;
						$InsertArray['added_by'] = $_SESSION['user_detail']['user_id'];
						$InsertArray['added_date'] = date('Y-m-d H:i:s');

						if ($this->insert($this->tableNames['client_invoice'], $InsertArray)) {

							$insertid = $this->getInsertID();
							$this->logMsg("New Invoice Added. ID : " . $insertid . ".");

							$processedInvoiceItemArray = array();
							foreach($invoiceItemArray as $itemArr) {

								$itemArr['invoice_id'] = $insertid;
								array_push($processedInvoiceItemArray, $itemArr);
							}

							if ($this->insertMultiple($this->tableNames['client_invoice_item'], $processedInvoiceItemArray)) {

								$iteminsertid = $this->getInsertID();
								$this->logMsg("New Invoice Item Added. ID : " . $iteminsertid . ".");
							}
						}
					}
				}

				$this->setSuccess($this->validationMessage['invoiceadded']);
				return true;
			}

            /*$invoicefile = fopen($_FILES['invoice_xlsx']['tmp_name'], "r");

            while (($invoiceData = fgetcsv($invoicefile, 10000, ";")) !== FALSE) {
                
                if($flag) { $flag = false; continue; }

                $arrayKey = $invoiceData[0];
                $invoiceArray[$arrayKey]['is_tax_payable'] = $invoiceData[1];
                $invoiceArray[$arrayKey]['invoice_date'] = $invoiceData[2];
                $invoiceArray[$arrayKey]['transportation_mode'] = $invoiceData[3];
                $invoiceArray[$arrayKey]['supply_datetime'] = $invoiceData[4];
                $invoiceArray[$arrayKey]['billing_name'] = $invoiceData[5];
                $invoiceArray[$arrayKey]['billing_address'] = $invoiceData[6];
                $invoiceArray[$arrayKey]['billing_state'] = $invoiceData[7];
                $invoiceArray[$arrayKey]['billing_gstin_number'] = $invoiceData[8];
                $invoiceArray[$arrayKey]['shipping_name'] = $invoiceData[9];
                $invoiceArray[$arrayKey]['shipping_address'] = $invoiceData[10];
                $invoiceArray[$arrayKey]['shipping_state'] = $invoiceData[11];
                $invoiceArray[$arrayKey]['shipping_gstin_number'] = $invoiceData[12];
                
                //items
                $invoiceItemArray['item_name'] = $invoiceData[13];
                $invoiceItemArray['item_hsncode'] = $invoiceData[14];
                $invoiceItemArray['item_quantity'] = $invoiceData[15];
                $invoiceItemArray['item_unit'] = $invoiceData[16];
                $invoiceItemArray['item_unit_price'] = $invoiceData[17];
                $invoiceItemArray['discount'] = $invoiceData[18];

                $invoiceArray[$arrayKey]['items'][] = $invoiceItemArray;
            }
            
            fclose($invoicefile);
            
            $this->pr($invoiceArray);
            die;*/
            
        }
    }
}