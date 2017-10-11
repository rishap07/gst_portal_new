<?php
$obj_gstr2 = new gstr2();
$obj_json = new json();
if (!$obj_gstr2->can_read('returnfile_list')) {
    $obj_gstr2->setError($obj_gstr2->getValMsg('can_read'));
    $obj_gstr2->redirect(PROJECT_URL . "/?page=dashboard");
    exit();
}
$dataCurrentUserArr = $obj_gstr2->getUserDetailsById($_SESSION['user_detail']['user_id']);
$returnmonth = date('Y-m');
if (isset($_REQUEST['returnmonth']) && $_REQUEST['returnmonth'] != '') {
    $returnmonth = $_REQUEST['returnmonth'];
}


if (isset($_POST['submit']) && $_POST['submit'] == 'Auto Populate New Data') {
   // die('12323232');
    $ReconcileQuery = $obj_json->getGst2ReconcileQuery($_SESSION['user_detail']['user_id'], $returnmonth);
    $tempRec = $getGstr2B2BQuery = $obj_json->getGstr2B2BQuery($_SESSION['user_detail']['user_id'], $returnmonth, $array_type = true);
  //  echo "<pre>";
  //  print_r($ReconcileQuery);
  //  print_r($getGstr2B2BQuery);
  //  echo "</pre>";
//die;
    //reconcile
    $dataArr = array();
    $matchData = $additionalData = 0;
    $x = 0;

    foreach ($ReconcileQuery as $reconcile) {
        $flag = 0;
        $y = 0;
        foreach ($getGstr2B2BQuery as $b2bData) {
            if (($reconcile['reference_number'] == $b2bData['reference_number']) && ($reconcile['company_gstin_number'] == $b2bData['company_gstin_number'])) {
                $flag = 1;
                $additionalData = 0;
                if (($reconcile['invoice_total_value'] == $b2bData['invoice_total']) && ($reconcile['invoice_date'] == $b2bData['invoice_date'])) {
                    //All values matched : MATCHED
                   // $dataArr[$x]['type'] = $reconcile['type'];
                    $dataArr[$x]['reference_number'] = $reconcile['reference_number'];
                    $dataArr[$x]['invoice_date'] = $reconcile['invoice_date'];
                    $dataArr[$x]['invoice_total_value'] = $reconcile['invoice_total'];
                    $dataArr[$x]['total_taxable_subtotal'] = $reconcile['taxable_total'];
                    $dataArr[$x]['invoice_status'] = 'match';
                    $dataArr[$x]['company_gstin_number'] = $reconcile['company_gstin_number'];
                    $dataArr[$x]['total_cgst_amount'] = $reconcile['cgst'];
                    $dataArr[$x]['total_sgst_amount'] = $reconcile['sgst'];
                    $dataArr[$x]['total_igst_amount'] = $reconcile['igst'];
                    $dataArr[$x]['total_cess_amount'] = $reconcile['cess'];
                    //$dataArr[$x]['invoice_nature'] = '';
                    $dataArr[$x]['invoice_type'] = $reconcile['invoice_type'];
                    $dataArr[$x]['added_by'] = $_SESSION['user_detail']['user_id'];
                    $dataArr[$x]['added_date'] = date('Y-m-d H:i:s',time());
                    $dataArr[$x]['updated_date'] = date('Y-m-d H:i:s',time());
                    $dataArr[$x]['financial_month'] = $reconcile['financial_month'];
                    
                } else {
                    //MISMATCH
                   // $dataArr[$x]['type'] = $reconcile['type'];
                    $dataArr[$x]['reference_number'] = $reconcile['reference_number'];
                    $dataArr[$x]['invoice_date'] = $reconcile['invoice_date'];
                    $dataArr[$x]['invoice_total_value'] = $reconcile['invoice_total'];
                    $dataArr[$x]['total_taxable_subtotal'] = $reconcile['taxable_total'];
                    $dataArr[$x]['invoice_status'] = 'mismatch';
                    $dataArr[$x]['company_gstin_number'] = $reconcile['company_gstin_number'];
                    $dataArr[$x]['total_cgst_amount'] = $reconcile['cgst'];
                    $dataArr[$x]['total_sgst_amount'] = $reconcile['sgst'];
                    $dataArr[$x]['total_igst_amount'] = $reconcile['igst'];
                    $dataArr[$x]['total_cess_amount'] = $reconcile['cess'];
                    //$dataArr[$x]['invoice_nature'] = '';
                    $dataArr[$x]['invoice_type'] = $reconcile['invoice_type'];
                    $dataArr[$x]['added_by'] = $_SESSION['user_detail']['user_id'];
                    $dataArr[$x]['added_date'] = date('Y-m-d H:i:s',time());
                    $dataArr[$x]['updated_date'] = date('Y-m-d H:i:s',time());
                    $dataArr[$x]['financial_month'] = $reconcile['financial_month'];
                }
                unset($tempRec[$y]);
            }
            $y++;
        }
        if ($flag == 0) {
            //Addtional
            $dataArr[$x]['reference_number'] = $reconcile['reference_number'];
            $dataArr[$x]['invoice_date'] = $reconcile['invoice_date'];
            $dataArr[$x]['invoice_total_value'] = $reconcile['invoice_total'];
            $dataArr[$x]['total_taxable_subtotal'] = $reconcile['taxable_total'];
            $dataArr[$x]['invoice_status'] = 'additional';
            $dataArr[$x]['company_gstin_number'] = $reconcile['company_gstin_number'];
            $dataArr[$x]['total_cgst_amount'] = $reconcile['cgst'];
            $dataArr[$x]['total_sgst_amount'] = $reconcile['sgst'];
            $dataArr[$x]['total_igst_amount'] = $reconcile['igst'];
            $dataArr[$x]['total_cess_amount'] = $reconcile['cess'];
            //$dataArr[$x]['invoice_nature'] = '';
            $dataArr[$x]['invoice_type'] = $reconcile['invoice_type'];
            $dataArr[$x]['added_by'] = $_SESSION['user_detail']['user_id'];
            $dataArr[$x]['added_date'] = date('Y-m-d H:i:s',time());
            $dataArr[$x]['updated_date'] = date('Y-m-d H:i:s',time());
            $dataArr[$x]['financial_month'] = $reconcile['financial_month'];
        }
        $x = 0;
    }

    //Missing
    $missingArry = $tempRec;
    foreach ($missingArry as $missingArr) {
        $dataArr[$x]['reference_number'] = $missingArr['reference_number'];
        $dataArr[$x]['invoice_date'] = $missingArr['invoice_date'];
        $dataArr[$x]['invoice_total_value'] = $missingArr['invoice_total'];
        $dataArr[$x]['total_taxable_subtotal'] = $missingArr['taxable_total'];
        $dataArr[$x]['invoice_status'] = 'additional';
        $dataArr[$x]['company_gstin_number'] = $missingArr['company_gstin_number'];
        $dataArr[$x]['total_cgst_amount'] = $missingArr['cgst'];
        $dataArr[$x]['total_sgst_amount'] = $missingArr['sgst'];
        $dataArr[$x]['total_igst_amount'] = $missingArr['igst'];
        $dataArr[$x]['total_cess_amount'] = $missingArr['cess'];
        //$dataArr[$x]['invoice_nature'] = '';
        $dataArr[$x]['invoice_type'] = $missingArr['invoice_type'];
        $dataArr[$x]['added_by'] = $_SESSION['user_detail']['user_id'];
        $dataArr[$x]['added_date'] = date('Y-m-d H:i:s',time());
        $dataArr[$x]['updated_date'] = date('Y-m-d H:i:s',time());
        $dataArr[$x]['financial_month'] = date('Ym',strtotime($missingArr['invoice_date']));
        }
        $x++;
        $dataArr[0];
  echo "<pre>";print_r($dataArr[0]); echo "</pre>";
  if($data === $obj_json->insertMultiple($tableName = 'gstr2_reconcile_final', $dataArr))
          {
      
      die('success');
          }else{
              print_r($data);
              die('false');
          }
   
}


?>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="col-md-6 col-sm-6 col-xs-12 heading">
            <h1>GSTR-2 Filing</h1>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12 text-right breadcrumb-nav"> <a href="#">Home</a><i class="fa fa-angle-right" aria-hidden="true"></i> <a href="#">File Return</a><i class="fa fa-angle-right" aria-hidden="true"></i> <span class="active">GSTR-2 Filing</span> </div>
        <div class="whitebg formboxcontainer">
            <form method='post'>
                <input type="submit" name="submit" class="btn btn-success" value="Auto Populate New Data">
            </form>
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
            <div class="tab col-md-12 col-sm-12 col-xs-12">
                <?php include(PROJECT_ROOT . "/modules/return/include/tab.php");?>
            </div>
            <div class="clear"></div>
            <div class="row gstr2-reconcile">
                <div class="row reconciliation">
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <div class="lightgreen col-text">
                            <div class="dashcoltxt">
                                <div class="boxtextheading pull-left">Matched</div>
                                <div class="pull-right btn bordergreen"><a href="http://10.0.16.145/projects/gst_portal_new/?page=return_gstr2_view_reconcile_invoices&matchedFlag=1&amp;ids=">View Records</a></div>
                                <div class="clear height10"></div>
                                <div class="txtnumber col-md-4 col-sm-4"><?php 
                                    
                                    //echo $matchData; ?><br>
                                    <span>RECORDS</span><br>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <div class="lightblue col-text">
                            <div class="dashcoltxt">
                                <div class="boxtextheading pull-left">Missing</div>
                                <div class="pull-right btn borderblue"><a href="http://10.0.16.145/projects/gst_portal_new/?page=return_gstr2_view_reconcile_invoices&matchedFlag=0&amp;ids=">View Records</a></div>
                                <div class="clear height10"></div>
                                <div class="txtnumber col-md-4 col-sm-4">0<br>
                                    <span>RECORDS</span><br>
                                </div>
                                <div class="txtnumber col-md-4 col-sm-4">0<br>
                                    <span>ADDRESSED</span><br>
                                </div>
                                <div class="txtnumber redtxt col-md-4 col-sm-4">0<br>
                                    <span>PENDING</span><br>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <div class="lightyellowbg col-text">
                            <div class="dashcoltxt">
                                <div class="boxtextheading pull-left">Additional</div>
                                <div class="pull-right btn borderbrown"><a href="http://10.0.16.145/projects/gst_portal_new/?page=return_gstr2_view_reconcile_invoices&matchedFlag=0&amp;ids=">View Records</a></div>
                                <div class="clear height10"></div>
                                <div class="txtnumber col-md-4 col-sm-4"><?php //echo $additionalData; ?><br>
                                    <span>RECORDS</span><br>
                                </div>
                                <div class="txtnumber col-md-4 col-sm-4">0<br>
                                    <span>ADDRESSED</span><br>
                                </div>
                                <div class="txtnumber redtxt col-md-4 col-sm-4">0<br>
                                    <span>PENDING</span><br>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <div class="pinkbg col-text">
                            <div class="dashcoltxt">
                                <div class="boxtextheading pull-left">Mismatch</div>
                                <div class="pull-right btn borderred"><a href="http://10.0.16.145/projects/gst_portal_new/?page=return_gstr2_view_reconcile_invoices&matchedFlag=0&amp;ids=0">View Records</a></div>
                                <div class="clear height10"></div>
                                <div class="txtnumber col-md-4 col-sm-4">0<br>
                                    <span>RECORDS</span><br>
                                </div>
                                <div class="txtnumber col-md-4 col-sm-4">0<br>
                                    <span>ADDRESSED</span><br>
                                </div>
                                <div class="txtnumber redtxt col-md-4 col-sm-4">0<br>
                                    <span>PENDING</span><br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="clear"></div>


            <script>
                $(document).ready(function () {
                    $('#returnmonth').on('change', function () {
                        window.location.href = "<?php echo PROJECT_URL; ?>/?page=return_gstr2_reconcile&returnmonth=" + $(this).val();
                    });
                });
            </script>