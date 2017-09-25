<?php
$obj_transition = new transition();
$obj_gstr2 = new gstr2();
$obj_gstr3b = new gstr3b();
//$obj_login->sendMobileMessage
$returnmonth = date('Y-m');

if (isset($_POST['returnmonth'])) {
    $returnmonth = $_POST['returnmonth'];
    $obj_gstr2->redirect(PROJECT_URL . "/?page=return_gstr3bitc_summary&returnmonth=" . $returnmonth);
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
if(isset($_POST['offset']) && $_POST['offset']=='offset liability') {
   if($obj_gstr3b->checkoffsetLiability())
   {
	   
   }
}
?>

<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="col-md-6 col-sm-6 col-xs-12 heading"><h1>GSTR-3B ITC summary</h1></div>
        <div class="col-md-6 col-sm-6 col-xs-12 text-right breadcrumb-nav"><a href="#">Home</a>
            <i class="fa fa-angle-right" aria-hidden="true"></i>  <a href="#">File Return</a> <i class="fa fa-angle-right" aria-hidden="true"></i> <span class="active">GST-Transition Form</span> </div>
        <div class="whitebg formboxcontainer">
			<?php $obj_gstr2->showErrorMessage(); ?>
		    <?php $obj_gstr2->showSuccessMessge(); ?>
		    <?php $obj_gstr2->unsetMessage(); ?>
             <form method="post" id="auto" name="auto">
                <button  type="button" style="display:none;" class="btn btn-success" id="btnConfirm">autopopulate</button>
                <input type="hidden" name="autoname" id="autoname" value="1" />
                <input style="display:none;" type='submit' class="btn btn-success" name='autopopulate' value='autopopulate'>
            </form>	 
            <div class="tab">
                <a href="<?php echo PROJECT_URL . '/?page=return_gstr3b_file&returnmonth='.$returnmonth ?>" >
                    Prepare GSTR-3B 
                </a>
                <a href="<?php echo PROJECT_URL . '/?page=return_filegstr3b_file&returnmonth='.$returnmonth ?>" >
                    File GSTR-3B
                </a>
				 <a href="<?php echo PROJECT_URL . '/?page=return_gstr3bitc_summary&returnmonth='.$returnmonth ?>" class="active" >
                    ITC Paid
                </a>
              
             </div>  			
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
                <div class="greyheading">1.ITC Available</div>
                <div class="tableresponsive">
                    <form method="post" enctype="multipart/form-data" id='form'>
                        <table border="1" bordercolor="#ccc" cellpadding="5" cellspacing="0"  class="table  tablecontent tablecontent2 bordernone" id='table1a'>
                            <thead>
                                <tr>
                                    <th>Description</th>
                                    <th>Tax payable(<i class="fa fa-inr"></i>)</th>
                                    <th colspan="4">Paid througnh itc(<i class="fa fa-inr"></i>)</th>
									
                                    <th>Tax/cess paid in cash(<i class="fa fa-inr"></i>)</th>
									<th>Interest paid in cash(<i class="fa fa-inr"></i>)</th>
									<th>Late fee paid in cash(<i class="fa fa-inr"></i>)</th>
									
					                                 
                                </tr>
								<tr>
								<th>&nbsp;</th>
								<th>&nbsp;</th>
								<th>integratedtax</th>
								<th>centraltax</th>
								<th>statetax</th>
								<th>cess</th>
								<th>&nbsp;</th>
								<th>&nbsp;</th>
								<th>&nbsp;</th>
								
								
								</tr>
                            </thead>
                            <tbody>
               <tr>    
               <td colspan='9'>Other than reverse charge</td>     
                </tr>
				<tr>
			<td class="lftheading">IntegratedTax</td>     
               
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' readonly="true" name='taxpayable_igst_other' value="525000"/></td>
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='paiditcigst_igst' value=""/></td>
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='paiditcigst_cgst' value=""/></td>
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='paiditcigst_sgst' value="<?php  echo (isset($paiditc_sgst)) ? $paiditc_sgst : '' ?>"/></td>
			   <td><input type="text" maxlength="15" disabled="" onkeypress="return  isNumberKey(event,this);" name="" value="" class="form-control" placeholder=""></td>
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='taxpaidigst_igst' value="<?php  echo (isset($taxpaidigst_igst)) ? $taxpaidigst_igst : '' ?>"/></td>
			  <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='interestpaidigst_igst' value="<?php  echo (isset($interestpaidigst_igst)) ? $interestpaidigst_igst : '' ?>"/></td>
			  <td><input type="text" maxlength="15" disabled="" onkeypress="return  isNumberKey(event,this);" name="" value="" class="form-control" placeholder=""></td>
			   </tr> 
				</tr>
				<tr>
			<td class="lftheading">CentralTax</td>     
               
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' readonly="true" name='taxpayable_cgst_other' value="107348"/></td>
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='paiditccgst_igst' value="<?php  echo (isset($paiditccgst_igst)) ? $paiditccgst_igst : '' ?>"/></td>
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='paiditccgst_cgst' value="<?php  echo (isset($paiditccgst_cgst)) ? $paiditccgst_cgst : '' ?>"/></td>
			    <td><input type="text" maxlength="15" disabled="" onkeypress="return  isNumberKey(event,this);" name="" value="" class="form-control" placeholder=""></td>
			  <td><input type="text" maxlength="15" disabled="" onkeypress="return  isNumberKey(event,this);" name="" value="" class="form-control" placeholder=""></td>
			 
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='taxpaidcgst_igst' value="<?php  echo (isset($taxpaidcgst_igst)) ? $taxpaidcgst_igst : '' ?>"/></td>
			  <td><input type="text" maxlength="15"  onkeypress="return  isNumberKey(event,this);" name="interestpaidcgst_igst" value="<?php  echo (isset($interestpaidcgst_igst)) ? $interestpaidcgst_igst : '' ?>" class="form-control" placeholder=""></td>
			  <td><input type="text" maxlength="15"  onkeypress="return  isNumberKey(event,this);" name="latefee_cash" value="<?php  echo (isset($latefee_cash)) ? $latefee_cash : '' ?>" class="form-control" placeholder=""></td>
			   </tr> 
				</tr>
				<tr>
			<td class="lftheading">State/UtTax</td>     
               
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' readonly="true" name='taxpayable_sgst_other' value="107348"/></td>
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='paiditcsgst_igst' value="<?php  echo (isset($paiditcsgst_igst)) ? $paiditcsgst_igst : '' ?>"/></td>
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' disabled name='' value="<?php  echo (isset($paiditcsgst_cgst)) ? $paiditcsgst_cgst : '' ?>"/></td>
			    <td><input type="text" maxlength="15"  onkeypress="return  isNumberKey(event,this);" name="paiditcsgst_sgst" value="<?php  echo (isset($paiditcsgst_sgst)) ? $paiditcsgst_sgst : '' ?>" class="form-control" placeholder=""></td>
			  <td><input type="text" maxlength="15" disabled="" onkeypress="return  isNumberKey(event,this);" name="" value="" class="form-control" placeholder=""></td>
			 
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='taxpaidsgst_sgst' value="<?php  echo (isset($taxpaidsgst_sgst)) ? $taxpaidsgst_sgst : '' ?>"/></td>
			  <td><input type="text" maxlength="15"  onkeypress="return  isNumberKey(event,this);" name="interestpaidsgst_sgst" value="<?php  echo (isset($interestpaidsgst_sgst)) ? $interestpaidsgst_sgst : '' ?>" class="form-control" placeholder=""></td>
			  <td><input type="text" maxlength="15"  onkeypress="return  isNumberKey(event,this);" name="latefee_sgst" value="<?php  echo (isset($latefee_sgst)) ? $latefee_sgst : '' ?>" class="form-control" placeholder=""></td>
			   </tr> 
				</tr>
				<tr>
			<td class="lftheading">Cess</td>     
               
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' readonly="true" name='taxpayable_cess_other' value="<?php  echo (isset($taxpayable_cess_other)) ? $taxpayable_cess_other : '' ?>"/></td>
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' disabled name='' value="<?php  echo (isset($paiditccess_igst)) ? $paiditccess_igst : '' ?>"/></td>
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' disabled name='' value="<?php  echo (isset($paiditccess_cgst)) ? $paiditccess_cgst : '' ?>"/></td>
			    <td><input type="text" maxlength="15"  onkeypress="return  isNumberKey(event,this);" disabled name='' value="<?php  echo (isset($paiditcsgst_sgst)) ? $paiditcsgst_sgst : '' ?>" class="form-control" placeholder=""></td>
			  <td><input type="text" maxlength="15"  onkeypress="return  isNumberKey(event,this);" name="paiditccess_cess" value="" class="form-control" placeholder=""></td>
			 
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='taxpaidcess_cess' value="<?php  echo (isset($taxpaidcess_cess)) ? $taxpaidcess_cess : '' ?>"/></td>
			  <td><input type="text" maxlength="15"  onkeypress="return  isNumberKey(event,this);"  name='interestpaidcess_cess' value="<?php  echo (isset($interestpaidcess_cess)) ? $interestpaidcess_cess : '' ?>" class="form-control" placeholder=""></td>
			  <td><input type="text" maxlength="15"  onkeypress="return  isNumberKey(event,this);" disabled name='' value="<?php  echo (isset($latefee_sgst)) ? $latefee_sgst : '' ?>" class="form-control" placeholder=""></td>
			   </tr> 
				
				 <tr>    
               <td colspan='9'>Reverse Charge</td>     
                </tr>
				<tr>
			<td class="lftheading">IntegratedTax</td>     
               
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' readonly="true" name='taxpayable_igst_reverse' value="172099"/></td>
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' disabled name='' value="<?php  echo (isset($paiditccess_igst)) ? $paiditccess_igst : '' ?>"/></td>
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' disabled name='' value="<?php  echo (isset($paiditccess_cgst)) ? $paiditccess_cgst : '' ?>"/></td>
			    <td><input type="text" maxlength="15"  onkeypress="return  isNumberKey(event,this);" disabled name='' value="<?php  echo (isset($paiditcsgst_sgst)) ? $paiditcsgst_sgst : '' ?>" class="form-control" placeholder=""></td>
			  <td><input type="text" maxlength="15"  onkeypress="return  isNumberKey(event,this);" disabled name='' value="" class="form-control" placeholder=""></td>
			 
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='taxpaidcess_igst' value="172099"/></td>
			  <td><input type="text" maxlength="15"  onkeypress="return  isNumberKey(event,this);"  disabled name='' value="<?php  echo (isset($interestpaidcess_cess)) ? $interestpaidcess_cess : '' ?>" class="form-control" placeholder=""></td>
			  <td><input type="text" maxlength="15"  onkeypress="return  isNumberKey(event,this);" disabled name='' value="<?php  echo (isset($latefee_sgst)) ? $latefee_sgst : '' ?>" class="form-control" placeholder=""></td>
			   </tr> 
			   <tr>
			<td class="lftheading">CentralTax</td>     
               
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' readonly="true" name='taxpayable_cgst_reverse' value="29083"/></td>
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' disabled name='' value="<?php  echo (isset($paiditccess_igst)) ? $paiditccess_igst : '' ?>"/></td>
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' disabled name='' value="<?php  echo (isset($paiditccess_cgst)) ? $paiditccess_cgst : '' ?>"/></td>
			    <td><input type="text" maxlength="15"  onkeypress="return  isNumberKey(event,this);" disabled name='' value="<?php  echo (isset($paiditcsgst_sgst)) ? $paiditcsgst_sgst : '' ?>" class="form-control" placeholder=""></td>
			  <td><input type="text" maxlength="15"  onkeypress="return  isNumberKey(event,this);" disabled name='' value="" class="form-control" placeholder=""></td>
			 
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='taxpaidcess_cgst' value="29083"/></td>
			  <td><input type="text" maxlength="15"  onkeypress="return  isNumberKey(event,this);"  disabled name='' value="<?php  echo (isset($interestpaidcess_cess)) ? $interestpaidcess_cess : '' ?>" class="form-control" placeholder=""></td>
			  <td><input type="text" maxlength="15"  onkeypress="return  isNumberKey(event,this);" disabled name='' value="<?php  echo (isset($latefee_sgst)) ? $latefee_sgst : '' ?>" class="form-control" placeholder=""></td>
			   </tr> 
			   <tr>
			<td class="lftheading">StateTax</td>     
               
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' readonly="true" name='taxpayable_sgst_reverse' value="29083"/></td>
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' disabled name='' value="<?php  echo (isset($paiditccess_igst)) ? $paiditccess_igst : '' ?>"/></td>
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' disabled name='' value="<?php  echo (isset($paiditccess_cgst)) ? $paiditccess_cgst : '' ?>"/></td>
			    <td><input type="text" maxlength="15"  onkeypress="return  isNumberKey(event,this);" disabled name='' value="<?php  echo (isset($paiditcsgst_sgst)) ? $paiditcsgst_sgst : '' ?>" class="form-control" placeholder=""></td>
			  <td><input type="text" maxlength="15"  onkeypress="return  isNumberKey(event,this);" disabled name='' value="" class="form-control" placeholder=""></td>
			 
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='taxpaidcess_sgst' value="29083"/></td>
			  <td><input type="text" maxlength="15"  onkeypress="return  isNumberKey(event,this);"  disabled name='' value="<?php  echo (isset($interestpaidcess_cess)) ? $interestpaidcess_cess : '' ?>" class="form-control" placeholder=""></td>
			  <td><input type="text" maxlength="15"  onkeypress="return  isNumberKey(event,this);" disabled name='' value="<?php  echo (isset($latefee_sgst)) ? $latefee_sgst : '' ?>" class="form-control" placeholder=""></td>
			   </tr> 
			   <tr>
			<td class="lftheading">Cess</td>     
               
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' readonly="true" name='taxpayable_cess_reverse' value="0"/></td>
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' disabled name='' value="<?php  echo (isset($paiditccess_igst)) ? $paiditccess_igst : '' ?>"/></td>
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' disabled name='' value="<?php  echo (isset($paiditccess_cgst)) ? $paiditccess_cgst : '' ?>"/></td>
			    <td><input type="text" maxlength="15"  onkeypress="return  isNumberKey(event,this);" disabled name='' value="<?php  echo (isset($paiditcsgst_sgst)) ? $paiditcsgst_sgst : '' ?>" class="form-control" placeholder=""></td>
			  <td><input type="text" maxlength="15"  onkeypress="return  isNumberKey(event,this);" disabled name='' value="" class="form-control" placeholder=""></td>
			 
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='taxpaidcess_cess' value="0"/></td>
			  <td><input type="text" maxlength="15"  onkeypress="return  isNumberKey(event,this);"  disabled name='' value="<?php  echo (isset($interestpaidcess_cess)) ? $interestpaidcess_cess : '' ?>" class="form-control" placeholder=""></td>
			  <td><input type="text" maxlength="15"  onkeypress="return  isNumberKey(event,this);" disabled name='' value="<?php  echo (isset($latefee_sgst)) ? $latefee_sgst : '' ?>" class="form-control" placeholder=""></td>
			   </tr>			   		   
              </tbody>
              </table>
              </div>                						
       <div class="tableresponsive">
        <div class="adminformbxsubmit" style="width:100%;"> 
         <div class="tc" style="float:right;">
		 <input type="button" value="<?php echo ucfirst('Back'); ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=return_gstr2_mydata&returnmonth=" . $_REQUEST["returnmonth"]; ?>';" class="btn btn-danger" class="redbtn marlef10"/>
         <input type='submit' class="btn btn-success" name='submit' value='checkbalance' id='submit'>
		 <input type='submit' class="btn btn-success" name='offset' value='offset liability' id='submit'>
		 
         </div>                             
        </div>                                            
    
       </div></div> 

        </div>
        <div class="clear height40"></div>     

    </div>
    <!--CONTENT START HERE-->
</form>
<div class="clear"></div>
<script>
    $(document).ready(function () {
        $('#returnmonth').on('change', function () {
            document.form2.action = '<?php echo PROJECT_URL; ?>/?page=return_gstr3bitc_summary&returnmonth=<?php echo $returnmonth; ?>';
                        document.form2.submit();
                    });
                });
</script>

<script>
    $(document).ready(function () {
        $('#returnmonth').on('change', function () {
            document.form2.action = '<?php echo PROJECT_URL; ?>/?page=return_gstr3bitc_summary&returnmonth=<?php echo $returnmonth; ?>';
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
                messageText: "Auto-compute for Nil rated, exempted and Non-GST supplies is based only on Invoices data.<br><br>Your current data for this section will be erased and it will be reset based on summary computed from Invoice level data.",
                alertType: "danger"
            }).done(function (e) {
                //$("body").append('<div>Callback from confirm ' + e + '</div>');
                if (e == true)
                {
                    document.auto.action = '<?php echo PROJECT_URL; ?>/?page=return_gstr3bitc_summary&returnmonth=<?php echo $returnmonth; ?>';
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