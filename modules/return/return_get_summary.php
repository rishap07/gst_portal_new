<?php
$obj_gstr1 = new gstr1();
$obj_api =  new gstr();
if (!isset($_REQUEST['returnmonth']) || $_REQUEST['returnmonth'] == '') 
{
  $obj_gstr1->redirect(PROJECT_URL . "/?page=return_client");
  exit();
}
if (isset($_POST['returnmonth'])) 
{
  $returnmonth = $_POST['returnmonth'];
  $obj_gstr1->redirect(PROJECT_URL . "/?page=return_get_summary&returnmonth=" . $returnmonth);
  exit();
}

$returnmonth = '2017-07';
if ($_REQUEST['returnmonth'] != '') {
  $returnmonth = $_REQUEST['returnmonth'];
}
if(!empty($returnmonth)) {
  $api_return_period_array = explode('-',$returnmonth);
  $api_return_period = $api_return_period_array[1].$api_return_period_array[0];
}

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
$username='Karthiheyini.TN.1';
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

$session_key = $data->sek;
$decrypt_sess_key=openssl_decrypt(base64_decode($session_key),"aes-256-ecb",$key, OPENSSL_RAW_DATA);

$data = json_decode($result_data);
$query =  "select a.company_state,a.billing_gstin_number,a.reference_number,a.invoice_date,a.invoice_total_value,a.supply_place,a.invoice_type,a.supply_type,b.igst_rate,b.cgst_rate,b.sgst_rate,b.taxable_subtotal, sum(b.igst_amount) as igst_amount, sum(b.cgst_amount) as cgst_amount, sum(b.sgst_amount) as sgst_amount,sum(b.cess_amount) as cess_amount from ".TAB_PREFIX."client_invoice a inner join ".TAB_PREFIX."client_invoice_item b on a.invoice_id=b.invoice_id where a.is_gstr1_uploaded='0' and a.status='1' and a.added_by='".$_SESSION['user_detail']['user_id']."'  and a.invoice_date like '%".$returnmonth."%' and a.billing_gstin_number!='' group by a.reference_number, b.igst_rate";

$dataInv = $obj_api->get_results($query);
$dataArr = array();
$dataArr["gstin"]= "33GSPTN0741G1ZF";
$dataArr["fp"]= $api_return_period;
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

$query =  "select a.company_state,a.billing_gstin_number,a.reference_number,a.invoice_date,a.invoice_total_value,a.supply_place,a.invoice_type,a.supply_type,b.igst_rate,b.cgst_rate,b.sgst_rate,b.taxable_subtotal, sum(b.igst_amount) as igst_amount, sum(b.cgst_amount) as cgst_amount, sum(b.sgst_amount) as sgst_amount,sum(b.cess_amount) as cess_amount from ".TAB_PREFIX."client_invoice a inner join ".TAB_PREFIX."client_invoice_item b on a.invoice_id=b.invoice_id where a.is_gstr1_uploaded='0' and a.status='1' and a.added_by='".$_SESSION['user_detail']['user_id']."' and a.invoice_date like '%".$returnmonth."%' and a.billing_gstin_number!='' and a.invoice_total_value>'250000' and a.supply_place!=a.company_state group by a.reference_number, b.igst_rate order by a.supply_place ";
$dataInv = $obj_api->get_results($query);
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



$query =  "select a.invoice_id,a.company_state,a.billing_gstin_number,a.reference_number,a.invoice_date,a.invoice_total_value,a.supply_place,a.invoice_type,a.supply_type,b.igst_rate,b.cgst_rate,b.sgst_rate,b.taxable_subtotal, sum(b.igst_amount) as igst_amount, sum(b.cgst_amount) as cgst_amount, sum(b.sgst_amount) as sgst_amount,sum(b.cess_amount) as cess_amount from ".TAB_PREFIX."client_invoice a inner join ".TAB_PREFIX."client_invoice_item b on a.invoice_id=b.invoice_id where a.is_gstr1_uploaded='0' and a.status='1' and a.added_by='".$_SESSION['user_detail']['user_id']."'  and a.invoice_date like '%".$returnmonth."%' and a.billing_gstin_number='' and (a.supply_place=a.company_state  or (a.supply_place!=a.company_state and a.invoice_total_value<='250000')) group by a.reference_number, b.igst_rate order by a.supply_place ";
$dataInv = $obj_api->get_results($query);

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

$json_data = json_encode($dataArr);
$encodejson=base64_encode(openssl_encrypt(base64_encode($json_data),"aes-256-ecb",$decrypt_sess_key, OPENSSL_RAW_DATA));

$hmac = base64_encode(hash_hmac('sha256', base64_encode($json_data), $decrypt_sess_key, true));

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
  'ret_period: '.$api_return_period.' ',
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


$retData=$datasave->data;
$rek=$datasave->rek;
$apiEk=openssl_decrypt(base64_decode($rek),"aes-256-ecb",$decrypt_sess_key, OPENSSL_RAW_DATA);
$decodejson= base64_decode(openssl_decrypt(base64_decode($retData),"aes-256-ecb",$apiEk, OPENSSL_RAW_DATA));
$ref = json_decode($decodejson);
$refId=$ref->reference_id;

$header2 = array(
  'accept:application/json',
  'auth-token:' . $data->auth_token . '',
  'client-secret:' . $client_secret . '',
  'clientid:' . $clientid . '',
  'Content-Type: application/json',
  'gstin:' . $gstin . '',
  'ip-usr:' . $ip_usr . '',
  'ret_period:'.$api_return_period.' ',
  'state-cd:' . $state_cd . '',
  'txn:' . $txn . '',
  'username:' . $username . '',
  'action:' . 'RETSTATUS' . ''
);
$url2 = 'http://devapi.gstsystem.co.in/taxpayerapi/v0.3/returns?action=RETSTATUS&gstin='.$gstin. '&ret_period='.$api_return_period.'&ref_id='.$refId.'';

function hitUrl2($url, $data_string, $header)
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
$result_data1 = hitUrl2($url2, '', $header2);
$retDta = json_decode($result_data1);
$retRek=$retDta->rek;
$retData1=$retDta->data;

$apiEk1=openssl_decrypt(base64_decode($retRek),"aes-256-ecb",$decrypt_sess_key, OPENSSL_RAW_DATA);
$decodejson1= base64_decode(openssl_decrypt(base64_decode($retData1),"aes-256-ecb",$apiEk1, OPENSSL_RAW_DATA));

$getReturnUrl='http://devapi.gstsystem.co.in/taxpayerapi/v0.3/returns/gstr1?gstin='.$gstin. '&ret_period='.$api_return_period.'&action=B2B';
$result_data1 = hitUrl2($getReturnUrl, '', $header2);
$retDta = json_decode($result_data1);
$retRek=$retDta->rek;
$retData1=$retDta->data;
$apiEk1=openssl_decrypt(base64_decode($retRek),"aes-256-ecb",$decrypt_sess_key, OPENSSL_RAW_DATA);
$decodejson1= base64_decode(openssl_decrypt(base64_decode($retData1),"aes-256-ecb",$apiEk1, OPENSSL_RAW_DATA));
?>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr">
    <div class="col-md-11 col-sm-12 col-xs-12 mobpadlr">
        <div class="col-md-12 col-sm-12 col-xs-12 heading">
            <div class="tab col-md-12 col-sm-12 col-xs-12">
                <a href="<?php echo PROJECT_URL . '/?page=return_summary&returnmonth=' . $returnmonth ?>" >
                    View GSTR1 Summary
                </a>
                <a href="<?php echo PROJECT_URL . '/?page=return_view_invoices&returnmonth=' . $returnmonth ?>">
                    View My Invoice
                </a>
                <a href="<?php echo PROJECT_URL . '/?page=return_upload_invoices&returnmonth=' . $returnmonth ?>"  >
                    Upload To GSTN
                </a>
                </a>
                <a href="<?php echo PROJECT_URL . '/?page=return_filling_summary&returnmonth=' . $returnmonth ?>">
                    File GSTr-1
                </a>
                <a href="<?php echo PROJECT_URL . '/?page=return_get_summary&returnmonth=' . $returnmonth ?>" class="active">
                    GSTR1 SUMMARY
                </a>                
            </div>
            <div id="get_summary" class="tabcontent">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="col-md-6 col-sm-12 col-xs-12"><h3>GSTR1 Summary</h3></div>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="whitebg formboxcontainer">
                            <div class="pull-right rgtdatetxt">
                                <form method='post' name='form2'>
                                    Month Of Return 
                                    <?php
                                    $dataQuery = "SELECT DATE_FORMAT(invoice_date,'%Y-%m') AS niceDate FROM gst_client_invoice group by nicedate";
                                    $dataRes = $obj_gstr1->get_results($dataQuery);
                                    if (!empty($dataRes)) {
                                        ?>
                                        <select class="dateselectbox" id="returnmonth" name="returnmonth">
                                        <?php
                                        foreach ($dataRes as $dataRe) {
                                            ?>
                                                <option value="<?php echo $dataRe->niceDate; ?>" <?php if ($dataRe->niceDate == $returnmonth) { echo 'selected'; } ?>><?php echo $dataRe->niceDate; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                        <?php
                                    } else {
                                        ?>
                                        <select class="dateselectbox" id="returnmonth" name="returnmonth">
                                            <option>July 2017</option>
                                        </select>
                                    <?php }
                                    ?>
                                </form>
                            </div>
                            <div class="clearfix"></div>
                            <div id="display_json"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    get_summary();
    
    $(document).ready(function () {
        $('#returnmonth').on('change', function () {
            document.form2.action = '<?php echo PROJECT_URL; ?>/?page=return_get_summary&returnmonth=<?php echo $returnmonth; ?>';
            document.form2.submit();            
        });
    });

    /******* To get Summary of GSTR1 ********/
    function get_summary() {
        var json = '<?php echo $decodejson1;?>';
        $.ajax({
            url: "<?php echo PROJECT_URL; ?>/?ajax=api_gstr1_json",
            type: "post",
           data: {json: json},
            success: function (response) {
               $('#display_json').html(response);

            },
            error: function() {
            }
        });
    }
    /******* To get Summary of GSTR1 ********/
</script>