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

final class users extends validation {

    public function __construct() {
        parent::__construct();
    }
	public function is_positive_integer($str) {
  return (is_numeric($str) && $str > 0 && $str == round($str));
    }
    public function addSubUser() {

        $dataArr['first_name'] = isset($_POST['first_name']) ? $_POST['first_name'] : '';
        $dataArr['last_name'] = isset($_POST['last_name']) ? $_POST['last_name'] : '';
        $dataArr['company_name'] = isset($_POST['company_name']) ? $_POST['company_name'] : '';
        $dataArr['username'] = isset($_POST['username']) ? $_POST['username'] : '';
        $dataArr['password'] = isset($_POST['password']) ? $_POST['password'] : '';
        $dataArr['email'] = isset($_POST['emailaddress']) ? $_POST['emailaddress'] : '';
        $dataArr['phone_number'] = isset($_POST['phonenumber']) ? $_POST['phonenumber'] : '';
        $dataArr['status'] = isset($_POST['user_status']) ? $_POST['user_status'] : '';
		$dataArr['no_of_client'] = isset($_POST['no_of_client']) ? $_POST['no_of_client'] : '';
        $dataArr['subscriber_code'] = $this->generateSubscriberRandomCode(6, $this->tableNames['user'], "subscriber_code");
      
        if (empty($dataArr)) {
            $this->setError($this->validationMessage['mandatory']);
            return false;
        }

        if (!$this->validateSubUser($dataArr)) {
            return false;
        }

        $dataCurrentArr = $this->getUserDetailsById($this->sanitize($_SESSION['user_detail']['user_id']));

        $dataArr['username'] = $dataCurrentArr['data']->subscriber_code . "_" . $dataArr['username'];
        if ($this->checkUsernameExist($dataArr['username'])) {
            $this->setError($this->validationMessage['usernameexist']);
            return false;
        }
        $dataArr['password'] = $this->password_encrypt($dataArr['password']); /* encrypt password */
        $dataArr['user_group'] = 5;
        $dataArr['added_by'] = $_SESSION['user_detail']['user_id'];
        $dataArr['added_date'] = date('Y-m-d H:i:s');

        if ($this->insert($this->tableNames['user'], $dataArr)) {

            $this->setSuccess($this->validationMessage['useradded']);
            $insertid = $this->getInsertID();
            $this->logMsg("New User Added. ID : " . $insertid . ".","addSubuser");
            return true;
        } else {

            $this->setError($this->validationMessage['failed']);
            return false;
        }

        return true;
    }
   
    public function updateSubUser() {

        $dataArr['first_name'] = isset($_POST['first_name']) ? $_POST['first_name'] : '';
        $dataArr['last_name'] = isset($_POST['last_name']) ? $_POST['last_name'] : '';
        $dataArr['company_name'] = isset($_POST['company_name']) ? $_POST['company_name'] : '';

        if (isset($_POST['password']) && $_POST['password'] != '') {
            $dataArr['password'] = isset($_POST['password']) ? $_POST['password'] : '';
        }

        $dataArr['email'] = isset($_POST['emailaddress']) ? $_POST['emailaddress'] : '';
        $dataArr['phone_number'] = isset($_POST['phonenumber']) ? $_POST['phonenumber'] : '';
        $dataArr['status'] = isset($_POST['user_status']) ? $_POST['user_status'] : '';
		$dataArr['no_of_client'] = isset($_POST['no_of_client']) ? $_POST['no_of_client'] : '';
        $dataArr['subscriber_code'] = $this->generateSubscriberRandomCode(6, $this->tableNames['user'], "subscriber_code");
      
        if (empty($dataArr)) {
            $this->setError($this->validationMessage['mandatory']);
            return false;
        }

        if (!$this->validateSubUser($dataArr)) {
            return false;
        }

        if (isset($dataArr['password']) && $dataArr['password'] != '') {
            $dataArr['password'] = $this->password_encrypt($dataArr['password']);
        } /* encrypt password */
        $dataArr['updated_by'] = $_SESSION['user_detail']['user_id'];
        $dataArr['updated_date'] = date('Y-m-d H:i:s');

        $dataConditionArray['user_id'] = $this->sanitize($_GET['id']);
        if ($this->update($this->tableNames['user'], $dataArr, $dataConditionArray)) {

            $this->setSuccess($this->validationMessage['useredited']);
            $this->logMsg("User ID : " . $_GET['id'] . " has been updated","updateSubUser");
            return true;
        } else {

            $this->setError($this->validationMessage['failed']);
            return false;
        }

        return true;
    }

	public function validateSubUser($dataArr) {

        $rules = array(
            'first_name' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:First Name',
            'last_name' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Last Name',
            'company_name' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Company Name',
            'phone_number' => 'required||pattern:/^[' . $this->validateType['mobilenumber'] . ']+$/|#|lable_name:Phone Number',
            'email' => 'required||email|#|lable_name:Email',
            'status' => 'required||pattern:/^[' . $this->validateType['onlyzeroone'] . ']*$/|#|lable_name:Status'
        );

        if (array_key_exists("username", $dataArr)) {
            $rules['username'] = 'required||pattern:/^' . $this->validateType['username'] . '+$/|#|lable_name:Username';
        }

        if (array_key_exists("password", $dataArr)) {
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

    public function addPlanToSubscriber() {

        $dataArr['plan_id'] = isset($_GET['plan_id']) ? $_GET['plan_id'] : '';
        if (empty($dataArr)) {
            $this->setError($this->validationMessage['mandatory']);
            return false;
        }

        /* get plan details */
        $planDetail = $this->getAllActivePlanSuAdmin("p.id,p.name,p.description,p.plan_category,p.no_of_client,p.company_no,p.pan_num,p.sub_user,p.invoice_num,p.support,p.period_of_service,p.web_mobile_app,p.cloud_storage_gb,p.gst_expert_help,p.plan_price,(case when p.status='1' Then 'active' when p.status='0' then 'deactive' end) as status,c.name as cat_name,c.description as cat_description", "p.id='" . $dataArr['plan_id'] . "' and p.is_deleted='0'", $orderby = 'p.id asc');

		$dataArr['no_of_client'] = $planDetail[0]->no_of_client;
		$dataArr['company_no'] = $planDetail[0]->company_no;
		$dataArr['pan_num'] = $planDetail[0]->pan_num;
		$dataArr['sub_user'] = $planDetail[0]->sub_user;
		$dataArr['cloud_storage_gb'] = $planDetail[0]->cloud_storage_gb;
		$dataArr['plan_start_date'] = date('Y-m-d H:i:s');
		$dataArr['plan_due_date'] = (date('Y')+1) . "-03-31 23:59:59";
		$dataArr['plan_price'] = $planDetail[0]->plan_price;
		$dataArr['plan_details'] = json_encode($planDetail);
		$dataArr['status'] = '1';
		$dataArr['payment_method'] = 'banktransfer';
		$dataArr['payment_status'] = '0';
        $dataArr['added_by'] = $_SESSION['user_detail']['user_id'];
        $dataArr['added_date'] = date('Y-m-d H:i:s');
		
        if ($this->insert($this->tableNames['user_subscribed_plan'], $dataArr)) {

            $insertid = $this->getInsertID();
            $this->logMsg("New Plan Subscribed Added. ID : " . $insertid . ".", "user_subscribed_plan");

            $dataConditionArray['user_id'] = $_SESSION['user_detail']['user_id'];
            $dataUpdateArr['payment_status'] = "0";

            if ($this->update($this->tableNames['user'], $dataUpdateArr, $dataConditionArray)) {

                $_SESSION['plan_id'] = $dataArr['plan_id'];
                $_SESSION['subs_id'] = $insertid;

                $this->redirect(PROJECT_URL . '/?page=payment_online');
				exit();
				//$this->setSuccess($this->validationMessage['plansubscribed']);
				//return true;
			} else {
                $this->setError($this->validationMessage['failed']);
                return false;
            }
        } else {
            $this->setError($this->validationMessage['failed']);
            return false;
        }

        return true;
    }

    public function sendsubscribemail($module, $module_message, $to_send, $from_send, $bcc, $subject,$path) {

		$dataInsertArray['module'] = $module;
		$dataInsertArray['module_message'] = $module_message;
		$dataInsertArray['to_send'] = $to_send;
		$dataInsertArray['from_send'] = $from_send;
		$dataInsertArray['bcc'] = $bcc;
		$dataInsertArray['subject'] = $subject;
		$dataInsertArray['status'] = '0';
		$dataInsertArray['attachment'] = $path;

        if ($this->insert($this->tableNames['email'], $dataInsertArray)) {
            return true;
        } else {
            return false;
        }
    }
	
	public function saveUserInvoiceSetting() {

		$dataArr['invoice_label'] = isset($_POST['invoice_label']) ? $_POST['invoice_label'] : '';
		$dataArr['reference_label'] = isset($_POST['reference_label']) ? $_POST['reference_label'] : '';
		$dataArr['type_label'] = isset($_POST['type_label']) ? $_POST['type_label'] : '';
		$dataArr['nature_label'] = isset($_POST['nature_label']) ? $_POST['nature_label'] : '';
		$dataArr['date_label'] = isset($_POST['date_label']) ? $_POST['date_label'] : '';

        if (empty($dataArr)) {
            $this->setError($this->validationMessage['mandatory']);
            return false;
        }

		if (!$this->validateInvoiceSetting($dataArr)) {
            return false;
        }

        if ($this->checkUserInvoiceSettingExist($_SESSION['user_detail']['user_id'])) {

            $dataArr['updated_by'] = $_SESSION['user_detail']['user_id'];
            $dataArr['updated_date'] = date('Y-m-d H:i:s');

            $dataConditionArray['added_by'] = $this->sanitize($_SESSION['user_detail']['user_id']);
            if ($this->update($this->tableNames['client_invoice_setting'], $dataArr, $dataConditionArray)) {

                $this->setSuccess($this->validationMessage['invoicesettingsaved']);
                $this->logMsg("Invoice Label Setting Updated By ID : " . $_SESSION['user_detail']['user_id'] . " in invoice setting.","client_invoice_setting");
                return true;
            } else {

                $this->setError($this->validationMessage['failed']);
                return false;
            }
        } else {

            $dataArr['added_by'] = $_SESSION['user_detail']['user_id'];
            $dataArr['added_date'] = date('Y-m-d H:i:s');

            if ($this->insert($this->tableNames['client_invoice_setting'], $dataArr)) {

                $this->setSuccess($this->validationMessage['invoicesettingsaved']);
                $insertid = $this->getInsertID();
                $this->logMsg("Invoice Label Setting Added. ID : " . $insertid . ".","client_invoice_setting");
                return true;
            } else {
                $this->setError($this->validationMessage['failed']);
                return false;
            }
        }
    }
	
	public function validateInvoiceSetting($dataArr) {

        $rules = array('invoice_label' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Invoice Label');

		if( array_key_exists("reference_label",$dataArr) ) {
			$rules['reference_label'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Reference Label';
		}

		if( array_key_exists("type_label",$dataArr) ) {
			$rules['type_label'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Type Label';
		}

		if( array_key_exists("nature_label",$dataArr) ) {
			$rules['nature_label'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Nature Label';
		}

		if( array_key_exists("date_label",$dataArr) ) {
			$rules['date_label'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Date Label';
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

    public function saveUserThemeSetting() {

        $dataArr['theme_style'] = isset($_POST['theme_style']) ? $_POST['theme_style'] : 'theme-color.css';

		if(isset($_POST['show_logo']) && $_POST['show_logo'] == '1') {
			$dataArr['show_logo'] = '1';
		} else {
			$dataArr['show_logo'] = '0';
		}

		if(isset($_POST['show_signature']) && $_POST['show_signature'] == '1') {
			$dataArr['show_signature'] = '1';
		} else {
			$dataArr['show_signature'] = '0';
		}

		$dataArr['gross_turnover'] = isset($_POST['gross_turnover']) ? $_POST['gross_turnover'] : '';
		$dataArr['cur_gross_turnover'] = isset($_POST['cur_gross_turnover']) ? $_POST['cur_gross_turnover'] : '';
		$dataArr['isd_number'] = isset($_POST['isd_number']) ? $_POST['isd_number'] : '';
		$dataArr['gstin_username'] = isset($_POST['gstin_username']) ? $_POST['gstin_username'] : '';
		$dataArr['bank_name'] = isset($_POST['bank_name']) ? $_POST['bank_name'] : '';
		$dataArr['account_number'] = isset($_POST['account_number']) ? $_POST['account_number'] : '';
		$dataArr['branch_name'] = isset($_POST['branch_name']) ? $_POST['branch_name'] : '';
		$dataArr['ifsc_code'] = isset($_POST['ifsc_code']) ? $_POST['ifsc_code'] : '';

        if (empty($dataArr)) {
            $this->setError($this->validationMessage['mandatory']);
            return false;
        }

        if ($_FILES['theme_logo']['name'] != '') {

            $theme_logo = $this->imageUploads($_FILES['theme_logo'], 'theme-logo', 'upload', $this->allowImageExt, 1048576, 'Max file Size 1 MB');
            if ($theme_logo == FALSE) {
                return false;
            } else {
                $dataArr['theme_logo'] = $theme_logo;
            }
        }
		
		if ($_FILES['theme_signature']['name'] != '') {

            $theme_signature = $this->imageUploads($_FILES['theme_signature'], 'theme-signature', 'upload', $this->allowImageExt, 1048576, 'Max file Size 1 MB');
            if ($theme_signature == FALSE) {
                return false;
            } else {
                $dataArr['theme_signature'] = $theme_signature;
            }
        }

		if (!$this->validateThemeSetting($dataArr)) {
            return false;
        }
		
		$dataInsertArray['theme_style'] = $dataArr['theme_style'];
		$dataInsertArray['show_logo'] = $dataArr['show_logo'];
		$dataInsertArray['show_signature'] = $dataArr['show_signature'];
		if( array_key_exists("theme_logo",$dataArr) ) {
			$dataInsertArray['theme_logo'] = $dataArr['theme_logo'];
		}

		if( array_key_exists("theme_signature",$dataArr) ) {
			$dataInsertArray['theme_signature'] = $dataArr['theme_signature'];
		}

		/* update kyc turnover data */
		$dataInsertKYCArray['gross_turnover'] = $dataArr['gross_turnover'];
		$dataInsertKYCArray['cur_gross_turnover'] = $dataArr['cur_gross_turnover'];
		$dataInsertKYCArray['isd_number'] = $dataArr['isd_number'];
		$dataInsertKYCArray['gstin_username'] = $dataArr['gstin_username'];
		$dataInsertKYCArray['bank_name'] = $dataArr['bank_name'];
		$dataInsertKYCArray['account_number'] = $dataArr['account_number'];
		$dataInsertKYCArray['branch_name'] = $dataArr['branch_name'];
		$dataInsertKYCArray['ifsc_code'] = $dataArr['ifsc_code'];
		$dataInsertKYCArray['updated_by'] = $_SESSION['user_detail']['user_id'];
		$dataInsertKYCArray['updated_date'] = date('Y-m-d H:i:s');
		$dataConditionKYCArray['added_by'] = $this->sanitize($_SESSION['user_detail']['user_id']);
		$this->update($this->tableNames['client_kyc'], $dataInsertKYCArray, $dataConditionKYCArray);
		/* end of update kyc turnover data */

        if ($this->checkUserThemeSettingExist($_SESSION['user_detail']['user_id'])) {

            $dataInsertArray['updated_by'] = $_SESSION['user_detail']['user_id'];
            $dataInsertArray['updated_date'] = date('Y-m-d H:i:s');

            $dataConditionArray['added_by'] = $this->sanitize($_SESSION['user_detail']['user_id']);
            if ($this->update($this->tableNames['user_theme_setting'], $dataInsertArray, $dataConditionArray)) {

                $this->setSuccess($this->validationMessage['themesettingsaved']);
                $this->logMsg("Theme Setting ID : " . $_SESSION['user_detail']['user_id'] . " in theme setting has been updated.","user_themesetting");
                return true;
            } else {

                $this->setError($this->validationMessage['failed']);
                return false;
            }
        } else {

            $dataInsertArray['added_by'] = $_SESSION['user_detail']['user_id'];
            $dataInsertArray['added_date'] = date('Y-m-d H:i:s');

            if ($this->insert($this->tableNames['user_theme_setting'], $dataInsertArray)) {

                $this->setSuccess($this->validationMessage['themesettingsaved']);
                $insertid = $this->getInsertID();
                $this->logMsg("New Theme Setting Added. ID : " . $insertid . ".","user_themesetting");
                return true;
            } else {
                $this->setError($this->validationMessage['failed']);
                return false;
            }
        }
    }

    public function validateThemeSetting($dataArr) {

        $rules = array('theme_style' => 'pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Theme Style');

        if (array_key_exists("theme_logo", $dataArr)) {
            $rules['theme_logo'] = 'image|#|lable_name:Theme Logo';
        }

		if (array_key_exists("theme_signature", $dataArr)) {
            $rules['theme_signature'] = 'image|#|lable_name:Theme Signature';
        }

		if( array_key_exists("gross_turnover",$dataArr) ) {
			$rules['gross_turnover'] = 'required||numeric||decimalzero|#|lable_name:Gross Turnover';
		}

		if( array_key_exists("cur_gross_turnover",$dataArr) ) {
			$rules['cur_gross_turnover'] = 'required||numeric||decimalzero|#|lable_name:Current Gross Turnover';
		}

		if( array_key_exists("isd_number",$dataArr) ) {
			$rules['isd_number'] = 'pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:ISD Number';
		}

		if( array_key_exists("gstin_username",$dataArr) ) {
			$rules['gstin_username'] = 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:ISD Number';
		}

		if( array_key_exists("bank_name",$dataArr) ) {
			$rules['bank_name'] = 'pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Bank Name';
		}

		if( array_key_exists("account_number",$dataArr) ) {
			$rules['account_number'] = 'pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Account Number';
		}

		if( array_key_exists("branch_name",$dataArr) ) {
			$rules['branch_name'] = 'pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Branch Name';
		}

		if( array_key_exists("ifsc_code",$dataArr) ) {
			$rules['ifsc_code'] = 'pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:IFSC Code';
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

        if (!$this->validateAdminUser($dataArr)) {
            return false;
        }

        if ($this->checkUsernameExist($dataArr['username'])) {
            $this->setError($this->validationMessage['usernameexist']);
            return false;
        }

        if ($this->checkEmailAddressExist($dataArr['email'])) {
            $this->setError($this->validationMessage['emailexist']);
            return false;
        }

        if ($this->checkCompanyCodeExist($dataArr['company_code'])) {
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
            $this->logMsg("New User Added. ID : " . $insertid . ".","user_adminupdate");
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

        if (array_key_exists("username", $dataArr)) {
            $rules['username'] = 'required||pattern:/^' . $this->validateType['username'] . '+$/|#|lable_name:Username';
        }

        if (array_key_exists("password", $dataArr)) {
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

        if (isset($_POST['password']) && $_POST['password'] != '') {
            $dataArr['password'] = isset($_POST['password']) ? $_POST['password'] : '';
        }

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

        if (!$this->validateAdminUser($dataArr)) {
            return false;
        }

        if ($this->checkEmailAddressExist($dataArr['email'], $this->sanitize($_GET['id']))) {
            $this->setError($this->validationMessage['emailexist']);
            return false;
        }

        if ($this->checkCompanyCodeExist($dataArr['company_code'], $this->sanitize($_GET['id']))) {
            $this->setError($this->validationMessage['companycodeexist']);
            return false;
        }

        if (isset($dataArr['password']) && $dataArr['password'] != '') {
            $dataArr['password'] = $this->password_encrypt($dataArr['password']);
        } /* encrypt password */
        $dataArr['user_group'] = 2;
        $dataArr['updated_by'] = $_SESSION['user_detail']['user_id'];
        $dataArr['updated_date'] = date('Y-m-d H:i:s');

        $dataConditionArray['user_id'] = $this->sanitize($_GET['id']);
        if ($this->update($this->tableNames['user'], $dataArr, $dataConditionArray)) {

            $this->setSuccess($this->validationMessage['useredited']);
            $this->logMsg("User ID : " . $_GET['id'] . " in User has been updated","user_adminupdate");
            return true;
        } else {

            $this->setError($this->validationMessage['failed']);
            return false;
        }

        return true;
    }

    public function updateSubscriberUser() {

        $dataArr['first_name'] = isset($_POST['first_name']) ? $_POST['first_name'] : '';
        $dataArr['last_name'] = isset($_POST['last_name']) ? $_POST['last_name'] : '';

        if (isset($_POST['password']) && $_POST['password'] != '') {
            $dataArr['password'] = isset($_POST['password']) ? $_POST['password'] : '';
        }

        $dataArr['email'] = isset($_POST['emailaddress']) ? $_POST['emailaddress'] : '';
        $dataArr['company_name'] = isset($_POST['company_name']) ? $_POST['company_name'] : '';
        $dataArr['company_code'] = isset($_POST['company_code']) ? $_POST['company_code'] : '';
        $dataArr['phone_number'] = isset($_POST['phonenumber']) ? $_POST['phonenumber'] : '';
        $dataArr['status'] = isset($_POST['user_status']) ? $_POST['user_status'] : '';

        if (empty($dataArr)) {
            $this->setError($this->validationMessage['mandatory']);
            return false;
        }

        if (!$this->validateSubscriberUser($dataArr)) {
            return false;
        }

        if ($this->checkEmailAddressExist($dataArr['email'], $this->sanitize($_SESSION['user_detail']['user_id']))) {
            $this->setError($this->validationMessage['emailexist']);
            return false;
        }

        if ($this->checkCompanyCodeExist($dataArr['company_code'], $this->sanitize($_SESSION['user_detail']['user_id']))) {
            $this->setError($this->validationMessage['companycodeexist']);
            return false;
        }

        if (isset($dataArr['password']) && $dataArr['password'] != '') {
            $dataArr['password'] = $this->password_encrypt($dataArr['password']);
        } /* encrypt password */
        $dataArr['updated_by'] = $_SESSION['user_detail']['user_id'];
        $dataArr['updated_date'] = date('Y-m-d H:i:s');

        $dataConditionArray['user_id'] = $this->sanitize($_SESSION['user_detail']['user_id']);
        if ($this->update($this->tableNames['user'], $dataArr, $dataConditionArray)) {

            $this->setSuccess($this->validationMessage['profileupdated']);
            $this->logMsg("User ID : " . $_SESSION['user_detail']['user_id'] . " has been updated","update_subscriber");
            return true;
        } else {

            $this->setError($this->validationMessage['failed']);
            return false;
        }

        return true;
    }

    public function validateSubscriberUser($dataArr) {

        $rules = array(
            'first_name' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:First Name',
            'last_name' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Last Name',
            'email' => 'required||email|#|lable_name:Email',
            'company_name' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Company Name',
            'company_code' => 'required||pattern:/^[' . $this->validateType['alphanumeric'] . ']+$/|#|lable_name:Company Code',
            'phone_number' => 'required||pattern:/^[' . $this->validateType['mobilenumber'] . ']+$/|#|lable_name:Phone Number',
            'status' => 'required||pattern:/^[' . $this->validateType['onlyzeroone'] . ']*$/|#|lable_name:Status'
        );

        if (array_key_exists("password", $dataArr)) {
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
            $this->logMsg("User ID : " . $userid . " in User has been deleted","user_update");
            return true;
        } else {

            $this->setError($this->validationMessage['failed']);
            return false;
        }

        return true;
    }

    public function changePassword() {
        $dataArr = $this->getPassParams();
        if (empty($dataArr)) {
            $this->setError("Fill all fields");
            return false;
        }

        $dataRes = $this->findAll("cms_user", "user_id='" . $_SESSION['user_detail']['user_id'] . "'", "password");
        if ($dataRes[0]->password != md5($dataArr['old_password'])) {
            $this->setError("Password not matched");
            return false;
        }
        if ($dataArr['new_password'] != $dataArr['confirm_password']) {
            $this->setError("New Password not matched");
            return false;
        }
        $data['password'] = md5($dataArr['new_password']);
        if ($this->update("cms_user", $data, array("user_id" => $_SESSION['user_detail']['user_id']))) {
            $this->logMsg("Password Updated : User ID : " . $_SESSION['user_detail']['user_id'], "User Management", $_SESSION['user_detail']['user_id']);
            $this->setSuccess("Password updated");
            $this->redirect(ADMIN_URL . "/changepassword.php");
            exit();
        }
        $this->setError("Error try again to change the password");
        $this->redirect(ADMIN_URL . "/changepassword.php");
        exit();
    }

    public function getPassParams() {
        $dataArr = array();
        if (isset($_POST['submit']) && $_POST['submit'] == 'Submit') {
            $dataArr['old_password'] = $this->sanitize($_POST['exisiting_password']);
            $dataArr['new_password'] = $this->sanitize($_POST['new_password']);
            $dataArr['confirm_password'] = $this->sanitize($_POST['confirm_password']);
        }
        return $dataArr;
    }

    /*
     * 
     * Start : New Module of User Role
     * Created by : Rishap (29th May 2017)
     * 
     */

    final public function addUserRole() {
        $dataArr = $this->getUserRoleData();
        if (empty($dataArr)) {
            $this->setError($this->validationMessage['mandatory']);
            return false;
        }
        if (!$this->validateUserRole($dataArr)) {
            return false;
        }
        $dataArr['added_by'] = $_SESSION['user_detail']['user_id'];
        $dataArr['added_date'] = date('Y-m-d H:i:s');
        if (!$this->insert($this->tableNames['user_role'], $dataArr)) {
            $this->setError($this->validationMessage['failed']);
            return false;
        }
        $this->setSuccess($this->validationMessage['inserted']);
        $insertid = $this->getInsertID();
        $this->logMsg("New User Role Added. ID : " . $insertid . ".","user_role");
        return true;
    }

    private function getUserRoleData() {
        $dataArr = array();
        if (isset($_POST['submit']) && ($_POST['submit'] == 'submit' || ($_POST['submit'] == 'update' && isset($_GET['id'])))) {
            $dataArr['role_name'] = isset($_POST['role_name']) ? $_POST['role_name'] : '';
            $dataArr['role_page'] = isset($_POST['role_page']) ? $_POST['role_page'] : '';
            $dataArr['role_description'] = isset($_POST['role_description']) ? $_POST['role_description'] : '';
            $dataArr['status'] = isset($_POST['status']) ? $_POST['status'] : '';
        }
        return $dataArr;
    }

    private function validateUserRole($dataArr) {
        $rules = array(
            'role_name' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Role Name',
            'role_page' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Page Name',
            'role_description' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Role Description',
            'status' => 'required||numeric|#|lable_name:Status'
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

    final public function updateUserRole() {
        $dataArr = $this->getUserRoleData();
        if (empty($dataArr)) {
            $this->setError($this->validationMessage['mandatory']);
            return false;
        }
        if (!$this->validateUserRole($dataArr)) {
            return false;
        }
        $dataArr['updated_by'] = $_SESSION['user_detail']['user_id'];
        $dataArr['updated_date'] = date('Y-m-d H:i:s');
        if (!$this->update($this->tableNames['user_role'], $dataArr, array('user_role_id' => $this->sanitize($_GET['id'])))) {
            $this->setError($this->validationMessage['failed']);
            return false;
        }
        $this->logMsg("User Role ID : " . $_GET['id'] . " in User Role Module has been updated","user_role");
        $this->setSuccess($this->validationMessage['update']);
        return true;
    }

    /*
     * 
     * End of User Role Module
     * 
     */



    /*
     * 
     * Start : New Module of User Group
     * Created by : Rishap (1st June 2017)
     * 
     */

    private function addUserGroupPermission($posi) {
        $dataArr = $this->getUserGroupPermissionData('submit', $posi);
        if (empty($dataArr)) {
            $this->setError($this->validationMessage['mandatory']);
            return false;
        }
        $dataArr['group_id'] = $this->sanitize($_GET['id']);
        $dataArr['added_by'] = $_SESSION['user_detail']['user_id'];
        $dataArr['added_date'] = date('Y-m-d H:i:s');
		if (!$this->insert($this->tableNames['user_role_permission'], $dataArr)) {
            $this->setError($this->validationMessage['failed']);
            return false;
        }
        $this->setSuccess($this->validationMessage['inserted']);
        $insertid = $this->getInsertID();
        $this->logMsg("New User Group Permission Added. ID : " . $insertid . ".","group_permission");
        return true;
    }

    private function getUserGroupPermissionData($type, $posi) {
        $dataArr = array();
        if ($type == 'submit') {
            $dataArr['role_id'] = isset($_POST['user_role_id'][$posi]) ? $_POST['user_role_id'][$posi] : '';
            $dataArr['can_read'] = isset($_POST['view'][$_POST['user_role_id'][$posi]]) ? $_POST['view'][$_POST['user_role_id'][$posi]] : '0';
            $dataArr['can_create'] = isset($_POST['create'][$_POST['user_role_id'][$posi]]) ? $_POST['create'][$_POST['user_role_id'][$posi]] : '0';
            $dataArr['can_update'] = isset($_POST['update'][$_POST['user_role_id'][$posi]]) ? $_POST['update'][$_POST['user_role_id'][$posi]] : '0';
            $dataArr['can_delete'] = isset($_POST['delete'][$_POST['user_role_id'][$posi]]) ? $_POST['delete'][$_POST['user_role_id'][$posi]] : '0';
        } else if ($type == 'update') {
            $dataArr['set']['can_read'] = isset($_POST['view'][$_POST['user_role_id'][$posi]]) ? $_POST['view'][$_POST['user_role_id'][$posi]] : '0';
            $dataArr['set']['can_create'] = isset($_POST['create'][$_POST['user_role_id'][$posi]]) ? $_POST['create'][$_POST['user_role_id'][$posi]] : '0';
            $dataArr['set']['can_update'] = isset($_POST['update'][$_POST['user_role_id'][$posi]]) ? $_POST['update'][$_POST['user_role_id'][$posi]] : '0';
            $dataArr['set']['can_delete'] = isset($_POST['delete'][$_POST['user_role_id'][$posi]]) ? $_POST['delete'][$_POST['user_role_id'][$posi]] : '0';
        }
		
        return $dataArr;
    }

    private function validateUserGroupPermission($dataArr) {
        for ($x = 0; $x < count($dataArr); $x++) {
            $rules = array(
                'role_name' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Role Name',
                'role_page' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Page Name',
                'role_description' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Role Description',
                'status' => 'required||numeric|#|lable_name:Status'
            );
            $valid = $this->vali_obj->validate($dataArr[$x], $rules);
        }
        if ($valid->hasErrors()) {
            $err_arr = $valid->allErrors();
            $this->setError($err_arr);
            $valid->clearMessages();
            return false;
        }
        return true;
    }

    private function updateUserGroupPermission($posi) {
        $dataArr = $this->getUserGroupPermissionData('update', $posi);
        if (empty($dataArr)) {
            $this->setError($this->validationMessage['mandatory']);
            return false;
        }
//        if(!$this->validateUserGroupPermission($dataArr))
//        {
//            return false;
//        }

        $dataArr['set']['updated_by'] = $_SESSION['user_detail']['user_id'];
        $dataArr['set']['updated_date'] = date('Y-m-d H:i:s');
        $dataArr['where']['group_id'] = $this->sanitize($_GET['id']);
        $dataArr['where']['role_id'] = $this->sanitize($_POST['user_role_id'][$posi]);
        if (!$this->update($this->tableNames['user_role_permission'], $dataArr['set'], $dataArr['where'])) {
            $this->setError($this->validationMessage['failed']);
            return false;
        }
        $this->logMsg("User Permission ID : " . $this->sanitize($_GET['id']) . " in User Group Permission Module has been updated", 'User Group Permission', $this->sanitize($_GET['id']));
        $this->setSuccess($this->validationMessage['update']);
        return true;
    }

    final public function userGroupPermission() {
        $y = count($_POST['user_role_id']);
        for ($x = 0; $x < $y; $x++) {
            $data = $this->findAll($this->tableNames['user_role_permission'], 'is_deleted="0" and role_id="' . $this->sanitize($_POST['user_role_id'][$x]) . '" and group_id="' . $this->sanitize($_GET['id']) . '" ');
            if (empty($data)) {
                $zzz = $this->addUserGroupPermission($x);
            } else {
                $zzz = $this->updateUserGroupPermission($x);
            }
        }
        if (!empty($_SESSION['error'])) {
            return false;
        }
        return true;
    }

    /*
     * 
     * End of User Group Module
     * 
     */
}
