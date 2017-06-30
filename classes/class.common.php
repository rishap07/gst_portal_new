<?php
/*
 * 
 *  Developed By        :   Rishap Gandhi
 *  Description         :   A simple class for common functions to be used throughout the project 
 *  Date Created        :   May 18, 2017
 *  Last Modified       :   May 18, 2017
 *  Last Modified By    :   Rishap Gandhi
 *  Last Modification   :   file creation started
 * 
 */

class common extends db {
    /* FUNCTION TO PRINT AN ARRAY AND DIE */

    public function __construct() {
        parent::__construct();
    }

    public function pr($arr, $die = '') {
        echo "<pre>";
        print_r($arr);
        echo "</pre>";
        if ($die) {
            die;
        }
    }

    /* FUNCTION TO GET PAGE NAME */

    public function getPageName() {
        return substr($_SERVER["SCRIPT_NAME"], strrpos($_SERVER["SCRIPT_NAME"], "/") + 1);
    }

    /* FUNCTION TO ENCODE A VALUE */

    public function encode($str) {
        $str = strrev(base64_encode($str));
        return $str;
    }

    /* FUNCTION TO DECODE A VALUE ENCODED BY THE ABOVE ENCODE FUNCTION */

    public function decode($str) {
        $str = base64_decode(strrev($str));
        return $str;
    }

    /* FUNCTION TO CONVERT THE FIRST LETTER OF A STRING TO UPPERCASE AND OTHERS TO LOWERCASE */

    public function upperFirst($string) {
        return ucfirst(strtolower(trim($string)));
    }

    /* FUNCTION TO COUNT NUMBER OF WORDS IN A STRING */

    public function countWords($string) {
        $word_count = 0;
        $string = eregi_replace(" +", " ", $string);
        $string = eregi_replace("\n+", " ", $string);
        $string = eregi_replace(" +", " ", $string);
        $string = explode(" ", $string);
        while (list(, $word) = each($string)) {
            if (eregi("[0-9A-Za-z�-��-��-�]", $word)) {
                $word_count++;
            }
        }
        return $word_count;
    }

    /* FUNCTION TO REDIRECT TO A SPECIFIC URL */

    public function redirect($url) {
        if (!header('location : ' . $url)) {
            echo "<script type='text/javascript'>window.location.href = '" . $url . "'</script>";
            die;
        }
    }

    /* FUNCTION TO GET UNIQUE VALUE FROM AN ARRAY */

    public function uniqueFromArray($arr = array()) {
        if (!is_array($arr) || !count($arr) > 0) {
            return false;
        }
        return array_unique($arr);
    }

    /* FUNCTION TO GET UNIQUE VALUE OF AN ELEMENT IN A MULTI-DIMENSIONAL ARRAY */

    public function getUniqueFrom2D($arr = array(), $uniqueElement) {
        if (!is_array($arr) || !count($arr) > 0) {
            return false;
        }
        $tmpArr = array();
        foreach ($arr as $single) {
            if (is_arra($single)) {
                $tmpArr[] = $single[$uniqueElement];
            }
        }
        return arra_unique($tmpArr);
    }

    /* FUNCTION TO SHOW SUCCESS OR ERROR MESSAGE 
      /*
     * Function is used to show success or error message on table page of a module when we edit or create a record.
     */

    public function showActionMsgCMS() {
        if (isset($_SESSION['successMsg'])) {
            ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['successMsg']; ?>
            </div>
            <?php
            unset($_SESSION['successMsg']);
        } else if (isset($_SESSION['errorMsg'])) {
            ?>
            <div class="alert alert-error">
                <?php echo $_SESSION['errorMsg']; ?>
            </div>
            <?php
            unset($_SESSION['errorMsg']);
        }
    }

    /* FUNCTION TO LIMIT SHOW ONLY LIMITED CHARS OF A STRING */

    public function limitChars($string, $charLimit) {
        if ($string != '' && $charLimit != '' && is_numeric($charLimit)) {
            return substr($string, 0, $charLimit);
        }
    }

    /* FUNCTION TO LIMIT SHOW ONLY LIMITED CHARS OF A STRING */



    /* This function is use to create large image to thum image */

    public function image_thumb($image_path, $wt = 0, $ht = 0) {
        $size = getimagesize($image_path);
        switch ($size["mime"]) {
            case "image/jpeg":
                $im = imagecreatefromjpeg($image_path); //jpeg file
                break;
            case "image/gif":
                $im = imagecreatefromgif($image_path); //gif file
                break;
            case "image/png":
                $im = imagecreatefrompng($image_path); //png file
                break;
            default:
                $im = false;
                break;
        }
        //return $im;
        if ($im === false) {
            readfile($image_path);
        }
        $w = imagesx($im);
        $h = imagesy($im);
        if (isset($wt) && $wt != 0 && $ht != 0) {
            $new_wt = $wt;
            $new_ht = $new_wt * ($h / $w);
        } elseif (isset($ht) && $ht != 0 && $wt != 0) {
            $new_ht = $ht;
            $new_wt = $new_ht * ($w / $h);
        } else {
            $new_wt = (isset($wt) && $wt != 0) ? $wt : 560;
            $new_ht = (isset($ht) && $ht != 0) ? $ht : 560;
            if (($w / $h) > ($new_wt / $new_ht)) {
                $new_ht = $new_wt * ($h / $w);
            } else {
                $new_wt = $new_ht * ($w / $h);
            }
        }
        $im2 = ImageCreateTrueColor($new_wt, $new_ht);
        imagecopyResampled($im2, $im, 0, 0, 0, 0, $new_wt, $new_ht, $w, $h);
        imagejpeg($im2);
    }

    public function getCurrentDateTime() {
        return date("Y-m-d H:i:s");
    }

    public function generateRandomString($length, $tablename, $column, $prefix = '', $mixed = true) {

        if ($mixed) {
            $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        } else {
            $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }

        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        $finalstring = $prefix . $randomString;
        $sql_arr = $this->findAll($tablename, $column . '="' . $finalstring . '"', $params = '', $group_by = '', $order_by = '', $limit = '', $offset = '');

        if (count($sql_arr) > 0) {
            $this->generateRandomString($length, $tablename, $column, $prefix = '');
        } else {
            return $finalstring;
        }
    }
    
    public function generateSubscriberRandomCode($length, $tablename, $column, $prefix = '') {

        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        $finalstring = $prefix . $randomString;
        $sql_arr = $this->findAll($tablename, $column . '="' . $finalstring . '"', $params = '', $group_by = '', $order_by = '', $limit = '', $offset = '');

        if (count($sql_arr) > 0) {
            $this->generateSubscriberRandomCode($length, $tablename, $column, $prefix = '');
        } else {
            return $finalstring;
        }
    }

    function generateRandomNumber($length, $tablename, $column, $prefix = '') {
        
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        
        $finalstring = $prefix . $randomString;
        $sql_arr = $this->findAll($tablename, $column . '="' . $finalstring . '"', $params = '', $group_by = '', $order_by = '', $limit = '', $offset = '');
        
        if (count($sql_arr) > 0) {
            $this->generateRandomNumber($length, $tablename, $column, $prefix = '');
        } else {
            return $finalstring;
        }
    }

    public function logMsg($msg = '', $module = '', $module_id = '') {
        if ($msg != '') {
            $dataArr['module_name'] = $module;
            $dataArr['module_id'] = $module_id;
            $dataArr['msg'] = $msg;
            $dataArr['userid'] = $_SESSION['user_detail']['user_id'];
            $dataArr['ip'] = $this->getIPAddress();
            $dataArr['dateoflog'] = date('Y-m-d H:i:s');
            $this->insert(TAB_PREFIX . "admin_log", $dataArr);
        }
    }

    public function clientlogMsg($msg = '') {
        if ($msg != '') {
            $dataArr['msg'] = $msg;
            $dataArr['userid'] = '';
            $dataArr['ip'] = $this->getIPAddress();
            $dataArr['dateoflog'] = date('Y-m-d H:i:s');
            $this->insert(TAB_PREFIX . "user_log", $dataArr);
        }
    }

    public function getIPAddress() {
        return $_SERVER['REMOTE_ADDR'];
    }

    public function countryList() {
        $countryList = $this->findAll(TAB_PREFIX . 'country', '', 'iso_code_3, country', '', 'country asc');
        if (array_key_exists('ajax', $_REQUEST)) {
            echo json_encode(array('data' => $countryList, 'status' => 200, 'msg' => 'Country List'));
        }
        return json_encode(array('data' => $countryList, 'status' => 200, 'msg' => 'Country List'));
    }

    public function stateList($countryVal = '') {
        if ($countryVal != '') {
            $countryId = $countryVal;
        } else {
            $countryId = $_POST['countryId'];
        }
        $stateList = $this->findAll(TAB_PREFIX . 'states', "countryID='" . $countryId . "'", 'stateID, stateName', '', 'stateName asc');
        if (array_key_exists('ajax', $_REQUEST)) {
            echo json_encode(array('data' => $stateList, 'status' => 200, 'msg' => 'State List'));
        }
        return json_encode(array('data' => $stateList, 'status' => 200, 'msg' => 'State List'));
    }

    public function password_encrypt($pass) {
        if ($pass != '') {
            return md5($pass);
        }
        return false;
    }

    public function ageCal($fromDate, $toDate = '') {
        $from = new DateTime($fromDate);
        $toDateNew = (($toDate == '') ? 'today' : $toDate);
        $to = new DateTime($toDateNew);
        return $from->diff($to)->y;
    }

    public function ageDiff($fromDate, $toDate = '') {
        $date1 = new DateTime($fromDate);
        $toDateNew = (($toDate == '') ? 'today' : $toDate);
        $date2 = new DateTime($toDateNew);
        $diff = $date1->diff($date2);
        return $diff;
    }

    public function setError($msg) {
        if (!isset($_SESSION['error'])){
            if (is_array($msg)) {
                foreach ($msg as $key => $value) {
                    $_SESSION['error'][] = $value;
                }
            } else
                $_SESSION['error'][] = $msg;
        }
    }

    protected function getError() {
        if (isset($_SESSION['error']) && !empty($_SESSION['error'])) {
            $msg = "";
            for ($x = 0; $x < count($_SESSION['error']); $x++) {
                $msg.=$_SESSION['error'][$x] . "|";
            }
            $this->unsetMessage();
            return rtrim($msg, "|");
        }
    }

    public function showErrorMessage() {
        if (isset($_SESSION['error']) && !empty($_SESSION['error'])) {
            $msg = "<div style='color:#f00;background-color:#eddbe3;border-radius:4px;padding:8px 35px 8px 14px;text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);margin-bottom: 18px;border-color: #e8d1df;color: #bd4247;'>";
            for ($x = 0; $x < count($_SESSION['error']); $x++) {
                $msg .= "<i class='fa fa-exclamation-triangle'></i>&nbsp;" . ($x + 1) . ".&nbsp;" . $_SESSION['error'][$x] . "<br>";
            }
            $msg .= "</div>";
            echo $msg;
        }
    }
    
    public function getErrorMessage() {
        if (isset($_SESSION['error']) && !empty($_SESSION['error'])) {
            $msg = "<div style='color:#f00;background-color:#eddbe3;border-radius:4px;padding:8px 35px 8px 14px;text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);margin-bottom: 18px;border-color: #e8d1df;color: #bd4247;'>";
            for ($x = 0; $x < count($_SESSION['error']); $x++) {
                $msg .= "<i class='fa fa-exclamation-triangle'></i>&nbsp;" . ($x + 1) . ".&nbsp;" . $_SESSION['error'][$x] . "<br>";
            }
            $msg .= "</div>";
            return $msg;
        }
    }

    public function setSuccess($msg) {
        if (!isset($_SESSION['success'])) {
            if (is_array($msg)) {
                foreach ($msg as $key => $value) {
                    $_SESSION['success'][] = $value;
                }
            } else
                $_SESSION['success'][] = $msg;
        }
    }

    public function showSuccessMessge() {
        if (isset($_SESSION['success']) && !empty($_SESSION['success'])) {
            $msg = "<div id='sucmsg' style='background-color:#DBEDDF;border-radius:4px;padding:8px 35px 8px 14px;text-shadow:0 1px 0 rgba(255, 255, 255, 0.5);margin-bottom:18px;border-color:#D1E8DA;color:#39A25F;'><i class='fa fa-check'></i> <b>Success:</b>";
            for ($x = 0; $x < count($_SESSION['success']); $x++) {
                $msg.=$_SESSION['success'][$x] . "&nbsp;&nbsp;&nbsp;";
            }
            $msg .= "</div>";
            echo $msg;
        }
    }

    public function unsetMessage() {
        if (isset($_SESSION['error']) && !empty($_SESSION['error']) && $_SESSION['error'] != '') {
            $_SESSION['error'] = '';
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['success']) && !empty($_SESSION['success']) && $_SESSION['success'] != '') {
            $_SESSION['success'] = '';
            unset($_SESSION['success']);
        }
    }

    public function imageUploads($image, $uploadPath, $foldername, $allowdExt, $maxSize = '', $error = '') {
        if (count($image['name']) <= 5) {
            $obj_upload = new upload();
            $temp = $obj_upload->getExtension($image);
            $obj_upload->validate($image);
            $flag = 0;
            $nameArr = array();
            if (!empty($temp)) {
                $temp1 = explode('/', $temp);
                $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
                $newFileName = $obj_upload->newFileName($uploadPath . '_' . rand(1, 100), $ext);
                $nameArr = $newFileName;
                $obj_upload->setDestination(PROJECT_ROOT . "/" . $foldername . "/" . $uploadPath);
                $obj_upload->setAllowedExtensions($allowdExt);
                $obj_upload->setMaxSize($maxSize);
                if (!$obj_upload->validate($image, FALSE, $error)) {
                    $flag = 1;
                    return FALSE;
                }
                if ($flag == 0) {
                    $success = $obj_upload->uploadFile($image['tmp_name'], $nameArr);
                    if (!$success) {
                        $flag = 1;
                        $obj_upload->setError("Error in upload file in " . $image['name']);
                    }
                }
            } else {
                $flag = 1;
                $obj_upload->setError("Please choose file(s)");
            }
        } else {
            $obj_upload->setError("You can select max 5 pictures at one time ");
        }
        return $nameArr;
    }

    public function email_schedule($module, $module_msg, $to, $cc = '', $bcc = '', $subject, $message, $from = '', $attchment = '') {
        $sql = $this->insert(TAB_PREFIX . 'email', array('module' => $module, 'module_message' => $module_msg, 'to_send' => $to, 'cc' => $cc, 'bcc' => $bcc, 'subject' => $subject, 'body' => $message, 'from_send' => $from, 'datetime' => date('Y-m-d H:i:s'), 'attachment' => $attchment, 'status' => '0'));
        if ($sql)
            return true;
        else
            return false;
    }

    public function randomNumber($length) {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function moduleAccess($modulename) {
        if ($modulename != '') {
            if ($_SESSION['user_modules']) {
                if (in_array($modulename, $_SESSION['user_modules'])) {
                    return true;
                }
                $this->setError("You are not authorised to access this module. Kindly contact Administrator");
                return false;
            } else {
                $this->setError("You are not authorised to access this module. Kindly contact Administrator");
                return false;
            }
        }
        $this->setError("You are not authorised to access this module. Kindly contact Administrator");
        return false;
    }

    public function menuAccess($modulename) {
        if ($modulename != '') {
            if ($_SESSION['user_modules']) {
                if (in_array($modulename, $_SESSION['user_modules'])) {
                    return true;
                }
                return false;
            } else {
                return false;
            }
        }
        return false;
    }

    public function getTheme() {
        $dataArr = $this->findAll(TAB_PREFIX . "theme", "status='0' and is_deleted='0' ");
        if (empty($dataArr)) {
            $dataArr = $this->findAll(TAB_PREFIX . "theme", "is_deleted='0' limit='1' ");
        }
        return $dataArr;
    }
    
    /* validate id */
    public function validateId($id = '') {

        if(is_numeric($id) && $id > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    /* Get user details by user id */
    public function getUserDetailsById($user_id = '') {

        $data = $this->get_row("select *, CONCAT(first_name,' ',last_name) as name from " . $this->tableNames['user'] . " where user_id = '" . $user_id . "'");
        $dataArr = array();
        if (!empty($data)) {

            $kycDetails = $this->getClientKYCDetailsById( $data->user_id );
            $data->kyc = $kycDetails['data'];
            
            $dataArr['data'] = $data;
            $dataArr['message'] = $this->validationMessage['userexist'];
            $dataArr['status'] = 'success';
        } else {
            $dataArr['data'] = '';
            $dataArr['message'] = $this->validationMessage['usernotexist'];
            $dataArr['status'] = 'error';
        }

        return $dataArr;
    }
    
    public function getClientKYCDetailsById($user_id = '') {

        $data = $this->get_row("select ck.name, ck.email, ck.phone_number, ck.date_of_birth, ck.gstin_number, ck.pan_card_number, ck.uid_number, ck.identity_proof, ck.proof_photograph, ck.business_type, ck.address_proof, ck.registered_address, ck.registration_type, ck.state_id, s.state_name, s.state_code, s.state_tin, ck.added_by, ck.updated_by from " . $this->tableNames['client_kyc'] . " as ck inner join " . $this->tableNames['state'] . " as s on ck.state_id=s.state_id where 1=1 AND ck.added_by = " . $user_id);
        $dataArr = array();
        if (!empty($data)) {
            $dataArr['data'] = $data;
            $dataArr['status'] = 'success';
        } else {
            $dataArr['data'] = '';
            $dataArr['status'] = 'error';
        }

        return $dataArr;
    }
    
    public function getUserGroupDetailsById($group_id = '') {

        $data = $this->get_row("select * from " . $this->tableNames['user_group'] . " where id = '" . $group_id . "'");
        $dataArr = array();
        if (!empty($data)) {
            $dataArr['data'] = $data;
            $dataArr['status'] = 'success';
        } else {
            $dataArr['data'] = '';
            $dataArr['status'] = 'error';
        }

        return $dataArr;
    }
    
    /* Get all master units */
    public function getUnit($field = "*", $condition='', $orderby='unit_name asc', $limit='',$group_by='') {

        $query = "select ".$field."  from ".$this->tableNames['unit']." where 1=1 ";
        
        if($condition !='') {
            $query .= " and ".$condition;
        }
        
        if($group_by !='') {
            $query .= " group by ".$group_by;
        }
        $query .= " order by ".$orderby." ".$limit;
        return $this->get_results($query);
    }
    
    public function checkUsernameExist($username) {

        $checkUsername = $this->get_row("select * from " . $this->tableNames['user'] . " where 1=1 AND username = '" . $username . "'");
        if (count($checkUsername) == 1) {
            return true;
        }
    }

    public function checkEmailAddressExist($emailaddress, $user_id = '') {

        if($user_id && $user_id != '') {
            $checkEmailAddress = $this->get_row("select * from " . $this->tableNames['user'] ." where 1=1 AND user_id != ".$user_id." AND email = '" . $emailaddress . "'");
        } else {
            $checkEmailAddress = $this->get_row("select * from " . $this->tableNames['user'] . " where 1=1 AND email = '" . $emailaddress . "'");
        }
        
        if (count($checkEmailAddress) == 1) {
            return true;
        }
    }
    
    public function checkCompanyCodeExist($company_code, $user_id = '') {
        
        if($user_id && $user_id != '') {
            $companyCode = $this->get_row("select * from " . $this->tableNames['user'] ." where 1=1 AND user_id != ".$user_id." AND UPPER(company_code) = '" . strtoupper($company_code) . "'");
        } else {
            $companyCode = $this->get_row("select * from " . $this->tableNames['user'] . " where 1=1 AND UPPER(company_code) = '" . strtoupper($company_code) . "'");
        }
        
        if (count($companyCode) == 1) {
            return true;
        }
    }
    
    public function checkUserThemeSettingExist($user_id = '') {

        $checkSetting = $this->get_row("select * from " . $this->tableNames['user_theme_setting'] . " where 1=1 AND added_by = " . $user_id);
        if (count($checkSetting) == 1) {
            return true;
        }
    }
    
    public function checkGSTINNumberExist($gstinnumber, $user_id = '') {

        if($user_id && $user_id != '') {
            $numberGSTIN = $this->get_row("select * from " . $this->tableNames['client_gstin_detail'] ." where 1=1 AND added_by != ".$user_id." AND UPPER(gstin_number) = '" . strtoupper($gstinnumber) . "'");
        } else {
            $numberGSTIN = $this->get_row("select * from " . $this->tableNames['client_gstin_detail'] . " where 1=1 AND UPPER(gstin_number) = '" . strtoupper($gstinnumber) . "'");
        }
        
        if (count($numberGSTIN) == 1) {
            return true;
        }
    }
    
    public function getUserThemeSetting($user_id = '') {

        $data = $this->get_row("select * from " . $this->tableNames['user_theme_setting'] . " where 1=1 AND added_by = " . $user_id);
        $dataArr = array();
        if (!empty($data)) {
            $dataArr['data'] = $data;
            $dataArr['status'] = 'success';
        } else {
            $dataArr['data'] = '';
            $dataArr['status'] = 'error';
        }

        return $dataArr;
    }
    
    public function getMasterItems($field = "*", $condition='', $orderby='item_id asc', $limit='', $group_by='') {

        $query = "select ".$field."  from ".$this->tableNames['item']." where 1=1 ";
        
        if($condition != '') {
            $query .= " and ".$condition;
        }
        
        if($group_by != '') {
            $query .= " group by ".$group_by;
        }
        
        $query .= " order by ".$orderby." ".$limit;        
        return $this->get_results($query);
    }
    
    public function getMasterUnits($field = "*", $condition='', $orderby='unit_id asc', $limit='', $group_by='') {

        $query = "select ".$field."  from ".$this->tableNames['unit']." where 1=1 ";
        
        if($condition != '') {
            $query .= " and ".$condition;
        }
        
        if($group_by != '') {
            $query .= " group by ".$group_by;
        }
        
        $query .= " order by ".$orderby." ".$limit;        
        return $this->get_results($query);
    }
    
    /* save cookies */
    public function saveCookie($name, $value, $days = 30, $path = '/') {

        $expire = time() + (86400 * $days);
        setcookie($name, $value, $expire, "/");
    }

    public function getPreserveData($preserveKey) {

        $preserveSet = $this->get_row("select * from " . $this->tableNames['preserve_user'] . " where preserve_key = '" . $preserveKey . "'");
        return $preserveSet;
    }

    public function setRememberMeCookie($user_id) {

        $userData = $this->getUserDetailsById($user_id);

        if (isset($_COOKIE['preserveKey']) && $_COOKIE['preserveKey'] != '') {

            $preserveSet = $this->getPreserveData($_COOKIE['preserveKey']);
            if (count($preserveSet)) {

                $dataPreserveArr['expire_date'] = date('Y-m-d H:i:s', strtotime("+30 day"));

                if ($this->update($this->tableNames['preserve_user'], $dataPreserveArr, array("preserve_key" => $preserveSet->preserve_key))) {
                    return true;
                } else {
                    $this->setError($this->validationMessage['failed']);
                    return false;
                }
            }
        }
        
        /* Generate Preserve Key */
        $user_preserve_key = $this->generateRandomString(26, $this->tableNames['preserve_user'], "preserve_key", '', true);

        $dataPreserveArr['preserve_key'] = $user_preserve_key;
        $dataPreserveArr['user_id'] = $userData['data']->user_id;
        $dataPreserveArr['ip'] = $_SERVER['REMOTE_ADDR'];
        $dataPreserveArr['browser'] = $_SERVER['HTTP_USER_AGENT'];
        $dataPreserveArr['expire_date'] = date('Y-m-d H:i:s', strtotime("+7 day"));
        $dataPreserveArr['register_date'] = date('Y-m-d H:i:s');

        if ($this->insert($this->tableNames['preserve_user'], $dataPreserveArr)) {

            $this->saveCookie("preserveKey", $user_preserve_key, 30, '/');
            return true;
        } else {

            $this->setError($this->validationMessage['failed']);
            return false;
        }
    }
    
    protected function hitCurl($url,$parameters) {
        
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $parameters);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec ($curl);
        curl_close ($curl);
        return $server_output;
    }
    
    public function can_read($page_name)
    {
        if(isset($_SESSION['user_role'][$page_name]['can_read']) && $_SESSION['user_role'][$page_name]['can_read']=='1')
        {
            return true;
        }
        return false;
    }
    
    public function can_create($page_name)
    {
        if(isset($_SESSION['user_role'][$page_name]['can_create']) && $_SESSION['user_role'][$page_name]['can_create']=='1')
        {
            return true;
        }
        return false;
    }
    
    public function can_update($page_name)
    {
        if(isset($_SESSION['user_role'][$page_name]['can_update']) && $_SESSION['user_role'][$page_name]['can_update']=='1')
        {
            return true;
        }
        return false;
    }
    
    public function can_delete($page_name)
    {
        if(isset($_SESSION['user_role'][$page_name]['can_delete']) && $_SESSION['user_role'][$page_name]['can_delete']=='1')
        {
            return true;
        }
        return false;
    }
    
    /* state details by state code */
    public function getStateDetailByStateCode($state_code) {

        $data = $this->get_row("select * from " . $this->tableNames['state'] . " where UPPER(state_code) = '" . strtoupper($state_code) . "'");
        $dataArr = array();
        if (!empty($data)) {
            $dataArr['data'] = $data;
            $dataArr['status'] = 'success';
        } else {
            $dataArr['data'] = '';
            $dataArr['status'] = 'error';
        }

        return $dataArr;
    }
    
    /* state details by state code */
    public function getStateDetailByStateId($state_id) {

        $data = $this->get_row("select * from " . $this->tableNames['state'] . " where state_id = " . $state_id);
        $dataArr = array();
        if (!empty($data)) {
            $dataArr['data'] = $data;
            $dataArr['status'] = 'success';
        } else {
            $dataArr['data'] = '';
            $dataArr['status'] = 'error';
        }

        return $dataArr;
    }
    
    /* state details by state code */
    public function getStateDetailByStateTin($state_tin) {

        $data = $this->get_row("select * from " . $this->tableNames['state'] . " where state_id = '" . $state_tin ."'");
        $dataArr = array();
        if (!empty($data)) {
            $dataArr['data'] = $data;
            $dataArr['status'] = 'success';
        } else {
            $dataArr['data'] = '';
            $dataArr['status'] = 'error';
        }

        return $dataArr;
    }
    
    /* generate financial year for client */
    public function generateFinancialYear() {

        $financial_year = date('Y') . "-" . date('Y', strtotime('+1 years'));
        if(date('n') < 4) {
           $financial_year = date('Y', strtotime('-1 years')) . "-" . date('Y');
        }

        return $financial_year;
    }
    
    /* generate invoice number for client */
    public function generateInvoiceNumber($clientId) {
        
        $query = "select invoice_id  from ".$this->tableNames['client_invoice']." where 1=1 AND added_by=" . $clientId;
        $invoices = $this->get_results($query);
        
        if( !empty($invoices) ) {

            $nextInvoice = count($invoices) + 1;
            return "INV-" . str_pad($nextInvoice, 12, "0", STR_PAD_LEFT);
        } else {
            return "INV-000000000001";
        }
    }
    
    /* generate bill invoice number for client */
    public function generateBillInvoiceNumber($clientId) {
        
        $currentFinancialYear = $this->generateFinancialYear();
        $query = "select invoice_id  from ".$this->tableNames['client_bos_invoice']." where 1=1 AND financial_year = '".$currentFinancialYear."' AND added_by=" . $clientId;
        $invoices = $this->get_results($query);

        if( !empty($invoices) ) {

            $nextInvoice = count($invoices) + 1;
            return "IBS-" . str_pad($nextInvoice, 12, "0", STR_PAD_LEFT);
        } else {
            return "IBS-000000000001";
        }
    }
    
    /* generate receipt voucher invoice number for client */
    public function generateRVInvoiceNumber($clientId) {
        
        $currentFinancialYear = $this->generateFinancialYear();
        $query = "select invoice_id  from ".$this->tableNames['client_rv_invoice']." where 1=1 AND financial_year = '".$currentFinancialYear."' AND added_by=" . $clientId;
        $invoices = $this->get_results($query);
        
        if( !empty($invoices) ) {

            $nextInvoice = count($invoices) + 1;
            return "IRV-" . str_pad($nextInvoice, 12, "0", STR_PAD_LEFT);
        } else {
            return "IRV-000000000001";
        }
    }
    
    /* generate refund voucher invoice number for client */
    public function generateRFInvoiceNumber($clientId) {
        
        $currentFinancialYear = $this->generateFinancialYear();
        $query = "select invoice_id  from ".$this->tableNames['client_rf_invoice']." where 1=1 AND financial_year = '".$currentFinancialYear."' AND added_by=" . $clientId;
        $invoices = $this->get_results($query);
        
        if( !empty($invoices) ) {

            $nextInvoice = count($invoices) + 1;
            return "IRF-" . str_pad($nextInvoice, 12, "0", STR_PAD_LEFT);
        } else {
            return "IRF-000000000001";
        }
    }
    
    /* generate payment voucher invoice number for client */
    public function generatePVInvoiceNumber($clientId) {
        
        $currentFinancialYear = $this->generateFinancialYear();
        $query = "select invoice_id  from ".$this->tableNames['client_pv_invoice']." where 1=1 AND financial_year = '".$currentFinancialYear."' AND added_by=" . $clientId;
        $invoices = $this->get_results($query);
        
        if( !empty($invoices) ) {

            $nextInvoice = count($invoices) + 1;
            return "IPV-" . str_pad($nextInvoice, 12, "0", STR_PAD_LEFT);
        } else {
            return "IPV-000000000001";
        }
    }
    
    /* generate revised tax invoice number for client */
    public function generateRTInvoiceNumber($clientId) {
        
        $query = "select invoice_id  from ".$this->tableNames['client_rt_invoice']." where 1=1 AND added_by=" . $clientId;
        $invoices = $this->get_results($query);
        
        if( !empty($invoices) ) {

            $nextInvoice = count($invoices) + 1;
            return "IRT-" . str_pad($nextInvoice, 12, "0", STR_PAD_LEFT);
        } else {
            return "IRT-000000000001";
        }
    }
    
    /* generate special tax invoice number for client */
    public function generateSTInvoiceNumber($clientId) {
        
        $currentFinancialYear = $this->generateFinancialYear();
        $query = "select invoice_id  from ".$this->tableNames['client_st_invoice']." where 1=1 AND financial_year = '".$currentFinancialYear."' AND added_by=" . $clientId;
        $invoices = $this->get_results($query);

        if( !empty($invoices) ) {

            $nextInvoice = count($invoices) + 1;
            return "IST-" . str_pad($nextInvoice, 12, "0", STR_PAD_LEFT);
        } else {
            return "IST-000000000001";
        }
    }

    function convert_number_to_words($number) {

        //$hyphen      = '-';
        //$conjunction = ' and ';
        $hyphen      = ' ';
        $conjunction = ' ';
        $separator   = ', ';
        $negative    = 'negative ';
        $decimal     = ' point ';
        
        $dictionary  = array(
            0          => 'zero',
            1          => 'one',
            2          => 'two',
            3          => 'three',
            4          => 'four',
            5          => 'five',
            6          => 'six',
            7          => 'seven',
            8          => 'eight',
            9          => 'nine',
            10         => 'ten',
            11         => 'eleven',
            12         => 'twelve',
            13         => 'thirteen',
            14         => 'fourteen',
            15         => 'fifteen',
            16         => 'sixteen',
            17         => 'seventeen',
            18         => 'eighteen',
            19         => 'nineteen',
            20         => 'twenty',
            30         => 'thirty',
            40         => 'fourty',
            50         => 'fifty',
            60         => 'sixty',
            70         => 'seventy',
            80         => 'eighty',
            90         => 'ninety',
            100        => 'hundred',
            1000       => 'thousand',
            100000     => 'lakh',
            10000000   => 'crore',
            1000000000 => 'arab'
        );

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
            // overflow
            /*trigger_error(
                'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );*/
            return false;
        }

        if ($number < 0) {
            return $negative . $this->convert_number_to_words(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens   = ((int) ($number / 10)) * 10;
                $units  = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds  = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . $this->convert_number_to_words($remainder);
                }
                break;
            case $number < 100000:
                $thousands   = ((int) ($number / 1000));
                $remainder = $number % 1000;

                $thousands = $this->convert_number_to_words($thousands);

                $string .= $thousands . ' ' . $dictionary[1000];
                if ($remainder) {
                    $string .= $separator . $this->convert_number_to_words($remainder);
                }
                break;
            case $number < 10000000:
                $lakhs   = ((int) ($number / 100000));
                $remainder = $number % 100000;

                $lakhs = $this->convert_number_to_words($lakhs);

                $string = $lakhs . ' ' . $dictionary[100000];
                if ($remainder) {
                    $string .= $separator . $this->convert_number_to_words($remainder);
                }
                break;
            case $number < 1000000000:
                $crores   = ((int) ($number / 10000000));
                $remainder = $number % 10000000;

                $crores = $this->convert_number_to_words($crores);

                $string = $crores . ' ' . $dictionary[10000000];
                if ($remainder) {
                    $string .= $separator . $this->convert_number_to_words($remainder);
                }
                break;
            case $number < 100000000000:
                $arabs   = ((int) ($number / 1000000000));
                $remainder = $number % 1000000000;

                $arabs = $this->convert_number_to_words($arabs);

                $string = $arabs . ' ' . $dictionary[1000000000];
                if ($remainder) {
                    $string .= $separator . $this->convert_number_to_words($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = $this->convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= $this->convert_number_to_words($remainder);
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }

        return $string;
    }
    
    final public function getClientReturn($id)
    {
        $return_id = $this->sanitize($id);
        echo $query = "select * from ".TAB_PREFIX."return where client_id='".$_SESSION['user_detail']['user_id']."' and return_id='".$id."'";
        return $this->get_results($query);
    }
    final public function getClientKyc()
    {
        $query = "select * from ".TAB_PREFIX."client_kyc where added_by='".$_SESSION['user_detail']['user_id']."'";
        return $this->get_results($query);
    }
}
