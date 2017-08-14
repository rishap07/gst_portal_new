<?php
$obj_adminsetting = new adminsetting();


if (!isset($_SESSION['user_detail']['user_id']) || $_SESSION['user_detail']['user_id'] == '') {
    $obj_adminsetting->redirect(PROJECT_URL);
    exit();
}


if (!$obj_adminsetting->can_read('coupon_update')) {

    $obj_adminsetting->setError($obj_adminsetting->getValMsg('can_read'));
    $obj_adminsetting->redirect(PROJECT_URL . "/?page=dashboard");
    exit();
}

/* get current user data */
$dataCurrentArr = array();
if (isset($_GET["action"]) && ($_GET["action"] == "editSetting") && (isset($_GET["id"]))) {

    $sql = "select  *,count(id) as total from gst_admin_setting where id=" . $_GET["id"] . "";
    $dataCurrentArr = $obj_adminsetting->get_results($sql);
    $dataCurrentArr[0]->total;
//$dataCurrentArr = $obj_client->getUserDetailsById($obj_client->sanitize($_SESSION['user_detail']['user_id']));
}

if (isset($_POST['submit']) && $_POST['submit'] == 'submit') {

//if(!$obj_client->can_create('coupon_update')) {
    if ($obj_adminsetting->updateSetting()) {
        $obj_adminsetting->redirect(PROJECT_URL . "/?page=adminsetting_list");
    }
//}
}
if (isset($_POST['submit']) && $_POST['submit'] == 'update') {

    //if(!$obj_client->can_create('coupon_update')) {
    if ($obj_adminsetting->updateSetting()) {

        $obj_adminsetting->redirect(PROJECT_URL . "/?page=adminsetting_list");
    }
}
?>

<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>AdminSetting</h1></div>
        <div class="clear"></div>
        <?php $obj_adminsetting->showErrorMessage(); ?>
        <?php $obj_adminsetting->showSuccessMessge(); ?>
        <?php $obj_adminsetting->unsetMessage(); ?>


        <div class="clear"></div>
        <div class="whitebg formboxcontainer">
            <form name="client-user" enctype="multipart/form-data" id="client-user" method="POST">
                <h2 class="greyheading">
                    <?php
                    if (isset($_GET["action"]) && ($_GET["action"] == "editSetting") && (isset($_GET["id"]))) {
                        echo 'Update';
                    } else {
                        echo 'Create New';
                    }
                    ?>Admin Setting</h2>

                <div class="row">


                   
                    <div class="col-md-4 col-sm-4 col-xs-12 form-group">

                        <label>TollFree<span class="starred">*</span></label>
                         <textarea placeholder="TollFree Setting text"   name="tollfree_setting" id="tollfree_setting" class="required form-control"><?php
                        if (isset($_POST['tollfree_setting'])) {
                            echo $_POST['tollfree_setting'];
                        } else if (isset($dataCurrentArr[0]->tollfree_setting)) {
                            echo $dataCurrentArr[0]->tollfree_setting;
                        }
                        ?></textarea>
						
                        
                    </div>
					  <div class="col-md-4 col-sm-4 col-xs-12 form-group">

                        <label>LiveChat<span class="starred">*</span></label>
                         <textarea placeholder="TollFree Setting text"   name="livechat_setting" id="livechat_setting" class="required form-control"><?php
                        if (isset($_POST['livechat_setting'])) {
                            echo $_POST['livechat_setting'];
                        } else if (isset($dataCurrentArr[0]->livechat_setting)) {
                            echo $dataCurrentArr[0]->livechat_setting;
                        }
                        ?></textarea>
						
                        
                    </div>
                                
                 
                    
					 <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                        <label>Status<span class="starred">*</span></label>
                        <select name='setting_status' id='setting_status' class='required form-control'>

                            <option value='1' <?php
                            if (isset($dataCurrentArr[0]->status) && $dataCurrentArr[0]->status == 1) {
                                echo "selected='selected'";
                            }
                            ?>>Active</option>
                            <option value='0' <?php
                            if (isset($dataCurrentArr[0]->status) && $dataCurrentArr[0]->status == 0) {
                                echo "selected='selected'";
                            }
                            ?>>InActive</option>

                        </select></div><div class="clear"> </div>
                   
                   
                  
                 <div class="adminformbxsubmit" style="width:100%;">




                        <div class="tc">
                            <input type='submit' class="btn btn-default btn-success" name='submit' value='<?php
                            if (isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == "editNotification") {
                                echo 'update';
                            } else {
                                echo 'submit';
                            }
                            ?>' id='submit'>
                            <input type="button" value="<?php echo ucfirst('Back'); ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=dashboard"; ?>';" class="btn btn-danger"/>
                        </div>


                    </div>

                </div>

        </div>
        </form>
    </div>
</div>


<!--========================sidemenu over=========================-->

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
