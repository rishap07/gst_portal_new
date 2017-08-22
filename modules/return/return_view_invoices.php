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

if($type=="invoice")
{
	if (isset($_POST['returnmonth'])) 
{
	
    $returnmonth = $_POST['returnmonth'];
	
   $obj_gstr1->redirect(PROJECT_URL . "/?page=return_view_invoices&returnmonth=" . $returnmonth);
   exit();
    }
}



if(isset($_POST['submit']) && $_POST['submit']=='Upload TO GSTN')
{
	
    if ($obj_gstr1->selectgstr1Upload()) 
        {
        }


}

?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
 						
   <form method='post' name='form2'>
 <div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr">

    <div class="col-md-11 col-sm-12 col-xs-12 mobpadlr">
        <div class="col-md-12 col-sm-12 col-xs-12 heading">
            <div class="tab col-md-12 col-sm-12 col-xs-12">
                <a href="<?php echo PROJECT_URL . '/?page=return_get_summary&returnmonth=' . $returnmonth ?>">
                    GSTR1 SUMMARY
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
                
                <a href="<?php echo PROJECT_URL . '/?page=return_summary&returnmonth=' . $returnmonth ?>" >
                    View GSTR1 Summary
                </a>
            </div>
            <div id="view_invoice" class="tabcontent">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="col-md-6 col-sm-12 col-xs-12"><h3>My Invoices</h3></div>
                   
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
                                
                           </form>  </div> 
							  

								<div class="clearfix"></div>
								  <div class="col-md-6 col-sm-6 col-xs-12">
                        <a class='btn btn-default btn-success btnwidth' style="margin-top:-71px;"  href='<?php echo PROJECT_URL; ?>/?page=client_create_invoice'>Add New Invoice</a>
                    </div>  <div class="clearfix"></div>
					   <?php $obj_client->showErrorMessage(); ?>
                                <?php $obj_client->showSuccessMessge(); ?>
                                <?php $obj_client->unsetMessage(); ?>
								<?php
								$flag=0;
                            $dataReturns = $obj_gstr1->get_results("select * from ".TAB_PREFIX."return where return_month='".$returnmonth."' and client_id='".$_SESSION['user_detail']['user_id']."' and status='3' and type='gstr1'");
                            if(!empty($dataReturns))
                            {
								$flag=0;
                            ?>
                            <div id="sucmsg" style="background-color:#DBEDDF;border-radius:4px;padding:8px 35px 8px 14px;text-shadow:0 1px 0 rgba(255, 255, 255, 0.5);margin-bottom:18px;border-color:#D1E8DA;color:#39A25F;"><i class="fa fa-check"></i> <b>Success:</b> GSTR1 is Already Filed</div>
                            <?php
                            }
                            else
                            {
								$flag=1;
                            ?> 
                            
                      
                            <?php
                            }
                            ?>
                                <div class="invoice-types"><div class="invoice-types__heading">Types</div>
							
                                    <div class="invoice-types__content">
									
                                        <label for="invoice-types__invoice"><input type="radio" id="invoice-types__invoice" name="invoice_type" value="invoice" class="type" <?php if(isset($_POST['invoice_type']) && $_POST['invoice_type']=='invoice'){ echo 'checked=""';}else{echo 'checked=""';}?>>Invoice</label>
                                        <label for="invoice-types__cdn"><input type="radio" id="invoice-types__cdn" name="invoice_type" value="cdn" class="type" <?php if(isset($_POST['invoice_type']) && $_POST['invoice_type']=='cdn') echo 'checked=""';?>>Credit/Debit Note</label>
                                        <label for="invoice-types__advance_received"><input type="radio" id="invoice-types__advance_received" name="invoice_type" value="advance" class="type" <?php if(isset($_POST['invoice_type']) && $_POST['invoice_type']=='advance') echo 'checked=""';?>>Advance Receipt</label>
<!--                                        <label for="invoice-types__aggregate"><input type="radio" id="invoice-types__aggregate" name="invoice_type" value="nill" class="type" <?php if(isset($_POST['invoice_type']) && $_POST['invoice_type']=='nill') echo 'checked=""';?>>Agg. Nil/Exempt/Non GST</label>-->
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
										<form method="post">
										<?php
										if($flag==1)
										{
											?>
										<div class="col-md-6 col-sm-12 col-xs-12">
                            
                                    <input type="submit" name="submit" value="Upload TO GSTN" class="btn btn-default btn-success btnwidth addnew">
                              </div><div class="clear"></div><br>
										<?php } ?>
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
                                           $b2bItemquery = "select sum(it.cgst_amount) as cgst_amount,sum(it.sgst_amount) as sgst_amount,sum(it.igst_amount) as igst_amount,sum(it.cess_amount) as cess_amount from " . $obj_client->getTableName('client_invoice') . " i inner join " . $obj_client->getTableName("client_invoice_item") . " it on i.invoice_id=it.invoice_id  where i.invoice_nature='salesinvoice' and i.added_by='" . $_SESSION['user_detail']['user_id'] . "' and i.status='1' and i.is_canceled='0' and i.is_deleted='0' and i.billing_gstin_number!='' and i.invoice_date like '%" . $returnmonth . "%' ";
                                            $b2bItemData = $obj_client->get_results($b2bItemquery);
                                            $igstTotal += (isset($b2bItemData[0]->igst_amount)) ? $b2bItemData[0]->igst_amount : '0';
                                            $cgstTotal += (isset($b2bItemData[0]->cgst_amount)) ? $b2bItemData[0]->cgst_amount : '0';
                                            $sgstTotal += (isset($b2bItemData[0]->sgst_amount)) ? $b2bItemData[0]->sgst_amount : '0';
                                            $cessTotal += (isset($b2bItemData[0]->cess_amount)) ? $b2bItemData[0]->cess_amount : '0';
										
                                           $b2bquery = "select * from " . $obj_client->getTableName('client_invoice') . " where invoice_nature='salesinvoice' and added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0' and billing_gstin_number!='' and invoice_date like '%" . $returnmonth . "%'";
                                            $invCount += count($obj_client->get_results($b2bquery));
											
                                            $b2bTotquery = "select sum(invoice_total_value) as invoice_total_value from " . $obj_client->getTableName('client_invoice') . " where invoice_nature='salesinvoice' and added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0' and billing_gstin_number!='' and invoice_date like '%" . $returnmonth . "%'";
                                            $b2bTotData = $obj_client->get_results($b2bTotquery);
                                            $invTotal += (isset($b2bTotData[0]->invoice_total_value)) ? $b2bTotData[0]->invoice_total_value : '0';
                                            $total = 0;
                                            if (!empty($b2bItemData)) {
                                                $total = $b2bItemData[0]->cgst_amount + $b2bItemData[0]->sgst_amount + $b2bItemData[0]->igst_amount + $b2bItemData[0]->cess_amount;
                                            }
                                        }
                                        if($type=='cdn' || $type=='all')
                                        {
                                           $b2bItemquery = "select sum(it.cgst_amount) as cgst_amount,sum(it.sgst_amount) as sgst_amount,sum(it.igst_amount) as igst_amount,sum(it.cess_amount) as cess_amount from " . $obj_client->getTableName('client_invoice') . " i inner join " . $obj_client->getTableName("client_invoice_item") . " it on i.invoice_id=it.invoice_id  where (i.invoice_nature='creditnote' or i.invoice_nature='debitnote') and  i.added_by='" . $_SESSION['user_detail']['user_id'] . "' and i.status='1' and i.is_canceled='0' and i.is_deleted='0'  and i.invoice_date like '%" . $returnmonth . "%'";
                                            $b2bItemData = $obj_client->get_results($b2bItemquery);
                                            $igstTotal += (isset($b2bItemData[0]->igst_amount)) ? $b2bItemData[0]->igst_amount : '0';
                                            $cgstTotal += (isset($b2bItemData[0]->cgst_amount)) ? $b2bItemData[0]->cgst_amount : '0';
                                            $sgstTotal += (isset($b2bItemData[0]->sgst_amount)) ? $b2bItemData[0]->sgst_amount : '0';
                                            $cessTotal += (isset($b2bItemData[0]->cess_amount)) ? $b2bItemData[0]->cess_amount : '0';
                                          $b2bquery = "select * from " . $obj_client->getTableName('client_invoice') . " where (invoice_nature='creditnote' or invoice_nature='debitnote') and  added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0'  and invoice_date like '%" . $returnmonth . "%' ";
                                            $invCount += count($obj_client->get_results($b2bquery));
                                          $b2bTotquery = "select sum(invoice_total_value) as invoice_total_value from " . $obj_client->getTableName('client_invoice') . " where (invoice_nature='creditnote' or invoice_nature='debitnote') and  added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0'  and invoice_date like '%" . $returnmonth . "%'";
                                            $b2bTotData = $obj_client->get_results($b2bTotquery);
                                            $invTotal += (isset($b2bTotData[0]->invoice_total_value)) ? $b2bTotData[0]->invoice_total_value : '0';
                                            $total = 0;
                                            if (!empty($b2bItemData)) {
                                                $total = $b2bItemData[0]->cgst_amount + $b2bItemData[0]->sgst_amount + $b2bItemData[0]->igst_amount + $b2bItemData[0]->cess_amount;
                                            }
                                        }
                                        if($type=='advance' || $type=='all')
                                        {
                                            $b2bItemquery = "select sum(it.cgst_amount) as cgst_amount,sum(it.sgst_amount) as sgst_amount,sum(it.igst_amount) as igst_amount,sum(it.cess_amount) as cess_amount from " . $obj_client->getTableName('client_invoice') . " i inner join " . $obj_client->getTableName("client_invoice_item") . " it on i.invoice_id=it.invoice_id  where  i.added_by='" . $_SESSION['user_detail']['user_id'] . "' and i.status='1' and i.is_canceled='0' and i.is_deleted='0'  and i.invoice_type='receiptvoucherinvoice' and i.invoice_nature='salesinvoice' and  i.invoice_date like '%" . $returnmonth . "%'";
                                            $b2bItemData = $obj_client->get_results($b2bItemquery);
                                            $igstTotal += (isset($b2bItemData[0]->igst_amount)) ? $b2bItemData[0]->igst_amount : '0';
                                            $cgstTotal += (isset($b2bItemData[0]->cgst_amount)) ? $b2bItemData[0]->cgst_amount : '0';
                                            $sgstTotal += (isset($b2bItemData[0]->sgst_amount)) ? $b2bItemData[0]->sgst_amount : '0';
                                            $cessTotal += (isset($b2bItemData[0]->cess_amount)) ? $b2bItemData[0]->cess_amount : '0';
                                          $b2bquery = "select * from " . $obj_client->getTableName('client_invoice') . " where added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0'  and invoice_date like '%" . $returnmonth . "%' and invoice_type='receiptvoucherinvoice' and invoice_nature='salesinvoice' ";
                                            $invCount += count($obj_client->get_results($b2bquery));
                                           $b2bTotquery = "select sum(invoice_total_value) as invoice_total_value from " . $obj_client->getTableName('client_rv_invoice') . " where added_by='" . $_SESSION['user_detail']['user_id'] . "' and status='1' and is_canceled='0' and is_deleted='0'  and invoice_date like '%" . $returnmonth . "%' and invoice_type='receiptvoucherinvoice' and invoice_nature='salesinvoice'";
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
                                </div>
                                <br/>
						
                                <div class="adminformbx">
							
									
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="invoice-itemtable" id="mainTable1">
                                        <thead>
                                            <tr>
											 <th align='left'><input name="select_all" value="1" id="example-select-all" type="checkbox" />
                        </th>
                                                <th align='left'>Date</th>
                                                <th align='left'>Invoice Number</th>
                                                <th align='left'>Customer</th>
                                                <th align='left'>GSTIN</th>
                                                <th style='text-align:right'>Taxable AMT</th>
                                                <th style='text-align:right'>Total Tax</th>
                                                <th style='text-align:right'>Total Amt</th>
                                                <th align='center'>Type</th>
                                                <th align='center'>Status</th>
                                            </tr>
                                            <?php
                                            $invCount= 0;
                                            $igstTotal= 0;
                                            $cgstTotal= 0;
                                            $sgstTotal= 0;
                                            $cessTotal= 0;
                                            $invTotal=0;
                                            $flag=0;
											 if($type=='cdn' || $type=='all')
                                            {
                                            $b2bItemquery = "select  i.invoice_id,  i.invoice_date,i.invoice_id,i.reference_number,i.invoice_total_value,i.billing_gstin_number,i.billing_name,sum(it.cgst_amount) as cgst_amount,sum(it.sgst_amount) as sgst_amount,sum(it.igst_amount) as igst_amount,sum(it.cess_amount) as cess_amount from " . $obj_client->getTableName('client_rt_invoice') . " i inner join " . $obj_client->getTableName("client_rt_invoice_item") . " it on i.invoice_id=it.invoice_id  where (i.invoice_document_nature='creditnote' or i.invoice_document_nature='debitnote') and  i.added_by='" . $_SESSION['user_detail']['user_id'] . "' and i.status='1' and i.is_canceled='0' and i.is_deleted='0'  and i.invoice_date like '%" . $returnmonth . "%' group by i.invoice_id  order by i.invoice_date desc";
                                                $b2bItemData = $obj_client->get_results($b2bItemquery);
                                                $igstTotal += (isset($b2bItemData[0]->igst_amount)) ? $b2bItemData[0]->igst_amount : '0';
                                                $cgstTotal += (isset($b2bItemData[0]->cgst_amount)) ? $b2bItemData[0]->cgst_amount : '0';
                                                $sgstTotal += (isset($b2bItemData[0]->sgst_amount)) ? $b2bItemData[0]->sgst_amount : '0';
                                                $cessTotal += (isset($b2bItemData[0]->cess_amount)) ? $b2bItemData[0]->cess_amount : '0';
												
												 if(!empty($b2bItemData))
                                                {
                                                    $flag=1;
                                                    
                                                    foreach($b2bItemData as $b2bItem)
                                                    {
                                                        $totaltax = (isset($b2bItem->igst_amount)) ? $b2bItem->igst_amount : '0' + (isset($b2bItem->cgst_amount)) ? $b2bItem->cgst_amount : '0' + (isset($b2bItem->sgst_amount)) ? $b2bItem->sgst_amount : '0' + (isset($b2bItem->cess_amount)) ? $b2bItem->cess_amount : '0';
                                                        
                                                        ?>
                                                        <tr>
														<td align="center" bgcolor="#FFFFFF"><input type="checkbox" class="name" name="name[]" value="<?php echo $b2bItem->invoice_id;?>"/></td>
                                                            <td align='left'><?php echo $b2bItem->invoice_date;?></td>
                                                            <td align='left'><?php echo $b2bItem->reference_number;?></td>
                                                            <td align='left'><?php echo $b2bItem->billing_name;?></td>
                                                            <td align='left'><?php echo $b2bItem->billing_gstin_number;?></td>
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
                                            if($type=='advance')
                                            {
                                                $b2bItemquery = "select i.invoice_id,  i.invoice_date,i.invoice_id,i.reference_number,i.invoice_total_value,i.billing_gstin_number,i.billing_name,sum(it.cgst_amount) as cgst_amount,sum(it.sgst_amount) as sgst_amount,sum(it.igst_amount) as igst_amount,sum(it.cess_amount) as cess_amount from " . $obj_client->getTableName('client_rv_invoice') . " i inner join " . $obj_client->getTableName("client_rv_invoice_item") . " it on i.invoice_id=it.invoice_id  where  i.added_by='" . $_SESSION['user_detail']['user_id'] . "' and i.status='1' and i.is_canceled='0' and i.is_deleted='0'  and i.invoice_date like '%" . $returnmonth . "%' group by i.invoice_id  order by i.invoice_date desc";
                                                $b2bItemData = $obj_client->get_results($b2bItemquery);
                                                $igstTotal += (isset($b2bItemData[0]->igst_amount)) ? $b2bItemData[0]->igst_amount : '0';
                                                $cgstTotal += (isset($b2bItemData[0]->cgst_amount)) ? $b2bItemData[0]->cgst_amount : '0';
                                                $sgstTotal += (isset($b2bItemData[0]->sgst_amount)) ? $b2bItemData[0]->sgst_amount : '0';
                                                $cessTotal += (isset($b2bItemData[0]->cess_amount)) ? $b2bItemData[0]->cess_amount : '0';
												if(!empty($b2bItemData))
                                                {
                                                    $flag=1;
                                                    
                                                    foreach($b2bItemData as $b2bItem)
                                                    {
                                                        $totaltax = (isset($b2bItem->igst_amount)) ? $b2bItem->igst_amount : '0' + (isset($b2bItem->cgst_amount)) ? $b2bItem->cgst_amount : '0' + (isset($b2bItem->sgst_amount)) ? $b2bItem->sgst_amount : '0' + (isset($b2bItem->cess_amount)) ? $b2bItem->cess_amount : '0';
                                                        
                                                        ?>
                                                        <tr>
														<td align="center" bgcolor="#FFFFFF"><input type="checkbox" class="name" name="name[]" value="<?php echo $b2bItem->invoice_id;?>"/></td>
                                                            <td align='left'><?php echo $b2bItem->invoice_date;?></td>
                                                            <td align='left'><?php echo $b2bItem->reference_number;?></td>
                                                            <td align='left'><?php echo $b2bItem->billing_name;?></td>
                                                            <td align='left'><?php echo $b2bItem->billing_gstin_number;?></td>
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
                                            if($type=='invoice' || $type=='all')
                                            {
                                               $b2bItemquery = "select i.invoice_id,  i.is_gstr1_uploaded,i.invoice_date,i.invoice_id,i.reference_number,i.invoice_total_value,i.billing_gstin_number,i.billing_name,sum(it.cgst_amount) as cgst_amount,sum(it.sgst_amount) as sgst_amount,sum(it.igst_amount) as igst_amount,sum(it.cess_amount) as cess_amount from " . $obj_client->getTableName('client_invoice') . " i inner join " . $obj_client->getTableName("client_invoice_item") . " it on i.invoice_id=it.invoice_id  where i.invoice_nature='salesinvoice' and i.added_by='" . $_SESSION['user_detail']['user_id'] . "' and i.status='1' and i.is_canceled='0' and i.is_deleted='0' and i.invoice_date like '%" . $returnmonth . "%' group by i.invoice_id  order by i.invoice_date desc";
                                                $b2bItemData = $obj_client->get_results($b2bItemquery);
                                                
                                                
                                                if(!empty($b2bItemData))
                                                {
                                                    $flag=1;
                                                    
                                                    foreach($b2bItemData as $b2bItem)
                                                    {
                                                        $totaltax = (isset($b2bItem->igst_amount)) ? $b2bItem->igst_amount : '0' + (isset($b2bItem->cgst_amount)) ? $b2bItem->cgst_amount : '0' + (isset($b2bItem->sgst_amount)) ? $b2bItem->sgst_amount : '0' + (isset($b2bItem->cess_amount)) ? $b2bItem->cess_amount : '0';
                                                        $type ='';
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
                                                        ?>
                                                        <tr>
														<td align="center" bgcolor="#FFFFFF"><input type="checkbox" class="name" name="name[]" value="<?php echo $b2bItem->invoice_id;?>"/></td>
                                                            <td align='left'><?php echo $b2bItem->invoice_date;?></td>
                                                            <td align='left'><?php echo $b2bItem->reference_number;?></td>
                                                            <td align='left'><?php echo $b2bItem->billing_name;?></td>
                                                            <td align='left'><?php echo $b2bItem->billing_gstin_number;?></td>
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
                                           
                                            ?>
                                            
                                        </thead>
                                    </table> </form>
                                </div>  
								
                            </div>
                        </div>
                    </div>
               
            </div>
        </div>
    </div>
</div></form>
<script type="text/javascript">
    $(document).ready(function () {
        $('#multiple-checkboxes').multiselect();
    });
</script>
<script>
    $(document).ready(function () {
        $('#returnmonth,.type').on('change', function () {
			//alert(document.forms["form2"]["returnmonth"].value);
			//alert(document.forms["form2"]["invoice_type"].value);
            document.form2.action = '<?php echo PROJECT_URL; ?>/?page=return_view_invoices&returnmonth=<?php echo $returnmonth; ?>';
            document.form2.submit();
        });
    });
</script>
<script>
    
		   // Handle click on "Select all" control
    $('#example-select-all').on('change', function(){

        $('input[type="checkbox"]').prop('checked', this.checked);
                if ($('#example-select-all').is(':checked')) {
           
           // $('#uploadchecked').removeAttr('disabled');
        } else {
            // $('#uploadchecked').attr('disabled', 'disabled');
        }
    });

 
</script>

<script language="javascript">
    $(function () {
        // add multiple select / deselect functionality
        $("#selectall").click(function () {
            $('.name').attr('checked', this.checked);
        });
 
        // if all checkbox are selected, then check the select all checkbox
        // and viceversa
        $(".name").click(function () {
 
            if ($(".name").length == $(".name:checked").length) {
                $("#selectall").attr("checked", "checked");
            } else {
                $("#selectall").removeAttr("checked");
            }
 
        });
    });
</script>
