<?php

$obj_faq = new faq();
if (!isset($_SESSION['user_detail']['user_id']) || $_SESSION['user_detail']['user_id'] == '') {
    $obj_client->redirect(PROJECT_URL);
    exit();
}


/*if (!$obj_client->can_read('faq_list')) {

    $obj_client->setError($obj_client->getValMsg('can_read'));
    $obj_client->redirect(PROJECT_URL . "/?page=dashboard");
    exit();
}*/

/* get current user data */
$dataCurrentArr = array();
if (isset($_GET["action"]) && ($_GET["action"] == "editFaq") && (isset($_GET["id"]))) {

    $sql = "select  *,count(id) as totalfaq from gst_faq where id=" . $_GET["id"] . "";
    $dataCurrentArr = $obj_faq->get_row($sql);
}

if (isset($_POST['submit']) && $_POST['submit'] == 'submit') {

//if(!$obj_client->can_create('coupon_update')) {
    if ($obj_faq->updateFaq()) {
        $obj_faq->redirect(PROJECT_URL . "/?page=faq_list");
    }
//}
}
if (isset($_POST['submit']) && $_POST['submit'] == 'update') {

    //if(!$obj_client->can_create('coupon_update')) {
    if ($obj_faq->updateFaq()) {

        $obj_faq->redirect(PROJECT_URL . "/?page=faq_list");
    }
}
?>
<script src="<?php echo PROJECT_URL;?>/editor/ckeditor/ckeditor.js"></script>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>FAQ</h1></div>
        <div class="clear"></div>
        <?php $obj_faq->showErrorMessage(); ?>
        <?php $obj_faq->showSuccessMessge(); ?>
        <?php $obj_faq->unsetMessage(); ?>


        <div class="clear"></div>
        <div class="whitebg formboxcontainer">
            <form name="client-user" id="client-user" method="POST" enctype="multipart/form-data">
                <h2 class="greyheading">
                    <?php
                    if (isset($_GET["action"]) && ($_GET["action"] == "editFaq") && (isset($_GET["id"]))) {
                        echo 'Update';
                    } else {
                        echo 'Create New';
                    }
                    ?> FAQ Information</h2>

                <div class="row">


                    <div class="col-md-6 col-sm-6 col-xs-12 form-group">

                        <label>Question<span class="starred">*</span></label>


                        <input type="text" name="question" id="question" placeholder="Enter Question " class="required form-control" data-bind="content" value="<?php
                        if (isset($_POST['question'])) {
                            echo $_POST['question'];
                        } else if (isset($dataCurrentArr->question)) {
                            echo $dataCurrentArr->question;
                        }
                        ?>" />


                    </div>
                   
                     <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <label>Status<span class="starred">*</span></label>
                        <select name='faq_status' id='faq_status' class='required form-control'>

                            <option value='1' <?php
                            if (isset($dataCurrentArr->status) && $dataCurrentArr->status == 1) {
                                echo "selected='selected'";
                            }
                            ?>>Active</option>
                            <option value='0' <?php
                            if (isset($dataCurrentArr->status) && $dataCurrentArr->status == 0) {
                                echo "selected='selected'";
                            }
                            ?>>InActive</option>

                        </select></div><div class="clear"> </div>
                   
                    <div class="clear"></div>


                     
                 
                     
					
                    <div class="clear"></div>
					 <div class="col-md-12 col-sm-12 col-xs-12 form-group">

                        <label>Answer<span class="starred">*</span></label>
                         <textarea placeholder="Enter Answer" maxlength="255"  name="answer" id="answer" class="form-control"><?php
                        if (isset($_POST['answer'])) {
                            echo $_POST['answer'];
                        } else if (isset($dataCurrentArr->answer)) {
                            echo $dataCurrentArr->answer;
                        }
                        ?></textarea>
						 
                      
                    </div>  <div class="clear"></div>
                 <div class="adminformbxsubmit" style="width:100%;">




                        <div class="tc">
                            <input type='submit' class="btn btn-default btn-success" name='submit' value='<?php
                            if (isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == "editFaq") {
                                echo 'update';
                            } else {
                                echo 'submit';
                            }
                            ?>' id='submit'>
                            <input type="button" value="<?php echo ucfirst('Back'); ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=faq_list"; ?>';" class="btn btn-danger"/>
                        </div>


                    </div>

                </div>

        </div>
        </form>
    </div>
</div>


<!--========================sidemenu over=========================-->
<script>
    CKEDITOR.replace('answer', {
        filebrowserUploadUrl: "<?php echo PROJECT_URL; ?>/editor/ckeditor/ckupload.php",
        filebrowserBrowseUrl: "<?php echo PROJECT_URL; ?>/editor/ckeditor/browse.php?type=Images"
    });
</script>
<script>
    $(document).ready(function () {
        $('#submit').click(function () {
            var mesg = {};
            if (vali.validate(mesg, 'client-user')) {
                return true;
            }
            return false;
        });
    });
</script>
<script type="text/javascript">
    function isNumberKey(evt)
    {

        var charCode = (evt.which) ? evt.which : event.keyCode

        if ((charCode >= 40) && (charCode <= 57) && (charCode != 47) && (charCode != 42) && (charCode != 43) && (charCode != 44) && (charCode != 45) || (charCode == 8))
        {
            return true;

        } else
        {
            return false;

        }
    }

</script>
<script>
    $(document).ready(function () {
        $("*[name=end_date],*[name=start_date]").datetimepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            yearRange: '2017:<?php echo date("Y"); ?>',
            maxDate: '0:<?php echo date("Y"); ?>'
        });

    });
</script>
