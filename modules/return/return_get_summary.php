<?php
$obj_gstr1 = new gstr1();
$obj_api =  new gstr();

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

$response = $obj_api->returnSummary($returnmonth);

?>
<?php 

?>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr">
  <div class="col-md-11 col-sm-12 col-xs-12 mobpadlr">
    <div class="col-md-12 col-sm-12 col-xs-12 heading">
        <div class="tab col-md-12 col-sm-12 col-xs-12">
            <a href="<?php echo PROJECT_URL . '/?page=return_summary&returnmonth=' . $returnmonth ?>" >
                View GSTR1 Summary
            </a>   
            <a href="<?php echo PROJECT_URL . '/?page=return_view_invoices&returnmonth=' . $returnmonth ?>">
                View My Invoice
            </a>
            <a href="<?php echo PROJECT_URL . '/?page=return_upload_invoices&returnmonth=' . $returnmonth ?>"  >
                Upload To GSTN
            </a>
            <a href="<?php echo PROJECT_URL . '/?page=return_get_summary&returnmonth=' . $returnmonth ?>" class="active">
                GSTR1 SUMMARY
            </a> 
            
            </a>
            <a href="<?php echo PROJECT_URL . '/?page=return_filling_summary&returnmonth=' . $returnmonth ?>">
                File GSTr-1
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
                        <div id="display_json"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
</div>
<script>
    get_summary();
    $(document).ready(function () {
        $('#returnmonth').on('change', function () {
            document.form2.action = '<?php echo PROJECT_URL; ?>/?page=return_get_summary&returnmonth=<?php echo $returnmonth; ?>';
            document.form2.submit();            
        });

        $('body').delegate('.gstr1ViewDeleteBtn','click', function () {
            if(!confirm("Are you sure you want to delete?"))
            {
                return false;
            }
            $("#loading").show();
            var type = $(this).attr('type');
            var returnmonth = "<?php echo $returnmonth;?>";
            delete_item_invoice(type,returnmonth);          
        });
    });

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
            alert(type+ ' Invoice empty');
        }
    }
    /******* To delele invoice of GSTR1 ********/
</script>
