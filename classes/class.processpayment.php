<?php

/*
 * 
 *  Developed By        :   Himanshu Chittora
 *  Date Created        :   July 06, 2017
 *  Purpose   			:   class for Proceed Payment
 * 
 */

class processpayment extends validation
{
	public function __construct() {
        parent::__construct();
    }
	
	/* 
	Main function to proceed for payment.
	$tablename = Tablename here;
	$columnname = array of name of unique id and Amount  fields
	$raturnpage = Page name where user will redirect after payment.
	*/
	
	
	function pay_now($tablename,$columnname,$returnpage)
	{
	
	if($_POST && isset($_POST['submit'])){
	$get_amount= $this->findAll(TAB_PREFIX.$tablename,$columnname[0].'='.$_SESSION['plan_id'],"".$columnname[1]." as amount");
	$get_UserDetails= $this->findAll(TAB_PREFIX.'user',' user_id='.$_SESSION['user_detail']['user_id']);

			$cur_date = date('Y-m-d H:i:s');
			$ref_id = date('siHmdy');
			//Update payment process date
            $this->update('user_subscribed_plan', array('ref_id' => $ref_id), array('id' => $_SESSION['subs_id']));
			
			//Insert data in payment log
			$this->insert(TAB_PREFIX .'payment_log',array('process_payment_id'=>$ref_id,'datetime'=>$cur_date,'status'=>'0'));


	date_default_timezone_set('Asia/Calcutta');
	
	//echo date_default_timezone_get();
	
	$strCurDate = date('d-m-Y');
	
$hashData = "e21158bc40562aaf6c013b08be891b26"; //Pass your Registered Secret Key
$Redirect_Url= PROJECT_URL . '/response.php';
$hash = $hashData."|".urlencode('9006')."|".urlencode($get_amount[0]->amount)."|".urlencode($ref_id)."|".$Redirect_Url."|".urlencode('TEST');

$secure_hash = md5($hash); 
	?>
    <form action="https://secure.ebs.in/pg/ma/payment/request" name="payment" method="POST" id="payment">

<input type="hidden" value="9006" name="account_id"/>
<input type="hidden" value="<?php echo $get_amount[0]->amount; ?>" name="amount"/>
<input type="hidden" value="0" name="channel"/>
<input type="hidden" value="<?php echo $get_UserDetails[0]->username; ?>" name="name"/>
<input type="hidden" value="jaipur" name="address"/>
<input type="hidden" value="jaipur" name="city"/>
<input type="hidden" value="IN" name="country"/>
<input type="hidden" value="302022" name="postal_code"/>
<input type="hidden" value="INR" name="currency"/>
<input type="hidden" value="9876543210" name="phone"/>
<input type="hidden" value="himanshuchittora@gmail.com" name="email"/>
<input type="hidden" value="Payment information from GST" name="description"/>
<input type="hidden" value="GBP" name="display_currency"/>
<input type="hidden" value="1" name="display_currency_rates"/>
<input type="hidden" value="TEST" name="mode"/>
<input type="hidden" value="<?php echo $ref_id; ?>" name="reference_no"/>
<input type="hidden" value="<?php echo $Redirect_Url; ?>" name="return_url"/>
<input type="hidden" value="<?php echo $secure_hash ?>" name="secure_hash"/>
</form>

		<script type="text/javascript">
		window.onload=func1;
		function func1(){
			
			document.payment.submit();
		}
		</script>       
    <?php
	
	}
	}
}
?>