<?php
$obj_login = new login();
if (isset($_COOKIE['preserveKey']) && $_COOKIE['preserveKey'] != '') {
    $preserveSet = $obj_login->getPreserveData($_COOKIE['preserveKey']);
    if (count($preserveSet)) {
        $userData = $obj_login->getUserDetailsById($preserveSet->user_id);
        $_SESSION['user_detail']['user_id'] = $userData['data']->user_id;
        $_SESSION['user_detail']['username'] = $userData['data']->username;
        $_SESSION['user_detail']['email'] = $userData['data']->email;
        $obj_login->redirect(PROJECT_URL . "?page=dashboard");
    }
} else if (isset($_SESSION['user_detail']['user_id']) && intval($_SESSION['user_detail']['user_id']) > 0 && $_SESSION['user_detail']['user_id'] != '') {
    $userData = $obj_login->getUserDetailsById($_SESSION['user_detail']['user_id']);
    $_SESSION['user_detail']['user_id'] = $userData['data']->user_id;
    $_SESSION['user_detail']['username'] = $userData['data']->username;
    $_SESSION['user_detail']['email'] = $userData['data']->email;
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
        <link type="text/css" rel="stylesheet" href="<?php echo THEME_URL; ?>/css/style.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo THEME_URL; ?>/css/font-awesome.min.css" />
        <script src="<?php echo THEME_URL; ?>/js/jquery-3-2.js"></script>
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
                <?php if (isset($_POST['register_me']) && $_POST['register_me'] == 'REGISTER') { ?>
                    $(".userregister").click();
                <?php } ?>
            });
        </script>
    </head>
    <body class="loginpage">
        <div class="loginbx">
            <a class="adminlogo" href="#" target="_blank">
                <img src="image/logo.png" title="GST Keeper" alt="GST Keeper" />
            </a>
            <div class="logincontainer">
                <ul class="admintab">
                    <li class="userlogin active"><a href="javascript:void(0)">User Login</a></li>
                    <li class="userregister"><a href="javascript:void(0)">User Registration</a></li>
                </ul>
                <div class="adminloginbx logincontent">
                <?php if (isset($_POST['login_me']) && $_POST['login_me'] == 'LOGIN') { ?>
                    <?php $obj_login->showErrorMessage(); ?>
                    <?php $obj_login->unsetMessage(); ?>
                <?php } ?>
                    <form id="form-user-login" name="form-user-login" method="POST">
                        <div class="admintxt">
                            <label for="login_username">Username</label>
                            <input type="text" name="login_username" id="login_username" placeholder="Enter Your Username" />
                            <strong class="fa fa-user" aria-hidden="true"></strong>
                        </div>
                        <div class="admintxt">
                            <label for="login_password">Password</label>
                            <input type="password" id="login_password" name="login_password" placeholder="Password" />
                            <strong class="fa fa-key" aria-hidden="true"></strong>
                        </div>
                        <div class="rememberbx">
                            <input type="checkbox" id="login_rememberme" name="login_rememberme" value="1" /> <label for="login_rememberme">Remember Me</label>
                            <a href="#" class="forgetpass">Forget Password</a>
                        </div>
                        <input type="submit" name="login_me" id="login_me" value="LOGIN" />
                    </form>
                </div>
                <div class="adminloginbx registercontent">
                    <?php if (isset($_POST['register_me']) && $_POST['register_me'] == 'REGISTER') { ?>
                        <?php $obj_login->showErrorMessage(); ?>
                        <?php $obj_login->unsetMessage(); ?>
                    <?php } ?>
                    <form id="form-user-register" name="form-user-register" method="post">
                        <div class="admintxt">
                            <label for="username">Username</label>
                            <input id="username" name="username" type="text" placeholder="Enter Your Username" value="<?php echo isset($_POST['username']) ? $obj_login->sanitize($_POST['username']) : ''; ?>" required />
                            <strong class="fa fa-user" aria-hidden="true"></strong>
                        </div>
                        <div class="admintxt">
                            <label for="emailaddress">Mail Id</label>
                            <input id="emailaddress" name="emailaddress" type="text" placeholder="Mail Id" value="<?php echo isset($_POST['emailaddress']) ? $obj_login->sanitize($_POST['emailaddress']) : ''; ?>" required />
                            <strong class="fa fa-envelope" aria-hidden="true"></strong>
                        </div>
                        <div class="admintxt">
                            <label for="password">Password</label>
                            <input id="password" name="password" type="password" placeholder="Password" required />
                            <strong class="fa fa-key" aria-hidden="true"></strong>
                        </div>
                        <div class="admintxt">
                            <label for="confirmpassword">Confirm Password</label>
                            <input id="confirmpassword" name="confirmpassword" type="password" placeholder="Confirm Password" required />
                            <strong class="fa fa-key" aria-hidden="true"></strong>
                        </div>
                        <div class="rememberbx">
                            <input type="checkbox" id="rememberme" name="rememberme" value="1" /> <label for="rememberme">Remember Me</label>
                            <a href="#" class="forgetpass">Forget Password</a>
                        </div>
                        <input type="submit" name="register_me" id="register_me" value="REGISTER" />
                    </form>
                </div>
            </div>
        </div>
        <div class="adminfooter">Copyright @ <?php echo date('Y'); ?>, All rights reserved by GST Keeper</div>
    </body>
</html>