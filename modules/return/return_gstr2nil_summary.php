<?php
$obj_transition = new transition();
$obj_gstr2 = new gstr2();
//$obj_login->sendMobileMessage
$returnmonth = date('Y-m');

if (isset($_POST['returnmonth'])) {
    $returnmonth = $_POST['returnmonth'];
    $obj_gstr2->redirect(PROJECT_URL . "/?page=return_gstr2nil_summary&returnmonth=" . $returnmonth);
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
if (isset($_POST['submit']) && $_POST['submit'] == 'submit') {
    $flag = $obj_transition->checkVerifyUser();
    if ($flag == 'notverify') {
      $obj_transition->setError("To save nil summary first verify your email and mobile number");
			
    } else {
        if ($obj_gstr2->saveGstr2nilexemptSummary()) {
            //$obj_master->redirect(PROJECT_URL."/?page=master_receiver");
        }
    }
}
       $sql = "select  *,count(id) as totalinvoice from gst_return_upload_summary where added_by='" . $_SESSION['user_detail']['user_id'] . "' and financial_month like '%" . $returnmonth . "%' and is_deleted='0' and type='gstr2nil'  order by id desc limit 0,1";
       $returndata1 = $obj_transition->get_results($sql);
	 
		if($returndata1[0]->totalinvoice > 0)
		{
			$arr = $returndata1[0]->return_data;
			$arr1= base64_decode($arr);
			$summary_arr = json_decode($arr1);
			//$obj_transition->pr($summary_arr);
			$inter1=array(); 
			
			$inter1=!empty($summary_arr->inter)?$summary_arr->inter:'';
			$intra2=array(); 
			$intra2=!empty($summary_arr->intra)?$summary_arr->intra:'';
			$inter_cpddr='';
			$inter_exptdsply='';
			$inter_ngsply='';
			$inter_nilsply='';
			$intra_cpddr='';
			$intra_exptdsply='';
			$intra_ngsply='';
			$intra_nilsply='';
			
			if(!empty($inter1))
			{
				foreach($inter1 as $item)
				{
					$inter_cpddr=$item->cpddr;
					$inter_exptdsply=$item->exptdsply;
					$inter_ngsply=$item->ngsply;
					$inter_nilsply=$item->nilsply;
					
			
				}
			
			}
			if(!empty($intra2))
			{
				foreach($intra2 as $item)
				{
					$intra_cpddr=$item->cpddr;
					$intra_exptdsply=$item->exptdsply;
					$intra_ngsply=$item->ngsply;
					$intra_nilsply=$item->nilsply;
					
			
				}
			
			}
			
		}
?>

<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="col-md-6 col-sm-6 col-xs-12 heading"><h1>Nil summary</h1></div>
        <div class="col-md-6 col-sm-6 col-xs-12 text-right breadcrumb-nav"><a href="#">Home</a>
            <i class="fa fa-angle-right" aria-hidden="true"></i>  <a href="#">File Return</a> <i class="fa fa-angle-right" aria-hidden="true"></i> <span class="active">GST-Transition Form</span> </div>
        <div class="whitebg formboxcontainer">
			<?php $obj_gstr2->showErrorMessage(); ?>
		    <?php $obj_gstr2->showSuccessMessge(); ?>
		    <?php $obj_gstr2->unsetMessage(); ?>
             <form method="post" id="auto" name="auto">
                <button  type="button" style="display:none;" class="btn btn-success" id="btnConfirm">autopopulate</button>
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
                <div class="greyheading">1.Nil rated, exempted and Non-GST supplies</div>
                <div class="tableresponsive">
                    <form method="post" enctype="multipart/form-data" id='form'>
                        <table  class="table  tablecontent tablecontent2 bordernone" id='table1a'>
                            <thead>
                                <tr>
                                    <th>SupplyType</th>
                                    <th>Value Of Supplies Received From Compounding Dealer(<i class="fa fa-inr"></i>)</th>
                                    <th>Value Of Exempted Supplies Received (<i class="fa fa-inr"></i>)</th>
                                    <th>Total Non GST Outward Supplies(<i class="fa fa-inr"></i>)</th>
									<th>Nil Rated Supply(<i class="fa fa-inr"></i>)</th>
					                                 
                                </tr>
                            </thead>
                            <tbody>
                               <tr>    
               <td class="lftheading">Inter-state</td>     
               <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='inter_valueofsupply_compound' value="<?php  echo (isset($inter_cpddr)) ? $inter_cpddr : '' ?>"/></td>
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='inter_valueofexempt' value="<?php  echo (isset($inter_exptdsply)) ? $inter_exptdsply : '' ?>"/></td>
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='inter_totalnongst' value="<?php  echo (isset($inter_ngsply)) ? $inter_ngsply : '' ?>"/></td>
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='inter_nilrated' value="<?php  echo (isset($inter_nilsply)) ? $inter_nilsply : '' ?>"/></td>
			   </tr> 
			   <tr>
              <td class="lftheading">Intra-state</td>     
               <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='intra_valueofsupply_compound' value="<?php  echo (isset($intra_cpddr)) ? $intra_cpddr : '' ?>"/></td>
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='intra_valueofexempt' value="<?php  echo (isset($intra_exptdsply)) ? $intra_exptdsply : '' ?>"/></td>
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='intra_totalnongst' value="<?php  echo (isset($intra_ngsply)) ? $intra_ngsply : '' ?>"/></td>
			   <td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='intra_nilrated' value="<?php  echo (isset($intra_nilsply)) ? $intra_nilsply : '' ?>"/></td>
			   </tr> 			   
              </tbody>
              </table>
              </div>                						
       <div class="tableresponsive">
        <div class="adminformbxsubmit" style="width:100%;"> 
         <div class="tc">
         <input type='submit' class="btn btn-success" name='submit' value='submit' id='submit'>
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
            document.form2.action = '<?php echo PROJECT_URL; ?>/?page=return_gstr2nil_summary&returnmonth=<?php echo $returnmonth; ?>';
                        document.form2.submit();
                    });
                });
</script>

<script>
    $(document).ready(function () {
        $('#returnmonth').on('change', function () {
            document.form2.action = '<?php echo PROJECT_URL; ?>/?page=return_gstr2nil_summary&returnmonth=<?php echo $returnmonth; ?>';
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
                    document.auto.action = '<?php echo PROJECT_URL; ?>/?page=return_gstr2nil_summary&returnmonth=<?php echo $returnmonth; ?>';
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