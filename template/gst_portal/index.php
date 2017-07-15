<?php
$obj_login = new login();

if (isset($_COOKIE['preserveKey']) && $_COOKIE['preserveKey'] != '') {
    $preserveSet = $obj_login->getPreserveData($_COOKIE['preserveKey']);
    if (count($preserveSet)) {
        $userData = $obj_login->getUserDetailsById($preserveSet->user_id);
        $_SESSION['user_detail']['user_id'] = $userData['data']->user_id;
        $_SESSION['user_detail']['username'] = $userData['data']->username;
        $_SESSION['user_detail']['email'] = $userData['data']->email;
        $_SESSION['user_detail']['name'] = $userData['data']->name;
        $_SESSION['user_detail']['user_group'] = $userData['data']->user_group;
        $obj_login->redirect(PROJECT_URL . "?page=dashboard");
    }
} else if (isset($_SESSION['user_detail']['user_id']) && intval($_SESSION['user_detail']['user_id']) > 0 && $_SESSION['user_detail']['user_id'] != '') {
    $userData = $obj_login->getUserDetailsById($_SESSION['user_detail']['user_id']);
    $_SESSION['user_detail']['user_id'] = $userData['data']->user_id;
    $_SESSION['user_detail']['username'] = $userData['data']->username;
    $_SESSION['user_detail']['email'] = $userData['data']->email;
    $_SESSION['user_detail']['name'] = $userData['data']->name;
    $_SESSION['user_detail']['user_group'] = $userData['data']->user_group;
    $obj_login->redirect(PROJECT_URL . "?page=dashboard");
}

if (isset($_POST['login_me']) && $_POST['login_me'] == 'LOGIN') {

    if (!isset($_SERVER['HTTP_REFERER']) || empty($_SERVER['HTTP_REFERER'])) {
        $obj_login->setError('Invalid access to files');
    } else {
        if ($obj_login->loginUser()) {
            $obj_login->redirect(PROJECT_URL . "?page=dashboard");
        }
    }
}

if (isset($_POST['register_me']) && $_POST['register_me'] == 'REGISTER') {

    if (!isset($_SERVER['HTTP_REFERER']) || empty($_SERVER['HTTP_REFERER'])) {
        $obj_login->setError('Invalid access to files');
    } else {
        if ($obj_login->registerUser()) {
            $obj_login->redirect(PROJECT_URL . "?page=dashboard");
        }
    }
}
if (isset($_POST['forgot']) && $_POST['forgot'] == 'SendEmail'){

    if (!isset($_SERVER['HTTP_REFERER']) || empty($_SERVER['HTTP_REFERER'])) {
        $obj_login->setError('Invalid access to files');
    } else {
      // if ($obj_login->forgotPassword()) {
       //  $obj_login->setSuccess('Kindly check your email for verification.');
      // }
	   
	 
    }
}


?>

<!DOCTYPE html>
<html lang="En">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
        <title>Login Page</title>
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
function forgotSubmit(){
    var reg = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
    
    var email = $('#email').val();
   
    if(email.trim() == '' ){
        alert('Please enter your email.');
        $('#email').focus();
        return false;
   
    }else{
		
        $.ajax({
            type:'POST',
            url:'<?php echo PROJECT_URL; ?>/ajax=forgot_submit',
            data:'forgotSubmit=1&email='+email,
            beforeSend: function () {
                //$('.submitBtn').attr("disabled","disabled");
                //$('.modal-body').css('opacity', '.5');
            },
            success:function(msg){
                if(msg == 'ok'){
                   
                    $('#email').val('');
                  
                   // $('.statusMsg').html('<span style="color:green;">Thanks for contacting us, we\'ll get back to you soon.</p>');
                }else{
                  //  $('.statusMsg').html('<span style="color:red;">Some problem occurred, please try again.</span>');
                }
               // $('.submitBtn').removeAttr("disabled");
               // $('.modal-body').css('opacity', '');
            }
        });
    }
}
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
            <a class="adminlogo" href="" target="_blank"><img src="<?php echo PROJECT_URL;?>/image/logo.png" title="GST Keeper" alt="GST Keeper" /></a>
            <div class="logincontainer">
                <ul class="admintab">
                    <li class="userlogin  <?php if (isset($_POST['login_me']) && $_POST['login_me'] == 'LOGIN') { echo "active";}if (!isset($_POST['login_me']) && $_POST['login_me'] != 'LOGIN'  && !isset($_POST['register_me']) && $_POST['register_me'] != 'REGISTER'){ echo 'active';	}?>""><a href="javascript:void(0)">SIGN IN</a></li>
                    <li class="userregister <?php if (isset($_POST['register_me']) && $_POST['register_me'] == 'REGISTER') { echo "active";}?>"><a href="javascript:void(0)"> Register</a></li>
                </ul>
                <div class="adminloginbx logincontent" style="<?php if (isset($_POST['login_me']) && $_POST['login_me'] == 'LOGIN') { echo "display:block";}else if (isset($_POST['register_me']) && $_POST['register_me'] == 'REGISTER') { echo "display:none";}?>">
                    <p>Please enter your Username and Password</p>
					<div class="clear" ></div>
                    <?php if (isset($_POST['login_me']) && $_POST['login_me'] == 'LOGIN') { ?>
                        <?php $obj_login->showErrorMessage(); ?>
                        <?php $obj_login->unsetMessage(); ?>
						 <?php $obj_login->showSuccessMessge(); ?>
                    <?php } ?>
                    <form id="form-user-login" name="form-user-login" method="POST">
					
                        <div class="admintxt">	
                            <input type="text" name="login_username" id="login_username" placeholder="Enter Your Username" />
                            <strong class="fa fa-user" aria-hidden="true"></strong>
                        </div>
                        <div class="admintxt">

                            <input type="password" id="login_password" name="login_password" placeholder="Password" />
                            <strong class="fa fa-key" aria-hidden="true"></strong>
                        </div>
                        <div class="rememberbx">
                            <input type="checkbox" id="login_rememberme" name="login_rememberme" value="1" /> <label for="login_rememberme">Remember Me</label>
<!--                            <a href="" class="forgetpass">Forget Password</a>-->
                           <a href="forgot.php" class="forgetpass" >Forgot Password</a>
						   
                        </div>
                        <input type="submit" name="login_me" class="btnsubmit" id="login_me" value="LOGIN" />
                    </form>
                </div>
                  <div class="registercontent"  style="<?php if (isset($_POST['register_me']) && $_POST['register_me'] == 'REGISTER') { echo "display:block";}else if (isset($_POST['login_me']) && $_POST['login_me'] == 'LOGIN') { echo "display:none";}?>">
				  
					<div class="clear"></div>
                    <?php if (isset($_POST['register_me']) && $_POST['register_me'] == 'REGISTER') { ?>
                        <?php $obj_login->showErrorMessage(); ?>
                        <?php $obj_login->unsetMessage(); ?>
                    <?php } ?>
                    <form id="form-user-register" name="form-user-register" method="post">
					
					 <div class="admintxt">
                	
                	<input id="firstname" name="firstname" type="text" placeholder="First Name" autocomplete="off" value="<?php echo isset($_POST['firstname']) ? $obj_login->sanitize($_POST['firstname']) : ''; ?>" required />
                    <strong class="fa fa-user" aria-hidden="true"></strong>
                </div>
                
                <div class="admintxt">
                	
                	<input id="lastname" name="lastname" type="text" autocomplete="off" value="<?php echo isset($_POST['lastname']) ? $obj_login->sanitize($_POST['lastname']) : ''; ?>" required placeholder="Last Name" />
                    <strong class="fa fa-user" aria-hidden="true"></strong>
                </div>
				  <div class="admintxt">
                	
                	<input id="companyname" name="companyname" type="text" placeholder="Company Name" autocomplete="off" value="<?php echo isset($_POST['companyname']) ? $obj_login->sanitize($_POST['companyname']) : ''; ?>" required />
                    <strong class="fa fa-building" aria-hidden="true"></strong>
                </div>
                        <div class="admintxt">
                            <input id="username" name="username" type="text" autocomplete="off" placeholder="Enter Username" value="<?php echo isset($_POST['username']) ? $obj_login->sanitize($_POST['username']) : ''; ?>" required />
                            <strong class="fa fa-user" aria-hidden="true"></strong>
                        </div>
						 <div class="admintxt">
                	
                	<input id="mobilenumber" type="text" name='mobilenumber' autocomplete="off" value="<?php echo isset($_POST['mobilenumber']) ? $obj_login->sanitize($_POST['mobilenumber']) : ''; ?>" placeholder="Phone Number" required />
                    <strong class="fa fa-phone" aria-hidden="true"></strong>
                </div>
						
                        <div class="admintxt">
                            <input id="emailaddress" name="emailaddress" autocomplete="off" type="text" placeholder="Email Address" value="<?php echo isset($_POST['emailaddress']) ? $obj_login->sanitize($_POST['emailaddress']) : ''; ?>" required />
                            <strong class="fa fa-envelope" aria-hidden="true"></strong>
                        </div>
                        <div class="admintxt">
                            <input id="password" name="password" type="password" placeholder="Password" required />
                            <strong class="fa fa-key" aria-hidden="true"></strong>
                        </div>
                        <div class="admintxt">
                            <input id="confirmpassword" name="confirmpassword" type="password" placeholder="Confirm Password" required />
                            <strong class="fa fa-key" aria-hidden="true"></strong>
                        </div>
					  <tr>
   
               

                        <input type="submit" name="register_me" id="register_me" class="btnsubmit" value="REGISTER" />
                    </form>
                </div>
            </div>	
 
  
</div>
	   </div>
	   
    </body>
</html>