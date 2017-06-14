<?php
$obj_client = new client();
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

        if($obj_client->uploadClientInvoice()){

            $obj_client->redirect(PROJECT_URL."?page=client_invoice_list");
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

        <h1>Upload Invoice</h1>
        <hr class="headingborder">
        <div class="clear"></div>

        <form name="upload-invoice" id="upload-invoice" method="POST" enctype="multipart/form-data">

            <div class="adminformbx">

                <div class="kycform">

                    <div class="kycmainbox">
                        
                        <div class="formcol">
                            <label>Upload CSV<span class="starred">*</span></label>
							<div class="clear"></div>
                            <input type="file" name="invoice_csv" id="invoice_csv" class="required" />
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