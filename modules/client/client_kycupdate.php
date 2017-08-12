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
	
	if (isset($_POST['submit']) && $_POST['submit'] == 'submit') {
		
		if (!isset($_SERVER['HTTP_REFERER']) || empty($_SERVER['HTTP_REFERER'])) {
			$obj_client->setError('Invalid access to files');
		} else {

			if ($obj_client->saveClientKYC()) {
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
                    
					<?php if ($dataArr['data']->kyc != '' && isset($dataArr['data']->kyc->name)) { ?>
						<div class="col-md-4 col-sm-4 col-xs-12 form-group">
							<label>Name of Applicant / Company Name<span class="starred">*</span></label>
							<input type="text" placeholder="Name of Applicant" name="name" id="name" class="required form-control" data-bind="content" value="<?php if(isset($_POST['name'])) { echo $_POST['name']; } else if(isset($dataArr['data']->kyc->name)) { echo $dataArr['data']->kyc->name; } ?>" />
							<span class="greysmalltxt">(As appearing in supporting / identification document)</span>
						</div>
                    <?php } else { ?>
						<div class="col-md-4 col-sm-4 col-xs-12 form-group">
							<label>Name of Applicant / Company Name<span class="starred">*</span></label>
							<input type="text" placeholder="Name of Applicant" name="name" id="name" class="required form-control" data-bind="content" value="<?php if(isset($_POST['name'])) { echo $_POST['name']; } else if(isset($dataArr['data']->name)) { echo $dataArr['data']->name; } ?>" />
							<span class="greysmalltxt">(As appearing in supporting / identification document)</span>
						</div>
                    <?php } ?>
					
					<?php if ($dataArr['data']->kyc != '' && isset($dataArr['data']->kyc->email)) { ?>
						<div class="col-md-4 col-sm-4 col-xs-12 form-group">
							<label>Email Address<span class="starred">*</span></label>
							<input type="text" placeholder="Enter Email Address" name="email" id="email" class="required form-control" data-bind="email" value="<?php if(isset($_POST['email'])) { echo $_POST['email']; } else if(isset($dataArr['data']->kyc->email)) { echo $dataArr['data']->kyc->email; } ?>" />
						</div>
					<?php } else { ?>
                        <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                            <label>Email Address<span class="starred">*</span></label>
                            <input type="text" placeholder="Enter Email Address" name="email" id="email" class="required form-control" data-bind="email" value="<?php if(isset($_POST['email'])) { echo $_POST['email']; } else if(isset($dataArr['data']->email)) { echo $dataArr['data']->email; } ?>" />
                        </div>
					<?php } ?>

					<?php if ($dataArr['data']->kyc != '' && isset($dataArr['data']->kyc->phone_number)) { ?>
						<div class="col-md-4 col-sm-4 col-xs-12 form-group">
							<label>Contact Number<span class="starred">*</span></label>
							<input type="text" placeholder="Enter Contact Number" name="phone_number" id="phone_number" class="required form-control" data-bind="mobilenumber" value="<?php if(isset($_POST['phone_number'])) { echo $_POST['phone_number']; } else if(isset($dataArr['data']->kyc->phone_number)) { echo $dataArr['data']->kyc->phone_number; } ?>" />
						</div>
					<?php } else { ?>
						<div class="col-md-4 col-sm-4 col-xs-12 form-group">
							<label>Contact Number<span class="starred">*</span></label>
							<input type="text" placeholder="Enter Contact Number" name="phone_number" id="phone_number" class="required form-control" data-bind="mobilenumber" value="<?php if(isset($_POST['phone_number'])) { echo $_POST['phone_number']; } else if(isset($dataArr['data']->phone_number)) { echo $dataArr['data']->phone_number; } ?>" />
						</div>
					<?php } ?>
                    <div class="clear"></div>
                    
					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Date of Birth / Company Registered Date<span class="starred">*</span></label>
						<input type="text" placeholder="yyyy-mm-dd" name="date_of_birth" id="date_of_birth" class="required form-control" data-bind="date" value="<?php if(isset($_POST['date_of_birth'])) { echo $_POST['date_of_birth']; } else if(isset($dataArr['data']->kyc->date_of_birth)) { echo $dataArr['data']->kyc->date_of_birth; } ?>" />
					</div>

					<?php if ($dataArr['data']->kyc != '' && isset($dataArr['data']->kyc->gstin_number)) { ?>

						<div class="col-md-4 col-sm-4 col-xs-12 form-group">
							<label>GSTIN Number<span class="starred">*</span></label>
							<input type="text" disabled="true" id="gstin_number" placeholder="Enter gstin number" class="required form-control" data-bind="gstin" value="<?php if(isset($_POST['gstin_number'])) { echo $_POST['gstin_number']; } else if(isset($dataArr['data']->kyc->gstin_number)) { echo $dataArr['data']->kyc->gstin_number; } ?>" />
						</div>
					<?php } else { ?>
					
						<div class="col-md-4 col-sm-4 col-xs-12 form-group">
							<label>GSTIN Number<span class="starred">*</span></label>
							<input type="text" name="gstin_number" id="gstin_number" placeholder="Enter gstin number" class="required form-control" data-bind="gstin" value="<?php if(isset($_POST['gstin_number'])) { echo $_POST['gstin_number']; } else if(isset($dataArr['data']->kyc->gstin_number)) { echo $dataArr['data']->kyc->gstin_number; } ?>" />
						</div>
					<?php } ?>
					
					<?php if ($dataArr['data']->kyc != '' && isset($dataArr['data']->kyc->pan_card_number)) { ?>

						<div class="col-md-4 col-sm-4 col-xs-12 form-group">
							<label>Pan Card No<span class="starred">*</span></label>
							<input type="text" disabled="true" id="pan_card_number" placeholder="Enter pan card number" class="required form-control" data-bind="pancard" value="<?php if(isset($_POST['pan_card_number'])) { echo $_POST['pan_card_number']; } else if(isset($dataArr['data']->kyc->pan_card_number)) { echo $dataArr['data']->kyc->pan_card_number; } ?>" />
						</div>
					<?php } else { ?>
						
						<div class="col-md-4 col-sm-4 col-xs-12 form-group">
							<label>Pan Card No<span class="starred">*</span></label>
							<input type="text" name="pan_card_number" id="pan_card_number" placeholder="Enter pan card number" class="required form-control" data-bind="pancard" value="<?php if(isset($_POST['pan_card_number'])) { echo $_POST['pan_card_number']; } else if(isset($dataArr['data']->kyc->pan_card_number)) { echo $dataArr['data']->kyc->pan_card_number; } ?>" />
						</div>
					<?php } ?>
                    <div class="clear"></div>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>UID / Aadhar if Any</label>
						<input type="text" name="uid_number" class="form-control" id="uid_number" placeholder="Enter uid number" data-bind="alphanum" value="<?php if(isset($_POST['uid_number'])) { echo $_POST['uid_number']; } else if(isset($dataArr['data']->kyc->uid_number)) { echo $dataArr['data']->kyc->uid_number; } ?>" />
					</div>
					
					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Proof of identity<span class="starred">*</span></label>
						<div class="clear"></div>
						<input type="radio" name="identity_proof" <?php if(isset($_POST['identity_proof']) && $_POST['identity_proof'] === 'UID') { echo 'checked="checked"'; } else if(isset($dataArr['data']->kyc->identity_proof) && $dataArr['data']->kyc->identity_proof === 'UID') { echo 'checked="checked"'; } ?> value="UID" /><span>UID(Aadhar)</span>
						<input type="radio" name="identity_proof" <?php if(isset($_POST['identity_proof']) && $_POST['identity_proof'] === 'P') { echo 'checked="checked"'; } else if(isset($dataArr['data']->kyc->identity_proof) && $dataArr['data']->kyc->identity_proof === 'P') { echo 'checked="checked"'; } ?> value="P" /><span>Passport</span>
						<input type="radio" name="identity_proof" <?php if(isset($_POST['identity_proof']) && $_POST['identity_proof'] === 'VI') { echo 'checked="checked"'; } else if(isset($dataArr['data']->kyc->identity_proof) && $dataArr['data']->kyc->identity_proof === 'VI') { echo 'checked="checked"'; } ?> value="VI" /><span>Voter Id</span>
						<input type="radio" name="identity_proof" <?php if(isset($_POST['identity_proof']) && $_POST['identity_proof'] === 'DL') { echo 'checked="checked"'; } else if (isset($dataArr['data']->kyc->identity_proof) && $dataArr['data']->kyc->identity_proof === 'DL') { echo 'checked="checked"'; } ?> value="DL" /><span>Driving License</span>
						<input type="radio" name="identity_proof" <?php if(isset($_POST['identity_proof']) && $_POST['identity_proof'] === 'O') { echo 'checked="checked"'; } else if (isset($dataArr['data']->kyc->identity_proof) && $dataArr['data']->kyc->identity_proof === 'O') { echo 'checked="checked"'; } ?> value="O" /><span>Others</span>
					</div>
					
					<?php if ($dataArr['data']->kyc != '' && isset($dataArr['data']->kyc->proof_photograph)) { ?>
						<div class="col-md-4 col-sm-4 col-xs-12 form-group">
							<label>Identity Document</label>
							<div class="clear"></div>
							<input type="file" name="proof_photograph" class="form-control" id="proof_photograph" />
							<a class="pull-right" href="<?php echo PROJECT_URL . '/upload/kyc-docs/' . $dataArr['data']->kyc->proof_photograph; ?>" target="_blank">View</a>
							<span class="greysmalltxt">(supported file extension are pdf, jpg, jpeg, png)</span>
						</div>
					<?php } else { ?>
						<div class="col-md-4 col-sm-4 col-xs-12 form-group">
							<label>Identity Document <span class="starred">*</span></label>
							<div class="clear"></div>
							<input type="file" name="proof_photograph" class="required form-control" id="proof_photograph" />
							<span class="greysmalltxt">(supported file extension are pdf, jpg, jpeg, png)</span>
						</div>
					<?php } ?>
					<div class="clear"></div>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Business Area<span class="starred">*</span></label>
						<select name='business_area' id='business_area' class='required form-control'>
							
							<?php $dataBusinessArrs = $obj_client->get_results("select * from " . $obj_client->getTableName('business_area') . " where status='1' and is_deleted='0' order by business_area_name asc"); ?>
							<?php if (!empty($dataBusinessArrs)) { ?>
								<option value=''>Select Business Area</option>
								<?php foreach ($dataBusinessArrs as $dataBusinessArr) { ?>
									<option value='<?php echo $dataBusinessArr->business_area_id; ?>' <?php if(isset($_POST['business_area']) && $_POST['business_area'] == $dataBusinessArr->business_area_id) { echo 'selected="selected"'; } else if(isset($dataArr['data']->kyc->business_area) && $dataArr['data']->kyc->business_area == $dataBusinessArr->business_area_id) { echo 'selected="selected"'; } ?>><?php echo $dataBusinessArr->business_area_name; ?></option>
								<?php } ?>
							<?php } ?>
						</select>
					</div>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Business Type<span class="starred">*</span></label>
						<select name='business_type' id='business_type' class='required form-control'>

							<?php $dataBusinessTypeArrs = $obj_client->get_results("select * from " . $obj_client->getTableName('business_type') . " where status='1' and is_deleted='0' order by business_name asc"); ?>
							<?php if (!empty($dataBusinessTypeArrs)) { ?>
								<option value=''>Select Business Type</option>
								<?php foreach ($dataBusinessTypeArrs as $dataBusinessTypeArr) { ?>
									<option value='<?php echo $dataBusinessTypeArr->business_id; ?>' <?php if(isset($_POST['business_type']) && $_POST['business_type'] == $dataBusinessTypeArr->business_id) { echo 'selected="selected"'; } else if(isset($dataArr['data']->kyc->business_type) && $dataArr['data']->kyc->business_type == $dataBusinessTypeArr->business_id) { echo "selected='selected'"; } ?>><?php echo $dataBusinessTypeArr->business_name; ?></option>
								<?php } ?>
							<?php } ?>
						</select>
					</div>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Vendor Type<span class="starred">*</span></label>
						<select name='vendor_type' id='vendor_type' class='required form-control'>

							<?php $dataVendorArrs = $obj_client->get_results("select * from " . $obj_client->getTableName('vendor_type') . " where status='1' and is_deleted='0' order by vendor_name asc"); ?>
							<?php if (!empty($dataVendorArrs)) { ?>
								<option value=''>Select Vendor Type</option>
								<?php foreach ($dataVendorArrs as $dataVendorArr) { ?>
									<option value='<?php echo $dataVendorArr->vendor_id; ?>' <?php if(isset($_POST['vendor_type']) && $_POST['vendor_type'] == $dataVendorArr->vendor_id) { echo 'selected="selected"'; } else if(isset($dataArr['data']->kyc->vendor_type) && $dataArr['data']->kyc->vendor_type == $dataVendorArr->vendor_id) { echo "selected='selected'"; } ?>><?php echo $dataVendorArr->vendor_name; ?></option>
								<?php } ?>
							<?php } ?>
						</select>
					</div>
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
					 	<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>ISD Number</label>
						<input type="text" name="isd_number" class="form-control" id="isd_number" placeholder="Enter ISD number"  value="<?php if(isset($_POST['isd_number'])) { echo $_POST['isd_number']; } else if(isset($dataArr['data']->kyc->isd_number)) { echo $dataArr['data']->kyc->isd_number; } ?>" />
					</div>
					<div class="clear"></div>

					<h2 class="greyheading">Identity Details</h2>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
                        <label>Registered Address<span class="starred">*</span></label>
						<textarea placeholder="Registered Address" name="registered_address" id="registered_address" class="required form-control" data-bind="content"><?php if (isset($_POST['registered_address'])) { echo $_POST['registered_address']; } else if (isset($dataArr['data']->kyc->registered_address)) { echo $dataArr['data']->kyc->registered_address; } ?></textarea>
					</div>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>City <span class="starred">*</span></label>
						<input type="text" placeholder="City" name="city" id="city" data-bind="content" class="form-control required" value="<?php if (isset($_POST['city'])) { echo $_POST['city']; } else if (isset($dataArr['data']->kyc->city)) { echo $dataArr['data']->kyc->city; } ?>">
					</div>

                    <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                        <label>State <span class="starred">*</span></label>
                        <select name='state' id='state' class='required form-control'>
							<?php $dataStateArrs = $obj_client->get_results("select * from " . $obj_client->getTableName('state') . " where status='1' and is_deleted='0' order by state_name asc"); ?>
							<?php if (!empty($dataStateArrs)) { ?>
								<option value=''>Select State</option>
								<?php foreach ($dataStateArrs as $dataStateArr) { ?>
									<option value='<?php echo $dataStateArr->state_id; ?>' data-tin="<?php echo $dataStateArr->state_tin; ?>" data-code="<?php echo $dataStateArr->state_code; ?>" <?php if(isset($_POST['state']) && $_POST['state'] == $dataStateArr->state_id) { echo 'selected="selected"'; } else if(isset($dataArr['data']->kyc->state_id) && $dataArr['data']->kyc->state_id == $dataStateArr->state_id) { echo "selected='selected'"; } ?>><?php echo $dataStateArr->state_name; ?></option>
								<?php } ?>
							<?php } ?>
                        </select>
                    </div>
					<div class="clear"></div>
					
					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Zipcode <span class="starred">*</span></label>
						<input type="text" placeholder="Zipcode" name="zipcode" id="zipcode" class="form-control required" data-bind="number" value="<?php if (isset($_POST['zipcode'])) { echo $_POST['zipcode']; } else if (isset($dataArr['data']->kyc->zipcode)) { echo $dataArr['data']->kyc->zipcode; } ?>">
					</div>

                    <?php if ($dataArr['data']->kyc != '' && isset($dataArr['data']->kyc->address_proof)) { ?>

						<div class="col-md-4 col-sm-4 col-xs-12 form-group">
							<label>Address Document</label>
							<div class="clear"></div>
							<input type="file" name="address_proof" class="form-control" id="address_proof" />
							<a class="pull-right" href="<?php echo PROJECT_URL . '/upload/kyc-docs/' . $dataArr['data']->kyc->address_proof; ?>" target="_blank">View</a>
							<span class="greysmalltxt">(supported file extension are pdf, jpg, jpeg, png)</span>
						</div>
                    <?php } else { ?>
                        
						<div class="col-md-4 col-sm-4 col-xs-12 form-group">
							<label>Address Document<span class="starred">*</span></label>
							<div class="clear"></div>
							<input type="file" name="address_proof" class="form-control required" id="address_proof" />
							<span class="greysmalltxt">(supported file extension are pdf, .jpg,.jpeg,.png)</span>
						</div>
                    <?php } ?>
                  
                    <div class="clear"></div>
                    <h2 class="greyheading">Digital Certificate</h2>

					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
						<label>Digital Signature</label>
						<a href="javascript:void(0)">
							<div class="tooltip2">
								<i class="fa fa-info-circle" aria-hidden="true"></i>
								<span class="tooltiptext" style="width:350px;margin-left:-175px;">
									<p class="col-lg-12 text-left">1. Export certificate by using Internet Explorer or via USB.</p>
									<p class="col-lg-12 text-left">2. Select exported file.</p>
								</span>
							</div>
						</a>
						<div class="clear"></div>
						<input type="file" name="certificate" class="form-control" id="certificate" />
						<?php if(isset($dataArr['data']->kyc->digital_certificate_status) && $dataArr['data']->kyc->digital_certificate_status == 1) { echo '<span class="text-success">(Digital Signature is uploaded)</span>'; } else { '<span class="text-danger">(Kindly upload digital signature)</span>'; } ?>
						<div class="clear"></div>
						<span class="greysmalltxt">(supported file extension are cer, crt, der, pem)</span>
					</div>
                    <div class="clear"></div>
					
					<div class="adminformbxsubmit" style="width:100%;">
                        <div class="tc">
							<input type="hidden" name="action" value="submitKYC">
                            <input type='submit' class="btn btn-danger" name='submit' value='submit' id='submit'>
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

			var clientGSTIN = $("#gstin_number").val();
			var clientPAN = $("#pan_card_number").val();

			if(clientGSTIN.search(clientPAN) == -1) {
				jAlert('<div style="color:#f00;background-color:#eddbe3;border-radius:4px;padding:8px 35px 8px 14px;text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);margin-bottom: 18px;border-color: #e8d1df;color: #bd4247;"><i class="fa fa-exclamation-triangle"></i>&nbsp;1.&nbsp;PAN should be valid according to GSTIN.</div>');
				return false;
			}

			if(clientGSTIN.substring(0,2) != $("#state option:selected").attr("data-tin")) {
				jAlert('<div style="color:#f00;background-color:#eddbe3;border-radius:4px;padding:8px 35px 8px 14px;text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);margin-bottom: 18px;border-color: #e8d1df;color: #bd4247;"><i class="fa fa-exclamation-triangle"></i>&nbsp;1.&nbsp;State should be valid according to GSTIN.</div>');
				return false;
			}

			$("#loading").show();
			var kycFormData = new FormData(this);
			kycFormData.append("state_tin", $("#state option:selected").attr("data-tin"));

            $.ajax({
                //data: {kycData:$("#client-kyc").serialize(), action:"submitKYC"},
                data: kycFormData,
				cache: false,
				contentType: false,
				processData: false,
				dataType: 'json',
                type: 'post',
                url: "<?php echo PROJECT_URL; ?>/?ajax=client_submit_kyc",
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