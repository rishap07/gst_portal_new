<?php
$obj_gstr2 = new gstr2();
$returnmonth = date('Y-m');
if (isset($_POST['returnmonth'])) {
    $returnmonth = $_POST['returnmonth'];
    $obj_gstr2->redirect(PROJECT_URL . "/?page=return_gstr2&returnmonth=" . $returnmonth);
    exit();
}
$returnmonth = date('Y-m');
if (isset($_REQUEST['returnmonth']) && $_REQUEST['returnmonth'] != '') {
    $returnmonth = $_REQUEST['returnmonth'];
}

if (isset($_POST['submit']) && $_POST['submit'] == 'Upload TO GSTN') {
    if (!isset($_SERVER['HTTP_REFERER']) || empty($_SERVER['HTTP_REFERER'])) {
        $obj_gstr2->setError('Invalid access to files');
    } else {
        if ($obj_gstr2->gstr2Upload()) {
            
        }
    }
}
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
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
                            <option value="<?php echo $dataRe->niceDate; ?>" <?php if ($dataRe->niceDate == $returnmonth) { echo 'selected';} ?>><?php echo $dataRe->niceDate; ?></option>
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
           <div class="col-md-12 col-sm-12 col-xs-12 heading">
            <div class="tab col-md-12 col-sm-12 col-xs-12">
              <?php
                        include(PROJECT_ROOT."/modules/return/include/tab.php");
               ?>
            </div></div>
            <div class="clearfix"></div>
            <?php $obj_gstr2->showErrorMessage(); ?>
            <?php $obj_gstr2->showSuccessMessge(); ?>
            <?php $obj_gstr2->unsetMessage(); ?>
            <div class="clearfix"></div>
            <?php
            $dataReturns = $obj_gstr2->get_results("select * from " . TAB_PREFIX . "return where return_month='" . $returnmonth . "' and client_id='" . $_SESSION['user_detail']['user_id'] . "' and status='3' and type='gstr2'");
            if (!empty($dataReturns)) {
                ?>
                <div id="sucmsg" style="background-color:#DBEDDF;border-radius:4px;padding:8px 35px 8px 14px;text-shadow:0 1px 0 rgba(255, 255, 255, 0.5);margin-bottom:18px;border-color:#D1E8DA;color:#39A25F;"><i class="fa fa-check"></i> <b>Success:</b> GSTR2 is Already Filed</div>
                <?php
            } else {
                ?>
                <div class="col-md-6 col-sm-12 col-xs-12">
					<button type="button" class="btn btn-default btn-success btnwidth addnew" data-toggle="modal" data-target="#myModal">File GSTR2</button>
                </div>
                <?php
            }
            ?>
            <div class="tableresponsive">
                <table  class="table  tablecontent tablecontent2">
                    <thead>
                        <tr>
                            <th>TYPE OF INVOICE</th>
                            <th>NO. INVOICES</th>
                            <th>TAXABLE AMT</th>
                            <th class="text-right">TAX AMT</th>
                            <th class="text-right">TOTAL AMT INCL. TAX</th>
                            <th class=""></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>B2B</th>
                            <td align='left'>1</td>
                            <td align='left'>1234.56</td>
                            <td align='right'>1234.56</td>
                            <td align='right'>1234.56</td>
                        </tr>
                        <tr>
                            <td>B2C Large</td>
                            <td align='left'>1</td>
                            <td align='left'>1234.56</td>
                            <td align='right'>1234.56</td>
                            <td align='right'>1234.56</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div> 

    </div>
    <div class="clear height40"></div>      
</div>
<div class="clear"></div>
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg" style="margin-top:20%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">E-Filing</h4>
            </div>
            <div class="modal-body">
                <div style="width:50%; margin: auto">
                    <form method="post">
                        <input type="submit" name="submit" value="File with Aadhar" class="btn btn-default btn-success btnwidth addnew">
                        <input type="submit" name="submit" value="File with Digital Signature" class="btn btn-default btn-success btnwidth addnew" style="margin-right: 20px;">

                    </form>
                </div>
                <div class="clearfix height80"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#returnmonth').on('change', function () {
            document.form1.action = '<?php echo PROJECT_URL; ?>/?page=return_gstr2&returnmonth=<?php echo $returnmonth; ?>';
                        document.form1.submit();
                    });
                });
</script>