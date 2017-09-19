<?php
$obj_transition = new transition();
$obj_gstr2 = new gstr2();

//$obj_login->sendMobileMessage
$returnmonth = date('Y-m');

if (isset($_POST['returnmonth'])) {
    $returnmonth = $_POST['returnmonth'];
    $obj_transition->redirect(PROJECT_URL . "/?page=return_gstr2_mydata&returnmonth=" . $returnmonth);
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
        if ($obj_gstr2->saveGstr1nilexemptSummary()) {
            //$obj_master->redirect(PROJECT_URL."/?page=master_receiver");
        }
    }
}

?>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="col-md-6 col-sm-6 col-xs-12 heading"><h1>HSN-wise summary</h1></div>
        <div class="col-md-6 col-sm-6 col-xs-12 text-right breadcrumb-nav"><a href="#">Home</a>
            <i class="fa fa-angle-right" aria-hidden="true"></i>  <a href="#">File Return</a> <i class="fa fa-angle-right" aria-hidden="true"></i> <span class="active">GST-Transition Form</span> </div>
        <div class="whitebg formboxcontainer">          
           <form method="post" id="auto" name="auto">
                <button  type="button"  class="btn btn-success" id="btnConfirm">autopopulate</button>
                <input type="button" value="<?php echo ucfirst('Back'); ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=return_summary&returnmonth=" . $_REQUEST["returnmonth"]; ?>';" class="btn btn-danger" class="redbtn marlef10"/>

                <input type="hidden" name="autoname" id="autoname" value="1" />
                <input style="display:none;" type='submit' class="btn btn-success" name='autopopulate' value='autopopulate'>
            </form>	   			
            <div class="pull-right rgtdatetxt">

                <form method='post' name='form2'>
                    Month Of Return
<?php
$dataQuery = "SELECT DATE_FORMAT(invoice_date,'%Y-%m') AS niceDate FROM " . $db_obj->getTableName('client_invoice') . " group by nicedate";
$dataRes = $obj_transition->get_results($dataQuery);
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
                <div class="greyheading">1.Inward supplies received by the Taxpayer</div>
                <div class="tableresponsive">
                    <form method="post" enctype="multipart/form-data" id='form'>
                        <table border="1" bordercolor="#ccc" cellpadding="5" cellspacing="0"  class="table  tablecontent tablecontent2 bordernone" id='table1a'>
                            <thead>
                                <tr>
                                    <th>Type Of Invoice</th>
									<th>Number Of Transactions</th>
                                    <th>Taxable Amount(A)(<i class="fa fa-inr"></i>)</th>
									 <th>Taxable Amount(B)(<i class="fa fa-inr"></i>)</th>
                                    <th>Invoice Value<br>(Total Of Previous Columns<br> May Not Match) (B(<i class="fa fa-inr"></i>)</th>
                                    <th>ITC Available (<i class="fa fa-inr"></i>)</th>
									<th>&nbsp;&nbsp;</th>
                              </tr>
                            </thead>

                            <tbody>
						<tr>    
						<td class="lftheading">Inward Supplies received from Registered person including reverse charge supplies  3 | 4A</td>
						<td><label><span class="starred"></span></label></td>
						<td><label><span class="starred"></span></label></td>
						<td><label><span class="starred"></span></label></td>
						<td><label><span class="starred"></span></label></td>
						<td><label><span class="starred"></span></label></td>
						<td><div class="tc"><input type="button" value="View/Edit" onclick="" class="btn btn-success" class="redbtn marlef10"/></div></td>
						</tr>  
                       <tr>    
						<td class="lftheading">Import of Inputs/Capital Goods and Supplies received from SEZ 5</td>
						<td><label><span class="starred"></span></label></td>
						<td><label><span class="starred"></span></label></td>
						<td><label><span class="starred"></span></label></td>
						<td><label><span class="starred"></span></label></td>
						<td><label><span class="starred"></span></label></td>
						<td><div class="tc"><input type="button" value="View/Edit" onclick="" class="btn btn-success" class="redbtn marlef10"/></div></td>
						</tr> 
                        <tr>    
						<td class="lftheading">Import of Service (Reverse charge)</td>
						<td><label><span class="starred"></span></label></td>
						<td><label><span class="starred"></span></label></td>
						<td><label><span class="starred"></span></label></td>
						<td><label><span class="starred"></span></label></td>
						<td><label><span class="starred"></span></label></td>
						<td><div class="tc"><input type="button" value="View/Edit" onclick="" class="btn btn-success" class="redbtn marlef10"/></div></td>
						</tr> 
                        <tr>    
						<td class="lftheading">Debit/Credit Notes for supplies from Registered person 6C</td>
						<td><label><span class="starred"></span></label></td>
						<td><label><span class="starred"></span></label></td>
						<td><label><span class="starred"></span></label></td>
						<td><label><span class="starred"></span></label></td>
						<td><label><span class="starred"></span></label></td>
						<td><div class="tc"><input type="button" value="View/Edit" onclick="" class="btn btn-success" class="redbtn marlef10"/></div></td>
						</tr>  
                        <tr>    
						<td class="lftheading">Inward Supplies from Unregistered supplier (Reverse charge) 4B</td>
						<td><label><span class="starred"></span></label></td>
						<td><label><span class="starred"></span></label></td>
						<td><label><span class="starred"></span></label></td>
						<td><label><span class="starred"></span></label></td>
						<td><label><span class="starred"></span></label></td>
						<td><div class="tc"><input type="button" value="View/Edit" onclick="" class="btn btn-success" class="redbtn marlef10"/></div></td>
						</tr> 
                         <tr>    
						<td class="lftheading">Debit/Credit Notes for Unregistered supplier 6C</td>
						<td><label><span class="starred"></span></label></td>
						<td><label><span class="starred"></span></label></td>
						<td><label><span class="starred"></span></label></td>
						<td><label><span class="starred"></span></label></td>
						<td><label><span class="starred"></span></label></td>
						<td><div class="tc"><input type="button" value="View/Edit" onclick="" class="btn btn-success" class="redbtn marlef10"/></div></td>
						</tr>  							
                        </tbody>
                        </table>                        
                        </div>
    <div class="greyheading">2.Other details (summary level)</div>
	            <div class="tableresponsive">
                    <form method="post" enctype="multipart/form-data" id='form'>
                        <table border="1" bordercolor="#ccc" cellpadding="5" cellspacing="0"  class="table  tablecontent tablecontent2 bordernone" id='table1a'>
                            <thead>
                                <tr>
                                    <th>Type Of Invoice</th>
									<th>Taxable Amount(A)(<i class="fa fa-inr"></i>)</th>
									 <th>Tax Amount(B)(<i class="fa fa-inr"></i>)</th>
                                    <th>Invoice Value<br>(Total Of Previous Columns<br> May Not Match) (B(<i class="fa fa-inr"></i>)</th>
                                   <th>&nbsp;&nbsp;</th>
                              </tr>
                            </thead>

                            <tbody>
						<tr>    
						<td class="lftheading">Supplies from composition taxable person and other exempt/nil rated/non GST supplies 7</td>
						<td><label><span class="starred"></span></label></td>
						<td><label><span class="starred"></span></label></td>
						<td><label><span class="starred"></span></label></td>
						<td><div class="tc"><input type="button" value="View/Edit" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=return_gstr2nil_summary&returnmonth=".$_REQUEST["returnmonth"]; ?>';" class="btn btn-success" class="redbtn marlef10"/></div></td>
						</tr>
                        <tr>    
						<td class="lftheading">Advance amount paid for reverse charge supplies 10A</td>
						<td><label><span class="starred"></span></label></td>
						<td><label><span class="starred"></span></label></td>
						<td><label><span class="starred"></span></label></td>
						<td><div class="tc"><input type="button" value="View/Edit" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=return_gstr2advance_amount&returnmonth=".$_REQUEST["returnmonth"]; ?>';" class="btn btn-success" class="redbtn marlef10"/></div></td>
						</tr>
                        <tr>    
						<td class="lftheading">Adjustment of advance amount paid earlier for reverse charge supplies</td>
						<td><label><span class="starred"></span></label></td>
						<td><label><span class="starred"></span></label></td>
						<td><label><span class="starred"></span></label></td>
						<td><div class="tc"><input type="button" value="View/Edit" onclick="" class="btn btn-success" class="redbtn marlef10"/></div></td>
						</tr>
                        <tr>    
						<td class="lftheading">Input Tax Credit Reversal/Reclaim</td>
						<td><label><span class="starred"></span></label></td>
						<td><label><span class="starred"></span></label></td>
						<td><label><span class="starred"></span></label></td>
						<td><div class="tc"><input type="button" value="View/Edit" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=return_gstr2itc_summary&returnmonth=".$_REQUEST["returnmonth"]; ?>';" class="btn btn-success" class="redbtn marlef10"/></div></td>
						</tr>                        
                        </tbody>
                        </table>                        
                        </div><div class="clear"></div>
				   <div class="panel-group">
					<div class="panel panel-default">
					  <div class="panel-heading">HSN summary of inward supplies 13</div>
					  <div class="panel-body" style="text-align:left;">
					  As per GSTR-2 you need to provide HSN-wise summary of inward supplies made during the tax period.<br> Please enter and verify details of HSN-wise summary before filing.
					  </div>
					    <div class="panel-body" style="text-align:right;">
					  <div style="float: right;margin-top: -65px;" class="tc"><input type="button" value="View/Edit" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=return_gstr2hsnwise_summary&returnmonth=".$_REQUEST["returnmonth"]; ?>';" class="btn btn-success" class="redbtn marlef10"/></div>
					  </div>
					</div><div class="clear"></div><br>						
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
            var element = document.getElementById('trtable1');
            if (element != null && element.value == '') {
                document.getElementById('trtable1').style.display = 'none';
            }
            var element = document.getElementById('trtable2');
            if (element != null && element.value == '') {
                document.getElementById('trtable2').style.display = 'none';
            }
            var data1 = '<select class="required form-control" id="unit"  name="unit[]">';
            var data = '';
            data +=<?php $dataSupplyStateArrs = $obj_transition->get_results("select * from " . $obj_transition->getTableName('unit') . " where status='1' and is_deleted='0' order by unit_name asc"); ?>
<?php if (!empty($dataSupplyStateArrs)) { ?>
                data += '<option value="">Select Unit</option>';
    <?php foreach ($dataSupplyStateArrs as $dataSupplyStateArr) { ?>
                    data += '<option value="<?php echo $dataSupplyStateArr->unit_code; ?>"><?php echo $dataSupplyStateArr->unit_name; ?></option>';
    <?php } ?>
<?php } ?>

            data = data1 + data + '</select>';

            //  var markup = "<tr><td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='srno_from[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='srno_to[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='totalno[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='cancelled[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='netissued[]'/></td><td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td></tr>";
            var markup = "<tr><td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='hsn[]'/></td><td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='description[]'/></td><td>" + data + "</td><td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='qty[]'/></td><td><input type='text' onKeyPress='return  isNumberKey(event,this);' class='required form-control' name='taxable_subtotal[]'/></td><td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='invoice_total_value[]'/></td><td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='igst[]'/></td><td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='cgst[]'/></td><td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='sgst[]'/></td><td><input type='text' class='required form-control' onKeyPress='return  isNumberKey(event,this);' name='cess[]'/></td><td><a class='deleteInvoice del' href='javascript:void(0)'><div class='tooltip2'><i class='fa fa-trash deleteicon'></i><span class='tooltiptext'>Delete</span></div></a></td></tr>";

            // $("table tbody").append(markup);
            $('#table1a').append(markup);
        });
        $('body').delegate('.del', 'click', function () {
            $(this).closest('tr').remove();
        });



    });

</script>

<script>
    $(document).ready(function () {
        $('#returnmonth').on('change', function () {
            document.form2.action = '<?php echo PROJECT_URL; ?>/?page=return_gstr2_mydata&returnmonth=<?php echo $returnmonth; ?>';
                        document.form2.submit();
                    });
                });
</script>

<script>
    $(document).ready(function () {
        $('#returnmonth').on('change', function () {
            document.form2.action = '<?php echo PROJECT_URL; ?>/?page=return_gstr2_mydata&returnmonth=<?php echo $returnmonth; ?>';
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
                    document.auto.action = '<?php echo PROJECT_URL; ?>/?page=return_gstr2_mydata&returnmonth=<?php echo $returnmonth; ?>';
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