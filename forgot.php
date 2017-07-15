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
if (isset($_POST['forgot']) && $_POST['forgot'] == 'SendEmail'){

    if (!isset($_SERVER['HTTP_REFERER']) || empty($_SERVER['HTTP_REFERER'])) {
        $obj_login->setError('Invalid access to files');
    } else {
		
     if ($obj_login->forgotPassword()) {
       // $obj_login->setError('Kindly check your email for verification link.');
	   	$db_obj->setError('Kindly check your email for verification link.');	

      }
	   
	 
    }
}
?>
<html lang="En">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
        <title>ForgotPassword Page</title>
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
               
                <div class="adminloginbx logincontent" style="<?php if (isset($_POST['login_me']) && $_POST['login_me'] == 'LOGIN') { echo "display:block";}else if (isset($_POST['register_me']) && $_POST['register_me'] == 'REGISTER') { echo "display:none";}?>">
                    <p>Please enter your email address here</p>
					<div class="clear" ></div>
                    <?php if (isset($_POST['forgot']) && $_POST['forgot'] == 'SendEmail') { ?>
                        <?php $obj_login->showErrorMessage(); ?>
                        <?php $obj_login->unsetMessage(); ?>
						 <?php  $obj_login->showSuccessMessge(); ?>
                    <?php } ?>
                 <form id="form-user-forgot" name="form-user-forgot" method="POST">
			
                       <div class="admintxt">
                            <input id="email" name="email" autocomplete="off" type="text" placeholder="Email Address" value="<?php echo isset($_POST['email']) ? $obj_login->sanitize($_POST['email']) : ''; ?>" required />
                            <strong class="fa fa-envelope" aria-hidden="true"></strong>
                        </div>
                       
                     
                        <input type="submit" name="forgot" class="btnsubmit" id="forgot" value="SendEmail" />
                    </form>
                </div>
                 
 
  
  </div>
  
</div>
	   </div>
	   
    </body>
</html>