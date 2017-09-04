<?php
$obj_return = new returnfile();
if (!isset($_SESSION['user_detail']['user_id']) || $_SESSION['user_detail']['user_id'] == '') {
    $obj_return->redirect(PROJECT_URL);
    exit();
}


if (!$obj_return->can_read('notification_list')) {

    $obj_return->setError($obj_return->getValMsg('can_read'));
    $obj_return->redirect(PROJECT_URL . "/?page=dashboard");
    exit();
}

/* get current user data */
$dataCurrentArr = array();
if (isset($_GET["action"]) && ($_GET["action"] == "editReturnFile") && (isset($_GET["id"]))) {

    $sql = "select  *,count(id) as totallid from gst_returnfile_setting where id=" . $_GET["id"] . "";
    $dataCurrentArr = $obj_return->get_results($sql);
    $dataCurrentArr[0]->totallid;
//$dataCurrentArr = $obj_return->getUserDetailsById($obj_return->sanitize($_SESSION['user_detail']['user_id']));
}

if (isset($_POST['submit']) && $_POST['submit'] == 'submit') {

//if(!$obj_return->can_create('coupon_update')) {
    if ($obj_return->updateReturnFile()) {
        $obj_return->redirect(PROJECT_URL . "/?page=returnfile_list");
    }
//}
}
if (isset($_POST['submit']) && $_POST['submit'] == 'update') {

    //if(!$obj_return->can_create('coupon_update')) {
    if ($obj_return->updateNotification()) {

        $obj_return->redirect(PROJECT_URL . "/?page=returnfile_list");
    }
}
?>
<script src="<?php echo PROJECT_URL;?>/editor/ckeditor/ckeditor.js"></script>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>ReturnFile Setting</h1></div>
        <div class="clear"></div>
        <?php $obj_return->showErrorMessage(); ?>
        <?php $obj_return->showSuccessMessge(); ?>
        <?php $obj_return->unsetMessage(); ?>


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
                    ?>ReturnFile Setting</h2>

                <div class="row">


                    <div class="col-md-4 col-sm-4 col-xs-12 form-group">

                        <label>Return Form<span class="starred">*</span></label>


                        <input type="text" name="returnform_name" id="returnform_name" placeholder="Enter return form name" class="required form-control" data-bind="content" value="<?php
                        if (isset($_POST['returnform_name'])) {
                            echo $_POST['returnform_name'];
                        } else if (isset($dataCurrentArr[0]->returnform_name)) {
                            echo $dataCurrentArr[0]->returnform_name;
                        }
                        ?>" />


                    </div>
                   
                    <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                        <label>Vendor Type<span class="starred">*</span></label>
                        <select name='vendor_type' id='vendor_type' class='required form-control'>
                            <?php $dataVendorArrs = $obj_return->get_results("select * from " . $obj_return->getTableName('vendor_type') . " where status='1' and is_deleted='0' order by vendor_name asc"); ?>
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
                    </div> <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                        <label>Status<span class="starred">*</span></label>
                        <select name='returnfile_status' id='returnfile_status' class='required form-control'>

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
                   
                    <div class="clear"></div>


                    <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                        <label>EndDate<span class="starred">*</span></label>
                        <input type="text" name="returnfile_date" value="<?php if (isset($_POST['returnfile_date'])) { echo $_POST['returnfile_date']; } else if (isset($dataCurrentArr[0]->returnfile_date)) { echo $dataCurrentArr[0]->returnfile_date; }?>" class="required form-control"   />
		             </div>
					<div class="col-md-4 col-sm-4 col-xs-12 form-group">
                        <label>ReturnFile Type<span class="starred">*</span></label>
                        <select name='returnfile_type' id='returnfile_type' class='required form-control'>
                             <option value='2' <?php
                            if (isset($dataCurrentArr[0]->returnfile_type) && $dataCurrentArr[0]->returnfile_type == 2) {
                                echo "selected='selected'";
                            }
                            ?>>Yearly</option>
                            <option value='1' <?php
                            if (isset($dataCurrentArr[0]->returnfile_type) && $dataCurrentArr[0]->returnfile_type == 1) {
                                echo "selected='selected'";
                            }
                            ?>>Quartly</option>
							 <option value='0' <?php
                            if (isset($dataCurrentArr[0]->returnfile_type) && $dataCurrentArr[0]->returnfile_type == 0) {
                                echo "selected='selected'";
                            }
                            ?>>Monthly</option>
							</select>
							</div>                   
					
                    <div class="clear"></div>
					 <div class="col-md-12 col-sm-12 col-xs-12 form-group">

                        <label>Description<span class="starred">*</span></label>
                         <textarea placeholder="Enter return form description" maxlength="255"  name="returnfile_description" id="returnfile_description" class="required form-control"><?php
                        if (isset($_POST['returnfile_description'])) {
                            echo $_POST['returnfile_description'];
                        } else if (isset($dataCurrentArr[0]->returnfile_description)) {
                            echo $dataCurrentArr[0]->returnfile_description;
                        }
                        ?></textarea>
						 
                      
                    </div>  <div class="clear"></div>
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
    CKEDITOR.replace('notification_message', {
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
/*
    $(document).ready(function () {
        $("*[name=end_date],*[name=returnfile_date]").datetimepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            yearRange: '2017:<?php echo date("Y"); ?>',
            maxDate: '0:<?php echo date("Y"); ?>'
        });

    });
	*/
</script>
