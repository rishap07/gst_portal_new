<?php
$obj_client = new client();

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

	$eitemid = $obj_client->sanitize($_GET['id']);
	$itemdata = $obj_client->get_row("select * from " . $obj_client->getTableName('client_master_item') ." where item_id = '".$eitemid."' AND added_by = '".$obj_client->sanitize($_SESSION['user_detail']['user_id'])."'");

	if (empty($itemdata)) {
		$obj_client->setError("No item found.");
        $obj_client->redirect(PROJECT_URL."?page=client_item_list");
	}
	
	$dataArr = $obj_client->get_results("select cm.is_applicable, cm.item_id,cm.item_name, cm.item_category, cm.unit_price, cm.item_description, cm.item_unit, cm.status, CONCAT(UCASE(LEFT(m.item_name,1)),LCASE(SUBSTRING(m.item_name,2))) as category_name, m.hsn_code, u.unit_name, u.unit_code from ".$obj_client->getTableName('client_master_item')." as cm, ".$obj_client->getTableName('item')." as m, ".$obj_client->getTableName('unit')." as u WHERE cm.item_category = m.item_id AND cm.item_unit = u.unit_id AND cm.is_deleted='0' and cm.item_id = '".$obj_client->sanitize($_GET['id'])."'");
}
?>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>Item</h1></div>
		<hr class="headingborder">
		
		<?php $obj_client->showErrorMessage(); ?>
		<?php $obj_client->showSuccessMessge(); ?>
		<?php $obj_client->unsetMessage(); ?>
		
		<div class="whitebg formboxcontainer">
			<h2 class="greyheading"><?php if(isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == "editItem") { echo 'Edit Item'; } else { echo 'Add New Item'; } ?></h2>

			<form method="post" enctype="multipart/form-data" name="client-item" id='client-item'>
				<div class="row">

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label for="exampleInputITEM">Item <span class="starred">*</span></label>
						<input type="text" placeholder="Item name" name='item_name' id="item_name" data-bind="content" class="required form-control" value='<?php if(isset($_POST['item_name'])){ echo $_POST['item_name']; } else if(isset($dataArr[0]->item_name)){ echo $dataArr[0]->item_name; } ?>' />
					</div>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label for="exampleInputHSN/SAC Category">HSN/SAC Category <span class="starred">*</span></label>
						<input type="text" placeholder="Enter 3 character to search" name='item_category_name' id="item_category_name" value="<?php if(isset($dataArr[0]->category_name)){ echo $dataArr[0]->category_name; } ?>" data-bind="content" class="required form-control" />
						<input type="hidden" name='item_category' id="item_category" value="<?php if(isset($dataArr[0]->item_category)){ echo $dataArr[0]->item_category; } ?>" class="required" />
					</div>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>HSN/SAC Code</label>
						<div class="clear"></div>
						<div class="readonly-section" id="item_hsn_code"><?php if(isset($dataArr[0]->hsn_code)){ echo $dataArr[0]->hsn_code; } else { echo "HSN/SAC Code"; } ?></div>						
					</div>
					<div class="clear"></div>
                     <div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Applicable Taxes <span class="starred">*</span></label>
						<select name="is_applicable" class="required form-control">
							<option value="0" <?php if(isset($_POST['is_applicable']) && $_POST['is_applicable']==='0'){ echo 'selected="selected"'; } else if(isset($dataArr[0]->is_applicable) && $dataArr[0]->is_applicable==='0'){ echo 'selected="selected"'; } ?>>Applicable</option>
							<option value="1" <?php if(isset($_POST['is_applicable']) && $_POST['is_applicable']==='1'){ echo 'selected="selected"'; } else if(isset($dataArr[0]->is_applicable) && $dataArr[0]->is_applicable==='1'){ echo 'selected="selected"'; } ?>>Non-GST</option>
							<option value="2" <?php if(isset($_POST['is_applicable']) && $_POST['is_applicable']==='2'){ echo 'selected="selected"'; } else if(isset($dataArr[0]->is_applicable) && $dataArr[0]->is_applicable==='2'){ echo 'selected="selected"'; } ?>>Exempted</option>
						</select>
                    </div>
					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Unit Price (Rs.)</label>
						<input type="text" placeholder="Item Unit Price" name='unit_price' id="unit_price" class="requred itemUnitPrice form-control" data-bind="decimal" value='<?php if(isset($_POST['unit_price'])) { echo $_POST['unit_price']; } else if(isset($dataArr[0]->unit_price)){ echo $dataArr[0]->unit_price; } ?>'/>
					</div>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Item Unit <span class="starred">*</span></label>
						<select name="item_unit" id="item_unit" class="required form-control" data-bind="numnzero">
							<?php $dataUnitArrs = $obj_client->getUnit("unit_id,unit_name,unit_code,(case when status='1' Then 'active' when status='0' then 'deactive' end) as status", "is_deleted='0' AND status='1'"); ?>
							<?php if(!empty($dataUnitArrs)) { ?>
								<option value=''>Select Unit</option>
								<?php foreach($dataUnitArrs as $dataUnit) { ?>
									<option value='<?php echo $dataUnit->unit_id; ?>' data-unitcode="<?php echo $dataUnit->unit_code; ?>" <?php if(isset($_POST['item_unit']) && $_POST['item_unit'] === $dataUnit->unit_id){ echo 'selected="selected"'; } else if(isset($dataArr[0]->item_unit) && $dataArr[0]->item_unit === $dataUnit->unit_id) { echo 'selected="selected"'; } ?>><?php echo $dataUnit->unit_name; ?></option>
								<?php } ?>
							<?php } ?>
						</select>
					</div>
                       <div class="clear"></div>
					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Status<span class="starred">*</span></label>
						<select name="status" id="status" class="required form-control">
							<option value="1" <?php if(isset($_POST['status']) && $_POST['status'] === '1'){ echo 'selected="selected"'; } else if(isset($dataArr[0]->status) && $dataArr[0]->status === '1') { echo 'selected="selected"'; } ?>>Active</option>
							<option value="0" <?php if(isset($_POST['status']) && $_POST['status'] === '0'){ echo 'selected="selected"'; } else if(isset($dataArr[0]->status) && $dataArr[0]->status === '0') { echo 'selected="selected"'; } ?>>Inactive</option>
						</select>
					</div>
					
					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label for="itemDescription">Description</label>
						<textarea placeholder="Item Description" name='item_description' id="item_description" data-bind="content" class="form-control"><?php if(isset($_POST['item_description'])) { echo $_POST['item_description']; } else if(isset($dataArr[0]->item_description)){ echo $dataArr[0]->item_description; } ?></textarea>
					</div>
					<div class="clear height30"></div>

					<div class="adminformbxsubmit" style="width:100%;">
						<div class="tc">
							<input type='submit' class="btn btn-success" name='submit' value='<?php if(isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == "editItem") { echo 'update'; } else { echo 'submit'; } ?>' id='submit'>
							<input type="button" value="<?php echo ucfirst('Back'); ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=client_item_list"; ?>';" class="btn btn-danger" />
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<script>
    $(document).ready(function () {

		/* Get HSN/SAC Code */
        $("#item_category_name").autocomplete({
            minLength: 3,
            source: "<?php echo PROJECT_URL; ?>/?ajax=client_hsnsac_code",
            select: function( event, ui ) {
				$("#item_category").val(ui.item.item_id);
				$("#item_hsn_code").text(ui.item.hsn_code);
            }
        });
        /* End of Get HSN/SAC Code */

		/* Get on chnage of item category */
		$("#item_category_name").on("input", function() {
			$("#item_category").val("");
			$("#item_hsn_code").text("HSN/SAC Code");
		});
		/* End of on chnage of item category */

		/* validate item unit price allow only numbers or decimals */
		$(".kycmainbox").on("keypress input paste", ".itemUnitPrice", function (event) {
			return validateInvoiceAmount(event, this);
		});
		/* end of validate item unit price allow only numbers or decimals */
        
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