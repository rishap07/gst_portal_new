<?php
$obj_gstr1 = new gstr1();
$obj_api =  new gstr();
$returnmonth = '2017-07';
if (!isset($_REQUEST['type']) || $_REQUEST['type'] == '') 
{
  $obj_gstr1->redirect(PROJECT_URL . "/?page=return_client");
  exit();
}
if (!isset($_REQUEST['returnmonth']) || $_REQUEST['returnmonth'] == '') 
{
  $obj_gstr1->redirect(PROJECT_URL . "/?page=return_client");
  exit();
}
$type = 'B2B';
if ($_REQUEST['type'] != '') {
  $type = $_REQUEST['type'];
}


if ($_REQUEST['returnmonth'] != '') {
  $returnmonth = $_REQUEST['returnmonth'];
}

//echo $_REQUEST['type'];
$response = $obj_api->returnSummary($returnmonth,$_REQUEST['type']);
//$obj_api->pr($response);
if(!empty($response->ek) && isset($response->ek))   { 
    if(isset($_POST['submit']) && $_POST['submit']=='Upload GSTR JSON')
    {
        // /$obj_api->pr($_FILES);
        if ($_FILES['json']['name'] != '' && $_FILES['json']['name'] != '') {
            $extension = pathinfo($_FILES['json']['name'], PATHINFO_EXTENSION);
            if($extension == 'json' ) {

                $path = $_FILES['json']['tmp_name'];
                $filesize = $_FILES['json']['size'];
                if($filesize  > 0) {
                    $json = file_get_contents($path);
                    //echo $json;
                    if ($a = $obj_api->gstr1UploadSummary($returnmonth,$jstr='gstr1',$json,$response->ek)) 
                    {
                        //$obj_api->pr($a);
                        $response = $a;
                    }
                } else {
                    $obj_api->setError('Empty File.');
                    return false;
                }
            } else {
                $obj_api->setError('Invalid File Extension, should be in json only.');
                return false;
            }
        }
        else {
            $obj_api->setError('Invalid File ');
            return false;
        }
    }
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
                <a href="<?php echo PROJECT_URL . '/?page=return_upload_invoices&returnmonth=' . $returnmonth ?>"  >
                    Upload To GSTN
                </a>
                </a>
                 <a href="<?php echo PROJECT_URL . '/?page=return_get_summary&returnmonth=' . $returnmonth ?>" class="active">
                    GSTR1 SUMMARY
                </a>  
                <a href="<?php echo PROJECT_URL . '/?page=return_filling_summary&returnmonth=' . $returnmonth ?>">
                    File GSTr-1
                </a>            
            </div>
            <div id="get_summary" class="tabcontent">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="col-md-6 col-sm-12 col-xs-12"><h3>GSTR1 <?php echo $_REQUEST['type'];?> Summary</h3></div>
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
                            <?php if(!empty($response->urls) && isset($response->urls))   { ?>
                                <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                                    <form method="post" style="width:auto; display: inline-block;" enctype="multipart/form-data">
                                        <input type="file" name="json" class="btn btn-default  btnwidth">
                                        <br/>
                                        <input type="submit" name="submit" value="Upload GSTR JSON" class="btn btn-default btn-success btnwidth">
                                    </form>

                                </div>
                               
                                <div class="clearfix"></div>
                                <?php
                                $i=1;
                                foreach ($response->urls as $key => $value) { 
                                    //$obj_gstr1->pr($value);die;?>
                                    Download Encode URL : <a href="<?php echo 'http://sbfiles.gstsystem.co.in'.$value->ul;?>"><button class="btn btn-primary">GSTR1 Json Data <?php echo $i++;?> </button></a><br/><br/>
                                    <?php 
                                } 
                               
                            }
                            else { ?>
                                
                            <?php } ?>
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
            //alert(<?php echo $returnmonth; ?>);
            document.form2.action = '<?php echo PROJECT_URL; ?>/?page=return_get_summary_view&type=<?php echo $type; ?>&returnmonth=<?php echo $returnmonth; ?>';
            document.form2.submit();            
        });
    });

    /******* To get Summary of GSTR1 ********/
    function get_summary() {
        var json = '<?php echo $response;?>';
        var type = '<?php echo $type;?>';
        var returnmonth = '<?php echo $returnmonth;?>';
        $.ajax({
            url: "<?php echo PROJECT_URL; ?>/?ajax=return_gstr1_json_view",
            type: "post",
           data: {json: json,type: type,returnmonth:returnmonth},
            success: function (response) {
               $('#display_json').html(response);

            },
            error: function() {
            }
        });
    }
    /******* To get Summary of GSTR1 ********/
</script>