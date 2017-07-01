<div class="admincontainer greybg">
    <div class="formcontainer">
        <form>
            <div class="adminformbx">
                <div class="kycmainbox">
                    <div class="tc">
                        <div style="width:100%; margin:0 auto; min-height:220px; ">
                            <?php $db_obj->showErrorMessage(); ?>
                            <?php $db_obj->showSuccessMessge(); ?>
                            <?php $db_obj->unsetMessage(); ?>
                            <div class="clear"></div>
                            <div class="title"> Welcome <span><?php echo ucwords($_SESSION['user_detail']['name']);?></span></div>

                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <div class=""><a href="<?php echo PROJECT_URL."/?page=client_create_invoice";?>" class="dashbtn orangebg"><img src="image/icon-add-invoice.png" width="70" alt="#"><br/>Add Invoice</a></div>
                                </div>

                                <div class="col-md-3">
                                    <div class=""><a href="<?php echo PROJECT_URL."/?page=client_return";?>" class="dashbtn orangebg last"><img src="image/icon-return.png" width="70" alt="#"><br/>File GSTN Return</a></div>
                                </div>

                                <div class="col-md-3">
                                    <div class=""><a href="<?php echo PROJECT_URL."/?page=client_upload_invoice";?>" class="dashbtn orangebg"><img src="image/icon-report.png" width="70" alt="#"><br/>Import Invoices</a></div>
                                </div>
                                <div class="col-md-3">
                                    <div class=""><a href="<?php echo PROJECT_URL."/?page=client_kycupdate";?>" class="dashbtn orangebg last"><img src="image/icon-update.png" width="70" alt="#"><br/>Update KYC</a></div>
                                </div>
                                <div class="col-md-3">
                                    <div class=""><a href="<?php echo PROJECT_URL."/?page=master_receiver";?>" class="dashbtn orangebg last"><img src="image/icon-reciver-list.png" width="70" alt="#"><br/>Receiver List</a></div>
                                </div>
                                <div class="col-md-3">
                                    <div class=""><a href="<?php echo PROJECT_URL."/?page=master_supplier";?>" class="dashbtn orangebg last"><img src="image/icon-reciver-list.png" width="70" alt="#"><br/>Suppliers List</a></div>
                                </div>
                                <div class="col-md-3">
                                    <div class=""><a href="<?php echo PROJECT_URL."/?page=client_item_list";?>" class="dashbtn orangebg last"><img src="image/icon-report.png" width="70" alt="#"><br/>Item List</a></div>
                                </div>
                            </div>
                        </div>
                    </div>   
                </div>
            </div>
        </form>
    </div>
</div>