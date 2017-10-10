<?php
$obj_client = new client();
if(!$obj_client->can_read('returnfile_list'))
{
    $obj_client->setError($obj_client->getValMsg('can_read'));
    $obj_client->redirect(PROJECT_URL."/?page=dashboard");
    exit();
}
if (!isset($_REQUEST['returnmonth']) || $_REQUEST['returnmonth'] == '') {
    $obj_client->redirect(PROJECT_URL . "/?page=return_client");
    exit();
}
if (isset($_POST['returnmonth'])) {
    $returnmonth = $_POST['returnmonth'];
    $obj_client->redirect(PROJECT_URL . "/?page=return_gstr1a_download&returnmonth=" . $returnmonth);
    exit();
}
if(isset($_GET["type"]))
{
	$type = $_GET["type"];
}
//$type = "b2b";
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
                    <div class="col-md-6 col-sm-12 col-xs-12"><h3>GSTR-1A Invoice Details</h3></div>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="whitebg formboxcontainer">
                           
                            <?php $obj_client->showErrorMessage(); ?>
                            <?php $obj_client->showSuccessMessge(); ?>
                            <?php $obj_client->unsetMessage(); ?>
                         
							  
                            <div class="adminformbx" style="overflow-x:auto;max-width:1000px;">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="invoice-itemtable" id="mainTable1">
                                    <thead>
                                        <tr>
                                          
                                            <th style="text-align:left">CTIN</th>
                                            <th style="text-align:left">InvoiceNumber</th>
											<th style="text-align:left">InvoiceDate</th>
											<th style="text-align:left">InvoiceValue</th>
											
												 <th style="text-align:left">No.ofItem</th>
												
												  <th style="text-align:left">TaxValue</th>
												   <th style="text-align:left">IGST</th>
												   <th style="text-align:left">CGST</th>
												   <th style="text-align:left">SGST</th>
												   <th style="text-align:left">CESS</th>
												  </tr> 
												   
												   
										<?php
										$b2bItemquery="";
										$b2bItemData="";
										$igstTotal=0;
										$cgstTotal=0;$sgstTotal=0;$cessTotal=0;
                                         if($type=='cdn')
                                            {
                                        $b2bItemquery = "select count(it.invoice_id) as totalinvoice,  i.serial_number, i.invoice_id, i.invoice_date,i.invoice_id,i.reference_number,i.invoice_total_value,i.billing_gstin_number,i.billing_name,sum(it.cgst_amount) as cgst_amount,sum(it.sgst_amount) as sgst_amount,sum(it.igst_amount) as igst_amount,sum(it.cess_amount) as cess_amount from " . $obj_client->getTableName('client_rt_invoice') . " i inner join " . $obj_client->getTableName("client_rt_invoice_item") . " it on i.invoice_id=it.invoice_id  where (i.invoice_document_nature='creditnote' or i.invoice_document_nature='debitnote') and i.billing_gstin_number!='' and  i.added_by='" . $_SESSION['user_detail']['user_id'] . "' and i.status='1' and i.is_canceled='0' and i.is_deleted='0'  and i.invoice_date like '%" . $returnmonth . "%' group by i.invoice_id  order by i.invoice_date desc";
                                          $b2bItemData = $obj_client->get_results($b2bItemquery);
										  
                                            }
											else if($type=='cdnu')
                                            {
                                       $b2bItemquery = "select count(it.invoice_id) as totalinvoice,  i.serial_number, i.invoice_id, i.invoice_date,i.invoice_id,i.reference_number,i.invoice_total_value,i.billing_gstin_number,i.billing_name,sum(it.cgst_amount) as cgst_amount,sum(it.sgst_amount) as sgst_amount,sum(it.igst_amount) as igst_amount,sum(it.cess_amount) as cess_amount from " . $obj_client->getTableName('client_rt_invoice') . " i inner join " . $obj_client->getTableName("client_rt_invoice_item") . " it on i.invoice_id=it.invoice_id where (i.invoice_document_nature='creditnote' or i.invoice_document_nature='debitnote') and i.billing_gstin_number='' and  i.added_by='" . $_SESSION['user_detail']['user_id'] . "' and i.status='1' and i.is_canceled='0' and i.is_deleted='0'  and i.invoice_date like '%" . $returnmonth . "%' group by i.invoice_id  order by i.invoice_date desc";
                                          $b2bItemData = $obj_client->get_results($b2bItemquery);
										
                                            }
                                         else if($type=='b2b')	
										  {											  
									      $b2bItemquery = "select count(it.invoice_id) as totalinvoice, i.supply_type, i.serial_number, i.invoice_id,  i.is_gstr1_uploaded,i.invoice_date,i.invoice_id,i.reference_number,i.invoice_total_value,i.billing_gstin_number,i.billing_name,sum(it.cgst_amount) as cgst_amount,sum(it.sgst_amount) as sgst_amount,sum(it.igst_amount) as igst_amount,sum(it.cess_amount) as cess_amount from " . $obj_client->getTableName('client_invoice') . " i inner join " . $obj_client->getTableName("client_invoice_item") . " it on i.invoice_id=it.invoice_id  where i.invoice_nature='salesinvoice' and i.added_by='" . $_SESSION['user_detail']['user_id'] . "' and i.status='1' and i.is_canceled='0' and i.is_deleted='0' and i.invoice_date like '%" . $returnmonth . "%' group by i.invoice_id  order by i.invoice_date desc";
                                          $b2bItemData = $obj_client->get_results($b2bItemquery);
										    //var_dump($b2bItemData);
                                          } 
										  else
										  {
											  $b2bItemquery = "select count(it.invoice_id) as totalinvoice, i.supply_type, i.serial_number, i.invoice_id,  i.is_gstr1_uploaded,i.invoice_date,i.invoice_id,i.reference_number,i.invoice_total_value,i.billing_gstin_number,i.billing_name,sum(it.cgst_amount) as cgst_amount,sum(it.sgst_amount) as sgst_amount,sum(it.igst_amount) as igst_amount,sum(it.cess_amount) as cess_amount from " . $obj_client->getTableName('client_invoice') . " i inner join " . $obj_client->getTableName("client_invoice_item") . " it on i.invoice_id=it.invoice_id  where i.invoice_nature='salesinvoice' and i.added_by='" . $_SESSION['user_detail']['user_id'] . "' and i.status='1' and i.is_canceled='0' and i.is_deleted='0' and i.invoice_date like '%" . $returnmonth . "%' group by i.invoice_id  order by i.invoice_date desc";
                                          $b2bItemData = $obj_client->get_results($b2bItemquery);
										  }
                                                
                                                if(!empty($b2bItemData))
                                                {
                                                    $flag=1;
                                                    
                                                    foreach($b2bItemData as $b2bItem)
                                                    {
                                                        $totaltax = (isset($b2bItem->igst_amount)) ? $b2bItem->igst_amount : '0' + (isset($b2bItem->cgst_amount)) ? $b2bItem->cgst_amount : '0' + (isset($b2bItem->sgst_amount)) ? $b2bItem->sgst_amount : '0' + (isset($b2bItem->cess_amount)) ? $b2bItem->cess_amount : '0';
                                                        $type ='';
														if($type="all")
														{
															
														}
														else
														{
                                                        if($b2bItem->billing_gstin_number=='' && $b2bItem->invoice_total_value > 25000)
                                                        {
                                                            $type = 'B2CL';
                                                        }
                                                        else if($b2bItem->billing_gstin_number=='' && $b2bItem->invoice_total_value <= 25000)
                                                        {
                                                            $type = 'B2CS';
                                                        }
                                                        else if($b2bItem->billing_gstin_number!='')
                                                        {
                                                            $type = 'B2B';
                                                        }
														 $type = ($b2bItem->billing_gstin_number=='') ? 'B2C' : 'B2B';
														}
                                                       
                                                        ?>
                                                        <tr>
														
                                                            <td align='left'>01AABCE2207R1Z5</td>
															  <td align='left'><?php echo $b2bItem->serial_number;?></td>
                                                            <td align='left'><?php echo $b2bItem->invoice_date;?></td>
                                                            <td align='left'><?php echo $b2bItem->invoice_total_value;?></td>
                                                           
                                                            <td style='text-align:right'><?php echo $b2bItem->totalinvoice;?></td>
                                                            <td style='text-align:right'><?php echo $totaltax?></td>
                                                            <td style='text-align:right'><?php echo $b2bItem->igst_amount;?></td>
															    <td style='text-align:right'><?php echo $b2bItem->cgst_amount;?></td>
																 <td style='text-align:right'><?php echo $b2bItem->sgst_amount;?></td>
																  <td style='text-align:right'><?php echo $b2bItem->cess_amount;?></td>
                                                         
                                                        </tr>
                                                        <?php
                                                    }
                                                
                                            }	   
                                             ?>
                                       
<!--                                    
										
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