<?php
$obj_user = new users();
if( !isset($_SESSION['user_detail']['user_id']) || $_SESSION['user_detail']['user_id'] == '' ) {
    $obj_user->redirect(PROJECT_URL);
    exit();
}

if( isset($_POST['submit']) && $_POST['submit'] == 'submit' ) {

    if(!isset($_SERVER['HTTP_REFERER']) || empty($_SERVER['HTTP_REFERER'])){
        
        $obj_user->setError('Invalid access to files');
    } else {

        if($obj_user->saveUserThemeSetting()){

            $obj_user->redirect(PROJECT_URL."?page=user_themesetting");
        }
    }
}

$dataThemeSettingArr = array();
$dataThemeSettingArr = $obj_user->getUserThemeSetting( $obj_user->sanitize($_SESSION['user_detail']['user_id']) );
?>

<!--========================sidemenu over=========================-->
<div class="admincontainer greybg">
    <div class="formcontainer">
        
        <?php $obj_user->showErrorMessage(); ?>
        <?php $obj_user->showSuccessMessge(); ?>
        <?php $obj_user->unsetMessage(); ?>

        <h1>Update Theme Setting</h1>
        <hr class="headingborder">
        <div class="clear"></div>

        <form name="user-theme-setting" id="user-theme-setting" method="POST" enctype="multipart/form-data">

            <div class="adminformbx">

                <div class="kycform">

                    <div class="kycmainbox">
					
                        <div class="formcol">
                            <label>Upload Logo</label>
                            <div class="clear"></div>

                            <?php if(isset($dataThemeSettingArr['data']->theme_logo) && $dataThemeSettingArr['data']->theme_logo != "") { ?>
                                <img src="<?php echo PROJECT_URL .'/upload/theme-logo/'. $dataThemeSettingArr['data']->theme_logo; ?>">
                                <div class="clear"></div>
                            <?php } ?>

                            <input type="file" name="theme_logo" id="theme_logo">
                            <div class="clear"></div>
                            <small>(Recommended size w=170 and h=50)</small>
                        </div>
                        
                        <div class="formcol two">
                            <label>Choose Theme Style</label>
                            <select name="theme_style" id="theme_style" class="required">
								<option value="theme-color.css" <?php if(isset($_POST['theme_style']) &&  $_POST['theme_style'] === 'theme-color.css'){ echo 'selected="selected"'; } else if(isset($dataThemeSettingArr['data']->theme_style) && $dataThemeSettingArr['data']->theme_style === 'theme-color.css') { echo 'selected="selected"'; } ?>>Default Style</option>
                                <option value="theme-blue.css" <?php if(isset($_POST['theme_style']) &&  $_POST['theme_style'] === 'theme-blue.css'){ echo 'selected="selected"'; } else if(isset($dataThemeSettingArr['data']->theme_style) && $dataThemeSettingArr['data']->theme_style === 'theme-blue.css') { echo 'selected="selected"'; } ?>>Blue Style</option>
                                <option value="theme-red.css" <?php if(isset($_POST['theme_style']) &&  $_POST['theme_style'] === 'theme-red.css'){ echo 'selected="selected"'; } else if(isset($dataThemeSettingArr['data']->theme_style) && $dataThemeSettingArr['data']->theme_style === 'theme-red.css') { echo 'selected="selected"'; } ?>>Red Style</option>
                                <option value="theme-yellow.css" <?php if(isset($_POST['theme_style']) &&  $_POST['theme_style'] === 'theme-yellow.css'){ echo 'selected="selected"'; } else if(isset($dataThemeSettingArr['data']->theme_style) && $dataThemeSettingArr['data']->theme_style === 'theme-yellow.css') { echo 'selected="selected"'; } ?>>Yellow Style</option>
                                <option value="theme-green.css" <?php if(isset($_POST['theme_style']) &&  $_POST['theme_style'] === 'theme-green.css'){ echo 'selected="selected"'; } else if(isset($dataThemeSettingArr['data']->theme_style) && $dataThemeSettingArr['data']->theme_style === 'theme-green.css') { echo 'selected="selected"'; } ?>>Green Style</option>
                                <option value="theme-grey.css" <?php if(isset($_POST['theme_style']) &&  $_POST['theme_style'] === 'theme-grey.css'){ echo 'selected="selected"'; } else if(isset($dataThemeSettingArr['data']->theme_style) &&  $dataThemeSettingArr['data']->theme_style === 'theme-grey.css') { echo 'selected="selected"'; } ?>>Grey Style</option>
                            </select>
                        </div>
                                       
                        <div class="clear"></div>
                        <div class="clear height10"></div>
                        
                        <div class="tc">
                            <input type='submit' class="btn orangebg" name='submit' value='submit' id='submit'>
                            <input type="button" value="<?php echo ucfirst('Back'); ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=dashboard"; ?>';" class="btn redbg" class="redbtn marlef10"/>
                        </div>

                    </div>

                </div>

            </div>

        </form>
<!--========================adminformbox over=========================-->    
    </div>
<!--========================admincontainer over=========================-->
</div>
<script>

    $(document).ready(function () {
		
	$('#submit').click(function () {
            var mesg = {};
            if (vali.validate(mesg,'user-theme-setting')) {
                return true;
            }
            return false;
        });
    });
</script>