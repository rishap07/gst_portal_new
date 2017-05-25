<?php
$obj_plan = new plan();
if( !isset($_SESSION['user_detail']['user_id']) || $_SESSION['user_detail']['user_id'] == '' ) {
    $obj_plan->redirect(PROJECT_URL);
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

<!--========================sidemenu over=========================-->
<div class="admincontainer greybg">
    <div class="formcontainer">
        
        <?php $obj_plan->showErrorMessage(); ?>
        <?php $obj_plan->showSuccessMessge(); ?>
        <?php $obj_plan->unsetMessage(); ?>

        <h1>Add Plan</h1>
        <hr class="headingborder">
        <div class="clear"></div>

        <form name="add-plan" id="add-plan" method="POST">

            <div class="adminformbx">

                <div class="kycform">

                    <div class="kycmainbox">

                        <div class="formcol">
                            <label>Name<span class="starred">*</span></label>
                            <input type="text" name="plan_name" id="plan_name" placeholder="Enter plan name" class="required" data-bind="content" value="<?php echo isset($_POST['plan_name']) ? $_POST['plan_name'] : ''; ?>" />
                        </div>
                        
                        <div class="formcol two" style="min-height:auto;">
                            <label>Description<span class="starred">*</span></label>
                            <textarea name="plan_description" id="plan_description" placeholder="Enter plan description" class="required" data-bind="content"><?php echo isset($_POST['plan_description']) ? $_POST['plan_description'] : ''; ?></textarea>
                        </div>

                        <div class="formcol third">
                            <label>No Of Client<span class="starred">*</span></label>
                            <input type="text" name="no_of_client" id="no_of_client" placeholder="Enter no of client" class="required" data-bind="number" value="<?php echo isset($_POST['no_of_client']) ? $_POST['no_of_client'] : ''; ?>" />
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
                                            echo '<option value="'.$category->id.'">'.$category->name.'</option>';
                                        }
                                    }
                                ?>
                            </select>
                        </div>

                        <div class="formcol two">
                            <label>Plan Price<span class="starred">*</span></label>
                            <input type="text" name="plan_price" id="plan_price" placeholder="Enter plan price" class="required" data-bind="decimal" value="<?php echo isset($_POST['plan_price']) ? $_POST['plan_price'] : ''; ?>" required/>
                        </div>
                        
                        <div class="formcol third">
                            <label>Visibility<span class="starred">*</span></label>
                            <div class="clear"></div>
                            <input type="radio" name="plan_visibility" checked="checked" value="1" /><span>Yes</span> <input type="radio" name="plan_visibility" value="0" /><span>No</span>
                        </div>
                        <div class="clear"></div>

                        <div class="formcol">
                            <label>Status<span class="starred">*</span></label>
                            <div class="clear"></div>
                            <input type="radio" name="plan_status" checked="checked" value="1" /><span>Active</span> <input type="radio" name="plan_status" value="0" /><span>Inactive</span>
                        </div>
                        <div class="clear"></div>

                        <div class="clear height10"></div>

                        <div class="tc">
                            <input type="submit" name="submit_add_plan" id="submit_add_plan" value="SUBMIT" class="btn orangebg">
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
        $('#submit_add_plan').click(function () {
            var mesg = {};
            if (vali.validate(mesg,'add-plan')) {
                return true;
            }
            return false;
        });
    });
</script>