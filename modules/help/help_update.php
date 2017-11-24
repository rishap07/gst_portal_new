<?php
$obj_help = new help();
if (!isset($_SESSION['user_detail']['user_id']) || $_SESSION['user_detail']['user_id'] == '') {
    $obj_client->redirect(PROJECT_URL);
    exit();
}


/*if (!$obj_client->can_read('help_list')) {

    $obj_client->setError($obj_client->getValMsg('can_read'));
    $obj_client->redirect(PROJECT_URL . "/?page=dashboard");
    exit();
}*/

/* get current user data */
$dataCurrentArr = array();
if (isset($_GET["action"]) && ($_GET["action"] == "editHelp") && (isset($_GET["id"]))) {

    $sql = "select  *,count(id) as totalhelp from gst_help where id=" . $_GET["id"] . "";
    $dataCurrentArr = $obj_help->get_results($sql);
}

if (isset($_POST['submit']) && $_POST['submit'] == 'submit') {

//if(!$obj_client->can_create('coupon_update')) {
    if ($obj_help->updateHelp()) {
		
        $obj_help->redirect(PROJECT_URL . "/?page=help_list");
    }
//}
}
if (isset($_POST['submit']) && $_POST['submit'] == 'update') {

    //if(!$obj_client->can_create('coupon_update')) {
    if ($obj_help->updateHelp()) {

        $obj_help->redirect(PROJECT_URL . "/?page=help_list");
    }
}
?>
<script src="<?php echo PROJECT_URL;?>/editor/ckeditor/ckeditor.js"></script>

<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="col-md-12 col-sm-12 col-xs-12 heading">
      <h1>Help</h1>
    </div>
    <div class="clear"></div>
    <?php $obj_help->showErrorMessage(); ?>
    <?php $obj_help->showSuccessMessge(); ?>
    <?php $obj_help->unsetMessage(); ?>
    <div class="clear"></div>
    <div class="whitebg formboxcontainer">
    <form name="client-user" id="client-user" method="POST" enctype="multipart/form-data">
      <h2 class="greyheading">
        <?php
                    if (isset($_GET["action"]) && ($_GET["action"] == "editHelp") && (isset($_GET["id"]))) {
                        echo 'Update';
                    } else {
                        echo 'Create New';
                    }
                    ?>
        Help Information</h2>
      <div class="row">
        <div class="col-md-4 col-sm-4 col-xs-12 form-group">
          <label>Title<span class="starred">*</span></label>
          <input autocomplete="off" type="text" name="help_name" id="help_name" placeholder="Enter Help Title " class="required form-control" data-bind="content" value="<?php
                        if (isset($_POST['help_name'])) {
                            echo $_POST['help_name'];
                        } else if (isset($dataCurrentArr[0]->help_name)) {
                            echo $dataCurrentArr[0]->help_name;
                        }
                        ?>" />
        </div>
        <div class="col-md-4 col-sm-4 col-xs-12 form-group">
          <label>Help Document</label>
          <input type="file" class="form-control" name="help_document"/>
        </div>
        <div class="col-md-4 col-sm-4 col-xs-12 form-group">
          <label>Status<span class="starred">*</span></label>
          <select name='help_status' id='help_status' class='required form-control'>
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
          </select>
        </div>
        <div class="clear"> </div>
        <div class="clear"></div>
        <div class="col-md-4 col-sm-4 col-xs-12 form-group">
          <label>StartDate<span class="starred">*</span></label>
          <input autocomplete="off" type="text" placeholder="yyyy-mm-dd"  name="start_date" value="<?php
					if (isset($_POST['start_date'])) {
						echo $_POST['start_date'];
					} else if (isset($dataCurrentArr[0]->start_date)) {
						echo $dataCurrentArr[0]->start_date;
					}
					?>" class="required form-control"   />
        </div>
        <div class="col-md-4 col-sm-4 col-xs-12 form-group">
          <label>EndDate<span class="starred">*</span></label>
          <input autocomplete="off" type="text" placeholder="yyyy-mm-dd" name="end_date" id="end_date" value="<?php
                        if (isset($_POST['end_date'])) {
                            echo $_POST['end_date'];
                        } else if (isset($dataCurrentArr[0]->end_date)) {
                            echo $dataCurrentArr[0]->end_date;
                        }
?>" class="required form-control" 
                               />
        </div>
        <div class="clear"></div>
        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
          <label>Message<span class="starred">*</span></label>
          <textarea placeholder="Enter Help message" maxlength="255"  name="help_message" id="help_message" class="form-control"><?php
                        if (isset($_POST['help_message'])) {
                            echo $_POST['help_message'];
                        } else if (isset($dataCurrentArr[0]->help_message)) {
                            echo $dataCurrentArr[0]->help_message;
                        }
                        ?>
</textarea>
        </div>
        <div class="clear"></div>
        <div class="adminformbxsubmit" style="width:100%;">
          <div class="tc">
            <input type='submit' class="btn btn-default btn-success" name='submit' value='<?php
                            if (isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == "editHelp") {
                                echo 'update';
                            } else {
                                echo 'submit';
                            }
                            ?>' id='submit'>
            <input type="button" value="<?php echo ucfirst('Back'); ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=help_list"; ?>';" class="btn btn-danger"/>
          </div>
        </div>
      </div>
      </div>
    </form>
  </div>
</div>

<!--========================sidemenu over=========================--> 
<script>
    CKEDITOR.replace('help_message', {
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
