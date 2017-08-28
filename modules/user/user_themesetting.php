<?php
$obj_user = new users();

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
$dataCurrentUserArr = $obj_user->getUserDetailsById( $obj_user->sanitize($_SESSION['user_detail']['user_id']) );
?>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">

		<div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>Business Setting</h1></div>
		<hr class="headingborder">
		<div class="clear height10"></div>
		<?php $obj_user->showErrorMessage(); ?>
		<?php $obj_user->showSuccessMessge(); ?>
		<?php $obj_user->unsetMessage(); ?>

		<div class="whitebg formboxcontainer">
            <h2 class="greyheading">Business Setting</h2>
            <div class="clear"></div>
            <form name="user-theme-setting" id="user-theme-setting" method="POST" enctype="multipart/form-data">
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
                    <div class="clear height10"></div>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Gross Turnover in the preceding Financial Year <span class="starred">*</span></label>
						<input type="text" placeholder="Gross Turnover" name="gross_turnover" id="gross_turnover" class="form-control required" data-bind="decimal" value="<?php if (isset($_POST['gross_turnover'])) { echo $_POST['gross_turnover']; } else if (isset($dataCurrentUserArr['data']->kyc->gross_turnover)) { echo $dataCurrentUserArr['data']->kyc->gross_turnover; } ?>">
					</div>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Gross Turnover - April to June, 2017 <span class="starred">*</span></label>
						<input type="text" placeholder="Cur Gross Turnover" name="cur_gross_turnover" id="cur_gross_turnover" class="form-control required" data-bind="decimal" value="<?php if (isset($_POST['cur_gross_turnover'])) { echo $_POST['cur_gross_turnover']; } else if (isset($dataCurrentUserArr['data']->kyc->cur_gross_turnover)) { echo $dataCurrentUserArr['data']->kyc->cur_gross_turnover; } ?>">
					</div>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>ISD Number</label>
						<input type="text" name="isd_number" class="form-control" id="isd_number" placeholder="Enter ISD Number" data-bind="content" value="<?php if(isset($_POST['isd_number'])) { echo $_POST['isd_number']; } else if(isset($dataCurrentUserArr['data']->kyc->isd_number)) { echo $dataCurrentUserArr['data']->kyc->isd_number; } ?>" />
					</div>
					<div class="clear"></div>
					
					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>GSTIN Username <span class="starred">*</span></label>
						<input type="text" name="gstin_username" class="form-control required" id="gstin_username" placeholder="Enter GSTIN Username" data-bind="content" value="<?php if(isset($_POST['gstin_username'])) { echo $_POST['gstin_username']; } else if(isset($dataCurrentUserArr['data']->kyc->gstin_username)) { echo $dataCurrentUserArr['data']->kyc->gstin_username; } ?>" />
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