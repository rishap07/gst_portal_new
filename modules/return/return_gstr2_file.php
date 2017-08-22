<?php
$obj_gstr2 = new gstr2();
$returnmonth = date('Y-m');
if (isset($_POST['returnmonth'])) {
    $returnmonth = $_POST['returnmonth'];
    $obj_gstr2->redirect(PROJECT_URL . "/?page=return_client&returnmonth=" . $returnmonth);
    exit();
}
$returnmonth = date('Y-m');
if (isset($_REQUEST['returnmonth']) && $_REQUEST['returnmonth'] != '') {
    $returnmonth = $_REQUEST['returnmonth'];
}
$time = strtotime($returnmonth . "-01");
$month = date("M", strtotime("+1 month", $time));
if (isset($_POST['submit']) && ($_POST['submit'] = 'File with Aadhar' || $_POST['submit'] == 'File with Digital Signature')) {
    if (!isset($_SERVER['HTTP_REFERER']) || empty($_SERVER['HTTP_REFERER'])) {
        $obj_gstr2->setError('Invalid access to files');
    } else {
        if ($obj_gstr2->gstr2File()) {
            
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

<?php $obj_gstr2->showErrorMessage(); ?>
<?php $obj_gstr2->showSuccessMessge(); ?>
<?php $obj_gstr2->unsetMessage(); ?>

            <div class="pull-right rgtdatetxt">
                <form method='post' name='form4' id="form4">
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
                    <?php } ?>
                </form>
            </div>       


            <div class="col-md-12 col-sm-12 col-xs-12 tablistnav padleft0">
<?php
                              include(PROJECT_ROOT."/modules/return/include/tab.php");
               ?>            </div>
            <div class="clear"> </div>
            <div class="text-center">
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
                <div class="clearfix"></div>

            </div><div class="Clear"></div>
            <div class="invoice-types"><div class="invoice-types__heading"><h4>GSTR-2 Filing Summary</h4></div>



                <div class="tableresponsive">

                    <table  class="table  tablecontent tablecontent2">
                        <thead>

                            <tr>
                                <th>Type Of Invoice</th>
                                <th>NO. INVOICES</th>
                                <th>TAXABLE AMT (₹)	</th>
                                <th class="text-right">TAX AMT (₹)</th>
                                <th class="text-right">ITC CLAIMED (₹)</th>
                                <th class="text-right">REV.CHARGE (₹)</th>

                                <th class=""></th></tr>
                        </thead>
                        <tbody>
                            <tr><td colspan="10">No Invoices </td></tr>
                        </tbody>
                    </table>
                </div>
                <div class="clear">
                    <h4>HSN/SAC summary</h4>
                    <div class="tableresponsive">

                        <table  class="table  tablecontent tablecontent2">
                            <thead>

                                <tr>
                                    <th>S.No.</th>
                                    <th>GOODS/SERVICES</th>
                                    <th>DESCRIPTION</th>
                                    <th class="text-right">HSN/SAC</th>
                                    <th class="text-right">UOM</th>
                                    <th class="text-right">QUANTITY</th>
                                    <th class="text-right">NATURE OF SUPPLY</th>
                                    <th class="text-right">TAXABLE (₹)</th>
                                    <th class="text-right">IGST (₹)</th>
                                    <th class="text-right">CGST (₹)</th>
                                    <th class="text-right">SGST (₹)</th>
                                    <th class="text-right">CESS (₹)</th>

                                    <th class=""></th></tr>
                            </thead>
                            <tbody>
                                <tr><td colspan="10">No Invoices </td></tr>
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
                    window.location.href = "<?php echo PROJECT_URL; ?>/?page=return_gstr2_file&returnmonth=" + $(this).val();
                });
            });
        </script>