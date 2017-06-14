<div class="admincontainer greybg">
    <div class="formcontainer">
        <form>
            <div class="adminformbx">

                <div class="kycmainbox">
                    <div class="clear height20"></div>
                    <div class="tc">
                        <div style="width:100%; margin:0 auto; min-height:220px; ">
                            <?php $db_obj->showErrorMessage(); ?>
                            <?php $db_obj->showSuccessMessge(); ?>
                            <?php $db_obj->unsetMessage(); ?>
                            <div class="clear"></div>
                            <div class="title"> Welcome <span><?php
                            echo (trim($_SESSION['user_detail']['name'])!='') ? ucwords($_SESSION['user_detail']['name']) : strtolower($_SESSION['user_detail']['username']) ;?></span></div>
<!--                            <div class="sucess" style="padding:10px; font-size:15px;">Your 3 step Migration progress incompleted. Please complete your migration process shortly </div>-->
                            <div class="clear height20"></div>

                            <div class="col-md-6">
                                <div class="lightorange dashleftbox orangeborder">
                                    <div class="boxheading txtorange fl">New Customer Add</div>
                                    <div class="fr addmorebtn"><a href="<?php echo PROJECT_URL;?>/?page=client_update" class="greencolor smallbtn">+ Add More</a></div>
                                    <div class="clear"></div>
                                    <?php $dataClientArr = $db_obj->getClient("user_id,CONCAT(first_name,' ',last_name) as name,username,email,company_name,(case when status='1' Then 'active' when status='0' then 'deactive' end) as status","is_deleted='0' AND added_by='".$_SESSION['user_detail']['user_id']."'"); ?>
                                    
                                    <?php if(!empty($dataClientArr)) { ?>
                                        <ul>
                                            <?php foreach($dataClientArr as $dataClient) { ?>
                                            
                                                <?php
                                                    $color = '';
                                                    switch ($dataClient->status) {
                                                        
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
                                                <li><?php echo ucwords($dataClient->name); ?> (<?php echo $dataClient->username;?>) <span><a href="<?php echo PROJECT_URL; ?>?page=client_update&action=editClient&id=<?php echo ($dataClient->user_id);?>" class="<?php echo $color; ?>txt"><?php echo $dataClient->status; ?></a></span></li>
                                            <?php } ?>
                                        </ul>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="col-md-6">
                                    <div class=""><a href="javascript:void(0)" class="dashbtn orangebg"><img src="image/icon-add-invoice.png" width="70" alt="#"><br/>Add Invoice</a></div>
                                </div>

                                <div class="col-md-6">
                                    <div class=""><a href="javascript:void(0)" class="dashbtn orangebg last"><img src="image/icon-add-invoice.png" width="70" alt="#"><br/>Migrate Invoice GSTN Server</a></div>
                                </div>

                                <div class="col-md-6">
                                    <div class=""><a href="javascript:void(0)" class="dashbtn orangebg"><img src="image/icon-report.png" width="70" alt="#"><br/>Report</a></div>
                                </div>

                                <div class="col-md-6">
                                    <div class=""><a href="javascript:void(0)" class="dashbtn orangebg last"><img src="image/icon-add-invoice.png" width="70" alt="#"><br/>Other</a></div>
                                </div>
                            </div>

                        </div>
                    </div>   
                </div>

            </div>
        </form>
    </div>
</div>