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

        if($obj_client->saveClientGSTN()){

            $obj_client->redirect(PROJECT_URL."?page=client_gstn");
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

        <h1>Enter GSTN Number</h1>
        <hr class="headingborder">
        <div class="clear"></div>

        <form name="user-gstn" id="user-gstn" method="POST">

            <div class="adminformbx">

                <div class="kycform">

                    <div class="kycmainbox">

                        <div class="formcol">
                            <label>GSTN Number<span class="starred">*</span></label>
                            <input type="text" name="gstn_number" id="gstn_number" placeholder="Enter gstn number" class="required" data-bind="alphanum" value="<?php if(isset($_POST['gstn_number'])){ echo $_POST['gstn_number']; } else if(isset($dataArr['data']->gstn->gstn_number)){ echo $dataArr['data']->gstn->gstn_number; } ?>" />
                        </div>
                        
                        <div class="formcol two">
                            <label>GSTN Issue Date<span class="starred">*</span></label>
                            <input type="text" placeholder="yyyy-mm-dd" name="gstn_issue_date" id="gstn_issue_date" class="required" data-bind="date" value="<?php if(isset($_POST['gstn_issue_date'])){ echo $_POST['gstn_issue_date']; } else if(isset($dataArr['data']->gstn->gstn_issue_date)){ echo $dataArr['data']->gstn->gstn_issue_date; } ?>" />
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
        
        /* gstn issue date */
        $("#gstn_issue_date").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            minDate: '0'
        });

        $('#submit').click(function () {
            var mesg = {};
            if (vali.validate(mesg,'user-gstn')) {
                return true;
            }
            return false;
        });
    });
</script>