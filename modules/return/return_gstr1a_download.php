<?php
$obj_client = new client();

if (!isset($_REQUEST['returnmonth']) || $_REQUEST['returnmonth'] == '') {
    $obj_client->redirect(PROJECT_URL . "/?page=return_client");
    exit();
}
if (isset($_POST['returnmonth'])) {
    $returnmonth = $_POST['returnmonth'];
    $obj_client->redirect(PROJECT_URL . "/?page=return_gstr1a_download&returnmonth=" . $returnmonth);
    exit();
}
$returnmonth = date('Y-m');
if ($_REQUEST['returnmonth'] != '') {
    $returnmonth = $_REQUEST['returnmonth'];
}
?>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr">
    <div class="col-md-11 col-sm-12 col-xs-12 mobpadlr">
        <div class="col-md-12 col-sm-12 col-xs-12 heading">
            <div class="tab col-md-12 col-sm-12 col-xs-12">
                <a href="<?php echo PROJECT_URL . '/?page=return_gstr1a_download&returnmonth=' . $returnmonth ?>" class="active" >
                    Download GSTR-1A
                </a>
                <a href="<?php echo PROJECT_URL . '/?page=return_gstr1a_reconcile&returnmonth=' . $returnmonth ?>"  >
                 GSTR-1A Reconcile
                </a>
                <a href="<?php echo PROJECT_URL . '/?page=return_gstr1a_upload_invoices&returnmonth=' . $returnmonth ?>" >
                    Upload GSTR-1A
                </a>
                </a>
                <a href="<?php echo PROJECT_URL . '/?page=return_gstr1a_filling_summary&returnmonth=' . $returnmonth ?>">
                    File GSTR-1A
                </a>         
               
            </div>
            <div id="London" class="tabcontent">
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
                                </form>
                            </div> 
                            <?php $obj_client->showErrorMessage(); ?>
                            <?php $obj_client->showSuccessMessge(); ?>
                            <?php $obj_client->unsetMessage(); ?>
                         
							  <div class="col-md-6 col-sm-12 col-xs-12">
                                <form method="post">
                                    <input type="submit" name="submit" value="Download GSTR1A" class="btn btn-default btn-success btnwidth addnew">
                                </form>
                            </div>
                            <div class="adminformbx">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="invoice-itemtable" id="mainTable1">
                                    <thead>
                                        <tr>
                                            <th>Type Of Invoice</th>
                                            <th style="text-align:right">No. Invoices</th>
                                            <th style="text-align:right">Taxable Amount ( <i class="fa fa-inr"></i> )</th>
                                              <th style="text-align:right">CGST ( <i class="fa fa-inr"></i> )</th>
											<th style="text-align:right">SGSt ( <i class="fa fa-inr"></i> )</th>
										    <th style="text-align:right">IGST ( <i class="fa fa-inr"></i> )</th>
											<th style="text-align:right">CESS ( <i class="fa fa-inr"></i> )</th>
                                            <th style="text-align:right">Total Amount ( <i class="fa fa-inr"></i> )</th>
											<th style="text-align:right">Action</th>
                                        </tr>
                                        <tr>
                                            <?php
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
                                            ?>
                                            <td>B2B</th>
                                            <td align='right'><?php echo count($b2bData); ?></td>
                                            <td align='right'><?php echo (!empty($b2bTotData) && !is_null($b2bTotData[0]->invoice_total_value)) ? $b2bTotData[0]->invoice_total_value : 0; ?></td>
                                            <td align='right'><?php echo $b2bItemData[0]->cgst_amount; ?></td>
											<td align='right'><?php echo $b2bItemData[0]->sgst_amount; ?></td>
											<td align='right'><?php echo $b2bItemData[0]->igst_amount; ?></td>
											<td align='right'><?php echo $b2bItemData[0]->cess_amount; ?></td>
															
											
                                            <td align='right'><?php echo (!empty($b2bTotData) && !is_null($b2bTotData[0]->invoice_total_value)) ? $b2bTotData[0]->invoice_total_value : 0; ?></td>
											<td align='right'><a href="<?php echo PROJECT_URL . '/?page=return_gstr1a_download_details&type=b2b&returnmonth=' . $returnmonth ?>">View Details</a>
                                        </tr>
<!--                                        <tr>
                                            <td>B2B Amendments</th>
                                            <td align='right'>NA</td>
                                            <td align='right'>NA</td>
                                            <td align='right'>NA</td>
                                            <td align='right'>NA</td>
                                        </tr>-->
                                      
<!--                                        <tr>
                                            <td>B2C Large Amendments</td>
                                            <td align='right'>NA</td>
                                            <td align='right'>NA</td>
                                            <td align='right'>NA</td>
                                            <td align='right'>NA</td>
                                        </tr>-->
                                    
<!--                                        <tr>
                                            <td>B2C Small Amendments</td>
                                            <td align='right'>NA</td>
                                            <td align='right'>NA</td>
                                            <td align='right'>NA</td>
                                            <td align='right'>NA</td>
                                        </tr>-->
										<?php
                                            $b2bItemquery = "select sum(it.cgst_amount) as cgst_amount,sum(it.sgst_amount) as sgst_amount,sum(it.igst_amount) as igst_amount,sum(it.cess_amount) as cess_amount from " . $obj_client->getTableName('client_rt_invoice') . " i inner join " . $obj_client->getTableName("client_rt_invoice_item") . " it on i.invoice_id=it.invoice_id  where (i.invoice_document_nature='creditnote' or i.invoice_document_nature='debitnote') and i.billing_gstin_number!='' and  i.added_by='" . $_SESSION['user_detail']['user_id'] . "' and i.status='1' and i.is_canceled='0' and i.is_deleted='0'  and i.invoice_date like '%" . $returnmonth . "%'";
                                            $b2bItemData = $obj_client->get_results($b2bItemquery);
                                            $b2bquery = "select * from " . $obj_client->getTableName('client_rt_invoice') . " where (invoice_document_nature='creditnote' or invoice_document_nature='debitnote') and billing_gstin_number!='' and  added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0'  and invoice_date like '%" . $returnmonth . "%' ";
                                            $b2bData = $obj_client->get_results($b2bquery);
                                            $b2bTotquery = "select sum(invoice_total_value) as invoice_total_value from " . $obj_client->getTableName('client_rt_invoice') . " where (invoice_document_nature='creditnote' or invoice_document_nature='debitnote') and billing_gstin_number!='' and  added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0'  and invoice_date like '%" . $returnmonth . "%'";
                                            $b2bTotData = $obj_client->get_results($b2bTotquery);
                                            $total = 0;
                                            if (!empty($b2bItemData)) {
                                                $total = $b2bItemData[0]->cgst_amount + $b2bItemData[0]->sgst_amount + $b2bItemData[0]->igst_amount + $b2bItemData[0]->cess_amount;
											}
											if(!empty($b2bTotData[0]->invoice_total_value))
											{
                                            ?>
                                        <tr>
                                            
                                            <td>Credit Debit Notes Registered</td>
                                            <td align='right'><?php echo count($b2bData); ?></td>
                                            <td align='right'><?php echo (!empty($b2bTotData) && !is_null($b2bTotData[0]->invoice_total_value)) ? $b2bTotData[0]->invoice_total_value : 0; ?></td>
                                           <td align='right'><?php echo $b2bItemData[0]->cgst_amount; ?></td>
											<td align='right'><?php echo $b2bItemData[0]->sgst_amount; ?></td>
											<td align='right'><?php echo $b2bItemData[0]->igst_amount; ?></td>
											<td align='right'><?php echo $b2bItemData[0]->cess_amount; ?></td>
													
                                            <td align='right'><?php echo (!empty($b2bTotData) && !is_null($b2bTotData[0]->invoice_total_value)) ? $b2bTotData[0]->invoice_total_value : 0; ?></td>
                                        <td align='right'><a href="<?php echo PROJECT_URL . '/?page=return_gstr1a_download_details&type=cdn&returnmonth=' . $returnmonth ?>">View Details</a>
                                        </tr>
											<?php } ?>
<!--                                        <tr>
                                            <td>Credit Debit Notes Amendments Registered</td>
                                            <td align='right'>NA</td>
                                            <td align='right'>NA</td>
                                            <td align='right'>NA</td>
                                            <td align='right'>NA</td>
                                        </tr>-->
<!--                                        <tr>
                                            <td>NIL</td>
                                            <td align='right'>NA</td>
                                            <td align='right'>NA</td>
                                            <td align='right'>NA</td>
                                            <td align='right'>NA</td>
                                        </tr>-->
                                      

                                            <?php
                                            $b2bItemquery = "select sum(it.cgst_amount) as cgst_amount,sum(it.sgst_amount) as sgst_amount,sum(it.igst_amount) as igst_amount,sum(it.cess_amount) as cess_amount from " . $obj_client->getTableName('client_rt_invoice') . " i inner join " . $obj_client->getTableName("client_rt_invoice_item") . " it on i.invoice_id=it.invoice_id  where (i.invoice_document_nature='creditnote' or i.invoice_document_nature='debitnote') and i.billing_gstin_number='' and  i.added_by='" . $_SESSION['user_detail']['user_id'] . "' and i.status='1' and i.is_canceled='0' and i.is_deleted='0'  and i.invoice_date like '%" . $returnmonth . "%'";
                                            $b2bItemData = $obj_client->get_results($b2bItemquery);
                                            $b2bquery = "select * from " . $obj_client->getTableName('client_rt_invoice') . " where (invoice_document_nature='creditnote' or invoice_document_nature='debitnote') and billing_gstin_number='' and  added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0'  and invoice_date like '%" . $returnmonth . "%' ";
                                            $b2bData = $obj_client->get_results($b2bquery);
                                            $b2bTotquery = "select sum(invoice_total_value) as invoice_total_value from " . $obj_client->getTableName('client_rt_invoice') . " where (invoice_document_nature='creditnote' or invoice_document_nature='debitnote') and billing_gstin_number='' and  added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0'  and invoice_date like '%" . $returnmonth . "%'";
                                            $b2bTotData = $obj_client->get_results($b2bTotquery);
                                            $total = 0;
                                            if (!empty($b2bItemData)) {
                                                $total = $b2bItemData[0]->cgst_amount + $b2bItemData[0]->sgst_amount + $b2bItemData[0]->igst_amount + $b2bItemData[0]->cess_amount;
                                            }
											if(!empty($b2bTotData[0]->invoice_total_value))
											{
                                            ?>
                                          <tr>
                                            <td>Credit Debit Notes Unregistered</td>
                                            <td align='right'><?php echo count($b2bData); ?></td>
                                            <td align='right'><?php echo (!empty($b2bTotData) && !is_null($b2bTotData[0]->invoice_total_value)) ? $b2bTotData[0]->invoice_total_value : 0; ?></td>
                                            <td align='right'><?php echo $b2bItemData[0]->cgst_amount; ?></td>
											<td align='right'><?php echo $b2bItemData[0]->sgst_amount; ?></td>
											<td align='right'><?php echo $b2bItemData[0]->igst_amount; ?></td>
											<td align='right'><?php echo $b2bItemData[0]->cess_amount; ?></td>
													
                                            <td align='right'><?php echo (!empty($b2bTotData) && !is_null($b2bTotData[0]->invoice_total_value)) ? $b2bTotData[0]->invoice_total_value : 0; ?></td>
                                       <td align='right'><a href="<?php echo PROJECT_URL . '/?page=return_gstr1a_download_details&type=cdnu&returnmonth=' . $returnmonth ?>">View Details</a>
											</tr><?php } ?>
<!--                                        <tr>
                                            <td>Credit Debit Notes Amendments Unregistered</td>
                                            <td align='right'>NA</td>
                                            <td align='right'>NA</td>
                                            <td align='right'>NA</td>
                                            <td align='right'>NA</td>
                                        </tr>-->
                                    </thead>
                                </table>
                            </div>  
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
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
        $('#returnmonth').on('change', function () {
            document.form2.action = '<?php echo PROJECT_URL; ?>/?page=return_gstr1a_download&returnmonth=<?php echo $returnmonth; ?>';
                        document.form2.submit();
                    });
                });
</script>