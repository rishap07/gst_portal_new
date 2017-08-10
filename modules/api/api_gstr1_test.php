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

$client_secret = 'a9bcf665fe424883b7b94791eb31f667';
$clientid = 'l7xx1ed437f1e18347c38bd2aad6e6dd3b3c';
$ip_usr = '203.197.205.110';
$state_cd='33';
$txn='TXN789123456789';
$username='Cyfuture.TN.TP.1';
$action='AUTHTOKEN';

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
  "gt": 3782969.01,
  "cur_gt": 3782969.01,
  "b2b": [
    {
      "ctin": "27GSPMH4901G1ZQ",
      "inv": [
        {
          "inum": "S008400",
          "idt": "24-04-2016",
          "val": 729248.16,
          "pos": "27",
          "rchrg": "N",
          "etin": "33GSPTN1272G1ZB",
          "inv_typ": "R",
          "itms": [
            {
              "num": 1,
              "itm_det": {
                "rt": 5,
                "txval": 10000,
                "iamt": 833.33,
                "csamt": 500
              }
            }
          ]
        }
      ]
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
$gstin = '33GSPTN3941G1Z7';
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
  'ret_period: 082017 ',
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
print_r($datasave)."<br><br>";

?>