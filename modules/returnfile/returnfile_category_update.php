<?php
$obj_return = new returnfile();

$catid=0;						
if (!isset($_SESSION['user_detail']['user_id']) || $_SESSION['user_detail']['user_id'] == '') {
    $obj_return->redirect(PROJECT_URL);
    exit();
}


if (!$obj_return->can_read('returnfile_list')) {

    $obj_return->setError($obj_return->getValMsg('can_read'));
    $obj_return->redirect(PROJECT_URL . "/?page=dashboard");
    exit();
}

/* get current user data */
$dataCurrentArr = array();
if (isset($_GET["action"]) && ($_GET["action"] == "editReturnFile") && (isset($_GET["id"]))) {

    $sql = "select  *,count(id) as totallid from gst_return_categories where id=" . $_GET["id"] . "";
    $dataCurrentArr = $obj_return->get_results($sql);
    $dataCurrentArr[0]->totallid;
//$dataCurrentArr = $obj_return->getUserDetailsById($obj_return->sanitize($_SESSION['user_detail']['user_id']));
}

if (isset($_POST['submit']) && $_POST['submit'] == 'submit') {

//if(!$obj_return->can_create('coupon_update')) {
    if ($obj_return->updateReturnCategory()) {
        $obj_return->redirect(PROJECT_URL . "/?page=returnfile_category_list");
    }
//}
}
?>

<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>ReturnCategory Setting</h1></div>
        <div class="clear"></div>
        <?php $obj_return->showErrorMessage(); ?>
        <?php $obj_return->showSuccessMessge(); ?>
        <?php $obj_return->unsetMessage(); ?>


        <div class="clear"></div>
        <div class="whitebg formboxcontainer">
          
                <h2 class="greyheading">
                    <?php
                    if (isset($_GET["action"]) && ($_GET["action"] == "editReturnFile") && (isset($_GET["id"]))) {
                        echo 'Update';
                    } else {
                        echo 'Create New';
                    }
                    ?>ReturnCategory Setting</h2>                                                                                                                                                                                                                                                                                                                                         
              <form method="post" name="return-form" id="return-form">
                <div class="row">	    			
                    <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                    <label>CategoryName<span class="starred">*</span></label>
                        <input type="text" name="return_cat" id="return_cat" placeholder="Enter return category name" class="required form-control" data-bind="content" value="<?php
                        if (isset($_POST['return_cat'])) {
                            echo $_POST['return_cat'];
                        } else if (isset($dataCurrentArr[0]->return_name)) {
                            echo $dataCurrentArr[0]->return_name;
                        }
                        ?>" />
                    </div>
					
					 <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                        <label>Vendor Type<span class="starred">*</span></label>
                        <select name='vendor_type' id='vendor_type' class='required form-control'>
                            <?php $dataVendorArrs = $obj_return->get_results("select * from " . $obj_return->getTableName('vendor_type') . " where status='1' and is_deleted='0' order by vendor_name asc"); ?>
                            <?php if (!empty($dataVendorArrs)) { ?>
                                <?php
                                if ($dataCurrentArr[0]->returntofile_vendor_id == 0) {
                                    ?>
                                    <option value='0' 'selected="selected">Select AllVendor</option>
                                <?php } else { ?>
                                    <option value='0'>Select AllVendor</option>
                                <?php } ?>
                                <?php foreach ($dataVendorArrs as $dataVendorArr) {  ?>
                                    <option value='<?php echo $dataVendorArr->vendor_id; ?>' <?php
                                            if (isset($_POST['vendor_type']) && $_POST['vendor_type'] == $dataVendorArr->vendor_id) {
                                                echo 'selected="selected"';
                                            } else if (isset($dataVendorArr->vendor_id) && $dataCurrentArr[0]->returntofile_vendor_id == $dataVendorArr->vendor_id) {
                                                echo 'selected="selected"';
                                            }
                                            ?>><?php echo $dataVendorArr->vendor_name; ?></option>
							 <?php } ?>
							<?php } ?>
                        </select>
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
			
                    <div class="col-md-4 col-sm-4 col-xs-12 form-group">
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

                        </select></div>                  
                     <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                    <label>Return Heading<span class="starred">*</span></label>
                        <input type="text" name="return_subheading" id="return_subheading" placeholder="Enter return category heading" class="required form-control" data-bind="content" value="<?php
                        if (isset($_POST['return_subheading'])) {
                            echo $_POST['return_subheading'];
                        } else if (isset($dataCurrentArr[0]->return_subheading)) {
                            echo $dataCurrentArr[0]->return_subheading;
                        }
                        ?>" />
                    </div>
					  <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                    <label>Return Url<span class="starred">*</span></label>
                        <input type="text" name="return_url" id="return_url" placeholder="Enter return category url" class="required form-control" data-bind="content" value="<?php
                        if (isset($_POST['return_url'])) {
                            echo $_POST['return_url'];
                        } else if (isset($dataCurrentArr[0]->return_url)) {
                            echo $dataCurrentArr[0]->return_url;
                        }
                        ?>" />
                    </div>
					
					 <div class="clear"></div>
					  
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
</form>
        </div>
       
    </div>
</div>


<!--========================sidemenu over=========================-->
<script>
    $(document).ready(function () {
        $('#submit').click(function () {
            var mesg = {};
            if (vali.validate(mesg,'return-form')) {
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
			function submitDataOnClick(form){	
					
				document.form2.action='<?php echo PROJECT_URL;?>/?page=returnfile_update';
				document.form2.submit();
			}
			*/
    </script>
<script>
/*
function getSubCat(val) {
	$.ajax({
	type: "POST",
	async : true,
	url: '<?php echo PROJECT_URL;?>/?page=returnfile_update',
	data:'r_id='+val,
	success: function(data){
		$("#return_subcat").html(data);
	}
	});
}
*/
</script>

	<script>
    $(document).ready(function () {
        $("*[name=end_date],*[name=returnfile_date]").datetimepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            yearRange: '2017:<?php echo date("Y"); ?>',
            maxDate: '0:<?php echo date("Y"); ?>'
        });

    });
</script>



