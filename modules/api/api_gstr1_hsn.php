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

//Start Code For Doc
$dataInvDoc = array();
$dataInvDoc["gstin"]= "27GSPMH3941G1ZK";
$dataInvDoc["fp"]= "062017";
$dataInvDoc["gt"]= (float)"53782969.00";
$dataInvDoc["cur_gt"]= (float)"53782969.00";

$final_array = $dataRevise = $dataRevised = $dataDebit = $dataCredit = $dataReceipt = $dataRefund = $dataDeliveryJobWork = 
$dataDeliverySUAP = $dataDeliverySULGAS = $dataDeliverySupplyOther = array();

/*********** Start code For Doc Sales *************/
$querySales =  "select a.invoice_id,a.reference_number from ".$obj_api->getTableName('client_invoice')." a where a.is_gstr1_uploaded='1' and a.status='1' and a.added_by='".$_SESSION['user_detail']['user_id']."'  and a.invoice_date like '%2017-06%' and a.reference_number != '' and  a.invoice_type in('taxinvoice','exportinvoice','sezunitinvoice','deemedexportinvoice','sezunitinvoice')  order by a.reference_number";
$queryCancle =  "select a.invoice_id,a.reference_number from ".$obj_api->getTableName('client_invoice')." a where a.is_gstr1_uploaded='1' and a.status='1' and a.added_by='".$_SESSION['user_detail']['user_id']."'  and a.invoice_date like '%2017-05%' and a.reference_number != '' and a.is_canceled = 1  and  a.invoice_type in('taxinvoice','exportinvoice','sezunitinvoice','deemedexportinvoice','sezunitinvoice') group by a.reference_number order by a.reference_number";

$dataInvSales = $obj_api->get_results($querySales);
$dataInvCancelSales = $obj_api->get_results($queryCancle);
if(isset($dataInvSales) && !empty($dataInvSales))
{
  $doc_num = 1;
  $z=0;
  $a = 1;
  $totnum= count($dataInvSales);
  $cancel = count($dataInvCancelSales);
  $net_issue = $totnum - $cancel;
  $dataSales['doc_num'] = (int)$doc_num;
  $dataSales['docs'][$z]['num'] = (int)$a;
  $dataSales['docs'][$z]['from'] = $dataInvSales[0]->reference_number;
  $dataSales['docs'][$z]['to'] = $dataInvSales[$totnum-1]->reference_number;
  $dataSales['docs'][$z]['totnum'] = (int)$totnum;
  $dataSales['docs'][$z]['cancel'] = (int)$cancel;
  $dataSales['docs'][$z]['net_issue'] = (int)$net_issue;
  $final_array[] = $dataSales;
}
/*********** End code For Doc Sales *************/

/*********** Start code For Doc Revised *************/
$queryRevised =  "select a.invoice_id,a.reference_number from ".$obj_api->getTableName('client_invoice')." a where a.is_gstr1_uploaded='1' and a.status='1' and a.added_by='".$_SESSION['user_detail']['user_id']."'  and a.invoice_date like '%2017-06%' and a.reference_number != '' and  a.invoice_type = 'revisedtaxinvoice'  order by a.reference_number";

$queryRevisedCancle =  "select a.invoice_id,a.reference_number from ".$obj_api->getTableName('client_invoice')." a where a.is_gstr1_uploaded='1' and a.status='1' and a.added_by='".$_SESSION['user_detail']['user_id']."'  and a.invoice_date like '%2017-05%' and a.reference_number != '' and a.is_canceled = 1  and  a.invoice_type = 'revisedtaxinvoice' group by a.reference_number order by a.reference_number";

$dataInvRevised = $obj_api->get_results($queryRevised);
$dataInvCancleRevised = $obj_api->get_results($queryRevisedCancle);

if(isset($dataInvRevised) && !empty($dataInvRevised))
{
  $doc_num = 2;
  $z=0;
  $a = 1;
  $totnum= count($dataInvRevised);
  $cancel = count($dataInvCancleRevised);
  $net_issue = $totnum - $cancel;
  $dataRevised['doc_num'] = (int)$doc_num;
  $dataRevised['docs'][$z]['num'] = (int)$a;
  $dataRevised['docs'][$z]['from'] = $dataInvRevised[0]->reference_number;
  $dataRevised['docs'][$z]['to'] = $dataInvRevised[$totnum-1]->reference_number;
  $dataRevised['docs'][$z]['totnum'] = (int)$totnum;
  $dataRevised['docs'][$z]['cancel'] = (int)$cancel;
  $dataRevised['docs'][$z]['net_issue'] = (int)$net_issue;
  $final_array[] = $dataRevised;
}
/*********** End code For Doc Revised *************/

/*********** Start code For Debit  *************/
$queryDebit =  "select a.invoice_id,a.reference_number from ".$obj_api->getTableName('client_invoice')." a where a.is_gstr1_uploaded='1' and a.status='1' and a.added_by='".$_SESSION['user_detail']['user_id']."'  and a.invoice_date like '%2017-06%' and a.reference_number != '' and  a.invoice_type = 'debitnote'  order by a.reference_number";

$queryDebitCancle =  "select a.invoice_id,a.reference_number from ".$obj_api->getTableName('client_invoice')." a where a.is_gstr1_uploaded='1' and a.status='1' and a.added_by='".$_SESSION['user_detail']['user_id']."'  and a.invoice_date like '%2017-05%' and a.reference_number != '' and a.is_canceled = 1  and  a.invoice_type = 'debitnote' group by a.reference_number order by a.reference_number";

$dataInvDebit = $obj_api->get_results($queryDebit);
$dataInvCancleDebit = $obj_api->get_results($queryDebitCancle);

if(isset($dataInvDebit) && !empty($dataInvDebit))
{
  $doc_num = 3;
  $z=0;
  $a = 1;
  $totnum= count($dataInvDebit);
  $cancel = count($dataInvCancleDebit);
  $net_issue = $totnum - $cancel;
  $dataDebit['doc_num'] = (int)$doc_num;
  $dataDebit['docs'][$z]['num'] = (int)$a;
  $dataDebit['docs'][$z]['from'] = $dataInvDebit[0]->reference_number;
  $dataDebit['docs'][$z]['to'] = $dataInvDebit[$totnum-1]->reference_number;
  $dataDebit['docs'][$z]['totnum'] = (int)$totnum;
  $dataDebit['docs'][$z]['cancel'] = (int)$cancel;
  $dataDebit['docs'][$z]['net_issue'] = (int)$net_issue;
  $final_array[] = $dataDebit;
}
/*********** End code For Debit  *************/

/*********** Start code For Credit  *************/
$queryCredit =  "select a.invoice_id,a.reference_number from ".$obj_api->getTableName('client_invoice')." a where a.is_gstr1_uploaded='1' and a.status='1' and a.added_by='".$_SESSION['user_detail']['user_id']."'  and a.invoice_date like '%2017-06%' and a.reference_number != '' and  a.invoice_type = 'creditnote'  order by a.reference_number";

$queryCreditCancle =  "select a.invoice_id,a.reference_number from ".$obj_api->getTableName('client_invoice')." a where a.is_gstr1_uploaded='1' and a.status='1' and a.added_by='".$_SESSION['user_detail']['user_id']."'  and a.invoice_date like '%2017-05%' and a.reference_number != '' and a.is_canceled = 1  and  a.invoice_type = 'creditnote' group by a.reference_number order by a.reference_number";

$dataInvCredit = $obj_api->get_results($queryCredit);
$dataInvCancleCredit = $obj_api->get_results($queryCreditCancle);

if(isset($dataInvCredit) && !empty($dataInvCredit))
{
  $doc_num = 4;
  $z=0;
  $a = 1;
  $totnum= count($dataInvCredit);
  $cancel = count($dataInvCancleCredit);
  $net_issue = $totnum - $cancel;
  $dataCredit['doc_num'] = (int)$doc_num;
  $dataCredit['docs'][$z]['num'] = (int)$a;
  $dataCredit['docs'][$z]['from'] = $dataInvCredit[0]->reference_number;
  $dataCredit['docs'][$z]['to'] = $dataInvCredit[$totnum-1]->reference_number;
  $dataCredit['docs'][$z]['totnum'] = (int)$totnum;
  $dataCredit['docs'][$z]['cancel'] = (int)$cancel;
  $dataCredit['docs'][$z]['net_issue'] = (int)$net_issue;
  $final_array[] = $dataCredit;
}
/*********** End code For Credit  *************/

/*********** Start code For Receipt   *************/
$queryReceipt =  "select a.invoice_id,a.reference_number from ".$obj_api->getTableName('client_invoice')." a where a.is_gstr1_uploaded='1' and a.status='1' and a.added_by='".$_SESSION['user_detail']['user_id']."'  and a.invoice_date like '%2017-06%' and a.reference_number != '' and  a.invoice_type = 'receiptvoucherinvoice'  order by a.reference_number";

$queryReceiptCancle =  "select a.invoice_id,a.reference_number from ".$obj_api->getTableName('client_invoice')." a where a.is_gstr1_uploaded='1' and a.status='1' and a.added_by='".$_SESSION['user_detail']['user_id']."'  and a.invoice_date like '%2017-05%' and a.reference_number != '' and a.is_canceled = 1  and  a.invoice_type = 'receiptvoucherinvoice' group by a.reference_number order by a.reference_number";

//End Code For Doc
$dataInvReceipt = $obj_api->get_results($queryReceipt);
$dataInvCancleReceipt = $obj_api->get_results($queryReceiptCancle);

if(isset($dataInvReceipt) && !empty($dataInvReceipt))
{
  $doc_num = 5;
  $z=0;
  $a = 1;
  $totnum= count($dataInvReceipt);
  $cancel = count($dataInvCancleReceipt);
  $net_issue = $totnum - $cancel;
  $dataReceipt['doc_num'] = (int)$doc_num;
  $dataReceipt['docs'][$z]['num'] = (int)$a;
  $dataReceipt['docs'][$z]['from'] = $dataInvReceipt[0]->reference_number;
  $dataReceipt['docs'][$z]['to'] = $dataInvReceipt[$totnum-1]->reference_number;
  $dataReceipt['docs'][$z]['totnum'] = (int)$totnum;
  $dataReceipt['docs'][$z]['cancel'] = (int)$cancel;
  $dataReceipt['docs'][$z]['net_issue'] = (int)$net_issue;
  $final_array[] = $dataReceipt;
}
/*********** End code For Receipt   *************/

/*********** Start code For Refund   *************/
$queryReceipt =  "select a.invoice_id,a.reference_number from ".$obj_api->getTableName('client_invoice')." a where a.is_gstr1_uploaded='1' and a.status='1' and a.added_by='".$_SESSION['user_detail']['user_id']."'  and a.invoice_date like '%2017-06%' and a.reference_number != '' and  a.invoice_type = 'refundvoucherinvoice'  order by a.reference_number";

$queryReceiptCancle =  "select a.invoice_id,a.reference_number from ".$obj_api->getTableName('client_invoice')." a where a.is_gstr1_uploaded='1' and a.status='1' and a.added_by='".$_SESSION['user_detail']['user_id']."'  and a.invoice_date like '%2017-05%' and a.reference_number != '' and a.is_canceled = 1  and  a.invoice_type = 'refundvoucherinvoice' group by a.reference_number order by a.reference_number";

//End Code For Doc
$dataInvRefund = $obj_api->get_results($queryReceipt);
$dataInvCancleRefund = $obj_api->get_results($queryReceiptCancle);

if(isset($dataInvRefund) && !empty($dataInvRefund))
{
  $doc_num = 6;
  $z=0;
  $a = 1;
  $totnum= count($dataInvRefund);
  $cancel = count($dataInvCancleRefund);
  $net_issue = $totnum - $cancel;
  $dataRefund['doc_num'] = (int)$doc_num;
  $dataRefund['docs'][$z]['num'] = (int)$a;
  $dataRefund['docs'][$z]['from'] = $dataInvRefund[0]->reference_number;
  $dataRefund['docs'][$z]['to'] = $dataInvRefund[$totnum-1]->reference_number;
  $dataRefund['docs'][$z]['totnum'] = (int)$totnum;
  $dataRefund['docs'][$z]['cancel'] = (int)$cancel;
  $dataRefund['docs'][$z]['net_issue'] = (int)$net_issue;
  $final_array[] = $dataRefund;
}
/*********** End code For Refund   *************/

/*********** Start code Delivery Challan for job work  *************/
$queryDeliveryJobWork =  "select a.invoice_id,a.reference_number from ".$obj_api->getTableName('client_invoice')." a where a.is_gstr1_uploaded='1' and a.status='1' and a.added_by='".$_SESSION['user_detail']['user_id']."'  and a.invoice_date like '%2017-06%' and a.reference_number != '' and  a.invoice_type = 'deliverychallaninvoice' and  a.delivery_challan_type = 'jobwork' order by a.reference_number";

$queryDeliveryJobWorkCancle =  "select a.invoice_id,a.reference_number from ".$obj_api->getTableName('client_invoice')." a where a.is_gstr1_uploaded='1' and a.status='1' and a.added_by='".$_SESSION['user_detail']['user_id']."'  and a.invoice_date like '%2017-05%' and a.reference_number != '' and a.is_canceled = 1  and  a.invoice_type = 'deliverychallaninvoice' and  a.delivery_challan_type = 'jobwork' group by a.reference_number order by a.reference_number";

//End Code For Doc
$dataInvDeliveryJobWork = $obj_api->get_results($queryDeliveryJobWork);
$dataInvCancleDeliveryJobWork = $obj_api->get_results($queryDeliveryJobWorkCancle);

if(isset($dataInvDeliveryJobWork) && !empty($dataInvDeliveryJobWork))
{
  $doc_num = 7;
  $z=0;
  $a = 1;
  $totnum= count($dataInvRefund);
  $cancel = count($dataInvCancleDeliveryJobWork);
  $net_issue = $totnum - $cancel;
  $dataDeliveryJobWork['doc_num'] = (int)$doc_num;
  $dataDeliveryJobWork['docs'][$z]['num'] = (int)$a;
  $dataDeliveryJobWork['docs'][$z]['from'] = $dataInvDeliveryJobWork[0]->reference_number;
  $dataDeliveryJobWork['docs'][$z]['to'] = $dataInvDeliveryJobWork[$totnum-1]->reference_number;
  $dataDeliveryJobWork['docs'][$z]['totnum'] = (int)$totnum;
  $dataDeliveryJobWork['docs'][$z]['cancel'] = (int)$cancel;
  $dataDeliveryJobWork['docs'][$z]['net_issue'] = (int)$net_issue;
  $final_array[] = $dataDeliveryJobWork;
}
/*********** End code Delivery Challan for job work *************/

/*********** Start code Delivery Challan for supply on approval  *************/
$queryDeliverySUAP =  "select a.invoice_id,a.reference_number from ".$obj_api->getTableName('client_invoice')." a where a.is_gstr1_uploaded='1' and a.status='1' and a.added_by='".$_SESSION['user_detail']['user_id']."'  and a.invoice_date like '%2017-06%' and a.reference_number != '' and  a.invoice_type = 'deliverychallaninvoice' and  a.delivery_challan_type = 'supplyonapproval' order by a.reference_number";

$queryDeliverySUAPCancle =  "select a.invoice_id,a.reference_number from ".$obj_api->getTableName('client_invoice')." a where a.is_gstr1_uploaded='1' and a.status='1' and a.added_by='".$_SESSION['user_detail']['user_id']."'  and a.invoice_date like '%2017-05%' and a.reference_number != '' and a.is_canceled = 1  and  a.invoice_type = 'deliverychallaninvoice' and  a.delivery_challan_type = 'supplyonapproval' group by a.reference_number order by a.reference_number";

//End Code For Doc
$dataInvDeliverySUAP = $obj_api->get_results($queryDeliverySUAP);
$dataInvCancleDeliverySUAP = $obj_api->get_results($queryDeliverySUAPCancle);

if(isset($dataInvDeliverySUAP) && !empty($dataInvDeliveryJobWork))
{
  $doc_num = 8;
  $z=0;
  $a = 1;
  $totnum= count($dataInvDeliverySUAP);
  $cancel = count($dataInvCancleDeliverySUAP);
  $net_issue = $totnum - $cancel;
  $dataDeliverySUAP['doc_num'] = (int)$doc_num;
  $dataDeliverySUAP['docs'][$z]['num'] = (int)$a;
  $dataDeliverySUAP['docs'][$z]['from'] = $dataInvDeliverySUAP[0]->reference_number;
  $dataDeliverySUAP['docs'][$z]['to'] = $dataInvDeliverySUAP[$totnum-1]->reference_number;
  $dataDeliverySUAP['docs'][$z]['totnum'] = (int)$totnum;
  $dataDeliverySUAP['docs'][$z]['cancel'] = (int)$cancel;
  $dataDeliverySUAP['docs'][$z]['net_issue'] = (int)$net_issue;
  $final_array[] = $dataDeliverySUAP;
}
/*********** End code Delivery Challan for supply on approval *************/

/*********** Start code Delivery Challan in case of liquid gas  *************/
$queryDeliverySULGAS =  "select a.invoice_id,a.reference_number from ".$obj_api->getTableName('client_invoice')." a where a.is_gstr1_uploaded='1' and a.status='1' and a.added_by='".$_SESSION['user_detail']['user_id']."'  and a.invoice_date like '%2017-06%' and a.reference_number != '' and  a.invoice_type = 'deliverychallaninvoice' and  a.delivery_challan_type = 'supplyofliquidgas' order by a.reference_number";

$queryDeliverySULGASCancle =  "select a.invoice_id,a.reference_number from ".$obj_api->getTableName('client_invoice')." a where a.is_gstr1_uploaded='1' and a.status='1' and a.added_by='".$_SESSION['user_detail']['user_id']."'  and a.invoice_date like '%2017-05%' and a.reference_number != '' and a.is_canceled = 1  and  invoice_type = 'deliverychallaninvoice' and  a.delivery_challan_type = 'supplyofliquidgas' group by a.reference_number order by a.reference_number";

//End Code For Doc
$dataInvDeliverySULGAS = $obj_api->get_results($queryDeliverySULGAS);
$dataInvCancleDeliverySULGAS = $obj_api->get_results($queryDeliverySULGASCancle);

if(isset($dataInvDeliverySULGAS) && !empty($dataInvDeliverySULGAS))
{
  $doc_num = 9;
  $z=0;
  $a = 1;
  $totnum= count($dataInvDeliverySULGAS);
  $cancel = count($dataInvCancleDeliverySULGAS);
  $net_issue = $totnum - $cancel;
  $dataDeliverySULGAS['doc_num'] = (int)$doc_num;
  $dataDeliverySULGAS['docs'][$z]['num'] = (int)$a;
  $dataDeliverySULGAS['docs'][$z]['from'] = $dataInvDeliverySULGAS[0]->reference_number;
  $dataDeliverySULGAS['docs'][$z]['to'] = $dataInvDeliverySULGAS[$totnum-1]->reference_number;
  $dataDeliverySULGAS['docs'][$z]['totnum'] = (int)$totnum;
  $dataDeliverySULGAS['docs'][$z]['cancel'] = (int)$cancel;
  $dataDeliverySULGAS['docs'][$z]['net_issue'] = (int)$net_issue;
  $final_array[] = $dataDeliverySULGAS;
}
/*********** End code Delivery Challan in case of liquid gas *************/

/*********** Start code Delivery Challan in cases other than by way of supply  *************/
$queryDeliverySupplyOther =  "select a.invoice_id,a.reference_number from ".$obj_api->getTableName('client_invoice')." a where a.is_gstr1_uploaded='1' and a.status='1' and a.added_by='".$_SESSION['user_detail']['user_id']."'  and a.invoice_date like '%2017-06%' and a.reference_number != '' and  a.invoice_type = 'deliverychallaninvoice' and  a.delivery_challan_type = 'supplyofliquidgas' order by a.reference_number";

$queryDeliverySupplyOtherCancle =  "select a.invoice_id,a.reference_number from ".$obj_api->getTableName('client_invoice')." a where a.is_gstr1_uploaded='1' and a.status='1' and a.added_by='".$_SESSION['user_detail']['user_id']."'  and a.invoice_date like '%2017-05%' and a.reference_number != '' and a.is_canceled = 1  and  invoice_type = 'deliverychallaninvoice' and  a.delivery_challan_type = 'others' group by a.reference_number order by a.reference_number";

//End Code For Doc
$dataInvDeliverySupplyOther = $obj_api->get_results($queryDeliverySupplyOther);
$dataInvCancleDeliverySupplyOther= $obj_api->get_results($queryDeliverySupplyOtherCancle);

if(isset($dataInvDeliverySupplyOther) && !empty($dataInvDeliverySupplyOther))
{
  $doc_num = 10;
  $z=0;
  $a = 1;
  $totnum= count($dataInvDeliverySupplyOther);
  $cancel = count($dataInvCancleDeliverySupplyOther);
  $net_issue = $totnum - $cancel;
  $dataDeliverySupplyOther['doc_num'] = (int)$doc_num;
  $dataDeliverySupplyOther['docs'][$z]['num'] = (int)$a;
  $dataDeliverySupplyOther['docs'][$z]['from'] = $dataInvDeliverySupplyOther[0]->reference_number;
  $dataDeliverySupplyOther['docs'][$z]['to'] = $dataInvDeliverySupplyOther[$totnum-1]->reference_number;
  $dataDeliverySupplyOther['docs'][$z]['totnum'] = (int)$totnum;
  $dataDeliverySupplyOther['docs'][$z]['cancel'] = (int)$cancel;
  $dataDeliverySupplyOther['docs'][$z]['net_issue'] = (int)$net_issue;
  $final_array[] = $dataDeliverySupplyOther;
}
/*********** End code Delivery Challan in cases other than by way of supply  *************/

$dataInvDoc['doc_issue']['doc_det']  = $final_array;


$json_data = json_encode($dataInvDoc);


//exp
//Start Code For AT Payload


$queryExp =  "select a.export_bill_number,a.export_bill_date,a.export_bill_port_code,a.invoice_id,a.export_supply_meant,a.company_state,a.billing_gstin_number,a.reference_number,a.invoice_date,a.invoice_total_value,b.item_name,a.supply_place,a.invoice_type,b.taxable_subtotal,b.igst_rate,b.cgst_rate,b.sgst_rate, sum(b.igst_amount) as igst_amount, sum(b.cgst_amount) as cgst_amount, sum(b.sgst_amount) as sgst_amount,sum(b.cess_amount) as cess_amount from ".$obj_api->getTableName('client_invoice')." a inner join ".$obj_api->getTableName('client_invoice_item')." b on a.invoice_id=b.invoice_id where a.invoice_type = 'exportinvoice' and a.is_gstr1_uploaded='0' and a.status='1' and a.added_by='".$_SESSION['user_detail']['user_id']."' group by a.invoice_id,b.igst_rate order by a.export_supply_meant";

//End Code For AT Payload
$dataInvExp = $obj_api->get_results($queryExp);
$dataArrExp = array();
$dataArrExp["gstin"]= "27GSPMH3941G1ZK";
$dataArrExp["fp"]= "062017";
$dataArrExp["gt"]= (float)"53782969.00";
$dataArrExp["cur_gt"]= (float)"53782969.00";
if (isset($dataInvExp) && !empty($dataInvExp)) {
    $y = 0;
    $a = 1;
    $mydata = array();
    foreach ($dataInvExp as $key => $value) {
        $mydata[$value->export_supply_meant][] = $value;
    }
    if (!empty($mydata)) {
        if (isset($mydata['withpayment']) && !empty($mydata['withpayment'])) {
            $x = 0;
            $y = 0;
            $z = 0;
            $temp_number = '';
            foreach ($mydata['withpayment'] as $dataIn) {
                if ($temp_number != '' && $temp_number != $dataIn->reference_number) {
                    $z = 0;
                    $y++;
                }
                $rt = ($dataIn->company_state == $dataIn->supply_place) ? ($dataIn->sgst_rate + $dataIn->cgst_rate) : $dataIn->igst_rate;

                $dataArr1['exp_typ'] = "WPAY";
                $dataArr1['inv'][$y]['inum'] = $dataIn->reference_number;
                $dataArr1['inv'][$y]['idt'] = date('d-m-Y', strtotime($dataIn->invoice_date));
                $dataArr1['inv'][$y]['val'] = (float) $dataIn->invoice_total_value;
                $dataArr1['inv'][$y]['sbpcode'] = $dataIn->export_bill_port_code;
                $dataArr1['inv'][$y]['sbnum'] = $dataIn->export_bill_number;
                $dataArr1['inv'][$y]['sbdt'] = $dataIn->export_bill_date > 0 ? date('d-m-Y', strtotime($dataIn->export_bill_date)) : '';
                $dataArr1['inv'][$y]['itms'][$z]['txval'] = (float) $dataIn->taxable_subtotal;
                $dataArr1['inv'][$y]['itms'][$z]['rt'] = (float) $rt;
                $dataArr1['inv'][$y]['itms'][$z]['iamt'] = (float) $dataIn->igst_amount;
                $temp_number = $dataIn->reference_number;
                $z++;
                $exp_array[] = (array) $dataIn;
            }
        }
        if (isset($mydata['withoutpayment']) && !empty($mydata['withoutpayment'])) {
            $x = 0;
            $y = 0;
            $z = 0;
            $temp_number = '';
            foreach ($mydata['withoutpayment'] as $dataIn) {
                $rt = ($dataIn->company_state == $dataIn->supply_place) ? ($dataIn->sgst_rate + $dataIn->cgst_rate) : $dataIn->igst_rate;
                if ($temp_number != '' && $temp_number != $dataIn->reference_number) {
                    $z = 0;
                    $y++;
                }
                $dataArr2['exp_typ'] = "WOPAY";
                $dataArr2['inv'][$y]['inum'] = $dataIn->reference_number;
                $dataArr2['inv'][$y]['idt'] = date('d-m-Y', strtotime($dataIn->invoice_date));
                $dataArr2['inv'][$y]['val'] = (float) $dataIn->invoice_total_value;
                $dataArr2['inv'][$y]['sbpcode'] = $dataIn->export_bill_port_code;
                $dataArr2['inv'][$y]['sbnum'] = $dataIn->export_bill_number;
                $dataArr2['inv'][$y]['sbdt'] = $dataIn->export_bill_date > 0 ? date('d-m-Y', strtotime($dataIn->export_bill_date)) : '';
                $dataArr2['inv'][$y]['itms'][$z]['txval'] = (float) $dataIn->taxable_subtotal;
                $dataArr2['inv'][$y]['itms'][$z]['rt'] = (float) $rt;
                $dataArr2['inv'][$y]['itms'][$z]['iamt'] = (float) $dataIn->igst_amount;
                $temp_number = $dataIn->reference_number;
                $z++;
                $exp_array[] = (array) $dataIn;
            }
        }
    }
    if (!empty($exp_array)) {
        $exp_ids['client_invoice']['invoice_id'] = array_unique(array_column($exp_array, 'invoice_id'));
    }

    $x = 0;
    if (!empty($dataArr1)) {
        $dataArrExp['exp'][$x] = $dataArr1;
        $x++;
    }
    if (!empty($dataArr2)) {
        $dataArrExp['exp'][$x] = $dataArr2;
    }
}

$json_data = json_encode($dataArrExp);
echo '<pre>';
print_r($json_data);die;
//echo '<br/>';

//End Code For Doc
echo $json_data;
die;
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