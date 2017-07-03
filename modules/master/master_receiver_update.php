<?php
$obj_master = new master();
if(!$obj_master->can_create('master_receiver') && !isset($_GET['id'])) {
    
	$obj_master->setError($obj_master->getValMsg('can_create'));
    $obj_master->redirect(PROJECT_URL."/?page=dashboard");
    exit();
}

if(!$obj_master->can_update('master_receiver') && isset($_GET['id'])) {

	$obj_master->setError($obj_master->getValMsg('can_update'));
	$obj_master->redirect(PROJECT_URL."/?page=master_receiver");
	exit(); 
}

if(isset($_POST['submit']) && $_POST['submit']=='submit') {

    if(!$obj_master->can_create('master_receiver')) {

        $obj_master->setError($obj_master->getValMsg('can_create'));
        $obj_master->redirect(PROJECT_URL."/?page=master_receiver");
        exit();
    }

    if($obj_master->addReceiver()){
        $obj_master->redirect(PROJECT_URL."/?page=master_receiver");
    }
}

if(isset($_POST['submit']) && $_POST['submit']=='update' && isset($_GET['id'])) {

    if(!$obj_master->can_update('master_receiver')) {

        $obj_master->setError($obj_master->getValMsg('can_update'));
        $obj_master->redirect(PROJECT_URL."/?page=master_receiver");
        exit();
	}
	
    if($obj_master->updateReceiver()) {
        $obj_master->redirect(PROJECT_URL."/?page=master_receiver");
    }
}

$dataArr = array();
if(isset($_GET['id'])) {
    
	$erecid = $obj_master->sanitize($_GET['id']);
	$recdata = $obj_master->get_row("select * from " . $obj_master->getTableName('receiver') ." where receiver_id = '".$erecid."' AND added_by = '".$obj_master->sanitize($_SESSION['user_detail']['user_id'])."' AND is_deleted='0'");

	if (empty($recdata)) {
		$obj_master->setError("No receiver found.");
        $obj_master->redirect(PROJECT_URL."?page=master_receiver");
	}

	$dataArr = $obj_master->findAll($obj_master->getTableName('receiver'),"is_deleted='0' and receiver_id='".$obj_master->sanitize($_GET['id'])."'");
}
?>
<div class="admincontainer greybg">
    <div class="formcontainer">
        <?php $obj_master->showErrorMessage(); ?>
        <?php $obj_master->showSuccessMessge(); ?>
        <?php $obj_master->unsetMessage(); ?>
        <h1>Receiver</h1>
        <hr class="headingborder">
        <h2 class="greyheading"><?php echo isset($_GET['id']) ? 'Edit Receiver' : 'Add New Receiver'; ?></h2>
        <form method="post" enctype="multipart/form-data" id='form'>
            <div class="adminformbx">
                <div class="kycform">
                    <div class="kycmainbox">
                        <div class="clear"></div>
                        
						<div class="formcol">
                            <label>GSTIN</label>
                            <input type="text" placeholder="GSTIN" name='gstid' data-bind="gstin" value='<?php if(isset($_POST['gstid'])){ echo $_POST['gstid']; } else if(isset($dataArr[0]->gstid)){ echo $dataArr[0]->gstid; } ?>' />
                            <span class="greysmalltxt"></span>
						</div>
                        
						<div class="formcol two">
                            <label>Name<span class="starred">*</span></label>
                            <input type="text" placeholder="Name"  name='name' data-bind="content" class="required" value='<?php if(isset($_POST['name'])){ echo $_POST['name'];}else if(isset($dataArr[0]->name)){ echo $dataArr[0]->name; } ?>'/>
                        </div>
                        <div class="formcol third">
                            <label>Address<span class="starred">*</span></label>
                            <input type="text" placeholder="Address" name='address' data-bind="content" class="required" value='<?php if(isset($_POST['address'])){ echo $_POST['address'];}else if(isset($dataArr[0]->address)){ echo $dataArr[0]->address; } ?>'/>
                        </div>
                        <div class="formcol">
                            <label>State<span class="starred">*</span></label>
                            <select name='state' id='state' class='required'>
                                <?php
                                $dataStateArrs = $obj_master->get_results("select * from ".$obj_master->getTableName('state')." where status='1' and is_deleted='0' order by state_name asc");
                                if(!empty($dataStateArrs)) { ?>
                                    <option value=''>Select State</option>
                                    <?php
                                    foreach($dataStateArrs as $dataStateArr) { ?>
                                        <option value='<?php echo $dataStateArr->state_id; ?>' <?php if(isset($_POST['state']) && $_POST['state'] === $dataStateArr->state_id){ echo 'selected="selected"'; } else if(isset($dataArr[0]->state) && $dataStateArr->state_id == $dataArr[0]->state){ echo 'selected="selected"'; } ?>><?php echo $dataStateArr->state_name; ?></option>
                                    <?php }
                                } else { ?>
                                    <option value=''>No State Found</option>
								<?php } ?>
                            </select>
                            <span class="greysmalltxt"></span> 
                        </div>

                        <div class="formcol third">
                            <label>Status<span class="starred">*</span></label>
                            <select name="status">
                                <option value="1" <?php if(isset($_POST['status']) && $_POST['status']==='1'){ echo 'selected="selected"'; } else if(isset($dataArr[0]->status) && $dataArr[0]->status==='1'){ echo 'selected="selected"'; } ?>>Active</option>
                                <option value="0" <?php if(isset($_POST['status']) && $_POST['status']==='0'){ echo 'selected="selected"'; } else if(isset($dataArr[0]->status) && $dataArr[0]->status==='0'){ echo 'selected="selected"'; } ?>>In-Active</option>
                            </select>
                        </div>
                        <div class="clear height30"></div>
                        <div class="adminformbxsubmit" style="width:100%;"> 
                            <div class="tc">
                                <input type='submit' class="btn orangebg" name='submit' value='<?php echo isset($_GET['id']) ? 'update' : 'submit'; ?>' id='submit'>
                                <input type="button" value="<?php echo ucfirst('Back'); ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=master_receiver"; ?>';" class="btn redbg" class="redbtn marlef10"/>
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
		
		/* select2 js for state */
        $("#state").select2();
		
        $('#submit').click(function () {
            var mesg = {};
            if (vali.validate(mesg,'form')) {
                return true;
            }
            return false;
        });
    });
</script>
    