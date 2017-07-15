<?php
$obj_client = new client();
if (!isset($_REQUEST['returnmonth']) || $_REQUEST['returnmonth'] == '') {
    $obj_client->redirect(PROJECT_URL . "/?page=return_client");
    exit();
}
$returnmonth = '2017-07';
if ($_REQUEST['returnmonth'] != '') {
    $returnmonth = $_REQUEST['returnmonth'];
}
$type='invoice';
if(isset($_POST['invoice_type']))
{
    $type=$_POST['invoice_type'];
}
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr">
    <div class="col-md-11 col-sm-12 col-xs-12 mobpadlr">
        <div class="col-md-12 col-sm-12 col-xs-12 heading">
            <div class="tab col-md-12 col-sm-12 col-xs-12">
                <a href="<?php echo PROJECT_URL . '/?page=return_summary&returnmonth=' . $returnmonth ?>" >
                    View GSTR1 Summary
                </a>
                <a href="<?php echo PROJECT_URL . '/?page=return_view_invoices&returnmonth=' . $returnmonth ?>" class="active">
                    View My Invoice
                </a>
                <a href="<?php echo PROJECT_URL . '/?page=return_upload_invoices&returnmonth=' . $returnmonth ?>">
                    Upload To GSTN
                </a>
                <a href="<?php echo PROJECT_URL . '/?page=return_filling_summary&returnmonth=' . $returnmonth ?>">
                    File GSTr-1
                </a>
            </div>
            <div id="view_invoice" class="tabcontent">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="col-md-6 col-sm-12 col-xs-12"><h3>My Invoices</h3></div>
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <a class='btn btn-default btn-success btnwidth addnew' href='<?php echo PROJECT_URL; ?>/?page=client_create_invoice'>Add New Invoice</a></div>
                </div>
                <form method='post' name='form2'>
                    <div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="whitebg formboxcontainer">
                                <div class="pull-right rgtdatetxt">
                                    Month Of Return 
                                    <?php
                                    $dataQuery = "SELECT DATE_FORMAT(invoice_date,'%Y-%m') AS niceDate FROM gst_client_invoice group by nicedate";
                                    $dataRes = $obj_client->get_results($dataQuery);
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

                                </div>
                                <?php $obj_client->showErrorMessage(); ?>
                                <?php $obj_client->showSuccessMessge(); ?>
                                <?php $obj_client->unsetMessage(); ?>
                                <div class="invoice-types"><div class="invoice-types__heading">Types</div>
                                    <div class="invoice-types__content">
                                        <label for="invoice-types__invoice"><input type="radio" id="invoice-types__invoice" name="invoice_type" value="invoice" class="type" <?php if(isset($_POST['invoice_type']) && $_POST['invoice_type']=='invoice'){ echo 'checked=""';}else{echo 'checked=""';}?>>Invoice</label>
                                        <label for="invoice-types__cdn"><input type="radio" id="invoice-types__cdn" name="invoice_type" value="cdn" class="type" <?php if(isset($_POST['invoice_type']) && $_POST['invoice_type']=='cdn') echo 'checked=""';?>>Credit/Debit Note</label>
                                        <label for="invoice-types__advance_received"><input type="radio" id="invoice-types__advance_received" name="invoice_type" value="advance" class="type" <?php if(isset($_POST['invoice_type']) && $_POST['invoice_type']=='advance') echo 'checked=""';?>>Advance Receipt</label>
                                        <label for="invoice-types__aggregate"><input type="radio" id="invoice-types__aggregate" name="invoice_type" value="nill" class="type" <?php if(isset($_POST['invoice_type']) && $_POST['invoice_type']=='nill') echo 'checked=""';?>>Agg. Nil/Exempt/Non GST</label>
                                        <label for="invoice-types__summary"><input type="radio" id="invoice-types__summary" name="invoice_type" value="all" class="type" <?php if(isset($_POST['invoice_type']) && $_POST['invoice_type']=='all') echo 'checked=""';?>>All Type Summary</label>
                                    </div>
                                </div>
                                <div>
<!--                                    <div class="filtercol"><strong>Filter:</strong>
                                        <select id="multiple-checkboxes" multiple="multiple">
                                            <option value="1">B2B</option>
                                            <option value="2">B2C LARGE</option>
                                            <option value="3">B2C SMALL</option>
                                            <option value="4">EXPORT</option>
                                        </select>
                                    </div>-->
                                    <br/>
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0"  class="table table-striped invoice-filter-table" id="mainTable1">
                                        <?php
                                        if($type=='invoice' && $type=='all')
                                        {
                                            $b2bItemquery = "select sum(it.cgst_amount) as cgst_amount,sum(it.sgst_amount) as sgst_amount,sum(it.igst_amount) as igst_amount,sum(it.cess_amount) as cess_amount from " . $obj_client->getTableName('client_invoice') . " i inner join " . $obj_client->getTableName("client_invoice_item") . " it on i.invoice_id=it.invoice_id  where i.invoice_nature='salesinvoice' and i.added_by='" . $_SESSION['user_detail']['user_id'] . "' and i.status='1' and i.is_canceled='0' and i.is_deleted='0' and i.billing_gstin_number!='' and i.invoice_date like '%" . $returnmonth . "%' ";
                                            $b2bItemData = $obj_client->get_results($b2bItemquery);
                                            $b2bquery = "select * from " . $obj_client->getTableName('client_invoice') . " where invoice_nature='salesinvoice' and added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0' and billing_gstin_number!='' and invoice_date like '%" . $returnmonth . "%'";
                                            $b2bData = $obj_client->get_results($b2bquery);
                                            $b2bTotquery = "select sum(invoice_total_value) as invoice_total_value from " . $obj_client->getTableName('client_invoice') . " where invoice_nature='salesinvoice' and added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0' and billing_gstin_number!='' and invoice_date like '%" . $returnmonth . "%'";
                                            $b2bTotData = $obj_client->get_results($b2bTotquery);
                                            $total = 0;
                                            if (!empty($b2bItemData)) {
                                                $total = $b2bItemData[0]->cgst_amount + $b2bItemData[0]->sgst_amount + $b2bItemData[0]->igst_amount + $b2bItemData[0]->cess_amount;
                                            }
                                        }
                                        ?>
                                        <thead>
                                            <tr>
                                                <th align='left'>Total Transactions</th>
                                                <th align='left'>Total IGST</th>
                                                <th align='left'>Total SGST</th>
                                                <th align='left'>Total CGST</th>
                                                <th align='left'>Total Cess</th>
                                                <th align='left'>Total Amount</th>
                                            </tr>
                                            <tr>
                                                <td>1</td>
                                                <td>10.05</td>
                                                <td>0.0</td>
                                                <td>0.0</td>
                                                <td>0.0</td>
                                                <td>201.05</td>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                                <br/>
                                <div class="adminformbx">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="invoice-itemtable" id="mainTable1">
                                        <thead>
                                            <tr>
                                                <th align='left'>Date</th>
                                                <th align='left'>Id</th>
                                                <th align='left'>Customer</th>
                                                <th align='left'>GSTIN</th>
                                                <th align='left'>Taxable AMT</th>
                                                <th align='left'>Total Tax</th>
                                                <th align='left'>Total Amt</th>
                                                <th align='left'>Type</th>
                                                <th align='left'>Staus</th>
                                            </tr>
                                            <tr>
                                                <td>13/07/2017</th>
                                                <td>INV0000000005</th>
                                                <td>test</th>
                                                <td>12ABCDE</th>
                                                <td>201.05</th>
                                                <td>10.05</th>
                                                <td>201.05</th>
                                                <td>B2B</th>
                                                <td>Not Completed</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>  
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#multiple-checkboxes').multiselect();
    });
</script>
<script>
    function openCity(evt, cityName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(cityName).style.display = "block";
        evt.currentTarget.className += " active";
    }
</script>
<script>
    $(document).ready(function () {
        $('#returnmonth,.type').on('change', function () {
            document.form2.action = '<?php echo PROJECT_URL; ?>/?page=return_view_invoices&returnmonth=<?php echo $returnmonth; ?>';
            document.form2.submit();
        });
    });
</script>