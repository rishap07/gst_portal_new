<?php
$obj_api =  new gstr();
function RandomToken($length) {
  if (!isset($length) || intval($length) <= 8) {
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

function RandomKey($length) {
  if (!isset($length) || intval($length) <= 8) {
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
$key = pack('H*', $keyhash);

# create a random IV to use with CBC encoding
$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);

$ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $inputToken, MCRYPT_MODE_ECB, $iv);

$hexcode = bin2hex($ciphertext);

$pem_private_key = file_get_contents(PROJECT_ROOT . '/modules/api/GSTN_Public_Key/GSTN_private.pem');
$private_key = openssl_pkey_get_private($pem_private_key);
$pem_public_key = openssl_pkey_get_details($private_key)['key'];
$public_key = openssl_pkey_get_public($pem_public_key);

$encrypted = "";
openssl_public_encrypt($ciphertext, $encrypted, $public_key);

$app_key = base64_encode($encrypted);   //encrypted string

$key = pack('H*', $hexcode);

$otp_code = '575757';
$otp_encode = utf8_encode($otp_code);

$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);

$ciphertext_enc = aes256_ecb_encrypt($key, $otp_encode, $iv);

function aes256_ecb_encrypt($key, $data, $iv) {
  if (32 !== strlen($key))
      $key = hash('SHA256', $key, true);
  if (16 !== strlen($iv))
      $iv = hash('MD5', $iv, true);
  $padding = 16 - (strlen($data) % 16);
  $data .= str_repeat(chr($padding), $padding);
  return mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_ECB, $iv);
}

$otp = base64_encode($ciphertext_enc);

$client_secret = 'fa6f03446473400fa21240241affe2a5';
$clientid = 'l7xx2909cd95daee418b8118e070b6b24dd6';
$ip_usr = '49.50.73.109';
$state_cd = '27';
$txn = 'TXN789123456789';

$username = 'Cyfuture.MH.TP.1';
$action = 'AUTHTOKEN';
$data = array("username" => $username, "action" => $action, "app_key" => $app_key, "otp" => $otp);

$data_string = json_encode($data);
$header = array(
  'client-secret: ' . $client_secret . '',
  'Content-Length: ' . strlen($data_string),
  'clientid: ' . $clientid . '',
  'Content-Type: application/json',
  'ip-usr: ' . $ip_usr . '',
  'state-cd: ' . $state_cd . '',
  'txn: ' . $txn . '',
  'karvyclientid: ' . $clientid . '',
  'karvyclient-secret: ' . $client_secret . '');

$url = 'http://gsp.karvygst.com/v0.3/authenticate';

function hitUrl($url, $data_string, $header) {
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
  $result = curl_exec($ch);
  curl_close($ch);

  return $result;
}

$result_data = hitUrl($url, $data_string, $header);
$data = json_decode($result_data);

# --- DECRYPTION ---
$query =  "select a.company_state,a.invoice_date,a.invoice_total_value,b.item_name,a.supply_place,a.invoice_type,b.item_hsncode,b.item_quantity,b.item_unit,b.taxable_subtotal, sum(b.igst_amount) as igst_amount, sum(b.cgst_amount) as cgst_amount, sum(b.sgst_amount) as sgst_amount,sum(b.cess_amount) as cess_amount from ".TAB_PREFIX."client_invoice a inner join ".TAB_PREFIX."client_invoice_item b on a.invoice_id=b.invoice_id where a.is_gstr1_uploaded='0' and a.status='1' and a.added_by='".$_SESSION['user_detail']['user_id']."' and a.invoice_date like '%2017-06%' group by b.item_hsncode";

//echo $query;
$dataInv = $obj_api->get_results($query);
//$obj_api->pr($dataInv); 
$dataArr = array();
$dataArr["gstin"]= "27GSPMH3941G1ZK";
$dataArr["fp"]= "062017";
$dataArr["gt"]= (float)"53782969.00";
$dataArr["cur_gt"]= (float)"53782969.00";
if(isset($dataInv))
{
  $x=0;
  $y=0;
  $a=1;
  $temp_number='';
  $ctin = '';
  foreach($dataInv as $dataIn)
  {
    $dataArr['hsn']['data'][$y]['num']=(int)$a;
    $dataArr['hsn']['data'][$y]['hsn_sc']=$dataIn->item_hsncode;
    $dataArr['hsn']['data'][$y]['desc']= $dataIn->item_name;
    $dataArr['hsn']['data'][$y]['uqc']= $dataIn->item_unit;
    $dataArr['hsn']['data'][$y]['qty']= (float)$dataIn->item_quantity;
    $dataArr['hsn']['data'][$y]['val']=(float)$dataIn->invoice_total_value;
    $dataArr['hsn']['data'][$y]['txval']=(float)$dataIn->taxable_subtotal;
    $dataArr['hsn']['data'][$y]['iamt']=(float)$dataIn->igst_amount;
    $dataArr['hsn']['data'][$y]['samt']=(float)$dataIn->sgst_amount;
    $dataArr['hsn']['data'][$y]['camt']=(float)$dataIn->cgst_amount;
    $dataArr['hsn']['data'][$y]['csamt']=(float)$dataIn->cess_amount;
    $a++;
    $y++;

  }
}

$json_data = json_encode($dataArr);

//Start Code For NIL Payload
$query1 =  "select a.company_state,a.billing_gstin_number,a.reference_number,a.supply_place,a.invoice_date,a.invoice_total_value,b.item_name,a.invoice_type,b.taxable_subtotal, sum(b.igst_amount) as igst_amount, sum(b.cgst_amount) as cgst_amount, sum(b.sgst_amount) as sgst_amount,sum(b.cess_amount) as cess_amount from ".TAB_PREFIX."client_invoice a inner join ".TAB_PREFIX."client_invoice_item b on a.invoice_id=b.invoice_id  where a.is_gstr1_uploaded='0' and a.status='1' and a.added_by='".$_SESSION['user_detail']['user_id']."' and a.invoice_date like '%2017-06%' and a.billing_gstin_number!='' ";

$query2 =  "select a.company_state,a.billing_gstin_number,a.reference_number,a.supply_place,a.invoice_date,a.invoice_total_value,b.item_name,a.invoice_type,b.taxable_subtotal, sum(b.igst_amount) as igst_amount, sum(b.cgst_amount) as cgst_amount, sum(b.sgst_amount) as sgst_amount,sum(b.cess_amount) as cess_amount from ".TAB_PREFIX."client_invoice a inner join ".TAB_PREFIX."client_invoice_item b on a.invoice_id=b.invoice_id  where a.is_gstr1_uploaded='0' and a.status='1' and a.added_by='".$_SESSION['user_detail']['user_id']."' and a.invoice_date like '%2017-06%' and a.billing_gstin_number='' ";

$dataInv1 = $obj_api->get_results($query1);
$dataInv2 = $obj_api->get_results($query2);

$dataArrNil = $nill_inv_array_b2b = $nill_inv_array_b2c =  array();
$dataArrNil["gstin"]= "27GSPMH3941G1ZK";
$dataArrNil["fp"]= "062017";
$dataArrNil["gt"]= (float)"53782969.00";
$dataArrNil["cur_gt"]= (float)"53782969.00";
if(isset($dataInv1))
{
  $x=0;
  $y=0;
  $a=1;
  $temp_number='';
  $ctin = '';
  foreach($dataInv1 as $dataIn)
  {
    if($dataIn->company_state!=$dataIn->supply_place)
    {
      $nill_inv_array_b2b[$y]['sply_ty']='INTERB2B';
    }
    else
    {
      $nill_inv_array_b2b[$y]['sply_ty']='INTRAB2B';
    }
    $nill_inv_array_b2b[$y]['expt_amt'] = (float)0;
    $nill_inv_array_b2b[$y]['nil_amt'] = (float)0;
    $nill_inv_array_b2b[$y]['ngsup_amt'] = (float)0;
    $y++;

  }
}

if(isset($dataInv2)) {
  foreach($dataInv2 as $dataIn){
    if($dataIn->company_state!=$dataIn->supply_place)
    {
      $nill_inv_array_b2c[$x]['sply_ty']='INTERB2C';
    }
    else
    {
      $nill_inv_array_b2c[$x]['sply_ty']='INTRAB2C';
    }
    $nill_inv_array_b2c[$x]['expt_amt'] = (float)0;
    $nill_inv_array_b2c[$x]['nil_amt'] = (float)0;
    $nill_inv_array_b2c[$x]['ngsup_amt'] = (float)0;
    $x++;
  }
}
if(!empty($nill_inv_array_b2c)) {
  $nill_inv_array_b2b =  array_merge($nill_inv_array_b2b, $nill_inv_array_b2c); 
}

$dataArrNil["nill"][0]["inv"]= $nill_inv_array_b2b;
$json_data = json_encode($dataArrNil);
//End Code For NIL Payload

//Start Code For AT Payload
$queryAt =  "select a.company_state,a.reference_number,a.billing_gstin_number,a.reference_number,a.supply_place,a.invoice_date,a.invoice_total_value,b.item_name,b.taxable_subtotal, sum(b.igst_amount) as igst_amount, sum(b.cgst_amount) as cgst_amount, sum(b.sgst_amount) as sgst_amount,sum(b.cess_amount) as cess_amount,b.igst_rate,b.cgst_rate,b.sgst_rate from ".TAB_PREFIX."client_rv_invoice a inner join ".TAB_PREFIX."client_rv_invoice_item b on a.invoice_id=b.invoice_id  where  a.status='1'  and a.added_by='".$_SESSION['user_detail']['user_id']."'  group by a.supply_place ,b.igst_rate order by a.supply_place ";

//End Code For AT Payload
$dataInvAt = $obj_api->get_results($queryAt);
$dataArrAt = array();
$dataArrAt["gstin"]= "27GSPMH3941G1ZK";
$dataArrAt["fp"]= "062017";
$dataArrAt["gt"]= (float)"53782969.00";
$dataArrAt["cur_gt"]= (float)"53782969.00";
if(isset($dataInvAt))
{
  $z=0;
  $y=0;
  $a=1;
  $at_pos='';
  $at_rate = '';
  foreach($dataInvAt as $dataIn)
  {
    $rt = $dataIn->igst_rate;
    //$rt = ($dataIn->company_state==$dataIn->supply_place) ? ($dataIn->sgst_rate+ $dataIn->cgst_rate) :  $dataIn->igst_rate;
    if($at_pos!='' && $at_pos!=$dataIn->supply_place)
    {
      $y++;
      $z=0;
    }
    
    $dataArrAt['at'][$y]['pos'] = (strlen($dataIn->supply_place)=='1')? '0'.$dataIn->supply_place : $dataIn->supply_place;
    if($dataIn->company_state!=$dataIn->supply_place)
    {
      $dataArrAt['at'][$y]['sply_ty']='INTER';
    }
    else
    {
      $dataArrAt['at'][$y]['sply_ty']='INTRA';
    }
    
    $dataArrAt['at'][$y]['itms'][$z]['rt']=(float)$rt;
    $dataArrAt['at'][$y]['itms'][$z]['ad_amt']=(float)$dataIn->taxable_subtotal;
    $dataArrAt['at'][$y]['itms'][$z]['iamt']=(float)$dataIn->igst_amount;
    $dataArrAt['at'][$y]['itms'][$z]['samt']=(float)$dataIn->sgst_amount;
    $dataArrAt['at'][$y]['itms'][$z]['camt']=(float)$dataIn->cgst_amount;
    $dataArrAt['at'][$y]['itms'][$z]['csamt']=(float)$dataIn->cess_amount;
    $at_pos=$dataIn->supply_place;
    $at_rate=$rt;
    $z++;

  }
}


$json_data = json_encode($dataArrAt);
echo '<pre>'; print_r($dataArrAt);
die;
echo '<br/>';
echo $json_data;
if (isset($data->sek)) {
  $session_key = $data->sek;
  $decrypt_sess_key = openssl_decrypt(base64_decode($session_key), "aes-256-ecb", $key, OPENSSL_RAW_DATA);
  $encodejson = base64_encode(openssl_encrypt(base64_encode($json_data), "aes-256-ecb", $decrypt_sess_key, OPENSSL_RAW_DATA));
  /*     * *************************hmac Genration******************************************** */
  $hmac = base64_encode(hash_hmac('sha256', base64_encode($json_data), $decrypt_sess_key, true));
  /*     * *************************hitCurl******************************************** */
  $client_secret = 'fa6f03446473400fa21240241affe2a5';
  $clientid = 'l7xx2909cd95daee418b8118e070b6b24dd6';
  $ip_usr = '49.50.73.109';
  $state_cd = '27';
  $txn = 'TXN789123456789';
  $gstin = '27GSPMH3941G1ZK';
  $username = 'Cyfuture.MH.TP.1';
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
    'ret_prd:062017 ',
    'state-cd:' . $state_cd . '',
    'txn:' . $txn . '',
    'username:' . $username . '',
    'karvyclientid:' . $clientid . '',
    'karvyclient-secret:' . $client_secret . ''
  );
  $url1 = 'http://gsp.karvygst.com/v0.3/returns/gstr1';

  function hitUrl1($url, $data_string, $header) {
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
  $retData = $datasave->data;
  $rek = $datasave->rek;

  $apiEk = openssl_decrypt(base64_decode($rek), "aes-256-ecb", $decrypt_sess_key, OPENSSL_RAW_DATA);
  $decodejson = base64_decode(openssl_decrypt(base64_decode($retData), "aes-256-ecb", $apiEk, OPENSSL_RAW_DATA));

  $ref = json_decode($decodejson);
  $refId = $ref->reference_id;

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
  $url2 = 'http://gsp.karvygst.com/v0.3/returns?action=RETSTATUS&gstin=' . $gstin . '&ret_period=062017&ref_id=' . $refId . '';

  function hitUrl2($url, $data_string, $header) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
  }

  sleep(5);
  $result_data1 = hitUrl2($url2, '', $header2);
  $retDta = json_decode($result_data1);

  $retRek = $retDta->rek;
  $retData1 = $retDta->data;

  $apiEk1 = openssl_decrypt(base64_decode($retRek), "aes-256-ecb", $decrypt_sess_key, OPENSSL_RAW_DATA);
  $decodejson1 = base64_decode(openssl_decrypt(base64_decode($retData1), "aes-256-ecb", $apiEk1, OPENSSL_RAW_DATA));

  echo "<br>RETSTATUS<br>$decodejson1<br><br>";

  $getReturnUrl = 'http://gsp.karvygst.com/v0.3/returns/gstr1?gstin=' . $gstin . '&ret_period=062017&action=RETSUM';
  $result_data1 = hitUrl2($getReturnUrl, '', $header2);
  $retDta = json_decode($result_data1);

  $retRek = $retDta->rek;
  $retData1 = $retDta->data;

  $apiEk1 = openssl_decrypt(base64_decode($retRek), "aes-256-ecb", $decrypt_sess_key, OPENSSL_RAW_DATA);

  $decodejson1 = base64_decode(openssl_decrypt(base64_decode($retData1), "aes-256-ecb", $apiEk1, OPENSSL_RAW_DATA));
  echo "GET SUMMARY<br><pre>";
  $retDta = json_decode($decodejson1);
  echo '<pre>';print_r($retDta) . "<br><br><br>";
  /****** ****************************FOR GSTR1 FILE********************************* */

}
else 
{
   //echo "IP is blocked";
}
?>