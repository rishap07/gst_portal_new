<?php
$obj_client = new client();

if(!$obj_client->can_read('client_kyc')) {

    $obj_client->setError($obj_client->getValMsg('can_read'));
    $obj_client->redirect(PROJECT_URL."/?page=dashboard");
    exit();
}

if(!$obj_client->can_create('client_kyc')) {

    $obj_client->setError($obj_client->getValMsg('can_create'));
    $obj_client->redirect(PROJECT_URL."/?page=dashboard");
    exit();
}

if(!$obj_client->can_update('client_kyc')) {

    $obj_client->setError($obj_client->getValMsg('can_update'));
    $obj_client->redirect(PROJECT_URL."/?page=dashboard");
    exit();
}

$dataArr = array();
$dataArr = $obj_client->getUserDetailsById( $obj_client->sanitize($_SESSION['user_detail']['user_id']) );

if( isset($_POST['submit']) && $_POST['submit'] == 'submit' ) {

    if(!isset($_SERVER['HTTP_REFERER']) || empty($_SERVER['HTTP_REFERER'])){
        
        $obj_client->setError('Invalid access to files');
    } else {

        if($obj_client->saveClientKYC()){
            $obj_client->redirect(PROJECT_URL."?page=client_kycupdate");
        }
    }
}
?>
<!--========================sidemenu over=========================-->
<div class="admincontainer greybg">
    <div class="formcontainer">
        
        <?php $obj_client->showErrorMessage(); ?>
        <?php $obj_client->showSuccessMessge(); ?>
        <?php $obj_client->unsetMessage(); ?>
        
        <h1>Know your Client (KYC)</h1>
        <hr class="headingborder">
        <h2 class="greyheading">Identity Details</h2>
        <form name="client-kyc" id="client-kyc" method="POST" enctype="multipart/form-data">
            <div class="adminformbx">
                <div class="kycform">
                    
                    <div class="kycmainbox">
                        
                        <div class="clear"></div>

                        <?php if($dataArr['data']->kyc != '' && isset($dataArr['data']->kyc->name)) { ?>

                            <div class="formcol">
                                <label>Name of Applicant / Company Name<span class="starred">*</span></label>
                                <input type="text" placeholder="Name of Applicant" name="name" id="name" class="required" data-bind="content" value="<?php if(isset($_POST['name'])){ echo $_POST['name']; } else if(isset($dataArr['data']->kyc->name)){ echo $dataArr['data']->kyc->name; } ?>" />
                                <span class="greysmalltxt">(As appearing in supporting / identification document)</span>
                            </div>
                        
                        <?php } else { ?>
                            
                            <div class="formcol">
                                <label>Name of Applicant / Company Name<span class="starred">*</span></label>
                                <input type="text" placeholder="Name of Applicant" name="name" id="name" class="required" data-bind="content" value="<?php if(isset($_POST['name'])){ echo $_POST['name']; } else if(isset($dataArr['data']->name)){ echo $dataArr['data']->name; } ?>" />
                                <span class="greysmalltxt">(As appearing in supporting / identification document)</span>
                            </div>
                        
                        <?php } ?>
						
						<?php if($dataArr['data']->kyc != '' && isset($dataArr['data']->kyc->email)) { ?>

							<div class="formcol two">
								<label>Email Address<span class="starred">*</span></label>
								<input type="text" placeholder="Enter Email Address" name="email" id="email" class="required" data-bind="email" value="<?php if(isset($_POST['email'])){ echo $_POST['email']; } else if(isset($dataArr['data']->kyc->email)){ echo $dataArr['data']->kyc->email; } ?>" />
							</div>

						<?php } else { ?>
						
							<div class="formcol two">
								<label>Email Address<span class="starred">*</span></label>
								<input type="text" placeholder="Enter Email Address" name="email" id="email" class="required" data-bind="email" value="<?php if(isset($_POST['email'])){ echo $_POST['email']; } else if(isset($dataArr['data']->email)){ echo $dataArr['data']->email; } ?>" />
							</div>

						<?php } ?>
						
						<?php if($dataArr['data']->kyc != '' && isset($dataArr['data']->kyc->phone_number)) { ?>

							<div class="formcol third">
								<label>Contact Number<span class="starred">*</span></label>
								<input type="text" placeholder="Enter Contact Number" name="phone_number" id="phone_number" class="required" data-bind="mobilenumber" value="<?php if(isset($_POST['phone_number'])){ echo $_POST['phone_number']; } else if(isset($dataArr['data']->kyc->phone_number)){ echo $dataArr['data']->kyc->phone_number; } ?>" />
							</div>

						<?php } else { ?>
							
							<div class="formcol third">
								<label>Contact Number<span class="starred">*</span></label>
								<input type="text" placeholder="Enter Contact Number" name="phone_number" id="phone_number" class="required" data-bind="mobilenumber" value="<?php if(isset($_POST['phone_number'])){ echo $_POST['phone_number']; } else if(isset($dataArr['data']->phone_number)){ echo $dataArr['data']->phone_number; } ?>" />
							</div>

						<?php } ?>

                        <div class="clear"></div>
						
						<div class="formcol">
                            <label>Date of Birth / Company Registered Date<span class="starred">*</span></label>
                            <input type="text" placeholder="yyyy-mm-dd" name="date_of_birth" id="date_of_birth" class="required" data-bind="date" value="<?php if(isset($_POST['date_of_birth'])){ echo $_POST['date_of_birth']; } else if(isset($dataArr['data']->kyc->date_of_birth)){ echo $dataArr['data']->kyc->date_of_birth; } ?>" />
                        </div>

						<div class="formcol two">
                            <label>GSTIN Number<span class="starred">*</span></label>
                            <input type="text" name="gstin_number" id="gstin_number" placeholder="Enter gstin number" class="required" data-bind="gstin" value="<?php if(isset($_POST['gstin_number'])){ echo $_POST['gstin_number']; } else if(isset($dataArr['data']->kyc->gstin_number)){ echo $dataArr['data']->kyc->gstin_number; } ?>" />
                        </div>

						<div class="formcol third">
                            <label>Pan Card No<span class="starred">*</span></label>
                            <input type="text" name="pan_card_number" id="pan_card_number" placeholder="Enter pan card number" class="required" data-bind="pancard" value="<?php if(isset($_POST['pan_card_number'])){ echo $_POST['pan_card_number']; } else if(isset($dataArr['data']->kyc->pan_card_number)){ echo $dataArr['data']->kyc->pan_card_number; } ?>" />
                        </div>
						
						<div class="clear"></div>

                        <div class="formcol">
                            <label>UID / Aadhar if Any</label>
                            <input type="text" name="uid_number" id="uid_number" placeholder="Enter uid number" data-bind="alphanum" value="<?php if(isset($_POST['uid_number'])){ echo $_POST['uid_number']; } else if(isset($dataArr['data']->kyc->uid_number)){ echo $dataArr['data']->kyc->uid_number; } ?>" />
                        </div>

                        <div class="formcol two">
                            <label>Proof of identity submitted for PAN exempt cases<span class="starred">*</span></label>
                            <div class="clear"></div>
                            <input type="radio" name="identity_proof" <?php if(isset($_POST['identity_proof']) && $_POST['identity_proof'] === 'UID'){ echo 'checked="checked"'; } else if(isset($dataArr['data']->kyc->identity_proof) && $dataArr['data']->kyc->identity_proof === 'UID') { echo 'checked="checked"'; } ?> value="UID" /><span>UID(Aadhar)</span>
                            <input type="radio" name="identity_proof" <?php if(isset($_POST['identity_proof']) && $_POST['identity_proof'] === 'P'){ echo 'checked="checked"'; } else if(isset($dataArr['data']->kyc->identity_proof) && $dataArr['data']->kyc->identity_proof === 'P') { echo 'checked="checked"'; } ?> value="P" /><span>Passport</span>
                            <input type="radio" name="identity_proof" <?php if(isset($_POST['identity_proof']) && $_POST['identity_proof'] === 'VI'){ echo 'checked="checked"'; } else if(isset($dataArr['data']->kyc->identity_proof) && $dataArr['data']->kyc->identity_proof === 'VI') { echo 'checked="checked"'; } ?> value="VI" /><span>Voter Id</span>
                            <input type="radio" name="identity_proof" <?php if(isset($_POST['identity_proof']) && $_POST['identity_proof'] === 'DL'){ echo 'checked="checked"'; } else if(isset($dataArr['data']->kyc->identity_proof) && $dataArr['data']->kyc->identity_proof === 'DL') { echo 'checked="checked"'; } ?> value="DL" /><span>Driving License</span>
                            <input type="radio" name="identity_proof" <?php if(isset($_POST['identity_proof']) && $_POST['identity_proof'] === 'O'){ echo 'checked="checked"'; } else if(isset($dataArr['data']->kyc->identity_proof) && $dataArr['data']->kyc->identity_proof === 'O') { echo 'checked="checked"'; } ?> value="O" /><span>Others</span>
                        </div>
						
						<?php if($dataArr['data']->kyc != '' && isset($dataArr['data']->kyc->proof_photograph)) { ?>

                            <div class="formcol third">
                                <label>Proof of identity</label>
                                <div class="clear"></div>
                                <input type="file" name="proof_photograph" id="proof_photograph" />
                                <a class="pull-right" href="<?php echo PROJECT_URL . '/upload/kyc-docs/' . $dataArr['data']->kyc->proof_photograph; ?>" target="_blank">View</a>
                            </div>

                        <?php } else { ?>

                            <div class="formcol third">
                                <label>Proof of identity <span class="starred">*</span></label>
                                <div class="clear"></div>
                                <input type="file" name="proof_photograph" class="required" id="proof_photograph" />
                            </div>

                        <?php } ?>
                        
						<div class="clear"></div>

						<div class="formcol">
							<label>Business Type<span class="starred">*</span></label>
                            <select name='business_type' id='business_type' class='required'>
								<?php $dataBusinessArrs = $obj_client->get_results("select * from ".$obj_client->getTableName('business_type')." where status='1' and is_deleted='0' order by business_name asc"); ?>
                                <?php if(!empty($dataBusinessArrs)) { ?>
                                    <option value=''>Select Business Type</option>
                                    <?php foreach($dataBusinessArrs as $dataBusinessArr) { ?>

                                        <?php if(isset($_POST['business_type']) && $_POST['business_type'] == $dataBusinessArr->business_id){ ?>
                                            <option value='<?php echo $dataBusinessArr->business_id; ?>' selected="selected"><?php echo $dataBusinessArr->business_name; ?></option>
                                        <?php } else if(isset($dataArr['data']->kyc->business_type) && $dataArr['data']->kyc->business_type == $dataBusinessArr->business_id){ ?>
                                            <option value='<?php echo $dataBusinessArr->business_id; ?>' selected="selected"><?php echo $dataBusinessArr->business_name; ?></option>
                                        <?php } else { ?>
                                            <option value='<?php echo $dataBusinessArr->business_id; ?>'><?php echo $dataBusinessArr->business_name; ?></option>
                                        <?php } ?>

                                    <?php } ?>
                                <?php } ?>
                            </select>
						</div>

                        <div class="clear"></div>

                        <h2>Business Address Details</h2>
                        
						<div class="formcol">
                            <label>Registered Address<span class="starred">*</span></label>
                            <input type="text" placeholder="Registered Address" name="registered_address" id="registered_address" class="required" data-bind="address" value="<?php if(isset($_POST['registered_address'])){ echo $_POST['registered_address']; } else if(isset($dataArr['data']->kyc->registered_address)){ echo $dataArr['data']->kyc->registered_address; } ?>" />
                        </div>
                        
                        <div class="formcol two">
                            <label>State <span class="starred">*</span></label>
                            <select name='state' id='state' class='required'>
                                <?php $dataStateArrs = $obj_client->get_results("select * from ".$obj_client->getTableName('state')." where status='1' and is_deleted='0' order by state_name asc"); ?>
                                <?php if(!empty($dataStateArrs)) { ?>
                                    <option value=''>Select State</option>
                                    <?php foreach($dataStateArrs as $dataStateArr) { ?>

                                        <?php if(isset($_POST['state']) && $_POST['state'] == $dataStateArr->state_id){ ?>
                                            <option value='<?php echo $dataStateArr->state_id; ?>' selected="selected" data-code="<?php echo $dataStateArr->state_code;?>"><?php echo $dataStateArr->state_name; ?></option>
                                        <?php } else if(isset($dataArr['data']->kyc->state_id) && $dataArr['data']->kyc->state_id == $dataStateArr->state_id){ ?>
                                            <option value='<?php echo $dataStateArr->state_id; ?>' selected="selected" data-code="<?php echo $dataStateArr->state_code;?>"><?php echo $dataStateArr->state_name; ?></option>
                                        <?php } else { ?>
                                            <option value='<?php echo $dataStateArr->state_id; ?>' data-code="<?php echo $dataStateArr->state_code;?>"><?php echo $dataStateArr->state_name; ?></option>
                                        <?php } ?>

                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
						
                        <?php if($dataArr['data']->kyc != '' && isset($dataArr['data']->kyc->address_proof)) { ?>
                        
                            <div class="formcol third">
                                <label>Proof of address</label>
                                <div class="clear"></div>
                                <input type="file" name="address_proof" id="address_proof" />
                                <a class="pull-right" href="<?php echo PROJECT_URL . '/upload/kyc-docs/' . $dataArr['data']->kyc->address_proof; ?>" target="_blank">View</a>
                            </div>
                        
                        <?php } else { ?>
                            
                            <div class="formcol third">
                                <label>Proof of address<span class="starred">*</span></label>
                                <div class="clear"></div>
                                <input type="file" name="address_proof" class="required" id="address_proof" />
                            </div>
                        
                        <?php } ?>
                        
                        <div class="clear"></div>

                        <div class="clear height10"></div>
                        <div class="adminformbxsubmit" style="width:100%;">
                            <div class="tc">
                                <input type='submit' class="btn orangebg" name='submit' value='submit' id='submit'>
                                <input type="button" value="<?php echo ucfirst('Back'); ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=dashboard"; ?>';" class="btn redbg" class="redbtn marlef10"/>
                            </div>
                        </div>
                    
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    $(document).ready(function () {
        
        /* Date of birth datepicker */
        $( "#date_of_birth" ).datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
			yearRange: '1900:<?php echo date("Y"); ?>',
            maxDate: '0'
        });
		
		/* select2 js for business type */
        $("#business_type").select2();
		
		/* select2 js for state */
        $("#state").select2();

        $('#submit').click(function () {
            var mesg = {};
            if (vali.validate(mesg,'client-kyc')) {
                return true;
            }
            return false;
        });
    });
</script>