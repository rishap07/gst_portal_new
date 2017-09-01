<?php
$obj_plan = new plan();

if(!$obj_plan->can_read('plan_list')) {

    $obj_plan->setError($obj_plan->getValMsg('can_read'));
    $obj_plan->redirect(PROJECT_URL."/?page=dashboard");
    exit();
}

if(!$obj_plan->can_update('plan_list')) {

    $obj_plan->setError($obj_plan->getValMsg('can_update'));
    $obj_plan->redirect(PROJECT_URL."/?page=plan_list");
    exit();
}

if( isset($_GET['action']) && $_GET['action'] == 'editPlan' && isset($_GET['id']) && intval($_GET['id']) > 0) {
    
    $planid = $_GET['id'];
    $planDetail = $obj_plan->getPlanDetails($planid);
    
    if( $planDetail['status'] == "success" ) {
        $planData = $planDetail['data'];
    } else {
        $obj_plan->setError($planDetail['message']);
        $obj_plan->redirect(PROJECT_URL."?page=plan_list");
    }

} else {
    $obj_plan->redirect(PROJECT_URL."?page=plan_list");
}

if( isset($_POST['submit_edit_plan']) && $_POST['submit_edit_plan'] == 'SUBMIT' ) {

    if(!isset($_SERVER['HTTP_REFERER']) || empty($_SERVER['HTTP_REFERER'])){
        
        $obj_plan->setError('Invalid access to files');
    } else {

        if($obj_plan->editPlan()){
            $obj_plan->redirect(PROJECT_URL."?page=plan_list");
        }
    }
}
?>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
	<div class="col-md-12 col-sm-12 col-xs-12">

		<div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>Edit plan</h1></div>
		<hr class="headingborder">
		<?php $obj_plan->showErrorMessage(); ?>
		<?php $obj_plan->showSuccessMessge(); ?>
		<?php $obj_plan->unsetMessage(); ?>
		<div class="clear"></div>

		<div class="whitebg formboxcontainer">
			<h2>Edit Plan Period</h2>
			<div class="clear"></div>

			<form name="edit-plan" id="edit-plan" method="POST">
				<div class="row">

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<input type="hidden" name="eplanid" id="eplanid" value="<?php echo $planData->id; ?>">
						<label>Name<span class="starred">*</span></label>
						<input type="text" name="plan_name" id="plan_name" placeholder="Enter plan name" class="required form-control" data-bind="content" value="<?php echo isset($planData->name) ?  $planData->name : ''; ?>"/>
					</div>
				
					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Description<span class="starred">*</span></label>
						<textarea name="plan_description" id="plan_description" placeholder="Enter plan description" class="required form-control" data-bind="content"><?php echo isset($planData->description) ?  $planData->description : ''; ?></textarea>
					</div>
				
					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>No Of Client<span class="starred">*</span></label>
						<input type="text" name="no_of_client" id="no_of_client" placeholder="Enter no of client" class="required form-control" data-bind="number" value="<?php echo isset($planData->no_of_client) ?  $planData->no_of_client : ''; ?>" />
					</div>
					<div class="clear"></div>
				
					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<?php $allPlanCategories = $obj_plan->getPlanCategories(); ?>
						<label>Plan Category <span class="starred">*</span></label>
						<select name="plan_period" id="plan_period" class="required form-control" data-bind="number">
							<option value="">Select Plan</option>
							<?php
								if( $allPlanCategories['status'] == "success" ) {
									foreach($allPlanCategories['data'] as $category) {

										if($planData->plan_category == $category->id) {
											echo '<option selected="selected" value="'.$category->id.'">'.$category->name.'</option>';
										} else {
											echo '<option value="'.$category->id.'">'.$category->name.'</option>';
										}
									}
								}
							?>
						</select>
					</div>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Plan Price <span class="starred">*</span></label>
						<input type="text" name="plan_price" id="plan_price" placeholder="Enter plan price" class="required form-control" data-bind="decimal" value="<?php echo isset($planData->plan_price) ? $planData->plan_price : ''; ?>" />
					</div>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Visibility <span class="starred">*</span></label>
						<select name="plan_visibility" id="plan_visibility" class="form-control">
							<option value="1" <?php if(isset($_POST['plan_visibility']) && $_POST['plan_visibility'] === '1'){ echo 'selected="selected"'; } else if(isset($planData->visible) && $planData->visible==='1'){ echo 'selected="selected"'; } ?>>Yes</option>
							<option value="0" <?php if(isset($_POST['plan_visibility']) && $_POST['plan_visibility'] === '0'){ echo 'selected="selected"'; } else if(isset($planData->visible) && $planData->visible==='0'){ echo 'selected="selected"'; } ?>>No</option>
						</select>
					</div>
					<div class="clear"></div>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>No.of Sub Users <span class="starred">*</span></label>
						<input type="text" name="sub_user" id="sub_user" placeholder="Enter no of Sub Users" class="required form-control" data-bind="number" value="<?php echo isset($planData->sub_user) ? $planData->sub_user : ''; ?>" />
					</div>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>No.of PAN<span class="starred">*</span></label>
						<input type="text" name="pan_num" id="pan_num" placeholder="Enter no of Pan" class="required form-control" data-bind="number" value="<?php echo isset($planData->pan_num) ? $planData->pan_num : ''; ?>" />
					</div>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>No.of company <span class="starred">*</span></label>
						<input type="text" name="company_no" id="company_no" placeholder="Enter no of company" class="required form-control" data-bind="number" value="<?php echo isset($planData->company_no) ? $planData->company_no : ''; ?>" />
					</div>
				
					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Support <span class="starred">*</span></label>
						<input type="text" name="support" id="support" placeholder="support type" class="required form-control" value="<?php echo isset($planData->support) ? $planData->support : ''; ?>" />
					</div>
				
					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Cloud Stograge(GB) <span class="starred">*</span></label>
						<input type="text" name="cloud_storage_gb" id="cloud_storage_gb" placeholder="Cloud Stograge(gb)" class="required form-control"  value="<?php echo isset($planData->cloud_storage_gb) ? $planData->cloud_storage_gb : ''; ?>" />
					</div>
				
					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>GSt Expert Help <span class="starred">*</span></label>
						<input type="text" name="gst_expert_help" id="gst_expert_help" placeholder="GSt Expert Help" class="required form-control"  value="<?php echo isset($planData->gst_expert_help) ? $planData->gst_expert_help : ''; ?>" />
					</div>
				
					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Status<span class="starred">*</span></label>
						<select name="plan_status" id="plan_status" class="form-control">
							<option value="1" <?php if(isset($_POST['plan_status']) && $_POST['plan_status'] === '1'){ echo 'selected="selected"'; } else if(isset($planData->status) && $planData->status==='1'){ echo 'selected="selected"'; } ?>>Active</option>
							<option value="0" <?php if(isset($_POST['plan_status']) && $_POST['plan_status'] === '0'){ echo 'selected="selected"'; } else if(isset($planData->status) && $planData->status==='0'){ echo 'selected="selected"'; } ?>>In-Active</option>
						</select>
					</div>
					<div class="clear"></div>

					<div class="adminformbxsubmit" style="width:100%;">
						<div class="tc">
							<input type="submit" name="submit_edit_plan" id="submit_edit_plan" value="SUBMIT" class="btn btn-danger">
							<input type="button" value="<?php echo ucfirst('Back'); ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=plan_list"; ?>';" class="btn btn-danger" />
							<div class="clear height20"></div>
						</div>
					</div>

				</div>
			</form>

		</div>
	</div>
</div>
<!--========================sidemenu over=========================-->
<script>
    $(document).ready(function () {
        $('#submit_edit_plan').click(function () {
            var mesg = {};
            if (vali.validate(mesg,'edit-plan')) {
                return true;
            }
            return false;
        });
    });
</script>