<?php
$obj_gstr2 = new gstr2();

//$obj_login->sendMobileMessage
$returnmonth = date('Y-m');
if(!$obj_gstr2->can_read('returnfile_list'))
{
    $obj_gstr2->setError($obj_gstr2->getValMsg('can_read'));
    $obj_gstr2->redirect(PROJECT_URL."/?page=dashboard");
    exit();
}
if (isset($_POST['returnmonth'])) {
    $returnmonth = $_POST['returnmonth'];
    $obj_gstr2->redirect(PROJECT_URL . "/?page=return_gstr2advance_amount&returnmonth=" . $returnmonth);
    exit();
}
$returnmonth = date('Y-m');
if (isset($_REQUEST['returnmonth']) && $_REQUEST['returnmonth'] != '') {
    $returnmonth = $_REQUEST['returnmonth'];
}
$returnmonth = date('Y-m');
if ($_REQUEST['returnmonth'] != '') {
    $returnmonth = $_REQUEST['returnmonth'];
}

?>

 
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="col-md-6 col-sm-6 col-xs-12 heading"><h1>Adjustment of Advances</h1></div>
        <div class="col-md-6 col-sm-6 col-xs-12 text-right breadcrumb-nav"><a href="#">Home</a>
            <i class="fa fa-angle-right" aria-hidden="true"></i>  <a href="#">File Return</a> <i class="fa fa-angle-right" aria-hidden="true"></i> <span class="active">GST-Transition Form</span> </div>
        <div class="whitebg formboxcontainer">
        	<?php $obj_gstr2->showErrorMessage(); ?>
		    <?php $obj_gstr2->showSuccessMessge(); ?>
		    <?php $obj_gstr2->unsetMessage(); ?>
           
            <form method="post" id="auto" name="auto">
               
                <input type="button" value="<?php echo ucfirst('Back'); ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=return_gstr2_mydata&returnmonth=" . $_REQUEST["returnmonth"]; ?>';" class="btn btn-danger" class="redbtn marlef10"/>

                <input type="hidden" name="autoname" id="autoname" value="1" />
                <input style="display:none;" type='submit' class="btn btn-success" name='autopopulate' value='autopopulate'>
            </form>	   			
            <div class="pull-right rgtdatetxt">

                <form method='post' name='form2'>
                    Month Of Return
<?php
$dataQuery = "SELECT DATE_FORMAT(invoice_date,'%Y-%m') AS niceDate FROM " . $db_obj->getTableName('client_invoice') . " group by nicedate";
$dataRes = $obj_gstr2->get_results($dataQuery);
if (!empty($dataRes)) {
    ?>
                        <select class="dateselectbox" id="returnmonth" name="returnmonth">
    <?php
    foreach ($dataRes as $dataRe) {
        ?>
                                <option value="<?php echo $dataRe->niceDate; ?>" <?php if ($dataRe->niceDate == $returnmonth) {
            echo 'selected';
        } ?>><?php echo $dataRe->niceDate; ?></option>
                            <?php
                        }
                        ?>
                        </select>
                        <?php
                    } else {
                        ?>
                        <select class="dateselectbox" id="returnmonth" name="returnmonth">
                            <option>July 2017</option>
                        </select>
                        <?php }
                        ?>
                </form>
            </div>
                   
            <form method="post" enctype="multipart/form-data" id='form'> 
                <div class="greyheading">1.Adjustment of Advances</div>
                <div class="tableresponsive">
                    <form method="post" enctype="multipart/form-data" id='form'>
                        <table  class="table  tablecontent tablecontent2 bordernone" id='table1a'>
                            <thead>
                                <tr>
                                    <th>Placeof Supply</th>
                                    <th>SupplyType</th>
                                    <th>&nbsp;&nbsp;</th>
									<th>&nbsp;&nbsp;</th>
									<th>&nbsp;&nbsp;</th>
									<th>&nbsp;&nbsp;</th>
                                </tr>
                            </thead>

             <tbody>

               <tr>
               <td><select  name="place_of_supply[]"   id='place_of_supply' class="required form-control">
	                <?php $dataSupplyStateArrs = $obj_gstr2->get_results("select * from ".$obj_gstr2->getTableName('state')." where status='1' and is_deleted='0' order by state_name asc"); ?>
							<?php if(!empty($dataSupplyStateArrs)) { ?>
								<?php foreach($dataSupplyStateArrs as $dataSupplyStateArr) { ?>
								<option value='<?php echo $dataSupplyStateArr->state_id; ?>' <?php
                                    
										
										if($dataSupplyStateArr->state_id==0)
										{
                                        echo "selected='selected'";
                                        }
									
                                    ?>><?php echo $dataSupplyStateArr->state_name . " (" . $dataSupplyStateArr->state_tin . ")"; ?></option>
								<?php } ?>
							<?php } ?>
						     </select>
                     </td>
					 <td>
					 <select  name="supply_type[]"   id='supply_type' class="required form-control">
					 <option value='1'>Inter-State</option>
					 <option value='2'>Intra-State</option>
					 </select>
					 </td> 
                    <td>
					<button type='button' class='btn btn-success del'>collapse</button>
					</td>					 
                     </tr>
            
		   <tr>
		   <table id="expanddemo" class="expanddemo" class="table  tablecontent tablecontent2 bordernone">
           <thead>
                                <tr>
                                    <th>Rate(%)</th>
                                    <th>Gross Advance Amount (Excluding Tax)(<i class="fa fa-inr"></i>) </th>
                                    <th>IGST Amount(<i class="fa fa-inr"></i>) </th>
									<th>CGST Amount(<i class="fa fa-inr"></i>) </th>
									<th>SGST Amount(<i class="fa fa-inr"></i>) </th>
									<th>CESS Amount(<i class="fa fa-inr"></i>) </th>
                                 
                                </tr>
                            </thead>

             <tbody>
                  <tr>
                  <td><input type='text' readonly="true" class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='rate0_rate' value="0" /></td>
				  <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='rate0_grossamount' value="" /></td>
				  <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='rate0_igst_amount' value="" /></td>
				  <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='rate0_cgst_amount' value="" /></td>
				  <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='rate0_sgst_amount' value="" /></td>
				  <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='rate0_cess_amount' value="" /></td>
				  </tr>
				  <tr>
                  <td><input type='text' readonly="true" class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='rate3_rate' value="3" /></td>
				  <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='rate3_grossamount' value="" /></td>
				  <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='rate3_igst_amount' value="" /></td>
				  <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='rate3_cgst_amount' value="" /></td>
				  <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='rate3_sgst_amount' value="" /></td>
				  <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='rate3_cess_amount' value="" /></td>
				  </tr>
				   <tr>
                  <td><input type='text' readonly="true" class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='rate5_rate' value="5" /></td>
				  <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='rate5_grossamount' value="" /></td>
				  <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='rate5_igst_amount' value="" /></td>
				  <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='rate5_cgst_amount' value="" /></td>
				  <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='rate5_sgst_amount' value="" /></td>
				  <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='rate5_cess_amount' value="" /></td>
				  </tr>
				   <tr>
                  <td><input type='text' readonly="true" class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='rate12_rate' value="12" /></td>
				  <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='rate12_grossamount' value="" /></td>
				  <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='rate12_igst_amount' value="" /></td>
				  <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='rate12_cgst_amount' value="" /></td>
				  <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='rate12_sgst_amount' value="" /></td>
				  <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='rate12_cess_amount' value="" /></td>
				  </tr>
				   <tr>
                  <td><input type='text' readonly="true" class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='rate18_rate' value="18" /></td>
				  <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='rate18_grossamount' value="" /></td>
				  <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='rate18_igst_amount' value="" /></td>
				  <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='rate18_cgst_amount' value="" /></td>
				  <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='rate18_sgst_amount' value="" /></td>
				  <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='rate18_cess_amount' value="" /></td>
				  </tr>
				   <tr>
                  <td><input type='text' readonly="true" class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='rate28_rate' value="28" /></td>
				  <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='rate28_grossamount' value="" /></td>
				  <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='rate28_igst_amount' value="" /></td>
				  <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='rate28_cgst_amount' value="" /></td>
				  <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='rate28_sgst_amount' value="" /></td>
				  <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='rate28_cess_amount' value="" /></td>
				  </tr>
				   <tr>
                  <td><input type='text' readonly="true" class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='rate0.25_rate' value="0.25" /></td>
				  <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='rate0.25_grossamount' value="" /></td>
				  <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='rate0.25_igst_amount' value="" /></td>
				  <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='rate0.25_cgst_amount' value="" /></td>
				  <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='rate0.25_sgst_amount' value="" /></td>
				  <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='rate0.25_cess_amount' value="" /></td>
				  </tr>
                  </tbody>
                  </table>
		  </tr>          			 
					 </tbody>
					 </table>
					
                        <input type="button" value="Add New Row" class="btn btn-success add-table1a"  href="javascript:void(0)">
                      



                        <div class="tableresponsive">
                                <div class="adminformbxsubmit" style="width:100%;"> 
                                    <div class="tc">
                                        <input type='submit' class="btn btn-success" name='submit' value='submit' id='submit'>
                                        </div>
                                </div>
    
                       </div>


                </div> 

        </div>
        <div class="clear height40"></div>     

    </div>
    <!--CONTENT START HERE-->
</form>
<div class="clear"></div>  

<script type="text/javascript">
    $(document).ready(function () {

        $(".add-table1a").click(function () {
            
            var data1 ='<select class="required form-control" id="place_of_supply"  name="place_of_supply[]">';
			 var data='';
			 data +=<?php $dataSupplyStateArrs = $obj_gstr2->get_results("select * from ".$obj_gstr2->getTableName('state')." where status='1' and is_deleted='0' order by state_name asc"); ?>
						<?php if(!empty($dataSupplyStateArrs)) { ?>
						<?php foreach($dataSupplyStateArrs as $dataSupplyStateArr) { ?>
								data += '<option value="<?php echo $dataSupplyStateArr->state_id; ?>"><?php echo $dataSupplyStateArr->state_name; ?></option>';
							<?php } ?>
						<?php } ?>
							
			data = data1+ data+'</select>';
			var datasupply ='<select class="required form-control" id="supply_type"  name="supply_type[]">';
			
			 datasupply +='<option value="1">Inter-State</option><option value="2">Intra-State</option></select>';

        var markup = "<tr><td>" + data + "</td><td>" + datasupply + "</td> <td><button type='button' class='btn btn-success del'>collapse</button></td></tr>";
       // var markup1='<tr id="expanddemo" class="expanddemo"><td><input type="text" readonly="true" class="required form-control" onKeyPress="return  isNumberKey(event,this);" name="rate0_rate" value="0" /></td><td><input type="text" class="required form-control" onKeyPress="return  isNumberKey(event,this);" name="rate0_grossamount" value="" /></td><td><input type="text" class="required form-control" onKeyPress="return  isNumberKey(event,this);" name="rate0_igst_amount" value="" /></td><td><input type="text" class="required form-control" onKeyPress="return  isNumberKey(event,this);" name="rate0_cgst_amount" value="" /></td><td><input type="text" class="required form-control" onKeyPress="return  isNumberKey(event,this);" name="rate0_sgst_amount" value="" /></td><td><input type="text" class="required form-control" onKeyPress="return  isNumberKey(event,this);" name="rate0_cess_amount" value="" /></td></tr>';     
	  var markup1='<tr><table id="expanddemo" class="expanddemo"><thead><tr><th>Rate(%)</th><th>Gross Advance Amount (Excluding Tax)(<i class="fa fa-inr"></i>) </th><th>IGST Amount(<i class="fa fa-inr"></i>) </th><th>CGST Amount(<i class="fa fa-inr"></i>) </th><th>SGST Amount(<i class="fa fa-inr"></i>) </th><th>CESS Amount(<i class="fa fa-inr"></i>) </th></tr></thead><tbody>';
var markup2='<tr><td><input type="text" readonly="true" class="required form-control" onKeyPress="return  isNumberKey(event,this);" name="rate0_rate" value="0" /></td><td><input type="text" class="required form-control" onKeyPress="return  isNumberKey(event,this);" name="rate0_grossamount" value="" /></td><td><input type="text" class="required form-control" onKeyPress="return  isNumberKey(event,this);" name="rate0_igst_amount" value="" /></td><td><input type="text" class="required form-control" onKeyPress="return  isNumberKey(event,this);" name="rate0_cgst_amount" value="" /></td><td><input type="text" class="required form-control" onKeyPress="return  isNumberKey(event,this);" name="rate0_sgst_amount" value="" /></td><td><input type="text" class="required form-control" onKeyPress="return  isNumberKey(event,this);" name="rate0_cess_amount" value="" /></td></tr>';
var markup3='<tr><td><input type="text" readonly="true" class="required form-control" onKeyPress="return  isNumberKey(event,this);" name="rate3_rate" value="3" /></td><td><input type="text" class="required form-control" onKeyPress="return  isNumberKey(event,this);" name="rate3_grossamount" value="" /></td><td><input type="text" class="required form-control" onKeyPress="return  isNumberKey(event,this);" name="rate3_igst_amount" value="" /></td><td><input type="text" class="required form-control" onKeyPress="return  isNumberKey(event,this);" name="rate3_cgst_amount" value="" /></td><td><input type="text" class="required form-control" onKeyPress="return  isNumberKey(event,this);" name="rate3_sgst_amount" value="" /></td><td><input type="text" class="required form-control" onKeyPress="return  isNumberKey(event,this);" name="rate3_cess_amount" value="" /></td></tr>';
var markup4='<tr><td><input type="text" readonly="true" class="required form-control" onKeyPress="return  isNumberKey(event,this);" name="rate5_rate" value="5" /></td><td><input type="text" class="required form-control" onKeyPress="return  isNumberKey(event,this);" name="rate5_grossamount" value="" /></td><td><input type="text" class="required form-control" onKeyPress="return  isNumberKey(event,this);" name="rate5_igst_amount" value="" /></td><td><input type="text" class="required form-control" onKeyPress="return  isNumberKey(event,this);" name="rate5_cgst_amount" value="" /></td><td><input type="text" class="required form-control" onKeyPress="return  isNumberKey(event,this);" name="rate5_sgst_amount" value="" /></td><td><input type="text" class="required form-control" onKeyPress="return  isNumberKey(event,this);" name="rate5_cess_amount" value="" /></td></tr>';
var markup5='<tr><td><input type="text" readonly="true" class="required form-control" onKeyPress="return  isNumberKey(event,this);" name="rate12_rate" value="12" /></td><td><input type="text" class="required form-control" onKeyPress="return  isNumberKey(event,this);" name="rate12_grossamount" value="" /></td><td><input type="text" class="required form-control" onKeyPress="return  isNumberKey(event,this);" name="rate12_igst_amount" value="" /></td><td><input type="text" class="required form-control" onKeyPress="return  isNumberKey(event,this);" name="rate12_cgst_amount" value="" /></td><td><input type="text" class="required form-control" onKeyPress="return  isNumberKey(event,this);" name="rate12_sgst_amount" value="" /></td><td><input type="text" class="required form-control" onKeyPress="return  isNumberKey(event,this);" name="rate12_cess_amount" value="" /></td></tr>';
var markup6='<tr><td><input type="text" readonly="true" class="required form-control" onKeyPress="return  isNumberKey(event,this);" name="rate18_rate" value="18" /></td><td><input type="text" class="required form-control" onKeyPress="return  isNumberKey(event,this);" name="rate18_grossamount" value="" /></td><td><input type="text" class="required form-control" onKeyPress="return  isNumberKey(event,this);" name="rate18_igst_amount" value="" /></td><td><input type="text" class="required form-control" onKeyPress="return  isNumberKey(event,this);" name="rate18_cgst_amount" value="" /></td><td><input type="text" class="required form-control" onKeyPress="return  isNumberKey(event,this);" name="rate18_sgst_amount" value="" /></td><td><input type="text" class="required form-control" onKeyPress="return  isNumberKey(event,this);" name="rate18_cess_amount" value="" /></td></tr>';
var markup7='<tr><td><input type="text" readonly="true" class="required form-control" onKeyPress="return  isNumberKey(event,this);" name="rate28_rate" value="28" /></td><td><input type="text" class="required form-control" onKeyPress="return  isNumberKey(event,this);" name="rate28_grossamount" value="" /></td><td><input type="text" class="required form-control" onKeyPress="return  isNumberKey(event,this);" name="rate28_igst_amount" value="" /></td><td><input type="text" class="required form-control" onKeyPress="return  isNumberKey(event,this);" name="rate28_cgst_amount" value="" /></td><td><input type="text" class="required form-control" onKeyPress="return  isNumberKey(event,this);" name="rate28_sgst_amount" value="" /></td><td><input type="text" class="required form-control" onKeyPress="return  isNumberKey(event,this);" name="rate28_cess_amount" value="" /></td></tr>';
var markup8='<tr><td><input type="text" readonly="true" class="required form-control" onKeyPress="return  isNumberKey(event,this);" name="rate0.25_rate" value="0.25" /></td><td><input type="text" class="required form-control" onKeyPress="return  isNumberKey(event,this);" name="rate0.25_grossamount" value="" /></td><td><input type="text" class="required form-control" onKeyPress="return  isNumberKey(event,this);" name="rate0.25_igst_amount" value="" /></td><td><input type="text" class="required form-control" onKeyPress="return  isNumberKey(event,this);" name="rate0.25_cgst_amount" value="" /></td><td><input type="text" class="required form-control" onKeyPress="return  isNumberKey(event,this);" name="rate0.25_sgst_amount" value="" /></td><td><input type="text" class="required form-control" onKeyPress="return  isNumberKey(event,this);" name="rate0.25_cess_amount" value="" /></td></tr></tbody> </table></tr>';
	     $('#table1a').append(markup);
		 $('#table1a').append(markup1);
		 $('#table1a').append(markup2);
		 $('#table1a').append(markup3);
		 $('#table1a').append(markup4);
		 $('#table1a').append(markup5);
		 $('#table1a').append(markup6);
		 $('#table1a').append(markup7);
		 $('#table1a').append(markup8);
		 
		 
		 
		
		 
        });
        $('body').delegate('.del', 'click', function () {
            //$(this).closest('tr').remove();
		    $(this).closest('table').next('.expanddemo').toggle();
			
        });



    });

</script>
<script type="text/javascript">
    $(document).ready(function () {

        $(".add-expand").click(function () {
           
			document.getElementById('trexpand').style.display = 'block';
			
			});

    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
 
        $(".add-collapse").click(function () {
			//document.getElementById('trexpand').style.display = 'none';
			 $(this).closest('tr').next('.expanddemo').toggle();
			
			});

    });
</script>
<script>
    $(document).ready(function () {
        $('#returnmonth').on('change', function () {
            document.form2.action = '<?php echo PROJECT_URL; ?>/?page=return_gstr2advance_amount&returnmonth=<?php echo $returnmonth; ?>';
                        document.form2.submit();
                    });
                });
</script>

<script>
    $(document).ready(function () {
        $('#returnmonth').on('change', function () {
            document.form2.action = '<?php echo PROJECT_URL; ?>/?page=return_gstr2advance_amount&returnmonth=<?php echo $returnmonth; ?>';
                        document.form2.submit();
                    });
                });
</script>
<script>
    function ezBSAlert(options) {
        var deferredObject = $.Deferred();
        var defaults = {
            type: "alert", //alert, prompt,confirm 
            modalSize: 'modal-sm', //modal-sm, modal-lg
            okButtonText: 'Confirm',
            cancelButtonText: 'Cancel',
            yesButtonText: 'Yes',
            noButtonText: 'No',
            headerText: 'Important : Please Read And Confirm',
            messageText: 'Message',
            alertType: 'default', //default, primary, success, info, warning, danger
            inputFieldType: 'text', //could ask for number,email,etc
        }
        $.extend(defaults, options);

        var _show = function () {
            var headClass = "navbar-default";
            switch (defaults.alertType) {
                case "primary":
                    headClass = "alert-primary";
                    break;
                case "success":
                    headClass = "alert-success";
                    break;
                case "info":
                    headClass = "alert-info";
                    break;
                case "warning":
                    headClass = "alert-warning";
                    break;
                case "danger":
                    headClass = "alert-danger";
                    break;
            }
            $('BODY').append(
                    '<div id="ezAlerts" style="z-index: 99999" class="modal fade">' +
                    '<div class="modal-dialog" class="' + defaults.modalSize + '">' +
                    '<div class="modal-content">' +
                    '<div id="ezAlerts-header" class="modal-header ' + headClass + '">' +
                    '<button id="close-button" type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>' +
                    '<h4 id="ezAlerts-title" class="modal-title">Modal title</h4>' +
                    '</div>' +
                    '<div id="ezAlerts-body" class="modal-body">' +
                    '<div id="ezAlerts-message" ></div>' +
                    '</div>' +
                    '<div id="ezAlerts-footer" class="modal-footer">' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>'
                    );

            $('.modal-header').css({
                'padding': '15px 15px',
                '-webkit-border-top-left-radius': '5px',
                '-webkit-border-top-right-radius': '5px',
                '-moz-border-radius-topleft': '5px',
                '-moz-border-radius-topright': '5px',
                'border-top-left-radius': '5px',
                'border-top-right-radius': '5px'
            });

            $('#ezAlerts-title').text(defaults.headerText);
            $('#ezAlerts-message').html(defaults.messageText);

            var keyb = "false", backd = "static";
            var calbackParam = "";
            switch (defaults.type) {
                case 'alert':
                    keyb = "true";
                    backd = "true";
                    $('#ezAlerts-footer').html('<button class="btn btn-' + defaults.alertType + '">' + defaults.okButtonText + '</button>').on('click', ".btn", function () {
                        calbackParam = true;
                        $('#ezAlerts').modal('hide');
                    });
                    break;
                case 'confirm':
                    var btnhtml = '<button id="ezok-btn" class="btn btn-primary">' + defaults.yesButtonText + '</button>';
                    if (defaults.noButtonText && defaults.noButtonText.length > 0) {
                        btnhtml += '<button id="ezclose-btn" class="btn btn-default">' + defaults.noButtonText + '</button>';
                    }
                    $('#ezAlerts-footer').html(btnhtml).on('click', 'button', function (e) {
                        if (e.target.id === 'ezok-btn') {
                            calbackParam = true;
                            $('#ezAlerts').modal('hide');
                        } else if (e.target.id === 'ezclose-btn') {
                            calbackParam = false;
                            $('#ezAlerts').modal('hide');
                        }
                    });
                    break;
                case 'prompt':
                    $('#ezAlerts-message').html(defaults.messageText + '<br /><br /><div class="form-group"><input type="' + defaults.inputFieldType + '" class="form-control" id="prompt" /></div>');
                    $('#ezAlerts-footer').html('<button class="btn btn-primary">' + defaults.okButtonText + '</button>').on('click', ".btn", function () {
                        calbackParam = $('#prompt').val();
                        $('#ezAlerts').modal('hide');
                    });
                    break;
            }

            $('#ezAlerts').modal({
                show: false,
                backdrop: backd,
                keyboard: keyb
            }).on('hidden.bs.modal', function (e) {
                $('#ezAlerts').remove();
                deferredObject.resolve(calbackParam);
            }).on('shown.bs.modal', function (e) {
                if ($('#prompt').length > 0) {
                    $('#prompt').focus();
                }
            }).modal('show');
        }

        _show();
        return deferredObject.promise();
    }





    $(document).ready(function () {


        $("#btnConfirm").on("click", function () {
            ezBSAlert({
                type: "confirm",
                messageText: "Auto-compute for HSN summary is based only on Invoices data.<br><br>Your current data for this section will be erased and it will be reset based on summary computed from Invoice level data.",
                alertType: "danger"
            }).done(function (e) {
                //$("body").append('<div>Callback from confirm ' + e + '</div>');
                if (e == true)
                {
                    document.auto.action = '<?php echo PROJECT_URL; ?>/?page=return_gstr2advance_amount&returnmonth=<?php echo $returnmonth; ?>';
                                        document.auto.submit();
                                    }
                                });
                            });

                        });
</script>   
<script>
    $(document).ready(function () {

        /* select2 js for state */
        //$("#place_of_supply_unregistered_person").select2();


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