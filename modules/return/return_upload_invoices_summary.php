<?php
$obj_gstr = new gstr();
$dataCurrentUserArr = $obj_gstr->getUserDetailsById( $obj_gstr->sanitize($_SESSION['user_detail']['user_id']) );
if($dataCurrentUserArr['data']->kyc->vendor_type!='1'){
    $obj_gstr->setError("Invalid Access to file");
    $obj_gstr->redirect(PROJECT_URL . "/?page=dashboard");
    exit();
}
if (!isset($_REQUEST['returnmonth']) || $_REQUEST['returnmonth'] == '') 
{
    $obj_gstr->redirect(PROJECT_URL . "/?page=return_client");
    exit();
}
if(isset($_POST['submit']) && $_POST['submit']=='Upload GSTR JSON')
{
    // /$obj_gstr->pr($_FILES);
    if ($_FILES['json']['name'] != '' && $_FILES['json']['name'] != '') {

        $extension = pathinfo($_FILES['json']['name'], PATHINFO_EXTENSION);
        if($extension == 'json' ) {

            $path = $_FILES['json']['tmp_name'];
            $filesize = $_FILES['json']['size'];
            if($filesize  > 0) {
                $cert_content = file_get_contents($path);
                //echo $cert_content;
                if ($obj_gstr->gstr1UploadSummary($returnmonth,$jstr='gstr1',$cert_content)) 
                {
                    //echo "ddfgfd";
                }
            } else {
                $obj_gstr->setError('Empty File.');
                return false;
            }
        } else {
            $obj_gstr->setError('Invalid File Extension, should be in json only.');
            return false;
        }
    }
    else {
        $obj_gstr->setError('Invalid File ');
        return false;
    }
}
$returnmonth = '2017-07';
if ($_REQUEST['returnmonth'] != '') {
    $returnmonth = $_REQUEST['returnmonth'];
}
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
                <a href="<?php echo PROJECT_URL . '/?page=return_upload_invoices&returnmonth=' . $returnmonth ?>" >
                    Upload To GSTN
                </a>
                </a>
                <a href="<?php echo PROJECT_URL . '/?page=return_get_summary&returnmonth=' . $returnmonth ?>" class="active" >
                    GSTR1 SUMMARY
                </a>
                <a href="<?php echo PROJECT_URL . '/?page=return_filling_summary&returnmonth=' . $returnmonth ?>">
                    File GSTr-1
                </a>     
            </div>

            <div id="upload_invoice" class="tabcontent">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="col-md-6 col-sm-12 col-xs-12"><h3>Upload Summary</h3></div>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="whitebg formboxcontainer">
                            <div class="pull-right rgtdatetxt">
                                <form method='post' name='form2'>
                                    Month Of Return 
                                    <?php
                                    $dataQuery = "SELECT DATE_FORMAT(invoice_date,'%Y-%m') AS niceDate FROM gst_client_invoice group by nicedate";
                                    $dataRes = $obj_gstr->get_results($dataQuery);
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
                            <?php $obj_gstr->showErrorMessage(); ?>
                            <?php $obj_gstr->showSuccessMessge(); ?>
                            <?php $obj_gstr->unsetMessage(); ?>
                            <div class="clearfix"></div>
                            
                            <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                                <form method="post" style="width:auto; display: inline-block;" enctype="multipart/form-data">
                                    <input type="file" name="json" class="btn btn-default  btnwidth">
                                    <br/>
                                    <input type="submit" name="submit" value="Upload GSTR JSON" class="btn btn-default btn-success btnwidth">
                                </form>

                            </div>
                           
                            <div class="clearfix"></div>
                             
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#returnmonth').on('change', function () {
            document.form2.action = '<?php echo PROJECT_URL; ?>/?page=return_upload_invoices&returnmonth=<?php echo $returnmonth; ?>';
            document.form2.submit();
        });
    });
</script>