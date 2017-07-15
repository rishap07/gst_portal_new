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
<div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr">
    <div class="col-md-11 col-sm-12 col-xs-12 mobpadlr">
        <div class="col-md-12 col-sm-12 col-xs-12 heading">
            <div class="pull-left">
                <h1>Return summary for the select month</h1>
            </div>
            <div class="pull-right rgtdatetxt">
                 
                <form method='post' name='form2'>
				Month Of Return
				<?php
				$dataQuery = "SELECT DATE_FORMAT(invoice_date,'%Y-%m') AS niceDate FROM gst_client_invoice group by nicedate";
				$dataRes = $obj_client->get_results($dataQuery);
				if(!empty($dataRes))
				{
					?>
					<select class="dateselectbox" id="returnmonth" name="returnmonth">
						<?php
						foreach($dataRes as $dataRe)
						{
							?>
							<option value="<?php echo $dataRe->niceDate;?>" <?php if($dataRe->niceDate == $returnmonth){ echo 'selected';}?>><?php echo $dataRe->niceDate;?></option>
							<?php
						}
						?>
						
					</select>
					<?php
				}else
				{
				?>
                <select class="dateselectbox" id="returnmonth" name="returnmonth">
                    <option>July 2017</option>
                </select>
				<?php }
				?>
				</form>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="returnbox">
                <div class="col-md-6 col-sm-6 col-xs-6 boxheading">SALES INVOICE</div>
                <div class="col-md-6 col-sm-6 col-xs-6 text-right">  
                    <a href="<?php echo PROJECT_URL;?>/?page=client_upload_invoice" class="btn btnimport">Choose File</a>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <?php
                    $querySales = "select * from ".$obj_client->getTableName('client_invoice')." where invoice_nature='salesinvoice' and added_by='".$_SESSION['user_detail']['user_id']."' and status='1' and is_canceled='0' and is_deleted='0' and invoice_date like '%".$returnmonth."%'";
                    $salesData = $obj_client->get_results($querySales);
                    ?>
                    Total Sales Invoices : <?php echo count($salesData);?>
                    <div class="clear" style="height:20px;"></div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="returnbox">
                <div class="col-md-6 col-sm-6 col-xs-6 boxheading">PURCHASE INVOICE</div>
                <div class="col-md-6 col-sm-6 col-xs-6 text-right">
                    <a href="<?php echo PROJECT_URL;?>/?page=client_upload_invoice" class="btn btnimport">Choose File</a></div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    Total Purchase Invoices : NA
                    <div class="clear" style="height:20px;"></div>
                </div>
            </div>
        </div>

        <div class="col-md-12 col-sm-12 col-xs-12"> <p class="hr--text"><span class="text--uppercase">GST Returns</span></p></div>

        <!--GSTR STEP START HERE--->
        <div class="col-md-12 col-sm-12 col-xs-12 whitebg gstr-box">
            <div class="lightyellow roundbtn"><span>GSTR-1</span>  | <span>Sales Return Filing</span></div>
            <div class="clearfix"></div>
            <div class="gstr-step-row">
                <div class="row">
                    <div class="col-md-8 col-sm-8 modpadlr">
                        <div class="rowsteps">
                            <div class="row step-col actionable">
                                <div class="col-md-5 col-sm-5 col-xs-5"><span class="statusbullet"></span>
                                    <span class="txtinovice">Upload invoices to GSTN</span></div>
                                <div class="col-md-3 col-sm-3 col-xs-3"><span class="txtorange">Pending</span></div>
                                <div class="col-md-4 col-sm-4 txtorange col-xs-4"><i class="fa fa-clock-o" aria-hidden="true"></i> Due on 10 <?php echo $month;?></div>
                            </div>
                        </div>

                        <div class="rowsteps">
                            <div class="row step-col actionable">
                                <div class="col-md-5 col-sm-5 col-xs-5"><span class="statusbullet"></span>
                                    <span class="txtinovice">File GSTR-1</span></div>
                                <div class="col-md-3 col-sm-3 col-xs-3"><span class="txtorange">Pending</span></div>
                                <div class="col-md-4 col-sm-4 txtorange col-xs-4"><i class="fa fa-clock-o" aria-hidden="true"></i> Due on 10 <?php echo $month;?></div>
                            </div>
                        </div>
                        <div class="rowsteps">
                            <div class="row step-col actionable">
                                <div class="col-md-5 col-sm-5 col-xs-5"><span class="statusbullet txt_grey"></span>
                                    <span class="txtinovice txt_grey">Reconcile Invoices</span></div>
                                <div class="col-md-3 col-sm-3 col-xs-3"><span class="txtorange txt_grey">Pending</span></div>
                                <div class="col-md-4 col-sm-4 txtorange col-xs-4 txt_grey"><i class="fa fa-clock-o" aria-hidden="true"></i> Due on 17 <?php echo $month;?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4" style="background:#fde7e0; border-radius:3px; padding:15px">
                        <div class="gstrrgtbox">File GSTR-1<br/><span>To work on GST Return 1 (Sales)</span></div>
                        <a href="<?php echo PROJECT_URL;?>/?page=return_summary&returnmonth=<?php echo $returnmonth;?>" class="btn btn-orange" style="width:100%;">Start Now</a>
                    </div>
                </div>
            </div>
        </div>

        <!--GSTR STEP END HERE--->      


        <!--GSTR STEP START HERE--->
        <div class="col-md-12 col-sm-12 col-xs-12 whitebg gstr-box">
            <div class="lightyellow roundbtn"><span>GSTR-2</span>  | <span>Purchase Return Filing</span></div>
            <div class="clearfix"></div>
            <div class="gstr-step-row">
                <div class="row">
                    <div class="col-md-8 col-sm-8 modpadlr">
                        <div class="rowsteps">
                            <div class="row step-col actionable">
                                <div class="col-md-5 col-sm-5 col-xs-5"><span class="statusbullet"></span>
                                    <span class="txtinovice">Download invoices from GSTN</span></div>
                                <div class="col-md-3 col-sm-3 col-xs-3"><span class="txtorange">Pending</span></div>
                                <div class="col-md-4 col-sm-4 txtorange col-xs-4"><i class="fa fa-clock-o" aria-hidden="true"></i> Due on 15 <?php echo $month;?></div>
                            </div>
                        </div>

                        <div class="rowsteps">
                            <div class="row step-col actionable">
                                <div class="col-md-5 col-sm-5 col-xs-5"><span class="statusbullet"></span>
                                    <span class="txtinovice">Match and Reconcile</span></div>
                                <div class="col-md-3 col-sm-3 col-xs-3"><span class="txtorange">Pending</span></div>
                                <div class="col-md-4 col-sm-4 txtorange col-xs-4"><i class="fa fa-clock-o" aria-hidden="true"></i> Due on 15 <?php echo $month;?></div>
                            </div>
                        </div>
                        <div class="rowsteps">
                            <div class="row step-col actionable">
                                <div class="col-md-5 col-sm-5 col-xs-5"><span class="statusbullet"></span>
                                    <span class="txtinovice">Claim ITC</span></div>
                                <div class="col-md-3 col-sm-3 col-xs-3"><span class="txtorange">Pending</span></div>
                                <div class="col-md-4 col-sm-4 txtorange col-xs-4"><i class="fa fa-clock-o" aria-hidden="true"></i> Due on 15 <?php echo $month;?></div>
                            </div>
                        </div>
                        <div class="rowsteps">
                            <div class="row step-col actionable">
                                <div class="col-md-5 col-sm-5 col-xs-5"><span class="statusbullet"></span>
                                    <span class="txtinovice">File GSTR-2</span></div>
                                <div class="col-md-3 col-sm-3 col-xs-3"><span class="txtorange">Pending</span></div>
                                <div class="col-md-4 col-sm-4 txtorange col-xs-4"><i class="fa fa-clock-o" aria-hidden="true"></i> Due on 15 <?php echo $month;?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4" style="background:#fde7e0; border-radius:3px; padding:15px">
                        <div class="gstrrgtbox">File GSTR-2<br/><span>To work on GST Return 2 (Purchases)</span></div>
                        <a href="#" class="btn btn-orange" style="width:100%;">Start Now</a>
                    </div>
                </div>
            </div>
        </div>

        <!--GSTR STEP END HERE--->      
        <!--GSTR STEP START HERE--->
        <div class="col-md-12 col-sm-12 col-xs-12 whitebg gstr-box">
            <div class="lightyellow roundbtn"><span>GSTR-3</span>  | <span>Monthly Return Filing</span></div>
            <div class="clearfix"></div>
            <div class="gstr-step-row">
                <div class="row">
                    <div class="col-md-8 col-sm-8 modpadlr">
                        <div class="rowsteps">
                            <div class="row step-col actionable">
                                <div class="col-md-5 col-sm-5 col-xs-5"><span class="statusbullet"></span>
                                    <span class="txtinovice">Review Monthly Summary</span></div>
                                <div class="col-md-3 col-sm-3 col-xs-3"><span class="txtorange">Pending</span></div>
                                <div class="col-md-4 col-sm-4 txtorange col-xs-4"><i class="fa fa-clock-o" aria-hidden="true"></i> Due on 20 <?php echo $month;?></div>
                            </div>
                        </div>

                        <div class="rowsteps">
                            <div class="row step-col actionable">
                                <div class="col-md-5 col-sm-5 col-xs-5"><span class="statusbullet"></span>
                                    <span class="txtinovice">Payments / Refunds</span></div>
                                <div class="col-md-3 col-sm-3 col-xs-3"><span class="txtorange">Pending</span></div>
                                <div class="col-md-4 col-sm-4 txtorange col-xs-4"><i class="fa fa-clock-o" aria-hidden="true"></i> Due on 20 <?php echo $month;?></div>
                            </div>
                        </div>
                        <div class="rowsteps">
                            <div class="row step-col actionable">
                                <div class="col-md-5 col-sm-5 col-xs-5"><span class="statusbullet"></span>
                                    <span class="txtinovice">View Challans</span></div>
                                <div class="col-md-3 col-sm-3 col-xs-3"><span class="txtorange">Pending</span></div>
                                <div class="col-md-4 col-sm-4 txtorange col-xs-4"><i class="fa fa-clock-o" aria-hidden="true"></i> Due on 20 <?php echo $month;?></div>
                            </div>
                        </div>
                        <div class="rowsteps">
                            <div class="row step-col actionable">
                                <div class="col-md-5 col-sm-5 col-xs-5"><span class="statusbullet"></span>
                                    <span class="txtinovice">File GSTR-3</span></div>
                                <div class="col-md-3 col-sm-3 col-xs-3"><span class="txtorange">Pending</span></div>
                                <div class="col-md-4 col-sm-4 txtorange col-xs-4"><i class="fa fa-clock-o" aria-hidden="true"></i> Due on 20 <?php echo $month;?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4" style="background:#fde7e0; border-radius:3px; padding:15px">
                        <div class="gstrrgtbox">File GSTR-3<br/><span>To work on GST Return 3</span></div>
                        <a href="#" class="btn btn-orange" style="width:100%;">Start Now</a>
                    </div>
                </div>
            </div>
        </div>

        <!--GSTR STEP END HERE--->   


    </div>

    <div class="col-md-12 col-sm-12 col-xs-12 padrgtnone mobpadlr formcontainer" style="display:none;">
        <div class="col-md-12 col-sm-12 col-xs-12">

            <h1>Returns</h1>
            <div class="whitebg formboxcontainer">

                <?php $obj_client->showErrorMessage(); ?>
                <?php $obj_client->showSuccessMessge(); ?>
                <?php $obj_client->unsetMessage(); ?>
                <h2 class="greyheading">Returns Listing</h2>
                <div class="adminformbx">

                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="invoice-itemtable" id="mainTable">
                        <thead>
                            <tr>
                                <th align='left'>#</th>
                                <th align='left'>Financial Year</th>
                                <th align='left'>Month</th>
                                <th align='left'>GSTR</th>
                                <th align='left'>Status</th>
                                <th align='left'>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>  
            </div>
        </div>
    </div>
    <div class="clear height80">
    </div>
    <script>
        $(document).ready(function () {
            $('#returnmonth').on('change',function(){
				
				<?php
				if(isset($_REQUEST['returnmonth']) && $_REQUEST['returnmonth'] != '')
				{
					?>
					document.form2.action='<?php echo PROJECT_URL;?>/?page=return_client&returnmonth=<?php echo $_REQUEST['returnmonth'];?>';
					<?php
				}else
				{
					?>
					document.form2.action='<?php echo PROJECT_URL;?>/?page=return_client';
					<?php
				}
				?>
				document.form2.submit();
			});
        });

    </script>