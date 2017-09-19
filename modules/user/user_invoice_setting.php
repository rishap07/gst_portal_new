<?php
$obj_user = new users();

if(!$obj_user->can_read('client_invoice')) {

	$obj_user->setError($obj_user->getValMsg('can_read'));
	$obj_user->redirect(PROJECT_URL."/?page=dashboard");
	exit();
}

if(!$obj_user->can_create('client_invoice')) {

	$obj_user->setError($obj_user->getValMsg('can_create'));
	$obj_user->redirect(PROJECT_URL."/?page=dashboard");
	exit();
}

if (isset($_POST['submit']) && $_POST['submit'] == 'submit') {

    if (!isset($_SERVER['HTTP_REFERER']) || empty($_SERVER['HTTP_REFERER'])) {

        $obj_user->setError('Invalid access to files');
    } else {

        if ($obj_user->saveUserInvoiceSetting()) {
            $obj_user->redirect(PROJECT_URL . "?page=user_invoice_setting");
        }
    }
}
$dataInvoiceSettingArr = array();
$dataInvoiceSettingArr = $obj_user->getUserInvoiceSetting($obj_user->sanitize($_SESSION['user_detail']['user_id']));
?>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">

		<div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>Invoice Setting</h1></div>
		<hr class="headingborder">
		<div class="clear height10"></div>
		<?php $obj_user->showErrorMessage(); ?>
		<?php $obj_user->showSuccessMessge(); ?>
		<?php $obj_user->unsetMessage(); ?>

		<div class="whitebg formboxcontainer">
            <form name="user-invoice-setting" id="user-invoice-setting" method="POST">

				<div class="row">
					<h2 class="greyheading">Label Setting</h2>
					<div class="clear"></div>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Invoice Label <span class="starred">*</span></label>
						<input type="text" placeholder="Invoice Label" name="invoice_label" id="invoice_label" class="form-control required" data-bind="content" value="<?php if (isset($_POST['invoice_label'])) { echo $_POST['invoice_label']; } else if (isset($dataInvoiceSettingArr['data']->invoice_label)) { echo $dataInvoiceSettingArr['data']->invoice_label; } ?>">
					</div>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Reference Label <span class="starred">*</span></label>
						<input type="text" placeholder="Reference Label" name="reference_label" id="reference_label" class="form-control required" data-bind="content" value="<?php if (isset($_POST['reference_label'])) { echo $_POST['reference_label']; } else if (isset($dataInvoiceSettingArr['data']->reference_label)) { echo $dataInvoiceSettingArr['data']->reference_label; } ?>">
					</div>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Type Label <span class="starred">*</span></label>
						<input type="text" placeholder="Type Label" name="type_label" id="type_label" class="form-control required" data-bind="content" value="<?php if(isset($_POST['type_label'])) { echo $_POST['type_label']; } else if(isset($dataInvoiceSettingArr['data']->type_label)) { echo $dataInvoiceSettingArr['data']->type_label; } ?>" />
					</div>
					<div class="clear"></div>
					
					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Nature Label <span class="starred">*</span></label>
						<input type="text" placeholder="Nature Label" name="nature_label" id="nature_label" class="form-control required" data-bind="content" value="<?php if(isset($_POST['nature_label'])) { echo $_POST['nature_label']; } else if(isset($dataInvoiceSettingArr['data']->nature_label)) { echo $dataInvoiceSettingArr['data']->nature_label; } ?>" />
					</div>
					
					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Date Label <span class="starred">*</span></label>
						<input type="text" placeholder="Date Label" name="date_label" id="date_label" class="form-control required" data-bind="content" value="<?php if(isset($_POST['date_label'])) { echo $_POST['date_label']; } else if(isset($dataInvoiceSettingArr['data']->date_label)) { echo $dataInvoiceSettingArr['data']->date_label; } ?>" />
					</div>
					<div class="clear"></div>

                    <div class="adminformbxsubmit" style="width:100%;">
                        <div class="tc">
                            <input type='submit' class="btn btn-default btn-success" name='submit' value='submit' id='submit'>
                            <input type="button" value="<?php echo ucfirst('Back'); ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=dashboard"; ?>';" class="btn btn-danger" />
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
            if (vali.validate(mesg, 'user-invoice-setting')) {
                return true;
            }
            return false;
        });
    });
</script>