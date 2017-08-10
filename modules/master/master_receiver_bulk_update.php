<?php
$obj_master = new master();
$excelError = false;
$returnMessage = '';

if (!isset($_SESSION['user_detail']['user_id']) || $_SESSION['user_detail']['user_id'] == '') {
    $obj_master->redirect(PROJECT_URL);
    exit();
}

if (!$obj_master->can_read('master_receiver')) {

    $obj_master->setError($obj_master->getValMsg('can_read'));
    $obj_master->redirect(PROJECT_URL . "/?page=dashboard");
    exit();
}

if(isset($_POST['submit']) && $_POST['submit']=='submit') {

    if(!$obj_master->can_create('master_receiver')) {

        $obj_master->setError($obj_master->getValMsg('can_create'));
        $obj_master->redirect(PROJECT_URL."/?page=master_receiver");
        exit();
    }

    if($obj_master->add_bulk_Receiver()){
        $obj_master->redirect(PROJECT_URL."/?page=master_receiver");
    }
}
$dataCurrentArr = array();
$dataCurrentArr = $obj_master->getUserDetailsById($obj_master->sanitize($_SESSION['user_detail']['user_id']));
?>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="col-md-12 col-sm-12 col-xs-12 heading"><h1> Upload Receiver/Customer</h1></div>

        <div class="whitebg formboxcontainer">
            <?php $obj_master->showErrorMessage(); ?>
            <?php $obj_master->showSuccessMessge(); ?>
            <?php $obj_master->unsetMessage(); ?>

            <?php
            if ($excelError === true) {

                if (isset($returnMessage->status) && $returnMessage->status === "error") {
                    echo '<p style="background-color:#eddbe3;border-radius:4px;padding:8px 35px 8px 14px;text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);margin-bottom: 18px;border-color:#e8d1df;"><a style="color:#bd4247;font-weight:bold;" target="_blank" class="error-excel-file" href="' . $returnMessage->excelurl . '">Download Excel File With Errors.</a></p>';
                }
            }
            ?>



            <div class="clear"></div>

            <form name="upload-invoice" id="upload-invoice" method="POST" enctype="multipart/form-data">
                <div class="row">


                    <div class="col-md-4 col-sm-4 col-xs-12 form-group">

                        <label>Upload Excel File<span class="starred">*</span></label>
                        <div class="clear"></div>
                        <input type="file" name="invoice_xlsx" id="invoice_xlsx" class="required form-control" />
                    </div><div class="clear"></div>

                    <div class="col-md-4 col-sm-4 col-xs-12 form-group">

                        <label>Download Sample Excel File</label>
                        <div class="clear"></div>
                        <a href="<?php echo PROJECT_URL . "/upload/receiver.xlsx"; ?>">Download Sample</a>
                    </div>                

                    <div class="clear"></div>

                    <div class="adminformbxsubmit" style="width:100%;">

                        <div class="tc">
                            <input type='submit' class="btn btn-danger" name='submit' value='submit' id='submit'>
                            <input type="button" value="<?php echo ucfirst('Back'); ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=master_receiver"; ?>';" class="btn btn-danger" />
                        </div>

                    </div>

                </div>
            </form>
        </div>

    </div>
</div>
<!--========================sidemenu over=========================-->
<script>
    $(document).ready(function () {
        $('#submit').click(function () {
            var mesg = {};
            if (vali.validate(mesg, 'upload-invoice')) {
                return true;
            }
            return false;
        });
    });
</script>