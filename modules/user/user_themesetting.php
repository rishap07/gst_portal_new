<?php
$obj_user = new users();
if (!isset($_SESSION['user_detail']['user_id']) || $_SESSION['user_detail']['user_id'] == '') {
    $obj_user->redirect(PROJECT_URL);
    exit();
}
if (isset($_POST['submit']) && $_POST['submit'] == 'submit') {

    if (!isset($_SERVER['HTTP_REFERER']) || empty($_SERVER['HTTP_REFERER'])) {

        $obj_user->setError('Invalid access to files');
    } else {

        if ($obj_user->saveUserThemeSetting()) {

            $obj_user->redirect(PROJECT_URL . "?page=user_themesetting");
        }
    }
}
$dataThemeSettingArr = array();
$dataThemeSettingArr = $obj_user->getUserThemeSetting($obj_user->sanitize($_SESSION['user_detail']['user_id']));
?>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="col-md-12 col-sm-12 col-xs-12 heading">  <h1>Business Setting</h1></div>
        <hr class="headingborder">
        <div class="clear height10"></div>
        <?php $obj_user->showErrorMessage(); ?>
        <?php $obj_user->showSuccessMessge(); ?>
        <?php $obj_user->unsetMessage(); ?>
        <div class="whitebg formboxcontainer">
            <h2 class="greyheading">Business Setting</h2>
            <div class="clear"></div>
            <form name="add-plan" id="add-plan" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                        <label>Upload Logo</label>
                        <div class="clear"></div>
                        <input type="file" name="theme_logo" id="theme_logo">
                        <div class="clear"></div>
                        <small>(Recommended size w=170 and h=50)</small>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                        <?php if (isset($dataThemeSettingArr['data']->theme_logo) && $dataThemeSettingArr['data']->theme_logo != "") { ?>
                            <img src="<?php echo PROJECT_URL . '/upload/theme-logo/' . $dataThemeSettingArr['data']->theme_logo; ?>">
                            <div class="clear"></div>
                        <?php } ?>
                    </div>
                    <div class="clear"></div>
                    <div class="adminformbxsubmit" style="width:100%;">
                        <div class="tc">
                            <input type='submit' class="btn btn-default btn-success" name='submit' value='submit' id='submit'>
                            <input type="button" value="<?php echo ucfirst('Back'); ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=dashboard"; ?>';" class="btn btn-danger" />
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!--========================sidemenu over=========================-->
<script>

    $(document).ready(function () {

        $('#submit').click(function () {
            var mesg = {};
            if (vali.validate(mesg, 'user-theme-setting')) {
                return true;
            }
            return false;
        });
    });
</script>