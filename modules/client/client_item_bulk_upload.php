<?php
$obj_client = new client();
$obj_master = new master();
$excelError = false;
$returnMessage = '';

if(!$obj_client->can_read('client_master_item')) {

    $obj_client->setError($obj_client->getValMsg('can_read'));
    $obj_client->redirect(PROJECT_URL."/?page=dashboard");
    exit();
}

if(isset($_POST['submit']) && $_POST['submit']=='submit') {

   if(!$obj_client->can_create('client_master_item')) {

		$obj_client->setError($obj_client->getValMsg('can_create'));
		$obj_client->redirect(PROJECT_URL."/?page=client_item_list");
		exit();
	}


        $uploadInvoice = $obj_master->add_bulk_Item();


    if ($uploadInvoice === true) {
        $obj_master->redirect(PROJECT_URL . "/?page=client_item_list");
    } else {

        $excelError = true;
        $returnMessage = json_decode($uploadInvoice);
    }
       
}
 
    
   
$dataCurrentArr = array();
$dataCurrentArr = $obj_client->getUserDetailsById($obj_client->sanitize($_SESSION['user_detail']['user_id']));

?>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="col-md-12 col-sm-12 col-xs-12 heading"><h1> Upload Item</h1></div>

        <div class="whitebg formboxcontainer">
            <?php $obj_client->showErrorMessage(); ?>
            <?php $obj_client->showSuccessMessge(); ?>
            <?php $obj_client->unsetMessage(); ?>

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
                        <a href="<?php echo PROJECT_URL . "/upload/item.xlsx"; ?>">Download Sample</a>
                    </div>                

                    <div class="clear"></div>

                    <div class="adminformbxsubmit" style="width:100%;">

                        <div class="tc">
                            <input type='submit' class="btn btn-danger" name='submit' value='submit' id='submit'>
                            <input type="button" value="<?php echo ucfirst('Back'); ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=client_item_list"; ?>';" class="btn btn-danger" />
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