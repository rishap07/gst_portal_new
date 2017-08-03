<?php
$obj_client = new client();
$obj_gstr1 = new gstr1();
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
if (isset($_POST['returnmonth'])) 
{
	
    $returnmonth = $_POST['returnmonth'];
	 
    $obj_gstr1->redirect(PROJECT_URL . "/?page=return_purchase_all&returnmonth=" . $returnmonth);
     exit();
}
?>
      
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
           <form method='post' name='form2' id="form2">
       <div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
	   
       			<div class="col-md-12 col-sm-12 col-xs-12">
               
                	<div class="col-md-6 col-sm-6 col-xs-12 heading"><h1>GSTR-2 Filing</h1></div>
                    <div class="col-md-6 col-sm-6 col-xs-12 text-right breadcrumb-nav"><a href="#">Home</a>  <i class="fa fa-angle-right" aria-hidden="true"></i>  <a href="#">File Return</a> <i class="fa fa-angle-right" aria-hidden="true"></i> <span class="active">GSTR-2 Filing</span> </div>
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
                     
                    	<div class="col-md-12 col-sm-12 col-xs-12 tablistnav padleft0">
							<ul>
								<li><a href="<?php echo PROJECT_URL . '/?page=return_gstr2&returnmonth=' . $returnmonth ?>" >View GSTR2 Summary</a></li>
								<li><a href="<?php echo PROJECT_URL . '/?page=return_purchase_all&returnmonth=' . $returnmonth ?>" class="active"> View My Data</a></li>
								<li><a href="<?php echo PROJECT_URL . '/?page=return_vendor_invoices&returnmonth=' . $returnmonth ?>">Download GSTR-2A</a></li>
								<li><a href="<?php echo PROJECT_URL . '/?page=return_gstr2_reconcile&returnmonth=' . $returnmonth ?>">GSTR-2 Reconcile</a></li>
                                <li><a href="<?php echo PROJECT_URL . '/?page=return_gstr2_upload_invoices&returnmonth=' . $returnmonth ?>">Upload To GSTN</a></li>
								<li><a href="<?php echo PROJECT_URL . '/?page=return_gstr2_file&returnmonth=' . $returnmonth ?>">GSTR-2 Filing</a></li>

								
							</ul>
                            </div><br>
							<?php $obj_client->showErrorMessage(); ?>
                                <?php $obj_client->showSuccessMessge(); ?>
                                <?php $obj_client->unsetMessage(); ?>
                              
                       <div class="clear"></div>            
                      <div class="text-right">
					 <a href="<?php echo PROJECT_URL . '/?page=purchase_invoice_create' ?>" class="btngreen"><i class="fa fa-cloud-download" aria-hidden="true"></i>Add New Invoice</a> 
					
					 </div>
							  <div class="invoice-types"><div class="invoice-types__heading">Types</div>
                                    <div class="invoice-types__content">
                                        <label for="invoice-types__invoice"><input type="radio" id="invoice-types__invoice" name="invoice_type" value="invoice" class="type" <?php if(isset($_POST['invoice_type']) && $_POST['invoice_type']=='invoice'){ echo 'checked=""';}else{echo 'checked=""';}?>>Invoice</label>
                                        <label for="invoice-types__cdn"><input type="radio" id="invoice-types__cdn" name="invoice_type" value="cdn" class="type" <?php if(isset($_POST['invoice_type']) && $_POST['invoice_type']=='cdn') echo 'checked=""';?>>Credit/Debit Note</label>
                                        <label for="invoice-types__advance_received"><input type="radio" id="invoice-types__advance_received" name="invoice_type" value="advance" class="type" <?php if(isset($_POST['invoice_type']) && $_POST['invoice_type']=='advance') echo 'checked=""';?>>Advance Receipt</label>
<!--                                        <label for="invoice-types__aggregate"><input type="radio" id="invoice-types__aggregate" name="invoice_type" value="nill" class="type" <?php if(isset($_POST['invoice_type']) && $_POST['invoice_type']=='nill') echo 'checked=""';?>>Agg. Nil/Exempt/Non GST</label>-->
                                        <label for="invoice-types__summary"><input type="radio" id="invoice-types__summary" name="invoice_type" value="all" class="type" <?php if(isset($_POST['invoice_type']) && $_POST['invoice_type']=='all') echo 'checked=""';?>>All Type Summary</label>
                                    </div>
                                </div><br>
							 
							 <table width="100%" border="0" cellspacing="0" cellpadding="0"  class="table table-striped invoice-filter-table" id="mainTable1">
                                         <?php
                                        $invCount= 0;
                                        $igstTotal= 0;
                                        $cgstTotal= 0;
                                        $sgstTotal= 0;
                                        $cessTotal= 0;
                                        $invTotal=0;
                                        if($type=='invoice' || $type=='all')
                                        {
                                            $b2bItemquery = "select sum(it.cgst_amount) as cgst_amount,sum(it.sgst_amount) as sgst_amount,sum(it.igst_amount) as igst_amount,sum(it.cess_amount) as cess_amount from " . $obj_client->getTableName('client_purchase_invoice') . " i inner join " . $obj_client->getTableName("client_purchase_invoice_item") . " it on i.purchase_invoice_id=it.purchase_invoice_id  where i.invoice_nature='purchaseinvoice' and i.added_by='" . $_SESSION['user_detail']['user_id'] . "' and i.status='1' and i.is_canceled='0' and i.is_deleted='0' and i.supplier_billing_gstin_number!='' and i.invoice_date like '%" . $returnmonth . "%' ";
                                            $b2bItemData = $obj_client->get_results($b2bItemquery);
                                            $igstTotal += (isset($b2bItemData[0]->igst_amount)) ? $b2bItemData[0]->igst_amount : '0';
                                            $cgstTotal += (isset($b2bItemData[0]->cgst_amount)) ? $b2bItemData[0]->cgst_amount : '0';
                                            $sgstTotal += (isset($b2bItemData[0]->sgst_amount)) ? $b2bItemData[0]->sgst_amount : '0';
                                            $cessTotal += (isset($b2bItemData[0]->cess_amount)) ? $b2bItemData[0]->cess_amount : '0';
                                            $b2bquery = "select * from " . $obj_client->getTableName('client_purchase_invoice') . " where invoice_nature='purchaseinvoice' and added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0' and supplier_billing_gstin_number!='' and invoice_date like '%" . $returnmonth . "%'";
                                            $invCount += count($obj_client->get_results($b2bquery));
                                            $b2bTotquery = "select sum(invoice_total_value) as invoice_total_value from " . $obj_client->getTableName('client_purchase_invoice') . " where invoice_nature='purchaseinvoice' and added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0' and supplier_billing_gstin_number!='' and invoice_date like '%" . $returnmonth . "%'";
                                            $b2bTotData = $obj_client->get_results($b2bTotquery);
                                            $invTotal += (isset($b2bTotData[0]->invoice_total_value)) ? $b2bTotData[0]->invoice_total_value : '0';
                                            $total = 0;
                                            if (!empty($b2bItemData)) {
                                                $total = $b2bItemData[0]->cgst_amount + $b2bItemData[0]->sgst_amount + $b2bItemData[0]->igst_amount + $b2bItemData[0]->cess_amount;
                                            }
                                        }
										if($type=='cdn' || $type=='all')
                                        {
                                            $b2bItemquery = "select sum(it.cgst_amount) as cgst_amount,sum(it.sgst_amount) as sgst_amount,sum(it.igst_amount) as igst_amount,sum(it.cess_amount) as cess_amount from " . $obj_client->getTableName('client_rt_invoice') . " i inner join " . $obj_client->getTableName("client_rt_invoice_item") . " it on i.invoice_id=it.invoice_id  where (i.invoice_document_nature='creditnote' or i.invoice_document_nature='debitnote') and  i.added_by='" . $_SESSION['user_detail']['user_id'] . "' and i.status='1' and i.is_canceled='0' and i.is_deleted='0'  and i.invoice_date like '%" . $returnmonth . "%'";
                                            $b2bItemData = $obj_client->get_results($b2bItemquery);
                                            $igstTotal += (isset($b2bItemData[0]->igst_amount)) ? $b2bItemData[0]->igst_amount : '0';
                                            $cgstTotal += (isset($b2bItemData[0]->cgst_amount)) ? $b2bItemData[0]->cgst_amount : '0';
                                            $sgstTotal += (isset($b2bItemData[0]->sgst_amount)) ? $b2bItemData[0]->sgst_amount : '0';
                                            $cessTotal += (isset($b2bItemData[0]->cess_amount)) ? $b2bItemData[0]->cess_amount : '0';
                                            $b2bquery = "select * from " . $obj_client->getTableName('client_rt_invoice') . " where (invoice_document_nature='creditnote' or invoice_document_nature='debitnote') and  added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0'  and invoice_date like '%" . $returnmonth . "%' ";
                                            $invCount += count($obj_client->get_results($b2bquery));
                                            $b2bTotquery = "select sum(invoice_total_value) as invoice_total_value from " . $obj_client->getTableName('client_rt_invoice') . " where (invoice_document_nature='creditnote' or invoice_document_nature='debitnote') and  added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0'  and invoice_date like '%" . $returnmonth . "%'";
                                            $b2bTotData = $obj_client->get_results($b2bTotquery);
                                            $invTotal += (isset($b2bTotData[0]->invoice_total_value)) ? $b2bTotData[0]->invoice_total_value : '0';
                                            $total = 0;
                                            if (!empty($b2bItemData)) {
                                                $total = $b2bItemData[0]->cgst_amount + $b2bItemData[0]->sgst_amount + $b2bItemData[0]->igst_amount + $b2bItemData[0]->cess_amount;
                                            }
                                        }
                                        if($type=='advance' || $type=='all')
                                        {
                                            $b2bItemquery = "select sum(it.cgst_amount) as cgst_amount,sum(it.sgst_amount) as sgst_amount,sum(it.igst_amount) as igst_amount,sum(it.cess_amount) as cess_amount from " . $obj_client->getTableName('client_rv_invoice') . " i inner join " . $obj_client->getTableName("client_rv_invoice_item") . " it on i.invoice_id=it.invoice_id  where  i.added_by='" . $_SESSION['user_detail']['user_id'] . "' and i.status='1' and i.is_canceled='0' and i.is_deleted='0'  and i.invoice_date like '%" . $returnmonth . "%'";
                                            $b2bItemData = $obj_client->get_results($b2bItemquery);
                                            $igstTotal += (isset($b2bItemData[0]->igst_amount)) ? $b2bItemData[0]->igst_amount : '0';
                                            $cgstTotal += (isset($b2bItemData[0]->cgst_amount)) ? $b2bItemData[0]->cgst_amount : '0';
                                            $sgstTotal += (isset($b2bItemData[0]->sgst_amount)) ? $b2bItemData[0]->sgst_amount : '0';
                                            $cessTotal += (isset($b2bItemData[0]->cess_amount)) ? $b2bItemData[0]->cess_amount : '0';
                                            $b2bquery = "select * from " . $obj_client->getTableName('client_rv_invoice') . " where added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0'  and invoice_date like '%" . $returnmonth . "%' ";
                                            $invCount += count($obj_client->get_results($b2bquery));
                                           $b2bTotquery = "select sum(invoice_total_value) as invoice_total_value from " . $obj_client->getTableName('client_rv_invoice') . " where added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0'  and invoice_date like '%" . $returnmonth . "%'";
                                            $b2bTotData = $obj_client->get_results($b2bTotquery);
                                            $invTotal += (isset($b2bTotData[0]->invoice_total_value)) ? $b2bTotData[0]->invoice_total_value : '0';
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
                                               <td><?php echo $invCount;?></td>
                                                <td><?php echo $igstTotal; ?></td>
                                                <td><?php echo $sgstTotal; ?></td>
                                                <td><?php echo $cgstTotal; ?></td>
                                                <td><?php echo $cessTotal; ?></td>
                                                <td><?php echo $invTotal; ?></td>
                                            </tr>
                                        </thead>
                                    </table>
                           <div class="tableresponsive">
						     
                            <table  class="table  tablecontent tablecontent2">
                                <thead>
								
                                <tr>
                                <th>Date</th>
                                <th>Id</th>
                                <th>Vendor</th>
                                <th class="text-right">GSTIN</th>
                                <th class="text-right">TaxableAmt.</th>
								 <th class="text-right">TotalTax</th>
								  <th class="text-right">TotalAmount</th>
								   <th class="text-right">Type</th>
								    <th class="text-right">Status</th>
									<?php
                                            $invCount= 0;
                                            $igstTotal= 0;
                                            $cgstTotal= 0;
                                            $sgstTotal= 0;
                                            $cessTotal= 0;
                                            $invTotal=0;
                                            $flag=0;
                                            if($type=='invoice' || $type=='all')
                                            {
                                                $b2bItemquery = "select  i.is_gstr1_uploaded,i.invoice_date,i.purchase_invoice_id,i.reference_number,i.invoice_total_value,i.supplier_billing_gstin_number,i.supplier_billing_name,sum(it.cgst_amount) as cgst_amount,sum(it.sgst_amount) as sgst_amount,sum(it.igst_amount) as igst_amount,sum(it.cess_amount) as cess_amount from " . $obj_client->getTableName('client_purchase_invoice') . " i inner join " . $obj_client->getTableName("client_purchase_invoice_item") . " it on i.purchase_invoice_id=it.purchase_invoice_id  where i.invoice_nature='purchaseinvoice' and i.added_by='" . $_SESSION['user_detail']['user_id'] . "' and i.status='1' and i.is_canceled='0' and i.is_deleted='0' and i.invoice_date like '%" . $returnmonth . "%' group by i.purchase_invoice_id  order by i.invoice_date desc";
                                                $b2bItemData = $obj_client->get_results($b2bItemquery);
                                                
                                                
                                                if(!empty($b2bItemData))
                                                {
                                                    $flag=1;
                                                    
                                                    foreach($b2bItemData as $b2bItem)
                                                    {
                                                        $totaltax = (isset($b2bItem->igst_amount)) ? $b2bItem->igst_amount : '0' + (isset($b2bItem->cgst_amount)) ? $b2bItem->cgst_amount : '0' + (isset($b2bItem->sgst_amount)) ? $b2bItem->sgst_amount : '0' + (isset($b2bItem->cess_amount)) ? $b2bItem->cess_amount : '0';
                                                        $type ='';
                                                        if($b2bItem->supplier_billing_gstin_number=='' && $b2bItem->invoice_total_value > 25000)
                                                        {
                                                            $type = 'B2CL';
                                                        }
                                                        else if($b2bItem->supplier_billing_gstin_number=='' && $b2bItem->invoice_total_value <= 25000)
                                                        {
                                                            $type = 'B2CS';
                                                        }
                                                        else if($b2bItem->supplier_billing_gstin_number!='')
                                                        {
                                                            $type = 'B2B';
                                                        }
                                                        $type = ($b2bItem->supplier_billing_gstin_number=='') ? 'B2C' : 'B2B';
                                                        ?>
                                <tr>
                                                            <td align='left'><?php echo $b2bItem->invoice_date;?></td>
                                                            <td align='left'><?php echo $b2bItem->reference_number;?></td>
                                                            <td align='left'><?php echo $b2bItem->supplier_billing_name;?></td>
                                                            <td align='left'><?php echo $b2bItem->supplier_billing_gstin_number;?></td>
                                                            <td style='text-align:right'><?php echo $b2bItem->invoice_total_value;?></td>
                                                            <td style='text-align:right'><?php echo $totaltax?></td>
                                                            <td style='text-align:right'><?php echo $b2bItem->invoice_total_value;?></td>
                                                            <td align='center'><?php echo $type; ?></td>
                                                            <td align='center'><?php echo (isset($b2bItem->is_gstr1_uploaded) && $b2bItem->is_gstr1_uploaded=='0') ? 'Pending':'Uploaded';?></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                            }
                                            if($type=='cdn' || $type=='all')
                                            {
                                                $b2bItemquery = "select sum(it.cgst_amount) as cgst_amount,sum(it.sgst_amount) as sgst_amount,sum(it.igst_amount) as igst_amount,sum(it.cess_amount) as cess_amount from " . $obj_client->getTableName('client_rt_invoice') . " i inner join " . $obj_client->getTableName("client_rt_invoice_item") . " it on i.invoice_id=it.invoice_id  where (i.invoice_document_nature='creditnote' or i.invoice_document_nature='debitnote') and  i.added_by='" . $_SESSION['user_detail']['user_id'] . "' and i.status='1' and i.is_canceled='0' and i.is_deleted='0'  and i.invoice_date like '%" . $returnmonth . "%'";
                                                $b2bItemData = $obj_client->get_results($b2bItemquery);
                                                $igstTotal += (isset($b2bItemData[0]->igst_amount)) ? $b2bItemData[0]->igst_amount : '0';
                                                $cgstTotal += (isset($b2bItemData[0]->cgst_amount)) ? $b2bItemData[0]->cgst_amount : '0';
                                                $sgstTotal += (isset($b2bItemData[0]->sgst_amount)) ? $b2bItemData[0]->sgst_amount : '0';
                                                $cessTotal += (isset($b2bItemData[0]->cess_amount)) ? $b2bItemData[0]->cess_amount : '0';
                                            }
                                            if($type=='advance' || $type=='all')
                                            {
                                                $b2bItemquery = "select sum(it.cgst_amount) as cgst_amount,sum(it.sgst_amount) as sgst_amount,sum(it.igst_amount) as igst_amount,sum(it.cess_amount) as cess_amount from " . $obj_client->getTableName('client_rv_invoice') . " i inner join " . $obj_client->getTableName("client_rv_invoice_item") . " it on i.invoice_id=it.invoice_id  where  i.added_by='" . $_SESSION['user_detail']['user_id'] . "' and i.status='1' and i.is_canceled='0' and i.is_deleted='0'  and i.invoice_date like '%" . $returnmonth . "%'";
                                                $b2bItemData = $obj_client->get_results($b2bItemquery);
                                                $igstTotal += (isset($b2bItemData[0]->igst_amount)) ? $b2bItemData[0]->igst_amount : '0';
                                                $cgstTotal += (isset($b2bItemData[0]->cgst_amount)) ? $b2bItemData[0]->cgst_amount : '0';
                                                $sgstTotal += (isset($b2bItemData[0]->sgst_amount)) ? $b2bItemData[0]->sgst_amount : '0';
                                                $cessTotal += (isset($b2bItemData[0]->cess_amount)) ? $b2bItemData[0]->cess_amount : '0';
                                            }
                                            ?>
                                            
                                </thead>
                                <tbody>
                              
                                </tbody>
                            </table>
                        </div>
                        </div> 
                    
       	  </div>
 		 <div class="clear height40"></div>      
    </div>
  <div class="clear"></div></form>
  <script type="text/javascript">
    $(document).ready(function () {
        $('#multiple-checkboxes').multiselect();
    });
</script>

<script>
    $(document).ready(function () {
        $('#returnmonth,.type').on('change', function () {
            document.form2.action = '<?php echo PROJECT_URL; ?>/?page=return_purchase_all&returnmonth=<?php echo $returnmonth; ?>';
            document.form2.submit();
        });
    });
</script>