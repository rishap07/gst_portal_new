<?php
$obj_return = new returnfile();

$catid=0;						
if (!isset($_SESSION['user_detail']['user_id']) || $_SESSION['user_detail']['user_id'] == '') {
    $obj_return->redirect(PROJECT_URL);
    exit();
}


if (!$obj_return->can_read('returnsetting_list')) {

    $obj_return->setError($obj_return->getValMsg('can_read'));
    $obj_return->redirect(PROJECT_URL . "/?page=dashboard");
    exit();
}

/* get current user data */
$dataCurrentArr = array();
if (isset($_GET["action"]) && ($_GET["action"] == "editReturnFile") && (isset($_GET["id"]))) {

    $sql = "select  *,count(id) as totallid from gst_return_subcategories where id=" . $_GET["id"] . "";
    $dataCurrentArr = $obj_return->get_results($sql);
    $dataCurrentArr[0]->totallid;
//$dataCurrentArr = $obj_return->getUserDetailsById($obj_return->sanitize($_SESSION['user_detail']['user_id']));
}

if (isset($_POST['submit']) && $_POST['submit'] == 'submit') {

//if(!$obj_return->can_create('coupon_update')) {
    if ($obj_return->updateReturnSubcategory()) {
        $obj_return->redirect(PROJECT_URL . "/?page=returnfile_subcategory_list");
    }
//}
}
?>

<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>ReturnSubCategory Setting</h1></div>
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
                    ?>Return SubCategory Setting</h2>                                                                                                                                                                                                                                                                                                                                         
<form method="post" name="return-form" id="return-form">
      <div class="row">
	    <div class="col-md-4 col-sm-4 col-xs-12 form-group">
					
					  <label>Return Category<span class="starred">*</span></label>
                        <select name='return_cat'   id='return_cat' class='required form-control'>
                            <?php $dataCatArrs = $obj_return->get_results("select * from " . $obj_return->getTableName('return_cat') . " where status='1' and is_deleted='0' order by id asc"); ?>
                            <?php if (!empty($dataCatArrs)) { ?>
                                <?php
                                if ($dataCurrentArr[0]->cat_id == 0) {
                                    ?>
                                    <option value='0' 'selected="selected">Select category</option>
                                <?php } else { ?>
                                    <option value='0'>Select category</option>
                                <?php } ?>
                                <?php foreach ($dataCatArrs as $dataCatArr) { ?>
                                    <option value='<?php echo $dataCatArr->id; ?>' <?php
									
									      if (isset($dataCurrentArr[0]->cat_id) && $dataCurrentArr[0]->cat_id == $dataCatArr->id) {
											  $catid = $dataCatArr->id;
                                              echo 'selected="selected"';
                                            }
                                            else if (isset($_POST['return_cat']) && $_POST['return_cat'] == $dataCatArr->id) {
                                                echo 'selected="selected"';
                                            } else if(isset($_GET['r_id']) && $_GET['r_id'] == $dataCatArr->id) {
                                                echo 'selected="selected"';
                                            } 
                                            ?>><?php echo $dataCatArr->return_name; ?></option>
							 <?php } ?>
							<?php } ?>
                        </select>
                    </div>
					
                    <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                      <label>SubCategoryName<span class="starred">*</span></label>
                       <input type="text" name="return_subcat" id="return_subcat" placeholder="Enter return form name" class="required form-control" data-bind="content" value="<?php
                        if (isset($_POST['return_subcat'])) {
                            echo $_POST['return_subcat'];
                        } else if (isset($dataCurrentArr[0]->subcat_name)) {
                            echo $dataCurrentArr[0]->subcat_name;
                        }
                        ?>" />
                    </div>
					  <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                      <label>Order Value<span class="starred">*</span></label>
                       <input type="text" name="order_value" id="order_value" placeholder="Enter return form name" class="required form-control" data-bind="content" value="<?php
                        if (isset($_POST['order_value'])) {
                            echo $_POST['order_value'];
                        } else if (isset($dataCurrentArr[0]->order_value)) {
                            echo $dataCurrentArr[0]->order_value;
                        }
                        ?>" />
                    </div>

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



