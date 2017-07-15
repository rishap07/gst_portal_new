<?php
$obj_client = new client();
if (!isset($_REQUEST['returnmonth']) || $_REQUEST['returnmonth'] == '') {
    $obj_client->redirect(PROJECT_URL . "/?page=return_client");
    exit();
}
$returnmonth= '2017-07';
if($_REQUEST['returnmonth'] != '')
{
    $returnmonth= '2017-07';
}
?>
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr">
    <div class="col-md-11 col-sm-12 col-xs-12 mobpadlr">
        <div class="col-md-12 col-sm-12 col-xs-12 heading">
            
            <div class="tab col-md-12 col-sm-12 col-xs-12">
               
				 <a href="<?php echo PROJECT_URL . '/?page=return_summary&returnmonth='.$returnmonth ?>" >
                   View GSTR1 Summary
                </a>
                <a href="<?php echo PROJECT_URL . '/?page=return_view_invoices&returnmonth='.$returnmonth ?>" >
                    View My Invoice
                </a>
                <a href="<?php echo PROJECT_URL . '/?page=return_upload_invoices&returnmonth='.$returnmonth ?>">
                    Upload To GSTN
                 </a>
				   <a href="<?php echo PROJECT_URL . '/?page=return_filling_summary&returnmonth='.$returnmonth ?>" class="active">
                    File GSTr-1
                </a>
            </div>
            <div id="upload_invoice" class="tabcontent">
			<div class="col-md-12 col-sm-12 col-xs-12">
			 <div class="col-md-6 col-sm-12 col-xs-12"><h3>GSTR-1 Filing Summary</h3></div>
            
               
		
                <div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="whitebg formboxcontainer">
					
                            <?php $obj_client->showErrorMessage(); ?>
                            <?php $obj_client->showSuccessMessge(); ?>
                            <?php $obj_client->unsetMessage(); ?>
							
							
                            <div class="adminformbx">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="invoice-itemtable" id="mainTable1">
                                    <thead>
                                        <tr>
                                            <th align='left'>TYPE OF INVOICE</th>
                                            <th align='left'>NO. INVOICES</th>
                                            <th align='left'>TAXABLE AMT (₹)</th>
                                            <th align='left'>TAX AMT (₹)</th>
                                            <th align='left'>THROUGH E-COM (₹)	</th>
											<th align='left'>REV.CHARGE (₹)	</th>
											
											
											
                                        </tr>
                                        <tr>
                                            <td>B2B</td>
                                            <td>1</td>
                                            <td>201.15</td>
                                            <td>14.05</td>
                                            <td>0.0</td>
											 <td>0.0</td>
											
                                        </tr>
										 <tr>
                                            <td>Advance Receipt</td>
                                            <td>2</td>
                                            <td>15400</td>
                                            <td>5.0</td>
                                            <td>0.0</td>
											
                                        </tr>
                                        
                                    </thead>
                                </table>
                            </div>  
                        </div>
                    </div>
                </div>
            </div>
			    <div id="upload_invoice" class="tabcontent">
			<div class="col-md-12 col-sm-12 col-xs-12">
			 <div class="col-md-6 col-sm-12 col-xs-12"><h3>HSN/SAC summary

</h3></div>
             
                <div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="whitebg formboxcontainer">
					
                            <?php $obj_client->showErrorMessage(); ?>
                            <?php $obj_client->showSuccessMessge(); ?>
                            <?php $obj_client->unsetMessage(); ?>
							
							
                            <div class="adminformbx">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="invoice-itemtable" id="mainTable1">
                                    <thead>
                                        <tr>
                                            <th align='left'>S.No.</th>
                                            <th align='left'>GOODS/SERVICES</th>
                                            <th align='left'>DESCRIPTION</th>
                                            <th align='left'>HSN/SAC</th>
                                            <th align='left'>UOM</th>
											<th align='left'>QUANTITY</th>
											<th align='left'>NATURE OF SUPPLY</th>
											<th align='left'>TAXABLE (₹)</th>
											<th align='left'>IGST (₹)</th>
											<th align='left'>CGST (₹)</th>
											<th align='left'>SGST (₹)</th>
											<th align='left'>CESS (₹)</th>
											
											
											
											
                                        </tr>
                                        <tr>
                                            <td>1</td>
                                            <td>Goods</td>
                                            <td>Keyboard</td>
                                            <td>07123200</td>
                                            <td></td>
											<td>5</td>
											<td>Inter state B2B</td>
											<td>201.50</td>
											<td>10.05</td>
											<td>0.0</td>
											<td>0.0</td>
											<td>4.02</td>
											
                                        </tr>
										
                                        
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