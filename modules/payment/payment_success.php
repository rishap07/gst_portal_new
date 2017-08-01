<div class="redbanner">
	<div class="container">
    	<div class="col-sm-6 col-xs-12 col-md-12 banner-heading">
			<h1>Payment success</h1>
        </div>
    </div>
</div>
<!--HEADER START HERE-->

<div class="bodycontent">
	<div class="container bxshadow">
		<div class="breadcrumb-li"><a href="<?php echo PROJECT_URL; ?>">Home</a>/<span>Process payment</span></div>
		<div class="formbox step1">
			<div class="col-sm-12 col-xs-12 col-md-12" style="padding-left:0px;">
				<div class="form clinicform">
					<h2 class="tc">Thank you for payment.</h2>
					<div class="clearfix height20"></div>
					<p class="tc" style="font-weight:normal;font-size:17px;">Your payment has been success.<br>Your Transaction ID is :<span style="color:#d23001;"> <?php echo $_SESSION['txn_id'] ?></span></p>
					<div class="clearfix height20"></div>
				</div>
			</div> 
		</div>
	</div>
</div>