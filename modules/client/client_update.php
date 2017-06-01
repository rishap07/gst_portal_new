<?php
$obj_client = new client();
if( !isset($_SESSION['user_detail']['user_id']) || $_SESSION['user_detail']['user_id'] == '' ) {
    $obj_client->redirect(PROJECT_URL);
    exit();
}

if( isset($_POST['submit']) && $_POST['submit'] == 'submit' ) {

    if(!isset($_SERVER['HTTP_REFERER']) || empty($_SERVER['HTTP_REFERER'])){
        
        $obj_client->setError('Invalid access to files');
    } else {

        if($obj_client->addClientUser()){

            $obj_client->redirect(PROJECT_URL."?page=client_list");
        }
    }
}

if( isset($_POST['submit']) && $_POST['submit'] == 'update' && isset($_GET['id']) && $obj_client->validateId($_GET['id']) && isset($_GET['action']) && $_GET['action'] == "editClient") {

    if(!isset($_SERVER['HTTP_REFERER']) || empty($_SERVER['HTTP_REFERER'])){
        
        $obj_client->setError('Invalid access to files');
    } else {

        if($obj_client->updateClientUser()){

            $obj_client->redirect(PROJECT_URL."?page=client_list");
        }
    }
}

$dataCurrentArr = array();
$dataCurrentArr = $obj_client->getUserDetailsById( $obj_client->sanitize($_SESSION['user_detail']['user_id']) );

$dataArr = array();
if(isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == "editClient") {
    $dataArr = $obj_client->getUserDetailsById( $obj_client->sanitize($_GET['id']) );
}
?>

<!--========================sidemenu over=========================-->
<div class="admincontainer greybg">
    <div class="formcontainer">
        
        <?php $obj_client->showErrorMessage(); ?>
        <?php $obj_client->showSuccessMessge(); ?>
        <?php $obj_client->unsetMessage(); ?>

        <h1><?php if(isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == "editClient") { echo 'Update'; } else { echo 'Add'; } ?> Client User</h1>
        <hr class="headingborder">
        <div class="clear"></div>

        <form name="client-user" id="client-user" method="POST">

            <div class="adminformbx">

                <div class="kycform">

                    <div class="kycmainbox">
                        
                        <div class="formcol">
                            <label>First Name<span class="starred">*</span></label>
                            <input type="text" name="first_name" id="first_name" placeholder="Enter first name" class="required" data-bind="content" value="<?php if(isset($_POST['first_name'])){ echo $_POST['first_name']; } else if(isset($dataArr['data']->first_name)){ echo $dataArr['data']->first_name; } ?>" />
                        </div>
                        
                        <div class="formcol two">
                            <label>Last Name<span class="starred">*</span></label>
                            <input type="text" name="last_name" id="last_name" placeholder="Enter last name" class="required" data-bind="content" value="<?php if(isset($_POST['last_name'])){ echo $_POST['last_name']; } else if(isset($dataArr['data']->last_name)){ echo $dataArr['data']->last_name; } ?>" />
                        </div>
                        
                        <div class="formcol third">
                            <label>Company Name<span class="starred">*</span></label>
                            <input type="text" name="company_name" id="company_name" placeholder="Enter company name" class="required" data-bind="content" value="<?php if(isset($_POST['company_name'])){ echo $_POST['company_name']; } else if(isset($dataArr['data']->company_name)){ echo $dataArr['data']->company_name; } ?>" />
                        </div>
                        
                        <div class="clear"></div>
                        
                        <?php if(isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == "editClient") { ?>
                            
                            <div class="formcol">
                                <label>Username</label>
                                <div class="clear"></div>
                                <div class="username not-allowed"><?php if(isset($dataArr['data']->username)){ echo $dataArr['data']->username; } ?></div>
                            </div>
                        
                        <?php } else { ?>
                        
                            <div class="formcol">
                                <label>Username<span class="starred">*</span></label>
                                <div style="clear: both;">
                                <?php echo $dataCurrentArr['data']->subscriber_code; ?>_<input type="text" name="username" id="username" placeholder="Enter username" class="required" data-bind="content" value="<?php echo isset($_POST['username']) ? $_POST['username'] : ''; ?>" style="width:auto" />
                                </div>
                            </div>
                        
                        <?php } ?>
                        
                        <?php if(isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == "editClient") { ?>
                            
                            <div class="formcol two">
                                <label>Password</label>
                                <input type="password" name="password" id="password" placeholder="Enter password" data-bind="content" />
                            </div>
                        
                        <?php } else { ?>
                        
                            <div class="formcol two">
                                <label>Password<span class="starred">*</span></label>
                                <input type="password" name="password" id="password" placeholder="Enter password" class="required" data-bind="content" />
                            </div>
                        
                        <?php } ?>

                        <div class="formcol third">
                            <label>Email Address<span class="starred">*</span></label>
                            <input type="text" name="emailaddress" id="emailaddress" placeholder="Enter email address" class="required" data-bind="email" value="<?php if(isset($_POST['emailaddress'])){ echo $_POST['emailaddress']; } else if(isset($dataArr['data']->email)){ echo $dataArr['data']->email; } ?>" />
                        </div>                        
                        <div class="clear"></div>
                        
                        <div class="formcol">
                            <label>Phone Number<span class="starred">*</span></label>
                            <input type="text" name="phonenumber" id="phonenumber" placeholder="Enter phone number" class="required" data-bind="mobilenumber" value="<?php if(isset($_POST['phonenumber'])){ echo $_POST['phonenumber']; } else if(isset($dataArr['data']->phone_number)){ echo $dataArr['data']->phone_number; } ?>" />
                        </div>
                        
                        <div class="formcol two">
                            <label>Company Code<span class="starred">*</span></label>
                            <input type="text" name="company_code" id="company_code" placeholder="Enter company code" class="required" data-bind="alphanum" value="<?php if(isset($_POST['company_code'])){ echo $_POST['company_code']; } else if(isset($dataArr['data']->company_code)){ echo $dataArr['data']->company_code; } ?>" />
                        </div>
                        
                        <div class="formcol third">
                            <label>Status<span class="starred">*</span></label>
                            <div class="clear"></div>
                            <input type="radio" name="user_status" <?php if(isset($_POST['user_status']) &&  $_POST['user_status'] === '1'){ echo 'checked="checked"'; } else if(isset($dataArr['data']->status) && $dataArr['data']->status === '1') { echo 'checked="checked"'; } ?> value="1" /><span>Active</span> <input type="radio" name="user_status" <?php if(isset($_POST['user_status']) &&  $_POST['user_status'] === '0'){ echo 'checked="checked"'; } else if(isset($dataArr['data']->status) && $dataArr['data']->status === '0') { echo 'checked="checked"'; } ?> value="0" /><span>Inactive</span>
                        </div>
                        <div class="clear"></div>

                        <div class="clear height10"></div>
                        
                        <div class="tc">
                            <input type='submit' class="btn orangebg" name='submit' value='<?php if(isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == "editClient") { echo 'update'; } else { echo 'submit'; } ?>' id='submit'>
                            <input type="button" value="<?php echo ucfirst('Back'); ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=client_list"; ?>';" class="btn redbg" class="redbtn marlef10"/>
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
            if (vali.validate(mesg,'client-user')) {
                return true;
            }
            return false;
        });
    });
</script>