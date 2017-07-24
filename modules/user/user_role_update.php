<?php
$obj_users = new users();
if(isset($_POST['submit']) && $_POST['submit']=='submit')
{
    if($obj_users->addUserRole())
    {
        $obj_users->redirect(PROJECT_URL."/?page=user_role");
    }
}
if(isset($_POST['submit']) && $_POST['submit']=='update' && isset($_GET['id']))
{
    if($obj_users->updateUserRole())
    {
        $obj_users->redirect(PROJECT_URL."/?page=user_role");
    }
}
$dataArr = array();
if(isset($_GET['id']))
{
    $dataArr = $obj_users->findAll($obj_users->getTableName('user_role'),"is_deleted='0' and user_role_id='".$obj_users->sanitize($_GET['id'])."'");
}


?>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>User Role</h1></div>
           <hr class="headingborder">
            <?php $obj_users->showErrorMessage(); ?>
        <?php $obj_users->showSuccessMessge(); ?>
        <?php $obj_users->unsetMessage(); ?>

       
        <div class="whitebg formboxcontainer">
           <h2 class="greyheading"><?php echo isset($_GET['id']) ? 'Edit Role' : 'Add New Role'; ?></h2>
       
        <div class="clear"></div>

        <form name="add-plan" id="add-plan" method="POST">
                <div class="row">
                     
				                
                   	 <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                          
                            <label>Role Name<span class="starred">*</span></label>
                            <input type="text" placeholder="Role Name" name='role_name' data-bind="content" class="required form-control" value='<?php if(isset($_POST['role_name'])){ echo $_POST['role_name'];}else if(isset($dataArr[0]->role_name)){ echo $dataArr[0]->role_name;}?>' />
                            <span class="greysmalltxt"></span>
                         </div>
                            
                      <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                                           
                        
                            <label>Page Name<span class="starred">*</span></label>
                            <input type="text" placeholder="Page Name"  name='role_page' data-bind="content" class="required form-control" value='<?php if(isset($_POST['role_page'])){ echo $_POST['role_page'];}else if(isset($dataArr[0]->role_page)){ echo $dataArr[0]->role_page;}?>'/>
                        </div>
                           
                             <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                                                 
                            <label>Role Description<span class="starred">*</span></label>
                            <input type="text" placeholder="Role Description" name='role_description' data-bind="demical" class="required form-control" value='<?php if(isset($_POST['role_description'])){ echo $_POST['role_description'];}else if(isset($dataArr[0]->role_description)){ echo $dataArr[0]->role_description;}?>'/>
                        </div>
						<div class="clear"></div>
					 
                      <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                            <label>Status<span class="starred">*</span></label>
                            <select name="status">
                                <option value="1" <?php if(isset($_POST['status']) &&  $_POST['status']==='1'){ echo 'selected';}else if(isset($dataArr[0]->state_code) && $dataArr[0]->state_code==='1'){ echo 'selected';}?>>Active</option>
                                <option value="0" <?php if(isset($_POST['status']) &&  $_POST['status']==='0'){ echo 'selected';}else if(isset($dataArr[0]->state_code) && $dataArr[0]->state_code==='0'){ echo 'selected';}?>>In-Active</option>
                            </select>
                        </div>
                 	<div class="clear"></div>
                       <div class="adminformbxsubmit" style="width:100%;"> 
                            <div class="tc">
                                <input type='submit' class="btn btn-danger" name='submit' value='<?php echo isset($_GET['id']) ? 'update' : 'submit'; ?>' id='submit'>
                                <input type="button" value="<?php echo ucfirst('Back'); ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=user_role"; ?>';" class="btn btn-danger" />
                            </div> 
                        </div>
						

                        </div>
							
                        </div>
                </div>
                   
                </div>
      
                        </form>
                </div>


<script>
    $(document).ready(function () {
        $("#state").change(function () {
           val1 = $(this).val().split(":");
           $("#state_code").val(val1[1]);
        });
        $('#submit').click(function () {
            var mesg = {};
            if (vali.validate(mesg,'form')) {
                return true;
            }
            return false;
        });
    });
</script>
    