<?php

final class gstr extends validation {

    public $ciphertext ='';
    public $hexcode ='';
    public $public_key ='';
    public $app_key = '';
    public $otp_ency = '';
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
        $hexcode = $_SESSION['hexcode']=$this->hexcode;
        //return $hexcode;
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
         $appkey= $_SESSION['app_key']=$this->app_key;

        return $appkey;
    }
    
    final public function aes256_ecb_encrypt($key, $data, $iv) {
        if(32 !== strlen($key)) $key = hash('SHA256', $key, true);
        if(16 !== strlen($iv)) $iv = hash('MD5', $iv, true);
        $padding = 16 - (strlen($data) % 16);
        $data .= str_repeat(chr($padding), $padding);
        return mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_ECB, $iv);
    }
    
    private function getOTPEncypt($otp)
    {
        $key = pack('H*', $_SESSION['hexcode']);
        //$otp_code = $otp;
        $otp_code = '575757';
        $otp_encode =utf8_encode($otp_code);

        # create a random IV to use with ECB encoding
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);

        $ciphertext_enc= $this->aes256_ecb_encrypt($key,$otp_encode,$iv);
        return base64_encode($ciphertext_enc);
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
    
    
    public function authenticateToken($encypt_otp)
    {
       $app_key =$this->getCertificateKey();
        print_r($_SESSION);die;
        $client_secret = 'fa6f03446473400fa21240241affe2a5';
        $clientid = 'l7xx2909cd95daee418b8118e070b6b24dd6';
        $ip_usr = '49.50.73.109';
        $state_cd='27';
        $txn='TXN789123456789';

        $username='Cyfuture.MH.TP.1';
        $action='AUTHTOKEN';

        $data = array("username" => $username, "action" => $action, "app_key" => $_SESSION['app_key'], "otp" =>$encypt_otp);
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
        if($result_data->status_cd=='1')
        {
            $this->setSuccess("OTP Authenticated");
            return true;
        }
        $this->setError("OTP Authentication Failed");
        return false;
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
}