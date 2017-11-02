<?php
$obj_gstr1 = new gstr1();
$obj_api =  new gstr();
if(!$obj_gstr1->can_read('returnfile_list'))
{
    $obj_gstr1->setError($obj_gstr1->getValMsg('can_read'));
    $obj_gstr1->redirect(PROJECT_URL."/?page=dashboard");
    exit();
}
//session_destroy();
$dataCurrentUserArr = $obj_gstr1->getUserDetailsById( $obj_gstr1->sanitize($_SESSION['user_detail']['user_id']) );
//$obj_gstr1->pr($dataCurrentUserArr['data']);die;
if($dataCurrentUserArr['data']->kyc->vendor_type!='1'){
    $obj_gstr1->setError("Invalid Access to file");
    $obj_gstr1->redirect(PROJECT_URL . "/?page=dashboard");
    exit();
}
if (!isset($_REQUEST['returnmonth']) || $_REQUEST['returnmonth'] == '') 
{
  $obj_gstr1->redirect(PROJECT_URL . "/?page=return_client");
  exit();
}
if (isset($_POST['returnmonth'])) 
{
  $returnmonth = $_POST['returnmonth'];
  $obj_gstr1->redirect(PROJECT_URL . "/?page=return_get_summary&returnmonth=" . $returnmonth);
  exit();
}

$returnmonth = '2017-07';
if ($_REQUEST['returnmonth'] != '') {
  $returnmonth = $_REQUEST['returnmonth'];
}
$response = '';
if(isset($_POST['summary_type']) && $_POST['summary_type']=='Download GSTR1 Summary')
{
    if (!isset($_SERVER['HTTP_REFERER']) || empty($_SERVER['HTTP_REFERER'])) 
    {
        $obj_gstr1->setError('Invalid access to files');
    } 
    else 
    {
        //$obj_gstr1->pr($_POST);
        

    }
}
$callReturnSummary = $obj_api->returnSummary($returnmonth);
/***** End GSTR1 API Call *****/

if($callReturnSummary != false) {
    /***** Start Code Insert/update to summary *****/
    $savedata['json'] = base64_encode(serialize($callReturnSummary));
    $obj_api->save_user_summary($savedata,'gstr1',$returnmonth);
    /***** End Code Insert/update to summary *****/
}
else {
   $obj_api->delete_user_summary('gstr1',$returnmonth);
}
/***** Start Get Summray from DB of gstr1 *****/
$response = $obj_api->get_user_summary('gstr1',$returnmonth);

/***** End Get Summray from DB of gstr1 *****/
?>
<?php 

?>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr">
  <div class="col-md-11 col-sm-12 col-xs-12 mobpadlr">
    <div class="col-md-12 col-sm-12 col-xs-12 heading">
        <div class="tab col-md-12 col-sm-12 col-xs-12">
            <a href="<?php echo PROJECT_URL . '/?page=return_summary&returnmonth=' . $returnmonth ?>" >
                1.View GSTR1 Summary
            </a>   
            <a href="<?php echo PROJECT_URL . '/?page=return_view_invoices&returnmonth=' . $returnmonth ?>">
                2.View My Invoice
            </a>
           <!--  <a href="<?php echo PROJECT_URL . '/?page=return_upload_invoices&returnmonth=' . $returnmonth ?>"  >
                Upload To GSTN
            </a> -->
            <a href="<?php echo PROJECT_URL . '/?page=return_get_summary&returnmonth=' . $returnmonth ?>" class="active">
                3.GSTR1 SUMMARY
            </a> 
            
            </a>
            <a href="<?php echo PROJECT_URL . '/?page=return_filling_summary&returnmonth=' . $returnmonth ?>">
                4.File GSTr-1
            </a>
                      
        </div>
        <div id="get_summary" class="tabcontent">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="col-md-6 col-sm-12 col-xs-12"><h3>GSTR1 Summary</h3></div>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="whitebg formboxcontainer">
                        <div class="pull-right rgtdatetxt">
                            <form method='post' name='form2'>
                                Month Of Return 
                                <?php
                                $dataQuery = "SELECT DATE_FORMAT(invoice_date,'%Y-%m') AS niceDate FROM gst_client_invoice group by nicedate";
                                $dataRes = $obj_gstr1->get_results($dataQuery);
                                if (!empty($dataRes)) {
                                    ?>
                                    <select class="dateselectbox" id="returnmonth" name="returnmonth">
                                    <?php
                                    foreach ($dataRes as $dataRe) {
                                        ?>
                                            <option value="<?php echo $dataRe->niceDate; ?>" <?php if ($dataRe->niceDate == $returnmonth) { echo 'selected'; } ?>><?php echo $dataRe->niceDate; ?></option>
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
                        <div class="clearfix"></div>
                        <?php $obj_gstr1->showErrorMessage(); ?>
                        <?php $obj_gstr1->showSuccessMessge(); ?>
                        <?php $obj_gstr1->unsetMessage(); ?>

                        <form method="post" name="form4" id="SummaryForm" action="">

                            <input type="submit" name="submit_summary" id="gstr1_summary_download" value="Latest Download GSTR1 Summary" class="btn  btn-success " >
                            <input type="hidden" name="summary_type"  value="Download GSTR1 Summary"  >
                        </form>
                        <?php
                        /*if(!empty($response)) { ?>
                            
                        <?php } */ ?>
                        <div id="display_json"></div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
</div>
<?php 
$obj_api->DownloadSummaryOtpPopupJs();
?>
<div id="DeleteotpModalBox" class="modal fade" role="dialog" style="z-index: 999999;top: 78px;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <label>Enter OTP</label>
                <input id="all_sum_otp_code" type="textbox" name="otp" class="form-control" data-bind="numeric"  autocomplete="off">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button id="DeleteotpModalBoxSubmit" type="button" value="OTP" class="btn btn-success" >Submit</button>
            </div>
        </div>
    </div>
</div>
<script>
    
    $(document).ready(function () {
        get_summary();
        $('#returnmonth').on('change', function () {
            document.form2.action = '<?php echo PROJECT_URL; ?>/?page=return_get_summary&returnmonth=<?php echo $returnmonth; ?>';
            document.form2.submit();            
        });

        $('body').delegate('.gstr1ViewDeleteBtn','click', function () {
            var type = $(this).attr('type');
            $.ajax({
                url: "<?php echo PROJECT_URL; ?>/?ajax=return_gstr1_details_check",
                type: "json",
                success: function (response) {
                    //alert(response);
                    if(response == 1) {
                        $("#DeleteotpModalBox").modal("show");
                        return false;
                    }
                    else if(response == 0) {
                        common_function_part(type);

                    }
                    else {
                       location.reload();
                       return false;
                    }
                },
                error: function() {
                    alert("Please try again.");
                    return false;
                }
            });     
        });
        $( "#DeleteotpModalBoxSubmit" ).click(function( event ) {
            var otp = $('#all_sum_otp_code').val();
            var type = $('.gstr1ViewDeleteBtn').attr('type');
            //alert(otp);
            //event.preventDefault();
            if(otp != " ") {
                $.ajax({
                    url: "<?php echo PROJECT_URL; ?>/?ajax=return_gstr1_otp_request",
                    type: "post",
                    data: {otp:otp},
                    success: function (response) {
                        //alert(response);
                        var arr = $.parseJSON(response);
                        if(arr.error_code == 0) {
                            //$("#DeleteotpModalBox").modal("hide");
                            //common_function_part(type);
                            location.reload();
                        }
                        else {
                            location.reload();
                            return false;
                        }
                    },
                    error: function() {
                        alert("Enter OTP First");
                        return false;
                    }
                });
                return false;
            }
            else {
                alert("Enter OTP First");
                return false;
            }
            return false;
        });
    });

    function common_function_part(type){
        if(!confirm("Are you sure you want to delete all "+type+" invoices ? "))
        {
            return false;
        }
        $("#loading").show();
        var returnmonth = "<?php echo $returnmonth;?>";
        delete_item_invoice(type,returnmonth);

         
    }

    /******* To get Summary of GSTR1 ********/
    function get_summary() {
        var json = '<?php echo $response;?>';
        var returnmonth = '<?php echo $returnmonth;?>';
        $.ajax({
            url: "<?php echo PROJECT_URL; ?>/?ajax=return_gstr1_json",
            type: "post",
            data: {json: json,returnmonth:returnmonth},
            success: function (response) {
               $('#display_json').html(response);
               
            },
            error: function() {
            }
        });
    }
    /******* To get Summary of GSTR1 ********/

    /******* To delele invoice of GSTR1 ********/
    function delete_item_invoice(type,returnmonth) {
        if(type!= '') {
            $.ajax({
                url: "<?php echo PROJECT_URL; ?>/?ajax=return_gstr1_delete_item_invoice",
                type: "post",
                data: {type:type,returnmonth:returnmonth,deleteType:'all'},
                success: function (response) {
                    $("#loading").hide();
                    location.reload();
                    
                },
                error: function() {
                }
            });
        }
        else {
            alert(' Invoice not found');
        }
    }
    /******* To delele invoice of GSTR1 ********/
</script>
