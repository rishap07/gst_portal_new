<?php
$obj_client = new client();
$returnmonth = date('Y-m');
if(!$obj_client->can_read('returnfile_list'))
{
    $obj_client->setError($obj_client->getValMsg('can_read'));
    $obj_client->redirect(PROJECT_URL."/?page=dashboard");
    exit();
}
if(isset($_POST['returnmonth']))
{
    $returnmonth = $_POST['returnmonth'];
	$obj_client->redirect(PROJECT_URL."/?page=return_client&returnmonth=".$returnmonth);
	exit();
}
$returnmonth= date('Y-m');
if(isset($_REQUEST['returnmonth']) && $_REQUEST['returnmonth'] != '')
{
    $returnmonth= $_REQUEST['returnmonth'];
}
$time = strtotime($returnmonth."-01");
$month = date("M", strtotime("+1 month", $time));
?>
      
          
       <div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
       <div class="col-md-12 col-sm-12 col-xs-12">
               
                	<div class="col-md-6 col-sm-6 col-xs-12 heading"><h1>GSTR-3B Filing</h1></div>
                    <div class="col-md-6 col-sm-6 col-xs-12 text-right breadcrumb-nav"><a href="#">Home</a>  <i class="fa fa-angle-right" aria-hidden="true"></i>  <a href="#">File Return</a> <i class="fa fa-angle-right" aria-hidden="true"></i> <span class="active">GSTR-2 Filing</span> </div>
                     <div class="whitebg formboxcontainer">
					 
					 <?php $obj_client->showErrorMessage(); ?>
                                <?php $obj_client->showSuccessMessge(); ?>
                                <?php $obj_client->unsetMessage(); ?>
							<div class="pull-right rgtdatetxt">
								<form method='post' name='form4' id="form4">
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
										<?php } ?>
									</form>
                                </div>       
								
                       
                    	<div class="col-md-12 col-sm-12 col-xs-12 tablistnav padleft0">
							<ul>
								<li><a href="<?php echo PROJECT_URL . '/?page=return_gstr3b_file_save&returnmonth=' . $returnmonth ?>" class="active" >Save GSTR-3B</a></li>
								<li><a href="<?php echo PROJECT_URL . '/?page=return_gstr3b_file_view&returnmonth=' . $returnmonth ?>" > View GSTR-3B</a></li>
								
								
							</ul>
						</div>
							 
							 <div class="invoice-types"><div class="invoice-types__heading"><h4>GSTR-2 Filing Summary</h4></div>
                                   
							 <div class="clear"></div>
							<h4>3.1 Details of Outward Supplies and inward supplies liable to reverse charge</h4>
                           <div class="tableresponsive">
						    
                            <table  class="table  tablecontent tablecontent2">
                                <thead>
								
                                <tr>
                                <th>SupplyType</th>
                            
                                <th>TAXABLE AMT (₹)	</th>
                                <th class="text-right">IAMT (₹)</th>
								<th class="text-right">CAMT (₹)</th>
								<th class="text-right">SAMT (₹)</th>
								<th class="text-right">CSAMT (₹)</th>
                             
                                <th class=""></th></tr>
								<tr>
								<td>Outward supply(nil rated,exempted)</td>
								<td >250</td>
								<td>100</td>
								<td>50</td>
								<td>100</td>
								<td>150</td>
								
								</tr>
								<tr>
								<td>Outward supply(Zero rated)</td>
								<td >250</td>
								<td>100</td>
								<td>50</td>
								<td></td>
								<td></td>
								
								</tr>
								<tr>
								<td>Outward supply(Nil rated)</td>
								<td>250</td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								
								</tr>
								<tr>
								<td>Inward supply(Liable to reverse charge)</td>
								<td>250</td>
								<td>100</td>
								<td>50</td>
								<td>100</td>
								<td>150</td>
								
								</tr>
								<tr>
								<td>Non GST outward supply</td>
								<td>250</td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								
								</tr>
								
                                </thead>
                                <tbody>
                            
                                </tbody>
                            </table>
                        </div>
						 <div class="clear"></div>
							<h4>inter-State supplies made to unregistered persons, composition taxable persons and UIN holders</h4>
                           <div class="tableresponsive">
						    
                            <table  class="table  tablecontent tablecontent2">
                                <thead>
								
                                <tr>
								 <th>SupplyType</th>
								 <th>POS</th>
                               
                            
                                <th>TAXABLE AMT (₹)	</th>
                                <th class="text-right">IAMT (₹)</th>
								
                                <th class=""></th></tr>
								<tr>
								<td>Unregistered Details</td>
								<td>07</td>
								<td>100</td>
								<td align="right">50</td>
							
								
								</tr>
								<tr>
								<td>Composition vendor</td>
								<td>07</td>
								<td>100</td>
								<td align="right">50</td>
							
								</tr>
								<tr>
								<td>Uin Holder</td>
								<td>07</td>
								<td>100</td>
								<td align="right">50</td>
								
								</tr>
								
							
								
                                </thead>
                                <tbody>
                            
                                </tbody>
                            </table>
                        </div>
						 <div class="clear"></div>
							<h4>Eligible ITC</h4>
							<h4>(A) ITC Available</h4>
                           <div class="tableresponsive">
						    
                            <table  class="table  tablecontent tablecontent2">
                                <thead>
								
                                <tr>
								
								 <th>type</th>
                               
                            
                                
                                <th class="text-right">IAMT (₹)</th>
								<th class="text-right">CAMT (₹)</th>
								<th class="text-right">SAMT (₹)</th>
								<th class="text-right">CSAMT (₹)</th>
								
                                <th class=""></th></tr>
								<tr>
								<td>IMPG</td>
								<td align="right">136</td>
								<td align="right">274</td>
								<td align="right">160</td>
									<td align="right">100</td>
							
								
								</tr>
								
								
								
							
								
                                </thead>
                                <tbody>
                            
                                </tbody>
                            </table>
                        </div>
						
                        </div> 
                    
       	  </div>
 		 <div class="clear height40"></div>      
    </div>
  <div class="clear"></div>
<script>
	$(document).ready(function () {
		$('#returnmonth').on('change', function () {
			window.location.href = "<?php echo PROJECT_URL; ?>/?page=return_gstr3b_file_save&returnmonth=" + $(this).val();
		});
	});
</script>