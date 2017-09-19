<?php
$obj_user = new users();

if (!$obj_user->can_read('client_kyc')) {
	$obj_user->setError($obj_user->getValMsg('can_read'));
	$obj_user->redirect(PROJECT_URL . "/?page=dashboard");
	exit();
}

if (!$obj_user->can_create('client_kyc')) {
	$obj_user->setError($obj_user->getValMsg('can_create'));
	$obj_user->redirect(PROJECT_URL . "/?page=dashboard");
	exit();
}

if (!$obj_user->can_update('client_kyc')) {
	$obj_user->setError($obj_user->getValMsg('can_update'));
	$obj_user->redirect(PROJECT_URL . "/?page=dashboard");
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
$dataCurrentUserArr = $obj_user->getUserDetailsById( $obj_user->sanitize($_SESSION['user_detail']['user_id']) );
?>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">

		<div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>Company Setting</h1></div>
		<hr class="headingborder">
		<div class="clear height10"></div>
		<?php $obj_user->showErrorMessage(); ?>
		<?php $obj_user->showSuccessMessge(); ?>
		<?php $obj_user->unsetMessage(); ?>

		<div class="whitebg formboxcontainer">
            <form name="user-theme-setting" id="user-theme-setting" method="POST" enctype="multipart/form-data">

				<div class="row">
					<h2 class="greyheading">Business Setting</h2>
					<div class="clear"></div>

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

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Show Logo</label>
						<div class="onoffswitch">
							<input type="checkbox" value="1" name="show_logo" id="show_logo" class="onoffswitch-checkbox" <?php if(isset($dataThemeSettingArr['data']->show_logo) && $dataThemeSettingArr['data']->show_logo == '1') { echo 'checked="checked"'; } ?>>
							<label class="onoffswitch-label" for="show_logo">
								<span class="onoffswitch-inner"></span>
								<span class="onoffswitch-switch"></span>
							</label>
						</div>
					</div>
                    <div class="clear height10"></div>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
                        <label>Upload Signature</label>
                        <div class="clear"></div>
                        <input type="file" name="theme_signature" id="theme_signature">
                        <div class="clear"></div>
                        <small>(Max Width=300 and Max Height=50)</small>
                    </div>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
                        <?php if (isset($dataThemeSettingArr['data']->theme_signature) && $dataThemeSettingArr['data']->theme_signature != "") { ?>
                            <img src="<?php echo PROJECT_URL . '/upload/theme-signature/' . $dataThemeSettingArr['data']->theme_signature; ?>">
                            <div class="clear"></div>
                        <?php } ?>
                    </div>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Show Signature</label>
						<div class="onoffswitch">
							<input type="checkbox" value="1" name="show_signature" id="show_signature" class="onoffswitch-checkbox" <?php if(isset($dataThemeSettingArr['data']->show_signature) && $dataThemeSettingArr['data']->show_signature == '1') { echo 'checked="checked"'; } ?>>
							<label class="onoffswitch-label" for="show_signature">
								<span class="onoffswitch-inner"></span>
								<span class="onoffswitch-switch"></span>
							</label>
						</div>
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
					
					<h2 class="greyheading">Bank Details</h2>
					<div class="clear"></div>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Bank Name</label>
						<input type="text" name="bank_name" class="form-control" id="bank_name" placeholder="Enter Bank Name" data-bind="content" value="<?php if(isset($_POST['bank_name'])) { echo $_POST['bank_name']; } else if(isset($dataCurrentUserArr['data']->kyc->bank_name)) { echo $dataCurrentUserArr['data']->kyc->bank_name; } ?>" />
					</div>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Account Number</label>
						<input type="text" name="account_number" class="form-control" id="account_number" placeholder="Enter Account Number" data-bind="content" value="<?php if(isset($_POST['account_number'])) { echo $_POST['account_number']; } else if(isset($dataCurrentUserArr['data']->kyc->account_number)) { echo $dataCurrentUserArr['data']->kyc->account_number; } ?>" />
					</div>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Branch Name</label>
						<input type="text" name="branch_name" class="form-control" id="branch_name" placeholder="Enter Branch Name" data-bind="content" value="<?php if(isset($_POST['branch_name'])) { echo $_POST['branch_name']; } else if(isset($dataCurrentUserArr['data']->kyc->branch_name)) { echo $dataCurrentUserArr['data']->kyc->branch_name; } ?>" />
					</div>
					<div class="clear"></div>
					
					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>IFSC Code</label>
						<input type="text" name="ifsc_code" class="form-control" id="ifsc_code" placeholder="Enter IFSC Code" data-bind="content" value="<?php if(isset($_POST['ifsc_code'])) { echo $_POST['ifsc_code']; } else if(isset($dataCurrentUserArr['data']->kyc->ifsc_code)) { echo $dataCurrentUserArr['data']->kyc->ifsc_code; } ?>" />
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