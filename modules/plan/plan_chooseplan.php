<?php
$obj_users = new users();
if( !isset($_SESSION['user_detail']['user_id']) || $_SESSION['user_detail']['user_id'] == '' ) {
    $obj_users->redirect(PROJECT_URL);
    exit();
}

if( isset($_GET['plan_id']) && $obj_users->validateId($_GET['plan_id']) ) {
        
    if (!isset($_SERVER['HTTP_REFERER']) || empty($_SERVER['HTTP_REFERER'])) {
        $obj_users->setError('Invalid access to files');
    } else {
        if ($obj_users->addPlanToSubscriber()) {
            $obj_users->redirect(PROJECT_URL . "?page=dashboard");
        }
    }
}
?>

<!--========================sidemenu over=========================-->
<div class="admincontainer greybg">
    <div class="formcontainer">
        
        <h1>Choose Plan</h1>
        <hr class="headingborder">
        <h2 class="greyheading">Available Plan Listing</h2>
        
        <div class="adminformbx">

            <div id="tabs">
                <?php $planCategory = $obj_users->getPlanCategory("id,name,month,description,(case when status='1' Then 'active' when status='0' then 'deactive' end) as status","is_deleted='0' AND status='1'"); ?>
                <?php if(count($planCategory) > 0) { ?>
                    <ul class="tabinner">
                        <?php foreach($planCategory as $category) { ?>
                            <li><a href="#tabs-<?php echo $category->id; ?>"><?php echo $category->name; ?></a></li>
                        <?php } ?>
                    </ul>
                
                    <?php foreach($planCategory as $category) { ?>

                        <div id="tabs-<?php echo $category->id; ?>">
                            
                            <?php $categoryPlans = $obj_users->getAllActivePlanSuAdmin("p.id,p.name,p.description,p.no_of_client,p.plan_category,p.plan_price,(case when p.status='1' Then 'active' when p.status='0' then 'deactive' end) as status","c.id='".$category->id."' and p.is_deleted='0' and p.added_by='22'",$orderby='p.id asc'); ?>
                            
                            <?php $counter = 1; ?>
                            <?php foreach ($categoryPlans as $plan ) { ?>
                                
                                <div class="columns">
                                    <ul class="price">
                                        <li class="header"><?php echo $plan->name; ?></li>
                                        <li class="grey"><?php echo '&#8377;' . $plan->plan_price; ?></li>
                                        <li><?php echo mb_strimwidth($plan->description, 0, 25, "..."); ?></li>
                                        <li><?php echo $plan->no_of_client; ?> Clients</li>
                                        <li class="grey"><a href="<?php echo PROJECT_URL . "?page=plan_chooseplan&plan_id=" . $plan->id; ?>" class="button">Subscribe Now</a></li>
                                    </ul>
                                </div>
                                
                                <?php if($counter % 4 == 0) { ?>
                                    <div class="clear"></div>
                                <?php } ?>
                                
                                <?php $counter++; ?>
                            <?php } ?>
                                    
                            <div class="clear"></div>

                        </div>
                
                    <?php } ?>
                
                <?php } ?>
                
            </div>
            
        </div>
<!--========================adminformbox over=========================-->    
    </div>
<!--========================admincontainer over=========================-->
</div>
<script>
    $(document).ready(function () {
        $( "#tabs" ).tabs();
    });
</script>