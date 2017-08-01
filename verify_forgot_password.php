<?php
include_once('conf/config.inc.php');
$obj_login = new login();
$db_obj = new common();
$theme_data = $obj_login->getTheme();
if(isset($theme_data[0]->theme_name) && $theme_data[0]->theme_name!='') {
    
	define('THEME_PATH',THEME_DIR .$theme_data[0]->theme_folder);
    define('THEME_URL',PROJECT_URL."/template/" .$theme_data[0]->theme_folder);
} else {
    
	define('THEME_PATH',THEME_DIR .'gst_portal');
    define('THEME_URL',PROJECT_URL."/template/gst_portal");
}
 

if(isset($_GET['verifyForgot']) && isset($_GET['passkey']))
		{
			$_SESSION['user_detail']['passkey']=$_GET['passkey'];
			$_SESSION['user_detail']['token']=$_GET['verifyForgot'];
			if($db_obj->forgotEmailVerify())
			{
			
			//$_SESSION["success_verify"] ="success";
			$db_obj->redirect(PROJECT_URL."/verify_forgot_password.php");
			}
			else
			{
				unset($_SESSION['user_detail']['passkey']);
			    unset($_SESSION['user_detail']['token']);
				$db_obj->redirect(PROJECT_URL."/forgot.php");
			}
			
			
		}
		if(isset($_SESSION['user_detail']['passkey']) && (isset($_SESSION['user_detail']['token'])))
		{
			
		}
		else
		{
			$db_obj->redirect(PROJECT_URL);
		}
	
	
		
if (isset($_POST['resetpass']) && $_POST['resetpass'] == 'Reset Password'){

	if(isset($_SESSION['user_detail']['passkey']) && ($_SESSION['user_detail']['token']))
	{
		
			if($obj_login->updatePassword())
			{
			unset($_SESSION['user_detail']['passkey']);
			unset($_SESSION['user_detail']['token']);
			$_SESSION["success_verify_forgot"]="success";
		
			}
	}
	else
			{
				$db_obj->redirect(PROJECT_URL);
			}
}

?>


<html lang="En">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
        <title>ResetPassword Page</title>
        <link type="text/css" rel="stylesheet" href="<?php echo THEME_URL; ?>/css/style.css?2" />
        <link type="text/css" rel="stylesheet" href="<?php echo THEME_URL; ?>/css/font-awesome.min.css?1" />
        <script src="<?php echo THEME_URL; ?>/js/jquery-3-2.js?1"></script>
		 
        <script>
            $(document).ready(function () {
                $(".userlogin").click(function (e) {

                    $(".admintab li").removeClass("active");
                    $(this).addClass("active");
                    $(".logincontent").show();
                    $(".registercontent").hide();
                });
                $(".userregister").click(function (e) {
                    //   $(".loginpage h1").text("Welcome to User Registration Form");
                    $(".admintab li").removeClass("active");
                    $(this).addClass("active");
                    $(".logincontent").hide();
                    $(".registercontent").show();
                });
            });
        </script>
		
		<script>
		  //$(document).ready(function(){
        //setTimeout(function() {
         // $('#sucmsg').fadeOut('fast');
       // }, 10000); // <-- time in milliseconds
		
   // });
		</script> 
		
    </head>
    <body class="loginpage">
        <div class="loginleftcontent">
            <h1>A Complete GST  Solutions with <span>GST KEEPER</span></h1>
            <ul>
                <li>Configure formats of GST Invoices as per your business or professional needs</li>
                <li>Create varieties of Masters for quick entries </li>
                <li>Automatic Calculation of GST </li>
                <li>Auto-calculate GSTR from purchase and sales invoices  </li>
                <li>Directly upload the invoice from One Solution on GSTN   </li>
            </ul>
        </div>
		
        <div class="loginbx">
            <a class="adminlogo" href="<?php echo PROJECT_URL; ?>" target="_blank"><img src="<?php echo PROJECT_URL;?>/image/logo.png" title="GST Keeper" alt="GST Keeper" /></a>
            <div class="logincontainer">
               <ul class="admintab">
                    <li class="userlogin"><a href="<?php echo PROJECT_URL; ?>">SIGN IN</a></li>
					   <li class="userregister"><a href="<?php echo PROJECT_URL; ?>">Register</a></li>
                   
                </ul>
                <div class="adminloginbx logincontent">
                    <p>Please enter your new password details here</p>
					<div class="clear" ></div>
                 
                        <?php $obj_login->showErrorMessage(); ?>
                        <?php $obj_login->unsetMessage(); ?>
						 <?php $obj_login->showSuccessMessge(); ?>
                 <?php
				if (isset($_SESSION["success_verify_forgot"]) && $_SESSION["success_verify_forgot"]=="success")
				{
					unset($_SESSION["success_verify_forgot"]);
				?>
				
		
                     <div id='sucmsg' style='background-color:#DBEDDF;border-radius:4px;padding:8px 35px 8px 14px;text-shadow:0 1px 0 rgba(255, 255, 255, 0.5);margin-bottom:18px;border-color:#D1E8DA;color:#39A25F;'><i class='fa fa-check'></i> <b>Your password has been successfully changed,<a href="<?php echo PROJECT_URL; ?>">Click here to login.</a></b></div>
				<?php } ?>
				 <?php
				if (isset($_SESSION["success_verify"]) && $_SESSION["success_verify"]=="success")
				{
					unset($_SESSION["success_verify"]);
				?>
				
		
                     <div id='sucmsg' style='background-color:#DBEDDF;border-radius:4px;padding:8px 35px 8px 14px;text-shadow:0 1px 0 rgba(255, 255, 255, 0.5);margin-bottom:18px;border-color:#D1E8DA;color:#39A25F;'><i class='fa fa-check'></i> <b>Your email is verified please update your new password.</b></div>
				<?php } ?>
                 <form id="form-user-forgot" name="form-user-forgot" method="POST">
			
                        <div class="admintxt">

                            <input type="password" id="password" name="password" placeholder="Password" />
                            <strong class="fa fa-key" aria-hidden="true"></strong>
                        </div>
						  <div class="admintxt">

                            <input type="password" id="confirmpassword" name="confirmpassword" placeholder="Confirm Password" />
                            <strong class="fa fa-key" aria-hidden="true"></strong>
                        </div>
						<input type="hidden" name="userid" id="userid" value="<?php echo $_GET["passkey"]; ?>" />
                       
                     
                        <input type="submit" name="resetpass" class="btnsubmit" id="resetpass" value="Reset Password" />
                    </form>
                </div>
                 
 
  
  </div>
  
</div>
	   
	   
    </body>
</html>