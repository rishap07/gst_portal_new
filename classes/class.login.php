<?php
/*
 * Created by Ishwar Lal Ghiya
 * Dated: 2017-05-18
 * Created Purpose : For Login Purpose
 */

class login extends validation {

    public function __construct() {
        parent::__construct();
    }

    public function loginUser() {

        $dataArr['user_name'] = isset($_POST['login_username']) ? $_POST['login_username'] : '';
        $dataArr['password'] = isset($_POST['login_password']) ? $_POST['login_password'] : '';

        if (!$this->validateLogin($dataArr)) {
            return false;
        }

        $dataArr['api_code'] = 'XYZ';
        $dataArr['api_user'] = 'ABC';
        $val = '';

        foreach ($dataArr as $key => $value) {
            $val .= $value . "|";
        }

        $dataArr['secure_hash'] = strtoupper(md5($val));
        $dataArr['api_method'] = 'login';
        $url = PROJECT_URL . "/api.php";

        $server_output = $this->hitCurl($url, $dataArr);
        $server_output = json_decode($server_output);

        if (count($server_output) > 0) {

            if (isset($server_output->msg) && $server_output->msg == 'success' && $server_output->code == '2') {

                $_SESSION['user_detail']['user_id'] = $server_output->data->user[0]->user_id;
                $_SESSION['user_detail']['name'] = $server_output->data->user[0]->name;
                $_SESSION['user_detail']['username'] = $server_output->data->user[0]->username;
                $_SESSION['user_detail']['email'] = $server_output->data->user[0]->email;
                $_SESSION['user_detail']['user_group'] = $server_output->data->user[0]->user_group;
				$_SESSION["user_detail"]["profile_picture"] = $server_output->data->user[0]->profile_pics;

                for ($x = 0; $x < count($server_output->data->user_permission); $x++) {

                    $_SESSION['user_role'][$server_output->data->user_permission[$x]->role_page]['can_read'] = $server_output->data->user_permission[$x]->can_read;
                    $_SESSION['user_role'][$server_output->data->user_permission[$x]->role_page]['can_create'] = $server_output->data->user_permission[$x]->can_create;
                    $_SESSION['user_role'][$server_output->data->user_permission[$x]->role_page]['can_update'] = $server_output->data->user_permission[$x]->can_update;
                    $_SESSION['user_role'][$server_output->data->user_permission[$x]->role_page]['can_delete'] = $server_output->data->user_permission[$x]->can_delete;
                }

                if (isset($_POST['login_rememberme']) && $_POST['login_rememberme'] == 1) {

                    if ($this->setRememberMeCookie($server_output->data->user[0]->user_id)) {
                        return true;
                    } else {
                        $this->setError($this->validationMessage['cookie_err']);
                        return false;
                    }
                }
                return true;
            } else {
                $msg = explode('|', $server_output->msg);
                $this->setError($msg);
                return false;
            }
        } else {
            $this->setError($this->validationMessage['loginerror']);
            return false;
        }
    }

    public function validateLogin($dataArr) {

        $rules = array(
            'user_name' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:User Name',
            'password' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Password',
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

    public function registerUser() {

        $dataArr['mobilenumber'] = isset($_POST['mobilenumber']) ? $_POST['mobilenumber'] : '';
        $dataArr['username'] = isset($_POST['username']) ? $_POST['username'] : '';
        $dataArr['emailaddress'] = isset($_POST['emailaddress']) ? $_POST['emailaddress'] : '';
        $dataArr['password'] = isset($_POST['password']) ? $_POST['password'] : '';
        $dataArr['confirmpassword'] = isset($_POST['confirmpassword']) ? $_POST['confirmpassword'] : '';
        $dataArr['companyname'] = isset($_POST['companyname']) ? $_POST['companyname'] : '';
        $dataArr['firstname'] = isset($_POST['firstname']) ? $_POST['firstname'] : '';
        $dataArr['lastname'] = isset($_POST['lastname']) ? $_POST['lastname'] : '';
        $dataArr['gstin_number'] = isset($_POST['gstin_number']) ? $_POST['gstin_number'] : '';
        $dataArr['coupon'] = isset($_POST['coupon']) ? $_POST['coupon'] : '';

        if (!$this->validateRegister($dataArr)) {
            return false;
        }

        if ($dataArr['password'] != $_POST['confirmpassword']) {
            $this->setError($this->validationMessage['passwordnotmatched']);
            return false;
        }

        if ($this->checkUsernameExist($dataArr['username'])) {
            $this->setError($this->validationMessage['usernameexist']);
            return false;
        }

        if ($this->checkEmailAddressExist($dataArr['emailaddress'])) {
            $this->setError($this->validationMessage['emailexist']);
            return false;
        }

        $coupon_datas = array();
        if ($dataArr['coupon'] != '') {
            $coupon_data = $this->get_results("select * from " . TAB_PREFIX . "coupon where hidden='0' and status='1' and name='" . $dataArr['coupon'] . "'");
            if (!empty($coupon_data)) {
                $client_datas = $this->get_results("select * from " . TAB_PREFIX . "user where coupon='" . $dataArr['coupon'] . "'");
                if (isset($coupon_data[0]->coupon_uses) && count($client_datas) < $coupon_data[0]->coupon_uses) {
                    $coupon_datas = $coupon_data;
                } else {
                    $this->setError('Coupon Code Expired');
                    return false;
                }
            } else {
                $this->setError('Invalid Coupon Code');
                return false;
            }
        }

        /* create insert array */
        $dataInsertArray['username'] = $dataArr['username'];
        $dataInsertArray['email'] = $dataArr['emailaddress'];
        $dataInsertArray['first_name'] = $dataArr['firstname'];
        $dataInsertArray['last_name'] = $dataArr['lastname'];
        $dataInsertArray['phone_number'] = $dataArr['mobilenumber'];
        $dataInsertArray['company_name'] = $dataArr['companyname'];
        $dataInsertArray['gstin_number'] = $dataArr['gstin_number'];
        $dataInsertArray['subscriber_code'] = $this->generateSubscriberRandomCode(6, $this->tableNames['user'], "subscriber_code");
        $dataInsertArray['coupon'] = $dataArr['coupon'];

        $dataInsertArray['password'] = $this->password_encrypt($dataArr['password']); /* encrypt password */
        $dataInsertArray['added_by'] = '22';
        $dataInsertArray['added_date'] = date('Y-m-d H:i:s');
        $dataInsertArray['email_code'] = md5(uniqid(rand(), 1));

        if ($this->insert($this->tableNames['user'], $dataInsertArray)) {

            /* get user data by its id */
            $userData = $this->getUserDetailsById($this->getInsertID());

            $_SESSION['user_detail']['user_id'] = $userData['data']->user_id;
            $_SESSION['user_detail']['username'] = $userData['data']->username;
            $_SESSION['user_detail']['email'] = $userData['data']->email;
            $_SESSION['user_detail']['phone_number'] = $userData['data']->phone_number;
            $_SESSION['user_detail']['name'] = $userData['data']->name;
            $_SESSION['user_detail']['user_group'] = $userData['data']->user_group;
            /* assign user permissions */
            $rolequery = "select b.role_page,a.can_read,a.can_create,a.can_update,a.can_delete from " . $this->tableNames['user_role_permission'] . " a left join " . $this->tableNames['user_role'] . " b on a.role_id=b.user_role_id where a.group_id='" . $userData['data']->user_group . "' and a.is_deleted='0' and a.status='1'";
            $userPermission = $this->get_results($rolequery);

            for ($x = 0; $x < count($userPermission); $x++) {

                $_SESSION['user_role'][$userPermission[$x]->role_page]['can_read'] = $userPermission[$x]->can_read;
                $_SESSION['user_role'][$userPermission[$x]->role_page]['can_create'] = $userPermission[$x]->can_create;
                $_SESSION['user_role'][$userPermission[$x]->role_page]['can_update'] = $userPermission[$x]->can_update;
                $_SESSION['user_role'][$userPermission[$x]->role_page]['can_delete'] = $userPermission[$x]->can_delete;
            }
            /* code for send email to user  */

            $this->sendRegisteremail("registration", 'register by ' . $userData['data']->name . '', $dataArr['emailaddress'], 'noreply@gstkeeper.com', "lokesh.chotiya@cyfuture.com,rishap.gandhi@cyfuture.com", "New Subscriber Registration", $dataInsertArray['email_code']);

            if (isset($_POST['rememberme']) && $_POST['rememberme'] == 1) {

                if ($this->setRememberMeCookie($userData['data']->user_id)) {
                    return true;
                } else {
                    return false;
                }
            }

            if (!isset($_SESSION['plan_id']) || $_SESSION['plan_id'] == '') {
                return true;
            } else {
                $dataGo4host['user_id'] = $userData['data']->user_id;
                $dataGo4host['username'] = $userData['data']->username;
                $dataGo4host['email'] = $userData['data']->email;
                $dataGo4host['phone_number'] = $userData['data']->phone_number;

                $dataGo4host['name'] = $userData['data']->name;
                $dataGo4host['user_group'] = $userData['data']->user_group;
                $dataGo4host['plan_id'] = $_SESSION['plan_id'];
                unset($_SESSION['plan_id']);
                $dataPlan = $this->get_results("select * from " . TAB_PREFIX . "subscriber_plan where id='" . $this->sanitize($dataGo4host['plan_id']) . "' and is_deleted='0' and status='1'");
                $dataGo4host['price'] = isset($dataPlan[0]->plan_price) ? $dataPlan[0]->plan_price : '9990';
                $price_data = $dataGo4host['price'];

                if(empty($dataPlan))
                {
                    $this->setError('Invalid Plan Selection');
                    return false;
                }
                                
                if (!empty($coupon_datas)) {
                    if (isset($coupon_datas[0]->type) && $coupon_datas[0]->type == '0') {
                        $dataGo4host['price'] = $price_data - $coupon_datas[0]->coupon_value;
                        $dataGo4host['price'] = $dataGo4host['price'] + ($dataGo4host['price'] * 0.18);
                    } else if (isset($coupon_datas[0]->type) && $coupon_datas[0]->type == '1') {
                        $dataGo4host['price'] = $dataGo4host['price'] - round((($price_data * $coupon_datas[0]->coupon_value) / (100 + $coupon_datas[0]->coupon_value)), 2, PHP_ROUND_HALF_DOWN);
                        $dataGo4host['price'] = $dataGo4host['price'] + ($dataGo4host['price'] * 0.18);
                        $dataGo4host['price'] = round($dataGo4host['price'], 2, PHP_ROUND_HALF_DOWN);
                    }
                } else {
                    $dataGo4host['price'] = $dataGo4host['price'] + ($price_data * 0.18);
                }

                //Insert data in payment log
                $ref_id = date('siHmdy');
                $cur_date = date('Y-m-d H:i:s');
                $dataGo4host['ref_id'] = $ref_id;

                $dataPlan1['plan_id'] = $dataGo4host['plan_id'];
                $dataPlan1['plan_price'] = $dataPlan[0]->plan_price;
                $dataPlan1['plan_details'] = json_encode($dataPlan);
                $dataPlan1['no_of_client'] = $dataPlan[0]->no_of_client;
                $dataPlan1['company_no'] = $dataPlan[0]->company_no;
                $dataPlan1['pan_num'] = $dataPlan[0]->pan_num;

                $dataPlan1['plan_start_date'] = date('Y-m-d');
                $dataPlan1['plan_due_date'] = date('Y') . '-03-31';
                $dataPlan1['status'] = '1';
                $dataPlan1['payment_method'] = 'banktransfer';
                $dataPlan1['payment_status'] = '0';
                $dataPlan1['ref_id'] = $ref_id;
                $dataPlan1['added_by'] = $dataGo4host['user_id'];
                $dataPlan1['added_date'] = date('Y-m-d H:i:s');


                $this->insert(TAB_PREFIX . "user_subscribed_plan", $dataPlan1);
                $this->insert(TAB_PREFIX . 'payment_log', array(
                    'ref_id' => $ref_id,
                    'datetime' => $cur_date,
                    'status' => '0'
                ));

                $this->redirectGohost($dataGo4host);
                return true;
            }
        } else {
            $this->setError($this->validationMessage['failed']);
            return false;
        }
    }

    public function redirectGohost($data) {
        ?>

        <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <head>
            <script type="text/javascript">
                function submitToPaypal()
                {
                    document.Form2.action = '<?php echo PROJECT_URL; ?>/go4hosting/keeper_payment.php';
                    document.Form2.submit();
                }
            </script>
        </head>
        <body onload="submitToPaypal();">
            <form action="<?php echo PROJECT_URL; ?>/go4hosting/keeper_payment.php" name="Form2" method="POST" id="Form2"> 
                <input type="hidden" value="0" name="channel"/>
                <input type="hidden" value="25039" name="account_id"/>
                <input type="hidden" value="<?php echo $data['ref_id']; ?>" name="reference_no"/>
                <input type="hidden" value="<?php echo $data['price']; ?>" name="amount"/>
                <input type="hidden" value="INR" name="currency"/>
                <input type="hidden" value="INR" name="display_currency"/>
                <input type="hidden" value="1" name="display_currency_rates"/>
                <input type="hidden" value="Payment information from GST" name="description"/>
                <input type="hidden" value="<?php echo PROJECT_URL . "/go4hosting/keeper_response.php"; ?>" name="return_url"/>
                <input type="hidden" value="LIVE" name="mode"/>
                <input type="hidden" value="<?php echo $data['username']; ?>" name="name"/>
                <input type="hidden" value="Delhi" name="address"/>
                <input type="hidden" value="Delhi" name="city"/>
                <input type="hidden" value="110010" name="postal_code"/>
                <input type="hidden" value="IND" name="country"/>
                <input type="hidden" value="<?php echo $data['email']; ?>" name="email"/>
                <input type="hidden" value="<?php echo $data['phone_number']; ?>" name="phone"/>
            </form>
        </body>
        </html>
        <?php
        exit();
    }

    public function forgotPassword() {

        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $dataArr["emailaddress"] = $email;
        if (!$this->validateEmail($dataArr)) {
            return false;
        }
        $sql = "select * from " . TAB_PREFIX . "user where email='" . $email . "'";

        $data = $this->get_results($sql);
        if (count($data) > 0) {

            $userid = $data[0]->user_id;
            $name = $data[0]->first_name;
            $sql_forgot = "select * from " . TAB_PREFIX . "forgot_email where userid='" . $userid . "' order by id desc limit 0,1";
            $emaildata = $this->get_results($sql_forgot);

            if (count($emaildata) > 0) {
                $to_time = $emaildata[0]->code_senttime;
                $to_time = strtotime($to_time);
                $from_time = strtotime(date('Y-m-d H:i:s'));
                $time_diff = round(abs($to_time - $from_time) / 60, 2);
                if ($time_diff > 15) {

                    if ($this->sendMail('Email Verify', 'User ID : ' . $userid . ' email forgotPassword', $data[0]->email, 'noreply@gstkeeper.com', '', 'rishap.gandhi@cyfuture.com,sheetalprasad95@gmail.com', '', 'Password Reset', $this->getEmailVerifyMailBody($userid, $name))) {

                        $this->setSuccess('Kindly check your email for verification');
                        return true;
                    } else {
                        $this->setError('Try again some issue in sending in email.');
                        return false;
                    }
                } else {
                    // $this->setError($this->validationMessage['failed']);
                    //return false;
                    $this->setError('Email is already sent please check your mailbox or try again after 15 minutes');
                    return false;
                }
            } else {
                if ($this->sendMail('Email Verify', 'User ID : ' . $userid . ' email forgotPassword', $data[0]->email, 'noreply@gstkeeper.com', '', 'rishap.gandhi@cyfuture.com,sheetalprasad95@gmail.com', '', 'Password Reset', $this->getEmailVerifyMailBody($userid, $name))) {
                    $this->setSuccess('Kindly check your email for verification');
                    return true;
                } else {
                    $this->setError('Try again some issue in sending in email.');
                    return false;
                }
            }
        } else {
            $this->setError('Please check your email address it does not exists.');
            return false;
        }
    }

    public function updatePassword() {
        $userid;

        $userid = isset($_SESSION['user_detail']['passkey']) ? $_SESSION['user_detail']['passkey'] : '';
        $token = isset($_SESSION['user_detail']['token']) ? $_SESSION['user_detail']['token'] : '';

        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $dataArr["password"] = $password;
        $confirm_password = isset($_POST['confirmpassword']) ? $_POST['confirmpassword'] : '';
        if (!$this->validatePassword($dataArr)) {
            return false;
        }
        if ($dataArr['password'] != $_POST['confirmpassword']) {
            $this->setError($this->validationMessage['passwordnotmatched']);
            return false;
        }

        if ($this->update(TAB_PREFIX . "user", array('password' => md5($password)), array('forgotemail_code' => $token))) {
            $this->setSuccess('Kindly check your email for verification.');
            return true;
        }
    }

    public function getToken() {
        $token = md5(uniqid(rand(), 1));
        $result = $this->get_results("select * from " . TAB_PREFIX . "user where forgotemail_code='" . $token . "'");
        if (count($result) > 0) {
            $token = $this->getToken();
        } else {
            return $token;
        }
    }
    
    private function getEmailVerifyMailBody($userid, $name) {
        $token = $this->getToken();
        //$data = '<a href="'.PROJECT_URL.'/verify_forgot_password.php?verifyForgot=' . $token . '&passkey='.base64_encode($userid).'">Click here</a>  or copy the below url and paste on browser to verify your email';
       $mpdfHtml='';
		$mpdfHtml = $mpdfHtml.'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
        $mpdfHtml = $mpdfHtml.'<html xmlns="http://www.w3.org/1999/xhtml">';
        $mpdfHtml = $mpdfHtml.'<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>gst</title></head><body>';
        $mpdfHtml = $mpdfHtml.'<div style="width:720px; margin:auto; border:solid #CCC 1px;">';
        $mpdfHtml = $mpdfHtml.'<table cellpadding="0" cellspacing="0" width="100%">';
       $mpdfHtml = $mpdfHtml.'<tbody><tr>  <td height="auto"><table width="720" cellpadding="0" cellspacing="0" bgcolor="#fff" style="font-family:Arial, Helvetica, sans-serif;margin:0px auto;">';
       $mpdfHtml = $mpdfHtml.' <tbody>  <tr> <td width="30"></td>    <td><table width="100%" cellpadding="0" cellspacing="0">    <tbody>';
       $mpdfHtml = $mpdfHtml.'<tr><td align="left" valign="middle" height="80"><a target="_blank" href="https://www.gstkeeper.com/"><img src="https://gstkeeper.com/newsletter/4july2017/gst-logo.png" alt="" border="0"></a></td>';
       $mpdfHtml = $mpdfHtml.' <td align="right" valign="middle" style="font-size:18px;color:#cf3502;font-family:Arial, Helvetica, sans-serif;" height="80px"> <span><img src="https://gstkeeper.com/newsletter/6july2017/phone-icon.jpg" alt=""></span>1-800-212-2022<br>';     
	   $mpdfHtml = $mpdfHtml.'<span><img src="https://gstkeeper.com/newsletter/6july2017/mail-icon.jpg" alt=""></span><a href="mailto:contact@gstkeeper.com" style="font-size:14px;color:#cf3502;text-decoration:none;"> contact@gstkeeper.com</a></td> </tr> </tbody> </table></td>';
	$mpdfHtml = $mpdfHtml.' <td width="30"></td> </tr> <tr> <td width="30"></td>    <td><table width="100%" cellpadding="0" cellspacing="0"><tbody>';          
		$mpdfHtml = $mpdfHtml.'<tr>  <td align="center" valign="middle"><img src="https://www.gstkeeper.com/newsletter/7july/images/banner.jpg" alt="" border="0" width="700"></td> </tr>';
		$mpdfHtml = $mpdfHtml.' </tbody> </table></td> <td width="30"></td>   </tr>';           
	  $mpdfHtml = $mpdfHtml.'<tr>  <td width="30"  ></td> <td><table width="100%" cellpadding="0" cellspacing="0">  <tbody>';          
	$mpdfHtml = $mpdfHtml.' <tr><td height="319" align="center" valign="top"><table width="100%" cellpadding="0px" cellspacing="0" > <tbody>';        
	 $mpdfHtml = $mpdfHtml.' <tr> <td width="13"></td>  <td width="350"  style="font-size:15px;color:#090909;font-family:Arial, Helvetica, sans-serif; padding-top:10px; "></td><td width="20"></td>  </tr>';
	 $mpdfHtml = $mpdfHtml.' <tr>  <td colspan="3" height="10"></td>  </tr>';
	 $mpdfHtml = $mpdfHtml.'  <tr><td width="13"></td> <td height="140" align="justify"  valign="top" style="font-size:13px;color:#191919;font-family:Arial, Helvetica, sans-serif; line-height:18px; "> <p><strong>Dear ' . $name . '</strong></p>'; 
	 $mpdfHtml = $mpdfHtml.'  <p> We have received your request for password change.<br>Please click the link below to reset your password:</p><p><a target="_blank" style="padding:2px 5px;background:#cf3502;color:#fff;text-decoration:none;font-size:20px;" href="' . PROJECT_URL . '/verify_forgot_password.php?verifyForgot=' . $token . '&passkey=' . md5($userid) . '" >Click here</a> </p>';                           
	 $mpdfHtml = $mpdfHtml.'  <p>Note: For security reasons, it is advisable to change the password immediately after the first login.</p>';				 
     $mpdfHtml = $mpdfHtml.'<p>Thank You for using our services.</p><p>If you have any queries, please mail us at contact@gstkeeper.com for further assistance.</p><br><br>Thanks!<br>The GST Keeper Team</p>';
     $mpdfHtml = $mpdfHtml.'  </td><td width="20"></td>  </tr> </tbody>   </table></td></tr>';
     $mpdfHtml = $mpdfHtml.' </tbody>  </table></td>    </tr>';      
     $mpdfHtml = $mpdfHtml.' <!--<tr><td  align="center" height="29"><img src="http://cdn.go4hosting.in/mailer/12-oct/resources-img.jpg"  alt=""    /></td> </tr>-->';
     $mpdfHtml = $mpdfHtml.'  <tr>  <td colspan="3" height="15"></td> </tr>';     
     $mpdfHtml = $mpdfHtml.'  <tr>   <td width="30"></td> <td><table width="98%" align="right" cellpadding="0" cellspacing="0" style="background-color:#f1f1f1; height:80px; padding:10px;">    <tbody>     <tr>';
     $mpdfHtml = $mpdfHtml.'<td width="47%"><a href="http://www.cyfuture.com/" target="_blank"><img src="https://gstkeeper.com/newsletter/4july2017/cyfuture-logo.png" alt="" border="0" /></a></td>';
     $mpdfHtml = $mpdfHtml.'<td width="53%" align="right"><table width="100%" cellpadding="0" cellspacing="0">   <tbody>';
     $mpdfHtml = $mpdfHtml.' <tr>  <td width="20" height="50"></td>  <td valign="middle" style="font-size:14px;color:#333;font-family:Arial, Helvetica, sans-serif;"><strong><i>Connect with us</i></strong></td>';           
                    
     $mpdfHtml = $mpdfHtml.'<td valign="middle" width="50" align="center"><a target="_blank" href="https://www.facebook.com/GST-Keeper-632910016898628/"><img src="https://gstkeeper.com/newsletter/4july2017/fb-icon.png" alt="" border="0" /></a></td>';
     $mpdfHtml = $mpdfHtml.'<td valign="middle" width="40" align="left"><a target="_blank" href="https://plus.google.com/101841021110541536034"><img src="https://gstkeeper.com/newsletter/4july2017/g+-icon.png" alt="" border="0" /></a></td>';
     $mpdfHtml = $mpdfHtml.'<td valign="middle" width="40" align="left"><a target="_blank" href="https://twitter.com/GstKeeper"><img src="https://gstkeeper.com/newsletter/4july2017/twit-icon.png" alt="" border="0" /></a></td>';
     $mpdfHtml = $mpdfHtml.'<td valign="middle" width="40" align="left"><a target="_blank" href="https://www.youtube.com/channel/UCsDdNFR8kJ3YVWpEvBrFeSA"><img src="https://gstkeeper.com/newsletter/4july2017/utube-icon.png" alt="" border="0" /></a></td>';
     $mpdfHtml = $mpdfHtml.'<td valign="middle" width="40" align="left"><a target="_blank" href="https://www.linkedin.com/company/gst-keeper"><img src="https://gstkeeper.com/newsletter/4july2017/in-icon.jpg" alt="" border="0" /></a></td>';
     $mpdfHtml = $mpdfHtml.'</tr> </tbody>  </table></td></tr> </tbody> </table></td><td width="30"></td>  </tr>';
      $mpdfHtml = $mpdfHtml.'  <tr><td width="30"></td> <td height="76" valign="middle"><table width="100%" cellpadding="0" cellspacing="0"><tbody>';       
      $mpdfHtml = $mpdfHtml.' <tr><td width="20"></td><td align="center"><font style="font-size:14px;color:#444;font-family:Arial, Helvetica, sans-serif;">Cyfuture ( India ) Pvt. Ltd.</font><br>';   
       $mpdfHtml = $mpdfHtml.'<font style="font-size:12px;color:#444;font-family:Arial, Helvetica, sans-serif;">Plot No. 197-198 Noida Special Economic Zone (NSEZ) Phase II, Noida 201 305</font><br>';     
       $mpdfHtml = $mpdfHtml.' <font style="font-size:12px;color:#444;font-family:Arial, Helvetica, sans-serif;">E-mail: <a style="text-decoration:none;color:#3194d5;" href="mailto:contact@gstkeeper.com">contact@gstkeeper.com</a></font><br></td>';      
        $mpdfHtml = $mpdfHtml.' <td width="15" align="left">&nbsp;</td> </tr></tbody>  </table></td></tbody></table></td></tr></tbody></table></div></body></html>'; 
        $this->update(TAB_PREFIX . "user", array('forgotemail_code' => $token, 'forgotemail_verify' => 0), array('user_id' => $userid));
        $dataInsertArray['userid'] = $userid;
        $dataInsertArray['code'] = $token;
        $dataInsertArray['code_senttime'] = date('Y-m-d H:i:s');


        if ($this->insert($this->tableNames['forgot_email'], $dataInsertArray)) {
            $this->setSuccess('Kindly check your email for verification.');
            return $mpdfHtml;
        }
    }
	
   

    public function sendRegisteremail($module, $module_message, $to_send, $from_send, $bcc, $subject, $token) {
        $dataInsertArray['module'] = $module;
        $dataInsertArray['module_message'] = $module_message;
        $dataInsertArray['to_send'] = $to_send;
        $dataInsertArray['from_send'] = $from_send;
        $dataInsertArray['bcc'] = $bcc;
        $dataInsertArray['subject'] = $subject;
        $dataInsertArray['status'] = '0';
        $dataInsertArray['body'] = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
                        <span><img src="https://gstkeeper.com/newsletter/6july2017/mail-icon.jpg" alt=""></span><a href="mailto:contact@gstkeeper.com" style="font-size:14px;color:#cf3502;text-decoration:none;"> contact@gstkeeper.com</a></td>
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
                         <tr> <td width="13"></td> <td width="350" style="font-size:15px;color:#090909;font-family:Arial, Helvetica, sans-serif; padding-top:10px; "><strong>Welcome Aboard!! </strong></td> <td width="20"></td> </tr>
                        <tr>
                          <td width="13"></td>
                          <td width="350"  style="font-size:15px;color:#090909;font-family:Arial, Helvetica, sans-serif; padding-top:10px; "><strong>Hi ' . $_SESSION['user_detail']['name'] . '
</strong></td>
                          <td width="20"></td>
                          </tr>
                        <tr>
                          <td colspan="3" height="10"></td>
                          </tr>
                        <tr>
                          <td width="13"></td>
                          <td height="140" align="justify"  valign="top" style="font-size:13px;color:#191919;font-family:Arial, Helvetica, sans-serif; line-height:18px; "><p>
                            Thanks for subscribing into GST Keeper! We look forward to help you by offering our<br> best-in-class GST compliance software that not only enhances your productivity but also<br> improves the overall business agility. </p>
                            <p>
Our main objective is to remove the complications associated with GST tax compliance <br> and provide you an easier platform to deal with GST credits, returns and other related issues.</p>
<p>
If you have any suggestions regarding our GST Keeper software, we would love to hear<br> from you. For your valuable suggestions,please <a href="https://www.gstkeeper.com/contact-us.php">click here</a></p>
<p>
Here is your Login information details <br>
User Name: ' . $_SESSION['user_detail']['username'] . '<br>
Please click the link below to verify your account:<br><br>
<a href="' . PROJECT_URL . '/?page=dashboard&verifyemail=' . $token . '&passkey=' . md5($_SESSION['user_detail']['user_id']) . '" style="padding:2px 5px;background:#cf3502;color:#fff;text-decoration:none;font-size:20px;" target="_blank">Verify</a></p>
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




        if ($this->insert($this->tableNames['email'], $dataInsertArray)) {
            return true;
        } else {
            return false;
        }
    }

    public function validatePassword($dataArr) {

        $rules = array(
            'password' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/||min:8||max:20|#|lable_name:Password'
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

    public function validateEmail($dataArr) {

        $rules = array(
            'emailaddress' => 'required||email|#|lable_name:Email'
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

    public function validateRegister($dataArr) {

        $rules = array(
            'username' => 'required||pattern:/^' . $this->validateType['username'] . '+$/|#|lable_name:User Name',
            'firstname' => 'required||pattern:/^' . $this->validateType['firstname'] . '+$/|#|lable_name:First Name',
            'lastname' => 'required||pattern:/^' . $this->validateType['lastname'] . '+$/|#|lable_name:Last Name',
            'companyname' => 'pattern:/^[' . $this->validateType['content'] . ']+$/|#|lable_name:Company Name',
            'emailaddress' => 'required||email|#|lable_name:Email',
            'mobilenumber' => 'required||pattern:/^' . $this->validateType['mobilenumber'] . '+$/|#|lable_name:Mobile Number',
            'password' => 'required||pattern:/^[' . $this->validateType['content'] . ']+$/||min:8||max:20|#|lable_name:Password'
        );

        if (array_key_exists("gstin_number", $dataArr)) {
            $rules['gstin_number'] = 'pattern:/^' . $this->validateType['gstinnumber'] . '+$/||min:15||max:15|#|lable_name:GSTIN';
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

}
?>