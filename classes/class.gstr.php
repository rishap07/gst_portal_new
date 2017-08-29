<?php
/*
 * 
 *  Developed By        :   Sheetal
 *  Description         :   R N D For API GSTR1 encryprtion n decryption 
 *  Date Created        :   July 18, 2017
 *  Last Modified By    :   Monika Deswal 
 *  Last Modification   :   GSTR1 API encryprtion n decryption implmentation
 * 
*/

final class gstr extends validation {

    public $ciphertext ='';
    public $hexcode ='';
    public $public_key ='';
    public $app_key = '';
    public $otp_ency = '';
    public $hKey='';
    public $error_msg = array();
    function __construct() {
        parent::__construct();
    }
    
    final public function RandomToken($length){
        if(!isset($length) || intval($length) <= 8 ){
          $length = 16;
        }
        if (function_exists('random_bytes')) {
            return bin2hex(random_bytes($length));
        }
        if (function_exists('mcrypt_create_iv')) {
            return bin2hex(mcrypt_create_iv($length, MCRYPT_DEV_URANDOM));
        } 
        if (function_exists('openssl_random_pseudo_bytes')) {
            return bin2hex(openssl_random_pseudo_bytes($length));
        }
    }
    final public function RandomKey($length){
        if(!isset($length) || intval($length) <= 8 ){
          $length = 16;
        }
        if (function_exists('random_bytes')) {
            return bin2hex(random_bytes($length));
        }
        if (function_exists('mcrypt_create_iv')) {
            return bin2hex(mcrypt_create_iv($length, MCRYPT_DEV_URANDOM));
        } 
        if (function_exists('openssl_random_pseudo_bytes')) {
            return bin2hex(openssl_random_pseudo_bytes($length));
        }
    }
    
    public function keyGeneration()
    {
        if(empty($this->checkUserGstr1Exists('hexcode'))) {
            $inputToken = $this->RandomToken(16);
            $keyhash = $this->RandomKey(32);
            $key = pack('H*',$keyhash);

            # create a random IV to use with ECB encoding
            $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
            $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);

            # creates a cipher text compatible with AES (Rijndael block size = 128)
            # to keep the text confidential 
            # only suitable for encoded input that never ends with value 00h
            # (because of default zero padding)
            $this->ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key,
                                         $inputToken, MCRYPT_MODE_ECB, $iv);

            # creating hexcode for otp emcryption by app-key
            $this->hexcode= bin2hex($this->ciphertext);
            $_SESSION['hexcode'] = $this->hexcode; 
        }
        else {
            $_SESSION['hexcode'] = $this->hexcode = $this->checkUserGstr1Exists('hexcode');
        }
    }

    public function getCertificateKey()
    {
        
        if(empty($this->checkUserGstr1Exists('app_key'))) {
            $pem_private_key = file_get_contents(PROJECT_ROOT.'/modules/api/GSTN_Public_Key/GSTN_private.pem');
            $private_key = openssl_pkey_get_private($pem_private_key);
            $pem_public_key = openssl_pkey_get_details($private_key)['key'];
            $this->public_key = openssl_pkey_get_public($pem_public_key);
            
            $encrypted="";
            openssl_public_encrypt($this->ciphertext , $encrypted, $this->public_key);

            $this->app_key=base64_encode($encrypted);  
            $_SESSION['app_key']=$this->app_key;
        }
        else {
            $_SESSION['app_key'] = $this->app_key = $this->checkUserGstr1Exists('app_key');
        }
    }
    
    public function aes256_ecb_encrypt($key, $data, $iv) {
        if(32 !== strlen($key)) $key = hash('SHA256', $key, true);
        if(16 !== strlen($iv)) $iv = hash('MD5', $iv, true);
        $padding = 16 - (strlen($data) % 16);
        $data .= str_repeat(chr($padding), $padding);
        return mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_ECB, $iv);
    }
    
    public function getOTPEncypt($otp)
    {
        $key = pack('H*', $_SESSION['hexcode']);
        //echo 'key: '. $key ;
        //$otp = '575757';
        $this->hKey=$key;
        $otp_code = $otp;
        $otp_encode =utf8_encode($otp_code);
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $ciphertext_enc= $this->aes256_ecb_encrypt($key,$otp_encode,$iv);
        return $ciphertext_enc;
        //return base64_encode($ciphertext_enc);
    }

    public function submitOTP()
    {
        $dataArr['otp'] = isset($_POST['otp']) ? $_POST['otp'] : '';
        if($dataArr['otp']=='')
        {
            $this->setError("OTP is mandatory");
            return false;
        }
        $encypt_otp = $this->getOTPEncypt($dataArr['otp']);
        $this->authenticateToken($encypt_otp);
    }
    /*public function requestOTP()
    {
        $this->keyGeneration();
        $this->getCertificateKey();

        $client_secret = 'fa6f03446473400fa21240241affe2a5';
        $clientid = 'l7xx2909cd95daee418b8118e070b6b24dd6';
        $ip_usr = '49.50.73.109';
        $state_cd='27';
        $txn='TXN789123456789';
        $username='Cyfuture.MH.TP.1';
        $action='OTPREQUEST';

        $data = array("username" => $username, "action" => $action, "app_key" => $_SESSION['app_key']);

        $data_string = json_encode($data);
        $header= array(
            'client-secret: '.$client_secret.'',
            'Content-Length: ' . strlen($data_string),
                'clientid: '.$clientid.'',
                'Content-Type: application/json',
                'ip-usr: '.$ip_usr.'',
                'state-cd: '.$state_cd.'',
                'txn: '.$txn.'',
                'karvyclientid: '.$clientid.'',
                'karvyclient-secret: '.$client_secret.'');

        $url='http://gsp.karvygst.com/v0.3/authenticate';

        $result_data= $this->hitCurlwithHeader($url,$data_string,$header);
        $result_data = json_decode($result_data);
        var_dump($result_data);
        if(isset($result_data->status_cd) && $result_data->status_cd=='1')
        {
            $this->setSuccess("OTP Send to your registered number");
            return true;
        }
        $this->setError("Unable to send OTP");
        return false;
    }
    */
    
    public function authenticateToken()
    {
        $this->keyGeneration();
        $this->getCertificateKey();
        // otp encyption
        $ciphertext_enc = $this->getOTPEncypt('575757');
        // otp encyption
        
        $otp = base64_encode($ciphertext_enc);
        $username = $this->username();
        $data = array("username" => $username, "action" => 'AUTHTOKEN', "app_key" => $_SESSION['app_key'], "otp" =>$otp);
        $data_string = json_encode($data);
        
        //Start code for create header
        $header_array = array(
            'Content-Length: ' . strlen($data_string).'',
        );
        $header = $this->header($header_array);
        //End code for create header

        $url=  'http://devapi.gstsystem.co.in/taxpayerapi/v0.2/authenticate';
        $result_data= $this->hitUrl($url,$data_string,$header);

        $data = json_decode($result_data);

        if(empty($this->checkUserGstr1Exists('app_key')) && empty($this->checkUserGstr1Exists('auth_token'))) {
            if(isset($data->status_cd) && $data->status_cd=='1')
            {
                $session_key = $data->sek;
                $decrypt_sess_key = openssl_decrypt(base64_decode($session_key),"aes-256-ecb",$this->hKey, OPENSSL_RAW_DATA);
                $_SESSION['decrypt_sess_key']=$decrypt_sess_key;
                $_SESSION['auth_token']=$data->auth_token;
                $_SESSION['auth_date'] = date('Y-m-d h:i:s');

                // save it to user
                //$this->pr($_SESSION); 

                $savedata = array();
                $savedata['otp'] = '575757';
                $savedata['hexcode'] = $_SESSION['hexcode'];
                $savedata['app_key'] = $_SESSION['app_key'];
                $savedata['auth_token'] = $_SESSION['auth_token'];
                $savedata['decrypt_sess_key'] = $session_key;
                $savedata['added_date'] = $_SESSION['auth_date'];
                $this->save_user_gstr1($savedata);

                return $data;
            }
            else {
                $this->setError("Unable to proccess");
                return false;
            }
        }
        else {
            $session_key  = $this->checkUserGstr1Exists('decrypt_sess_key');
            $decrypt_sess_key = openssl_decrypt(base64_decode($session_key),"aes-256-ecb",$this->hKey, OPENSSL_RAW_DATA);
            $_SESSION['decrypt_sess_key']= $decrypt_sess_key;
            $_SESSION['auth_token'] =  $this->checkUserGstr1Exists('auth_token');
            $_SESSION['auth_date'] =  $this->checkUserGstr1Exists('added_date');

            return $data;
        }   
            
    }
    
    public function returnSave($dataArr,$returnmonth,$jstr) {
        $this->authenticateToken();
        $msg = $return_encode = '';
        $error = 1;
        $response = array();
        
        $json_data = json_encode($dataArr);

        //echo $json_data;

        $encodejson=base64_encode(openssl_encrypt(base64_encode($json_data),"aes-256-ecb",$_SESSION['decrypt_sess_key'], OPENSSL_RAW_DATA));
        $hmac = base64_encode(hash_hmac('sha256', base64_encode($json_data), $_SESSION['decrypt_sess_key'], true));
        
        $response = $this->gstCommonRetunSave($encodejson,$hmac,$returnmonth,$jstr);      
        return $response;
    }

    public function returnSubmit($returnmonth) {
        $this->authenticateToken();
        $error =1;
        $msg = '';
        $api_return_period = $this->getRetrunPeriodFormat($returnmonth);
        $gstin = $this->gstin();
        $username = $this->username();

        $sub_data='{
          "gstin": "'.$gstin.'",
          "ret_period": "042016"
        }';
        $encodejson=base64_encode(openssl_encrypt(base64_encode($sub_data),"aes-256-ecb",$_SESSION['decrypt_sess_key'], OPENSSL_RAW_DATA));
        $sdata1 = array("action" => 'RETSUBMIT', "data" => $encodejson);

        $data_string = json_encode($sdata1);


        //Start code for create header
        $header_array = array(
            'Content-Length: ' . strlen($data_string).'',
            'auth-token:' . $_SESSION['auth_token'] . '',
            'gstin:' . $gstin . '',
            'ret_period: '.$api_return_period.' ',
            'username:' . $username . '',
            'accept:application/json',
            'action:RETSUBMIT'
        );
        $header3 = $this->header($header_array);
        //End code for create header
        $getSubmitUrl='http://devapi.gstsystem.co.in/taxpayerapi/v0.3/returns/gstr1';
        $submit_data1 = $this->hitUrl($getSubmitUrl, $data_string, $header3);
        $retDta1 = json_decode($submit_data1);
        if(isset($retDta1->status_cd) && $retDta1->status_cd=='1'  && $msg == '')
        {
            $retRek=$retDta1->rek;
            $retData1=$retDta1->data;

            $apiEk1=openssl_decrypt(base64_decode($retRek),"aes-256-ecb",$decrypt_sess_key, OPENSSL_RAW_DATA);
            $decodejson1= base64_decode(openssl_decrypt(base64_decode($retData1),"aes-256-ecb",$apiEk1, OPENSSL_RAW_DATA));

            echo "<br>RETSTATUS Of Submit<br>$decodejson1<br><br>";
            $msg = "Congratulations! invoice has been submitted succussfuly.";
            $error = 0;
        }
        else {
           $msg = $retDta1->error->message;
        }
        $response['message'] = $msg;
        $response['error'] = $error;
        return $response;
    }

    public function returnFiling($returnmonth) {
        $this->authenticateToken();
        $error =1;
        $msg = '';
        $api_return_period = $this->getRetrunPeriodFormat($returnmonth);
        $gstin = $this->gstin();
        $username = $this->username();

        $reqPayLoad= $this->returnSummary($returnmonth);
        $encodejson=base64_encode(openssl_encrypt(base64_encode($reqPayLoad),"aes-256-ecb",$_SESSION['decrypt_sess_key'], OPENSSL_RAW_DATA));
        $sdata1 = array("action" => 'RETFILE', "data" => $encodejson);

        $data_string = json_encode($sdata1);
        
        //Start code for create header
        $header_array = array(
            'Content-Length: ' . strlen($data_string).'',
            'auth-token:' . $_SESSION['auth_token'] . '',
            'gstin:' . $gstin . '',
            'ret_period: '.$api_return_period.' ',
            'username:' . $username . '',
            'accept:application/json',
            'action:RETFILE'
        );
        $header3 = $this->header($header_array);
        //End code for create header

        $getSubmitUrl='http://devapi.gstsystem.co.in/taxpayerapi/v0.3/returns/gstr1';
        $submit_data1 = $this->hitUrl($getSubmitUrl, $data_string, $header3);
        $retDta1 = json_decode($submit_data1);
        if(isset($retDta1->status_cd) && $retDta1->status_cd=='1'  && $msg == '')
        {
            echo "RETFILE:<br><pre>";
            print_r($retDta1)."<br><br>";

            $msg = "Congratulations! invoice has been refill succussfuly.";
            $error = 0;
        }
        else {
            $msg = $retDta1->error->message;
        }
        

        $response['message'] = $msg;
        $response['error'] = $error;
        return $response;
    }

    public function returnSummary($returnmonth,$type='',$jstr='gstr1') {
        $this->authenticateToken();
        //$this->pr($_SESSION);
        $gstin = $this->gstin();
        $username = $this->username();
        $ctin = $this->ctin();
        $api_return_period = $this->getRetrunPeriodFormat($returnmonth);
        //Start code for create header
        $header2_array = array(
            'auth-token:' . $_SESSION['auth_token'] . '',
            'gstin:' . $gstin . '',
            'ret_period: '.$api_return_period.' ',
            'username:' . $username . '',
            'accept:application/json',
            'action:' . 'RETSTATUS' . ''
        );
            
        $header2 = $this->header($header2_array);
        //$this->pr($header2);
        //End code for create header

        if($type=='') {
             $getReturnUrl='http://devapi.gstsystem.co.in/taxpayerapi/v0.3/returns/'.$jstr.'?gstin='.$gstin. '&ret_period='.$api_return_period.'&action=RETSUM';
        }
        else {
            $getReturnUrl='http://devapi.gstsystem.co.in/taxpayerapi/v0.3/returns/'.$jstr.'?gstin='.$gstin. '&ret_period='.$api_return_period.'&action='.$type;
        }

        if($type=='B2B') {
            //$getReturnUrl='http://devapi.gstsystem.co.in/taxpayerapi/v0.3/returns/'.$jstr.'?gstin='.$gstin. '&ret_period='.$api_return_period.'&action='.$type.'&action_required=Y&ctin=33GSPTN4901G1ZD&from_time=';
            $getReturnUrl='http://devapi.gstsystem.co.in/taxpayerapi/v0.3/returns/'.$jstr.'?gstin='.$gstin. '&ret_period='.$api_return_period.'&action='.$type;
        }
        if($type=='HSN') {
            $getReturnUrl='http://devapi.gstsystem.co.in/taxpayerapi/v0.3/returns/'.$jstr.'?gstin='.$gstin. '&ret_period='.$api_return_period.'&action=HSNSUM';
        }
        if($type=='CDNR') {
            $getReturnUrl='http://devapi.gstsystem.co.in/taxpayerapi/v0.3/returns/'.$jstr.'?gstin='.$gstin. '&ret_period='.$api_return_period.'&action='.$type.'&action_required=Y&from_time='.$api_return_period;
        }
        if($type=='TXPD') {
            $getReturnUrl='http://devapi.gstsystem.co.in/taxpayerapi/v0.3/returns/'.$jstr.'?gstin='.$gstin. '&ret_period='.$api_return_period.'&action=TXP';
        }
        if($type=='CDN') {
            $getReturnUrl='http://devapi.gstsystem.co.in/taxpayerapi/v0.3/returns/'.$jstr.'?gstin='.$gstin. '&ret_period='.$api_return_period.'&action=CDN';
        }
        
        //echo $getReturnUrl;

        $result_data_sum = $this->hitGetUrl($getReturnUrl, '', $header2);
        $retDta_sum = json_decode($result_data_sum);
        //$this->pr($retDta_sum);
        if(isset($retDta_sum->status_cd) && $retDta_sum->status_cd=='1')
        {
            $retRek_sum = $retDta_sum->rek;
            $retData_sum = $retDta_sum->data;
            $apiEk1_sum = openssl_decrypt(base64_decode($retRek_sum),"aes-256-ecb",$_SESSION['decrypt_sess_key'], OPENSSL_RAW_DATA);
            $decodejson_sum = base64_decode(openssl_decrypt(base64_decode($retData_sum),"aes-256-ecb",$apiEk1_sum, OPENSSL_RAW_DATA));
            $return_encode = $decodejson_sum;
            //echo  $return_encode;
            return $return_encode;
        }
        else {
            $msg = "Sorry! Unable to process";
            /*if(isset($retDta_sum->error)) {
                $this->array_key_search('message', $retDta_sum->error);
                $msg = $this->error_msg;;
            }

            $this->setError($msg);*/
            return false;
        }

    }

    public function returnDeleteItems($deleteData, $returnmonth, $jstr) {
        $this->authenticateToken();
        $action = 'RETSAVE';
        $deleteData = json_encode($deleteData);
        //echo $deleteData;
        $encodejson=base64_encode(openssl_encrypt(base64_encode($deleteData),"aes-256-ecb",$_SESSION['decrypt_sess_key'], OPENSSL_RAW_DATA));

        $hmac = base64_encode(hash_hmac('sha256', base64_encode($deleteData), $_SESSION['decrypt_sess_key'], true));
        $response = $this->gstCommonRetunSave($encodejson,$hmac,$returnmonth,$jstr); 
        return $response;

    }

    public function gstCommonRetunSave($encodejson,$hmac,$returnmonth,$jstr) {
        $error =1;
        $msg = '';
        $api_return_period = $this->getRetrunPeriodFormat($returnmonth);

        $gstin = $this->gstin();
        $username = $this->username();
        $action = 'RETSAVE';

        $data = array(
          "action" => $action,
          "data" => $encodejson,
          "hmac" => $hmac
        );
        $data_string = json_encode($data);
        //Start code for create header
        $header_array = array(
            'Content-Length: ' . strlen($data_string).'',
            'auth-token:' . $_SESSION['auth_token'] . '',
            'gstin:' . $gstin . '',
            'ret_period: '.$api_return_period.' ',
            'username:' . $username . '',
            'accept:application/json',
        );
        $header = $this->header($header_array);
        //End code for create header
       // $this->pr($header);

        $url = 'http://devapi.gstsystem.co.in/taxpayerapi/v0.3/returns/'.$jstr;
        
        $result_data = $this->hitPulUrl($url, $data_string, $header);
        $datasave = json_decode($result_data);
        //$this->pr($datasave);
        if(isset($datasave->status_cd) && $datasave->status_cd=='1' && $msg == '')
        {
            $retData=$datasave->data;
            $rek=$datasave->rek;
            $apiEk=openssl_decrypt(base64_decode($rek),"aes-256-ecb",$_SESSION['decrypt_sess_key'], OPENSSL_RAW_DATA);
            $decodejson= base64_decode(openssl_decrypt(base64_decode($retData),"aes-256-ecb",$apiEk, OPENSSL_RAW_DATA));
            $ref = json_decode($decodejson);

            $refId = $ref->reference_id;
            sleep(5);
            
            //Start code for create header
            $header2_array = array(
                'auth-token:' . $_SESSION['auth_token'] . '',
                'gstin:' . $gstin . '',
                'ret_period: '.$api_return_period.' ',
                'username:' . $username . '',
                'accept:application/json',
                'action:' . 'RETSTATUS' . ''
            );
            
            $header2 = $this->header($header2_array);
            //$this->pr($header2);
            //End code for create header
            $url2 = 'http://devapi.gstsystem.co.in/taxpayerapi/v0.3/returns?action=RETSTATUS&gstin='.$gstin. '&ret_period='.$api_return_period.'&ref_id='.$refId.'';
            $result_data1 = $this->hitGetUrl($url2, '', $header2);
            
            $retDta = json_decode($result_data1);
            $this->pr($retDta);
            if(isset($retDta->status_cd) && $retDta->status_cd=='1' && $msg == '')
            {
                $retRek=$retDta->rek;
                $retData1=$retDta->data;

                $apiEk1=openssl_decrypt(base64_decode($retRek),"aes-256-ecb",$_SESSION['decrypt_sess_key'], OPENSSL_RAW_DATA);
                $decodejson1= base64_decode(openssl_decrypt(base64_decode($retData1),"aes-256-ecb",$apiEk1, OPENSSL_RAW_DATA));
                ;
                if(!empty($decodejson1) && $msg == '') {
                    $jstr1_status = json_decode($decodejson1,true);
                    $this->pr($jstr1_status);
                    
                    if(isset($jstr1_status['status_cd']) && $jstr1_status['status_cd']=='P' && $msg == '') {

                        $error = 0;
                    }
                    else {
                        $this->array_key_search('error_msg', $jstr1_status);
                        $msg = $this->error_msg;;
                    }
                }
                else {
                   $msg = "Sorry! Invalid proccess";
                }
            }
            else {
                $msg = "Sorry! Invalid proccess";
            }
        }
        else {
            if(isset($datasave->error)) {
                $this->array_key_search('message', $datasave->error);
                $msg = $this->error_msg;;
            }
           
            if(!$msg) {
                $msg = "Sorry! Unable to authenticate";
            }
            
        }

        $response['message'] = $msg;
        $response['error'] = $error;
        return $response;
    }


    public function getRetrunPeriodFormat($returnmonth) {
        $api_return_period = '';
        if(!empty($returnmonth)) {
          $api_return_period_array = explode('-',$returnmonth);
          $api_return_period = $api_return_period_array[1].$api_return_period_array[0];
        }
        return $api_return_period;
    }

    public function checkUserGstr1Exists($type='') {
        $check = false;
        if(!empty($type)) {
            $user_gstr = $this->get_user_gstr();
            if(!empty($user_gstr)) {
                $last_auth_date = $user_gstr[0]->added_date;
                $user_id = $user_gstr[0]->user_id;
                $today_date = date('Y-m-d h:i:s');
                $diff = (strtotime($today_date)-strtotime($last_auth_date));
                
                //6000
                if($diff <= 6000) {
                    if($type=='otp') {
                        $check = $user_gstr[0]->otp;
                    }
                    if($type=='hexcode') {
                        $check = $user_gstr[0]->hexcode;
                    }
                    if($type=='app_key') {
                        $check = $user_gstr[0]->app_key;
                    }
                    if($type=='auth_token') {
                        $check = $user_gstr[0]->auth_token;
                    }
                    if($type=='decrypt_sess_key') {
                        $check = $user_gstr[0]->decrypt_sess_key;
                    }
                    if($type=='added_date') {
                        $check = $user_gstr[0]->added_date;
                    }
                    
                }
                else {
                    $this->query("DELETE FROM ".$this->getTableName('user_gstr1')." WHERE user_id = ".$user_id);
                    $this->gstr_session_destroy();
                }
            }
            
        }
        return $check;
    }

    public function gstr_session_destroy() {
        if(!empty($_SESSION['hexcode']) && !empty($_SESSION['app_key']) && !empty($_SESSION['auth_token']) && !empty($_SESSION['auth_date']) && !empty($_SESSION['decrypt_sess_key'])) {
            unset($_SESSION['hexcode']);
            unset($_SESSION['app_key']);
            unset($_SESSION['auth_token']);
            unset($_SESSION['auth_date']);
            unset($_SESSION['decrypt_sess_key']);
        }
    }

    public function header($fields= array()) {
        $client_secret = 'a9bcf665fe424883b7b94791eb31f667';
        $clientid = 'l7xx1ed437f1e18347c38bd2aad6e6dd3b3c';
        $ip_usr = '203.197.205.110';
        $state_cd = $this->state_cd();
        $txn= 'TXN789123456789';
        $header_new_array = array();

        $header= array(
          'client-secret: '.$client_secret.'',
          'clientid: '.$clientid.'',
          'Content-Type: application/json',
          'ip-usr: '.$ip_usr.'',
          'state-cd: '.$state_cd.'',
          'txn: '.$txn.'');

        if(!empty($fields)) {
            $header= array_merge($header,$fields);
        }
        return $header;     
    }

    public function username() {
        $username = 'Cyfuture.TN.TP.1';//'Cyfuture.TN.TP.1';//'Karthiheyini.TN.1';
        if(isset($_SESSION['user_detail']['user_id'])) {
            $clientKyc = $this->get_results("select `gstin_username` as username from " . $this->getTableName('client_kyc') ." where 1=1 AND added_by = ".$_SESSION['user_detail']['user_id']." ");
            if(!empty($clientKyc)) {
                $username = $clientKyc[0]->username;

            }
        }

        return $username;
    }

    public function gstin() {
        $gstin = '';//'33GSPTN3941G1Z7';
        if(isset($_SESSION['user_detail']['user_id'])) {
            $clientKyc = $this->get_results("select `gstin_number` as gstin from " . $this->getTableName('client_kyc') ." where 1=1 AND added_by = ".$_SESSION['user_detail']['user_id']." ");
            if(!empty($clientKyc)) {
                $gstin = $clientKyc[0]->gstin;

            }
        }
        return $gstin;
    }

    public function ctin() {
        $ctin = '';
        if(isset($_SESSION['user_detail']['user_id'])) {
            $clientKyc = $this->get_results("select `billing_gstin_number` as ctin from " . $this->getTableName('client_kyc') ." where 1=1 AND added_by = ".$_SESSION['user_detail']['user_id']." ");
            if(!empty($clientKyc)) {
                $gstin = $clientKyc[0]->ctin;

            }
        }
        return $ctin;
    }

    public function gross_turnover($user_id=0) {
        $gt = '';
        $clientGt = $this->get_results("select gross_turnover as gt from " . $this->getTableName('client_kyc') ." where 1=1 AND added_by = ".$user_id." ");
        $gt = $clientGt[0]->gt;
        return $gt;
    }

    public function cur_gross_turnover($user_id=0) {
        $cur_gt = '';
        $clientGt = $this->get_results("select cur_gross_turnover as cur_gt from " . $this->getTableName('client_kyc') ." where 1=1 AND added_by = ".$user_id." ");
        $cur_gt = $clientGt[0]->cur_gt;
        return $cur_gt;
    }

    public function is_gross_turnover_check($user_id=0) {
        $is_checked = '';
        $clientKyc = $this->get_results("select `gross_turnover` from " . $this->getTableName('client_kyc') ." where 1=1 AND added_by = ".$user_id." ");
        if(!empty($clientKyc)) {
            $is_checked = $clientKyc[0]->gross_turnover;
        }
        return $is_checked;
    }
    public function is_username_exists($user_id=0) {
        $is_checked = '';
        $clientKyc = $this->get_results("select `gstin_username` from " . $this->getTableName('client_kyc') ." where 1=1 AND added_by = ".$user_id." ");
        if(!empty($clientKyc)) {
            $is_checked = $clientKyc[0]->gstin_username;
        }
        return $is_checked;
    }

    public function state_cd() {
        $state_cd = '';
        $state_cd = substr($this->gstin(),0,2);
        return $state_cd;
    }

    public function get_user_gstr() {
        $user_ustr = array();
        if(isset($_SESSION['user_detail']['user_id'])) {
            $user_ustr = $this->get_results("select * from " . $this->getTableName('user_gstr1') ." where user_id = ".$_SESSION['user_detail']['user_id']." ");
            
        }
        return $user_ustr;
    }

    public function save_user_gstr1($data) {
        if(isset($_SESSION['user_detail']['user_id'])) {
            $user_ustr = $this->get_results("select * from " . $this->getTableName('user_gstr1') ." where 1=1 and  user_id = ".$_SESSION['user_detail']['user_id']." ");
            $data['user_id'] = $_SESSION['user_detail']['user_id'];

            if (!empty($user_ustr)) {
                $dataGST1['otp'] = $data['otp'];
                $dataGST1['hexcode'] = $data['hexcode'];
                $dataGST1['app_key'] = $data['app_key'];
                $dataGST1['auth_token'] = $data['auth_token'];
                $dataGST1['decrypt_sess_key'] = $data['decrypt_sess_key'];
                $dataGST1['added_date'] = $data['added_date'];

                $dataGST1where['user_id'] =  $data['user_id'];
                $this->update($this->getTableName('user_gstr1'),  $dataGST1, $dataGST1where);

            } 
            else {
                $dataGST1['otp'] = $data['otp'];
                $dataGST1['hexcode'] = $data['hexcode'];
                $dataGST1['app_key'] = $data['app_key'];
                $dataGST1['auth_token'] = $data['auth_token'];
                $dataGST1['decrypt_sess_key'] = $data['decrypt_sess_key'];
                $dataGST1['added_date'] = $data['added_date'];
                $dataGST1['user_id'] =  $data['user_id'];

                $this->insert($this->getTableName('user_gstr1'), $dataGST1);
            } 
        }   
    }

    public function hitUrl($url,$data_string,$header) { 
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;     
    }

    public function hitPulUrl($url, $data_string, $header)
    {
      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
      $result = curl_exec($ch);
      curl_close($ch);
      return $result;
    }

    public function hitGetUrl($url, $data_string, $header)
    {
      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
      $result = curl_exec($ch);
      curl_close($ch);
      return $result;
    }

    public function array_key_search($searched_key, $array = array()){
        $key_value = false;
        foreach($array as $key => $value){
            $key = "$key";
            
            if($key == $searched_key){
                 $this->error_msg[] =  $value;
            }else{
                if(is_array($value)){
                    $key_value = self::array_key_search($searched_key, $value);
                }
            }
            
        }
    }
   
}
