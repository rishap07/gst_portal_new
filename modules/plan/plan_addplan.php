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
                            
                        
                            <label>Name<span class="starred">*</span></label>
                            <input type="text" name="plan_name" id="plan_name" placeholder="Enter plan name" class="required form-control" data-bind="content" value="<?php echo isset($_POST['plan_name']) ? $_POST['plan_name'] : ''; ?>" />
                        </div>
                      <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                                               
                         
                            <label>Description<span class="starred">*</span></label>
                            <textarea name="plan_description" id="plan_description" placeholder="Enter plan description" class="required form-control" data-bind="content"><?php echo isset($_POST['plan_description']) ? $_POST['plan_description'] : ''; ?></textarea>
                        </div>

                             <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                      
                            <label>No Of Client<span class="starred">*</span></label>
                            <input type="text" name="no_of_client" id="no_of_client" placeholder="Enter no of client" class="required form-control" data-bind="number" value="<?php echo isset($_POST['no_of_client']) ? $_POST['no_of_client'] : ''; ?>" />
                        </div>
						<div class="clear"></div>
					 
                            <?php $allPlanCategories = $obj_plan->getPlanCategories(); ?>
                          <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                          
                            <label>Plan Period<span class="starred">*</span></label>
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
                           
                            <label>Plan Price<span class="starred">*</span></label>
                            <input type="text" name="plan_price" id="plan_price" placeholder="Enter plan price" class="required form-control" data-bind="decimal" value="<?php echo isset($_POST['plan_price']) ? $_POST['plan_price'] : ''; ?>" required/>
                        </div>
						      <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                            
                            <label>Visibility<span class="starred">*</span></label>
                            <div class="clear"></div>
                            <input type="radio" name="plan_visibility" checked="checked" value="1" /><span>Yes</span> <input type="radio" name="plan_visibility" value="0" /><span>No</span>
                        </div>
						 <div class="clear"></div>

                       <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                          
                            <label>Status<span class="starred">*</span></label>
                            <div class="clear"></div>
                            <input type="radio" name="plan_status" checked="checked" value="1" /><span>Active</span> <input type="radio" name="plan_status" value="0" /><span>Inactive</span>
                        </div>
              <div class="clear"></div>
						 <div class="adminformbxsubmit" style="width:100%;">
                             
						 
						 
						    <div class="tc">
                            <input type="submit" name="submit_add_plan" id="submit_add_plan" value="SUBMIT" class="btn btn-danger">
                            <div class="clear height20"></div>
                        </div>
						

                        </div>
							
                        </div>
                </div>
                   
                </div>
      
                        </form>
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