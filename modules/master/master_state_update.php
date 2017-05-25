<?php
$obj_master = new master();
if(isset($_POST['submit']) && $_POST['submit']=='submit')
{
    if($obj_master->addState())
    {
        $obj_master->redirect(PROJECT_URL."/?page=master_state");
    }
}
if(isset($_POST['submit']) && $_POST['submit']=='update' && isset($_GET['id']))
{
    if($obj_master->updateState())
    {
        
        $obj_master->redirect(PROJECT_URL."/?page=master_state");
    }
   
}
$dataArr = array();
if(isset($_GET['id']))
{
    $dataArr = $obj_master->findAll($obj_master->getTableName('state'),"is_deleted='0' and state_id='".$obj_master->sanitize($_GET['id'])."'");
}
?>
<div class="admincontainer greybg">
    <div class="formcontainer">
        <?php $obj_master->showErrorMessage(); ?>
        <?php $obj_master->showSuccessMessge(); ?>
        <?php $obj_master->unsetMessage(); ?>
        <div class="clear"></div>
        <h1>State</h1>
        <hr class="headingborder">
        <h2 class="greyheading"><?php echo isset($_GET['id']) ? 'Edit State' : 'Add New States'; ?></h2>
        <form method="post" enctype="multipart/form-data" id='form'>
            <div class="adminformbx">
                <div class="kycform">
                    <div class="kycmainbox">
                        <div class="clear"></div>
                        <div class="formcol">
                            <label>State<span class="starred">*</span></label>
                            <input type="text" name='state_name' placeholder="State" class='required' data-bind="content" value='<?php if(isset($_POST['state_name'])){ echo $_POST['state_name'];}else if(isset($dataArr[0]->state_name)){ echo $dataArr['0']->state_name;}?>' />
                            <span class="greysmalltxt"></span> </div>
                        <div class="formcol two">
                            <label>State Code<span class="starred">*</span></label>
                            <input type="text" name='state_code' placeholder="Name" class='required' data-bind="alphanum" value='<?php if(isset($_POST['state_code'])){ echo $_POST['state_code'];}else if(isset($dataArr[0]->state_code)){ echo $dataArr['0']->state_code;}?>' />
                        </div>
                        <div class="formcol third">
                            <label>Status<span class="starred">*</span></label>
                            <select name="status">
                                <option value="1" <?php if(isset($_POST['status']) &&  $_POST['status']==='1'){ echo 'selected';}else if(isset($dataArr[0]->state_code) && $dataArr[0]->state_code==='1'){ echo 'selected';}?>>Active</option>
                                <option value="0" <?php if(isset($_POST['status']) &&  $_POST['status']==='0'){ echo 'selected';}else if(isset($dataArr[0]->state_code) && $dataArr[0]->state_code==='0'){ echo 'selected';}?>>In-Active</option>
                            </select>
                        </div>
                        <div class="clear height30"></div>
                        <div class="adminformbxsubmit" style="width:100%;"> 
                            <div class="tc">
                                <input type='submit' class="btn orangebg" name='submit' value='<?php echo isset($_GET['id']) ? 'update' : 'submit'; ?>' id='submit'>
                                <input type="button" value="<?php echo ucfirst('Back'); ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=master_state"; ?>';" class="btn redbg" class="redbtn marlef10"/>
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