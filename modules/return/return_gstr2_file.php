<?php
$obj_client = new client();
$returnmonth = date('Y-m');
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
               
                	<div class="col-md-6 col-sm-6 col-xs-12 heading"><h1>GSTR-2 Filing</h1></div>
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
								<li><a href="<?php echo PROJECT_URL . '/?page=return_gstr2&returnmonth=' . $returnmonth ?>" >View GSTR2 Summary</a></li>
								<li><a href="<?php echo PROJECT_URL . '/?page=return_purchase_all&returnmonth=' . $returnmonth ?>" > View My Data</a></li>
								<li><a href="<?php echo PROJECT_URL . '/?page=return_vendor_invoices&returnmonth=' . $returnmonth ?>">Download GSTR-2A</a></li>
								<li><a href="<?php echo PROJECT_URL . '/?page=return_gstr2_reconcile&returnmonth=' . $returnmonth ?>">GSTR-2 Reconcile</a></li>
								<li><a href="<?php echo PROJECT_URL . '/?page=return_gstr2_file&returnmonth=' . $returnmonth ?>" class="active">GSTR-2 Filing</a></li>
									<li><a href="<?php echo PROJECT_URL . '/?page=return_gstr2_upload_invoices&returnmonth=' . $returnmonth ?>">Upload To GSTN</a></li>
								
							</ul>
						</div>
							<div class="clear"> </div>
                                <div class="text-center">
					 
					 <a href="#" class="btngreen"><i class="fa fa-upload" aria-hidden="true"></i> FILE GSTR-2</a>
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
<script>
	$(document).ready(function () {
		$('#returnmonth').on('change', function () {
			window.location.href = "<?php echo PROJECT_URL; ?>/?page=return_gstr2_file&returnmonth=" + $(this).val();
		});
	});
</script>