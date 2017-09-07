<?php
$obj_return = new returnfile();

$catid=0;						
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

<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="col-md-12 col-sm-12 col-xs-12 heading"><h1>ReturnFile Setting</h1></div>
        <div class="clear"></div>
        <?php $obj_return->showErrorMessage(); ?>
        <?php $obj_return->showSuccessMessge(); ?>
        <?php $obj_return->unsetMessage(); ?>


        <div class="clear"></div>
        <div class="whitebg formboxcontainer">
          
                <h2 class="greyheading">
                    <?php
                    if (isset($_GET["action"]) && ($_GET["action"] == "editNotification") && (isset($_GET["id"]))) {
                        echo 'Update';
                    } else {
                        echo 'Create New';
                    }
                    ?>ReturnFile Setting</h2>
<form method="post" name="form1">
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
                        <label>Return SubCategory<span class="starred">*</span></label>
                        <select name='return_subcat' id='return_subcat' class='required form-control'>
						<?php 
						if(isset($_GET['r_id'])) {
							$sql='';
							
						   $sql="select * from " . $obj_return->getTableName('return_subcat') . " where status='1' and is_deleted='0' and cat_id='".$_GET["r_id"]."' order by id asc";
						}else{
						$sql="select * from " . $obj_return->getTableName('return_subcat') . " where status='1' and is_deleted='0' and cat_id='".$catid."' order by id asc";
				
						}							
                            $dataCatArrs = $obj_return->get_results($sql); ?>
                            <?php if (!empty($dataCatArrs)) { ?>
                                <?php
                                if ($dataCurrentArr[0]->subcat_id == 0) {
                                    ?>
                                    <option value='0' selected="selected">Select Subcategory</option>
                                <?php } else { ?>
                                    <option value='0'>Select Subcategory</option>
                                <?php } ?>
                                <?php foreach ($dataCatArrs as $dataCatArr) { ?>
                                    <option value='<?php echo $dataCatArr->id; ?>' <?php
                                            if (isset($_POST['return_cat']) && $_POST['return_cat'] == $dataCatArr->id) {
                                                echo 'selected="selected"';
                                            } else if (isset($dataCurrentArr[0]->subcat_id) && $dataCurrentArr[0]->subcat_id == $dataCatArr->id) {
                                                echo 'selected="selected"';
                                            }
                                            ?>><?php echo $dataCatArr->subcat_name; ?></option>
							 <?php } ?>
						<?php }  ?>
                        </select>
                    </div>
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
                   <div class="clear"></div>
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

                        </select></div>
                    

                    <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                        <label>EndDate<span class="starred">*</span></label>
                        <input type="text" name="returnfile_date" value="<?php if (isset($_POST['returnfile_date'])) { echo $_POST['returnfile_date']; } else if (isset($dataCurrentArr[0]->returnfile_date)) { echo $dataCurrentArr[0]->returnfile_date; }?>" class="required form-control"   />
		             </div>
					 <div class="clear"></div>
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
					 

					 <div class="col-md-8 col-sm-8 col-xs-12 form-group">

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
</form>
        </div>
       
    </div>
</div>


<!--========================sidemenu over=========================-->

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

 $(document).ready(function() {
  $("#return_cat").change(function() {
	 
    var return_id = $(this).val();
	
	      <?php
				if(isset($_REQUEST['action']) && $_REQUEST['action'] != '' && $_REQUEST['action']=="editReturnFile")
				{
					?>
					window.location.href = '<?php echo PROJECT_URL; ?>/?page=returnfile_update&action=editReturnFile&id=<?php echo $_REQUEST["id"]; ?>&r_id='+return_id+'';
	
					<?php
				}else
				{
					?>
						window.location.href = '<?php echo PROJECT_URL; ?>/?page=returnfile_update&r_id='+return_id+'';
	
					<?php
				}
				?>
	
  });
});
     

    
    </script>


