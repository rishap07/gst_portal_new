<?php
$obj_login = new login();
if(isset($_POST['plan']) && $_POST['plan']!=''){
	$_SESSION['plan_id'] = $_POST['plan'];
}

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
?>

<!DOCTYPE html>
<html lang="En">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
        <title>Login | GST Online Portal </title>
        <link type="text/css" rel="stylesheet" href="<?php echo THEME_URL; ?>/css/style.css?3" />
        <link type="text/css" rel="stylesheet" href="<?php echo THEME_URL; ?>/css/font-awesome.min.css?3" />
        <script src="<?php echo THEME_URL; ?>/js/jquery-3-2.js?3"></script>
		<script src="<?php echo PROJECT_URL; ?>/script/validation.js?3"></script>

        <script>
            $(document).ready(function () {

				$(".userlogin").click(function (e) {
                    $(".admintab li").removeClass("active");
                    $(this).addClass("active");
                    $(".logincontent").show();
                    $(".registercontent").hide();
                });

				$(".userregister").click(function (e) {
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
            <a class="adminlogo" href="" target="_blank"><img src="<?php echo PROJECT_URL;?>/image/logo.png" title="GST Keeper" alt="GST Keeper" /></a>
			<div class="logincontainer">
				<ul class="admintab">
					<li class="userlogin <?php if(isset($_POST['login_me']) && $_POST['login_me'] == 'LOGIN') { echo "active"; } if(!isset($_POST['login_me']) && $_POST['login_me'] != 'LOGIN' && !isset($_POST['register_me']) && $_POST['register_me'] != 'REGISTER'){ echo 'active'; } ?>">
						<a href="javascript:void(0)">SIGN IN</a>
					</li>
					<li class="userregister <?php if(isset($_POST['register_me']) && $_POST['register_me'] == 'REGISTER') { echo "active"; } ?>">
						<a href="javascript:void(0)">Register</a>
					</li>
				</ul>

				<div class="adminloginbx logincontent" style="<?php if(isset($_POST['login_me']) && $_POST['login_me'] == 'LOGIN') { echo "display:block"; } else if(isset($_POST['register_me']) && $_POST['register_me'] == 'REGISTER') { echo "display:none"; } ?>">
					<p>Enter your Username and Password</p>
					<div class="clear" ></div>
					
					<?php if (isset($_POST['login_me']) && $_POST['login_me'] == 'LOGIN') { ?>
					
						<?php $obj_login->showErrorMessage(); ?>
					<?php $obj_login->unsetMessage(); ?>
						<?php $obj_login->showSuccessMessge(); ?>
					<?php } ?>
					<form id="form-user-login" name="form-user-login" method="POST">

						<div class="admintxt">	
							<input type="text" name="login_username" id="login_username" class="required" data-bind="content" placeholder="Username" />
							<strong class="fa fa-user" aria-hidden="true"></strong>
						</div>
					
						<div class="admintxt">
							<input type="password" id="login_password" name="login_password" autocomplete="off" class="required" placeholder="Password" />
							<strong class="fa fa-key" aria-hidden="true"></strong>
						</div>
					
						<div class="rememberbx">
							<input type="checkbox" id="login_rememberme" name="login_rememberme" value="1" /> <label for="login_rememberme">Remember Me</label>
							<a href="<?php echo PROJECT_URL; ?>/forgot.php" class="forgetpass">Forgot Password</a>
						</div>
						<input type="submit" name="login_me" class="btnsubmit" id="login_me" value="LOGIN" />
					</form>
				</div>

				<div class="registercontent" style="<?php if (isset($_POST['register_me']) && $_POST['register_me'] == 'REGISTER') { echo "display:block"; } else if (isset($_POST['login_me']) && $_POST['login_me'] == 'LOGIN') { echo "display:none"; } ?>">

					<div class="clear"></div>
					<?php if (isset($_POST['register_me']) && $_POST['register_me'] == 'REGISTER') { ?>
						<?php $obj_login->showErrorMessage(); ?>
						<?php $obj_login->unsetMessage(); ?>
					<?php } ?>
					<form id="form-user-register" name="form-user-register" method="post">

						<div class="admintxt">
							<input id="firstname" name="firstname" type="text" placeholder="First Name" autocomplete="off" value="<?php echo isset($_POST['firstname']) ? $obj_login->sanitize($_POST['firstname']) : ''; ?>" data-bind="content" class="required" />
							<strong class="fa fa-user" aria-hidden="true"></strong>
						</div>

						<div class="admintxt">
							<input id="lastname" name="lastname" type="text" placeholder="Last Name" autocomplete="off" value="<?php echo isset($_POST['lastname']) ? $obj_login->sanitize($_POST['lastname']) : ''; ?>" data-bind="content" class="required" />
							<strong class="fa fa-user" aria-hidden="true"></strong>
						</div>

						<div class="admintxt">
							<input id="companyname" name="companyname" type="text" placeholder="Company Name" autocomplete="off" value="<?php echo isset($_POST['companyname']) ? $obj_login->sanitize($_POST['companyname']) : ''; ?>" data-bind="content" />
							<strong class="fa fa-building" aria-hidden="true"></strong>
						</div>

						<div class="admintxt">
							<input id="username" name="username" type="text" autocomplete="off" placeholder="Enter Username" value="<?php echo isset($_POST['username']) ? $obj_login->sanitize($_POST['username']) : ''; ?>" data-bind="content" class="required" />
							<strong class="fa fa-user" aria-hidden="true"></strong>
						</div>

						<div class="admintxt">
							<input id="mobilenumber" type="text" name='mobilenumber' autocomplete="off" placeholder="Phone Number" value="<?php echo isset($_POST['mobilenumber']) ? $obj_login->sanitize($_POST['mobilenumber']) : ''; ?>" data-bind="mobilenumber" class="required" />
							<strong class="fa fa-phone" aria-hidden="true"></strong>
						</div>

						<div class="admintxt">
							<input id="emailaddress" name="emailaddress" autocomplete="off" type="text" placeholder="Email Address" value="<?php echo isset($_POST['emailaddress']) ? $obj_login->sanitize($_POST['emailaddress']) : ''; ?>" data-bind="email" class="required" />
							<strong class="fa fa-envelope" aria-hidden="true"></strong>
						</div>

						<div class="admintxt">
							<input id="password" name="password" type="password" placeholder="Password" class="required" />
							<strong class="fa fa-key" aria-hidden="true"></strong>
						</div>

						<div class="admintxt">
							<input id="confirmpassword" name="confirmpassword" type="password" placeholder="Confirm Password" class="required" />
							<strong class="fa fa-key" aria-hidden="true"></strong>
						</div>

						<div class="admintxt">
							<input id="city" name="city" type="text" placeholder="City" class="required" />
							<strong class="fa fa-location-arrow" aria-hidden="true"></strong>
						</div>

						<div class="admintxt">
							<select name='state' id='state' class='required'>
								<?php $dataBStateArrs = $obj_login->get_results("select * from ".$obj_login->getTableName('state')." where status='1' and is_deleted='0' order by state_name asc"); ?>
								<?php if(!empty($dataBStateArrs)) { ?>
									<option value=''>Select State</option>
									<?php foreach($dataBStateArrs as $dataBStateArr) { ?>
										<option value='<?php echo $dataBStateArr->state_id; ?>'><?php echo $dataBStateArr->state_name; ?></option>
									<?php } ?>
								<?php } ?>
							</select>
							<strong class="fa fa-location-arrow" aria-hidden="true"></strong>
						</div>

						<div class="admintxt">
							<input id="gstin_number" name="gstin_number" type="text" data-bind="gstin" placeholder="Enter GSTIN" value="<?php echo isset($_POST['gstin_number']) ? $obj_login->sanitize($_POST['gstin_number']) : ''; ?>" />
							<strong class="fa fa-key" aria-hidden="true"></strong>
						</div>
						
						<div class="admintxt">
							<input id="coupon" name="coupon" type="text" data-bind="content" placeholder="Coupon Code" />
							<strong class="fa fa-key" aria-hidden="true"></strong>
						</div>
						
						<div class="rememberbx">
							<input type="checkbox" id="rememberme" name="rememberme" value="1" /> <label for="rememberme">Remember Me</label>
							<a href="<?php echo PROJECT_URL; ?>/forgot.php" class="forgetpass">Forgot Password</a>
						</div>
						<input type="submit" name="register_me" id="register_me" class="btnsubmit" value="REGISTER" />
					</form>
				</div>
			</div>
		</div>
		<script>
			$(document).ready(function () {

				$('#register_me').click(function () {
					var mesg = {};
					if (vali.validate(mesg, 'form-user-register')) {
						return true;
					}
					return false;
				});

				$('#login_me').click(function () {
					var mesg = {};
					if (vali.validate(mesg, 'form-user-login')) {
						return true;
					}
					return false;
				});
			});
		</script>
	</body>
</html>