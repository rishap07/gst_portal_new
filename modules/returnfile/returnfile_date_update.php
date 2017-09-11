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

    $sql = "select  *,count(id) as totallid from gst_returnfile_dates where id=" . $_GET["id"] . "";
    $dataCurrentArr = $obj_return->get_results($sql);
    $dataCurrentArr[0]->totallid;
//$dataCurrentArr = $obj_return->getUserDetailsById($obj_return->sanitize($_SESSION['user_detail']['user_id']));
}

if (isset($_POST['submit']) && $_POST['submit'] == 'submit') {

//if(!$obj_return->can_create('coupon_update')) {
    if ($obj_return->updateReturnFileDate()) {
        $obj_return->redirect(PROJECT_URL . "/?page=returnfile_date_list");
    }
//}
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
                                    
                                <?php } else { ?>
                                    
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
                        } else if (isset($dataCurrentArr[0]->return_name)) {
                            echo $dataCurrentArr[0]->return_name;
                        }
                        ?>" />


                    </div>
                   <div class="clear"></div>
				   <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                        <label>StartDate<span class="starred">*</span></label>
                        <input type="text" placeholder="yyyy-mm-dd"  name="returnfile_date" value="<?php
					if (isset($_POST['returnfile_date'])) {
						echo $_POST['returnfile_date'];
					} else if (isset($dataCurrentArr[0]->returnfile_date)) {
						echo $dataCurrentArr[0]->returnfile_date;
					}
					?>" class="required form-control"   />
												 

                    </div>
                   <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                        <label>Month<span class="starred">*</span></label>
                        <select name='returnfile_month' id='returnfile_month' class='required form-control'>

                            <option value='01' <?php
                            if (isset($dataCurrentArr[0]->return_month) && $dataCurrentArr[0]->return_month == 01) {
                                echo "selected='selected'";
                            }
                            ?>>Jan</option>
                            <option value='02' <?php
                            if (isset($dataCurrentArr[0]->return_month) && $dataCurrentArr[0]->return_month == 02) {
                                echo "selected='selected'";
                            }
                            ?>>Feb</option>
							<option value='03' <?php
                            if (isset($dataCurrentArr[0]->return_month) && $dataCurrentArr[0]->return_month == 03) {
                                echo "selected='selected'";
                            }
                            ?>>March</option>
							<option value='04' <?php
                            if (isset($dataCurrentArr[0]->return_month) && $dataCurrentArr[0]->return_month == 04) {
                                echo "selected='selected'";
                            }
                            ?>>April</option>
							<option value='05' <?php
                            if (isset($dataCurrentArr[0]->return_month) && $dataCurrentArr[0]->return_month == 05) {
                                echo "selected='selected'";
                            }
                            ?>>May</option>
							<option value='06' <?php
                            if (isset($dataCurrentArr[0]->return_month) && $dataCurrentArr[0]->return_month == 06) {
                                echo "selected='selected'";
                            }
                            ?>>June</option>
							<option value='07' <?php
                            if (isset($dataCurrentArr[0]->return_month) && $dataCurrentArr[0]->return_month == 07) {
                                echo "selected='selected'";
                            }
                            ?>>July</option>
							<option value='08' <?php
                            if (isset($dataCurrentArr[0]->return_month) && $dataCurrentArr[0]->return_month == 08) {
                                echo "selected='selected'";
                            }
                            ?>>Aug</option>
							<option value='09' <?php
                            if (isset($dataCurrentArr[0]->return_month) && $dataCurrentArr[0]->return_month == 09) {
                                echo "selected='selected'";
                            }
                            ?>>Sep</option>
							<option value='10' <?php
                            if (isset($dataCurrentArr[0]->return_month) && $dataCurrentArr[0]->return_month == 10) {
                                echo "selected='selected'";
                            }
                            ?>>Oct</option>
							<option value='11' <?php
                            if (isset($dataCurrentArr[0]->return_month) && $dataCurrentArr[0]->return_month == 11) {
                                echo "selected='selected'";
                            }
                            ?>>Nov</option>
							<option value='12' <?php
                            if (isset($dataCurrentArr[0]->return_month) && $dataCurrentArr[0]->return_month == 12) {
                                echo "selected='selected'";
                            }
                            ?>>Dec</option>
                        </select></div>
                    					
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

 $(document).ready(function() {
  $("#return_cat").change(function() {
	 
    var return_id = $(this).val();
	
	      <?php
				if(isset($_REQUEST['action']) && $_REQUEST['action'] != '' && $_REQUEST['action']=="editReturnFile")
				{
					?>
					window.location.href = '<?php echo PROJECT_URL; ?>/?page=returnfile_date_update&action=editReturnFile&id=<?php echo $_REQUEST["id"]; ?>&r_id='+return_id+'';
	
					<?php
				}else
				{
					?>
						window.location.href = '<?php echo PROJECT_URL; ?>/?page=returnfile_date_update&r_id='+return_id+'';
	
					<?php
				}
				?>
	
  });
});
     

    
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



