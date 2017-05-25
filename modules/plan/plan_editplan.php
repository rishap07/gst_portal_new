<?php
$obj_plan = new plan();
if( !isset($_SESSION['user_detail']['user_id']) || $_SESSION['user_detail']['user_id'] == '' ) {
    $obj_plan->redirect(PROJECT_URL);
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

<!--========================sidemenu over=========================-->
<div class="admincontainer greybg">
    <div class="formcontainer">
        
        <?php $obj_plan->showErrorMessage(); ?>
        <?php $obj_plan->showSuccessMessge(); ?>
        <?php $obj_plan->unsetMessage(); ?>

        <h1>Add Plan</h1>
        <hr class="headingborder">
        <div class="clear"></div>

        <form name="edit-plan" id="edit-plan" method="POST">

            <div class="adminformbx">

                <div class="kycform">

                    <div class="kycmainbox">
                        
                        <input type="hidden" name="eplanid" id="eplanid" value="<?php echo $planData->id; ?>">

                        <div class="formcol">
                            <label>Name<span class="starred">*</span></label>
                            <input type="text" name="plan_name" id="plan_name" placeholder="Enter plan name" class="required" data-bind="content" value="<?php echo isset($planData->name) ?  $planData->name : ''; ?>"/>
                        </div>
                        
                        <div class="formcol two" style="min-height:auto;">
                            <label>Description<span class="starred">*</span></label>
                            <textarea name="plan_description" id="plan_description" placeholder="Enter plan description" class="required" data-bind="content"><?php echo isset($planData->description) ?  $planData->description : ''; ?></textarea>
                        </div>

                        <div class="formcol third">
                            <label>No Of Client<span class="starred">*</span></label>
                            <input type="text" name="no_of_client" id="no_of_client" placeholder="Enter no of client" class="required" data-bind="number" value="<?php echo isset($planData->no_of_client) ?  $planData->no_of_client : ''; ?>" />
                        </div>
                        <div class="clear"></div>
                        
                        <?php $allPlanCategories = $obj_plan->getPlanCategories(); ?>
                        <div class="formcol" style="min-height:auto;">
                            <label>Plan Period<span class="starred">*</span></label>
                            <select name="plan_period" id="plan_period" class="required" data-bind="number">
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

                        <div class="formcol two">
                            <label>Plan Price<span class="starred">*</span></label>
                            <input type="text" name="plan_price" id="plan_price" placeholder="Enter plan price" class="required" data-bind="decimal" value="<?php echo isset($planData->plan_price) ? $planData->plan_price : ''; ?>" />
                        </div>
                        
                        <div class="formcol third">
                            <label>Visibility<span class="starred">*</span></label>
                            <div class="clear"></div>
                            <input type="radio" name="plan_visibility" <?php if($planData->visible == '1') { echo 'checked="checked"'; } ?> value="1" /><span>Yes</span> <input type="radio" name="plan_visibility" <?php if($planData->visible == '0') { echo 'checked="checked"'; } ?> value="0" /><span>No</span>
                        </div>
                        <div class="clear"></div>

                        <div class="formcol">
                            <label>Status<span class="starred">*</span></label>
                            <div class="clear"></div>
                            <input type="radio" name="plan_status" <?php if($planData->status == '1') { echo 'checked="checked"'; } ?> value="1" /><span>Active</span> <input type="radio" name="plan_status" <?php if($planData->status == '0') { echo 'checked="checked"'; } ?> value="0" /><span>Inactive</span>
                        </div>
                        <div class="clear"></div>

                        <div class="clear height10"></div>

                        <div class="tc">
                            <input type="submit" name="submit_edit_plan" id="submit_edit_plan" value="SUBMIT" class="btn orangebg">
                            <input type="button" value="<?php echo ucfirst('Back'); ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=plan_list"; ?>';" class="btn redbg" class="redbtn marlef10"/>
                            <div class="clear height20"></div>
                        </div>

                    </div>

                </div>

            </div>

        </form>
<!--========================adminformbox over=========================-->    
    </div>
<!--========================admincontainer over=========================-->
</div>
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