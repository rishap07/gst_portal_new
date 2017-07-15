<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr">
    <div class="col-md-10 col-sm-9 col-xs-12 mobpadlr">
        <?php $db_obj->showErrorMessage(); ?>
        <?php $db_obj->showSuccessMessge(); ?>
        <?php $db_obj->unsetMessage(); ?>
        <div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>Dashboard Overview</h1></div>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class=" whitebg dashleftbox">
                <div class="boxheading">New Subscriber List</div>
                <div class="border"></div>
                <div class="listcontent">
                    <?php $dataArrs = $db_obj->getSubscriber($is_deleted = '0', $orderby = 'user_id desc', $limit = 'limit 0,5'); ?>
                    <ul>
                        <?php
                        if (!empty($dataArrs)) {
                            foreach ($dataArrs as $dataArr) {
                                $color = '';
                                switch ($dataArr->payment_status) {
                                    case 'pending':
                                        $color = 'blue';
                                        break;
                                    case 'accepted':
                                        $color = 'green';
                                        break;
                                    case 'rejected':
                                        $color = 'red';
                                        break;
                                    case 'mark as fraud':
                                        $color = 'orange';
                                        break;
                                    default:
                                        $color = 'blue';
                                }
                                ?>
                                <li><?php echo ucwords($dataArr->first_name . " " . $dataArr->last_name); ?> (<?php echo $dataArr->username; ?>) <span><a href="#" class="<?php echo $color; ?>txt"><?php echo ucwords($dataArr->payment_status); ?></a></span></li>
                                <?php
                            }
                        } else {
                            ?>
                            <li>No User Added Yet</li>
                            <?php
                        }
                        ?>
                    </ul>
                </div>
                <div class="clear height30"></div>
                <div class="tc"><a href="javascript:void(0)" class="greenbtnborder animation">+ Add More</a> 
                    <a href="javascript:void(0)" class="redbtnborder animation" style="margin-left:5px;">VIEW ALL</a></div>
            </div>   
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class=" whitebg dashleftbox">
                <div class="boxheading">Plans List</div>
                <div class="border"></div>
                <div class="listcontent">
                    <?php
                    $dataArrs = $db_obj->getAllActivePlanSuAdmin("p.id,p.name,(case when p.status='1' Then 'active' when p.status='0' then 'deactive' end) as status,c.name as cat_name", "p.is_deleted='0' and p.added_by='" . $_SESSION['user_detail']['user_id'] . "'", $orderby = 'p.id desc', $limit = 'limit 0,5');
                    ?>
                    <ul>
                    <?php
                    if (!empty($dataArrs)) {
                        foreach ($dataArrs as $dataArr) {
                            $color = '';
                            switch ($dataArr->status) {
                                case 'active':
                                    $color = 'green';
                                    break;
                                case 'deactive':
                                    $color = 'red';
                                    break;
                                default:
                                    $color = 'blue';
                            }
                            ?>
                                <li><?php echo ucwords($dataArr->name); ?> (<?php echo ucwords($dataArr->cat_name); ?>) <span><a href="<?php echo PROJECT_URL; ?>/?page=plan_editplan&action=editPlan&id=<?php echo $dataArr->id; ?>" class="<?php echo $color; ?>txt"><?php echo ucwords($dataArr->status); ?></a></span></li>
                                <?php
                            }
                        } else {
                            ?>
                            <li>No Plans Added Yet</li>
                            <?php
                        }
                        ?>
                    </ul>
                </div>
                <div class="clear height30"></div>
                <div class="tc"><a href="<?php echo PROJECT_URL;?>/?page=plan_addplan" class="greenbtnborder">+ Add More</a> 
                    <a href="<?php echo PROJECT_URL;?>/?page=plan_list" class="redbtnborder" style="margin-left:5px;">VIEW ALL</a>
                </div>
            </div>   
        </div>
        <div class="dasfooter">Copyright @ by GST Keeper</div>  	
    </div>
    <div class="col-md-2 col-sm-3 col-xs-12 dash-rightnav">
        <?php include(PROJECT_ROOT . "/modules/dashboard/view/rightpanel.php"); ?>
    </div>
</div>
