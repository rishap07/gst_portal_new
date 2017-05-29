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

        if($obj_user->addAdminUser()){

            $obj_user->redirect(PROJECT_URL."?page=user_adminlist");
        }
    }
}

if( isset($_POST['submit']) && $_POST['submit'] == 'update' && isset($_GET['id']) && $obj_user->validateId($_GET['id']) && isset($_GET['action']) && $_GET['action'] == "editAdmin") {

    if(!isset($_SERVER['HTTP_REFERER']) || empty($_SERVER['HTTP_REFERER'])){
        
        $obj_user->setError('Invalid access to files');
    } else {

        if($obj_user->updateAdminUser()){

            $obj_user->redirect(PROJECT_URL."?page=user_adminlist");
        }
    }
}

$dataArr = array();
if(isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == "editAdmin") {
    $dataArr = $obj_user->getUserDetailsById( $obj_user->sanitize($_GET['id']) );
}
?>

<!--========================sidemenu over=========================-->
<div class="admincontainer greybg">
    <div class="formcontainer">
        
        <?php $obj_user->showErrorMessage(); ?>
        <?php $obj_user->showSuccessMessge(); ?>
        <?php $obj_user->unsetMessage(); ?>

        <h1><?php if(isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == "editAdmin") { echo 'Update'; } else { echo 'Add'; } ?> Admin User</h1>
        <hr class="headingborder">
        <div class="clear"></div>

        <form name="admin-user" id="admin-user" method="POST">

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
                        
                        <?php if(isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == "editAdmin") { ?>
                            
                            <div class="formcol">
                                <label>Username</label>
                                <div class="clear"></div>
                                <div class="username not-allowed"><?php if(isset($dataArr['data']->username)){ echo $dataArr['data']->username; } ?></div>
                            </div>
                        
                        <?php } else { ?>
                        
                            <div class="formcol">
                                <label>Username<span class="starred">*</span></label>
                                <input type="text" name="username" id="username" placeholder="Enter username" class="required" data-bind="content" value="<?php echo isset($_POST['username']) ? $_POST['username'] : ''; ?>" />
                            </div>
                        
                        <?php } ?>
                        
                        <?php if(isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == "editAdmin") { ?>
                            
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
                            <label>No Of Client<span class="starred">*</span></label>
                            <input type="text" name="no_of_client" id="no_of_client" placeholder="Enter no of client" class="required" data-bind="number" value="<?php if(isset($_POST['no_of_client'])){ echo $_POST['no_of_client']; } else if(isset($dataArr['data']->no_of_client)){ echo $dataArr['data']->no_of_client; } ?>" />
                        </div>
                        <div class="clear"></div>

                        <div class="formcol">
                            <label>Status<span class="starred">*</span></label>
                            <div class="clear"></div>
                            <input type="radio" name="user_status" <?php if(isset($_POST['user_status']) &&  $_POST['user_status'] === '1'){ echo 'hecked="checked"'; } else if(isset($dataArr['data']->status) && $dataArr['data']->status === '1') { echo 'checked="checked"'; } ?> value="1" /><span>Active</span> <input type="radio" name="user_status" <?php if(isset($_POST['user_status']) &&  $_POST['user_status'] === '0'){ echo 'hecked="checked"'; } else if(isset($dataArr['data']->status) && $dataArr['data']->status === '0') { echo 'checked="checked"'; } ?> value="0" /><span>Inactive</span>
                        </div>
                        
                        <div class="formcol">
                            <label>Payment Status<span class="starred">*</span></label>
                            <select name="payment_status" id="payment_status">
                                <option value="0" <?php if(isset($_POST['payment_status']) &&  $_POST['payment_status']==='0'){ echo 'selected="selected"'; } else if(isset($dataArr['data']->payment_status) && $dataArr['data']->payment_status === '0'){ echo 'selected';}?>>Pending</option>
                                <option value="1" <?php if(isset($_POST['payment_status']) &&  $_POST['payment_status']==='1'){ echo 'selected="selected"'; } else if(isset($dataArr['data']->payment_status) && $dataArr['data']->payment_status === '1'){ echo 'selected';}?>>Success</option>
                                <option value="2" <?php if(isset($_POST['payment_status']) &&  $_POST['payment_status']==='2'){ echo 'selected="selected"'; } else if(isset($dataArr['data']->payment_status) && $dataArr['data']->payment_status === '2'){ echo 'selected';}?>>Mark As Fraud</option>
                                <option value="3" <?php if(isset($_POST['payment_status']) &&  $_POST['payment_status']==='3'){ echo 'selected="selected"'; } else if(isset($dataArr['data']->payment_status) && $dataArr['data']->payment_status === '3'){ echo 'selected';}?>>Rejected</option>
                                <option value="4" <?php if(isset($_POST['payment_status']) &&  $_POST['payment_status']==='4'){ echo 'selected="selected"'; } else if(isset($dataArr['data']->payment_status) && $dataArr['data']->payment_status === '4'){ echo 'selected';}?>>Refund</option>
                            </select>
                        </div>
                        <div class="clear"></div>

                        <div class="clear height10"></div>
                        
                        <div class="tc">
                            <input type='submit' class="btn orangebg" name='submit' value='<?php if(isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == "editAdmin") { echo 'update'; } else { echo 'submit'; } ?>' id='submit'>
                            <input type="button" value="<?php echo ucfirst('Back'); ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=user_adminlist"; ?>';" class="btn redbg" class="redbtn marlef10"/>
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
            if (vali.validate(mesg,'admin-user')) {
                return true;
            }
            return false;
        });
    });
</script>