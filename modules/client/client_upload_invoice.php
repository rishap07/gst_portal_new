<?php
$obj_client = new client();
$excelError = false;
$returnMessage = '';

if( !isset($_SESSION['user_detail']['user_id']) || $_SESSION['user_detail']['user_id'] == '' ) {
    $obj_client->redirect(PROJECT_URL);
    exit();
}

if(!$obj_client->can_read('client_invoice')) {

    $obj_client->setError($obj_client->getValMsg('can_read'));
    $obj_client->redirect(PROJECT_URL."/?page=dashboard");
    exit();
}

if( isset($_POST['submit']) && $_POST['submit'] == 'submit' ) {

	if(!$obj_client->can_create('client_invoice')) {
        $obj_client->setError($obj_client->getValMsg('can_create'));
        $obj_client->redirect(PROJECT_URL."/?page=client_invoice_list");
        exit();
    }

    if(!isset($_SERVER['HTTP_REFERER']) || empty($_SERVER['HTTP_REFERER'])){
        
        $obj_client->setError('Invalid access to files');
    } else {

		$uploadInvoice = $obj_client->uploadClientInvoice();
        if($uploadInvoice === true){

            $obj_client->redirect(PROJECT_URL."?page=client_invoice_list");
        } else {

			$excelError = true;
			$returnMessage = json_decode($uploadInvoice);
		}
    }
}

$dataCurrentArr = array();
$dataCurrentArr = $obj_client->getUserDetailsById( $obj_client->sanitize($_SESSION['user_detail']['user_id']) );
?>

<!--========================sidemenu over=========================-->
<div class="admincontainer greybg">
    <div class="formcontainer">
        
        <?php $obj_client->showErrorMessage(); ?>
        <?php $obj_client->showSuccessMessge(); ?>
        <?php $obj_client->unsetMessage(); ?>
		
		<?php
			if($excelError === true) {

				if(isset($returnMessage->status) && $returnMessage->status === "error") {
					echo '<p style="background-color:#eddbe3;border-radius:4px;padding:8px 35px 8px 14px;text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);margin-bottom: 18px;border-color:#e8d1df;"><a style="color:#bd4247;font-weight:bold;" target="_blank" class="error-excel-file" href="'.$returnMessage->excelurl.'">Download Excel File With Errors.</a></p>';
				}
			}
		?>

        <h1>Upload Invoice</h1>
        <hr class="headingborder">
        <div class="clear"></div>

        <form name="upload-invoice" id="upload-invoice" method="POST" enctype="multipart/form-data">

            <div class="adminformbx">

                <div class="kycform">

                    <div class="kycmainbox">
                        
                        <div class="formcol">
                            <label>Upload Excel File<span class="starred">*</span></label>
							<div class="clear"></div>
                            <input type="file" name="invoice_xlsx" id="invoice_xlsx" class="required" />
                        </div>
                        
                        <div class="clear"></div>

                        <div class="clear height10"></div>
                        
                        <div class="tc">
                            <input type='submit' class="btn orangebg" name='submit' value='submit' id='submit'>
                            <input type="button" value="<?php echo ucfirst('Back'); ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=client_invoice_list"; ?>';" class="btn redbg" class="redbtn marlef10"/>
                        </div>

                    </div>

                </div>

            </div>

        </form>
<!--========================adminformbox over=========================-->    
    </div>
<!--========================admincontainer over=========================-->
</div>
<script>
    $(document).ready(function () {
        $('#submit').click(function () {
            var mesg = {};
            if (vali.validate(mesg,'upload-invoice')) {
                return true;
            }
            return false;
        });
    });
</script>