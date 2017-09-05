<?php $returnmonth = date('Y-m'); ?>
<style>
    table, th, td {
/*   border: 1px solid black;
   text-align: justify;
    padding: 12px;*/
}
    </style>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr">
    <div class="col-md-12 col-sm-11 col-xs-12 mobpadlr">
        <div class="clear"></div>
        <?php $db_obj->showErrorMessage(); ?>
        <?php $db_obj->showSuccessMessge(); ?>
        <?php $db_obj->unsetMessage(); ?>
        <div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>Dashboard Overview</h1></div>
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class=" whitebg dashleftbox">
                <div class="boxheading">New Business List<a style="float:right;" href="<?php echo PROJECT_URL; ?>/?page=subscriber_gstr_list&returnmonth=<?php echo $returnmonth; ?>" class="redbtnborder animation" style="margin-left:5px;">VIEW GSTR STATUS</a>
        </div>
		        <div class="border1"></div>
                <div class="listcontent">
                    <?php $dataClientArr = $db_obj->getClient("user_id,user_group,CONCAT(first_name,' ',last_name) as name, username, u.email, company_name,k.gstin_number, (case when status='1' Then 'active' when status='0' then 'deactive' end) as status", "u.is_deleted='0' AND u.added_by='" . $_SESSION['user_detail']['user_id'] . "'"); 
					
					?>
                     
                    <?php

                    if (!empty($dataClientArr)) {
                        ?>
                    <table class="table table-bordered table-hover" style="font-size:14px;">
                        <tr>
                            <th>User Name</th>   
							<th>User Type</th>
                            <th>Company Name</th>
							<th>No.Of Client</th>
						    <th> Gstn No.</th>
                            <th>Action</th>
                        </tr>
                        <?php foreach ($dataClientArr as $dataClient) { ?>
                        <?php $dataCurrentArr = $db_obj->getUserDetailsById($dataClient->user_id); ?>
						<?php
						  $no_of_client=0;
						 $sql = "select  count(user_id) as no_of_client from " . TAB_PREFIX . "user where added_by='" .$dataClient->user_id."' and user_group='4'";
                         $returndata = $db_obj->get_results($sql);
						 
						?>
                        <tr>
                            <td><?php echo ucwords($dataClient->name);?>(<?php echo $dataClient->username; ?>)</td>
							 <td> <?php if($dataClient->user_group==4) { echo 'Client'; } else { echo 'Subuser';}   ?></td> 
                            <td> <?php echo $dataClient->company_name ?></td> 
							<td><?php if($dataClient->user_group==5){ ?><?php if(!empty($returndata[0]) && ($returndata[0]->no_of_client > 0)) { ?><span class="pull-right"><a href="<?php echo PROJECT_URL; ?>?page=subscriber_client_list&id=<?php echo ($dataClient->user_id); ?>" class="txt">ViewClient</a></span><?php echo $returndata[0]->no_of_client; } else { echo $no_of_client; } } ?></td>
                            <td> <?php if($dataClient->user_group==5) { echo 'NA'; } else { echo $dataClient->gstin_number; } ?></td> 
							<?php if($dataClient->user_group==5){?>
						    <td> <span class="pull-right"><a href="<?php echo PROJECT_URL; ?>?page=subscriber_update&action=editClient&id=<?php echo ($dataClient->user_id); ?>" class="txt">Update Profile</a></span></td> 
						<?php } else { ?> <td> <?php if ($dataCurrentArr['data']->kyc != '') { ?><span class="pull-right1"><a href="<?php echo PROJECT_URL; ?>?page=client_loginas&id=<?php echo ($dataClient->user_id); ?>" class="txt">Login As Client</a></span><?php } ?> <span class="pull-right"><a href="<?php echo PROJECT_URL; ?>?page=client_kycupdate_by_subscriber&action=updateClientKYC&id=<?php echo ($dataClient->user_id); ?>" class="txt">Update KYC</a></span></td> <?php } ?>
                        
						</tr>
                        <?php } ?>
                        
                    </table>
<!--                        <ul>
                            <?php foreach ($dataClientArr as $dataClient) { ?>
                                <?php //$dataCurrentArr = $db_obj->getUserDetailsById($dataClient->user_id); ?>
                                <li>
                                    <?php //echo $dataClient->company_name ?>
                                </li>
                                <li>
                                    <?php echo $dataClient->gstin_number ?>
                                </li>
                                <li><?php echo ucwords($dataClient->name); ?> (<?php echo $dataClient->username; ?>) <?php if ($dataCurrentArr['data']->kyc != '') { ?><span class="pull-right"><a href="<?php echo PROJECT_URL; ?>?page=client_loginas&id=<?php echo ($dataClient->user_id); ?>" class="txt">Login As Client</a></span><?php } ?> <span class="pull-right"><a href="<?php echo PROJECT_URL; ?>?page=client_kycupdate_by_subscriber&action=updateClientKYC&id=<?php echo ($dataClient->user_id); ?>" class="txt">Update KYC</a></span></li>
                            <?php } ?>
                        </ul>-->
                    <?php } ?>
                </div>
                <div class="clear height30"></div>
                <div class="tc">
                    <a href="<?php echo PROJECT_URL; ?>/?page=client_update" class="greenbtnborder animation">+ Add More</a> 
                    <a href="<?php echo PROJECT_URL; ?>/?page=client_list" class="redbtnborder animation" style="margin-left:5px;">VIEW ALL</a>
                </div>
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
    <div class="col-md-2 col-sm-3 col-xs-12 dash-rightnav" style="display:none;">
        <?php include(PROJECT_ROOT . "/modules/dashboard/view/rightpanel.php"); ?>
    </div>
</div>
