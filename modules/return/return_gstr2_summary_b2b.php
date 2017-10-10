<?php
$obj_gstr2 = new gstr2();
$obj_api =  new gstr();
$returnmonth = date('Y-m');
if(!$obj_gstr2->can_read('returnfile_list'))
{
    $obj_gstr2->setError($obj_gstr2->getValMsg('can_read'));
    $obj_gstr2->redirect(PROJECT_URL."/?page=dashboard");
    exit();
}
if (isset($_POST['returnmonth'])) {
    $returnmonth = $_POST['returnmonth'];
    $obj_gstr2->redirect(PROJECT_URL . "/?page=return_gstr2&returnmonth=" . $returnmonth);
    exit();
}
if (isset($_REQUEST['returnmonth']) && $_REQUEST['returnmonth'] != '') {
    $returnmonth = $_REQUEST['returnmonth'];
    if ($obj_gstr2->startGstr2()) {
        
    }
} else {

    $dataQuery = "SELECT DATE_FORMAT(invoice_date,'%Y-%m') AS niceDate FROM gst_client_invoice group by nicedate ORDER by invoice_date desc LIMIT 0,1";
    $dataRes = $obj_gstr2->get_results($dataQuery);
    if (!empty($dataRes)) {
        $returnmonth = $dataRes[0]->niceDate;
    }
}
$time = strtotime($returnmonth . "-01");
$month = date("M", strtotime("+1 month", $time));
$response_b2b = $obj_api->returnSummary($returnmonth,'B2B','gstr2a');
$response_cdn = $obj_api->returnSummary($returnmonth,'CDN','gstr2a');
?>
<?php

?>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="col-md-6 col-sm-6 col-xs-12 heading"><h1>GSTR-2 Filing</h1></div>
        <div class="col-md-6 col-sm-6 col-xs-12 text-right breadcrumb-nav"><a href="#">Home</a>  <i class="fa fa-angle-right" aria-hidden="true"></i>  <a href="#">File Return</a> <i class="fa fa-angle-right" aria-hidden="true"></i> <span class="active">GSTR-2 Filing</span> </div>
        <div class="whitebg formboxcontainer">
            <div class="pull-right rgtdatetxt">
                <form method='post' name='form1'>
                    Month Of Return 
                    <?php
                        $dataQuery = "SELECT DATE_FORMAT(invoice_date,'%Y-%m') AS niceDate FROM gst_client_invoice group by nicedate";
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

            <div class="col-md-12 col-sm-12 col-xs-12 tablistnav padleft0">
            <?php
                include(PROJECT_ROOT."/modules/return/include/tab.php");
            ?>
            </div>
            <div class="tableresponsive">
            <div class="clearfix"></div>
            <?php $obj_gstr2->showErrorMessage(); ?>
            <?php $obj_gstr2->showSuccessMessge(); ?>
            <?php $obj_gstr2->unsetMessage(); ?>
            
            <div id="display_json"></div>

            </div>
        </div> 

    </div>
    <div class="clear height40"></div>      
</div>
<div class="clear"></div>
<script>
    get_summary();
    $(document).ready(function () {
        $('#returnmonth').on('change', function () {
            document.form1.action = '<?php echo PROJECT_URL; ?>/?page=return_gstr2&returnmonth=<?php echo $returnmonth; ?>';
                        document.form1.submit();
                    });
                });

    /******* To get Summary of GSTR2 ********/
    function get_summary() {
        var response_b2b = '<?php echo $response_b2b;?>';
        var response_cdn = '<?php echo $response_cdn;?>';
        var jstr = 'gstr2b'
        var returnmonth = '<?php echo $returnmonth;?>';
        $.ajax({
            url: "<?php echo PROJECT_URL; ?>/?ajax=return_gstr1_json_view",
            type: "post",
            data: {response_b2b: response_b2b,response_cdn: response_cdn,returnmonth:returnmonth,jstr:jstr},
            success: function (response) {
               $('#display_json').html(response);

            },
            error: function() {
            }
        });
    }
    /******* To get Summary of GSTR2 ********/
</script>