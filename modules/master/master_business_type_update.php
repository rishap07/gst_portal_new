<?php
$obj_master = new master();
if(!$obj_master->can_read('master_business_type')) {

    $obj_master->setError($obj_master->getValMsg('can_read'));
    $obj_master->redirect(PROJECT_URL."/?page=dashboard");
    exit();
}

if(isset($_POST['submit']) && $_POST['submit']=='submit') {
    
	if(!$obj_master->can_create('master_business_type')) {
        $obj_master->setError($obj_master->getValMsg('can_create'));
       $obj_master->redirect(PROJECT_URL."/?page=master_business_type");
        exit();
    }
    
	if($obj_master->updateBusinessType()) {
        $obj_master->redirect(PROJECT_URL."/?page=master_business_type");
    }
}

if(isset($_POST['submit']) && $_POST['submit']=='update' && isset($_GET['id'])) {
	
   if(!$obj_master->can_update('master_business_type')) {

        $obj_master->setError($obj_master->getValMsg('can_update'));
        $obj_master->redirect(PROJECT_URL."/?page=master_business_type");
       exit(); 
    }
    
	if($obj_master->updateBusinessType()) {
        $obj_master->redirect(PROJECT_URL."/?page=master_business_type");
    }
}

$dataArr = array();
if(isset($_GET['id'])) {
    $dataArr = $obj_master->findAll($obj_master->getTableName('business_type'),"is_deleted='0' and business_id='".$obj_master->sanitize($_GET['id'])."'");
}
?>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>BusinessType</h1></div>
         <hr class="headingborder">
		   <div class="clear"></div>
         <?php $obj_master->showErrorMessage(); ?>
        <?php $obj_master->showSuccessMessge(); ?>
        <?php $obj_master->unsetMessage(); ?>
        <div class="clear"></div>
       
        <div class="whitebg formboxcontainer">
         
        <h2 class="greyheading"><?php echo isset($_GET['id']) ? 'Edit BusinessType' : 'Add New BusinessArea'; ?></h2>
        <form method="post" enctype="multipart/form-data" id='form'>
                <div class="row">
                     
						 <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                            <label>BusinessType Name<span class="starred">*</span></label>
                            <input type="text" name='business_type_name' placeholder="BusinessType Name" class='required form-control' data-bind="content" value='<?php if(isset($_POST['business_type_name'])){ echo $_POST['business_type_name'];}else if(isset($dataArr[0]->business_name)){ echo $dataArr['0']->business_name;}?>' />
                            <span class="greysmalltxt"></span>
							</div>
                   
                      

                  <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                       
                            <label>Status<span class="starred">*</span></label>
                            <select name="business_type_status" class="form-control">
                                <option value="1" <?php if(isset($_POST['business_type_status']) && $_POST['business_type_status'] === '1'){ echo 'selected="selected"'; } else if(isset($dataArr[0]->status) && $dataArr[0]->status === '1'){ echo 'selected="selected"'; } ?>>Active</option>
                                <option value="0" <?php if(isset($_POST['business_type_status']) && $_POST['business_type_status'] === '0'){ echo 'selected="selected"'; } else if(isset($dataArr[0]->status) && $dataArr[0]->status === '0'){ echo 'selected="selected"'; } ?>>In-Active</option>
                            </select>
                        </div>
						 
    
                     <div class="clear height30"></div>
                                
								
							 <div class="adminformbxsubmit" style="width:100%;"> 
                            <div class="tc">
                                <input type='submit' class="btn btn-success" name='submit' value='<?php echo isset($_GET['id']) ? 'update' : 'submit'; ?>' id='submit'>
                                <input type="button" value="<?php echo ucfirst('Back'); ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=master_vendor"; ?>';" class="btn btn-danger" />
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
        $('#submit').click(function () {
            var mesg = {};
            if (vali.validate(mesg,'form')) {
                return true;
            }
            return false;
        });
    });
</script>