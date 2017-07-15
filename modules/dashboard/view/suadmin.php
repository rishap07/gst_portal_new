<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr">
    <div class="col-md-10 col-sm-9 col-xs-12 mobpadlr">
        <?php $db_obj->showErrorMessage(); ?>
        <?php $db_obj->showSuccessMessge(); ?>
        <?php $db_obj->unsetMessage(); ?>
        <div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>Dashboard Overview</h1></div>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class=" whitebg dashleftbox">
                <div class="boxheading">New Admin List</div>
                <div class="border"></div>
                <div class="listcontent">
                    <?php
                    $dataArrs = $db_obj->getAdmin($is_deleted = '0', $orderby = 'user_id desc', $limit = 'limit 0,5');
                    ?>
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
                                <li><?php echo ucwords($dataArr->first_name . " " . $dataArr->last_name); ?> (<?php echo $dataArr->username; ?>) <span><a href="<?php echo PROJECT_URL; ?>?page=user_adminupdate&action=editAdmin&id=<?php echo ($dataArr->user_id); ?>" class="<?php echo $color; ?>txt"><?php echo ucwords($dataArr->payment_status); ?></a></span></li>
                                <?php
                            }
                        } else {
                            ?>
                            <li>No Admin Added Yet</li>
                            <?php
                        }
                        ?>
                    </ul>
                </div>
                <div class="clear height30"></div>

                <div class="tc"><a href="<?php echo PROJECT_URL; ?>/?page=user_adminupdate" class="greenbtnborder animation">+ Add More</a> 
                    <a href="<?php echo PROJECT_URL; ?>/?page=user_adminlist" class="redbtnborder animation" style="margin-left:5px;">VIEW ALL</a></div>

            </div>   
        </div>
        <!--                    <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class=" whitebg dashleftbox">
                                    <div class="boxheading">Plans List</div>
                                    <div class="border"></div>
                                    <div class="listcontent">
                                        <ul>
                                            <li>No Plans Added Yet</li>
                                            
                                        </ul>
                                    </div>
                                    <div class="clear height30"></div>
                                     <div class="tc"><a href="registration.php" class="greenbtnborder">+ Add More</a> 
                                     <a href="registration.php" class="redbtnborder" style="margin-left:5px;">VIEW ALL</a></div>
                                    
                                 </div>   
                            </div>-->
        <div class="dasfooter">Copyright @ by GST Keeper</div>  	
    </div>
    <div class="col-md-2 col-sm-3 col-xs-12 dash-rightnav">
        <?php include(PROJECT_ROOT . "/modules/dashboard/view/rightpanel.php"); ?>
    </div>

</div>




