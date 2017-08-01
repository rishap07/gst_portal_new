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

$pem_private_key = file_get_contents('GSTN_Public_Key/GSTN_private.pem');
$private_key = openssl_pkey_get_private($pem_private_key);
$pem_public_key = openssl_pkey_get_details($private_key)['key'];
$public_key = openssl_pkey_get_public($pem_public_key);

$encrypted="";
openssl_public_encrypt($ciphertext , $encrypted, $public_key);

$app_key=base64_encode($encrypted);   //encrypted string
if ($app_key)
{
echo "your App Key is<br>".$app_key."<br><br>";
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



$client_secret = 'fa6f03446473400fa21240241affe2a5';
$clientid = 'l7xx2909cd95daee418b8118e070b6b24dd6';
$ip_usr = '49.50.73.109';
$state_cd='27';
$txn='TXN789123456789';

$username='Cyfuture.MH.TP.1';
$action='AUTHTOKEN';
//$action='OTPREQUEST';

//$data = array("username" => $username, "action" => $action, "app_key" => $app_key);
$data = array("username" => $username, "action" => $action, "app_key" => $app_key, "otp" =>$otp);

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

 //$url='http://gsp.karvygst.com/Authenticate/RequestToken';
//    $url='http://gsp.karvygst.com/Authenticate/RequestOTP';
    $url=  'http://gsp.karvygst.com/v0.3/authenticate';
  
function hitUrl($url,$data_string,$header) {
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
        $result = curl_exec($ch);
        curl_close($ch);
        //echo $result;  
        return $result;     

    }
  $result_data= hitUrl($url,$data_string,$header);
  $data=json_decode($result_data);
  echo "Your auth_token  <br>" .$data->auth_token."<br><br>";
   $session_key=$data->sek;

 echo "Your session_key  <br>" .$session_key."<br><br>";
    # --- DECRYPTION ---


$result_data = hitUrl($url, $data_string, $header);
$data = json_decode($result_data);
print_r($data);
echo "<br><br>Your auth_token  <br />" . $data->auth_token . "<br /><br />";
$session_key = $data->sek;
echo "Your seeion key :  <br />" . $data->sek . "<br /><br />";

// --- DECRYPTION  OF SESSION KEY START---

//$ciphertext_dec = base64_decode($session_key);

$decrypt_sess_key=openssl_decrypt(base64_decode($session_key),"aes-256-ecb",$key, OPENSSL_RAW_DATA);


/*echo "dec is<br /> $ciphertext_dec <br /><br />";

function aes256_ecb_decrypt($key, $data, $iv)
  {
  if (32 !== strlen($key)) $key = hash('SHA256', $key, true);
  if (16 !== strlen($iv)) $iv = hash('MD5', $iv, true);
  $padding = 16 - (strlen($data) % 16);
  $data.= str_repeat(chr($padding) , $padding);
  return mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_ECB, $iv);
  }

$decrypt_sess_key = aes256_ecb_decrypt($key, $ciphertext_dec, $iv);*/
//$decrypt_sess_key = base64_encode($decrypt_sess_key);
echo "your decrypt_sess_key is<br />" . $decrypt_sess_key . "<br /><br />";

// --- DECRYPTION  OF SESSION KEY END---


//{"gstin":"27GSPMH3941G1ZK","fp":"062017","gt":3782969.01,"cur_gt":3782969.01,"b2b":[{"ctin":"27GSPMH3941G1ZK","inv":[{"inum":"S008400","idt":"24-06-2017","val":729248.16,"pos":"27","rchrg":"N","etin":"","inv_typ":"R","itms":[{"num":1,"itm_det":{"rt":5,"txval":1000,"camt":833.33,"samt":833.33,"csamt":500}}]}]}]}

$json_data='{
  "gstin": "27GSPMH3941G1ZK",
  "fp": "062017",
  "gt": 3782969.00,
  "cur_gt": 3782969.00,
  "b2b": [
    {
      "ctin": "27GSPMH0151G1ZW",
      "inv": [
        {
          "inum": "KirloskarSale1",
          "idt": "01-06-2017",
          "val": 45344.24,
          "pos": "27",
          "inv_typ": "R",
          "rchrg": "N",
          "itms": [
            {
              "num": 2,
              "itm_det": {
                "rt": 5.0,
                "txval": 4365465.0,
                "camt":218273.25,
                "samt":218273.25,                
                "csamt": 0.0
              }
            },
            {
              "num": 1,
              "itm_det": {
                "rt": 12.0,
                "txval": 453445.0,
                "camt":54413.4,
                "samt":54413.4,
                "csamt": 0.0
              }
            }
          ]
        }
      ]
    }
  ]
} ';



/*$json_data1['gstin'] = '27GSPMH3941G1ZK';
$json_data1['fp'] = '072017';
$json_data1['gt'] = '3782969.01';
$json_data1['cur_gt'] = '3782969.01';
$json_data1['b2b'][0]['ctin'] = '08AABCC7015R1ZB';
$json_data1['b2b'][0]['inv'][0]['inum'] = 'S008400';
$json_data1['b2b'][0]['inv'][0]['idt'] = '01-07-2017';
$json_data1['b2b'][0]['inv'][0]['val'] = '729248.16';
$json_data1['b2b'][0]['inv'][0]['pos'] = '08';
$json_data1['b2b'][0]['inv'][0]['rchrg'] = 'N';
$json_data1['b2b'][0]['inv'][0]['inv_typ'] = 'R';
$json_data1['b2b'][0]['inv'][0]['itms'][0]['num'] = 1;
$json_data1['b2b'][0]['inv'][0]['itms'][0]['itm_det']['rt'] = '18.00';
$json_data1['b2b'][0]['inv'][0]['itms'][0]['itm_det']['txval'] = '1000.00';
$json_data1['b2b'][0]['inv'][0]['itms'][0]['itm_det']['camt'] = '90.00';
$json_data1['b2b'][0]['inv'][0]['itms'][0]['itm_det']['samt'] = '90.00';*/

//$json_data = json_encode($json_data1);
$encodejson=base64_encode(openssl_encrypt(base64_encode($json_data),"aes-256-ecb",$decrypt_sess_key, OPENSSL_RAW_DATA));

echo "your json data is<br />" . $json_data . "<br /><br />";
echo "<pre>";
var_dump($json_data);
echo "</pre>";

/************************** json_data  encription ***********************/
// $json_encode = utf8_encode($json_data);
// $base64Payload = base64_encode($json_encode);
// $jsonData = utf8_encode($base64Payload);
// $json_encode_enc = aes256_ecb_encrypt($decrypt_sess_key, $jsonData, $iv);
// $encodejson = base64_encode($json_encode_enc);
echo "your encodejson is<br />" . $encodejson . "<br /><br />";



/***************************hmac Genration*********************************************/

$hmac = base64_encode(hash_hmac('sha256', base64_encode($json_data), $decrypt_sess_key, true));
echo "your hmac is<br />" . $hmac . "<br /><br />";

/***************************hitCurl*********************************************/
$client_secret = 'fa6f03446473400fa21240241affe2a5';
$clientid = 'l7xx2909cd95daee418b8118e070b6b24dd6';
$ip_usr = '49.50.73.109';
$state_cd = '27';
$txn = 'TXN789123456789';
$gstin = '27GSPMH3941G1ZK';
$username = 'Cyfuture.MH.TP.1';
$action = 'RETSAVE';

// $action='OTPREQUEST';
// $data = array("username" => $username, "action" => $action, "app_key" => $app_key);

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
  'ret_prd:062017 ',
  'state-cd:' . $state_cd . '',
  'txn:' . $txn . '',
  'username:' . $username . '',
  'karvyclientid:' . $clientid . '',
  'karvyclient-secret:' . $client_secret . ''
);
$url1 = 'http://gsp.karvygst.com/v0.3/returns/gstr1';

  //$url1='http://gsp.karvygst.com/GSTR1/RETSAVE';

function hitUrl1($url, $data_string, $header)
  {
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
  $result = curl_exec($ch);
  curl_close($ch);

  // echo $result;

  return $result;
  }

$result_data1 = hitUrl1($url1, $data_string1, $header1);
$datasave = json_decode($result_data1);
print_r($datasave)."<br><br>";
$retData=$datasave->data;
$rek=$datasave->rek;

$apiEk=openssl_decrypt(base64_decode($rek),"aes-256-ecb",$decrypt_sess_key, OPENSSL_RAW_DATA);

$decodejson= base64_decode(openssl_decrypt(base64_decode($retData),"aes-256-ecb",$apiEk, OPENSSL_RAW_DATA));


$ref = json_decode($decodejson);
print_r($datasave)."<br><br>";
$refId=$ref->reference_id;

echo "<br>refId<br>$refId<br><br>";




$header2 = array(
  'accept:application/json',
  'auth-token:' . $data->auth_token . '',
  'client-secret:' . $client_secret . '',
  'clientid:' . $clientid . '',
  'Content-Type: application/json',
  'gstin:' . $gstin . '',
  'ip-usr:' . $ip_usr . '',
  'ret_prd:062017',
  'state-cd:' . $state_cd . '',
  'txn:' . $txn . '',
  'username:' . $username . '',
  'karvyclientid:' . $clientid . '',
  'action:' . 'RETSTATUS' . '',
  'karvyclient-secret:' . $client_secret . ''
);
$url2 = 'http://gsp.karvygst.com/v0.3/returns?action=RETSTATUS&gstin='.$gstin. '&ret_period=062017&ref_id='.$refId.'';

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

$result_data1 = hitUrl2($url2, '', $header2);
$retDta = json_decode($result_data1);
print_r($retDta)."<br><br>";

$retRek=$retDta->rek;
$retData1=$retDta->data;

$apiEk1=openssl_decrypt(base64_decode($retRek),"aes-256-ecb",$decrypt_sess_key, OPENSSL_RAW_DATA);

echo "<br>decode json1<br>$apiEk1<br><br>";

$decodejson1= base64_decode(openssl_decrypt(base64_decode($retData1),"aes-256-ecb",$apiEk1, OPENSSL_RAW_DATA));

echo "<br>decode json1<br>$decodejson1<br><br>";

$getReturnUrl='http://gsp.karvygst.com/v0.3/returns/gstr1?gstin='.$gstin. '&ctin=27GSPMH0151G1ZW&ret_period=062017&action=B2B';
$result_data1 = hitUrl2($getReturnUrl, '', $header2);
$retDta = json_decode($result_data1);
print_r($retDta)."<br><br>";

$retRek=$retDta->rek;
$retData1=$retDta->data;

$apiEk1=openssl_decrypt(base64_decode($retRek),"aes-256-ecb",$decrypt_sess_key, OPENSSL_RAW_DATA);

echo "<br>decode json1<br>$apiEk1<br><br>";

$decodejson1= base64_decode(openssl_decrypt(base64_decode($retData1),"aes-256-ecb",$apiEk1, OPENSSL_RAW_DATA));

echo "<br>get invoice data<br>$decodejson1<br><br>";
?>