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

        if($obj_client->saveClientGSTIN()){

            $obj_client->redirect(PROJECT_URL."?page=client_gstin");
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

        <h1>Enter GSTIN Number</h1>
        <hr class="headingborder">
        <div class="clear"></div>

        <form name="user-gstin" id="user-gstin" method="POST">

            <div class="adminformbx">

                <div class="kycform">

                    <div class="kycmainbox">

                        <div class="formcol">
                            <label>GSTIN Number<span class="starred">*</span></label>
                            <input type="text" name="gstin_number" id="gstin_number" placeholder="Enter gstin number" class="required" data-bind="alphanum" value="<?php if(isset($_POST['gstin_number'])){ echo $_POST['gstin_number']; } else if(isset($dataArr['data']->gstin->gstin_number)){ echo $dataArr['data']->gstin->gstin_number; } ?>" />
                        </div>
                        
                        <div class="formcol two">
                            <label>GSTIN Issue Date<span class="starred">*</span></label>
                            <input type="text" placeholder="yyyy-mm-dd" name="gstin_issue_date" id="gstin_issue_date" class="required" data-bind="date" value="<?php if(isset($_POST['gstin_issue_date'])){ echo $_POST['gstin_issue_date']; } else if(isset($dataArr['data']->gstin->gstin_issue_date)){ echo $dataArr['data']->gstin->gstin_issue_date; } ?>" />
                        </div>
                        
                        <div class="clear"></div>
                        <div class="clear height10"></div>
                        
                        <div class="tc">
                            <input type='submit' class="btn orangebg" name='submit' value='submit' id='submit'>
                            <input type="button" value="<?php echo ucfirst('Back'); ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=client_registrationchoice"; ?>';" class="btn redbg" class="redbtn marlef10"/>
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
        
        /* gstin issue date */
        $("#gstin_issue_date").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            minDate: '0'
        });

        $('#submit').click(function () {
            var mesg = {};
            if (vali.validate(mesg,'user-gstin')) {
                return true;
            }
            return false;
        });
    });
</script>