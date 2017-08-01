<?php
$obj_client = new client();
$obj_plan = new plan();
if( !isset($_SESSION['user_detail']['user_id']) || $_SESSION['user_detail']['user_id'] == '' ) {
    $obj_client->redirect(PROJECT_URL);
    exit();
}

if(!$obj_client->can_read('client_list')) {

    $obj_client->setError($obj_client->getValMsg('can_read'));
    $obj_client->redirect(PROJECT_URL."/?page=dashboard");
    exit();
}

/* get current user data */
$dataCurrentArr = array();
$dataCurrentArr = $obj_client->getUserDetailsById($obj_client->sanitize($_SESSION['user_detail']['user_id']));

if( isset($_POST['submit']) && $_POST['submit'] == 'submit' ) {
	
	if(!$obj_client->can_create('client_list')) {
        $obj_client->setError($obj_client->getValMsg('can_create'));
        $obj_client->redirect(PROJECT_URL."/?page=user_adminlist");
        exit();
    }

	$subscribePlanDetail = $obj_plan->getPlanDetails($dataCurrentArr['data']->plan_id);
	$totalClientCreated = $obj_client->getClient("count(user_id) as totalClientCreated", "added_by=".$obj_client->sanitize($_SESSION['user_detail']['user_id']));
	if($totalClientCreated[0]->totalClientCreated >= intval($subscribePlanDetail['data']->no_of_client)) {

		$obj_client->setError('You have reach maximum client creation limit.');
		$obj_client->redirect(PROJECT_URL."?page=client_list");
	}

	if(!isset($_SERVER['HTTP_REFERER']) || empty($_SERVER['HTTP_REFERER'])){

        $obj_client->setError('Invalid access to files');
    } else {

        if($obj_client->addClientUser()){

            $obj_client->redirect(PROJECT_URL."?page=client_list");
        }
    }
}

if( isset($_POST['submit']) && $_POST['submit'] == 'update' && isset($_GET['id']) && $obj_client->validateId($_GET['id']) && isset($_GET['action']) && $_GET['action'] == "editClient") {
	
	if(!$obj_client->can_update('client_list')) {
        $obj_client->setError($obj_client->getValMsg('can_update'));
        $obj_client->redirect(PROJECT_URL."/?page=user_adminlist");
        exit();
    }

    if(!isset($_SERVER['HTTP_REFERER']) || empty($_SERVER['HTTP_REFERER'])){
        
        $obj_client->setError('Invalid access to files');
    } else {

        if($obj_client->updateClientUser()){

            $obj_client->redirect(PROJECT_URL."?page=client_list");
        }
    }
}

$dataArr = array();
if(isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == "editClient") {
    $dataArr = $obj_client->getUserDetailsById( $obj_client->sanitize($_GET['id']) );
}
?>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>Business User</h1></div>
			<div class="clear"></div>
            <?php $obj_client->showErrorMessage(); ?>
			<?php $obj_client->showSuccessMessge(); ?>
			<?php $obj_client->unsetMessage(); ?>

         
        <div class="clear"></div>
			<div class="whitebg formboxcontainer">
        <form name="client-user" id="client-user" method="POST">
            <h2 class="greyheading"><?php if(isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == "Edit ") { echo 'Update'; } else { echo 'Add '; } ?>Business User</h2>
       
                <div class="row">
                     
				                
                   	 <div class="col-md-4 col-sm-4 col-xs-12 form-group">

                            <label>First Name<span class="starred">*</span></label>
                            <input type="text" name="first_name" id="first_name" placeholder="Enter first name" class="required form-control" data-bind="content" value="<?php if(isset($_POST['first_name'])){ echo $_POST['first_name']; } else if(isset($dataArr['data']->first_name)){ echo $dataArr['data']->first_name; } ?>" />
                        </div>
                      <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                           
                            <label>Last Name<span class="starred">*</span></label>
                            <input type="text" name="last_name" id="last_name" placeholder="Enter last name" class="required form-control" data-bind="content" value="<?php if(isset($_POST['last_name'])){ echo $_POST['last_name']; } else if(isset($dataArr['data']->last_name)){ echo $dataArr['data']->last_name; } ?>" />
                        </div>

                             <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                      
                         
                            <label>Company Name<span class="starred">*</span></label>
                            <input type="text" name="company_name" id="company_name" placeholder="Enter company name" class="required form-control" data-bind="content" value="<?php if(isset($_POST['company_name'])){ echo $_POST['company_name']; } else if(isset($dataArr['data']->company_name)){ echo $dataArr['data']->company_name; } ?>" />
                        </div>
						<div class="clear"></div>
						
					   <?php if(isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == "editClient") { ?>
                            
                            <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                                <label>Username</label>
                                <div class="clear"></div>
                                <div class="username not-allowed"><?php if(isset($dataArr['data']->username)){ echo $dataArr['data']->username; } ?></div>
                            </div>
                        
                        <?php } else { ?>
                        
                          <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                                <label>Username<span class="starred">*</span></label>
                                <div style="clear: both;">
                                <?php echo $dataCurrentArr['data']->subscriber_code; ?>_<input type="text" name="username" id="username" placeholder="Enter username" class="required form-control" data-bind="content" value="<?php echo isset($_POST['username']) ? $_POST['username'] : ''; ?>" style="width:auto" />
                                </div>
                            </div>
                        
                        <?php } ?>
                            <?php if(isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == "editClient") { ?>
                            
                            <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                                <label>Password</label>
                                <input type="password" name="password" id="password" autocomplete="off" class="form-control" placeholder="Enter password" data-bind="content" />
                            </div>
                        
                        <?php } else { ?>
                        
                            <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                                <label>Password<span class="starred">*</span></label>
                                <input type="password" name="password" id="password" autocomplete="off" placeholder="Enter password" class="required form-control" data-bind="content" />
                            </div>
                        
                        <?php } ?>
                 	  <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                          
                            <label>Email Address<span class="starred">*</span></label>
                            <input type="text" name="emailaddress" id="emailaddress" placeholder="Enter email address" class="form-control" data-bind="email" value="<?php if(isset($_POST['emailaddress'])){ echo $_POST['emailaddress']; } else if(isset($dataArr['data']->email)){ echo $dataArr['data']->email; } ?>" />
                        </div>    
						<div class="clear"></div>

						      <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                                                        
                            <label>Phone Number<span class="starred">*</span></label>
                            <input type="text" name="phonenumber" id="phonenumber" placeholder="Enter phone number" class="required form-control" data-bind="mobilenumber" value="<?php if(isset($_POST['phonenumber'])){ echo $_POST['phonenumber']; } else if(isset($dataArr['data']->phone_number)){ echo $dataArr['data']->phone_number; } ?>" />
                        </div>
						 
                       <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                       
                            <label>Status<span class="starred">*</span></label>
                            <div class="clear"></div>
                            
					   <select name='user_status' id='user_status' class='required form-control'>
                        
						   <option value='1' <?php
                                    if (isset($dataArr['data']->status) && $dataArr['data']->status == 1) {
                                        echo "selected='selected'";
                                    }
                                    ?>>Active</option>
									 <option value='0' <?php
                                    if (isset($dataArr['data']->status) && $dataArr['data']->status == 0) {
                                        echo "selected='selected'";
                                    }
                                    ?>>Inactive</option>
							
                        </select>
                        </div>
              <div class="clear"></div>
						 <div class="adminformbxsubmit" style="width:100%;">
                             
						 
						 
						   
						<div class="tc">
                            <input type='submit' class="btn btn-danger" name='submit' value='<?php if(isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == "editClient") { echo 'update'; } else { echo 'submit'; } ?>' id='submit'>
                            <input type="button" value="<?php echo ucfirst('Back'); ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=client_list"; ?>';" class="btn btn-danger"/>
                        </div>
						

                        </div>
							
                        </div>
                
 </div>
</form>
    </div>
                </div>
      
                       
<!--========================sidemenu over=========================-->

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