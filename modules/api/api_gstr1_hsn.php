<?php
function RandomToken($length){
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
function RandomKey($length){
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

$inputToken = RandomToken(16);
$keyhash = RandomKey(32);
   $key = pack('H*',$keyhash);

   # create a random IV to use with CBC encoding
   $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
   $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);

   $ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key,
                                $inputToken, MCRYPT_MODE_ECB, $iv);

   $hexcode= bin2hex($ciphertext);

$pem_private_key = file_get_contents(PROJECT_ROOT.'/modules/api/GSTN_Public_Key/GSTN_private.pem');
$private_key = openssl_pkey_get_private($pem_private_key);
$pem_public_key = openssl_pkey_get_details($private_key)['key'];
$public_key = openssl_pkey_get_public($pem_public_key);

$encrypted="";
openssl_public_encrypt($ciphertext , $encrypted, $public_key);

$app_key=base64_encode($encrypted);   //encrypted string
if ($app_key)
{
//echo "your App Key is<br>".$app_key."<br><br>";
}

   $key = pack('H*', $hexcode);

   
   $otp_code = '575757';
   $otp_encode =utf8_encode($otp_code);


   $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
   $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
   

   $ciphertext_enc= aes256_ecb_encrypt($key,$otp_encode,$iv);

function aes256_ecb_encrypt($key, $data, $iv) {
 if(32 !== strlen($key)) $key = hash('SHA256', $key, true);
 if(16 !== strlen($iv)) $iv = hash('MD5', $iv, true);
 $padding = 16 - (strlen($data) % 16);
 $data .= str_repeat(chr($padding), $padding);
 return mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_ECB, $iv);
}

$otp = base64_encode($ciphertext_enc);

if ($otp)
{
echo "your otp is<br>".$otp."<br><br>";
}



$client_secret = 'a9bcf665fe424883b7b94791eb31f667';
$clientid = 'l7xx1ed437f1e18347c38bd2aad6e6dd3b3c';
$ip_usr = '203.197.205.110';
$state_cd='33';
$txn='TXN789123456789';
$username='Cyfuture.TN.TP.1';
$action='AUTHTOKEN';
//$action='OTPREQUEST';

//$data = array("username" => $username, "action" => $action, "app_key" => $app_key);
$dataOTPReq = array("username" => $username, "action" => 'OTPREQUEST', "app_key" => $app_key);
$otpReq_string = json_encode($dataOTPReq);

$data = array("username" => $username, "action" => $action, "app_key" => $app_key, "otp" =>$otp);

$data_string = json_encode($data);
$header= array(
   'client-secret: '.$client_secret.'',
   'Content-Length: ' . strlen($data_string),
 'clientid: '.$clientid.'',
 'Content-Type: application/json',
 'ip-usr: '.$ip_usr.'',
 'state-cd: '.$state_cd.'',
 'txn: '.$txn.'');

    $url=  'http://devapi.gstsystem.co.in/taxpayerapi/v0.2/authenticate';
  
function hitUrl($url,$data_string,$header) {
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
        $result = curl_exec($ch);
        curl_close($ch);
         
        return $result;     

    }
/*    $result_otpReq_data= hitUrl($url,$otpReq_string,$header);
    $result_otpReq_data=json_decode($result_otpReq_data);
    print_r($result_otpReq_data)."<br>";*/
  $result_data= hitUrl($url,$data_string,$header);
  $data=json_decode($result_data);

print_r($data);

$result_data = hitUrl($url, $data_string, $header);
$data = json_decode($result_data);

$session_key = $data->sek;


$decrypt_sess_key=openssl_decrypt(base64_decode($session_key),"aes-256-ecb",$key, OPENSSL_RAW_DATA);

echo "your decrypt_sess_key is<br />" . $decrypt_sess_key . "<br /><br />";

$json_data='{
  "gstin": "33GSPTN3941G1Z7",
  "fp": "042016",
  "gt": 3782969.00,
  "cur_gt": 3782969.00,
  "b2cs": [
    {
      "sply_ty": "INTRA",
      "rt": 5,
      "typ": "OE",
      "pos": "33",
      "txval": 110,
      "camt": 10,
       "samt": 10,
      "csamt": 10
    },
    {
      "rt": 5,
      "sply_ty": "INTRA",
      "typ": "OE",
      "pos": "33",
      "txval": 220,
      "camt": 20,
      "samt": 10,
      "csamt": 10
    }
  ]
}';
$encodejson=base64_encode(openssl_encrypt(base64_encode($json_data),"aes-256-ecb",$decrypt_sess_key, OPENSSL_RAW_DATA));

//echo "your json data is<br />" . $json_data . "<br /><br />";
//echo "<pre>";
//var_dump($json_data);
//echo "</pre>";

//echo "your encodejson is<br />" . $encodejson . "<br /><br />";

/***************************hmac Genration*********************************************/

$hmac = base64_encode(hash_hmac('sha256', base64_encode($json_data), $decrypt_sess_key, true));
echo "your hmac is<br />" . $hmac . "<br /><br />";

/***************************hitCurl*********************************************/
$client_secret = 'a9bcf665fe424883b7b94791eb31f667';
$clientid = 'l7xx1ed437f1e18347c38bd2aad6e6dd3b3c';
$ip_usr = '203.197.205.110';
$state_cd = '33';
$txn = 'TXN789123456789';
$gstin  = '33GSPTN3941G1Z7';
$username = 'Cyfuture.TN.TP.1';
$action = 'RETSAVE';


$data1 = array(
  "action" => $action,
  "data" => $encodejson,
  "hmac" => $hmac
);
$data_string1 = json_encode($data1);
$header1 = array(
  'accept:application/json',
  'auth-token:' . $data->auth_token . '',
  'client-secret:' . $client_secret . '',
  'clientid:' . $clientid . '',
  'Content-Type: application/json',
  'gstin:' . $gstin . '',
  'ip-usr:' . $ip_usr . '',
  'ret_period: 042016 ',
  'state-cd:' . $state_cd . '',
  'txn:' . $txn . '',
  'username:' . $username . ''
);
$url1 = 'http://devapi.gstsystem.co.in/taxpayerapi/v0.3/returns/gstr1';

function hitUrl1($url, $data_string, $header)
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

$result_data1 = hitUrl1($url1, $data_string1, $header1);
$datasave = json_decode($result_data1);
echo "RETSAVE<br>";
print_r($datasave);
echo "<br><br>";
$retData=$datasave->data;
$rek=$datasave->rek;

$apiEk=openssl_decrypt(base64_decode($rek),"aes-256-ecb",$decrypt_sess_key, OPENSSL_RAW_DATA);

$decodejson= base64_decode(openssl_decrypt(base64_decode($retData),"aes-256-ecb",$apiEk, OPENSSL_RAW_DATA));









$ref = json_decode($decodejson);
echo "reference_id<br>";
print_r($ref);
echo "<br><br>";
$refId=$ref->reference_id;

//echo "<br>refId<br>$refId<br><br>";

$header2 = array(
  'accept:application/json',
  'auth-token:' . $data->auth_token . '',
  'client-secret:' . $client_secret . '',
  'clientid:' . $clientid . '',
  'Content-Type: application/json',
  'gstin:' . $gstin . '',
  'ip-usr:' . $ip_usr . '',
  'ret_period:042016',
  'state-cd:' . $state_cd . '',
  'txn:' . $txn . '',
  'username:' . $username . '',
  'action:' . 'RETSTATUS' . ''
);
$url2 = 'http://devapi.gstsystem.co.in/taxpayerapi/v0.3/returns?action=RETSTATUS&gstin='.$gstin. '&ret_period=042016&ref_id='.$refId.'';

  //$url1='http://gsp.karvygst.com/GSTR1/RETSAVE';

function hitUrl2($url, $data_string, $header)
  {
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
  $result = curl_exec($ch);
  curl_close($ch);

  // echo $result;

  return $result;
  }
sleep(5);
$result_data1 = hitUrl2($url2, '', $header2);
$retDta = json_decode($result_data1);
print_r($retDta)."<br><br>";
$retRek=$retDta->rek;
$retData1=$retDta->data;

$apiEk1=openssl_decrypt(base64_decode($retRek),"aes-256-ecb",$decrypt_sess_key, OPENSSL_RAW_DATA);
$decodejson1= base64_decode(openssl_decrypt(base64_decode($retData1),"aes-256-ecb",$apiEk1, OPENSSL_RAW_DATA));

echo "<br>RETSTATUS<br>$decodejson1<br><br>";
echo "<pre>";
//var_dump(json_decode($decodejson1));
echo "</pre>";

$getReturnUrl='http://devapi.gstsystem.co.in/taxpayerapi/v0.3/returns/gstr1?gstin='.$gstin. '&ret_period=042016&action=RETSUM';
$result_data1 = hitUrl2($getReturnUrl, '', $header2);
$retDta = json_decode($result_data1);
//echo "gstr1 Summary<br>";
//print_r($retDta)."<br><br>";

$retRek=$retDta->rek;
$retData1=$retDta->data;

$apiEk1=openssl_decrypt(base64_decode($retRek),"aes-256-ecb",$decrypt_sess_key, OPENSSL_RAW_DATA);

//echo "<br>decode json1<br>$apiEk1<br><br>";

$decodejson1= base64_decode(openssl_decrypt(base64_decode($retData1),"aes-256-ecb",$apiEk1, OPENSSL_RAW_DATA));
echo "GET SUMMARY<br><pre>";
//echo $decodejson1;die;
$retDta = json_decode($decodejson1);
print_r($retDta)."<br><br><br>";

/******************************FOR GSTR1 FILE**********************************/

/*$reqPayLoad='{
  "gstin": "33GSPTN3941G1Z7",
  "ret_period": "042016",
  "summ_typ": "L",
  "chksum": "bf38398c3bd9f332278f7c0dfa67bcdb3b894a4b47878080fbd7c8fc6cdc713c",
  "sec_sum": [
    {
      "sec_nm": "B2CS",
      "chksum": "22f9adf47851bf3c02f1d4804f7d013af7b61b41e41b2afd4f10190e4b3857e5",
      "ttl_rec": 1,
      "ttl_val": 260,
      "ttl_igst": 0,
      "ttl_cgst": 20,
      "ttl_sgst": 10,
      "ttl_cess": 10,
      "ttl_tax": 220,

    }
  ]
}';


$encodejson=base64_encode(openssl_encrypt(base64_encode($reqPayLoad),"aes-256-ecb",$decrypt_sess_key, OPENSSL_RAW_DATA));
$sdata1 = array("action" => 'RETFILE', "data" => $encodejson);

$data_string = json_encode($sdata1);
$header3 = array(
  'accept:application/json',
  'auth-token:' . $data->auth_token . '',
  'client-secret:' . $client_secret . '',
  'clientid:' . $clientid . '',
  'Content-Type: application/json',
  'gstin:' . $gstin . '',
  'ip-usr:' . $ip_usr . '',
  'ret_period: 042016 ',
  'state-cd:' . $state_cd . '',
  'txn:' . $txn . '',
  'username:' . $username . '',
  'action:RETFILE'
);
$getSubmitUrl='http://devapi.gstsystem.co.in/taxpayerapi/v0.3/returns/gstr1';
$submit_data1 = hitUrl($getSubmitUrl, $data_string, $header3);
$retDta1 = json_decode($submit_data1);
echo "RETFILE:<br>";
print_r($retDta1)."<br><br>";


/***************************SUBMIT GSTR1 ****************************/
$sub_data='{
  "gstin": "'.$gstin.'",
  "ret_period": "042016"
}';
$encodejson=base64_encode(openssl_encrypt(base64_encode($sub_data),"aes-256-ecb",$decrypt_sess_key, OPENSSL_RAW_DATA));
$sdata1 = array("action" => 'RETSUBMIT', "data" => $encodejson);

$data_string = json_encode($sdata1);
$header3 = array(
  'accept:application/json',
  'auth-token:' . $data->auth_token . '',
  'client-secret:' . $client_secret . '',
  'clientid:' . $clientid . '',
  'Content-Type: application/json',
  'gstin:' . $gstin . '',
  'ip-usr:' . $ip_usr . '',
  'ret_period: 042016 ',
  'state-cd:' . $state_cd . '',
  'txn:' . $txn . '',
  'username:' . $username . '',
  'action:RETSUBMIT'
);
$getSubmitUrl='http://devapi.gstsystem.co.in/taxpayerapi/v0.3/returns/gstr1';
$submit_data1 = hitUrl($getSubmitUrl, $data_string, $header3);
$retDta1 = json_decode($submit_data1);
echo "SUBMIT<br>";
print_r($retDta1)."<br><br>";
/************END***************************************************/
?>