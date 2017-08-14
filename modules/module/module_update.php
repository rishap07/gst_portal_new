<?php
$obj_module = new module();

if( !isset($_SESSION['user_detail']['user_id']) || $_SESSION['user_detail']['user_id'] == '' ) {
    $obj_module->redirect(PROJECT_URL);
    exit();
}


if(!$obj_module->can_read('module_list')) {

    $obj_module->setError($obj_module->getValMsg('can_read'));
    $obj_module->redirect(PROJECT_URL."/?page=dashboard");
    exit();
}

/* get current user data */
$dataCurrentArr = array();
if(isset($_GET["action"]) && ($_GET["action"]=="editModule") && (isset($_GET["id"])))
{

  $sql = "select  *,count(module_id) as totalmodule from gst_module where module_id=".$_GET["id"]."";
   $dataCurrentArr = $obj_module->get_results($sql);
   $dataCurrentArr[0]->totalmodule;
//$dataCurrentArr = $obj_module->getUserDetailsById($obj_module->sanitize($_SESSION['user_detail']['user_id']));
}
 
if( isset($_POST['submit']) && $_POST['submit'] == 'submit' ) {

//if(!$obj_module->can_create('module_list')) {
	if($obj_module->updateModule())
	{
		$obj_module->redirect(PROJECT_URL."/?page=module_list");
	}
//}
}
if( isset($_POST['submit']) && $_POST['submit'] == 'update' ) {
	
	//if(!$obj_module->can_read('module_list')) {
		if($obj_module->updateModule())
		{
		
			$obj_module->redirect(PROJECT_URL."/?page=module_list");
		}
	//}
}






?>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>Module</h1></div>
			<div class="clear"></div>
            <?php $obj_module->showErrorMessage(); ?>
			<?php $obj_module->showSuccessMessge(); ?>
			<?php $obj_module->unsetMessage(); ?>

         
        <div class="clear"></div>
			<div class="whitebg formboxcontainer">
        <form name="client-user" id="client-user" method="POST">
            <h2 class="greyheading">
			<?php
			if(isset($_GET["action"]) && ($_GET["action"]=="editCoupon") && (isset($_GET["id"])))
{ 
echo	'Update'; } else { echo 'Create New' ; } ?> Module Information</h2>
       
                <div class="row">
                     	
                              
                    	 <div class="col-md-4 col-sm-4 col-xs-12 form-group">

                            <label>Module Name<span class="starred">*</span></label>
							
							<input type="text"   name="module_name" id="module_name" placeholder="Enter Module name" class="required form-control" data-bind="content" value="<?php if(isset($_POST['module_name'])){ echo $_POST['module_name']; } else if(isset($dataCurrentArr[0]->module_name)){ echo $dataCurrentArr[0]->module_name; } ?>" />
						
								
                         </div>
						
                       
						
						 <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                      
                         
                            <label>Url<span class="starred">*</span></label>
                           <input type="text"   name="module_url" id="module_url" placeholder="Enter Module Url" class="required form-control" data-bind="content" value="<?php if(isset($_POST['module_url'])){ echo $_POST['module_url']; } else if(isset($dataCurrentArr[0]->url)){ echo $dataCurrentArr[0]->url; } ?>" />
						   </div>
						   <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                      
                         
                            <label>Title<span class="starred">*</span></label>
                           <input type="text"   name="module_title" id="module_title" placeholder="Enter Module Title" class="required form-control" data-bind="content" value="<?php if(isset($_POST['module_title'])){ echo $_POST['module_title']; } else if(isset($dataCurrentArr[0]->Title)){ echo $dataCurrentArr[0]->Title; } ?>" />
						   </div>
						    <div class="clear"></div>
						
						 <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                             <label>Status<span class="starred">*</span></label>
                         <select name='module_status' id='module_status' class='required form-control'>
                        
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
						 <div class="adminformbxsubmit" style="width:100%;">
                             
						 
						 
						   
						<div class="tc">
                            <input type='submit' class="btn btn-success" name='submit' value='<?php if(isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == "editModule") { echo 'update'; } else { echo 'submit'; } ?>' id='submit'>
                            <input type="button" value="<?php echo ucfirst('Back'); ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=module_list"; ?>';" class="btn btn-danger"/>
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