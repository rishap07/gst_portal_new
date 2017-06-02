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
                            <div class="title"> Welcome <span><?php echo ucwords($_SESSION['user_detail']['name']);?></span></div>
                            <div class="sucess" style="padding:10px; font-size:15px;">Your 3 step Migration progress incompleted. Please complete your migration process shortly </div>
                            <div class="clear height20"></div>

                            <div class="col-md-12">
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