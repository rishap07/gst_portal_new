<?php
$obj_plan = new plan();

if(!$obj_plan->can_read('plan_category_list')) {

    $obj_plan->setError($obj_plan->getValMsg('can_read'));
    $obj_plan->redirect(PROJECT_URL."/?page=dashboard");
    exit();
}

if(!$obj_plan->can_update('plan_category_list')) {

    $obj_plan->setError($obj_plan->getValMsg('can_update'));
    $obj_plan->redirect(PROJECT_URL."/?page=plan_categorylist");
    exit();
}

if( isset($_GET['action']) && $_GET['action'] == 'editPlanCategory' && isset($_GET['id']) && intval($_GET['id']) > 0) {
    
    $categoryid = $_GET['id'];
    $planCategoryDetail = $obj_plan->getPlanCategoryDetails($categoryid);
    
    if( $planCategoryDetail['status'] == "success" ) {
        $planCategoryData = $planCategoryDetail['data'];
    } else {
        $obj_plan->setError($planCategoryDetail['message']);
        $obj_plan->redirect(PROJECT_URL."?page=plan_categorylist");
    }

} else {
    $obj_plan->redirect(PROJECT_URL."?page=plan_categorylist");
}

if( isset($_POST['edit_plan_category']) && $_POST['edit_plan_category'] == 'SUBMIT' ) {

    if(!isset($_SERVER['HTTP_REFERER']) || empty($_SERVER['HTTP_REFERER'])){
        
        $obj_plan->setError('Invalid access to files');
    } else {

        if($obj_plan->editPlanCategory()){
            $obj_plan->redirect(PROJECT_URL."?page=plan_categorylist");
        }
    }
}
?>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
	<div class="col-md-12 col-sm-12 col-xs-12">

		<div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>Plan</h1></div>
		<hr class="headingborder">
		<?php $obj_plan->showErrorMessage(); ?>
		<?php $obj_plan->showSuccessMessge(); ?>
		<?php $obj_plan->unsetMessage(); ?>
		<div class="clear"></div>

		<div class="whitebg formboxcontainer">
			<h2 class="greyheading">Edit Plan Category</h2>
			<div class="clear"></div>
			<form name="edit-plan-category" id="edit-plan-category" method="POST">
				<div class="row">

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<input type="hidden" name="ecatid" id="ecatid" value="<?php echo $planCategoryData->id; ?>">
						<label>Name<span class="starred">*</span></label>
						<input type="text" name="category_name" value="<?php echo $planCategoryData->name; ?>" class="required form-control" data-bind="content" id="category_name" placeholder="Enter plan category name" />
					</div>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Description<span class="starred">*</span></label>
						<textarea name="category_description" id="category_description" class="required form-control" data-bind="content" placeholder="Enter plan category description"><?php echo $planCategoryData->description; ?></textarea>
					</div>
					
					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Status<span class="starred">*</span></label>
						<select name="plan_category_status" class="form-control">
							<option value="1" <?php if(isset($_POST['status']) && $_POST['status'] === '1'){ echo 'selected="selected"'; } else if(isset($planCategoryData->status) && $planCategoryData->status === '1'){ echo 'selected="selected"'; } ?>>Active</option>
							<option value="0" <?php if(isset($_POST['status']) && $_POST['status'] === '0'){ echo 'selected="selected"'; } else if(isset($planCategoryData->status) && $planCategoryData->status === '0'){ echo 'selected="selected"'; } ?>>In-Active</option>
						</select>
					</div>

					<div class="clear"></div>
					<div class="adminformbxsubmit" style="width:100%;">
						<div class="tc">
							<input type="submit" name="edit_plan_category" id="edit_plan_category" value="SUBMIT" class="btn btn-danger">
							<input type="button" value="<?php echo ucfirst('Back'); ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=plan_categorylist"; ?>';" class="btn btn-danger" />
							<div class="clear height20"></div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<script>
    $(document).ready(function () {
        $('#edit_plan_category').click(function () {
            var mesg = {};
            if (vali.validate(mesg,'edit-plan-category')) {
                return true;
            }
            return false;
        });
    });
</script>