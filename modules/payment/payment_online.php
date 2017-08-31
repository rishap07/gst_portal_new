<?php
	$obj_pay = new processpayment();
	if($_POST && isset($_POST['submit'])){
		$process = $obj_pay->pay_now('subscriber_plan', array('id','plan_price'), 'response_payment');
	}
?>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>Choose Plan</h1></div>
		<div class="clear"></div>
		<h2 class="greyheading">Process payment</h2>
		
		<div class="whitebg formboxcontainer">
			<div class="row">
				<div class="clear"></div>
				<?php $obj_pay->showErrorMessage(); ?>
				<?php $obj_pay->showSuccessMessge(); ?>
				<?php $obj_pay->unsetMessage(); ?>
				<div class="clear"></div>
				<p class="tc" style="font-weight:normal;font-size:17px;">You have selected Online payment method to pay the application fees. <br>Click on the Button Below and you'll be redirected to  payment gateway for payment.</p>
				<div class="clearfix height20"></div>
				<form method="post">
					<div class="tc">
					    Coupon Code : 
						<input type="text" name="coupon" placeholder="Coupon">
					</div>
					<div class="clear height20"></div>
					<div class="tc">
						<input type="submit" name="submit" value="Proceed to Payment" class="btn btn-success" style="padding-left:20px;padding-right:20px;width:auto;">
					</div>
				</form>
			</div>
		</div>
	</div>
</div>