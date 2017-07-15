<?php
$obj_client = new client();
if(isset($_POST['submit']) && $_POST['submit']=='Send OTP')
{
	$obj_client->sendVerifcationSms();
}
if(isset($_POST['submit1']) && $_POST['submit1']=='Submit OTP')
{
	$obj_client->checkSmsOTP();
}
?>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">
		<div class="clear"></div>
            <?php $obj_client->showErrorMessage(); ?>
			<?php $obj_client->showSuccessMessge(); ?>
			<?php $obj_client->unsetMessage(); ?>
		
        <div class="clear"></div>
        <div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>Subscriber Mobile Number Verification</h1></div>
		<div class="whitebg formboxcontainer">
			<h4>Kindly click on submit button to send OTP on your registered mobile number.</h4>
			<form method="post">
				
				<div class="row">
					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<input type="submit"  class="btn btn-danger" name='submit' name="submit" value="Send OTP"><br>
					</div>
				</div>
				<div class="clear"></div>
				 
				<div class="row">
					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Enter OTP</label>
						<input type="text" class="form-control" name="otp" />
					</div>
				</div>
				<div class="clear"></div>
				<input type="submit"  class="btn btn-danger" name='submit1'  value="Submit OTP">
			</form>
		</div>
	</div>
</div>