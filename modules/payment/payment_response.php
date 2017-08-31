<?php
$obj_pay = new processpayment();
if ($_POST && isset($_POST['ResponseCode'])) {
    $process = $obj_pay->payment_method();
    $obj_pay->redirect(PROJECT_URL . "/?page=payment_response");
    exit();
}
if (!isset($_SESSION['res'])) {
    $obj_pay->redirect(PROJECT_URL);
}
?>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>Payment Response</h1></div>

        <div class="whitebg formboxcontainer">
            <div class="row">
                <?php $obj_pay->showErrorMessage(); ?>
                <?php $obj_pay->showSuccessMessge(); ?>
                <?php $obj_pay->unsetMessage(); ?>
                <?php
                if (isset($_SESSION['res']) && $_SESSION['res'] = '1') {
                    ?>
                    <p class="tc" style="font-weight:normal; font-size:17px;">Click to go on <a href="<?php echo PROJECT_URL . "/?page=dashboard"; ?>">Dashboard</a>.</p>  
                <?php } else if (isset($_SESSION['res']) && $_SESSION['res'] = '2') {
                    ?>
                    <p class="tc" style="font-weight:normal; font-size:17px;">Your Payment is FAILED kindly try again.<a href="<?php echo PROJECT_URL . "/?page=plan_chooseplan"; ?>">Click to go on Plan page.</a></p>  
                <?php
                } else {
                    ?>
                    <p class="tc" style="font-weight:normal; font-size:17px;">Click to go on <a href="<?php echo PROJECT_URL . "/?page=dashboard"; ?>">Dashboard</a>.</p>  
                    <?php
                }
                unset($_SESSION['res']);
                ?>
                <div class="clearfix height25"></div>
            </div>
        </div>
    </div>
</div>
<script>
    (function (i, s, o, g, r, a, m) {
        i['GoogleAnalyticsObject'] = r;
        i[r] = i[r] || function () {
            (i[r].q = i[r].q || []).push(arguments)
        }, i[r].l = 1 * new Date();
        a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
        a.async = 1;
        a.src = g;
        m.parentNode.insertBefore(a, m)
    })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

    ga('create', 'UA-97471132-1', 'auto');
    ga('send', 'pageview');
</script>