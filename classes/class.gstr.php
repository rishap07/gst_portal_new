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
    public function Iv(){
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        return $iv;
    }
    public function encrypt($key,$data) {
      $encrypt=base64_encode(openssl_encrypt(base64_encode($data),"aes-256-ecb",$key, OPENSSL_RAW_DATA));
      return $encrypt;
    }
    public function decrypt($key,$data) {
      $decrypt=openssl_decrypt(base64_decode($data),"aes-256-ecb",$key, OPENSSL_RAW_DATA);
      return $decrypt;
    }
    public function hmac($key,$data) {
        $hmac = base64_encode(hash_hmac('sha256', base64_encode($data), $key, true));
        return $hmac;

    }
    
    public function keyGeneration()
    {
        if(!empty($_SESSION['inputToken'])) {
            $inputToken = $_SESSION['inputToken'];
        }
        else {
            $_SESSION['inputToken'] = $inputToken = $this->RandomToken(16);
        }
        if(!empty($_SESSION['keyhash'])) {
            $keyhash = $_SESSION['keyhash'];
        }
        else {
            $_SESSION['keyhash'] = $keyhash = $this->RandomKey(32);
        }
        if(!empty($_SESSION['key'])) {
            $key = $_SESSION['key'];
        }
        else {
           $_SESSION['key'] = $key = pack('H*',$keyhash);
        }
        if(!empty($_SESSION['iv'])) {
            $iv = $_SESSION['iv'];
        }
        else {
           # create a random IV to use with ECB encoding
            $_SESSION['iv'] = $iv = $this->Iv();
        }
        # creates a cipher text compatible with AES (Rijndael block size = 128)
        # to keep the text confidential 
        # only suitable for encoded input that never ends with value 00h
        # (because of default zero padding)
        if(!empty($_SESSION['ciphertext'])) {
            $this->ciphertext = $_SESSION['ciphertext'];
        }
        else {
           $_SESSION['ciphertext']= $this->ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key,
            $inputToken, MCRYPT_MODE_ECB, $iv);
        }
        if(!empty($_SESSION['hexcode'])) {
            $this->hexcode = $_SESSION['hexcode'];
        }
        else {
            # creating hexcode for otp emcryption by app-key
            $this->hexcode= bin2hex($this->ciphertext);
            $_SESSION['hexcode'] = $this->hexcode; 
        }
            
    }

    public function getCertificateKey()
    {
        if(!empty($_SESSION['public_key'])) {
            $this->public_key = $_SESSION['public_key'];
        }
        else {
            $pem_private_key = file_get_contents(PROJECT_ROOT.'/modules/api/GSTN_Public_Key/GSTN_G2B_Prod_Public.pem');
            $private_key = openssl_pkey_get_public($pem_private_key);
            $pem_public_key = openssl_pkey_get_details($private_key)['key'];
            $_SESSION['public_key'] = $this->public_key = openssl_pkey_get_public($pem_public_key);
        }
        if(!empty($_SESSION['app_key'])) {
            $this->app_key = $_SESSION['app_key'];
        }
        else {
            $encrypted="";
            openssl_public_encrypt($this->ciphertext , $encrypted, $this->public_key);
            $this->app_key=base64_encode($encrypted);  
            $_SESSION['app_key']=$this->app_key;
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
        
        if(empty($_SESSION['hkey'])) {
            $key = pack('H*', $_SESSION['hexcode']);
            //echo 'key: '. $key ;
            //$otp = '575757';
            $_SESSION['hkey'] = $this->hKey= $key;
        }
        else {
            $this->hKey = $key = $_SESSION['hkey'];
        }

        $otp_code = $otp;
        $otp_encode =utf8_encode($otp_code);
        //$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
        $iv = $_SESSION['iv'];//mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $ciphertext_enc= $this->aes256_ecb_encrypt($key,$otp_encode,$iv);
        return $ciphertext_enc;
        //return base64_encode($ciphertext_enc);
    }
    public function requestOTP($code='')
    {
        //echo 'otp';
        $this->gstr_session_destroy();
        $this->keyGeneration();
        $this->getCertificateKey();
       
        $username = $this->username();
        $data = array("username" => $username, "action" => 'OTPREQUEST', "app_key" => $_SESSION['app_key']);
        $data_string = json_encode($data);
        
        //Start code for create header
        $header_array = array(
            'Content-Length: ' . strlen($data_string).'',
        );
        $header = $this->header($header_array);
       // $this->pr($header);
        //End code for create header

        $dataGST1['user_id'] = $_SESSION['user_detail']['user_id'];
        $dataGST1['header'] = json_encode(array('header' =>$header, 'data' => $data_string));
        $dataGST1['response'] =  isset($result_data)?$result_data:'';
        $dataGST1['type'] =  'OTP';
        $dataGST1['code'] =  $code;
        $dataGST1['inserted_date'] =  date('Y-m-d H:i:s');
        $this->insert($this->getTableName('otp_request'), $dataGST1);
        return true;

        $url = 'https://gspapi.karvygst.com/Authenticate';
        //$url=  'http://devapi.gstsystem.co.in/taxpayerapi/v0.2/authenticate';
        //echo $url;

        $result_data= $this->hitUrl($url,$data_string,$header);
        $data = json_decode($result_data);
        //$this->pr($data);

        if(isset($data->status_cd) && $data->status_cd=='1')
        {
            return true;
        }
        else {
            $this->array_key_search('message', $data);
            $msg = $this->error_msg;
            return false;
        }
        
    }
    
    public function authenticateToken($otp,$code='')
    {
        $otp_code = $_SESSION['otp'] = $otp; //'643592';//'575757'
        //$hkey = $_SESSION['hkey'] = pack('H*', $hexcode);
        $ciphertext_enc = $this->getOTPEncypt($_SESSION['otp']);
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
        //$this->pr($header);

        $dataGST1['user_id'] = $_SESSION['user_detail']['user_id'];
        $dataGST1['header'] = json_encode(array('header' =>$header, 'data' => $data_string));
        $dataGST1['response'] =  isset($result_data)?$result_data:'';
        $dataGST1['type'] =  'AUTHTOKEN';
        $dataGST1['code'] =  $code;
        $dataGST1['inserted_date'] =  date('Y-m-d H:i:s');
        $this->insert($this->getTableName('otp_request'), $dataGST1);
        return true;

        // $url=  'http://devapi.gstsystem.co.in/taxpayerapi/v0.2/authenticate';
        $url = 'https://gspapi.karvygst.com/Authenticate';
        $result_data= $this->hitUrl($url,$data_string,$header);
        $data = json_decode($result_data);
        /*$this->pr($data);
        die;*/

        if(isset($data->status_cd) && $data->status_cd=='1')
        {
            $session_key = $data->sek;
            $decrypt_sess_key = openssl_decrypt(base64_decode($session_key),"aes-256-ecb",$_SESSION['hkey'], OPENSSL_RAW_DATA);
            $_SESSION['decrypt_sess_key'] = $decrypt_sess_key;
            $_SESSION['auth_token'] = $data->auth_token;
            $_SESSION['auth_date'] = date('Y-m-d H:i:s');

           
            $savedata = array();
            $encode['otp'] = $_SESSION['otp'];
            $encode['hexcode'] = $_SESSION['hexcode'];
            $encode['app_key'] = $_SESSION['app_key'];
            $encode['auth_token'] = $_SESSION['auth_token'];
            $encode['decrypt_sess_key'] = $_SESSION['decrypt_sess_key'];
            $encode['inputToken'] = $_SESSION['inputToken'];
            $encode['keyhash'] = $_SESSION['keyhash'];
            $encode['key'] = $_SESSION['key'];
            $encode['iv'] = $_SESSION['iv'];
            $encode['ciphertext'] = $_SESSION['ciphertext'];
            $encode['public_key'] = $_SESSION['public_key'];

            $savedata['params'] = base64_encode(serialize($encode));
            $savedata['added_date'] = $_SESSION['auth_date'];
            $savedata['otp_date'] = $_SESSION['auth_date'];
            //$this->pr($savedata);
            $this->save_user_gstr1($savedata);
            return true;
        }
        else {
            $this->setError($data->error->message);
            return false;
        }
            
    }
    
    public function returnSave($dataArr,$returnmonth,$jstr) {
        if($this->authenticateToken() == false) {
            return false;
        }
        //$this->pr($_SESSION);die;
        $msg = $return_encode = '';
        $error = 1;
        $response = array();
        
        $json_data = json_encode($dataArr);

       // echo $json_data;
        $encodejson=base64_encode(openssl_encrypt(base64_encode($json_data),"aes-256-ecb",$_SESSION['decrypt_sess_key'], OPENSSL_RAW_DATA));
        $hmac = $this->hmac($_SESSION['decrypt_sess_key'],$json_data);
        
        $response = $this->gstCommonRetunSave($encodejson,$hmac,$returnmonth,$jstr);      
        return $response;
    }

    public function returnSubmit($returnmonth) {
        if($this->authenticateToken() == false) {
            return false;
        }
        $error =1;
        $msg = '';
        $api_return_period = $this->getRetrunPeriodFormat($returnmonth);
        $gstin = $this->gstin();
        $username = $this->username();

        $sub_data='{
          "gstin": "'.$gstin.'",
          "ret_period": "'.$api_return_period.'"
        }';
        $encodejson = base64_encode(openssl_encrypt(base64_encode($sub_data),"aes-256-ecb",$_SESSION['decrypt_sess_key'], OPENSSL_RAW_DATA));
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
        $getSubmitUrl = 'https://gspapi.karvygst.com/returns/gstr1';
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
        if($this->authenticateToken() == false) {
            return false;
        }
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

        $getSubmitUrl='https://gspapi.karvygst.com/returns/gstr1';
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

    public function returnSummary($returnmonth,$type='',$jstr='gstr1') 
    {
        /*if($this->authenticateToken() == false) {
            return false;
        }*/
        if(!empty($_SESSION['auth_token'])) {
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
                $getReturnUrl='https://gspapi.karvygst.com/returns/'.$jstr.'?gstin='.$gstin. '&ret_period='.$api_return_period.'&action=RETSUM';
            }
            else {
                $getReturnUrl='https://gspapi.karvygst.com/returns/'.$jstr.'?gstin='.$gstin. '&ret_period='.$api_return_period.'&action='.$type;
            }

            if($type=='B2B') {
                //$getReturnUrl='http://devapi.gstsystem.co.in/taxpayerapi/v0.3/returns/'.$jstr.'?gstin='.$gstin. '&ret_period='.$api_return_period.'&action='.$type.'&action_required=Y&ctin=33GSPTN4901G1ZD&from_time=';
                $getReturnUrl='https://gspapi.karvygst.com/returns/'.$jstr.'?gstin='.$gstin. '&ret_period='.$api_return_period.'&action='.$type;
            }
            if($type=='HSN') {
                $getReturnUrl='https://gspapi.karvygst.com/returns/'.$jstr.'?gstin='.$gstin. '&ret_period='.$api_return_period.'&action=HSNSUM';
            }
            if($type=='CDNR') {
                $getReturnUrl='https://gspapi.karvygst.com/returns/'.$jstr.'?gstin='.$gstin. '&ret_period='.$api_return_period.'&action='.$type.'&action_required=Y&from_time='.$api_return_period;
            }
            if($type=='TXPD') {
                $getReturnUrl='https://gspapi.karvygst.com/returns/'.$jstr.'?gstin='.$gstin. '&ret_period='.$api_return_period.'&action=TXP';
            }
            if($type=='CDN') {
                $getReturnUrl='https://gspapi.karvygst.com/returns/'.$jstr.'?gstin='.$gstin. '&ret_period='.$api_return_period.'&action=CDN';
            }
            
            //echo $getReturnUrl;

            $result_data_sum = $this->hitGetUrl($getReturnUrl, '', $header2);
            $retDta_sum = json_decode($result_data_sum);
           // $this->pr($retDta_sum);
            
            if(isset($retDta_sum->status_cd) && $retDta_sum->status_cd=='1' )
            {
                $retRek_sum = $retDta_sum->rek;
                $retData_sum = $retDta_sum->data;
                $apiEk1_sum = openssl_decrypt(base64_decode($retRek_sum),"aes-256-ecb",$_SESSION['decrypt_sess_key'], OPENSSL_RAW_DATA);
                $decodejson_sum = base64_decode(openssl_decrypt(base64_decode($retData_sum),"aes-256-ecb",$apiEk1_sum, OPENSSL_RAW_DATA));
                $return_encode = $decodejson_sum;
               //echo  $return_encode;
                return $return_encode;
            }
            elseif(isset($retDta_sum->status_cd) && $retDta_sum->status_cd=='2')         
            {
                $retRek_sum = $retDta_sum->rek;
                $retData_sum = $retDta_sum->data;
                $apiEk1_sum = openssl_decrypt(base64_decode($retRek_sum),"aes-256-ecb",$_SESSION['decrypt_sess_key'], OPENSSL_RAW_DATA);
                $decodejson_sum = base64_decode(openssl_decrypt(base64_decode($retData_sum),"aes-256-ecb",$apiEk1_sum, OPENSSL_RAW_DATA));
                $return_encode = $decodejson_sum;
               // echo  $return_encode;
                
                if(!empty($return_encode)) {
                    $filearr = json_decode($return_encode);
                    $token = $filearr->token;
                    $_SESSION['token'] = $token = '677b0e10557a4f56b2236387cfc24060';
                    //Start code for create header
                    $header3_array = array(
                        'auth-token:' . $_SESSION['auth_token'] . '',
                        'gstin:' . $gstin . '',
                        'ret_period: '.$api_return_period.' ',
                        'username:' . $username . '',
                        'accept:application/json',
                        'action:' . 'FILEDET' . ''
                    );
                        
                    $header3 = $this->header($header3_array);
                    if(!$this->gst_is_expired($_SESSION['auth_date']) == false) {
                        $dataGST1['token'] =  $_SESSION['token'];
                        $dataGST1where['user_id'] =  $_SESSION['user_detail']['user_id'];
                        $this->update($this->getTableName('user_gstr1'),  $dataGST1, $dataGST1where);
                    }
                    $filedetUrl='https://gspapi.karvygst.com/returns/'.$jstr.'?token=677b0e10557a4f56b2236387cfc24060&action=FILEDET&gstin='.$gstin.'&ret_period='.$api_return_period.'';
                    //echo $filedetUrl;
                    $result_data_sum1 = $this->hitGetUrl($filedetUrl, '', $header3);
                    $retDta1 = json_decode($result_data_sum1);
                    //$this->pr($retDta1);
                    if(isset($retDta1->status_cd) && $retDta1->status_cd=='1')
                    {
                        $retData2=$retDta1->data;
                        $rek2=$retDta1->rek;
                        $apiEk2=openssl_decrypt(base64_decode($rek2),"aes-256-ecb",$_SESSION['decrypt_sess_key'], OPENSSL_RAW_DATA);
                        $decodejson2= base64_decode(openssl_decrypt(base64_decode($retData2),"aes-256-ecb",$apiEk2, OPENSSL_RAW_DATA));
                        $ref2 = json_decode($decodejson2);
                        //$this->pr($ref2);
                        if(!empty($ref2)) {
                            $response = $ref2;
                            return $response;
                        }
                    }
                    else {
                       
                        $msg = "Sorry! Unable to authenticate";
                        $this->setError($msg);
                        
                    }
                }
                else {
                    $msg = "Sorry! Unable to process";
                    $this->setError($msg);
                }  
                
            }
            else {
                $msg = "Sorry! Unable to process";
                if(isset($retDta_sum->error)) {
                    $this->array_key_search('message', $retDta_sum->error);
                    $msg = $this->error_msg;;
                }

                $this->setError($msg);
            }
        }
        else {
            $this->setError($this->validationMessage['gstinservererror']);
        }
    }

    public function gstr1UploadSummary($returnmonth,$jstr='gstr1',$encodeJson,$rek) {
        //echo 'json: '.$encodeJson;
        $rek = 'orvyvoONhUC8fVeze8iabN44V0wyd5x1y+0YzhRQqjY=';
        $derypt_rek = base64_decode($rek);
        $final_json = base64_decode(openssl_decrypt(base64_decode($encodeJson),"aes-256-ecb",$derypt_rek, OPENSSL_RAW_DATA));
        return $final_json;
    }

    public function decrypt_string($encodedText = '', $salt = '8638FD63E6CC16872ACDED6CE49E5A270ECDE1B3B938B590E547138BB7F120EA') {
        $key = pack('H*', $salt);
        $ciphertext_dec = base64_decode($encodedText);
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $iv_dec = substr($ciphertext_dec, 0, $iv_size);
        $ciphertext_dec = substr($ciphertext_dec, $iv_size);
        return mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);
    }

    public function gstrFileTokenGenrate() {

    }

    public function returnDeleteItems($deleteData, $returnmonth, $jstr) {
        if($this->authenticateToken() == false) {
            return false;
        }
        $action = 'RETSAVE';
        $deleteData = json_encode($deleteData);
        //echo $deleteData;
        $encodejson=base64_encode(openssl_encrypt(base64_encode($deleteData),"aes-256-ecb",$_SESSION['decrypt_sess_key'], OPENSSL_RAW_DATA));

        $hmac = $this->hmac($_SESSION['decrypt_sess_key'],$deleteData);
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

        //$url = 'http://devapi.gstsystem.co.in/taxpayerapi/v0.3/returns/'.$jstr;
        $url = 'https://gspapi.karvygst.com/returns/'.$jstr;
        
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
           // $url2 = 'http://devapi.gstsystem.co.in/taxpayerapi/v0.3/returns?action=RETSTATUS&gstin='.$gstin. '&ret_period='.$api_return_period.'&ref_id='.$refId.'';

            $url2 = 'https://gspapi.karvygst.com/returns/RETSTATUS?gstin='.$gstin. '&ret_period='.$api_return_period.'&ref_id='.$refId.'';
            $result_data1 = $this->hitGetUrl($url2, '', $header2);
            
            $retDta = json_decode($result_data1);
            //$this->pr($retDta);
            if(isset($retDta->status_cd) && $retDta->status_cd=='1' && $msg == '')
            {
                $retRek=$retDta->rek;
                $retData1=$retDta->data;

                $apiEk1=openssl_decrypt(base64_decode($retRek),"aes-256-ecb",$_SESSION['decrypt_sess_key'], OPENSSL_RAW_DATA);
                $decodejson1= base64_decode(openssl_decrypt(base64_decode($retData1),"aes-256-ecb",$apiEk1, OPENSSL_RAW_DATA));
                ;
                if(!empty($decodejson1) && $msg == '') {
                    $jstr1_status = json_decode($decodejson1,true);
                    //$this->pr($jstr1_status);
                    
                    if(isset($jstr1_status['status_cd']) && $jstr1_status['status_cd']=='P' && $msg == '') {

                        $error = 0;
                    }
                    /*elseif(isset($jstr1_status['status_cd']) && $jstr1_status['status_cd']=='IP' && $msg == ''){
                        $msg = "Invoices are under procces, kindly wait for some time.";
                        $error = 2;
                    }*/
                    else {
                        $this->array_key_search('error_msg', $jstr1_status);
                        $msg = $this->error_msg;
                        //$this->pr($msg);
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

    // public function checkUserGstr1Exists($type='') {
    //     $check = false;
    //     if(!empty($type)) {
    //         $user_gstr = $this->get_user_gstr();
    //         if(!empty($user_gstr)) {
    //             $last_auth_date = $user_gstr[0]->added_date;
    //             $user_id = $user_gstr[0]->user_id;
    //             if($this->gst_is_expired($last_auth_date) == false) {
    //                 if($type=='otp') {
    //                     $check = $user_gstr[0]->otp;
    //                 }
    //                 if($type=='hexcode') {
    //                     $check = $user_gstr[0]->hexcode;
    //                 }
    //                 if($type=='app_key') {
    //                     $check = $user_gstr[0]->app_key;
    //                 }
    //                 if($type=='auth_token') {
    //                     $check = $user_gstr[0]->auth_token;
    //                 }
    //                 if($type=='decrypt_sess_key') {
    //                     $check = $user_gstr[0]->decrypt_sess_key;
    //                 }
    //                 if($type=='added_date') {
    //                     $check = $user_gstr[0]->added_date;
    //                 }
                    
    //             }
    //             else {
    //                 $this->query("DELETE FROM ".$this->getTableName('client_invoice')." WHERE `user_id` = ".$user_id.""); 
    //                 //$this->gstr_session_destroy();
    //             }
            
    //         }
    //     }
    //     return $check;
    // }

    public function gstr_session_destroy() {

        if(isset($_SESSION['hexcode'])) {
           unset($_SESSION['hexcode']); 
        }
        if(isset($_SESSION['app_key'])) {
           unset($_SESSION['app_key']); 
        }
        if(isset($_SESSION['auth_token'])) {
           unset($_SESSION['auth_token']); 
        }
        if(isset($_SESSION['inputToken'])) {
           unset($_SESSION['inputToken']); 
        }
        if(isset($_SESSION['keyhash'])) {
           unset($_SESSION['keyhash']); 
        }
        if(isset($_SESSION['key'])) {
           unset($_SESSION['key']); 
        }
        if(isset($_SESSION['iv'])) {
           unset($_SESSION['iv']); 
        }
        if(isset($_SESSION['ciphertext'])) {
           unset($_SESSION['ciphertext']); 
        }
        if(isset($_SESSION['public_key'])) {
           unset($_SESSION['public_key']); 
        }
    }

    public function gst_is_expired($last_auth_date='') {
        $today_date = date('Y-m-d H:i:s');
        $diff = (strtotime($today_date)-strtotime($last_auth_date));
        if($diff <= 30) {
            return false;
        }
        else {
            return true;
        }
    }
    public function get_user_gstr() {
        $user_gstr = array();
        if(isset($_SESSION['user_detail']['user_id'])) {
            $user_gstr = $this->get_results("select * from " . $this->getTableName('user_gstr1') ." where user_id = '".$_SESSION['user_detail']['user_id']."'");
            
        }
        return $user_gstr;
    }
    public function get_user_gstr_session_create() {
        $user_gstr = array();
        if(isset($_SESSION['user_detail']['user_id'])) {
            $user_gstr = $this->get_results("select * from " . $this->getTableName('user_gstr1') ." where user_id = '".$_SESSION['user_detail']['user_id']."'");
            if(!empty($user_gstr)) {
                $decodeData = unserialize(base64_decode($user_gstr[0]->params));
                $_SESSION['otp'] = $decodeData['otp'];
                $_SESSION['hexcode'] = $decodeData['hexcode'];
                $_SESSION['hexcode'] = $decodeData['hexcode']; 
                $_SESSION['app_key'] = $decodeData['app_key'];
                $_SESSION['auth_token'] = $decodeData['auth_token'];
                $_SESSION['decrypt_sess_key'] = $decodeData['decrypt_sess_key'];
                $_SESSION['inputToken'] = $decodeData['inputToken'];
                $_SESSION['keyhash'] = $decodeData['keyhash'];
                $_SESSION['key'] = $decodeData['key'];
                $_SESSION['iv'] = $decodeData['iv'];
                $_SESSION['ciphertext'] = $decodeData['ciphertext'];
                $_SESSION['public_key'] = $decodeData['public_key'];
                $_SESSION['auth_date'] = $user_gstr[0]->added_date;
                return true;
            }
            
        }
        return false;
    }

    public function save_user_gstr1($data) {
        if(isset($_SESSION['user_detail']['user_id'])) {
            $user_ustr = $this->get_results("select * from " . $this->getTableName('user_gstr1') ." where 1=1 and  user_id = ".$_SESSION['user_detail']['user_id']." ");
            $data['user_id'] = $_SESSION['user_detail']['user_id'];

            if (!empty($user_ustr)) {
                $dataGST1['params'] = $data['params'];
                $dataGST1['updated_date'] =  date('Y-m-d H:i:s');

                $dataGST1where['user_id'] =  $data['user_id'];
                $this->update($this->getTableName('user_gstr1'),  $dataGST1, $dataGST1where);

            } 
            else {
                $dataGST1['params'] = $data['params'];
                $dataGST1['added_date'] = $data['added_date'];
                $dataGST1['user_id'] =  $data['user_id'];
                $dataGST1['otp_date'] =  $data['otp_date'];

                $this->insert($this->getTableName('user_gstr1'), $dataGST1);
            } 
        }   
    }

    public function header($fields= array()) {
        $client_secret = API_CLIENT_SECRET;
        $clientid = API_CLIENT_ID;
        $ip_usr = API_IP;
        $state_cd = $this->state_cd();
        $txn= API_TXN;
        $karvyclientid= 'VYFKG###fdkfjf';
        $karvyclientsecret= 'VYFdd##fdkfjf';

        $header_new_array = array();

        $header= array(
            'client-secret: '.$client_secret.'',
            'clientid: '.$clientid.'',
            'Content-Type: application/json',
            'ip-usr: '.$ip_usr.'',
            'state-cd: '.$state_cd.'',
            'txn: '.$txn.'',
            'karvyclientid:'.$karvyclientid.'',
            'karvyclient-secret:'.$karvyclientsecret.''
        );

        if(!empty($fields)) {
            $header= array_merge($header,$fields);
        }
        return $header;     
    }

    public function username() {
        $username = '';//'Cyfuture.TN.TP.1';//'Karthiheyini.TN.1';
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

    public function hitUrl($url,$data_string,$header) { 
        try {
            //$this->pr($data_string);
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
            $result = curl_exec($ch);
            //$this->pr($result);
            curl_close($ch);
            return $result;  
        }
        catch (Exception $e) {
            //$this->pr($e);
            //echo 'Caught exception: ',  $e->getMessage(), "\n";
        }

           
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
        if(!empty($array)) {
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
   
}
