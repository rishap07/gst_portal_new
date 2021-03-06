<?php
$obj_purchase = new purchase();
$excelError = false;
$returnMessage = '';

if(!$obj_purchase->can_read('client_invoice')) {

    $obj_purchase->setError($obj_purchase->getValMsg('can_read'));
    $obj_purchase->redirect(PROJECT_URL."/?page=dashboard");
    exit();
}

if(!$obj_purchase->can_create('client_invoice')) {
	
	$obj_purchase->setError($obj_purchase->getValMsg('can_create'));
	$obj_purchase->redirect(PROJECT_URL."/?page=purchase_invoice_list");
	exit();
}

if( isset($_POST['submit']) && $_POST['submit'] == 'submit' ) {

    if(!isset($_SERVER['HTTP_REFERER']) || empty($_SERVER['HTTP_REFERER'])){
        
        $obj_purchase->setError('Invalid access to files');
    } else {
		
		if($_POST['invoice_type'] == "taxinvoice") {

			$uploadInvoice = $obj_purchase->uploadPurchaseClientInvoice();
			$redirectPath = "purchase_invoice_list";
		} else if($_POST['invoice_type'] == "taximportinvoice") {
			
			$uploadInvoice = $obj_purchase->uploadPurchaseClientImportInvoice();
			$redirectPath = "purchase_invoice_list";
		} else if($_POST['invoice_type'] == "bosinvoice") {

			$uploadInvoice = $obj_purchase->uploadPurchaseClientBOSInvoice();
			$redirectPath = "purchase_bill_of_supply_invoice_list";
		} else if($_POST['invoice_type'] == "rvinvoice") {

			$uploadInvoice = $obj_purchase->uploadPurchaseClientRVInvoice();
			$redirectPath = "purchase_receipt_voucher_invoice_list";
		} else if($_POST['invoice_type'] == "pvinvoice") {

			$uploadInvoice = $obj_purchase->uploadPurchaseClientPVInvoice();
			$redirectPath = "purchase_payment_voucher_invoice_list";
		} else if($_POST['invoice_type'] == "rtinvoice") {

			$uploadInvoice = $obj_purchase->uploadPurchaseClientRTInvoice();
			$redirectPath = "purchase_revised_tax_invoice_list";
		} else {
			$obj_purchase->redirect(PROJECT_URL."?page=purchase_upload_invoice");
		}

        if($uploadInvoice === true){
            $obj_purchase->redirect(PROJECT_URL."?page=".$redirectPath);
        } else {

			$excelError = true;
			$returnMessage = json_decode($uploadInvoice);
		}
    }
}

$dataCurrentArr = array();
$dataCurrentArr = $obj_purchase->getUserDetailsById( $obj_purchase->sanitize($_SESSION['user_detail']['user_id']) );
?>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">

		<div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>Upload Purchase Invoice</h1></div>

		<div class="whitebg formboxcontainer">

			<?php $obj_purchase->showErrorMessage(); ?>
			<?php $obj_purchase->showSuccessMessge(); ?>
			<?php $obj_purchase->unsetMessage(); ?>

			<?php
				if($excelError === true) {

					if(isset($returnMessage->status) && $returnMessage->status === "error") {
						echo '<p style="background-color:#eddbe3;border-radius:4px;padding:8px 35px 8px 14px;text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);margin-bottom: 18px;border-color:#e8d1df;"><a style="color:#bd4247;font-weight:bold;" target="_blank" class="error-excel-file" href="'.$returnMessage->excelurl.'">Download Excel File With Errors.</a></p>';
					}
				}
			?>
			<div class="clear"></div>

			<form name="upload-invoice" id="upload-invoice" method="POST" enctype="multipart/form-data">
				<div class="row">

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Invoice Type<span class="starred">*</span></label>
						<select name="invoice_type" id="invoice_type" class="required form-control">
							<option value=''>Select Invoice Type</option>
							<option value='taxinvoice'>Tax Invoice</option>
							<option value='taximportinvoice'>Tax Import Invoice</option>
							<option value='bosinvoice'>Bill of Supply Invoice</option>
							<option value='rvinvoice'>Receipt Voucher Invoice</option>
							<option value='pvinvoice'>Payment Voucher Invoice</option>
							<option value='rtinvoice'>Revised Tax Invoice</option>
						</select>
					</div>
									 
					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Upload Excel File<span class="starred">*</span></label>
						<div class="clear"></div>
						<input type="file" name="invoice_xlsx" id="invoice_xlsx" class="required form-control" />
					</div>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Download Sample Excel File</label>
						<div class="clear"></div>
						<a href="<?php echo PROJECT_URL."/upload/purchase-excel.zip"; ?>"><b>Download Sample</b></a>
					</div>

					<div class="clear"></div>

					<div class="adminformbxsubmit" style="width:100%;">
						<div class="tc">
							<input type='submit' class="btn btn-success" name='submit' value='submit' id='submit'>
							<input type="button" value="<?php echo ucfirst('Back'); ?>" onclick="javascript:window.location.href='<?php echo PROJECT_URL . "/?page=purchase_invoice_list"; ?>';" class="btn btn-danger" />
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
            if (vali.validate(mesg,'upload-invoice')) {
				return true;
            }
            return false;
        });
    });
</script>