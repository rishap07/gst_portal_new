<?php
$obj_client = new client();
if( !isset($_SESSION['user_detail']['user_id']) || $_SESSION['user_detail']['user_id'] == '' ) {
    $obj_client->redirect(PROJECT_URL);
    exit();
}

if( isset($_POST['submit']) && $_POST['submit'] == 'submit' ) {

    if(!isset($_SERVER['HTTP_REFERER']) || empty($_SERVER['HTTP_REFERER'])){
        
        $obj_client->setError('Invalid access to files');
    } else {

        if($obj_client->saveClientKYC()){

            $obj_client->redirect(PROJECT_URL."?page=client_registrationchoice");
        }
    }
}
$dataArr = array();
$dataArr = $obj_client->getUserDetailsById( $obj_client->sanitize($_SESSION['user_detail']['user_id']) );
?>

<!--========================sidemenu over=========================-->
<div class="admincontainer greybg">
    <div class="formcontainer">
        
        <?php $obj_client->showErrorMessage(); ?>
        <?php $obj_client->showSuccessMessge(); ?>
        <?php $obj_client->unsetMessage(); ?>
        
        <h1>Know your Client (KYC)</h1>
        <hr class="headingborder">
        <h2 class="greyheading">Idenity Details</h2>
        <form name="client-kyc" id="client-kyc" method="POST" enctype="multipart/form-data">
            <div class="adminformbx">
                <div class="kycform">
                    
                    <div class="kycmainbox">
                        
                        <div class="clear"></div>
                        
                        <?php if($dataArr['data']->kyc != '' && isset($dataArr['data']->kyc->name)) { ?>
                            
                            <div class="formcol">
                                <label>Name of Applicant<span class="starred">*</span></label>
                                <input type="text" placeholder="Name of Applicant" name="name" id="name" class="required" data-bind="content" value="<?php if(isset($_POST['name'])){ echo $_POST['name']; } else if(isset($dataArr['data']->kyc->name)){ echo $dataArr['data']->kyc->name; } ?>" />
                                <span class="greysmalltxt">(As appearing in supporting / identification document)</span>
                            </div>
                        
                        <?php } else { ?>
                            
                            <div class="formcol">
                                <label>Name of Applicant<span class="starred">*</span></label>
                                <input type="text" placeholder="Name of Applicant" name="name" id="name" class="required" data-bind="content" value="<?php if(isset($_POST['name'])){ echo $_POST['name']; } else if(isset($dataArr['data']->name)){ echo $dataArr['data']->name; } ?>" />
                                <span class="greysmalltxt">(As appearing in supporting / identification document)</span>
                            </div>
                        
                        <?php } ?>
                        
                        <div class="formcol two">
                            <label>Father's / Spouse Name<span class="starred">*</span></label>
                            <input type="text" placeholder="Father's / Spouse Name" name="companion_name" id="companion_name" class="required" data-bind="content" value="<?php if(isset($_POST['companion_name'])){ echo $_POST['companion_name']; } else if(isset($dataArr['data']->kyc->companion_name)){ echo $dataArr['data']->kyc->companion_name; } ?>" />
                        </div>

                        <div class="formcol third">
                            <label>Gender<span class="starred">*</span></label>
                            <div class="clear"></div>
                            <input type="radio" name="gender" <?php if(isset($_POST['gender']) && $_POST['gender'] === 'M'){ echo 'checked="checked"'; } else if(isset($dataArr['data']->kyc->gender) && $dataArr['data']->kyc->gender === 'M') { echo 'checked="checked"'; } ?> value="M" /><span>Male</span>
                            <input type="radio" name="gender" <?php if(isset($_POST['gender']) && $_POST['gender'] === 'F'){ echo 'checked="checked"'; } else if(isset($dataArr['data']->kyc->gender) && $dataArr['data']->kyc->gender === 'F') { echo 'checked="checked"'; } ?> value="F" /><span>Female</span>
                        </div>
                        
                        <div class="clear"></div>
                        
                        <div class="formcol">
                            <label>Martial Status<span class="starred">*</span></label>
                            <div class="clear"></div>
                            <input type="radio" name="martial_status" <?php if(isset($_POST['martial_status']) && $_POST['martial_status'] === 'S'){ echo 'checked="checked"'; } else if(isset($dataArr['data']->kyc->martial_status) && $dataArr['data']->kyc->martial_status === 'S') { echo 'checked="checked"'; } ?> value="S" /><span>Single</span>
                            <input type="radio" name="martial_status" <?php if(isset($_POST['martial_status']) && $_POST['martial_status'] === 'M'){ echo 'checked="checked"'; } else if(isset($dataArr['data']->kyc->martial_status) && $dataArr['data']->kyc->martial_status === 'M') { echo 'checked="checked"'; } ?> value="M" /><span>Married</span>
                        </div>
                        
                        <div class="formcol ">
                            <label>Date of Birth <span class="starred">*</span></label>
                            <input type="text" placeholder="yyyy-mm-dd" name="date_of_birth" id="date_of_birth" class="required" data-bind="date" value="<?php if(isset($_POST['date_of_birth'])){ echo $_POST['date_of_birth']; } else if(isset($dataArr['data']->kyc->date_of_birth)){ echo $dataArr['data']->kyc->date_of_birth; } ?>" />
                        </div>
                        
                        <div class="formcol third">
                            <label>Nationality<span class="starred">*</span></label>
                            <div class="clear"></div>
                            <input type="radio" name="nationality" <?php if(isset($_POST['nationality']) && $_POST['nationality'] === 'I'){ echo 'checked="checked"'; } else if(isset($dataArr['data']->kyc->nationality) && $dataArr['data']->kyc->nationality === 'I') { echo 'checked="checked"'; } ?> value="I" /><span>Indian</span>
                            <input type="radio" name="nationality" <?php if(isset($_POST['nationality']) && $_POST['nationality'] === 'O'){ echo 'checked="checked"'; } else if(isset($dataArr['data']->kyc->nationality) && $dataArr['data']->kyc->nationality === 'O') { echo 'checked="checked"'; } ?> value="O" /><span>Other</span>
                        </div>
                        
                        <div class="formcol">
                            <label>Status<span class="starred">*</span></label>
                            <div class="clear"></div>      
                            <input type="radio" name="status" <?php if(isset($_POST['status']) && $_POST['status'] === '1'){ echo 'checked="checked"'; } else if(isset($dataArr['data']->kyc->status) && $dataArr['data']->kyc->status === '1') { echo 'checked="checked"'; } ?> value="1" /><span>Yes</span>
                            <input type="radio" name="status" <?php if(isset($_POST['status']) && $_POST['status'] === '0'){ echo 'checked="checked"'; } else if(isset($dataArr['data']->kyc->status) && $dataArr['data']->kyc->status === '0') { echo 'checked="checked"'; } ?> value="0" /><span>No</span>
                        </div>
                        
                        <div class="formcol two">
                            <label>Pan Card No<span class="starred">*</span></label>
                            <input type="text" name="pan_card_number" id="pan_card_number" placeholder="Enter pan card number" class="required" data-bind="pancard" value="<?php if(isset($_POST['pan_card_number'])){ echo $_POST['pan_card_number']; } else if(isset($dataArr['data']->kyc->pan_card_number)){ echo $dataArr['data']->kyc->pan_card_number; } ?>" />
                        </div>
                        
                        <div class="formcol third">
                            <label>UID if Any</label>
                            <input type="text" name="uid_number" id="uid_number" placeholder="Enter uid number" data-bind="alphanum" value="<?php if(isset($_POST['uid_number'])){ echo $_POST['uid_number']; } else if(isset($dataArr['data']->kyc->uid_number)){ echo $dataArr['data']->kyc->uid_number; } ?>" />
                        </div>

                        <div class="formcol">
                            <label>Proof of identity submitted for PAN exempt cases<span class="starred">*</span></label>
                            <div class="clear"></div>
                            <input type="radio" name="identity_proof" <?php if(isset($_POST['identity_proof']) && $_POST['identity_proof'] === 'UID'){ echo 'checked="checked"'; } else if(isset($dataArr['data']->kyc->identity_proof) && $dataArr['data']->kyc->identity_proof === 'UID') { echo 'checked="checked"'; } ?> value="UID" /><span>UID(Aadhar)</span>
                            <input type="radio" name="identity_proof" <?php if(isset($_POST['identity_proof']) && $_POST['identity_proof'] === 'P'){ echo 'checked="checked"'; } else if(isset($dataArr['data']->kyc->identity_proof) && $dataArr['data']->kyc->identity_proof === 'P') { echo 'checked="checked"'; } ?> value="P" /><span>Passport</span>
                            <input type="radio" name="identity_proof" <?php if(isset($_POST['identity_proof']) && $_POST['identity_proof'] === 'VI'){ echo 'checked="checked"'; } else if(isset($dataArr['data']->kyc->identity_proof) && $dataArr['data']->kyc->identity_proof === 'VI') { echo 'checked="checked"'; } ?> value="VI" /><span>Voter Id</span>
                            <input type="radio" name="identity_proof" <?php if(isset($_POST['identity_proof']) && $_POST['identity_proof'] === 'DL'){ echo 'checked="checked"'; } else if(isset($dataArr['data']->kyc->identity_proof) && $dataArr['data']->kyc->identity_proof === 'DL') { echo 'checked="checked"'; } ?> value="DL" /><span>Driving License</span>
                            <input type="radio" name="identity_proof" <?php if(isset($_POST['identity_proof']) && $_POST['identity_proof'] === 'O'){ echo 'checked="checked"'; } else if(isset($dataArr['data']->kyc->identity_proof) && $dataArr['data']->kyc->identity_proof === 'O') { echo 'checked="checked"'; } ?> value="O" /><span>Others</span>
                        </div>
                        
                        <?php if($dataArr['data']->kyc != '' && isset($dataArr['data']->kyc->proof_photograph)) { ?>
                        
                            <div class="formcol two">
                                <label>Photograph</label>
                                <div class="clear"></div>
                                <input type="file" name="proof_photograph" id="proof_photograph" />
                                <a class="pull-right" href="<?php echo PROJECT_URL . '/upload/kyc-docs/' . $dataArr['data']->kyc->proof_photograph; ?>" target="_blank">View</a>
                            </div>
                        
                        <?php } else { ?>
                            
                            <div class="formcol two">
                                <label>Photograph <span class="starred">*</span></label>
                                <div class="clear"></div>
                                <input type="file" name="proof_photograph" class="required" id="proof_photograph" />
                            </div>
                        
                        <?php } ?>
                        
                        <div class="clear"></div>
                        <h2>B. Address Details</h2>
                        <div class="formcol">
                            <label>Address for Correspondence<span class="starred">*</span></label>
                            <input type="text" placeholder="Address for Correspondence" name="correspondence_address" id="correspondence_address" class="required" data-bind="address" value="<?php if(isset($_POST['correspondence_address'])){ echo $_POST['correspondence_address']; } else if(isset($dataArr['data']->kyc->correspondence_address)){ echo $dataArr['data']->kyc->correspondence_address; } ?>" />
                        </div>
                        
                        <div class="formcol two">
                            <label>Contact Details<span class="starred">*</span></label>
                            <textarea placeholder="Contact Details" name="correspondence_details" id="correspondence_details" class="required" data-bind="content"><?php if(isset($_POST['correspondence_details'])){ echo $_POST['correspondence_details']; } else if(isset($dataArr['data']->kyc->correspondence_details)){ echo $dataArr['data']->kyc->correspondence_details; } ?></textarea>
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
                        
                        <div class="formcol">
                            <label>Permanent Address<span class="starred">*</span></label>
                            <input type="text" placeholder="Permanent Address" name="permanent_address" id="permanent_address" class="required" data-bind="address" value="<?php if(isset($_POST['permanent_address'])){ echo $_POST['permanent_address']; } else if(isset($dataArr['data']->kyc->permanent_address)){ echo $dataArr['data']->kyc->permanent_address; } ?>" />
                        </div>
                        
                        <div class="formcol two">
                            <label>Occupation<span class="starred">*</span></label>
                            <input type="text" placeholder="Occupation" name="occupation" id="occupation" class="required" data-bind="content" value="<?php if(isset($_POST['occupation'])){ echo $_POST['occupation']; } else if(isset($dataArr['data']->kyc->occupation)){ echo $dataArr['data']->kyc->occupation; } ?>" />
                        </div>
                        
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
            maxDate: '-1d'
        });
        
        $('#submit').click(function () {
            var mesg = {};
            if (vali.validate(mesg,'client-kyc')) {
                return true;
            }
            return false;
        });
    });
</script>