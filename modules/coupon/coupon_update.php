<?php
$obj_client = new client();
$obj_sub = new subscriber();
$obj_coupon = new coupon();
if( !isset($_SESSION['user_detail']['user_id']) || $_SESSION['user_detail']['user_id'] == '' ) {
    $obj_client->redirect(PROJECT_URL);
    exit();
}


if(!$obj_client->can_read('coupon_update')) {

    $obj_client->setError($obj_client->getValMsg('can_read'));
    $obj_client->redirect(PROJECT_URL."/?page=dashboard");
    exit();
}

/* get current user data */
$dataCurrentArr = array();
if(isset($_GET["action"]) && ($_GET["action"]=="editCoupon") && (isset($_GET["id"])))
{

  $sql = "select  *,count(coupon_id) as totalcoupon from gst_coupon where coupon_id=".$_GET["id"]."";
   $dataCurrentArr = $obj_client->get_results($sql);
   $dataCurrentArr[0]->totalcoupon;
//$dataCurrentArr = $obj_client->getUserDetailsById($obj_client->sanitize($_SESSION['user_detail']['user_id']));
}
 
if( isset($_POST['submit']) && $_POST['submit'] == 'submit' ) {

//if(!$obj_client->can_create('coupon_update')) {
	if($obj_coupon->updateCoupon())
	{
		$obj_client->redirect(PROJECT_URL."/?page=coupon_list");
	}
//}
}
if( isset($_POST['submit']) && $_POST['submit'] == 'update' ) {
	
	//if(!$obj_client->can_create('coupon_update')) {
		if($obj_coupon->updateCoupon())
		{
		
			$obj_client->redirect(PROJECT_URL."/?page=coupon_list");
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
            <h2 class="greyheading">
			<?php
			if(isset($_GET["action"]) && ($_GET["action"]=="editCoupon") && (isset($_GET["id"])))
{ 
echo	'Update'; } else { echo 'Create New' ; } ?> Coupon Information</h2>
       
                <div class="row">
                     	
                              
                   	 <div class="col-md-4 col-sm-4 col-xs-12 form-group">

                            <label>Coupon Name<span class="starred">*</span></label>
							<?php
							if(isset($dataCurrentArr[0]->name))
							{
								?>
                            <input type="text" readonly="true" maxlength="6" name="coupon_name" id="coupon_name" placeholder="Enter coupon name" class="required form-control" data-bind="content" value="<?php if(isset($_POST['coupon_name'])){ echo $_POST['coupon_name']; } else if(isset($dataCurrentArr[0]->name)){ echo $dataCurrentArr[0]->name; } ?>" />
							<?php } else
							{
								?>
							<input type="text" maxlength="6"  name="coupon_name" id="coupon_name" placeholder="Enter coupon name" class="required form-control" data-bind="content" value="<?php if(isset($_POST['coupon_name'])){ echo $_POST['coupon_name']; } else if(isset($dataCurrentArr[0]->name)){ echo $dataCurrentArr[0]->name; } ?>" />
							<?php } ?>
					
								
                        </div>
						<?php
						if (isset($dataCurrentArr[0]->type))
						{
							?>
                      <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                             <label>Type<span class="starred">*</span></label>
                         <select name='coupon_type' disabled   id='coupon_type' class='required form-control'>
                        
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
						<?php } else 
						{
							?>  <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                             <label>Type<span class="starred">*</span></label>
                         <select name='coupon_type'  id='coupon_type' class='required form-control'>
                        
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
							
                        </select></div><?php } ?>
                        <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                      
                         <?php
							if(isset($dataCurrentArr[0]->coupon_value))
							{
								?>
                            <label>Coupon value<span class="starred">*</span></label>
                            <input type="text" readonly="true" onKeyPress="return  isNumberKey(event,this);" name="coupon_value" id="coupon_value" placeholder="Enter coupon value" class="required form-control" data-bind="content" value="<?php if(isset($_POST['coupon_value'])){ echo $_POST['coupon_value']; } else if(isset($dataCurrentArr[0]->coupon_value)){ echo $dataCurrentArr[0]->coupon_value; } ?>" />
							<?php } else
							{
								?>  <label>Coupon value<span class="starred">*</span></label>
								       <input type="text"  onKeyPress="return  isNumberKey(event,this);" name="coupon_value" id="coupon_value" placeholder="Enter coupon value" class="required form-control" data-bind="content" value="<?php if(isset($_POST['coupon_value'])){ echo $_POST['coupon_value']; } else if(isset($dataCurrentArr[0]->coupon_value)){ echo $dataCurrentArr[0]->coupon_value; } ?>" />
					
							<?php } ?>
                        </div>
						 <div class="clear"></div>
						
						 <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                      
                         
                            <label>Coupon Uses<span class="starred">*</span></label>
                            <input type="text" onKeyPress="return  isNumberKey(event,this);" name="coupon_uses" id="coupon_uses" placeholder="Enter coupon uses" class="required form-control" data-bind="content" value="<?php if(isset($_POST['coupon_uses'])){ echo $_POST['coupon_uses']; } else if(isset($dataCurrentArr[0]->coupon_uses)){ echo $dataCurrentArr[0]->coupon_uses; } ?>" />
                        </div>
						   <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                             <label>Show on Website<span class="starred">*</span></label>
                         <select name='coupon_hidden' id='coupon_hidden' class='required form-control'>
                        
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
						 <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                             <label>Status<span class="starred">*</span></label>
                         <select name='coupon_status' id='coupon_status' class='required form-control'>
                        
						   <option value='1' <?php
                                    if (isset($dataCurrentArr[0]->status) && $dataCurrentArr[0]->status == 1) {
                                        echo "selected='selected'";
                                    }
                                    ?>>Active</option>
									 <option value='0' <?php
                                    if (isset($dataCurrentArr[0]->status) && $dataCurrentArr[0]->status==0) {
                                        echo "selected='selected'";
                                    }
                                    ?>>InActive</option>
							
                        </select></div>
						 <div class="clear"></div>
						
                         
				
                          
                        
                           
							 
                     
                 	  
					
						    
						 
                     
              <div class="clear"></div>
						 <div class="adminformbxsubmit" style="width:100%;">
                             
						 
						 
						   
						<div class="tc">
                            <input type='submit' class="btn btn-success" name='submit' value='<?php if(isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == "editCoupon") { echo 'update'; } else { echo 'submit'; } ?>' id='submit'>
                            <input type="button" value="<?php echo ucfirst('Back'); ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=coupon_list"; ?>';" class="btn btn-danger"/>
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
 <script type="text/javascript">
        function isNumberKey(evt)
      {
         
        var charCode = (evt.which) ? evt.which : event.keyCode
                
        if ((charCode >= 40) && (charCode <= 57) &&(charCode!=47) &&(charCode!=42) && (charCode!=43) && (charCode!=44) && (charCode!=45) || (charCode == 8))
       {
       return true;
           
       }
    else
    {
     return false;

     }
	  }

    </script>