<?php
$obj_client = new client();
$obj_sub = new subscriber();
if( !isset($_SESSION['user_detail']['user_id']) || $_SESSION['user_detail']['user_id'] == '' ) {
    $obj_client->redirect(PROJECT_URL);
    exit();
}

if(!$obj_client->can_read('subscriber_update')) {

    $obj_client->setError($obj_client->getValMsg('can_read'));
    $obj_client->redirect(PROJECT_URL."/?page=dashboard");
    exit();
}

/* get current user data */
$dataCurrentArr = array();
 $sql="select * from gst_user WHERE (user_group='3' or user_group='4' or user_group='5') and user_id='".$_SESSION["user_detail"]["user_id"]."'";
$dataCurrentArr = $db_obj->get_results($sql);
 
if( isset($_POST['submit']) && $_POST['submit'] == 'submit' ) {
	
	if(!$obj_client->can_create('subscriber_update')) {
        $obj_client->setError($obj_client->getValMsg('can_create'));
        $obj_client->redirect(PROJECT_URL."/?page=dashboard");
        exit();
    }
	if($obj_sub->updateSubsriber())
	{
		$obj_client->redirect(PROJECT_URL."/?page=subscriber_update");
        exit();
	}
	
}

    if(isset($_GET["id"]) && ($obj_client->sanitize($_GET["id"])!=''))
	{
	  $sql="select * from ".TAB_PREFIX."user WHERE (user_group='3' or user_group='4' or user_group='5') and user_id='".$obj_client->sanitize($_GET["id"])."'";
	  $dataCurrentArr = $obj_sub->get_results($sql);	
	
      if($dataCurrentArr[0]->added_by==$_SESSION["user_detail"]["user_id"])
	  {		  
	     
	  }else{
		$obj_client->setError('You are not authorize to view this user profile.');
        $obj_client->redirect(PROJECT_URL . "?page=dashboard");
	  }
	}else{
      $sql="select * from ".TAB_PREFIX."user WHERE (user_group='3' or user_group='4' or user_group='5') and user_id='".$_SESSION["user_detail"]["user_id"]."'";
	  $dataCurrentArr = $obj_sub->get_results($sql);
	}
if (isset($_POST['submit']) && $_POST['submit'] == 'update' && isset($_GET['id']) && $obj_client->validateId($_GET['id']) && isset($_GET['action']) && $_GET['action'] == "editClient") {

    if(!$obj_client->can_create('subscriber_update')) {
        $obj_client->setError($obj_client->getValMsg('can_create'));
        $obj_client->redirect(PROJECT_URL."/?page=dashboard");
        exit();
    }
	if($obj_sub->updateSubsriber())
	{
		$obj_client->redirect(PROJECT_URL."/?page=subscriber_update");
        exit();
	}
}
?>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>Update Profile</h1></div>
			<div class="clear"></div>
            <?php $obj_client->showErrorMessage(); ?>
			<?php $obj_client->showSuccessMessge(); ?>
			<?php $obj_client->unsetMessage(); ?>

         
        <div class="clear"></div>
			<div class="whitebg formboxcontainer">
        <form name="client-user" id="client-user" method="POST"  enctype="multipart/form-data">
            <h2 class="greyheading">Update profile</h2>
       
                <div class="row">
                     	 <div class="col-md-4 col-sm-4 col-xs-12 form-group">

                            <label>UserName<span class="starred">*</span></label>
						              <input type="text" name="username" id="username" disabled  class="required form-control" data-bind="content" value="<?php echo $dataCurrentArr[0]->username; ?>" />
                               </div>
                              
                   	 <div class="col-md-4 col-sm-4 col-xs-12 form-group">

                            <label>First Name<span class="starred">*</span></label>
                            <input type="text" name="first_name" id="first_name" placeholder="Enter first name" class="required form-control" data-bind="content" value="<?php if(isset($_POST['first_name'])){ echo $_POST['first_name']; } else if(isset($dataCurrentArr[0]->first_name)){ echo $dataCurrentArr[0]->first_name; } ?>" />
                        </div>
                      <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                           
                            <label>Last Name<span class="starred">*</span></label>
                            <input type="text" name="last_name" id="last_name" placeholder="Enter last name" class="required form-control" data-bind="content" value="<?php if(isset($_POST['last_name'])){ echo $_POST['last_name']; } else if(isset($dataCurrentArr[0]->last_name)){ echo $dataCurrentArr[0]->last_name; } ?>" />
                        </div>
                     <div class="clear"></div>
						
                             <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                      
                         
                            <label>Company Name<span class="starred">*</span></label>
                            <input type="text" name="company_name" id="company_name" placeholder="Enter company name" class="required form-control" data-bind="content" value="<?php if(isset($_POST['company_name'])){ echo $_POST['company_name']; } else if(isset($dataCurrentArr[0]->company_name)){ echo $dataCurrentArr[0]->company_name; } ?>" />
                        </div>
						
				
                          
                        
                            <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                                <label>Password</label>
                                <input type="password" name="password" id="password" autocomplete="off" placeholder="Enter password" class="form-control" data-bind="content" />
                            </div>
							  <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                                <label>Confirm Password</label>
                                <input type="password" name="confirmpassword" id="confirmpassword" autocomplete="off" placeholder="Enter password" class="form-control" data-bind="content" />
                            </div>
                         <div class="clear"></div>
                        
                       
                 	  <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                          
                            <label>Email Address<span class="starred">*</span></label>
                            <input type="text" name="emailaddress" id="emailaddress" placeholder="Enter email address" class="required form-control" data-bind="email" value="<?php if(isset($_POST['emailaddress'])){ echo $_POST['emailaddress']; } else if(isset($dataCurrentArr[0]->email)){ echo $dataCurrentArr[0]->email; } ?>" />
                        </div>    
					
						      <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                                                        
                            <label>Phone Number<span class="starred">*</span></label>
                            <input type="text" name="phonenumber" id="phonenumber" placeholder="Enter phone number" class="required form-control" data-bind="mobilenumber" value="<?php if(isset($_POST['phonenumber'])){ echo $_POST['phonenumber']; } else if(isset($dataCurrentArr[0]->phone_number)){ echo $dataCurrentArr[0]->phone_number; } ?>" />
                        </div><div class="clear"></div>
						 <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                        <label>Upload Profile</label>
                        <div class="clear"></div>
                        <input type="file" name="profile_pics" id="profile_pics">
                        <div class="clear"></div>
                        <small>(Recommended size w=170 and h=50)</small>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                        <?php if (isset($dataCurrentArr[0]->profile_pics) && $dataCurrentArr[0]->profile_pics!= "") { ?>
                            <img src="<?php echo PROJECT_URL . '/upload/profile-picture/' . $dataCurrentArr[0]->profile_pics; ?>">
                            <div class="clear"></div>
                        <?php } ?>
                    </div>
						 
                     
              <div class="clear"></div>
						 <div class="adminformbxsubmit" style="width:100%;">
                     	<div class="tc">
                            <input type='submit' class="btn btn-success" name='submit' value='<?php if(isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == "editClient") { echo 'update'; } else { echo 'submit'; } ?>' id='submit'>
                            <input type="button" value="<?php echo ucfirst('Back'); ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=dashboard"; ?>';" class="btn btn-danger"/>
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