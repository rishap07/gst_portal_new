<?php
	$obj_gstr2 = new gstr2();
	
	$dataCurrentUserArr = $obj_gstr2->getUserDetailsById( $obj_gstr2->sanitize($_SESSION['user_detail']['user_id']) );
	$returnmonth= date('Y-m');
	if(isset($_REQUEST['returnmonth']) && $_REQUEST['returnmonth'] != '') {
		$returnmonth= $_REQUEST['returnmonth'];
	}
	if(isset($_POST['gstr2Download']) && $_POST['gstr2Download'] === "Download" && isset($_POST['flag']) && strtoupper($_POST['flag']) === "DOWNLOAD") {

		if(!isset($_SERVER['HTTP_REFERER']) || empty($_SERVER['HTTP_REFERER'])){
			$obj_gstr2->setError('Invalid access to files');
		} else {
			$response1 = $obj_gstr2->downloadGSTR2();
			$response_b2b = $response1['response_b2b'];
			$response_cdn = $response1['response_cdn'];
			//$obj_gstr2->pr($response1);die;
			
		}
	}

	
?>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
	<div class="col-md-12 col-sm-12 col-xs-12">

		<div class="col-md-6 col-sm-6 col-xs-12 heading"><h1>GSTR-2 Filing</h1></div>
		<div class="col-md-6 col-sm-6 col-xs-12 text-right breadcrumb-nav">
			<a href="#">Home</a>
			<i class="fa fa-angle-right" aria-hidden="true"></i>
			<a href="#">File Return</a>
			<i class="fa fa-angle-right" aria-hidden="true"></i>
			<span class="active">GSTR-2 Filing</span>
		</div>

		<div class="whitebg formboxcontainer">

			<div class="pull-right rgtdatetxt">
				<form method='post' name='form3' id="form3">
					Month Of Return 
					<?php
						$dataQuery = "SELECT DATE_FORMAT(invoice_date,'%Y-%m') AS niceDate FROM gst_client_invoice group by nicedate";
						$dataRes = $obj_gstr2->get_results($dataQuery);
						
						if (!empty($dataRes)) { ?>
							<select class="dateselectbox" id="returnmonth" name="returnmonth">
								<?php foreach ($dataRes as $dataRe) { ?>
									<option value="<?php echo $dataRe->niceDate; ?>" <?php if ($dataRe->niceDate == $returnmonth) { echo 'selected'; } ?>><?php echo $dataRe->niceDate; ?></option>
								<?php } ?>
							</select>
					<?php } else { ?>
					
						<select class="dateselectbox" id="returnmonth" name="returnmonth">
							<option>July 2017</option>
						</select>
					<?php } ?>
				</form>
			</div>

			<div class="col-md-12 col-sm-12 col-xs-12 tablistnav padleft0">
			<?php
        		include(PROJECT_ROOT."/modules/return/include/tab.php");
            ?>
			</div>
			<div class="clear"></div>
			
			<?php $obj_gstr2->showErrorMessage(); ?>
			<?php $obj_gstr2->showSuccessMessge(); ?>
			<?php $obj_gstr2->unsetMessage(); ?>
			<div class="clear"></div>
			<div class="text-right">
				<?php
				$dataReturns = $obj_gstr2->get_results("select * from " . TAB_PREFIX . "return where return_month='" . $returnmonth . "' and client_id='" . $_SESSION['user_detail']['user_id'] . "' and status='3' and type='gstr2'");
				if (!empty($dataReturns)) {
					?>
					<div id="sucmsg" style="background-color:#DBEDDF;border-radius:4px;padding:8px 35px 8px 14px;text-shadow:0 1px 0 rgba(255, 255, 255, 0.5);margin-bottom:18px;border-color:#D1E8DA;color:#39A25F;"><i class="fa fa-check"></i> <b>Success:</b> GSTR2 is Already Filed</div>
					<?php
				} else {
					?>
					<form name="gstr2-download" id="gstr2-download" method="post">
						<input type="hidden" name="gstr2ReturnMonth" value="<?php if(isset($_GET['returnmonth'])) { echo $_GET['returnmonth']; } ?>">
						<input type="hidden" name="flag" value="download">
						<button type="submit" name="gstr2Download" id="gstr2Download" value="Download" class="btngreen btn"><i class="fa fa-cloud-download" aria-hidden="true"></i> Download GSTR-2A</button>
					</form>
					<?php
				}
				?>
				
			</div>
			<div id="display_json"></div>
			<?php 
				$responseCDN = $obj_gstr2->checkUserInvoices($_SESSION['user_detail']['user_id'],$returnmonth,'CDN');
				$responseB2B = $obj_gstr2->checkUserInvoices($_SESSION['user_detail']['user_id'],$returnmonth,'B2B');
				$responseTable = '';
				if(!empty($responseB2B)) {
					$responseTable .= '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="invoice-itemtable" id="mainTable1">
					        <thead>
					        <tr>
					            <th>Number</th>
					            <th style="text-align:center">Invoice number</th> 
					            <th style="text-align:center">Ctin</th> 
					            <th style="text-align:center">Pos </th> 
					            <th style="text-align:center">Item </th>
					            <th style="text-align:center">Invoice type</th>    
					            <th style="text-align:center">Invoice date</th>
					            
					            <th style="text-align:center">Tax value ( <i class="fa fa-inr"></i> )</th>
					            <th style="text-align:center">Rate</th>
					            <th style="text-align:center">Iamt ( <i class="fa fa-inr"></i> )</th> 
					            <th style="text-align:center">Samt ( <i class="fa fa-inr"></i> )</th> 
					            <th style="text-align:center">Camt ( <i class="fa fa-inr"></i> )</th>                
					            <th style="text-align:center">Csamt ( <i class="fa fa-inr"></i> )</th>
					            <th style="text-align:center">Value ( <i class="fa fa-inr"></i> )</th>
					            
					            <th style="text-align:center">Rchrg</th>            
					        </tr>';
			            $i=0;
                        foreach ($responseB2B as $key3 => $value) {
                        	$idt = $value->invoice_date > 0 ? date('d-m-Y', strtotime($value->invoice_date)) : '';
                    		$responseTable .='<tr>
                               	<td align="center">'.$value->itms.'</td>
                                <td align="center">'.$value->reference_number.'</td>
                                <td align="center">'.$value->company_gstin_number.'</td>
                                <td align="center">'.$value->pos.'</td>
                                <td align="center">'.$i++.'</td>
                                <td align="center">'.$value->inv_typ.'</td>
                                <td align="center">'.$idt.'</td>
                                <td align="center">'.$value->total_taxable_subtotal.'</td>
                                <td align="center">'.$value->rate.'</td>
                                <td align="center">'.$value->total_igst_amount.'</td>
                                <td align="center">'.$value->total_sgst_amount.'</td>
                                <td align="center">'.$value->total_cgst_amount.'</td>
                                <td align="center">'.$value->total_cess_amount.'</td>
                                <td align="right">'.$value->invoice_total_value.'</td>
                                <td align="center">'.$value->rchrg.'</td>
                            </tr>';
                          
                        }
			            $responseTable .= '
			                </thead>
			            </table>';
				
			            echo $responseTable;
				}
				if(!empty($responseCDN)) {
					$responseTable .= '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="invoice-itemtable" id="mainTable1">
			            <thead>
			            <tr>
			                <th>Num</th>
			                <th style="text-align:center">Credit/Debit Note Number</th>    
			                <th style="text-align:center">Credit/Debit Note  Date</th>
			                <th style="text-align:center">Ctin </th>
			                <th style="text-align:center">Invoice Number</th> 
			                <th style="text-align:center">Invoice Date</th>
			                <th style="text-align:center">Item </th>
			                <th style="text-align:center">Pgst</th>
			                <th style="text-align:center">Txval ( <i class="fa fa-inr"></i> )</th>
			                <th style="text-align:center">Rate</th>
			                <th style="text-align:center">Iamt ( <i class="fa fa-inr"></i> )</th>  
			                <th style="text-align:center">Samt ( <i class="fa fa-inr"></i> )</th> 
			                <th style="text-align:center">Camt ( <i class="fa fa-inr"></i> )</th> 
			                <th style="text-align:center">Csamt ( <i class="fa fa-inr"></i> )</th>
			                <th style="text-align:center">Val ( <i class="fa fa-inr"></i> )</th>
			                <th style="text-align:center">Rsn </th>     
			                <th style="text-align:center">Ntty</th>
			                
			            </tr>';
			            $i=0;
                        foreach ($responseCDN as $key3 => $value) {
                        	$idt = $value->invoice_date > 0 ? date('d-m-Y', strtotime($value->invoice_date)) : '';
                        	$nt_dt = $value->nt_dt > 0 ? date('d-m-Y', strtotime($value->nt_dt)) : '';
                        		$responseTable .='<tr>
                        			<td align="center">'.$value->itms.'</td>
                        			<td align="center">'.$value->nt_num.'</td>
                        			 <td align="center">'.$value->company_gstin_number.'</td>
                        			<td align="center">'.$nt_dt.'</td>
	                                <td align="center">'.$value->reference_number.'</td>
	                                <td align="center">'.$idt.'</td>
	                                <td align="center">'.$i++.'</td>
	                                <td align="center">'.$value->p_gst.'</td>
	                                <td align="center">'.$value->total_taxable_subtotal.'</td>
	                                <td align="center">'.$value->rate.'</td>
	                                <td align="center">'.$value->total_igst_amount.'</td>
	                                <td align="center">'.$value->total_sgst_amount.'</td>
	                                <td align="center">'.$value->total_cgst_amount.'</td>
	                                <td align="center">'.$value->total_cess_amount.'</td>
	                                <td align="right">'.$value->invoice_total_value.'</td>
	                                <td align="center">'.$value->rsn.'</td>
	                                <td align="center">'.$value->ntty.'</td>
	                            </tr>';	
                          
                        }
			            $responseTable .= '
			                </thead>
			            </table>';
				
			            echo $responseTable;
				}
			?>



		</div>

		<div class="clear height40"></div>
	</div>
	<div class="clear"></div>
</div>
<script>
	get_summary();
	$(document).ready(function () {
		$('#returnmonth').on('change', function () {
			window.location.href = "<?php echo PROJECT_URL; ?>/?page=return_vendor_invoices&returnmonth=" + $(this).val();
		});
	});
	  /******* To get Summary of GSTR2 ********/
    function get_summary() {
        var response_b2b = '<?php echo $response_b2b;?>';
        var response_cdn = '<?php echo $response_cdn;?>';
        var jstr = 'gstr2b';
        var returnmonth = '<?php echo $returnmonth;?>';
        $.ajax({
            url: "<?php echo PROJECT_URL; ?>/?ajax=return_gstr1_json_view",
            type: "post",
            data: {response_b2b: response_b2b,response_cdn: response_cdn,returnmonth:returnmonth,jstr:jstr},
            success: function (response) {
               $('#display_json').html(response);

            },
            error: function() {
            }
        });
    }
    /******* To get Summary of GSTR2 ********/
</script>