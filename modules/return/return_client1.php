<?php
//echo date('m');
$datetime =new datetime('2017-02-10 00:00:00');
echo date_format($datetime,"d");
$obj_client = new client();
$returnmonth = date('Y-m');
if(isset($_POST['returnmonth']))
{
    $returnmonth = $_POST['returnmonth'];
	$obj_client->redirect(PROJECT_URL."/?page=return_client1&returnmonth=".$returnmonth);
	exit();
}
$returnmonth= date('Y-m');
if(isset($_REQUEST['returnmonth']) && $_REQUEST['returnmonth'] != '')
{
    $returnmonth= $_REQUEST['returnmonth'];
	
}
else
{
	
	$dataQuery = "SELECT DATE_FORMAT(invoice_date,'%Y-%m') AS niceDate FROM gst_client_invoice group by nicedate ORDER by invoice_date desc LIMIT 0,1";
	$dataRes = $obj_client->get_results($dataQuery);
	if(!empty($dataRes))
	{
	$returnmonth=$dataRes[0]->niceDate;
	}	
}
$time = strtotime($returnmonth."-01");
$month = date("M", strtotime("+1 month", $time));
$dataArr = array();

			
$dataArr = $obj_client->getUserDetailsById($obj_client->sanitize($_SESSION['user_detail']['user_id']));
 $sql='select * from ' . $db_obj->getTableName('return') . " where client_id='" . $_SESSION["user_detail"]["user_id"] . "' and return_month = '".$returnmonth."' and type='gstr1'";
$dataInvs = $db_obj->get_results($sql);

$status_msg="";
$status="";
$sale_status="";
$sale_file_status = 0;
$sale_download_status = 0;
$sale_upload_status = 0;
$sale_initiate_status = 0;
if(!empty($dataInvs))
{
	if($dataInvs[0]->status=='0')
	{
		$status_msg="Pending";
		$status = 0;
		$sale_file_status = 0;
		$sale_download_status = 0;
		$sale_upload_status = 0;
		$sale_initiate_status = 0;
	}
	else if($dataInvs[0]->status=='1')
	{
		$status_msg="Initiated";
			$status = 1;
		$sale_file_status = 0;
		$sale_download_status = 0;
		$sale_upload_status = 0;
		$sale_initiate_status = 1;
	}
	else if($dataInvs[0]->status=='2')
	{
		 $status_msg="Uploaded";
			$status = 2;
			$sale_file_status = 0;
		$sale_download_status = 0;
		$sale_upload_status = 1;
		$sale_initiate_status = 1;
	}
	else if($dataInvs[0]->status=='3')
	{
		$status_msg="Filed";
			$status = 3;
			$sale_file_status = 1;
		$sale_download_status = 0;
		$sale_upload_status = 1;
		$sale_initiate_status = 1;
	}
	else if($dataInvs[0]->status=='4')
	{
		$status_msg="downloaded";
			$status = 4;
			$sale_file_status = 1;
		$sale_download_status = 1;
		$sale_upload_status = 1;
		$sale_initiate_status = 1;
	}
	else
	{
		$status_msg="Pending";
			$status = 0;
	}
}
$sql='select * from ' . $db_obj->getTableName('return') . " where client_id='" . $_SESSION["user_detail"]["user_id"] . "' and return_month = '".$returnmonth."' and type='gstr2'";
$dataInvPurchase = $db_obj->get_results($sql);

$status_msg="";
$purchase_status="";
$purchase_file_status = 0;
$purchase_download_status = 0;
$purchase_upload_status = 0;
$purchase_initiate_status = 0;
if(!empty($dataInvPurchase))
{
	if($dataInvPurchase[0]->status=='0')
	{
		$status_msg="Pending";
		$purchase_status = 0;
	}
	else if($dataInvPurchase[0]->status=='1')
	{
		    $status_msg="Initiated";
			$purchase_status = 1;
			$purchase_file_status = 0;
			$purchase_download_status = 0;
			$purchase_upload_status = 0;
		    $purchase_initiate_status = 1;
	}
	else if($dataInvPurchase[0]->status=='2')
	{
		   $status_msg="Uploaded";
			$purchase_status = 2;
			$purchase_file_status = 0;
			$purchase_download_status = 1;
			$purchase_upload_status = 0;
		    $purchase_initiate_status = 1;
	}
	else if($dataInvPurchase[0]->status=='3')
	{
		    $status_msg="Filed";
			$purchase_status = 3;
			$purchase_file_status = 1;
			$purchase_download_status = 1;
			$purchase_upload_status = 1;
		    $purchase_initiate_status = 1;
	}
	else if($dataInvPurchase[0]->status=='4')
	{
		$status_msg="downloaded";
			$purchase_status = 4;
			$purchase_file_status = 0;
			$purchase_download_status = 1;
			$purchase_upload_status = 0;
		    $purchase_initiate_status = 1;
	}
	else
	{
		$dataInvPurchase="Pending";
		$purchase_status = 0;
	}
}
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
				 <?php
                    $queryPurchase = "select * from ".$obj_client->getTableName('client_purchase_invoice')." where invoice_nature='purchaseinvoice' and added_by='".$_SESSION['user_detail']['user_id']."' and status='1' and is_canceled='0' and is_deleted='0' and invoice_date like '%".$returnmonth."%'";
                    $purchaseData = $obj_client->get_results($querySales);
                    ?>
                    Total Purchase Invoices :<?php echo count($purchaseData);?>
                    <div class="clear" style="height:20px;"></div>
                </div>
            </div>
        </div>

        <div class="col-md-12 col-sm-12 col-xs-12"> <p class="hr--text"><span class="text--uppercase">GST Returns</span></p></div>
           <!--GSTR STEP END HERE--->   
		       <!--GSTR STEP END HERE--->   
         <div class="col-md-12 col-sm-12 col-xs-12 whitebg gstr-box">
            <div class="lightyellow roundbtn"><span>GST-Transition Form</span>  | <span>Monthly Filing</span></div>
            <div class="clearfix"></div>
            <div class="gstr-step-row">
                <div class="row">
                    <div class="col-md-8 col-sm-8 modpadlr">
                        <div class="rowsteps">
                            
                        </div>

                      
                       
                    </div>
                    <div class="col-md-4 col-sm-4" style="background:#fde7e0; margin-top:20px; border-radius:3px; padding:15px">
                        <div class="gstrrgtbox">Fill GST-Transition<br/><span>To work on GST Transition Form</span></div>
                        <a href="<?php echo PROJECT_URL;?>/?page=transition_gstr&returnmonth=<?php echo $returnmonth;?>" class="btn btn-orange" style="width:100%;">Start Now</a>
                    </div>
                </div>
            </div>
        </div>
    <?php
       $flag = $db_obj->getVendorName('composition vendor');
	   $vendor_id1=0;
		$sql="SELECT * FROM " . $db_obj->getTableName("returnfile_setting") . " as s inner join " . $db_obj->getTableName("return_cat") . " as c on c.id = s.cat_id
	 INNER join  " . $db_obj->getTableName("return_subcat") . " as subcat on subcat.id = s.subcat_id where c.id = 1";
	 $dataGstr3b = $db_obj->get_results($sql);
	 if(!empty($dataGstr3b) && ($flag==$dataArr['data']->kyc->vendor_type))
	 {	   
  
	?>
         <div class="col-md-12 col-sm-12 col-xs-12 whitebg gstr-box">
            <div class="lightyellow roundbtn"><span>GSTR-3B File</span>  | <span>Monthly Return Filing</span></div>
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

                      
                       
                    </div>
                    <div class="col-md-4 col-sm-4" style="background:#fde7e0; margin-top:20px; border-radius:3px; padding:15px">
                        <div class="gstrrgtbox">File GSTR-3B<br/><span>To work on GST Return 3B File</span></div>
                        <a href="<?php echo PROJECT_URL;?>/?page=return_gstr3b_file&returnmonth=<?php echo $returnmonth;?>" class="btn btn-orange" style="width:100%;">Start Now</a>
                    </div>
                </div>
            </div>
        </div>
		 
   
	   <div class="col-md-12 col-sm-12 col-xs-12 whitebg gstr-box">
            <div class="lightyellow roundbtn"><span>GSTR-4</span>  | <span>Monthly Return Filing</span></div>
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
                                    <span class="txtinovice">File GSTR-4</span></div>
                                <div class="col-md-3 col-sm-3 col-xs-3"><span class="txtorange">Pending</span></div>
                                <div class="col-md-4 col-sm-4 txtorange col-xs-4"><i class="fa fa-clock-o" aria-hidden="true"></i> Due on 20 <?php echo $month;?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4" style="background:#fde7e0; margin-top:20px; border-radius:3px; padding:15px">
                        <div class="gstrrgtbox">File GSTR-4<br/><span>To work on GST Return 4</span></div>
                        <a href="<?php echo PROJECT_URL.'/?page=return_gstr3'; ?>" class="btn btn-orange" style="width:100%;">Start Now</a>
                    </div>
                </div>
            </div>
        </div>
		
	<?php
}else{?>
        <!--GSTR STEP START HERE--->
		<?php
	   $flag = $db_obj->getVendorName('registered');
	   $vendor_id1=0;
		if($flag!=0)
		{
			$vendor_id1 = $flag;
		}	
	 $sql="SELECT * FROM " . $db_obj->getTableName("returnfile_setting") . " as s inner join " . $db_obj->getTableName("return_cat") . " as c on c.id = s.cat_id
	 INNER join  " . $db_obj->getTableName("return_subcat") . " as subcat on subcat.id = s.subcat_id where c.id = 1";
	 $dataGstr1 = $db_obj->get_results($sql);
	 if(!empty($dataGstr1) && ($flag==$dataArr['data']->kyc->vendor_type))
	 {
      	 ?>
        <div class="col-md-12 col-sm-12 col-xs-12 whitebg gstr-box">
            <div class="lightyellow roundbtn"><span>GSTR-1</span>  | <span>Sales Return Filing</span></div>
            <div class="clearfix"></div>
            <div class="gstr-step-row">
                <div class="row">
                    <div class="col-md-8 col-sm-8 modpadlr">
			<?php 
			$i=0;
           foreach($dataGstr1 as $data)
		   { $i = $i+1; 
		   if(isset($_REQUEST["returnmonth"]) && $_REQUEST["returnmonth"]!='')
		   {
		   $sql="SELECT * FROM " . $db_obj->getTableName("returnfile_dates") . " where cat_id='".$data->cat_id."' and subcat_id='".$data->subcat_id."' and  is_deleted='0' and status='1' and return_month='".$_REQUEST["returnmonth"]."'";
		   }
		   else
		   {
			$month = date('m');   
			echo $sql="SELECT * FROM " . $db_obj->getTableName("returnfile_dates") . " where cat_id='".$data->cat_id."' and subcat_id='".$data->subcat_id."' and  is_deleted='0' and status='1' and return_month='".$month."'";
		   }
		   $res = $db_obj->get_results($sql);
		   
		   ?>		   
                        <div class="rowsteps">
						 <div class="row step-col actionable">

                                <div class="col-md-5 col-sm-5 col-xs-5"><span class="statusbullet"></span>
                                <span class="txtinovice"><?php echo $data->subcat_name; ?></span></div>
                                <div class="col-md-3 col-sm-3 col-xs-3"><?php 
								if($i==1)
								{
								$status = $sale_initiate_status;
								}
								else if($i==2)
								{
									$status = $sale_upload_status;
								}
								else if($i==3)
								{
									$status = $sale_file_status;
								}
								else if($i==4)
								{
									$status = '';
								}
								else{
									$status = '';
								}
								if($status==1) { ?> <span class="txtinovice"> <?php echo  "Completed"; ?> </span> <?php } else {  ?> <span class="txtorange"> <?php echo "pending"; ?> </span> <?php } ?></div>
								<?php if($status==1) { 
								?>
                                <div class="col-md-4 col-sm-4 txtinovice col-xs-4"><i class="fa fa-check" aria-hidden="true"></i> Completed</div>
								<?php } else {  ?>
								  <div class="col-md-4 col-sm-4 txtorange col-xs-4"><i class="fa fa-clock-o" aria-hidden="true"></i> Due on <?php echo $data->returnfile_date; ?> <?php echo $month;?></div>
								<?php } ?>
                            </div>
							</div>
		   <?php } ?>                    
                        
                    </div>
                    <div class="col-md-4 col-sm-4" style="background:#fde7e0;margin-top:20px;  border-radius:3px; padding:15px">
                        <div class="gstrrgtbox">File GSTR-1<br/><span>To work on GST Return 1 (Sales)</span></div>
                        <a href="<?php echo PROJECT_URL;?>/?page=return_summary&returnmonth=<?php echo $returnmonth;?>" class="btn btn-orange" style="width:100%;">Start Now</a>
                    </div>
                </div>
            </div>
        </div>
	 <?php } ?>
        <!--GSTR STEP END HERE--->      


        <!--GSTR STEP START HERE--->
		<?php
	 $sql="SELECT * FROM " . $db_obj->getTableName("returnfile_setting") . " as s inner join " . $db_obj->getTableName("return_cat") . " as c on c.id = s.cat_id
	 INNER join  " . $db_obj->getTableName("return_subcat") . " as subcat on subcat.id = s.subcat_id where c.id = 2";
	 $dataGstr2 = $db_obj->get_results($sql);
	 
	 if(!empty($dataGstr2) && ($flag!=0))
	 { ?>
        <div class="col-md-12 col-sm-12 col-xs-12 whitebg gstr-box">
            <div class="lightyellow roundbtn"><span>GSTR-2</span>  | <span>Purchase Return Filing</span></div>
            <div class="clearfix"></div>
            <div class="gstr-step-row">
                <div class="row">
                    <div class="col-md-8 col-sm-8 modpadlr">
					
			<?php $i=0; foreach($dataGstr2 as $data) {
				$i = $i+1;
					         if($i==1)
								{
								$status = $purchase_initiate_status;
								}
								else if($i==2)
								{
									$status = $purchase_download_status;
								}
								else if($i==3)
								{
									$status = '';
								}
								else if($i==4)
								{
									$status = '';
								}
								else if($i==5)
								{
									$status = $purchase_file_status;
								}
								else{
									$status = '';
								}
               ?>				
					 <div class="rowsteps">
						 <div class="row step-col actionable">

                                <div class="col-md-5 col-sm-5 col-xs-5"><span class="statusbullet"></span>
                                    <span class="txtinovice"><?php echo $data->subcat_name; ?></span></div>
                                <div class="col-md-3 col-sm-3 col-xs-3"><?php if($status==1) { ?> <span class="txtinovice"> <?php echo  "Completed"; ?> </span> <?php } else {  ?> <span class="txtorange"> <?php echo "pending"; ?> </span> <?php } ?></div>
								<?php if($status==1) { 
								?>
                                <div class="col-md-4 col-sm-4 txtinovice col-xs-4"><i class="fa fa-check" aria-hidden="true"></i> Completed</div>
								<?php } else {  ?>
								  <div class="col-md-4 col-sm-4 txtorange col-xs-4"><i class="fa fa-clock-o" aria-hidden="true"></i> Due on <?php echo $data->returnfile_date; ?> <?php echo $month;?></div>
								<?php } ?>
                            </div>
							</div>
                        
			<?php } ?>
                    </div>
                    <div class="col-md-4 col-sm-4" style="background:#fde7e0;margin-top:20px;  border-radius:3px; padding:15px">
                        <div class="gstrrgtbox">File GSTR-2<br/><span>To work on GST Return 2 (Purchases)</span></div>
                        <a href="<?php echo PROJECT_URL.'/?page=return_gstr2&returnmonth='.$returnmonth; ?>" class="btn btn-orange" style="width:100%;">Start Now</a>
                    </div>
                </div>
            </div>
        </div>
	 <?php } ?>
        <!--GSTR STEP END HERE--->      
        <!--GSTR STEP START HERE--->
				<?php
	$sql="SELECT * FROM " . $db_obj->getTableName("returnfile_setting") . " as s inner join " . $db_obj->getTableName("return_cat") . " as c on c.id = s.cat_id
	 INNER join  " . $db_obj->getTableName("return_subcat") . " as subcat on subcat.id = s.subcat_id where c.id = 3";
	 $dataGstr3 = $db_obj->get_results($sql);
	 
	 if(!empty($dataGstr3) && ($flag!=0))
	 { ?>
        <div class="col-md-12 col-sm-12 col-xs-12 whitebg gstr-box">
            <div class="lightyellow roundbtn"><span>GSTR-3</span>  | <span>Monthly Return Filing</span></div>
            <div class="clearfix"></div>
            <div class="gstr-step-row">
                <div class="row">
                    <div class="col-md-8 col-sm-8 modpadlr">
		<?php $i=0; foreach($dataGstr3 as $data) {
				$i = $i+1;
					         if($i==1)
								{
								$status = '';
								}
								else if($i==2)
								{
									$status = '';
								}
								else if($i==3)
								{
									$status = '';
								}
								else if($i==4)
								{
									$status = '';
								}
								else{
									$status = '';
								}
               ?>			
                        <div class="rowsteps">
                            <div class="row step-col actionable">
                                <div class="col-md-5 col-sm-5 col-xs-5"><span class="statusbullet"></span>
                                    <span class="txtinovice"><?php echo $data->subcat_name; ?></span></div>
                                <div class="col-md-3 col-sm-3 col-xs-3"><span class="txtorange">Pending</span></div>
                                <div class="col-md-4 col-sm-4 txtorange col-xs-4"><i class="fa fa-clock-o" aria-hidden="true"></i> Due on <?php echo $data->returnfile_date; ?> <?php echo $month;?></div>
                            </div>
                        </div>

		<?php } ?>     
                    </div>
                    <div class="col-md-4 col-sm-4" style="background:#fde7e0;margin-top:20px;  border-radius:3px; padding:15px">
                        <div class="gstrrgtbox">File GSTR-3<br/><span>To work on GST Return 3</span></div>
                        <a href="<?php echo PROJECT_URL.'/?page=return_gstr3'; ?>" class="btn btn-orange" style="width:100%;">Start Now</a>
                    </div>
                </div>
            </div>
        </div>
   
<?php } } ?>

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
					document.form2.action='<?php echo PROJECT_URL;?>/?page=return_client1&returnmonth=<?php echo $_REQUEST['returnmonth'];?>';
					<?php
				}else
				{
					?>
					document.form2.action='<?php echo PROJECT_URL;?>/?page=return_client1';
					<?php
				}
				?>
				document.form2.submit();
			});
        });

    </script>