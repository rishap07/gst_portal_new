<?php
$obj_plan = new plan();
if( !isset($_SESSION['user_detail']['user_id']) || $_SESSION['user_detail']['user_id'] == '' ) {
    $obj_plan->redirect(PROJECT_URL);
    exit();
}

if(!$obj_plan->can_read('plan_category_list')) {

    $obj_plan->setError($obj_plan->getValMsg('can_read'));
    $obj_plan->redirect(PROJECT_URL."/?page=dashboard");
    exit();
}

if(!$obj_plan->can_create('plan_category_list')) {

    $obj_plan->setError($obj_plan->getValMsg('can_create'));
    $obj_plan->redirect(PROJECT_URL."/?page=plan_categorylist");
    exit();
}

if( isset($_POST['submit_plan_category']) && $_POST['submit_plan_category'] == 'SUBMIT' ) {

    if(!isset($_SERVER['HTTP_REFERER']) || empty($_SERVER['HTTP_REFERER'])){
        
        $obj_plan->setError('Invalid access to files');
    } else {

        if($obj_plan->addPlanCategory()){

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
			<h2 class="greyheading">Add Plan Category</h2>
			<div class="clear"></div>

			<form name="add-plan-category" id="add-plan-category" method="POST">
				<div class="row">

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Name<span class="starred">*</span></label>
						<input type="text" name="category_name" id="category_name" class="required form-control" data-bind="content" placeholder="Enter plan category name" />
					</div>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Description<span class="starred">*</span></label>
						<textarea name="category_description" class="required form-control" data-bind="content" id="category_description" placeholder="Enter plan category description"></textarea>
					</div>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Status<span class="starred">*</span></label>
						<select name="plan_category_status" class="form-control">
							<option value="1">Active</option>
							<option value="0">In-Active</option>
						</select>
					</div>
					<div class="clear"></div>
					<div class="adminformbxsubmit" style="width:100%;">
						<div class="tc">
							<input type="submit" name="submit_plan_category" id="submit_plan_category" value="SUBMIT" class="btn btn-success">
							<input type="button" value="<?php echo ucfirst('Back'); ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=plan_categorylist"; ?>';" class="btn btn-danger" class="redbtn marlef10"/>
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
        $('#submit_plan_category').click(function () {
            var mesg = {};
            if (vali.validate(mesg,'add-plan-category')) {
                return true;
            }
            return false;
        });
    });
</script>