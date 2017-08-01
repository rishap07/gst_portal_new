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

    public function addPlanToSubscriber() {

        $dataArr['plan_id'] = isset($_GET['plan_id']) ? $_GET['plan_id'] : '';
        $dataArr['plan_start_date'] = date('Y-m-d H:i:s');
        $dataArr['payment_status'] = "1";
        $dataArr['added_by'] = $_SESSION['user_detail']['user_id'];
        $dataArr['added_date'] = date('Y-m-d H:i:s');

        if (empty($dataArr)) {
            $this->setError($this->validationMessage['mandatory']);
            return false;
        }

        /* get plan details */
        $planDetail = $this->getAllActivePlanSuAdmin("p.id,p.name,p.plan_category,p.no_of_client,p.company_no,p.pan_num,p.invoice_num,p.support,p.period_of_service,p.web_mobile_app,p.cloud_storage_gb,p.gst_expert_help,p.plan_price,(case when p.status='1' Then 'active' when p.status='0' then 'deactive' end) as status,c.name as cat_name,c.description as cat_description", "p.id='" . $dataArr['plan_id'] . "' and p.is_deleted='0'", $orderby = 'p.id asc');
        $dataArr['plan_due_date'] = '2017-03-31 12:00:00';

        if ($this->insert($this->tableNames['user_subscribed_plan'], $dataArr)) {

            $insertid = $this->getInsertID();
            $this->logMsg("New Plan Subscribed Added. ID : " . $insertid . ".");

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

    public function generatePlanPdf($invid, $planDetail, $planduedate,$companyaddress,$useraddress) {
        $mpdfHtml = '';
        $mpdfHtml .= '<div style="margin:auto;font-size:16px;line-height:24px;color:#555;">';
        $mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:left;" cellpadding="0" cellspacing="0">';
        $mpdfHtml .= '<tr>';
        $mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;">';
        $mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:left;">';
        $mpdfHtml .= '<tr>';
        $mpdfHtml .= '<td style="font-size:45px;line-height:45px;color:#333;padding:5px;vertical-align:top;padding-bottom:20px;">';

        if (isset($dataThemeSettingArr['data']->theme_logo) && $dataThemeSettingArr['data']->theme_logo != "") {
            $mpdfHtml .= '<img src="' . PROJECT_URL . '/upload/theme-logo/' . $dataThemeSettingArr['data']->theme_logo . '" style="width:100%;max-width:300px;">';
        } else {
            $mpdfHtml .= '<img src="' . PROJECT_URL . '/image/gst-k-logo.png" style="width:100%;max-width:300px;">';
        }

        $mpdfHtml .= '</td>';

        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;text-align:right;padding-bottom:20px;">';
        $mpdfHtml .= '<b>Invoice #</b>: ' .$planDetail['0']->id . '<br>';
        $mpdfHtml .= '<b>Reference #</b>: ' . $planDetail['0']->id . '<br>';
        $mpdfHtml .= '<b>Type:</b> ' . 'Plan Invoice' . '<br>';
        $mpdfHtml .= '<b>Invoice Date:</b>' . date('Y-m-d');
        $mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';
        $mpdfHtml .= '</table>';
        $mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';
        
        $mpdfHtml .= '<tr>';
        $mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;">';
        $mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:left;">';
        $mpdfHtml .= '<tr>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;padding-bottom:20px;">';
        $mpdfHtml .= $companyaddress['name'] . '<br>';
        $mpdfHtml .= $companyaddress['address'] . '<br>';
        $mpdfHtml .= $companyaddress['address1'] . '<br>';
        $mpdfHtml .= '<b>GSTIN:</b> ' . $companyaddress['gstin'];
        $mpdfHtml .= '<b>SAC CODE:</b> ' . $companyaddress['sac'];
        $mpdfHtml .= '</td>';

        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;text-align:right;padding-bottom:20px;">';
        $mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';
        $mpdfHtml .= '</table>';
        $mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';
        
        
        $mpdfHtml .= '<tr>';
        $mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;">';
        $mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:left;">';
        $mpdfHtml .= '<tr>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;padding-bottom:40px;width:50%;">';

        $mpdfHtml .= '<b>Recipient Detail</b><br>';
        $mpdfHtml .= $useraddress['name'] . '<br>';
        $mpdfHtml .= $useraddress['address'] . '<br>';
        $mpdfHtml .= $useraddress['address1'] . '<br>';
        $mpdfHtml .= '<b>GSTIN:</b> ' . $useraddress['gstin'];
        $mpdfHtml .= '</td>';

        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;text-align:right;padding-bottom:20px;">';
        $mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';
        $mpdfHtml .= '</table>';
        $mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';
        
        $mpdfHtml .= '<tr>';

        $mpdfHtml .= '<td colspan="2">';

        $mpdfHtml .= '<table style="width:100%;line-height:inherit;text-align:center;">';
        $mpdfHtml .= '<tr>';
        $mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">S.No</td>';
        $mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;text-align:left;">Goods/Services</td>';
        $mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;text-align:right;">HSN/SAC Code</td>';
        $mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;text-align:right;">Qty</td>';
        $mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;text-align:right;">Unit</td>';
        $mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;text-align:right;">Rate</td>';
        $mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;text-align:right;">Total</td>';
        $mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;text-align:right;">Discount(%)</td>';
         $mpdfHtml .= '<td rowspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;text-align:right;">Taxable Value</td>';
//        $mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">CGST</td>';
//        $mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">SGST</td>';
        $mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;text-align:right;">IGST</td>';
        $mpdfHtml .= '<td colspan="2" style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;text-align:right;">CESS</td>';
        $mpdfHtml .= '</tr>';

        $mpdfHtml .= '<tr class="heading">';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;text-align:right;">(%)</td>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">Amt (₹)</td>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;text-align:right;">(%)</td>';
        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;text-align:right;">Amt (₹)</td>';
//        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">(%)</td>';
//        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">Amt (₹)</td>';
//        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">(%)</td>';
//        $mpdfHtml .= '<td style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">Amt (₹)</td>';
        $mpdfHtml .= '</tr>';
        $counter = 1;
        $total_taxable_subtotal = 0.00;
        $totaligstpercent = 18;
        $total_igst_amount = ($totaligstpercent / 100) * $planDetail['0']->plan_price;
        $total_plan_amount=$planDetail['0']->plan_price+$total_igst_amount;
        
        $mpdfHtml .= '<tr>';
            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;">';
            $mpdfHtml .= $counter;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;text-align:left;">';
            $mpdfHtml .= '<b>'.$planDetail['0']->cat_name.':'.$planDetail['0']->name.'</b><br>';
            $mpdfHtml .= '<b> GSTN :</b>'.$planDetail['0']->no_of_client.'<br>';
            $mpdfHtml .= '<b> Company :</b>'.$planDetail['0']->company_no.'<br>';
            $mpdfHtml .= '<b> Pan :</b>'.$planDetail['0']->pan_num.'<br>';
            $mpdfHtml .= '<b> Invoice number :</b>'.$planDetail['0']->invoice_num.'<br>';
            $mpdfHtml .= '<b> support :</b>'.$planDetail['0']->support.'<br>';
            $mpdfHtml .= '<b> period_of_service :</b>'.$planDetail['0']->period_of_service.'<br>';
            $mpdfHtml .= '<b> Web Mobile App :</b>'.$planDetail['0']->web_mobile_app.'<br>';
//            $mpdfHtml .= '<b> e_filing :'.$planDetail['0']->no_of_client.'</b><br>';
//            $mpdfHtml .= '<b> excel_tool :'.$planDetail['0']->no_of_client.'</b><br>';
            $mpdfHtml .= '<b> Cloud Storage :</b>'.$planDetail['0']->cloud_storage_gb.'<br>';
            $mpdfHtml .= '<b> Expert Help :</b>'.$planDetail['0']->gst_expert_help.'<br>';
            
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;text-align:right;">';
            $mpdfHtml .= '#123456';
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;text-align:right;">';
            $mpdfHtml .= 1;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;text-align:right;">';
            $mpdfHtml .= 1;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;text-align:right;">';
            $mpdfHtml .= $planDetail['0']->plan_price;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;text-align:right;">';
            $mpdfHtml .= $planDetail['0']->plan_price;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;text-align:right;">';
            $mpdfHtml .= 0;
            $mpdfHtml .= '</td>';
            
            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;text-align:right;">';
            $mpdfHtml .= $planDetail['0']->plan_price;
            $mpdfHtml .= '</td>';

//            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;">';
//            $mpdfHtml .= 'cgst_rate';
//            $mpdfHtml .= '</td>';
//
//            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;">';
//            $mpdfHtml .= 'cgst_amount';
//            $mpdfHtml .= '</td>';
//
//            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;">';
//            $mpdfHtml .= 'sgst_rate';
//            $mpdfHtml .= '</td>';
//
//            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;">';
//            $mpdfHtml .= 'sgst_amount';
//            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;text-align:right;">';
            $mpdfHtml .= $totaligstpercent;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;text-align:right;">';
            $mpdfHtml .= $total_igst_amount;
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;text-align:right;">';
            $mpdfHtml .= '0';
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '<td style="padding:5px;vertical-align:top;border-bottom:1px solid #eee;text-align:right;">';
            $mpdfHtml .= '0';
            $mpdfHtml .= '</td>';

            $mpdfHtml .= '</tr>';
            $mpdfHtml .= '<tr>';
             $mpdfHtml .= '<td colspan="17" style="padding:5px;vertical-align:top;text-align:right;border-top:2px solid #eee;font-weight:bold;">';
             $mpdfHtml .= 'Total Invoice Value (In Figure): ' . $total_plan_amount;
        $mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';
       
        $mpdfHtml .= '</br></br>';
        $mpdfHtml .= '<tr>';
        $mpdfHtml .= '<td colspan="10" style="padding:5px;vertical-align:top;text-align:right;border-top:2px solid #eee;font-weight:bold;">';
             $mpdfHtml .= 'This is a computer generated invoice. No signature is required.';
        $mpdfHtml .= '</td>';
        $mpdfHtml .= '</tr>';
        $mpdfHtml .= '</table>';

        $mpdfHtml .= '</td>';

        $mpdfHtml .= '</tr>';

        $mpdfHtml .= '</table>';
             $mpdfHtml .= '</div>';
        return $mpdfHtml;
//                echo "<pre>";
//        print_r($mpdfHtml);
//       
//        echo "</pre>";
//        die();
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

    public function saveUserThemeSetting() {

        $dataArr['theme_style'] = isset($_POST['theme_style']) ? $_POST['theme_style'] : 'theme-color.css';

        if (empty($dataArr)) {
            $this->setError($this->validationMessage['mandatory']);
            return false;
        }

        if (!$this->validateThemeSetting($dataArr)) {
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

        if ($this->checkUserThemeSettingExist($_SESSION['user_detail']['user_id'])) {

            $dataArr['updated_by'] = $_SESSION['user_detail']['user_id'];
            $dataArr['updated_date'] = date('Y-m-d H:i:s');

            $dataConditionArray['added_by'] = $this->sanitize($_SESSION['user_detail']['user_id']);
            if ($this->update($this->tableNames['user_theme_setting'], $dataArr, $dataConditionArray)) {

                $this->setSuccess($this->validationMessage['themesettingsaved']);
                $this->logMsg("Theme Setting ID : " . $_SESSION['user_detail']['user_id'] . " in theme setting has been updated.");
                return true;
            } else {

                $this->setError($this->validationMessage['failed']);
                return false;
            }
        } else {

            $dataArr['added_by'] = $_SESSION['user_detail']['user_id'];
            $dataArr['added_date'] = date('Y-m-d H:i:s');

            if ($this->insert($this->tableNames['user_theme_setting'], $dataArr)) {

                $this->setSuccess($this->validationMessage['themesettingsaved']);
                $insertid = $this->getInsertID();
                $this->logMsg("New Theme Setting Added. ID : " . $insertid . ".");
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
            $this->logMsg("User ID : " . $_GET['id'] . " in User has been updated");
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
            $this->logMsg("User ID : " . $_SESSION['user_detail']['user_id'] . " has been updated");
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
            $this->logMsg("User ID : " . $userid . " in User has been deleted");
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
        $this->logMsg("New User Role Added. ID : " . $insertid . ".");
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
        $this->logMsg("User Role ID : " . $_GET['id'] . " in User Role Module has been updated");
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
        $this->logMsg("New User Group Permission Added. ID : " . $insertid . ".");
        return true;
    }

    private function getUserGroupPermissionData($type, $posi) {
        $dataArr = array();
        if ($type == 'submit') {
            $dataArr['role_id'] = isset($_POST['user_role_id'][$posi]) ? $_POST['user_role_id'][$posi] : '';
            $dataArr['can_read'] = isset($_POST['view'][$_POST['user_role_id'][$posi]]) ? $_POST['view'][$_POST['user_role_id'][$x]] : '0';
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
