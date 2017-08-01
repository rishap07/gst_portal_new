<?php
$obj_client = new client();
$obj_sub = new subscriber();
if( !isset($_SESSION['user_detail']['user_id']) || $_SESSION['user_detail']['user_id'] == '' ) {
    $obj_client->redirect(PROJECT_URL);
    exit();
}
/*

if(!$obj_client->can_read('subscriber_update')) {

    $obj_client->setError($obj_client->getValMsg('can_read'));
    $obj_client->redirect(PROJECT_URL."/?page=dashboard");
    exit();
}
*/
/* get current user data */
$dataCurrentArr = array();
 $sql="select * from gst_user WHERE user_group='3' and user_id='".$_SESSION["user_detail"]["user_id"]."'";
$dataCurrentArr = $db_obj->get_results($sql);
 
if( isset($_POST['submit']) && $_POST['submit'] == 'submit' ) {
	
	if(!$obj_client->can_create('subscriber_update')) {
        $obj_client->setError($obj_client->getValMsg('can_create'));
        $obj_client->redirect(PROJECT_URL."/?page=dashboard");
        exit();
    }
	if($obj_sub->updateSubsriber())
	{
		
	}
	
}




?>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>Client Coupon</h1></div>
			<div class="clear"></div>
            <?php $obj_client->showErrorMessage(); ?>
			<?php $obj_client->showSuccessMessge(); ?>
			<?php $obj_client->unsetMessage(); ?>

         
        <div class="clear"></div>
			<div class="whitebg formboxcontainer">
        <form name="client-user" id="client-user" method="POST">
            <h2 class="greyheading">Update Coupon Information</h2>
       
                <div class="row">
                     	
                              
                   	 <div class="col-md-4 col-sm-4 col-xs-12 form-group">

                            <label>Coupon Name<span class="starred">*</span></label>
                            <input type="text" name="coupon_name" id="coupon_name" placeholder="Enter coupon name" class="required form-control" data-bind="content" value="<?php if(isset($_POST['coupon_name'])){ echo $_POST['coupon_name']; } else if(isset($dataCurrentArr[0]->name)){ echo $dataCurrentArr[0]->name; } ?>" />
                        </div>
						
                      <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                             <label>Type<span class="starred">*</span></label>
                         <select name='coupon_type' id='coupon_type' class='required form-control'>
                        
						   <option value='1' <?php
                                    if (isset($dataCurrentArr[0]->type) && $dataCurrentArr[0]->type == 1) {
                                        echo "selected='selected'";
                                    }
                                    ?>>Percentage</option>
									 <option value='0' <?php
                                    if (isset($dataCurrentArr[0]->type) && $dataCurrentArr[0]->type==0) {
                                        echo "selected='selected'";
                                    }
                                    ?>>Amount</option>
							
                        </select></div>
                        <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                      
                         
                            <label>Coupon value<span class="starred">*</span></label>
                            <input type="text" name="coupon_value" id="coupon_value" placeholder="Enter coupon value" class="required form-control" data-bind="content" value="<?php if(isset($_POST['coupon_value'])){ echo $_POST['coupon_value']; } else if(isset($dataCurrentArr[0]->coupon_value)){ echo $dataCurrentArr[0]->coupon_value; } ?>" />
                        </div>
						 <div class="clear"></div>
						
						 <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                      
                         
                            <label>Coupon Uses<span class="starred">*</span></label>
                            <input type="text" name="coupon_uses" id="coupon_uses" placeholder="Enter coupon uses" class="required form-control" data-bind="content" value="<?php if(isset($_POST['coupon_uses'])){ echo $_POST['coupon_uses']; } else if(isset($dataCurrentArr[0]->coupon_uses)){ echo $dataCurrentArr[0]->coupon_uses; } ?>" />
                        </div>
						   <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                             <label>Status<span class="starred">*</span></label>
                         <select name='coupon_hideen' id='coupon_hideen' class='required form-control'>
                        
						   <option value='1' <?php
                                    if (isset($dataCurrentArr[0]->hidden) && $dataCurrentArr[0]->hidden == 1) {
                                        echo "selected='selected'";
                                    }
                                    ?>>Hidden</option>
									 <option value='0' <?php
                                    if (isset($dataCurrentArr[0]->hidden) && $dataCurrentArr[0]->hidden==0) {
                                        echo "selected='selected'";
                                    }
                                    ?>>Active</option>
							
                        </select></div>
						 <div class="clear"></div>
						
                         
				
                          
                        
                           
							 
                     
                 	  
					
						    
						 
                     
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