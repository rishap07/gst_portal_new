<?php 
$ref_id = '212715072817';
$amount= '1.00';
$Redirect_Url= 'http://gstkeeper.com/portal/';
$hashData = "6cf89c6e859c40b24975b263c9fe5ee6"; //Pass your Registered Secret Key

$dataArr['channel'] = '0';
$dataArr['account_id'] = '25039';
$dataArr['reference_no'] = $ref_id;
$dataArr['amount'] = $amount;
$dataArr['currency'] = 'INR';
$dataArr['display_currency'] = 'GBP';
$dataArr['display_currency_rates'] = '1';
$dataArr['description'] = 'Test Order Description';
$dataArr['return_url'] = $Redirect_Url;
$dataArr['mode'] = 'LIVE';
$dataArr['name'] = 'Billing Name';
$dataArr['address'] = 'Billing Address';
$dataArr['city'] = 'Billing City';
$dataArr['postal_code'] = '600001';
$dataArr['country'] = 'IND';
$dataArr['email'] = 'name@yourdomain.in';
$dataArr['phone'] = '04423452345';

ksort($dataArr);
//die;

foreach ($dataArr as $key => $value){
	if (strlen($value) > 0) {
		$hashData .= '|'.$value;
	}
}
echo $hashData;
if (strlen($hashData) > 0) {
	$secure_hash = strtoupper(hash("sha512",$hashData));
}
?>
<form action="https://secure.ebs.in/pg/ma/payment/request" name="payment" target="_blank" method="POST" id="payment">
<?php
foreach ($dataArr as $key => $value){

		?>
		<input type="text" value="<?php echo $value;?>" name="<?php echo $key;?>"/>
		<?php
	
}
?>
<input type="text" value="<?php echo $secure_hash; ?>" name="secure_hash"/>
  <button onclick="document.payment.submit();"> SUBMIT </button>
</form>