<?php
//
//$obj_api =  new gstr();
//$inputToken = $obj_api->RandomToken(16);
//$keyhash = $obj_api->RandomKey(32);
//    $key = pack('H*',$keyhash);
//
//    # create a random IV to use with CBC encoding
//    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
//    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
//    
//    # creates a cipher text compatible with AES (Rijndael block size = 128)
//    # to keep the text confidential 
//    # only suitable for encoded input that never ends with value 00h
//    # (because of default zero padding)
//    $ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key,
//                                 $inputToken, MCRYPT_MODE_ECB, $iv);
//   
//    # creating hexcode for otp emcryption by app-key
//
//    $hexcode= bin2hex($ciphertext);
//
//$pem_private_key = file_get_contents(PROJECT_ROOT.'/modules/api/GSTN_Public_Key/GSTN_private.pem');
//$private_key = openssl_pkey_get_private($pem_private_key);
//$pem_public_key = openssl_pkey_get_details($private_key)['key'];
//$public_key = openssl_pkey_get_public($pem_public_key);
//
//$encrypted="";
//openssl_public_encrypt($ciphertext , $encrypted, $public_key);
//
//$app_key=base64_encode($encrypted);   //encrypted string
//if ($app_key)
//{
////echo "your App Key is<br>".$app_key."<br><br>";
//}
//
//    $key = pack('H*', $hexcode);
//
//    
//    $otp_code = '575757';
//    $otp_encode =utf8_encode($otp_code);
//
//    # create a random IV to use with ECB encoding
//    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
//    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
//    
//
//    $ciphertext_enc= aes256_ecb_encrypt($key,$otp_encode,$iv);
//
//function aes256_ecb_encrypt($key, $data, $iv) {
//  if(32 !== strlen($key)) $key = hash('SHA256', $key, true);
//  if(16 !== strlen($iv)) $iv = hash('MD5', $iv, true);
//  $padding = 16 - (strlen($data) % 16);
//  $data .= str_repeat(chr($padding), $padding);
//  return mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_ECB, $iv);
//}
//
//$otp = base64_encode($ciphertext_enc);
//
//if ($app_key)
//{
////echo "your otp is<br>".$otp."<br><br>";
//}
//
//
//
//$client_secret = 'fa6f03446473400fa21240241affe2a5';
//$clientid = 'l7xx2909cd95daee418b8118e070b6b24dd6';
//$ip_usr = '49.50.73.109';
//$state_cd='27';
//$txn='TXN789123456789';
//
//$username='Cyfuture.MH.TP.1';
//$action='AUTHTOKEN';
////$action='OTPREQUEST';
//
////$data = array("username" => $username, "action" => $action, "app_key" => $app_key);
//$data = array("username" => $username, "action" => $action, "app_key" => $app_key, "otp" =>$otp);
//
//$data_string = json_encode($data);
//$header= array(
//    'client-secret: '.$client_secret.'',
//    'Content-Length: ' . strlen($data_string),
//	'clientid: '.$clientid.'',
//	'Content-Type: application/json',
//	'ip-usr: '.$ip_usr.'',
//	'state-cd: '.$state_cd.'',
//	'txn: '.$txn.'');
//
//	$url='http://gsp.karvygst.com/Authenticate/RequestToken';
////	$url='http://gsp.karvygst.com/Authenticate/RequestOTP';
//   
//
//   $result_data= $obj_api->hitUrl($url,$data_string,$header);
//   echo $result_data;

$obj_api =  new gstr();
if(isset($_POST['submit1']) && $_POST['submit1']=='Request OTP')
{
    $obj_api->requestOTP();
}
if(isset($_POST['submit']) && $_POST['submit']=='Enter OTP')
{
    $obj_api->submitOTP();

    
}
?><?php $obj_api->showErrorMessage(); ?>
        <?php $obj_api->showSuccessMessge(); ?>
        <?php $obj_api->unsetMessage(); ?>
<?php
$echo1 ='{
  "gstin": "27AHQPA7588L1ZJ",
  "fp": "122016",
  "gt": 3782969.01,
  "b2b": [
    {
      "ctin": "01AABCE2207R1Z5",
      "inv": [
        {
          "inum": "S008400",
          "idt": "24-11-2016",
          "val": 729248.16,
          "pos": "06",
          "rchrg": "N",
          "prs": "Y",
          "od_num": "DR008400",
          "od_dt": "20-11-2016",
          "etin": "01AABCE5507R1Z4",
          "itms": [
            {
              "num": 1,
              "itm_det": {
                "ty": "G",
                "hsn_sc": "G1221",
                "txval": 10000,
                "irt": 3,
                "iamt": 833.33,
                "crt": 4,
                "camt": 500,
                "srt": 5,
                "samt": 900,
                "csrt": 2,
                "csamt": 500
              }
            }
          ]
        }
      ]
    }
  ]
}';
?>
<form method="post">
    <input type="submit" name="submit1" value="Request OTP">
</form>
<form method="post">
    <input type="text" placeholder="OTP" name="otp"><br>
    <input type="submit" name="submit" value="Enter OTP">
</form>
