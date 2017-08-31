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

    public $otp = '';

    public function __construct() {
        parent::__construct();
        $this->checkEmailMobileVerify();
    }

    public function pr($arr, $die = '') {
        echo "<pre>";
        print_r($arr);
        echo "</pre>";
        if ($die) {
            die;
        }
    }

    public function checkEmailMobileVerify() {
        if (isset($_SESSION['user_detail']['user_id']) && $_SESSION['user_detail']['user_id'] != '') {
			$data = $this->get_results("select * from " . TAB_PREFIX . "user where user_id='" . $_SESSION['user_detail']['user_id'] . "'");
            if ($data[0]->plan_id > 0 && !isset($_SESSION['verify'])) {
                $_SESSION['verify'] = 1;
                $link_address = PROJECT_URL . '/?page=client_email_verification';
                $link_address1 = PROJECT_URL . '/?page=client_sms_verification';

                if ($data[0]->email_verify == '0' && $_REQUEST['page'] != 'client_email_verification') {
                    $this->setError("To Verify your email <a href='" . $link_address . "'>click here</a>");
                }
                if ($data[0]->mobileno_verify == '0' && $_REQUEST['page'] != 'client_sms_verification') {
                    $this->setError("To Verify your contact number <a href='" . $link_address1 . "'>click here</a>");
                }
            }
        }
    }

    public function sendVerifcationEmail() {
        $data = $this->get_results("select * from " . TAB_PREFIX . "user where user_id='" . $_SESSION['user_detail']['user_id'] . "'");
        if ($data[0]->email_verify == '0') {
            $name = $data[0]->first_name;
            if ($this->sendMail('Email Verify', 'User ID : ' . $_SESSION['user_detail']['user_id'] . ' email verfication', $data[0]->email, 'noreply@gstkeeper.com', '', 'rishap.gandhi@cyfuture.com', '', 'Verify Your Email Address', $this->getEmailVerifyMailBody($name))) {
                $this->setSuccess('A confirmation mail has been sent to you (Kindly check your inbox & spam folder). <strong>Confirm your e-mail</strong> by clicking on the link in the mail. ');
            } else {
                $this->setError('Try again, there is some issue in sending an email.');
            }
        } else {
            $this->setError('It looks like you`ve already verified your email address with us. Thanks!');
        }
    }

    private function getEmailVerifyMailBody($name) {
        $token = md5(uniqid(rand(), 1));
        //$data = '<a href="'.PROJECT_URL.'/?page=dashboard&verifyemail=' . $token . '&passkey='.md5($_SESSION['user_detail']['user_id']).'">Click here</a>  or copy the below url and paste on browser to verify your email';
        $data = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>gst</title>
</head>

<body>
<div style="width:720px; margin:auto; border:solid #CCC 1px;">
<table cellpadding="0" cellspacing="0" width="100%" >
  <tbody>
    <tr>
      <td height="auto"><table width="720" cellpadding="0" cellspacing="0" bgcolor="#fff" style="font-family:Arial, Helvetica, sans-serif;margin:0px auto;">
          <tbody>
            <tr>
              <td width="30"></td>
              <td><table width="100%" cellpadding="0" cellspacing="0">
                  <tbody>
                    <tr>
                      <td align="left" valign="middle" height="80"><a target="_blank" href="https://www.gstkeeper.com/"><img src="https://gstkeeper.com/newsletter/4july2017/gst-logo.png" alt="" border="0"></a></td>
                      <td align="right" valign="middle" style="font-size:18px;color:#cf3502;font-family:Arial, Helvetica, sans-serif;" height="80px"> <span><img src="https://gstkeeper.com/newsletter/6july2017/phone-icon.jpg" alt=""></span>1-800-212-2022<br>
                        <span><img src="https://gstkeeper.com/newsletter/6july2017/mail-icon.jpg"></span><a href="mailto:contact@gstkeeper.com" style="font-size:14px;color:#cf3502;text-decoration:none;"> contact@gstkeeper.com</a></td>
                    </tr>
                  </tbody>
                </table></td>
              <td width="30"></td>
            </tr>
            <tr>
              <td width="30"></td>
              <td><table width="100%" cellpadding="0" cellspacing="0">
                  <tbody>
                    <tr>
                      <td align="center" valign="middle"><img src="https://www.gstkeeper.com/newsletter/7july/images/banner.jpg" alt="" border="0" width="700"></td>
                    </tr>
                  </tbody>
                </table></td>
              <td width="30"></td>
            </tr>
        
         
           
            <tr>
              <td width="30"  ></td>
              <td><table width="100%" cellpadding="0" cellspacing="0">
                <tbody>
                  <tr>
                    <td height="319" align="center" valign="top"><table width="100%" cellpadding="0px" cellspacing="0" >
                      <tbody>
                        
                        <tr>
                          <td width="13"></td>
                          <td width="350"  style="font-size:15px;color:#090909;font-family:Arial, Helvetica, sans-serif; padding-top:10px; "><strong>Hi ' . $name . ' 
</strong></td>
                          <td width="20"></td>
                          </tr>
                        <tr>
                          <td colspan="3" height="10"></td>
                          </tr>
                        <tr>
                          <td width="13"></td>
                          <td height="140" align="justify"  valign="top" style="font-size:13px;color:#191919;font-family:Arial, Helvetica, sans-serif; line-height:18px; ">
                            <p>Thanks for getting started with GST Keeper! We just need to verify your email address.</p>
                            
                            <p>Please click the link below:</p>
							<p>
							<a href="' . PROJECT_URL . '/?page=dashboard&verifyemail=' . $token . '&passkey=' . md5($_SESSION['user_detail']['user_id']) . '" style="padding:2px 5px;background:#cf3502;color:#fff;text-decoration:none;font-size:20px;" target="_blank" >Verify</a></p>
                            <BR /><BR /><BR />
     <p>Thanks,<BR />
<strong>The GST Keeper Team </strong></p></td>
                          </tr>
                        
                        </tbody>
                    </table></td>
                    </tr>
                </tbody>
              </table></td>
              
            </tr>
            <!--<tr>
         
         <td  align="center" height="29"><img src="http://cdn.go4hosting.in/mailer/12-oct/resources-img.jpg"  alt=""    /></td>
         
         </tr>-->
            
            <tr>
              <td colspan="3" height="15"></td>
            </tr>
         
          
            <tr>
              <td width="30"></td>
              <td><table width="98%" align="right" cellpadding="0" cellspacing="0" style="background-color:#f1f1f1; height:80px; padding:10px;">
                <tbody>
                  <tr>
                    <td width="47%"><a href="http://www.cyfuture.com/" target="_blank"><img src="https://gstkeeper.com/newsletter/4july2017/cyfuture-logo.png" alt="" border="0" /></a></td>
                    <td width="53%" align="right"><table width="100%" cellpadding="0" cellspacing="0">
                      <tbody>
                        <tr>
                          <td width="20" height="50"></td>
                          <td valign="middle" style="font-size:14px;color:#333;font-family:Arial, Helvetica, sans-serif;"><strong><i>Connect with us</i></strong></td>
                          <td valign="middle" width="50" align="center"><a target="_blank" href="https://www.facebook.com/GST-Keeper-632910016898628/"><img src="https://gstkeeper.com/newsletter/4july2017/fb-icon.png" alt="" border="0" /></a></td>
                          <td valign="middle" width="40" align="left"><a target="_blank" href="https://plus.google.com/101841021110541536034"><img src="https://gstkeeper.com/newsletter/4july2017/g+-icon.png" alt="" border="0" /></a></td>
                          <td valign="middle" width="40" align="left"><a target="_blank" href="https://twitter.com/GstKeeper"><img src="https://gstkeeper.com/newsletter/4july2017/twit-icon.png" alt="" border="0" /></a></td>
                          <td valign="middle" width="40" align="left"><a target="_blank" href="https://www.youtube.com/channel/UCsDdNFR8kJ3YVWpEvBrFeSA"><img src="https://gstkeeper.com/newsletter/4july2017/utube-icon.png" alt="" border="0" /></a></td>
                          <td valign="middle" width="40" align="left"><a target="_blank" href="https://www.linkedin.com/company/gst-keeper"><img src="https://gstkeeper.com/newsletter/4july2017/in-icon.jpg" alt="" border="0" /></a></td>
                        </tr>
                      </tbody>
                    </table></td>
                  </tr>
                </tbody>
              </table></td>
              <td width="30"></td>
            </tr>
            <tr>
              <td width="30"></td>
              <td height="76" valign="middle"><table width="100%" cellpadding="0" cellspacing="0">
                  <tbody>
                    <tr>
                      <td width="20"></td>
                      <td align="center"><font style="font-size:14px;color:#444;font-family:Arial, Helvetica, sans-serif;">Cyfuture ( India ) Pvt. Ltd.</font><br>
                        <font style="font-size:12px;color:#444;font-family:Arial, Helvetica, sans-serif;">Plot No. 197-198 Noida Special Economic Zone (NSEZ) Phase II, Noida 201 305</font><br>
                      <font style="font-size:12px;color:#444;font-family:Arial, Helvetica, sans-serif;">E-mail: <a style="text-decoration:none;color:#3194d5;" href="mailto:contact@gstkeeper.com">contact@gstkeeper.com</a></font><br></td>
                      <td width="15" align="left">&nbsp;</td>
                    </tr>
                  </tbody>
              </table></td>
             
         
          </tbody>
        </table></td>
    </tr>
  </tbody>
</table>




</div>
</body>
</html>';
        $this->update(TAB_PREFIX . "user", array('email_code' => $token), array('user_id' => $_SESSION['user_detail']['user_id']));
        return $data;
    }

    public function sendMail($module = '', $module_message = '', $to_send, $from_send, $cc = '', $bcc = '', $attachment = '', $subject, $body) {
        $dataInsertArray['module'] = $module;
        $dataInsertArray['module_message'] = $module_message;
        $dataInsertArray['to_send'] = $to_send;
        $dataInsertArray['from_send'] = $from_send;
        $dataInsertArray['cc'] = $cc;
        $dataInsertArray['bcc'] = $bcc;
        $dataInsertArray['attachment'] = $attachment;
        $dataInsertArray['subject'] = $subject;
        $dataInsertArray['body'] = $body;

        if ($this->insert($this->tableNames['email'], $dataInsertArray)) {
            return true;
        } else {
            return false;
        }
    }

    public function sendVerifcationSms() {
        $data = $this->get_results("select * from " . TAB_PREFIX . "user where user_id='" . $_SESSION['user_detail']['user_id'] . "'");
        if ($data[0]->mobileno_verify == '0') {
            if ($this->sendSMS($data[0]->phone_number, $this->getSMSVerifyBody())) {
                $this->setSuccess('An OTP has been sent on your registered mobile number, kindly check.');
            } else {
                $this->setError('Try again, there is some issue in sending an OTP.');
            }
        } else {
            $this->setError('Your mobile number is already verified.');
        }
    }

    protected function sendSMS($phone_number, $body) {
        $url = "http://49.50.67.32/smsapi/httpapi.jsp?username=GSTkeeptr&password=GSTk33p&from=GSTKPR&to=" . $phone_number . "&text=" . urlencode($body) . "&coding=0";
        $parameters = '';
        return $this->hitCurl($url, $parameters = '');
    }

    protected function getSMSVerifyBody() {
        $this->generateRandomString1('6');
        $data = 'Your GSTKeeper OTP is, ' . $this->otp;
        $this->update(TAB_PREFIX . "user", array('mobile_code' => $this->otp), array('user_id' => $_SESSION['user_detail']['user_id']));
        return $data;
    }

    protected function generateRandomString1($length = 6) {


        $str = substr(str_shuffle("0123456789"), 0, $length);

        $result = $this->get_results("select * from " . TAB_PREFIX . "user where mobile_code='" . $str . "'");
        if (count($result) > 0) {
            $this->generateRandomString1($length = 6);
        } else {
            $this->otp = $str;
        }
    }

    public function emailVerify() {
        $data['verifyemail'] = isset($_GET['verifyemail']) ? $_GET['verifyemail'] : '';
        $data['passkey'] = isset($_GET['passkey']) ? $_GET['passkey'] : '';

        $dataRe = $this->get_results('select * from ' . TAB_PREFIX . "user where user_id='" . $_SESSION['user_detail']['user_id'] . "'");
        if (count($dataRe) > 0) {
            if ($dataRe[0]->email_code == $data['verifyemail'] && md5($dataRe[0]->user_id) == $data['passkey']) {
                $this->setSuccess('Email verified');
                $this->update(TAB_PREFIX . "user", array('email_verify' => '1', 'email_verify_date' => date('Y-m-d H:i:s')), array('user_id' => $_SESSION['user_detail']['user_id']));
                return true;
            } else {
                $this->setError('Email not verified');
                return false;
            }
        }
    }

    public function forgotEmailVerify() {

        //$data['verifyForgot'] = isset($_GET['verifyForgot']) ? $_GET['verifyForgot'] : '';
        //$data['passkey'] = isset($_GET['passkey']) ? $_GET['passkey'] : '';
        $data['verifyForgot'] = isset($_SESSION['user_detail']['token']) ? $_SESSION['user_detail']['token'] : '';
        $data['passkey'] = isset($_SESSION['user_detail']['passkey']) ? $_SESSION['user_detail']['passkey'] : '';
        $id = base64_decode($_SESSION['user_detail']['passkey']);
        $sql = 'select * from ' . TAB_PREFIX . "user where forgotemail_code='" . $_SESSION['user_detail']['token'] . "'";
      
        $dataRe = $this->get_results($sql);
        if (count($dataRe) > 0) {
            if ($dataRe[0]->forgotemail_verify == 1) {
                $this->setError('Your email verification link is expired to You are not able to access this link any more, enter your registered mail address below to get a new Reset Password link.');
                //$this->update(TAB_PREFIX."user",array('forgotemail_verify'=>'1','forgotemail_verify_date'=>date('Y-m-d H:i:s')),array('user_id'=>$dataRe[0]->user_id));
                return false;
            } else {
                //$this->setError('Your email is verified please update your new password');
                $this->update(TAB_PREFIX . "user", array('forgotemail_verify' => '1', 'forgotemail_verify_date' => date('Y-m-d H:i:s')), array('user_id' => $dataRe[0]->user_id));
                return true;
            }
        } else {
            $this->setError('Email not verified');
            return false;
        }
    }

    public function checkSmsOTP() {
        $data['mobile_code'] = isset($_POST['otp']) ? $_POST['otp'] : '';
        if ($data['mobile_code'] == '') {
            $this->setError('Kindly enter OTP');
            return false;
        }

        $dataRe = $this->get_results("select * from " . TAB_PREFIX . "user where user_id='" . $_SESSION['user_detail']['user_id'] . "'");

        if ($data['mobile_code'] != $dataRe[0]->mobile_code) {
            $this->setError('Invalid OTP');
            return false;
        } else {
            $this->update(TAB_PREFIX . "user", array('mobileno_verify' => '1', 'mobile_verify_date' => date('Y-m-d H:i:s')), array('user_id' => $_SESSION['user_detail']['user_id']));
            $this->setSuccess('Your mobile number is verified');
            return true;
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
        echo "<script type='text/javascript'>window.location.href = '" . $url . "'</script>";
        die;
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
	public function forgotemaillogMsg($msg = '', $module = '', $module_id = '',$userid) {
        if ($msg != '') {
            $dataArr['module_name'] = $module;
            $dataArr['module_id'] = $module_id;
            $dataArr['msg'] = $msg;
            $dataArr['userid'] = $userid;
            $dataArr['ip'] = $this->getIPAddress();
            $dataArr['dateoflog'] = date('Y-m-d H:i:s');
            $this->insert(TAB_PREFIX . "admin_log", $dataArr);
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
        else {
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
        if (isset($_SESSION['verify']) && !empty($_SESSION['verify']) && $_SESSION['verify'] != '') {
            unset($_SESSION['verify']);
        }
    }

    public function imageUploads($image, $uploadPath, $foldername, $allowdExt, $maxSize = '', $error = '') {

		if (count($image['name']) <= 5) {

			$obj_upload = new upload();
            $temp = $obj_upload->getExtension($image);
            //$obj_upload->validate($image);
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

        if (is_numeric($id) && $id > 0) {
            return true;
        } else {
            return false;
        }
    }
	 /* Get admin setting for livechat and tollfree number */
    public function getAdminSetting()

	{
		$sql = "select  * from ".TAB_PREFIX."admin_setting";
         $dataCurrentArr = $this->get_results($sql);
		if(!empty($dataCurrentArr))
		{
			return $dataCurrentArr;	
		}
		return false;
	}		
    /* Get user details by user id */

    public function getUserDetailsById($user_id = '') {

        $data = $this->get_row("select *, CONCAT(first_name,' ',last_name) as name from " . $this->tableNames['user'] . " where user_id = '" . $user_id . "'");
        $dataArr = array();
        if (!empty($data)) {

            $kycDetails = $this->getClientKYCDetailsById($data->user_id);
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

		$query = "select ck.name, ck.email, ck.phone_number, ck.date_of_birth, ck.gstin_number, ck.pan_card_number, ck.uid_number, ck.identity_proof, ck.proof_photograph, ck.business_type, ck.business_area, ck.vendor_type, ck.address_proof, ck.registered_address, ck.city, ck.zipcode, ck.registration_type, ck.digital_certificate_status, ck.digital_certificate, ck.state_id, s.state_name, s.state_code, s.state_tin, ck.country_id, ck.added_by as kyc_added_by, ck.updated_by as kyc_updated_by, ck.gross_turnover, ck.cur_gross_turnover, ck.isd_number, ck.gstin_username, ck.bank_name, ck.account_number, ck.branch_name, ck.ifsc_code, c.country_code, c.country_name, CONCAT(ck.registered_address,', ',ck.city,', ',s.state_name,', ',ck.zipcode,', ',c.country_name) as full_address from " . $this->tableNames['client_kyc'] . " as ck left join " . $this->tableNames['state'] . " as s on ck.state_id = s.state_id left join " . $this->tableNames['country'] . " as c on ck.country_id = c.id where 1=1 AND ck.added_by = " . $user_id;
		$data = $this->get_row($query);
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

    public function getUnit($field = "*", $condition = '', $orderby = 'unit_name asc', $limit = '', $group_by = '') {

        $query = "select " . $field . "  from " . $this->tableNames['unit'] . " where 1=1 ";

        if ($condition != '') {
            $query .= " and " . $condition;
        }

        if ($group_by != '') {
            $query .= " group by " . $group_by;
        }
        $query .= " order by " . $orderby . " " . $limit;
        return $this->get_results($query);
    }

    public function checkUsernameExist($username) {

        $checkUsername = $this->get_row("select * from " . $this->tableNames['user'] . " where 1=1 AND username = '" . $username . "'");

		if (count($checkUsername) > 0) {
            return true;
        }
    }

    public function checkEmailAddressExist($emailaddress, $user_id = '') {

        if ($user_id && $user_id != '') {
            $checkEmailAddress = $this->get_row("select * from " . $this->tableNames['user'] . " where 1=1 AND user_id != " . $user_id . " AND email = '" . $emailaddress . "'");
        } else {
            $checkEmailAddress = $this->get_row("select * from " . $this->tableNames['user'] . " where 1=1 AND email = '" . $emailaddress . "'");
        }

        if (count($checkEmailAddress) == 1) {
            return true;
        }
    }

    public function checkCompanyCodeExist($company_code, $user_id = '') {

        if ($user_id && $user_id != '') {
            $companyCode = $this->get_row("select * from " . $this->tableNames['user'] . " where 1=1 AND user_id != " . $user_id . " AND UPPER(company_code) = '" . strtoupper($company_code) . "'");
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

        if ($user_id && $user_id != '') {
            $numberGSTIN = $this->get_row("select * from " . $this->tableNames['client_gstin_detail'] . " where 1=1 AND added_by != " . $user_id . " AND UPPER(gstin_number) = '" . strtoupper($gstinnumber) . "'");
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

    public function getMasterItems($field = "*", $condition = '', $orderby = 'item_id asc', $limit = '', $group_by = '') {

        $query = "select " . $field . "  from " . $this->tableNames['item'] . " where 1=1 ";

        if ($condition != '') {
            $query .= " and " . $condition;
        }

        if ($group_by != '') {
            $query .= " group by " . $group_by;
        }

        $query .= " order by " . $orderby . " " . $limit;
        return $this->get_results($query);
    }

    public function getMasterUnits($field = "*", $condition = '', $orderby = 'unit_id asc', $limit = '', $group_by = '') {

        $query = "select " . $field . "  from " . $this->tableNames['unit'] . " where 1=1 ";

        if ($condition != '') {
            $query .= " and " . $condition;
        }

        if ($group_by != '') {
            $query .= " group by " . $group_by;
        }

        $query .= " order by " . $orderby . " " . $limit;
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

    protected function hitCurl($url, $parameters) {

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $parameters);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($curl);
        curl_close($curl);
        return $server_output;
    }

    protected function hitCurlwithHeader($url, $parameters, $header) {

        $curl = curl_init($url);
        /*curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $parameters);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($curl);*/

		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($curl, CURLOPT_POSTFIELDS, $parameters);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER,$header);
		$result = curl_exec($curl);

		curl_close($curl);
		return $result;
    }

    public function can_read($page_name) {
        if (isset($_SESSION['user_role'][$page_name]['can_read']) && $_SESSION['user_role'][$page_name]['can_read'] == '1') {
            return true;
        }
        return false;
    }

    public function can_create($page_name) {
        if (isset($_SESSION['user_role'][$page_name]['can_create']) && $_SESSION['user_role'][$page_name]['can_create'] == '1') {
            return true;
        }
        return false;
    }

    public function can_update($page_name) {
        if (isset($_SESSION['user_role'][$page_name]['can_update']) && $_SESSION['user_role'][$page_name]['can_update'] == '1') {
            return true;
        }
        return false;
    }

    public function can_delete($page_name) {
        if (isset($_SESSION['user_role'][$page_name]['can_delete']) && $_SESSION['user_role'][$page_name]['can_delete'] == '1') {
            return true;
        }
        return false;
    }

    /* country details by country code */

    public function getCountryDetailByCountryCode($country_code) {

        $data = $this->get_row("select * from " . $this->tableNames['country'] . " where UPPER(country_code) = '" . strtoupper($country_code) . "'");
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

    /* country details by country id */

    public function getCountryDetailByCountryId($country_id) {

        $data = $this->get_row("select * from " . $this->tableNames['country'] . " where id = " . $country_id);
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

    /* state details by state id */

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
    public function getStateDetailByStateNameCode($state_name_code) {

        $data = $this->get_row("select * from " . $this->tableNames['state'] . " where 1=1 AND (UPPER(state_code) = '" . strtoupper($state_name_code) . "' OR UPPER(state_name) = '" . strtoupper($state_name_code) . "')");
       
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

    /* vendor details by vendor name */

    public function getVenderDetailByVendername($vendor_name) {

        $data = $this->get_row("select * from " . $this->tableNames['vendor_type'] . " where vendor_name = " ."'$vendor_name'" );
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
    /* state details by state tin */

    public function getStateDetailByStateTin($state_tin) {

        $data = $this->get_row("select * from " . $this->tableNames['state'] . " where state_id = '" . $state_tin . "'");
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

	/* vendor details by vendor id */

    public function getVendorDetailByVendorId($vendor_id) {

        $data = $this->get_row("select * from " . $this->tableNames['vendor_type'] . " where vendor_id = " . $vendor_id);
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
        if (date('n') < 4) {
            $financial_year = date('Y', strtotime('-1 years')) . "-" . date('Y');
        }

        return $financial_year;
    }

	/* check reference number exist */
	public function checkReferenceNumberExist($referenceNumber, $clientId, $invoice_id = '') {

		$currentFinancialYear = $this->generateFinancialYear();
        if ($invoice_id && $invoice_id != '') {
            $checkReferenceNumber = $this->get_row("select * from " . $this->tableNames['client_invoice'] . " where 1=1 AND invoice_id != " . $invoice_id . " AND financial_year = '" . $currentFinancialYear . "' AND reference_number = '" . $referenceNumber . "' AND added_by = '" . $clientId . "'");
        } else {
            $checkReferenceNumber = $this->get_row("select * from " . $this->tableNames['client_invoice'] . " where 1=1 AND financial_year = '" . $currentFinancialYear . "' AND reference_number = '" . $referenceNumber . "' AND added_by = '" . $clientId . "'");
        }

        if(count($checkReferenceNumber) > 0) {
            return true;
        }
    }

	/* generate invoice number for client */
    public function generateInvoiceNumber($clientId) {

		$currentFinancialYear = $this->generateFinancialYear();
        $query = "select invoice_id  from " . $this->tableNames['client_invoice'] . " where 1=1 AND invoice_type IN('taxinvoice','exportinvoice','sezunitinvoice','deemedexportinvoice') AND financial_year = '" . $currentFinancialYear . "' AND added_by=" . $clientId;
        $invoices = $this->get_results($query);

        if (!empty($invoices)) {

            $nextInvoice = count($invoices) + 1;
            return "INV-" . str_pad($nextInvoice, 12, "0", STR_PAD_LEFT);
        } else {
            return "INV-000000000001";
        }
    }

    /* generate bill invoice number for client */

    public function generateBillInvoiceNumber($clientId) {

        $currentFinancialYear = $this->generateFinancialYear();
        $query = "select invoice_id  from " . $this->tableNames['client_invoice'] . " where 1=1 AND invoice_type = 'billofsupplyinvoice' AND financial_year = '" . $currentFinancialYear . "' AND added_by=" . $clientId;
        $invoices = $this->get_results($query);

        if (!empty($invoices)) {

            $nextInvoice = count($invoices) + 1;
            return "IBS-" . str_pad($nextInvoice, 12, "0", STR_PAD_LEFT);
        } else {
            return "IBS-000000000001";
        }
    }

    /* generate receipt voucher invoice number for client */

    public function generateRVInvoiceNumber($clientId) {

        $currentFinancialYear = $this->generateFinancialYear();
        $query = "select invoice_id  from " . $this->tableNames['client_invoice'] . " where 1=1 AND invoice_type = 'receiptvoucherinvoice' AND financial_year = '" . $currentFinancialYear . "' AND added_by=" . $clientId;
        $invoices = $this->get_results($query);

        if (!empty($invoices)) {

            $nextInvoice = count($invoices) + 1;
            return "IRV-" . str_pad($nextInvoice, 12, "0", STR_PAD_LEFT);
        } else {
            return "IRV-000000000001";
        }
    }

    /* generate refund voucher invoice number for client */

    public function generateRFInvoiceNumber($clientId) {

        $currentFinancialYear = $this->generateFinancialYear();
        $query = "select invoice_id  from " . $this->tableNames['client_invoice'] . " where 1=1 AND invoice_type = 'refundvoucherinvoice' AND financial_year = '" . $currentFinancialYear . "' AND added_by=" . $clientId;
        $invoices = $this->get_results($query);

        if (!empty($invoices)) {

            $nextInvoice = count($invoices) + 1;
            return "IRF-" . str_pad($nextInvoice, 12, "0", STR_PAD_LEFT);
        } else {
            return "IRF-000000000001";
        }
    }
	
	/* generate revised tax, creadit note and debit note tax invoice number for client */

    public function generateRTInvoiceNumber($clientId) {

        $currentFinancialYear = $this->generateFinancialYear();
        $query = "select invoice_id  from " . $this->tableNames['client_invoice'] . " where 1=1 AND invoice_type IN('revisedtaxinvoice','creditnote','debitnote') AND financial_year = '" . $currentFinancialYear . "' AND added_by=" . $clientId;
        $invoices = $this->get_results($query);

        if (!empty($invoices)) {

            $nextInvoice = count($invoices) + 1;
            return "IRT-" . str_pad($nextInvoice, 12, "0", STR_PAD_LEFT);
        } else {
            return "IRT-000000000001";
        }
    }

    /* generate payment voucher invoice number for client */

    public function generateDCInvoiceNumber($clientId) {

        $currentFinancialYear = $this->generateFinancialYear();
        $query = "select invoice_id  from " . $this->tableNames['client_invoice'] . " where 1=1 AND invoice_type = 'deliverychallaninvoice' AND financial_year = '" . $currentFinancialYear . "' AND added_by=" . $clientId;
        $invoices = $this->get_results($query);

        if (!empty($invoices)) {

            $nextInvoice = count($invoices) + 1;
            return "IDC-" . str_pad($nextInvoice, 12, "0", STR_PAD_LEFT);
        } else {
            return "IDC-000000000001";
        }
    }

	/* check purchase reference number exist */
	public function checkPurchaseReferenceNumberExist($referenceNumber, $clientId, $purchase_invoice_id = '') {

		$currentFinancialYear = $this->generateFinancialYear();
        if ($purchase_invoice_id && $purchase_invoice_id != '') {
            $checkReferenceNumber = $this->get_row("select * from " . $this->tableNames['client_purchase_invoice'] . " where 1=1 AND purchase_invoice_id != " . $purchase_invoice_id . " AND financial_year = '" . $currentFinancialYear . "' AND reference_number = '" . $referenceNumber . "' AND added_by = '" . $clientId . "'");
        } else {
            $checkReferenceNumber = $this->get_row("select * from " . $this->tableNames['client_purchase_invoice'] . " where 1=1 AND financial_year = '" . $currentFinancialYear . "' AND reference_number = '" . $referenceNumber . "' AND added_by = '" . $clientId . "'");
        }

        if(count($checkReferenceNumber) > 0) {
            return true;
        }
    }

    /* generate purchase invoice number for client */
    public function generatePurchaseInvoiceNumber($clientId) {

        $currentFinancialYear = $this->generateFinancialYear();
        $query = "select purchase_invoice_id  from " . $this->tableNames['client_purchase_invoice'] . " where 1=1 AND invoice_type IN('taxinvoice','importinvoice','sezunitinvoice','deemedimportinvoice') AND financial_year = '" . $currentFinancialYear . "' AND added_by=" . $clientId;
        $invoices = $this->get_results($query);

        if (!empty($invoices)) {

            $nextInvoice = count($invoices) + 1;
            return "PIN-" . str_pad($nextInvoice, 12, "0", STR_PAD_LEFT);
        } else {
            return "PIN-000000000001";
        }
    }
	
	/* generate purchase bill invoice number for client */
    public function generatePurchaseBillInvoiceNumber($clientId) {

        $currentFinancialYear = $this->generateFinancialYear();
        $query = "select purchase_invoice_id  from " . $this->tableNames['client_purchase_invoice'] . " where 1=1 AND invoice_type = 'billofsupplyinvoice' AND financial_year = '" . $currentFinancialYear . "' AND added_by=" . $clientId;
        $invoices = $this->get_results($query);

        if (!empty($invoices)) {

            $nextInvoice = count($invoices) + 1;
            return "PBS-" . str_pad($nextInvoice, 12, "0", STR_PAD_LEFT);
        } else {
            return "PBS-000000000001";
        }
    }

	public function generatePurchaseRVInvoiceNumber($clientId) {

        $currentFinancialYear = $this->generateFinancialYear();
        $query = "select purchase_invoice_id  from " . $this->tableNames['client_purchase_invoice'] . " where 1=1 AND invoice_type = 'receiptvoucherinvoice' AND financial_year = '" . $currentFinancialYear . "' AND added_by=" . $clientId;
        $invoices = $this->get_results($query);

        if (!empty($invoices)) {

            $nextInvoice = count($invoices) + 1;
            return "PRV-" . str_pad($nextInvoice, 12, "0", STR_PAD_LEFT);
        } else {
            return "PRV-000000000001";
        }
    }
	
	public function generatePurchaseRFInvoiceNumber($clientId) {

        $currentFinancialYear = $this->generateFinancialYear();
        $query = "select purchase_invoice_id  from " . $this->tableNames['client_purchase_invoice'] . " where 1=1 AND invoice_type = 'refundvoucherinvoice' AND financial_year = '" . $currentFinancialYear . "' AND added_by=" . $clientId;
        $invoices = $this->get_results($query);

        if (!empty($invoices)) {

            $nextInvoice = count($invoices) + 1;
            return "PRF-" . str_pad($nextInvoice, 12, "0", STR_PAD_LEFT);
        } else {
            return "PRF-000000000001";
        }
    }
	
	public function generatePurchaseRTInvoiceNumber($clientId) {

        $currentFinancialYear = $this->generateFinancialYear();
        $query = "select purchase_invoice_id  from " . $this->tableNames['client_purchase_invoice'] . " where 1=1 AND invoice_type IN('revisedtaxinvoice','creditnote','debitnote') AND financial_year = '" . $currentFinancialYear . "' AND added_by=" . $clientId;
        $invoices = $this->get_results($query);

        if (!empty($invoices)) {

            $nextInvoice = count($invoices) + 1;
            return "PRT-" . str_pad($nextInvoice, 12, "0", STR_PAD_LEFT);
        } else {
            return "PRT-000000000001";
        }
    }

    function convert_number_to_words($number) {

        //$hyphen      = '-';
        //$conjunction = ' and ';
        $hyphen = ' ';
        $conjunction = ' ';
        $separator = ' ';
        $negative = 'negative ';
        $decimal = ' and ';

        $dictionary = array(
            0 => 'zero',
            1 => 'one',
            2 => 'two',
            3 => 'three',
            4 => 'four',
            5 => 'five',
            6 => 'six',
            7 => 'seven',
            8 => 'eight',
            9 => 'nine',
            10 => 'ten',
            11 => 'eleven',
            12 => 'twelve',
            13 => 'thirteen',
            14 => 'fourteen',
            15 => 'fifteen',
            16 => 'sixteen',
            17 => 'seventeen',
            18 => 'eighteen',
            19 => 'nineteen',
            20 => 'twenty',
            30 => 'thirty',
            40 => 'fourty',
            50 => 'fifty',
            60 => 'sixty',
            70 => 'seventy',
            80 => 'eighty',
            90 => 'ninety',
            100 => 'hundred',
            1000 => 'thousand',
            100000 => 'lakh',
            10000000 => 'crore'
        );

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {

            // overflow
            /* trigger_error(
              'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
              E_USER_WARNING
              ); */
            return false;
        }

        if (strlen($number) > 13) {
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
                $tens = ((int) ($number / 10)) * 10;
                $units = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . $this->convert_number_to_words($remainder);
                }
                break;
            case $number < 100000:
                $thousands = ((int) ($number / 1000));
                $remainder = $number % 1000;

                $thousands = $this->convert_number_to_words($thousands);

                $string .= $thousands . ' ' . $dictionary[1000];
                if ($remainder) {
                    $string .= $separator . $this->convert_number_to_words($remainder);
                }
                break;
            case $number < 10000000:
                $lakhs = ((int) ($number / 100000));
                $remainder = $number % 100000;

                $lakhs = $this->convert_number_to_words($lakhs);

                $string = $lakhs . ' ' . $dictionary[100000];
                if ($remainder) {
                    $string .= $separator . $this->convert_number_to_words($remainder);
                }
                break;
            default:
                $crores = ((int) ($number / 10000000));
                //$remainder = $number % 10000000;
                $remainder = bcmod($number, 10000000);

                $crores = $this->convert_number_to_words($crores);
                $string = $crores . ' ' . $dictionary[10000000];
                if ($remainder) {
                    $string .= $separator . $this->convert_number_to_words($remainder);
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction) && $fraction != 0) {
            $string .= $decimal;

            if ($fraction < 10 && substr($fraction, 0, 1) != 0)
                $fraction*=10;

            $fraction = (int) $fraction;

            $string .= $this->convert_number_to_words($fraction);
            $string .= ' paise';
        }

        return $string;
    }

    final public function getClientReturn($id) {
        $return_id = $this->sanitize($id);
        $query = "select * from " . TAB_PREFIX . "return where client_id='" . $_SESSION['user_detail']['user_id'] . "' and return_id='" . $id . "'";
        return $this->get_results($query);
    }

    final public function getClientKyc() {
        $query = "select * from " . TAB_PREFIX . "client_kyc where added_by='" . $_SESSION['user_detail']['user_id'] . "'";
        return $this->get_results($query);
    }

    final public function generalGSTR1InvoiceList($returnmonth,$uploaded='0') {
        $query = "select invoice_id from " . TAB_PREFIX . 'client_invoice' . " where invoice_nature='salesinvoice' and (invoice_type='creditnote' or invoice_type='debitnote' or invoice_type='taxinvoice' or invoice_type='receiptvoucherinvoice' or  invoice_type='exportinvoice' or invoice_type='sezunitinvoice' or invoice_type='deemedexportinvoice') and added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0'  and invoice_date like '%" . $returnmonth . "%' and is_gstr1_uploaded='".$uploaded."'";
        return $this->get_results($query);
    }

    final public function generalGSTR2InvoiceList($returnmonth) {
        $dataCurrentUserArr = $this->getUserDetailsById($this->sanitize($_SESSION['user_detail']['user_id']));
        $query = "select invoice_id from " . TAB_PREFIX . 'client_invoice' . " where invoice_nature='salesinvoice' and billing_gstin_number='" . $dataCurrentUserArr['data']->kyc->gstin_number . "' and status='1' and is_canceled='0' and is_deleted='0'  and invoice_date like '%" . $returnmonth . "%' and is_gstr1_uploaded != '0' and is_gstr2_downloaded='0'";
        return $this->get_results($query);
    }

}
