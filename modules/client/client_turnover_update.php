<?php
	$obj_client = new client();
	if (!$obj_client->can_read('client_kyc')) {
		$obj_client->setError($obj_client->getValMsg('can_read'));
		$obj_client->redirect(PROJECT_URL . "/?page=dashboard");
		exit();
	}

	if (!$obj_client->can_create('client_kyc')) {
		$obj_client->setError($obj_client->getValMsg('can_create'));
		$obj_client->redirect(PROJECT_URL . "/?page=dashboard");
		exit();
	}

	if (!$obj_client->can_update('client_kyc')) {
		$obj_client->setError($obj_client->getValMsg('can_update'));
		$obj_client->redirect(PROJECT_URL . "/?page=dashboard");
		exit();
	}

	$dataArr = array();
	$dataArr = $obj_client->getUserDetailsById($obj_client->sanitize($_SESSION['user_detail']['user_id']));
	if (empty($dataArr['data']->kyc) && !isset($dataArr['data']->kyc->name)) { 
	$obj_client->redirect(PROJECT_URL . "/?page=client_kyc");
	}
	if (isset($_POST['submit']) && $_POST['submit'] == 'submit') {
		
		if (!isset($_SERVER['HTTP_REFERER']) || empty($_SERVER['HTTP_REFERER'])) {
			$obj_client->setError('Invalid access to files');
		} else {

			if ($obj_client->saveClientKYCTurnover()) {
				$obj_client->redirect(PROJECT_URL . "?page=client_kycupdate");
			}
		}
	}
?>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="col-md-12 col-sm-12 col-xs-12 heading">
			<h1>Company Profile</h1>
		</div>
        <div class="clear"></div>
		
		<?php $obj_client->showErrorMessage(); ?>
		<?php $obj_client->showSuccessMessge(); ?>
		<?php $obj_client->unsetMessage(); ?>
        
		<div class="whitebg formboxcontainer">
            <h2 class="greyheading">Identity Details</h2>
            <form name="client-kyc" id="client-kyc" method="POST" enctype="multipart/form-data">
                <div class="row">					
					
					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Gross Turnover in the preceding Financial Year<span class="starred">*</span></label>
						<input type="text" placeholder="Gross Turnover" name="gross_turnover" id="gross_turnover" class="form-control required" data-bind="gross_turnover" value="<?php if (isset($_POST['gross_turnover'])) { echo $_POST['gross_turnover']; } else if (isset($dataArr['data']->kyc->gross_turnover)) { echo $dataArr['data']->kyc->gross_turnover; } ?>">
					</div>
					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
					<?php //echo '<pre>';print_r($dataArr);die;
					?>
						<label>Gross Turnover - April to June, 2017 <span class="starred">*</span></label>
						<input type="text" placeholder="Cur Gross Turnover" name="cur_gross_turnover" id="cur_gross_turnover" class="form-control required" data-bind="cur_gross_turnover" value="<?php if (isset($_POST['cur_gross_turnover'])) { echo $_POST['cur_gross_turnover']; } else if (isset($dataArr['data']->kyc->cur_gross_turnover)) { echo $dataArr['data']->kyc->cur_gross_turnover; } ?>">
					</div>
					 	
					<div class="clear"></div>

					
					
					
                 
					
					<div class="adminformbxsubmit" style="width:100%;">
                        <div class="tc">
							<input type="hidden" name="action" value="submitKYC">
                            <input type='submit' class="btn btn-success" name='submit' value='submit' id='submit'>
                            <input type="button" value="<?php echo ucfirst('Back'); ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=dashboard"; ?>';" class="btn btn-danger" />
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="clear height80"></div>
<!--========================sidemenu over=========================-->
<script>
	$(document).ready(function () {
		
		/* submit kyc form */
        $("#client-kyc").submit(function(event){

            event.preventDefault();

			

			$("#loading").show();
			var kycFormData = new FormData(this);
			//kycFormData.append("state_tin", $("#state option:selected").attr("data-tin"));

            $.ajax({
                //data: {kycData:$("#client-kyc").serialize(), action:"submitKYC"},
                data: kycFormData,
				cache: false,
				contentType: false,
				processData: false,
				dataType: 'json',
                type: 'post',
                url: "<?php echo PROJECT_URL; ?>/?ajax=client_submit_kyc_turnover",
                success: function(response){

					$("#loading").hide();
					if(response.status == "success") {
						window.location.href = '<?php echo PROJECT_URL; ?>/?page=client_kycupdate';
					} else {
						jAlert(response.message);
					}
                }
            });
        });
		/* end of submit kyc form */

		/* Date of birth datepicker */
		$("#date_of_birth").datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: 'yy-mm-dd',
			yearRange: '1900:<?php echo date("Y"); ?>',
			maxDate: '0'
		});

		/* select2 js for business type */
		$("#business_type").select2();
		/* select2 js for business area */
		$("#business_area").select2();
		/* select2 js for business area */
		$("#vendor_type").select2();

		/* select2 js for state */
		$("#state").select2();

		$('#submit').click(function () {
			var mesg = {};
			if (vali.validate(mesg, 'client-kyc')) {
				return true;
			}
			return false;
		});
	});
</script>