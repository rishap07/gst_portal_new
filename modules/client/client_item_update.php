<?php
$obj_client = new client();
if( !isset($_SESSION['user_detail']['user_id']) || $_SESSION['user_detail']['user_id'] == '' ) {
    $obj_client->redirect(PROJECT_URL);
    exit();
}

if(!$obj_client->can_read('client_master_item')) {

    $obj_client->setError($obj_client->getValMsg('can_read'));
    $obj_client->redirect(PROJECT_URL."/?page=dashboard");
    exit();
}

if(isset($_POST['submit']) && $_POST['submit'] == 'submit'){
	
	if(!$obj_client->can_create('client_master_item')) {

		$obj_client->setError($obj_client->getValMsg('can_create'));
		$obj_client->redirect(PROJECT_URL."/?page=client_item_list");
		exit();
	}
 
    if(!isset($_SERVER['HTTP_REFERER']) || empty($_SERVER['HTTP_REFERER'])){

        $obj_client->setError('Invalid access to files');
    } else {

        if($obj_client->addClientItem()) {
            $obj_client->redirect(PROJECT_URL."/?page=client_item_list");
        }
    }
}

if(isset($_POST['submit']) && $_POST['submit'] == 'update' && isset($_GET['id']) && $obj_client->validateId($_GET['id']) && isset($_GET['action']) && $_GET['action'] == "editItem") {
    
	if(!$obj_client->can_update('client_master_item')) {

		$obj_client->setError($obj_client->getValMsg('can_update'));
		$obj_client->redirect(PROJECT_URL."/?page=client_item_list");
		exit();
	}
	
    if(!isset($_SERVER['HTTP_REFERER']) || empty($_SERVER['HTTP_REFERER'])){
        
        $obj_client->setError('Invalid access to files');
    } else {

        if($obj_client->updateClientItem()){
            $obj_client->redirect(PROJECT_URL."/?page=client_item_list");
        }
    }  
}

$dataArr = array();
if(isset($_GET['id']) && $obj_client->validateId($_GET['id']) && isset($_GET['action']) && $_GET['action'] == "editItem"){
    $dataArr = $obj_client->get_results("select cm.item_id, cm.item_name, cm.item_category, cm.unit_price, cm.item_unit, cm.status, m.item_name as category_name, m.hsn_code, u.unit_name, u.unit_code from ".$obj_client->getTableName('client_master_item')." as cm, ".$obj_client->getTableName('item')." as m, ".$obj_client->getTableName('unit')." as u WHERE cm.item_category = m.item_id AND cm.item_unit = u.unit_id AND cm.is_deleted='0' and cm.item_id = '".$obj_client->sanitize($_GET['id'])."'");
}
?>
<div class="admincontainer greybg">
    <div class="formcontainer">
        
        <?php $obj_client->showErrorMessage(); ?>
        <?php $obj_client->showSuccessMessge(); ?>
        <?php $obj_client->unsetMessage(); ?>
        
        <h1>Item</h1>
        <hr class="headingborder">
        <h2 class="greyheading"><?php if(isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == "editItem") { echo 'Edit Item'; } else { echo 'Add New Item'; } ?></h2>
        <form method="post" enctype="multipart/form-data" name="client-item" id='client-item'>
            <div class="adminformbx">
                <div class="kycform">
                    <div class="kycmainbox">
                        <div class="clear"></div>
                        
                        <div class="formcol">
                            <label>Item<span class="starred">*</span></label>
                            <input type="text" placeholder="Item name" name='item_name' id="item_name" data-bind="content" class="required" value='<?php if(isset($_POST['item_name'])){ echo $_POST['item_name']; } else if(isset($dataArr[0]->item_name)){ echo $dataArr[0]->item_name; } ?>' />
                        </div>
                        
                        <div class="formcol two">
                            <label>Category<span class="starred">*</span></label>
                            <select name="item_category" id="item_category" class="required" data-bind="numnzero">
                                <?php $dataItemArrs = $obj_client->getMasterItems("item_id,item_name,hsn_code,(case when status='1' Then 'active' when status='0' then 'deactive' end) as status", "is_deleted='0' AND status='1'"); ?>
                                <?php if(!empty($dataItemArrs)) { ?>
                                    <option value=''>Select Category</option>
                                    <?php foreach($dataItemArrs as $dataItem) { ?>
                                        <option value='<?php echo $dataItem->item_id; ?>' data-hsncode="<?php echo $dataItem->hsn_code; ?>" <?php if(isset($_POST['item_category']) && $_POST['item_category'] === $dataItem->item_id){ echo 'selected="selected"'; } else if(isset($dataArr[0]->item_category) && $dataArr[0]->item_category === $dataItem->item_id) { echo 'selected="selected"'; } ?>><?php echo $dataItem->item_name; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                        
                        <div class="formcol third">
                            <label>HSN Code</label>
                            <div class="clear"></div>
                            <div class="readonly-section" id="item_hsn_code"><?php if(isset($dataArr[0]->hsn_code)){ echo $dataArr[0]->hsn_code; } else { echo "HSN Code"; } ?></div>
                        </div>
                        <div class="clear"></div>

                        <div class="formcol">
                            <label>Unit Price(Rs.)<span class="starred">*</span></label>
                            <input type="text" placeholder="Item Unit Price" name='unit_price' id="unit_price" class="required" data-bind="demical" value='<?php if(isset($_POST['unit_price'])) { echo $_POST['unit_price']; } else if(isset($dataArr[0]->unit_price)){ echo $dataArr[0]->unit_price; } ?>'/>
                        </div>
                        
                        <div class="formcol two">
                            <label>Item Unit<span class="starred">*</span></label>
                            <select name="item_unit" id="item_unit" class="required" data-bind="numnzero">
                                <?php $dataUnitArrs = $obj_client->getUnit("unit_id,unit_name,unit_code,(case when status='1' Then 'active' when status='0' then 'deactive' end) as status", "is_deleted='0' AND status='1'"); ?>
                                <?php if(!empty($dataUnitArrs)) { ?>
                                    <option value=''>Select Unit</option>
                                    <?php foreach($dataUnitArrs as $dataUnit) { ?>
                                        <option value='<?php echo $dataUnit->unit_id; ?>' data-unitcode="<?php echo $dataUnit->unit_code; ?>" <?php if(isset($_POST['item_unit']) && $_POST['item_unit'] === $dataUnit->unit_id){ echo 'selected="selected"'; } else if(isset($dataArr[0]->item_unit) && $dataArr[0]->item_unit === $dataUnit->unit_id) { echo 'selected="selected"'; } ?>><?php echo $dataUnit->unit_name; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                        
                        <div class="formcol third">
                            <label>Status<span class="starred">*</span></label>
                            <div class="clear"></div>
                            <input type="radio" name="status" <?php if(isset($_POST['status']) &&  $_POST['status'] === '1'){ echo 'checked="checked"'; } else if(isset($dataArr[0]->status) && $dataArr[0]->status==='1') { echo 'checked="checked"'; } ?> value="1" /><span>Active</span>
                            <input type="radio" name="status" <?php if(isset($_POST['status']) &&  $_POST['status'] === '0'){ echo 'checked="checked"'; } else if(isset($dataArr[0]->status) && $dataArr[0]->status==='0') { echo 'checked="checked"'; } ?> value="0" /><span>Inactive</span>
                        </div>
                        <div class="clear"></div>
                        
                        <div class="clear height30"></div>
                        <div class="adminformbxsubmit" style="width:100%;">
                            <div class="tc">
                                <input type='submit' class="btn orangebg" name='submit' value='<?php if(isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == "editItem") { echo 'update'; } else { echo 'submit'; } ?>' id='submit'>
                                <input type="button" value="<?php echo ucfirst('Back'); ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=client_item_list"; ?>';" class="btn redbg" class="redbtn marlef10"/>
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
        
        $("#item_category").change(function () {
           
            var hsncode = $(this).find(':selected').attr("data-hsncode");
            if(typeof(hsncode) === "undefined") {
                $("#item_hsn_code").text("HSN Code");
            } else {
                $("#item_hsn_code").text(hsncode);
            }
        });
        
        /* select2 js for item category */
        $("#item_category").select2();
        
        /* select2 js for item unit */
        $("#item_unit").select2();
        
        $('#submit').click(function () {
            var mesg = {};
            if (vali.validate(mesg,'client-item')) {
                return true;
            }
            return false;
        });
    });
</script>