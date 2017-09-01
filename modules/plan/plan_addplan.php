<?php
$obj_plan = new plan();
if( !isset($_SESSION['user_detail']['user_id']) || $_SESSION['user_detail']['user_id'] == '' ) {
    $obj_plan->redirect(PROJECT_URL);
    exit();
}

if(!$obj_plan->can_read('plan_list')) {

    $obj_plan->setError($obj_plan->getValMsg('can_read'));
    $obj_plan->redirect(PROJECT_URL."/?page=dashboard");
    exit();
}

if(!$obj_plan->can_create('plan_list')) {

    $obj_plan->setError($obj_plan->getValMsg('can_create'));
    $obj_plan->redirect(PROJECT_URL."/?page=plan_list");
    exit();
}

if( isset($_POST['submit_add_plan']) && $_POST['submit_add_plan'] == 'SUBMIT' ) {

    if(!isset($_SERVER['HTTP_REFERER']) || empty($_SERVER['HTTP_REFERER'])){
        
        $obj_plan->setError('Invalid access to files');
    } else {

        if($obj_plan->addPlan()){
            $obj_plan->redirect(PROJECT_URL."?page=plan_addplan");
        }
    }
}
?>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
	<div class="col-md-12 col-sm-12 col-xs-12">

		<div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>Plan</h1></div>
		<hr class="headingborder">
		<div class="clear"></div>
		<?php $obj_plan->showErrorMessage(); ?>
		<?php $obj_plan->showSuccessMessge(); ?>
		<?php $obj_plan->unsetMessage(); ?>

		<div class="whitebg formboxcontainer">
			<h2 class="greyheading">Add Plan</h2>
			<div class="clear"></div>

			<form name="add-plan" id="add-plan" method="POST">
				<div class="row">

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Name <span class="starred">*</span></label>
						<input type="text" name="plan_name" id="plan_name" placeholder="Enter plan name" class="required form-control" data-bind="content" value="<?php echo isset($_POST['plan_name']) ? $_POST['plan_name'] : ''; ?>" />
					</div>
				
					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Description <span class="starred">*</span></label>
						<textarea name="plan_description" id="plan_description" placeholder="Enter plan description" class="required form-control" data-bind="content"><?php echo isset($_POST['plan_description']) ? $_POST['plan_description'] : ''; ?></textarea>
					</div>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>No Of Client <span class="starred">*</span></label>
						<input type="text" name="no_of_client" id="no_of_client" placeholder="Enter no of client" class="required form-control" data-bind="number" value="<?php echo isset($_POST['no_of_client']) ? $_POST['no_of_client'] : ''; ?>" />
					</div>
					<div class="clear"></div>

					<?php $allPlanCategories = $obj_plan->getPlanCategories(); ?>
					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Plan Category <span class="starred">*</span></label>
						<select name="plan_period" id="plan_period" class="required form-control" data-bind="number">
							<option value="">Select Plan</option>
							<?php
								if( $allPlanCategories['status'] == "success" ) {
									foreach($allPlanCategories['data'] as $category) {
										echo '<option value="'.$category->id.'">'.$category->name.'</option>';
									}
								}
							?>
						</select>
					</div>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Plan Price <span class="starred">*</span></label>
						<input type="text" name="plan_price" id="plan_price" placeholder="Enter plan price" class="required form-control" data-bind="decimal" value="<?php echo isset($_POST['plan_price']) ? $_POST['plan_price'] : ''; ?>" />
					</div>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Visibility <span class="starred">*</span></label>
						<select name="plan_visibility" id="plan_visibility" class="form-control">
							<option value="1">Yes</option>
							<option value="0">No</option>
						</select>
					</div>
					<div class="clear"></div>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>No.of Sub Users <span class="starred">*</span></label>
						<input type="text" name="sub_user" id="sub_user" placeholder="Enter no of Sub Users" class="required form-control" data-bind="number" value="<?php echo isset($_POST['sub_user']) ? $_POST['sub_user'] : ''; ?>" />
					</div>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>No.of PAN <span class="starred">*</span></label>
						<input type="text" name="pan_num" id="pan_num" placeholder="Enter no of Pan" class="required form-control" data-bind="number" value="<?php echo isset($_POST['pan_num']) ? $_POST['pan_num'] : ''; ?>" />
					</div>
				
					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>No.of company <span class="starred">*</span></label>
						<input type="text" name="company_no" id="company_no" placeholder="Enter no of company" class="required form-control" data-bind="number" value="<?php echo isset($_POST['company_no']) ? $_POST['company_no'] : ''; ?>" />
					</div>
				
					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Support <span class="starred">*</span></label>
						<input type="text" name="support" id="support" placeholder="support type" class="required form-control" value="<?php echo isset($_POST['support']) ? $_POST['support'] : ''; ?>" />
					</div>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label> Cloud Stograge(GB) <span class="starred">*</span></label>
						<input type="text" name="cloud_storage_gb" id="cloud_storage_gb" placeholder="Cloud Stograge(GB)" class="required form-control"  value="<?php echo isset($_POST['cloud_storage_gb']) ? $_POST['cloud_storage_gb'] : ''; ?>" />
					</div>
				
					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>GSt Expert Help <span class="starred">*</span></label>
						<input type="text" name="gst_expert_help" id="gst_expert_help" placeholder="GSt Expert Help" class="required form-control"  value="<?php echo isset($_POST['gst_expert_help']) ? $_POST['gst_expert_help'] : ''; ?>" />
					</div>
					
					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Status <span class="starred">*</span></label>
						<select name="plan_status" id="plan_status" class="form-control">
							<option value="1">Active</option>
							<option value="0">In-Active</option>
						</select>
					</div>

					<div class="clear"></div>
					<div class="adminformbxsubmit" style="width:100%;">
						<div class="tc">
							<input type="submit" name="submit_add_plan" id="submit_add_plan" value="SUBMIT" class="btn btn-success">
							<input type="button" value="<?php echo ucfirst('Back'); ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=plan_list"; ?>';" class="btn btn-danger"/>
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
        $('#submit_add_plan').click(function () {
            var mesg = {};
            if (vali.validate(mesg,'add-plan')) {
                return true;
            }
            return false;
        });
    });
</script>