<?php
if ($db_obj->can_read('plan_category_list') || $db_obj->can_read('plan_list')) { ?>
    <a href="#" class="rgtmenu"><img src="<?php echo PROJECT_URL;?>/image/icon-add-plan.png" alt="#"><br/>Add Plan</a>

    <a href="#" class="rgtmenu"><img src="<?php echo PROJECT_URL;?>/image/icon-add-plan.png" alt="#"><br/>Payments Module</a>
    <a href="<?php echo PROJECT_URL . '/?page=user_group'; ?>" class="rgtmenu"><img src="<?php echo PROJECT_URL;?>/image/icon-setting.png" alt="#"><br/>SETTING</a>
    <a href="#" class="rgtmenu"><img src="<?php echo PROJECT_URL;?>/image/icon-report.png" alt="#"><br/>Reports</a>
<?php } ?>               
<?php if ($db_obj->can_read('client_list')) { ?>
    <a href="<?php echo PROJECT_URL; ?>/?page=client_update" class="rgtmenu"><img src="<?php echo PROJECT_URL;?>/image/icon-add-plan.png" alt="#"><br/>Add Business User</a>
<?php }?>
<?php if ($db_obj->can_read('client_invoice')) { ?>
    <a href="<?php echo PROJECT_URL . '/?page=client_upload_invoice'; ?>" class="rgtmenu"><img src="<?php echo PROJECT_URL;?>/image/icon-add-plan.png" alt="#"><br/>Upload Tax Invoice</a>
    <a href="<?php echo PROJECT_URL . '/?page=client_invoice_list'; ?>" class="rgtmenu"><img src="<?php echo PROJECT_URL;?>/image/icon-setting.png" alt="#"><br/>All Tax Invoice</a>
    <a href="<?php echo PROJECT_URL . '/?page=return_client'; ?>" class="rgtmenu"><img src="<?php echo PROJECT_URL;?>/image/icon-report.png" alt="#"><br/>Returns</a>
<?php }?>