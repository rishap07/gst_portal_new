<?php
$obj_master = new master();
if(!$obj_master->can_create('master_unit') && !isset($_GET['id'])){
	
    $obj_master->setError($obj_master->getValMsg('can_create'));
    $obj_master->redirect(PROJECT_URL."/?page=master_unit");
    exit();
}

if(!$obj_master->can_update('master_unit') && isset($_GET['id']))
{
    $obj_master->setError($obj_master->getValMsg('can_update'));
    $obj_master->redirect(PROJECT_URL."/?page=master_unit");
    exit();
}

if(isset($_POST['submit']) && $_POST['submit']=='submit') {

    if(!$obj_master->can_create('master_unit')) {

		$obj_master->setError($obj_master->getValMsg('can_create'));
        $obj_master->redirect(PROJECT_URL."/?page=master_unit");
        exit();
    }
	
    if($obj_master->addUnit()) {
		$obj_master->redirect(PROJECT_URL."/?page=master_unit");
    }
}

if(isset($_POST['submit']) && $_POST['submit']=='update' && isset($_GET['id']) && $obj_master->validateId($_GET['id']))
{
    if(!$obj_master->can_update('master_unit')) {

		$obj_master->setError($obj_master->getValMsg('can_update'));
        $obj_master->redirect(PROJECT_URL."/?page=master_unit");
        exit(); 
    }
	
    if($obj_master->updateUnit()) {
        $obj_master->redirect(PROJECT_URL."/?page=master_unit");
    }
}

$dataArr = array();
if(isset($_GET['id'])){
    $dataArr = $obj_master->findAll($obj_master->getTableName('unit'),"is_deleted='0' and unit_id='".$obj_master->sanitize($_GET['id'])."'");
}
?>
<div class="admincontainer greybg">
    <div class="formcontainer">
        <?php $obj_master->showErrorMessage(); ?>
        <?php $obj_master->showSuccessMessge(); ?>
        <?php $obj_master->unsetMessage(); ?>
        <div class="clear"></div>
        <h1>Unit</h1>
        <hr class="headingborder">
        <h2 class="greyheading"><?php echo isset($_GET['id']) ? 'Edit Unit' : 'Add New Unit'; ?></h2>
        <form method="post" enctype="multipart/form-data" id='form'>
            <div class="adminformbx">
                <div class="kycform">
                    <div class="kycmainbox">
                        <div class="clear"></div>
                        <div class="formcol">
                            <label>Unit<span class="starred">*</span></label>
                            <input type="text" name='unit_name' placeholder="Unit" class='required' data-bind="content" value='<?php if(isset($_POST['unit_name'])){ echo $_POST['unit_name']; } else if(isset($dataArr[0]->unit_name)){ echo $dataArr['0']->unit_name; } ?>' />
						</div>
                        
						<div class="formcol two">
                            <label>Unit Code<span class="starred">*</span></label>
                            <input type="text" name='unit_code' placeholder="Code" class='required' data-bind="alphanum" value='<?php if(isset($_POST['unit_code'])){ echo $_POST['unit_code']; } else if(isset($dataArr[0]->unit_code)){ echo $dataArr['0']->unit_code; } ?>' />
                        </div>
                        
						<div class="formcol third">
                            <label>Status<span class="starred">*</span></label>
                            <select name="status">
                                <option value="1" <?php if(isset($_POST['status']) &&  $_POST['status'] === '1'){ echo 'selected="selected"'; } else if(isset($dataArr[0]->status) && $dataArr[0]->status === '1'){ echo 'selected="selected"'; } ?>>Active</option>
                                <option value="0" <?php if(isset($_POST['status']) &&  $_POST['status'] === '0'){ echo 'selected="selected"'; } else if(isset($dataArr[0]->status) && $dataArr[0]->status === '0'){ echo 'selected="selected"'; } ?>>In-Active</option>
                            </select>
                        </div>
						
                        <div class="clear height30"></div>
                        <div class="adminformbxsubmit" style="width:100%;">
                            <div class="tc">
                                <input type='submit' class="btn orangebg" name='submit' value='<?php echo isset($_GET['id']) ? 'update' : 'submit'; ?>' id='submit'>
                                <input type="button" value="<?php echo ucfirst('Back'); ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=master_unit"; ?>';" class="btn redbg" class="redbtn marlef10"/>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </form>
    </div>
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