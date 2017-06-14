<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
*/
?>
<div class="admincontainer greybg">
    <div class="formcontainer">
        <form>
            <div class="adminformbx">
                <div class="kycmainbox">
                    <div class="clear height20"></div>
                    <div class="tc">
                        <div style="width:100%; margin:0 auto; min-height:220px; ">
                            
                            <div class="col-md-12">
                                <?php if($db_obj->can_read('master_state')){ ?><div class="col-md-3"><a href="<?php echo PROJECT_URL;?>/?page=master_state" class="dashbtn orangebg"><img src="image/icon-add-invoice.png" width="70" alt="#"><br/>State Master</a></div><?php } ?>
                                <?php if($db_obj->can_read('master_unit')){ ?><div class="col-md-3"><a href="<?php echo PROJECT_URL;?>/?page=master_unit" class="dashbtn orangebg"><img src="image/icon-add-invoice.png" width="70" alt="#"><br/>Unit Master</a></div><?php } ?>
								<?php if($db_obj->can_read('master_receiver')){ ?><div class="col-md-3"><a href="<?php echo PROJECT_URL;?>/?page=master_receiver" class="dashbtn orangebg last"><img src="image/icon-add-invoice.png" width="70" alt="#"><br/>Receiver Master</a></div><?php } ?>
                                <?php if($db_obj->can_read('master_supplier')){ ?><div class="col-md-3"><a href="<?php echo PROJECT_URL;?>/?page=master_supplier" class="dashbtn orangebg"><img src="image/icon-report.png" width="70" alt="#"><br/>Supplier Master</a></div><?php } ?>
                                <?php if($db_obj->can_read('master_item')){ ?><div class="col-md-3"><a href="<?php echo PROJECT_URL;?>/?page=master_item" class="dashbtn orangebg last"><img src="image/icon-add-invoice.png" width="70" alt="#"><br/>Item Master</a></div><?php } ?>
								<?php if($db_obj->can_read('client_master_item')){ ?><div class="col-md-3"><a href="<?php echo PROJECT_URL;?>/?page=client_item_list" class="dashbtn orangebg last"><img src="image/icon-add-invoice.png" width="70" alt="#"><br/>Item Master</a></div><?php } ?>
                            </div>

                        </div>
                    </div>   
                </div>

            </div>
    </div>
</form>
<!--========================adminformbox over=========================-->

</div>