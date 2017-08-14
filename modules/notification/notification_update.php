<?php
$obj_client = new client();

$obj_notification = new notification();
if (!isset($_SESSION['user_detail']['user_id']) || $_SESSION['user_detail']['user_id'] == '') {
    $obj_client->redirect(PROJECT_URL);
    exit();
}


if (!$obj_client->can_read('coupon_update')) {

    $obj_client->setError($obj_client->getValMsg('can_read'));
    $obj_client->redirect(PROJECT_URL . "/?page=dashboard");
    exit();
}

/* get current user data */
$dataCurrentArr = array();
if (isset($_GET["action"]) && ($_GET["action"] == "editNotification") && (isset($_GET["id"]))) {

    $sql = "select  *,count(notification_id) as totalnotification from gst_notification where notification_id=" . $_GET["id"] . "";
    $dataCurrentArr = $obj_client->get_results($sql);
    $dataCurrentArr[0]->totalnotification;
//$dataCurrentArr = $obj_client->getUserDetailsById($obj_client->sanitize($_SESSION['user_detail']['user_id']));
}

if (isset($_POST['submit']) && $_POST['submit'] == 'submit') {

//if(!$obj_client->can_create('coupon_update')) {
    if ($obj_notification->updateNotification()) {
        $obj_client->redirect(PROJECT_URL . "/?page=notification_list");
    }
//}
}
if (isset($_POST['submit']) && $_POST['submit'] == 'update') {

    //if(!$obj_client->can_create('coupon_update')) {
    if ($obj_notification->updateNotification()) {

        $obj_client->redirect(PROJECT_URL . "/?page=notification_list");
    }
}
?>

<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>Notification</h1></div>
        <div class="clear"></div>
        <?php $obj_client->showErrorMessage(); ?>
        <?php $obj_client->showSuccessMessge(); ?>
        <?php $obj_client->unsetMessage(); ?>


        <div class="clear"></div>
        <div class="whitebg formboxcontainer">
            <form name="client-user" id="client-user" method="POST">
                <h2 class="greyheading">
                    <?php
                    if (isset($_GET["action"]) && ($_GET["action"] == "editNotification") && (isset($_GET["id"]))) {
                        echo 'Update';
                    } else {
                        echo 'Create New';
                    }
                    ?>NotificationInformation</h2>

                <div class="row">


                    <div class="col-md-4 col-sm-4 col-xs-12 form-group">

                        <label>Title<span class="starred">*</span></label>


                        <input type="text" name="notification_name" id="notification_name" placeholder="Enter notification name" class="required form-control" data-bind="content" value="<?php
                        if (isset($_POST['notification_name'])) {
                            echo $_POST['notification_name'];
                        } else if (isset($dataCurrentArr[0]->notification_name)) {
                            echo $dataCurrentArr[0]->notification_name;
                        }
                        ?>" />


                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12 form-group">

                        <label>Message<span class="starred">*</span></label>
                         <textarea placeholder="Enter notification message" maxlength="255"  name="notification_message" id="notification_message" class="required form-control"><?php
                        if (isset($_POST['notification_message'])) {
                            echo $_POST['notification_message'];
                        } else if (isset($dataCurrentArr[0]->notification_message)) {
                            echo $dataCurrentArr[0]->notification_message;
                        }
                        ?></textarea>
						
                        
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                        <label>Vendor Type<span class="starred">*</span></label>
                        <select name='vendor_type' id='vendor_type' class='required form-control'>
                            <?php $dataVendorArrs = $obj_notification->get_results("select * from " . $obj_notification->getTableName('vendor_type') . " where status='1' and is_deleted='0' order by vendor_name asc"); ?>
                            <?php if (!empty($dataVendorArrs)) { ?>
                                <?php
                                if ($dataCurrentArr[0]->vendor_list == 0) {
                                    ?>
                                    <option value='0' 'selected="selected">Select AllVendor</option>
                                <?php } else { ?>
                                    <option value='0'>Select AllVendor</option>
                                <?php } ?>
                                <?php foreach ($dataVendorArrs as $dataVendorArr) { ?>
                                    <option value='<?php echo $dataVendorArr->vendor_id; ?>' <?php
                                            if (isset($_POST['vendor_type']) && $_POST['vendor_type'] == $dataVendorArr->vendor_id) {
                                                echo 'selected="selected"';
                                            } else if (isset($dataArr[0]->vendor_type) && $dataCurrentArr[0]->vendor_list == $dataVendorArr->vendor_id) {
                                                echo 'selected="selected"';
                                            }
                                            ?>><?php echo $dataVendorArr->vendor_name; ?></option>
    <?php } ?>
<?php } ?>
                        </select>
                    </div> <div class="clear"></div>


                    <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                        <label>StartDate<span class="starred">*</span></label>
                        <input type="text" placeholder="yyyy-mm-dd"  name="start_date" value="<?php
					if (isset($_POST['start_date'])) {
						echo $_POST['start_date'];
					} else if (isset($dataCurrentArr[0]->start_date)) {
						echo $dataCurrentArr[0]->start_date;
					}
					?>" class="required form-control"   />
												 

                    </div> 
                 
                    <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                        <label>EndDate<span class="starred">*</span></label>
                        <input type="text" placeholder="yyyy-mm-dd" name="end_date" id="end_date" value="<?php
                        if (isset($_POST['end_date'])) {
                            echo $_POST['end_date'];
                        } else if (isset($dataCurrentArr[0]->end_date)) {
                            echo $dataCurrentArr[0]->end_date;
                        }
?>" class="required form-control" 
                               />
                    </div> 
					 <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                        <label>Status<span class="starred">*</span></label>
                        <select name='notification_status' id='notification_status' class='required form-control'>

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
