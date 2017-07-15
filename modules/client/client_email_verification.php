<?php
$obj_client = new client();
if(isset($_POST['submit']) && $_POST['submit']=='Send Verification Mail')
{
	$obj_client->sendVerifcationEmail();
}
?>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">
		<div class="clear"></div>
            <?php $obj_client->showErrorMessage(); ?>
			<?php $obj_client->showSuccessMessge(); ?>
			<?php $obj_client->unsetMessage(); ?>
		
        <div class="clear"></div>
        <div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>Subscriber Email ID Verification</h1></div>
		<div class="whitebg formboxcontainer">
			<h4>Kindly click on send mail button to send verification mail on your registered mail address</h4>
			<form method="post">
				<input type="submit" name="submit"  class="btn btn-danger"  value="Send Verification Mail">
			</form>
		</div>
	</div>
</div>