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
                <h2 class="greyheading"><?php echo isset($_GET['id']) ? 'Edit Plan period' : 'Add New Plan'; ?></h2>
    
        <div class="clear"></div>

        <form name="edit-plan-category" id="edit-plan-category" method="POST">
                <div class="row">
                     
						 <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                                                   
                          <input type="hidden" name="ecatid" id="ecatid" value="<?php echo $planCategoryData->id; ?>">
                        
                            <label>Name<span class="starred">*</span></label>
                            <input type="text" name="category_name" value="<?php echo $planCategoryData->name; ?>" class="required form-control" data-bind="content" id="category_name" placeholder="Enter plan category name" />
                        </div>
                      <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                                               
                            
                            <label>Month<span class="starred">*</span></label>
                            <select name="category_month" id="category_month" class="required form-control" data-bind="number">
                                <option value="">Select Month</option>
                                <?php for($m=1; $m <= 12; $m++) { ?>
                                    
                                    <?php if( $planCategoryData->month == $m ) { ?>
                                        <option selected="selected" value="<?php echo $m; ?>"><?php echo $m; ?></option>
                                    <?php } else { ?>
                                        <option value="<?php echo $m; ?>"><?php echo $m; ?></option>
                                    <?php } ?>
                                    
                                <?php } ?>                             
                            </select>
                        </div>
                                        
                             <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                                                   
                            <label>Description<span class="starred">*</span></label>
                            <textarea name="category_description" id="category_description" class="required form-control" data-bind="content" placeholder="Enter plan category description"><?php echo $planCategoryData->description; ?></textarea>
                        </div>
						<div class="clear"></div>
					  <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                           
                            <label>Status<span class="starred">*</span></label>
                            <div class="clear"></div>
                            <input type="radio" name="plan_category_status" <?php if($planCategoryData->status == '1') { echo 'checked="checked"'; } ?> value="1" /><span>Active</span> <input type="radio" name="plan_category_status" <?php if($planCategoryData->status == '0') { echo 'checked="checked"'; } ?> value="0" /><span>Inactive</span>
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
                </div>
                   
                </div>
      
                   
                </div>
                   

            </form>
       


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