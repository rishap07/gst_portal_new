<?php
/*
 * 
 *  Developed By        :   Sheetal
 *  Description         :   API GSTR1 encryprtion n decryption 
 *  Date Created        :   May 18, 2017
 *  Last Modified By    :   Monika Deswal 
 *  Last Modification   :   API encryprtion n decryption convert to class structure
 * 
*/

final class gstr extends validation {

    public $ciphertext ='';
    public $hexcode ='';
    public $public_key ='';
    public $app_key = '';
    public $otp_ency = '';
    public $hKey='';
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
        $_SESSION['hexcode']=$this->hexcode;
    }
    
    public function getCertificateKey()
    {
        $pem_private_key = file_get_contents(PROJECT_ROOT.'/modules/api/GSTN_Public_Key/GSTN_private.pem');
        $private_key = openssl_pkey_get_private($pem_private_key);
        $pem_public_key = openssl_pkey_get_details($private_key)['key'];
        $this->public_key = openssl_pkey_get_public($pem_public_key);
        
        $encrypted="";
        openssl_public_encrypt($this->ciphertext , $encrypted, $this->public_key);

        $this->app_key=base64_encode($encrypted);  
        $_SESSION['app_key']=$this->app_key;
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
    
    public function requestOTP()
    {
        $this->keyGeneration();
        $this->getCertificateKey();

        //****************HEADER*********************************//
        $client_secret = 'fa6f03446473400fa21240241affe2a5';
        $clientid = 'l7xx2909cd95daee418b8118e070b6b24dd6';
        $ip_usr = '49.50.73.109';
        $state_cd='27';
        $txn='TXN789123456789';
        //****************DATA*********************************//
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
    

    public function header($fields= array()) {
        
        $client_secret = 'a9bcf665fe424883b7b94791eb31f667';
        $clientid = 'l7xx1ed437f1e18347c38bd2aad6e6dd3b3c';
        $ip_usr = '203.197.205.110';
        $state_cd='33';
        $txn='TXN789123456789';
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
        $username='Karthiheyini.TN.1';
        return $username;
    }
    public function gstin() {
        $gstin = '33GSPTN0741G1ZF';
        return $gstin;
    }
    
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
       
        if(isset($data->status_cd) && $data->status_cd=='1')
        {
            $session_key = $data->sek;
            $decrypt_sess_key = openssl_decrypt(base64_decode($session_key),"aes-256-ecb",$this->hKey, OPENSSL_RAW_DATA);
            
            $_SESSION['decrypt_sess_key']=$decrypt_sess_key;
            $_SESSION['auth_token']=$data->auth_token;
            return $data;
        }
        else {
            $this->setError("Unable to proccess");
            return false;
        }
        
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


    public function returnSave($dataArr,$returnmonth) {
        $msg = '';
        $error = 1;
        $response = array();
        $this->authenticateToken();
        $json_data = json_encode($dataArr);
        $encodejson=base64_encode(openssl_encrypt(base64_encode($json_data),"aes-256-ecb",$_SESSION['decrypt_sess_key'], OPENSSL_RAW_DATA));
        $hmac = base64_encode(hash_hmac('sha256', base64_encode($json_data), $_SESSION['decrypt_sess_key'], true));
        
        $gstin = $this->gstin();
        $username = $this->username();
        $action = 'RETSAVE';

        $data = array(
          "action" => $action,
          "data" => $encodejson,
          "hmac" => $hmac
        );
        $data_string = json_encode($data);
        if(!empty($returnmonth)) {
          $api_return_period_array = explode('-',$returnmonth);
          $api_return_period = $api_return_period_array[1].$api_return_period_array[0];
        }
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
        $url = 'http://devapi.gstsystem.co.in/taxpayerapi/v0.3/returns/gstr1';
        $result_data = $this->hitPulUrl($url, $data_string, $header);
        $datasave = json_decode($result_data);
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
            //End code for create header
            $url2 = 'http://devapi.gstsystem.co.in/taxpayerapi/v0.3/returns?action=RETSTATUS&gstin='.$gstin. '&ret_period='.$api_return_period.'&ref_id='.$refId.'';
            $result_data1 = $this->hitGetUrl($url2, '', $header2);
            
            $retDta = json_decode($result_data1);
            
            if(isset($retDta->status_cd) && $retDta->status_cd=='1' && $msg == '')
            {
                $retRek=$retDta->rek;
                $retData1=$retDta->data;

                $apiEk1=openssl_decrypt(base64_decode($retRek),"aes-256-ecb",$_SESSION['decrypt_sess_key'], OPENSSL_RAW_DATA);
                $decodejson1= base64_decode(openssl_decrypt(base64_decode($retData1),"aes-256-ecb",$apiEk1, OPENSSL_RAW_DATA));
                ;
                if(!empty($decodejson1) && $msg == '') {
                    $jstr1_status = json_decode($decodejson1,true);
                   /*echo '<pre>'; print_r($jstr1_status);
                            die;*/
                    if(isset($jstr1_status['status_cd']) && $jstr1_status['status_cd']=='P'  && $msg == '') {
                        $getReturnUrl='http://devapi.gstsystem.co.in/taxpayerapi/v0.3/returns/gstr1?gstin='.$gstin. '&ret_period='.$api_return_period.'&action=RETSUM';
                        $result_data_sum = $this->hitGetUrl($getReturnUrl, '', $header2);
                        $retDta_sum = json_decode($result_data_sum);
                        if(isset($retDta_sum->status_cd) && $retDta_sum->status_cd=='1'  && $msg == '')
                        {
                           
                            $retRek_sum = $retDta_sum->rek;
                            $retData_sum = $retDta_sum->data;
                            $apiEk1_sum = openssl_decrypt(base64_decode($retRek_sum),"aes-256-ecb",$_SESSION['decrypt_sess_key'], OPENSSL_RAW_DATA);
                            $decodejson_sum = base64_decode(openssl_decrypt(base64_decode($retData_sum),"aes-256-ecb",$apiEk1_sum, OPENSSL_RAW_DATA));
                            /*echo '<pre>'; print_r(json_decode($decodejson_sum,true));
                            die;*/

                            $sub_data='{
                              "gstin": "'.$gstin.'",
                              "ret_period": "062017"
                            }';
                            $encodejson=base64_encode(openssl_encrypt(base64_encode($sub_data),"aes-256-ecb",$_SESSION['decrypt_sess_key'], OPENSSL_RAW_DATA));
                            $sdata1 = array("action" => 'RETSUBMIT', "data" => $encodejson);

                            $data_string = json_encode($sdata1);
                            
                            //Start code for create header
                            $header3_array = array(
                              'accept:application/json',
                              'auth-token:' . $_SESSION['auth_token'] . '',
                              'gstin:' . $gstin . '',
                              'ret_period: '.$api_return_period.' ',
                              'username:' . $username . '',
                              'action:RETSUBMIT'
                            );
                            $header3 = $this->header($header3_array);
                            //End code for create header
                            $mygetSubmitUrl='http://devapi.gstsystem.co.in/taxpayerapi/v0.3/returns/gstr1';
                            $submit_final_data = $this->hitUrl($mygetSubmitUrl, $data_string, $header3);
                            $retDta_sumbit = json_decode($submit_final_data);
                            //echo '<pre>'; print_r($retDta_sumbit);
                            //die;
                            if(isset($retDta_sumbit->status_cd) && $retDta_sumbit->status_cd=='1'  && $msg == '')
                            {
                                $msg = "Congratulations! invoice has been saved";
                                $error = 0;
                            }
                            else {
                               $msg = $retDta_sumbit->error->message;
                            }
                        }
                        else {
                            $msg = "Sorry! Unable to process";
                        }
                    }
                    else {
                        $msg = $this->array_key_search('error_msg', $jstr1_status);
                        if(!$msg) {
                            $msg = "Sorry! Invalid data format";
                        }
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
            $msg = "Sorry! Unable to authenticate";
        }

        $response['message'] = $msg;
        $response['error'] = $error;
        return $response;

    }
    public function array_key_search($searched_key, $array = array()){
        $key_value = false;
        foreach($array as $key => $value){
            $key = "$key";
            if($key_value == false){
                if($key == $searched_key){
                    return $value;
                }else{
                    if(is_array($value)){
                        $key_value = self::array_key_search($searched_key, $value);
                    }
                }
            }
        }
        $key_value == is_null($key_value) ? false : $key_value;

        return $key_value;
    }

   
}