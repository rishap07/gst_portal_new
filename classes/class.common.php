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
        if (!isset($_SESSION['error'])) {
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
            //<b>Error(s)(" . count($_SESSION['error']) . ")</b>
            $msg = "<div style='color:#f00;background-color:#eddbe3;border-radius:4px;padding:8px 35px 8px 14px;text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);margin-bottom: 18px;border-color: #e8d1df;color: #bd4247;'><i class='fa fa-exclamation-triangle'></i>";
            for ($x = 0; $x < count($_SESSION['error']); $x++) {
                $msg.=($x + 1) . ".&nbsp;&nbsp;" . $_SESSION['error'][$x] . "<br>";
            }
            $msg .= "</div>";
            echo $msg;
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

        $data = $this->get_row("select * from " . $this->tableNames['user'] . " where user_id = '" . $user_id . "'");
        $dataArr = array();
        if (!empty($data)) {
            
            $groupDetails = $this->getUserGroupDetailsById( $data->user_group );
            if($groupDetails['status'] == "success") {
                $data->user_group = $groupDetails['data'];
            }           

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

                $dataPreserveArr['expire_date'] = date('Y-m-d H:i:s', strtotime("+7 day"));

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
    
    protected function hitCurl($url,$parameters)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $parameters);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec ($curl);
        curl_close ($curl);        
        return $server_output;
    }
}
