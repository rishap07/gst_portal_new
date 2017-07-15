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
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr">
        <div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>Choose Plan</h1></div>
        <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="adminformbx">
 <!--<div class="" style="color:#333;">Available Plan Listing</div>-->
            <div id="tabs">
                <?php $planCategory = $obj_users->getPlanCategory("id,name,description,(case when status='1' Then 'active' when status='0' then 'deactive' end) as status","is_deleted='0' AND status='1'"); 
                if(count($planCategory) > 0) { ?>
                	<div class="text-center">
                    <ul class="tabinner">
                        <?php foreach($planCategory as $category) { ?>
                            <li><a href="#tabs-<?php echo $category->id; ?>"><?php echo $category->name; ?></a></li>
                        <?php } ?>
                    </ul>
                    </div>
                    
                    <?php foreach($planCategory as $category) { 
                        if($category->id!=3)
                        {
                        ?>

                        <div id="tabs-<?php echo $category->id; ?>">
                            <!--<div class="col-md-3">
                                <ul class="price">
                                    <li class="header">Features/Plan</li>
                                    <li class="grey">Price</li>
                                    <li>GSTIN's</li>
                                    <li>No of Companies</li>
                                    <li>No of PAN's</li>
                                    <li>No of Invoices</li>
                                    <li>Reconciliation</li>
                                    <li>Returns</li>
                                    <li>24/7 Support</li>
                                    <li>Period of Service</li>
                                    <li>Web/Mobile App</li>
                                    <li>e-Filing</li>
                                    <li>Excel Tool</li>
                                    <li>Cloud Storage/GB</li>
                                    <li>GST Expert Help</li>
                                    
                                    <li>Purchase</li>
                                </ul>
                            </div>-->
                            <?php
                            
                            $categoryPlans = $obj_users->getAllActivePlanSuAdmin("p.id,p.name,p.description,p.no_of_client,p.plan_category,p.plan_price,p.company_no,p.pan_num,p.invoice_num,p.reconciliation,p.returns,p.support,p.period_of_service,p.web_mobile_app,p.e_filing,p.excel_tool,p.cloud_storage_gb,p.gst_expert_help","c.id='".$category->id."' and p.is_deleted='0' and p.added_by='22'",$orderby='p.id asc'); ?>
                            
                            <?php $counter = 1; ?>
                            
                            <?php foreach ($categoryPlans as $plan ) { ?>
                                
                                <div class="col-md-4">
                                <div class="pricecol text-center">
                                <div class="header"><?php echo $plan->name; ?><br />
                                <div class="planprice"><i class="fa fa-inr" aria-hidden="true"></i><?php echo $plan->plan_price; ?></div>
                                </div>
                                    <ul class="pricelist">
                                        
                                        <li><span>GSTIN's</span> <?php echo $plan->no_of_client; ?></li>
                                        <li><span>No of Companies</span> <?php echo $plan->company_no; ?></li>
                                        <li><span>No of PAN's</span> <?php echo $plan->pan_num; ?></li>
                                        <li><span>No of Invoices</span> <?php echo $plan->invoice_num; ?></li>
                                        <li><span>Reconciliation</span> <?php echo $plan->reconciliation; ?></li>
                                        <li><span>Returns</span> <?php echo $plan->returns; ?></li>
                                        <li><span>24/7 Support</span> <?php echo $plan->support; ?></li>
                                        <li><span>Period of Service</span> <?php echo $plan->period_of_service; ?></li>
                                        <li><span>Web/Mobile App</span> <?php echo $plan->web_mobile_app; ?></li>
                                        <li><span>e-Filing</span> <?php echo $plan->e_filing; ?></li>
                                        <li><span>Excel Tool</span> <?php echo $plan->excel_tool; ?></li>
                                        <li><span>Cloud Storage/GB</span> <?php echo $plan->cloud_storage_gb; ?></li>
                                        <li class="last"><span>GST Expert Help</span> <?php echo $plan->gst_expert_help; ?></li>
                                        
                                    </ul>
                                    <div class="text-center btnmargin"><a href="<?php echo PROJECT_URL . "?page=plan_chooseplan&plan_id=" . $plan->id; ?>" class="btnsubcribe">Subscribe Now</a></div>
                                    </div>
                                </div>
                                
                                <?php if($counter % 4 == 0) { ?>
                                    <div class="clear"></div>
                                <?php } ?>
                                
                                <?php $counter++; ?>
                            <?php } ?>
                                    
                            <div class="clear"></div>

                        </div>
                
                        <?php } 
                            else
                            {
                                ?>
                                <div id="tabs-<?php echo $category->id; ?>" class="bonanzaplan">
                                  <!-- <div class="col-md-3">
                                        <ul class="price">
                                            <li class="header">Features/Plan</li>
                                            <li class="grey">Price</li>
                                            <li class="grey">Purchase</li>
                                        </ul>
                                    </div>-->
                                    <?php
                            
                                    $categoryPlans = $obj_users->getAllActivePlanSuAdmin("p.id,p.name,p.description,p.no_of_client,p.plan_category,p.plan_price","c.id='".$category->id."' and p.is_deleted='0' and p.added_by='22'",$orderby='p.id asc'); ?>

                                    <?php $counter = 1; ?>

                                    <?php foreach ($categoryPlans as $plan ) { 
                                        ?>
                                        <div class="col-md-6 smallplancol">
                                        <div class="smallplanbox">
                                        <div class="pricecol text-center">
                                        <div class="plantxt"><div class="plancaption"><?php echo $plan->description;?></div> <div class="planprice"><?php echo $plan->plan_price;?></div></div>
                                            <div class="text-center btnmargin">
                                            <a href="<?php echo PROJECT_URL . "?page=plan_chooseplan&plan_id=" . $plan->id; ?>" class="btnsubcribe">Subscribe Now</a></div>
                                            </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                    <div class="clear"></div>
                                </div>
                                <?php
                            }
                        }
                        ?>
                
                <?php } ?>
                
            </div>
            
        </div>
<!--========================adminformbox over=========================-->    
    </div>
<!--========================admincontainer over=========================-->
</div>
</div>
<script>
    $(document).ready(function () {
        $( "#tabs" ).tabs();
    });
</script>