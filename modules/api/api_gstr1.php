<?php
 $obj_api =  new gstr();
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

$pem_private_key = file_get_contents(PROJECT_ROOT . '/modules/api/GSTN_Public_Key/GSTN_private.pem');
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
$username='Karthiheyini.TN.1';
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

//print_r($data);

$result_data = hitUrl($url, $data_string, $header);
$data = json_decode($result_data);

$session_key = $data->sek;


$decrypt_sess_key=openssl_decrypt(base64_decode($session_key),"aes-256-ecb",$key, OPENSSL_RAW_DATA);

//echo "your decrypt_sess_key is<br />" . $decrypt_sess_key . "<br /><br />";

$data = json_decode($result_data);
$query =  "select a.company_state,a.billing_gstin_number,a.reference_number,a.invoice_date,a.invoice_total_value,a.supply_place,a.invoice_type,a.supply_type,b.igst_rate,b.cgst_rate,b.sgst_rate,b.taxable_subtotal, sum(b.igst_amount) as igst_amount, sum(b.cgst_amount) as cgst_amount, sum(b.sgst_amount) as sgst_amount,sum(b.cess_amount) as cess_amount from ".TAB_PREFIX."client_invoice a inner join ".TAB_PREFIX."client_invoice_item b on a.invoice_id=b.invoice_id where a.is_gstr1_uploaded='0' and a.status='1' and a.added_by='".$_SESSION['user_detail']['user_id']."'  and a.invoice_date like '%2017-06%' and a.billing_gstin_number!='' group by a.reference_number, b.igst_rate";
echo $query;
 $dataInv = $obj_api->get_results($query);
   $obj_api->pr($dataInv); 
    $dataArr = array();
    $dataArr["gstin"]= "33GSPTN0741G1ZF";
      $dataArr["fp"]= "062017";
      $dataArr["gt"]= (float)"53782969.00";
      $dataArr["cur_gt"]= (float)"53782969.00";
    if(isset($dataInv))
    {
      
      $x=0;
      $y=0;
      $z=0;
      $a=1;
      $temp_number='';
      $ctin = '';
      foreach($dataInv as $dataIn)
      {
          if($ctin!='' && $ctin!=$dataIn->billing_gstin_number)
          {
            $x++;
          }
          if($temp_number!='' && $temp_number!=$dataIn->reference_number)
          {
            $z=0;
            $y++;
          }
          $dataArr['b2b'][$x]['ctin']=$dataIn->billing_gstin_number;
          $dataArr['b2b'][$x]['inv'][$y]['inum']=$dataIn->reference_number;
          $dataArr['b2b'][$x]['inv'][$y]['idt']=date('d-m-Y',strtotime($dataIn->invoice_date));
          $dataArr['b2b'][$x]['inv'][$y]['val']=(float)$dataIn->invoice_total_value;
          $dataArr['b2b'][$x]['inv'][$y]['pos']=strlen($dataIn->supply_place)=='1' ? '0'.$dataIn->supply_place : $dataIn->supply_place;
          $in_type='';
          if($dataIn->invoice_type!='taxinvoice')
          {
            $in_type='R';
          }
          else if($dataIn->invoice_type!='sezunitinvoice')
          {
            $in_type='SEWP';
          }
          else if($dataIn->invoice_type!='deemedexportinvoice')
          {
            $in_type='DE';
          }
          $rever_charge = ($dataIn->supply_type=='reversecharge') ? 'Y' : 'N';
          $dataArr['b2b'][$x]['inv'][$y]['inv_typ']=$in_type;
          $dataArr['b2b'][$x]['inv'][$y]['rchrg']=$rever_charge;
          $dataArr['b2b'][$x]['inv'][$y]['itms'][$z]['num']=(int)$a;
          $rt = ($dataIn->company_state==$dataIn->supply_place) ? ($dataIn->sgst_rate+ $dataIn->cgst_rate) :  $dataIn->igst_rate;
          $dataArr['b2b'][$x]['inv'][$y]['itms'][$z]['itm_det']['rt']=(float)$rt;
          $dataArr['b2b'][$x]['inv'][$y]['itms'][$z]['itm_det']['txval']=(float)$dataIn->taxable_subtotal;
          if($dataIn->company_state!=$dataIn->supply_place)
          {
              $dataArr['b2b'][$x]['inv'][$y]['itms'][$z]['itm_det']['iamt']=(float)$dataIn->igst_amount;
          }
          else
          {
              $dataArr['b2b'][$x]['inv'][$y]['itms'][$z]['itm_det']['samt']=(float)$dataIn->sgst_amount;
              $dataArr['b2b'][$x]['inv'][$y]['itms'][$z]['itm_det']['camt']=(float)$dataIn->cgst_amount;
          }
          $dataArr['b2b'][$x]['inv'][$y]['itms'][$z]['itm_det']['csamt']=(float)$dataIn->cess_amount;
          $z++;
           $temp_number=  $dataIn->reference_number;
           $a++;
      }
    }

    $query =  "select a.company_state,a.billing_gstin_number,a.reference_number,a.invoice_date,a.invoice_total_value,a.supply_place,a.invoice_type,a.supply_type,b.igst_rate,b.cgst_rate,b.sgst_rate,b.taxable_subtotal, sum(b.igst_amount) as igst_amount, sum(b.cgst_amount) as cgst_amount, sum(b.sgst_amount) as sgst_amount,sum(b.cess_amount) as cess_amount from ".TAB_PREFIX."client_invoice a inner join ".TAB_PREFIX."client_invoice_item b on a.invoice_id=b.invoice_id where a.is_gstr1_uploaded='0' and a.status='1' and a.added_by='".$_SESSION['user_detail']['user_id']."' and a.invoice_date like '%2017-06%' and a.billing_gstin_number!='' and a.invoice_total_value>'250000' and a.supply_place!=a.company_state group by a.reference_number, b.igst_rate order by a.supply_place "; 
    echo "<br>$query";
 $dataInv = $obj_api->get_results($query);
$obj_api->pr($dataInv);
 if(isset($dataInv))
    {
      
      $x=0;
      $y=0;
      $z=0;
      $a=1;
      $temp_number='';
      $ctin = '';
      foreach($dataInv as $dataIn)
      {

          if($temp_number!='' && $temp_number!=$dataIn->reference_number)
          {
            $z=0;
            $y++;
          }
          if($ctin!='' && $ctin!=$dataIn->supply_place)
          {
            $x++;
            $y=0;
          }
          $dataArr['b2cl'][$x]['pos']=strlen($dataIn->supply_place)=='1' ? '0'.$dataIn->supply_place: $dataIn->supply_place;
          $dataArr['b2cl'][$x]['inv'][$y]['inum']=$dataIn->reference_number;
          $dataArr['b2cl'][$x]['inv'][$y]['idt']=date('d-m-Y',strtotime($dataIn->invoice_date));
          $dataArr['b2cl'][$x]['inv'][$y]['val']=(float)$dataIn->invoice_total_value;
          
          $in_type='';
          if($dataIn->billing_gstin_number!='taxinvoice')
          {
            $in_type='R';
          }
          else if($dataIn->billing_gstin_number!='sezunitinvoice')
          {
            $in_type='SEWP';
          }
          else if($dataIn->billing_gstin_number!='deemedexportinvoice')
          {
            $in_type='DE';
          }
          $rever_charge = ($dataIn->supply_type=='reversecharge') ? 'Y' : 'N';
          
          $dataArr['b2cl'][$x]['inv'][$y]['itms'][$z]['num']=(int)$a;
          $rt = ($dataIn->company_state==$dataIn->supply_place) ? ($dataIn->sgst_rate+ $dataIn->cgst_rate) :  $dataIn->igst_rate;
          $dataArr['b2cl'][$x]['inv'][$y]['itms'][$z]['itm_det']['rt']=(float)$rt;
          $dataArr['b2cl'][$x]['inv'][$y]['itms'][$z]['itm_det']['txval']=(float)$dataIn->taxable_subtotal;
          if($dataIn->company_state!=$dataIn->supply_place)
          {
              $dataArr['b2cl'][$x]['inv'][$y]['itms'][$z]['itm_det']['iamt']=(float)$dataIn->igst_amount;
          }
          else
          {
              $dataArr['b2cl'][$x]['inv'][$y]['itms'][$z]['itm_det']['samt']=(float)$dataIn->sgst_amount;
              $dataArr['b2cl'][$x]['inv'][$y]['itms'][$z]['itm_det']['camt']=(float)$dataIn->cgst_amount;
          }
          $dataArr['b2cl'][$x]['inv'][$y]['itms'][$z]['itm_det']['csamt']=(float)$dataIn->cess_amount;
          $z++;
          $ctin=$dataIn->supply_place;
           $temp_number=  $dataIn->reference_number;
           $a++;

      }
    }



    $query =  "select a.invoice_id,a.company_state,a.billing_gstin_number,a.reference_number,a.invoice_date,a.invoice_total_value,a.supply_place,a.invoice_type,a.supply_type,b.igst_rate,b.cgst_rate,b.sgst_rate,b.taxable_subtotal, sum(b.igst_amount) as igst_amount, sum(b.cgst_amount) as cgst_amount, sum(b.sgst_amount) as sgst_amount,sum(b.cess_amount) as cess_amount from ".TAB_PREFIX."client_invoice a inner join ".TAB_PREFIX."client_invoice_item b on a.invoice_id=b.invoice_id where a.is_gstr1_uploaded='0' and a.status='1' and a.added_by='".$_SESSION['user_detail']['user_id']."'  and a.invoice_date like '%2017-06%' and a.billing_gstin_number='' and (a.supply_place=a.company_state  or (a.supply_place!=a.company_state and a.invoice_total_value<='250000')) group by a.reference_number, b.igst_rate order by a.supply_place ";
    echo "<br>$query";
    $dataInv = $obj_api->get_results($query);
    $obj_api->pr($dataInv);
    if(isset($dataInv))
    {
      
      $x=0;
      $y=0;
      $z=0;
      $a=1;
      $temp_number='';
      $ctin = '';
      foreach($dataInv as $dataIn)
      {
          if($ctin!='' && $ctin!=$dataIn->billing_gstin_number)
          {
            $x++;
          }
          if($temp_number!='' && $temp_number!=$dataIn->reference_number)
          {
            $z=0;
            $y++;
          }

          if($dataIn->company_state!=$dataIn->supply_place)
          {
              $dataArr['b2cs'][$x]['sply_ty']='INTER';
          }
          else
          {
            $dataArr['b2cs'][$x]['sply_ty']='INTRA';
          }

          $dataArr['b2cs'][$x]['rt']=(float)$rt;
          $dataArr['b2cs'][$x]['typ']='OE';
          $dataArr['b2cs'][$x]['pos']=strlen($dataIn->supply_place)=='1' ? '0'.$dataIn->supply_place : $dataIn->supply_place ;
          $dataArr['b2cs'][$x]['txval']=(float)$rt;
          if($dataIn->company_state!=$dataIn->supply_place)
          {
              $dataArr['b2cs'][$x]['iamt']=(float)$dataIn->igst_amount;
          }
          else
          {
              $dataArr['b2cs'][$x]['samt']=(float)$dataIn->sgst_amount;
              $dataArr['b2cs'][$x]['camt']=(float)$dataIn->cgst_amount;
          }
          $dataArr['b2cs'][$x]['csamt']=(float)$dataIn->cess_amount;
          $x++;
          $z++;
           $temp_number=  $dataIn->reference_number;
           $a++;
      }
    }


    $query =  "select a.corresponding_invoice_number,a.corresponding_invoice_date,a.invoice_document_nature,a.invoice_id,a.company_state,a.billing_gstin_number,a.reference_number,a.invoice_date,a.invoice_total_value,a.supply_place,b.igst_rate,b.cgst_rate,b.sgst_rate,b.taxable_subtotal, sum(b.igst_amount) as igst_amount, sum(b.cgst_amount) as cgst_amount, sum(b.sgst_amount) as sgst_amount,sum(b.cess_amount) as cess_amount from ".TAB_PREFIX."client_rt_invoice a inner join ".TAB_PREFIX."client_rt_invoice_item b on a.invoice_id=b.invoice_id where a.status='1' and a.added_by='30' and a.invoice_date like '%2017-06%' and a.invoice_corresponding_type='taxinvoice' and (a.billing_gstin_number!='') and a.invoice_document_nature!='revisedtaxinvoice' group by a.reference_number, b.igst_rate order by a.supply_place";
    echo $query;
    $dataInv = $obj_api->get_results($query);
    //$obj_api->pr($dataInv);
    if(isset($dataInv))
    {
      
      $x=0;
      $y=0;
      $z=0;
      $a=1;
      $temp_number='';
      $ctin = '';
      foreach($dataInv as $dataIn)
      {
          if($ctin!='' && $ctin!=$dataIn->billing_gstin_number)
          {
            $x++;
          }
          if($temp_number!='' && $temp_number!=$dataIn->reference_number)
          {
            $z=0;
            $y++;
          }
          $dataArr['cdnr'][$x]['ctin']=$dataIn->billing_gstin_number;
          $nt_type='';
          if($dataIn->invoice_document_nature=='creditnote')
          {
            $nt_type='C';
          }
          else 
          {
            $nt_type='D';
          }
          $dataArr['cdnr'][$x]['nt'][$y]['ntty']=$nt_type;
          $dataArr['cdnr'][$x]['nt'][$y]['nt_num']=$dataIn->reference_number;
          $dataArr['cdnr'][$x]['nt'][$y]['nt_dt']=date('d-m-Y',strtotime($dataIn->invoice_date));
          $dataArr['cdnr'][$x]['nt'][$y]['p_gst']="N";
          $dataArr['cdnr'][$x]['nt'][$y]['rsn']="Post Sale Discount";
          $dataArr['cdnr'][$x]['nt'][$y]['inum']=$dataIn->corresponding_invoice_number;
          $dataArr['cdnr'][$x]['nt'][$y]['idt']=date('d-m-Y',strtotime($dataIn->corresponding_invoice_date));
          $dataArr['cdnr'][$x]['nt'][$y]['val']=(float)$dataIn->invoice_total_value;
          $dataArr['cdnr'][$x]['nt'][$y]['itms'][$z]['num']=(int)$a;
          $rt = ($dataIn->company_state==$dataIn->supply_place) ? ($dataIn->sgst_rate+ $dataIn->cgst_rate) :  $dataIn->igst_rate;
          $dataArr['cdnr'][$x]['nt'][$y]['itms'][$z]['itm_det']['rt']=(float)$rt;
          $dataArr['cdnr'][$x]['nt'][$y]['itms'][$z]['itm_det']['txval']=(float)$dataIn->taxable_subtotal;
          if($dataIn->company_state!=$dataIn->supply_place)
          {
              $dataArr['cdnr'][$x]['nt'][$y]['itms'][$z]['itm_det']['iamt']=(float)$dataIn->igst_amount;
          }
          else
          {
              $dataArr['cdnr'][$x]['nt'][$y]['itms'][$z]['itm_det']['samt']=(float)$dataIn->sgst_amount;
              $dataArr['cdnr'][$x]['nt'][$y]['itms'][$z]['itm_det']['camt']=(float)$dataIn->cgst_amount;
          }
          $dataArr['cdnr'][$x]['nt'][$y]['itms'][$z]['itm_det']['csamt']=(float)$dataIn->cess_amount;
          $z++;
           $temp_number=  $dataIn->reference_number;
           $a++;
      }
    }



    $query =  "select a.corresponding_invoice_number,a.corresponding_invoice_date,a.invoice_document_nature,a.invoice_id,a.company_state,a.billing_gstin_number,a.reference_number,a.invoice_date,a.invoice_total_value,a.supply_place,b.igst_rate,b.cgst_rate,b.sgst_rate,b.taxable_subtotal, sum(b.igst_amount) as igst_amount, sum(b.cgst_amount) as cgst_amount, sum(b.sgst_amount) as sgst_amount,sum(b.cess_amount) as cess_amount from ".TAB_PREFIX."client_rt_invoice a inner join ".TAB_PREFIX."client_rt_invoice_item b on a.invoice_id=b.invoice_id where a.status='1' and a.added_by='30' and a.invoice_date like '%2017-06%' and a.supply_place!=a.company_state and a.invoice_corresponding_type='taxinvoice' and a.billing_gstin_number='' and a.invoice_total_value >'250000' and a.invoice_document_nature!='revisedtaxinvoice' group by a.reference_number, b.igst_rate order by a.supply_place";
   echo "<br>$query";
    $dataInv = $obj_api->get_results($query);
    $obj_api->pr($dataInv);
    if(isset($dataInv))
    {
      
      $x=0;
      $y=0;
      $y=0;
      $a=1;
      $temp_number='';
      $ctin = '';
      foreach($dataInv as $dataIn)
      {
          if($ctin!='' && $ctin!=$dataIn->billing_gstin_number)
          {
            $x++;
          }
          if($temp_number!='' && $temp_number!=$dataIn->reference_number)
          {
            $y=0;
            $y++;
          }

          $dataArr['cdnur'][$x]['typ']="B2CL";
          $dataArr['cdnur'][$x]['ntty']=$dataIn->reference_number;
          $dataArr['cdnur'][$x]['nt_num']=date('d-m-Y',strtotime($dataIn->invoice_date));
          $dataArr['cdnur'][$x]['nt_dt']="N";
          $dataArr['cdnur'][$x]['p_gst']="Post Sale Discount";
          $dataArr['cdnur'][$x]['rsn']=$dataIn->corresponding_invoice_number;
          $dataArr['cdnur'][$x]['inum']=date('d-m-Y',strtotime($dataIn->corresponding_invoice_date));
          $dataArr['cdnur'][$x]['idt']=(float)$dataIn->invoice_total_value;
          $dataArr['cdnur'][$x]['val'][$y]=(int)$a;
          $dataArr['cdnur'][$x]['itms'][$y]['num']=(int)$a;
          $rt = ($dataIn->company_state==$dataIn->supply_place) ? ($dataIn->sgst_rate+ $dataIn->cgst_rate) :  $dataIn->igst_rate;
          $dataArr['cdnur'][$x]['itms'][$y]['itm_det']['rt']=(float)$rt;
          $dataArr['cdnur'][$x]['itms'][$y]['itm_det']['txval']=(float)$dataIn->taxable_subtotal;
          if($dataIn->company_state!=$dataIn->supply_place)
          {
              $dataArr['cdnur'][$x]['itms'][$y]['itm_det']['iamt']=(float)$dataIn->igst_amount;
          }
          else
          {
              $dataArr['cdnur'][$x]['itms'][$y]['itm_det']['samt']=(float)$dataIn->sgst_amount;
              $dataArr['cdnur'][$x]['itms'][$y]['itm_det']['camt']=(float)$dataIn->cgst_amount;
          }
          $dataArr['cdnur'][$x]['itms'][$y]['itm_det']['csamt']=(float)$dataIn->cess_amount;
          $y++;
           $temp_number=  $dataIn->reference_number;
           $a++;
      }
    }


$json_data = json_encode($dataArr);
echo $json_data;
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
$gstin = '33GSPTN0741G1ZF';
$username = 'Karthiheyini.TN.1';
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
  'ret_period: 062017 ',
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

$retData=$datasave->data;
$rek=$datasave->rek;

$apiEk=openssl_decrypt(base64_decode($rek),"aes-256-ecb",$decrypt_sess_key, OPENSSL_RAW_DATA);

$decodejson= base64_decode(openssl_decrypt(base64_decode($retData),"aes-256-ecb",$apiEk, OPENSSL_RAW_DATA));









$ref = json_decode($decodejson);
echo "reference_id<br>";
print_r($ref)."<br><br>";
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
  'ret_period:062017',
  'state-cd:' . $state_cd . '',
  'txn:' . $txn . '',
  'username:' . $username . '',
  'action:' . 'RETSTATUS' . ''
);
$url2 = 'http://devapi.gstsystem.co.in/taxpayerapi/v0.3/returns?action=RETSTATUS&gstin='.$gstin. '&ret_period=062017&ref_id='.$refId.'';

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
/*echo "<pre>";
//var_dump(json_decode($decodejson1));
echo "</pre>";*/

$getReturnUrl='http://devapi.gstsystem.co.in/taxpayerapi/v0.3/returns/gstr1?gstin='.$gstin. '&ret_period=062017&action=RETSUM';
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
  "gstin": "27GSPMH3941G1ZK",
  "ret_period": "062017",
  "summ_typ": "L",
  "chksum": "c8b3b5e7f3b0720c9d17e2105c64ad4bc05e4a0e23e35707b2bc80d0199b8959",
  "sec_sum": [
    {
      "sec_nm": "b2b",
      "chksum": "ca515335ed89eaa5e3331a5feda19d1545d64dc25f40cf76676c413e64a73fa6",
      "ttl_rec": 8,
      "ttl_val": 453440347409.92,
      "ttl_igst": 2496,
      "ttl_cgst": 2186398728.54,
      "ttl_sgst": 2186398728.54,
      "ttl_cess": 0,
      "ttl_tax": 43719067643,
      "cpty_sum": [
        {
          "ctin": "27GSPMH0151G1ZW",
          "chksum": "881bb1823c4d745673ad03aee20e8988979402a64bdf921b0cd697887f4e13bf",
          "ttl_rec": 8,
          "ttl_val": 453440347409.92,
          "ttl_igst": 2496,
          "ttl_cgst": 2186398728.54,
          "ttl_sgst": 2186398728.54,
          "ttl_cess": 0,
          "ttl_tax": 43719067643
        }
      ]
    }
  ]
}';
*/ 
/*/**************************SUBMIT GSTR1 ****************************/
$sub_data='{
  "gstin": "'.$gstin.'",
  "ret_period": "062017"
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
  'ret_period: 062017 ',
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