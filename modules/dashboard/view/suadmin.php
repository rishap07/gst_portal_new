<div class="admincontainer greybg">
    <div class="formcontainer">
        <form>
            <div class="adminformbx">
                <div class="kycmainbox">
                    <div class="clear height20"></div>
                    <div class="tc">
                        <div style="width:100%; margin:0 auto; min-height:220px; ">
                            <div class="title"> Welcome <span><?php echo ucwords($_SESSION['user_detail']['name']);?></span></div>
                            <div class="clear height20"></div>
                            <div class="col-md-6">
                                <div class="lightorange dashleftbox orangeborder" style="max-height: 300px">
                                    <div class="boxheading txtorange fl">New Admin List</div>
                                    <div class="fr addmorebtn"><a href="<?php echo PROJECT_URL;?>/?page=user_adminupdate" class="greencolor smallbtn">+ Add More</a><a href="<?php echo PROJECT_URL;?>/?page=user_adminlist" class="bluecolor smallbtn">View All</a></div>
                                    <div class="clear"></div>
                                    <?php
                                    $dataArrs = $db_obj->getAdmin($is_deleted='0',$orderby='user_id desc',$limit='limit 0,5');
                                    ?>
                                    <ul>
                                        <?php
                                        if(!empty($dataArrs))
                                        {
                                            foreach($dataArrs as $dataArr)
                                            {
                                                $color = '';
                                                switch ($dataArr->payment_status)
                                                {
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
                                                <li><?php echo ucwords($dataArr->first_name." ".$dataArr->last_name);?> (<?php echo $dataArr->username;?>) <span><a href="<?php echo PROJECT_URL;?>?page=user_adminupdate&action=editAdmin&id=<?php echo ($dataArr->user_id);?>" class="<?php echo $color;?>txt"><?php echo ucwords($dataArr->payment_status);?></a></span></li>
                                                <?php
                                            }
                                        }
                                        else
                                        {
                                            ?>
                                            <li>No Admin Added Yet</li>
                                            <?php
                                        }
                                        ?>
                                    </ul>
                                </div>   
                            </div>
                            <div class="col-md-6">
                                <div class="lightorange dashleftbox orangeborder">
                                    <div class="boxheading txtorange fl">
                                        Plans List 
                                    </div>
                                    <div class="fr addmorebtn"><a href="<?php echo PROJECT_URL;?>/?page=plan_addplan" class="greencolor smallbtn">+ Add More</a><a href="<?php echo PROJECT_URL;?>/?page=plan_list" class="bluecolor smallbtn">View All</a></div>
                                    <div class="clear"></div>
                                    <?php
                                    $dataArrs = $db_obj->getAllActivePlanSuAdmin("p.id,p.name,(case when p.status='1' Then 'active' when  p.status='0' then 'deactive' end) as status,c.name as cat_name","p.is_deleted='0' and u.user_group='1'",$orderby='p.id desc',$limit='limit 0,5'); 
                                    ?>
                                    <ul>
                                        <?php
                                        if(!empty($dataArrs))
                                        {
                                            foreach($dataArrs as $dataArr)
                                            {
                                                $color = '';
                                                switch ($dataArr->status)
                                                {
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
                                                <li><?php echo ucwords($dataArr->name);?> (<?php echo ucwords($dataArr->cat_name);?>) <span><a href="<?php echo PROJECT_URL;?>/?page=plan_editplan&action=editPlan&id=<?php echo $dataArr->id;?>" class="<?php echo $color;?>txt"><?php echo ucwords($dataArr->status);?></a></span></li>
                                                <?php
                                            }
                                        }
                                        else
                                        {
                                            ?>
                                            <li>No Plans Added Yet</li>
                                            <?php
                                        }
                                        ?>
                                    </ul>
                                </div>   
                            </div>
                            <div class="clear height20"></div>
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <div class=""><a href="javascript:void(0)" class="dashbtn orangebg"><img src="image/icon-add-invoice.png" width="70" alt="#"><br/>Add Plan</a></div>
                                </div>
                                <div class="col-md-3">
                                
                                    <div class=""><a href="javascript:void(0)" class="dashbtn orangebg"><img src="image/icon-add-invoice.png" width="70" alt="#"><br/>Payments Module</a></div>
                                </div>
                                <div class="col-md-3">
                                    <div class=""><a href="javascript:void(0)" class="dashbtn orangebg"><img src="image/icon-add-invoice.png" width="70" alt="#"><br/>Settings</a></div>
<!--                                <div class=""><a href="#" class="dashbtn orangebg last"><img src="image/icon-add-invoice.png" width="70" alt="#"><br/>Migrate Invoice GSTN Server</a></div>-->
                                </div>
                                <div class="col-md-3">
                                <div class=""><a href="#" class="dashbtn orangebg"><img src="image/icon-report.png" width="70" alt="#"><br/>Report</a></div>
<!--                                <div class=""><a href="#" class="dashbtn orangebg last"><img src="image/icon-add-invoice.png" width="70" alt="#"><br/>Other</a></div>-->
                                </div>
                            </div>
                        </div>
                    </div>   
                </div>
            </div>
        </form>
    </div>
</div>