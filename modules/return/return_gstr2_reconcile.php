<?php
$obj_gstr2 = new gstr2();
$dataCurrentUserArr = $obj_gstr2->getUserDetailsById($_SESSION['user_detail']['user_id']);
$returnmonth = date('Y-m');
if (isset($_REQUEST['returnmonth']) && $_REQUEST['returnmonth'] != '') {
    $returnmonth = $_REQUEST['returnmonth'];
}
?>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="col-md-6 col-sm-6 col-xs-12 heading"><h1>GSTR-2 Filing</h1></div>
        <div class="col-md-6 col-sm-6 col-xs-12 text-right breadcrumb-nav">
            <a href="#">Home</a><i class="fa fa-angle-right" aria-hidden="true"></i>
            <a href="#">File Return</a><i class="fa fa-angle-right" aria-hidden="true"></i>
            <span class="active">GSTR-2 Filing</span>
        </div>

        <div class="whitebg formboxcontainer">
            <div class="pull-right rgtdatetxt">
                <form method='post' name='form5' id="form5">
                    Month Of Return 
                    <?php
                    $dataQuery = "SELECT DATE_FORMAT(invoice_date,'%Y-%m') AS niceDate FROM gst_client_invoice group by nicedate";
                    $dataRes = $obj_gstr2->get_results($dataQuery);
                    if (!empty($dataRes)) {
                        ?>
                        <select class="dateselectbox" id="returnmonth" name="returnmonth">
                            <?php foreach ($dataRes as $dataRe) { ?>
                                <option value="<?php echo $dataRe->niceDate; ?>" <?php
                                if ($dataRe->niceDate == $returnmonth) {
                                    echo 'selected';
                                }
                                ?>><?php echo $dataRe->niceDate; ?></option>
                                    <?php }
                                    ?>
                        </select>
                    <?php } else {
                        ?>
                        <select class="dateselectbox" id="returnmonth" name="returnmonth">
                            <option value="2017-07">2017-07</option>
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
            <div class="clear"></div>
            <?php
            $dataReturns = $obj_gstr2->get_results("select * from " . TAB_PREFIX . "return where return_month='" . $returnmonth . "' and client_id='" . $_SESSION['user_detail']['user_id'] . "' and status='3' and type='gstr2'");
            if (!empty($dataReturns)) {
                ?>
                <div id="sucmsg" style="background-color:#DBEDDF;border-radius:4px;padding:8px 35px 8px 14px;text-shadow:0 1px 0 rgba(255, 255, 255, 0.5);margin-bottom:18px;border-color:#D1E8DA;color:#39A25F;"><i class="fa fa-check"></i> <b>Success:</b> GSTR2 is Already Filed</div>
                <?php
            }
            ?>
            <div class="clear"></div>

            <div class="row gstr2-reconcile">

                <?php
                $missingAddressed = 0;
                $additionalAddressed = 0;
                $mismatchedAddressed = 0;
                $matched = 0;
                $missing = 0;
                $additional = 0;
                $mismatched = 0;
                $matchId = array();
                $mismatchId = array();
                $additionalId = array();
                $missingId = array();
                $addressed = 0;
                $gstr2DownlodedInvoices = $obj_gstr2->get_results("select
                    i.invoice_id, 
                    i.reference_number, 
                    i.serial_number, 
                    i.invoice_type, 
                    i.invoice_nature,
                    i.gstin_number as company_gstin_number, 
                    i.supply_type, 
                    i.export_supply_meant, 
                    i.invoice_date, 
                    i.supply_place, 
                    i.billing_gstin_number, 
                    i.shipping_gstin_number, 
                    i.invoice_total_value, 
                    i.financial_year, 
                    sum(it.taxable_subtotal) as total_taxable_subtotal, 
                    sum(it.cgst_amount) as total_cgst_amount, 
                    sum(it.sgst_amount) as total_sgst_amount, 
                    sum(it.igst_amount) as total_igst_amount, 
                    sum(it.cess_amount) as total_cess_amount 
                    from " . $obj_gstr2->getTableName('client_invoice') . " i inner join " . $obj_gstr2->getTableName("client_invoice_item") . " it on i.invoice_id=it.invoice_id 
                    where 
                    i.invoice_nature='salesinvoice' 
                    AND i.billing_gstin_number='" . $dataCurrentUserArr['data']->kyc->gstin_number . "'
                    AND i.status='1' 
                    AND i.is_canceled='0' 
                    AND i.is_deleted='0' 
                    AND i.is_gstr1_uploaded != '0'
                    AND i.is_gstr2_downloaded = '1' 
                    AND i.invoice_date like '%" . $returnmonth . "%'
                    group by i.invoice_id 
                    order by i.invoice_date ASC");
                
                if ($gstr2DownlodedInvoices) {

                    $query = "select 
                        ci.purchase_invoice_id, 
                        ci.reference_number, 
                        ci.serial_number, 
                        ci.invoice_type, 
                        ci.invoice_nature,
                        ci.company_gstin_number, 
                        ci.supply_type, 
                        ci.import_supply_meant, 
                        ci.invoice_date, 
                        ci.supply_place, 
                        ci.supplier_billing_gstin_number, 
                        ci.recipient_shipping_gstin_number, 
                        ci.invoice_total_value, 
                        ci.financial_year, 
                        sum(cii.taxable_subtotal) as total_taxable_subtotal, 
                        sum(cii.cgst_amount) as total_cgst_amount, 
                        sum(cii.sgst_amount) as total_sgst_amount, 
                        sum(cii.igst_amount) as total_igst_amount, 
                        sum(cii.cess_amount) as total_cess_amount 
                        from " . $obj_gstr2->getTableName('client_purchase_invoice') . " as ci INNER JOIN " . $obj_gstr2->getTableName("client_purchase_invoice_item") . " as cii ON ci.purchase_invoice_id = cii.purchase_invoice_id
                        where 
                        ci.invoice_nature='purchaseinvoice' 
                        AND ci.invoice_date like '%" . $returnmonth . "%'
                        AND ci.recipient_shipping_gstin_number='" . $dataCurrentUserArr['data']->kyc->gstin_number . "'
                        AND ci.supplier_billing_gstin_number='" . $gstr2DownlodedInvoices[0]->company_gstin_number . "'							
                        AND ci.is_deleted='0' 
                        group by ci.purchase_invoice_id";
//echo "<br><pre>";
                    $purchaseInvoices = $obj_gstr2->get_results($query);
//print_r($purchaseInvoices);
                    foreach ($gstr2DownlodedInvoices as $gstr2DownlodedInvoice) {
                        $flag = 0;
                        foreach ($purchaseInvoices as $purchaseInvoice) {
                            if ($gstr2DownlodedInvoice->reference_number === $purchaseInvoice->reference_number) {
                                $flag = 1;
                                //echo 'missmatched ';
                                if (($gstr2DownlodedInvoice->invoice_total_value == $purchaseInvoice->invoice_total_value) && ($gstr2DownlodedInvoice->total_taxable_subtotal == $purchaseInvoice->total_taxable_subtotal) && ($gstr2DownlodedInvoice->total_cgst_amount == $purchaseInvoice->total_cgst_amount) && ($gstr2DownlodedInvoice->total_igst_amount == $purchaseInvoice->total_igst_amount) && ($gstr2DownlodedInvoice->total_cess_amount == $purchaseInvoice->total_cess_amount) && ($gstr2DownlodedInvoice->total_sgst_amount == $purchaseInvoice->total_sgst_amount)) {
                                    $matched++;
                                    array_push($matchId, $purchaseInvoice->purchase_invoice_id);
                                    $invoideData = $obj_gstr2->get_results($query);
                                    $invoideData = $invoideData[0];
                                    $statusQuery = "select *
					from " . $obj_gstr2->getTableName('client_reconcile_purchase_invoice1') . " where
					reference_number='" . $gstr2DownlodedInvoice->reference_number . "'";
                                    $statusData = $obj_gstr2->get_results($statusQuery);
                                    if (count($statusData) <= 0) {
                                        $dataPurInv= array();
                                        $dataPurInv['invoice_type'] = $purchaseInvoice->invoice_type;
                                        $dataPurInv['invoice_nature'] = $purchaseInvoice->invoice_nature;
                                        $dataPurInv['reference_number'] = $purchaseInvoice->reference_number;
                                        $dataPurInv['serial_number'] = $purchaseInvoice->serial_number;
                                        $dataPurInv['invoice_date'] = $purchaseInvoice->invoice_date;
                                        $dataPurInv['invoice_status'] = '0';
                                        $dataPurInv['status'] = '1';
                                        $dataPurInv['added_by'] = $_SESSION['user_detail']['user_id'];
                                        $dataPurInv['added_date'] = date('Y-m-d H:i:s');
                                        $obj_gstr2->insert($obj_gstr2->getTableName('client_reconcile_purchase_invoice1'), $dataPurInv);
                                    }
                                } else {
                                    $statusQuery = "select *
					from " . $obj_gstr2->getTableName('client_reconcile_purchase_invoice1') . " where
					reference_number='" . $gstr2DownlodedInvoice->reference_number . "' AND status!='' AND invoice_status='3'";
                                    $statusData = $obj_gstr2->get_results($statusQuery);
                                    //print_r($statusData);die;
                                    if ($statusData) {
                                        $mismatchedAddressed++;
                                    }
                                    $statusQuery = "select *
			from " . $obj_gstr2->getTableName('client_reconcile_purchase_invoice1') . " where
			reference_number='" . $purchaseInvoice->reference_number . "'";
                                    $statusData = $obj_gstr2->get_results($statusQuery);
                                    if (count($statusData) <= 0) {
                                        $dataPurInv = array();
                                        $dataPurInv['invoice_type'] = $purchaseInvoice->invoice_type;
                                        $dataPurInv['invoice_nature'] = $purchaseInvoice->invoice_nature;
                                        $dataPurInv['reference_number'] = $purchaseInvoice->reference_number;
                                        $dataPurInv['serial_number'] = $purchaseInvoice->serial_number;
                                        $dataPurInv['invoice_date'] = $purchaseInvoice->invoice_date;
                                        $dataPurInv['invoice_status'] = '3';
                                        $dataPurInv['status'] = NULL;
                                        $dataPurInv['added_by'] = $_SESSION['user_detail']['user_id'];
                                        $dataPurInv['added_date'] = date('Y-m-d H:i:s');
                                        $obj_gstr2->insert($obj_gstr2->getTableName('client_reconcile_purchase_invoice1'), $dataPurInv);
                                    }
                                    $mismatched++;
                                    array_push($mismatchId, $purchaseInvoice->purchase_invoice_id);
                                }
                            }
                        }
                        if ($flag == 0) {
                            $statusQuery = "select *
					from " . $obj_gstr2->getTableName('client_reconcile_purchase_invoice1') . " where
					reference_number='" . $gstr2DownlodedInvoice->reference_number . "' AND status!='' AND invoice_status='1'";
                            $statusData = $obj_gstr2->get_results($statusQuery);

                            if ($statusData) {
                                $missingAddressed++;
                            }


                            $missing++;
                            array_push($missingId, $gstr2DownlodedInvoice->invoice_id);
                            $statusQuery = "select *
			from " . $obj_gstr2->getTableName('client_reconcile_purchase_invoice1') . " where
			reference_number='" . $gstr2DownlodedInvoice->reference_number . "'";
                            $statusData = $obj_gstr2->get_results($statusQuery);
                            if (count($statusData) <= 0) {

                                $dataPurInv = array();
                                $dataPurInv['invoice_type'] = $gstr2DownlodedInvoice->invoice_type;
                                $dataPurInv['invoice_nature'] = $gstr2DownlodedInvoice->invoice_nature;
                                $dataPurInv['reference_number'] = $gstr2DownlodedInvoice->reference_number;
                                $dataPurInv['serial_number'] = $gstr2DownlodedInvoice->serial_number;
                                $dataPurInv['invoice_date'] = $gstr2DownlodedInvoice->invoice_date;
                                $dataPurInv['invoice_status'] = '1';
                                $dataPurInv['status'] = NULL;
                                $dataPurInv['added_by'] = $_SESSION['user_detail']['user_id'];
                                $dataPurInv['added_date'] = date('Y-m-d H:i:s');
                                $obj_gstr2->insert($obj_gstr2->getTableName('client_reconcile_purchase_invoice1'), $dataPurInv);
                            }
                        }
                    }
                    foreach ($purchaseInvoices as $purchaseInvoice) {
                        $addFlag = 0;
                        foreach ($gstr2DownlodedInvoices as $gstr2DownlodedInvoice) {
                            if ($purchaseInvoice->reference_number === $gstr2DownlodedInvoice->reference_number) {
                                $addFlag = 1;
                            }
                        }
                        if ($addFlag == 0) {
                            $statusQuery = "select *
					from " . $obj_gstr2->getTableName('client_reconcile_purchase_invoice1') . " where
					reference_number='" . $purchaseInvoice->reference_number . "' AND status!='' AND invoice_status='2'";
                            $statusData = $obj_gstr2->get_results($statusQuery);
                            if ($statusData) {
                                $additionalAddressed++;
                            }
                            $additional++;
                            array_push($additionalId, $purchaseInvoice->purchase_invoice_id);
                            $statusQuery = "select *
			from " . $obj_gstr2->getTableName('client_reconcile_purchase_invoice1') . " where
			reference_number='" . $purchaseInvoice->reference_number . "'";
                            $statusData = $obj_gstr2->get_results($statusQuery);
                            if (count($statusData) <= 0) {
                                $dataPurInv = array();
                                $dataPurInv['invoice_type'] = $purchaseInvoice->invoice_type;
                                $dataPurInv['invoice_nature'] = $purchaseInvoice->invoice_nature;
                                $dataPurInv['reference_number'] = $purchaseInvoice->reference_number;
                                $dataPurInv['serial_number'] = $purchaseInvoice->serial_number;
                                $dataPurInv['invoice_date'] = $purchaseInvoice->invoice_date;
                                $dataPurInv['invoice_status'] = '2';
                                $dataPurInv['status'] = NULL;
                                $dataPurInv['added_by'] = $_SESSION['user_detail']['user_id'];
                                $dataPurInv['added_date'] = date('Y-m-d H:i:s');
                                $obj_gstr2->insert($obj_gstr2->getTableName('client_reconcile_purchase_invoice1'), $dataPurInv);
                            }
                            //echo "additional id".$purchaseInvoice->purchase_invoice_id."<br>";
                        }
                    }


                    if (sizeof($matchId) > 0) {
                        $matchId = implode(",", $matchId);
                    } 
                    if (sizeof($mismatchId) > 0) {
                        $mismatchId = implode(",", $mismatchId);
                    }
                    if (sizeof($missingId) > 0) {
                        $missingId = implode(",", $missingId);
                    }
                    if (sizeof($additionalId) > 0) {
                        $additionalId = implode(",", $additionalId);
                    }
/*                    print_r(sizeof($matchId));
                    print_r($mismatchId);
                    print_r($missingId);
                    print_r($additionalId);die;*/
                    ?>
                    <script>
                        $(document).ready(function () {
                            $('.row-offcanvas-left').addClass('');
                            $(".mobilemenu").click(function () {
                                $("#sidebar").toggle();
                                $('.row-offcanvas-left').addClass('mobileactive');

                            });

                        });


                    </script>



                    <div class="row reconciliation">

                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <div class="lightgreen col-text">
                                <div class="dashcoltxt">
                                    <div class="boxtextheading pull-left">Matched</div>
                                    <?php if (sizeof($matchId) > 0) { ?> 
                                        <a class="pull-right btn bordergreen" href="<?php echo PROJECT_URL . '/?page=return_view_reconcile_invoices&returnmonth=' . $returnmonth . '&matchId=' . $matchId ?>">View Records</a>
                                    <?php } ?>
                                    <div class="clear height10"></div>
                                    <div class="txtnumber col-md-4 col-sm-4"><?php echo $matched ?><br/><span>RECORDS</span><br/></div>


                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <div class="lightblue col-text">
                                <div class="dashcoltxt">
                                    <div class="boxtextheading pull-left">Missing</div>            <?php if (sizeof($missingId) > 0) { ?> 
                                        <a class="pull-right btn borderblue" href="<?php echo PROJECT_URL . '/?page=return_view_reconcile_invoices&returnmonth=' . $returnmonth . '&missingId=' . $missingId ?>">View Records</a>
                                    <?php } ?>
                                    <div class="clear height10"></div>
                                    <div class="txtnumber col-md-4 col-sm-4"><?php echo $missing ?><br/><span>RECORDS</span><br/></div>
                                    <div class="txtnumber col-md-4 col-sm-4"><?php echo $missingAddressed ?><br/><span>ADDRESSED</span><br/></div>
                                    <div class="txtnumber redtxt col-md-4 col-sm-4"><?php echo $missing - $missingAddressed ?><br/><span>PENDING</span><br/></div>

                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <div class="lightyellowbg col-text">
                                <div class="dashcoltxt">
                                    <div class="boxtextheading pull-left">Additional</div>
                                    <?php if (sizeof($additionalId) > 0) { ?> 
                                        <a class="pull-right btn borderbrown" href="<?php echo PROJECT_URL . '/?page=return_view_reconcile_invoices&returnmonth=' . $returnmonth . '&additionalId=' . $additionalId ?>">View Records</a>
                                    <?php } ?>
                                    <div class="clear height10"></div>
                                    <div class="txtnumber col-md-4 col-sm-4"><?php echo $additional ?><br/><span>RECORDS</span><br/></div>
                                    <div class="txtnumber col-md-4 col-sm-4"><?php echo $additionalAddressed ?><br/><span>ADDRESSED</span><br/></div>
                                    <div class="txtnumber redtxt col-md-4 col-sm-4"><?php echo $additional - $additionalAddressed ?><br/><span>PENDING</span><br/></div>

                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <div class="pinkbg col-text">
                                <div class="dashcoltxt">
                                    <div class="boxtextheading pull-left">Mismatch</div>
                                    <?php if (sizeof($mismatchId) > 0) { ?> 
                                        <a class="pull-right btn borderred" href="<?php echo PROJECT_URL . '/?page=return_view_reconcile_invoices&returnmonth=' . $returnmonth . '&mismatchId=' . $mismatchId ?>">View Records</a>
                                    <?php } ?>
                                    <div class="clear height10"></div>
                                    <div class="txtnumber col-md-4 col-sm-4"><?php echo $mismatched ?><br/><span>RECORDS</span><br/></div>
                                    <div class="txtnumber col-md-4 col-sm-4"><?php echo $mismatchedAddressed ?><br/><span>ADDRESSED</span><br/></div>
                                    <div class="txtnumber redtxt col-md-4 col-sm-4"><?php echo $mismatched - $mismatchedAddressed ?><br/><span>PENDING</span><br/></div>

                                </div>
                            </div>
                        </div>



                        <?php
                    } else {
                        echo "<div class='alert alert-danger fade in'> <strong>Error!</strong> Please download GSTR2A From GSTN For current Month. </div>";
                    }
                    ?>

<script>
    $(document).ready(function () {
        $('#returnmonth').on('change', function () {
            window.location.href = "<?php echo PROJECT_URL; ?>/?page=return_gstr2_reconcile&returnmonth=" + $(this).val();
        });
    });
</script>