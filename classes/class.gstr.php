<?php
/*
 * 
 *  Developed By        :   Monika Deswal
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
        if(empty($_SESSION['inputToken'])) {
            $_SESSION['inputToken'] = $this->RandomToken(16);
        }
        if(empty($_SESSION['keyhash'])) {
            $_SESSION['keyhash'] = $keyhash = $this->RandomKey(32);
        }
        if(empty($_SESSION['key'])) {
           $_SESSION['key'] = $key = pack('H*',$_SESSION['keyhash']);
        }
        if(empty($_SESSION['iv'])) {
           # create a random IV to use with ECB encoding
            $_SESSION['iv'] = $iv = $this->Iv();
        }
        # creates a cipher text compatible with AES (Rijndael block size = 128)
        # to keep the text confidential 
        # only suitable for encoded input that never ends with value 00h
        # (because of default zero padding)
        if(empty($_SESSION['ciphertext'])) {
           $_SESSION['ciphertext'] = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $_SESSION['key'],
            $_SESSION['inputToken'], MCRYPT_MODE_ECB, $_SESSION['iv']);
        }
        if(empty($_SESSION['hexcode'])) {
            # creating hexcode for otp emcryption by app-key
            $_SESSION['hexcode'] = bin2hex($_SESSION['ciphertext']);
        }
            
    }

    public function getCertificateKey()
    {
        if(empty($_SESSION['app_key'])) {
            $pem_private_key = file_get_contents(PROJECT_ROOT.'/modules/api/GSTN_Public_Key/GSTN_G2B_Prod_Public.pem');
            $private_key = openssl_pkey_get_public($pem_private_key);
            $pem_public_key = openssl_pkey_get_details($private_key)['key'];
            $public_key = openssl_pkey_get_public($pem_public_key);
            $encrypted="";
            openssl_public_encrypt($_SESSION['ciphertext'] , $encrypted, $public_key);
            $_SESSION['app_key']=base64_encode($encrypted);  
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
            $_SESSION['hkey'] = $key = pack('H*', $_SESSION['hexcode']);
        }
        $otp_code = $otp;
        $otp_encode =utf8_encode($otp_code);
        $ciphertext_enc = $_SESSION['ciphertext_enc'] = $this->aes256_ecb_encrypt($_SESSION['hkey'],$otp_encode,$_SESSION['iv']);
        return $ciphertext_enc;
    }
    
    public function requestOTP($code='')
    {
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
        //End code for create header
        $url = 'https://gspapi.karvygst.com/Authenticate';
        $result_data= $this->hitUrl($url,$data_string,$header);
        $data = json_decode($result_data);

        $dataGST1['user_id'] = $_SESSION['user_detail']['user_id'];
        $dataGST1['header'] = json_encode(array('header' =>$header, 'data' => $data_string));
        $dataGST1['response'] =  isset($result_data)?$result_data:'';
        $dataGST1['type'] =  'OTP';
        $dataGST1['code'] =  $code;
        $dataGST1['inserted_date'] =  date('Y-m-d H:i:s');
        $this->insert($this->getTableName('otp_request'), $dataGST1);
        
        if(isset($data->status_cd) && $data->status_cd=='1')
        {
            return true;
        }
        else {
            $this->array_key_search('message', $data);
            $msg = $this->error_msg;
            $this->setError($msg);
            return false;
        }
    }
    
    public function authenticateToken($otp,$code='')
    { 

        $otp_code = $_SESSION['otp'] = $otp;
        if(empty($_SESSION['ciphertext_enc'])) {
            $ciphertext_enc = $this->getOTPEncypt($_SESSION['otp']);
        }
        
        $otp = base64_encode($_SESSION['ciphertext_enc']);
        
        $username = $this->username();
        $data = array("username" => $username, "action" => 'AUTHTOKEN', "app_key" => $_SESSION['app_key'], "otp" =>$otp);
        $data_string = json_encode($data);
        
        //Start code for create header
        $header_array = array(
            'Content-Length: ' . strlen($data_string).'',
        );
        $header = $this->header($header_array);
        //End code for create header

        $url = 'https://gspapi.karvygst.com/Authenticate';
        $result_data= $this->hitUrl($url,$data_string,$header);
        $data = json_decode($result_data);

        $dataGST1['user_id'] = $_SESSION['user_detail']['user_id'];
        $dataGST1['header'] = json_encode(array('header' =>$header, 'data' => $data_string));
        $dataGST1['response'] =  isset($result_data)?$result_data:'';
        $dataGST1['type'] =  'AUTHTOKEN';
        $dataGST1['code'] =  $code;
        $dataGST1['inserted_date'] =  date('Y-m-d H:i:s');
        $this->insert($this->getTableName('otp_request'), $dataGST1);
       
        //$this->pr($data);
        if(isset($data->status_cd) && $data->status_cd=='1')
        {
            $session_key = $data->sek;
            $decrypt_sess_key = openssl_decrypt(base64_decode($session_key),"aes-256-ecb",$_SESSION['hkey'], OPENSSL_RAW_DATA);
            $_SESSION['decrypt_sess_key'] = $decrypt_sess_key;
            $_SESSION['auth_token'] = $data->auth_token;
            $_SESSION['auth_date'] = date('Y-m-d H:i:s');
          
            $savedata = array();
            $encode['inputToken'] = $_SESSION['inputToken'];
            $encode['keyhash'] = $_SESSION['keyhash'];
            $encode['key'] = $_SESSION['key'];
            $encode['iv'] = $_SESSION['iv'];
            $encode['ciphertext'] = $_SESSION['ciphertext'];
            $encode['hexcode'] = $_SESSION['hexcode'];
            $encode['hkey'] = $_SESSION['hkey'];
            $encode['app_key'] = $_SESSION['app_key'];
            $encode['otp'] = $_SESSION['otp'];
            $encode['decrypt_sess_key'] = $_SESSION['decrypt_sess_key'];
            $encode['auth_token'] = $_SESSION['auth_token'];
            $encode['ciphertext_enc'] = $_SESSION['ciphertext_enc'];
            $savedata['params'] = base64_encode(serialize($encode));
            $savedata['added_date'] = $_SESSION['auth_date'];
            $this->save_user_gstr1($savedata);
            return true;
        }
        else {
            $this->array_key_search('message', $data);
            $msg = $this->error_msg;
            $error_cd = isset($data->error->error_cd)?$data->error->error_cd:'';

            if($error_cd == 'AUTH4033' || $error_cd == 'AUTH4034' || $error_cd == 'AUTH4038') {
                return $error_cd;
            }
            else {
               $this->setError($msg); 
               return false;
            }
            
        }
    }
    
    public function returnSave($dataArr,$returnmonth,$jstr) {

        $msg = $return_encode = '';
        $error = 1;
        $response = array();
        $json_data = json_encode($dataArr);
        $encodejson=base64_encode(openssl_encrypt(base64_encode($json_data),"aes-256-ecb",$_SESSION['decrypt_sess_key'], OPENSSL_RAW_DATA));
        $hmac = $this->hmac($_SESSION['decrypt_sess_key'],$json_data); 
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
            'ret_prd: '.$api_return_period.' ',
            'username:' . $username . '',
            'accept:application/json',
        );
        $header = $this->header($header_array);
        //End code for create header
        //$this->pr($header);

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
                'ret_prd: '.$api_return_period.' ',
                'username:' . $username . '',
                'accept:application/json',
                'action:' . 'RETSTATUS' . ''
            );
            
            $header2 = $this->header($header2_array);
            //$this->pr($header2);
            //End code for create header
            $url2 = 'https://gspapi.karvygst.com/returns?action=RETSTATUS&gstin='.$gstin. '&ret_period='.$api_return_period.'&ref_id='.$refId.'';
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
                    elseif(isset($jstr1_status['status_cd']) && $jstr1_status['status_cd']=='IP' && $msg == ''){
                        $msg = "Invoices are under procces, kindly wait for some time.";
                        $error = 2;
                    }
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
    public function returnSummary($returnmonth,$type='',$jstr='gstr1')
    {
        if(!empty($_SESSION['auth_token'])) {
            $gstin = $this->gstin();
            $username = $this->username();
            $ctin = $this->ctin();
            $api_return_period = $this->getRetrunPeriodFormat($returnmonth);
            //Start code for create header
            $header2_array = array(
                'auth-token:' . $_SESSION['auth_token'] . '',
                'gstin:' . $gstin . '',
                'ret_prd: '.$api_return_period.' ',
                'username:' . $username . '',
                'accept:application/json',
                'action:' . 'RETSTATUS' . ''
            );
                
            $header2 = $this->header($header2_array);
            //End code for create header

            if($type=='') {
                $getReturnUrl='https://gspapi.karvygst.com/returns/'.$jstr.'?gstin='.$gstin. '&ret_period='.$api_return_period.'&action=RETSUM';
            }
            else {
                $getReturnUrl='https://gspapi.karvygst.com/returns/'.$jstr.'?gstin='.$gstin. '&ret_period='.$api_return_period.'&action='.$type;
            }

            if($type=='B2B') {
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
            
            $result_data_sum = $this->hitGetUrl($getReturnUrl, '', $header2);
            $retDta_sum = json_decode($result_data_sum);
            
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
                        'ret_prd: '.$api_return_period.' ',
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
                    $filedetUrl='https://gspapi.karvygst.com/returns/'.$jstr.'?token=677b0e10557a4f56b2236387cfc24060&action=FILEDET&gstin='.$gstin.'&ret_prd='.$api_return_period.'';
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
                return false;
            }
        }
        else {
            $this->setError("Invalid Auth Token");
            return false;
        }
    }

    public function returnSubmit($returnmonth) {
        $error =1;
        $msg = '';
        $api_return_period = $this->getRetrunPeriodFormat($returnmonth);
        $gstin = $this->gstin();
        $username = $this->username();

        $sub_data='{
          "gstin": "'.$gstin.'",
          "ret_prd": "'.$api_return_period.'"
        }';
        $encodejson = base64_encode(openssl_encrypt(base64_encode($sub_data),"aes-256-ecb",$_SESSION['decrypt_sess_key'], OPENSSL_RAW_DATA));
        $sdata1 = array("action" => 'RETSUBMIT', "data" => $encodejson);

        $data_string = json_encode($sdata1);

        //Start code for create header
        $header_array = array(
            'Content-Length: ' . strlen($data_string).'',
            'auth-token:' . $_SESSION['auth_token'] . '',
            'gstin:' . $gstin . '',
            'ret_prd: '.$api_return_period.' ',
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
            'ret_prd: '.$api_return_period.' ',
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


    public function gstr1UploadSummary($returnmonth,$jstr='gstr1',$encodeJson,$rek) {
        //echo 'json: '.$encodeJson;
        $rek = 'orvyvoONhUC8fVeze8iabN44V0wyd5x1y+0YzhRQqjY=';
        $derypt_rek = base64_decode($rek);
        $final_json = base64_decode(openssl_decrypt(base64_decode($encodeJson),"aes-256-ecb",$derypt_rek, OPENSSL_RAW_DATA));
        return $final_json;
    }
    public function returnDeleteItems($deleteData, $returnmonth, $jstr) 
    {
        $action = 'RETSAVE';
        $deleteData = json_encode($deleteData);
        //echo $deleteData;
        $encodejson=base64_encode(openssl_encrypt(base64_encode($deleteData),"aes-256-ecb",$_SESSION['decrypt_sess_key'], OPENSSL_RAW_DATA));

        $hmac = $this->hmac($_SESSION['decrypt_sess_key'],$deleteData);
        $response = $this->gstCommonRetunSave($encodejson,$hmac,$returnmonth,$jstr); 
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
        if(isset($_SESSION['decrypt_sess_key'])) {
           unset($_SESSION['decrypt_sess_key']); 
        }
        if(isset($_SESSION['auth_date'])) {
           unset($_SESSION['auth_date']); 
        }
        if(isset($_SESSION['hkey'])) {
           unset($_SESSION['hkey']); 
        }
        if(isset($_SESSION['otp'])) {
           unset($_SESSION['otp']); 
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
        if(isset($_SESSION['ciphertext_enc'])) {
           unset($_SESSION['ciphertext_enc']); 
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
                $_SESSION['hkey'] = $decodeData['hkey']; 
                $_SESSION['app_key'] = $decodeData['app_key'];
                $_SESSION['auth_token'] = $decodeData['auth_token'];
                $_SESSION['decrypt_sess_key'] = $decodeData['decrypt_sess_key'];
                $_SESSION['inputToken'] = $decodeData['inputToken'];
                $_SESSION['keyhash'] = $decodeData['keyhash'];
                $_SESSION['key'] = $decodeData['key'];
                $_SESSION['iv'] = $decodeData['iv'];
                $_SESSION['ciphertext'] = $decodeData['ciphertext'];
                $_SESSION['ciphertext_enc'] = $decodeData['ciphertext_enc'];
                $_SESSION['auth_date'] = $user_gstr[0]->updated_date;
                
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
                $dataGST1['updated_date'] =  $data['added_date'];
                $dataGST1['otp_date'] =  $data['added_date'];
                $dataGST1['user_id'] =  $data['user_id'];

                $this->insert($this->getTableName('user_gstr1'), $dataGST1);
            } 
        }   
    }

    public function save_user_summary($data,$key,$returnmonth) {
        if(isset($_SESSION['user_detail']['user_id'])) {
            $user_ustr = $this->get_results("select * from " . $this->getTableName('user_api_summary') ." where 1=1 and  user_id = ".$_SESSION['user_detail']['user_id']." and gst_key = '".$key."' and fmonth = '".$returnmonth."'  ");

            if (!empty($user_ustr)) {
                $dataGST1['json'] = $data['json'];
                $dataGST1['updated_date'] =  date('Y-m-d H:i:s');
                $dataGST1['fmonth'] =  $returnmonth;

                $dataGST1where['user_id'] =  $_SESSION['user_detail']['user_id'];
                $dataGST1where['fmonth'] =  $returnmonth;
                $this->update($this->getTableName('user_api_summary'),  $dataGST1, $dataGST1where);

            } 
            else {
                $dataGST1['gst_key'] =  $key;
                $dataGST1['json'] = $data['json'];
                $dataGST1['added_date'] = date('Y-m-d H:i:s');
                $dataGST1['user_id'] =  $_SESSION['user_detail']['user_id'];
                $dataGST1['fmonth'] =  $returnmonth;
                $this->insert($this->getTableName('user_api_summary'), $dataGST1);
            } 
        }   
    }
    public function get_user_summary($key,$returnmonth) {
        $json = '';
        if(isset($_SESSION['user_detail']['user_id'])) {
            $sql =  "select json from " .$this->getTableName('user_api_summary') ." where user_id = '".$_SESSION['user_detail']['user_id']."' and gst_key = '".$key."' and fmonth = '".$returnmonth."'";
            $user_gstr = $this->get_results($sql);

            if(!empty($user_gstr)) {
                $json = unserialize(base64_decode($user_gstr[0]->json));
            }
            
        }
        
        return $json;
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

    public function modalHtml() {
        ?>
        <div id="otpModalBox" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">       
              
              <div class="modal-body">
              <label>OTP:</label>
               <input id="otp_code" type="textbox" name="otp" class="form-control" data-bind="numeric">
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button id="otpModalBoxSubmit" type="button" value="OTP" class="btn btn-success" >Submit</button>
              </div>
            </div>
          </div>
        </div>
        <script type="text/javascript">
        $( "#otpModalBoxSubmit" ).click(function( event ) {
              var otp = $('#otp_code').val();
              //event.preventDefault();
              if(otp != " ") {
                $.ajax({
                    url: "<?php echo PROJECT_URL; ?>/?ajax=return_gstr1_otp_request",
                    type: "post",
                    data: {otp:otp},
                    success: function (response) {
                        //alert(response);
                        var arr = $.parseJSON(response);
                        if(arr.error_code == 0) {
                            $("#otpModalBox").modal("hide");
                            document.form4.submit();
                        }
                        else {
                            location.reload();
                            return false;
                        }
                    },
                    error: function() {
                        alert("Enter OTP First");
                        return false;
                    }
                });
                return false;
              }
              else {
                alert("Enter OTP First");
                return false;
              }
              return false;
            });
        </script>
        <?php
    }

    public function uploadOtpPopupJs() {
        $this->modalHtml();
        ?>
        <script type="text/javascript">
            $("#up").on("click", function (event) {
                flag=0;
                $(".name").each(function(){
                    if ($(this).prop("checked")==true){ 
                        flag=1;
                    }
                });
                if(flag==1)
                {
                    //event.preventDefault();
                    $.ajax({
                        url: "<?php echo PROJECT_URL; ?>/?ajax=return_gstr1_details_check",
                        type: "json",
                        success: function (response) {
                            //alert(response);
                            if(response == 1) {
                                $("#otpModalBox").modal("show");
                                return false;
                            }
                            else if(response == 0) {
                               document.form4.submit();
                            }
                            else {
                                location.reload();
                                return false;
                            }
                        },
                        error: function() {
                            alert("Please try again.");
                            return false;
                        }
                    });
                    return false;
                    
                }
                else
                {
                    alert("No Invoices are selected?");
                    return false;
                }
                return false;

            });
        </script>
        <?php
    }

    public function DownloadSummaryOtpPopupJs() {
        $this->modalHtml();
        ?>
        <script type="text/javascript">
        $(document).ready(function () {
            $("#gstr1_summary_download",'.gstr1ViewBtn','.gstr1ViewBtn').on("click", function (event) {
                //event.preventDefault();
                $.ajax({
                    url: "<?php echo PROJECT_URL; ?>/?ajax=return_gstr1_details_check",
                    type: "json",
                    success: function (response) {
                        //alert(response);
                        if(response == 1) {
                            $("#otpModalBox").modal("show");
                            return false;
                        }
                        else if(response == 0) {
                           document.form4.submit();
                        }
                        else {
                           location.reload();
                           return false;
                        }
                    },
                    error: function() {
                        alert("Please try again.");
                        return false;
                    }
                });
                return false;

            });
        });

        </script>
        <?php
    }
   
}
