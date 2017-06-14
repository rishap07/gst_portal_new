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

<!--========================sidemenu over=========================-->
<div class="admincontainer greybg">
    <div class="formcontainer">
        
        <?php $obj_plan->showErrorMessage(); ?>
        <?php $obj_plan->showSuccessMessge(); ?>
        <?php $obj_plan->unsetMessage(); ?>

        <h1>Add Plan Category</h1>
        <hr class="headingborder">
        <div class="clear"></div>

        <form name="add-plan-category" id="add-plan-category" method="POST">

            <div class="adminformbx">

                <div class="kycform">

                    <div class="kycmainbox">

                        <div class="formcol">
                            <label>Name<span class="starred">*</span></label>
                            <input type="text" name="category_name" id="category_name" class="required" data-bind="content" placeholder="Enter plan category name" />
                        </div>

                        <div class="formcol two">
                            <label>Month<span class="starred">*</span></label>
                            <select name="category_month" id="category_month" class="required" data-bind="number">
                                <option value="">Select Month</option>
                                <?php for($m=1; $m <= 12; $m++) { ?>             
                                    <option value="<?php echo $m; ?>"><?php echo $m; ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="formcol third" style="min-height:auto;">
                            <label>Description<span class="starred">*</span></label>
                            <textarea name="category_description" class="required" data-bind="content" id="category_description" placeholder="Enter plan category description"></textarea>
                        </div>
                        <div class="clear"></div>

                        <div class="formcol">
                            <label>Status<span class="starred">*</span></label>
                            <div class="clear"></div>
                            <input type="radio" name="plan_category_status" checked="checked" value="1" /><span>Active</span> <input type="radio" name="plan_category_status" value="0" /><span>Inactive</span>
                        </div>
                        <div class="clear"></div>

                        <div class="clear height10"></div>

                        <div class="tc">
                            <input type="submit" name="submit_plan_category" id="submit_plan_category" value="SUBMIT" class="btn orangebg">
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
        $('#submit_plan_category').click(function () {
            var mesg = {};
            if (vali.validate(mesg,'add-plan-category')) {
                return true;
            }
            return false;
        });
    });
</script>