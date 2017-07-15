<?php
$obj_master = new master();
if(!$obj_master->can_read('master_state')) {

    $obj_master->setError($obj_master->getValMsg('can_read'));
    $obj_master->redirect(PROJECT_URL."/?page=dashboard");
    exit();
}

if(isset($_POST['submit']) && $_POST['submit']=='submit') {
    
	if(!$obj_master->can_create('master_state')) {
        $obj_master->setError($obj_master->getValMsg('can_create'));
        $obj_master->redirect(PROJECT_URL."/?page=master_state");
        exit();
    }
    
	if($obj_master->addState()) {
        $obj_master->redirect(PROJECT_URL."/?page=master_state");
    }
}

if(isset($_POST['submit']) && $_POST['submit']=='update' && isset($_GET['id'])) {
	
    if(!$obj_master->can_update('master_state')) {

        $obj_master->setError($obj_master->getValMsg('can_update'));
        $obj_master->redirect(PROJECT_URL."/?page=master_state");
        exit(); 
    }
    
	if($obj_master->updateState()) {
        $obj_master->redirect(PROJECT_URL."/?page=master_state");
    }
}

$dataArr = array();
if(isset($_GET['id'])) {
    $dataArr = $obj_master->findAll($obj_master->getTableName('state'),"is_deleted='0' and state_id='".$obj_master->sanitize($_GET['id'])."'");
}
?>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>State</h1></div>
         <hr class="headingborder">
         <?php $obj_master->showErrorMessage(); ?>
        <?php $obj_master->showSuccessMessge(); ?>
        <?php $obj_master->unsetMessage(); ?>
        <div class="clear"></div>
       
        <div class="whitebg formboxcontainer">
         
        <h2 class="greyheading"><?php echo isset($_GET['id']) ? 'Edit State' : 'Add New States'; ?></h2>
        <form method="post" enctype="multipart/form-data" id='form'>
                <div class="row">
                     
						 <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                            <label>State<span class="starred">*</span></label>
                            <input type="text" name='state_name' placeholder="State" class='required form-control' data-bind="content" value='<?php if(isset($_POST['state_name'])){ echo $_POST['state_name'];}else if(isset($dataArr[0]->state_name)){ echo $dataArr['0']->state_name;}?>' />
                            <span class="greysmalltxt"></span>
							</div>
                      <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                       
                            <label>State Code<span class="starred">*</span></label>
                            <input type="text" name='state_code' placeholder="Name" class='required form-control' data-bind="alphanum" value='<?php if(isset($_POST['state_code'])){ echo $_POST['state_code'];}else if(isset($dataArr[0]->state_code)){ echo $dataArr['0']->state_code;}?>' />
                        </div>
   <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                          
                            <label>State Tin Number<span class="starred">*</span></label>
                            <input type="text" name='state_tin' placeholder="Tin" class='required form-control' data-bind="number" value='<?php if(isset($_POST['state_tin'])){ echo $_POST['state_tin'];}else if(isset($dataArr[0]->state_tin)){ echo $dataArr['0']->state_tin; } ?>' />
                        </div>
                        <div class="clear"></div>

                  <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                       
                            <label>Status<span class="starred">*</span></label>
                            <select name="status" class="form-control">
                                <option value="1" <?php if(isset($_POST['status']) && $_POST['status'] === '1'){ echo 'selected="selected"'; } else if(isset($dataArr[0]->status) && $dataArr[0]->status === '1'){ echo 'selected="selected"'; } ?>>Active</option>
                                <option value="0" <?php if(isset($_POST['status']) && $_POST['status'] === '0'){ echo 'selected="selected"'; } else if(isset($dataArr[0]->status) && $dataArr[0]->status === '0'){ echo 'selected="selected"'; } ?>>In-Active</option>
                            </select>
                        </div>
						 
    
                     <div class="clear height30"></div>
                                
								
							 <div class="adminformbxsubmit" style="width:100%;"> 
                            <div class="tc">
                                <input type='submit' class="btn btn-danger" name='submit' value='<?php echo isset($_GET['id']) ? 'update' : 'submit'; ?>' id='submit'>
                                <input type="button" value="<?php echo ucfirst('Back'); ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=master_state"; ?>';" class="btn btn-danger" />
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