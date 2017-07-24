<?php
?>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr">
    <div class="col-md-10 col-sm-9 col-xs-12 mobpadlr">
		<div class="clear"></div>
        <?php $db_obj->showErrorMessage(); ?>
        <?php $db_obj->showSuccessMessge(); ?>
        <?php $db_obj->unsetMessage(); ?>
        <div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>Dashboard Overview</h1></div>
        <div class="col-md-7 col-sm-7 col-xs-12">
            <div class=" whitebg dashleftbox">
                <div class="boxheading">New Business List</div>
                <div class="border"></div>
                <div class="listcontent">
                    <?php $dataClientArr = $db_obj->getClient("user_id,CONCAT(first_name,' ',last_name) as name,username,email,company_name,(case when status='1' Then 'active' when status='0' then 'deactive' end) as status", "is_deleted='0' AND added_by='" . $_SESSION['user_detail']['user_id'] . "'"); ?>

                    <?php if (!empty($dataClientArr)) { ?>
                        <ul>
                            <?php foreach ($dataClientArr as $dataClient) { ?>
                                <li><?php echo ucwords($dataClient->name); ?> (<?php echo $dataClient->username; ?>) <span class="pull-right"><a href="<?php echo PROJECT_URL; ?>?page=client_loginas&id=<?php echo ($dataClient->user_id); ?>" class="txt">Login As Client</a></span> <span class="pull-right"><a href="<?php echo PROJECT_URL; ?>?page=client_kycupdate_by_subscriber&action=updateClientKYC&id=<?php echo ($dataClient->user_id); ?>" class="txt">Update KYC</a></span></li>
                            <?php } ?>
                        </ul>
                    <?php } ?>
                </div>
                <div class="clear height30"></div>
                <div class="tc"><a href="<?php echo PROJECT_URL; ?>/?page=client_update" class="greenbtnborder animation">+ Add More</a> 
                    <a href="<?php echo PROJECT_URL; ?>/?page=client_list" class="redbtnborder animation" style="margin-left:5px;">VIEW ALL</a></div>
            </div>   
        </div>
        <!--        <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class=" whitebg dashleftbox">
                        <div class="boxheading">Plans List</div>
                        <div class="border"></div>
                        <div class="listcontent">
                            
                        </div>
                        <div class="clear height30"></div>
                        <div class="tc"><a href="javascript:void(0)" class="greenbtnborder">+ Add More</a> 
                            <a href="javascript:void(0)" class="redbtnborder" style="margin-left:5px;">VIEW ALL</a>
                        </div>
                    </div>   
                </div>-->
        <div class="dasfooter">Copyright @ by GST Keeper</div>  	
    </div>
    <div class="col-md-2 col-sm-3 col-xs-12 dash-rightnav">
        <?php include(PROJECT_ROOT . "/modules/dashboard/view/rightpanel.php"); ?>
    </div>
</div>
