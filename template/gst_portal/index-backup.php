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
    </head>
    <body class="loginpage">
        <div class="loginleftcontent">
            <h1>A Complete GST  Soultions with <span>GST KEEPER</span></h1>
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
                    <li class="userlogin active"><a href="javascript:void(0)">SIGN IN</a></li>
                    <li class="userregister"><a href="javascript:void(0)"> Register</a></li>
                </ul>
                <div class="adminloginbx logincontent">
                    <p>Plear enter your Username and Password</p>
                    <?php if (isset($_POST['login_me']) && $_POST['login_me'] == 'LOGIN') { ?>
                        <?php $obj_login->showErrorMessage(); ?>
                        <?php $obj_login->unsetMessage(); ?>
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
                        </div>
                        <input type="submit" name="login_me" id="login_me" value="LOGIN" />
                    </form>
                </div>
                <div class="adminloginbx registercontent">
                    <?php if (isset($_POST['login_me']) && $_POST['login_me'] == 'LOGIN') { ?>
                        <?php $obj_login->showErrorMessage(); ?>
                        <?php $obj_login->unsetMessage(); ?>
                    <?php } ?>
                    <form id="form-user-register" name="form-user-register" method="post">
                        <div class="admintxt">
                            <input id="username" name="username" type="text" placeholder="Enter Your Username" value="<?php echo isset($_POST['username']) ? $obj_login->sanitize($_POST['username']) : ''; ?>" required />
                            <strong class="fa fa-user" aria-hidden="true"></strong>
                        </div>
                        <div class="admintxt">
                            <input id="emailaddress" name="emailaddress" type="text" placeholder="Mail Id" value="<?php echo isset($_POST['emailaddress']) ? $obj_login->sanitize($_POST['emailaddress']) : ''; ?>" required />
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
                        <div class="rememberbx">
                            <input type="checkbox" id="rememberme" name="rememberme" value="1" /> <label for="rememberme">Remember Me</label>
                            <a href="" class="forgetpass">Forget Password</a>
                        </div>

                        <input type="submit" name="register_me" id="register_me" value="REGISTER" />
                    </form>
                </div>
            </div>	
        </div>
    </body>
</html>